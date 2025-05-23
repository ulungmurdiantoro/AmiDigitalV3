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
          <h4 class="card-title">Data Kesiapan Mutu {{ $transaksi_ami->prodi }} tahun {{ $transaksi_ami->periode }} </h4>
        </div>
        <div><b>Informasi tambahan :</b> </div>
        <div><i>Diajukan oleh {{ $transaksi_ami->prodi }} pada {{ $transaksi_ami->updated_at }} </i></div><br>
        @if ($transaksi_ami->status == 'Koreksi')
          <a href="#" data-bs-toggle="modal" data-bs-target="#selesaiModal" class="btn btn-success btn-icon-text mb-2 mb-md-0" rel="noopener noreferrer">
            <i class="link-icon" data-feather="check-circle"></i> <b>Selesaikan AMI</b>
          </a>
        @endif
        <div class="modal fade" id=selesaiModal tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <form action="{{ route('auditor.koreksi-ami.update', ['koreksi_ami' => $transaksi_ami->id]) }}" method="POST" enctype="multipart/form-data" id="PenggunaAuditorForm">
                @csrf
                @method('PUT')
                <div class="modal-header">
                  <h4 class="modal-title" id="exampleModalLabel"><b>Selesai AMI</b></h4>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <input type="hidden" name="ami_kode" class="form-control" value="" hidden>
                  <span>Jika Anda memutuskan untuk mengubah status menjadi "Selesai", maka Siklus AMI sudah selesai dan dapat di lihat hasilnya di menu 
                    Nilai Evaluasi Diri, Statistik AMI, dan Forcasting. 
                    <br><br> Harap pastikan bahwa semua indikator yang terkait sudah diperiksa dan disesuaikan sesuai dengan prosedur yang berlaku.</span>
                  <input type="hidden" name="id" value="{{ $transaksi_ami->id }}">
                  <input type="hidden" name="status" value="Selesai">
                </div>
                <div class="modal-footer">
                  <button type="submit" class="btn btn-success" data-bs-dismiss="modal">Submit</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@foreach ($nama_data_standar as $index => $nama)
  <div class="row">
    <div class="col-md-12 grid-margin stretch-card">
      <div class="card" style="border-radius: 5px; overflow: hidden;">
        <div class="card-header bg-primary text-white">
          <h6 class="mb-0">{{ $nama }}</h6>
        </div>
        <div class="card-body">
          {{-- @if (strpos($key, 'LAMDIK') !== false)
            <x-user.data-table.revisi-prodi-lamdik
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
            <x-user.data-table.revisi-prodi
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