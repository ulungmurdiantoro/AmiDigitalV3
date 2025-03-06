<div class="row">
	<div class="col-md-12 grid-margin stretch-card">
		<div class="card" style="border-radius: 5px; overflow: hidden;">
			<div class="card-header bg-primary text-white">
				<h6 class="mb-0">Syarat Perlu Peringkat Unggul</h6>
			</div>
			<div class="card-body">
				<table class="table table-bordered">
					<tr>
						<th class="col-md-1">No</th>
						<th class="col-md-3">Elemen</th>
						<th class="col-md-3">Indikator</th>
						<th class="col-md-3">Kriteria</th>
						<th class="col-md-2">Status</th>
					</tr>
					@foreach ($tablePeringkatUngguls as $index => $value)
						<tr>
							<td>{{ $index + 1 }}.</td>
							<td>{!! $value['elemen'] !!}</td>
							<td>{!! $value['indikator'] !!}</td>
							<td>{!! $value['kriteria'] !!}</td>
							<td>{{ $value['status'] }}</td>
						</tr>
					@endforeach
				</table>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12 grid-margin stretch-card">
		<div class="card" style="border-radius: 5px; overflow: hidden;">
			<div class="card-header bg-primary text-white">
				<h6 class="mb-0">Syarat Perlu Terakreditasi</h6>
			</div>
			<div class="card-body">
				<table class="table table-bordered">
					<tr>
						<th>Nilai Evaluasi Diri</th>
						<th>Syarat Perlu Unggul</th>
						<th>Masa Berlaku</th>
					</tr>
					<tr>
						<td>{{ $totals }}</td>
						<td>{{ $h2s }}</td>
						<td>{{ $h3s }}</td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>
