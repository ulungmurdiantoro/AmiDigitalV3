<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramStudi extends Model
{
    use HasFactory;

    protected $fillable = [
        'program_studis_code',
        'prodi_nama',
        'prodi_jenjang',
        'prodi_jurusan',
        'prodi_fakultas',
        'prodi_akreditasi',
        'akreditasi_kadaluarsa',
        'akreditasi_bukti',
        'feeder_kode_prodi',
    ];

    public function feederMahasiswas()
    {
        return $this->hasMany(FeederMahasiswa::class, 'prodi_kode', 'feeder_kode_prodi');
    }

    public function feederDosens()
    {
        return $this->hasMany(FeederDosen::class, 'prodi_kode', 'feeder_kode_prodi');
    }

    public function feederKelulusans()
    {
        return $this->hasMany(FeederKelulusan::class, 'prodi_kode', 'feeder_kode_prodi');
    }
}
