<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Karyawan;
use App\Models\LaporanKeuangan;
use App\Models\Notification;
use App\Models\Informasi;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class KaryawanLaporanController extends Controller
{
   /**
    * Get karyawan data for current user
    */
   private function getKaryawanForUser()
   {
      $user = Auth::user();
      return Karyawan::where('user_id', $user->id)->first();
   }

   /**
    * List pemasukan dari karyawan sendiri
    */
   public function indexPemasukan(Request $request): View
   {
      $karyawan = $this->getKaryawanForUser();

      if (!$karyawan) {
         abort(403, 'Akun Anda tidak terhubung dengan data karyawan. Hubungi administrator.');
      }

      $query = LaporanKeuangan::with(['cabang'])
         ->where('karyawan_id', $karyawan->id)
         ->where('jenis', LaporanKeuangan::JENIS_PEMASUKAN);

      // Filter tanggal
      if ($request->filled('tanggal_dari')) {
         $query->whereDate('tanggal', '>=', $request->tanggal_dari);
      }
      if ($request->filled('tanggal_sampai')) {
         $query->whereDate('tanggal', '<=', $request->tanggal_sampai);
      }

      // Filter status
      if ($request->filled('status')) {
         $query->where('status', $request->status);
      }

      // Filter kategori
      if ($request->filled('kategori')) {
         $query->where('kategori', $request->kategori);
      }

      $laporans = $query->orderBy('tanggal', 'desc')->paginate(15);

      // Summary
      $summaryQuery = LaporanKeuangan::where('karyawan_id', $karyawan->id)
         ->where('jenis', LaporanKeuangan::JENIS_PEMASUKAN);

      $summary = [
         'total' => $summaryQuery->count(),
         'approved' => (clone $summaryQuery)->where('status', LaporanKeuangan::STATUS_APPROVED)->count(),
         'pending' => (clone $summaryQuery)->where('status', LaporanKeuangan::STATUS_PENDING)->count(),
         'draft' => (clone $summaryQuery)->where('status', LaporanKeuangan::STATUS_DRAFT)->count(),
         'total_nilai' => (clone $summaryQuery)->where('status', LaporanKeuangan::STATUS_APPROVED)->sum('jumlah'),
      ];

      $kategoriList = LaporanKeuangan::KATEGORI_PEMASUKAN;

      return view('pages.karyawan.pemasukan.index', compact('laporans', 'karyawan', 'summary', 'kategoriList'));
   }

   /**
    * Show form untuk input pemasukan (khusus karyawan)
    */
   public function createPemasukan(): View
   {
      $karyawan = $this->getKaryawanForUser();

      if (!$karyawan) {
         abort(403, 'Akun Anda tidak terhubung dengan data karyawan. Hubungi administrator.');
      }

      $cabang = $karyawan->cabang;
      $jenis = LaporanKeuangan::JENIS_PEMASUKAN;
      $kategoriList = LaporanKeuangan::KATEGORI_PEMASUKAN;

      return view('pages.karyawan.pemasukan.create', compact('karyawan', 'cabang', 'jenis', 'kategoriList'));
   }

   /**
    * Store pemasukan dari karyawan
    */
   public function storePemasukan(Request $request): JsonResponse
   {
      $karyawan = $this->getKaryawanForUser();

      if (!$karyawan) {
         return response()->json([
            'success' => false,
            'message' => 'Akun Anda tidak terhubung dengan data karyawan.'
         ], 403);
      }

      $validated = $request->validate([
         'tanggal' => 'required|date',
         'kategori' => 'required|string|max:100',
         'keterangan' => 'required|string|max:500',
         'jumlah' => 'required|numeric|min:0',
         'bukti_transaksi' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
         'catatan' => 'nullable|string',
         'submit_type' => 'nullable|string|in:draft,pending',
      ], [
         'tanggal.required' => 'Tanggal wajib diisi',
         'kategori.required' => 'Kategori wajib dipilih',
         'keterangan.required' => 'Keterangan wajib diisi',
         'jumlah.required' => 'Jumlah wajib diisi',
         'jumlah.min' => 'Jumlah tidak boleh negatif',
      ]);

      try {
         // Handle file upload
         $buktiPath = null;
         if ($request->hasFile('bukti_transaksi')) {
            $buktiPath = $request->file('bukti_transaksi')->store('bukti-transaksi', 'public');
         }

         // Determine status based on submit_type
         $submitType = $request->input('submit_type', 'pending');
         $status = $submitType === 'draft'
            ? LaporanKeuangan::STATUS_DRAFT
            : LaporanKeuangan::STATUS_PENDING;

         $laporan = LaporanKeuangan::create([
            'cabang_id' => $karyawan->cabang_id,
            'karyawan_id' => $karyawan->id,
            'created_by' => Auth::id(),
            'tanggal' => $validated['tanggal'],
            'jenis' => LaporanKeuangan::JENIS_PEMASUKAN,
            'kategori' => $validated['kategori'],
            'keterangan' => $validated['keterangan'],
            'jumlah' => $validated['jumlah'],
            'bukti_transaksi' => $buktiPath,
            'catatan' => $validated['catatan'] ?? null,
            'status' => $status,
         ]);

         // Kirim notifikasi ke admin hanya jika status Pending
         if ($status === LaporanKeuangan::STATUS_PENDING) {
            Notification::notifyAdminsNewLaporan($laporan);
            $message = 'Laporan pemasukan berhasil diajukan dan menunggu approval admin.';
         } else {
            $message = 'Laporan pemasukan berhasil disimpan sebagai draft.';
         }

         return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $laporan
         ]);
      } catch (\Exception $e) {
         return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
         ], 500);
      }
   }

   /**
    * Laporan Harian untuk karyawan (hanya data miliknya)
    */
   public function laporanHarian(Request $request): View
   {
      $karyawan = $this->getKaryawanForUser();
      $tanggal = $request->get('tanggal', Carbon::today()->format('Y-m-d'));

      $query = LaporanKeuangan::with(['cabang'])
         ->whereDate('tanggal', $tanggal);

      // Karyawan hanya lihat data miliknya
      if ($karyawan) {
         $query->where('karyawan_id', $karyawan->id);
      } else {
         // Jika tidak ada karyawan, tampilkan yang dibuat user ini
         $query->where('created_by', Auth::id());
      }

      $laporans = $query->orderBy('created_at', 'desc')->get();

      $summary = [
         'total_pemasukan' => $laporans->where('jenis', 'Pemasukan')->sum('jumlah'),
         'total_pengeluaran' => $laporans->where('jenis', 'Pengeluaran')->sum('jumlah'),
      ];
      $summary['saldo'] = $summary['total_pemasukan'] - $summary['total_pengeluaran'];

      return view('pages.karyawan.laporan.harian', compact('laporans', 'tanggal', 'summary', 'karyawan'));
   }

   /**
    * Laporan Mingguan untuk karyawan
    */
   public function laporanMingguan(Request $request): View
   {
      $karyawan = $this->getKaryawanForUser();

      $mingguKe = $request->get('minggu', Carbon::now()->weekOfMonth);
      $bulan = $request->get('bulan', Carbon::now()->month);
      $tahun = $request->get('tahun', Carbon::now()->year);

      $startOfWeek = Carbon::create($tahun, $bulan, 1)->startOfMonth();
      $startOfWeek->addWeeks($mingguKe - 1)->startOfWeek();
      $endOfWeek = $startOfWeek->copy()->endOfWeek();

      $query = LaporanKeuangan::with(['cabang'])
         ->whereBetween('tanggal', [$startOfWeek, $endOfWeek]);

      if ($karyawan) {
         $query->where('karyawan_id', $karyawan->id);
      } else {
         $query->where('created_by', Auth::id());
      }

      $laporans = $query->orderBy('tanggal', 'desc')->get();

      // Group by tanggal
      $groupedLaporans = $laporans->groupBy(function ($item) {
         return $item->tanggal->format('Y-m-d');
      });

      $summary = [
         'total_pemasukan' => $laporans->where('jenis', 'Pemasukan')->sum('jumlah'),
         'total_pengeluaran' => $laporans->where('jenis', 'Pengeluaran')->sum('jumlah'),
      ];
      $summary['saldo'] = $summary['total_pemasukan'] - $summary['total_pengeluaran'];

      return view('pages.karyawan.laporan.mingguan', compact(
         'laporans',
         'groupedLaporans',
         'summary',
         'karyawan',
         'mingguKe',
         'bulan',
         'tahun',
         'startOfWeek',
         'endOfWeek'
      ));
   }

   /**
    * Laporan Bulanan untuk karyawan
    */
   public function laporanBulanan(Request $request): View
   {
      $karyawan = $this->getKaryawanForUser();

      $bulan = $request->get('bulan', Carbon::now()->month);
      $tahun = $request->get('tahun', Carbon::now()->year);

      $startOfMonth = Carbon::create($tahun, $bulan, 1)->startOfMonth();
      $endOfMonth = $startOfMonth->copy()->endOfMonth();

      $query = LaporanKeuangan::with(['cabang'])
         ->whereBetween('tanggal', [$startOfMonth, $endOfMonth]);

      if ($karyawan) {
         $query->where('karyawan_id', $karyawan->id);
      } else {
         $query->where('created_by', Auth::id());
      }

      $laporans = $query->orderBy('tanggal', 'desc')->get();

      // Weekly stats
      $weeklyStats = collect();
      for ($week = 1; $week <= 5; $week++) {
         $weekStart = Carbon::create($tahun, $bulan, 1)->startOfMonth()->addWeeks($week - 1)->startOfWeek();
         $weekEnd = $weekStart->copy()->endOfWeek();

         // Pastikan masih dalam bulan yang sama
         if ($weekStart->month != $bulan && $weekEnd->month != $bulan) continue;

         $weekLaporans = $laporans->filter(function ($item) use ($weekStart, $weekEnd) {
            return $item->tanggal->between($weekStart, $weekEnd);
         });

         if ($weekLaporans->isNotEmpty()) {
            $weeklyStats->push([
               'minggu' => $week,
               'pemasukan' => $weekLaporans->where('jenis', 'Pemasukan')->sum('jumlah'),
               'pengeluaran' => $weekLaporans->where('jenis', 'Pengeluaran')->sum('jumlah'),
               'saldo' => $weekLaporans->where('jenis', 'Pemasukan')->sum('jumlah') - $weekLaporans->where('jenis', 'Pengeluaran')->sum('jumlah'),
               'transaksi' => $weekLaporans->count(),
            ]);
         }
      }

      $summary = [
         'total_pemasukan' => $laporans->where('jenis', 'Pemasukan')->sum('jumlah'),
         'total_pengeluaran' => $laporans->where('jenis', 'Pengeluaran')->sum('jumlah'),
         'jumlah_transaksi' => $laporans->count(),
      ];
      $summary['saldo'] = $summary['total_pemasukan'] - $summary['total_pengeluaran'];

      return view('pages.karyawan.laporan.bulanan', compact(
         'laporans',
         'weeklyStats',
         'summary',
         'karyawan',
         'bulan',
         'tahun'
      ));
   }

   /**
    * Riwayat laporan karyawan
    */
   public function riwayat(Request $request): View
   {
      $karyawan = $this->getKaryawanForUser();

      // Base query for status counts
      $baseQuery = LaporanKeuangan::query();
      if ($karyawan) {
         $baseQuery->where('karyawan_id', $karyawan->id);
      } else {
         $baseQuery->where('created_by', Auth::id());
      }

      // Get status counts
      $statusCounts = $baseQuery->get()->groupBy('status')->map->count();

      // Build query for list
      $query = LaporanKeuangan::with(['cabang']);

      if ($karyawan) {
         $query->where('karyawan_id', $karyawan->id);
      } else {
         $query->where('created_by', Auth::id());
      }

      // Filter by status
      $status = $request->get('status');
      if ($status) {
         $query->where('status', $status);
      }

      // Filter by date range
      $dari = $request->get('dari');
      $sampai = $request->get('sampai');

      if ($dari) {
         $query->whereDate('tanggal', '>=', $dari);
      }
      if ($sampai) {
         $query->whereDate('tanggal', '<=', $sampai);
      }

      $laporans = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

      return view('pages.karyawan.laporan.riwayat', compact('laporans', 'karyawan', 'statusCounts', 'status', 'dari', 'sampai'));
   }

   /**
    * Edit laporan draft
    */
   public function editLaporan(string $laporan): View
   {
      $karyawan = $this->getKaryawanForUser();
      $hashId = $laporan;

      // Decode hash ID
      $decoded = \Vinkla\Hashids\Facades\Hashids::decode($hashId);
      if (empty($decoded)) {
         abort(404, 'Laporan tidak ditemukan');
      }

      $laporan = LaporanKeuangan::with(['cabang', 'karyawan'])
         ->where('id', $decoded[0])
         ->where('karyawan_id', $karyawan?->id)
         ->where('status', LaporanKeuangan::STATUS_DRAFT)
         ->firstOrFail();

      $cabang = $karyawan->cabang;
      $jenis = $laporan->jenis;
      $kategoriList = $jenis === LaporanKeuangan::JENIS_PEMASUKAN
         ? LaporanKeuangan::KATEGORI_PEMASUKAN
         : LaporanKeuangan::KATEGORI_PENGELUARAN;

      return view('pages.karyawan.laporan.edit', compact('laporan', 'karyawan', 'cabang', 'jenis', 'kategoriList'));
   }

   /**
    * Update laporan draft
    */
   public function updateLaporan(Request $request, string $laporan): JsonResponse
   {
      $karyawan = $this->getKaryawanForUser();
      $hashId = $laporan;

      // Decode hash ID
      $decoded = \Vinkla\Hashids\Facades\Hashids::decode($hashId);
      if (empty($decoded)) {
         return response()->json(['success' => false, 'message' => 'Laporan tidak ditemukan'], 404);
      }

      $laporan = LaporanKeuangan::where('id', $decoded[0])
         ->where('karyawan_id', $karyawan?->id)
         ->where('status', LaporanKeuangan::STATUS_DRAFT)
         ->first();

      if (!$laporan) {
         return response()->json(['success' => false, 'message' => 'Laporan tidak ditemukan atau tidak dapat diedit'], 404);
      }

      $validated = $request->validate([
         'tanggal' => 'required|date',
         'kategori' => 'required|string|max:100',
         'keterangan' => 'required|string|max:500',
         'jumlah' => 'required|numeric|min:0',
         'bukti_transaksi' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
         'catatan' => 'nullable|string',
         'submit_type' => 'nullable|string|in:draft,pending',
      ]);

      try {
         // Handle file upload
         if ($request->hasFile('bukti_transaksi')) {
            // Delete old file if exists
            if ($laporan->bukti_transaksi) {
               Storage::disk('public')->delete($laporan->bukti_transaksi);
            }
            $laporan->bukti_transaksi = $request->file('bukti_transaksi')->store('bukti-transaksi', 'public');
         }

         // Determine status based on submit_type
         $submitType = $request->input('submit_type', 'draft');
         $status = $submitType === 'draft'
            ? LaporanKeuangan::STATUS_DRAFT
            : LaporanKeuangan::STATUS_PENDING;

         $laporan->update([
            'tanggal' => $validated['tanggal'],
            'kategori' => $validated['kategori'],
            'keterangan' => $validated['keterangan'],
            'jumlah' => $validated['jumlah'],
            'catatan' => $validated['catatan'] ?? null,
            'status' => $status,
         ]);

         // Kirim notifikasi ke admin jika diajukan
         if ($status === LaporanKeuangan::STATUS_PENDING) {
            Notification::notifyAdminsNewLaporan($laporan);
            $message = 'Laporan berhasil diajukan untuk approval.';
         } else {
            $message = 'Laporan berhasil diperbarui.';
         }

         return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $laporan
         ]);
      } catch (\Exception $e) {
         return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
         ], 500);
      }
   }

   /**
    * Submit draft for approval
    */
   public function submitForApproval(string $laporan): JsonResponse
   {
      $karyawan = $this->getKaryawanForUser();
      $hashId = $laporan;

      // Decode hash ID
      $decoded = \Vinkla\Hashids\Facades\Hashids::decode($hashId);
      if (empty($decoded)) {
         return response()->json(['success' => false, 'message' => 'Laporan tidak ditemukan'], 404);
      }

      $laporan = LaporanKeuangan::where('id', $decoded[0])
         ->where('karyawan_id', $karyawan?->id)
         ->where('status', LaporanKeuangan::STATUS_DRAFT)
         ->first();

      if (!$laporan) {
         return response()->json(['success' => false, 'message' => 'Laporan tidak ditemukan atau tidak dalam status draft'], 404);
      }

      try {
         $laporan->update(['status' => LaporanKeuangan::STATUS_PENDING]);

         // Kirim notifikasi ke admin
         Notification::notifyAdminsNewLaporan($laporan);

         return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil diajukan untuk approval.'
         ]);
      } catch (\Exception $e) {
         return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
         ], 500);
      }
   }

   /**
    * Delete draft laporan
    */
   public function deleteLaporan(string $laporan): JsonResponse
   {
      $karyawan = $this->getKaryawanForUser();
      $hashId = $laporan;

      // Decode hash ID
      $decoded = \Vinkla\Hashids\Facades\Hashids::decode($hashId);
      if (empty($decoded)) {
         return response()->json(['success' => false, 'message' => 'Laporan tidak ditemukan'], 404);
      }

      $laporan = LaporanKeuangan::where('id', $decoded[0])
         ->where('karyawan_id', $karyawan?->id)
         ->where('status', LaporanKeuangan::STATUS_DRAFT)
         ->first();

      if (!$laporan) {
         return response()->json(['success' => false, 'message' => 'Laporan tidak ditemukan atau tidak dapat dihapus'], 404);
      }

      try {
         // Delete bukti transaksi if exists
         if ($laporan->bukti_transaksi) {
            Storage::disk('public')->delete($laporan->bukti_transaksi);
         }

         $laporan->delete();

         return response()->json([
            'success' => true,
            'message' => 'Draft laporan berhasil dihapus.'
         ]);
      } catch (\Exception $e) {
         return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
         ], 500);
      }
   }

   /**
    * List informasi manajemen untuk karyawan
    */
   public function indexInformasi(Request $request): View
   {
      $informasiList = Informasi::orderBy('created_at', 'desc')
         ->paginate(10);

      return view('pages.karyawan.informasi.index', compact('informasiList'));
   }

   /**
    * Show detail informasi untuk karyawan
    */
   public function showInformasi(string $id): View
   {
      // Decode hash ID
      $decoded = \Vinkla\Hashids\Facades\Hashids::decode($id);
      if (empty($decoded)) {
         abort(404, 'Informasi tidak ditemukan');
      }

      $informasi = Informasi::findOrFail($decoded[0]);

      return view('pages.karyawan.informasi.show', compact('informasi'));
   }
}
