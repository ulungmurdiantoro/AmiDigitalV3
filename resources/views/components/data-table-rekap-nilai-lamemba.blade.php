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
                  <th style="width: 60%; padding: 7px 0;">Indikator</th>
                  <th style="width: 10%; padding: 7px 0;">Informasi</th>
                  <th style="width: 12.5%; padding: 7px 0;">Memenuhi SN-Dikti/Standar LAM</th>
                  <th style="width: 12.5%; padding: 7px 0;">Deskripsi Temuan</th>
                </tr>
              </thead>
              <tbody>
                @php $nomor = 1; @endphp

                @foreach ($element->indicators as $indikator)
                  @php
                    $dokumenNilais = $indikator->dokumen_nilais;
                    $isEmptyTarget = $dokumenNilais->isEmpty();
                    $nilai = $dokumenNilais->first();
                    $hasil_nilai = $nilai?->hasil_nilai;

                    // Tentukan kelas baris
                    $rowClass = '';
                    if ($isEmptyTarget) {
                        $rowClass = 'table-warning'; // Belum ada dokumen nilai
                    } elseif ($hasil_nilai != 1) {
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
                      <button type="button" class="btn btn-warning btn-icon" data-bs-toggle="modal" data-bs-target="#editModal-indikator-{{ $indikator->id }}" title="Edit">
                        <i data-feather="award"></i>
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
                  <div class="modal fade" id="editModal-indikator-{{ $indikator->id }}" tabindex="-1" aria-labelledby="editModalLabel-indikator-{{ $indikator->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="editModalLabel-indikator-{{ $indikator->id }}">Deskripsi Temuan Audit</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                          <div class="details-container">
                            @php
                              $detailRows = [
                                ['Indikator', $indikator->nama_indikator ?? ''],
                                ['Jenis Temuan', optional($nilai)->jenis_temuan],
                                ['Deskripsi Temuan', optional($nilai)->hasil_deskripsi],
                                ['Kriteria', optional($nilai)->hasil_kriteria],
                                ['Akibat', optional($nilai)->hasil_akibat],
                                ['Akar Masalah', optional($nilai)->hasil_masalah],
                                ['Rekomendasi', optional($nilai)->hasil_rekomendasi],
                                ['Rencana Perbaikan', optional($nilai)->hasil_rencana_perbaikan],
                                ['Jadwal Perbaikan', optional($nilai)->hasil_jadwal_perbaikan],
                                ['Penanggung Jawab Perbaikan', optional($nilai)->hasil_perbaikan_penanggung],
                                ['Rencana Pencegahan', optional($nilai)->hasil_rencana_pencegahan],
                                ['Jadwal Pencegahan', optional($nilai)->hasil_jadwal_pencegahan],
                                ['Penanggung Jawab Pencegahan', optional($nilai)->hasil_rencana_penanggung],
                              ];
                            @endphp

                            @foreach ($detailRows as [$label, $value])
                              <div class="row mb-2">
                                <div class="col-4 fw-bold">{{ $label }}:</div>
                                <div class="col-8">{{ $value ?? '-' }}</div>
                              </div>
                            @endforeach
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
