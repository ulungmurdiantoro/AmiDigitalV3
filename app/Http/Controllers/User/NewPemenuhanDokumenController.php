<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Standard;
use App\Models\StandarCapaian;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use App\Models\StandarAkreditasi;
use App\Models\Jenjang;

class NewPemenuhanDokumenController extends Controller
{
    public function index(Request $request, $periode, $prodi)
    {
        $akreditasi_kode = $this->resolveAkreditasi(session('user_akses'));
        $jenjang_nama    = $this->resolveJenjang(session('user_penempatan'));

        $akreditasi = $this->getAkreditasi($akreditasi_kode);
        $jenjang    = $this->getJenjang($jenjang_nama);

        $standards = Standard::query()
            ->with([
                'elements.indicators.dokumen_nilais' => function ($q) use ($periode, $prodi) {
                    $q->where('periode', $periode)
                    ->where('prodi', $prodi);
                },
                'buktiStandar.dokumenCapaian'
            ])
            ->where('standar_akreditasi_id', $akreditasi->id)
            ->where('jenjang_id', $jenjang->id)
            ->whereHas('elements.indicators.dokumen_nilais', function ($q) use ($periode, $prodi) {
                $q->where('periode', $periode)
                ->where('prodi', $prodi);
            })
            ->get();

        if ($standards->isEmpty()) {
            return view('pages.admin.kriteria-dokumen.empty', compact('akreditasi', 'jenjang'));
        }

        return view('pages.user.pemenuhan-dokumen.index', compact('akreditasi', 'jenjang', 'standards'));
    }

    protected function resolveAkreditasi(?string $kode): string
    {
        $valid = Cache::remember('valid_akreditasi_list', 3600, fn() => StandarAkreditasi::pluck('nama')->toArray());
        if (!in_array($kode, $valid, true)) {
            Log::warning('Nilai akreditasi sesi tidak valid, fallback ke BAN-PT', ['session' => $kode]);
            return 'BAN-PT';
        }
        return $kode;
    }

    protected function resolveJenjang(?string $raw): string
    {
        $nama = trim(explode(' - ', (string)$raw, 2)[0]) ?: 'S1';
        $valid = Cache::remember('valid_jenjang_list', 3600, fn() => Jenjang::pluck('nama')->toArray());
        if (!in_array($nama, $valid, true)) {
            Log::warning('Nilai jenjang sesi tidak valid, fallback ke S1', ['session' => $nama]);
            return 'S1';
        }
        return $nama;
    }

    protected function getAkreditasi(string $kode): StandarAkreditasi
    {
        return Cache::remember("akreditasi_{$kode}", 3600, fn() =>
            StandarAkreditasi::where('nama', $kode)->firstOrFail()
        );
    }

    protected function getJenjang(string $nama): Jenjang
    {
        return Cache::remember("jenjang_{$nama}", 3600, fn() =>
            Jenjang::where('nama', $nama)->firstOrFail()
        );
    }

    protected function handleFileUpload($file, $folder = 'uploads/capaian/prodi')
    {
        try {
            $fileName = time() . '.' . $file->extension();
            $filePath = $file->storeAs($folder, $fileName, 'public');
            return '/storage/' . $filePath;
        } catch (\Exception $e) {
            Log::error('File upload failed: ' . $e->getMessage());
            return false;
        }
    }

    public function pemenuhanDokumen(Request $request, $indikator_id)
    {
        $indikator = \App\Models\Indikator::with(['element.standard'])->findOrFail($indikator_id);

        $standarCapaian = \App\Models\StandarCapaian::where('indikator_id', $indikator_id)
            ->orderByDesc('created_at')
            ->get();

        $standarElemen = $indikator->elemen;

        return view('pages.user.pemenuhan-dokumen.input-capaian.index', [
            'indikator_id'    => $indikator_id,
            'indikator'       => $indikator,
            'standarCapaian'  => $standarCapaian,
            'standarElemen'   => $standarElemen,
        ]);
    }

    public function pemenuhanDokumenCreate(Request $request, $indikator_id)
    {
        $indikator = \App\Models\Indikator::with(['element.standard'])->findOrFail($indikator_id);

        $standarElemen = $indikator->element;

        $standarTargets = \App\Models\StandarTarget::where('indikator_id', $indikator_id)->get();

        return view('pages.user.pemenuhan-dokumen.input-capaian.create', [
            'indikator_id'    => $indikator_id,
            'indikator'       => $indikator,
            'standarElemen'   => $standarElemen,
            'standarTargets'  => $standarTargets,
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

        $filePath = $this->handleFileUpload($request->file('dokumen_file'));
        if (!$filePath) {
            return back()->withErrors(['dokumen_file' => 'File upload failed. Please try again.']);
        }

        StandarCapaian::create([
            'capaian_kode' => 'cpn-' . Str::uuid() . uniqid(),
            'indikator_id' => $request->indikator_id,
            'dokumen_nama' => $request->dokumen_nama,
            'pertanyaan_nama' => $request->pertanyaan_nama,
            'dokumen_tipe' => $request->dokumen_tipe,
            'dokumen_keterangan' => $request->dokumen_keterangan,
            'dokumen_file' => $filePath,
            'periode' => $request->periode,
            'dokumen_kadaluarsa' => $request->dokumen_kadaluarsa,
            'informasi' => $request->informasi,
            'prodi' => session('user_penempatan'),
        ]);

        return redirect()->route('user.pemenuhan-dokumen.input-capaian', ['indikator_id' => $request->indikator_id])
            ->with(['success' => 'Tipe Dokumen created successfully.']);
    }

    public function pemenuhanDokumenEdit($id)
    {
        $standarCapaian = StandarCapaian::findOrFail($id);
        return view('pages.user.pemenuhan-dokumen.input-capaian.edit', [
            'standarCapaian' => $standarCapaian,
            'indikator_id' => $standarCapaian->indikator_id,
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
        $standarCapaian->fill($request->only(['periode', 'dokumen_kadaluarsa', 'informasi']));

        if ($request->hasFile('dokumen_file')) {
            if ($standarCapaian->dokumen_file) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $standarCapaian->dokumen_file));
            }

            $filePath = $this->handleFileUpload($request->file('dokumen_file'));
            if (!$filePath) {
                return back()->withErrors(['dokumen_file' => 'File upload failed. Please try again.']);
            }

            $standarCapaian->dokumen_file = $filePath;
        }

        $standarCapaian->save();

        return redirect()->route('user.pemenuhan-dokumen.input-capaian', ['indikator_id' => $request->indikator_id])
            ->with(['success' => 'Tipe Dokumen updated successfully.']);
    }

    public function pemenuhanDokumenDestroy($id)
    {
        $standarCapaian = StandarCapaian::findOrFail($id);

        if ($standarCapaian->dokumen_file) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $standarCapaian->dokumen_file));
        }

        $indikator_id = $standarCapaian->indikator_id;
        $standarCapaian->delete();

        return redirect()->route('user.pemenuhan-dokumen.input-capaian', ['indikator_id' => $indikator_id])
            ->with(['success' => 'Dokumen berhasil dihapus.']);
    }

    public function pemenuhanBuktiStore(Request $request)
    {
        $request->validate([
            'indikator_id'       => 'required|integer|exists:indikators,id',
            'dokumen_file'       => 'required|file|mimes:pdf,doc,docx,xls,xlsx|max:5120',
            'periode'            => 'required|string|max:20',
            'dokumen_kadaluarsa' => 'nullable|date',
            'informasi'          => 'nullable|string|max:1000',
        ]);

        $filePath = $this->handleFileUpload($request->file('dokumen_file'));
        if (!$filePath) {
            return back()->withErrors(['dokumen_file' => 'File upload failed. Please try again.']);
        }

        StandarCapaian::create([
            'capaian_kode'        => 'cpn-' . Str::uuid() . uniqid(),
            'bukti_standar_id'    => $request->input('indikator_id'),
            'dokumen_nama'        => $request->file('dokumen_file')->getClientOriginalName(),
            'dokumen_file'        => $filePath,
            'periode'             => $request->input('periode'),
            'dokumen_kadaluarsa'  => $request->input('dokumen_kadaluarsa'),
            'informasi'           => $request->input('informasi'),
            'prodi'               => session('user_penempatan'),
        ]);

        return redirect()->back()->with('success', 'Dokumen berhasil diupload.');
    }
}
