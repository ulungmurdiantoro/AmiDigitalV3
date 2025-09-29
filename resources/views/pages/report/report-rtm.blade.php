<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title>Report RTM</title>
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
					<td class="tbpeserta" style="text-align: justify">
							<p><b>Rapat Tinjauan Manajemen (RTM)</b> merupakan rapat yang dilakukan oleh seluruh manajemen 
							secara periodik untuk meninjau kinerja sistem manajemen mutu dan kinerja pelayanan institusi 
							serta memastikan kelanjutan, kesesuaian, kecukupan dan efektivitas sistem manajemen mutu dan 
							sistem pelayanan. Peninjauan kinerja sistem manajemen mutu dan kinerja pelayanan institusi dilakukan 
							berdasarkan materi berupa hasil audit mutu internal (AMI) program studi dan unit-unit lain, umpan 
							balik dari stakeholder, hasil survei kepuasan stakeholder, kinerja layanan, kinerja dosen, pencapaian 
							sasaran mutu/indikator kinerja, serta status tindak lanjut dari hasil tinjauan manajemen sebelumnya. 
							Rapat tinjauan manajemen merupakan tahapan yang strategis untuk memanfaatkan hasil AMI sebagai bagian 
							dari aspek pengendalian dalam PPEPP. Rapat tinjauan manajemen di lingkungan Universitas telah dilakukan 
							secara berjenjang, dimulai dari tingkat program studi, fakultas, hingga universitas. Tindak lanjut dari 
							rapat tinjauan manajemen merupakan bentuk nyata komitmen pimpinan untuk peningkatan mutu.</p>
							<br>
							<p>Luaran atau hasil dari RTM berupa kebijakan, keputusan, dan/atau tindakan untuk peningkatan sistem dan 
							hasil layanan, pemenuhan kebutuhan sumber daya, identifikasi perubahan-perubahan yang diperlukan baik 
							pada sistem penjaminan mutu maupun sistem pelayanan, penyediaan sumber daya dan fasilitas yang perlu 
							dilakukan agar sistem penjaminan mutu dan sistem pelayanan menjadi efektif.</p>
					</td>
				</tr>
			</tbody>
		</table>
		<svg height="5" width="1500">
			<line x1="0" y1="0" x2="1500" y2="0" style="stroke:rgb(179, 179, 179);stroke-width:5;" />
		</svg>
	</section>
	<section name="lingkup bahasan">
		<table class="tbsubbody" width="100%">
			<tr>
				<td class="tdjudul"><h3>LINGKUP BAHASAN</h3></td>
			</tr>
		</table>
		<svg height="10" width="1500">
				<line x1="0" y1="0" x2="1500" y2="0" style="stroke:rgb(255,200,0);stroke-width:10;" />
		</svg>
		<table class="tbsurvey" style="width:100%">
			<tbody>
				<tr>
					<td class="tbpeserta" style="text-align: justify">
						<p>Mengacu pada aspek Evaluasi dalam Siklus PPEPP SPMI, maka Universitas melaksanakan 
								tinjauan manajemen dengan ruang lingkup seperti yang dipersyaratkan, yaitu: </p>
						<table>
							<tr>
								<td>1.</td>
								<td>Hasil audit, meliputi temuan-temuan dalam AMI program studi.</td>
							</tr>
							<tr>
								<td>2.</td>
								<td>Tingkat kepuasan mahasiswa terhadap budaya mutu dan suasana akademik.</td>
							</tr>
							<tr>
								<td>3.</td>
								<td>Tingkat kepuasan mahasiswa terhadap pelaksanaan perkuliahan.</td>
							</tr>
							<tr>
								<td>4.</td>
								<td>Tingkat kepuasan dosen terhadap layanan administrasi.</td>
							</tr>
							<tr>
								<td>5.</td>
								<td>Kinerja proses bisnis, meliputi capaian sasaran mutu dan capaian program kerja.</td>
							</tr>
							<tr>
								<td style="vertical-align: text-top;">6.</td>
								<td>Tindakan pencegahan dan tindakan koreksi (hambatan program studi dan tindak lanjut untuk mencegah hambatan tersebut agar tidak terjadi lagi).</td>
							</tr>
							<tr>
								<td style="vertical-align: text-top;">7.</td>
								<td>Tindak lanjut tinjauan manajemen sebelumnya (catatan yang perlu mendapat perhatian pada tinjauan manajemen sebelumnya).</td>
							</tr>
							<tr>
								<td>8.</td>
								<td>Perubahan yang mempengaruhi Sistem Manajemen Mutu (baik internal maupun dari eksternal).</td>
							</tr>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
		<svg height="5" width="1500">
			<line x1="0" y1="0" x2="1500" y2="0" style="stroke:rgb(179, 179, 179);stroke-width:5;" />
		</svg>
	</section>
	<section name=" HASIL AUDIT MUTU INTERNAL">
		<table class="tbsubbody" width="100%">
			<tr>
				<td class="tdjudul, upper"><h3>1. HASIL AUDIT MUTU INTERNAL</h3></td>
			</tr>
		</table>
		<svg height="10" width="1500">
			<line x1="0" y1="0" x2="1500" y2="0" style="stroke:rgb(255,200,0);stroke-width:10;" />
		</svg>
		<table class="tbsurvey" style="width:100%">
			<tbody>
				<tr>
					<td class="tbpeserta" style="text-align: justify">
						<p>Hasil AMI bersumber pada ringkasan AMI program studi dan lembaga serta mengacu pada 
								pemenuhan PerBAN PT No. 5 Tahun 2019 Tentang IAPS dan PerBAN PT No. 3 Tahun 2019 
								Tentang IAPT. Hasil ringkasan AMI dan tindakan perbaikan disajikan pada Tabel 2 dan 
								3. Hasil AMI menunjukkan beberapa kondisi ketidaksesuaian dan diperlukan tindak 
								lanjut perbaikan pada siklus PPEPP selanjutnya, antara lain: </p>
					</td>
				</tr>
			</tbody>
		</table>
	</section>
	<section name="TABEL 1." class="subbab">
		<table class="tbsubbody" width="100%">
			<tr>
				<td class="tdjudul, upper"><h3>Tabel 1. Hasil Ringkasan AMI Tahun {{ $periode }}</h3></td>
			</tr>
		</table>
		<svg height="10" width="1500">
			<line x1="0" y1="0" x2="1500" y2="0" style="stroke:rgb(255,200,0);stroke-width:10;" />
		</svg>
		<canvas id="Standar1A"></canvas>
		<table class="tbsurvey" style="width:100%">
			<thead>
					<tr>
						<th style="width:5%" class="tbpeserta">No.</th>
						<th style="width:60%" class="tbpeserta">Standar Dikti</th>
						<th style="width:20%" class="tbpeserta">Nilai Capaian</th>
						<th style="width:15%" class="tbpeserta">Predikat</th>
					</tr>
			</thead>
			<tbody>
				@foreach($nama_data_standar as $index => $standar_nama)
					@php
						$key = 'data_standar_k' . ($index + 1);
						$data = $data_standar[$key] ?? [];
						$total_nilai = 0;
						$total_count = count($data);
					@endphp
					<tr>
						<td>{{ $index + 1 }}</td>
						<td>{{ $standar_nama }}</td>
						@php
							foreach ($data as $standar) {
								$nilai = optional($standar->$standarNilaisRelation)->hasil_nilai ?? 0;
								$total_nilai += $nilai;
							}
							
							$average_nilai = $total_count > 0 ? round($total_nilai / $total_count, 2) : 0;
							
							$predikat_akhir = match (true) {
								$average_nilai >= 3.5 => 'A (Unggul)',
								$average_nilai >= 2.5 => 'B (Baik)',
								$average_nilai >= 1.5 => 'C (Cukup)',
								$average_nilai >= 1.0 => 'D (Kurang)',
								default => 'D (Kurang)'
							};
						@endphp
						<td>{{ $average_nilai }}/4</td>
						<td>{{ $predikat_akhir }}</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</section>
	<section name="TABEL 2." class="subbab">
    <table class="tbsubbody" width="100%">
			<tr>
				<td class="tdjudul, upper"><h3>Tabel 2. Hasil Temuan AMI Tahun {{ $periode }}</h3></td>
			</tr>
    </table>
    <svg height="10" width="1500">
			<line x1="0" y1="0" x2="1500" y2="0" style="stroke:rgb(255,200,0);stroke-width:10;" />
    </svg>
    <canvas id="Standar1A"></canvas>
    <table class="tbsurvey" style="width:100%">
			<thead>
				<tr>
					<th style="width:7%" class="tbpeserta">Kode</th>
					<th style="width:10%" class="tbpeserta">Kategori</th>
					<th style="width:20%" class="tbpeserta">Standar Dikti</th>
					<th style="width:48%" class="tbpeserta">Uraian Kondisi/Temuan</th>
					<th style="width:15%" class="tbpeserta">Nilai Capaian</th>
				</tr>
			</thead>
			<tbody>
				@foreach($nama_data_standar as $index => $standar_nama)
					@php
						$key = 'data_standar_k' . ($index + 1);
						$data = $data_standar[$key] ?? [];
						$total_nilai = 0;
						$total_count = count($data);
					@endphp
					@foreach ($data as $standar)
						@php
							$nilai = optional($standar->$standarNilaisRelation)->hasil_nilai ?? null;
							$total_nilai += $nilai ?? 0;  
						@endphp
						@if (optional($standar->$standarNilaisRelation)->jenis_temuan != 'Sesuai')
							<tr>
								<td>{{ $standar->indikator_id }}</td>
								<td>{{ optional($standar->$standarNilaisRelation)->jenis_temuan ?? null }}</td>
								<td>{{ $standar->elemen_nama }}</td>
								<td>{{ optional($standar->$standarNilaisRelation)->hasil_deskripsi ?? null }}</td>
								<td>{{ $nilai !== null ? $nilai . '/4' : '0/4' }}</td>
							</tr>
						@endif
					@endforeach
				@endforeach
			</tbody>
    </table>
	</section>
	<section name="TABEL 3." class="subbab">
    <table class="tbsubbody" width="100%">
			<tr>
				<td class="tdjudul, upper"><h3>Tabel 2. Tindakan Perbaikan dari Hasil AMI Tahun {{ $periode }}</h3></td>
			</tr>
    </table>
    <svg height="10" width="1500">
			<line x1="0" y1="0" x2="1500" y2="0" style="stroke:rgb(255,200,0);stroke-width:10;" />
    </svg>
    <canvas id="Standar1A"></canvas>
    <table class="tbsurvey" style="width:100%">
			<thead>
				<tr>
					<th style="width:5%" class="tbpeserta">No.</th>
					<th style="width:10%" class="tbpeserta">Kategori</th>
					<th style="width:27%" class="tbpeserta">Uraian Kondisi/Temuan</th>
					<th style="width:27%" class="tbpeserta">Tindakan Perbaikan</th>
					<th style="width:8%" class="tbpeserta">Target Waktu</th>
					<th style="width:14%" class="tbpeserta">Penanggung Jawab</th>
					<th style="width:9%" class="tbpeserta">Status Akhir</th>
				</tr>
			</thead>
			<tbody>
				@foreach($nama_data_standar as $index => $standar_nama)
					@php
						$key = 'data_standar_k' . ($index + 1);
						$data = $data_standar[$key] ?? [];
						$total_nilai = 0;
						$total_count = count($data);
					@endphp

					@foreach ($data as $standar)
						@php
							$nilai = optional($standar->$standarNilaisRelation)->hasil_nilai ?? null;
							$total_nilai += $nilai ?? 0;  
						@endphp
						@if (optional($standar->$standarNilaisRelation)->jenis_temuan != 'Sesuai')
							<tr>
								<td>{{ $standar->indikator_id }}</td>
								<td>{{ optional($standar->$standarNilaisRelation)->jenis_temuan }}</td>
								<td>{{ optional($standar->$standarNilaisRelation)->hasil_deskripsi }}</td>
								<td>{{ optional($standar->$standarNilaisRelation)->hasil_rencana_perbaikan }}</td>
								<td>{{ optional($standar->$standarNilaisRelation)->hasil_jadwal_perbaikan }}</td>
								<td>{{ optional($standar->$standarNilaisRelation)->hasil_perbaikan_penanggung }}</td>
								<td>{{ optional($standar->$standarNilaisRelation)->status_akhir }}</td>
							</tr>
						@endif
					@endforeach
				@endforeach
			</tbody>
    </table>
	</section>
	<section name="Capaian Kinerja">
		<table class="tbsubbody" width="100%">
			<tr>
				<td class="tdjudul, upper"><h3>2. Capaian Kinerja </h3></td>
			</tr>
		</table>
		<svg height="10" width="1500">
			<line x1="0" y1="0" x2="1500" y2="0" style="stroke:rgb(255,200,0);stroke-width:10;" />
		</svg>
		<table class="tbsurvey" style="width:100%">
			<tbody>
				<tr>
					<td class="tbpeserta" style="text-align: justify">
						<p>Capaian kinerja Universitas berupa pengelolaan penelitian yang telah dilaksanakan sesuai 
						dengan IKU Universitas dapat dilihat pada tabel 4. Hasil dari beberapa parameter pengelolaan 
						penelitian. Parameter berikutnya yaitu data luaran lain penelitian juga belum ada. </p>
					</td>
				</tr>
			</tbody>
		</table>
	</section>
	<section name="TABEL 4." class="subbab">
    <table class="tbsubbody" width="100%">
			<tr>
				<td class="tdjudul, upper"><h3>Tabel 4. Tindak Lanjut dari Hasil Tinjauan Manajemen {{ $periode }} </h3></td>
			</tr>
    </table>
    <svg height="10" width="1500">
			<line x1="0" y1="0" x2="1500" y2="0" style="stroke:rgb(255,200,0);stroke-width:10;" />
    </svg>
    <canvas id="Standar1A"></canvas>
    <table class="tbsurvey" style="width:100%">
			<thead>
				<tr>
					<th style="width:5%" class="tbpeserta">No.</th>
					<th style="width:60%" class="tbpeserta">Elemen</th>
					<th style="width:20%" class="tbpeserta">Nilai Capaian</th>
					<th style="width:15%" class="tbpeserta">Predikat</th>
				</tr>
			</thead>
			<tbody>
				@foreach($nama_data_standar as $index => $standar_nama)
					@php
						$key = 'data_standar_k' . ($index + 1);
						$data = $data_standar[$key] ?? [];
						$total_nilai = 0;
						$total_count = count($data);
					@endphp

					@foreach ($data as $standar)
						@php
							$nilai = optional($standar->$standarNilaisRelation)->hasil_nilai ?? null;
							$pedikat = match (true) {
								$nilai >= 3.5 => 'A (Unggul)',
								$nilai >= 2.5 => 'B (Baik)',
								$nilai >= 1.5 => 'C (Cukup)',
								$nilai >= 1.0 => 'D (Kurang)',
								default => 'D (Kurang)'
							};
						@endphp
						<tr>
							<td>{{ $standar->indikator_id }}</td>
							<td>{{ $standar->elemen_nama }}</td>
							<td>{{ $nilai !== null ? $nilai . '/4' : '0/4' }}</td>
							<td>{{ $pedikat }}</td>
						</tr>
					@endforeach
				@endforeach
			</tbody>
    </table>
	</section>
	<section name="TINDAK LANJUT HASIL TINJAUAN MANAJEMEN">
		<table class="tbsubbody" width="100%">
			<tr>
				<td class="tdjudul, upper"><h3>3. TINDAK LANJUT HASIL TINJAUAN MANAJEMEN</h3></td>
			</tr>
		</table>
		<svg height="10" width="1500">
			<line x1="0" y1="0" x2="1500" y2="0" style="stroke:rgb(255,200,0);stroke-width:10;" />
		</svg>
		<table class="tbsurvey" style="width:100%">
			<tbody>
					<tr>
					<td class="tbpeserta" style="text-align: justify">
						<p>Tindak lanjut yang telah dilakukan Universitas terhadap hasil 
							tinjauan manajemen tahun 2019 disajikan pada Tabel 5.</p>
					</td>
					</tr>
			</tbody>
		</table>
	</section>
	<section name="TABEL 5." class="subbab">
    <table class="tbsubbody" width="100%">
			<tr>
				<td class="tdjudul, upper"><h3>Tabel 5. Tindak Lanjut dari Hasil Tinjauan Manajemen {{ $periode }}</h3></td>
			</tr>
    </table>
    <svg height="10" width="1500">
			<line x1="0" y1="0" x2="1500" y2="0" style="stroke:rgb(255,200,0);stroke-width:10;" />
    </svg>
    <canvas id="Standar1A"></canvas>
    <table class="tbsurvey" style="width:100%">
			<thead>
				<tr>
					<th style="width:5%" class="tbpeserta">No.</th>
					<th style="width:27%" class="tbpeserta">Rekomendasi Tinjauan Manajemen Sebelumnya ({{ $previousPeriode }})</th>
					<th style="width:21%" class="tbpeserta">Tindak Lanjut yang SUDAH dilakukan ({{ $periode }})</th>
					<th style="width:20%" class="tbpeserta">Kendala yang dihadapi</th>
					<th style="width:27%" class="tbpeserta">Rencana selanjutnya ({{ $nextPeriode }})</th>
				</tr>
			</thead>
			<tbody>
				@foreach($nama_data_standar as $index => $standar_nama)
				@php
						$key = 'data_standar_k' . ($index + 1);
						$data = $data_standar[$key] ?? [];
						$previous_key = 'data_standar_k' . ($index + 1);
						$previous_data = $previous_data_standar[$previous_key] ?? [];
						$total_nilai = 0;
						$total_count = count($data);
				@endphp

					@foreach ($data as $standar)
						<tr>
							<td>{{ $standar->indikator_id }}</td>
							@php
								// Find the corresponding previous data
								$previous_standar = $previous_data->firstWhere('indikator_id', $standar->indikator_id);
							@endphp
							@if ($previous_standar)
								<td>{{ optional($previous_standar->$standarNilaisRelation)->hasil_rekomendasi }}</td>
							@else
								<td>-</td>
							@endif
							<td>{{ optional($standar->$standarNilaisRelation)->hasil_rencana_perbaikan }}</td>
							<td>{{ optional($standar->$standarNilaisRelation)->hasil_masalah }}</td>
							<td>{{ optional($standar->$standarNilaisRelation)->hasil_rencana_pencegahan }}</td>
						</tr>
					@endforeach
				@endforeach
			</tbody>
    </table>
	</section>
	<section name="REKOMENDASI TINDAK LANJUT RTM">
		<table class="tbsubbody" width="100%">
			<tr>
				<td class="tdjudul, upper"><h3>3. REKOMENDASI TINDAK LANJUT RTM {{ $periode }}</h3></td>
			</tr>
		</table>
		<svg height="10" width="1500">
			<line x1="0" y1="0" x2="1500" y2="0" style="stroke:rgb(255,200,0);stroke-width:10;" />
		</svg>
		<table class="tbsurvey" style="width:100%; background-color: #f2f2f2; border: 1px solid #f2f2f2;">
			<tbody>
				<tr>
					<td>Rekomendasi tindak lanjut RTM yang harus segera dilaksanakan adalah:</td>
				</tr>
				@foreach($nama_data_standar as $index => $standar_nama)
					@php
						$key = 'data_standar_k' . ($index + 1);
						$data = $data_standar[$key] ?? [];
					@endphp
					@foreach ($data as $standar)
						<tr>
							<td style="border: 1px solid #f2f2f2;">{{ $standar->indikator_id }} - {{ optional($standar->$standarNilaisRelation)->hasil_rencana_perbaikan ?? null; }}</td>
						</tr>
					@endforeach
				@endforeach
			</tbody>
		</table>	
	</section>
	<section name="PENUTUP">
		<table class="tbsubbody" width="100%">
			<tr>
				<td class="tdjudul, upper"><h3>4. PENUTUP</h3></td>
			</tr>
		</table>
		<svg height="10" width="1500">
			<line x1="0" y1="0" x2="1500" y2="0" style="stroke:rgb(255,200,0);stroke-width:10;" />
		</svg>
		<table class="tbsurvey" style="width:100%">
			<tbody>
				<tr>
					<td class="tbpeserta" style="text-align: justify">
						<p>Hal mendasar dalam rapat tinjauan manajemen adalah kemampulaksanaan kegiatan tindak 
							lanjut sebagai bentuk komitmen eksekutif dan seluruh sivitas akademika dalam upaya 
							mendukung peningkatan mutu. Dokumen laporan RTM ini menjadi dokumen rekaman dalam 
							peningkatan kualitas penyelenggaraan tri darma perguruan tinggi oleh Universitas. 
							Dengan koordinasi dan kerjasama pihak terkait, diharapkan pelaksanaan siklus PPEPP 
							dapat berjalan dengan optimal.</p>
					</td>
				</tr>
			</tbody>
		</table>
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