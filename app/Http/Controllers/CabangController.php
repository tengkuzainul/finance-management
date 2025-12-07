<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class CabangController extends Controller
{
   /**
    * Display a listing of the resource.
    */
   public function index(Request $request): View|JsonResponse
   {
      $query = Cabang::query();

      // Search
      if ($request->filled('search')) {
         $search = $request->search;
         $query->where(function ($q) use ($search) {
            $q->where('nama_cabang', 'like', "%{$search}%")
               ->orWhere('kode_cabang', 'like', "%{$search}%")
               ->orWhere('alamat_lengkap', 'like', "%{$search}%");
         });
      }

      // Filter by status
      if ($request->filled('status')) {
         $query->where('is_active', $request->status === 'active');
      }

      // Sorting
      $sortBy = $request->get('sort_by', 'created_at');
      $sortOrder = $request->get('sort_order', 'desc');
      $query->orderBy($sortBy, $sortOrder);

      $cabangs = $query->paginate(10)->withQueryString();

      // For AJAX requests
      if ($request->ajax()) {
         return response()->json([
            'success' => true,
            'data' => $cabangs
         ]);
      }

      return view('pages.master-data.cabang.index', compact('cabangs'));
   }

   /**
    * Show the form for creating a new resource.
    */
   public function create(): View
   {
      $kodeCabang = Cabang::generateKodeCabang();
      return view('pages.master-data.cabang.create', compact('kodeCabang'));
   }

   /**
    * Store a newly created resource in storage.
    */
   public function store(Request $request): JsonResponse
   {
      $validated = $request->validate([
         'nama_cabang' => 'required|string|max:255',
         'kode_cabang' => 'required|string|max:20|unique:cabangs,kode_cabang',
         'alamat_lengkap' => 'required|string',
         'no_telepon' => 'nullable|string|max:20',
         'email' => 'nullable|email|max:255',
         'is_active' => 'boolean',
      ], [
         'nama_cabang.required' => 'Nama cabang wajib diisi',
         'kode_cabang.required' => 'Kode cabang wajib diisi',
         'kode_cabang.unique' => 'Kode cabang sudah digunakan',
         'alamat_lengkap.required' => 'Alamat lengkap wajib diisi',
         'email.email' => 'Format email tidak valid',
      ]);

      $validated['is_active'] = $request->has('is_active');
      $validated['jumlah_karyawan'] = 0;

      $cabang = Cabang::create($validated);

      return response()->json([
         'success' => true,
         'message' => 'Cabang berhasil ditambahkan!',
         'data' => $cabang,
         'redirect' => route('master-data.cabang.index')
      ]);
   }

   /**
    * Display the specified resource.
    */
   public function show(Cabang $cabang): View|JsonResponse
   {
      $cabang->load(['karyawans' => function ($query) {
         $query->where('is_active', true)->limit(5);
      }]);

      if (request()->ajax()) {
         return response()->json([
            'success' => true,
            'data' => $cabang
         ]);
      }

      return view('pages.master-data.cabang.show', compact('cabang'));
   }

   /**
    * Show the form for editing the specified resource.
    */
   public function edit(Cabang $cabang): View
   {
      return view('pages.master-data.cabang.edit', compact('cabang'));
   }

   /**
    * Update the specified resource in storage.
    */
   public function update(Request $request, Cabang $cabang): JsonResponse
   {
      $validated = $request->validate([
         'nama_cabang' => 'required|string|max:255',
         'kode_cabang' => 'required|string|max:20|unique:cabangs,kode_cabang,' . $cabang->id,
         'alamat_lengkap' => 'required|string',
         'no_telepon' => 'nullable|string|max:20',
         'email' => 'nullable|email|max:255',
         'is_active' => 'boolean',
      ], [
         'nama_cabang.required' => 'Nama cabang wajib diisi',
         'kode_cabang.required' => 'Kode cabang wajib diisi',
         'kode_cabang.unique' => 'Kode cabang sudah digunakan',
         'alamat_lengkap.required' => 'Alamat lengkap wajib diisi',
         'email.email' => 'Format email tidak valid',
      ]);

      $validated['is_active'] = $request->has('is_active');

      $cabang->update($validated);

      return response()->json([
         'success' => true,
         'message' => 'Cabang berhasil diperbarui!',
         'data' => $cabang,
         'redirect' => route('master-data.cabang.index')
      ]);
   }

   /**
    * Remove the specified resource from storage.
    */
   public function destroy(Cabang $cabang): JsonResponse
   {
      // Check if cabang has karyawan
      if ($cabang->karyawans()->count() > 0) {
         return response()->json([
            'success' => false,
            'message' => 'Cabang tidak dapat dihapus karena masih memiliki karyawan!'
         ], 400);
      }

      // Check if cabang has laporan keuangan
      if ($cabang->laporanKeuangans()->count() > 0) {
         return response()->json([
            'success' => false,
            'message' => 'Cabang tidak dapat dihapus karena masih memiliki laporan keuangan!'
         ], 400);
      }

      $cabang->delete();

      return response()->json([
         'success' => true,
         'message' => 'Cabang berhasil dihapus!'
      ]);
   }

   /**
    * Toggle status aktif cabang
    */
   public function toggleStatus(Cabang $cabang): JsonResponse
   {
      $cabang->is_active = !$cabang->is_active;
      $cabang->save();

      $status = $cabang->is_active ? 'diaktifkan' : 'dinonaktifkan';

      return response()->json([
         'success' => true,
         'message' => "Cabang berhasil {$status}!",
         'data' => $cabang
      ]);
   }

   /**
    * Get cabang for dropdown/select
    */
   public function getList(Request $request): JsonResponse
   {
      $query = Cabang::active();

      if ($request->filled('search')) {
         $query->where('nama_cabang', 'like', '%' . $request->search . '%');
      }

      $cabangs = $query->select('id', 'nama_cabang', 'kode_cabang')
         ->orderBy('nama_cabang')
         ->get();

      return response()->json([
         'success' => true,
         'data' => $cabangs
      ]);
   }
}
