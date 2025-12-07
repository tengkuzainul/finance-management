<?php

namespace App\Models;

use App\Traits\HasHashId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cabang extends Model
{
   use HasFactory, HasHashId;

   protected $table = 'cabangs';

   protected $fillable = [
      'nama_cabang',
      'kode_cabang',
      'alamat_lengkap',
      'no_telepon',
      'email',
      'jumlah_karyawan',
      'is_active',
   ];

   protected $casts = [
      'is_active' => 'boolean',
      'jumlah_karyawan' => 'integer',
   ];

   /**
    * Get karyawan yang ada di cabang ini
    */
   public function karyawans(): HasMany
   {
      return $this->hasMany(Karyawan::class, 'cabang_id');
   }

   /**
    * Get laporan keuangan untuk cabang ini
    */
   public function laporanKeuangans(): HasMany
   {
      return $this->hasMany(LaporanKeuangan::class, 'cabang_id');
   }

   /**
    * Update jumlah karyawan aktif
    */
   public function updateJumlahKaryawan(): void
   {
      $this->jumlah_karyawan = $this->karyawans()->where('is_active', true)->count();
      $this->save();
   }

   /**
    * Scope untuk cabang aktif
    */
   public function scopeActive($query)
   {
      return $query->where('is_active', true);
   }

   /**
    * Generate kode cabang otomatis
    */
   public static function generateKodeCabang(): string
   {
      $lastCabang = self::orderBy('id', 'desc')->first();
      $lastNumber = $lastCabang ? intval(substr($lastCabang->kode_cabang, 3)) : 0;
      return 'CBG' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
   }
}
