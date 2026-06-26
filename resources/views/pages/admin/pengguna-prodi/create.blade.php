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
    <li class="breadcrumb-item active" aria-current="page">Tambah Pengguna Prodi</li>
  </ol>
</nav>

<div class="row">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Tambah Pengguna Prodi</h4>
        <form action="{{ route('admin.pengguna-prodi.store') }}" method="POST" enctype="multipart/form-data" id="PenggunaProdiForm">
          @csrf
          <div class="row">
            <div class="col-sm-6">
              <div class="mb-3">
                <label for="user_id" class="form-label">NIP / NIK (ID)</label>
                <input id="user_id" class="form-control @error('user_id') is-invalid @enderror" name="user_id" type="text" value="{{ old('user_id') }}" placeholder="Masukkan NIP / NIK (ID)">
                @error('user_id')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div><!-- Col -->
            <div class="col-sm-6">
              <div class="mb-3">
                <label for="user_nama" class="form-label">Nama Lengkap</label>
                <input id="user_nama" class="form-control @error('user_nama') is-invalid @enderror" name="user_nama" type="text" value="{{ old('user_nama') }}" placeholder="Masukkan Nama Lengkap">
                @error('user_nama')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div><!-- Col -->
          </div>
          <div class="row">
            <div class="col-sm-6">
              <div class="mb-3">
                <label for="user_jabatan" class="form-label">Jabatan</label>
                <input id="user_jabatan" class="form-control @error('user_jabatan') is-invalid @enderror" name="user_jabatan" type="text" value="{{ old('user_jabatan') }}" placeholder="Jabatan">
                @error('user_jabatan')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div><!-- Col -->
          </div>
          <div class="row">
            <div class="col-sm-6">
              <div class="mb-3">
                <label for="user_penempatan" class="form-label">Tanggung Jawab Akun</label>
                <select class="form-select" name="user_penempatan" id="user_penempatan">
                    <option selected disabled>-</option>
                    @foreach($ProgramStudis as $ProgramStudi)
                        <option value="{{ $ProgramStudi->prodi_jenjang .' - '. $ProgramStudi->prodi_nama }}">
                          {{ $ProgramStudi->prodi_jenjang .' - '. $ProgramStudi->prodi_nama }}
                        </option>
                    @endforeach
                </select>
                @error('user_penempatan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div><!-- Col -->
            <div class="col-sm-6">
              <div class="mb-3">
                <label for="user_fakultas" class="form-label">Fakultas</label>
                <input id="user_fakultas" class="form-control @error('user_fakultas') is-invalid @enderror" name="user_fakultas" type="text" value="{{ old('user_fakultas') }}" readonly>
                @error('user_fakultas')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div><!-- Col -->
          </div>
          <input type="hidden" name="user_akses" id="user_akses" value="{{ old('user_akses') }}">
          <div class="row">
            <div class="col-sm-6">
                <div class="mb-3">
                  <label for="username" class="form-label">Username</label>
                  <input id="username" class="form-control @error('username') is-invalid @enderror" name="username" type="text" value="{{ old('username') }}" placeholder="Masukkan Username">
                  @error('username')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
            </div><!-- Col -->
            <div class="col-sm-6">
                <div class="mb-3">
                  <label for="password" class="form-label">Password</label>
                  <input id="password" class="form-control @error('password') is-invalid @enderror" name="password" type="password" value="{{ old('password') }}" placeholder="Masukkan Password  ">
                  @error('password')
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $(document).ready(function() {
    let dataProdi = @json($ProgramStudis); // Pass PHP data to JavaScript

    $('#user_penempatan').on('change', function() {
      let selectedValue = $(this).val();

      dataProdi.forEach(function(ProgramStudi) {
        let comparisonValue = ProgramStudi.prodi_jenjang + ' - ' + ProgramStudi.prodi_nama;
        if (selectedValue == comparisonValue) {
          $('#user_fakultas').val(ProgramStudi.prodi_fakultas);
          $('#user_akses').val(ProgramStudi.standar_akreditasi);
        }
      });
    });
  });
</script>

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