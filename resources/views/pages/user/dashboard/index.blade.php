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
              <h6 class="card-title mb-0">Total Target Tahun 2024</h6>
            </div>
            <br>
            <div class="row">
              <div class="col-6 col-md-12 col-xl-5">
                <h3 class="mb-2">4 Dokumen</h3>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-baseline">
              <h6 class="card-title mb-0">Total Capaian Tahun 2024</h6>
            </div>
            <br>
            <div class="row">
              <div class="col-6 col-md-12 col-xl-5">
                <h3 class="mb-2">1 Dokumen</h3>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-baseline">
              <h6 class="card-title mb-0">Total Kadaluarsa Tahun 2024</h6>
            </div>
            <br>
            <div class="row">
              <div class="col-6 col-md-12 col-xl-5">
                <h3 class="mb-2">1 Dokumen</h3>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div> <!-- row -->

<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
  <div>
    <h4 class="mb-3 mb-md-0">Progress AMI (Audit Mutu Internal) Tahun 2024 - Selesai <i class="btn-icon-prepend" data-feather="check-circle"></i>
    </h4>
  </div>
</div>
<div class="progress">
  <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
</div>
<br>

<div class="row">
  <div class="col-lg-5 col-xl-4 grid-margin grid-margin-xl-0 stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-baseline mb-2">
          <h6 class="card-title mb-0">Jadwal AMI</h6>
        </div>
        <p class="text-muted">
          Prodi : S1-Psikologi <br>
          Fakultas : Ekonomi <br>
          Standar Akreditasi : BAN-PT
        </p>
        <br>
        <div class="d-flex flex-column">
          <a href="javascript:;" class="d-flex align-items-center border-bottom pb-3">
            <div class="w-100">
              <div class="d-flex justify-content-between">
                <h6 class="fw-normal text-body mb-1">Opening Meeting AMI:</h6>
              </div>
              <p class="text-muted tx-13">2024-10-17</p>
            </div>
          </a>
          <a href="javascript:;" class="d-flex align-items-center border-bottom py-3">
            <div class="w-100">
              <div class="d-flex justify-content-between">
                <h6 class="fw-normal text-body mb-1">Pengisian Dokumen:</h6>
              </div>
              <p class="text-muted tx-13">2024-10-18 to 2024-10-19</p>
            </div>
          </a>
          <a href="javascript:;" class="d-flex align-items-center border-bottom py-3">
            <div class="w-100">
              <div class="d-flex justify-content-between">
                <h6 class="fw-normal text-body mb-1">Deskevaluasi:</h6>
              </div>
              <p class="text-muted tx-13">2024-10-20 to 2024-10-21</p>
            </div>
          </a>
          <a href="javascript:;" class="d-flex align-items-center border-bottom py-3">
            <div class="w-100">
              <div class="d-flex justify-content-between">
                <h6 class="fw-normal text-body mb-1">Audit Lapang:</h6>
              </div>
              <p class="text-muted tx-13">2024-10-22 to 2024-10-23</p>
            </div>
          </a>
          <a href="javascript:;" class="d-flex align-items-center border-bottom py-3">
            <div class="w-100">
              <div class="d-flex justify-content-between">
                <h6 class="fw-normal text-body mb-1">Penyusunan Tindakan Perbaikan dan Pencegahan:</h6>
              </div>
              <p class="text-muted tx-13">2024-10-22 to 2024-10-23</p>
            </div>
          </a>
          <a href="javascript:;" class="d-flex align-items-center border-bottom py-3">
            <div class="w-100">
              <div class="d-flex justify-content-between">
                <h6 class="fw-normal text-body mb-1">Penyusunan Laporan AMI:</h6>
              </div>
              <p class="text-muted tx-13">2024-10-24 to 2024-10-25</p>
            </div>
          </a>
          <a href="javascript:;" class="d-flex align-items-center border-bottom py-3">
            <div class="w-100">
              <div class="d-flex justify-content-between">
                <h6 class="fw-normal text-body mb-1">RTM:</h6>
              </div>
              <p class="text-muted tx-13">2024-10-31</p>
            </div>
          </a>
        </div>
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
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingOne">
              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                Pengumuman 1
              </button>
            </h2>
            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
              <div class="accordion-body">
                <strong>This is the first item's accordion body.</strong> It is shown by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
              </div>
              <div class="accordion-body">
                <p class="text-muted mb-3">Dikirim oleh : Titin Purnaningsih, S.Si., M.Si, pukul : 08:57:43 am, pada tanggal 2022-11-23.</p>
              </div>
            </div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingTwo">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                Pengumuman 2
              </button>
            </h2>
            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
              <div class="accordion-body">
                <strong>This is the second item's accordion body.</strong> It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
              </div>
            </div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingThree">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                Pengumuman 3
              </button>
            </h2>
            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
              <div class="accordion-body">
                <strong>This is the third item's accordion body.</strong> It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
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

@push('custom-scripts')
  <script src="{{ asset('assets/js/dashboard.js') }}"></script>
@endpush