<div class="table-responsive">
  <table id="{{ $id }}" class="col-md-12 table-striped table-hover">
    <thead class="text-bg-secondary">
      <tr>
        <th class="col-md-1 text-center" style="padding: 0.5rem;">Kode</th>
        <th class="col-md-2" style="padding: 0.5rem;">Elemen</th>
        <th class="col-md-5" style="padding: 0.5rem;">Indikator</th>
        <th class="col-md-1 text-center" style="padding: 0.5rem;">Informasi</th>
        <th class="col-md-1 text-center" style="padding: 0.5rem;">Terget</th>
        <th class="col-md-1 text-center" style="padding: 0.5rem;">Capaian</th>
        <th class="col-md-1 text-center" style="padding: 0.5rem;">Nilai</th>
        <th class="col-md-1 text-center" style="padding: 0.5rem;">evaluasi</th>
      </tr>
    </thead>
    <tbody>
        @foreach ($standards as $standard)
          <tr>
            <td class="text-center">{{ $standard->indikator_kode }}</td>
            <td>{{ $standard->elemen_nama }}</td>
            <td>{!! nl2br(e($standard->indikator_nama)) !!}</td>
            <td>
              <a href="#" data-bs-toggle="modal" data-bs-target="#infoModal{{ $standard->id }}" class="btn btn-warning btn-icon">
                <i data-feather="info"></i>
              </a>
            </td>
            <td>
              <a href="#" data-bs-toggle="modal" data-bs-target="#targetModal{{ $standard->id }}" class="btn btn-primary btn-icon">
                {{ $standard->standarTargetsBanptS1->count() }}<br>
              </a>
            </td>
            <td>
              <a href="#" data-bs-toggle="modal" data-bs-target="#capaianModal{{ $standard->id }}" class="btn btn-warning btn-icon">
                {{ $standard->standarCapaiansBanptS1->count() }}<br>
              </a>
            </td>
            <td>
              {{ optional($standard->standarNilaisBanptS1)->hasil_nilai ?? 0 }}
            </td>
            <td>
              <a href="#" data-bs-toggle="modal" data-bs-target="#editModal{{ $standard->id }}" class="btn btn-info btn-icon">
                <i data-feather="edit"></i>
              </a>
            </td>
          </tr>

          <!-- Info Modal -->
          <div class="modal fade" id="infoModal{{ $standard->id }}" tabindex="-1" aria-labelledby="infoModalLabel{{ $standard->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="infoModalLabel{{ $standard->id }}">Informasi Indikator</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <p><b>Indikator</b>: {!! nl2br(e($standard->indikator_nama)) !!}</p>
                  <br>
                  <p>{!! nl2br(e($standard->indikator_info)) !!}</p>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>

          <!-- Target Modal -->
          <div class="modal fade" id="targetModal{{ $standard->id }}" tabindex="-1" aria-labelledby="targetModalLabel{{ $standard->id }}" aria-hidden="true">
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
                  @foreach ($standard->standarTargetsBanptS1 as $target)
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

          <!-- Capaian Modal -->
          <div class="modal fade" id="capaianModal{{ $standard->id }}" tabindex="-1" aria-labelledby="capaianModalLabel{{ $standard->id }}" aria-hidden="true">
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
                  @foreach ($standard->standarCapaiansBanptS1 as $capaian)
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

          <!-- Edit Modal -->
          <div class="modal fade" id="editModal{{ $standard->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $standard->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
              <form action="{{ route('auditor.evaluasi-ami.store') }}" method="POST" enctype="multipart/form-data" id="InputAmiForm">
                @csrf
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel{{ $standard->id }}">Edit Nilai Mandiri AMI (Audit Mutu Internal)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <input type="hidden" name="ami_kodes" value="{{ $transkasis->ami_kode }}">
                    <input type="hidden" name="indikator_kodes" value="{{ $standard->indikator_kode }}">
                    <input type="hidden" name="indikator_bobots" value="{{ $standard->indikator_bobot }}">
                    <input type="hidden" name="prodis" value="{{ $prodis }}">
                    <input type="hidden" name="periodes" value="{{ $periodes }}">
                    <span>Kriteria</span>:
                    <input type="text" name="standar_namas" class="form-control mb-1" value="{{ $standard->standar_nama }}" readonly>
                    <span>Elemen</span>:
                    <input type="text" name="elemen_namas" class="form-control mb-1" value="{{ $standard->elemen_nama }}" readonly>
                    <span>Indikator</span>:
                    <textarea name="indikator_namas" class="form-control mb-1" readonly>{{ $standard->indikator_nama }}</textarea>
                    <span>Nilai Mandiri</span>:
                    <input type="number" name="nilai_mandiris" class="form-control mb-1 w-50" value="{{ optional($standard->standarNilaisBanptS1)->mandiri_nilai ?? 0 }}" readonly>
                    <span>Verifikasi Nilai Auditor</span>:
                    <input type="number" min="0" max="4" step="0.01" name="hasil_nilais" class="form-control mb-1 w-50" value="{{ optional($standard->standarNilaisBanptS1)->hasil_nilai ?? 0 }}">
                    <span>Kriteria</span> :
                    <textarea name="hasil_kriterias" class="form-control mb-1" placeholder="Deskripsikan kriteria pada indikator ini, kosongkan jika tidak ada.">{{ optional($standard->standarNilaisBanptS1)->hasil_kriteria }}</textarea>
                    <span>Deskripsi Temuan</span> :
                    <textarea name="hasil_deskripsis" class="form-control mb-1" placeholder="Deskripsikan temuan pada indikator ini, kosongkan jika tidak ada.">{{ optional($standard->standarNilaisBanptS1)->hasil_deskripsi }}</textarea>
                    <span>Jenis Temuan</span> : 
                    <select name="jenis_temuans" class="form-control mb-1" required>
                      <option {{ optional($standard->standarNilaisBanptS1)->jenis_temuan }}>{{ optional($standard->standarNilaisBanptS1)->jenis_temuan }}</option>
                      <option value="Sesuai">Sesuai</option>
                      <option value="OB">OB</option>
                      <option value="KTS">KTS</option>
                    </select>
                    <span>Akibat</span> :
                    <textarea name="hasil_akibats" class="form-control mb-1" placeholder=" Deskripsikan akar masalah pada indikator ini yang menyebabkan adanya temuan, kosongkan jika tidak ada.">{{ optional($standard->standarNilaisBanptS1)->hasil_akibat }}</textarea>
                    <span>Akar Masalah</span> :
                    <textarea name="hasil_masalahs" class="form-control mb-1" placeholder=" Deskripsikan akar masalah pada indikator ini yang menyebabkan adanya temuan, kosongkan jika tidak ada.">{{ optional($standard->standarNilaisBanptS1)->hasil_masalah }}</textarea>
                    <span>Rekomendasi</span> :
                    <textarea name="hasil_rekomendasis" class="form-control mb-1" placeholder=" Deskripsikan rekomendasi pada indikator ini yang dapat menghilangkan adanya temuan atau peluang untuk peningkatan, kosongkan jika tidak ada.">{{ optional($standard->standarNilaisBanptS1)->hasil_rekomendasi }}</textarea>
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
      {{-- @else
        <tr>
          <td colspan="7">No standards available.</td>
        </tr>
      @endif --}}
    </tbody>
  </table>
</div>
