@php
    $bgMap  = ['green' => '#d4edda', 'orange' => '#fff3cd', 'red' => '#f8d7da', 'blue' => '#d1ecf1'];
    $clrMap = ['green' => '#155724', 'orange' => '#856404', 'red' => '#721c24', 'blue' => '#0c5460'];
    $bg     = $bgMap[$warnaStatus]  ?? '#e2e3e5';
    $clr    = $clrMap[$warnaStatus] ?? '#383d41';

    $distribusi = [
        ['jenis' => 'Input',          'butir' => 17, 'bobot' => 60,  'persen' => 15],
        ['jenis' => 'Proses',         'butir' => 27, 'bobot' => 120, 'persen' => 30],
        ['jenis' => 'Output/Outcome', 'butir' => 38, 'bobot' => 220, 'persen' => 55],
    ];
@endphp

{{-- ============================================================
     I. BOBOT BAGIAN PER KRITERIA
     ============================================================ --}}
<div class="card mb-4">
    <div class="card-header bg-primary text-white fw-bold">
        I. BOBOT BAGIAN PER KRITERIA
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0 align-middle text-center">
                <thead class="table-light">
                    <tr>
                        <th class="text-start" style="width:36%">Kriteria</th>
                        <th>Butir Dinilai</th>
                        <th>Total Bobot (dari 400)</th>
                        <th>NA Kriteria</th>
                        <th>Rerata Skor</th>
                        <th>Skor Min</th>
                        <th>Syarat Unggul</th>
                    </tr>
                </thead>
                <tbody>
                    @php $naKriteriaTotal = 0; @endphp
                    @forelse($perKriteria as $kr)
                        @php
                            $naKriteriaTotal += $kr['na_kriteria'];
                            $unggulOk = $kr['rerata_ok'] && $kr['min_ok'];
                        @endphp
                        <tr>
                            <td class="text-start fw-semibold">{{ $kr['nama'] }}</td>
                            <td>{{ $kr['jumlah_butir'] }}</td>
                            <td>{{ number_format($kr['bobot_total'], 1) }}</td>
                            <td class="fw-bold">{{ number_format($kr['na_kriteria'], 2) }}</td>
                            <td>
                                <span class="{{ $kr['rerata_ok'] ? 'text-success fw-bold' : 'text-danger' }}">
                                    {{ number_format($kr['rerata'], 2) }}
                                    <small>({{ $kr['rerata_ok'] ? '≥ 3.20 ✓' : '< 3.20 ✗' }})</small>
                                </span>
                            </td>
                            <td>
                                <span class="{{ $kr['min_ok'] ? 'text-success fw-bold' : 'text-danger' }}">
                                    {{ number_format($kr['min_skor'], 2) }}
                                    <small>({{ $kr['min_ok'] ? '≥ 3.00 ✓' : '< 3.00 ✗' }})</small>
                                </span>
                            </td>
                            <td>
                                @if($unggulOk)
                                    <span class="badge bg-success">Terpenuhi</span>
                                @else
                                    <span class="badge bg-danger">Belum Terpenuhi</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-muted text-center py-3">Belum ada data penilaian.</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot class="table-secondary fw-bold">
                    <tr>
                        <td class="text-start">TOTAL</td>
                        <td>{{ array_sum(array_column($perKriteria, 'jumlah_butir')) }}</td>
                        <td>{{ number_format(array_sum(array_column($perKriteria, 'bobot_total')), 1) }}</td>
                        <td>{{ number_format($naTotal, 2) }}</td>
                        <td colspan="3"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

{{-- ============================================================
     II. PERSENTASE INPUT, PROSES, OUTPUT/OUTCOME
     ============================================================ --}}
<div class="card mb-4">
    <div class="card-header bg-info text-white fw-bold">
        II. PERSENTASE INPUT, PROSES, OUTPUT/OUTCOME
    </div>
    <div class="card-body">
        <div class="table-responsive mb-3">
            <table class="table table-bordered table-hover mb-0 align-middle text-center">
                <thead class="table-light">
                    <tr>
                        <th style="width:25%">Jenis</th>
                        <th>Jumlah Butir</th>
                        <th>Jumlah Bobot (dari 400)</th>
                        <th>Persentase Bobot</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($distribusi as $d)
                    <tr>
                        <td class="fw-semibold">{{ $d['jenis'] }}</td>
                        <td>{{ $d['butir'] }}</td>
                        <td>{{ $d['bobot'] }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2 justify-content-center">
                                <div class="progress flex-grow-1" style="height:18px;max-width:140px">
                                    <div class="progress-bar
                                        @if($d['jenis'] === 'Input') bg-info
                                        @elseif($d['jenis'] === 'Proses') bg-warning
                                        @else bg-success
                                        @endif"
                                        style="width:{{ $d['persen'] }}%">
                                    </div>
                                </div>
                                <span class="fw-bold">{{ $d['persen'] }}%</span>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-secondary fw-bold">
                    <tr>
                        <td>Total</td>
                        <td>80</td>
                        <td>400</td>
                        <td>100%</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <p class="text-muted small mb-0">
            * Distribusi bobot berdasarkan LAMINFOKOM IAPS 2.1 Matriks Penilaian.
            Output/Outcome memiliki bobot terbesar (55%) karena mencerminkan capaian nyata program studi.
        </p>
    </div>
</div>

{{-- ============================================================
     III. PENILAIAN STATUS AKREDITASI
     ============================================================ --}}
<div class="card mb-4">
    <div class="card-header fw-bold" style="background-color:{{ $bg }};color:{{ $clr }}">
        III. PENILAIAN STATUS AKREDITASI
    </div>
    <div class="card-body">

        {{-- Nilai Akhir & Status --}}
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card h-100 text-center border-0 shadow-sm">
                    <div class="card-body py-4">
                        <div class="text-muted small mb-1">Nilai Akhir (NA)</div>
                        <div class="display-5 fw-bold">{{ number_format($naTotal, 2) }}</div>
                        <div class="text-muted small">dari maksimal 400</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 text-center border-0 shadow-sm" style="background-color:{{ $bg }}">
                    <div class="card-body py-4">
                        <div class="small mb-1" style="color:{{ $clr }};opacity:.8">Status Akreditasi</div>
                        <div class="fs-3 fw-bold" style="color:{{ $clr }}">{{ $statusAkreditasi }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 text-center border-0 shadow-sm">
                    <div class="card-body py-4">
                        <div class="text-muted small mb-1">Durasi Berlaku</div>
                        <div class="fs-3 fw-bold">{{ $durasiAkreditasi }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Syarat Unggul --}}
        <div class="alert alert-light border mb-4">
            <h6 class="fw-bold mb-2">Syarat Tambahan Status Unggul (NA ≥ 321)</h6>
            <div class="row g-2">
                <div class="col-md-6">
                    <div class="d-flex align-items-center gap-2">
                        @if($syaratRerataOk)
                            <i class="bi bi-check-circle-fill text-success fs-5"></i>
                        @else
                            <i class="bi bi-x-circle-fill text-danger fs-5"></i>
                        @endif
                        <span>Rerata nilai setiap kriteria <strong>≥ 3.20</strong>
                            &nbsp;<span class="{{ $syaratRerataOk ? 'text-success' : 'text-danger' }} fw-semibold">
                                ({{ $syaratRerataOk ? 'Terpenuhi' : 'Belum Terpenuhi' }})
                            </span>
                        </span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex align-items-center gap-2">
                        @if($syaratMinOk)
                            <i class="bi bi-check-circle-fill text-success fs-5"></i>
                        @else
                            <i class="bi bi-x-circle-fill text-danger fs-5"></i>
                        @endif
                        <span>Setiap butir penilaian <strong>≥ 3.00</strong>
                            &nbsp;<span class="{{ $syaratMinOk ? 'text-success' : 'text-danger' }} fw-semibold">
                                ({{ $syaratMinOk ? 'Terpenuhi' : 'Belum Terpenuhi' }})
                            </span>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabel ambang batas --}}
        <h6 class="fw-bold mb-2">Tabel Ambang Batas Status Akreditasi (LAMINFOKOM IAPS 2.1)</h6>
        <div class="table-responsive">
            <table class="table table-bordered align-middle text-center mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Nilai Akhir (NA)</th>
                        <th>Syarat Tambahan</th>
                        <th>Status Akreditasi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="{{ $naTotal < 200 ? 'table-danger fw-bold' : '' }}">
                        <td>NA &lt; 200</td>
                        <td>—</td>
                        <td>
                            <span class="badge bg-danger px-3 py-2">Tidak Terakreditasi</span>
                        </td>
                    </tr>
                    <tr class="{{ ($naTotal >= 200 && $naTotal < 321) ? 'table-warning fw-bold' : '' }}">
                        <td>200 ≤ NA &lt; 321</td>
                        <td>—</td>
                        <td>
                            <span class="badge bg-warning text-dark px-3 py-2">Terakreditasi</span>
                        </td>
                    </tr>
                    <tr class="{{ ($naTotal >= 321 && $naTotal < 361 && !($syaratRerataOk && $syaratMinOk)) ? 'table-warning fw-bold' : '' }}">
                        <td>321 ≤ NA &lt; 361</td>
                        <td class="text-start small">
                            Jika ada satu butir penilaian dari salah satu Kriteria yang nilainya &lt; 3,00 <em>atau</em>
                            rerata nilai salah satu Kriteria &lt; 3,20 → tetap Terakreditasi
                        </td>
                        <td>
                            <span class="badge bg-warning text-dark px-3 py-2">Terakreditasi</span>
                        </td>
                    </tr>
                    <tr class="{{ ($naTotal >= 321 && $naTotal < 361 && $syaratRerataOk && $syaratMinOk) ? 'table-success fw-bold' : '' }}">
                        <td>321 ≤ NA &lt; 361</td>
                        <td class="text-start small">
                            Rerata nilai setiap Kriteria ≥ 3,20 <strong>DAN</strong>
                            setiap butir penilaian ≥ 3,00
                        </td>
                        <td>
                            <span class="badge bg-success px-3 py-2">Unggul (3 Tahun)</span>
                        </td>
                    </tr>
                    <tr class="{{ ($naTotal >= 361 && $syaratRerataOk && $syaratMinOk) ? 'table-success fw-bold' : '' }}">
                        <td>NA ≥ 361</td>
                        <td class="text-start small">
                            Rerata nilai setiap Kriteria ≥ 3,20 <strong>DAN</strong>
                            setiap butir penilaian ≥ 3,00
                        </td>
                        <td>
                            <span class="badge bg-success px-3 py-2">Unggul (5 Tahun)</span>
                        </td>
                    </tr>
                    <tr class="{{ ($naTotal >= 361 && !($syaratRerataOk && $syaratMinOk)) ? 'table-warning fw-bold' : '' }}">
                        <td>NA ≥ 361</td>
                        <td class="text-start small">
                            Jika syarat rerata/butir belum terpenuhi → tetap Terakreditasi
                        </td>
                        <td>
                            <span class="badge bg-warning text-dark px-3 py-2">Terakreditasi</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <p class="text-muted small mt-3 mb-0">
            * Prediksi ini dihasilkan berdasarkan data nilai yang telah diinput pada siklus AMI. Hasil akhir akreditasi
            ditentukan sepenuhnya oleh LAM Infokom setelah proses asesmen lapangan.
        </p>
    </div>
</div>
