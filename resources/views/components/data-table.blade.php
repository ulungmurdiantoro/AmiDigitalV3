<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <p class="mb-3"></p>
        <div class="table-responsive">
          <table id="{{ $tableId ?? 'defaultTableId' }}" class="table-striped table-hover" style="font-size: 0.875rem;">
            <thead>
              <tr>
                <th class="text-bg-secondary col-md-1">Kode</th>
                <th class="text-bg-secondary col-md-2">Elemen</th>
                <th class="text-bg-secondary col-md-6">Indikator</th>
                <th class="text-bg-secondary col-md-1">Informasi</th>
                <th class="text-bg-secondary col-md-1">Kebutuhan Dokumen</th>
                <th class="text-bg-secondary col-md-1">Kelola Kebutuhan</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($data as $standard)
                <tr>
                  <td style="text-align: center;">{{ $standard->indikator_kode }}</td>
                  <td>{{ $standard->elemen_nama }}</td>
                  <td>
                    @foreach (explode("\n", $standard->indikator_nama) as $line)
                      {{ $line }}<br>
                    @endforeach
                  </td>
                  <td>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#infoModal{{ $standard->id }}" class="btn btn-warning btn-icon">
                      <i data-feather="info"></i>
                    </a>
                  </td>
                  <td>{{ $standard->standar_targets_count }}</td>
                  <td>
                    <a href="{{ route('admin.kriteria-dokumen.kelola-target', $standard->indikator_kode) }}" class="btn btn-primary btn-icon" title="Manage Target">
                      <i data-feather="plus-square"></i>
                    </a>
                  </td>
                </tr>
                <!-- Modal -->
                <x-modal :id="'infoModal' . $standard->id" title="Informasi Penilaian">
                  @foreach (explode("\n", $standard->indikator_info) as $line)
                    {{ $line }}<br>
                  @endforeach
                </x-modal>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
