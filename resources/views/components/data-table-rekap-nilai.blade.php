<div class="table-responsive">
  <table id="{{ $id }}" class="col-md-12 table-striped table-hover">
    <thead class="text-bg-secondary">
      <tr>
        <th class="col-md-1 text-center" style="padding: 0.5rem;">Kode</th>
        <th class="col-md-2" style="padding: 0.5rem;">Elemen</th>
        <th class="col-md-5" style="padding: 0.5rem;">Indikator</th>
        <th class="col-md-1 text-center" style="padding: 0.5rem;">Nilai</th>
        <th class="col-md-1 text-center" style="padding: 0.5rem;">Bobot</th>
        <th class="col-md-1 text-center" style="padding: 0.5rem;">Tertimbang</th>
        <th class="col-md-1 text-center" style="padding: 0.5rem;">Deskripsi Temuan</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($standards as $standard)
        <tr>
          <td class="text-center">{{ $standard->indikator_kode }}</td>
          <td>{{ $standard->elemen_nama }}</td>
          <td>{!! nl2br(e($standard->indikator_nama)) !!}</td>
          <td class="text-center">
            {{ optional($standard->standarNilaiS1)->hasil_nilai ?? 0 }}
          </td>
          <td class="text-center">
            {{ optional($standard->standarNilaiS1)->bobot ?? 0 }}
          </td>
          <td class="text-center">
            {{ (optional($standard->standarNilaiS1)->hasil_nilai ?? 0) * (optional($standard->standarNilaiS1)->bobot ?? 0) }}
          </td>            
          <td class="text-center">
            <a href="#" data-bs-toggle="modal" data-bs-target="#infoModal{{ $standard->id }}" class="btn btn-warning btn-icon">
              <i data-feather="info"></i>
            </a>
          </td>
        </tr>

        <div class="modal fade" id="infoModal{{ $standard->id }}" tabindex="-1" aria-labelledby="infoModalLabel{{ $standard->id }}" aria-hidden="true">
          <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="infoModalLabel{{ $standard->id }}">Deskripsi Temuan Audit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <div class="details-container">
                  <div class="row mb-2">
                    <div class="col-1 fw-bold">Kode:</div>
                    <div class="col-2">{{ $standard->indikator_kode }}</div>
                    <div class="col-2 fw-bold">Elemen:</div>
                    <div class="col-7">{{ $standard->elemen_nama }}</div>
                  </div>
                  <div class="row mb-2">
                    <div class="col-3 fw-bold">Jenis Temuan:</div>
                    <div class="col-9">{{ optional($standard->standarNilaiS1)->jenis_temuan }}</div>
                  </div>
                  <div class="row mb-2">
                    <div class="col-3 fw-bold">Deskripsi Temuan:</div>
                    <div class="col-9">{{ optional($standard->standarNilaiS1)->hasil_deskripsi }}</div>
                  </div>
                  <div class="row mb-2">
                    <div class="col-3 fw-bold">Kriteria:</div>
                    <div class="col-9">{{ optional($standard->standarNilaiS1)->hasil_kriteria }}</div>
                  </div>
                  <div class="row mb-2">
                    <div class="col-3 fw-bold">Akibat:</div>
                    <div class="col-9">{{ optional($standard->standarNilaiS1)->hasil_akibat }}</div>
                  </div>
                  <div class="row mb-2">
                    <div class="col-3 fw-bold">Akar Masalah:</div>
                    <div class="col-9">{{ optional($standard->standarNilaiS1)->hasil_masalah }}</div>
                  </div>
                  <div class="row mb-2">
                    <div class="col-3 fw-bold">Rekomendasi:</div>
                    <div class="col-9">{{ optional($standard->standarNilaiS1)->hasil_rekomendasi }}</div>
                  </div>
                  @if (optional($standard->standarNilaiS1)->jenis_temuan !== 'Sesuai')
                    <div class="row mb-2">
                      <div class="col-3 fw-bold">Rencana Perbaikan:</div>
                      <div class="col-9">{{ optional($standard->standarNilaiS1)->hasil_rencana_perbaikan }}</div>
                    </div>
                    <div class="row mb-2">
                      <div class="col-3 fw-bold">Jadwal Perbaikan:</div>
                      <div class="col-3">{{ optional($standard->standarNilaiS1)->hasil_jadwal_perbaikan }}</div>
                      <div class="col-3 fw-bold">Penanggung Jawab:</div>
                      <div class="col-3">{{ optional($standard->standarNilaiS1)->hasil_perbaikan_penanggung }}</div>
                    </div>
                    <div class="row mb-2">
                      <div class="col-3 fw-bold">Rencana Pencegahan:</div>
                      <div class="col-9">{{ optional($standard->standarNilaiS1)->hasil_rencana_pencegahan }}</div>
                    </div>
                    <div class="row mb-2">
                      <div class="col-3 fw-bold">Jadwal Pencegahan:</div>
                      <div class="col-3">{{ optional($standard->standarNilaiS1)->hasil_jadwal_pencegahan }}</div>
                      <div class="col-3 fw-bold">Penanggung Jawab:</div>
                      <div class="col-3">{{ optional($standard->standarNilaiS1)->hasil_rencana_penanggung }}</div>
                    </div>
                  @endif
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
