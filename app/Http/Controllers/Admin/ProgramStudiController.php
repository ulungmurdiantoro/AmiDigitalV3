<?php

namespace App\Http\Controllers\Admin;

use App\Models\ProgramStudi;
use App\Models\Jurusan;
use App\Models\Fakultas;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use Termwind\Components\Dd;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class ProgramStudiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $program_studis = ProgramStudi::when(request()->q, function($program_studis) {
            $program_studis = $program_studis->where('prodi_nama', 'like', '%'. request()->q . '%');
        })->latest()->paginate(10);

        //append query string to pagination links
        $program_studis->appends(['q' => request()->q]);

        return view('pages.admin.program-studi.index', [
            'program_studis' => $program_studis,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $Jurusans = Jurusan::all();
        $Fakultass = Fakultas::all();
        return view('pages.admin.program-studi.create', [
            'Jurusans' => $Jurusans,
            'Fakultass' => $Fakultass,
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
            'prodi_nama' => 'required|string',
            'prodi_jenjang' => 'required|string',
            'prodi_jurusan' => 'required|string',
            'prodi_fakultas' => 'required|string',
            'prodi_akreditasi' => 'required',
            'akreditasi_kadaluarsa' => 'required|date',
            'akreditasi_bukti' => 'mimes:jpg,png,pdf,doc,docx|max:2048',
        ]);

        try {
            $fileName = time() . '.' . $request->akreditasi_bukti->extension();
            $filePath = $request->file('akreditasi_bukti')->storeAs('uploads/akreditasi/prodi', $fileName, 'public');
        } catch (\Exception $e) {
            Log::error('File upload failed: ' . $e->getMessage());
            return back()->withErrors(['akreditasi_bukti' => 'File upload failed. Please try again.']);
        }

        try {
            ProgramStudi::create([
                'program_studis_code' => 'prd-' .Str::uuid() . uniqid(),
                'prodi_nama' => $request->prodi_nama,
                'prodi_jenjang' => $request->prodi_jenjang,
                'prodi_jurusan' => $request->prodi_jurusan,
                'prodi_fakultas' => $request->prodi_fakultas,
                'prodi_akreditasi' => $request->prodi_akreditasi,
                'akreditasi_kadaluarsa' => $request->akreditasi_kadaluarsa,
                'akreditasi_bukti' => '/storage/' . $filePath,
            ]);
        } catch (\Exception $e) {
            Log::error('Database insertion failed: ' . $e->getMessage());
            return back()->withErrors(['database' => 'Failed to save data. Please try again.']);
        }

        // Redirect
        return redirect()->route('admin.program-studi.index');
    }

    public function storejurusan(Request $requestjurusan)
    {
        // Validate request
        $requestjurusan->validate([
            'jurusan_nama' => 'required|string|unique:jurusans',
        ]);

        try {
            Jurusan::create([
                'jurusan_kode' => 'jrsn-' .Str::uuid() . uniqid(),
                'jurusan_nama' => $requestjurusan->jurusan_nama,
            ]);
        } catch (\Exception $e) {
            Log::error('Database insertion failed: ' . $e->getMessage());
            return back()->withErrors(['database' => 'Failed to save data. Please try again.']);
        }

        // Redirect to a page (e.g., back to the form or list of departments) with a success message
        return redirect()->route('program-studi.create') // Change this route to wherever you want to redirect
                        ->with('success', 'Jurusan successfully created!');
    }

    public function storefakultas(Request $requestfakultas)
    {
        // Validate request
        $requestfakultas->validate([
            'fakultas_nama' => 'required|string|unique:fakultas',
        ]);

        try {
            Fakultas::create([
                'fakultas_kode' => 'fkts-' .Str::uuid() . uniqid(),
                'fakultas_nama' => $requestfakultas->fakultas_nama,
            ]);
        } catch (\Exception $e) {
            Log::error('Database insertion failed: ' . $e->getMessage());
            return back()->withErrors(['database' => 'Failed to save data. Please try again.']);
        }

        // Redirect to a page (e.g., back to the form or list of departments) with a success message
        return redirect()->route('program-studi.create') // Change this route to wherever you want to redirect
                        ->with('success', 'Fakultas successfully created!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $program_studis = ProgramStudi::findOrFail($id);
        return view('pages.admin.program-studi.edit', compact('program_studis'));
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
        // Find the existing Program Studi
        $program_studis = ProgramStudi::findOrFail($id);

        // Validate the form data
        $validatedData = $request->validate([
            'prodi_nama' => 'required|string|max:255',
            'prodi_jenjang' => 'required|string',
            'prodi_jurusan' => 'required|string',
            'prodi_fakultas' => 'required|string',
            'prodi_akreditasi' => 'required|string',
            'akreditasi_kadaluarsa' => 'nullable|date',
            'akreditasi_bukti' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        // Update the program studi details
        $program_studis->prodi_nama = $validatedData['prodi_nama'];
        $program_studis->prodi_jenjang = $validatedData['prodi_jenjang'];
        $program_studis->prodi_jurusan = $validatedData['prodi_jurusan'];
        $program_studis->prodi_fakultas = $validatedData['prodi_fakultas'];
        $program_studis->prodi_akreditasi = $validatedData['prodi_akreditasi'];
        $program_studis->akreditasi_kadaluarsa = $validatedData['akreditasi_kadaluarsa'];

        // Handle file upload for Bukti Akreditasi if a new file is uploaded
        if ($request->hasFile('akreditasi_bukti')) {
            $file = $request->file('akreditasi_bukti');
            $filename = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('akreditasi_bukti', $filename, 'public');

            // Delete the old file if it exists
            if ($program_studis->akreditasi_bukti) {
                Storage::disk('public')->delete($program_studis->akreditasi_bukti);
            }

            // Save new file path
            $program_studis->akreditasi_bukti = $filePath;
        }

        // Save the updated program studi
        $program_studis->save();

        // Redirect back with a success message
        return redirect()->route('admin.program-studi.index');
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
            $programStudi = ProgramStudi::findOrFail($id);
            $programStudi->delete();
            
            return redirect()->route('admin.program-studi.index')->with('success', 'Program Studi deleted successfully');
        } catch (\Exception $e) {
            return redirect()->route('admin.program-studi.index')->with('error', 'There was an error deleting the Program Studi');
        }
    }
}
