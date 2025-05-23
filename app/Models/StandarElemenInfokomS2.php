<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StandarElemenInfokomS2 extends Model
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

    public function standarTargets()
    {
        return $this->hasMany(StandarTarget::class, 'indikator_kode', 'indikator_kode');
    }
}
