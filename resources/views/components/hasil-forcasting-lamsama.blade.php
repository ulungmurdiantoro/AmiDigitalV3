@php
    $bgMap  = ['green' => '#d4edda', 'blue' => '#d1ecf1', 'orange' => '#fff3cd', 'red' => '#f8d7da', 'gray' => '#e2e3e5'];
    $clrMap = ['green' => '#155724', 'blue' => '#0c5460', 'orange' => '#856404', 'red' => '#721c24', 'gray' => '#383d41'];
    $bg     = $bgMap[$warnaStatus]  ?? '#e2e3e5';
    $clr    = $clrMap[$warnaStatus] ?? '#383d41';

    $levelBadge = [
        'Baik Sekali' => ['bg-success',  '4 – Baik Sekali'],
        'Baik'        => ['bg-primary',  '3 – Baik'],
        'Cukup'       => ['bg-warning text-dark', '2 – Cukup'],
        'Kurang'      => ['bg-danger',   '1 – Kurang'],
        'Belum Dinilai' => ['bg-secondary', '—'],
    ];
@endphp

{{-- ============================================================
     I. KRITERIA MINIMAL PENILAIAN PER STANDAR
     ============================================================ --}}
<div class="card mb-4">
    <div class="card-header bg-primary text-white fw-bold">
        I. KRITERIA MINIMAL PENILAIAN
        <span class="fw-normal small ms-2">
            (Syarat: semua butir ≥ CUKUP / skor ≥ 2)
        </span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0 align-middle text-center">
                <thead class="table-light">
                    <tr>
                        <th class="text-start" style="width:40%">Standar / Kriteria</th>
                        <th>Butir Total</th>
                        <th>Sudah Dinilai</th>
                        <th>
                            <span class="badge bg-success">Baik Sekali</span>
                        </th>
                        <th>
                            <span class="badge bg-primary">Baik</span>
                        </th>
                        <th>
                            <span class="badge bg-warning text-dark">Cukup</span>
                        </th>
                        <th>
                            <span class="badge bg-danger">Kurang</span>
                        </th>
                        <th>Status Syarat</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($perStandar as $s)
                    <tr class="{{ $s['ada_kurang'] ? 'table-danger' : ($s['dinilai'] > 0 ? 'table-success bg-opacity-25' : '') }}">
                        <td class="text-start fw-semibold">
                            {{ preg_replace('/^[A-F]\.\s*/i', '', $s['nama']) }}
                        </td>
                        <td>{{ $s['total_butir'] }}</td>
                        <td>
                            {{ $s['dinilai'] }}
                            @if($s['dinilai'] < $s['total_butir'])
                                <small class="text-muted">({{ $s['total_butir'] - $s['dinilai'] }} belum)</small>
                            @endif
                        </td>
                        <td class="fw-bold text-success">{{ $s['baik_sekali'] ?: '—' }}</td>
                        <td class="fw-bold text-primary">{{ $s['baik'] ?: '—' }}</td>
                        <td class="fw-bold text-warning-emphasis">{{ $s['cukup'] ?: '—' }}</td>
                        <td class="{{ $s['kurang'] > 0 ? 'fw-bold text-danger' : 'text-muted' }}">
                            {{ $s['kurang'] > 0 ? $s['kurang'] : '—' }}
                        </td>
                        <td>
                            @if($s['ada_kurang'])
                                <span class="badge bg-danger">Tidak Terpenuhi</span>
                            @elseif($s['dinilai'] === 0)
                                <span class="badge bg-secondary">Belum Dinilai</span>
                            @elseif($s['dinilai'] < $s['total_butir'])
                                <span class="badge bg-warning text-dark">Sebagian</span>
                            @else
                                <span class="badge bg-success">Terpenuhi</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-3">Belum ada data standar.</td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot class="table-secondary fw-bold">
                    <tr>
                        <td class="text-start">TOTAL</td>
                        <td>{{ $totalButir }}</td>
                        <td>{{ $totalDinilai }}</td>
                        <td class="text-success">{{ $jumlahBaikSekali ?: '—' }}</td>
                        <td class="text-primary">{{ $jumlahBaik ?: '—' }}</td>
                        <td class="text-warning-emphasis">{{ $jumlahCukup ?: '—' }}</td>
                        <td class="{{ $jumlahKurang > 0 ? 'text-danger' : 'text-muted' }}">
                            {{ $jumlahKurang > 0 ? $jumlahKurang : '—' }}
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

{{-- Butir KURANG detail (only if any) --}}
@if(count($butirKurang) > 0)
<div class="card mb-4 border-danger">
    <div class="card-header bg-danger text-white fw-bold">
        <i class="bi bi-exclamation-triangle-fill me-1"></i>
        Butir yang Belum Memenuhi Syarat Minimal (Skor &lt; 2 / KURANG)
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width:5%">No</th>
                        <th style="width:28%">Standar</th>
                        <th style="width:8%">Kode</th>
                        <th>Indikator</th>
                        <th style="width:12%">Skor</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($butirKurang as $idx => $b)
                    <tr class="table-danger">
                        <td class="text-center">{{ $idx + 1 }}</td>
                        <td class="small">{{ preg_replace('/^[A-F]\.\s*/i', '', $b['standar']) }}</td>
                        <td class="text-center fw-bold">{{ $b['kode'] }}</td>
                        <td class="small">{{ Str::limit($b['nama_indikator'], 120) }}</td>
                        <td class="text-center">
                            <span class="badge bg-danger fs-6">{{ number_format($b['skor'], 0) }} – Kurang</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

{{-- ============================================================
     II. STATUS AKHIR AKREDITASI
     ============================================================ --}}
<div class="card mb-4">
    <div class="card-header fw-bold" style="background-color:{{ $bg }};color:{{ $clr }}">
        II. STATUS AKHIR AKREDITASI
    </div>
    <div class="card-body">

        {{-- Status summary cards --}}
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card h-100 text-center border-0 shadow-sm">
                    <div class="card-body py-3">
                        <div class="text-muted small mb-1">Butir Dinilai</div>
                        <div class="display-6 fw-bold">{{ $totalDinilai }}<small class="fs-6 text-muted"> / {{ $totalButir }}</small></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100 text-center border-0 shadow-sm {{ $jumlahKurang > 0 ? 'border-danger' : '' }}">
                    <div class="card-body py-3">
                        <div class="text-muted small mb-1">Butir KURANG (&lt;2)</div>
                        <div class="display-6 fw-bold {{ $jumlahKurang > 0 ? 'text-danger' : 'text-success' }}">
                            {{ $jumlahKurang }}
                        </div>
                        <div class="small {{ $jumlahKurang > 0 ? 'text-danger' : 'text-success' }}">
                            {{ $jumlahKurang > 0 ? 'Perlu diperbaiki' : 'Tidak ada' }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100 text-center border-0 shadow-sm">
                    <div class="card-body py-3">
                        <div class="text-muted small mb-1">Butir ≥ CUKUP (≥2)</div>
                        <div class="display-6 fw-bold text-success">{{ $totalDinilai - $jumlahKurang }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100 text-center border-0 shadow-sm" style="background-color:{{ $bg }}">
                    <div class="card-body py-3">
                        <div class="small mb-1" style="color:{{ $clr }};opacity:.8">Prediksi Status</div>
                        <div class="fs-5 fw-bold" style="color:{{ $clr }}">{{ $statusAkreditasi }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Keterangan status --}}
        <div class="alert alert-light border mb-4">
            <strong>Analisis:</strong> {{ $keteranganStatus }}
            @if($indikasiunggul)
                <div class="mt-1 text-success fw-semibold">
                    <i class="bi bi-star-fill me-1"></i>{{ $indikasiunggul }}
                </div>
            @endif
        </div>

        {{-- Tabel ambang batas --}}
        <h6 class="fw-bold mb-2">Tabel Kriteria Status Akreditasi (LAMSAMA IAPS 3.1)</h6>
        <div class="table-responsive">
            <table class="table table-bordered align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Kondisi Penilaian</th>
                        <th>Syarat</th>
                        <th>Status Akreditasi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="{{ $jumlahKurang > 0 ? 'table-danger fw-bold' : '' }}">
                        <td>Ada butir bernilai KURANG (skor &lt; 2)</td>
                        <td>Minimal 1 butir &lt; CUKUP</td>
                        <td><span class="badge bg-danger px-3 py-2">Tidak Terakreditasi</span></td>
                    </tr>
                    <tr class="{{ ($jumlahKurang === 0 && $jumlahBaikSekali < $totalDinilai && $totalDinilai > 0) ? 'table-info fw-bold' : '' }}">
                        <td>Semua butir ≥ CUKUP, belum semua BAIK SEKALI</td>
                        <td>Semua 24 butir ≥ skor 2 (CUKUP)</td>
                        <td><span class="badge bg-info text-dark px-3 py-2">Terakreditasi</span></td>
                    </tr>
                    <tr class="{{ ($jumlahBaikSekali === $totalDinilai && $totalDinilai > 0) ? 'table-success fw-bold' : '' }}">
                        <td>Semua butir = BAIK SEKALI (skor 4)</td>
                        <td>Semua 24 butir = skor 4 + instrument Unggul (35 butir) terpisah</td>
                        <td><span class="badge bg-success px-3 py-2">Indikasi Terakreditasi Unggul</span></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="alert alert-secondary mt-3 mb-0 small">
            <strong>Catatan:</strong>
            <ul class="mb-0 mt-1">
                <li>Instrumen <strong>Terakreditasi</strong> memuat <strong>24 butir</strong> penilaian (LAMSAMA IAPS 3.1).</li>
                <li>Instrumen <strong>Terakreditasi Unggul</strong> adalah dokumen terpisah dengan <strong>35 butir</strong> yang berbeda (termasuk kriteria tambahan).</li>
                <li>Prediksi ini berdasarkan data AMI pada instrumen Terakreditasi. Status akhir ditetapkan oleh LAMSAMA.</li>
            </ul>
        </div>
    </div>
</div>
