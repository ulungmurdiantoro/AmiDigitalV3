<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
  <div class="row align-items-start mb-2">
    <h4 class="mb-3 mb-md-0">Nilai Evaluasi Diri {{ $prodi }} Tahun {{ $periode }}</h4>
    <div class="col-md-7">
      <br><p class="text-muted tx-13 mb-3 mb-md-0">Rekap nilai dan feedback pasca aktivitas AMI (Audit Mutu Internal) pada sistem AMI UPR</p>
    </div>
    <div class="col-md-4 d-flex justify-content-md-end">
      <div class="btn-group mb-3 mb-md-0" role="group" aria-label="Basic example">
        <a href="{{ route('auditor.nilai-evaluasi-diri.rekap-nilai.report-lha', [
            'periode' => urlencode($periode),
            'prodi' => $prodi
          ]) }}" 
          class="btn btn-lg btn-outline-primary"
          target="_blank"
          rel="noopener noreferrer">
          Export LHA
        </a>
        <a href="{{ route('auditor.nilai-evaluasi-diri.rekap-nilai.report-rtm', [
            'periode' => urlencode($periode),
            'prodi' => $prodi
          ]) }}" 
          class="btn btn-lg btn-outline-primary"
          target="_blank"
          rel="noopener noreferrer">
          Export RTM
        </a>
      </div>
    </div>
  </div>
</div>