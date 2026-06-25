<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeederDosen extends Model
{
    protected $fillable = [
        'nidn', 'nama', 'jenis_kelamin', 'pendidikan_terakhir',
        'jabatan_akademik', 'status_ketenagaan', 'bidang_keahlian',
        'prodi_kode', 'synced_at',
    ];

    protected $casts = [
        'synced_at' => 'datetime',
    ];

    public function scopeTetap($query)
    {
        return $query->where('status_ketenagaan', 'Tetap');
    }

    public function scopeTidakTetap($query)
    {
        return $query->where('status_ketenagaan', 'Tidak Tetap');
    }
}
