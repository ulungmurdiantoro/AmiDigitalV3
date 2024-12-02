@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
@endpush

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Statistik AMI</a></li>
    <li class="breadcrumb-item active" aria-current="page">Statistik Total</li>
  </ol>
</nav>

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
          <div>
            <h4 class="mb-3 mb-md-0">Daftar program studi yang telah menyelesaikan AMI</h4>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table table-striped table-hover">
            <thead class="text-bg-secondary">
              <tr>
                <th class="text-bg-secondary">Tanggal Pengajuan</th>
                <th class="text-bg-secondary">Periode</th>
                <th class="text-bg-secondary">Program Studi</th>
                <th class="text-bg-secondary">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Dai Rios</td>
                <td>Personnel Lead</td>
                <td>Edinburgh</td>
                <td>
                  <button type="button" class="btn btn-primary btn-icon-text">
                    <i class="btn-icon-prepend" data-feather="pie-chart"></i>
                      Rekap Nilai
                  </button>
                </td>
              </tr>
              <tr>
                <td>Jenette Caldwell</td>
                <td>Development Lead</td>
                <td>New York</td>
                <td>
                  <button type="button" class="btn btn-primary btn-icon-text">
                    <i class="btn-icon-prepend" data-feather="pie-chart"></i>
                      Rekap Nilai
                  </button>
                </td>
              </tr>
              <tr>
                <td>Yuri Berry</td>
                <td>Chief Marketing Officer (CMO)</td>
                <td>New York</td>
                <td>
                  <button type="button" class="btn btn-primary btn-icon-text">
                    <i class="btn-icon-prepend" data-feather="pie-chart"></i>
                      Rekap Nilai
                  </button>
                </td>
              </tr>
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