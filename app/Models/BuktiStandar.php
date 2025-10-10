<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuktiStandar extends Model
{
    use HasFactory;

    protected $fillable = [
        'standard_id',
        'nama',
        'deskripsi',

    ];


    public function standard() {
        return $this->belongsTo(Standard::class);
    }

    public function dokumenCapaian()
{
    return $this->hasMany(StandarCapaian::class, 'bukti_standar_id');
}

}
