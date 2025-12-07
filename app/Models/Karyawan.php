<?php

namespace App\Models;

use App\Traits\HasHashId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Karyawan extends Model
{
   use HasFactory, HasHashId;

   protected $table = 'karyawans';

   protected $fillable = [
      'cabang_id',
      'user_id',
      'nik',
      'nama_lengkap',
      'tempat_lahir',
      'tanggal_lahir',
      'jenis_kelamin',
      'alamat',
      'no_telepon',
      'email',
      'agama',
      'status_pernikahan',
      'tanggal_masuk',
      'is_active',
      'foto',
   ];

   protected $casts = [
      'is_active' => 'boolean',
      'tanggal_lahir' => 'date',
      'tanggal_masuk' => 'date',
   ];

   /**
    * Get cabang tempat karyawan bekerja
    */
   public function cabang(): BelongsTo
   {
      return $this->belongsTo(Cabang::class, 'cabang_id');
   }

   /**
    * Get user account yang terhubung
    */
   public function user(): BelongsTo
   {
      return $this->belongsTo(User::class, 'user_id');
   }

   /**
    * Get laporan keuangan yang dibuat oleh karyawan ini
    */
   public function laporanKeuangans(): HasMany
   {
      return $this->hasMany(LaporanKeuangan::class, 'karyawan_id');
   }

   /**
    * Scope untuk karyawan aktif
    */
   public function scopeActive($query)
   {
      return $query->where('is_active', true);
   }

   /**
    * Generate NIK otomatis
    */
   public static function generateNIK(): string
   {
      $year = date('Y');
      $month = date('m');
      $lastKaryawan = self::whereYear('created_at', $year)
         ->whereMonth('created_at', $month)
         ->orderBy('id', 'desc')
         ->first();

      $lastNumber = $lastKaryawan ? intval(substr($lastKaryawan->nik, -4)) : 0;
      return 'KRY' . $year . $month . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
   }

   /**
    * Get full name with title
    */
   public function getFullNameAttribute(): string
   {
      return $this->nama_lengkap;
   }

   /**
    * Get age from tanggal lahir
    */
   public function getAgeAttribute(): ?int
   {
      return $this->tanggal_lahir ? $this->tanggal_lahir->age : null;
   }

   /**
    * Get work duration
    */
   public function getWorkDurationAttribute(): ?string
   {
      if (!$this->tanggal_masuk) {
         return null;
      }

      $diff = $this->tanggal_masuk->diff(now());
      $years = $diff->y;
      $months = $diff->m;

      if ($years > 0) {
         return $years . ' tahun ' . $months . ' bulan';
      }
      return $months . ' bulan';
   }

   /**
    * Boot method untuk event handling
    */
   protected static function boot()
   {
      parent::boot();

      // Update jumlah karyawan di cabang setelah create/update/delete
      static::created(function ($karyawan) {
         if ($karyawan->cabang) {
            $karyawan->cabang->updateJumlahKaryawan();
         }
      });

      static::updated(function ($karyawan) {
         if ($karyawan->cabang) {
            $karyawan->cabang->updateJumlahKaryawan();
         }
         // Jika cabang berubah, update cabang lama juga
         if ($karyawan->isDirty('cabang_id') && $karyawan->getOriginal('cabang_id')) {
            $oldCabang = Cabang::find($karyawan->getOriginal('cabang_id'));
            if ($oldCabang) {
               $oldCabang->updateJumlahKaryawan();
            }
         }
      });

      static::deleted(function ($karyawan) {
         if ($karyawan->cabang) {
            $karyawan->cabang->updateJumlahKaryawan();
         }
      });
   }
}
