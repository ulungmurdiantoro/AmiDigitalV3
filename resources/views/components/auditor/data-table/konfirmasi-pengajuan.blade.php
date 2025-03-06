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
                {{ $standard->$standarTargetsRelations->count() }}<br>
              </a>
            </td>
            <td>
              <a href="#" data-bs-toggle="modal" data-bs-target="#capaianModal{{ $standard->id }}" class="btn btn-warning btn-icon">
                {{ $standard->$standarCapaiansRelations->count() }}<br>
              </a>
            </td>
            <td class="text-center">
              {{ optional($standard->$standarNilaisRelations)->mandiri_nilai ?? 0 }}
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

        @endforeach
      {{-- @else
        <tr>
          <td colspan="7">No standards available.</td>
        </tr>
      @endif --}}
    </tbody>
  </table>
</div>
