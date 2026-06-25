@php
    $bgMap  = ['green' => '#d4edda', 'blue' => '#d1ecf1', 'orange' => '#fff3cd', 'red' => '#f8d7da', 'gray' => '#e2e3e5'];
    $clrMap = ['green' => '#155724', 'blue' => '#0c5460', 'orange' => '#856404', 'red' => '#721c24', 'gray' => '#383d41'];
    $bg     = $bgMap[$warnaStatus]  ?? '#e2e3e5';
    $clr    = $clrMap[$warnaStatus] ?? '#383d41';

    // Computed rerata from AMI (mapped from standards)
    $amiChecks = [
        [
            'label'    => 'Sistem Tata Pamong (Akuntabilitas)',
            'keterangan' => 'Rerata skor butir Kriteria II. Akuntabilitas',
            'rerata'   => $rerataAkuntabilitas,
            'ok3'      => $syaratUnggul3Akuntabilitas,  // ≥ 3.00
            'ok5'      => $syaratUnggul5Akuntabilitas,  // ≥ 3.50
            'okTer'    => $syaratTerAkuntabilitas,      // ≥ 2.00
        ],
        [
            'label'    => 'Kurikulum (Relevansi Pendidikan)',
            'keterangan' => 'Rerata skor butir Kriteria III. Relevansi Pendidikan, Penelitian, dan PkM',
            'rerata'   => $rerataRelevansi,
            'ok3'      => $syaratUnggul3Relevansi,
            'ok5'      => $syaratUnggul5Relevansi,
            'okTer'    => $syaratTerRelevansi,
        ],
        [
            'label'    => 'Sistem Penjaminan Mutu (SPMI)',
            'keterangan' => 'Rerata skor butir Kriteria VII. Sistem Penjaminan Mutu',
            'rerata'   => $rerataSpmi,
            'ok3'      => $syaratUnggul3Spmi,
            'ok5'      => $syaratUnggul5Spmi,
            'okTer'    => $syaratTerSpmi,
        ],
    ];

    // Tabel 4.1.1 — Syarat Perlu Terakreditasi Unggul Seluruh Jenjang (LAM Teknik IAPS AVP 2025)
    // Kolom jenjang: S1, S2, S3, D3, S1T (Sarjana Terapan), S2T (Magister Terapan), S3T (Doktor Terapan), PPI
    // ** = Unggul 3 Tahun; *** = Unggul 5 Tahun; × = tidak dipersyaratkan
    $tabel411 = [
        [
            'no'   => 1,
            'kriteria'   => 'Sistem Tata Pamong',
            'indikator'  => 'Rerata skor butir pada kriteria tata pamong',
            'computed'   => true,
            'S1'   => ['≥ 3,00', '≥ 3,50'],  'S2'  => ['≥ 3,00','≥ 3,50'],
            'S3'   => ['≥ 3,00', '≥ 3,50'],  'D3'  => ['×',     '×'     ],
            'S1T'  => ['≥ 3,00', '≥ 3,50'],  'S2T' => ['≥ 3,00','≥ 3,50'],
            'S3T'  => ['≥ 3,00', '≥ 3,50'],  'PPI' => ['≥ 3,00','≥ 3,50'],
        ],
        [
            'no'   => 2,
            'kriteria'   => 'Kurikulum',
            'indikator'  => 'Rerata skor butir pada kriteria kurikulum',
            'computed'   => true,
            'S1'   => ['≥ 3,00', '≥ 3,50'],  'S2'  => ['≥ 3,00','≥ 3,50'],
            'S3'   => ['≥ 3,00', '≥ 3,50'],  'D3'  => ['≥ 3,00','≥ 3,50'],
            'S1T'  => ['≥ 3,00', '≥ 3,50'],  'S2T' => ['≥ 3,00','≥ 3,50'],
            'S3T'  => ['≥ 3,00', '≥ 3,50'],  'PPI' => ['≥ 3,00','≥ 3,50'],
        ],
        [
            'no'   => 3,
            'kriteria'   => 'Ketersediaan MK Basic Sciences & Matematika',
            'indikator'  => 'Jumlah SKS MK basic sciences dan matematika',
            'computed'   => false,
            'S1'   => ['≥ 25 SKS', '≥ 25 SKS'], 'S2'  => ['×','×'],
            'S3'   => ['×',         '×'        ], 'D3'  => ['×','×'],
            'S1T'  => ['×',         '×'        ], 'S2T' => ['×','×'],
            'S3T'  => ['×',         '×'        ], 'PPI' => ['≥ 4 SKS','≥ 4 SKS'],
        ],
        [
            'no'   => 4,
            'kriteria'   => 'Kualifikasi Akademik DTPS (Doktor)',
            'indikator'  => 'Persentase DTPS bergelar Doktor terhadap total DTPS',
            'computed'   => false,
            'S1'   => ['≥ 30%', '≥ 40%'], 'S2'  => ['×','×'],
            'S3'   => ['×',      '×'     ], 'D3'  => ['×','×'],
            'S1T'  => ['×',      '×'     ], 'S2T' => ['×','×'],
            'S3T'  => ['×',      '×'     ], 'PPI' => ['≥ 12,5%','≥ 15%'],
        ],
        [
            'no'   => 5,
            'kriteria'   => 'Jabatan Akademik DTPS',
            'indikator'  => 'Persentase DTPS berjabatan Guru Besar / Lektor Kepala / Lektor',
            'computed'   => false,
            'S1'   => ['≥ 40% GB/LK/L', '≥ 50%'], 'S2'  => ['≥ 40% GB/LK','≥ 50%'],
            'S3'   => ['≥ 40% GB',       '≥ 50%'], 'D3'  => ['×',          '×'     ],
            'S1T'  => ['≥ 30%',           '≥ 40%'], 'S2T' => ['≥ 40% GB/LK','≥ 50%'],
            'S3T'  => ['≥ 40% GB',        '≥ 50%'], 'PPI' => ['×',           '×'    ],
        ],
        [
            'no'   => 6,
            'kriteria'   => 'Rasio NDTPSPPI / NDTI',
            'indikator'  => 'Rasio dosen tetap PSPPI terhadap dosen industri (khusus PPI)',
            'computed'   => false,
            'S1'   => ['×','×'], 'S2'  => ['×','×'],
            'S3'   => ['×','×'], 'D3'  => ['×','×'],
            'S1T'  => ['×','×'], 'S2T' => ['×','×'],
            'S3T'  => ['×','×'], 'PPI' => ['2,00 < skor < 3,00','≥ 3,00'],
        ],
        [
            'no'   => 7,
            'kriteria'   => 'Kinerja DTPS',
            'indikator'  => 'Persentase DTPS dengan publikasi jurnal internasional bereputasi / prosiding Scopus / paten',
            'computed'   => false,
            'S1'   => ['≥ 25%', '≥ 50%'], 'S2'  => ['≥ 25%','≥ 50%'],
            'S3'   => ['≥ 25%', '≥ 50%'], 'D3'  => ['≥ 7,5%','≥ 10%'],
            'S1T'  => ['≥ 20%', '≥ 30%'], 'S2T' => ['×',      '×'    ],
            'S3T'  => ['≥ 30%', '≥ 40%'], 'PPI' => ['×',       '×'   ],
        ],
        [
            'no'   => 8,
            'kriteria'   => 'Publikasi DTPS (Rasio)',
            'indikator'  => 'Rasio publikasi DTPS terhadap jumlah DTPS (khusus PPI)',
            'computed'   => false,
            'S1'   => ['×','×'], 'S2'  => ['×','×'],
            'S3'   => ['×','×'], 'D3'  => ['×','×'],
            'S1T'  => ['×','×'], 'S2T' => ['×','×'],
            'S3T'  => ['×','×'], 'PPI' => ['≥ 0,2','≥ 0,5'],
        ],
        [
            'no'   => 9,
            'kriteria'   => 'Publikasi Ilmiah Mahasiswa',
            'indikator'  => 'Rasio mahasiswa yang publikasi jurnal (khusus Magister)',
            'computed'   => false,
            'S1'   => ['×','×'], 'S2'  => ['≥ 0,05','≥ 0,1'],
            'S3'   => ['×','×'], 'D3'  => ['×','×'],
            'S1T'  => ['×','×'], 'S2T' => ['×','×'],
            'S3T'  => ['×','×'], 'PPI' => ['×','×'],
        ],
        [
            'no'   => 10,
            'kriteria'   => 'Publikasi Ilmiah Mahasiswa (Scopus)',
            'indikator'  => 'Rasio mahasiswa yang publikasi Scopus (khusus Doktor)',
            'computed'   => false,
            'S1'   => ['×','×'], 'S2'  => ['×','×'],
            'S3'   => ['≥ 0,125','≥ 0,25'], 'D3'  => ['×','×'],
            'S1T'  => ['×','×'], 'S2T' => ['×','×'],
            'S3T'  => ['×','×'], 'PPI' => ['×','×'],
        ],
        [
            'no'   => 11,
            'kriteria'   => 'Pagelaran / Publikasi Mahasiswa',
            'indikator'  => 'Rasio pagelaran / publikasi mahasiswa (khusus Sarjana Terapan)',
            'computed'   => false,
            'S1'   => ['×','×'], 'S2'  => ['×','×'],
            'S3'   => ['×','×'], 'D3'  => ['×','×'],
            'S1T'  => ['≥ 0,05','≥ 0,1'], 'S2T' => ['×','×'],
            'S3T'  => ['×','×'], 'PPI' => ['×','×'],
        ],
        [
            'no'   => 12,
            'kriteria'   => 'Pagelaran / Publikasi Mahasiswa (Bereputasi)',
            'indikator'  => 'Rasio pagelaran / publikasi mahasiswa bereputasi (khusus Magister Terapan)',
            'computed'   => false,
            'S1'   => ['×','×'], 'S2'  => ['×','×'],
            'S3'   => ['×','×'], 'D3'  => ['×','×'],
            'S1T'  => ['×','×'], 'S2T' => ['≥ 0,125','≥ 0,25'],
            'S3T'  => ['×','×'], 'PPI' => ['×','×'],
        ],
        [
            'no'   => 13,
            'kriteria'   => 'Waktu Tunggu Lulusan',
            'indikator'  => 'Rata-rata masa tunggu lulusan mendapatkan pekerjaan pertama',
            'computed'   => false,
            'S1'   => ['≤ 6 bulan','≤ 6 bulan'], 'S2'  => ['×','×'],
            'S3'   => ['×','×'],                  'D3'  => ['≤ 3 bulan','≤ 3 bulan'],
            'S1T'  => ['≤ 6 bulan','≤ 6 bulan'], 'S2T' => ['×','×'],
            'S3T'  => ['×','×'],                  'PPI' => ['×','×'],
        ],
        [
            'no'   => 14,
            'kriteria'   => 'Kesesuaian Bidang Kerja',
            'indikator'  => 'Persentase lulusan yang bekerja sesuai bidang studi',
            'computed'   => false,
            'S1'   => ['≥ 50%','≥ 50%'], 'S2'  => ['≥ 50%','≥ 50%'],
            'S3'   => ['×','×'],           'D3'  => ['≥ 70%','≥ 70%'],
            'S1T'  => ['≥ 50%','≥ 50%'], 'S2T' => ['×','×'],
            'S3T'  => ['×','×'],           'PPI' => ['×','×'],
        ],
        [
            'no'   => 15,
            'kriteria'   => 'Sistem Penjaminan Mutu (SPMI)',
            'indikator'  => 'Rerata skor butir pada kriteria penjaminan mutu',
            'computed'   => true,
            'S1'   => ['≥ 3,00','≥ 3,50'], 'S2'  => ['≥ 3,00','≥ 3,50'],
            'S3'   => ['≥ 3,00','≥ 3,50'], 'D3'  => ['≥ 3,00','≥ 3,50'],
            'S1T'  => ['≥ 3,00','≥ 3,50'], 'S2T' => ['≥ 3,00','≥ 3,50'],
            'S3T'  => ['≥ 3,00','≥ 3,50'], 'PPI' => ['≥ 3,00','≥ 3,50'],
        ],
    ];

    $jenjangKols = [
        'S1'  => 'Sarjana / S1',
        'S2'  => 'Magister / S2',
        'S3'  => 'Doktor / S3',
        'D3'  => 'Diploma III / D3',
        'S1T' => 'Sarjana Terapan',
        'S2T' => 'Magister Terapan',
        'S3T' => 'Doktor Terapan',
        'PPI' => 'Profesi Insinyur',
    ];
@endphp

{{-- ============================================================
     I. SYARAT PERLU TERAKREDITASI UNGGUL — Dari AMI (Tata Pamong, Kurikulum, SPMI)
     ============================================================ --}}
<div class="card mb-4">
    <div class="card-header bg-primary text-white fw-bold">
        I. SYARAT PERLU TERAKREDITASI UNGGUL — Nilai AMI
        <span class="fw-normal small ms-2">(Kriteria yang dapat dievaluasi dari data AMI)</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered align-middle mb-0 text-center">
                <thead class="table-light">
                    <tr>
                        <th class="text-start" style="width:35%">Kriteria (dari Tabel 4.1.1 LAM Teknik)</th>
                        <th>Rerata Skor AMI</th>
                        <th>Syarat Terakreditasi<br><small class="fw-normal">rerata ≥ 2,00</small></th>
                        <th>Syarat Unggul 3 Thn **<br><small class="fw-normal">rerata ≥ 3,00</small></th>
                        <th>Syarat Unggul 5 Thn ***<br><small class="fw-normal">rerata ≥ 3,50</small></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($amiChecks as $c)
                    <tr>
                        <td class="text-start">
                            <div class="fw-semibold">{{ $c['label'] }}</div>
                            <div class="text-muted small">{{ $c['keterangan'] }}</div>
                        </td>
                        <td>
                            @if($c['rerata'] > 0)
                                <span class="fw-bold fs-5 {{ $c['ok3'] ? 'text-success' : ($c['okTer'] ? 'text-warning-emphasis' : 'text-danger') }}">
                                    {{ number_format($c['rerata'], 2) }}
                                </span>
                                <div class="text-muted small">dari maks 4,00</div>
                            @else
                                <span class="text-muted fst-italic">Belum ada data</span>
                            @endif
                        </td>
                        <td>
                            @if($c['rerata'] > 0)
                                @if($c['okTer'])
                                    <span class="badge bg-success px-3">✓ Terpenuhi</span>
                                @else
                                    <span class="badge bg-danger px-3">✗ Belum</span>
                                    <div class="small text-danger mt-1">Perlu +{{ number_format(2.0 - $c['rerata'], 2) }} poin</div>
                                @endif
                            @else
                                <span class="badge bg-secondary">—</span>
                            @endif
                        </td>
                        <td>
                            @if($c['rerata'] > 0)
                                @if($c['ok3'])
                                    <span class="badge bg-success px-3">✓ Terpenuhi</span>
                                @else
                                    <span class="badge bg-warning text-dark px-3">✗ Belum</span>
                                    <div class="small text-muted mt-1">Perlu +{{ number_format(3.0 - $c['rerata'], 2) }} poin</div>
                                @endif
                            @else
                                <span class="badge bg-secondary">—</span>
                            @endif
                        </td>
                        <td>
                            @if($c['rerata'] > 0)
                                @if($c['ok5'])
                                    <span class="badge bg-success px-3">✓ Terpenuhi</span>
                                @else
                                    <span class="badge bg-warning text-dark px-3">✗ Belum</span>
                                    <div class="small text-muted mt-1">Perlu +{{ number_format(3.5 - $c['rerata'], 2) }} poin</div>
                                @endif
                            @else
                                <span class="badge bg-secondary">—</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-secondary fw-bold">
                    <tr>
                        <td class="text-start">Kesimpulan Syarat (dari AMI)</td>
                        <td>—</td>
                        <td>
                            @if($syaratTerakreditasi)
                                <span class="badge bg-success px-3">✓ Terpenuhi</span>
                            @else
                                <span class="badge bg-danger px-3">✗ Belum Terpenuhi</span>
                            @endif
                        </td>
                        <td>
                            @if($syaratUnggul3Terpenuhi)
                                <span class="badge bg-success px-3">✓ Terpenuhi</span>
                            @else
                                <span class="badge bg-warning text-dark px-3">✗ Belum Terpenuhi</span>
                            @endif
                        </td>
                        <td>
                            @if($syaratUnggul5Terpenuhi)
                                <span class="badge bg-success px-3">✓ Terpenuhi</span>
                            @else
                                <span class="badge bg-warning text-dark px-3">✗ Belum Terpenuhi</span>
                            @endif
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

{{-- ============================================================
     II. SYARAT PERLU TERAKREDITASI UNGGUL SELURUH JENJANG (Tabel 4.1.1)
     ============================================================ --}}
<div class="card mb-4">
    <div class="card-header bg-info text-dark fw-bold">
        II. SYARAT PERLU TERAKREDITASI UNGGUL SELURUH JENJANG PROGRAM STUDI
        <span class="fw-normal small ms-2">(Tabel 4.1.1 — Pedoman Penilaian Instrumen LAM Teknik IAPS AVP 2025)</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered align-middle mb-0 small">
                <thead class="table-dark">
                    <tr>
                        <th rowspan="2" style="width:3%" class="text-center align-middle">No</th>
                        <th rowspan="2" style="width:18%" class="align-middle">Kriteria</th>
                        <th rowspan="2" style="width:20%" class="align-middle">Indikator</th>
                        @foreach($jenjangKols as $key => $label)
                        <th colspan="2" class="text-center">{{ $label }}</th>
                        @endforeach
                    </tr>
                    <tr>
                        @foreach($jenjangKols as $key => $label)
                        <th class="text-center text-warning-emphasis bg-dark" title="Unggul 3 Tahun">**</th>
                        <th class="text-center text-info bg-dark" title="Unggul 5 Tahun">***</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($tabel411 as $row)
                    <tr class="{{ $row['computed'] ? 'table-light' : '' }}">
                        <td class="text-center fw-bold">{{ $row['no'] }}</td>
                        <td class="fw-semibold">
                            {{ $row['kriteria'] }}
                            @if($row['computed'])
                                <span class="badge bg-primary ms-1" title="Dapat dievaluasi dari data AMI">AMI</span>
                            @endif
                        </td>
                        <td class="text-muted">{{ $row['indikator'] }}</td>
                        @foreach($jenjangKols as $key => $label)
                        @php
                            $v3 = $row[$key][0] ?? '×';
                            $v5 = $row[$key][1] ?? '×';
                        @endphp
                        <td class="text-center {{ $v3 === '×' ? 'text-muted bg-light' : 'fw-semibold text-dark' }}">
                            {{ $v3 }}
                        </td>
                        <td class="text-center {{ $v5 === '×' ? 'text-muted bg-light' : 'fw-semibold text-dark' }}">
                            {{ $v5 }}
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer text-muted small">
        <strong>Keterangan:</strong>
        <span class="me-3"><strong>**</strong> = Syarat Perlu Terakreditasi Unggul 3 Tahun</span>
        <span class="me-3"><strong>***</strong> = Syarat Perlu Terakreditasi Unggul 5 Tahun</span>
        <span class="me-3"><strong>×</strong> = Tidak dipersyaratkan untuk jenjang tersebut</span>
        <span class="badge bg-primary me-1">AMI</span> = Dapat dievaluasi dari skor AMI (Bagian I).
        Kriteria lainnya memerlukan data LKPS (Laporan Kinerja Program Studi) yang diverifikasi LAM Teknik.
    </div>
</div>

{{-- ============================================================
     III. STATUS AKREDITASI BERDASARKAN NILAI AKREDITASI
     ============================================================ --}}
<div class="card mb-4">
    <div class="card-header fw-bold" style="background-color:{{ $bg }};color:{{ $clr }}">
        III. STATUS AKREDITASI BERDASARKAN NILAI AKREDITASI (NA)
    </div>
    <div class="card-body">

        {{-- Rerata per standar --}}
        <h6 class="fw-bold mb-2">Rerata Skor Per Kriteria</h6>
        <div class="table-responsive mb-4">
            <table class="table table-bordered align-middle mb-0 text-center small">
                <thead class="table-light">
                    <tr>
                        <th class="text-start" style="width:45%">Kriteria / Standar</th>
                        <th>Butir Dinilai</th>
                        <th>Rerata Skor</th>
                        <th>Syarat Terakreditasi (≥ 2,00)</th>
                        <th>Syarat Unggul 3 Thn (≥ 3,00)</th>
                        <th>Syarat Unggul 5 Thn (≥ 3,50)</th>
                    </tr>
                </thead>
                <tbody>
                    @php $totalButir = 0; @endphp
                    @forelse($perStandar as $s)
                    @php
                        $totalButir += $s['jumlah_butir'];
                        $r = $s['rerata'];
                    @endphp
                    <tr>
                        <td class="text-start fw-semibold">{{ $s['nama'] }}</td>
                        <td>{{ $s['jumlah_butir'] ?: '—' }}</td>
                        <td>
                            @if($s['jumlah_butir'] > 0)
                                <span class="fw-bold {{ $r >= 3.5 ? 'text-success' : ($r >= 3.0 ? 'text-primary' : ($r >= 2.0 ? 'text-warning-emphasis' : 'text-danger')) }}">
                                    {{ number_format($r, 2) }}
                                </span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            @if($s['jumlah_butir'] > 0)
                                @if($r >= 2.0)
                                    <span class="badge bg-success">✓</span>
                                @else
                                    <span class="badge bg-danger">✗</span>
                                @endif
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            @if($s['jumlah_butir'] > 0)
                                @if($r >= 3.0)
                                    <span class="badge bg-success">✓</span>
                                @else
                                    <span class="badge bg-secondary">✗</span>
                                @endif
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            @if($s['jumlah_butir'] > 0)
                                @if($r >= 3.5)
                                    <span class="badge bg-success">✓</span>
                                @else
                                    <span class="badge bg-secondary">✗</span>
                                @endif
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted py-3">Belum ada data standar.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Summary cards --}}
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card h-100 text-center border-0 shadow-sm">
                    <div class="card-body py-3">
                        <div class="text-muted small mb-1">Butir Dinilai</div>
                        <div class="display-6 fw-bold">{{ $totalButir }}</div>
                        <div class="text-muted small">indikator</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100 text-center border-0 shadow-sm">
                    <div class="card-body py-3">
                        <div class="text-muted small mb-1">Nilai Akreditasi (NA)</div>
                        <div class="display-6 fw-bold">{{ number_format($naTotal, 0) }}</div>
                        <div class="text-muted small">dari maks 400</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100 text-center border-0 shadow-sm">
                    <div class="card-body py-3">
                        <div class="text-muted small mb-1">Syarat Terakreditasi (AMI)</div>
                        <div class="mt-1">
                            @if($syaratTerakreditasi)
                                <span class="badge bg-success fs-6 px-3 py-2">✓ Terpenuhi</span>
                            @else
                                <span class="badge bg-danger fs-6 px-3 py-2">✗ Belum</span>
                            @endif
                        </div>
                        <div class="text-muted small mt-1">Tata Pamong, Kurikulum, SPMI ≥ 2,00</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100 text-center border-0 shadow-sm" style="background-color:{{ $bg }}">
                    <div class="card-body py-3">
                        <div class="small mb-1" style="color:{{ $clr }};opacity:.8">Prediksi Status</div>
                        <div class="fw-bold fs-5" style="color:{{ $clr }}">{{ $statusAkreditasi }}</div>
                        @if($durasiAkreditasi !== '-')
                        <div class="small fw-semibold" style="color:{{ $clr }}">{{ $durasiAkreditasi }}</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabel 3 — Status Akreditasi Berdasarkan Nilai Akreditasi --}}
        <h6 class="fw-bold mb-2">Tabel 3 — Status Akreditasi Berdasarkan Nilai Akreditasi (LAM Teknik IAPS AVP 2025)</h6>
        <div class="table-responsive mb-3">
            <table class="table table-bordered align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th style="width:5%" class="text-center">No</th>
                        <th>Nilai Akreditasi (NA)</th>
                        <th>Syarat Terakreditasi</th>
                        <th>Syarat Unggul 3 Thn (**)</th>
                        <th>Syarat Unggul 5 Thn (***)</th>
                        <th>Status Akreditasi</th>
                        <th class="text-center">Durasi</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $na = $naTotal;
                        $row1Active = ($na >= 361 && $syaratTerakreditasi && $syaratUnggul3Terpenuhi && $syaratUnggul5Terpenuhi);
                        $row2Active = ($na >= 361 && $syaratTerakreditasi && $syaratUnggul3Terpenuhi && !$syaratUnggul5Terpenuhi);
                        $row3Active = ($na >= 331 && $na < 361 && $syaratTerakreditasi && $syaratUnggul3Terpenuhi);
                        $row4Active = ($na >= 331 && $na < 361 && $syaratTerakreditasi && !$syaratUnggul3Terpenuhi);
                        $row5Active = ($na >= 200 && $na < 331 && $syaratTerakreditasi);
                        $row6Active = ($na >= 200 && !$syaratTerakreditasi);
                        $row7Active = ($na < 200);
                    @endphp
                    <tr class="{{ $row1Active ? 'table-success fw-bold border border-success border-3' : '' }}">
                        <td class="text-center">1</td>
                        <td>NA ≥ 361</td>
                        <td class="text-success">✓ Terpenuhi</td>
                        <td class="text-success">✓ Terpenuhi</td>
                        <td class="text-success">✓ Terpenuhi</td>
                        <td><span class="badge bg-success px-3 py-2">Terakreditasi Unggul</span></td>
                        <td class="text-center fw-bold">5 Tahun</td>
                    </tr>
                    <tr class="{{ $row2Active ? 'table-success fw-bold border border-success border-3' : '' }}">
                        <td class="text-center">2</td>
                        <td>NA ≥ 361</td>
                        <td class="text-success">✓ Terpenuhi</td>
                        <td class="text-success">✓ Terpenuhi</td>
                        <td class="text-muted">× Tidak Terpenuhi</td>
                        <td><span class="badge bg-success px-3 py-2">Terakreditasi Unggul</span></td>
                        <td class="text-center fw-bold">3 Tahun</td>
                    </tr>
                    <tr class="{{ $row3Active ? 'table-success fw-bold border border-success border-3' : '' }}">
                        <td class="text-center">3</td>
                        <td>331 ≤ NA &lt; 361</td>
                        <td class="text-success">✓ Terpenuhi</td>
                        <td class="text-success">✓ Terpenuhi</td>
                        <td class="text-muted">✓ atau ×</td>
                        <td><span class="badge bg-success px-3 py-2">Terakreditasi Unggul</span></td>
                        <td class="text-center fw-bold">3 Tahun</td>
                    </tr>
                    <tr class="{{ $row4Active ? 'table-info fw-bold border border-info border-3' : '' }}">
                        <td class="text-center">4</td>
                        <td>331 ≤ NA &lt; 361</td>
                        <td class="text-success">✓ Terpenuhi</td>
                        <td class="text-muted">× Tidak Terpenuhi</td>
                        <td class="text-muted">×</td>
                        <td><span class="badge bg-info text-dark px-3 py-2">Terakreditasi</span></td>
                        <td class="text-center fw-bold">5 Tahun</td>
                    </tr>
                    <tr class="{{ $row5Active ? 'table-info fw-bold border border-info border-3' : '' }}">
                        <td class="text-center">5</td>
                        <td>200 ≤ NA &lt; 331</td>
                        <td class="text-success">✓ Terpenuhi</td>
                        <td class="text-muted">—</td>
                        <td class="text-muted">—</td>
                        <td><span class="badge bg-info text-dark px-3 py-2">Terakreditasi</span></td>
                        <td class="text-center fw-bold">5 Tahun</td>
                    </tr>
                    <tr class="{{ $row6Active ? 'table-danger fw-bold border border-danger border-3' : '' }}">
                        <td class="text-center">6</td>
                        <td>NA ≥ 200</td>
                        <td class="text-danger">× Tidak Terpenuhi</td>
                        <td class="text-muted">—</td>
                        <td class="text-muted">—</td>
                        <td><span class="badge bg-danger px-3 py-2">Tidak Terakreditasi</span></td>
                        <td class="text-center">—</td>
                    </tr>
                    <tr class="{{ $row7Active ? 'table-danger fw-bold border border-danger border-3' : '' }}">
                        <td class="text-center">7</td>
                        <td>NA &lt; 200</td>
                        <td class="text-muted">—</td>
                        <td class="text-muted">—</td>
                        <td class="text-muted">—</td>
                        <td><span class="badge bg-danger px-3 py-2">Tidak Terakreditasi</span></td>
                        <td class="text-center">—</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="alert alert-secondary mb-0 small">
            <strong>Catatan:</strong>
            <ul class="mb-0 mt-1">
                <li>NA = Σ(skor butir × bobot butir), nilai maks 400. Bobot setiap butir ditetapkan oleh auditor AMI.</li>
                <li>Syarat Terakreditasi yang dievaluasi dari AMI: rerata Kriteria II (Akuntabilitas), III (Relevansi Pendidikan), dan VII (SPMI) ≥ 2,00.</li>
                <li>Syarat Unggul ** dan *** di atas hanya mencakup kriteria yang dapat dievaluasi dari skor AMI (Tata Pamong, Kurikulum, SPMI). Syarat lainnya seperti kualifikasi DTPS, jabatan akademik, kinerja DTPS, waktu tunggu, dan kesesuaian bidang kerja <strong>memerlukan data LKPS</strong> yang diverifikasi LAM Teknik.</li>
                <li>Status akhir akreditasi ditetapkan oleh LAM Teknik berdasarkan seluruh dokumen IAPS AVP 2025.</li>
            </ul>
        </div>
    </div>
</div>
