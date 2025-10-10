<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StandarCapaian extends Model
{
    use HasFactory;

    protected $fillable = [
        'capaian_kode',
        'bukti_standar_id',
        'indikator_id',
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

    public function standarCapaiansBanptS1()
    {
        return $this->belongsTo(StandarElemenBanptS1::class, 'indikator_id', 'indikator_id');
    }

    public function standarTarget()
    {
        return $this->belongsTo(standarTarget::class, 'indikator_id', 'indikator_id');
    }
}
