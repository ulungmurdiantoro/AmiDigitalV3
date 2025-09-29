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
        <form action="{{ route('user.pemenuhan-dokumen.input-capaian.store') }}" method="POST" enctype="multipart/form-data" id="InputCapaianForm">
          @csrf
          <div class="row">
            <div class="col-sm-6">
              <div class="mb-3">
                <label for="standar_nama" class="form-label">Kriteria :</label>
                <input id="standar_nama" class="form-control" name="standar_nama" type="text" value="{{ $standarElemen->standar_nama }}" disabled>
              </div>
            </div><!-- Col -->
            <div class="col-sm-6">
              <div class="mb-3">
                <label for="elemen_nama" class="form-label">Elemen :</label>
                <input id="elemen_nama" class="form-control" name="elemen_nama" type="text" value="{{ $standarElemen->elemen_nama }}" disabled>
              </div>
            </div><!-- Col -->
          </div>
          <div class="row">
            <div class="col-sm-6">
              <div class="mb-3">
                <label for="indikator_nama" class="form-label">Indikator :</label>
                <textarea id="indikator_nama" class="form-control" name="indikator_nama" disabled>{{ $standarElemen->indikator_nama }}</textarea>
              </div>
            </div><!-- Col -->
            <div class="col-sm-6">
              <div class="mb-3">
                <label for="indikator_id" class="form-label">Jenjang :</label>
                <input id="indikator_id" class="form-control" name="indikator_id" type="text" value="{{ $indikator_id }}" disabled>
                <input type="hidden" name="indikator_id" value="{{ $indikator_id }}">
              </div>
            </div><!-- Col -->
          </div>
          <div class="row">
            <div class="col-sm-6">
              <div class="mb-3">
                <label for="dokumen_nama" class="form-label">Nama Dokumen :</label>
                <select class="form-select" name="dokumen_nama" id="dokumen_nama">
                  <option selected disabled>-</option>
                  @foreach($standarTargets as $standarTarget)
                    <option value="{{ $standarTarget->dokumen_nama}}">
                      {{ $standarTarget->dokumen_nama}}
                    </option>
                  @endforeach
                </select>
                @error('dokumen_nama')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div><!-- Col -->
            <div class="col-sm-6">
              <div class="mb-3">
                <label for="pertanyaan_nama" class="form-label">Pertanyaan :</label>
                <input id="pertanyaan_nama" class="form-control @error('pertanyaan_nama') is-invalid @enderror" name="pertanyaan_nama" type="text" value="{{ old('pertanyaan_nama') }}" readonly>
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
                <input id="dokumen_tipe" class="form-control @error('dokumen_tipe') is-invalid @enderror" name="dokumen_tipe" type="text" value="{{ old('dokumen_tipe') }}" readonly>
                @error('dokumen_tipe')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div><!-- Col -->
            <div class="col-sm-6">
              <div class="mb-3">
                <label for="dokumen_keterangan" class="form-label">Keterangan / Informasi Lain :</label>
                <textarea id="dokumen_keterangan" class="form-control @error('dokumen_keterangan') is-invalid @enderror" name="dokumen_keterangan" readonly>{{ old('dokumen_keterangan') }}</textarea>
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
                <input id="periode" class="form-control @error('periode') is-invalid @enderror" name="periode" type="text" value="{{ old('periode') }}" placeholder="Periode">
                @error('periode')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div><!-- Col -->
            <div class="col-sm-6">
              <div class="mb-3">
                <label for="dokumen_kadaluarsa" class="form-label">Tanggal Kadaluarsa :</label>
                <div class="input-group flatpickr" id="flatpickr-date">
                  <input id="dokumen_kadaluarsa" name="dokumen_kadaluarsa" type="text" class="form-control @error('dokumen_kadaluarsa') is-invalid @enderror" placeholder="Pilih Tanggal" data-input>
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
                <textarea id="informasi" class="form-control @error('informasi') is-invalid @enderror" name="informasi" placeholder="Informasi Tambahan">{{ old('informasi') }}</textarea>
                @error('informasi')
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
    let dataTarget = @json($standarTargets); // Pass PHP data to JavaScript

    $('#dokumen_nama').on('change', function() {
      console.log('Dropdown changed'); // Ensure this logs

      let selectedValue = $(this).val();
      console.log('Selected value:', selectedValue); // Log the selected value

      console.log('Data Dokumen:', dataTarget); // Log the data structure for debugging

      dataTarget.forEach(function(standarTarget) {
        let comparisonValue = standarTarget.dokumen_nama;
        console.log('Comparing selected value with:', comparisonValue); // Log the comparison value
        if (selectedValue == comparisonValue) {
          console.log("Match found, updating fields:", standarTarget.pertanyaan_nama, standarTarget.dokumen_tipe, standarTarget.dokumen_keterangan); // Log matched fields
          $('#pertanyaan_nama').val(standarTarget.pertanyaan_nama); // Set value of pertanyaan_nama
          $('#dokumen_tipe').val(standarTarget.dokumen_tipe); // Set value of dokumen_tipe
          $('#dokumen_keterangan').val(standarTarget.dokumen_keterangan); // Set value of dokumen_keterangan
        }
      });
    });
  });
</script>
<script>
  $(document).ready(function() {
    let currentDate = new Date();
    let currentYear = currentDate.getFullYear();
    let periodeValue;

    if (currentDate.getMonth() < 5) { // Months are 0-based, so 5 is June
      periodeValue = (currentYear - 1) + "/" + currentYear;
    } else {
      periodeValue = currentYear + "/" + (currentYear + 1);
    }

    $('#periode').val(periodeValue); // Set the value of the periode input field
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