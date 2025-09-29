<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StandarTarget;
use App\Models\DokumenTipe;

class KelolaTargetController extends Controller
{
    public function index(Request $request)
    {
        // Retrieve data from the request
        $indikatorKode = $request->input('indikator_id');
        $elemenNama = $request->input('elemen_nama');
        $indikatorNama = $request->input('indikator_nama');

        $standarTarget = StandarTarget::when(request()->q, function($standarTarget) {
            $standarTarget = $standarTarget->where('id', 'like', '%'. request()->q . '%');
        })->latest()->paginate(10);
        dd([ 'indikator_id' => $indikatorKode, 'elemen_nama' => $elemenNama, 'indikator_nama' => $indikatorNama, 'standarTarget' => $standarTarget, ]);
        // Pass the data to the view
        return view('pages.admin.kriteria-dokumen.kelola-target.index', [
            'indikator_id' => $indikatorKode,
            'elemen_nama' => $elemenNama,
            'indikator_nama' => $indikatorNama,
            'standarTarget' => $standarTarget,
        ]);
    }

    public function create()
    {
        $dokumenTipes = DokumenTipe::all();
        return view('pages.admin.kriteria-dokumen.kelola-target.create', [
            'dokumenTipes' => $dokumenTipes,
        ]);
    }
}
