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
use App\Imports\StandarBanptS1Import;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class KriteriaDokumenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Define the standar names and titles
        $title_data_standar = [
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

        // Define levels
        $levels = ['d3', 's1', 's2', 's3', 's1terapan', 's2terapan', 's3terapan'];
        $models = [
            'd3' => StandarElemenBanptD3::class,
            's1' => StandarElemenBanptS1::class,
            's2' => StandarElemenBanptS2::class,
            's3' => StandarElemenBanptS3::class,
            's1terapan' => StandarElemenBanptS3::class, // Adjust if this differs
            's2terapan' => StandarElemenBanptS3::class, // Adjust if this differs
            's3terapan' => StandarElemenBanptS3::class, // Adjust if this differs
        ];

        $data_standar = [];
        $data = [];

        // Loop through each level and title to populate data
        foreach ($levels as $level) {
            foreach ($title_data_standar as $index => $title) {
                $key = "data_standar_ban_pt{$level}_k" . ($index + 1);

                // Build the query
                $data_standar[$key] = $models[$level]::when(request()->q, function ($query) {
                    $query->where('elemen_nama', 'like', '%' . request()->q . '%');
                })->where('standar_nama', $title)
                ->withCount('standarTargets')
                ->orderBy('id', 'desc')
                ->paginate(30)
                ->appends(['q' => request()->q]);

                // Define the title key
                $data["nama_data_standar_ban_pt{$level}_k" . ($index + 1)] = $title;
            }
        }

        $tableId = 'standardsTable'; // Define a unique ID for the table

        return view('pages.admin.kriteria-dokumen.index', array_merge($data_standar, $data, ['tableId' => $tableId]));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.admin.kriteria-dokumen.create');
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

    public function import()
    {
        
        return view('pages.admin.kriteria-dokumen.import');
    }

    public function storeImport(Request $request)
    {
        $request->validate([
            'nama_dokumen' => 'required|mimes:csv,xls,xlsx'
        ]);

        try {
            // Import data
            Excel::import(new StandarBanptS1Import(), $request->file('nama_dokumen'));

            return redirect()->route('admin.kriteria-dokumen.index')->with('success', 'File imported successfully.');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->route('admin.kriteria-dokumen.index')->with('error', 'There was an issue importing the file.');
        }
    }

    public function kelolaTarget(Request $request, $indikator_kode)
    {
        // Validate if indikator_kode exists
        $standarElemen = StandarElemenBanptS1::where('indikator_kode', $indikator_kode)->firstOrFail();

        // Fetch StandarTarget data
        $standarTarget = StandarTarget::when($request->q, function ($query, $q) {
            $query->where('id', 'like', "%{$q}%"); // Update the field if needed
        })->latest()->paginate(10);

        // Return the view
        return view('pages.admin.kriteria-dokumen.kelola-target.index', [
            'indikator_kode' => $indikator_kode,
            'standarTarget' => $standarTarget,
            'standarElemen' => $standarElemen,
        ]);
    }

    public function kelolaTargetCreate($indikator_kode)
    {

        $dokumenTipes = DokumenTipe::all();
        
        // Validate if indikator_kode exists
        $standarElemen = StandarElemenBanptS1::where('indikator_kode', $indikator_kode)->firstOrFail();

        // Pass the data to the view
        return view('pages.admin.kriteria-dokumen.kelola-target.create', [
            'indikator_kode' => $indikator_kode,
            'standarElemen' => $standarElemen,
            'dokumenTipes' => $dokumenTipes,
        ]);
    }

    public function kelolaTargetStore(Request $request)
    {
        // Validate request
        $request->validate([ 
            'dokumen_nama' => 'required|string|max:255',
            'pertanyaan_nama' => 'required|string|max:255',
            'dokumen_tipe' => 'required|string|max:255',
            'dokumen_keterangan' => 'nullable|string',
        ]); 

        try {
            StandarTarget::create([
                'target_kode' => 'tgr-' . Str::uuid() . uniqid(),
                'indikator_kode' => $request->indikator_kode,
                'dokumen_nama' => $request->dokumen_nama,
                'pertanyaan_nama' => $request->pertanyaan_nama,
                'dokumen_tipe' => $request->dokumen_tipe,
                'dokumen_keterangan' => $request->dokumen_keterangan,
            ]);
        } catch (\Exception $e) {
            Log::error('Database insertion failed: ' . $e->getMessage());
            return back()->withErrors(['database' => 'Failed to save data. Please try again.']);
        }

        // Redirect
        return redirect()->route('admin.kriteria-dokumen.kelola-target', ['indikator_kode' => $request->indikator_kode])
        ->with([
            'success' => 'Tipe Dokumen created successfully.',
        ]);    
    }

    public function kelolaTargetEdit($indikator_kode)
    {
        $dokumenTipes = DokumenTipe::all();
        
        // Validate if indikator_kode exists
        $standarElemen = StandarElemenBanptS1::where('indikator_kode', $indikator_kode)->firstOrFail();

        // Fetch StandarTarget data
        $standarTarget = StandarTarget::where('indikator_kode', $indikator_kode)->firstOrFail();

        // Pass the data to the view
        return view('pages.admin.kriteria-dokumen.kelola-target.edit', [
            'indikator_kode' => $indikator_kode,
            'standarTarget' => $standarTarget,
            'standarElemen' => $standarElemen,
            'dokumenTipes' => $dokumenTipes,
        ]);    }

    // Update method
    public function kelolaTargetUpdate(Request $request, $id)
    {
        // Validate the request data
        $validated = $request->validate([
            'dokumen_nama' => 'required|string|max:255',
            'pertanyaan_nama' => 'required|string|max:255',
            'dokumen_tipe' => 'required|string|max:255',
            'dokumen_keterangan' => 'nullable|string',
        ]);

        // Find the target by its ID
        $standarTarget = StandarTarget::findOrFail($id);

        // Update the target with the validated data
        $standarTarget->update($validated);

        // Redirect back with a success message
        return redirect()->route('admin.kriteria-dokumen.kelola-target', ['indikator_kode' => $request->indikator_kode])
        ->with([
            'success' => 'Tipe Dokumen created successfully.',
        ]);    
    }
    // Destroy method
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
            // Add other validations as necessary
        ]);

        // Store logic
        $tipeDokumen = new DokumenTipe();
        $tipeDokumen->tipe_nama = $request->tipe_nama;
        // Add other fields as necessary and save
        $tipeDokumen->save();

        return redirect()->route('admin.kriteria-dokumen.kelola-target.create', ['indikator_kode' => $request->indikator_kode])
            ->with([
                'success' => 'Tipe Dokumen created successfully.',
            ]);
    }

    public function tipeDokumenDestroy(Request $request)
    {
        // Validate the request using 'id' as the field name for the document type ID
        $request->validate([
            'id' => 'required|integer|exists:dokumen_tipes,id',
        ]);

        try {
            // Use the correct field name from the validated request to delete the record
            DokumenTipe::destroy($request->id);
        } catch (\Exception $e) {
            Log::error('Database deletion failed: ' . $e->getMessage());
            return back()->withErrors(['database' => 'Failed to delete data. Please try again.']);
        }

        // Redirect back with a success message
        return back()->with('success', 'Document type successfully deleted!');
    }

}
