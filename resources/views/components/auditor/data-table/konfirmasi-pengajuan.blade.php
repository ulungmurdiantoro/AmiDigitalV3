<div class="table-responsive">
  <table id="{{ $id }}" class="col-md-12 table-striped table-hover">
    <thead class="text-bg-secondary">
      <tr>
        <th class="col-md-1 text-center" style="padding: 0.5rem;">Kode</th>
        <th class="col-md-2" style="padding: 0.5rem;">Elemen</th>
        <th class="col-md-5" style="padding: 0.5rem;">Indikator</th>
        <th class="col-md-1 text-center" style="padding: 0.5rem;">Informasi</th>
        <th class="col-md-1 text-center" style="padding: 0.5rem;">Target</th>
        <th class="col-md-1 text-center" style="padding: 0.5rem;">Capaian</th>
        <th class="col-md-1 text-center" style="padding: 0.5rem;">Nilai</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($standards as $element)
        @foreach ($element->indicators as $indikator)
          @php
            $isSelesai = optional($transkasis)->status === 'Selesai';
            $nilaiMandiri = optional($indikator->$standarNilaisRelations)->mandiri_nilai;
          @endphp
          <tr style="{{ $isSelesai ? '' : ($nilaiMandiri < 1 ? 'background-color: rgba(140, 18, 61, .85); color: white;' : '') }}">
            <td class="text-center">{{ $indikator->indikator_kode }}</td>
            <td>{{ $element->nama }}</td>
            <td>{!! nl2br(e($indikator->nama_indikator)) !!}</td>
            <td>
              <a href="#" data-bs-toggle="modal" data-bs-target="#infoModal{{ $indikator->id }}" class="btn btn-warning btn-icon">
                <i data-feather="info"></i>
              </a>
            </td>
            <td>
              <a href="#" data-bs-toggle="modal" data-bs-target="#targetModal{{ $indikator->id }}" class="btn btn-primary btn-icon">
                {{ $indikator->$standarTargetsRelations->count() }}<br>
              </a>
            </td>
            <td>
              <a href="#" data-bs-toggle="modal" data-bs-target="#capaianModal{{ $indikator->id }}" class="btn btn-warning btn-icon">
                {{ $indikator->$standarCapaiansRelations->count() }}<br>
              </a>
            </td>
            <td class="text-center">
              {{ $nilaiMandiri ?? 0 }}
            </td>
          </tr>

          <!-- Info Modal -->
          <div class="modal fade" id="infoModal{{ $indikator->id }}" tabindex="-1" aria-labelledby="infoModalLabel{{ $indikator->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="infoModalLabel{{ $indikator->id }}">Informasi Indikator</h5>
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

          <!-- Target Modal -->
          <div class="modal fade" id="targetModal{{ $indikator->id }}" tabindex="-1" aria-labelledby="targetModalLabel{{ $indikator->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="targetModalLabel{{ $indikator->id }}">Target / Kebutuhan Dokumen</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <div class="mb-2">
                    <b>Indikator</b> : {{ $indikator->nama_indikator }}
                  </div>
                  @foreach ($indikator->$standarTargetsRelations as $target)
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
          <div class="modal fade" id="capaianModal{{ $indikator->id }}" tabindex="-1" aria-labelledby="capaianModalLabel{{ $indikator->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="capaianModalLabel{{ $indikator->id }}">Capaian Dokumen</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <div class="mb-3">
                    <b>Indikator</b> : {{ $indikator->nama_indikator }}
                  </div>
                  @foreach ($indikator->$standarCapaiansRelations as $capaian)
                    <div>
                      {{ $loop->iteration }}. {{ $capaian->dokumen_nama }} - {{ $capaian->dokumen_tipe }} ({{ $capaian->dokumen_keterangan }})<br>
                      <a href="{{ asset($capaian->dokumen_file) }}" target="_blank" class="btn btn-warning btn-icon" rel="noopener noreferrer">
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

        @endforeach
      @endforeach
    </tbody>
  </table>
</div>

