<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Indikator extends Model
{
    use HasFactory;

    protected $fillable = [
        'elemen_id',
        'nama_indikator',
        'kategori',
        'info',
        'lkps',
        'bobot',
    ];

    public function dokumen_targets()
    {
        return $this->hasMany(StandarTarget::class, 'indikator_id'); 
    }

    public function dokumen_capaians()
    {
        return $this->hasMany(StandarCapaian::class, 'indikator_id');
    }

    // In Indikator.php
    public function element() {
        return $this->belongsTo(Element::class, 'elemen_id', 'id');
    }

}
