@extends('layout.master-auditor')

@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
  <div class="row align-items-start mb-2">
    <h4 class="mb-3 mb-md-0">Distribusi Nilai AMI {{ $prodi }} Tahun {{ $periode }}</h4>
    <div class="col-md-0">
      <br><p class="text-muted tx-13 mb-3 mb-md-0">Menampilkan semua nilai hasil AMI tanpa pengelompokan</p>
    </div>
  </div>
</div>

@php
  $nilaiLabels = collect();
  $nilaiValues = collect();

  foreach(($standards ?? []) as $index => $standard) {
    foreach($standard->elements as $eIndex => $element) {
      foreach($element->indicators as $iIndex => $indikator) {
        $kode = ($index + 1) . '.' . ($eIndex + 1) . '.' . ($iIndex + 1);
        $dokumen = $indikator->dokumen_nilais?->where('periode', $periode) ?? collect();

        foreach ($dokumen as $d) {
          $nilaiLabels->push($kode);
          $nilaiValues->push(round($d->hasil_nilai ?? 0, 2));
        }
      }
    }
  }
@endphp

<div class="row">
	<div class="col-md-12 grid-margin stretch-card">
		<div class="card" style="border-radius: 5px; overflow: hidden;">
			<div class="card-header bg-primary text-white">
				<h6 class="mb-0">Statistik Spiderweb</h6>
			</div>
			<div class="card-body">
				<canvas id="StatistikSpiderweb" class="mt-md-3 mt-xl-0"></canvas>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12 grid-margin stretch-card">
		<div class="card" style="border-radius: 5px; overflow: hidden;">
			<div class="card-header bg-primary text-white">
				<h6 class="mb-0">Diagram Batang</h6>
			</div>
			<div class="card-body">
        <div id="DiagramBatang" class="mt-md-3 mt-xl-0"></div>
			</div>
		</div>
	</div>
</div>
@endsection

@push('plugin-scripts')
	<!-- Include jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- Include ApexCharts -->
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
  <!-- Include Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>@endpush

@push('custom-scripts')
<script>
  const nilaiLabels = @json($nilaiLabels);
  const nilaiValues = @json($nilaiValues);
</script>
<script src="{{ asset('assets/js/chart-total-lamemba.js') }}"></script>
@endpush
