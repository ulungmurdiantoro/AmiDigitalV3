<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StandarAkreditasi;
use App\Models\Jenjang;
use App\Models\Standard;
use App\Models\StandarElemenBanptS1;
use App\Models\StandarTarget;
use App\Models\DokumenTipe;
use App\Imports\StandarBanptD3Import;
use App\Imports\StandarBanptS1Import;
use App\Imports\StandarBanptS2Import;
use App\Imports\StandarLamdikPPGImport;
use App\Imports\StandarLamdikS1Import;
use App\Imports\StandarLamdikS2Import;
use App\Models\Indikator;
use App\Models\BuktiStandar;
use App\Models\Element;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Dimensions;

class NewKriteriaDokumenController extends Controller
{  
    public function index(Request $request)
    {
        $validAkreditasi = StandarAkreditasi::pluck('nama')->toArray();
        $validJenjang = Jenjang::pluck('nama')->toArray();

        $validated = $request->validate([
            'akreditasi' => ['nullable', Rule::in($validAkreditasi)],
            'jenjang' => ['nullable', Rule::in($validJenjang)],
        ]);


        $akreditasi_kode = $validated['akreditasi'] ?? 'BAN-PT';
        $jenjang_nama = $validated['jenjang'] ?? 'S1';

        $akreditasi = Cache::remember("akreditasi_{$akreditasi_kode}", 3600, function () use ($akreditasi_kode) {
            return StandarAkreditasi::where('nama', $akreditasi_kode)->firstOrFail();
        });

        $jenjang = Cache::remember("jenjang_{$jenjang_nama}", 3600, function () use ($jenjang_nama) {
            return Jenjang::where('nama', $jenjang_nama)->firstOrFail();
        });

        $standardsQuery = Standard::query()
            ->with(['elements.indicators', 'buktiStandar'])
            ->where('standar_akreditasi_id', $akreditasi->id)
            ->where('jenjang_id', $jenjang->id);

        $standards = $standardsQuery->get();

        if ($standards->isEmpty()) {
            return view('pages.admin.kriteria-dokumen.empty', compact('akreditasi', 'jenjang'));
        }

        return view('pages.admin.kriteria-dokumen.index2', [
            'akreditasi' => $akreditasi,
            'jenjang' => $jenjang,
            'standards' => $standards,
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

        $indikator = Indikator::with(['element.standard'])->findOrFail($indikator_id);

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
            'indikator' => $indikator,
            'importTitle' => $importTitle,
        ]);
    }

    public function kelolaTargetCreate($importTitle, $indikator_id)
    {
        $importTitle = urldecode($importTitle);

        $indikator = Indikator::with(['element.standard'])->findOrFail($indikator_id);

        $dokumenTipes = DokumenTipe::all();

        return view('pages.admin.kriteria-dokumen.kelola-target.create', [
            'indikator_id' => $indikator_id,
            'indikator' => $indikator,
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

    public function kelolabuktiCreate($importTitle, $id)
    {
        $importTitle = urldecode($importTitle);
        [$akreditasiNama, $jenjangNama] = explode(' ', $importTitle, 2);

        $akreditasi = StandarAkreditasi::where('nama', $akreditasiNama)->firstOrFail();
        $jenjang = Jenjang::where('nama', $jenjangNama)->firstOrFail();

        $standard = Standard::findOrFail($id);

        $allStandards = Standard::with('buktiStandar')
            ->where('standar_akreditasi_id', $akreditasi->id)
            ->where('jenjang_id', $jenjang->id)
            ->get();

        return view('pages.admin.kriteria-dokumen.kelola-bukti.create', compact('standard', 'importTitle', 'allStandards'));
    }

    public function kelolaBuktiStore(Request $request)
    {
        $request->validate([
            'standard_id' => 'required|exists:standards,id',
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        BuktiStandar::create([
            'standard_id' => $request->standard_id,
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
        ]);

        return back()->with('success', 'Bukti berhasil ditambahkan.');
    }

    public function kelolaBuktiUpdate(Request $request, $id)
    {
        $bukti = BuktiStandar::findOrFail($id);

        $data = $request->validate([
            'nama'      => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $bukti->update($data);

        return back()->with('success', 'Bukti berhasil diperbarui.');
    }

    public function kelolaBuktiDestroy($id)
    {
        $bukti = BuktiStandar::findOrFail($id);
        $bukti->delete();

        return back()->with('success', 'Bukti berhasil dihapus.');
    }

    public function kelolaIndikatorCreate($importTitle, $id)
    {
        $importTitle = urldecode($importTitle);
        [$akreditasiNama, $jenjangNama] = explode(' ', $importTitle, 2);

        $akreditasi = StandarAkreditasi::where('nama', $akreditasiNama)->firstOrFail();
        $jenjang = Jenjang::where('nama', $jenjangNama)->firstOrFail();

        $element = Element::findOrFail($id);

        $standards = Standard::where('standar_akreditasi_id', $akreditasi->id)
            ->where('jenjang_id', $jenjang->id)
            ->pluck('id'); // ambil ID-nya saja

        $allElements = Standard::with('elements.indicators') // tambahkan eager load indikator
            ->where('standar_akreditasi_id', $akreditasi->id)
            ->where('jenjang_id', $jenjang->id)
            ->get()
            ->pluck('elements')
            ->flatten();


        return view('pages.admin.kriteria-dokumen.kelola-indikator.create', compact('importTitle', 'allElements', 'element'));
    }

    public function kelolaIndikatorStore(Request $request)
    {
        $request->validate([
            'elemen_id' => 'required|exists:elements,id',
            'nama' => 'required|string',
            'deskripsi' => 'nullable|string',
        ]);

        Indikator::create([
            'elemen_id' => $request->elemen_id,
            'nama_indikator' => $request->nama,
            'info' => $request->deskripsi,
        ]);

        return back()->with('success', 'Bukti berhasil ditambahkan.');
    }
    

    public function kelolaIndikatorUpdate(Request $request, $id) 
    {
        $data = $request->validate([
            'nama_indikator' => 'required|string|max:255',
            'info'           => 'nullable|string',
        ]);
        Indikator::findOrFail($id)->update($data);
        return back()->with('success','Indikator berhasil diperbarui.');
    }

    public function kelolaIndikatorDestroy($id) 
    {
        Indikator::findOrFail($id)->delete();
        return back()->with('success','Indikator berhasil dihapus.');
    }



}
