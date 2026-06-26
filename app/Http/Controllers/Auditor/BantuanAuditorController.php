<?php

namespace App\Http\Controllers\Auditor;

use App\Http\Controllers\Controller;

class BantuanAuditorController extends Controller
{
    public function index()
    {
        return view('pages.auditor.bantuan.index');
    }
}
