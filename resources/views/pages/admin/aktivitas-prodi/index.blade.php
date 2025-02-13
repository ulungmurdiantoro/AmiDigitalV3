@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
@endpush

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item active" aria-current="page">Aktivitas Prodi</li>
  </ol>
</nav>

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
          <div>
            <h4 class="mb-3 mb-md-0">Daftar Program Studi yang telah Implementasi AMI</h4>
          </div>
        </div>
        <div class="table-responsive">
          <table id="dataTableExample"  class="table table-striped table-hover">
            <thead>
              <tr>
                <th class="text-bg-secondary">Tanggal Pengajuan</th>
                <th class="text-bg-secondary">Periode</th>
                <th class="text-bg-secondary">Program Studi</th>
                <th class="text-bg-secondary">Progress</th>
                <th class="text-bg-secondary">aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($transaksi_ami as $transaksi_ami)
                <tr>
                  <td>{{ $transaksi_ami->formatted_created_at }}</td>
                  <td>{{ $transaksi_ami->periode }}</td>
                  <td>{{ $transaksi_ami->prodi }}</td>
                  <td class="text-center">
                    {{ $transaksi_ami->status }}
                    <div class="progress mt-1">
                      <div class="progress-bar progress-bar-striped progress-bar-animated {{ $transaksi_ami->progressColor }}" 
                        role="progressbar" 
                        style="width: {{ $transaksi_ami->progress }}%" 
                        aria-valuenow="{{ $transaksi_ami->progress }}" 
                        aria-valuemin="0" 
                        aria-valuemax="100">
                      </div>
                    </div>
                  </td>
                  <td>
                    <a href="{{ route('auditor.aktivitas-prodi.show-pengajuan', ['periode' => urlencode($transaksi_ami->periode), 'prodi' => $transaksi_ami->prodi]) }}" class="btn btn-primary btn-icon" rel="noopener noreferrer">
                      <i data-feather="plus-square"></i>
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