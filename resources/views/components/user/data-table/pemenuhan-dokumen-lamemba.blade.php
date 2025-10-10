<div class="row">
    @foreach ($standards as $standard)
    {{-- @dd($standards) --}}
        <div class="col-md-12 mb-4">
            <div class="card shadow-sm">
                <div class="card-header fw-bold">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <button type="button" class="btn btn-warning btn-sm btn-icon" data-bs-toggle="modal" data-bs-target="#infoModal{{ $standard->id }}">
                                <i data-feather="info"></i>
                            </button>
                            <span>{{ $standard->nama }}</span>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="infoModal{{ $standard->id }}" tabindex="-1" aria-labelledby="infoModalLabel{{ $standard->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="infoModalLabel{{ $standard->id }}">Informasi Dimensi</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                            </div>
                            <div class="modal-body">
                                <p><strong>Dimensi:</strong> {!! nl2br(e($standard->nama)) !!}</p>
                                <hr>
                                <p>{!! nl2br(e($standard->deskripsi)) !!}</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table id="{{ $id }}" class="col-md-12 table-striped table-hover" style="table-layout: fixed; width: 100%;">
                            <thead class="text-bg-secondary">
                                <tr class="text-white text-center">
                                    <th style="width: 5%; padding: 7px 0;">No</th>
                                    <th style="width: 75%; padding: 7px 0;">Indikator</th>
                                    <th style="width: 10%; padding: 7px 0;">Informasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $nomor = 1; @endphp
                                @foreach ($standard->indicators as $indikator)
                                    @php $isEmptyTarget = $indikator->dokumen_targets->isEmpty(); @endphp
                                    <tr>
                                        <td class="text-center" style="padding: 5px 0;">{{ $nomor++ }}</td>
                                        <td style="padding: 5px 0;">{!! nl2br(e($indikator->nama_indikator)) !!}</td>
                                        <td class="text-center" style="padding: 5px 0;">
                                            <button class="btn btn-warning btn-icon" data-bs-toggle="modal" data-bs-target="#infoModal{{ $indikator->id }}">
                                                <i data-feather="info"></i>
                                            </button>
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
                                                    <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
