@extends('layout.master-auditor')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
@endpush

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item active" aria-current="page">Koreksi Mutu</li>
  </ol>
</nav>

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
          <div>
            <h4 class="mb-3 mb-md-0">Data Koreksi AMI</h4>
            <p class="text-muted mb-3">Tindaklanjuti aktivitas AMI (Audit Mutu Internal) dengan memberikan feedback atau tanggapan dari setiap perbaikan.</p>
          </div>
        </div>
        @foreach ($data_kesiapan as $kesiapan)
          <div class="row flex-grow-1">
            <div class="col-md-5 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-baseline">
                    <input type="hidden" name="periode" value="{{ $kesiapan->periode }}">
                    <input type="hidden" name="prodi_ami" value="{{ $kesiapan->prodi }}">
                    <h2 class="card-title">AMI {{ $kesiapan->periode }} {{ $kesiapan->prodi }}</h2>
                  </div>
                  <div class="row">
                    <p class="text-muted mb-3">Kesiapan AMI (Audit Mutu Internal) Program Studi {{ $kesiapan->prodi }} tahun {{ $kesiapan->periode }}</p>
                    
                    <a href="{{ route('auditor.koreksi-ami.revisi-ami', ['periode' => urlencode($kesiapan->periode), 'prodi' => $kesiapan->prodi]) }}" class="btn btn-secondary btn-icon-text mb-2 mb-md-0" title="Perbarui Data">
                      <i class="link-icon" data-feather="edit-3"></i> Lihat Status dan Perbarui Data
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </div>
</div>
@endsection

@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
@endpush

@push('custom-scripts')
  <script src="{{ asset('assets/js/data-table.js') }}"></script>
@endpush