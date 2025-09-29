<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Element extends Model
{
    use HasFactory;

    protected $fillable = [
        'standard_id',
        'nama',
    ];

    public function indicators() {
        return $this->hasMany(Indikator::class, 'elemen_id');
    }

    public function standard() {
        return $this->belongsTo(Standard::class);
    }
}
