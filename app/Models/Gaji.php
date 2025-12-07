<?php

namespace App\Models;

use App\Traits\HasHashId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Gaji extends Model
{
    use HasHashId;

    protected $table = 'gajis';

    protected $fillable = [
        'karyawan_id',
        'cabang_id',
        'tanggal',
        'total_pemasukan',
        'persen_gaji',
        'nominal_gaji',
        'jumlah_transaksi',
        'status',
        'catatan',
        'approved_by',
        'paid_at',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'total_pemasukan' => 'decimal:2',
        'persen_gaji' => 'decimal:2',
        'nominal_gaji' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_PAID = 'paid';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * Relasi ke karyawan
     */
    public function karyawan(): BelongsTo
    {
        return $this->belongsTo(Karyawan::class);
    }

    /**
     * Relasi ke cabang
     */
    public function cabang(): BelongsTo
    {
        return $this->belongsTo(Cabang::class);
    }

    /**
     * Relasi ke user yang approve/bayar
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Hitung gaji dari pemasukan dan persentase
     */
    public static function calculateGaji(float $totalPemasukan, float $persenGaji): float
    {
        return $totalPemasukan * ($persenGaji / 100);
    }

    /**
     * Get status badge color
     */
    public function getStatusBadgeAttribute(): array
    {
        return match ($this->status) {
            self::STATUS_PENDING => ['color' => 'yellow', 'text' => 'Pending'],
            self::STATUS_PAID => ['color' => 'green', 'text' => 'Dibayar'],
            self::STATUS_CANCELLED => ['color' => 'red', 'text' => 'Dibatalkan'],
            default => ['color' => 'gray', 'text' => 'Unknown'],
        };
    }
}
