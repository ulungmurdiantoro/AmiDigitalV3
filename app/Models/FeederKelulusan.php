<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeederKelulusan extends Model
{
    protected $fillable = [
        'nim', 'nama', 'angkatan', 'tahun_lulus',
        'semester_ke', 'ipk_lulus', 'prodi_kode', 'synced_at',
    ];

    protected $casts = [
        'angkatan'    => 'integer',
        'tahun_lulus' => 'integer',
        'semester_ke' => 'integer',
        'ipk_lulus'   => 'float',
        'synced_at'   => 'datetime',
    ];

    // Lulus tepat waktu S1 = <= 8 semester
    public function scopeTepeatWaktu($query, int $maks = 8)
    {
        return $query->where('semester_ke', '<=', $maks);
    }
}
