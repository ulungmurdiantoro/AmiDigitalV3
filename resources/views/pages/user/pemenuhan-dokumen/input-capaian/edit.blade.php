@extends('layout.master-user')

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
    <li class="breadcrumb-item"><a href="#">Pemenuhan Dokumen</a></li>
    <li class="breadcrumb-item"><a href="#">Input Capaian</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit Dokumen</li>
  </ol>
</nav>

<div class="row">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Edit Dokumen</h4>
        <form action="{{ route('user.pemenuhan-dokumen.input-capaian.update', $standarCapaian->id) }}" method="POST" enctype="multipart/form-data" id="InputCapaianForm">
          @csrf
          @method('PUT')
          <div class="row">
            <div class="col-sm-6">
              <div class="mb-3">
                <label for="dokumen_nama" class="form-label">Nama Dokumen :</label>
                <input type="hidden" name="indikator_kode" value="{{ $indikator_kode }}">
                <input id="dokumen_nama" class="form-control" name="dokumen_nama" type="text" value="{{ $standarCapaian->dokumen_nama }}" disabled>
                @error('dokumen_nama')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div><!-- Col -->
            <div class="col-sm-6">
              <div class="mb-3">
                <label for="pertanyaan_nama" class="form-label">Pertanyaan :</label>
                <input id="pertanyaan_nama" class="form-control" name="pertanyaan_nama" type="text" value="{{ $standarCapaian->pertanyaan_nama }}" disabled>
                @error('pertanyaan_nama')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div><!-- Col -->
          </div>
          <div class="row">
            <div class="col-sm-6">
              <div class="mb-3">
                <label for="dokumen_tipe" class="form-label">Tipe Dokumen :</label>
                <input id="dokumen_tipe" class="form-control" name="dokumen_tipe" type="text" value="{{ $standarCapaian->dokumen_tipe }}" disabled>
                @error('dokumen_tipe')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div><!-- Col -->
            <div class="col-sm-6">
              <div class="mb-3">
                <label for="dokumen_keterangan" class="form-label">Keterangan / Informasi Lain :</label>
                <textarea id="dokumen_keterangan" class="form-control" name="dokumen_keterangan" disabled>{{ $standarCapaian->dokumen_keterangan }}</textarea>
                @error('dokumen_keterangan')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div><!-- Col -->
          </div>
          <div class="row">
            <div class="col-sm-6">
              <div class="mb-3">
                <label for="dokumen_file" class="form-label">File :</label>
                <input name="dokumen_file" type="file" id="myDropify" class="form-control @error('dokumen_file') is-invalid @enderror"/>
                @error('dokumen_file')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div><!-- Col -->
          </div>
          <div class="row">
            <div class="col-sm-6">
              <div class="mb-3">
                <label for="periode" class="form-label">Periode :</label>
                <input id="periode" class="form-control @error('periode') is-invalid @enderror" name="periode" type="text" value="{{ $standarCapaian->periode }}" placeholder="Periode">
                @error('periode')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div><!-- Col -->
            <div class="col-sm-6">
              <div class="mb-3">
                <label for="dokumen_kadaluarsa" class="form-label">Tanggal Kadaluarsa :</label>
                <div class="input-group flatpickr" id="flatpickr-date">
                  <input id="dokumen_kadaluarsa" name="dokumen_kadaluarsa" type="text" class="form-control @error('dokumen_kadaluarsa') is-invalid @enderror" value="{{ $standarCapaian->dokumen_kadaluarsa }}" placeholder="Pilih Tanggal" data-input>
                  <span class="input-group-text input-group-addon" data-toggle><i data-feather="calendar"></i></span>
                </div>
                @error('dokumen_kadaluarsa')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div><!-- Col -->
          </div>
          <div class="row">
            <div class="col-sm-6">
              <div class="mb-3">
                <label for="informasi" class="form-label">Informasi Tambahan :</label>
                <textarea id="informasi" class="form-control @error('informasi') is-invalid @enderror" name="informasi" placeholder="Informasi Tambahan">{{ $standarCapaian->informasi }}</textarea>
                @error('informasi')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div><!-- Col -->
          </div>
          <input class="btn btn-primary" type="submit" value="Update">
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