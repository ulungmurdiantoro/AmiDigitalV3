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
          @endphp
          <tr style="{{ $nilai == 0
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
                    <input type="hidden" name="ami_kodes" value="{{ $transaksis->ami_kode ?? '' }}">
                    <input type="hidden" name="indikator_ids" value="{{ $indikator->id }}">
                    <input type="hidden" name="indikator_bobots" value="{{ $indikator->bobot ?? '' }}">
                    <input type="hidden" name="prodis" value="{{ $prodis ?? '' }}">
                    <input type="hidden" name="periodes" value="{{ $periodes ?? '' }}">

                    <span>Kriteria</span>:
                    <div class="form-control mb-1 bg-light">{{ $standard->standard->nama ?? '' }}</div>
                    <input type="hidden" name="elemen_namas" value="{{ $standard->standard->nama ?? '' }}">

                    <span>Elemen</span>:
                    <div class="form-control mb-1 bg-light">{{ $standard->nama ?? '' }}</div>
                    <input type="hidden" name="standar_namas" value="{{ $standard->nama ?? '' }}">

                    <span>Indikator</span>:
                    <div class="form-control mb-1 bg-light">{{ $indikator->nama_indikator }}</div>
                    <input type="hidden" name="indikator_namas" value="{{ $indikator->nama_indikator }}">

                    <span>Nilai Mandiri</span>:
                    <input type="number" name="mandiri_nilais" class="form-control mb-1 w-50" value="{{ $mandiri ?? 0 }}" readonly>
                    <span>Verifikasi Nilai Auditor</span>:
                    <input type="number" min="0" max="4" step="0.01" name="hasil_nilais" class="form-control mb-1 w-50" value="{{ optional($standard->dokumen_nilais)->hasil_nilai }}">
                    
                    <span>Kriteria</span>:
                    <textarea name="hasil_kriterias" class="form-control mb-1" placeholder="Deskripsikan kriteria...">{{ optional($standard->dokumen_nilais)->hasil_kriteria }}</textarea>

                    <span>Deskripsi Temuan</span>:
                    <textarea name="hasil_deskripsis" class="form-control mb-1" placeholder="Deskripsikan temuan...">{{ optional($standard->dokumen_nilais)->hasil_deskripsi }}</textarea>

                    <span>Jenis Temuan</span>:
                    <select name="jenis_temuans" class="form-control mb-1" required>
                      @php $jenis = optional($standard->dokumen_nilais)->jenis_temuan; @endphp
                      <option value="" @selected(!$jenis)>Pilih...</option>
                      <option value="Sesuai" @selected($jenis === 'Sesuai')>Sesuai</option>
                      <option value="OB" @selected($jenis === 'OB')>OB</option>
                      <option value="KTS" @selected($jenis === 'KTS')>KTS</option>
                    </select>

                    <span>Akibat</span>:
                    <textarea name="hasil_akibats" class="form-control mb-1" placeholder="Deskripsikan akibat...">{{ optional($standard->dokumen_nilais)->hasil_akibat }}</textarea>

                    <span>Akar Masalah</span>:
                    <textarea name="hasil_masalahs" class="form-control mb-1" placeholder="Deskripsikan akar masalah...">{{ optional($standard->dokumen_nilais)->hasil_masalah }}</textarea>

                    <span>Rekomendasi</span>:
                    <textarea name="hasil_rekomendasis" class="form-control mb-1" placeholder="Rekomendasi perbaikan...">{{ optional($standard->dokumen_nilais)->hasil_rekomendasi }}</textarea>
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
      @endforeach
    </tbody>
  </table>
</div>
