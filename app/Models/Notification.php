<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Notification extends Model
{
   use HasFactory;

   protected $fillable = [
      'user_id',
      'from_user_id',
      'type',
      'title',
      'message',
      'link',
      'notifiable_type',
      'notifiable_id',
      'read_at',
   ];

   protected $casts = [
      'read_at' => 'datetime',
   ];

   // Tipe notifikasi
   const TYPE_LAPORAN_BARU = 'laporan_baru';
   const TYPE_LAPORAN_APPROVED = 'laporan_approved';
   const TYPE_LAPORAN_REJECTED = 'laporan_rejected';
   const TYPE_LAPORAN_PENDING = 'laporan_pending';
   const TYPE_INFORMASI = 'informasi';

   /**
    * User penerima notifikasi
    */
   public function user(): BelongsTo
   {
      return $this->belongsTo(User::class);
   }

   /**
    * User pengirim/trigger notifikasi
    */
   public function fromUser(): BelongsTo
   {
      return $this->belongsTo(User::class, 'from_user_id');
   }

   /**
    * Model terkait (polymorphic)
    */
   public function notifiable(): MorphTo
   {
      return $this->morphTo();
   }

   /**
    * Scope untuk notifikasi yang belum dibaca
    */
   public function scopeUnread($query)
   {
      return $query->whereNull('read_at');
   }

   /**
    * Scope untuk notifikasi yang sudah dibaca
    */
   public function scopeRead($query)
   {
      return $query->whereNotNull('read_at');
   }

   /**
    * Tandai sebagai sudah dibaca
    */
   public function markAsRead(): void
   {
      if (is_null($this->read_at)) {
         $this->update(['read_at' => now()]);
      }
   }

   /**
    * Cek apakah sudah dibaca
    */
   public function isRead(): bool
   {
      return !is_null($this->read_at);
   }

   /**
    * Buat notifikasi untuk admin (laporan baru dari karyawan)
    */
   public static function notifyAdminsNewLaporan(LaporanKeuangan $laporan): void
   {
      $admins = User::where('is_admin', true)->where('is_active', true)->get();
      $creator = $laporan->creator;
      $karyawan = $laporan->karyawan;

      foreach ($admins as $admin) {
         self::create([
            'user_id' => $admin->id,
            'from_user_id' => $creator?->id,
            'type' => self::TYPE_LAPORAN_PENDING,
            'title' => 'Laporan Baru Menunggu Approval',
            'message' => ($karyawan?->nama_lengkap ?? $creator?->name ?? 'Karyawan') . ' mengajukan laporan ' . strtolower($laporan->jenis) . ' sebesar Rp ' . number_format($laporan->jumlah, 0, ',', '.'),
            'link' => route('master-data.laporan-keuangan.show', $laporan->hash_id),
            'notifiable_type' => LaporanKeuangan::class,
            'notifiable_id' => $laporan->id,
         ]);
      }
   }

   /**
    * Buat notifikasi untuk karyawan (laporan diapprove/ditolak)
    */
   public static function notifyKaryawanLaporanStatus(LaporanKeuangan $laporan, string $status): void
   {
      // Notify ke creator laporan
      $creator = $laporan->creator;
      if (!$creator) return;

      $approver = $laporan->approver;
      $type = $status === 'Approved' ? self::TYPE_LAPORAN_APPROVED : self::TYPE_LAPORAN_REJECTED;
      $statusText = $status === 'Approved' ? 'disetujui' : 'ditolak';

      // Link ke riwayat laporan untuk karyawan
      $link = $creator->is_admin
         ? route('master-data.laporan-keuangan.show', $laporan->hash_id)
         : route('karyawan.laporan.riwayat');

      self::create([
         'user_id' => $creator->id,
         'from_user_id' => $approver?->id,
         'type' => $type,
         'title' => 'Laporan ' . ucfirst($statusText),
         'message' => 'Laporan ' . strtolower($laporan->jenis) . ' Anda sebesar Rp ' . number_format($laporan->jumlah, 0, ',', '.') . ' telah ' . $statusText . ' oleh ' . ($approver?->name ?? 'Admin'),
         'link' => $link,
         'notifiable_type' => LaporanKeuangan::class,
         'notifiable_id' => $laporan->id,
      ]);
   }

   /**
    * Get icon berdasarkan type
    */
   public function getIconAttribute(): string
   {
      return match ($this->type) {
         self::TYPE_LAPORAN_BARU, self::TYPE_LAPORAN_PENDING => 'fa-file-invoice-dollar text-blue-500',
         self::TYPE_LAPORAN_APPROVED => 'fa-check-circle text-green-500',
         self::TYPE_LAPORAN_REJECTED => 'fa-times-circle text-red-500',
         default => 'fa-bell text-gray-500',
      };
   }

   /**
    * Get background color berdasarkan type
    */
   public function getBgColorAttribute(): string
   {
      return match ($this->type) {
         self::TYPE_LAPORAN_BARU, self::TYPE_LAPORAN_PENDING => 'bg-blue-100',
         self::TYPE_LAPORAN_APPROVED => 'bg-green-100',
         self::TYPE_LAPORAN_REJECTED => 'bg-red-100',
         default => 'bg-gray-100',
      };
   }
}
