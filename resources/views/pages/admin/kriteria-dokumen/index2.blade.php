@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
@endpush

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item active">Kriteria Dokumen</li>
  </ol>
</nav>

@php
  $institutions = ['BAN-PT', 'LAMDIK', 'INFOKOM', 'LAMEMBA'];
  $degreesByInstitution = [
    'BAN-PT' => ['D3', 'S1', 'S2', 'S3', 'S1 Terapan', 'S2 Terapan', 'S3 Terapan'],
    'LAMDIK' => ['PPG', 'S1', 'S2', 'S3', 'S1 Terapan', 'S2 Terapan', 'S3 Terapan'],
    'INFOKOM' => ['D3', 'S1', 'S2', 'S3', 'S1 Terapan', 'S2 Terapan', 'S3 Terapan'],
    'LAMEMBA' => ['D3', 'S1', 'S2', 'S3', 'S1 Terapan', 'S2 Terapan', 'S3 Terapan']
  ];
@endphp

<div class="row">
  @foreach($institutions as $institution)
    <div class="col-md-4 grid-margin stretch-card">
      <div class="card">
        <div class="card-header"><h4 class="card-title mb-0">{{ $institution }}</h4></div>
        <div class="card-body">
          <div class="d-flex flex-wrap justify-content-around">
            @foreach($degreesByInstitution[$institution] as $degree)
              <a href="{{ route('admin.kriteria-dokumen.index', ['akreditasi' => $institution, 'jenjang' => $degree]) }}"
                  class="btn btn-outline-primary my-3 {{ $akreditasi->nama == $institution && $jenjang->nama == $degree ? 'active' : '' }}">
                {{ $degree }}
              </a>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  @endforeach
</div>

<div class="row mt-4">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-header">
        <h4 class="card-title mb-0">{{ $akreditasi->nama }} {{ $jenjang->nama }}</h4>
      </div>
      <div class="card-body d-flex justify-content-center align-items-center">
        <a href="{{ route('admin.kriteria-dokumen.import', ['akreditasi' => $akreditasi->nama, 'jenjang' => $jenjang->nama]) }}"
            class="btn btn-success btn-lg btn-icon-text me-3">
          <i class="fas fa-file-import mr-2"></i> Import
        </a>
      </div>
    </div>
  </div>
</div>

@foreach($standards as $index => $standard)
  <div class="row mb-4">
    <div class="col-md-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-header bg-primary text-white">
          <h6 id="dataTitle{{ $index + 1 }}">
            @if(request('akreditasi') === 'LAMEMBA')
              <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn btn-warning btn-sm btn-icon" data-bs-toggle="modal" data-bs-target="#infoModal{{ $standard->id }}">
                  <i data-feather="info"></i>
                </button>
                <span>
                  {{ $standard->nama }} - {{ $akreditasi->nama }} {{ $jenjang->nama }}
                </span>
              </div>
            @else
              {{ $standard->nama }} - {{ $akreditasi->nama }} {{ $jenjang->nama }}
            @endif
          </h6>
        </div>
        <div class="modal fade" id="infoModal{{ $standard->id }}" tabindex="-1" aria-labelledby="infoModalLabel{{ $standard->id }}" aria-hidden="true">
          <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="infoModalLabel{{ $standard->id }}">Informasi Indikator</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
              </div>
              <div class="modal-body">
                <p><strong>Standard:</strong> {!! nl2br(e($standard->nama)) !!}</p>
                <hr>
                <p>{!! nl2br(e($standard->deskripsi)) !!}</p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
              </div>
            </div>
          </div>
        </div>
        <div class="card-body">
          @if(request('akreditasi') === 'LAMEMBA')
            <x-admin.kriteria-dokumen-lamemba-data-table
              id="dataTableExample{{ $index + 1 }}"
              :standards="$standard->elements"
              :showImportData="$index === 0"
              importTitle="{{ $akreditasi->nama }} {{ $jenjang->nama }}"
            />
          @else
            <x-admin.kriteria-dokumen-data-table 
              id="dataTableExample{{ $index + 1 }}"
              :standards="$standard->elements"
              :showImportData="$index === 0"
              importTitle="{{ $akreditasi->nama }} {{ $jenjang->nama }}"
            />
          @endif
        </div>
      </div>
    </div>
  </div>
@endforeach

<nav class="settings-sidebar">
  <div class="sidebar-body">
    <a href="#" class="settings-sidebar-toggler"><i data-feather="settings"></i></a>
    <h6 class="text-muted mb-2">Daftar Kriteria:</h6>
    <ul class="breadcrumb breadcrumb-dot mb-3 pb-3 border-bottom">
      @foreach($standards as $index => $standard)
        <li class="breadcrumb-item"><a href="#dataTitle{{ $index + 1 }}">{{ $standard->nama }}</a></li>
      @endforeach
    </ul>
  </div>
</nav>
@endsection

@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
@endpush

@push('custom-scripts')
<script>
  $(function () {
    $('.table').DataTable();
  });
</script>
@endpush
