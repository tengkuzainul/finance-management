<?php

namespace App\Models;

use App\Traits\HasHashId;
use Illuminate\Database\Eloquent\Model;

class Pengaturan extends Model
{
    use HasHashId;

    protected $table = 'pengaturans';

    protected $fillable = [
        'kode',
        'nama',
        'nilai',
        'tipe',
        'deskripsi',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get setting value by kode
     */
    public static function getValue(string $kode, $default = null)
    {
        $setting = self::where('kode', $kode)->where('is_active', true)->first();

        if (!$setting) {
            return $default;
        }

        return match ($setting->tipe) {
            'number' => (float) $setting->nilai,
            'boolean' => filter_var($setting->nilai, FILTER_VALIDATE_BOOLEAN),
            'json' => json_decode($setting->nilai, true),
            default => $setting->nilai,
        };
    }

    /**
     * Set setting value by kode
     */
    public static function setValue(string $kode, $nilai): bool
    {
        $setting = self::where('kode', $kode)->first();

        if (!$setting) {
            return false;
        }

        $setting->nilai = is_array($nilai) ? json_encode($nilai) : $nilai;
        return $setting->save();
    }
}
