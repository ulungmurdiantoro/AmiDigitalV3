@props(['standards', 'importTitle'])
{{-- @dd($transaksis,$prodis,$periodes) --}}
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
                                    <th style="width: 65%; padding: 7px 0;">Indikator</th>
                                    <th style="width: 10%; padding: 7px 0;">Informasi</th>
                                    {{-- <th style="width: 10%; padding: 7px 0;">Dokumen</th> --}}
                                    <th style="width: 20%; padding: 7px 0;">Memenuhi SN-Dikti/Standar LAM</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $nomor = 1; @endphp
                                @foreach ($standard->indicators as $indikator)
                                    @php $isEmptyTarget = $indikator->dokumen_nilais->isEmpty(); @endphp
                                    
                                    <tr>
                                        <td class="text-center" style="padding: 5px 0;">{{ $nomor++ }}</td>
                                        <td style="padding: 5px 0;">{!! nl2br(e($indikator->nama_indikator)) !!}</td>
                                        <td class="text-center" style="padding: 5px 0;">
                                            <button class="btn btn-warning btn-icon" data-bs-toggle="modal" data-bs-target="#infoModal{{ $indikator->id }}">
                                                <i data-feather="info"></i>
                                            </button>
                                        </td>
                                        {{-- @dd($indikator->dokumen_nilais) --}}
                                        @php $nilaiMandiri = $indikator->dokumen_nilais->first()?->mandiri_nilai; @endphp

                                        <td class="text-center" style="padding: 5px 0;">
                                            <input type="checkbox" class="check-standar" style="transform: scale(1.5);"
                                                data-id="{{ $indikator->id }}"
                                                data-bobot="{{ $indikator->bobot }}"
                                                data-ami="{{ $transaksis->ami_kode }}"
                                                data-prodi="{{ $prodis }}"
                                                data-periode="{{ $periodes }}"
                                                {{ $nilaiMandiri == 1 ? 'checked' : '' }}>
                                        </td>
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
<script>
    document.querySelectorAll('.check-standar').forEach(function(checkbox) {
        let debounceTimer;

        checkbox.addEventListener('change', function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                const indikatorId = this.dataset.id;
                const bobot = this.dataset.bobot || 0;
                const amiKode = this.dataset.ami;
                const prodi = this.dataset.prodi;
                const periode = this.dataset.periode;
                const nilaiMandiri = this.checked ? 1 : 0;

                fetch("{{ route('user.pengajuan-ami.input-ami.store') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        ami_kodes: amiKode,
                        indikator_ids: indikatorId,
                        indikator_bobots: bobot,
                        prodis: prodi,
                        periodes: periode,
                        nilai_mandiris: nilaiMandiri
                    })
                })
                .then(response => {
                    if (!response.ok) throw new Error("Gagal menyimpan");
                    return response.json();
                })
                .then(data => {
                    console.log("Berhasil disimpan:", data);
                })
                .catch(error => {
                    console.error("Gagal:", error);
                });
            }, 300); // debounce 300ms
        });
    });
</script>

