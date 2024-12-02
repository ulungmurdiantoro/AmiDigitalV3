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
    <li class="breadcrumb-item"><a href="#">Dokumen SPMI & AMI</a></li>
    <li class="breadcrumb-item active" aria-current="page">Tambah Dokumen SPMI & AMI</li>
  </ol>
</nav>

<div class="row">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Tambah Dokumen SPMI & AMI</h4>
        <form action="{{ route('admin.dokumen-spmi-ami.store') }}" method="POST" enctype="multipart/form-data" id="DokumenSpmiAmiForm">
          @csrf
          <div class="row">
            <div class="col-sm-6">
              <div class="mb-3">
                <label for="kategori_dokumen" class="form-label">Kategori Dokumen</label>
                <select class="form-select @error('kategori_dokumen') is-invalid @enderror" name="kategori_dokumen" id="kategori_dokumen">
                  <option selected disabled>-</option>
                  @foreach($KategoriDokumens as $KategoriDokumen)
                    <option value="{{ $KategoriDokumen->kategori_dokumen }}">
                      {{ $KategoriDokumen->kategori_dokumen }}
                    </option>
                  @endforeach
                </select>   
                @error('kategori_dokumen')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div><!-- Col -->
            <div class="col-sm-6">
              <div class="mb-3">
                <label for="nama_dokumen" class="form-label">Nama Dokumen</label>
                <input id="nama_dokumen" class="form-control @error('nama_dokumen') is-invalid @enderror" name="nama_dokumen" type="text" value="{{ old('nama_dokumen') }}" placeholder="Masukkan Nama Dokumen">
                @error('nama_dokumen')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div><!-- Col -->
          </div>
          <div class="row">
            <div class="col-sm-6">
              <div class="mb-3">
                <label for="file_spmi_ami" class="form-label">File Dokumen</label>
                <input name="file_spmi_ami" type="file" id="myDropify" class="form-control @error('file_spmi_ami') is-invalid @enderror"/>
                @error('file_spmi_ami')
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