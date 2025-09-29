<?php

namespace App\Http\Controllers\User;

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
use App\Models\StandarCapaian;
use App\Models\DokumenTipe;
use App\Models\Indikator;
use App\Models\Jenjang;
use App\Models\StandarAkreditasi;
use App\Models\Standard;
use App\Models\StandarTarget;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Cache;

class PemenuhanDokumenController extends Controller
{
    public function index(Request $request)
    {
        $penempatan = session('user_penempatan'); 
        $akses = session('user_akses');           

        $jenjangNama = trim(explode('-', $penempatan)[0]);

        $jenjang = Jenjang::where('nama', $jenjangNama)->first();

        $akreditasi = StandarAkreditasi::where('nama', $akses)->first();

        if (!$jenjang || !$akreditasi) {
            abort(404, 'Data jenjang atau standar akreditasi tidak ditemukan');
        }

        $standardsQuery = Standard::query()
            ->with(['elements.indicators'])
            ->where('standar_akreditasi_id', $akreditasi->id)
            ->where('jenjang_id', $jenjang->id);

        $standards = $standardsQuery->get();

        return view('pages.user.pemenuhan-dokumen.index', compact('jenjang', 'akreditasi', 'standards'));

    }

    public function pemenuhanDokumen(Request $request, $indikator_id)
    {
        $penempatan = session('user_penempatan'); 
        $akses = session('user_akses');           

        $jenjangNama = trim(explode('-', $penempatan)[0]);

        $jenjang = Jenjang::where('nama', $jenjangNama)->first();
        $akreditasi = StandarAkreditasi::where('nama', $akses)->first();

        if (!$jenjang || !$akreditasi) {
            abort(404, 'Data jenjang atau standar akreditasi tidak ditemukan');
        }

        $indikator = Indikator::with(['element.standard'])
            ->where('id', $indikator_id)
            ->first();

        if (!$indikator || 
            $indikator->element->standard->jenjang_id !== $jenjang->id || 
            $indikator->element->standard->standar_akreditasi_id !== $akreditasi->id) {
            abort(404, 'Indikator tidak ditemukan atau tidak sesuai dengan jenjang/akreditasi');
        }

        $standarCapaian = StandarCapaian::where('indikator_id', $indikator_id)
            ->when($request->q, function ($query, $q) {
                $query->where('id', 'like', "%{$q}%");
            })
            ->where('prodi', $penempatan)
            ->latest()
            ->paginate(10);

        return view('pages.user.pemenuhan-dokumen.input-capaian.index', [
            'indikator_id' => $indikator_id,
            'standarCapaian' => $standarCapaian,
            'indikator' => $indikator,
        ]);
    }

    public function pemenuhanDokumenCreate(Request $request, $indikator_id)
    {
        $penempatan = session('user_penempatan'); 
        $akses = session('user_akses');           

        $jenjangNama = trim(explode('-', $penempatan)[0]);

        $jenjang = Jenjang::where('nama', $jenjangNama)->first();
        $akreditasi = StandarAkreditasi::where('nama', $akses)->first();

        if (!$jenjang || !$akreditasi) {
            abort(404, 'Data jenjang atau standar akreditasi tidak ditemukan');
        }

        $indikator = Indikator::with(['element.standard'])
            ->where('id', $indikator_id)
            ->first();

        if (!$indikator || 
            $indikator->element->standard->jenjang_id !== $jenjang->id || 
            $indikator->element->standard->standar_akreditasi_id !== $akreditasi->id) {
            abort(404, 'Indikator tidak ditemukan atau tidak sesuai dengan jenjang/akreditasi');
        }

        $standarTarget = StandarTarget::where('indikator_id', $indikator_id)
            ->when($request->q, function ($query, $q) {
                $query->where('id', 'like', "%{$q}%");
            })
            ->where('jenjang', $penempatan)
            ->latest()
            ->paginate(10);

        return view('pages.user.pemenuhan-dokumen.input-capaian.create', [
            'indikator_id' => $indikator_id,
            'indikator ' => $indikator,
            'standarTargets' => $standarTarget,
        ]);
    }

    public function pemenuhanDokumenStore(Request $request)
    {
        $request->validate([
            'indikator_id' => 'required|string',
            'dokumen_nama' => 'required|string',
            'pertanyaan_nama' => 'required|string',
            'dokumen_tipe' => 'required|string',
            'dokumen_keterangan' => 'nullable|string',
            'dokumen_file' => 'required|file|mimes:pdf,doc,docx,xlsx,png,jpg,jpeg',
            'periode' => 'required|string',
            'dokumen_kadaluarsa' => 'required|date',
            'informasi' => 'nullable|string',
        ]);
        
        try {
            $fileName = time() . '.' . $request->dokumen_file->extension();
            $filePath = $request->file('dokumen_file')->storeAs('uploads/capaian/prodi', $fileName, 'public');
        } catch (\Exception $e) {
            Log::error('File upload failed: ' . $e->getMessage());
            return back()->withErrors(['dokumen_file' => 'File upload failed. Please try again.']);
        }

        StandarCapaian::create([
            'capaian_kode' => 'cpn-' . Str::uuid() . uniqid(),
            'indikator_id' => $request->input('indikator_id'),
            'dokumen_nama' => $request->input('dokumen_nama'),
            'pertanyaan_nama' => $request->input('pertanyaan_nama'),
            'dokumen_tipe' => $request->input('dokumen_tipe'),
            'dokumen_keterangan' => $request->input('dokumen_keterangan'),
            'dokumen_file' => '/storage/' . $filePath, 
            'periode' => $request->input('periode'),
            'dokumen_kadaluarsa' => $request->input('dokumen_kadaluarsa'),
            'informasi' => $request->input('informasi'),
            'prodi' => session('user_penempatan'),
        ]);

        return redirect()->route('user.pemenuhan-dokumen.input-capaian', ['indikator_id' => $request->indikator_id])
        ->with([
            'success' => 'Tipe Dokumen created successfully.',
        ]);    
    }

    public function pemenuhanDokumenEdit($id)
    {
        $standarCapaian = StandarCapaian::findOrFail($id);

        $indikator_id = $standarCapaian->indikator_id;

        return view('pages.user.pemenuhan-dokumen.input-capaian.edit', [
            'standarCapaian' => $standarCapaian,
            'indikator_id' => $indikator_id,
        ]);    
    }

    public function pemenuhanDokumenUpdate(Request $request, $id)
    {
        $request->validate([
            'dokumen_file' => 'file|mimes:pdf,doc,docx,xlsx,xls|max:2048',
            'periode' => 'required|string',
            'dokumen_kadaluarsa' => 'required|date',
            'informasi' => 'nullable|string',
        ]);

        $standarCapaian = StandarCapaian::findOrFail($id);
        $standarCapaian->periode = $request->periode;
        $standarCapaian->dokumen_kadaluarsa = $request->dokumen_kadaluarsa;
        $standarCapaian->informasi = $request->informasi;

        if ($request->hasFile('dokumen_file')) {
            if ($standarCapaian->dokumen_file) {
                Storage::disk('public')->delete($standarCapaian->dokumen_file);
            }

            $fileName = time() . '.' . $request->dokumen_file->extension();
            $filePath = $request->file('dokumen_file')->storeAs('uploads/capaian/prodi', $fileName, 'public');
            $standarCapaian->dokumen_file = '/storage/' . $filePath;
        }

        $standarCapaian->save();

        return redirect()->route('user.pemenuhan-dokumen.input-capaian', ['indikator_id' => $request->indikator_id])
        ->with([
            'success' => 'Tipe Dokumen updated successfully.',
        ]);    
    }

    public function pemenuhanDokumenDestroy($id)
    {
        $standarCapaian = StandarCapaian::findOrFail($id);

        if ($standarCapaian->dokumen_file) {
            Storage::disk('public')->delete($standarCapaian->dokumen_file);
        }

        $indikator_id = $standarCapaian->indikator_id;

        $standarCapaian->delete();

        return redirect()->route('user.pemenuhan-dokumen.input-capaian', ['indikator_id' => $indikator_id])
            ->with([
                'success' => 'Tipe Dokumen deleted successfully.',
            ]);
    }

}
