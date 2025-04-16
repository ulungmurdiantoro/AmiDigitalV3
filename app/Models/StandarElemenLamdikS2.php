<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StandarElemenLamdikS2 extends Model
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

    public function standarTargetsLamdikS2()
    {
        return $this->hasMany(StandarTarget::class, 'indikator_kode', 'indikator_kode');
    }

    public function standarCapaiansLamdikS2()
    {
        return $this->hasMany(StandarCapaian::class, 'indikator_kode', 'indikator_kode');
    }

    public function standarNilaisLamdikS2()
    {
        return $this->hasOne(StandarNilai::class, 'indikator_kode', 'indikator_kode');
    }

    public function standarNilaisNotSesuaiLamdikS2()
    {
        return $this->hasOne(StandarNilai::class, 'indikator_kode', 'indikator_kode')
                    ->where('jenis_temuan', '!=', 'Sesuai');
    }
}
