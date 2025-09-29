<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StandarAkreditasi extends Model
{
    use HasFactory;

    public function standards() {
        return $this->hasMany(Standard::class);
    }
}

