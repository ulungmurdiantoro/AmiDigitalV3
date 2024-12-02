@extends('layout.master-user')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
@endpush

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Rekap Dokumen</a></li>
    <li class="breadcrumb-item active" aria-current="page">Dokumen Kadaluarsa</li>  
  </ol>
</nav>

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
          <div>
            <h4 class="mb-3 mb-md-0">Daftar Dokumen Kadaluarsa</h4>
          </div>
        </div>
        <div class="table-responsive">
          <table id="dataTableExample" class="table table-striped">
            <thead>
              <tr>
                <th class="text-bg-secondary">Periode</th>
                <th class="text-bg-secondary">Kriteria</th>
                <th class="text-bg-secondary">Elemen</th>
                <th class="text-bg-secondary">Nama Dokumen</th>
                <th class="text-bg-secondary">Tipe Dokumen</th>
                <th class="text-bg-secondary">Keterangan</th>
                <th class="text-bg-secondary">Informasi Tambahan</th>
                <th class="text-bg-secondary">File</th>
                <th class="text-bg-secondary">Tanggal Kadaluarsa</th>
                <th class="text-bg-secondary">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($DokumenSpmiAmis as $DokumenSpmiAmi)
                <tr>
                  <td>{{ $DokumenSpmiAmi->kategori_dokumen }}</td>
                  <td>{{ $DokumenSpmiAmi->nama_dokumen }}</td>
                  <td>
                    <a href="{{ url('/admin/program-studi/download') }}" target="_blank" class="btn btn-warning btn-icon" rel="noopener noreferrer">
                      <i data-feather="download"></i>
                    </a>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
@endpush

@push('custom-scripts')
  <script src="{{ asset('assets/js/data-table.js') }}"></script>
@endpush