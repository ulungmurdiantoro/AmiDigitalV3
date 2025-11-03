@props([
  'standards' => collect(),
  'elements' => collect(),
  'transkasis' => collect(),
  'periodes' => collect(),
  'prodis' => collect(),
])

{{-- Syarat Terakreditasi Unggul --}}
<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card" style="border-radius: 5px; overflow: hidden;">
      <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between">
        <div>
          <button type="button" class="btn btn-warning btn-sm btn-icon" data-bs-toggle="modal" data-bs-target="#infoModal">
            <i data-feather="info"></i>
          </button>
          <span class="ms-2">Syarat Terakreditasi Unggul</span>
        </div>
      </div>
      <div class="card-body">
        <table class="table table-bordered">
          <thead>
            <tr class="text-center">
              <th class="text-dark col-md-1">No</th>
              <th class="text-dark col-md-11">Syarat Terakreditasi Unggul</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td class="text-center">1</td>
              <td>Pemenuhan 6 dimensi terpilih pada Kriteria 2, 4, 6 memenuhi Standar SN-Dikti/Standar LAM (K2 = memenuhi Standar LAM; K4+K6 = memenuhi SN-Dikti).</td>
            </tr>
            <tr>
              <td class="text-center">2</td>
              <td>Pemenuhan 15 dimensi lainnya memenuhi standar SN-Dikti dan Standar LAM.</td>
            </tr>
            <tr>
              <td class="text-center">3</td>
              <td>Pemenuhan pada kualifikasi dan luaran dosen.</td>
            </tr>
          </tbody>
        </table>
      </div>

      {{-- Modal Info --}}
      <div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="infoModalLabel">Informasi Syarat Terakreditasi Unggul</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
              <p>Syarat terakreditasi Unggul dari 21 dimensi Instrumen Akreditasi Unggul adalah pemenuhan standar enam dimensi terpilih pada Kriteria 2 (Tata Kelola dan Tata Pamong), Kriteria 4 (Dosen), dan Kriteria 6 (Pendidikan dan Pengajaran), pemenuhan standar 15 dimensi lainnya, serta pemenuhan kualifikasi dan luaran dosen.</p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Pemenuhan Enam Dimensi Terpilih --}}
<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card" style="border-radius: 5px; overflow: hidden;">
      <div class="card-header bg-primary text-white">
        <h6 class="mb-0">Pemenuhan Enam Dimensi Terpilih pada Kriteria 2, 4, dan 6</h6>
      </div>
      <div class="card-body">
        <table class="table table-bordered">
          <thead>
            <tr class="text-center">
              <th class="text-dark col-md-1">No</th>
              <th class="text-dark col-md-2">Kriteria</th>
              <th class="text-dark col-md-2">Dimensi</th>
              <th class="text-dark col-md-5">Indikator</th>
              <th class="text-dark col-md-2">Pemenuhan Standar</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($standards as $standard)
              <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $standard->kriteria }}</td>
                <td>{{ $standard->dimensi }}</td>
                <td>{{ $standard->indikator }}</td>
                <td class="text-center">
                  <span class="badge bg-{{ $standard->status == 'Memenuhi' ? 'success' : 'danger' }}">
                    {{ $standard->status }}
                  </span>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="text-center text-muted">Belum ada data dimensi yang tersedia.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

{{-- Syarat Perlu Peringkat Baik Sekali --}}
<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card" style="border-radius: 5px; overflow: hidden;">
      <div class="card-header bg-primary text-white">
        <h6 class="mb-0">Syarat Perlu Peringkat Baik Sekali</h6>
      </div>
      <div class="card-body">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th class="col-md-1">No</th>
              <th class="col-md-9">Syarat</th>
              <th class="col-md-2">Status</th>
            </tr>
          </thead>
          <tbody>
            {{-- Tambahkan data syarat di sini --}}
            <tr>
              <td class="text-center">1</td>
              <td>Nilai Evaluasi Diri ≥ 3.25</td>
              <td class="text-center"><span class="badge bg-success">Memenuhi</span></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

{{-- Syarat Perlu Terakreditasi --}}
<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card" style="border-radius: 5px; overflow: hidden;">
      <div class="card-header bg-primary text-white">
        <h6 class="mb-0">Syarat Perlu Terakreditasi</h6>
      </div>
      <div class="card-body">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th rowspan="2">Nilai Evaluasi Diri</th>
              <th rowspan="2">Syarat Perlu Terakreditasi</th>
              <th colspan="2">Syarat Perlu Peringkat</th>
              <th rowspan="2">Status</th>
              <th rowspan="2">Peringkat</th>
            </tr>
            <tr>
              <th>Baik Sekali</th>
              <th>Unggul</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td class="text-center">3.50</td>
              <td class="text-center">≥ 3.00</td>
              <td class="text-center">≥ 3.25</td>
              <td class="text-center">≥ 3.50</td>
              <td class="text-center"><span class="badge bg-success">Memenuhi</span></td>
              <td class="text-center"><strong>Unggul</strong></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
