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
use App\Models\StandarTarget;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PemenuhanDokumenController extends Controller
{
    public function index(Request $request)
    {
        $penempatan = session('user_penempatan'); 
        $akses = session('user_akses'); 

        preg_match('/\b(S[0-9]+(?: Terapan)?|D[0-9]+|PPG)\b/', $penempatan, $matches);
        $degree = $matches[0] ?? 'PPG';
        // dd($matches);

        $key = trim($akses . ' ' . $degree);

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
                'standarNilaisRelation' => 'standarNilaisD3',
                'standarNames' => $standar_names_banpt,
            ],
            'BAN-PT S1' => [
                'modelClass' => StandarElemenBanptS1::class,
                'standarTargetsRelation' => 'standarTargetsBanptS1',
                'standarCapaiansRelation' => 'standarCapaiansBanptS1',
                'standarNilaisRelation' => 'standarNilaisBanptS1',
                'standarNames' => $standar_names_banpt,
            ],
            'LAMDIK PPG' => [
                'modelClass' => StandarElemenLamdikD3::class,
                'standarTargetsRelation' => 'standarTargetsLamdikD3',
                'standarCapaiansRelation' => 'standarCapaiansLamdikD3',
                'standarNilaisRelation' => 'standarNilaisLamdikD3',
                'standarNames' => $standar_names_lamdik,
            ],
            'LAMDIK S1' => [
                'modelClass' => StandarElemenLamdikS1::class,
                'standarTargetsRelation' => 'standarTargetsLamdikS1',
                'standarCapaiansRelation' => 'standarCapaiansLamdikS1',
                'standarNilaisRelation' => 'standarNilaisLamdikS1',
                'standarNames' => $standar_names_lamdik,
            ],
            'LAMDIK S2' => [
                'modelClass' => StandarElemenLamdikS2::class,
                'standarTargetsRelation' => 'standarTargetsLamdikS2',
                'standarCapaiansRelation' => 'standarCapaiansLamdikS2',
                'standarNilaisRelation' => 'standarNilaisLamdikS2',
                'standarNames' => $standar_names_lamdik,
            ],
        ];

        if (!isset($degreeMappings[$key])) {
            Log::warning("Unknown degree key: {$key}, falling back to BAN-PT S1");
        }
        $degreeInfo = $degreeMappings[$key] ?? $degreeMappings['BAN-PT S1'];

        $modelClass = $degreeInfo['modelClass'];
        $standarTargetsRelation = $degreeInfo['standarTargetsRelation'];
        $standarCapaiansRelation = $degreeInfo['standarCapaiansRelation'];
        $standarNames = $degreeInfo['standarNames'];

        $data_standar = [];
        $degree = trim($degree);

        foreach ($standarNames as $index => $name) {
            $data_standar['data_standar_k' . ($index + 1)] = $modelClass::with([
                $standarTargetsRelation => function ($query) use ($key) {
                    $query->where('jenjang', $key);
                },
                $standarCapaiansRelation => function ($query) use ($penempatan) {
                    $query->where('prodi', $penempatan);
                },
            ])
            ->when($request->q, function ($query) use ($request) {
                $query->where('elemen_nama', 'like', '%' . $request->q . '%');
            })
            ->where('standar_nama', $name)
            ->latest()
            ->paginate(30)
            ->appends(['q' => $request->q]);
        }        

        return view('pages.user.pemenuhan-dokumen.index', [
            'nama_data_standar' => $standarNames,
            'standarTargetsRelation' => $standarTargetsRelation,
            'standarCapaiansRelation' => $standarCapaiansRelation,
            'data_standar' => $data_standar,
            'key' => $key
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
