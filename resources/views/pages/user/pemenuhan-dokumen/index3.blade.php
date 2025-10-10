@extends('layout.master-user')

@section('content')
  @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
      <h4 class="mb-3 mb-md-0">Pemenuhan Dokumen {{ $akreditasi->nama }} {{ $jenjang->nama }}</h4>
    </div>
  </div>

  @foreach ($standards as $index => $standard)
  {{-- @dd($standard->buktiStandar) --}}
    <div class="row">
      <div class="col-md-12 grid-margin stretch-card">
        <div class="card" style="border-radius: 5px; overflow: hidden;">
          <div class="card-header bg-primary text-white">
            <h6 id="dataTitle{{ $index + 1 }}" class="mb-0">
              {{ $standard->nama }} - {{ $akreditasi->nama }} {{ $jenjang->nama }}
            </h6>
          </div>

          <div class="card-body p-0">
            <x-user.data-table.pemenuhan-dokumen-lamemba-baru
              :id="'std-'.$standard->id"
              :bukti="$standard->buktiStandar"
              :standards="$standard"
              editRouteName="admin.kriteria-dokumen.kelola-bukti.edit"
            />
          </div>
        </div>
      </div>
    </div>
  @endforeach
@endsection

@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="https://unpkg.com/feather-icons"></script>
@endpush

@push('custom-scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    if (window.feather) feather.replace();
  });
</script>
@endpush
