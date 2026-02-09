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
							<p><b>Rapat Tinjauan Manajemen (RTM)</b> merupakan forum evaluatif yang diselenggarakan secara berkala oleh 
								jajaran pimpinan institusi guna menelaah efektivitas sistem manajemen mutu dan kualitas layanan kelembagaan. 
								Tujuan utama dari kegiatan ini adalah untuk memastikan keberlanjutan, kesesuaian, kecukupan, serta efektivitas 
								sistem yang diterapkan dalam mendukung pencapaian visi institusi.
							</p><br>
							<p>Evaluasi dalam RTM didasarkan pada berbagai sumber data yang mencerminkan kinerja institusi, antara lain: hasil 
								Audit Mutu Internal (AMI) dari program studi dan unit kerja, umpan balik dari pemangku kepentingan, hasil survei 
								kepuasan stakeholder, capaian kinerja layanan dan dosen, pencapaian indikator mutu, serta status tindak lanjut 
								dari RTM sebelumnya. RTM berperan sebagai mekanisme pengendalian dalam siklus PPEPP (Penetapan, Pelaksanaan, Evaluasi, 
								Pengendalian, dan Peningkatan), dengan memanfaatkan hasil AMI sebagai dasar pengambilan keputusan strategis.
							</p><br>
							<p>Pelaksanaan RTM dilakukan secara berjenjang, dimulai dari tingkat program studi, fakultas, hingga universitas, 
								sebagai wujud komitmen pimpinan terhadap peningkatan mutu berkelanjutan.
							</p><br>
							<p>Luaran dari RTM mencakup kebijakan, keputusan, dan/atau tindakan yang diarahkan untuk memperbaiki sistem dan hasil layanan, 
								memenuhi kebutuhan sumber daya, serta mengidentifikasi perubahan yang diperlukan dalam sistem penjaminan mutu dan pelayanan. 
								Selain itu, RTM juga menjadi dasar dalam penyediaan fasilitas dan sumber daya yang mendukung efektivitas sistem secara keseluruhan.
							</p>
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
						<p>
							Berdasarkan komponen evaluasi dalam siklus PPEPP Sistem Penjaminan Mutu Internal (SPMI), Universitas melaksanakan kegiatan tinjauan manajemen dengan cakupan yang telah ditetapkan, meliputi aspek-aspek berikut:
						</p>
						<table>
							<tr>
								<td>1.</td>
								<td>Hasil audit internal, mencakup temuan-temuan yang diperoleh dari pelaksanaan Audit Mutu Internal (AMI) pada tingkat program studi.</td>
							</tr>
							<tr>
								<td>2.</td>
								<td>Evaluasi tingkat kepuasan mahasiswa terhadap penerapan budaya mutu serta suasana akademik yang mendukung proses pembelajaran.</td>
							</tr>
							<tr>
								<td>3.</td>
								<td>Penilaian kepuasan mahasiswa terhadap pelaksanaan kegiatan perkuliahan, termasuk aspek metodologi dan interaksi pembelajaran.</td>
							</tr>
							<tr>
								<td>4.</td>
								<td>Penilaian kepuasan dosen terhadap efektivitas layanan administrasi akademik dan non-akademik yang disediakan oleh institusi.</td>
							</tr>
							<tr>
								<td>5.</td>
								<td>Evaluasi kinerja proses bisnis institusi, mencakup pencapaian terhadap sasaran mutu dan realisasi program kerja yang telah direncanakan.</td>
							</tr>
							<tr>
								<td style="vertical-align: text-top;">6.</td>
								<td>Identifikasi dan analisis terhadap tindakan pencegahan serta koreksi atas hambatan yang dihadapi program studi, termasuk tindak lanjut yang dirancang untuk mencegah terulangnya hambatan serupa.</td>
							</tr>
							<tr>
								<td style="vertical-align: text-top;">7.</td>
								<td>Penelaahan terhadap tindak lanjut hasil tinjauan manajemen sebelumnya, khususnya pada poin-poin yang memerlukan perhatian dan penyelesaian lanjutan.</td>
							</tr>
							<tr>
								<td>8.</td>
								<td>Identifikasi perubahan yang berdampak terhadap Sistem Manajemen Mutu, baik yang berasal dari dinamika internal institusi maupun faktor eksternal.</td>
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
						<p>Hasil Audit Mutu Internal (AMI) diperoleh dari ringkasan pelaksanaan AMI pada tingkat program studi dan lembaga, 
							yang disusun dengan mengacu pada ketentuan dalam Peraturan BAN-PT Nomor 5 Tahun 2019 tentang Instrumen Akreditasi 
							Program Studi (IAPS) dan Peraturan BAN-PT Nomor 3 Tahun 2019 tentang Instrumen Akreditasi Perguruan Tinggi (IAPT). 
							Ringkasan hasil AMI beserta rekomendasi tindakan perbaikannya disajikan dalam Tabel 2 dan Tabel 3.
						</p><br>
						<p>Temuan dari AMI menunjukkan adanya sejumlah ketidaksesuaian terhadap standar mutu yang telah ditetapkan. Oleh karena itu, 
							diperlukan tindak lanjut perbaikan yang akan diintegrasikan ke dalam siklus PPEPP berikutnya. Beberapa aspek yang menjadi 
							perhatian mencakup ketidaksesuaian dalam pelaksanaan standar akademik dan non-akademik, kurangnya dokumentasi pendukung, 
							serta perlunya penguatan mekanisme evaluasi dan tindak lanjut terhadap hasil audit sebelumnya.
						</p>
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
					<th style="width:55%" class="tbpeserta">Standar Dikti</th>
					<th style="width:20%" class="tbpeserta">Nilai Capaian</th>
					<th style="width:20%" class="tbpeserta">Predikat</th>
				</tr>
			</thead>
			<tbody>
				@php
					$total_nilai = 0;
					$total_count = 0;
				@endphp

				@forelse(($standards ?? []) as $index => $standard)
					@php
						$indicators = $standard->elements
								->flatMap(fn($element) => $element->indicators);

						$total_count = $indicators->count();

						$dokumenNilais = $indicators->map(function($indicator) use ($periode, $prodi) {
							return $indicator->dokumen_nilais()
								->where('periode', $periode)
								->where('prodi', $prodi)
								->first();
						})->filter();

						$total_nilai = $dokumenNilais->sum(fn($d) => $d->hasil_nilai ?? 0);

						$predikat_akhir = $total_nilai >= $total_count ? 'Memenuhi' : 'Tidak Memenuhi';
					@endphp

					<tr>
						<td>{{ $index + 1 }}</td>
						<td>{{ $standard->nama }}</td>
						<td>{{ $total_nilai }}/{{ $total_count }}</td>
						<td>{{ $predikat_akhir }}</td>
					</tr>

				@empty
					<tr>
						<td colspan="4">Belum ada data standar yang tersedia.</td>
					</tr>
				@endforelse
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
						<th style="width:43%" class="tbpeserta">Uraian Kondisi/Temuan</th>
						<th style="width:20%" class="tbpeserta">Nilai Capaian</th>
					</tr>
				</thead>
				<tbody>
					@php $total_nilai = 0; @endphp
					@foreach(($standards ?? []) as $index => $standard)
						@foreach($standard->elements as $eIndex => $element)
							@foreach($element->indicators as $iIndex => $indikator)
								@php
									$nilaiItem = $indikator->dokumen_nilais()
									->where('periode', $periode)
									->where('prodi', $prodi)
									->first();

									$nilai = optional($nilaiItem)->hasil_nilai ?? null;
									$temuan = optional($nilaiItem)->jenis_temuan ?? null;
									$deskripsi = optional($nilaiItem)->hasil_deskripsi ?? null;
									$status = $nilai === 1 ? 'Memenuhi' : 'Tidak Memenuhi';
									$kode = ($index + 1) . '.' . ($eIndex + 1) . '.' . ($iIndex + 1);
								@endphp

								@if ($temuan !== 'Sesuai')
									<tr>
										<td>{{ $kode }}</td>
										<td>{{ $temuan ?? '-' }}</td>
										<td>{{ $standard->nama ?? '-' }}</td>
										<td>{{ $deskripsi ?? '-' }}</td>
										<td>{{ $status }}</td>
									</tr>
								@endif
							@endforeach
						@endforeach
					@endforeach
				</tbody>
			</table>
	</section>
	<section name="TABEL 3." class="subbab">
    <table class="tbsubbody" width="100%">
			<tr>
				<td class="tdjudul, upper"><h3>Tabel 3. Tindakan Perbaikan dari Hasil AMI Tahun {{ $periode }}</h3></td>
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
				@php $total_nilai = 0; @endphp
					@foreach(($standards ?? []) as $index => $standard)
						@foreach($standard->elements as $eIndex => $element)
							@foreach($element->indicators as $iIndex => $indikator)
								@php
									$nilaiItem = $indikator->dokumen_nilais()
										->where('periode', $periode)
										->where('prodi', $prodi)
										->first();

									$nilai = optional($nilaiItem)->hasil_nilai ?? null;
									$temuan = optional($nilaiItem)->jenis_temuan ?? null;
									$deskripsi = optional($nilaiItem)->hasil_deskripsi ?? null;
									$rencana_perbaikan = optional($nilaiItem)->hasil_rencana_perbaikan ?? null;
									$jadwal_perbaikan = optional($nilaiItem)->hasil_jadwal_perbaikan ?? null;
									$jadwal_perbaikan = optional($nilaiItem)->hasil_perbaikan_penanggung ?? null;
									$status_akhir = optional($nilaiItem)->status_akhir ?? null;
									$status = $nilai == 1 ? 'Memenuhi' : 'Tidak Memenuhi';
									$kode = ($index + 1) . '.' . ($eIndex + 1) . '.' . ($iIndex + 1);
								@endphp

								@if ($temuan !== 'Sesuai')
									<tr>
										<td>{{ $kode }}</td>
										<td>{{ $temuan ?? '-' }}</td>
										<td>{{ $deskripsi ?? '-' }}</td>
										<td>{{ $rencana_perbaikan ?? '-' }}</td>
										<td>{{ $jadwal_perbaikan ?? '-' }}</td>
										<td>{{ $perbaikan_penanggung ?? '-' }}</td>
										<td>{{ $status_akhir ?? '-' }}</td>
									</tr>
								@endif
							@endforeach
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
				</tr>
			</thead>
			<tbody>
				@php $total_nilai = 0; @endphp
					@foreach(($standards ?? []) as $index => $standard)
						@foreach($standard->elements as $eIndex => $element)
							@foreach($element->indicators as $iIndex => $indikator)
								@php
									$nilaiItem = $indikator->dokumen_nilais()
										->where('periode', $periode)
										->where('prodi', $prodi)
										->first();

									$nilai = optional($nilaiItem)->hasil_nilai ?? null;
									$temuan = optional($nilaiItem)->jenis_temuan ?? null;
									$deskripsi = optional($nilaiItem)->hasil_deskripsi ?? null;
									$status = $nilai == 1 ? 'Memenuhi' : 'Tidak Memenuhi';
									$kode = ($index + 1) . '.' . ($eIndex + 1) . '.' . ($iIndex + 1);
								@endphp

								<tr>
									<td>{{ $kode }}</td>
									<td>{{ $element->nama ?? '-' }}</td>
									<td>{{ $status }}</td>
								</tr>
							@endforeach
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
					<th style="width:7%" class="tbpeserta">No.</th>
					<th style="width:27%" class="tbpeserta">Rekomendasi Tinjauan Manajemen Sebelumnya ({{ $periodeSebelumnya }})</th>
					<th style="width:21%" class="tbpeserta">Tindak Lanjut yang SUDAH dilakukan ({{ $periode }})</th>
					<th style="width:20%" class="tbpeserta">Kendala yang dihadapi</th>
					<th style="width:25%" class="tbpeserta">Rencana selanjutnya ({{ $periodeSelanjutnya }})</th>
        </tr>
			</thead>
			<tbody>
        @foreach(($standards ?? []) as $index => $standard)
					@foreach($standard->elements as $eIndex => $element)
						@foreach($element->indicators as $iIndex => $indikator)
							@php
								$kode = ($index + 1) . '.' . ($eIndex + 1) . '.' . ($iIndex + 1);

								// PERIODE SAAT INI (hasOne => query relasi)
								$nilaiItem = $indikator->dokumen_nilais()
									->where('periode', $periode)
									->where('prodi', $prodi)
									->first();

								// PERIODE SEBELUMNYA
								$nilaiItemSebelumnya = $indikator->dokumen_nilais()
									->where('periode', $periodeSebelumnya)
									->where('prodi', $prodi)
									->first();

								$nilai = optional($nilaiItem)->hasil_nilai;
								$temuan = optional($nilaiItem)->jenis_temuan;
								$deskripsi = optional($nilaiItem)->hasil_deskripsi;
								$status = $nilai === 1 ? 'Memenuhi' : 'Tidak Memenuhi';

								// Placeholder untuk data lintas periode
								$rekomendasiSebelumnya = optional($nilaiItemSebelumnya)->hasil_rekomendasi ?? '-';
								$tindakLanjut = optional($nilaiItem)->hasil_rencana_perbaikan ?? '-';
								$kendala = optional($nilaiItem)->hasil_masalah ?? '-';
								$rencanaSelanjutnya = optional($nilaiItem)->hasil_rencana_pencegahan ?? '-';
							@endphp

							@if ($temuan !== 'Sesuai')
								<tr>
									<td>{{ $kode }}</td>
									<td>{{ $rekomendasiSebelumnya }}</td>
									<td>{{ $tindakLanjut }}</td>
									<td>{{ $kendala }}</td>
									<td>{{ $rencanaSelanjutnya }}</td>
								</tr>
							@endif
						@endforeach
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
				@foreach(($standards ?? []) as $index => $standard)
					@foreach($standard->elements as $eIndex => $element)
						@foreach($element->indicators as $iIndex => $indikator)
							@php
								$kode = ($index + 1) . '.' . ($eIndex + 1) . '.' . ($iIndex + 1);

								$nilaiItem = $indikator->dokumen_nilais()
									->where('periode', $periode)
									->where('prodi', $prodi)
									->first();

								$nilai = optional($nilaiItem)->hasil_nilai;
								$rencanaSelanjutnya = optional($nilaiItem)->hasil_rencana_pencegahan ?? '-';
							@endphp

							@if ($temuan !== 'Sesuai')
								<tr>
									<td style="border: 1px solid #f2f2f2;">{{ $kode }} - {{ $rencanaSelanjutnya }}</td>
								</tr>
							@endif
						@endforeach
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