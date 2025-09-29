@extends('layout.master-user')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
  <div>
    <h4 class="mb-3 mb-md-0">Pemenuhan Dokumen {{ $akreditasi->nama }} {{ $jenjang->nama }}</h4>
  </div>
</div>
{{-- @dd($key) --}}
@foreach ($standards as $index => $standard)
  <div class="row">
    <div class="col-md-12 grid-margin stretch-card">
      <div class="card" style="border-radius: 5px; overflow: hidden;">
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
        <div class="card-body">
          @if($akreditasi->nama === 'LAMEMBA')
            {{-- <x-user.data-table.pemenuhan-dokumen-lamemba
              id="dataTableExample{{ $index + 1 }}"
              :standards="$standard->elements"
              :showImportData="$index === 0"
              importTitle="{{ $akreditasi->nama }} {{ $jenjang->nama }}"
            /> --}}
          @else 
            <x-user.data-table.pemenuhan-dokumen 
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
  <script src="{{ asset('assets/js/data-table.js') }}"></script>
@endpush