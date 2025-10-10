@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
@endpush

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item active">Kriteria Dokumen</li>
  </ol>
</nav>

@php
  $institutions = ['BAN-PT', 'LAMDIK', 'INFOKOM', 'LAMEMBA'];
  $degreesByInstitution = [
    'BAN-PT' => ['D3', 'S1', 'S2', 'S3', 'S1 Terapan', 'S2 Terapan', 'S3 Terapan'],
    'LAMDIK' => ['PPG', 'S1', 'S2', 'S3', 'S1 Terapan', 'S2 Terapan', 'S3 Terapan'],
    'INFOKOM' => ['D3', 'S1', 'S2', 'S3', 'S1 Terapan', 'S2 Terapan', 'S3 Terapan'],
    'LAMEMBA' => ['D3', 'S1', 'S2', 'S3', 'S1 Terapan', 'S2 Terapan', 'S3 Terapan']
  ];
@endphp

<div class="row">
  @foreach($institutions as $institution)
    <div class="col-md-4 grid-margin stretch-card">
      <div class="card">
        <div class="card-header"><h4 class="card-title mb-0">{{ $institution }}</h4></div>
        <div class="card-body">
          <div class="d-flex flex-wrap justify-content-around">
            @foreach($degreesByInstitution[$institution] as $degree)
              <a href="{{ route('admin.kriteria-dokumen.index', ['akreditasi' => $institution, 'jenjang' => $degree]) }}"
                class="btn btn-outline-primary my-3 {{ $akreditasi->nama == $institution && $jenjang->nama == $degree ? 'active' : '' }}">
                {{ $degree }}
              </a>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  @endforeach
</div>

@foreach($standards as $index => $standard)
  <div class="row mb-4">
    <div class="col-md-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-header bg-primary text-white">
          <h6 id="dataTitle{{ $index + 1 }}" class="mb-0">
            @if(request('akreditasi') === 'LAMEMBA')
              <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn btn-warning btn-sm btn-icon" data-bs-toggle="modal" data-bs-target="#infoModal{{ $standard->id }}">
                  <i data-feather="info"></i>
                </button>
                <span>{{ $standard->nama }} - {{ $akreditasi->nama }} {{ $jenjang->nama }}</span>
              </div>
            @else
              {{ $standard->nama }} - {{ $akreditasi->nama }} {{ $jenjang->nama }}
            @endif
          </h6>
        </div>

        {{-- INFO MODAL: satu header, tombol tambah di kanan --}}
        <div class="modal fade" id="infoModal{{ $standard->id }}" tabindex="-1" aria-labelledby="infoModalLabel{{ $standard->id }}" aria-hidden="true">
          <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header justify-content-between">
                <h5 class="modal-title" id="infoModalLabel{{ $standard->id }}">Informasi Kriteria</h5>
                <button type="button" class="btn-close ms-1" data-bs-dismiss="modal" aria-label="Tutup"></button>
              </div>
              <div class="modal-header d-flex justify-content-between align-items-center">
                <div>
                  <p><strong>Kriteria:</strong> {!! nl2br(e($standard->nama)) !!}</p>
                </div>
                <div>
                  <a href="{{ route('admin.kriteria-dokumen.kelola-bukti.create', ['importTitle' => $akreditasi->nama . ' ' . $jenjang->nama, 'id' => $standard->id]) }}" class="btn btn-sm btn-primary">
                    <i data-feather="plus-square"></i> Tambah Bukti
                  </a>
                </div>
              </div>
              <div class="modal-body">
                <p>{!! nl2br(e($standard->deskripsi)) !!}</p>

                @foreach ($standard->buktiStandar as $i => $bukti)
                  <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                      <strong>{{ $i + 1 }}.</strong> {{ $bukti->nama }}
                      @if ($bukti->deskripsi) - {{ $bukti->deskripsi }} @endif
                    </div>
                    <div class="btn-group">
                      {{-- EDIT --}}
                      <button type="button"
                              class="btn btn-sm btn-outline-primary btn-edit-bukti"
                              data-source-modal="#infoModal{{ $standard->id }}"
                              data-update-url="{{ route('admin.kriteria-dokumen.kelola-bukti.update', $bukti->id) }}"
                              data-nama="{{ $bukti->nama }}"
                              data-deskripsi="{{ $bukti->deskripsi }}">
                        <i data-feather="edit-3"></i>
                      </button>
                      {{-- DELETE --}}
                      <button type="button"
                              class="btn btn-sm btn-outline-danger btn-delete-bukti"
                              data-source-modal="#infoModal{{ $standard->id }}"
                              data-destroy-url="{{ route('admin.kriteria-dokumen.kelola-bukti.destroy', $bukti->id) }}"
                              data-nama="{{ $bukti->nama }}"
                              data-deskripsi="{{ $bukti->deskripsi }}">
                        <i data-feather="trash-2"></i>
                      </button>
                    </div>
                  </div>
                @endforeach
              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
              </div>
            </div>
          </div>
        </div>
        {{-- END INFO MODAL --}}

        <div class="card-body">
          @if(request('akreditasi') === 'LAMEMBA')
            <x-admin.kriteria-dokumen-lamemba-data-table
              id="dataTableExample{{ $index + 1 }}"
              :standards="$standard->elements"
              :showImportData="$index === 0"
              importTitle="{{ $akreditasi->nama }} {{ $jenjang->nama }}"
            />
          @else
            <x-admin.kriteria-dokumen-data-table 
              id="dataTableExample{{ $index + 1 }}"
              :standards="$standard->elements"
              :showImportData="$index === 0"
              importTitle="{{ $akreditasi->nama }} {{ $jenjang->nama }}"
            />
          @endif
        </div>
      </div>
    </div>
  </div>
@endforeach

<nav class="settings-sidebar">
  <div class="sidebar-body">
    <a href="#" class="settings-sidebar-toggler"><i data-feather="settings"></i></a>
    <h6 class="text-muted mb-2">Daftar Kriteria:</h6>
    <ul class="breadcrumb breadcrumb-dot mb-3 pb-3 border-bottom">
      @foreach($standards as $index => $standard)
        <li class="breadcrumb-item"><a href="#dataTitle{{ $index + 1 }}">{{ $standard->nama }}</a></li>
      @endforeach
    </ul>
  </div>
</nav>

{{-- REUSABLE MODALS (diletakkan SEKALI SAJA, di luar loop) --}}
<div class="modal fade" id="editBuktiModal" tabindex="-1" aria-labelledby="editBuktiModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form id="editBuktiForm" action="#" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-header">
          <h5 class="modal-title" id="editBuktiModalLabel">Edit Bukti</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3 text-start">
            <label for="edit_nama" class="form-label">Nama Bukti</label>
            <input type="text" class="form-control" id="edit_nama" name="nama" required>
          </div>
          <div class="mb-3 text-start">
            <label for="edit_deskripsi" class="form-label">Deskripsi</label>
            <textarea class="form-control" id="edit_deskripsi" name="deskripsi" rows="3"></textarea>
          </div>
          {{-- Context opsional jika diperlukan server-side --}}
          <input type="hidden" name="importTitle" value="{{ $akreditasi->nama . ' ' . $jenjang->nama }}">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="deleteBuktiModal" tabindex="-1" aria-labelledby="deleteBuktiModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form id="deleteBuktiForm" action="#" method="POST">
        @csrf
        @method('DELETE')
        <div class="modal-header">
          <h5 class="modal-title" id="deleteBuktiModalLabel">Konfirmasi Hapus</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body text-start">
          <p>Yakin ingin menghapus bukti <strong id="delete_nama_preview">—</strong>?</p>
          <p class="text-muted mb-0"><em id="delete_deskripsi_preview"></em></p>
          {{-- Context opsional jika diperlukan server-side --}}
          <input type="hidden" name="importTitle" value="{{ $akreditasi->nama . ' ' . $jenjang->nama }}">
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-danger">Hapus</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        </div>
      </form>
    </div>
  </div>
</div>
{{-- END REUSABLE MODALS --}}

@endsection

@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
  <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
@endpush

@push('custom-scripts')
<script>
  // DataTables init - sesuaikan selector jika komponen tabelmu punya class khusus
  $(function () {
    $('.table').DataTable();
  });

  document.addEventListener('DOMContentLoaded', function () {
    if (window.feather) feather.replace();

    const editForm   = document.getElementById('editBuktiForm');
    const editNama   = document.getElementById('edit_nama');
    const editDesk   = document.getElementById('edit_deskripsi');

    const deleteForm  = document.getElementById('deleteBuktiForm');
    const delNamaPrev = document.getElementById('delete_nama_preview');
    const delDeskPrev = document.getElementById('delete_deskripsi_preview');

    // helper: tutup modal sumber lalu buka target
    function openAfterHidingSource(sourceSelector, openFn) {
      const srcEl = document.querySelector(sourceSelector);
      if (!srcEl) { openFn(); return; }
      const srcModal = bootstrap.Modal.getOrCreateInstance(srcEl);
      const handler = function () {
        srcEl.removeEventListener('hidden.bs.modal', handler);
        openFn();
      };
      srcEl.addEventListener('hidden.bs.modal', handler);
      srcModal.hide();
    }

    // EDIT handler
    document.querySelectorAll('.btn-edit-bukti').forEach(btn => {
      btn.addEventListener('click', function () {
        const updateUrl = this.dataset.updateUrl;
        const nama      = this.dataset.nama || '';
        const deskripsi = this.dataset.deskripsi || '';
        const source    = this.dataset.sourceModal || null;

        editForm.setAttribute('action', updateUrl);
        editNama.value = nama;
        editDesk.value = deskripsi;

        openAfterHidingSource(source, () => {
          const target = document.getElementById('editBuktiModal');
          bootstrap.Modal.getOrCreateInstance(target).show();
        });
      });
    });

    // DELETE handler
    document.querySelectorAll('.btn-delete-bukti').forEach(btn => {
      btn.addEventListener('click', function () {
        const destroyUrl = this.dataset.destroyUrl;
        const nama       = this.dataset.nama || '—';
        const deskripsi  = this.dataset.deskripsi || '';
        const source     = this.dataset.sourceModal || null;

        deleteForm.setAttribute('action', destroyUrl);
        delNamaPrev.textContent = nama;
        delDeskPrev.textContent = deskripsi;

        openAfterHidingSource(source, () => {
          const target = document.getElementById('deleteBuktiModal');
          bootstrap.Modal.getOrCreateInstance(target).show();
        });
      });
    });

    // Feather re-render saat modal tampil
    const reIcon = () => { if (window.feather) feather.replace(); };
    document.getElementById('editBuktiModal').addEventListener('shown.bs.modal', reIcon);
    document.getElementById('deleteBuktiModal').addEventListener('shown.bs.modal', reIcon);
  });
</script>
@endpush
