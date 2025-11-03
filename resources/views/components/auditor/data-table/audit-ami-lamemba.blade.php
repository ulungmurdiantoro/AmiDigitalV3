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
                  <th style="width: 20%; padding: 7px 0;">Memenuhi SN-Dikti/Standar LAM</th>
                  <th style="width: 10%; padding: 7px 0;">Kelola</th>
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
                      <button type="button" class="btn btn-primary btn-icon" data-bs-toggle="modal" data-bs-target="#editModal-indikator-{{ $indikator->id }}" title="Edit">
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
                  <div class="modal fade" id="editModal-indikator-{{ $indikator->id }}" tabindex="-1" aria-labelledby="editModalLabel-indikator-{{ $indikator->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                      <form action="{{ route('auditor.evaluasi-ami.store') }}" method="POST" enctype="multipart/form-data" id="InputAmiForm-{{ $indikator->id }}">
                        @csrf
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel-indikator-{{ $indikator->id }}">Verifikasi AMI (Audit Mutu Internal)</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                            <input type="hidden" name="ami_kodes" value="{{ $transkasis->ami_kode ?? '' }}">
                            <input type="hidden" name="indikator_ids" value="{{ $indikator->id }}">
                            <input type="hidden" name="indikator_bobots" value="{{ $indikator->bobot ?? '' }}">
                            <input type="hidden" name="prodis" value="{{ $prodis ?? '' }}">
                            <input type="hidden" name="periodes" value="{{ $periodes ?? '' }}">

                            <span>Kriteria</span>:
                            <div class="form-control mb-1 bg-light">{{ $standards->nama ?? '' }}</div>
                            <input type="hidden" name="standar_namas" value="{{ $standards->nama ?? '' }}">

                            <span>Elemen</span>:
                            <div class="form-control mb-1 bg-light">{{ $element->nama ?? '' }}</div>
                            <input type="hidden" name="elemen_namas" value="{{ $element->nama ?? '' }}">

                            <span>Indikator</span>:
                            <div class="form-control mb-1 bg-light">{{ $indikator->nama_indikator }}</div>
                            <input type="hidden" name="indikator_namas" value="{{ $indikator->nama_indikator }}">

                            <div class="d-flex align-items-center mb-2">
                              <span>Memenuhi SN-Dikti/Standar LAM Menurut Prodi</span>:
                              @php $nilaiMandiri = $indikator->dokumen_nilais->first()?->mandiri_nilai; @endphp
                              <input type="checkbox" class="form-check-input ms-3"
                                value="1" {{ $nilaiMandiri == 1 ? 'checked' : '' }} disabled style="transform: scale(1.5);">
                              <input type="hidden" name="mandiri_nilais" value="{{ $nilaiMandiri == 1 ? '1' : '0' }}">
                            </div>

                            <div class="d-flex align-items-center mb-2">
                              <span>Verifikasi Auditor</span>:
                              @php $hasilNilai = optional($nilai)->hasil_nilai; @endphp
                              <input type="hidden" name="hasil_nilais" value="0">
                              <input type="checkbox" name="hasil_nilais" class="form-check-input ms-3"
                                value="1" {{ $hasilNilai == 1 ? 'checked' : '' }} style="transform: scale(1.5);">
                            </div>

                            <span>Kriteria</span>:
                            <textarea name="hasil_kriterias" class="form-control mb-1" placeholder="Deskripsikan kriteria...">{{ optional($nilai)->hasil_kriteria }}</textarea>

                            <span>Deskripsi Temuan</span>:
                            <textarea name="hasil_deskripsis" class="form-control mb-1" placeholder="Deskripsikan temuan...">{{ optional($nilai)->hasil_deskripsi }}</textarea>

                            <span>Jenis Temuan</span>:
                            <select name="jenis_temuans" class="form-control mb-1" required>
                              @php $jenis = optional($nilai)->jenis_temuan; @endphp
                              <option value="" @selected(!$jenis)>Pilih...</option>
                              <option value="Sesuai" @selected($jenis === 'Sesuai')>Sesuai</option>
                              <option value="OB" @selected($jenis === 'OB')>OB</option>
                              <option value="KTS" @selected($jenis === 'KTS')>KTS</option>
                            </select>

                            <span>Akibat</span>:
                            <textarea name="hasil_akibats" class="form-control mb-1" placeholder="Deskripsikan akibat...">{{ optional($nilai)->hasil_akibat }}</textarea>

                            <span>Akar Masalah</span>:
                            <textarea name="hasil_masalahs" class="form-control mb-1" placeholder="Deskripsikan akar masalah...">{{ optional($nilai)->hasil_masalah }}</textarea>

                            <span>Rekomendasi</span>:
                            <textarea name="hasil_rekomendasis" class="form-control mb-1" placeholder="Rekomendasi perbaikan...">{{ optional($nilai)->hasil_rekomendasi }}</textarea>
                          </div>

                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                          </div>
                        </div>
                      </form>
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
