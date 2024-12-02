<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DokumenSpmiAmi extends Model
{
    use HasFactory;

    protected $fillable = [
        'dokumen_kode',
        'kategori_dokumen',
        'nama_dokumen',
        'file_spmi_ami',
    ];
}
