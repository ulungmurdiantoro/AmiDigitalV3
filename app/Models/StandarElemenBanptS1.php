<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StandarElemenBanptS1 extends Model
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

    public function standarTargetsBanptS1()
    {
        return $this->hasMany(StandarTarget::class, 'indikator_id', 'indikator_id');
    }

    public function standarCapaiansBanptS1()
    {
        return $this->hasMany(StandarCapaian::class, 'indikator_id', 'indikator_id');
    }

    public function standarNilaisBanptS1()
    {
        return $this->hasOne(StandarNilai::class, 'indikator_id', 'indikator_id');
    }

    public function standarNilaisNotSesuaiBanptS1()
    {
        return $this->hasOne(StandarNilai::class, 'indikator_id', 'indikator_id')
                    ->where('jenis_temuan', '!=', 'Sesuai');
    }

}
