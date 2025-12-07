<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Vinkla\Hashids\Facades\Hashids;

class KaryawanController extends Controller
{
   /**
    * Display a listing of the resource.
    */
   public function index(Request $request): View|JsonResponse
   {
      $query = Karyawan::with(['cabang', 'user']);

      // Search
      if ($request->filled('search')) {
         $search = $request->search;
         $query->where(function ($q) use ($search) {
            $q->where('nama_lengkap', 'like', "%{$search}%")
               ->orWhere('nik', 'like', "%{$search}%")
               ->orWhere('email', 'like', "%{$search}%")
               ->orWhere('jabatan', 'like', "%{$search}%");
         });
      }

      // Filter by cabang (decode hash if provided)
      if ($request->filled('cabang_id')) {
         $cabangId = $request->cabang_id;
         // Try to decode hash, if fails assume it's already numeric
         $decoded = Hashids::decode($cabangId);
         $cabangId = !empty($decoded) ? $decoded[0] : $cabangId;
         $query->where('cabang_id', $cabangId);
      }

      // Filter by status karyawan
      if ($request->filled('status_karyawan')) {
         $query->where('status_karyawan', $request->status_karyawan);
      }

      // Filter by status aktif
      if ($request->filled('status')) {
         $query->where('is_active', $request->status === 'active');
      }

      // Sorting
      $sortBy = $request->get('sort_by', 'created_at');
      $sortOrder = $request->get('sort_order', 'desc');
      $query->orderBy($sortBy, $sortOrder);

      $karyawans = $query->paginate(10)->withQueryString();
      $cabangs = Cabang::active()->orderBy('nama_cabang')->get();

      if ($request->ajax()) {
         return response()->json([
            'success' => true,
            'data' => $karyawans
         ]);
      }

      return view('pages.master-data.karyawan.index', compact('karyawans', 'cabangs'));
   }

   /**
    * Show the form for creating a new resource.
    */
   public function create(Request $request): View
   {
      $nik = Karyawan::generateNIK();
      $cabangs = Cabang::active()->orderBy('nama_cabang')->get();

      // Decode cabang_id from hash if provided
      $selectedCabangId = null;
      if ($request->filled('cabang_id')) {
         $decoded = Hashids::decode($request->cabang_id);
         $selectedCabangId = !empty($decoded) ? $decoded[0] : null;
      }

      return view('pages.master-data.karyawan.create', compact('nik', 'cabangs', 'selectedCabangId'));
   }

   /**
    * Store a newly created resource in storage.
    */
   public function store(Request $request): JsonResponse
   {
      // Decode hash ID for cabang_id before validation
      if ($request->filled('cabang_id')) {
         $decoded = Hashids::decode($request->cabang_id);
         if (!empty($decoded)) {
            $request->merge(['cabang_id' => $decoded[0]]);
         }
      }

      $validated = $request->validate([
         'cabang_id' => 'required|exists:cabangs,id',
         'nik' => 'required|string|max:30|unique:karyawans,nik',
         'nama_lengkap' => 'required|string|max:255',
         'tempat_lahir' => 'nullable|string|max:100',
         'tanggal_lahir' => 'nullable|date',
         'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
         'alamat' => 'nullable|string',
         'no_telepon' => 'nullable|string|max:20',
         'email' => 'nullable|email|max:255',
         'agama' => 'nullable|string|max:50',
         'status_pernikahan' => 'nullable|in:Belum Menikah,Menikah,Duda,Janda',
         'tanggal_masuk' => 'required|date',
         'is_active' => 'boolean',
         'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
         // User account fields
         'create_user_account' => 'boolean',
         'username' => 'nullable|required_if:create_user_account,1|string|max:255|unique:users,username',
         'password' => 'nullable|required_if:create_user_account,1|string|min:6',
      ], [
         'cabang_id.required' => 'Cabang wajib dipilih',
         'nik.required' => 'NIK wajib diisi',
         'nik.unique' => 'NIK sudah digunakan',
         'nama_lengkap.required' => 'Nama lengkap wajib diisi',
         'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih',
         'tanggal_masuk.required' => 'Tanggal masuk wajib diisi',
         'username.required_if' => 'Username wajib diisi jika membuat akun user',
         'username.unique' => 'Username sudah digunakan',
         'password.required_if' => 'Password wajib diisi jika membuat akun user',
         'password.min' => 'Password minimal 6 karakter',
         'foto.image' => 'File harus berupa gambar',
         'foto.max' => 'Ukuran foto maksimal 2MB',
      ]);

      try {
         DB::beginTransaction();

         // Handle foto upload
         $fotoPath = null;
         if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('karyawan/foto', 'public');
         }

         // Create user account if requested
         $userId = null;
         if ($request->has('create_user_account') && $request->create_user_account) {
            $user = User::create([
               'name' => $validated['nama_lengkap'],
               'username' => $validated['username'],
               'email' => $validated['email'] ?? $validated['username'] . '@kebabikhwan.local',
               'password' => Hash::make($validated['password']),
               'is_active' => $request->has('is_active'),
               'is_admin' => false,
            ]);
            $userId = $user->id;
         }

         // Create karyawan
         $karyawan = Karyawan::create([
            'cabang_id' => $validated['cabang_id'],
            'user_id' => $userId,
            'nik' => $validated['nik'],
            'nama_lengkap' => $validated['nama_lengkap'],
            'tempat_lahir' => $validated['tempat_lahir'] ?? null,
            'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'alamat' => $validated['alamat'] ?? null,
            'no_telepon' => $validated['no_telepon'] ?? null,
            'email' => $validated['email'] ?? null,
            'agama' => $validated['agama'] ?? null,
            'status_pernikahan' => $validated['status_pernikahan'] ?? null,
            'tanggal_masuk' => $validated['tanggal_masuk'],
            'is_active' => $request->has('is_active'),
            'foto' => $fotoPath,
         ]);

         DB::commit();

         return response()->json([
            'success' => true,
            'message' => 'Karyawan berhasil ditambahkan!',
            'data' => $karyawan,
            'redirect' => route('master-data.karyawan.index')
         ]);
      } catch (\Exception $e) {
         DB::rollBack();

         // Delete uploaded file if exists
         if ($fotoPath && Storage::disk('public')->exists($fotoPath)) {
            Storage::disk('public')->delete($fotoPath);
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
   public function show(Karyawan $karyawan): View|JsonResponse
   {
      $karyawan->load(['cabang', 'user', 'laporanKeuangans' => function ($query) {
         $query->latest()->limit(10);
      }]);

      if (request()->ajax()) {
         return response()->json([
            'success' => true,
            'data' => $karyawan
         ]);
      }

      return view('pages.master-data.karyawan.show', compact('karyawan'));
   }

   /**
    * Show the form for editing the specified resource.
    */
   public function edit(Karyawan $karyawan): View
   {
      $cabangs = Cabang::active()->orderBy('nama_cabang')->get();
      $karyawan->load(['cabang', 'user']);

      return view('pages.master-data.karyawan.edit', compact('karyawan', 'cabangs'));
   }

   /**
    * Update the specified resource in storage.
    */
   public function update(Request $request, Karyawan $karyawan): JsonResponse
   {
      // Decode hash ID for cabang_id before validation
      if ($request->filled('cabang_id')) {
         $decoded = Hashids::decode($request->cabang_id);
         if (!empty($decoded)) {
            $request->merge(['cabang_id' => $decoded[0]]);
         }
      }

      $validated = $request->validate([
         'cabang_id' => 'required|exists:cabangs,id',
         'nik' => 'required|string|max:30|unique:karyawans,nik,' . $karyawan->id,
         'nama_lengkap' => 'required|string|max:255',
         'tempat_lahir' => 'nullable|string|max:100',
         'tanggal_lahir' => 'nullable|date',
         'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
         'alamat' => 'nullable|string',
         'no_telepon' => 'nullable|string|max:20',
         'email' => 'nullable|email|max:255',
         'agama' => 'nullable|string|max:50',
         'status_pernikahan' => 'nullable|in:Belum Menikah,Menikah,Duda,Janda',
         'tanggal_masuk' => 'required|date',
         'is_active' => 'boolean',
         'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
         // User account fields for update
         'update_password' => 'boolean',
         'password' => 'nullable|required_if:update_password,1|string|min:6',
      ], [
         'cabang_id.required' => 'Cabang wajib dipilih',
         'nik.required' => 'NIK wajib diisi',
         'nik.unique' => 'NIK sudah digunakan',
         'nama_lengkap.required' => 'Nama lengkap wajib diisi',
         'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih',
         'tanggal_masuk.required' => 'Tanggal masuk wajib diisi',
         'password.min' => 'Password minimal 6 karakter',
         'foto.image' => 'File harus berupa gambar',
         'foto.max' => 'Ukuran foto maksimal 2MB',
      ]);

      try {
         DB::beginTransaction();

         // Handle foto upload
         $fotoPath = $karyawan->foto;
         if ($request->hasFile('foto')) {
            // Delete old foto
            if ($karyawan->foto && Storage::disk('public')->exists($karyawan->foto)) {
               Storage::disk('public')->delete($karyawan->foto);
            }
            $fotoPath = $request->file('foto')->store('karyawan/foto', 'public');
         }

         // Update user account if exists
         if ($karyawan->user) {
            $karyawan->user->update([
               'name' => $validated['nama_lengkap'],
               'is_active' => $request->has('is_active'),
            ]);

            // Update password if requested
            if ($request->has('update_password') && $request->update_password && $request->filled('password')) {
               $karyawan->user->update([
                  'password' => Hash::make($validated['password'])
               ]);
            }
         }

         // Update karyawan
         $karyawan->update([
            'cabang_id' => $validated['cabang_id'],
            'nik' => $validated['nik'],
            'nama_lengkap' => $validated['nama_lengkap'],
            'tempat_lahir' => $validated['tempat_lahir'] ?? null,
            'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'alamat' => $validated['alamat'] ?? null,
            'no_telepon' => $validated['no_telepon'] ?? null,
            'email' => $validated['email'] ?? null,
            'agama' => $validated['agama'] ?? null,
            'status_pernikahan' => $validated['status_pernikahan'] ?? null,
            'tanggal_masuk' => $validated['tanggal_masuk'],
            'is_active' => $request->has('is_active'),
            'foto' => $fotoPath,
         ]);

         DB::commit();

         return response()->json([
            'success' => true,
            'message' => 'Karyawan berhasil diperbarui!',
            'data' => $karyawan,
            'redirect' => route('master-data.karyawan.index')
         ]);
      } catch (\Exception $e) {
         DB::rollBack();

         return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
         ], 500);
      }
   }

   /**
    * Remove the specified resource from storage.
    */
   public function destroy(Karyawan $karyawan): JsonResponse
   {
      // Check if karyawan has laporan keuangan
      if ($karyawan->laporanKeuangans()->count() > 0) {
         return response()->json([
            'success' => false,
            'message' => 'Karyawan tidak dapat dihapus karena memiliki laporan keuangan!'
         ], 400);
      }

      try {
         DB::beginTransaction();

         // Delete foto if exists
         if ($karyawan->foto && Storage::disk('public')->exists($karyawan->foto)) {
            Storage::disk('public')->delete($karyawan->foto);
         }

         // Delete associated user account if exists
         if ($karyawan->user) {
            $karyawan->user->delete();
         }

         $karyawan->delete();

         DB::commit();

         return response()->json([
            'success' => true,
            'message' => 'Karyawan berhasil dihapus!'
         ]);
      } catch (\Exception $e) {
         DB::rollBack();

         return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
         ], 500);
      }
   }

   /**
    * Toggle status aktif karyawan
    */
   public function toggleStatus(Karyawan $karyawan): JsonResponse
   {
      try {
         DB::beginTransaction();

         $karyawan->is_active = !$karyawan->is_active;
         $karyawan->save();

         // Also toggle user account if exists
         if ($karyawan->user) {
            $karyawan->user->update(['is_active' => $karyawan->is_active]);
         }

         DB::commit();

         $status = $karyawan->is_active ? 'diaktifkan' : 'dinonaktifkan';

         return response()->json([
            'success' => true,
            'message' => "Karyawan berhasil {$status}!",
            'data' => $karyawan
         ]);
      } catch (\Exception $e) {
         DB::rollBack();

         return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
         ], 500);
      }
   }

   /**
    * Get karyawan for dropdown/select by cabang
    */
   public function getListByCabang(Request $request): JsonResponse
   {
      $query = Karyawan::active();

      if ($request->filled('cabang_id')) {
         $query->where('cabang_id', $request->cabang_id);
      }

      if ($request->filled('search')) {
         $query->where('nama_lengkap', 'like', '%' . $request->search . '%');
      }

      $karyawans = $query->select('id', 'nama_lengkap', 'nik', 'jabatan', 'cabang_id')
         ->orderBy('nama_lengkap')
         ->get();

      return response()->json([
         'success' => true,
         'data' => $karyawans
      ]);
   }
}
