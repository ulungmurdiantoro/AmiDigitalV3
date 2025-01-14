@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
@endpush

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item active" aria-current="page">Kriteria Dokumen</li>
  </ol>
</nav>

<div class="row">
  <div class="col-12 col-xl-12 stretch-card">
    <div class="row flex-grow-1">
      <!-- Your existing buttons for different categories -->
    </div>
  </div>
</div> <!-- row -->

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-header">
        <h4 class="card-title">Import Data BAN-PT S1</h4>
      </div>
      <div class="card-body d-flex justify-content-center align-items-center">
        <a href="{{ url('/admin/kriteria-dokumen/create') }}" class="btn btn-success btn-lg btn-icon-text">
          <i class="fas fa-file-import mr-2"></i> Import
        </a>
      </div>
    </div>
  </div>
</div>

@foreach ($nama_data_standar as $index => $nama)
<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
          <div>
            <p id="dataTitle{{ $index + 1 }}" class="mb-3"><b>{{ $nama }}</b></p>
          </div>
        </div>
        <x-admin.kriteria-dokumen-data-table id="dataTableExample{{ $index + 1 }}" :standards="$data_standar['data_standar_k' . ($index + 1)]" />
      </div>
    </div>
  </div>
</div>
@endforeach

<nav class="settings-sidebar">
  <div class="sidebar-body">
    <a href="#" class="settings-sidebar-toggler">
      <i data-feather="settings"></i>
    </a>
    <h6 class="text-muted mb-2">Daftar Kriteria:</h6>
    <div class="mb-3 pb-3 border-bottom">
      <ul class="breadcrumb breadcrumb-dot">
        <li class="breadcrumb-item"><a href="#dataTitle1">Kondisi Eksternal</a></li>
        <li class="breadcrumb-item"><a href="#dataTitle2">Profil UPPS</a></li>
        <li class="breadcrumb-item"><a href="#dataTitle3">Kriteria 1</a></li>
        <li class="breadcrumb-item"><a href="#dataTitle4">Kriteria 2</a></li>
        <li class="breadcrumb-item"><a href="#dataTitle5">Kriteria 3</a></li>
        <li class="breadcrumb-item"><a href="#dataTitle6">Kriteria 4</a></li>
        <li class="breadcrumb-item"><a href="#dataTitle7">Kriteria 5</a></li>
        <li class="breadcrumb-item"><a href="#dataTitle8">Kriteria 6</a></li>
        <li class="breadcrumb-item"><a href="#dataTitle9">Kriteria 7</a></li>
        <li class="breadcrumb-item"><a href="#dataTitle10">Kriteria 8</a></li>
        <li class="breadcrumb-item"><a href="#dataTitle11">Kriteria 9</a></li>
        <li class="breadcrumb-item"><a href="#dataTitle12">Analisis dan Penetapan Program Pengembangan</a></li>
      </ul>
    </div>
  </div>
</nav>

@endsection

@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
@endpush

@push('custom-scripts')
<script>
  $(document).ready(function() {
    $('#dataTableExample1, #dataTableExample2, #dataTableExample3, #dataTableExample4, #dataTableExample5, #dataTableExample6, #dataTableExample7, #dataTableExample8, #dataTableExample9, #dataTableExample10, #dataTableExample11, #dataTableExample12, #dataTableExample13, #dataTableExample14, #dataTableExample15, #dataTableExample16, #dataTableExample17, #dataTableExample18, #dataTableExample19, #dataTableExample20, #dataTableExample21, #dataTableExample22, #dataTableExample23, #dataTableExample24, #dataTableExample25, #dataTableExample26, #dataTableExample27, #dataTableExample28, #dataTableExample29, #dataTableExample30, #dataTableExample31, #dataTableExample32, #dataTableExample33, #dataTableExample34, #dataTableExample35, #dataTableExample36').DataTable();
  });

  // Additional custom scripts for button functionality
</script>
@endpush
