<?php

namespace App\Http\Controllers\Auditor;

use App\Http\Controllers\Controller;
use App\Models\Indikator;
use App\Models\StandarNilai;
use App\Models\PenjadwalanAmi;
use App\Models\StandarAkreditasi;
use App\Models\Jenjang;
use App\Models\TransaksiAmi;
use App\Models\User;
use App\Models\Standard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class EvaluasiAmiAuditorController extends Controller
{
    public function index()
    {
        $data_kesiapan = TransaksiAmi::whereHas('auditorAmi', function($query) {
            $query->where('users_kode', session('user_kode'));
        })
        ->where('status', 'Diterima')
        ->latest()
        ->get();

        return view('pages.auditor.evaluasi-ami.index', [
            'data_kesiapan' => $data_kesiapan,
        ]);
    }


    public function auditAmi(Request $request, $periode, $prodi)
    {
        $transaksi_ami = TransaksiAmi::where('periode', $periode)
        ->where('prodi', $prodi)
        ->with('auditorAmi.user') 
        ->first();

        $akreditasi_kode  =$transaksi_ami->standar_akreditasi;       
        $jenjang_raw      = $prodi;       

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

        $standards = Standard::with([
            'elements.indicators.dokumen_nilais' => function ($query) use ($periode, $prodi) {
                $query->where('periode', $periode)
                    ->where('prodi', $prodi);
            },
            'elements.indicators.dokumen_targets',
            'elements.indicators.dokumen_capaians' => function ($query) use ($periode, $prodi) {
                $query->where('periode', $periode)
                    ->where('prodi', $prodi);
            },
            'elements.indicators',
            'elements.standard',
            'buktiStandar.dokumenCapaian' => function ($query) use ($periode, $prodi) {
                $query->where('periode', $periode)
                    ->where('prodi', $prodi);
            },
            'buktiStandar'
        ])
        ->where('standar_akreditasi_id', $akreditasi->id)
        ->where('jenjang_id', $jenjang->id)
        ->get();

        $penjadwalan_ami = PenjadwalanAmi::with(['auditor_ami.user'])
            ->when($request->q, function ($query) use ($request) {
                $query->whereHas('auditor_ami.user', function ($q) use ($request) {
                    $q->where('user_nama', 'like', '%' . $request->q . '%');
                })
                ->orWhere('prodi_nama', 'like', '%' . $request->q . '%');
            })
            ->where('prodi', $jenjang_raw)
            ->latest()
            ->get();

        $auditors = User::where('user_level', 'auditor')->get();

        $akreditasi = Cache::remember("akreditasi_{$akreditasi_kode}", 3600, function () use ($akreditasi_kode) {
            return StandarAkreditasi::where('nama', $akreditasi_kode)->firstOrFail();
        });

        $jenjang = Cache::remember("jenjang_{$jenjang_nama}", 3600, function () use ($jenjang_nama) {
            return Jenjang::where('nama', $jenjang_nama)->firstOrFail();
        });
        
        return view('pages.auditor.evaluasi-ami.audit-ami.index', [
            'akreditasi' => $akreditasi,
            'jenjang'    => $jenjang,
            'standards'  => $standards,
            'periode' => $periode,
            'prodi' => $jenjang_raw,
            'penjadwalan_ami' => $penjadwalan_ami,
            'transaksi_ami' => $transaksi_ami,
            'auditors' => $auditors,
        ]);
    }

    public function store(Request $request)
    {
        Log::info('Incoming Data:', $request->all());

        $validatedData = $request->validate([
            'ami_kodes' => 'required|string',
            'indikator_ids' => 'required|string',
            'indikator_bobots' => 'nullable|string',
            'mandiri_nilais' => 'required|numeric|min:0|max:4',
            'hasil_nilais' => 'required|numeric|min:0|max:4',
            'hasil_kriterias' => 'nullable|string',
            'hasil_deskripsis' => 'nullable|string',
            'jenis_temuans' => 'required|string',
            'hasil_akibats' => 'nullable|string',
            'hasil_masalahs' => 'nullable|string',
            'hasil_rekomendasis' => 'nullable|string',
            'prodis' => 'nullable|string',
            'periodes' => 'nullable|string',
        ]);

        try {
            $standard = StandarNilai::where('indikator_id', $validatedData['indikator_ids'])
                ->where('ami_kode', $validatedData['ami_kodes'])
                ->first();

            if ($standard) {
                $standard->mandiri_nilai = $validatedData['mandiri_nilais'];
                $standard->hasil_nilai = $validatedData['hasil_nilais'];
                $standard->hasil_kriteria = $validatedData['hasil_kriterias'];
                $standard->hasil_deskripsi = $validatedData['hasil_deskripsis'];
                $standard->jenis_temuan = $validatedData['jenis_temuans'];
                $standard->hasil_akibat = $validatedData['hasil_akibats'];
                $standard->hasil_masalah = $validatedData['hasil_masalahs'];
                $standard->hasil_rekomendasi = $validatedData['hasil_rekomendasis'];

                if ($standard->save()) {
                    Log::info('Data updated successfully:', $standard->toArray());
                    return redirect()->back()->with('success', 'Data berhasil diperbarui!');
                } else {
                    Log::error('Failed to update data.');
                    return redirect()->back()->with('error', 'Failed to update data.');
                }
            } else {
                $standard = new StandarNilai();
                $standard->ami_kode = $validatedData['ami_kodes'];
                $standard->indikator_id = $validatedData['indikator_ids'];
                $standard->mandiri_nilai = $validatedData['mandiri_nilais'];
                $standard->hasil_nilai = $validatedData['hasil_nilais'];
                $standard->bobot = $validatedData['indikator_bobots'];
                $standard->hasil_kriteria = $validatedData['hasil_kriterias'];
                $standard->hasil_deskripsi = $validatedData['hasil_deskripsis'];
                $standard->jenis_temuan = $validatedData['jenis_temuans'];
                $standard->hasil_akibat = $validatedData['hasil_akibats'];
                $standard->hasil_masalah = $validatedData['hasil_masalahs'];
                $standard->hasil_rekomendasi = $validatedData['hasil_rekomendasis'];
                $standard->prodi = $validatedData['prodis'];
                $standard->periode = $validatedData['periodes'];

                if ($standard->save()) {
                    Log::info('Data saved successfully:', $standard->toArray());
                    return redirect()->back()->with('success', 'Data berhasil disimpan!');
                } else {
                    Log::error('Failed to save data.');
                    return redirect()->back()->with('error', 'Failed to save data.');
                }
            }
        } catch (\Exception $e) {
            Log::error('Error saving or updating data:', ['message' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function aiAssess(Request $request)
    {
        $request->validate([
            'indikator_id' => 'required|integer',
            'ami_kode'     => 'required|string',
            'prodi'        => 'required|string',
            'periode'      => 'required|string',
        ]);

        $indikator = Indikator::with([
            'dokumen_targets',
            'dokumen_capaians' => fn($q) => $q->where('prodi', $request->prodi)->where('periode', $request->periode),
            'dokumen_nilais'   => fn($q) => $q->where('prodi', $request->prodi)->where('periode', $request->periode),
            'element.standard.akreditasi',
            'element.standard.jenjang',
        ])->findOrFail($request->indikator_id);

        $standarNama    = $indikator->element->standard->nama ?? '-';
        $elemenNama     = $indikator->element->nama ?? '-';
        $mandiriNilai   = $indikator->dokumen_nilais->mandiri_nilai ?? 0;
        $akreditasiNama = $indikator->element->standard->akreditasi->nama ?? 'BAN-PT';
        $jenjangNama    = $indikator->element->standard->jenjang->nama ?? 'S1';

        $targets = $indikator->dokumen_targets->map(fn($t) =>
            "- {$t->dokumen_nama} [{$t->dokumen_tipe}]: {$t->dokumen_keterangan}"
        )->join("\n");

        $capaians = $indikator->dokumen_capaians->map(fn($c) =>
            "- {$c->dokumen_nama} [{$c->dokumen_tipe}]: {$c->dokumen_keterangan}" .
            ($c->dokumen_kadaluarsa ? " (kadaluarsa: {$c->dokumen_kadaluarsa})" : '') .
            ($c->informasi ? " | info: {$c->informasi}" : '')
        )->join("\n");

        // Kerangka penilaian per lembaga akreditasi
        $frameworks = [
            'BAN-PT' => [
                'nama_lengkap' => 'BAN-PT — IAPS 5.1 (PerBAN-PT No. 36 Tahun 2025)',
                'kriteria'     => '4 Kriteria: Budaya Mutu, Relevansi, Akuntabilitas, Diferensiasi Misi.',
                'status'       => "- TERAKREDITASI: semua syarat perlu terpenuhi + skor total ≥ 80%\n- TERAKREDITASI UNGGUL: melampaui SN Dikti pada indikator kunci + skor ≥ 80%\n- TIDAK TERAKREDITASI: tidak memenuhi syarat perlu atau skor < 80%",
                'skala'        => "- 4 = Melampaui SN Dikti → siap Terakreditasi Unggul\n- 3 = Memenuhi SN Dikti penuh → siap Terakreditasi\n- 2 = Sebagian besar memenuhi, celah minor (OB)\n- 1 = Sebagian memenuhi, kesenjangan mayor (KTS)\n- 0 = Tidak memenuhi sama sekali (KTS)",
            ],
            'LAMEMBA' => [
                'nama_lengkap' => 'LAMEMBA — Lembaga Akreditasi Mandiri Ekonomi Manajemen Bisnis dan Akuntansi',
                'kriteria'     => '5 Kriteria, 12 Dimensi, 29 Indikator: (1) Tata Pamong dan Tata Kelola, (2) Pengelolaan Dosen dan Tenaga Kependidikan, (3) Keuangan dan Sarana Prasarana, (4) Pendidikan dan Pengajaran, (5) Penelitian dan Pengabdian kepada Masyarakat. LAMEMBA memiliki 2 instrumen terpisah: instrumen Terakreditasi dan instrumen Terakreditasi Unggul.',
                'status'       => "- TERAKREDITASI: memenuhi seluruh indikator pada instrumen Terakreditasi\n- TERAKREDITASI UNGGUL: memenuhi seluruh indikator pada instrumen Terakreditasi Unggul (standar lebih tinggi)\n- TIDAK TERAKREDITASI: tidak memenuhi syarat perlu pada instrumen Terakreditasi",
                'skala'        => "- 4 = Melampaui standar → siap Terakreditasi Unggul\n- 3 = Memenuhi standar Terakreditasi penuh\n- 2 = Sebagian besar memenuhi, ada celah minor (OB)\n- 1 = Sebagian memenuhi, kesenjangan mayor (KTS)\n- 0 = Tidak ada bukti (KTS)",
            ],
            'LAMINFOKOM' => [
                'nama_lengkap' => 'LAMINFOKOM — Lembaga Akreditasi Mandiri Informatika dan Komputer (IAPS 2.1, 2025)',
                'kriteria'     => '3 Kriteria dengan siklus PPEPP (Penetapan, Pelaksanaan, Evaluasi, Pengendalian, Peningkatan): (1) Budaya Mutu — 10 butir, bobot 40/400; (2) Relevansi Pendidikan — 20 butir, bobot 120/400; (3) Relevansi Penelitian — 15 butir, bobot 240/400. Bobot total = 400. Persentase kategori: Input 15%, Proses 30%, Output/Outcome 55%.',
                'status'       => "FORMULA: NA = Σ(skor_i × bobot_i/4), bobot total = 400, skor 0–4, NA maks = 400.\n- TERAKREDITASI: 200 ≤ NA < 321 (tanpa syarat tambahan)\n- TERAKREDITASI UNGGUL 3 tahun: 321 ≤ NA < 361 + rerata setiap kriteria ≥ 3,20 + setiap butir ≥ 3,00\n- TERAKREDITASI UNGGUL 5 tahun: NA ≥ 361 + rerata setiap kriteria ≥ 3,20 + setiap butir ≥ 3,00\n- Jika NA ≥ 321 tapi ada butir < 3,00 atau rerata kriteria < 3,20 → hanya Terakreditasi (bukan Unggul)\n- TIDAK TERAKREDITASI: NA < 200",
                'skala'        => "- 4 = Sangat Baik — memenuhi/melampaui semua parameter (wajib untuk syarat Unggul)\n- 3 = Baik — memenuhi sebagian besar parameter (ambang minimum syarat Unggul per butir)\n- 2 = Cukup — memenuhi sebagian parameter (OB)\n- 1 = Kurang — tidak memenuhi standar (KTS)\n- 0 = Tidak ada bukti (KTS berat)\nCatatan: syarat Unggul memerlukan setiap butir ≥ 3,00 DAN rerata setiap kriteria ≥ 3,20.",
            ],
            'LAMDIK' => [
                'nama_lengkap' => 'LAMDIK — Lembaga Akreditasi Mandiri Kependidikan (IAPS/IAPSK 3.0)',
                'kriteria'     => '9 Kriteria: (1) Visi Keilmuan PS, (2) Tata Pamong & Tata Kelola UPPS, (3) Mahasiswa, (4) Dosen & Tenaga Kependidikan, (5) Keuangan, Sarana & Prasarana, (6) Pendidikan, (7) Penelitian, (8) Pengabdian kepada Masyarakat, (9) Penjaminan Mutu. Elemen dikelompokkan: Input, Proses, Output (bobot Output > Proses > Input). LAMDIK memiliki 2 instrumen: IAPSK (47 elemen, khusus kependidikan) dan IAPS (65 elemen, umum).',
                'status'       => "FORMULA: NA = Σ(bobot_i × skor_i), dimana Σbobot = 100, skor 1–4, NA maks = 400.\n- TERAKREDITASI: NA ≥ 200 (berlaku 5 tahun)\n- TERAKREDITASI UNGGUL: NA ≥ 361 + memenuhi syarat perlu unggul\n- TIDAK TERAKREDITASI: NA < 200",
                'skala'        => "- 4 = Memenuhi/melampaui SEMUA parameter mutu elemen → kontribusi bobot penuh\n- 3 = Memenuhi sebagian besar parameter, implementasi hampir optimal\n- 2 = Memenuhi sebagian parameter, ada kelemahan signifikan (OB)\n- 1 = Tidak memenuhi standar mutu elemen (KTS)\nCatatan: setiap elemen memiliki bobot berbeda (lihat matriks penilaian). NA = jumlah (bobot × skor) semua elemen.",
            ],
            'LAMPTKES' => [
                'nama_lengkap' => 'LAM-PTKes — Lembaga Akreditasi Mandiri Pendidikan Tinggi Kesehatan',
                'kriteria'     => 'Instrumen berbeda per bidang kesehatan (Kedokteran, Farmasi, Keperawatan, Kebidanan, Gizi, Kesehatan Masyarakat, dll). Dua jenis instrumen: (1) Kualitatif 9 Kriteria — VMTS, Tata Pamong & Kerja Sama, Mahasiswa, SDM, Keuangan & Sarana, Pendidikan, Penelitian, PkM, Luaran & Capaian; (2) Kuantitatif berbasis Laporan Kinerja Program Studi. Penekanan pada kompetensi klinis, fasilitas praktikum, dan capaian lulusan kesehatan.',
                'status'       => "- TERAKREDITASI: memenuhi standar mutu minimal LAM-PTKes (syarat perlu + skor cukup)\n- TERAKREDITASI UNGGUL: melampaui SN Dikti pada kriteria kunci klinis dan luaran\n- TIDAK TERAKREDITASI: tidak memenuhi syarat perlu atau skor di bawah ambang batas",
                'skala'        => "- 4 = Melampaui SN Dikti sepenuhnya → menuju Terakreditasi Unggul\n- 3 = Memenuhi SN Dikti penuh → siap Terakreditasi\n- 2 = Sebagian memenuhi, ada kelemahan signifikan (OB)\n- 1 = Belum memenuhi standar mutu (KTS)\n- 0 = Tidak ada bukti sama sekali (KTS)",
            ],
            'LAMSAMA' => [
                'nama_lengkap' => 'LAMSAMA — Lembaga Akreditasi Mandiri Sains Alam dan Ilmu Formal (IAPS 3.1)',
                'kriteria'     => '6 Kriteria (bukan 9): A. Tata Kelola & Penjaminan Mutu (6 butir), B. Pendidikan & Pengajaran (12 butir), C. Penelitian (4 butir), D. Pengabdian kepada Masyarakat (4 butir), E. Capaian & Luaran (2 butir), F. Analisis Capaian & Program Pengembangan (1 butir). Total 24 butir untuk Terakreditasi, 35 butir untuk Terakreditasi Unggul. Bidang: MIPA dan sains formal.',
                'status'       => "MEKANISME: tidak ada formula NA — penilaian berbasis ambang minimum PER BUTIR.\n- TERAKREDITASI: SEMUA 24 butir mencapai minimal CUKUP (tidak boleh ada yang KURANG)\n- TERAKREDITASI UNGGUL: 35 butir (instrumen berbeda), sebagian besar harus BAIK SEKALI, sisanya minimal BAIK atau CUKUP\n- TIDAK TERAKREDITASI: ada butir yang KURANG (di bawah ambang minimum)",
                'skala'        => "- BAIK SEKALI (4) = Memenuhi/melampaui SEMUA parameter → wajib untuk Unggul\n- BAIK (3) = Memenuhi sebagian besar parameter\n- CUKUP (2) = Memenuhi parameter minimum → ambang batas Terakreditasi\n- KURANG (1) = Tidak memenuhi standar mutu → butir gagal, risiko Tidak Terakreditasi\nCatatan: tidak ada skor 0. Setiap butir dinilai 1–4. Status ditentukan oleh butir terlemah.",
            ],
            'LAMTEKNIK' => [
                'nama_lengkap' => 'LAM Teknik — Lembaga Akreditasi Mandiri Program Studi Keteknikan (IAPS AVP 2025)',
                'kriteria'     => '4 Kriteria: (1) Diferensiasi Misi — VMTS UPPS dan visi keilmuan PS; (2) Akuntabilitas — Tata Pamong, Tata Kelola, Kerja Sama, Keuangan; (3) Relevansi — SDM, Pendidikan, Penelitian, PkM, Sarana Prasarana; (4) Budaya Mutu — SPMI berbasis siklus PPEPP. Jumlah butir per jenjang: D1/D2/D3=56, S1=60, STr=64, M=55, MTr=58, D=53, DTr=56, PPI=54. Bobot: Input 25%, Proses 35%, Output/Outcome 40%.',
                'status'       => "FORMULA: NA = Σ(bobot_i × skor_i), Σbobot = 100, skor 0–4, NA maks = 400.\n- TERAKREDITASI UNGGUL 5 tahun: NA ≥ 361 + memenuhi syarat perlu Unggul (rerata skor ≥ 3.50)\n- TERAKREDITASI UNGGUL 3 tahun: NA ≥ 361 + memenuhi syarat perlu Unggul (rerata skor ≥ 3.00) ATAU 331 ≤ NA < 361 + syarat perlu Unggul terpenuhi\n- TERAKREDITASI 5 tahun: 200 ≤ NA < 331 + syarat perlu terpenuhi ATAU 331 ≤ NA < 361 + syarat Unggul tidak terpenuhi\n- TIDAK TERAKREDITASI: NA < 200 ATAU syarat perlu Terakreditasi tidak terpenuhi",
                'skala'        => "- 4 = Memenuhi/melampaui SEMUA parameter → kontribusi bobot penuh\n- 3 = Memenuhi sebagian besar parameter (ambang syarat perlu Unggul)\n- 2 = Memenuhi sebagian parameter, kelemahan signifikan (OB)\n- 1 = Tidak memenuhi standar mutu elemen (KTS)\n- 0 = Tidak ada bukti sama sekali (KTS berat)\nCatatan: ada skor 0. Syarat perlu Unggul mensyaratkan rerata skor per kriteria ≥ 3.00 (3 tahun) atau ≥ 3.50 (5 tahun).",
            ],
        ];

        // Pilih framework sesuai akreditasi, fallback ke generik
        $fw = $frameworks[$akreditasiNama] ?? [
            'nama_lengkap' => $akreditasiNama . ' — Lembaga Akreditasi Program Studi',
            'kriteria'     => 'Mengacu pada standar akreditasi ' . $akreditasiNama . ' yang berlaku.',
            'status'       => "- TERAKREDITASI: memenuhi standar minimal\n- TERAKREDITASI UNGGUL: melampaui standar\n- TIDAK TERAKREDITASI: tidak memenuhi syarat perlu",
            'skala'        => "- 4 = Melampaui standar → siap Unggul\n- 3 = Memenuhi standar → siap Terakreditasi\n- 2 = Sebagian memenuhi (OB)\n- 1 = Belum memenuhi (KTS)\n- 0 = Tidak ada bukti (KTS)",
        ];

        $prompt = <<<PROMPT
Anda adalah auditor AMI (Audit Mutu Internal) perguruan tinggi yang profesional, bertugas mengevaluasi kesiapan program studi menghadapi akreditasi {$akreditasiNama} jenjang {$jenjangNama}.

=== LEMBAGA AKREDITASI ===
{$fw['nama_lengkap']}

=== KRITERIA PENILAIAN ===
{$fw['kriteria']}

=== SKALA NILAI (0–4) ===
{$fw['skala']}

=== KETENTUAN STATUS AKREDITASI ===
{$fw['status']}

=== JENIS TEMUAN AMI ===
- "Sesuai" → nilai 3–4: memenuhi/melampaui standar, tidak ada tindak lanjut wajib
- "OB" (Observasi) → nilai 2: ada peluang perbaikan, belum menjadi ketidaksesuaian
- "KTS" (Ketidaksesuaian) → nilai 0–1: tidak memenuhi standar, wajib ditindaklanjuti

=== KONTEKS INDIKATOR ===
Standar   : {$standarNama}
Elemen    : {$elemenNama}
Indikator : {$indikator->nama_indikator}
Kode      : {$indikator->indikator_kode}
Deskriptor/Aspek Penilaian:
{$indikator->info}

=== DOKUMEN YANG DIBUTUHKAN (Target) ===
{$targets}

=== DOKUMEN YANG TELAH DIUNGGAH PRODI (Capaian) ===
{$capaians}

=== NILAI MANDIRI PRODI ===
{$mandiriNilai} / 4

=== TUGAS ===
Evaluasi kesiapan prodi pada indikator ini berdasarkan standar {$akreditasiNama} dan tentukan:
- hasil_nilai: angka 0–4 sesuai skala di atas
- jenis_temuan: "Sesuai", "OB", atau "KTS"
- hasil_kriteria: standar/kriteria {$akreditasiNama} yang menjadi acuan (1–2 kalimat)
- hasil_deskripsi: temuan auditor — apa yang sudah terpenuhi dan apa yang belum (2–4 kalimat)
- hasil_akibat: dampak terhadap status akreditasi {$akreditasiNama} jika tidak diperbaiki (1–2 kalimat)
- hasil_masalah: akar masalah ketidaksesuaian (1–2 kalimat, atau "-" jika Sesuai)
- hasil_rekomendasi: rekomendasi konkret untuk perbaikan (1–2 kalimat)

Kembalikan HANYA JSON valid tanpa komentar, tanpa markdown:
{"hasil_nilai":0,"jenis_temuan":"","hasil_kriteria":"","hasil_deskripsi":"","hasil_akibat":"","hasil_masalah":"","hasil_rekomendasi":""}
PROMPT;

        // Model waterfall: paling cerdas → paling banyak kuota
        $models = [
            'gemini-2.5-flash',       // 20 RPD, paling cerdas
            'gemini-2.5-flash-lite',  // 20 RPD
            'gemini-3.1-flash-lite',  // 500 RPD
            'gemma-4-26b-a4b-it',     // 1500 RPD, unlimited TPM
        ];

        $payload = [
            'contents' => [['parts' => [['text' => $prompt]]]],
            'generationConfig' => [
                'temperature'      => 0.3,
                'responseMimeType' => 'application/json',
            ],
        ];

        $lastError = null;

        foreach ($models as $model) {
            try {
                $response = Http::timeout(60)->post(
                    "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key=" . env('GEMINI_API_KEY'),
                    $payload
                );

                if ($response->status() === 429 || $response->status() === 503) {
                    Log::info("Gemini model {$model} rate limited ({$response->status()}), trying next");
                    continue;
                }

                if (!$response->successful()) {
                    Log::warning("Gemini model {$model} error {$response->status()}", ['body' => $response->body()]);
                    continue;
                }

                $text = $response->json('candidates.0.content.parts.0.text') ?? '{}';
                $data = json_decode($text, true);

                if (!$data || !isset($data['hasil_nilai'])) {
                    return response()->json(['error' => 'Respons AI tidak valid', 'raw' => $text], 500);
                }

                $data['_model_used'] = $model;
                return response()->json($data);

            } catch (\Exception $e) {
                Log::warning("Gemini model {$model} exception: " . $e->getMessage());
                $lastError = $e->getMessage();
                continue;
            }
        }

        Log::error('Semua model Gemini gagal', ['last_error' => $lastError]);
        return response()->json(['error' => 'Semua model AI tidak tersedia saat ini, coba lagi nanti.'], 503);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id' => 'required',
            'status' => 'required',
        ]);

        $pengajuan = TransaksiAmi::findOrFail($id);
        $pengajuan->status = $request->status;
        $pengajuan->save();

        return redirect()->route('auditor.evaluasi-ami.index')->with('success', 'Pengajuan updated successfully.');
    }

}
