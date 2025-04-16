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
        <th class="col-md-1 text-center" style="padding: 0.5rem;">Evaluasi</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($standards as $standard)
        <tr style="{{
          empty(optional($standard->$standarNilaisRelations)->status_akhir)
            ? (
              (!empty(optional($standard->$standarNilaisRelations)->hasil_rencana_perbaikan)
              && !empty(optional($standard->$standarNilaisRelations)->hasil_rencana_pencegahan))
                ? 'background-color: #be7456; color: white;'
                : 'background-color: rgba(140, 18, 61, .85); color: white;'
            )
          : ''
          }}">
    
          <td class="text-center">{{ $standard->indikator_kode }}</td>
          <td>{{ $standard->elemen_nama }}</td>
          <td>{!! nl2br(e($standard->indikator_nama)) !!}</td>
          <td class="text-center">
            <a href="#" data-bs-toggle="modal" data-bs-target="#infoModal{{ $standard->id }}" class="btn btn-warning btn-icon">
              <i data-feather="info"></i>
            </a>
          </td>
          <td class="text-center">
            <a href="#" data-bs-toggle="modal" data-bs-target="#targetModal{{ $standard->id }}" class="btn btn-primary btn-icon">
              {{ $standard->$standarTargetsRelations->count() }}<br>
            </a>
          </td>
          <td class="text-center">
            <a href="#" data-bs-toggle="modal" data-bs-target="#capaianModal{{ $standard->id }}" class="btn btn-warning btn-icon">
              {{ $standard->$standarCapaiansRelations->count() }}<br>
            </a>
          </td>
          <td class="text-center">
            {{ optional($standard->$standarNilaisRelations)->hasil_nilai ?? 0 }}
          </td>
          <td class="text-center">
            <a href="#" data-bs-toggle="modal" data-bs-target="#nilaiModal{{ $standard->id }}" class="btn btn-info btn-icon">
              <i data-feather="edit"></i>
            </a>
          </td>
        </tr>

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
                @foreach ($standard->$standarTargetsRelations as $target)
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
                @foreach ($standard->$standarCapaiansRelations as $capaian)
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

        <div class="modal fade" id="nilaiModal{{ $standard->id }}" tabindex="-1" aria-labelledby="nilaiModalLabel{{ $standard->id }}" aria-hidden="true">
          <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel{{ $standard->id }}">Edit Nilai Mandiri AMI (Audit Mutu Internal)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <!-- Form Section -->
                <form action="{{ route('auditor.koreksi-ami.store') }}" method="POST" enctype="multipart/form-data" id="InputAmiForm">
                  @csrf
                  {{-- @method('PUT') --}}
                  <input type="hidden" name="ami_kodes" value="{{ $transkasis->ami_kode }}">
                  <input type="hidden" name="indikator_kodes" value="{{ $standard->indikator_kode }}">
                  <input type="hidden" name="indikator_bobots" value="{{ $standard->indikator_bobot }}">
                  <input type="hidden" name="prodis" value="{{ $prodis }}">
                  <input type="hidden" name="periodes" value="{{ $periodes }}">
                  <div class="mb-3">
                    <label for="hasil_nilais" class="form-label">Verifikasi Nilai Auditor</label>
                    <input type="number" min="0" max="4" step="0.01" name="hasil_nilais" class="form-control w-50" value="{{ optional($standard->$standarNilaisRelations)->hasil_nilai ?? 0 }}">
                  </div>
                  <div class="mb-3">
                    <label for="status_akhirs" class="form-label">Status Akhir</label>
                    <select name="status_akhirs" class="form-control w-50" required>
                      <option value="{{ optional($standard->$standarNilaisRelations)->status_akhir }}">{{ optional($standard->$standarNilaisRelations)->status_akhir }}</option>
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
                    <div class="col-3">{{ $standard->indikator_kode }}</div>
                    
                    <div class="col-2 fw-bold">Elemen:</div>
                    <div class="col-6">{{ $standard->elemen_nama }}</div>
                  </div>
                  <div class="row mb-2">
                  </div>
                  <div class="row mb-2">
                    <div class="col-3 fw-bold">Nilai Mandiri:</div>
                    <div class="col-9">{{ optional($standard->$standarNilaisRelations)->mandiri_nilai }}</div>
                  </div>
                  <div class="row mb-2">
                    <div class="col-3 fw-bold">Jenis Temuan:</div>
                    <div class="col-9">{{ optional($standard->$standarNilaisRelations)->jenis_temuan }}</div>
                  </div>
                  <div class="row mb-2">
                    <div class="col-3 fw-bold">Deskripsi Temuan:</div>
                    <div class="col-9">{{ optional($standard->$standarNilaisRelations)->hasil_deskripsi }}</div>
                  </div>
                  <div class="row mb-2">
                    <div class="col-3 fw-bold">Kriteria:</div>
                    <div class="col-9">{{ optional($standard->$standarNilaisRelations)->hasil_kriteria }}</div>
                  </div>
                  <div class="row mb-2">
                    <div class="col-3 fw-bold">Akibat:</div>
                    <div class="col-9">{{ optional($standard->$standarNilaisRelations)->hasil_akibat }}</div>
                  </div>
                  <div class="row mb-2">
                    <div class="col-3 fw-bold">Akar Masalah:</div>
                    <div class="col-9">{{ optional($standard->$standarNilaisRelations)->hasil_masalah }}</div>
                  </div>
                  <div class="row mb-2">
                    <div class="col-3 fw-bold">Rekomendasi:</div>
                    <div class="col-9">{{ optional($standard->$standarNilaisRelations)->hasil_rekomendasi }}</div>
                  </div>
                  <div class="row mb-2">
                    <div class="col-3 fw-bold">Rencana Perbaikan:</div>
                    <div class="col-9">{{ optional($standard->$standarNilaisRelations)->hasil_rencana_perbaikan }}</div>
                  </div>
                  <div class="row mb-2">
                    <div class="col-3 fw-bold">Jadwal Perbaikan:</div>
                    <div class="col-3">{{ optional($standard->$standarNilaisRelations)->hasil_jadwal_perbaikan }}</div>
                    <div class="col-3 fw-bold">Penanggung Jawab:</div>
                    <div class="col-3">{{ optional($standard->$standarNilaisRelations)->hasil_perbaikan_penanggung }}</div>
                  </div>
                  <div class="row mb-2">
                    <div class="col-3 fw-bold">Rencana Pencegahan:</div>
                    <div class="col-9">{{ optional($standard->$standarNilaisRelations)->hasil_rencana_pencegahan }}</div>
                  </div>
                  <div class="row mb-2">
                    <div class="col-3 fw-bold">Jadwal Pencegahan:</div>
                    <div class="col-3">{{ optional($standard->$standarNilaisRelations)->hasil_jadwal_pencegahan }}</div>
                    <div class="col-3 fw-bold">Penanggung Jawab:</div>
                    <div class="col-3">{{ optional($standard->$standarNilaisRelations)->hasil_rencana_penanggung }}</div>
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
