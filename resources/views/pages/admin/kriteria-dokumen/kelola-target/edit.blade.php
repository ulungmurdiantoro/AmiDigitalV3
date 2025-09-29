@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/jquery-tags-input/jquery.tagsinput.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/dropzone/dropzone.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/dropify/css/dropify.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/pickr/themes/classic.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Kriteria Dokumen</a></li>
    <li class="breadcrumb-item"><a href="#">Kelola Dokumen</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit Dokumen</li>
  </ol>
</nav>

<div class="row">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Edit Dokumen</h4>
        <form action="{{ route('admin.kriteria-dokumen.kelola-target.update', $standarTarget->id) }}" method="POST" enctype="multipart/form-data" id="KelolaTargetForm">
            @csrf
            @method('PUT') 
            <div class="row">
                <div class="col-sm-6">
                    <div class="mb-3">
                        <label for="standar_nama" class="form-label">Kriteria</label>
                        <input id="standar_nama" class="form-control" name="standar_nama" type="text" value="{{ $standarElemen->standar_nama }}" disabled>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="mb-3">
                        <label for="elemen_nama" class="form-label">Elemen</label>
                        <input id="elemen_nama" class="form-control" name="elemen_nama" type="text" value="{{ $standarElemen->elemen_nama }}" disabled>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="mb-3">
                        <label for="indikator_nama" class="form-label">Indikator</label>
                        <textarea id="indikator_nama" class="form-control" name="indikator_nama" disabled>{{ $standarElemen->indikator_nama }}</textarea>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="mb-3">
                        <label for="indikator_id" class="form-label">Jenjang</label>
                        <input id="indikator_id" class="form-control" name="indikator_id" type="text" value="{{ $indikator_id }}" disabled>
                        <input type="hidden" name="indikator_id" value="{{ $indikator_id }}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="mb-3">
                        <label for="dokumen_nama" class="form-label">Nama Dokumen</label>
                        <input id="dokumen_nama" class="form-control @error('dokumen_nama') is-invalid @enderror" name="dokumen_nama" type="text" value="{{ old('dokumen_nama', $standarTarget->dokumen_nama) }}" placeholder="Nama Dokumen">
                        @error('dokumen_nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="mb-3">
                        <label for="pertanyaan_nama" class="form-label">Pertanyaan</label>
                        <input id="pertanyaan_nama" class="form-control @error('pertanyaan_nama') is-invalid @enderror" name="pertanyaan_nama" type="text" value="{{ old('pertanyaan_nama', $standarTarget->pertanyaan_nama) }}" placeholder="Pertanyaan">
                        @error('pertanyaan_nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="mb-3">
                        <label for="dokumen_tipe" class="form-label">Tipe Dokumen</label>
                        <label style="float: right;">
                            <a href="#" type="button" data-bs-toggle="modal" data-bs-target="#tipeDokumenModal"><i class="btn-icon-prepend" data-feather="plus-circle"></i> Tambah Tipe Dokumen</a>
                            <a href="#" type="button" data-bs-toggle="modal" data-bs-target="#tipeDokumenHapus" style="color: red;"><i class="btn-icon-prepend" data-feather="minus-circle"></i> Hapus Tipe Dokumen</a>
                        </label>
                        <select class="form-select" name="dokumen_tipe" id="dokumen_tipe" required>
                            <option value="" disabled {{ old('dokumen_tipe', $standarTarget->dokumen_tipe) === null ? 'selected' : '' }}>Pilih tipe dokumen...</option>
                            @foreach($dokumenTipes as $tipeDokumen)
                                <option value="{{ $tipeDokumen->tipe_nama }}" {{ old('dokumen_tipe', $standarTarget->dokumen_tipe) == $tipeDokumen->tipe_nama ? 'selected' : '' }}>
                                    {{ $tipeDokumen->tipe_nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('dokumen_tipe')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="mb-3">
                        <label for="dokumen_keterangan" class="form-label">Keterangan / Informasi Lain :</label>
                        <textarea id="dokumen_keterangan" class="form-control @error('dokumen_keterangan') is-invalid @enderror" name="dokumen_keterangan" placeholder="Keterangan / Informasi Lain">{{ old('dokumen_keterangan', $standarTarget->dokumen_keterangan) }}</textarea>
                        @error('dokumen_keterangan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <input class="btn btn-primary" type="submit" value="Submit">
        </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="tipeDokumenModal" tabindex="-1" aria-labelledby="tipeDokumenModal" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('admin.kriteria-dokumen.kelola-target.tipedokumenstore') }}" method="POST" enctype="multipart/form-data" id="tipeDokumenForm">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="tipeDokumenModal">Tambah Tipe Dokumen</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-sm-12">
              <div class="mb-12">
                <label for="tipe_nama" class="form-label">Nama Tipe Dokumen</label>
                <input type="hidden" name="indikator_id" value="{{ $indikator_id }}">
                <input id="tipe_nama" class="form-control" name="tipe_nama" type="text" placeholder="Masukkan Nama Tipe Dokumen">
              </div>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-sm-12">
              <div class="mb-12">
                <label>Daftar Tipe Dokumen Terdaftar</label>
                @foreach ($dokumenTipes as $tipeDokumen)
                  <div>{{ $loop->iteration }}. {{ $tipeDokumen->tipe_nama }}</div>
                @endforeach
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <input class="btn btn-primary" type="submit" value="Submit">
        </div>
      </div>
    </form>
  </div>
</div>

<div class="modal fade" id="tipeDokumenHapus" tabindex="-1" aria-labelledby="tipeDokumenHapusLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="tipeDokumenHapusLabel">Hapus Tipe Dokumen</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('admin.kriteria-dokumen.kelola-target.tipedokumendestroy') }}" method="POST">
        @csrf
        @method('DELETE')
        <div class="modal-body">
          <div class="row">
            <div class="col-sm-12">
              <div class="mb-3">
                <label for="prodi_tipeDokumen" class="form-label">Pilih Tipe Dokumen yang akan Dihapus:</label>
                <select class="form-select" name="id" id="prodi_tipeDokumen" required>
                    <option value="" disabled selected>Pilih tipe dokumen...</option>
                    @foreach($dokumenTipes as $tipeDokumen)
                      <option value="{{ $tipeDokumen->id }}">{{ $tipeDokumen->tipe_nama }}</option>
                    @endforeach
                </select>
              </div>
            </div>
          </div>
          <div class="row mt-4">
            <div class="col-sm-12">
              <label>Daftar Tipe Dokumen Terdaftar:</label>
              @foreach ($dokumenTipes as $tipeDokumen)
                <div>{{ $loop->iteration }}. {{ $tipeDokumen->tipe_nama }}</div>
              @endforeach
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
          <button type="submit" class="btn btn-danger">Hapus</button>
        </div>
      </form>

    </div>
  </div>
</div>

@endsection

@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/inputmask/jquery.inputmask.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/typeahead-js/typeahead.bundle.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/jquery-tags-input/jquery.tagsinput.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/dropzone/dropzone.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/dropify/js/dropify.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/pickr/pickr.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/moment/moment.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/flatpickr/flatpickr.min.js') }}"></script>
@endpush

@push('custom-scripts')
  <script src="{{ asset('assets/js/form-validation.js') }}"></script>
  <script src="{{ asset('assets/js/bootstrap-maxlength.js') }}"></script>
  <script src="{{ asset('assets/js/inputmask.js') }}"></script>
  <script src="{{ asset('assets/js/select2.js') }}"></script>
  <script src="{{ asset('assets/js/typeahead.js') }}"></script>
  <script src="{{ asset('assets/js/tags-input.js') }}"></script>
  <script src="{{ asset('assets/js/dropzone.js') }}"></script>
  <script src="{{ asset('assets/js/dropify.js') }}"></script>
  <script src="{{ asset('assets/js/pickr.js') }}"></script>
  <script src="{{ asset('assets/js/flatpickr.js') }}"></script>
@endpush