@extends('layout.master-user')

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
      <i class="btn-icon-prepend" data-feather="layers"></i>
        Pemenuhan Dokumen
    </button>
    <button type="button" class="btn btn-primary btn-icon-text mb-2 mb-md-0">
      <i class="btn-icon-prepend" data-feather="file-text"></i>
        Nilai Evaluasi Diri
    </button>
  </div>
</div>

<div class="row">
  <div class="col-12 col-xl-12 stretch-card">
    <div class="row flex-grow-1">
      <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-baseline">
              <h6 class="card-title mb-0">Total Target Tahun {{ $periode }}</h6>
            </div>
            <br>
            <div class="row">
              <div class="col-6 col-md-12 col-xl-5">
                <h3 class="mb-2">{{ $totalTarget }} Dokumen</h3>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-baseline">
              <h6 class="card-title mb-0">Total Capaian Tahun {{ $periode }}</h6>
            </div>
            <br>
            <div class="row">
              <div class="col-6 col-md-12 col-xl-5">
                <h3 class="mb-2">{{ $totalCapaian }} Dokumen</h3>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-baseline">
              <h6 class="card-title mb-0">Total Kadaluarsa Tahun {{ $periode }}</h6>
            </div>
            <br>
            <div class="row">
              <div class="col-6 col-md-12 col-xl-5">
                <h3 class="mb-2">{{ $totalKadaluarsa }} Dokumen</h3>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div> <!-- row -->
@foreach ($transaksi_ami as $transaksi)
  <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
      <h4 class="mb-3 mb-md-0">Progress AMI (Audit Mutu Internal) Tahun {{ $periode }} - {{ $transaksi->status }} <i class="btn-icon-prepend" data-feather="check-circle"></i>
      </h4>
    </div>
  </div>
  <div class="progress">
    <div class="progress-bar progress-bar-striped progress-bar-animated {{ $transaksi->progressColor }}" role="progressbar" style="width: {{ $transaksi->progress }}%" aria-valuenow="{{ $transaksi->progress }}" aria-valuemin="0" aria-valuemax="100"></div>
  </div>
@endforeach
<br>
<div class="row">
  <div class="col-lg-5 col-xl-4 grid-margin grid-margin-xl-0 stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-baseline mb-2">
          <h6 class="card-title mb-0">Jadwal AMI</h6>
        </div>
        @foreach ($jadwalAmi as $item)
          <p class="text-muted">
            Prodi : {{ $item->prodi }} <br>
            Fakultas : {{ $item->fakultas }} <br>
            Standar Akreditasi : {{ $item->standar_akreditasi }}
          </p>
          <br>
          <div class="d-flex flex-column">
            <a href="javascript:;" class="d-flex align-items-center border-bottom pb-3">
              <div class="w-100">
                <div class="d-flex justify-content-between">
                  <h6 class="fw-normal text-body mb-1">Opening Meeting AMI:</h6>
                </div>
                <p class="text-muted tx-13">{{ $item->formatted_opening_ami }}</p>
              </div>
            </a>
            <a href="javascript:;" class="d-flex align-items-center border-bottom py-3">
              <div class="w-100">
                <div class="d-flex justify-content-between">
                  <h6 class="fw-normal text-body mb-1">Pengisian Dokumen:</h6>
                </div>
                <p class="text-muted tx-13">{{ $item->formatted_pengisian_dokumen }}</p>
              </div>
            </a>
            <a href="javascript:;" class="d-flex align-items-center border-bottom py-3">
              <div class="w-100">
                <div class="d-flex justify-content-between">
                  <h6 class="fw-normal text-body mb-1">Deskevaluasi:</h6>
                </div>
                <p class="text-muted tx-13">{{ $item->formatted_deskevaluasion }}</p>
              </div>
            </a>
            <a href="javascript:;" class="d-flex align-items-center border-bottom py-3">
              <div class="w-100">
                <div class="d-flex justify-content-between">
                  <h6 class="fw-normal text-body mb-1">Audit Lapang:</h6>
                </div>
                <p class="text-muted tx-13">{{ $item->formatted_assessment }}</p>
              </div>
            </a>
            <a href="javascript:;" class="d-flex align-items-center border-bottom py-3">
              <div class="w-100">
                <div class="d-flex justify-content-between">
                  <h6 class="fw-normal text-body mb-1">Penyusunan Tindakan Perbaikan dan Pencegahan:</h6>
                </div>
                <p class="text-muted tx-13">{{ $item->formatted_tindakan_koreksi }}</p>
              </div>
            </a>
            <a href="javascript:;" class="d-flex align-items-center border-bottom py-3">
              <div class="w-100">
                <div class="d-flex justify-content-between">
                  <h6 class="fw-normal text-body mb-1">Penyusunan Laporan AMI:</h6>
                </div>
                <p class="text-muted tx-13">{{ $item->formatted_laporan_ami }}</p>
              </div>
            </a>
            <a href="javascript:;" class="d-flex align-items-center border-bottom py-3">
              <div class="w-100">
                <div class="d-flex justify-content-between">
                  <h6 class="fw-normal text-body mb-1">RTM:</h6>
                </div>
                <p class="text-muted tx-13">{{ $item->formatted_rtm }}</p>
              </div>
            </a>
          </div>
        @endforeach
      </div>
    </div>
  </div>
  <div class="col-lg-7 col-xl-8 stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-baseline mb-2">
          <h6 class="card-title mb-0">Pengumuman</h6>
        </div>
        <p class="text-muted">
          Pengumuman dan Informasi
        </p>
        <br>
        <div class="accordion" id="accordionExample">
          @foreach($pengumuman as $index => $item)
            <div class="accordion-item">
              <h2 class="accordion-header" id="heading{{ $index }}">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}" aria-expanded="{{ $index == 0 ? 'true' : 'false' }}" aria-controls="collapse{{ $index }}">
                  {{ $item->pengumuman_judul }}
                </button>
              </h2>
              <div id="collapse{{ $index }}" class="accordion-collapse collapse {{ $index == 0 ? 'show' : '' }}" aria-labelledby="heading{{ $index }}" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                  {{ $item->pengumuman_informasi }}
                </div>
                <div class="accordion-body">
                  <p class="text-muted mb-3">Dikirim oleh: {{ $item->sender_name }}, pukul: {{ $item->created_at->format('H:i:s a') }}, pada tanggal {{ $item->created_at->format('Y-m-d') }}.</p>
                </div>
              </div>
            </div>
          @endforeach
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

@push('custom-scripts')
  <script src="{{ asset('assets/js/dashboard.js') }}"></script>
@endpush