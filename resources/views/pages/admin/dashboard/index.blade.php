@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
  <div>
    <h4 class="mb-3 mb-md-0">Welcome to Dashboard</h4>
  </div>
  <div class="d-flex align-items-center flex-wrap text-nowrap">
    <button type="button" class="btn btn-outline-primary btn-icon-text me-2 mb-2 mb-md-0">
      <i class="btn-icon-prepend" data-feather="send"></i>
        Buat Pengumuman
    </button>
    <button type="button" class="btn btn-primary btn-icon-text mb-2 mb-md-0">
      <i class="btn-icon-prepend" data-feather="monitor"></i>
        Aktivitas Prodi
    </button>
  </div>
</div>
{{-- @dd($amiS1Selesai) --}}
<div class="row">
  <div class="col-12 col-xl-12 stretch-card">
    <div class="row flex-grow-1">
      <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-baseline">
              <h6 class="card-title mb-0">Jumlah Pengguna Sistem</h6>
            </div>
            <div class="row">
              <div class="col-6 col-md-12 col-xl-6">
                <div id="penggunaAdmin" data-value="{{ $penggunaAdmin }}" class="mt-md-3 mt-xl-0"></div>
                
              </div>
              <div class="col-6 col-md-12 col-xl-6">
                <div id="penggunaProdi" data-value="{{ $penggunaProdi }}" class="mt-md-3 mt-xl-0"></div>
              </div>
              <div class="col-6 col-md-12 col-xl-12">
                <div id="penggunaAuditor" data-value="{{ $penggunaAuditor }}" class="mt-md-3 mt-xl-0"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-baseline">
              <h6 class="card-title mb-0">Jumlah Prodi Terdaftar</h6>
            </div>
            <div class="row">
              <div class="col-6 col-md-12 col-xl-12">
                <div id="jumlahProdi" class="mt-md-3 mt-xl-0"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div> <!-- row -->

<div class="row">
  <div class="col-12 col-xl-12 stretch-card">
    <div class="row flex-grow-1">
      <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-baseline">
              <h6 class="card-title mb-0">Persentase Pengajuan AMI Tahun {{ $periode }}</h6>
            </div>
            <div class="row">
              <div class="col-6 col-md-12 col-xl-12">
                <div id="persenAmi" class="mt-md-3 mt-xl-0"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-baseline">
              <h6 class="card-title mb-0">Jumlah Pengajuan AMI Tahun {{ $periode }}</h6>
            </div>
            <div class="row">
              <div class="col-6 col-md-12 col-xl-12">
                <div id="jumlahAmi" class="mt-md-3 mt-xl-0"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div> <!-- row -->

{{-- ── Section Neo Feeder ─────────────────────────────────────────────────── --}}
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
  <div>
    <h4 class="mb-3 mb-md-0">
      Data PDDikti &mdash; Neo Feeder
      @if($feederData && $feederData['is_fake'])
        <span class="badge bg-warning text-dark ms-1">FAKE MODE</span>
      @endif
    </h4>
  </div>
  <div class="d-flex align-items-center flex-wrap text-nowrap">
    @if($feederData)
      <span class="text-muted me-3">
        Sync terakhir: {{ $feederData['last_sync'] ? \Carbon\Carbon::parse($feederData['last_sync'])->translatedFormat('d M Y, H:i') : '-' }}
      </span>
    @endif
    <button type="button" class="btn btn-outline-primary btn-icon-text mb-2 mb-md-0" id="btnSync">
      <i class="btn-icon-prepend" data-feather="refresh-cw"></i>
      Sync Sekarang
    </button>
  </div>
</div>

@if(!$feederSynced)
  <div class="alert alert-warning mb-3" role="alert">
    <i data-feather="alert-circle"></i>
    Data Neo Feeder belum disinkronkan. Klik <strong>Sync Sekarang</strong> untuk memuat data.
  </div>
@else
<div class="row">
  {{-- Mahasiswa Aktif --}}
  <div class="col-6 col-md-3 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h6 class="card-title mb-0">Mahasiswa Aktif</h6>
        <h3 class="mt-2 mb-1 text-primary">{{ number_format($feederData['mahasiswa_aktif']) }}</h3>
        <p class="text-muted mb-0">mahasiswa</p>
      </div>
    </div>
  </div>
  {{-- Dosen Tetap (DPR) --}}
  <div class="col-6 col-md-3 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h6 class="card-title mb-0">Dosen Penghitung Rasio</h6>
        <h3 class="mt-2 mb-1 text-success">{{ $feederData['dpr'] }}</h3>
        <p class="text-muted mb-0">tetap + {{ $feederData['dtt'] }} tidak tetap</p>
      </div>
    </div>
  </div>
  {{-- Rasio --}}
  <div class="col-6 col-md-3 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h6 class="card-title mb-0">Rasio Mhs : Dosen</h6>
        <h3 class="mt-2 mb-1 {{ $feederData['rasio'] <= 30 ? 'text-success' : ($feederData['rasio'] <= 45 ? 'text-warning' : 'text-danger') }}">
          {{ $feederData['rasio'] }} : 1
        </h3>
        <p class="text-muted mb-0">maks. 45 : 1 (BAN-PT)</p>
      </div>
    </div>
  </div>
  {{-- IPK Lulusan --}}
  <div class="col-6 col-md-3 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        @php $latestIpk = collect($feederData['ipk_lulusan'])->last(); @endphp
        <h6 class="card-title mb-0">IPK Rata-rata Lulusan</h6>
        <h3 class="mt-2 mb-1 text-info">{{ $latestIpk ? $latestIpk['rata_rata'] : '-' }}</h3>
        <p class="text-muted mb-0">{{ $latestIpk ? 'lulusan ' . $latestIpk['tahun_lulus'] : 'belum ada data' }}</p>
      </div>
    </div>
  </div>
</div>

<div class="row">
  {{-- Jabatan Akademik DPR --}}
  <div class="col-md-6 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h6 class="card-title">Jabatan Akademik DPR (LKPS Tabel 2-I.2)</h6>
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th class="text-bg-secondary">Jabatan</th>
                <th class="text-bg-secondary text-end">Jumlah</th>
                <th class="text-bg-secondary text-end">%</th>
              </tr>
            </thead>
            <tbody>
              @php $totalDpr = $feederData['dpr'] ?: 1; @endphp
              @foreach(['Guru Besar','Lektor Kepala','Lektor','Asisten Ahli','Tenaga Pengajar'] as $jab)
                @php $n = $feederData['jabatan_dpr'][$jab] ?? 0; @endphp
                @if($n > 0)
                <tr>
                  <td>{{ $jab }}</td>
                  <td class="text-end">{{ $n }}</td>
                  <td class="text-end">{{ round($n / $totalDpr * 100, 1) }}%</td>
                </tr>
                @endif
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  {{-- Kelulusan Tepat Waktu --}}
  <div class="col-md-6 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h6 class="card-title">Kelulusan Tepat Waktu &le;8 Semester (LKPS 9.1.2.3)</h6>
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th class="text-bg-secondary">Tahun Lulus</th>
                <th class="text-bg-secondary text-end">Total</th>
                <th class="text-bg-secondary text-end">Tepat Waktu</th>
                <th class="text-bg-secondary text-end">%</th>
              </tr>
            </thead>
            <tbody>
              @forelse($feederData['kelulusan_tepat'] as $tahun => $row)
              <tr>
                <td>{{ $tahun }}</td>
                <td class="text-end">{{ $row['total'] }}</td>
                <td class="text-end">{{ $row['tepat'] }}</td>
                <td class="text-end">
                  <span class="badge {{ $row['persen'] >= 75 ? 'bg-success' : 'bg-warning text-dark' }}">
                    {{ $row['persen'] }}%
                  </span>
                </td>
              </tr>
              @empty
              <tr><td colspan="4" class="text-center text-muted">Belum ada data</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endif
{{-- ── End Section Neo Feeder ──────────────────────────────────────────────── --}}

@endsection

@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/flatpickr/flatpickr.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/apexcharts/apexcharts.min.js') }}"></script>
@endpush

<script>
  var prodiS1 = @json($prodiS1);
  var prodiS2 = @json($prodiS2);
  var prodiS3 = @json($prodiS3);
  var prodiD3 = @json($prodiD3);
  var prodiS1T = @json($prodiS1T);
  var prodiS2T = @json($prodiS2T);
  var prodiS3T = @json($prodiS3T);
  var prodiPPG = @json($prodiPPG);

  var Diajukanami = @json($Diajukanami);
  var Diterimaami = @json($Diterimaami);
  var Koreksiami = @json($Koreksiami);
  var Selesaiami = @json($Selesaiami);

  var amiSelesai = {
    'amiD3Diajukan': @json($amiD3Diajukan),
    'amiS1Diajukan': @json($amiS1Diajukan),
    'amiS2Diajukan': @json($amiS2Diajukan),
    'amiS3Diajukan': @json($amiS3Diajukan),
    'amiS1TDiajukan': @json($amiS1TDiajukan), 
    'amiS2TDiajukan': @json($amiS2TDiajukan),
    'amiS3TDiajukan': @json($amiS3TDiajukan),
    'amiPPGDiajukan': @json($amiPPGDiajukan),
    'amiD3Diterima': @json($amiD3Diterima),
    'amiS1Diterima': @json($amiS1Diterima),
    'amiS2Diterima': @json($amiS2Diterima),
    'amiS3Diterima': @json($amiS3Diterima),
    'amiS1TDiterima': @json($amiS1TDiterima), 
    'amiS2TDiterima': @json($amiS2TDiterima),
    'amiS3TDiterima': @json($amiS3TDiterima),
    'amiPPGDiterima': @json($amiPPGDiterima),
    'amiD3Koreksi': @json($amiD3Koreksi),
    'amiS1Koreksi': @json($amiS1Koreksi),
    'amiS2Koreksi': @json($amiS2Koreksi),
    'amiS3Koreksi': @json($amiS3Koreksi),
    'amiS1TKoreksi': @json($amiS1TKoreksi), 
    'amiS2TKoreksi': @json($amiS2TKoreksi),
    'amiS3TKoreksi': @json($amiS3TKoreksi),
    'amiPPGKoreksi': @json($amiPPGKoreksi),
    'amiD3Selesai': @json($amiD3Selesai),
    'amiS1Selesai': @json($amiS1Selesai),
    'amiS2Selesai': @json($amiS2Selesai),
    'amiS3Selesai': @json($amiS3Selesai),
    'amiS1TSelesai': @json($amiS1TSelesai), 
    'amiS2TSelesai': @json($amiS2TSelesai),
    'amiS3TSelesai': @json($amiS3TSelesai),
    'amiPPGSelesai': @json($amiPPGSelesai)
  };
</script>

@push('custom-scripts')
  <script src="{{ asset('assets/js/admin-dashboard.js') }}"></script>
  <script>
    document.getElementById('btnSync')?.addEventListener('click', function () {
      const btn = this;
      btn.disabled = true;
      btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Menyinkronkan...';

      fetch('{{ route("admin.feeder-config.sync") }}', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'X-Requested-With': 'XMLHttpRequest',
        }
      })
      .then(r => r.json())
      .then(data => {
        if (data.success) {
          alert('✓ ' + data.message);
          location.reload();
        } else {
          alert('✗ ' + data.message);
        }
      })
      .catch(() => alert('✗ Gagal menghubungi server.'))
      .finally(() => {
        btn.disabled = false;
        btn.innerHTML = '<i data-feather="refresh-cw" style="width:13px;height:13px;"></i> Sync Sekarang';
        feather.replace();
      });
    });
  </script>
@endpush