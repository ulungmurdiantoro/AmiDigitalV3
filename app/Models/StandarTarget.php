<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StandarTarget extends Model
{
    use HasFactory;

    protected $fillable = [
        'jenjang',
        'target_kode',
        'indikator_kode',
        'pertanyaan_nama',
        'dokumen_nama',
        'dokumen_tipe',
        'dokumen_keterangan',
    ];
}
