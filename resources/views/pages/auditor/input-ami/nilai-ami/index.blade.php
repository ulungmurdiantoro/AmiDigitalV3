@extends('layout.master-auditor')

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
    <li class="breadcrumb-item active" aria-current="page">Tambah Dokumen</li>
  </ol>
</nav>

<div class="row">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Tambah Dokumen</h4>
        <form action="{{ route('admin.kriteria-dokumen.kelola-target.store') }}" method="POST" enctype="multipart/form-data" id="KelolaTargetForm">
          @csrf
          <div class="row">
            <div class="col-sm-6">
              <div class="mb-3">
                <label for="standar_nama" class="form-label">Kriteria</label>
                <input id="standar_nama" class="form-control" name="standar_nama" type="text" value="{{ $standarElemen->standar_nama }}" disabled>
              </div>
            </div><!-- Col -->
            <div class="col-sm-6">
              <div class="mb-3">
                <label for="elemen_nama" class="form-label">Elemen</label>
                <input id="elemen_nama" class="form-control" name="elemen_nama" type="text" value="{{ $standarElemen->elemen_nama }}" disabled>
              </div>
            </div><!-- Col -->
          </div>
          <div class="row">
            <div class="col-sm-6">
              <div class="mb-3">
                <label for="indikator_nama" class="form-label">Indikator</label>
                <textarea id="indikator_nama" class="form-control" name="indikator_nama" disabled>{{ $standarElemen->indikator_nama }}</textarea>
              </div>
            </div><!-- Col -->
            <div class="col-sm-6">
              <div class="mb-3">
                <label for="indikator_kode" class="form-label">Jenjang</label>
                <input id="indikator_kode" class="form-control" name="indikator_kode" type="text" value="{{ $indikator_kode }}" disabled>
                <input type="hidden" name="indikator_kode" value="{{ $indikator_kode }}">
              </div>
            </div><!-- Col -->
          </div>
          <div class="row">
            <div class="col-sm-6">
              <div class="mb-3">
                <label for="dokumen_nama" class="form-label">Nilai Mandiri</label>
                <input id="dokumen_nama" class="form-control @error('dokumen_nama') is-invalid @enderror" name="dokumen_nama" type="text" value="4" disabled>
                @error('dokumen_nama')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div><!-- Col -->
            <div class="col-sm-6">
              <div class="mb-3">
                <label for="pertanyaan_nama" class="form-label">Verifikasi Nilai Auditor</label>
                <input id="pertanyaan_nama" class="form-control @error('pertanyaan_nama') is-invalid @enderror" name="pertanyaan_nama" type="text" value="{{ old('pertanyaan_nama') }}" placeholder="Verifikasi Nilai Auditor">
                @error('pertanyaan_nama')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div><!-- Col -->
          </div>
          <div class="row">
            <div class="col-sm-6">
              <div class="mb-3">
                <label for="dokumen_keterangan" class="form-label">Kriteria :</label>
                <textarea id="dokumen_keterangan" class="form-control @error('dokumen_keterangan') is-invalid @enderror" name="dokumen_keterangan" placeholder="Keterangan / Informasi Lain">{{ old('dokumen_keterangan') }}</textarea>
                @error('dokumen_keterangan')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div><!-- Col -->
            <div class="col-sm-6">
              <div class="mb-3">
                <label for="dokumen_keterangan" class="form-label">Deskripsi Temuan:</label>
                <textarea id="dokumen_keterangan" class="form-control @error('dokumen_keterangan') is-invalid @enderror" name="dokumen_keterangan" placeholder="Keterangan / Informasi Lain">{{ old('dokumen_keterangan') }}</textarea>
                @error('dokumen_keterangan')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div><!-- Col -->
          </div>
          <div class="row">
            <div class="col-sm-6">
              <div class="mb-3">
                <label for="dokumen_keterangan" class="form-label">Jenis Temuan</label>
                <select class="form-select" name="dokumen_tipe" id="dokumen_tipe" required>
                  <option value="" disabled selected>Pilih Jenis Temuan</option>
                    <option value="">Sesuai</option>
                </select>
                @error('dokumen_keterangan')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div><!-- Col -->
            <div class="col-sm-6">
              <div class="mb-3">
                <label for="dokumen_keterangan" class="form-label">Akibat</label>
                <textarea id="dokumen_keterangan" class="form-control @error('dokumen_keterangan') is-invalid @enderror" name="dokumen_keterangan" placeholder="Keterangan / Informasi Lain">{{ old('dokumen_keterangan') }}</textarea>
                @error('dokumen_keterangan')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div><!-- Col -->
          </div>
          <div class="row">
            <div class="col-sm-6">
              <div class="mb-3">
                <label for="dokumen_keterangan" class="form-label">Akar Masalah</label>
                <textarea id="dokumen_keterangan" class="form-control @error('dokumen_keterangan') is-invalid @enderror" name="dokumen_keterangan" placeholder="Keterangan / Informasi Lain">{{ old('dokumen_keterangan') }}</textarea>
                @error('dokumen_keterangan')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div><!-- Col -->
            <div class="col-sm-6">
              <div class="mb-3">
                <label for="dokumen_keterangan" class="form-label">Rekomendasi</label>
                <textarea id="dokumen_keterangan" class="form-control @error('dokumen_keterangan') is-invalid @enderror" name="dokumen_keterangan" placeholder="Keterangan / Informasi Lain">{{ old('dokumen_keterangan') }}</textarea>
                @error('dokumen_keterangan')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div><!-- Col -->
          </div>
          <input class="btn btn-primary" type="submit" value="Submit">
        </form>
      </div>
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