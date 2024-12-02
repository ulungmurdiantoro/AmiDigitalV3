dd(array_merge($data_standar, $data));

@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
@endpush

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item active" aria-current="page">Kriteria Dokumen</li>
  </ol>
</nav>

<div id="dataBanPtS1" class="data-table" style="display:block;">
  <div class="row">
    <div class="col-md-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">Import Data BAN-PT S1</h4>
        </div>
        <div class="card-body d-flex justify-content-center align-items-center">
          <a href="{{ url('/admin/kriteria-dokumen/create') }}" class="btn btn-success btn-lg btn-icon-text">
            <i class="fas fa-file-import mr-2"></i> Import
          </a>
        </div>
      </div>
    </div>
  </div>
  @for ($i = 1; $i <= 12; $i++)
    @php
      $titleKey = "nama_data_standar_ban_pts1_k{$i}";
      $dataKey = "data_standar_ban_pts1_k{$i}";
      $title = $$titleKey; // Dynamically access the title
      $data = $$dataKey;   // Dynamically access the data
    @endphp

    @if (isset($title) && isset($data))
      <x-data-table :title="$title" :tableId="'dataTableExample' . $i" :data="$data" />
    @else
      <p>Data for standard {{ $i }} is not available.</p>
    @endif
  @endfor
</div>



<div id="dataBanPtS1" class="data-table" style="display:block;">
  <div class="row">
    <div class="col-md-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">Import Data BAN-PT S1</h4>
        </div>
        <div class="card-body d-flex justify-content-center align-items-center">
          <a href="{{ url('/admin/kriteria-dokumen/create') }}" class="btn btn-success btn-lg btn-icon-text">
            <i class="fas fa-file-import mr-2"></i> Import
          </a>
        </div>
      </div>
    </div>
  </div>
  @for ($i = 1; $i <= 12; $i++)
    @php
      $titleVar = 'nama_data_standar_ban_pts1_k' . $i;
      $dataVar = 'data_standar_ban_pts1_k' . $i;
      $title = $$titleVar ?? '';
      $data = $$dataVar ?? collect([]);
    @endphp
    <x-data-table :title="$title" :tableId="'dataTableExample' . $i" :data="$data" />
  @endfor
</div>

<nav class="settings-sidebar">
  <div class="sidebar-body">
    <a href="#" class="settings-sidebar-toggler">
      <i data-feather="settings"></i>
    </a>
    <h6 class="text-muted mb-2">Daftar Kriteria:</h6>
    <div class="mb-3 pb-3 border-bottom">
      <ul class="breadcrumb breadcrumb-dot">
        <li class="breadcrumb-item"><a href="#"></a></li>
        @for ($i = 1; $i <= 12; $i++)
          <li class="breadcrumb-item"><a href="#dataTableExample{{ $i }}">Kriteria {{ $i }}</a></li>
        @endfor
      </ul>
    </div>
  </div>
</nav>

@endsection

@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
@endpush

@push('custom-scripts')
<script>
  $(document).ready(function() {
    $('#dataTableExample1, #dataTableExample2, #dataTableExample3, #dataTableExample4, #dataTableExample5, #dataTableExample6, #dataTableExample7, #dataTableExample8, #dataTableExample9, #dataTableExample10, #dataTableExample11, #dataTableExample12, #dataTableExample13, #dataTableExample14, #dataTableExample15, #dataTableExample16, #dataTableExample17, #dataTableExample18, #dataTableExample19, #dataTableExample20, #dataTableExample21, #dataTableExample22, #dataTableExample23, #dataTableExample24, #dataTableExample25, #dataTableExample26, #dataTableExample27, #dataTableExample28, #dataTableExample29, #dataTableExample30, #dataTableExample31, #dataTableExample32, #dataTableExample33, #dataTableExample34, #dataTableExample35, #dataTableExample36').DataTable();
  });

  document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('btnShowBanPtS1').addEventListener('click', function() {
      showTable('dataBanPtS1');
    });
    document.getElementById('btnShowBanPtS2').addEventListener('click', function() {
      showTable('dataBanPtS2');
    });
    document.getElementById('btnShowBanPtS3').addEventListener('click', function() {
      showTable('dataBanPtS3');
    });
    document.getElementById('btnShowBanPtD3').addEventListener('click', function() {
      showTable('dataBanPtD3');
    });
    document.getElementById('btnShowBanPtS1Terapan').addEventListener('click', function() {
      showTable('dataBanPtTerapanS1');
    });
    document.getElementById('btnShowBanPtS2Terapan').addEventListener('click', function() {
      showTable('dataBanPtTerapanS2');
    });
    document.getElementById('btnShowBanPtS3Terapan').addEventListener('click', function() {
      showTable('dataBanPtTerapanS3');
    });
    document.getElementById('btnShowLamDikS1').addEventListener('click', function() {
      showTable('dataLamDikS1');
    });
    document.getElementById('btnShowLamDikS2').addEventListener('click', function() {
      showTable('dataLamDikS2');
    });
    document.getElementById('btnShowLamDikS3').addEventListener('click', function() {
      showTable('dataLamDikS3');
    });
    document.getElementById('btnShowLamDikD3').addEventListener('click', function() {
      showTable('dataLamDikD3');
    });
    document.getElementById('btnShowLamDikS1Terapan').addEventListener('click', function() {
      showTable('dataLamDikTerapanS1');
    });
    document.getElementById('btnShowLamDikS2Terapan').addEventListener('click', function() {
      showTable('dataLamDikTerapanS2');
    });
    document.getElementById('btnShowLamDikS3Terapan').addEventListener('click', function() {
      showTable('dataLamDikTerapanS3');
    });
    // Add similar event listeners for other buttons

    function showTable(divId) {
      console.log('Showing table:', divId); // Debug log
      var tables = document.querySelectorAll('.data-table');
      tables.forEach(function(table) {
        table.style.display = 'none';
      });
      document.getElementById(divId).style.display = 'block';
      console.log('Displayed table:', divId); // Debug log
    }
  });
</script>
@endpush
