@props([
    'standards',
    'transaksis',
    'importTitle' => null,
    // prefix untuk id tabel agar unik per standar
    'tablePrefix' => 'tbl-standar',
])

<div class="row">
  @foreach ($standards as $element)
    {{-- @dd($element) --}}
    <div class="col-md-12 mb-4">
      <div class="card shadow-sm">
        <div class="card-header fw-bold">
          <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2">
              <button type="button" class="btn btn-warning btn-sm btn-icon" data-bs-toggle="modal" data-bs-target="#infoModal-standar-{{ $element->id }}">
                <i data-feather="info"></i>
              </button>
              <span>{{ $element->nama }}</span>
            </div>
          </div>
        </div>

        {{-- Modal Info: Standar/Dimensi --}}
        <div class="modal fade" id="infoModal-standar-{{ $element->id }}" tabindex="-1" aria-labelledby="infoModalLabel-standar-{{ $element->id }}" aria-hidden="true">
          <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="infoModalLabel-standar-{{ $element->id }}">Informasi Dimensi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
              </div>
              <div class="modal-body">
                <p><strong>Dimensi:</strong> {!! nl2br(e($element->nama)) !!}</p>
                <hr>
                <p>{!! nl2br(e($element->deskripsi)) !!}</p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
              </div>
            </div>
          </div>
        </div>

        <div class="card-body p-0">
          <div class="table-responsive">
            <table id="{{ $id }}" class="col-md-12 table-striped table-hover" style="table-layout: fixed; width: 100%;">
              <thead class="text-bg-secondary">
                <tr class="text-white text-center">
                  <th style="width: 5%;  padding: 7px 0;">No</th>
                  <th style="width: 55%; padding: 7px 0;">Indikator</th>
                  <th style="width: 10%; padding: 7px 0;">Informasi</th>
                  <th style="width: 7.5%; padding: 7px 0;">Jenis Temuan</th>
                  <th style="width: 15%; padding: 7px 0;">Memenuhi SN-Dikti/Standar LAM</th>
                  <th style="width: 7.5%; padding: 7px 0;">Kelola</th>
                </tr>
              </thead>
              <tbody>
                @php $nomor = 1; @endphp

                @foreach ($element->indicators as $indikator)
                  @php
                    $dokumenNilais = $indikator->dokumen_nilais;
                    $isEmptyTarget = $dokumenNilais->isEmpty();
                    $nilai = $dokumenNilais->first();
                    $jenis_temuan = $nilai?->jenis_temuan;
                    $hasil_nilai = $nilai?->hasil_nilai ;

                    // Tentukan kelas baris
                    $rowClass = '';
                    if ($isEmptyTarget) {
                        $rowClass = 'table-warning'; // Belum ada dokumen nilai
                    } elseif ($jenis_temuan != 'Sesuai') {
                        $rowClass = 'table-danger'; // Nilai tidak memenuhi
                    }
                  @endphp

                  <tr class="{{ $rowClass }}">
                    <td class="text-center" style="padding: 5px 0;">{{ $nomor++ }}</td>
                    <td style="padding: 5px 0;">{!! nl2br(e($indikator->nama_indikator)) !!}</td>

                    <td class="text-center" style="padding: 5px 0;">
                      <button class="btn btn-warning btn-icon" data-bs-toggle="modal" data-bs-target="#infoModal-indikator-{{ $indikator->id }}">
                        <i data-feather="info"></i>
                      </button>
                    </td>

                    <td class="text-center" style="padding: 5px 0;">
                      @if ($jenis_temuan == 'Sesuai') 
                        <span class="bg-success d-inline-flex align-items-center justify-content-center" style="padding: 7.5px 7.5px; color: white; border-radius: 10%;">
                          {{ $dokumenNilais->first()?->jenis_temuan }}
                        </span> 
                      @else
                        <span class="bg-danger d-inline-flex align-items-center justify-content-center" style="padding: 7.5px 7.5px; color: white; border-radius: 10%;">
                          {{ $dokumenNilais->first()?->jenis_temuan ?? 'KTS/OB' }}
                        </span>
                      @endif
                    </td>

                    <td class="text-center" style="padding: 5px 0;">
                      @if ($hasil_nilai == 1) 
                        <span class="bg-success d-inline-flex align-items-center justify-content-center" style="padding: 7.5px 7.5px; color: white; border-radius: 10%;">
                          <i data-feather="check-circle"></i>
                        </span> 
                      @else
                        <span class="bg-danger d-inline-flex align-items-center justify-content-center" style="padding: 7.5px 7.5px; color: white; border-radius: 10%;">
                          <i data-feather="x-circle"></i>
                        </span>
                      @endif
                    </td>
                    <td class="text-center" style="padding: 5px 0;">
                      <button type="button" class="btn btn-primary btn-icon" data-bs-toggle="modal" data-bs-target="#nilaiModal{{ $indikator->id }}" title="Edit">
                        <i data-feather="edit"></i>
                      </button>
                    </td>
                  </tr>

                  {{-- Modal Info --}}
                  <div class="modal fade" id="infoModal-indikator-{{ $indikator->id }}" tabindex="-1" aria-labelledby="infoModalLabel-indikator-{{ $indikator->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="infoModalLabel-indikator-{{ $indikator->id }}">Informasi Indikator</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                        </div>
                        <div class="modal-body">
                          <p><strong>Indikator:</strong> {!! nl2br(e($indikator->nama_indikator)) !!}</p>
                          <hr>
                          <p>{!! nl2br(e($indikator->info)) !!}</p>
                        </div>
                        <div class="modal-footer">
                          <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        </div>
                      </div>
                    </div>
                  </div>

                  {{-- Modal Edit --}}
                  <div class="modal fade" id="nilaiModal{{ $indikator->id }}" tabindex="-1" aria-labelledby="nilaiModalLabel{{ $indikator->id }}" aria-hidden="true">
										<div class="modal-dialog modal-lg modal-dialog-centered">
											<div class="modal-content">
												<div class="modal-header">
													<h5 class="modal-title" id="nilaiModalLabel{{ $indikator->id }}">Update Rencana Perbaikan & Pencegahan</h5>
													<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
												</div>

												<div class="modal-body">
													<!-- Informasi Indikator & Temuan -->
													<div class="mb-3 p-3 bg-light border rounded">
														<div class="row mb-2">
															<div class="col-md-2 fw-bold">Elemen:</div>
															<div class="col-md-10">{!! nl2br(e($element->nama)) !!}</div>
														</div>
														<div class="row mb-2">
															<div class="col-md-2 fw-bold">Indikator:</div>
															<div class="col-md-10">{!! nl2br(e($indikator->nama_indikator)) !!}</div>
														</div>
														<div class="row mb-2 align-items-center">
															<div class="col-md-3 fw-bold">Nilai Menurut Prodi:</div>
															<div class="col-md-1">
																<input type="checkbox" class="form-check-input" disabled {{ optional($nilai)->mandiri_nilai == 1 ? 'checked' : '' }}>
															</div>
															<div class="col-md-3 fw-bold">Verifikasi Auditor:</div>
															<div class="col-md-1">
																<input type="checkbox" class="form-check-input" disabled {{ optional($nilai)->hasil_nilai == 1 ? 'checked' : '' }}>
															</div>
														</div>
														<div class="row mb-2">
															<div class="col-md-3 fw-bold">Status Akhir:</div>
															<div class="col-md-9"><span class="badge bg-info text-dark">{{ optional($nilai)->status_akhir ?? '-' }}</span></div>
														</div>
														<div class="row mb-2">
															<div class="col-md-3 fw-bold">Jenis Temuan:</div>
															<div class="col-md-9">{{ optional($nilai)->jenis_temuan }}</div>
														</div>
														<div class="row mb-2">
															<div class="col-md-3 fw-bold">Deskripsi:</div>
															<div class="col-md-9">{{ optional($nilai)->hasil_deskripsi }}</div>
														</div>
														<div class="row mb-2">
															<div class="col-md-3 fw-bold">Kriteria:</div>
															<div class="col-md-9">{{ optional($nilai)->hasil_kriteria }}</div>
														</div>
														<div class="row mb-2">
															<div class="col-md-3 fw-bold">Akibat:</div>
															<div class="col-md-9">{{ optional($nilai)->hasil_akibat }}</div>
														</div>
														<div class="row mb-2">
															<div class="col-md-3 fw-bold">Akar Masalah:</div>
															<div class="col-md-9">{{ optional($nilai)->hasil_masalah }}</div>
														</div>
														<div class="row mb-2">
															<div class="col-md-3 fw-bold">Rekomendasi:</div>
															<div class="col-md-9">{{ optional($nilai)->hasil_rekomendasi }}</div>
														</div>
													</div>

													<!-- Form Update Perbaikan & Pencegahan -->
													<form action="{{ route('auditor.koreksi-ami.store') }}" method="POST" id="UpdateAmiForm{{ $indikator->id }}">
														@csrf
														<input type="hidden" name="ami_kodes" value="{{ $transaksis->ami_kode }}">
														<input type="hidden" name="indikator_ids" value="{{ $indikator->id }}">
														<input type="hidden" name="indikator_bobots" value="{{ $indikator->bobot ?? 0 }}">
														<input type="hidden" name="prodis" value="{{ $prodis }}">
														<input type="hidden" name="periodes" value="{{ $periodes }}">
														<input type="hidden" name="jenis_temuans" value="{{ optional($nilai)->jenis_temuan ?? 'Tidak ada' }}">

														<div class="mb-3">
															<h6 class="fw-bold text-primary">Rencana Perbaikan</h6>
															<textarea name="hasil_rencana_perbaikan" class="form-control mb-2" rows="2">{{ old('hasil_rencana_perbaikan', optional($nilai)->hasil_rencana_perbaikan) }}</textarea>
															<div class="row">
																<div class="col-md-4">
																	<input type="date" name="hasil_jadwal_perbaikan" class="form-control" value="{{ old('hasil_jadwal_perbaikan', optional($nilai)->hasil_jadwal_perbaikan) }}">
																</div>
																<div class="col-md-8">
																	<input type="text" name="hasil_perbaikan_penanggung" class="form-control" placeholder="Penanggung Jawab" value="{{ old('hasil_perbaikan_penanggung', optional($nilai)->hasil_perbaikan_penanggung) }}">
																</div>
															</div>
														</div>

														<div class="mb-3">
															<h6 class="fw-bold text-primary">Rencana Pencegahan</h6>
															<textarea name="hasil_rencana_pencegahan" class="form-control mb-2" rows="2">{{ old('hasil_rencana_pencegahan', optional($nilai)->hasil_rencana_pencegahan) }}</textarea>
															<div class="row">
																<div class="col-md-4">
																	<input type="date" name="hasil_jadwal_pencegahan" class="form-control" value="{{ old('hasil_jadwal_pencegahan', optional($nilai)->hasil_jadwal_pencegahan) }}">
																</div>
																<div class="col-md-8">
																	<input type="text" name="hasil_rencana_penanggung" class="form-control" placeholder="Penanggung Jawab" value="{{ old('hasil_rencana_penanggung', optional($nilai)->hasil_rencana_penanggung) }}">
																</div>
															</div>
														</div>

														<button type="submit" class="btn btn-success btn-sm">
															<i class="fas fa-save"></i> Simpan Perubahan
														</button>
													</form>
												</div>

												<div class="modal-footer">
													<button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
												</div>
											</div>
										</div>
									</div>


                @endforeach

              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  @endforeach
</div>

@push('custom-scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    if (window.feather) { feather.replace(); }
  });
</script>
@endpush
