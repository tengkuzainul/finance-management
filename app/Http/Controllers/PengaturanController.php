<?php

namespace App\Http\Controllers;

use App\Models\Informasi;
use App\Models\Karyawan;
use App\Models\Notification;
use App\Models\Pengaturan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Vinkla\Hashids\Facades\Hashids;

class PengaturanController extends Controller
{
   /**
    * Display settings page with tabs
    */
   public function index(Request $request)
   {
      $tab = $request->get('tab', 'konfigurasi');

      // Get all settings
      $pengaturans = Pengaturan::where('is_active', true)->get();

      // Get persen gaji setting specifically
      $persenGaji = Pengaturan::where('kode', 'persen_gaji')->first();

      // If not exists, create default
      if (!$persenGaji) {
         $persenGaji = Pengaturan::create([
            'kode' => 'persen_gaji',
            'nama' => 'Persentase Gaji Karyawan',
            'nilai' => '13',
            'tipe' => 'number',
            'deskripsi' => 'Persentase gaji harian karyawan dari total pemasukan yang diapprove',
            'is_active' => true,
         ]);
      }

      // Get informasi list
      $informasis = Informasi::with('creator')
         ->orderBy('created_at', 'desc')
         ->paginate(10);

      return view('pages.settings.index', compact('tab', 'pengaturans', 'persenGaji', 'informasis'));
   }

   /**
    * Update persen gaji setting
    */
   public function updatePersenGaji(Request $request)
   {
      $request->validate([
         'persen_gaji' => 'required|numeric|min:0|max:100',
      ]);

      $persenGaji = Pengaturan::where('kode', 'persen_gaji')->first();

      if (!$persenGaji) {
         $persenGaji = new Pengaturan();
         $persenGaji->kode = 'persen_gaji';
         $persenGaji->nama = 'Persentase Gaji Karyawan';
         $persenGaji->tipe = 'number';
         $persenGaji->deskripsi = 'Persentase gaji harian karyawan dari total pemasukan yang diapprove';
         $persenGaji->is_active = true;
      }

      $persenGaji->nilai = $request->persen_gaji;
      $persenGaji->save();

      return redirect()->route('settings.index', ['tab' => 'konfigurasi'])
         ->with('success', 'Persentase gaji berhasil diperbarui menjadi ' . $request->persen_gaji . '%');
   }

   /**
    * Store new informasi
    */
   public function storeInformasi(Request $request)
   {
      $request->validate([
         'judul' => 'required|string|max:255',
         'deskripsi' => 'required|string',
         'lampiran' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:5120',
      ]);

      $data = [
         'judul' => $request->judul,
         'deskripsi' => $request->deskripsi,
         'created_by' => Auth::id(),
      ];

      // Handle lampiran upload
      if ($request->hasFile('lampiran')) {
         $file = $request->file('lampiran');
         $filename = time() . '_' . $file->getClientOriginalName();
         $path = $file->storeAs('informasi', $filename, 'public');
         $data['lampiran'] = $path;
      }

      $informasi = Informasi::create($data);

      // Send notification to all karyawan
      $this->sendNotificationToAllKaryawan($informasi);

      return redirect()->route('settings.index', ['tab' => 'informasi'])
         ->with('success', 'Informasi berhasil ditambahkan dan notifikasi telah dikirim ke seluruh karyawan');
   }

   /**
    * Show informasi detail
    */
   public function showInformasi($id)
   {
      $decoded = Hashids::decode($id);

      if (empty($decoded)) {
         abort(404);
      }

      $informasi = Informasi::with('creator')->findOrFail($decoded[0]);

      return view('pages.settings.informasi-detail', compact('informasi'));
   }

   /**
    * Update informasi
    */
   public function updateInformasi(Request $request, $id)
   {
      $decoded = Hashids::decode($id);

      if (empty($decoded)) {
         abort(404);
      }

      $informasi = Informasi::findOrFail($decoded[0]);

      $request->validate([
         'judul' => 'required|string|max:255',
         'deskripsi' => 'required|string',
         'lampiran' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:5120',
      ]);

      $data = [
         'judul' => $request->judul,
         'deskripsi' => $request->deskripsi,
      ];

      // Handle lampiran upload
      if ($request->hasFile('lampiran')) {
         // Delete old lampiran if exists
         if ($informasi->lampiran) {
            Storage::disk('public')->delete($informasi->lampiran);
         }

         $file = $request->file('lampiran');
         $filename = time() . '_' . $file->getClientOriginalName();
         $path = $file->storeAs('informasi', $filename, 'public');
         $data['lampiran'] = $path;
      }

      $informasi->update($data);

      return redirect()->route('settings.informasi.show', $id)
         ->with('success', 'Informasi berhasil diperbarui');
   }

   /**
    * Delete informasi
    */
   public function destroyInformasi($id)
   {
      $decoded = Hashids::decode($id);

      if (empty($decoded)) {
         abort(404);
      }

      $informasi = Informasi::findOrFail($decoded[0]);

      // Delete lampiran if exists
      if ($informasi->lampiran) {
         Storage::disk('public')->delete($informasi->lampiran);
      }

      $informasi->delete();

      return redirect()->route('settings.index', ['tab' => 'informasi'])
         ->with('success', 'Informasi berhasil dihapus');
   }

   /**
    * Send notification to all karyawan
    */
   private function sendNotificationToAllKaryawan(Informasi $informasi)
   {
      // Get all active karyawan with user accounts
      $karyawans = Karyawan::whereHas('user', function ($query) {
         $query->where('is_active', true);
      })->with('user')->get();

      foreach ($karyawans as $karyawan) {
         if ($karyawan->user) {
            Notification::create([
               'user_id' => $karyawan->user->id,
               'from_user_id' => Auth::id(),
               'type' => Notification::TYPE_INFORMASI,
               'title' => 'Informasi Baru: ' . $informasi->judul,
               'message' => \Illuminate\Support\Str::limit($informasi->deskripsi, 100),
               'link' => route('informasi.show', $informasi->hashid),
            ]);
         }
      }
   }

   /**
    * Remove lampiran from informasi
    */
   public function removeLampiran($id)
   {
      $decoded = Hashids::decode($id);

      if (empty($decoded)) {
         return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
      }

      $informasi = Informasi::findOrFail($decoded[0]);

      if ($informasi->lampiran) {
         Storage::disk('public')->delete($informasi->lampiran);
         $informasi->update(['lampiran' => null]);
      }

      return response()->json(['success' => true, 'message' => 'Lampiran berhasil dihapus']);
   }
}
