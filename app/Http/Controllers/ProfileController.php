<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
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
         $user = Auth::user();

         // Delete old avatar if exists
         if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
         }

         // Store new avatar
         $path = $request->file('avatar')->store('avatars', 'public');

         // Update user
         $user->update(['avatar' => $path]);

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
         $user = Auth::user();

         // Delete avatar file if exists
         if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
         }

         // Update user
         $user->update(['avatar' => null]);

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
      $user = Auth::user();

      $validated = $request->validate([
         'name' => 'required|string|max:255',
         'email' => 'required|email|max:255|unique:users,email,' . $user->id,
         'phone' => 'nullable|string|max:20',
      ], [
         'name.required' => 'Nama wajib diisi',
         'email.required' => 'Email wajib diisi',
         'email.email' => 'Format email tidak valid',
         'email.unique' => 'Email sudah digunakan',
      ]);

      try {
         $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
         ]);

         return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui'
         ]);
      } catch (\Exception $e) {
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

      $user = Auth::user();

      // Check current password
      if (!Hash::check($validated['current_password'], $user->password)) {
         return response()->json([
            'success' => false,
            'message' => 'Password saat ini tidak sesuai'
         ], 422);
      }

      try {
         $user->update([
            'password' => Hash::make($validated['password'])
         ]);

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
         $user = Auth::user();
         $user->update(['ttd' => $request->signature]);

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
         $user = Auth::user();
         $user->update(['ttd' => null]);

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
