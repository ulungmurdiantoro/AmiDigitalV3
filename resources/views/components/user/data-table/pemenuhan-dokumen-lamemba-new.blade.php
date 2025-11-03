@props(['standards', 'importTitle'])

<div class="row">
	<div class="col-md-12 mb-4">
		<div class="card shadow-sm">
			<div class="card-body p-0">
				<div class="table-responsive">
					<table id="{{ $id }}" class="col-md-12 table-striped table-hover" style="table-layout: fixed; width: 100%;">
						<thead class="text-bg-secondary">
							<tr class="text-white text-center">
								<th style="width: 5%; padding: 7px 0;">No</th>
								<th style="width: 65%; padding: 7px 0;">Bukti Dokumen</th>
								<th style="width: 15%; padding: 7px 0;">Informasi</th>
								<th style="width: 15%; padding: 7px 0;">Kelola</th>
							</tr>
						</thead>
						<tbody>
							@php $nomor = 1; @endphp
							@foreach ($standards as $indikator)
								@php $hasDokumen = $indikator->dokumenCapaian->isEmpty(); @endphp
								<tr @if($hasDokumen) style="background-color: rgba(140, 18, 61, .85); color: white;" @endif>
									<td class="text-center" style="padding: 5px 0;">{{ $nomor++ }}</td>
									<td style="padding: 5px 0;">{!! nl2br(e($indikator->nama)) !!}</td>
									<td class="text-center" style="padding: 5px 0;">
										<button class="btn btn-warning btn-icon" data-bs-toggle="modal" data-bs-target="#infoModal{{ $indikator->id }}">
											<i data-feather="info"></i>
										</button>
									</td>
									<td class="text-center" style="padding: 5px 0;">
										<button type="button" class="btn btn-primary btn-icon" data-bs-toggle="modal" data-bs-target="#modalUpload-{{ $indikator->id }}" title="upload">
											<i data-feather="upload"></i>
										</button>

										<div class="modal fade" id="modalUpload-{{ $indikator->id }}" tabindex="-1" aria-labelledby="modalUploadLabel-{{ $indikator->id }}" aria-hidden="true">
											<div class="modal-dialog modal-xl modal-dialog-centered">
												<div class="modal-content">
													<form action="{{ route('user.pemenuhan-dokumen.input-bukti.store') }}" method="POST" enctype="multipart/form-data">
														@csrf
														<input type="hidden" name="indikator_id" value="{{ $indikator->id }}">
														<input type="hidden" name="dokumen_nama" value="{{ $indikator->nama }}">

														<div class="modal-header">
															<h5 class="modal-title" id="modalUploadLabel-{{ $indikator->id }}">Upload Dokumen Standar Capaian</h5>
															<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
														</div>

														<div class="modal-body">
															<div class="row g-4">
																<div class="col-md-6">
																	<label for="dokumen_file" class="form-label">File Dokumen</label>
																	<input type="file" name="dokumen_file" id="dokumen_file" class="form-control" accept=".pdf,.doc,.docx,.xls,.xlsx" required>
																</div>

																<div class="col-md-6">
																	<label for="periode" class="form-label">Periode</label>
																	<input type="text" id="periode" name="periode" class="form-control" placeholder="Contoh: 2024/2025" value="2024/2025" required>
																</div>

																<div class="col-md-6">
																	<label for="dokumen_kadaluarsa" class="form-label">Tanggal Kadaluarsa</label>
																	<input type="date" id="dokumen_kadaluarsa" name="dokumen_kadaluarsa" class="form-control" placeholder="Pilih Tanggal">
																</div>


																<div class="col-md-6">
																	<label for="informasi" class="form-label">Informasi Tambahan</label>
																	<textarea id="informasi" name="informasi" class="form-control" rows="3" placeholder="Informasi Tambahan jika ada...">{{ old('informasi') }}</textarea>
																</div>
															</div>
														</div>

														<div class="modal-footer">
															<button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
															<button type="submit" class="btn btn-success">Upload Dokumen</button>
														</div>
													</form>
												</div>
											</div>
										</div>
										<div class="modal fade" id="infoModal{{ $indikator->id }}" tabindex="-1" aria-labelledby="infoModalLabel{{ $indikator->id }}" aria-hidden="true">
											<div class="modal-dialog modal-lg modal-dialog-centered">
												<div class="modal-content">
													<div class="modal-header">
														<h5 class="modal-title" id="infoModalLabel{{ $indikator->id }}">Informasi Bukti Dokumen</h5>
														<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
													</div>

													<div class="modal-body text-start" style="color: black;">
														<p class="mb-2"><strong>Nama:</strong> {{ $indikator->nama ?? '—' }}</p>
														<p class="mb-0"><strong>Deskripsi:</strong></p>
														<p>{!! nl2br(e($indikator->deskripsi ?? '—')) !!}</p>

														@php $dokumens = $indikator->dokumenCapaian ?? collect(); @endphp

														@if($dokumens->count())
															<hr>
															<div class="d-flex align-items-center justify-content-between mb-2">
																<h6 class="mb-0">Dokumen Terunggah</h6>
															</div>

															<ul class="list-group mb-3">
																@foreach($dokumens as $dokumen)
																	<li class="list-group-item d-flex justify-content-between align-items-start">
																		<div class="ms-2 me-auto">
																			<div class="fw-bold">{{ $dokumen->dokumen_nama ?? '—' }}</div>
																			<small class="text-muted d-block">
																				Periode: {{ $dokumen->periode ?? '—' }}
																				@if(!empty($dokumen->dokumen_kadaluarsa))
																					| Kadaluarsa: {{ $dokumen->dokumen_kadaluarsa }}
																				@endif
																			</small>
																			@if(!empty($dokumen->informasi))
																				<small class="d-block">Informasi: {{ $dokumen->informasi }}</small>
																			@endif
																			@if(!empty($dokumen->dokumen_file))
																				<a href="{{ asset($dokumen->dokumen_file) }}" target="_blank" class="text-primary">Lihat Dokumen</a>
																			@endif
																		</div>

																		{{-- Trigger ke modal konfirmasi delete yang sudah Anda punya --}}
																		<button
																			type="button"
																			class="btn btn-sm btn-danger"
																			data-bs-toggle="modal"
																			data-bs-target="#hapusModal{{ $dokumen->id }}"
																			title="Hapus dokumen"
																		>
																			<i data-feather="trash-2"></i>
																		</button>
																	</li>
																@endforeach
															</ul>
														@else
															<p class="text-muted mb-0">Belum ada dokumen yang diunggah untuk indikator ini.</p>
														@endif
													</div>

													<div class="modal-footer">
														<button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
													</div>
												</div>
											</div>
										</div>

										@foreach ($indikator->dokumenCapaian as $dokumen)
											<div class="modal fade" id="hapusModal{{ $dokumen->id }}" tabindex="-1" aria-labelledby="hapusModalLabel{{ $dokumen->id }}" aria-hidden="true">
												<div class="modal-dialog modal-dialog-centered">
													<div class="modal-content">
														<div class="modal-header">
															<h5 class="modal-title" id="hapusModalLabel{{ $dokumen->id }}">Konfirmasi Hapus</h5>
															<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
														</div>
														<div class="modal-body">
															Apakah kamu yakin ingin menghapus dokumen <strong>{{ $dokumen->dokumenCapaian_nama }}</strong>?
														</div>
														<div class="modal-footer">
															<form action="{{ route('user.pemenuhan-dokumen.input-capaian.destroy', $dokumen->id) }}" method="POST">
																@csrf
																@method('DELETE')
																<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
																<button type="submit" class="btn btn-danger">Hapus</button>
															</form>
														</div>
													</div>
												</div>
											</div>
										@endforeach
									</td>
								</tr>
								
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
