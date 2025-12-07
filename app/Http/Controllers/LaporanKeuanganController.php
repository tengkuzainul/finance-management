<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Karyawan;
use App\Models\LaporanKeuangan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Vinkla\Hashids\Facades\Hashids;
use Carbon\Carbon;

class LaporanKeuanganController extends Controller
{
   /**
    * Display a listing of the resource.
    */
   public function index(Request $request): View|JsonResponse
   {
      $query = LaporanKeuangan::with(['cabang', 'karyawan', 'creator', 'approver']);

      // Search
      if ($request->filled('search')) {
         $search = $request->search;
         $query->where(function ($q) use ($search) {
            $q->where('keterangan', 'like', "%{$search}%")
               ->orWhere('kategori', 'like', "%{$search}%")
               ->orWhere('catatan', 'like', "%{$search}%");
         });
      }

      // Filter by cabang (decode hash if needed)
      if ($request->filled('cabang_id')) {
         $cabangId = $request->cabang_id;
         // Try to decode as hashid
         $decoded = Hashids::decode($cabangId);
         if (!empty($decoded)) {
            $cabangId = $decoded[0];
         }
         $query->where('cabang_id', $cabangId);
      }

      // Filter by jenis (decode if encrypted)
      if ($request->filled('jenis')) {
         $jenis = $request->jenis;

         // Check if jenis is encrypted (hashids)
         if (!in_array($jenis, ['Pemasukan', 'Pengeluaran'])) {
            $decoded = Hashids::decode($jenis);
            if (!empty($decoded)) {
               $jenis = $decoded[0] == 1 ? 'Pemasukan' : 'Pengeluaran';
            }
         }

         $query->where('jenis', $jenis);
      }

      // Filter by status
      if ($request->filled('status')) {
         $query->where('status', $request->status);
      }

      // Filter by karyawan (decode hash if needed)
      if ($request->filled('karyawan_id')) {
         $karyawanId = $request->karyawan_id;
         // Try to decode as hashid
         $decoded = Hashids::decode($karyawanId);
         if (!empty($decoded)) {
            $karyawanId = $decoded[0];
         }
         $query->where('karyawan_id', $karyawanId);
      }

      // Filter by tanggal
      if ($request->filled('tanggal_mulai') && $request->filled('tanggal_akhir')) {
         $query->whereBetween('tanggal', [$request->tanggal_mulai, $request->tanggal_akhir]);
      } elseif ($request->filled('tanggal_mulai')) {
         $query->where('tanggal', '>=', $request->tanggal_mulai);
      } elseif ($request->filled('tanggal_akhir')) {
         $query->where('tanggal', '<=', $request->tanggal_akhir);
      }

      // Sorting
      $sortBy = $request->get('sort_by', 'tanggal');
      $sortOrder = $request->get('sort_order', 'desc');
      $query->orderBy($sortBy, $sortOrder);

      $laporans = $query->paginate(15)->withQueryString();
      $cabangs = Cabang::active()->orderBy('nama_cabang')->get();

      // Summary statistics
      $summary = [
         'total_pemasukan' => LaporanKeuangan::pemasukan()->approved()->sum('jumlah'),
         'total_pengeluaran' => LaporanKeuangan::pengeluaran()->approved()->sum('jumlah'),
         'pending_count' => LaporanKeuangan::pending()->count(),
      ];
      $summary['saldo'] = $summary['total_pemasukan'] - $summary['total_pengeluaran'];

      if ($request->ajax()) {
         return response()->json([
            'success' => true,
            'data' => $laporans,
            'summary' => $summary
         ]);
      }

      return view('pages.master-data.laporan-keuangan.index', compact('laporans', 'cabangs', 'summary'));
   }

   /**
    * Show the form for creating a new resource.
    */
   public function create(Request $request): View
   {
      $cabangs = Cabang::active()->orderBy('nama_cabang')->get();
      $karyawans = Karyawan::active()->orderBy('nama_lengkap')->get();

      // Decode jenis from hash
      $jenis = LaporanKeuangan::JENIS_PEMASUKAN; // default
      if ($request->filled('jenis')) {
         $jenisMap = [
            'pemasukan' => LaporanKeuangan::JENIS_PEMASUKAN,
            'pengeluaran' => LaporanKeuangan::JENIS_PENGELUARAN,
         ];
         $decoded = Hashids::decode($request->jenis);
         if (!empty($decoded)) {
            $jenisKey = $decoded[0] == 1 ? 'pemasukan' : 'pengeluaran';
            $jenis = $jenisMap[$jenisKey] ?? LaporanKeuangan::JENIS_PEMASUKAN;
         }
      }

      $kategoriList = $jenis === LaporanKeuangan::JENIS_PEMASUKAN
         ? LaporanKeuangan::KATEGORI_PEMASUKAN
         : LaporanKeuangan::KATEGORI_PENGELUARAN;

      // Decode cabang_id from hash if provided
      $selectedCabangId = null;
      if ($request->filled('cabang_id')) {
         $decoded = Hashids::decode($request->cabang_id);
         $selectedCabangId = !empty($decoded) ? $decoded[0] : null;
      }

      return view('pages.master-data.laporan-keuangan.create', compact('cabangs', 'karyawans', 'jenis', 'kategoriList', 'selectedCabangId'));
   }

   /**
    * Store a newly created resource in storage.
    */
   public function store(Request $request): JsonResponse
   {
      // Decode hash IDs before validation
      $cabangId = null;
      $karyawanId = null;

      if ($request->filled('cabang_id')) {
         $decoded = Hashids::decode($request->cabang_id);
         $cabangId = !empty($decoded) ? $decoded[0] : null;
         $request->merge(['cabang_id' => $cabangId]);
      }

      if ($request->filled('karyawan_id')) {
         $decoded = Hashids::decode($request->karyawan_id);
         $karyawanId = !empty($decoded) ? $decoded[0] : null;
         $request->merge(['karyawan_id' => $karyawanId]);
      }

      $validated = $request->validate([
         'cabang_id' => 'required|exists:cabangs,id',
         'karyawan_id' => 'nullable|exists:karyawans,id',
         'tanggal' => 'required|date',
         'jenis' => 'required|in:Pemasukan,Pengeluaran',
         'kategori' => 'required|string|max:100',
         'keterangan' => 'required|string|max:500',
         'jumlah' => 'required|numeric|min:0',
         'bukti_transaksi' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
         'catatan' => 'nullable|string',
         'status' => 'nullable|in:Draft,Pending',
      ], [
         'cabang_id.required' => 'Cabang wajib dipilih',
         'tanggal.required' => 'Tanggal wajib diisi',
         'jenis.required' => 'Jenis transaksi wajib dipilih',
         'kategori.required' => 'Kategori wajib dipilih',
         'keterangan.required' => 'Keterangan wajib diisi',
         'jumlah.required' => 'Jumlah wajib diisi',
         'jumlah.min' => 'Jumlah tidak boleh negatif',
         'bukti_transaksi.max' => 'Ukuran file maksimal 5MB',
      ]);

      try {
         // Handle file upload
         $buktiPath = null;
         if ($request->hasFile('bukti_transaksi')) {
            $buktiPath = $request->file('bukti_transaksi')->store('laporan/bukti', 'public');
         }

         $laporan = LaporanKeuangan::create([
            'cabang_id' => $validated['cabang_id'],
            'karyawan_id' => $validated['karyawan_id'] ?? null,
            'created_by' => Auth::id(),
            'tanggal' => $validated['tanggal'],
            'jenis' => $validated['jenis'],
            'kategori' => $validated['kategori'],
            'keterangan' => $validated['keterangan'],
            'jumlah' => $validated['jumlah'],
            'bukti_transaksi' => $buktiPath,
            'catatan' => $validated['catatan'] ?? null,
            'status' => $validated['status'] ?? LaporanKeuangan::STATUS_DRAFT,
         ]);

         return response()->json([
            'success' => true,
            'message' => 'Laporan keuangan berhasil ditambahkan!',
            'data' => $laporan,
            'redirect' => route('master-data.laporan-keuangan.index')
         ]);
      } catch (\Exception $e) {
         // Delete uploaded file if exists
         if ($buktiPath && Storage::disk('public')->exists($buktiPath)) {
            Storage::disk('public')->delete($buktiPath);
         }

         return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
         ], 500);
      }
   }

   /**
    * Display the specified resource.
    */
   public function show(LaporanKeuangan $laporanKeuangan): View|JsonResponse
   {
      $laporanKeuangan->load(['cabang', 'karyawan', 'creator', 'approver']);

      if (request()->ajax()) {
         return response()->json([
            'success' => true,
            'data' => $laporanKeuangan
         ]);
      }

      return view('pages.master-data.laporan-keuangan.show', compact('laporanKeuangan'));
   }

   /**
    * Show the form for editing the specified resource.
    */
   public function edit(LaporanKeuangan $laporanKeuangan): View|JsonResponse|RedirectResponse
   {
      if (!$laporanKeuangan->canEdit()) {
         if (request()->ajax()) {
            return response()->json([
               'success' => false,
               'message' => 'Laporan tidak dapat diedit karena sudah diproses!'
            ], 400);
         }
         return redirect()->route('master-data.laporan-keuangan.index')
            ->with('error', 'Laporan tidak dapat diedit karena sudah diproses!');
      }

      $cabangs = Cabang::active()->orderBy('nama_cabang')->get();
      $karyawans = Karyawan::active()->orderBy('nama_lengkap')->get();

      $kategoriList = $laporanKeuangan->jenis === LaporanKeuangan::JENIS_PEMASUKAN
         ? LaporanKeuangan::KATEGORI_PEMASUKAN
         : LaporanKeuangan::KATEGORI_PENGELUARAN;

      return view('pages.master-data.laporan-keuangan.edit', compact('laporanKeuangan', 'cabangs', 'karyawans', 'kategoriList'));
   }

   /**
    * Update the specified resource in storage.
    */
   public function update(Request $request, LaporanKeuangan $laporanKeuangan): JsonResponse
   {
      if (!$laporanKeuangan->canEdit()) {
         return response()->json([
            'success' => false,
            'message' => 'Laporan tidak dapat diedit karena sudah diproses!'
         ], 400);
      }

      // Decode hash IDs before validation
      if ($request->filled('cabang_id')) {
         $decoded = Hashids::decode($request->cabang_id);
         $cabangId = !empty($decoded) ? $decoded[0] : null;
         $request->merge(['cabang_id' => $cabangId]);
      }

      if ($request->filled('karyawan_id')) {
         $decoded = Hashids::decode($request->karyawan_id);
         $karyawanId = !empty($decoded) ? $decoded[0] : null;
         $request->merge(['karyawan_id' => $karyawanId]);
      }

      $validated = $request->validate([
         'cabang_id' => 'required|exists:cabangs,id',
         'karyawan_id' => 'nullable|exists:karyawans,id',
         'tanggal' => 'required|date',
         'jenis' => 'required|in:Pemasukan,Pengeluaran',
         'kategori' => 'required|string|max:100',
         'keterangan' => 'required|string|max:500',
         'jumlah' => 'required|numeric|min:0',
         'bukti_transaksi' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
         'catatan' => 'nullable|string',
         'status' => 'nullable|in:Draft,Pending',
      ], [
         'cabang_id.required' => 'Cabang wajib dipilih',
         'tanggal.required' => 'Tanggal wajib diisi',
         'jenis.required' => 'Jenis transaksi wajib dipilih',
         'kategori.required' => 'Kategori wajib dipilih',
         'keterangan.required' => 'Keterangan wajib diisi',
         'jumlah.required' => 'Jumlah wajib diisi',
         'jumlah.min' => 'Jumlah tidak boleh negatif',
         'bukti_transaksi.max' => 'Ukuran file maksimal 5MB',
      ]);

      try {
         // Handle file upload
         $buktiPath = $laporanKeuangan->bukti_transaksi;
         if ($request->hasFile('bukti_transaksi')) {
            // Delete old file
            if ($laporanKeuangan->bukti_transaksi && Storage::disk('public')->exists($laporanKeuangan->bukti_transaksi)) {
               Storage::disk('public')->delete($laporanKeuangan->bukti_transaksi);
            }
            $buktiPath = $request->file('bukti_transaksi')->store('laporan/bukti', 'public');
         }

         $laporanKeuangan->update([
            'cabang_id' => $validated['cabang_id'],
            'karyawan_id' => $validated['karyawan_id'] ?? null,
            'tanggal' => $validated['tanggal'],
            'jenis' => $validated['jenis'],
            'kategori' => $validated['kategori'],
            'keterangan' => $validated['keterangan'],
            'jumlah' => $validated['jumlah'],
            'bukti_transaksi' => $buktiPath,
            'catatan' => $validated['catatan'] ?? null,
            'status' => $validated['status'] ?? $laporanKeuangan->status,
         ]);

         return response()->json([
            'success' => true,
            'message' => 'Laporan keuangan berhasil diperbarui!',
            'data' => $laporanKeuangan,
            'redirect' => route('master-data.laporan-keuangan.index')
         ]);
      } catch (\Exception $e) {
         return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
         ], 500);
      }
   }

   /**
    * Remove the specified resource from storage.
    */
   public function destroy(LaporanKeuangan $laporanKeuangan): JsonResponse
   {
      if ($laporanKeuangan->status === LaporanKeuangan::STATUS_APPROVED) {
         return response()->json([
            'success' => false,
            'message' => 'Laporan yang sudah diapprove tidak dapat dihapus!'
         ], 400);
      }

      try {
         // Delete bukti file if exists
         if ($laporanKeuangan->bukti_transaksi && Storage::disk('public')->exists($laporanKeuangan->bukti_transaksi)) {
            Storage::disk('public')->delete($laporanKeuangan->bukti_transaksi);
         }

         $laporanKeuangan->delete();

         return response()->json([
            'success' => true,
            'message' => 'Laporan keuangan berhasil dihapus!'
         ]);
      } catch (\Exception $e) {
         return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
         ], 500);
      }
   }

   /**
    * Approve laporan keuangan
    */
   public function approve(LaporanKeuangan $laporanKeuangan): JsonResponse
   {
      if (!$laporanKeuangan->canApprove()) {
         return response()->json([
            'success' => false,
            'message' => 'Laporan tidak dapat diapprove!'
         ], 400);
      }

      if ($laporanKeuangan->approve(Auth::id())) {
         // Kirim notifikasi ke karyawan
         \App\Models\Notification::notifyKaryawanLaporanStatus($laporanKeuangan, 'Approved');

         return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil diapprove!'
         ]);
      }

      return response()->json([
         'success' => false,
         'message' => 'Gagal mengapprove laporan!'
      ], 500);
   }

   /**
    * Reject laporan keuangan
    */
   public function reject(Request $request, LaporanKeuangan $laporanKeuangan): JsonResponse
   {
      if (!$laporanKeuangan->canApprove()) {
         return response()->json([
            'success' => false,
            'message' => 'Laporan tidak dapat direject!'
         ], 400);
      }

      $catatan = $request->input('catatan');

      if ($laporanKeuangan->reject(Auth::id(), $catatan)) {
         // Kirim notifikasi ke karyawan
         \App\Models\Notification::notifyKaryawanLaporanStatus($laporanKeuangan, 'Rejected');

         return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil direject!'
         ]);
      }

      return response()->json([
         'success' => false,
         'message' => 'Gagal mereject laporan!'
      ], 500);
   }

   /**
    * Submit laporan for approval
    */
   public function submitForApproval(LaporanKeuangan $laporanKeuangan): JsonResponse
   {
      if ($laporanKeuangan->submitForApproval()) {
         return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil diajukan untuk approval!'
         ]);
      }

      return response()->json([
         'success' => false,
         'message' => 'Gagal mengajukan laporan!'
      ], 400);
   }

   /**
    * Get kategori by jenis
    */
   public function getKategori(Request $request): JsonResponse
   {
      $jenis = $request->get('jenis', LaporanKeuangan::JENIS_PEMASUKAN);

      $kategoriList = $jenis === LaporanKeuangan::JENIS_PEMASUKAN
         ? LaporanKeuangan::KATEGORI_PEMASUKAN
         : LaporanKeuangan::KATEGORI_PENGELUARAN;

      return response()->json([
         'success' => true,
         'data' => $kategoriList
      ]);
   }

   /**
    * Export laporan keuangan to PDF
    */
   public function exportPdf(Request $request)
   {
      $query = LaporanKeuangan::with(['cabang', 'karyawan', 'creator', 'approver'])
         ->approved();

      // Filter by cabang
      if ($request->filled('cabang_id')) {
         $query->where('cabang_id', $request->cabang_id);
      }

      // Filter by jenis
      if ($request->filled('jenis')) {
         $query->where('jenis', $request->jenis);
      }

      // Filter by tanggal
      $tanggalMulai = $request->get('tanggal_mulai', Carbon::now()->startOfMonth()->format('Y-m-d'));
      $tanggalAkhir = $request->get('tanggal_akhir', Carbon::now()->endOfMonth()->format('Y-m-d'));

      $query->whereBetween('tanggal', [$tanggalMulai, $tanggalAkhir]);

      $laporans = $query->orderBy('tanggal', 'asc')->get();

      // Get cabang info if filtered
      $cabang = null;
      if ($request->filled('cabang_id')) {
         $cabang = Cabang::find($request->cabang_id);
      }

      // Calculate summary
      $summary = [
         'total_pemasukan' => $laporans->where('jenis', LaporanKeuangan::JENIS_PEMASUKAN)->sum('jumlah'),
         'total_pengeluaran' => $laporans->where('jenis', LaporanKeuangan::JENIS_PENGELUARAN)->sum('jumlah'),
      ];
      $summary['saldo'] = $summary['total_pemasukan'] - $summary['total_pengeluaran'];

      // Get owner/admin user for signature (get first admin with signature)
      $owner = User::where('is_admin', true)
         ->whereNotNull('ttd')
         ->first();

      // Generate PDF
      $pdf = Pdf::loadView('pdf.laporan-keuangan', [
         'laporans' => $laporans,
         'cabang' => $cabang,
         'summary' => $summary,
         'tanggalMulai' => Carbon::parse($tanggalMulai),
         'tanggalAkhir' => Carbon::parse($tanggalAkhir),
         'jenisFilter' => $request->get('jenis'),
         'owner' => $owner,
      ]);

      $pdf->setPaper('A4', 'portrait');

      $filename = 'laporan-keuangan-' . $tanggalMulai . '-' . $tanggalAkhir . '.pdf';

      return $pdf->download($filename);
   }

   /**
    * Preview PDF laporan keuangan
    */
   public function previewPdf(Request $request)
   {
      $query = LaporanKeuangan::with(['cabang', 'karyawan', 'creator', 'approver'])
         ->approved();

      // Filter by cabang
      if ($request->filled('cabang_id')) {
         $query->where('cabang_id', $request->cabang_id);
      }

      // Filter by jenis
      if ($request->filled('jenis')) {
         $query->where('jenis', $request->jenis);
      }

      // Filter by tanggal
      $tanggalMulai = $request->get('tanggal_mulai', Carbon::now()->startOfMonth()->format('Y-m-d'));
      $tanggalAkhir = $request->get('tanggal_akhir', Carbon::now()->endOfMonth()->format('Y-m-d'));

      $query->whereBetween('tanggal', [$tanggalMulai, $tanggalAkhir]);

      $laporans = $query->orderBy('tanggal', 'asc')->get();

      // Get cabang info if filtered
      $cabang = null;
      if ($request->filled('cabang_id')) {
         $cabang = Cabang::find($request->cabang_id);
      }

      // Calculate summary
      $summary = [
         'total_pemasukan' => $laporans->where('jenis', LaporanKeuangan::JENIS_PEMASUKAN)->sum('jumlah'),
         'total_pengeluaran' => $laporans->where('jenis', LaporanKeuangan::JENIS_PENGELUARAN)->sum('jumlah'),
      ];
      $summary['saldo'] = $summary['total_pemasukan'] - $summary['total_pengeluaran'];

      // Get owner/admin user for signature (get first admin with signature)
      $owner = User::where('is_admin', true)
         ->whereNotNull('ttd')
         ->first();

      // Generate PDF
      $pdf = Pdf::loadView('pdf.laporan-keuangan', [
         'laporans' => $laporans,
         'cabang' => $cabang,
         'summary' => $summary,
         'tanggalMulai' => Carbon::parse($tanggalMulai),
         'tanggalAkhir' => Carbon::parse($tanggalAkhir),
         'jenisFilter' => $request->get('jenis'),
         'owner' => $owner,
      ]);

      $pdf->setPaper('A4', 'portrait');

      return $pdf->stream('laporan-keuangan.pdf');
   }
}
