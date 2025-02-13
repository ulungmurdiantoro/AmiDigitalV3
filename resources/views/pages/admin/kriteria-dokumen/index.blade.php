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
  <div class="col-md-4 grid-margin stretch-card">
    <div class="card">
      <div class="card-header">
        <h4 class="card-title mb-0">BAN-PT</h4>
      </div>
      <div class="card-body">
        <div class="d-flex flex-wrap justify-content-around">
          <a href="{{ route('admin.kriteria-dokumen.index', ['degree' => 'BAN-PT D3']) }}" class="btn btn-outline-primary my-3">D3</a>
          <a href="{{ route('admin.kriteria-dokumen.index', ['degree' => 'BAN-PT S1']) }}" class="btn btn-outline-primary my-3">S1</a>
          <a href="{{ route('admin.kriteria-dokumen.index', ['degree' => 'BAN-PT S2']) }}" class="btn btn-outline-primary my-3">S2</a>
          <!-- Repeat for other degrees -->
          
          <button class="btn btn-outline-primary my-3" onclick="updateImportSection('BAN-PT S3')">S3</button>
          <button class="btn btn-outline-primary my-3" onclick="updateImportSection('BAN-PT S1 Terapan')">S1 Terapan</button>
          <button class="btn btn-outline-primary my-3" onclick="updateImportSection('BAN-PT S2 Terapan')">S2 Terapan</button>
          <button class="btn btn-outline-primary my-3" onclick="updateImportSection('BAN-PT S3 Terapan')">S3 Terapan</button>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row mt-4">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-header">
        <h4 class="card-title mb-0 import-title">{{ $degree }}</h4>
      </div>
      <div class="card-body d-flex justify-content-center align-items-center">
        <a href="{{ url('/admin/kriteria-dokumen/'. $degree . '/create/') }}" class="btn btn-success btn-lg btn-icon-text">
          <i class="fas fa-file-import mr-2"></i> Import
        </a>
      </div>
    </div>
  </div>
</div>

@foreach ($nama_data_standar as $index => $nama)
  <div class="row">
    <div class="col-md-12 grid-margin stretch-card">
      <div class="card" style="border-radius: 5px; overflow: hidden;">
        <div class="card-header bg-primary text-white">
          <h6 id="dataTitle{{ $index + 1 }}">{{ $nama }} - {{ $degree }}</h6>
        </div>
        <div class="card-body">
          <x-admin.kriteria-dokumen-data-table 
            id="dataTableExample{{ $index + 1 }}" 
            :standards="$data_standar['data_standar_k' . ($index + 1)]"
            :showImportData="$index == 0"
            importTitle="{{ $degree }}" />
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
        @foreach ($nama_data_standar as $index => $nama)
          <li class="breadcrumb-item"><a href="#dataTitle{{ $index + 1 }}">{{ $nama }}</a></li>
        @endforeach
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
  function updateImportSection(degree) {
    var importTitleElements = document.querySelectorAll('.import-title');
    importTitleElements.forEach(function(element) {
        element.textContent = 'Import Data ' + degree;
    });
  }

  $(document).ready(function() {
    @foreach ($nama_data_standar as $index => $nama)
      $('#dataTableExample{{ $index + 1 }}').DataTable();
    @endforeach
  });
</script>
@endpush
