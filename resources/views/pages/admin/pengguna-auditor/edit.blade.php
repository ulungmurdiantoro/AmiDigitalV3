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
    <li class="breadcrumb-item"><a href="#">Pengguna Auditor</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit Pengguna Auditor</li>
  </ol>
</nav>

<div class="row">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Edit Pengguna Auditor</h4>
        <form action="{{ route('admin.pengguna-auditor.update', $users->id) }}" method="POST" enctype="multipart/form-data" id="PenggunaAuditorFormEdit">
          @csrf
          @method('PUT')
          <div class="row">
            <div class="col-sm-6">
              <div class="mb-3">
                <label for="user_id" class="form-label">NIP / NIK (ID)</label>
                <input id="user_id" class="form-control @error('user_id') is-invalid @enderror" name="user_id" type="text" value="{{ old('user_id', $users->user_id) }}" placeholder="Masukkan NIP / NIK (ID)">
                @error('user_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div><!-- Col -->
            <div class="col-sm-6">
              <div class="mb-3">
                <label for="user_nama" class="form-label">Nama Lengkap</label>
                <input id="user_nama" class="form-control @error('user_nama') is-invalid @enderror" name="user_nama" type="text" value="{{ old('user_nama', $users->user_nama) }}" placeholder="Masukkan Nama Lengkap">
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
                <input id="user_jabatan" class="form-control @error('user_jabatan') is-invalid @enderror" name="user_jabatan" type="text" value="{{ old('user_jabatan', $users->user_jabatan) }}" placeholder="Jabatan">
                @error('user_jabatan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div><!-- Col -->
          </div>
          <div class="row">
            <div class="col-sm-6">
                <div class="mb-3">
                  <label for="user_pelatihan" class="form-label">Tahun Pelatihan </label>
                  <input id="user_pelatihan" class="form-control @error('user_pelatihan') is-invalid @enderror" name="user_pelatihan" type="number" value="{{ old('user_pelatihan', $users->user_pelatihan) }}" placeholder="Tahun Pelatihan">
                  @error('user_pelatihan')
                      <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
            </div><!-- Col -->
          </div>
          <div class="row">
            <div class="col-sm-6">
              <div class="mb-3">
                <label for="user_sertfikat" class="form-label">Sertifikat Pelatihan</label>
                <input name="user_sertfikat" type="file" id="myDropify" class="form-control @error('user_sertfikat') is-invalid @enderror"/>
                @error('user_sertfikat')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div><!-- Col -->
            <div class="col-sm-6">
              <div class="mb-3">
                <label for="user_sk" class="form-label">SK</label>
                <input name="user_sk" type="file" id="myDropify1" class="form-control @error('user_sk') is-invalid @enderror"/>
                @error('user_sk')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div><!-- Col -->
          </div>
          <div class="row">
            <div class="col-sm-6">
                <div class="mb-3">
                  <label for="username" class="form-label">Username</label>
                  <input id="username" class="form-control @error('username') is-invalid @enderror" name="username" type="text" value="{{ old('username', $users->username) }}" placeholder="Masukkan Username">
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
      console.log('Dropdown changed'); // Ensure this logs

      let selectedValue = $(this).val();
      console.log('Selected value:', selectedValue); // Log the selected value

      console.log('Data Prodi:', dataProdi); // Log the data structure for debugging

      dataProdi.forEach(function(ProgramStudi) {
        let comparisonValue = ProgramStudi.prodi_jenjang + ' - ' + ProgramStudi.prodi_nama;
        console.log('Comparing selected value with:', comparisonValue); // Log the comparison value
        if (selectedValue == comparisonValue) {
          console.log("Match found, updating fakultas:", ProgramStudi.prodi_fakultas); // Log matched fakultas
          $('#user_fakultas').val(ProgramStudi.prodi_fakultas); // Set value of user_fakultas
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