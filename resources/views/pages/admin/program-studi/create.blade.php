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
    <li class="breadcrumb-item active" aria-current="page">Tambah Program Studi</li>
  </ol>
</nav>

<div class="row">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Tambah Program Studi</h4>
        <form action="{{ route('admin.program-studi.store') }}" method="POST" enctype="multipart/form-data" id="ProdiForm">
          @csrf
          <div class="row">
            <div class="col-sm-6">
              <div class="mb-3">
                <label for="prodi_nama" class="form-label">Nama Program Studi</label>
                <input id="prodi_nama" class="form-control @error('prodi_nama') is-invalid @enderror" name="prodi_nama" type="text" value="{{ old('prodi_nama') }}" placeholder="Masukkan Nama Program Studi">
                @error('prodi_nama')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div><!-- Col -->
            <div class="col-sm-6">
              <div class="mb-3">
                <label for="prodi_jenjang" class="form-label">Jenjang</label>
                <select class="form-select @error('prodi_jenjang') is-invalid @enderror" name="prodi_jenjang" id="prodi_jenjang">
                  <option selected disabled>-</option>
                  <option>S1</option>
                  <option>S2</option>
                  <option>S3</option>
                  <option>S1 Terapan</option>
                  <option>S2 Terapan</option>
                  <option>S3 Terapan</option>
                  <option>PPG</option>
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
                <label style="float: right;">
                  <a href="#" type="button" data-bs-toggle="modal" data-bs-target="#jurusanModal"><i class="btn-icon-prepend" data-feather="plus-circle"></i> Tambah Jurusan</a> 
                  <a href="#" type="button" data-bs-toggle="modal" data-bs-target="#jurusanHapus" style="color: red;"><i class="btn-icon-prepend" data-feather="minus-circle"></i> Hapus Jurusan</a>
                </label>
                <select class="form-select @error('prodi_jurusan') is-invalid @enderror" name="prodi_jurusan" id="prodi_jurusan">
                    <option selected disabled>-</option>
                    @foreach($Jurusans as $Jurusan)
                      <option value="{{ $Jurusan->jurusan_nama }}">{{ $Jurusan->jurusan_nama }}</option>
                    @endforeach
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
                  @foreach($Fakultass as $Fakultas)
                    <option value="{{ $Fakultas->fakultas_nama }}">{{ $Fakultas->fakultas_nama }}</option>
                  @endforeach
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

<div class="modal fade" id="jurusanModal" tabindex="-1" aria-labelledby="jurusanModal" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('admin.program-studi.storejurusan') }}" method="POST" enctype="multipart/form-data" id="JurusanForm">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="jurusanModal">Tambah Jurusan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-sm-12">
              <div class="mb-12">
                <label for="jurusan_nama" class="form-label">Nama Jurusan</label>
                <input id="jurusan_nama" class="form-control" name="jurusan_nama" type="text" placeholder="Masukkan Nama Jurusan">
              </div>
            </div><!-- Col -->
          </div>
          <br>
          <div class="row">
            <div class="col-sm-12">
              <div class="mb-12">
                <label>Daftar Jurusan Terdaftar</label>
                @foreach ($Jurusans as $jurusan)
                  <div>{{ $loop->iteration }}. {{ $jurusan->jurusan_nama }}</div>
                @endforeach
              </div>
            </div><!-- Col -->
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <input class="btn btn-primary" type="submit" value="Submit">
        </div>
      </div>
    </form>
  </div>
</div>
<div class="modal fade" id="jurusanHapus" tabindex="-1" aria-labelledby="jurusanHapus" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="jurusanHapus">Hapus Jurusan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
      </div>
      <div class="modal-body">
      <div class="row">
          <div class="col-sm-12">
            <div class="mb-12">
              <label for="prodi_nama" class="form-label">Pilih Jurusan yang akan Dihapus :</label>
              <select class="form-select" name="prodi_jurusan" id="prodi_jurusan">
                <option selected disabled>-</option>
                <@foreach($Jurusans as $Jurusan)
                  <option value="{{ $Jurusan->jurusan_nama }}">{{ $Jurusan->jurusan_nama }}</option>
                @endforeach
              </select>
            </div>
          </div><!-- Col -->
        </div>
        <br>
        <div class="row">
          <div class="col-sm-12">
            <div class="mb-12">
              <label>Daftar Jurusan Terdaftar</label>
              @foreach ($Jurusans as $jurusan)
                <div>{{ $loop->iteration }}. {{ $jurusan->jurusan_nama }}</div>
              @endforeach
            </div>
          </div><!-- Col -->
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-danger">Save changes</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="fakultasModal" tabindex="-1" aria-labelledby="fakultasModal" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('admin.program-studi.storefakultas') }}" method="POST" enctype="multipart/form-data" id="FakultasForm">
    @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="fakultasModal">Tambah Fakultas</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-sm-12">
              <div class="mb-12">
                <label for="fakultas_nama" class="form-label">Nama Fakultas</label>
                <input id="fakultas_nama" class="form-control" name="fakultas_nama" type="text" placeholder="Masukkan Nama Fakultas">
              </div>
            </div><!-- Col -->
          </div>
          <br>
          <div class="row">
            <div class="col-sm-12">
              <div class="mb-12">
                <label>Daftar Fakultas Terdaftar</label>
                @foreach ($Fakultass as $Fakultas)
                  <div>{{ $loop->iteration }}. {{ $Fakultas->fakultas_nama }}</div>
                @endforeach
              </div>
            </div><!-- Col -->
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <input class="btn btn-primary" type="submit" value="Submit">
        </div>
      </div>
    </form>
  </div>
</div>
<div class="modal fade" id="fakultasHapus" tabindex="-1" aria-labelledby="fakultasHapus" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="fakultasHapus">Hapus Fakultas</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
      </div>
      <div class="modal-body">
      <div class="row">
          <div class="col-sm-12">
            <div class="mb-12">
              <label for="prodi_nama" class="form-label">Pilih Fakultas yang akan Dihapus :</label>
              <select class="form-select" name="prodi_jurusan" id="prodi_jurusan">
                <option selected disabled>-</option>
                @foreach($Fakultass as $Fakultas)
                  <option value="{{ $Fakultas->fakultas_nama }}">{{ $Fakultas->fakultas_nama }}</option>
                @endforeach
              </select>
            </div>
          </div><!-- Col -->
        </div>
        <br>
        <div class="row">
          <div class="col-sm-12">
            <div class="mb-12">
              <label>Daftar Fakultas Terdaftar</label>
              @foreach ($Fakultass as $Fakultas)
                <div>{{ $loop->iteration }}. {{ $Fakultas->fakultas_nama }}</div>
              @endforeach
            </div>
          </div><!-- Col -->
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-danger">Save changes</button>
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