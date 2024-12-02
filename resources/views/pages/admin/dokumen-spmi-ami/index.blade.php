@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
@endpush

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item active" aria-current="page">Dokumen SPMI & AMI</li>
  </ol>
</nav>

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
          <div>
            <h4 class="mb-3 mb-md-0">Daftar Dokumen SPMI & AMI</h4>
          </div>
          <div class="d-flex align-items-center flex-wrap text-nowrap">
            <a href="{{ url('/admin/dokumen-spmi-ami/create') }}" type="button" class="btn btn-primary btn-icon-text mb-2 mb-md-0">
              <i class="btn-icon-prepend" data-feather="plus-circle"></i>
                Tambah Data
            </a>
          </div>
        </div>
        <div class="table-responsive">
          <table id="dataTableExample" class="table table-striped">
            <thead>
              <tr>
                <th class="text-bg-secondary">Kategori Dokumen</th>
                <th class="text-bg-secondary">Nama Dokumen</th>
                <th class="text-bg-secondary">File</th>
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
                  <td>
                    <a href="{{ url('/admin/program-studi/' . $DokumenSpmiAmi->id . '/edit/') }}" class="btn btn-primary btn-icon" rel="noopener noreferrer">
                      <i data-feather="edit"></i>
                    </a>
                    <a href="#" class="btn btn-danger btn-icon" data-bs-toggle="modal" data-bs-target="#deleteModal" rel="noopener noreferrer">
                      <i data-feather="delete"></i>
                    </a> 
                  </td>
                </tr>
                <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModal" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="deleteModal">Delete Program Studi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                      </div>
                      <div class="modal-body" style="align-items: center;">
                        <h6><b>Are you sure?</b></h6>
                        <p>You won't be able to revert this!</p>
                      </div>
                      <div class="modal-footer">
                        <form action="{{ route('admin.dokumen-spmi-ami.destroy', $DokumenSpmiAmi->id) }}" method="POST">
                          @csrf
                          @method('DELETE')
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                          <button type="submit" class="btn btn-danger">Delete</button>
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