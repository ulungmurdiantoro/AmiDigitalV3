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
    <li class="breadcrumb-item"><a href="#">Kelola Bukti</a></li>
    <li class="breadcrumb-item active" aria-current="page">Tambah Bukti</li>
  </ol>
</nav>
{{-- @dd($importTitle, $standard, $allStandards) --}}
<div class="row">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Tambah Bukti Dokumen</h4>
        <form action="{{ route('admin.kriteria-dokumen.kelola-bukti.store') }}" method="POST" enctype="multipart/form-data" id="KelolaTargetForm">
          @csrf
					<div class="mb-4">
						<label for="standardSelector" class="form-label">Pilih Kriteria:</label>
						<select id="standardSelector" class="form-select">
							<option value="standard-{{ $standard->id }}" selected>{{ $standard->nama }} (Aktif)</option>
							@foreach($allStandards->where('id', '!=', $standard->id) as $item)
								<option value="standard-{{ $item->id }}">{{ $item->nama }}</option>
							@endforeach
						</select>
					</div>

					<div id="buktiContainer">
						@foreach($allStandards as $item)
							<div id="standard-{{ $item->id }}" class="bukti-list" style="{{ $item->id === $standard->id ? '' : 'display: none;' }}">
								<h6 class="fw-bold">{{ $item->nama }}</h6>
								<ul>
									@forelse($item->buktiStandar as $bukti)
										<li>{{ $bukti->nama }} @if($bukti->deskripsi) - {{ $bukti->deskripsi }} @endif</li>
									@empty
										<li><em>Belum ada bukti</em></li>
									@endforelse
								</ul>
							</div>
						@endforeach
					</div>

          <div class="row">
            <div class="col-sm-6">
              <div class="mb-3">
                <label for="nama" class="form-label">Nama Bukti Dokumen</label>
                <input id="nama" class="form-control @error('nama') is-invalid @enderror" name="nama" type="text" value="{{ old('nama') }}" placeholder="Nama Dokumen">
                @error('nama')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div><!-- Col -->
            <div class="col-sm-6">
              <div class="mb-3">
								<label for="deskripsi" class="form-label">Keterangan / Informasi Lain :</label>
                <textarea id="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" name="deskripsi" placeholder="Keterangan / Informasi Lain">{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div><!-- Col -->
          </div>
					<input type="hidden" name="standard_id" id="standard_id" value="{{ $standard->id }}">
					<input type="hidden" name="importTitle" value="{{ $importTitle }}">
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
	<script>
  document.addEventListener('DOMContentLoaded', function () {
    const selector = document.getElementById('standardSelector');
    const buktiLists = document.querySelectorAll('.bukti-list');

    function showSelectedBukti(id) {
      buktiLists.forEach(list => list.style.display = 'none');
      const target = document.getElementById(id);
      if (target) target.style.display = 'block';
    }

    // Tampilkan bukti awal sesuai pilihan default
    showSelectedBukti(selector.value);

    // Tampilkan bukti saat dropdown berubah
    selector.addEventListener('change', function () {
			showSelectedBukti(this.value);

			const selectedId = this.value.replace('standard-', '');
			document.getElementById('standard_id').value = selectedId;
		});

  });
</script>

@endpush