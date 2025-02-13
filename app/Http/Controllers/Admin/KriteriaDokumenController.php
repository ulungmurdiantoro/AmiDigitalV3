<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StandarElemenBanptD3;
use App\Models\StandarElemenBanptS1;
use App\Models\StandarElemenBanptS2;
use App\Models\StandarElemenBanptS3;
use App\Models\StandarElemenBanptTerapanS1;
use App\Models\StandarElemenBanptTerapanS2;
use App\Models\StandarElemenBanptTerapanS3;
use App\Models\StandarElemenLamdikD3;
use App\Models\StandarElemenLamdikS1;
use App\Models\StandarElemenLamdikS2;
use App\Models\StandarElemenLamdikS3;
use App\Models\StandarElemenLamdikTerapanS1;
use App\Models\StandarElemenLamdikTerapanS2;
use App\Models\StandarElemenLamdikTerapanS3;
use App\Models\StandarTarget;
use App\Models\DokumenTipe;
use App\Imports\StandarBanptD3Import;
use App\Imports\StandarBanptS1Import;
use App\Imports\StandarBanptS2Import;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class KriteriaDokumenController extends Controller
{
    public function index(Request $request)
    {
        $degree = $request->get('degree', 'BAN-PT S1'); // Default to 'S1' if not specified

        $standar_names = [
            'Kondisi Eksternal',
            'Profil Unit Pengelola Program Studi',
            '1. Visi, Misi, Tujuan dan Strategi',
            '2. Tata Pamong dan Kerjasama',
            '3. Mahasiswa',
            '4. Sumber Daya Manusia',
            '5. Keuangan, Sarana dan Prasarana',
            '6. Pendidikan',
            '7. Penelitian',
            '8. Pengabdian Kepada Masyarakat',
            '9. Luaran dan Capaian Tridharma',
            'Analisis dan Penetapan Program Pengembangan'
        ];

        switch ($degree) {
            case 'D3':
                $modelClass = \App\Models\StandarElemenBanptD3::class;
                $standarTargetsRelation = 'standarTargetsD3';
                $standarCapaiansRelation = 'standarCapaiansD3';
                break;
            case 'S1':
                $modelClass = \App\Models\StandarElemenBanptS1::class;
                $standarTargetsRelation = 'standarTargetsS1';
                $standarCapaiansRelation = 'standarCapaiansS1';
                break;
            default:
                $modelClass = \App\Models\StandarElemenBanptS1::class;
                $standarTargetsRelation = 'standarTargetsS1';
                $standarCapaiansRelation = 'standarCapaiansS1';
        }

        $data_standar = [];
        foreach ($standar_names as $index => $name) {
            $data_standar['data_standar_k' . ($index + 1)] = $modelClass::with($standarTargetsRelation, $standarCapaiansRelation)
                ->when($request->q, function ($query) use ($request) {
                    $query->where('elemen_nama', 'like', '%' . $request->q . '%');
                })
                ->where('standar_nama', $name)
                ->latest()
                ->paginate(30)
                ->appends(['q' => $request->q]);
        }

        return view('pages.admin.kriteria-dokumen.index', [
            'nama_data_standar' => $standar_names,
            'data_standar' => $data_standar,
            'degree' => $degree
        ]);
    }

    public function create(Request $request)
    {
        $degree = $request->degree; // Get the degree from the request
        return view('pages.admin.kriteria-dokumen.create', [
            'degree' => $degree
        ]);
    }

    public function import()
    {
        
        return view('pages.admin.kriteria-dokumen.import');
    }

    public function storeImport(Request $request)
    {
        $request->validate([
            'nama_dokumen' => 'required|mimes:csv,xls,xlsx'
        ]);

        // Get the degree from the request
        $degree = $request->input('degree');

        // Select the appropriate import class based on the degree
        switch ($degree) {
            case 'D3':
                $importClass = new StandarBanptD3Import();
                break;
            case 'S1':
                $importClass = new StandarBanptS1Import();
                break;
            case 'S2':
                $importClass = new StandarBanptS2Import();
                break;
            // Add other cases as needed
            default:
                return redirect()->route('admin.kriteria-dokumen.index')->with('error', 'Invalid degree selected.');
        }

        try {
            // Import data using the selected import class
            Excel::import($importClass, $request->file('nama_dokumen'));

            return redirect()->route('admin.kriteria-dokumen.index')->with('success', 'File imported successfully.');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->route('admin.kriteria-dokumen.index')->with('error', 'There was an issue importing the file.');
        }
    }

    public function kelolaTarget(Request $request, $importTitle, $indikator_kode)
    {
        $importTitle = urldecode($importTitle); // Decode the importTitle
    
        $standarElemen = StandarElemenBanptS1::where('indikator_kode', $indikator_kode)->firstOrFail();
    
        $standarTarget = StandarTarget::where('indikator_kode', $indikator_kode)->when($request->q, function ($query, $q) {
            $query->where('id', 'like', "%{$q}%");
        })->latest()->paginate(10);
    
        return view('pages.admin.kriteria-dokumen.kelola-target.index', [
            'indikator_kode' => $indikator_kode,
            'standarTarget' => $standarTarget,
            'standarElemen' => $standarElemen,
            'importTitle' => $importTitle,
        ]);
    }
    

    public function kelolaTargetCreate($importTitle, $indikator_kode)
    {
        $importTitle = urldecode($importTitle); // Decode the importTitle

        $dokumenTipes = DokumenTipe::all();
        $standarElemen = StandarElemenBanptS1::where('indikator_kode', $indikator_kode)->firstOrFail();

        return view('pages.admin.kriteria-dokumen.kelola-target.create', [
            'indikator_kode' => $indikator_kode,
            'standarElemen' => $standarElemen,
            'dokumenTipes' => $dokumenTipes,
            'importTitle' => $importTitle,
        ]);
    }

    public function kelolaTargetStore(Request $request)
    {
        $request->validate([ 
            'dokumen_nama' => 'required|string|max:255',
            'pertanyaan_nama' => 'required|string|max:255',
            'dokumen_tipe' => 'required|string|max:255',
            'indikator_kode' => 'required|string|max:255',
            'importTitle' => 'required|string|max:255',
            'dokumen_keterangan' => 'nullable|string',
        ]); 

        try {
            StandarTarget::create([
                'target_kode' => 'tgr-' . Str::uuid() . uniqid(),
                'indikator_kode' => $request->indikator_kode,
                'jenjang' => $request->importTitle,
                'dokumen_nama' => $request->dokumen_nama,
                'pertanyaan_nama' => $request->pertanyaan_nama,
                'dokumen_tipe' => $request->dokumen_tipe,
                'dokumen_keterangan' => $request->dokumen_keterangan,
            ]);
        } catch (\Exception $e) {
            Log::error('Database insertion failed: ' . $e->getMessage());
            return back()->withErrors(['database' => 'Failed to save data. Please try again.']);
        }

        return redirect()->route('admin.kriteria-dokumen.kelola-target', ['importTitle' => $request->importTitle, 'indikator_kode' => $request->indikator_kode])
        ->with([
            'success' => 'Tipe Dokumen created successfully.',
        ]);    
    }

    public function kelolaTargetEdit($indikator_kode)
    {
        $dokumenTipes = DokumenTipe::all();
        
        $standarElemen = StandarElemenBanptS1::where('indikator_kode', $indikator_kode)->firstOrFail();

        $standarTarget = StandarTarget::where('indikator_kode', $indikator_kode)->firstOrFail();

        return view('pages.admin.kriteria-dokumen.kelola-target.edit', [
            'indikator_kode' => $indikator_kode,
            'standarTarget' => $standarTarget,
            'standarElemen' => $standarElemen,
            'dokumenTipes' => $dokumenTipes,
        ]);    
    }

    public function kelolaTargetUpdate(Request $request, $id)
    {
        $validated = $request->validate([
            'dokumen_nama' => 'required|string|max:255',
            'pertanyaan_nama' => 'required|string|max:255',
            'dokumen_tipe' => 'required|string|max:255',
            'dokumen_keterangan' => 'nullable|string',
        ]);

        $standarTarget = StandarTarget::findOrFail($id);

        $standarTarget->update($validated);

        return redirect()->route('admin.kriteria-dokumen.kelola-target', ['indikator_kode' => $request->indikator_kode])
        ->with([
            'success' => 'Tipe Dokumen created successfully.',
        ]);    
    }

    public function kelolaTargetDestroy(Request $request, $id)
    {
        try {
            $standarTarget = StandarTarget::findOrFail($id);
            $standarTarget->delete();
            
            return redirect()->route('admin.kriteria-dokumen.kelola-target', ['indikator_kode' => $request->indikator_kode])
                ->with('success', 'Target deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.kriteria-dokumen.kelola-target', ['indikator_kode' => $request->indikator_kode])
                ->with('error', 'Failed to delete target.');
        }
    }

    public function tipeDokumenStore (Request $request)
    {
        $request->validate([
            'tipe_nama' => 'required|string|max:255',
            'indikator_kode' => 'required|string',
        ]);

        $tipeDokumen = new DokumenTipe();
        $tipeDokumen->tipe_nama = $request->tipe_nama;

        $tipeDokumen->save();

        return redirect()->route('admin.kriteria-dokumen.kelola-target.create', ['indikator_kode' => $request->indikator_kode])
            ->with([
                'success' => 'Tipe Dokumen created successfully.',
            ]);
    }

    public function tipeDokumenDestroy(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:dokumen_tipes,id',
        ]);

        try {
            DokumenTipe::destroy($request->id);
        } catch (\Exception $e) {
            Log::error('Database deletion failed: ' . $e->getMessage());
            return back()->withErrors(['database' => 'Failed to delete data. Please try again.']);
        }

        return back()->with('success', 'Document type successfully deleted!');
    }

}
