@props([
  'standards' => collect(),
  'elements' => collect(),
  'transkasis' => collect(),
  'periodes' => collect(),
  'prodis' => collect(),
])
<style>
  table.table-bordered th,
  table.table-bordered td {
    border: 1px solid rgba(0, 0, 0, 0.2);
		padding: 8px;
;
  }
</style>

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
				<table class="col-md-12 table-bordered" style="table-layout: fixed; width: 100%;">
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
        <table class="col-md-12 table-bordered" style="table-layout: fixed; width: 100%;">
          <thead>
            <tr class="text-center">
              <th style="width: 7%;" class="text-dark">No</th>
              <th style="width: 15%;" class="text-dark">Kriteria</th>
              <th style="width: 13%;" class="text-dark">Dimensi</th>
              <th style="width: 50%;" class="text-dark">Indikator</th>
              <th style="width: 15%;" class="text-dark">Pemenuhan Standar</th>
            </tr>
          </thead>
          <tbody>
            @foreach(($standards ?? collect()) as $sIndex => $standard)
              @foreach($standard->elements->filter(fn($e) => in_array($e->nama, [
                'Tata Pamong',
                'Tata Kelola',
                'Kecukupan Dan Kualifikasi Dosen',
                'Pengelolaan Dosen',
                'Kurikulum',
                'Jaminan Pembelajaran',
              ])) as $eIndex => $element)

                @foreach($element->indicators as $iIndex => $indikator)
                  @php
                    // Penomoran hierarkis
                    $kode = ($sIndex + 1) . '.' . ($eIndex + 1) . '.' . ($iIndex + 1);

                    // Ambil nilai berdasarkan periode
                    $nilai = $indikator->dokumen_nilais?->where('periode', $periodes)->first();
                    $hasil = $nilai?->hasil_nilai;

                    // Tentukan status dan warna badge
                    $status = match(true) {
                      $hasil == 1 => 'Memenuhi',
                      $hasil == 0 => 'Tidak Memenuhi',
                      default => 'Belum Dinilai',
                    };

                    $warna = match(true) {
                      $hasil == 1 => 'success',
                      $hasil == 0 => 'danger',
                      default => 'secondary',
                    };
                  @endphp

                  <tr>
                    <td class="text-center">{{ $kode }}</td>
                    <td>{{ $standard->nama }}</td>
                    <td>{{ $element->nama }}</td>
                    <td>{!! nl2br(e($indikator->nama_indikator)) !!}</td>
                    <td class="text-center">
											<div class="d-inline-flex align-items-center justify-content-center rounded px-3 py-1 border border-{{ $warna }} text-{{ $warna }}" 
												style="background-color: transparent; max-width: 100%; white-space: normal;">
                        {{ $status }}
                      </div>
                    </td>
                  </tr>
                @endforeach
              @endforeach
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

{{-- Pemenuhan Enam Dimensi Terpilih --}}
<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card" style="border-radius: 5px; overflow: hidden;">
      <div class="card-header bg-primary text-white">
        <h6 class="mb-0">Pemenuhan 15 Dimensi Lainnya</h6>
      </div>
      <div class="card-body">
        <table class="col-md-12 table-bordered" style="table-layout: fixed; width: 100%;">
          <thead>
            <tr class="text-center">
              <th style="width: 7%;" class="text-dark">No</th>
              <th style="width: 15%;" class="text-dark">Kriteria</th>
              <th style="width: 13%;" class="text-dark">Dimensi</th>
              <th style="width: 50%;" class="text-dark">Indikator</th>
              <th style="width: 15%;" class="text-dark">Pemenuhan Standar</th>
            </tr>
          </thead>
          <tbody>
            @foreach(($standards ?? collect()) as $sIndex => $standard)
              @foreach($standard->elements->filter(fn($e) => !in_array($e->nama, [
                'Tata Pamong',
                'Tata Kelola',
                'Kecukupan Dan Kualifikasi Dosen',
                'Pengelolaan Dosen',
                'Kurikulum',
                'Jaminan Pembelajaran',
                'Pemenuhan Kualifikasi dan Luaran Dosen',
              ])) as $eIndex => $element)

                @foreach($element->indicators as $iIndex => $indikator)
                  @php
                    // Penomoran hierarkis
                    $kode = ($sIndex + 1) . '.' . ($eIndex + 1) . '.' . ($iIndex + 1);

                    // Ambil nilai berdasarkan periode
                    $nilai = $indikator->dokumen_nilais?->where('periode', $periodes)->first();
                    $hasil = $nilai?->hasil_nilai;

                    // Tentukan status dan warna badge
                    $status = match(true) {
                      $hasil == 1 => 'Memenuhi',
                      $hasil == 0 => 'Tidak Memenuhi',
                      default => 'Belum Dinilai',
                    };

                    $warna = match(true) {
                      $hasil == 1 => 'success',
                      $hasil == 0 => 'danger',
                      default => 'secondary',
                    };
                  @endphp

                  <tr>
                    <td class="text-center">{{ $kode }}</td>
                    <td>{{ $standard->nama }}</td>
                    <td>{{ $element->nama }}</td>
                    <td>{!! nl2br(e($indikator->nama_indikator)) !!}</td>
                    <td class="text-center">
											<div class="d-inline-flex align-items-center justify-content-center rounded px-3 py-1 border border-{{ $warna }} text-{{ $warna }}" 
												style="background-color: transparent; max-width: 100%; white-space: normal;">
                        {{ $status }}
                      </div>
                    </td>
                  </tr>
                @endforeach
              @endforeach
            @endforeach
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
      <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between">
        <div>
          <button type="button" class="btn btn-warning btn-sm btn-icon" data-bs-toggle="modal" data-bs-target="#infoModal">
            <i data-feather="info"></i>
          </button>
          <span class="ms-2">Syarat Perlu Unggul Berdasarkan Kualifikasi dan Luaran Dosen</span>
        </div>
      </div>
      <div class="card-body">
				<div class="row">
					<div class="col-md-12 mb-4">
						<div class="card shadow-sm">
							{{-- <div class="card-header bg-secondary text-white text-center fw-bold py-2">
								Pemenuhan Kualifikasi dan Luaran Dosen
							</div> --}}
							<div class="card-body p-0">
								<div class="table-responsive">
									<table class="col-md-12 table-bordered" style="table-layout: fixed; width: 100%;">
										<thead>
											<tr class="text-center">
												<th style="width: 7%;" class="text-dark">No</th>
												<th style="width: 15%;" class="text-dark">Kriteria</th>
												<th style="width: 13%;" class="text-dark">Dimensi</th>
												<th style="width: 50%;" class="text-dark">Indikator</th>
												<th style="width: 15%;" class="text-dark">Pemenuhan Standar</th>
											</tr>
										</thead>
										<tbody>
											@foreach(($standards ?? collect())->filter(fn($s) => in_array($s->nama, [
												'Pemenuhan Kualifikasi dan Luaran Dosen',
											])) as $sIndex => $standard)
												@foreach($standard->elements as $eIndex => $element)
													@foreach($element->indicators as $iIndex => $indikator)
														@php
															// Penomoran hierarkis
															$kode = ($sIndex + 1) . '.' . ($eIndex + 1) . '.' . ($iIndex + 1);

															// Ambil nilai berdasarkan periode
															$nilai = $indikator->dokumen_nilais?->where('periode', $periodes)->first();
															$hasil = $nilai?->hasil_nilai;

															// Tentukan status dan warna badge
															$status = match(true) {
																$hasil == 1 => 'Memenuhi',
																$hasil == 0 => 'Tidak Memenuhi',
																default => 'Belum Dinilai',
															};

															$warna = match(true) {
																$hasil == 1 => 'success',
																$hasil == 0 => 'danger',
																default => 'secondary',
															};
														@endphp

														<tr>
															<td class="text-center">{{ $kode }}</td>
															<td>{{ $standard->nama }}</td>
															<td>{{ $element->nama }}</td>
															<td>{!! nl2br(e($indikator->nama_indikator)) !!}</td>
															<td class="text-center">
																<div class="d-inline-flex align-items-center justify-content-center rounded px-3 py-1 border border-{{ $warna }} text-{{ $warna }}" 
																	style="background-color: transparent; max-width: 100%; white-space: normal;">
																	{{ $status }}
																</div>
															</td>
														</tr>
													@endforeach
												@endforeach
											@endforeach
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
      </div>
      <div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="infoModalLabel">Informasi Syarat Perlu Unggul Berdasarkan Kualifikasi dan Luaran Dosen</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
              <p>Syarat perlu Unggul pada Instrumen Akreditasi Unggul untuk setiap program ditekankan kepada persyaratan jumlah dosen, Jumlah Dosen 
								Tetap dengan pendidikan Magister/Doktor, Jumlah Dosen Tetap dengan Kualifikasi Jenjang Jabatan Akademik, Publikasi dan Jumlah Dosen Tetap 
								dengan Sertifikat Kompetensi.</p>
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
