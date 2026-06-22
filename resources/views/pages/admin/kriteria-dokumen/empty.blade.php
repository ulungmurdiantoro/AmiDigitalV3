@extends('layout.master')

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item active">Kriteria Dokumen</li>
  </ol>
</nav>

@if(!empty($available ?? []))
  {{-- Tetap tampilkan selektor akreditasi/jenjang yang punya data --}}
  <div class="row">
    @foreach(($available ?? []) as $institution => $degrees)
      <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
          <div class="card-header"><h4 class="card-title mb-0">{{ $institution }}</h4></div>
          <div class="card-body">
            <div class="d-flex flex-wrap justify-content-around">
              @foreach($degrees as $degree)
                <a href="{{ route('admin.kriteria-dokumen.index', ['akreditasi' => $institution, 'jenjang' => $degree['jenjang']]) }}"
                   class="btn btn-outline-primary my-2">{{ $degree['label'] }}</a>
              @endforeach
            </div>
          </div>
        </div>
      </div>
    @endforeach
  </div>
@endif

<div class="alert alert-warning">
  Belum ada data kriteria
  @if(!empty($akreditasi))
    untuk <strong>{{ optional($akreditasi)->nama }} {{ optional($jenjang)->nama }}</strong>
  @endif.
  Silakan jalankan seeder / import data kriteria terlebih dahulu.
</div>
@endsection
