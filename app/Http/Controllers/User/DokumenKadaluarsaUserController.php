<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StandarCapaian;
use App\Imports\StandarBanptS1Import;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DokumenKadaluarsaUserController extends Controller
{
    public function index()
    {
        $activity = 'Kadaluarsa';

        $DokumenSpmiAmis = StandarCapaian::with('Indikator')
            ->when(request('q'), function($query, $q) {
                $query->where('dokumen_nama', 'like', "%{$q}%");
            })
            ->whereDate('dokumen_kadaluarsa', '<', now())
            ->where('prodi', session('user_penempatan')) // filter sesuai session
            ->latest()
            ->paginate(10)
            ->appends(['q' => request('q')]);

        return view('pages.user.dokumen-aktif.index', compact('DokumenSpmiAmis', 'activity'));
    }
}
