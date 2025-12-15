<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
   /**
    * Show profile page
    */
   public function index(): View
   {
      return view('pages.profile.index');
   }

   /**
    * Update avatar
    */
   public function updateAvatar(Request $request): JsonResponse
   {
      $request->validate([
         'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
      ], [
         'avatar.required' => 'Pilih foto terlebih dahulu',
         'avatar.image' => 'File harus berupa gambar',
         'avatar.mimes' => 'Format gambar harus jpeg, png, jpg, gif, atau webp',
         'avatar.max' => 'Ukuran gambar maksimal 2MB',
      ]);

      try {
         $user = User::find(Auth::id());

         // Delete old avatar if exists
         if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
         }

         // Store new avatar
         $path = $request->file('avatar')->store('avatars', 'public');

         // Update user
         $user->avatar = $path;
         $user->save();

         return response()->json([
            'success' => true,
            'message' => 'Foto profil berhasil diperbarui',
            'avatar_url' => asset('storage/' . $path)
         ]);
      } catch (\Exception $e) {
         return response()->json([
            'success' => false,
            'message' => 'Gagal mengupload foto: ' . $e->getMessage()
         ], 500);
      }
   }

   /**
    * Remove avatar
    */
   public function removeAvatar(): JsonResponse
   {
      try {
         $user = User::find(Auth::id());

         // Delete avatar file if exists
         if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
         }

         // Update user
         $user->avatar = null;
         $user->save();

         return response()->json([
            'success' => true,
            'message' => 'Foto profil berhasil dihapus'
         ]);
      } catch (\Exception $e) {
         return response()->json([
            'success' => false,
            'message' => 'Gagal menghapus foto: ' . $e->getMessage()
         ], 500);
      }
   }

   /**
    * Update profile information
    */
   public function updateProfile(Request $request): JsonResponse
   {
      $userId = Auth::id();

      $validated = $request->validate([
         'name' => 'required|string|max:255',
         'email' => 'required|email|max:255|unique:users,email,' . $userId,
         'phone' => 'nullable|string|max:20',
      ], [
         'name.required' => 'Nama wajib diisi',
         'email.required' => 'Email wajib diisi',
         'email.email' => 'Format email tidak valid',
         'email.unique' => 'Email sudah digunakan',
      ]);

      try {
         DB::beginTransaction();

         // Update user data
         $user = User::find($userId);
         $user->name = $validated['name'];
         $user->email = $validated['email'];
         $user->save();

         // Update karyawan data (phone number) if exists
         $karyawan = Karyawan::where('user_id', $userId)->first();
         if ($karyawan && isset($validated['phone'])) {
            $karyawan->no_telepon = $validated['phone'];
            $karyawan->nama_lengkap = $validated['name']; // Sync name
            $karyawan->email = $validated['email']; // Sync email
            $karyawan->save();
         }

         DB::commit();

         return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui'
         ]);
      } catch (\Exception $e) {
         DB::rollBack();
         return response()->json([
            'success' => false,
            'message' => 'Gagal memperbarui profil: ' . $e->getMessage()
         ], 500);
      }
   }

   /**
    * Update password
    */
   public function updatePassword(Request $request): JsonResponse
   {
      $validated = $request->validate([
         'current_password' => 'required',
         'password' => ['required', 'confirmed', Password::min(8)],
      ], [
         'current_password.required' => 'Password saat ini wajib diisi',
         'password.required' => 'Password baru wajib diisi',
         'password.confirmed' => 'Konfirmasi password tidak cocok',
         'password.min' => 'Password minimal 8 karakter',
      ]);

      $user = User::find(Auth::id());

      // Check current password
      if (!Hash::check($validated['current_password'], $user->password)) {
         return response()->json([
            'success' => false,
            'message' => 'Password saat ini tidak sesuai'
         ], 422);
      }

      try {
         $user->password = Hash::make($validated['password']);
         $user->save();

         return response()->json([
            'success' => true,
            'message' => 'Password berhasil diperbarui'
         ]);
      } catch (\Exception $e) {
         return response()->json([
            'success' => false,
            'message' => 'Gagal memperbarui password: ' . $e->getMessage()
         ], 500);
      }
   }

   /**
    * Save signature (TTD)
    */
   public function saveSignature(Request $request): JsonResponse
   {
      $request->validate([
         'signature' => 'required|string',
      ], [
         'signature.required' => 'Tanda tangan wajib diisi',
      ]);

      try {
         $user = User::find(Auth::id());
         $user->ttd = $request->signature;
         $user->save();

         return response()->json([
            'success' => true,
            'message' => 'Tanda tangan berhasil disimpan'
         ]);
      } catch (\Exception $e) {
         return response()->json([
            'success' => false,
            'message' => 'Gagal menyimpan tanda tangan: ' . $e->getMessage()
         ], 500);
      }
   }

   /**
    * Remove signature (TTD)
    */
   public function removeSignature(): JsonResponse
   {
      try {
         $user = User::find(Auth::id());
         $user->ttd = null;
         $user->save();

         return response()->json([
            'success' => true,
            'message' => 'Tanda tangan berhasil dihapus'
         ]);
      } catch (\Exception $e) {
         return response()->json([
            'success' => false,
            'message' => 'Gagal menghapus tanda tangan: ' . $e->getMessage()
         ], 500);
      }
   }
}
