@props(['standards', 'importTitle'])

<div class="row">
    @foreach ($standards as $standard)
    {{-- @dd($standard) --}}
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
                                    {{-- <th style="width: 10%; padding: 7px 0;">Dokumen</th> --}}
                                    {{-- <th style="width: 10%; padding: 7px 0;">Kelola</th> --}}
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
                                        {{-- <td class="text-center" style="padding: 5px 0;">{{ $indikator->dokumen_targets->count() }}</td> --}}
                                        {{-- <td class="text-center" style="padding: 5px 0;">
                                            <button type="button" class="btn btn-primary btn-icon" data-bs-toggle="modal" data-bs-target="#modalEditIndikator-{{ $indikator->id }}" title="Edit">
                                                <i data-feather="edit"></i>
                                            </button>

                                            <button type="button" class="btn btn-danger btn-icon" data-bs-toggle="modal" data-bs-target="#modalDeleteIndikator-{{ $indikator->id }}" title="Hapus">
                                                <i data-feather="trash-2"></i>
                                            </button>

                                            <div class="modal fade" id="modalEditIndikator-{{ $indikator->id }}" tabindex="-1" aria-labelledby="modalEditIndikatorLabel-{{ $indikator->id }}" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <form action="{{ route('admin.kriteria-dokumen.kelola-indikator.update', $indikator->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="modalEditIndikatorLabel-{{ $indikator->id }}">Edit Indikator</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3 text-start">
                                                            <label for="nama_indikator_{{ $indikator->id }}" class="form-label">Nama Indikator</label>
                                                            <input type="text"
                                                                id="nama_indikator_{{ $indikator->id }}"
                                                                name="nama_indikator"
                                                                class="form-control"
                                                                value="{{ old('nama_indikator', $indikator->nama_indikator) }}"
                                                                required>
                                                            </div>
                                                            <div class="mb-3 text-start">
                                                            <label for="info_{{ $indikator->id }}" class="form-label">Keterangan / Informasi Lain</label>
                                                            <textarea id="info_{{ $indikator->id }}"
                                                                name="info"
                                                                class="form-control"
                                                                rows="3">{{ old('info', $indikator->info) }}</textarea>
                                                            </div>

                                                            <input type="hidden" name="elemen_id" value="{{ $indikator->elemen_id ?? ($element->id ?? null) }}">
                                                            <input type="hidden" name="importTitle" value="{{ $importTitle ?? '' }}">
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                                </div>
                                            </div>

                                            <div class="modal fade" id="modalDeleteIndikator-{{ $indikator->id }}" tabindex="-1" aria-labelledby="modalDeleteIndikatorLabel-{{ $indikator->id }}" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <form action="{{ route('admin.kriteria-dokumen.kelola-indikator.destroy', $indikator->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalDeleteIndikatorLabel-{{ $indikator->id }}">Hapus Indikator</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                                    </div>
                                                    <div class="modal-body text-start">
                                                        <p>Apakah Anda yakin ingin menghapus indikator berikut?</p>
                                                        <ul class="mb-0">
                                                        <li><strong>{{ $indikator->nama_indikator }}</strong></li>
                                                        @if($indikator->info)
                                                            <li><em>{{ $indikator->info }}</em></li>
                                                        @endif
                                                        </ul>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-danger">Hapus</button>
                                                    </div>
                                                    </form>
                                                </div>
                                                </div>
                                            </div>
                                        </td> --}}

                                    </tr>

                                    {{-- Modal Info --}}
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
