<?php

namespace App\Models;

use App\Traits\HasHashId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Informasi extends Model
{
    use HasHashId;

    protected $table = 'informasis';

    protected $fillable = [
        'kode_informasi',
        'judul',
        'deskripsi',
        'lampiran',
        'created_by',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relasi ke user yang membuat informasi
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Generate kode informasi otomatis
     */
    public static function generateKode(): string
    {
        $date = now()->format('Ymd');
        $prefix = "INF-{$date}-";

        $lastInfo = self::where('kode_informasi', 'like', "{$prefix}%")
            ->orderBy('kode_informasi', 'desc')
            ->first();

        if ($lastInfo) {
            $lastNumber = (int) substr($lastInfo->kode_informasi, -3);
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '001';
        }

        return $prefix . $newNumber;
    }

    /**
     * Boot method untuk auto generate kode
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->kode_informasi)) {
                $model->kode_informasi = self::generateKode();
            }
        });
    }
}
