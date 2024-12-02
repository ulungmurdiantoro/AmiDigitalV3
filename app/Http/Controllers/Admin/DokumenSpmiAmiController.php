<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KategoriDokumen;
use App\Models\DokumenSpmiAmi;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DokumenSpmiAmiController extends Controller
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

        return view('pages.admin.dokumen-spmi-ami.index', [
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
        $KategoriDokumens = KategoriDokumen::all();
        return view('pages.admin.dokumen-spmi-ami.create', [
            'KategoriDokumens' => $KategoriDokumens,
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
        // Validate request
        $request->validate([
            'kategori_dokumen' => 'required|string',
            'nama_dokumen' => 'required|string',
            'file_spmi_ami' => 'mimes:jpg,png,pdf,doc,docx,xlsx,xlsm,pptx|max:2048',
        ]);

        try {
            $fileName = time() . '.' . $request->file_spmi_ami->extension();
            $filePath = $request->file('file_spmi_ami')->storeAs('uploads/spmi_ami', $fileName, 'public');
        } catch (\Exception $e) {
            Log::error('File upload failed: ' . $e->getMessage());
            return back()->withErrors(['file_spmi_ami' => 'File upload failed. Please try again.']);
        }

        try {
            DokumenSpmiAmi::create([
                'dokumen_kode' => 'spmi-ami-' .Str::uuid() . uniqid(),
                'kategori_dokumen' => $request->kategori_dokumen,
                'nama_dokumen' => $request->nama_dokumen,
                'file_spmi_ami' => '/storage/' . $filePath,
            ]);
        } catch (\Exception $e) {
            Log::error('Database insertion failed: ' . $e->getMessage());
            return back()->withErrors(['database' => 'Failed to save data. Please try again.']);
        }

        // Redirect
        return redirect()->route('admin.dokumen-spmi-ami.index');
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
        try {
            $dokumenSpmiAmi = DokumenSpmiAmi::findOrFail($id);
            $dokumenSpmiAmi->delete();
            
            return redirect()->route('admin.dokumen-spmi-ami.index')->with('success', 'Penjadwalan AMI berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('admin.dokumen-spmi-ami.index')->with('error', 'Terjadi kesalahan saat menghapus Penjadwalan AMI.');
        }
    }
}
