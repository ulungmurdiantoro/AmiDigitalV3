<div class="table-responsive">
  <table id="{{ $id }}" class="col-md-12 table-striped table-hover">
    <thead class="text-bg-secondary">
      <tr>
        <th class="col-md-1 text-center">Kode</th>
        <th class="col-md-2">Elemen</th>
        <th class="col-md-6">Indikator</th>
        <th class="col-md-1 text-center">Informasi</th>
        <th class="col-md-1 text-center">Kebutuhan Dokumen</th>
        <th class="col-md-1 text-center">Kelola Kebutuhan</th>
      </tr>
    </thead>
    {{-- @dd($standarTargetsRelations) --}}
    <tbody>
      @foreach ($standards as $standard)
        <tr style="{{ $standard->{$standarTargetsRelations}->count() === 0 ? 'background-color: rgba(140, 18, 61, .85); color: white;' : '' }}">
          <td class="text-center">{{ $standard->indikator_kode }}</td>
          <td>{{ $standard->elemen_nama }}</td>
          <td>{!! nl2br(e($standard->indikator_nama)) !!}</td>
          <td class="text-center">
              <a href="#" data-bs-toggle="modal" data-bs-target="#infoModal{{ $standard->id }}" class="btn btn-warning btn-icon">
                  <i data-feather="info"></i>
              </a>
          </td>
          <td class="text-center">
              {{ $standard->{$standarTargetsRelations}->count() }}
          </td>
          <td class="text-center">
              <a href="{{ route('admin.kriteria-dokumen.kelola-target', ['importTitle' => urlencode($importTitle), 'indikator_kode' => $standard->indikator_kode]) }}" class="btn btn-primary btn-icon" title="Manage Target">
                  <i data-feather="plus-square"></i>
              </a>
          </td>
        </tr>
        <!-- Modal for Indikator Info -->
        <div class="modal fade" id="infoModal{{ $standard->id }}" tabindex="-1" aria-labelledby="exampleModalLabel{{ $standard->id }}" aria-hidden="true">
          <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel{{ $standard->id }}">Informasi Indikator</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <p><b>Indikator:</b> {!! nl2br(e($standard->indikator_nama)) !!}</p>
                <br>
                <p>{!! nl2br(e($standard->indikator_info)) !!}</p>
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
