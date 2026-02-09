@props(['standards', 'importTitle', 'id', 'prodis', 'periodes', 'transaksis'])
<style>

</style>
{{-- @dd($standards) --}}
<div class="table-responsive">
  <table id="{{ $id }}" class="col-md-12 table-striped table-hover" style="table-layout: fixed; width: 100%;">
    <thead class="text-bg-secondary">
      <tr class="text-white text-center">
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
            {{ optional($indikator->dokumen_nilais)->hasil_nilai ?? 0 }}
            </td>
            <td class="text-center">
              {{ optional($indikator->dokumen_nilais)->bobot ?? 0 }}
            </td>
            <td class="text-center">
              {{ (optional($indikator->dokumen_nilais)->hasil_nilai ?? 0) * (optional($indikator->dokumen_nilais)->bobot ?? 0) }}
            </td>            
            <td class="text-center" style="padding: 5px 0;">
              <button type="button" class="btn btn-warning btn-icon" data-bs-toggle="modal" data-bs-target="#editModal-indikator-{{ $indikator->id }}" title="Edit">
                <i data-feather="award"></i>
              </button>
            </td>
          </tr>

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
                        ['Jenis Temuan', optional($indikator->dokumen_nilais)->jenis_temuan],
                        ['Deskripsi Temuan', optional($indikator->dokumen_nilais)->hasil_deskripsi],
                        ['Kriteria', optional($indikator->dokumen_nilais)->hasil_kriteria],
                        ['Akibat', optional($indikator->dokumen_nilais)->hasil_akibat],
                        ['Akar Masalah', optional($indikator->dokumen_nilais)->hasil_masalah],
                        ['Rekomendasi', optional($indikator->dokumen_nilais)->hasil_rekomendasi],
                        ['Rencana Perbaikan', optional($indikator->dokumen_nilais)->hasil_rencana_perbaikan],
                        ['Jadwal Perbaikan', optional($indikator->dokumen_nilais)->hasil_jadwal_perbaikan],
                        ['Penanggung Jawab Perbaikan', optional($indikator->dokumen_nilais)->hasil_perbaikan_penanggung],
                        ['Rencana Pencegahan', optional($indikator->dokumen_nilais)->hasil_rencana_pencegahan],
                        ['Jadwal Pencegahan', optional($indikator->dokumen_nilais)->hasil_jadwal_pencegahan],
                        ['Penanggung Jawab Pencegahan', optional($indikator->dokumen_nilais)->hasil_rencana_penanggung],
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
      @endforeach
    </tbody>
  </table>
</div>
