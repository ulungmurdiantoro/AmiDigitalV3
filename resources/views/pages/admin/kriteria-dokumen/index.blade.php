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
  @php
    $institutions = ['BAN-PT', 'LAMDIK', 'INFOKOM'];

    $degreesByInstitution = [
        'BAN-PT' => [
          'D3',
          'S1',
          'S2',
          'S3',
          'S1 Terapan',
          'S2 Terapan',
          'S3 Terapan'
        ],
        'LAMDIK' => [
          'PPG',
          'S1',
          'S2',
          'S3',
          'S1 Terapan',
          'S2 Terapan',
          'S3 Terapan'
        ],
        'INFOKOM' => [
          'D3',
          'S1',
          'S2',
          'S3',
          'S1 Terapan',
          'S2 Terapan',
          'S3 Terapan'
        ]
    ];
  @endphp

  @foreach($institutions as $institution)
    <div class="col-md-4 grid-margin stretch-card">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title mb-0">{{ $institution }}</h4>
        </div>
        <div class="card-body">
          <div class="d-flex flex-wrap justify-content-around">
            @foreach($degreesByInstitution[$institution] as $deg)
              <a href="{{ route('admin.kriteria-dokumen.index', ['degree' => $institution . ' ' . $deg]) }}" class="btn btn-outline-primary my-3">
                {{ $deg }}
              </a>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  @endforeach
</div>

@php
  $selectedDegree = request('degree', 'BAN-PT S1');
@endphp

<div class="row mt-4">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-header">
        <h4 class="card-title mb-0 import-title">{{ $selectedDegree }}</h4>
      </div>
      <div class="card-body d-flex justify-content-center align-items-center">
        <a href="{{ url('/admin/kriteria-dokumen/'. $selectedDegree . '/import/') }}" class="btn btn-success btn-lg btn-icon-text me-3">
          <i class="fas fa-file-import mr-2"></i> Import
        </a>
        {{-- <a href="{{ url('/admin/kriteria-dokumen/'. $selectedDegree . '/create/') }}" class="btn btn-primary btn-lg btn-icon-text me-3">
          <i class="fas fa-plus-circle mr-2"></i> Tambah Indikator
        </a> --}}
      </div>
    </div>
  </div>
</div>

@foreach ($nama_data_standar as $index => $nama)
  <div class="row">
    <div class="col-md-12 grid-margin stretch-card">
      <div class="card" style="border-radius: 5px; overflow: hidden;">
        <div class="card-header bg-primary text-white">
          <h6 id="dataTitle{{ $index + 1 }}">{{ $nama }} - {{ $selectedDegree }}</h6>
        </div>
        <div class="card-body">
          {{-- @if (strpos($selectedDegree, 'LAMDIK') !== false)
            <x-admin.kriteria-dokumen-lamdik-data-table 
              id="dataTableExample{{ $index + 1 }}" 
              :standards="$data_standar['data_standar_k' . ($index + 1)]"
              :showImportData="$index == 0"
              importTitle="{{ $selectedDegree }}"
              :standarTargetsRelations="$standarTargetsRelation"
            />
          @else --}}
            <x-admin.kriteria-dokumen-data-table 
              id="dataTableExample{{ $index + 1 }}" 
              :standards="$data_standar['data_standar_k' . ($index + 1)]"
              :showImportData="$index == 0"
              importTitle="{{ $selectedDegree }}"
              :standarTargetsRelations="$standarTargetsRelation"
            />
          {{-- @endif --}}
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
  $(document).ready(function() {
    @foreach ($nama_data_standar as $index => $nama)
      $('#dataTableExample{{ $index + 1 }}').DataTable();
    @endforeach
  });
</script>
@endpush