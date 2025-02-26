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
                <div id="penggunaAdmin" data-value="{{ $penggunaAdmin }} class="mt-md-3 mt-xl-0"></div>
              </div>
              <div class="col-6 col-md-12 col-xl-6">
                <div id="penggunaProdi" data-value="{{ $penggunaProdi }} class="mt-md-3 mt-xl-0"></div>
              </div>
              <div class="col-6 col-md-12 col-xl-12">
                <div id="penggunaAuditor" data-value="{{ $penggunaAuditor }} class="mt-md-3 mt-xl-0"></div>
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
@endpush