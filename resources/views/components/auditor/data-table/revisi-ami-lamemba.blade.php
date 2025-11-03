@props([
    'standards',
    'importTitle' => null,
    // prefix untuk id tabel agar unik per standar
    'tablePrefix' => 'tbl-standar',
])

<div class="row">
  @foreach ($elements as $element)
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
                          <h5 class="modal-title" id="nilaiModalLabel{{ $indikator->id }}">Verifikasi AMI (Audit Mutu Internal)</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                          <!-- Form Section -->
                          <form action="{{ route('auditor.koreksi-ami.store') }}" method="POST" enctype="multipart/form-data" id="InputAmiForm{{ $indikator->id }}">
                            @csrf

                            <!-- Hidden Inputs -->
                            <input type="hidden" name="ami_kodes" value="{{ $transkasis->ami_kode }}">
                            <input type="hidden" name="indikator_ids" value="{{ $indikator->id }}">
                            <input type="hidden" name="indikator_bobots" value="{{ $indikator->bobot ?? 0 }}">
                            <input type="hidden" name="prodis" value="{{ $prodis }}">
                            <input type="hidden" name="periodes" value="{{ $periodes }}">
                            <input type="hidden" name="jenis_temuans" value="{{ optional($nilai)->jenis_temuan ?? 'Tidak ada' }}">

                            <!-- Nilai Auditor -->
                            <div class="mb-3">
                              <label for="hasil_nilais" class="form-label">Verifikasi Nilai Auditor</label>
                              @php $hasilNilai = optional($nilai)->hasil_nilai; @endphp
                              <input type="hidden" name="hasil_nilais" value="0">
                              <input type="checkbox" name="hasil_nilais" class="form-check-input ms-3"
                                value="1" {{ $hasilNilai == 1 ? 'checked' : '' }} style="transform: scale(1.5);">
                            </div>

                            <!-- Status Akhir -->
                            <div class="mb-3">
                              <label for="status_akhirs" class="form-label">Status Akhir</label>
                              <select name="status_akhirs" class="form-control w-50" required>
                                <option selected disabled>{{ optional($nilai)->status_akhir ?? 'Pilih status' }}</option>
                                <option value="Belum ditindaklanjuti">Belum ditindaklanjuti</option>
                                <option value="Proses ditindaklanjuti">Proses ditindaklanjuti</option>
                                <option value="Sudah ditindaklanjuti">Sudah ditindaklanjuti</option>
                              </select>
                            </div>

                            <button type="submit" class="btn btn-info btn-sm">
                              <i class="fas fa-check-circle"></i> Submit
                            </button>
                          </form>

                          <hr class="my-4">

                          <!-- Detail Section -->
                          <div class="details-container p-3 bg-light border rounded">
                            <div class="mb-3">
                              <h6 class="fw-bold text-primary">Informasi Indikator</h6>
                              <div class="row mb-2">
                                <div class="col-md-2 fw-bold">Elemen:</div>
                                <div class="col-md-10">{!! nl2br(e($element->nama)) !!}</div>
                              </div>
                              <div class="row mb-2">
                                <div class="col-md-2 fw-bold">Indikator:</div>
                                <div class="col-md-10">{!! nl2br(e($indikator->nama_indikator)) !!}</div>
                              </div>
                            </div>

                            <div class="mb-3">
                              <h6 class="fw-bold text-primary">Nilai & Temuan</h6>
                              <div class="row mb-2">
                                <div class="col-md-3 fw-bold">Nilai Menurut Prodi:</div>
                                <div class="col-md-9">{{ optional($nilai)->mandiri_nilai }}</div>
                              </div>
                              <div class="row mb-2">
                                <div class="col-md-3 fw-bold">Jenis Temuan:</div>
                                <div class="col-md-9">{{ optional($nilai)->jenis_temuan }}</div>
                              </div>
                              <div class="row mb-2">
                                <div class="col-md-3 fw-bold">Deskripsi Temuan:</div>
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

                            <div class="mb-3">
                              <h6 class="fw-bold text-primary">Rencana Perbaikan</h6>
                              <div class="row mb-2">
                                <div class="col-md-3 fw-bold">Rencana:</div>
                                <div class="col-md-9">{{ optional($nilai)->hasil_rencana_perbaikan }}</div>
                              </div>
                              <div class="row mb-2">
                                <div class="col-md-3 fw-bold">Jadwal:</div>
                                <div class="col-md-3">{{ optional($nilai)->hasil_jadwal_perbaikan }}</div>
                                <div class="col-md-3 fw-bold">Penanggung Jawab:</div>
                                <div class="col-md-3">{{ optional($nilai)->hasil_perbaikan_penanggung }}</div>
                              </div>
                            </div>

                            <div class="mb-3">
                              <h6 class="fw-bold text-primary">Rencana Pencegahan</h6>
                              <div class="row mb-2">
                                <div class="col-md-3 fw-bold">Rencana:</div>
                                <div class="col-md-9">{{ optional($nilai)->hasil_rencana_pencegahan }}</div>
                              </div>
                              <div class="row mb-2">
                                <div class="col-md-3 fw-bold">Jadwal:</div>
                                <div class="col-md-3">{{ optional($nilai)->hasil_jadwal_pencegahan }}</div>
                                <div class="col-md-3 fw-bold">Penanggung Jawab:</div>
                                <div class="col-md-3">{{ optional($nilai)->hasil_rencana_penanggung }}</div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
