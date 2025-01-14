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
    <li class="breadcrumb-item"><a href="#">Penjadwalan AMI</a></li>
    <li class="breadcrumb-item active" aria-current="page">Tambah Penjadwalan AMI</li>
  </ol>
</nav>

<div class="row">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Tambah Penjadwalan AMI</h4>
        <form action="{{ route('admin.penjadwalan-ami.store') }}" method="POST" id="PenjadwalanAMIForm">
          @csrf
          <div class="row">
            <div class="col-sm-6">
              <div class="mb-3">
                <label for="prodi" class="form-label">Program Studi</label>
                <select class="form-select @error('user_penempatan') is-invalid @enderror" name="prodi" id="prodi">
                  <option selected disabled>-</option>
                  @foreach($users as $user)
                    <option value="{{ $user->user_penempatan }}">{{$user->user_penempatan }}</option>
                  @endforeach
                </select>
                @error('user_id')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div><!-- Col -->
          </div>
          <div class="row">
            <div class="col-sm-6">
              <div class="mb-3">
                <label for="fakultas" class="form-label">Fakultas</label>
                <input id="fakultas" class="form-control @error('fakultas') is-invalid @enderror" name="fakultas" type="text" value="{{ old('fakultas') }}" readonly>
                @error('fakultas')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div><!-- Col -->
            <div class="col-sm-6">
              <div class="mb-3">
                <label for="standar_akreditasi" class="form-label">Standar Akreditasi</label>
                <input id="standar_akreditasi" class="form-control @error('standar_akreditasi') is-invalid @enderror" name="standar_akreditasi" type="text" value="{{ old('standar_akreditasi') }}" readonly>
                @error('standar_akreditasi')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div><!-- Col -->
          </div>
          <div class="row">
            <div class="col-sm-6">
              <div class="mb-3">
                <label for="auditor_kode" class="form-label">Ketua Auditor</label>
                <select class="form-select @error('user_penempatan') is-invalid @enderror" name="auditor_kode" id="auditor_kode">
                  <option selected disabled>-</option>
                  @foreach($auditors as $auditor)
                    <option value="{{ $auditor->users_code }}">{{$auditor->user_nama }}</option>
                  @endforeach
                </select>                
                @error('auditor_kode')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div><!-- Col -->
            <div class="col-sm-6">
              <div class="mb-3">
                <label for="periode" class="form-label">Periode</label>
                <input id="periode" class="form-control @error('periode') is-invalid @enderror" name="periode" type="text" value="2024/2025" placeholder="Periode">
                @error('periode')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div><!-- Col -->
          </div>
          <div class="row">
            <div class="col-sm-6">
              <div class="mb-3">
                <label for="opening_ami" class="form-label">Opening meeting AMI</label>
                <div class="input-group flatpickr" id="flatpickr-date">
                  <input id="opening_ami" name="opening_ami" type="text" class="form-control @error('opening_ami') is-invalid @enderror" placeholder="Pilih Tanggal" data-input>
                  <span class="input-group-text input-group-addon" data-toggle><i data-feather="calendar"></i></span>
                </div>
                @error('opening_ami')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div><!-- Col -->
            <div class="col-sm-6">
              <div class="mb-3">
                <label for="pengisian_dokumen" class="form-label">Pengisian Dokumen :</label>
                <div class="input-group flatpickr" id="flatpickr-date-range">
                  <input id="pengisian_dokumen" name="pengisian_dokumen" type="text" class="form-control @error('pengisian_dokumen') is-invalid @enderror" placeholder="Pilih Tanggal" data-input>
                  <span class="input-group-text input-group-addon" data-toggle><i data-feather="calendar"></i></span>
                </div>
                @error('pengisian_dokumen')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div><!-- Col -->
          </div><!-- Row -->
          <div class="row">
            <div class="col-sm-6">
              <div class="mb-3">
                <label for="deskevaluasion" class="form-label">Desk Evaluasion</label>
                  <div class="input-group flatpickr" id="flatpickr-date-range">
                  <input id="deskevaluasion" name="deskevaluasion" type="text" class="form-control @error('deskevaluasion') is-invalid @enderror" placeholder="Pilih Tanggal" data-input>
                  <span class="input-group-text input-group-addon" data-toggle><i data-feather="calendar"></i></span>
                  @error('deskevaluasion')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>
            </div><!-- Col -->
            <div class="col-sm-6">
              <div class="mb-3">
                <label for="assessment" class="form-label">Audit Lapang</label>
                  <div class="input-group flatpickr" id="flatpickr-date-range">
                  <input id="assessment" name="assessment" type="text" class="form-control @error('assessment') is-invalid @enderror" placeholder="Pilih Tanggal" data-input>
                  <span class="input-group-text input-group-addon" data-toggle><i data-feather="calendar"></i></span>
                  @error('assessment')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>
            </div><!-- Col -->
          </div>
          <div class="row">
            <div class="col-sm-6">
              <div class="mb-3">
                <label for="tindakan_koreksi" class="form-label">Penyusunan tindakan perbaikan dan pecegahan</label>
                  <div class="input-group flatpickr" id="flatpickr-date-range">
                  <input id="tindakan_koreksi" name="tindakan_koreksi" type="text" class="form-control @error('tindakan_koreksi') is-invalid @enderror" placeholder="Pilih Tanggal" data-input>
                  <span class="input-group-text input-group-addon" data-toggle><i data-feather="calendar"></i></span>
                  @error('tindakan_koreksi')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>
            </div><!-- Col -->
            <div class="col-sm-6">
              <div class="mb-3">
                <label for="laporan_ami" class="form-label">Penyusunan Laporan AMI</label>
                  <div class="input-group flatpickr" id="flatpickr-date-range">
                  <input id="laporan_ami" name="laporan_ami" type="text" class="form-control @error('laporan_ami') is-invalid @enderror" placeholder="Pilih Tanggal" data-input>
                  <span class="input-group-text input-group-addon" data-toggle><i data-feather="calendar"></i></span>
                  @error('laporan_ami')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>
            </div><!-- Col -->
          </div>
          <div class="row">
            <div class="col-sm-6">
              <div class="mb-3">
                <label for="rtm" class="form-label">RTM</label>
                <div class="input-group flatpickr" id="flatpickr-date">
                  <input id="rtm" name="rtm" type="text" class="form-control @error('rtm') is-invalid @enderror" placeholder="Pilih Tanggal" data-input>
                  <span class="input-group-text input-group-addon" data-toggle><i data-feather="calendar"></i></span>
                  @error('rtm')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
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
    let dataProdi = @json($users); // Pass PHP data to JavaScript

    $('#prodi').on('change', function() {
      let selectedValue = $(this).val();

      // Find the matching user data
      let selectedProgramStudi = dataProdi.find(function(ProgramStudi) {
        return ProgramStudi.user_penempatan == selectedValue;
      });

      // If a match is found, update the fields
      if (selectedProgramStudi) {
        $('#fakultas').val(selectedProgramStudi.user_fakultas); // Update fakultas
        $('#standar_akreditasi').val(selectedProgramStudi.user_akses); // Update standar_akreditasi
      }
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