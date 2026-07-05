<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Gaji;
use App\Models\Karyawan;
use App\Models\LaporanKeuangan;
use App\Models\Pengaturan;
use App\Models\User;
use App\Services\WhatsAppService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Vinkla\Hashids\Facades\Hashids;

class GajiController extends Controller
{
   /**
    * Admin: Display all gaji data
    */
   public function index(Request $request)
   {
      $query = Gaji::with(['karyawan.user', 'cabang', 'approver'])
         ->orderBy('tanggal', 'desc');

      // Filter by cabang
      if ($request->filled('cabang')) {
         $cabangId = Hashids::decode($request->cabang);
         if (!empty($cabangId)) {
            $query->where('cabang_id', $cabangId[0]);
         }
      }

      // Filter by karyawan
      if ($request->filled('karyawan')) {
         $karyawanId = Hashids::decode($request->karyawan);
         if (!empty($karyawanId)) {
            $query->where('karyawan_id', $karyawanId[0]);
         }
      }

      // Filter by status
      if ($request->filled('status')) {
         $query->where('status', $request->status);
      }

      // Filter by date range
      if ($request->filled('dari_tanggal')) {
         $query->whereDate('tanggal', '>=', $request->dari_tanggal);
      }
      if ($request->filled('sampai_tanggal')) {
         $query->whereDate('tanggal', '<=', $request->sampai_tanggal);
      }

      // Filter by month
      if ($request->filled('bulan')) {
         $bulan = Carbon::parse($request->bulan);
         $query->whereMonth('tanggal', $bulan->month)
            ->whereYear('tanggal', $bulan->year);
      }

      $gajis = $query->paginate(15)->withQueryString();

      // Get summary statistics
      $summary = [
         'total_gaji' => $query->clone()->sum('nominal_gaji'),
         'total_pending' => $query->clone()->where('status', 'pending')->sum('nominal_gaji'),
         'total_paid' => $query->clone()->where('status', 'paid')->sum('nominal_gaji'),
         'count_records' => $query->clone()->count(),
      ];

      // Get filter options
      $cabangs = Cabang::where('is_active', true)->get();
      $karyawans = Karyawan::with('user')->get();

      return view('pages.gaji.admin.index', compact('gajis', 'summary', 'cabangs', 'karyawans'));
   }

   /**
    * Admin: Generate/Calculate gaji for a specific date
    */
   public function generate(Request $request)
   {
      $request->validate([
         'tanggal' => 'required|date',
         'cabang_id' => 'nullable|string',
      ]);

      $tanggal = Carbon::parse($request->tanggal);
      $persenGaji = Pengaturan::getValue('persen_gaji', 13);

      // Get cabang filter if provided
      $cabangFilter = null;
      if ($request->filled('cabang_id')) {
         $decoded = Hashids::decode($request->cabang_id);
         if (!empty($decoded)) {
            $cabangFilter = $decoded[0];
         }
      }

      // Get all approved Pendapatan for that date, grouped by cabang_id
      $query = LaporanKeuangan::where('jenis', 'Pendapatan')
         ->where('status', 'Approved')
         ->whereDate('tanggal', $tanggal);

      if ($cabangFilter) {
         $query->where('cabang_id', $cabangFilter);
      }

      $pemasukanByCabang = $query->select(
         'cabang_id',
         DB::raw('SUM(jumlah) as total_pemasukan'),
         DB::raw('COUNT(*) as jumlah_transaksi')
      )
         ->groupBy('cabang_id')
         ->get();

      $created = 0;
      $updated = 0;
      $skipped = 0;

      foreach ($pemasukanByCabang as $data) {
         if (!$data->cabang_id) {
            $skipped++;
            continue;
         }

         // Get all active employees in this cabang
         $karyawans = Karyawan::where('cabang_id', $data->cabang_id)
            ->where('is_active', true)
            ->get();

         if ($karyawans->isEmpty()) {
            $skipped++;
            continue;
         }

         foreach ($karyawans as $karyawan) {
            $nominalGaji = Gaji::calculateGaji($data->total_pemasukan, $persenGaji);

            $gaji = Gaji::updateOrCreate(
               [
                  'karyawan_id' => $karyawan->id,
                  'tanggal' => $tanggal->toDateString(),
               ],
               [
                  'cabang_id' => $data->cabang_id,
                  'total_pemasukan' => $data->total_pemasukan,
                  'persen_gaji' => $persenGaji,
                  'nominal_gaji' => $nominalGaji,
                  'jumlah_transaksi' => $data->jumlah_transaksi,
                  'status' => 'pending',
               ]
            );

            if ($gaji->wasRecentlyCreated) {
               $created++;
            } else {
               $updated++;
            }
         }
      }

      $message = "Gaji berhasil digenerate untuk tanggal {$tanggal->format('d M Y')}. ";
      $message .= "Dibuat: {$created}, Diupdate: {$updated}";
      if ($skipped > 0) {
         $message .= ", Dilewati: {$skipped}";
      }

      return redirect()->route('gaji.index')
         ->with('success', $message);
   }

   /**
    * Admin: Mark gaji as paid
    */
   public function markAsPaid(Request $request, $id)
   {
      $decoded = Hashids::decode($id);
      if (empty($decoded)) {
         abort(404);
      }

      $gaji = Gaji::with(['karyawan', 'cabang'])->findOrFail($decoded[0]);
      $gaji->update([
         'status' => 'paid',
         'approved_by' => Auth::id(),
         'paid_at' => now(),
      ]);

      // Send WhatsApp notification
      $waResult = $this->sendWhatsAppNotification($gaji);

      $message = 'Gaji telah ditandai sebagai dibayar';
      if ($waResult['success']) {
         $message .= ' dan notifikasi WhatsApp telah dikirim ke karyawan.';
      } elseif (!empty(config('services.fonnte.token'))) {
         $message .= '. Namun notifikasi WhatsApp gagal dikirim: ' . ($waResult['message'] ?? 'Unknown error');
      }

      return redirect()->back()->with('success', $message);
   }

   /**
    * Admin: Batch mark as paid
    */
   public function batchMarkAsPaid(Request $request)
   {
      $request->validate([
         'gaji_ids' => 'required|array',
         'gaji_ids.*' => 'string',
      ]);

      $count = 0;
      $waSuccess = 0;
      $waFailed = 0;
      $paidGajis = [];

      foreach ($request->gaji_ids as $hashId) {
         $decoded = Hashids::decode($hashId);
         if (!empty($decoded)) {
            $gaji = Gaji::with(['karyawan', 'cabang'])->find($decoded[0]);
            if ($gaji && $gaji->status === 'pending') {
               $gaji->update([
                  'status' => 'paid',
                  'approved_by' => Auth::id(),
                  'paid_at' => now(),
               ]);
               $count++;
               $paidGajis[] = $gaji;
            }
         }
      }

      // Send WhatsApp notifications for all paid gajis
      if (!empty($paidGajis) && !empty(config('services.fonnte.token'))) {
         foreach ($paidGajis as $gaji) {
            $result = $this->sendWhatsAppNotification($gaji);
            if ($result['success']) {
               $waSuccess++;
            } else {
               $waFailed++;
            }
            // Small delay to avoid rate limiting
            usleep(500000); // 0.5 second
         }
      }

      $message = "{$count} gaji telah ditandai sebagai dibayar";
      if ($waSuccess > 0 || $waFailed > 0) {
         $message .= ". Notifikasi WhatsApp: {$waSuccess} berhasil, {$waFailed} gagal.";
      }

      return redirect()->back()->with('success', $message);
   }

   /**
    * Send WhatsApp notification for gaji payment
    */
   private function sendWhatsAppNotification(Gaji $gaji): array
   {
      $whatsApp = new WhatsAppService();
      return $whatsApp->sendSalaryNotification($gaji);
   }

   /**
    * Admin: Show detail gaji
    */
   public function show($id)
   {
      $decoded = Hashids::decode($id);
      if (empty($decoded)) {
         abort(404);
      }

      $gaji = Gaji::with(['karyawan.user', 'cabang', 'approver'])->findOrFail($decoded[0]);

      // Get related laporan keuangan for that day based on branch
      $laporans = LaporanKeuangan::where('cabang_id', $gaji->cabang_id)
         ->where('jenis', 'Pendapatan')
         ->where('status', 'Approved')
         ->whereDate('tanggal', $gaji->tanggal)
         ->get();

      return view('pages.gaji.admin.show', compact('gaji', 'laporans'));
   }

   /**
    * Admin: Export slip gaji PDF
    */
   public function exportSlipPdf($id)
   {
      $decoded = Hashids::decode($id);
      if (empty($decoded)) {
         abort(404);
      }

      $gaji = Gaji::with(['karyawan.user', 'cabang', 'approver'])->findOrFail($decoded[0]);

      // Get related laporan keuangan based on branch
      $laporans = LaporanKeuangan::where('cabang_id', $gaji->cabang_id)
         ->where('jenis', 'Pendapatan')
         ->where('status', 'Approved')
         ->whereDate('tanggal', $gaji->tanggal)
         ->get();

      // Get owner/admin user for signature (get first admin with signature)
      $owner = User::where('is_admin', true)
         ->whereNotNull('ttd')
         ->first();

      $pdf = Pdf::loadView('pages.gaji.slip-pdf', compact('gaji', 'laporans', 'owner'));
      $pdf->setPaper('A5', 'portrait');

      $filename = 'slip-gaji-' . ($gaji->karyawan->nama_lengkap ?? 'karyawan') . '-' . $gaji->tanggal->format('Y-m-d') . '.pdf';

      return $pdf->download($filename);
   }

   /**
    * Admin: Export rekap gaji per hari PDF
    */
   public function exportRekapPdf(Request $request)
   {
      $tanggal = $request->filled('tanggal') ? Carbon::parse($request->tanggal) : now();

      $query = Gaji::with(['karyawan.user', 'cabang'])
         ->whereDate('tanggal', $tanggal)
         ->orderBy('cabang_id');

      // Filter by cabang
      if ($request->filled('cabang')) {
         $decoded = Hashids::decode($request->cabang);
         if (!empty($decoded)) {
            $query->where('cabang_id', $decoded[0]);
         }
      }

      $gajis = $query->get();
      $persen = Pengaturan::getValue('persen_gaji', 13);

      // Get owner/admin user for signature (get first admin with signature)
      $owner = User::where('is_admin', true)
         ->whereNotNull('ttd')
         ->first();

      $pdf = Pdf::loadView('pages.gaji.rekap-pdf', compact('gajis', 'tanggal', 'persen', 'owner'));
      $pdf->setPaper('A4', 'landscape');

      $filename = 'rekap-gaji-' . $tanggal->format('Y-m-d') . '.pdf';

      return $pdf->download($filename);
   }

   /**
    * Karyawan: View own gaji history
    */
   public function myGaji(Request $request)
   {
      $user = Auth::user();
      $karyawan = $user->karyawan;

      if (!$karyawan) {
         abort(403, 'Anda tidak terdaftar sebagai karyawan');
      }

      $query = Gaji::with(['cabang', 'approver'])
         ->where('karyawan_id', $karyawan->id)
         ->orderBy('tanggal', 'desc');

      // Filter by status
      if ($request->filled('status')) {
         $query->where('status', $request->status);
      }

      // Filter by month and year (separate fields)
      if ($request->filled('bulan')) {
         $query->whereMonth('tanggal', $request->bulan);
      }
      if ($request->filled('tahun')) {
         $query->whereYear('tanggal', $request->tahun);
      }

      // Filter by date range
      if ($request->filled('dari_tanggal')) {
         $query->whereDate('tanggal', '>=', $request->dari_tanggal);
      }
      if ($request->filled('sampai_tanggal')) {
         $query->whereDate('tanggal', '<=', $request->sampai_tanggal);
      }

      $gajis = $query->paginate(15)->withQueryString();

      // Get summary
      $summary = [
         'total_gaji_bulan_ini' => Gaji::where('karyawan_id', $karyawan->id)
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->sum('nominal_gaji'),
         'total_gaji_dibayar' => Gaji::where('karyawan_id', $karyawan->id)
            ->where('status', 'paid')
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->sum('nominal_gaji'),
         'total_gaji_pending' => Gaji::where('karyawan_id', $karyawan->id)
            ->where('status', 'pending')
            ->sum('nominal_gaji'),
      ];

      return view('pages.gaji.karyawan.index', compact('gajis', 'summary', 'karyawan'));
   }

   /**
    * Karyawan: View own gaji detail
    */
   public function myGajiDetail($id)
   {
      $user = Auth::user();
      $karyawan = $user->karyawan;

      if (!$karyawan) {
         abort(403, 'Anda tidak terdaftar sebagai karyawan');
      }

      $decoded = Hashids::decode($id);
      if (empty($decoded)) {
         abort(404);
      }

      $gaji = Gaji::with(['cabang', 'approver'])
         ->where('karyawan_id', $karyawan->id)
         ->findOrFail($decoded[0]);

      // Get related laporan keuangan based on branch
      $laporans = LaporanKeuangan::where('cabang_id', $gaji->cabang_id)
         ->where('jenis', 'Pendapatan')
         ->where('status', 'Approved')
         ->whereDate('tanggal', $gaji->tanggal)
         ->get();

      return view('pages.gaji.karyawan.show', compact('gaji', 'laporans', 'karyawan'));
   }

   /**
    * Karyawan: Download own slip gaji
    */
   public function mySlipPdf($id)
   {
      $user = Auth::user();
      $karyawan = $user->karyawan;

      if (!$karyawan) {
         abort(403, 'Anda tidak terdaftar sebagai karyawan');
      }

      $decoded = Hashids::decode($id);
      if (empty($decoded)) {
         abort(404);
      }

      $gaji = Gaji::with(['karyawan.user', 'cabang', 'approver'])
         ->where('karyawan_id', $karyawan->id)
         ->findOrFail($decoded[0]);

      // Get related laporan keuangan based on branch
      $laporans = LaporanKeuangan::where('cabang_id', $gaji->cabang_id)
         ->where('jenis', 'Pendapatan')
         ->where('status', 'Approved')
         ->whereDate('tanggal', $gaji->tanggal)
         ->get();

      // Get owner/admin user for signature (get first admin with signature)
      $owner = User::where('is_admin', true)
         ->whereNotNull('ttd')
         ->first();

      $pdf = Pdf::loadView('pages.gaji.slip-pdf', compact('gaji', 'laporans', 'owner'));
      $pdf->setPaper('A5', 'portrait');

      $filename = 'slip-gaji-' . $gaji->tanggal->format('Y-m-d') . '.pdf';

      return $pdf->download($filename);
   }
}

