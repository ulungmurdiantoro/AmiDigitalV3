@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
  <div class="row align-items-start mb-2">
    <h4 class="mb-3 mb-md-0">Statistik Elemen {{ $prodi }} Tahun {{ $periode }}</h4>
    <div class="col-md-0">
      <br><p class="text-muted tx-13 mb-3 mb-md-0">Menginformasikan hasil AMI (Audit Mutu Internal) melalui diagram dan dikelompokan berdasarkan elemen pada sistem AMI UPR</p>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-6 grid-margin stretch-card">
    <div class="card" style="border-radius: 2px; overflow: hidden;">
      <div class="card-header bg-primary text-white">
        <h6 class="mb-0">Statistik Spiderweb</h6>
      </div>
      <div class="card-body">
        <canvas id="StatistikSpiderweb" class="mt-md-3 mt-xl-0"></canvas>
      </div>
    </div>
  </div>
  <div class="col-md-6 grid-margin stretch-card">
    <div class="card" style="border-radius: 2px; overflow: hidden;">
      <div class="card-header bg-primary text-white">
        <h6 class="mb-0">Diagram Batang</h6>
      </div>
      <div class="card-body">
        <div id="DiagramBatang" class="mt-md-3 mt-xl-0"></div>
      </div>
    </div>
  </div>
  @php
    $chartData = collect($standards)->map(function ($standard) {
        // kumpulkan semua indikator dari semua elemen
        $indicators = $standard->elements->flatMap(fn($e) => $e->indicators);

        // hasOne => ambil hasil_nilai langsung dari model (atau 0 jika null)
        $totalNilai = $indicators->sum(function ($i) {
            return (int) ($i->dokumen_nilais?->hasil_nilai ?? 0);
        });

        return [
            'nama'        => $standard->nama,
            'total_nilai' => $totalNilai,
            'total_count' => $indicators->count(),
        ];
    });
  @endphp

  @foreach ($standards as $index => $standard)
    <div class="col-md-6 grid-margin stretch-card">
      <div class="card" style="border-radius: 2px; overflow: hidden;">
        <div class="card-header bg-primary text-white">
          <h6 class="mb-0">{{ $standard->nama }}</h6>
        </div>
        <div class="card-body">
          <canvas id="StatistikSpiderweb-{{ $index }}" class="mt-md-3 mt-xl-0"></canvas>
        </div>
      </div>
    </div>
  @endforeach
</div>
@endsection

@push('plugin-scripts')
  <!-- Include jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- Include ApexCharts -->
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
  <!-- Include Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@push('custom-scripts')
<script>
  const chartData = @json($chartData);
  const standarData = @json($standards);
</script>
<script src="{{ asset('assets/js/chart-elemen-lamemba.js') }}"></script>
<script src="{{ asset('assets/js/chart-average-elemen-lamemba.js') }}"></script>
@endpush
