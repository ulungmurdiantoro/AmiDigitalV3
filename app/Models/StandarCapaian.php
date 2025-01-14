<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StandarCapaian extends Model
{
    use HasFactory;

    protected $fillable = [
        'capaian_kode',
        'indikator_kode',
        'dokumen_nama',
        'pertanyaan_nama',
        'dokumen_tipe',
        'dokumen_keterangan',
        'dokumen_file',
        'dokumen_kadaluarsa',
        'informasi',
        'periode',
        'prodi',
    ];

    public function standarCapaiansS1()
    {
        return $this->belongsTo(StandarElemenBanptS1::class, 'indikator_kode', 'indikator_kode');
    }

    public function standarTarget()
    {
        return $this->belongsTo(standarTarget::class, 'indikator_kode', 'indikator_kode');
    }
}
