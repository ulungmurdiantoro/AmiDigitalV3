@props(['standards', 'importTitle', 'id', 'prodis', 'periodes', 'transaksis'])
<style>

</style>
<div class="table-responsive">
  <table id="{{ $id }}" class="col-md-12 table-striped table-hover" style="table-layout: fixed; width: 100%;">
    <thead class="text-bg-secondary">
      <tr class="text-white text-center">
        <th class="col-md-1 text-center" style="padding: 0.5rem;">Kode</th>
        <th class="col-md-2" style="padding: 0.5rem;">Elemen</th>
        <th class="col-md-4" style="padding: 0.5rem;">Indikator</th>
        <th class="col-md-1 text-center" style="padding: 0.5rem;">Informasi</th>
        <th class="col-md-1 text-center" style="padding: 0.5rem;">Terget</th>
        <th class="col-md-1 text-center" style="padding: 0.5rem;">Capaian</th>
        <th class="col-md-1 text-center" style="padding: 0.5rem;">Nilai</th>
        <th class="col-md-1 text-center" style="padding: 0.5rem;">evaluasi</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($standards as $standard)
        @foreach ($standard->indicators as $indikator)
          @php
            $nilai = $indikator->dokumen_nilais->hasil_nilai ?? 0;
            $mandiri = $indikator->dokumen_nilais->mandiri_nilai ?? 0;
            $jenis_temuan = $indikator->dokumen_nilais->jenis_temuan ?? null;
          @endphp
          <tr style="{{ $jenis_temuan !== 'Sesuai'
              ? 'background-color: rgba(140, 18, 61, .85); color: white;'
              : '' }}">

            <td class="text-center" style="vertical-align: top; padding: 5px 1px;">{{ $indikator->indikator_kode }}</td>
            <td style="vertical-align: top; padding: 5px 1px;">{{ $standard->nama }}</td>
            <td style="padding: 5px 1px;">{!! nl2br(e($indikator->nama_indikator)) !!}</td>
            <td class="text-center">
              <a href="#" data-bs-toggle="modal" data-bs-target="#infoModal{{ $indikator->id }}" class="btn btn-warning btn-icon">
                <i data-feather="info"></i>
              </a>
            </td>
            <td class="text-center">
              <a href="#" data-bs-toggle="modal" data-bs-target="#targetModal{{ $indikator->id }}" class="btn btn-primary btn-icon">
                {{ $indikator->dokumen_targets->count() }}<br>
              </a>
            </td>
            <td class="text-center">
              <a href="#" data-bs-toggle="modal" data-bs-target="#capaianModal{{ $indikator->id }}" class="btn btn-warning btn-icon">
                {{ $indikator->dokumen_capaians->count() }}<br>
              </a>
            </td>
            <td class="text-center">
              {{ $nilai }}
            </td>
            <td class="text-center">
              <a href="#" data-bs-toggle="modal" data-bs-target="#editModal-indikator-{{ $indikator->id }}" class="btn btn-info btn-icon">
                <i data-feather="edit"></i>
              </a>
            </td>
          </tr>

          <div class="modal fade" id="infoModal{{ $indikator->id }}" tabindex="-1" aria-labelledby="infoModalLabel{{ $indikator->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="infoModalLabel{{ $standard->id }}">Informasi Indikator</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <p><b>Indikator</b>: {!! nl2br(e($indikator->nama_indikator)) !!}</p>
                  <br>
                  <p>{!! nl2br(e($indikator->info)) !!}</p>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>

          <div class="modal fade" id="targetModal{{ $indikator->id }}" tabindex="-1" aria-labelledby="targetModalLabel{{ $indikator->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="targetModalLabel{{ $standard->id }}">Target / Kebutuhan Dokumen</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <div class="mb-2">
                    <b>Indikator</b> : {{ $standard->indikator_nama }}
                  </div>
                  @foreach ($indikator->dokumen_targets as $target)
                    <div>
                      {{ $loop->iteration }}. {{ $target->dokumen_nama }} - {{ $target->dokumen_tipe }} ({{ $target->dokumen_keterangan }})
                    </div>
                  @endforeach
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>

          <div class="modal fade" id="capaianModal{{ $indikator->id }}" tabindex="-1" aria-labelledby="capaianModalLabel{{ $indikator->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="capaianModalLabel{{ $standard->id }}">Capaian Dokumen</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <div class="mb-3">
                    <b>Indikator</b> : {{ $standard->indikator_nama }}
                  </div>
                  @foreach ($indikator->dokumen_capaians as $capaian)
                    <div>
                      {{ $loop->iteration }}. {{ $capaian->dokumen_nama }} - {{ $capaian->dokumen_tipe }} ({{ $capaian->dokumen_keterangan }})<br>
                      <a href="{{ $capaian->dokumen_file }}" target="_blank" class="btn btn-warning btn-icon" rel="noopener noreferrer">
                        <i data-feather="download"></i>
                      </a>
                      <span style="color:brown;"><i>Kadaluarsa pada: {{ $capaian->dokumen_kadaluarsa }}</i></span>
                    </div>
                  @endforeach
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>

          <div class="modal fade" id="editModal-indikator-{{ $indikator->id }}" tabindex="-1" aria-labelledby="nilaiModalLabel{{ $indikator->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="editModalLabel{{ $indikator->id }}">Edit Nilai Mandiri AMI (Audit Mutu Internal)</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <!-- Form Section -->
                  <form action="{{ route('auditor.koreksi-ami.store') }}" method="POST" enctype="multipart/form-data" id="InputAmiForm">
                    @csrf
                    {{-- @method('PUT') --}}
                    <input type="hidden" name="ami_kodes" value="{{ $transaksis->ami_kode }}">
                    <input type="hidden" name="indikator_ids" value="{{ $indikator->indikator_id }}">
                    <input type="hidden" name="indikator_bobots" value="{{ $indikator->indikator_bobot }}">
                    <input type="hidden" name="prodis" value="{{ $prodis }}">
                    <input type="hidden" name="periodes" value="{{ $periodes }}">
                    <div class="mb-3">
                      <label for="hasil_nilais" class="form-label">Verifikasi Nilai Auditor</label>
                      <input type="number" min="0" max="4" step="0.01" name="hasil_nilais" class="form-control w-50" value="{{ optional($indikator->dokumen_nilais)->hasil_nilai ?? 0 }}">
                    </div>
                    <div class="mb-3">
                      <label for="status_akhirs" class="form-label">Status Akhir</label>
                      <select name="status_akhirs" class="form-control w-50" required>
                        <option value="{{ optional($indikator->dokumen_nilais)->status_akhir }}">{{ optional($indikator->dokumen_nilais)->status_akhir }}</option>
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
                  <div class="details-container">
                    <div class="row mb-2">
                      <div class="col-1 fw-bold">Kode:</div>
                      <div class="col-3">{{ $indikator->indikator_id }}</div>
                      
                      <div class="col-2 fw-bold">Elemen:</div>
                      <div class="col-6">{{ $indikator->elemen_nama }}</div>
                    </div>
                    <div class="row mb-2">
                    </div>
                    <div class="row mb-2">
                      <div class="col-3 fw-bold">Nilai Mandiri:</div>
                      <div class="col-9">{{ optional($indikator->dokumen_nilais)->mandiri_nilai }}</div>
                    </div>
                    <div class="row mb-2">
                      <div class="col-3 fw-bold">Jenis Temuan:</div>
                      <div class="col-9">{{ optional($indikator->dokumen_nilais)->jenis_temuan }}</div>
                    </div>
                    <div class="row mb-2">
                      <div class="col-3 fw-bold">Deskripsi Temuan:</div>
                      <div class="col-9">{{ optional($indikator->dokumen_nilais)->hasil_deskripsi }}</div>
                    </div>
                    <div class="row mb-2">
                      <div class="col-3 fw-bold">Kriteria:</div>
                      <div class="col-9">{{ optional($indikator->dokumen_nilais)->hasil_kriteria }}</div>
                    </div>
                    <div class="row mb-2">
                      <div class="col-3 fw-bold">Akibat:</div>
                      <div class="col-9">{{ optional($indikator->dokumen_nilais)->hasil_akibat }}</div>
                    </div>
                    <div class="row mb-2">
                      <div class="col-3 fw-bold">Akar Masalah:</div>
                      <div class="col-9">{{ optional($indikator->dokumen_nilais)->hasil_masalah }}</div>
                    </div>
                    <div class="row mb-2">
                      <div class="col-3 fw-bold">Rekomendasi:</div>
                      <div class="col-9">{{ optional($indikator->dokumen_nilais)->hasil_rekomendasi }}</div>
                    </div>
                    <div class="row mb-2">
                      <div class="col-3 fw-bold">Rencana Perbaikan:</div>
                      <div class="col-9">{{ optional($indikator->dokumen_nilais)->hasil_rencana_perbaikan }}</div>
                    </div>
                    <div class="row mb-2">
                      <div class="col-3 fw-bold">Jadwal Perbaikan:</div>
                      <div class="col-3">{{ optional($indikator->dokumen_nilais)->hasil_jadwal_perbaikan }}</div>
                      <div class="col-3 fw-bold">Penanggung Jawab:</div>
                      <div class="col-3">{{ optional($indikator->dokumen_nilais)->hasil_perbaikan_penanggung }}</div>
                    </div>
                    <div class="row mb-2">
                      <div class="col-3 fw-bold">Rencana Pencegahan:</div>
                      <div class="col-9">{{ optional($indikator->dokumen_nilais)->hasil_rencana_pencegahan }}</div>
                    </div>
                    <div class="row mb-2">
                      <div class="col-3 fw-bold">Jadwal Pencegahan:</div>
                      <div class="col-3">{{ optional($indikator->dokumen_nilais)->hasil_jadwal_pencegahan }}</div>
                      <div class="col-3 fw-bold">Penanggung Jawab:</div>
                      <div class="col-3">{{ optional($indikator->dokumen_nilais)->hasil_rencana_penanggung }}</div>
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
      @endforeach
    </tbody>
  </table>
</div>
