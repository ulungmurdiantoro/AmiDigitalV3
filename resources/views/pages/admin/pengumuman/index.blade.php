@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
@endpush

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item active" aria-current="page">Pengumuman</li>
  </ol>
</nav>

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
          <div>
            <h4 class="mb-3 mb-md-0">Daftar Pengumuman</h4>
          </div>
          <div class="d-flex align-items-center flex-wrap text-nowrap">
            <a href="{{ url('/admin/pengumuman/create') }}" type="button" class="btn btn-primary btn-icon-text mb-2 mb-md-0">
              <i class="btn-icon-prepend" data-feather="plus-circle"></i>
                Tambah Data
            </a>
          </div>
        </div>
        <div class="accordion" id="accordionExample">
          @foreach($pengumuman as $index => $item)
            <div class="accordion-item">
              <h2 class="accordion-header" id="heading{{ $index }}">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}" aria-expanded="{{ $index == 0 ? 'true' : 'false' }}" aria-controls="collapse{{ $index }}">
                  {{ $item->pengumuman_judul }}
                </button>
              </h2>
              <div id="collapse{{ $index }}" class="accordion-collapse collapse {{ $index == 0 ? 'show' : '' }}" aria-labelledby="heading{{ $index }}" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                  {{ $item->pengumuman_informasi }}
                </div>
                <div class="accordion-body">
                  <p class="text-muted mb-3">Dikirim oleh: {{ $item->sender_name }}, pukul: {{ $item->created_at->format('H:i:s a') }}, pada tanggal {{ $item->created_at->format('Y-m-d') }}.</p>
                  <a href="{{ url('/admin/pengumuman/' . $item->id . '/edit/') }}" class="btn btn-primary btn-icon" rel="noopener noreferrer">
                    <i data-feather="edit"></i>
                  </a>
                  <a href="#" class="btn btn-danger btn-icon" data-bs-toggle="modal" data-bs-target="#deleteModal" rel="noopener noreferrer">
                    <i data-feather="delete"></i>
                  </a> 
                </div>
              </div>
            </div>
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
                    <form action="{{ route('admin.pengumuman.destroy', $item->id) }}" method="POST">
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