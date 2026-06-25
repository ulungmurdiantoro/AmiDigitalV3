@extends('layout.master-user')

@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
  <div>
    <h4 class="mb-1">Laporan Kinerja Program Studi (LKPS)</h4>
    <p class="text-muted tx-13 mb-0">{{ $prodi }} &bull; Periode {{ $periode }}</p>
  </div>
  @if($tersync)
  <div class="d-flex gap-2 flex-wrap">
    <button class="btn btn-sm btn-outline-primary" id="btn-snapshot">
      <i data-feather="save" style="width:14px;height:14px"></i> Simpan Snapshot
    </button>
    <a href="{{ route('user.lkps.export') }}" class="btn btn-sm btn-success">
      <i data-feather="download" style="width:14px;height:14px"></i> Export Excel
    </a>
  </div>
  @endif
</div>

@if(!$terhubung)
<div class="alert alert-warning">
  Prodi ini belum dihubungkan ke Neo Feeder. Hubungi Admin untuk mengkonfigurasi koneksi PDDikti.
</div>
@elseif(!$tersync)
<div class="alert alert-info">
  Data PDDikti belum tersinkronisasi. Lakukan sinkronisasi terlebih dahulu melalui Dashboard.
</div>
@else


@php $r = $lkpsData['ringkasan']; @endphp

{{-- Summary Cards --}}
<div class="row g-3 mb-4">
  <div class="col-6 col-md-3">
    <div class="card card-body text-center py-3">
      <div class="fs-4 fw-bold text-primary">{{ number_format($r['mhs_aktif']) }}</div>
      <div class="text-muted small">Mahasiswa Aktif</div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="card card-body text-center py-3">
      <div class="fs-4 fw-bold text-success">{{ $r['dtps'] }}</div>
      <div class="text-muted small">Jml. DTPS</div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="card card-body text-center py-3">
      <div class="fs-4 fw-bold {{ ($r['rasio'] >= 15 && $r['rasio'] <= 25) ? 'text-success' : 'text-warning' }}">
        {{ $r['rasio'] }}
      </div>
      <div class="text-muted small">Rasio Mhs : DTPS</div>
      <div class="small text-muted">Ideal: 15–25</div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="card card-body text-center py-3">
      <div class="fs-4 fw-bold {{ $r['pct_doktor'] >= 50 ? 'text-success' : 'text-warning' }}">
        {{ $r['pct_doktor'] }}%
      </div>
      <div class="text-muted small">DTPS Kualifikasi S3</div>
      <div class="small text-muted">Syarat Unggul: ≥50%</div>
    </div>
  </div>
</div>

{{-- ══════════════════════════════════════════════════════════════════════════
     TABEL 4.a. LKPS — Profil DTPS
══════════════════════════════════════════════════════════════════════════ --}}
<div class="card mb-4">
  <div class="card-header d-flex align-items-center justify-content-between">
    <div>
      <span class="fw-bold">Tabel 4.a. LKPS</span>
      <span class="text-muted ms-2">— Profil Dosen Tetap Program Studi (DTPS)</span>
    </div>
    <span class="badge bg-success">Otomatis</span>
  </div>
  <div class="card-body p-0">
    @if(count($lkpsData['tabel_4a']['dtps']) > 0)
    <div class="table-responsive">
      <table class="table table-sm table-bordered table-hover mb-0 small">
        <thead class="table-light">
          <tr>
            <th class="text-center" style="width:40px">No</th>
            <th>NIDN</th>
            <th>Nama Dosen</th>
            <th>Jabatan Fungsional</th>
            <th>Pendidikan Terakhir</th>
            <th>Bidang Keahlian</th>
          </tr>
        </thead>
        <tbody>
          @foreach($lkpsData['tabel_4a']['dtps'] as $i => $d)
          @php
            $jab = $d['jabatan'] ?? '-';
            $jabCls = match($jab) {
              'Guru Besar'    => 'badge bg-danger',
              'Lektor Kepala' => 'badge bg-warning text-dark',
              'Lektor'        => 'badge bg-info text-dark',
              'Asisten Ahli'  => 'badge bg-secondary',
              default         => 'badge bg-light text-dark border',
            };
            $pend = $d['pendidikan'] ?? '-';
            $pendCls = match($pend) { 'S3', 'Doktor' => 'fw-bold text-success', 'S2', 'Magister' => 'text-primary', default => '' };
          @endphp
          <tr>
            <td class="text-center">{{ $i+1 }}</td>
            <td>{{ $d['nidn'] ?? '-' }}</td>
            <td>{{ $d['nama'] }}</td>
            <td><span class="{{ $jabCls }}">{{ $jab }}</span></td>
            <td><span class="{{ $pendCls }}">{{ $pend }}</span></td>
            <td>{{ $d['bidang_keahlian'] ?? '-' }}</td>
          </tr>
          @endforeach
        </tbody>
        <tfoot class="table-light">
          <tr>
            @php
              $jmlDtps  = count($lkpsData['tabel_4a']['dtps']);
              $jmlDok   = collect($lkpsData['tabel_4a']['dtps'])->whereIn('pendidikan', ['S3','Doktor'])->count();
              $jmlGb    = collect($lkpsData['tabel_4a']['dtps'])->where('jabatan','Guru Besar')->count();
              $jmlLk    = collect($lkpsData['tabel_4a']['dtps'])->where('jabatan','Lektor Kepala')->count();
            @endphp
            <td colspan="6" class="small text-muted">
              Jml DTPS: <strong>{{ $jmlDtps }}</strong>
              &nbsp;|&nbsp; Kualifikasi S3: <strong>{{ $jmlDok }}</strong>
              ({{ $jmlDtps > 0 ? round($jmlDok/$jmlDtps*100,1) : 0 }}%)
              &nbsp;|&nbsp; GB+LK: <strong>{{ $jmlGb + $jmlLk }}</strong>
              ({{ $jmlDtps > 0 ? round(($jmlGb+$jmlLk)/$jmlDtps*100,1) : 0 }}%)
            </td>
          </tr>
        </tfoot>
      </table>
    </div>
    @else
    <div class="p-3 text-muted text-center small">Belum ada data DTPS tersinkronisasi.</div>
    @endif
  </div>
</div>

{{-- ══════════════════════════════════════════════════════════════════════════
     TABEL 4.b. LKPS — Dosen Tidak Tetap (DTT)
══════════════════════════════════════════════════════════════════════════ --}}
<div class="card mb-4">
  <div class="card-header d-flex align-items-center justify-content-between">
    <div>
      <span class="fw-bold">Tabel 4.b. LKPS</span>
      <span class="text-muted ms-2">— Dosen Tidak Tetap (DTT)</span>
    </div>
    <span class="badge bg-success">Otomatis</span>
  </div>
  <div class="card-body p-0">
    @if(count($lkpsData['tabel_4a']['dtt']) > 0)
    <div class="table-responsive">
      <table class="table table-sm table-bordered table-hover mb-0 small">
        <thead class="table-light">
          <tr>
            <th class="text-center" style="width:40px">No</th>
            <th>NIDN</th>
            <th>Nama Dosen</th>
            <th>Jabatan Fungsional</th>
            <th>Pendidikan Terakhir</th>
            <th>Bidang Keahlian</th>
          </tr>
        </thead>
        <tbody>
          @foreach($lkpsData['tabel_4a']['dtt'] as $i => $d)
          <tr>
            <td class="text-center">{{ $i+1 }}</td>
            <td>{{ $d['nidn'] ?? '-' }}</td>
            <td>{{ $d['nama'] }}</td>
            <td>{{ $d['jabatan'] ?? '-' }}</td>
            <td>{{ $d['pendidikan'] ?? '-' }}</td>
            <td>{{ $d['bidang_keahlian'] ?? '-' }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    @else
    <div class="p-3 text-muted text-center small">Tidak ada dosen tidak tetap tercatat.</div>
    @endif
  </div>
</div>

{{-- ══════════════════════════════════════════════════════════════════════════
     TABEL 6.a. LKPS — Data Mahasiswa per Angkatan
══════════════════════════════════════════════════════════════════════════ --}}
<div class="card mb-4">
  <div class="card-header d-flex align-items-center justify-content-between">
    <div>
      <span class="fw-bold">Tabel 6.a. LKPS</span>
      <span class="text-muted ms-2">— Data Mahasiswa per Angkatan</span>
    </div>
    <span class="badge bg-success">Otomatis</span>
  </div>
  <div class="card-body p-0">
    @php $t6a = $lkpsData['tabel_6a']; @endphp
    @if(count($t6a['rows']) > 0)
    <div class="table-responsive">
      <table class="table table-sm table-bordered table-hover mb-0 small">
        <thead class="table-light">
          <tr>
            <th class="text-center">Tahun Masuk (Angkatan)</th>
            <th class="text-center">Jumlah Diterima</th>
            <th class="text-center">Aktif</th>
            <th class="text-center">Lulus</th>
            <th class="text-center">Keluar / Tidak Aktif</th>
          </tr>
        </thead>
        <tbody>
          @foreach($t6a['rows'] as $row)
          <tr>
            <td class="text-center fw-bold">{{ $row['angkatan'] }}</td>
            <td class="text-center">{{ number_format($row['diterima']) }}</td>
            <td class="text-center text-success fw-bold">{{ number_format($row['aktif']) }}</td>
            <td class="text-center text-primary">{{ number_format($row['lulus']) }}</td>
            <td class="text-center text-muted">{{ number_format($row['keluar']) }}</td>
          </tr>
          @endforeach
        </tbody>
        <tfoot class="table-light">
          <tr>
            <td class="fw-bold">Total Aktif</td>
            <td colspan="4" class="fw-bold text-success">{{ number_format($t6a['total_aktif']) }} mahasiswa</td>
          </tr>
        </tfoot>
      </table>
    </div>
    @else
    <div class="p-3 text-muted text-center small">Belum ada data mahasiswa tersinkronisasi.</div>
    @endif
  </div>
</div>

{{-- ══════════════════════════════════════════════════════════════════════════
     TABEL 6.b. LKPS — IPK Lulusan
══════════════════════════════════════════════════════════════════════════ --}}
<div class="card mb-4">
  <div class="card-header d-flex align-items-center justify-content-between">
    <div>
      <span class="fw-bold">Tabel 6.b. LKPS</span>
      <span class="text-muted ms-2">— IPK Lulusan</span>
    </div>
    <span class="badge bg-success">Otomatis</span>
  </div>
  <div class="card-body p-0">
    @php $t6b = $lkpsData['tabel_6b']; @endphp
    @if(count($t6b) > 0)
    <div class="table-responsive">
      <table class="table table-sm table-bordered table-hover mb-0 small">
        <thead class="table-light">
          <tr>
            <th class="text-center">Tahun Lulus</th>
            <th class="text-center">Jumlah Lulusan</th>
            <th class="text-center">IPK Min</th>
            <th class="text-center">IPK Maks</th>
            <th class="text-center">IPK Rata-rata (RIPK)</th>
            <th class="text-center">Estimasi Skor</th>
          </tr>
        </thead>
        <tbody>
          @foreach($t6b as $row)
          @php
            $ripk = $row['ipk_rata'];
            $ripkCls = $ripk >= 3.25 ? 'text-success fw-bold' : ($ripk >= 2.75 ? 'text-warning fw-bold' : 'text-danger');
            if ($ripk >= 3.25)      $skor = '4.00';
            elseif ($ripk >= 2.00)  $skor = number_format((8 * $ripk - 14.5) / 1.0, 2);
            else                    $skor = '0.00';
          @endphp
          <tr>
            <td class="text-center fw-bold">{{ $row['tahun_lulus'] }}</td>
            <td class="text-center">{{ number_format($row['jumlah_lulusan']) }}</td>
            <td class="text-center">{{ number_format($row['ipk_min'], 2) }}</td>
            <td class="text-center">{{ number_format($row['ipk_max'], 2) }}</td>
            <td class="text-center {{ $ripkCls }}">{{ number_format($ripk, 2) }}</td>
            <td class="text-center">
              @if($ripk >= 3.25)
                <span class="badge bg-success">{{ $skor }}</span>
              @elseif($ripk >= 2.00)
                <span class="badge bg-warning text-dark">{{ $skor }}</span>
              @else
                <span class="badge bg-danger">{{ $skor }}</span>
              @endif
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <div class="px-3 py-2 small text-muted">
      Formula: RIPK ≥ 3,25 → Skor 4 &nbsp;|&nbsp; 2,00 ≤ RIPK &lt; 3,25 → Skor = (8 × RIPK − 14,5)
    </div>
    @else
    <div class="p-3 text-muted text-center small">Belum ada data kelulusan tersinkronisasi.</div>
    @endif
  </div>
</div>

{{-- ══════════════════════════════════════════════════════════════════════════
     TABEL 6.d. LKPS — Masa Studi & Kelulusan Tepat Waktu
══════════════════════════════════════════════════════════════════════════ --}}
<div class="card mb-4">
  <div class="card-header d-flex align-items-center justify-content-between">
    <div>
      <span class="fw-bold">Tabel 6.d. LKPS</span>
      <span class="text-muted ms-2">— Masa Studi &amp; Kelulusan Tepat Waktu</span>
    </div>
    <span class="badge bg-success">Otomatis</span>
  </div>
  <div class="card-body p-0">
    @php $t6d = $lkpsData['tabel_6d']; @endphp
    @if(count($t6d) > 0)
    <div class="table-responsive">
      <table class="table table-sm table-bordered table-hover mb-0 small">
        <thead class="table-light">
          <tr>
            <th class="text-center">Tahun Lulus</th>
            <th class="text-center">Jumlah Lulusan</th>
            <th class="text-center">Rata-rata Masa Studi (Thn)</th>
            <th class="text-center">Lulus Tepat Waktu (≤8 Sem)</th>
            <th class="text-center">% Tepat Waktu (PTW)</th>
            <th class="text-center">Estimasi Skor PTW</th>
          </tr>
        </thead>
        <tbody>
          @foreach($t6d as $row)
          @php
            $ptw   = $row['pct_tepat'];
            $ms    = $row['rata_masa_studi'];
            $ptwCls = $ptw >= 50 ? 'text-success fw-bold' : ($ptw >= 25 ? 'text-warning fw-bold' : 'text-danger fw-bold');
            $msCls  = ($ms >= 3.5 && $ms <= 4.5) ? 'text-success fw-bold' : 'text-warning fw-bold';
            if ($ptw >= 50)       $skorPtw = '4.00';
            elseif ($ptw > 0)     $skorPtw = number_format(1 + (6 * $ptw / 100), 2);
            else                  $skorPtw = '1.00';
          @endphp
          <tr>
            <td class="text-center fw-bold">{{ $row['tahun_lulus'] }}</td>
            <td class="text-center">{{ number_format($row['jumlah']) }}</td>
            <td class="text-center {{ $msCls }}">{{ number_format($ms, 2) }}</td>
            <td class="text-center">{{ number_format($row['tepat_waktu']) }}</td>
            <td class="text-center {{ $ptwCls }}">{{ $ptw }}%</td>
            <td class="text-center">
              @if($ptw >= 50)
                <span class="badge bg-success">{{ $skorPtw }}</span>
              @elseif($ptw > 0)
                <span class="badge bg-warning text-dark">{{ $skorPtw }}</span>
              @else
                <span class="badge bg-secondary">{{ $skorPtw }}</span>
              @endif
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <div class="px-3 py-2 small text-muted">
      Masa Studi: Ideal 3,5 &lt; MS ≤ 4,5 thn → Skor 4 &nbsp;|&nbsp; PTW ≥ 50% → Skor 4 &nbsp;|&nbsp; PTW &lt; 50% → Skor = 1 + (6 × PTW/100)
    </div>
    @else
    <div class="p-3 text-muted text-center small">Belum ada data kelulusan tersinkronisasi.</div>
    @endif
  </div>
</div>

@endif {{-- end tersync --}}

{{-- ══════════════════════════════════════════════════════════════════════════
     Riwayat Snapshot
══════════════════════════════════════════════════════════════════════════ --}}
<div class="card mt-2">
  <div class="card-header fw-bold">Riwayat Snapshot LKPS</div>
  <div class="card-body p-0">
    @if($snapshots->isEmpty())
    <div class="p-3 text-muted text-center small">Belum ada snapshot disimpan.</div>
    @else
    <div class="table-responsive">
      <table class="table table-sm table-bordered table-hover mb-0 small">
        <thead class="table-light">
          <tr>
            <th>Tanggal Simpan</th>
            <th>Periode</th>
            <th>Disimpan Oleh</th>
            <th class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach($snapshots as $snap)
          <tr>
            <td>{{ $snap->created_at->format('d/m/Y H:i') }}</td>
            <td>{{ $snap->periode }}</td>
            <td>{{ $snap->created_by ?? '-' }}</td>
            <td class="text-center">
              <a href="{{ route('user.lkps.export', ['snapshot' => $snap->id]) }}"
                 class="btn btn-xs btn-outline-success me-1" style="font-size:.75rem;padding:2px 8px">
                <i data-feather="download" style="width:12px;height:12px"></i> Excel
              </a>
              <button class="btn btn-xs btn-outline-danger btn-del-snapshot"
                      data-id="{{ $snap->id }}"
                      data-url="{{ route('user.lkps.snapshot.destroy', $snap) }}"
                      style="font-size:.75rem;padding:2px 8px">
                <i data-feather="trash-2" style="width:12px;height:12px"></i>
              </button>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    @endif
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  if (typeof feather !== 'undefined') feather.replace();

  const btnSnap = document.getElementById('btn-snapshot');
  if (btnSnap) {
    btnSnap.addEventListener('click', () => {
      btnSnap.disabled = true;
      btnSnap.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Menyimpan...';
      fetch('{{ route('user.lkps.snapshot') }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
      })
      .then(r => r.json())
      .then(res => {
        alert(res.message);
        if (res.success) location.reload();
        else { btnSnap.disabled = false; btnSnap.innerHTML = '<i data-feather="save" style="width:14px;height:14px"></i> Simpan Snapshot'; }
      })
      .catch(() => { btnSnap.disabled = false; });
    });
  }

  document.querySelectorAll('.btn-del-snapshot').forEach(btn => {
    btn.addEventListener('click', () => {
      if (!confirm('Hapus snapshot ini?')) return;
      fetch(btn.dataset.url, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
      })
      .then(r => r.json())
      .then(res => { if (res.success) btn.closest('tr').remove(); else alert(res.message); });
    });
  });
});
</script>
@endsection
