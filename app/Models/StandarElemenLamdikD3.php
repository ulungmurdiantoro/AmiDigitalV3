<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StandarElemenLamdikD3 extends Model
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

    public function standarTargetsLamdikD3()
    {
        return $this->hasMany(StandarTarget::class, 'indikator_id', 'indikator_id');
    }

    public function standarCapaiansLamdikD3()
    {
        return $this->hasMany(StandarCapaian::class, 'indikator_id', 'indikator_id');
    }

    public function standarNilaisLamdikD3()
    {
        return $this->hasOne(StandarNilai::class, 'indikator_id', 'indikator_id');
    }

    public function standarNilaisNotSesuaiLamdikD3()
    {
        return $this->hasOne(StandarNilai::class, 'indikator_id', 'indikator_id')
                    ->where('jenis_temuan', '!=', 'Sesuai');
    }
}
