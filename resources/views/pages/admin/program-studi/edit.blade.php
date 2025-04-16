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
    <li class="breadcrumb-item"><a href="#">Pengguna Sistem</a></li>
    <li class="breadcrumb-item"><a href="#">Program Studi</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit Program Studi</li>
  </ol>
</nav>

<div class="row">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Edit Program Studi</h4>
        <form action="{{ route('admin.program-studi.update', $program_studis->id) }}" method="POST" enctype="multipart/form-data" id="ProdiForm">
          @csrf
          @method('PUT')
          <div class="row">
            <div class="col-sm-6">
              <div class="mb-3">
                <label for="prodi_nama" class="form-label">Nama Program Studi</label>
                <input 
                  id="prodi_nama" 
                  class="form-control @error('prodi_nama') is-invalid @enderror" 
                  name="prodi_nama" 
                  type="text" 
                  value="{{ old('prodi_nama', $program_studis->prodi_nama) }}" 
                  placeholder="Masukkan Nama Program Studi">
                @error('prodi_nama')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div><!-- Col -->
            <div class="col-sm-6">
              <div class="mb-3">
                <label for="prodi_jenjang" class="form-label">Jenjang</label>
                <select 
                  class="form-select @error('prodi_jenjang') is-invalid @enderror" 
                  name="prodi_jenjang" 
                  id="prodi_jenjang">
                  <option disabled>-</option>
                  <option value="S1" {{ old('prodi_jenjang', $program_studis->prodi_jenjang) == 'S1' ? 'selected' : '' }}>S1</option>
                  <option value="S2" {{ old('prodi_jenjang', $program_studis->prodi_jenjang) == 'S2' ? 'selected' : '' }}>S2</option>
                  <option value="S3" {{ old('prodi_jenjang', $program_studis->prodi_jenjang) == 'S3' ? 'selected' : '' }}>S3</option>
                  <option value="S1 Terapan" {{ old('prodi_jenjang', $program_studis->prodi_jenjang) == 'S1 Terapan' ? 'selected' : '' }}>S1 Terapan</option>
                  <option value="S2 Terapan" {{ old('prodi_jenjang', $program_studis->prodi_jenjang) == 'S2 Terapan' ? 'selected' : '' }}>S2 Terapan</option>
                  <option value="S3 Terapan" {{ old('prodi_jenjang', $program_studis->prodi_jenjang) == 'S3 Terapan' ? 'selected' : '' }}>S3 Terapan</option>
                  <option value="PPG" {{ old('prodi_jenjang', $program_studis->prodi_jenjang) == 'PPG' ? 'selected' : '' }}>PPG</option>
                </select>
                @error('prodi_jenjang')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div><!-- Col -->
          </div>
          <div class="row">
            <div class="col-sm-6">
              <div class="mb-3">
                <label for="prodi_jurusan" class="form-label">Jurusan</label>
                <select 
                  class="form-select @error('prodi_jurusan') is-invalid @enderror" 
                  name="prodi_jurusan" 
                  id="prodi_jurusan">
                  <option disabled>-</option>
                  <option value="Jurusan1" {{ old('prodi_jurusan', $program_studis->prodi_jurusan) == 'Jurusan1' ? 'selected' : '' }}>Jurusan1</option>
                  <option value="Jurusan2" {{ old('prodi_jurusan', $program_studis->prodi_jurusan) == 'Jurusan2' ? 'selected' : '' }}>Jurusan2</option>
                </select>
                @error('prodi_jurusan')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div><!-- Col -->
            <div class="col-sm-6">
              <div class="mb-3">
                <label for="prodi_fakultas" class="form-label">Fakultas</label>
                <select 
                  class="form-select @error('prodi_fakultas') is-invalid @enderror" 
                  name="prodi_fakultas" 
                  id="prodi_fakultas">
                  <option disabled>-</option>
                  <option value="Fakultas1" {{ old('prodi_fakultas', $program_studis->prodi_fakultas) == 'Fakultas1' ? 'selected' : '' }}>Fakultas1</option>
                  <option value="Fakultas2" {{ old('prodi_fakultas', $program_studis->prodi_fakultas) == 'Fakultas2' ? 'selected' : '' }}>Fakultas2</option>
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
                <select 
                  class="form-select @error('prodi_akreditasi') is-invalid @enderror" 
                  name="prodi_akreditasi" 
                  id="prodi_akreditasi">
                  <option disabled>-</option>
                  <option value="Unggul" {{ old('prodi_akreditasi', $program_studis->prodi_akreditasi) == 'Unggul' ? 'selected' : '' }}>Unggul</option>
                  <option value="Baik Sekali" {{ old('prodi_akreditasi', $program_studis->prodi_akreditasi) == 'Baik Sekali' ? 'selected' : '' }}>Baik Sekali</option>
                  <option value="Baik" {{ old('prodi_akreditasi', $program_studis->prodi_akreditasi) == 'Baik' ? 'selected' : '' }}>Baik</option>
                  <option value="A" {{ old('prodi_akreditasi', $program_studis->prodi_akreditasi) == 'A' ? 'selected' : '' }}>A</option>
                  <option value="B" {{ old('prodi_akreditasi', $program_studis->prodi_akreditasi) == 'B' ? 'selected' : '' }}>B</option>
                  <option value="C" {{ old('prodi_akreditasi', $program_studis->prodi_akreditasi) == 'C' ? 'selected' : '' }}>C</option>
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
                  <input 
                    id="akreditasi_kadaluarsa" 
                    name="akreditasi_kadaluarsa" 
                    type="text" 
                    class="form-control @error('akreditasi_kadaluarsa') is-invalid @enderror" 
                    value="{{ old('akreditasi_kadaluarsa', $program_studis->akreditasi_kadaluarsa) }}" 
                    placeholder="Pilih Tanggal" 
                    data-input>
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
                <input 
                  name="akreditasi_bukti" 
                  type="file" 
                  id="myDropify" 
                  class="form-control @error('akreditasi_bukti') is-invalid @enderror"/>
                @error('akreditasi_bukti')
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