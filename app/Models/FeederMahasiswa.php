<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeederMahasiswa extends Model
{
    protected $fillable = [
        'nim', 'nama', 'jenis_kelamin', 'angkatan',
        'semester_aktif', 'status', 'ipk', 'prodi_kode', 'synced_at',
    ];

    protected $casts = [
        'angkatan'    => 'integer',
        'ipk'         => 'float',
        'synced_at'   => 'datetime',
    ];

    public function scopeAktif($query)
    {
        return $query->where('status', 'Aktif');
    }

    public function scopeAngkatan($query, int $year)
    {
        return $query->where('angkatan', $year);
    }
}
