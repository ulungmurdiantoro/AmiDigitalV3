<div class="row">
	<div class="col-md-12 grid-margin stretch-card">
		<div class="card" style="border-radius: 5px; overflow: hidden;">
			<div class="card-header bg-primary text-white">
				<h6 class="mb-0">Hasil Forcasting</h6>
			</div>
			<div class="card-body">
				<table class="table table-bordered">
					<tr>
						<th class="col-md-1">No</th>
						<th class="col-md-9">Syarat</th>
						<th class="col-md-2">Status</th>
					</tr>
					@foreach ($tableTerakreditasis as $index => $value)
						<tr>
							<td>{{ $index + 1 }}.</td>
							<td>{!! $value['syarat'] !!}</td>
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
				<h6 class="mb-0">Syarat Perlu Peringkat Unggul</h6>
			</div>
			<div class="card-body">
				<table class="table table-bordered">
					<tr>
						<th class="col-md-1">No</th>
						<th class="col-md-9">Syarat</th>
						<th class="col-md-2">Status</th>
					</tr>
					@foreach ($tablePeringkatUngguls as $index => $value)
						<tr>
							<td>{{ $index + 1 }}.</td>
							<td>{!! $value['syarat'] !!}</td>
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
				<h6 class="mb-0">Syarat Perlu Peringkat Baik Sekali</h6>
			</div>
			<div class="card-body">
				<table class="table table-bordered">
					<tr>
						<th class="col-md-1">No</th>
						<th class="col-md-9">Syarat</th>
						<th class="col-md-2">Status</th>
					</tr>
					@foreach ($tableBaikSekalis as $index => $value)
						<tr>
							<td>{{ $index + 1 }}.</td>
							<td>{!! $value['syarat'] !!}</td>
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
						<th rowspan="2">Nilai Evaluasi Diri</th>
						<th rowspan="2">Syarat Perlu Terakreditasi</th>
						<th colspan="2">Syarat Perlu Peringkat</th>
						<th rowspan="2">Status</th>
						<th rowspan="2">Peringkat</th>
					</tr>
					<tr>
						<th>Unggul</th>
						<th>Baik Sekali</th>
					</tr>
					<tr>
						<td>{{ $totals }}</td>
						<td>{{ $h2s }}</td>
						<td>{{ $h3s }}</td>
						<td>{{ $h4s }}</td>
						<td>{{ $h5s }}</td>
						<td>{{ $h6s }}</td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>
