@extends('layout.master-user')

@push('plugin-styles')
  <!-- Include DataTables CSS -->
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
@endpush

@section('content')
  <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div class="row align-items-start mb-2">
      <h4 class="mb-3 mb-md-0">Forecasting (Prediksi) {{ $prodi }} Tahun {{ $periode }}</h4>
      <div class="col-md-0">
        <br><p class="text-muted tx-13 mb-3 mb-md-0">Forecasting (Prediksi) digunakan sebagai gambaran dari hasil AMI (Audit Mutu Internal) pada sistem AMI UPR
        </p>
      </div>
    </div>
  </div>

  <x-hasil-forcasting-lamemba
    :standards="$standards"
    :transkasis="$transaksi_ami"
    :periodes="$periode"
    :prodis="$prodi"
  />

@endsection
@push('plugin-scripts')
  <!-- Include jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@endpush
