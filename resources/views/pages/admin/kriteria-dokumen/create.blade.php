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
    <li class="breadcrumb-item active" aria-current="page">Tambah Kriteria Dokumen</li>
  </ol>
</nav>

<div class="row">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Tambah Kriteria Dokumen</h4>
        <form action="{{ route('admin.kriteria-dokumen.store') }}" method="POST" enctype="multipart/form-data" id="ProdiForm">
          @csrf
          <div class="row">
            <div class="col-sm-6">
              <div class="mb-3">
                <label for="prodi_nama" class="form-label">Indikator Kode</label>
                <input id="prodi_nama" class="form-control @error('prodi_nama') is-invalid @enderror" name="prodi_nama" type="text" value="{{ old('prodi_nama') }}" placeholder="Masukkan Nama Program Studi">
                @error('prodi_nama')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div><!-- Col -->
          </div>
          <div class="row">
            <div class="col-sm-6">
              <div class="mb-3">
                <label for="prodi_jurusan" class="form-label">earthone </label>
                <label style="float: right;">
                  <a href="#" type="button" data-bs-toggle="modal" data-bs-target="#jurusanModal"><i class="btn-icon-prepend" data-feather="plus-circle"></i> Tambah Jurusan</a> 
                  <a href="#" type="button" data-bs-toggle="modal" data-bs-target="#jurusanHapus" style="color: red;"><i class="btn-icon-prepend" data-feather="minus-circle"></i> Hapus Jurusan</a>
                </label>
                <select class="form-select @error('prodi_jurusan') is-invalid @enderror" name="prodi_jurusan" id="prodi_jurusan">
                    <option selected disabled>-</option>
                    {{-- @foreach($Jurusans as $Jurusan)
                      <option value="{{ $Jurusan->jurusan_nama }}">{{ $Jurusan->jurusan_nama }}</option>
                    @endforeach --}}
                </select>
                @error('prodi_jurusan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div><!-- Col -->
            <div class="col-sm-6">
              <div class="mb-3">
                <label for="prodi_fakultas" class="form-label">Fakultas</label>
                <label style="float: right;">
                  <a href="#" type="button" data-bs-toggle="modal" data-bs-target="#fakultasModal"><i class="btn-icon-prepend" data-feather="plus-circle"></i> Tambah Fakultas</a> 
                  <a href="#" type="button" data-bs-toggle="modal" data-bs-target="#fakultasHapus" style="color: red;"><i class="btn-icon-prepend" data-feather="minus-circle"></i> Hapus Fakultas</a>
                </label>
                <select class="form-select @error('prodi_fakultas') is-invalid @enderror" name="prodi_fakultas" id="prodi_fakultas">
                  <option selected disabled>-</option>
                  {{-- @foreach($Fakultass as $Fakultas)
                    <option value="{{ $Fakultas->fakultas_nama }}">{{ $Fakultas->fakultas_nama }}</option>
                  @endforeach --}}
                </select>
                @error('prodi_fakultas')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div><!-- Col -->
          </div>
          <div class="row">
            <div class="col-sm-6">
                <div class="mb-3">
                    <label for="prodi_akreditasi" class="form-label">Status Akreditasi</label>
                    <select class="form-select @error('prodi_akreditasi') is-invalid @enderror" name="prodi_akreditasi" id="prodi_akreditasi">
                        <option selected disabled>-</option>
                        <option>Unggul</option>
                        <option>Baik Sekali</option>
                        <option>Baik</option>
                        <option>A</option>
                        <option>B</option>
                        <option>C</option>
                    </select>
                    @error('prodi_akreditasi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div><!-- Col -->
            <div class="col-sm-6">
              <div class="mb-3">
                <label for="akreditasi_kadaluarsa" class="form-label">Tanggal Kadaluarsa Akreditasi</label>
                <div class="input-group flatpickr" id="flatpickr-date">
                  <input id="akreditasi_kadaluarsa" name="akreditasi_kadaluarsa" type="text" class="form-control @error('akreditasi_kadaluarsa') is-invalid @enderror" placeholder="Pilih Tanggal" data-input>
                  <span class="input-group-text input-group-addon" data-toggle><i data-feather="calendar"></i></span>
                  @error('akreditasi_kadaluarsa')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>
            </div><!-- Col -->
          </div>
          <div class="row">
            <div class="col-sm-6">
              <div class="mb-3">
                <label for="akreditasi_bukti" class="form-label">Bukti Akreditasi</label>
                <input name="akreditasi_bukti" type="file" id="myDropify" class="form-control @error('akreditasi_bukti') is-invalid @enderror"/>
                @error('akreditasi_bukti')
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