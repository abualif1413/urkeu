<?php
	error_reporting(0);
	session_start();
	include_once "../models/autoloader.php";
	include_once "../models/terbilang.php";
	
	//require_once "../dompdf-master/src/Dompdf.php";
	
	require_once '../dompdf/lib/html5lib/Parser.php';
	require_once '../dompdf/lib/php-font-lib/src/FontLib/Autoloader.php';
	require_once '../dompdf/lib/php-svg-lib/src/autoload.php';
	require_once '../dompdf/src/Autoloader.php';
	Dompdf\Autoloader::register();

	// reference the Dompdf namespace
	use Dompdf\Dompdf;
	
	// Inisialisasi isi cetak
	$record = PermohonanDanaModel::GetFullRecord01($_GET["id"]);
	$rincian = DetailPermohonanDanaBusinessLogic::GetList02($_GET["id"]);
	
	$normatif = PermohonanDanaModel::GetTotalList($_GET["id"]);
	
	// Pejabat dari konfigurasi
	$konf_kpa = get_ttd_dokumen(1, "002", $record["tanggal"]);
	$konf_wkpa = get_ttd_dokumen(1, "001", $record["tanggal"]);
	
	$tulisan_nrp = "NRP";
	if($record["id_jenis_pegawai"] == 1)
		$tulisan_nrp = "NRP";
	else if($record["id_jenis_pegawai"] == 2)
		$tulisan_nrp = "NIP";
	else
		$tulisan_nrp = "NIK";
	
	$tulisan_nrp_diketahui = "NRP";
	if($record["id_jenis_pegawai_diketahui"] == 1)
		$tulisan_nrp_diketahui = "NRP";
	else if($record["id_jenis_pegawai_diketahui"] == 2)
		$tulisan_nrp_diketahui = "NIP";
	else
		$tulisan_nrp_diketahui = "NIK";
	
	$tbl = "";
	$no = 0;
	$total_jumlah = 0;
	$total_ppn = 0;
	$total_pph = 0;
	foreach ($rincian as $rinci) {
		$total_jumlah += ($rinci->jumlah + $rinci->ppn + $rinci->pph);
		$no++;
		$tbl .= "<tr>";
			$tbl .= "<td align='right'>" . $no . "</td>";
			$tbl .= "<td>" . $rinci->uraian . "</td>";
			$tbl .= "<td align='right'>" . $rinci->qty . "</td>";
			$tbl .= "<td>" . $rinci->satuan . "</td>";
			$tbl .= "<td align='right'>" . number_format($rinci->harga_satuan, 2, ".", ",") . "</td>";
			$tbl .= "<td align='right'>" . number_format($rinci->jumlah + $rinci->ppn + $rinci->pph, 2, ".", ",") . "</td>";
			$tbl .= "<td align='right'></td>";
		$tbl .= "</tr>";
	}
	
	if($normatif["jumlah_dibayarkan"] > 0) {
		$total_jumlah_normatif = $normatif["jumlah_dibayarkan"];
		$total_ppn_normatif = 0;
		$total_pph_normatif = $normatif["pph"];
		$total_jumlah += $total_jumlah_normatif + $total_ppn_normatif + $total_pph_normatif;
		
		$no++;
		$tbl .= "<tr style='page-break-inside: avoid;'>";
			$tbl .= "<td align='right'>" . $no . "</td>";
			$tbl .= "<td>Normatif</td>";
			$tbl .= "<td align='right'></td>";
			$tbl .= "<td></td>";
			$tbl .= "<td align='right'>" . number_format(($total_jumlah_normatif), 2, ".", ",") . "</td>";
			$tbl .= "<td align='right'>" . number_format(($total_jumlah_normatif + $total_ppn_normatif + $total_pph_normatif), 2, ".", ",") . "</td>";
			$tbl .= "<td align='right'></td>";
		$tbl .= "</tr>";
	}
	
	$tbl .= "<tr style='font-weight: bold;'>";
		$tbl .= "<td align='center' colspan='5'>TOTAL</td>";
		$tbl .= "<td align='right'>" . number_format($total_jumlah, 2, ".", ",") . "</td>";
		$tbl .= "<td></td>";
	$tbl .= "</tr>";
	$tbl .= "<tr style='font-weight: bold; font-style: italic;'>";
		$tbl .= "<td align='left' colspan='7'><b>Terbilang : </b><br /><span style='font-weight: lighter;'>" . terbilang($total_jumlah) . " rupiah</span></td>";
	$tbl .= "</tr>";
	
	$cetak = "
		<style>
			@page { margin: 1cm 2cm; border: solid 1px black;}
		</style>
		
					<div style='margin-bottom: 30px; left: 0px; right: 0px; font-size: 10pt;'>
						<div style='height: 0cm;'></div>
						<table width='500px' style='font-weight: bold;'>
							<tr>
								<td align='left'>KEPOLISIAN NEGARA REPUBLIK INDONESIA</td>
							</tr>
							<tr>
								<td align='left' style='padding-left: 55px;'>DAERAH SUMATERA UTARA</td>
							</tr>
							<tr>
								<td align='left' style='text-decoration: underline;'>RUMAH SAKIT BHAYANGKARA TK II MEDAN</td>
							</tr>
						</table>
					</div>
					<div style='text-align: center; font-family: serif; font-size: 12pt; font-weight: bold; text-decoration: underline'>SURAT PERINCIAN PERTANGGUNG JAWABAN BELANJA</div>
					<div style='text-align: center; font-family: serif; font-size: 12pt;'>Nomor : SPPJB/" . $record["na_nomor"] . "/" . $record["na_bulan"] . "/" . $record["na_tahun"] . "/" . $record["na_divisi"] . "</div>
					<br /><br />
					<table style='font-size: 9pt;'>
						<tr>
							<td valign='top'>Perihal</td>
							<td valign='top'>:</td>
							<td valign='top'>" . $record["keterangan"] . "</td>
						</tr>
					</table>
					<br />
					<span style='font-size: 9pt;'>Saya yang bertanda tangan di bawah ini :</span><br /><br />
					<table cellspacing='0' cellpadding='0' style='text-transform: uppercase; font-size: 9pt; margin-left: 20px;' width='100%'>
						<tr>
							<td width='230px'>Nama</td>
							<td width='10px'>:</td>
							<td>" . $record["nama_pegawai"] . "</td>
						</tr>
						<tr>
							<td width='230px'>Pangkat / NRP / NIP</td>
							<td width='10px'>:</td>
							<td>" . $record["pangkat"] . " / " . $record["nik"] . "</td>
						</tr>
						<tr>
							<td width='230px'>Jabatan</td>
							<td width='10px'>:</td>
							<td>" . $record["jabatan"] . "</td>
						</tr>
					</table>
					<br />
					<span style='font-size: 9pt;'>Dengan ini saya mengajukan permintaan sbb:</span><br />
					<table width='100%' border='1' cellspacing='0' cellpadding='1' style='border-collapse: collapse; font-size: 9pt; page-break-inside: auto;'>
						<thead>
							<tr align='center'>
								<th width='30px'>No.</th>
								<th width='200px'>Materil</th>
								<th colspan='2'>Jumlah<br />Barang</th>
								<th>Harga Satuan</th>
								<th width='200px'>Jumlah</th>
								<th>KET</th>
							</tr>
						</thead>
						<tbody>
							" . $tbl . "
						</tbody>
					</table>
					<br />
				
				
					<br />
					<table width='100%' cellspacing='0' cellpadding='0' style='font-size: 9pt; table-layout: fixed;' border='0'>
						<tr>
							<td align='center'>Diketahui Oleh</td>
							<td align='center'>Medan, " . tanggal_indonesia_panjang($record["tanggal"]) . "</td>
						</tr>
						<tr>
							<td align='center' style='font-weight: bold;'>" . $record["jabatan_diketahui"] . "</td>
							<td align='center' style='font-weight: bold;'>" . $record["jabatan"] . "</td>
						</tr>
						<tr>
							<td style='height: 70px'></td>
							<td></td>
						</tr>
						<tr>
							<td align='center' style='font-weight: bold; text-decoration: underline;'>" . $record["nama_pegawai_diketahui"] . "</td>
							<td align='center' style='font-weight: bold; text-decoration: underline;'>" . $record["nama_pegawai"] . "</td>
						</tr>
						<tr>
							<td align='center' style=''>" . strtoupper($record["pangkat_diketahui"]) . " " . $tulisan_nrp_diketahui . " " . $record["nik_diketahui"] . "</td>
							<td align='center' style=''>" . strtoupper($record["pangkat"]) . " " . $tulisan_nrp . " " . $record["nik"] . "</td>
						</tr>
					</table>
					<br />
				
				
					<br />
					<table width='100%' cellspacing='0' cellpadding='0' style='font-size: 9pt; table-layout: fixed;' border='0'>
						<tr>
							<td align='center'>Menyetujui</td>
							<td align='center' width='300px'>Mengetahui</td>
						</tr>
						<tr>
							<td align='center' style='font-weight: bold;'>" . $konf_wkpa["judul_ttd"] . "</td>
							<td align='center' style='font-weight: bold;'>" . $konf_kpa["judul_ttd"] . "</td>
						</tr>
						<tr>
							<td style='height: 70px'></td>
							<td></td>
						</tr>
						<tr>
							<td align='center' style='font-weight: bold; text-decoration: underline;'>" . $konf_wkpa["nama_pegawai"] . "</td>
							<td align='center' style='font-weight: bold; text-decoration: underline;'>" . $konf_kpa["nama_pegawai"] . "</td>
						</tr>
						<tr>
							<td align='center'>" . $konf_wkpa["pangkat"] . " " . $konf_wkpa["sebutan_nrp"] . " " . $konf_wkpa["nik"] . "</td>
							<td align='center'>" . $konf_kpa["pangkat"] . " " . $konf_kpa["sebutan_nrp"] . " " . $konf_kpa["nik"] . "</td>
						</tr>
					</table>
					<br />
				
				
					<br />
					<span style='font-size: 9pt;'>Catatan disposisi :</span>
					<br /><br /><br /><br /><br />
				
	";
	
	// instantiate and use the dompdf class
	$dompdf = new Dompdf();
	$dompdf->loadHtml($cetak);
	
	// (Optional) Setup the paper size and orientation
	$dompdf->setPaper('A4', 'portrait');
	
	// Render the HTML as PDF
	$dompdf->render();
	$font = $dompdf->getFontMetrics()->get_font("helvetica", "bold");
	$dompdf->getCanvas()->page_text(50, 810, "Halaman: {PAGE_NUM} dari {PAGE_COUNT}", $font, 8, array(0,0,0));
	
	// Output the generated PDF to Browser
	$dompdf->stream("sptjb rincian.pdf", array("Attachment" => false));
	//echo $cetak
?>
