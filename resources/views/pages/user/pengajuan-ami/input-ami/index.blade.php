@extends('layout.master-user')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
  <div>
    <h4 class="mb-3 mb-md-0">Pemenuhan Dokumen {{ session('user_akses') }} {{ session('user_penempatan') }}</h4>
  </div>
</div>

<div class="row">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
          <h4 class="card-title">Data Pengajuan AMI (Audit Mutu Internal)</h4>
        </div>
        <div class="row">
          <div class="col-sm-6">
            <div class="mb-3">
              <label for="periode" class="form-label">Periode</label>
              <input name="periode" type="text" class="form-control @error('periode') is-invalid @enderror" value="{{ $transaksi_ami->periode }}" disabled/>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6">
            <div class="mb-3">
              <label for="status" class="form-label">Status</label>
              <input name="status" type="text" class="form-control @error('status') is-invalid @enderror" value="{{ $transaksi_ami->status }}" disabled/>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6">
            <div class="mb-3">
              <label for="auditor" class="form-label">Daftar Auditor</label>
              @foreach($transaksi_ami->auditorAmi as $auditor)
                <input name="auditor" type="text" class="form-control @error('auditor') is-invalid @enderror" value="{{ $auditor->user->user_nama }}" disabled/>
              @endforeach
            </div>
          </div>
        </div>
        @if ($transaksi_ami->status == 'Draft')
          <form action="{{ route('user.pengajuan-ami.input-ami.update') }}" method="POST" enctype="multipart/form-data" id="PenggunaAuditorForm">
            @csrf
            <input type="hidden" name="id" value="{{ $transaksi_ami->id }}">
            <input type="hidden" name="status" value="Diajukan">
            <input class="btn btn-success" type="submit" value="Ajukan AMI">
          </form>
        @endif
      </div>
    </div>
  </div>
</div>
{{-- @dd($transaksi_ami); --}}
@foreach ($nama_data_standar as $index => $nama)
  <div class="row">
    <div class="col-md-12 grid-margin stretch-card">
      <div class="card" style="border-radius: 5px; overflow: hidden;">
        <div class="card-header bg-primary text-white">
          <h6 class="mb-0">{{ $nama }}</h6>
        </div>
        <div class="card-body">
          {{-- @if (strpos($key, 'LAMDIK') !== false)
            <x-user.data-table.input-ami-lamdik
              id="dataTableExample{{ $index + 1 }}" 
              :standards="$data_standar['data_standar_k' . ($index + 1)]" 
              :periodes="$periode" 
              :prodis="$prodi"
              :transkasis="$transaksi_ami"
              :standarTargetsRelations="$standarTargetsRelation"
              :standarCapaiansRelations="$standarCapaiansRelation"
              :standarNilaisRelations="$standarNilaisRelation"
            />
          @else --}}
            <x-user.data-table.input-ami
              id="dataTableExample{{ $index + 1 }}" 
              :standards="$data_standar['data_standar_k' . ($index + 1)]" 
              :periodes="$periode" 
              :prodis="$prodi"
              :transkasis="$transaksi_ami"
              :standarTargetsRelations="$standarTargetsRelation"
              :standarCapaiansRelations="$standarCapaiansRelation"
              :standarNilaisRelations="$standarNilaisRelation"
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
      <li class="breadcrumb-item"><a href="#"></a></li>
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
  <script src="{{ asset('assets/js/data-table.js') }}"></script>
@endpush