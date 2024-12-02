@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
@endpush

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Pengguna Sistem</a></li>
    <li class="breadcrumb-item active" aria-current="page">Pengguna Prodi</li>
  </ol>
</nav>

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
          <div>
            <h4 class="mb-3 mb-md-0">Daftar Pengguna Prodi</h4>
          </div>
          <div class="d-flex align-items-center flex-wrap text-nowrap">
            <a href="{{ url('/admin/pengguna-prodi/create') }}" type="button" class="btn btn-primary btn-icon-text mb-2 mb-md-0">
              <i class="btn-icon-prepend" data-feather="plus-circle"></i>
                Tambah Data
            </a>
          </div>
        </div>
        <div class="table-responsive">
          <table id="dataTableExample"  class="table table-striped">
            <thead>
              <tr>
                <th class="text-bg-secondary">NIP/NIK(ID)</th>
                <th class="text-bg-secondary">Nama</th>
                <th class="text-bg-secondary">Jabatan</th>
                <th class="text-bg-secondary">Tanggung Jawab</th>
                <th class="text-bg-secondary">Fakultas</th>
                <th class="text-bg-secondary">Standar Akreditasi</th>
                <th class="text-bg-secondary">Status</th>
                <th class="text-bg-secondary">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($users as $user)
                <tr>
                  <td>{{ $user->user_id }}</td>
                  <td>{{ $user->user_nama }}</td>
                  <td>{{ $user->user_jabatan }}</td>
                  <td>{{ $user->user_penempatan }}</td>
                  <td>{{ $user->user_fakultas }}</td>
                  <td>{{ $user->user_akses }}</td>
                  <td>{{ $user->user_status }}</td>
                  <td>
                    <a href="{{ url('/admin/pengguna-prodi/' . $user->id . '/edit/') }}" class="btn btn-primary btn-icon" rel="noopener noreferrer">
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
                        <h5 class="modal-title" id="deleteModal">Delete Penggguna Prodi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                      </div>
                      <div class="modal-body" style="align-items: center;">
                        <h6><b>Are you sure?</b></h6>
                        <p>You won't be able to revert this!</p>
                      </div>
                      <div class="modal-footer">
                        <form action="{{ route('admin.pengguna-prodi.destroy', $user->id) }}" method="POST">
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