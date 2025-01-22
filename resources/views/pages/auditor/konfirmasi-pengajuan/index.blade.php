@extends('layout.master-auditor')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
@endpush

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item active" aria-current="page">Konfirmasi Pengajuan</li>
  </ol>
</nav>

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
          <div>
            <h4 class="mb-3 mb-md-0">Daftar Pengajuan AMI</h4>
          </div>
        </div>
        <div class="table-responsive">
          <table id="dataTableExample" class="table table-striped">
            <thead>
              <tr>
                <th class="text-bg-secondary">Tanggal</th>
                <th class="text-bg-secondary">Periode</th>
                <th class="text-bg-secondary">Program Studi</th>
                <th class="text-bg-secondary">Informasi Tambahan</th>
                <th class="text-bg-secondary">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($Pengajuan as $Pengajuan)
                <tr>
                  <td>{{ $Pengajuan->created_at }}</td>
                  <td>{{ $Pengajuan->periode }}</td>
                  <td>{{ $Pengajuan->prodi }}</td>
                  <td>{{ $Pengajuan->informasi_tambahan }}</td>
                  <td>
                    <a href="{{ route('auditor.konfirmasi-pengajuan.show-pengajuan', ['periode' => urlencode($Pengajuan->periode), 'prodi' => $Pengajuan->prodi]) }}" class="btn btn-primary btn-icon" rel="noopener noreferrer">
                      <i data-feather="plus-square"></i>
                    </a>
                    <a href="#" class="btn btn-success btn-icon" data-bs-toggle="modal" data-bs-target="#acceptModal" rel="noopener noreferrer">
                      <i data-feather="check-circle"></i>
                    </a>
                    <a href="#" class="btn btn-danger btn-icon" data-bs-toggle="modal" data-bs-target="#rejectModal" rel="noopener noreferrer">
                      <i data-feather="delete"></i>
                    </a> 
                  </td>
                </tr>
                <div class="modal fade" id="acceptModal" tabindex="-1" aria-labelledby="acceptModal" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="acceptModal">Terima Pengajuan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                      </div>
                      <div class="modal-body" style="align-items: center;">
                        <h6><b>Are you sure?</b></h6>
                        <p>You won't be able to revert this!</p>
                      </div>
                      <div class="modal-footer">
                        <form action="{{ route('auditor.konfirmasi-pengajuan.update', $Pengajuan->id) }}" method="POST">
                          @csrf
                          @method('PUT')
                          <input type="hidden" name="id" value="{{ $Pengajuan->id }}">
                          <input type="hidden" name="status" value="Diterima">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                          <button type="submit" class="btn btn-success">Terima</button>
                        </form>
                        
                      </div>
                    </div>
                  </div>
                </div>                
                <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModal" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="rejectModal">Tolak Pengajuan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                      </div>
                      <div class="modal-body" style="align-items: center;">
                        <h6><b>Are you sure?</b></h6>
                        <p>You won't be able to revert this!</p>
                      </div>
                      <div class="modal-footer">
                        <form action="{{ route('auditor.konfirmasi-pengajuan.update', $Pengajuan->id) }}" method="POST">
                          @csrf
                          @method('PUT')
                          <input type="hidden" name="id" value="{{ $Pengajuan->id }}">
                          <input type="hidden" name="status" value="Draft">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                          <button type="submit" class="btn btn-danger">Tolak</button>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>                
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