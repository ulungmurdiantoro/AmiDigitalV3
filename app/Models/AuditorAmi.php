<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditorAmi extends Model
{
    use HasFactory;

    protected $fillable = [
        'auditor_kode',
        'users_kode',
        'tim_ami',
    ];

    public function user() { 
        return $this->belongsTo(User::class, 'user_code', 'users_code'); 
    }
}
