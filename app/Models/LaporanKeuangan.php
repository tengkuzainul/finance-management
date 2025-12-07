<?php

namespace App\Models;

use App\Traits\HasHashId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaporanKeuangan extends Model
{
   use HasFactory, HasHashId;

   protected $table = 'laporan_keuangans';

   protected $fillable = [
      'cabang_id',
      'karyawan_id',
      'created_by',
      'tanggal',
      'jenis',
      'kategori',
      'keterangan',
      'jumlah',
      'bukti_transaksi',
      'catatan',
      'status',
      'approved_by',
      'approved_at',
   ];

   protected $casts = [
      'tanggal' => 'date',
      'jumlah' => 'decimal:2',
      'approved_at' => 'datetime',
   ];

   // Konstanta untuk jenis
   const JENIS_PEMASUKAN = 'Pemasukan';
   const JENIS_PENGELUARAN = 'Pengeluaran';

   // Konstanta untuk status
   const STATUS_DRAFT = 'Draft';
   const STATUS_PENDING = 'Pending';
   const STATUS_APPROVED = 'Approved';
   const STATUS_REJECTED = 'Rejected';

   // Konstanta untuk kategori pemasukan
   const KATEGORI_PEMASUKAN = [
      'Penjualan Tunai',
      'Penjualan Non-Tunai',
      'Pendapatan Lainnya',
   ];

   // Konstanta untuk kategori pengeluaran
   const KATEGORI_PENGELUARAN = [
      'Bahan Baku',
      'Gaji Karyawan',
      'Listrik & Air',
      'Sewa Tempat',
      'Operasional',
      'Pemeliharaan',
      'Marketing',
      'Lain-lain',
   ];

   /**
    * Get cabang dari laporan ini
    */
   public function cabang(): BelongsTo
   {
      return $this->belongsTo(Cabang::class, 'cabang_id');
   }

   /**
    * Get karyawan yang menginput laporan
    */
   public function karyawan(): BelongsTo
   {
      return $this->belongsTo(Karyawan::class, 'karyawan_id');
   }

   /**
    * Get user yang membuat laporan
    */
   public function creator(): BelongsTo
   {
      return $this->belongsTo(User::class, 'created_by');
   }

   /**
    * Get user yang meng-approve laporan
    */
   public function approver(): BelongsTo
   {
      return $this->belongsTo(User::class, 'approved_by');
   }

   /**
    * Scope untuk pemasukan
    */
   public function scopePemasukan($query)
   {
      return $query->where('jenis', self::JENIS_PEMASUKAN);
   }

   /**
    * Scope untuk pengeluaran
    */
   public function scopePengeluaran($query)
   {
      return $query->where('jenis', self::JENIS_PENGELUARAN);
   }

   /**
    * Scope untuk status approved
    */
   public function scopeApproved($query)
   {
      return $query->where('status', self::STATUS_APPROVED);
   }

   /**
    * Scope untuk status pending
    */
   public function scopePending($query)
   {
      return $query->where('status', self::STATUS_PENDING);
   }

   /**
    * Scope untuk status draft
    */
   public function scopeDraft($query)
   {
      return $query->where('status', self::STATUS_DRAFT);
   }

   /**
    * Scope untuk filter berdasarkan periode
    */
   public function scopePeriode($query, $startDate, $endDate)
   {
      return $query->whereBetween('tanggal', [$startDate, $endDate]);
   }

   /**
    * Scope untuk filter berdasarkan cabang
    */
   public function scopeByCabang($query, $cabangId)
   {
      return $query->where('cabang_id', $cabangId);
   }

   /**
    * Get formatted jumlah
    */
   public function getFormattedJumlahAttribute(): string
   {
      return 'Rp ' . number_format($this->jumlah, 0, ',', '.');
   }

   /**
    * Get formatted tanggal
    */
   public function getFormattedTanggalAttribute(): string
   {
      return $this->tanggal ? $this->tanggal->format('d/m/Y') : '-';
   }

   /**
    * Get status badge class
    */
   public function getStatusBadgeClassAttribute(): string
   {
      return match ($this->status) {
         self::STATUS_DRAFT => 'bg-gray-100 text-gray-800',
         self::STATUS_PENDING => 'bg-yellow-100 text-yellow-800',
         self::STATUS_APPROVED => 'bg-green-100 text-green-800',
         self::STATUS_REJECTED => 'bg-red-100 text-red-800',
         default => 'bg-gray-100 text-gray-800',
      };
   }

   /**
    * Get jenis badge class
    */
   public function getJenisBadgeClassAttribute(): string
   {
      return match ($this->jenis) {
         self::JENIS_PEMASUKAN => 'bg-green-100 text-green-800',
         self::JENIS_PENGELUARAN => 'bg-red-100 text-red-800',
         default => 'bg-gray-100 text-gray-800',
      };
   }

   /**
    * Check if laporan can be edited
    */
   public function canEdit(): bool
   {
      return in_array($this->status, [self::STATUS_DRAFT, self::STATUS_REJECTED]);
   }

   /**
    * Check if laporan can be approved
    */
   public function canApprove(): bool
   {
      return $this->status === self::STATUS_PENDING;
   }

   /**
    * Approve laporan
    */
   public function approve(int $approvedBy): bool
   {
      if (!$this->canApprove()) {
         return false;
      }

      $this->status = self::STATUS_APPROVED;
      $this->approved_by = $approvedBy;
      $this->approved_at = now();
      return $this->save();
   }

   /**
    * Reject laporan
    */
   public function reject(int $approvedBy, ?string $catatan = null): bool
   {
      if (!$this->canApprove()) {
         return false;
      }

      $this->status = self::STATUS_REJECTED;
      $this->approved_by = $approvedBy;
      $this->approved_at = now();
      if ($catatan) {
         $this->catatan = $catatan;
      }
      return $this->save();
   }

   /**
    * Submit for approval
    */
   public function submitForApproval(): bool
   {
      if ($this->status !== self::STATUS_DRAFT) {
         return false;
      }

      $this->status = self::STATUS_PENDING;
      return $this->save();
   }

   /**
    * Generate nomor laporan
    */
   public static function generateNomorLaporan(string $jenis): string
   {
      $prefix = $jenis === self::JENIS_PEMASUKAN ? 'IN' : 'OUT';
      $year = date('Y');
      $month = date('m');

      $lastLaporan = self::where('jenis', $jenis)
         ->whereYear('created_at', $year)
         ->whereMonth('created_at', $month)
         ->orderBy('id', 'desc')
         ->first();

      $lastNumber = $lastLaporan ? intval(substr($lastLaporan->id, -4)) + 1 : 1;
      return $prefix . $year . $month . str_pad($lastNumber, 4, '0', STR_PAD_LEFT);
   }

   /**
    * Get total pemasukan dalam periode
    */
   public static function getTotalPemasukan($startDate = null, $endDate = null, $cabangId = null): float
   {
      $query = self::pemasukan()->approved();

      if ($startDate && $endDate) {
         $query->periode($startDate, $endDate);
      }

      if ($cabangId) {
         $query->byCabang($cabangId);
      }

      return (float) $query->sum('jumlah');
   }

   /**
    * Get total pengeluaran dalam periode
    */
   public static function getTotalPengeluaran($startDate = null, $endDate = null, $cabangId = null): float
   {
      $query = self::pengeluaran()->approved();

      if ($startDate && $endDate) {
         $query->periode($startDate, $endDate);
      }

      if ($cabangId) {
         $query->byCabang($cabangId);
      }

      return (float) $query->sum('jumlah');
   }

   /**
    * Get saldo/profit dalam periode
    */
   public static function getSaldo($startDate = null, $endDate = null, $cabangId = null): float
   {
      return self::getTotalPemasukan($startDate, $endDate, $cabangId)
         - self::getTotalPengeluaran($startDate, $endDate, $cabangId);
   }
}
