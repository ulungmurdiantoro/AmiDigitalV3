<?php

namespace App\Http\Controllers\Auditor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KategoriDokumen;
use App\Models\DokumenSpmiAmi;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DokumenSpmiAmiauditorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $DokumenSpmiAmis = DokumenSpmiAmi::when(request()->q, function($DokumenSpmiAmis) {
            $DokumenSpmiAmis = $DokumenSpmiAmis->where('nama_dokumen', 'like', '%'. request()->q . '%');
        })->latest()->paginate(10);

        //append query string to pagination links
        $DokumenSpmiAmis->appends(['q' => request()->q]);

        return view('pages.auditor.dokumen-spmi-ami.index', [
            'DokumenSpmiAmis' => $DokumenSpmiAmis,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
