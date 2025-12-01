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

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card" style="border-radius: 5px; overflow: hidden;">
      <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between">
        <div>
          <h6 class="mb-0">Syarat Terakreditasi Unggul</h6>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="card-body">
            <table class="col-md-12 table-bordered" style="table-layout: fixed; width: 100%;">
              <thead>
                <tr class="text-center">
                  <th class="text-dark col-md-1">No</th>
                  <th class="text-dark col-md-11">Syarat Terakreditasi Unggul Dua Tahun</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="text-center">1</td>
                  <td>Minimal 70% atau minimal 40 indikator melampaui SN Dikti.</td>
                </tr>
                <tr>
                  <td class="text-center">2</td>
                  <td>8 indikator pada Syarat Perlu Terakreditasi Unggul melampaui SN Dikti.</td>
                </tr>
                <tr>
                  <td class="text-center">3</td>
                  <td>Kualifikasi dosen memenuhi Syarat Perlu Terakreditasi Unggul</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card-body">
            <table class="col-md-12 table-bordered" style="table-layout: fixed; width: 100%;">
              <thead>
                <tr class="text-center">
                  <th class="text-dark col-md-1">No</th>
                  <th class="text-dark col-md-11">Syarat Terakreditasi Unggul Lima Tahun</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="text-center">1</td>
                  <td>Minimal 90% atau 52 indikator melampaui SN Dikti.</td>
                </tr>
                <tr>
                  <td class="text-center">2</td>
                  <td>8 indikator pada Syarat Perlu Terakreditasi Unggul melampaui SN Dikti.</td>
                </tr>
                <tr>
                  <td class="text-center">3</td>
                  <td> Kualifikasi dan publikasi/luaran dosen memenuhi Syarat Perlu Terakreditasi Unggul</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card" style="border-radius: 5px; overflow: hidden;">
      <div class="card-header bg-primary text-white">
        <h6 class="mb-0">8 indikator pada Syarat Perlu Terakreditasi Unggul melampaui SN Dikti.</h6>
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
            @foreach(($standards ?? collect())->unique('nama') as $sIndex => $standard)
              @foreach($standard->elements as $eIndex => $element)

                @foreach($element->indicators->filter(fn($i) => $i->kategori === 'lock') as $iIndex => $indikator)
                  @php
                    $kode = ($sIndex + 1) . '.' . ($eIndex + 1) . '.' . ($iIndex + 1);

                    $nilai = $indikator->dokumen_nilais->first();
                    $hasil = $nilai?->hasil_nilai;

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

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card" style="border-radius: 5px; overflow: hidden;">
      <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between">
        <div>
          <h6 class="mb-0">Kualifikasi dan publikasi/luaran dosen memenuhi Syarat Perlu Terakreditasi Unggul</h6>
        </div>
      </div>
      <div class="card-body">
				<div class="row">
					<div class="col-md-12 mb-4">
						<div class="card shadow-sm">
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
															$kode = ($sIndex + 1) . '.' . ($eIndex + 1) . '.' . ($iIndex + 1);

															$nilai = $indikator->dokumen_nilais->first();
															$hasil = $nilai?->hasil_nilai;

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
              <h5 class="modal-title" id="infoModalLabel"> Kualifikasi dan publikasi/luaran dosen memenuhi Syarat Perlu Terakreditasi Unggul</h5>
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

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card" style="border-radius: 5px; overflow: hidden;">
      <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between">
        <div>
          <h6 class="mb-0">Indikator Melampaui SN Dikti</h6>
        </div>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-12 mb-4">
            <div class="card shadow-sm">
              <div class="card-body p-0">
                <div class="table-responsive">
                  @php
                    $countMemenuhi = 0;
                    $countTotal = 0;
                  @endphp

                  @foreach(($standards ?? collect()) as $standard)
                    @foreach($standard->elements as $element)
                      @foreach($element->indicators as $indikator)
                        @php
                          $countTotal++;
                          $nilai = $indikator->dokumen_nilais->first();
                          if($nilai?->hasil_nilai == 1) {
                            $countMemenuhi++;
                          }
                        @endphp
                      @endforeach
                    @endforeach
                  @endforeach

                  <table class="table table-bordered mb-0">
                    <thead>
                      <tr class="text-center bg-light">
                        <th>Total Indikator</th>
                        <th>Jumlah Memenuhi SN Dikti</th>
                        <th>Persentase</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr class="text-center">
                        <td>{{ $countTotal }}</td>
                        <td class="text-success fw-bold">{{ $countMemenuhi }}</td>
                        <td>
                          @if($countTotal > 0)
                            {{ number_format(($countMemenuhi / $countTotal) * 100, 2) }} %
                          @else
                            0 %
                          @endif
                        </td>
                      </tr>
                    </tbody>
                  </table>

                  <div class="p-3">
                    @php
                      $percentage = $countTotal > 0 ? ($countMemenuhi / $countTotal) * 100 : 0;
                      if($percentage < 50) {
                        $barClass = 'bg-danger';
                      } elseif($percentage < 75) {
                        $barClass = 'bg-warning';
                      } else {
                        $barClass = 'bg-success';
                      }

                      if($percentage >= 90 && $countMemenuhi >= 52) {
                        $kesimpulan = "Terakreditasi Unggul 5 Tahun";
                        $alertClass = "success";
                      } elseif($percentage >= 70 && $countMemenuhi >= 40) {
                        $kesimpulan = "Terakreditasi Unggul 2 Tahun";
                        $alertClass = "warning";
                      } else {
                        $kesimpulan = "Belum Terakreditasi Unggul";
                        $alertClass = "danger";
                      }
                    @endphp

                    <div class="progress" style="height: 25px;">
                      <div class="progress-bar {{ $barClass }}" role="progressbar" 
                          style="width: {{ $percentage }}%;" 
                          aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100">
                        {{ number_format($percentage, 2) }} %
                      </div>
                    </div>

                    <div class="alert alert-{{ $alertClass }} text-center fw-bold mt-3">
                      {{ $kesimpulan }} <br>
                      ({{ $countMemenuhi }} dari {{ $countTotal }} indikator, {{ number_format($percentage, 2) }}%)
                    </div>
                  </div>

                </div> 
              </div> 
            </div> 
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- <div class="row">
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
                    $nilai = $indikator->dokumen_nilais->first();
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
</div> --}}