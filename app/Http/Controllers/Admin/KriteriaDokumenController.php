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
use App\Imports\StandarLamdikPPGImport;
use App\Imports\StandarLamdikS1Import;
use App\Imports\StandarLamdikS2Import;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class KriteriaDokumenController extends Controller
{  
    public function index(Request $request)
    {
        $degree = $request->get('degree', 'BAN-PT S1');

        $standar_names_banpt = [
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

        $standar_names_lamdik = [
            'Visi Keilmuan',
            'Tata Pamong dan Tata Kelola',
            'Mahasiswa',
            'Dosen dan Tenaga Kependidikan',
            'Keuangan, Sarana dan Prasarana Pendidikan',
            'Pendidikan',
            'Pengabdian Kepada Masyarakat',
            'Penjaminan Mutu',
        ];

        $degreeMappings = [
            'BAN-PT D3' => [
                'modelClass' => StandarElemenBanptD3::class,
                'standarTargetsRelation' => 'standarTargetsD3',
                'standarCapaiansRelation' => 'standarCapaiansD3',
                'standarNames' => $standar_names_banpt,
            ],
            'BAN-PT S1' => [
                'modelClass' => StandarElemenBanptS1::class,
                'standarTargetsRelation' => 'standarTargetsBanptS1',
                'standarCapaiansRelation' => 'standarCapaiansBanptS1',
                'standarNames' => $standar_names_banpt,
            ],
            'BAN-PT S2' => [
                'modelClass' => StandarElemenBanptS2::class,
                'standarTargetsRelation' => 'standarTargetsBanptS2',
                'standarCapaiansRelation' => 'standarCapaiansBanptS2',
                'standarNames' => $standar_names_banpt,
            ],
            'LAMDIK S1' => [
                'modelClass' => StandarElemenLamdikS1::class,
                'standarTargetsRelation' => 'standarTargetsLamdikS1',
                'standarCapaiansRelation' => 'standarCapaiansLamdikS1',
                'standarNames' => $standar_names_lamdik,
            ],
            'LAMDIK PPG' => [
                'modelClass' => StandarElemenLamdikD3::class,
                'standarTargetsRelation' => 'standarTargetsLamdikD3',
                'standarCapaiansRelation' => 'standarCapaiansLamdikD3',
                'standarNames' => $standar_names_lamdik,
            ],
            'LAMDIK S2' => [
                'modelClass' => StandarElemenLamdikS2::class,
                'standarTargetsRelation' => 'standarTargetsLamdikS2',
                'standarCapaiansRelation' => 'standarCapaiansLamdikS2',
                'standarNames' => $standar_names_lamdik,
            ],
        ];

        $degreeInfo = $degreeMappings[$degree] ?? $degreeMappings['BAN-PT S1'];

        $modelClass = $degreeInfo['modelClass'];
        $standarTargetsRelation = $degreeInfo['standarTargetsRelation'];
        $standarCapaiansRelation = $degreeInfo['standarCapaiansRelation'];
        $standarNames = $degreeInfo['standarNames'];

        $data_standar = [];
        foreach ($standarNames as $index => $name) {
            $degree = trim($degree);
            // dd($degree);
            $data_standar['data_standar_k' . ($index + 1)] = $modelClass::with([
                $standarTargetsRelation => function ($query) use ($degree) {
                    $query->where('jenjang', $degree);
                },
                $standarCapaiansRelation,
            ])
            ->when($request->q, function ($query) use ($request) {
                $query->where('elemen_nama', 'like', '%' . $request->q . '%');
            })
            ->where('standar_nama', $name)
            ->latest()
            ->paginate(30)
            ->appends(['q' => $request->q]);
        }        

        return view('pages.admin.kriteria-dokumen.index', [
            'nama_data_standar' => $standarNames,
            'standarTargetsRelation' => $standarTargetsRelation,
            'data_standar' => $data_standar,
            'degree' => $degree
        ]);
    }

    public function create(Request $request)
    {
        $degree = $request->degree; 
        return view('pages.admin.kriteria-dokumen.create', [
            'degree' => $degree
        ]);
    }

    public function import(Request $request)
    {
        $degree = $request->degree; 
        return view('pages.admin.kriteria-dokumen.import', [
            'degree' => $degree
        ]);
    }

    public function storeImport(Request $request)
    {
        $request->validate([
            'nama_dokumen' => 'required|mimes:csv,xls,xlsx'
        ]);

        $degree = $request->input('degree');

        switch ($degree) {
            case 'BAN-PT D3':
                $importClass = new StandarBanptD3Import();
                break;
            case 'BAN-PT S1':
                $importClass = new StandarBanptS1Import();
                break;
            case 'BAN-PT S2':
                $importClass = new StandarBanptS2Import();
                break;
            case 'LAMDIK PPG':
                $importClass = new StandarLamdikPPGImport();
                break;
            case 'LAMDIK S1':
                $importClass = new StandarLamdikS1Import();
                break;
            case 'LAMDIK S2':
                $importClass = new StandarLamdikS2Import();
                break;
            default:
                return redirect()->route('admin.kriteria-dokumen.index')->with('error', 'Invalid degree selected.');
        }

        try {
            Excel::import($importClass, $request->file('nama_dokumen'));

            return redirect()->route('admin.kriteria-dokumen.index')->with('success', 'File imported successfully.');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->route('admin.kriteria-dokumen.index')->with('error', 'There was an issue importing the file.');
        }
    }

    public function kelolaTarget(Request $request, $importTitle, $indikator_id)
    {
        $importTitle = urldecode($importTitle);

        $degreeMappings = [
            'BAN-PT D3' => [
                'modelClass' => StandarElemenBanptD3::class,
            ],
            'BAN-PT S1' => [
                'modelClass' => StandarElemenBanptS1::class,
            ],
            'BAN-PT S2' => [
                'modelClass' => StandarElemenBanptS2::class,
            ],
            'LAMDIK PPG' => [
                'modelClass' => StandarElemenLamdikD3::class,
            ],
            'LAMDIK S1' => [
                'modelClass' => StandarElemenLamdikS1::class,
            ],
            'LAMDIK S2' => [
                'modelClass' => StandarElemenLamdikS2::class,
            ],
        ];

        $degreeInfo = $degreeMappings[$importTitle] ?? $degreeMappings['BAN-PT S1'];
        $modelClass = $degreeInfo['modelClass'];

        $standarElemen = $modelClass::where('indikator_id', $indikator_id)->firstOrFail();

        $standarTarget = StandarTarget::where('indikator_id', $indikator_id)
            ->when($request->q, function ($query, $q) {
                $query->where('id', 'like', "%{$q}%");
            })
            ->where('jenjang', $importTitle)
            ->latest()
            ->paginate(10);

        return view('pages.admin.kriteria-dokumen.kelola-target.index', [
            'indikator_id' => $indikator_id,
            'standarTarget' => $standarTarget,
            'standarElemen' => $standarElemen,
            'importTitle' => $importTitle,
        ]);
    }


    public function kelolaTargetCreate($importTitle, $indikator_id)
    {
        $importTitle = urldecode($importTitle); // Decode the importTitle

        $degreeMappings = [
            'BAN-PT D3' => [
                'modelClass' => StandarElemenBanptD3::class,
            ],
            'BAN-PT S1' => [
                'modelClass' => StandarElemenBanptS1::class,
            ],
            'BAN-PT S2' => [
                'modelClass' => StandarElemenBanptS2::class,
            ],
            'LAMDIK PPG' => [
                'modelClass' => StandarElemenLamdikD3::class,
            ],
            'LAMDIK S1' => [
                'modelClass' => StandarElemenLamdikS1::class,
            ],
            'LAMDIK S2' => [
                'modelClass' => StandarElemenLamdikS2::class,
            ],
        ];

        $degreeInfo = $degreeMappings[$importTitle] ?? $degreeMappings['BAN-PT S1'];
        $modelClass = $degreeInfo['modelClass'];

        $standarElemen = $modelClass::where('indikator_id', $indikator_id)->firstOrFail();
        $dokumenTipes = DokumenTipe::all();

        return view('pages.admin.kriteria-dokumen.kelola-target.create', [
            'indikator_id' => $indikator_id,
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
            'indikator_id' => 'required|string|max:255',
            'importTitle' => 'required|string|max:255',
            'dokumen_keterangan' => 'nullable|string',
        ]); 

        try {
            StandarTarget::create([
                'target_kode' => 'tgr-' . Str::uuid() . uniqid(),
                'indikator_id' => $request->indikator_id,
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

        return redirect()->route('admin.kriteria-dokumen.kelola-target', ['importTitle' => $request->importTitle, 'indikator_id' => $request->indikator_id])
        ->with([
            'success' => 'Tipe Dokumen created successfully.',
        ]);    
    }

    public function kelolaTargetEdit($indikator_id)
    {
        $dokumenTipes = DokumenTipe::all();
        
        $standarElemen = StandarElemenBanptS1::where('indikator_id', $indikator_id)->firstOrFail();

        $standarTarget = StandarTarget::where('indikator_id', $indikator_id)->firstOrFail();

        return view('pages.admin.kriteria-dokumen.kelola-target.edit', [
            'indikator_id' => $indikator_id,
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

        return redirect()->route('admin.kriteria-dokumen.kelola-target', ['indikator_id' => $request->indikator_id])
        ->with([
            'success' => 'Tipe Dokumen created successfully.',
        ]);    
    }

    public function kelolaTargetDestroy(Request $request, $id)
    {
        try {
            $standarTarget = StandarTarget::findOrFail($id);
            $standarTarget->delete();
            
            return redirect()->route('admin.kriteria-dokumen.kelola-target', ['indikator_id' => $request->indikator_id])
                ->with('success', 'Target deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.kriteria-dokumen.kelola-target', ['indikator_id' => $request->indikator_id])
                ->with('error', 'Failed to delete target.');
        }
    }

    public function tipeDokumenStore (Request $request)
    {
        $request->validate([
            'tipe_nama' => 'required|string|max:255',
            'indikator_id' => 'required|string',
        ]);

        $tipeDokumen = new DokumenTipe();
        $tipeDokumen->tipe_nama = $request->tipe_nama;

        $tipeDokumen->save();

        return redirect()->route('admin.kriteria-dokumen.kelola-target.create', ['indikator_id' => $request->indikator_id])
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
