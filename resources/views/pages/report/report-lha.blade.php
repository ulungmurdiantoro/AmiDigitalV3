<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title>Report LHA</title>
	<style>
		body {
			font-family: "Gill Sans", sans-serif;
		}

		section {
			margin-left: 0mm;
			margin-right: 0mm;
		}

		p {
			font-size: 10.5pt;
		}

		td {
			font-size: 10.5pt;
		}

		h3 {
			font-size: 12pt;
			color: #005c99;
			font-weight: bold;
		}

		h5 {
			font-size: 11pt;
			color: #005c99;
			font-weight: bold;
		}

		h2 {
			font-size: 13pt;
			color: #005c99;
			font-weight: bold;
			text-align: center;
		}

		th {
			color:#ffffff;
			width: 15%;
			text-align: left;
			background-color: #005ce6;
			font-size: 10pt;
		}

		.subth {
			color:#ffffff;
			width: 15%;
			text-align: left;
			background-color: #3385ff;
			font-size: 10pt;
		}

		td {
			background-color: #f2f2f2;
		}

		.txtheader {
			font-size: 22pt;
			color: #000000;
		}

		h1 {
			font-size: 18pt;
			color: #005c99;
			font-weight: bold;
		}

		h4 {
			font-size: 32pt;
		}

		.txtheader2 {
			font-size: 14pt;
			color: #000000;
		}

		.tbsurvey {
			text-align: left;
			vertical-align: top;
			border-spacing: 2px;
		}

		.tbnilai {
			text-align: left;
			border-spacing: 2px;
		}

		.tbpeserta {
			padding: 3px;
			border-collapse: collapse;
			font-size: 10.5pt;
		}

		.tbhaeder {
			text-align: left;
			border-spacing: 2px;
		}

		.thheader {
			color:#005c99;
			width: 15%;
			text-align: left;
			background-color: #ccdcff;
			font-size: 10pt;
		}

		.tdheader {
				width: 35%;
				text-align: left;
				background-color: #f2f2f2;
				font-size: 10.5pt;
		}

		.tdjudul {
			text-align: left;
			border-spacing: 2px;
			background-color: #ffffff;
		}

		.tdbab{
			text-align: center;
			padding-top: 20px;
			border-collapse: collapse;
			background-color: #ffffff;
		}

		.tbsubbody{
			text-align: left;
			border-spacing: 0px;
			padding-top: 20px;
			background-color: #ffffff;
		}

		.tbsubbab{
			text-align: left;
			border-spacing: 0px;
			padding-top: 5px;
			background-color: #ffffff;
		}

		.tbscan, .tdscan{
			border: 1px solid black;
			text-align: left;
			border-collapse: collapse;
			background-color: #ffffff;
		}

		.thscan{
			border: 1px solid black;
			text-align: left;
			border-collapse: collapse;
			vertical-align: bottom;
			height:36px;
		}

		.tdttd{
			text-align: left;
			border-collapse: collapse;
			vertical-align: bottom;
			height:100px;
			background-color: #ffffff;
		}

		.tbtotal{
			text-align: center;
			padding: 3px;
			border-collapse: collapse;
			font-size: 10.5pt;
			font-weight: bold;
		}
		.upper{ 
			text-transform: uppercase; 
			background-color: #ffffff;
		}

		@page {
			margin-top: 12mm;
			margin-bottom: 5mm;
			margin-left: 7.5mm;
			margin-right: 7.5mm;
		}

		.subbab {
			margin-left: 5mm;
		}
	</style>
</head>
<body>
	<header>
		<table width="100%">
			<tr>
				<td class="tdjudul">
					<h1 class="upper">{{ session('instansi') }}<br>QUALITY ASSESSMENT REPORT</h1>
				</td>
				<td class="tdjudul"></td>
			</tr>
    </table>
		<svg height="7" width="1500">
      <line x1="0" y1="0" x2="1500" y2="0" style="stroke:rgb(0,0,0);stroke-width:7"/>
    </svg>
		<table class="tbhaeder" style="width:100%">
			<tr>
				<th class="thheader">Program Studi</th>
				<td class="tdheader">{{ $transaksi_ami->prodi }}</td>
				<th class="thheader">Nama Kaprodi</th>
				<td class="tdheader">
						{{ $transaksi_ami->penempatanUser->user_nama ?? 'N/A' }}
				</td>
			</tr>
			<tr>
				<th class="thheader">Jenjang</th>
				<td class="tdheader">{{ $prodiPrefix }}</td>
				<th class="thheader">Dikeluarkan Oleh</th>
				<td class="tdheader">mutu-perguruantinggi.id</td>
			</tr>
			<tr>
				<th class="thheader">Tanggal Audit</th>
				<td class="tdheader">{{ $tanggal_audit }}</td>
				<th class="thheader">Auditor</th>
				<td class="tdheader">
					@if($transaksi_ami->auditorAmi->isNotEmpty())
						@foreach ($transaksi_ami->auditorAmi as $auditor)
							<div>{{ $auditor->user->user_nama ?? 'N/A' }}</div>
						@endforeach
					@else
						N/A
					@endif
				</td>
			</tr>
			<tr>
				<th class="thheader">Tanggal Terbit</th>
				<td class="tdheader">{{ $tanggal }}</td>
				<th class="thheader">Tujuan</th>
				<td class="tdheader">Audit Mutu Internal</td>
			</tr>
		</table>
	</header>
	<section name="nilai_akhir">
		<table class="tbsubbody" width="100%">
			<tr>
				<td class="tdjudul"><h3>NILAI AKHIR</h3></td>
			</tr>
		</table>
		<svg height="10" width="1500">
			<line x1="0" y1="0" x2="1500" y2="0" style="stroke:rgb(255,200,0);stroke-width:10;" />
		</svg>
		<table class="tbnilai" style="width:100%">
			<thead>
				<tr>
					<th style="width:30%" class="tbpeserta">{{ $forecast['naLabel'] ?? 'Nilai Akhir' }}</th>
					<th style="width:70%" class="tbpeserta">Skala Penilaian ({{ $akreditasiKode ?? $akreditasi->nama }})</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td class="tbpeserta" style="text-align:center;">
						<h4>{{ $forecast['naDisplay'] ?? $totalNilai }}</h4>
					</td>
					<td class="tbpeserta" style="font-size:10px;">
						{{ $forecast['thresholdLabel'] ?? '-' }}
					</td>
				</tr>
			</tbody>
		</table>
	</section>

	<section name="hasil-forecasting">
		<table class="tbsubbody" width="100%">
			<tr>
				<td class="tdjudul"><h3>HASIL FORECASTING AKREDITASI</h3></td>
			</tr>
		</table>
		<svg height="10" width="1500">
			<line x1="0" y1="0" x2="1500" y2="0" style="stroke:rgb(255,200,0);stroke-width:10;" />
		</svg>
		@php
			$forecast = $forecast ?? [];
			$fWarna = $forecast['warna'] ?? 'gray';
			$fBgMap = ['green' => '#d4edda', 'blue' => '#d1ecf1', 'orange' => '#fff3cd', 'red' => '#f8d7da', 'gray' => '#e2e3e5'];
			$fClrMap = ['green' => '#155724', 'blue' => '#0c5460', 'orange' => '#856404', 'red' => '#721c24', 'gray' => '#383d41'];
			$fBg  = $fBgMap[$fWarna] ?? '#e2e3e5';
			$fClr = $fClrMap[$fWarna] ?? '#383d41';
		@endphp
		<table class="tbsurvey" style="width:100%; border-collapse:collapse;">
			<tbody>
				<tr>
					<th class="tbpeserta subth" style="width:25%">Standar Akreditasi</th>
					<td class="tbpeserta">{{ $akreditasiKode ?? $akreditasi->nama }} — {{ $jenjang->nama }}</td>
				</tr>
				<tr>
					<th class="tbpeserta subth">Prediksi Status</th>
					<td class="tbpeserta" style="background-color:{{ $fBg }}; color:{{ $fClr }}; font-weight:bold; font-size:13px;">
						{{ $forecast['status'] ?? '-' }}
					</td>
				</tr>
				<tr>
					<th class="tbpeserta subth">Durasi Berlaku</th>
					<td class="tbpeserta">{{ $forecast['durasi'] ?? '-' }}</td>
				</tr>
				<tr>
					<th class="tbpeserta subth">Analisis &amp; Catatan</th>
					<td class="tbpeserta">{{ $forecast['detail'] ?? '-' }}</td>
				</tr>
				<tr>
					<th class="tbpeserta subth">Ambang Batas</th>
					<td class="tbpeserta" style="font-size:10px;">{{ $forecast['thresholdLabel'] ?? '-' }}</td>
				</tr>
			</tbody>
		</table>
		<br>
		<p style="font-size:9px; color:#555; font-style:italic;">
			* Forecasting ini merupakan prediksi berdasarkan data penilaian AMI internal dan mengacu pada Pedoman Penilaian masing-masing badan akreditasi.
			Hasil akhir akreditasi ditentukan oleh asesor eksternal dan dapat berbeda dari prediksi ini.
		</p>
	</section>
	<section name="pendahuluan">
		<table class="tbsubbody" width="100%">
			<tr>
				<td class="tdjudul"><h3>PENDAHULUAN</h3></td>
			</tr>
		</table>
		<svg height="10" width="1500">
			<line x1="0" y1="0" x2="1500" y2="0" style="stroke:rgb(255,200,0);stroke-width:10;" />
		</svg>
		<table class="tbsurvey" style="width:100%">
			<tbody>
				<tr>
					<td class="tbpeserta">
						<p><b>Audit Mutu Internal Digital (AMI DIGITAL)</b> menerapkan prinsip Balance Scorecard, 
						agar Perguruan Tinggi lebih mudah mengetahui kinerja pada indikator yang dinilai, persiapan asesmen internal maupun eksternal.</p>
						<br>
						<p><b>AMI DIGITAL</b> akan membantu memberikan visualisasi melalui hasil grafik terhadap kinerja dan pencapaian mutu Perguruan Tinggi, 
						sebagai fungsi pengukuran, evaluasi dan peningkatan (continual improvement).</p>
					</td>
				</tr>
			</tbody>
		</table>
		<svg height="5" width="1500">
			<line x1="0" y1="0" x2="1500" y2="0" style="stroke:rgb(179, 179, 179);stroke-width:5;" />
		</svg>
	</section>
	<section name="standar">
    @forelse(($standards ?? []) as $index => $standard)
			<table class="tbsubbody" width="100%">
				<tr>
					<td class="tdjudul upper"><h3>{{ $standard->nama }}</h3></td>
				</tr>
			</table>
			<svg height="10" width="1500">
				<line x1="0" y1="0" x2="1500" y2="0" style="stroke:rgb(255,200,0);stroke-width:10;" />
			</svg>

			<table class="tbsurvey" style="width:100%">
				<thead>
					<tr>
						<th style="width:7%" class="tbpeserta">Kode</th>
						<th style="width:20%" class="tbpeserta">Elemen</th>
						<th style="width:60%" class="tbpeserta">Indikator</th>
						<th style="width:13%" class="tbpeserta">Memenuhi</th>
					</tr>
				</thead>
				<tbody>
					@foreach($standard->elements as $eIndex => $element)
						@foreach($element->indicators as $iIndex => $indikator)
							@php $kode = ($index + 1) . '.' . ($eIndex + 1) . '.' . ($iIndex + 1); @endphp
							<tr>
								<td>{{ $kode }}</td>
								<td>{{ $element->nama }}</td>
								<td>{{ $indikator->nama_indikator }}</td>
								<td>
									@php
										$nilai = $indikator->dokumen_nilais()
											->where('periode', $periode)
											->where('prodi', $prodi)
											->first();
									@endphp

									{!! ($nilai && $nilai->hasil_nilai == 1) ? '✔' : '✘' !!}
								</td>
							</tr>
						@endforeach
					@endforeach
				</tbody>
			</table>

        {{-- Deskripsi Temuan Audit --}}
        <section name="temuan-{{ $standard->id }}" class="subbab">
					<table class="tbsubbab" width="100%">
						<tr>
							<td class="tdjudul upper"><h5>Deskripsi Temuan Audit</h5></td>
						</tr>
					</table>
					<svg height="10" width="1500">
						<line x1="0" y1="0" x2="1500" y2="0" style="stroke:rgb(255, 227, 128);stroke-width:10;" />
					</svg>

					@foreach($standard->elements as $eIndex => $element)
						@foreach($element->indicators as $iIndex => $indikator)
							@php
								$nilai = $indikator->dokumen_nilais()
									->where('periode', $periode)
									->where('prodi', $prodi)
									->first();
							@endphp

							@if($nilai)
								<table class="tbsurvey" style="width:100%">
									<tr>
										<th style="width:15%" class="tbpeserta subth">Kode</th>
										<td colspan="3" class="tbpeserta">{{ $kode }}</td>
									</tr>
									<tr>
										<th class="tbpeserta subth">Jenis Temuan</th>
										<td colspan="3" class="tbpeserta">{{ $nilai->jenis_temuan }}</td>
									</tr>
									<tr>
										<th class="tbpeserta subth">Deskripsi Temuan</th>
										<td colspan="3" class="tbpeserta">{{ $nilai->hasil_deskripsi }}</td>
									</tr>
									<tr>
										<th class="tbpeserta subth">Kriteria</th>
										<td colspan="3" class="tbpeserta">{{ $nilai->hasil_kriteria }}</td>
									</tr>
									<tr>
										<th class="tbpeserta subth">Akibat</th>
										<td colspan="3" class="tbpeserta">{{ $nilai->hasil_akibat }}</td>
									</tr>
									<tr>
										<th class="tbpeserta subth">Akar Masalah</th>
										<td colspan="3" class="tbpeserta">{{ $nilai->hasil_masalah }}</td>
									</tr>
									<tr>
										<th class="tbpeserta subth">Rekomendasi</th>
										<td colspan="3" class="tbpeserta">{{ $nilai->hasil_rekomendasi }}</td>
									</tr>

									@if($nilai->jenis_temuan !== 'Sesuai')
										<tr>
											<th class="tbpeserta subth">Rencana Perbaikan</th>
											<td colspan="3" class="tbpeserta">{{ $nilai->hasil_rencana_perbaikan }}</td>
										</tr>
										<tr>
											<th class="tbpeserta subth">Jadwal Perbaikan</th>
											<td class="tbpeserta">{{ $nilai->hasil_jadwal_perbaikan }}</td>
											<th class="tbpeserta subth">Penanggung Jawab</th>
											<td class="tbpeserta">{{ $nilai->hasil_perbaikan_penanggung }}</td>
										</tr>
										<tr>
											<th class="tbpeserta subth">Rencana Pencegahan</th>
											<td colspan="3" class="tbpeserta">{{ $nilai->hasil_rencana_pencegahan }}</td>
										</tr>
										<tr>
											<th class="tbpeserta subth">Jadwal Pencegahan</th>
											<td class="tbpeserta">{{ $nilai->hasil_jadwal_pencegahan }}</td>
											<th class="tbpeserta subth">Penanggung Jawab</th>
											<td class="tbpeserta">{{ $nilai->hasil_rencana_penanggung }}</td>
										</tr>
									@endif
								</table>
							@endif
						@endforeach
					@endforeach
        </section>
		@empty
			<div class="alert alert-warning">
				Belum ada kriteria/standar yang dapat ditampilkan.
			</div>
		@endforelse
	</section>
	<section name="ttd">
		<br>
		<br>
		<br>
		<br>
		<table style="width:100%; background-color: #ffffff;">
			<thead>
				<tr>
					<td style="width:30%; background-color: #ffffff;">
						<br>
						Ketua Prodi  
					</td>
					<td style="width:40%; background-color: #ffffff;"></td>
					<td style="width:30%; background-color: #ffffff;">
						Surabaya, <br>
						Ketua Auditor 
					</td>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td class="tdttd">{{ $transaksi_ami->penempatanUser->user_nama ?? 'N/A' }}</td>
					<td class="tdttd"></td>
					<td class="tdttd">{{ $auditor->user->user_nama ?? 'N/A' }}</td>
				</tr>
			</tbody>
		</table>
		<div style="width: 35%; text-align: left; float: right;"></div>
		<br><br><br><br>
		<div style="width: 35%; text-align: left; float: right;">
		</div>
	</section>
</body>