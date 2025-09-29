<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StandarElemenLamdikS2 extends Model
{
    use HasFactory;

    protected $fillable = [
        'indikator_id',
        'standar_nama',
        'elemen_nama',
        'indikator_nama',
        'indikator_info',
        'indikator_lkps',
        'indikator_bobot',
    ];

    public function standarTargetsLamdikS2()
    {
        return $this->hasMany(StandarTarget::class, 'indikator_id', 'indikator_id');
    }

    public function standarCapaiansLamdikS2()
    {
        return $this->hasMany(StandarCapaian::class, 'indikator_id', 'indikator_id');
    }

    public function standarNilaisLamdikS2()
    {
        return $this->hasOne(StandarNilai::class, 'indikator_id', 'indikator_id');
    }

    public function standarNilaisNotSesuaiLamdikS2()
    {
        return $this->hasOne(StandarNilai::class, 'indikator_id', 'indikator_id')
                    ->where('jenis_temuan', '!=', 'Sesuai');
    }
}
