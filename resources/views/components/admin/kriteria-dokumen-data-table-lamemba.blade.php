
<div class="table-responsive">
    @props(['id', 'standards', 'showImportData', 'importTitle'])
    <table id="{{ $id }}" class="col-md-12 table-striped table-hover" style="table-layout: fixed; width: 100%;">
        <thead class="text-bg-secondary">
            <tr>
                <th class="text-white text-center" style="width: 5%">No</th>
                <th class="text-white" style="width: 15%">Elemen</th>
                <th class="text-white" style="width: 50%">Indikator</th>
                <th class="text-white text-center" style="width: 10%">Informasi</th>
                <th class="text-white text-center" style="width: 10%">Kebutuhan Dokumen</th>
                <th class="text-white text-center" style="width: 10%">Kelola Kebutuhan</th>
            </tr>
        </thead>
        <tbody>
        @php $nomor = 1; @endphp
        @foreach ($standards as $element)
            @foreach ($element->indicators as $indikator)
                @php $isEmptyTarget = $indikator->dokumen_targets->isEmpty(); @endphp
                <tr @if($isEmptyTarget) style="background-color: rgba(140, 18, 61, .85); color: white;" @endif>
                    <td class="text-center">{{ $nomor++ }}</td>
                    <td>{{ $element->nama }}</td>
                    <td class="indikator-cell">{!! nl2br(e($indikator->nama_indikator)) !!}</td>
                    <td class="text-center">
                        <button type="button" class="btn btn-warning btn-icon" data-bs-toggle="modal" data-bs-target="#infoModal{{ $indikator->id }}">
                            <i data-feather="info"></i>
                        </button>
                    </td>
                    <td class="text-center">
                        {{ $indikator->dokumen_targets->count() ?? 0 }}
                    </td>
                    <td class="text-center">
                        <a href="{{ route('admin.kriteria-dokumen.kelola-target', [
                            'importTitle' => urlencode($importTitle),
                            'indikator_id' => $indikator->id
                        ]) }}" class="btn btn-primary btn-icon" title="Kelola Target">
                            <i data-feather="plus-square"></i>
                        </a>
                    </td>
                </tr>

                <div class="modal fade" id="infoModal{{ $indikator->id }}" tabindex="-1" aria-labelledby="infoModalLabel{{ $indikator->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="infoModalLabel{{ $indikator->id }}">Informasi Indikator</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                            </div>
                            <div class="modal-body">
                                <p><strong>Indikator:</strong> {!! nl2br(e($indikator->nama_indikator)) !!}</p>
                                <hr>
                                <p>{!! nl2br(e($indikator->info)) !!}</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endforeach
        </tbody>
    </table>
</div>