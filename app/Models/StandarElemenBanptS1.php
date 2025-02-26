<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StandarElemenBanptS1 extends Model
{
    use HasFactory;

    protected $fillable = [
        'indikator_kode',
        'standar_nama',
        'elemen_nama',
        'indikator_nama',
        'indikator_info',
        'indikator_lkps',
        'indikator_bobot',
    ];

    public function standarTargetsBanptS1()
    {
        return $this->hasMany(StandarTarget::class, 'indikator_kode', 'indikator_kode');
    }

    public function standarCapaiansBanptS1()
    {
        return $this->hasMany(StandarCapaian::class, 'indikator_kode', 'indikator_kode');
    }

    public function standarNilaisBanptS1()
    {
        return $this->hasOne(StandarNilai::class, 'indikator_kode', 'indikator_kode');
    }

}
