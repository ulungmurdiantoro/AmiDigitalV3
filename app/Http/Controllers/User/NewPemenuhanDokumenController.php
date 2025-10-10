<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\BuktiStandar;
use Illuminate\Http\Request;
use App\Models\StandarElemenBanptD3;
use App\Models\StandarElemenBanptS1;
use App\Models\Standard;
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
use App\Models\StandarTarget;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Cache;
use App\Models\StandarAkreditasi;
use App\Models\Jenjang;

class NewPemenuhanDokumenController extends Controller
{

    public function index(Request $request)
    {
        $akreditasi_kode  = session('user_akses');         
        $jenjang_raw      = session('user_penempatan');       

        $jenjang_nama = trim(explode(' - ', (string)$jenjang_raw, 2)[0]);
        if ($jenjang_nama === '') $jenjang_nama = 'S1';

        $validAkreditasi = StandarAkreditasi::pluck('nama')->toArray();
        $validJenjang    = Jenjang::pluck('nama')->toArray();

        if (!in_array($akreditasi_kode, $validAkreditasi, true)) {
            Log::warning('Nilai akreditasi sesi tidak valid, fallback ke BAN-PT', ['session' => $akreditasi_kode]);
            $akreditasi_kode = 'BAN-PT';
        }
        if (!in_array($jenjang_nama, $validJenjang, true)) {
            Log::warning('Nilai jenjang sesi tidak valid, fallback ke S1', ['session' => $jenjang_nama]);
            $jenjang_nama = 'S1';
        }

        $akreditasi = Cache::remember("akreditasi_{$akreditasi_kode}", 3600, function () use ($akreditasi_kode) {
            return StandarAkreditasi::where('nama', $akreditasi_kode)->firstOrFail();
        });

        $jenjang = Cache::remember("jenjang_{$jenjang_nama}", 3600, function () use ($jenjang_nama) {
            return Jenjang::where('nama', $jenjang_nama)->firstOrFail();
        });

        $standards = Standard::query()
            ->with(['elements.indicators', 'buktiStandar.dokumenCapaian'])
            ->where('standar_akreditasi_id', $akreditasi->id)
            ->where('jenjang_id', $jenjang->id)
            ->get();

        if ($standards->isEmpty()) {
            return view('pages.admin.kriteria-dokumen.empty', [
                'akreditasi' => $akreditasi,
                'jenjang'    => $jenjang,
            ]);
        }

        return view('pages.user.pemenuhan-dokumen.index', [
            'akreditasi' => $akreditasi,
            'jenjang'    => $jenjang,
            'standards'  => $standards,
        ]);
    }

    public function pemenuhanDokumen(Request $request, $indikator_id)
    {
        $penempatan = session('user_penempatan'); 
        $akses = session('user_akses'); 

        preg_match('/\b(S[0-9]+(?: Terapan)?|D[0-9]+|PPG)\b/', $penempatan, $matches);
        $degree = $matches[0] ?? 'PPG';

        $key = trim($akses . ' ' . $degree);

        $degreeMappings = [
            'BAN-PT D3' => [
                'modelClass' => StandarElemenBanptD3::class,
            ],
            'BAN-PT S1' => [
                'modelClass' => StandarElemenBanptS1::class,
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

        if (!isset($degreeMappings[$key])) {
            Log::warning("Unknown degree key: {$key}, falling back to BAN-PT S1");
        }

        $degreeInfo = $degreeMappings[$key] ?? $degreeMappings['BAN-PT S1'];

        $modelClass = $degreeInfo['modelClass'];
        $standarElemen = $modelClass::where('indikator_id', $indikator_id)->firstOrFail();

        $standarCapaian = StandarCapaian::where('indikator_id', $indikator_id)
            ->where('prodi', $penempatan)
            ->when($request->q, function ($query, $q) {
                $query->where('id', 'like', "%{$q}%");
            })->latest()->paginate(10);

        return view('pages.user.pemenuhan-dokumen.input-capaian.index', [
            'indikator_id' => $indikator_id,
            'standarCapaian' => $standarCapaian,
            'standarElemen' => $standarElemen,
        ]);
    }

    public function pemenuhanDokumenCreate(Request $request, $indikator_id)
    {
        $penempatan = session('user_penempatan', 'BAN-PT'); 
        $akses = session('user_akses', 'S1'); 

        preg_match('/\b(S[0-9]+(?: Terapan)?|D[0-9]+|PPG)\b/', $penempatan, $matches);
        $degree = $matches[0] ?? 'PPG';

        $key = trim($akses . ' ' . $degree);

        $degreeMappings = [
            'BAN-PT D3' => [
                'modelClass' => StandarElemenBanptD3::class,
            ],
            'BAN-PT S1' => [
                'modelClass' => StandarElemenBanptS1::class,
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

        if (!isset($degreeMappings[$key])) {
            Log::warning("Unknown degree key: {$key}, falling back to BAN-PT S1");
        }

        $degreeInfo = $degreeMappings[$key] ?? $degreeMappings['BAN-PT S1'];

        $modelClass = $degreeInfo['modelClass'];
        $standarElemen = $modelClass::where('indikator_id', $indikator_id)->firstOrFail();
        $standarTargets = StandarTarget::where('indikator_id', $indikator_id)->where('jenjang', $key)->get();

        return view('pages.user.pemenuhan-dokumen.input-capaian.create', [
            'indikator_id' => $indikator_id,
            'standarElemen' => $standarElemen,
            'standarTargets' => $standarTargets,
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

        // Hapus file jika ada
        if ($standarCapaian->dokumen_file) {
            Storage::disk('public')->delete($standarCapaian->dokumen_file);
        }

        // Simpan indikator_id sebelum delete
        $indikator_id = $standarCapaian->indikator_id;

        // Hapus record
        $standarCapaian->delete();

        // Redirect sesuai kondisi
        if ($indikator_id) {
            return redirect()->route('user.pemenuhan-dokumen.input-capaian', ['indikator_id' => $indikator_id])
                ->with(['success' => 'Dokumen berhasil dihapus.']);
        } else {
            return redirect()->route('user.pemenuhan-dokumen.index')
                ->with(['success' => 'Dokumen berhasil dihapus, tetapi tidak bisa diarahkan ke indikator terkait.']);
        }
    }


    public function pemenuhanBuktiStore(Request $request)
    {
        // ✅ Validasi input
        $validated = $request->validate([
            'indikator_id'       => 'required|integer|exists:indikators,id',
            'dokumen_file'       => 'required|file|mimes:pdf,doc,docx,xls,xlsx|max:5120', // max 5MB
            'periode'            => 'required|string|max:20',
            'dokumen_kadaluarsa' => 'nullable|date',
            'informasi'          => 'nullable|string|max:1000',
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
            'bukti_standar_id' => $request->input('indikator_id'),
            'dokumen_nama' => $request->input('dokumen_nama'),
            'dokumen_file' => '/storage/' . $filePath, 
            'periode' => $request->input('periode'),
            'dokumen_kadaluarsa' => $request->input('dokumen_kadaluarsa'),
            'informasi' => $request->input('informasi'),
            'prodi' => session('user_penempatan'),
        ]);

        // 🔁 Redirect dengan pesan sukses
        return redirect()->back()->with('success', 'Dokumen berhasil diupload.');
    }

}
