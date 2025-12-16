@extends('layout.master-user')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
@endpush

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Rekap Dokumen</a></li>
    <li class="breadcrumb-item active" aria-current="page">Dokumen {{ $activity }}</li>  
  </ol>
</nav>

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
          <div>
            <h4 class="mb-3 mb-md-0">Daftar Dokumen {{ $activity }}</h4>
          </div>
        </div>
        <div class="table-responsive">
          <table id="dataTableExample" class="table table-striped table-hover">
            <thead>
              <tr>
                <th class="text-bg-secondary">Periode</th>
                {{-- <th class="text-bg-secondary">Elemen</th> --}}
                <th class="text-bg-secondary">Indikator</th>
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
                  <td>{{ $DokumenSpmiAmi->periode }}</td>
                  {{-- <td>{{ $DokumenSpmiAmi->Indikator->nama_indikator ?? '-' }}</td> --}}
                  <td>{!! nl2br(e($DokumenSpmiAmi->Indikator->nama_indikator ?? '-')) !!}</td>
                  <td>{{ $DokumenSpmiAmi->dokumen_nama }}</td>
                  <td>{{ $DokumenSpmiAmi->dokumen_tipe }}</td>
                  <td>{{ $DokumenSpmiAmi->dokumen_keterangan }}</td>
                  <td>{{ $DokumenSpmiAmi->informasi }}</td>
                  <td>
                    <a href="{{ asset($DokumenSpmiAmi->dokumen_file) }}" 
                        target="_blank" 
                        class="btn btn-warning btn-icon" 
                        rel="noopener noreferrer">
                      <i data-feather="download"></i>
                    </a>
                  </td>
                  <td>{{ \Carbon\Carbon::parse($DokumenSpmiAmi->dokumen_kadaluarsa)->format('d M Y') }}</td>
                  <td>
                    <a href="{{ route('user.pemenuhan-dokumen.input-capaian.edit', $DokumenSpmiAmi->id) }}" 
                        class="btn btn-primary btn-icon" 
                        rel="noopener noreferrer">
                      <i data-feather="edit"></i>
                    </a>
                    <button type="button" 
                            class="btn btn-danger btn-icon" 
                            data-bs-toggle="modal" 
                            data-bs-target="#deleteModal{{ $DokumenSpmiAmi->id }}">
                      <i data-feather="delete"></i>
                    </button>
                  </td>
                </tr>

                <!-- Delete Confirmation Modal -->
                <div class="modal fade" id="deleteModal{{ $DokumenSpmiAmi->id }}" tabindex="-1" aria-hidden="true">
                  <div class="modal-dialog">
                    {{-- <form method="POST" action="{{ route('user.pemenuhan-dokumen.destroy', $DokumenSpmiAmi->id) }}">
                      @csrf
                      @method('DELETE')
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title">Konfirmasi Hapus</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                          Apakah kamu yakin ingin menghapus dokumen <strong>{{ $DokumenSpmiAmi->dokumen_nama }}</strong>?
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                          <button type="submit" class="btn btn-danger">Hapus</button>
                        </div>
                      </div>
                    </form> --}}
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
  <script>
    $(document).ready(function() {
      $('#dataTableExample').DataTable({
        pageLength: 10,
        ordering: true,
        searching: true,
        language: {
          url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
        }
      });
    });
  </script>
@endpush
