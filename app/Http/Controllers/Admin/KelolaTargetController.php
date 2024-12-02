<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StandarTarget;
use App\Models\DokumenTipe;

class KelolaTargetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Retrieve data from the request
        $indikatorKode = $request->input('indikator_kode');
        $elemenNama = $request->input('elemen_nama');
        $indikatorNama = $request->input('indikator_nama');

        $standarTarget = StandarTarget::when(request()->q, function($standarTarget) {
            $standarTarget = $standarTarget->where('id', 'like', '%'. request()->q . '%');
        })->latest()->paginate(10);
        dd([ 'indikator_kode' => $indikatorKode, 'elemen_nama' => $elemenNama, 'indikator_nama' => $indikatorNama, 'standarTarget' => $standarTarget, ]);
        // Pass the data to the view
        return view('pages.admin.kriteria-dokumen.kelola-target.index', [
            'indikator_kode' => $indikatorKode,
            'elemen_nama' => $elemenNama,
            'indikator_nama' => $indikatorNama,
            'standarTarget' => $standarTarget,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $dokumenTipes = DokumenTipe::all();
        return view('pages.admin.kriteria-dokumen.kelola-target.create', [
            'dokumenTipes' => $dokumenTipes,
        ]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
