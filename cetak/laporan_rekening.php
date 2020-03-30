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
	$tanggal = $_GET["tahun"] . "-" . $_GET["bulan"] . "-01";
	$periode = $_GET["tahun"] . "-" . $_GET["bulan"];
	$bulan = semua_bulan();
	
	// Pejabat dari konfigurasi
	$konf_kpa = get_ttd_dokumen(9, "001", $tanggal);
	$konf_kaurkeu = get_ttd_dokumen(9, "002", $tanggal);
	
	
	$db = new DBConnection();
	$db->perintahSQL = "
		SELECT
			rek.*, COALESCE(sld.saldo, 0) saldo
		FROM
			itbl_apps_rekening_bank rek
			LEFT JOIN itbl_apps_saldo_rekening_bank sld ON (rek.id = sld.id_rekening AND sld.periode = ?)
	";
	$db->add_parameter("s", $periode);
	$rekening = $db->execute_reader();
	$db = null;
	$tbl = "";
	$no = 0;
	foreach ($rekening as $rek) {
		$no++;
		$tbl .= "<tr>";
			$tbl .= "<td align='center'>" . $no . "</td>";
			$tbl .= "<td align='center'>060</td>";
			$tbl .= "<td align='center'>01</td>";
			$tbl .= "<td align='center'>640384</td>";
			$tbl .= "<td align='left'>" . $rek["no_rekening"] . "</td>";
			$tbl .= "<td align='left'>" . $rek["an_rekening"] . "</td>";
			$tbl .= "<td align='center'>" . $rek["kode_rekening"] . "</td>";
			$tbl .= "<td align='center'>" . $rek["kode_bank"] . "</td>";
			$tbl .= "<td align='left'>" . $rek["nama_bank"] . "</td>";
			$tbl .= "<td align='left'>" . $rek["cabang_bank"] . "</td>";
			$tbl .= "<td align='center'>" . substr($rek["kode_rekening"], 0, 2) . "</td>";
			$tbl .= "<td align='left'>" . $rek["nomor_persetujuan_rekening"] . "</td>";
			$tbl .= "<td align='center'>" . tanggal_indonesia_pendek($rek["tanggal_persetujuan_rekening"]) . "</td>";
			$tbl .= "<td align='center'>" . tanggal_indonesia_pendek(tanggal_hari_terakhir($tanggal)) . "</td>";
			$tbl .= "<td align='right'>" . number_format($rek["saldo"], 2) . "</td>";
			$tbl .= "<td align='left'></td>";
		$tbl .= "</tr>";
	}
	
	$cetak = "
		<style>
			@page { margin: 0.5cm 0.5cm; }
		</style>
		<div style='margin-bottom: 20px; font-size: 10pt;'>
			<table width='500px' style='font-weight: bold;'>
				<tr>
					<td align='left'>KEPOLISIAN NEGARA REPUBLIK INDONESIA</td>
				</tr>
				<tr>
					<td align='left' style='padding-left: 70px;'>DAERAH SUMATERA UTARA</td>
				</tr>
				<tr>
					<td align='left' style='text-decoration: underline;'>RUMAH SAKIT BHAYANGKARA TK II MEDAN</td>
				</tr>
			</table>
		</div>
		<div style='text-align: center; font-family: serif; font-size: 12pt; font-weight: bold; text-decoration: underline;'>LAPORAN REKENING BANK</div>
		<div style='text-align: center; font-family: serif; font-size: 10pt; font-weight: bold;'>PER " . tanggal_indonesia_panjang(tanggal_hari_terakhir($tanggal)) . "</div>
		
		<br />
		
		<table border='1' width='100%' style='font-size: 8pt; border-collapse: collapse;' cellspacing='0' cellpadding='1'>
			<thead>
				<tr align='center'>
					<th rowspan='2'>NO</th>
					<th colspan='3'>SATUAN<br />KERJA</th>
					<th colspan='3'>REKENING</th>
					<th colspan='3'>BANK</th>
					<th>KODE</th>
					<th colspan='2'>PERSETUJUAN REKENING</th>
					<th rowspan='2' width='70px'>TGL. TRANSAKSI TERAKHIR</th>
					<th rowspan='2' width='100px'>SALDO AKHIR</th>
					<th rowspan='2' width='70px'>KET</th>
				</tr>
				<tr align='center'>
					<th width='30px'>BA</th>
					<th width='30px'>ES.1</th>
					<th width='50px'>KODE</th>
					
					<th width='100px'>NOMOR</th>
					<th>NAMA</th>
					<th width='50px'>KODE</th>
					
					<th width='50px'>KODE</th>
					<th width='100px'>NAMA</th>
					<th width='120px'>CABANG</th>
					
					<th width='80px'>REKENING</th>
					
					<th width='150px'>NOMOR</th>
					<th width='70px'>TANGGAL</th>
				</tr>
			</thead>
			<tbody>
				" . $tbl . "
			</tbody>
		</table>
		
		<br />
		<br />
		<table width='100%' cellspacing='0' cellpadding='0' style='font-size: 9pt; table-layout: fixed; page-break-inside: avoid;' border='0'>
			<tr>
				<td align='center'>Diketahui Oleh</td>
				<td align='center'>Medan, " . tanggal_indonesia_panjang(tanggal_hari_terakhir($tanggal)) . "</td>
			</tr>
			<tr>
				<td align='center' style='font-weight: bold;' valign='top'>" . $konf_kpa["judul_ttd"] . "</td>
				<td align='center' style='font-weight: bold;' valign='top'>" . $konf_kaurkeu["judul_ttd"] . "</td>
			</tr>
			<tr>
				<td style='height: 70px'></td>
				<td></td>
			</tr>
			<tr>
				<td align='center' style='font-weight: bold; text-decoration: underline;' valign='top'>" . $konf_kpa["nama_pegawai"] . "</td>
				<td align='center' style='font-weight: bold; text-decoration: underline;' valign='top'>" . $konf_kaurkeu["nama_pegawai"] . "</td>
			</tr>
			<tr>
				<td align='center' style='' valign='top'>" . $konf_kpa["pangkat"] . " " . $konf_kpa["sebutan_nrp"] . " " . $konf_kpa["nik"] . "</td>
				<td align='center' style='' valign='top'>" . $konf_kaurkeu["pangkat"] . " " . $konf_kaurkeu["sebutan_nrp"] . " " . $konf_kaurkeu["nik"] . "</td>
			</tr>
		</table>
	";
	
	// instantiate and use the dompdf class
	$dompdf = new Dompdf();
	$dompdf->loadHtml($cetak);
	
	// (Optional) Setup the paper size and orientation
	$dompdf->setPaper('legal', 'landscape');
	
	// Render the HTML as PDF
	$dompdf->render();
	//echo $cetak;
	
	// Output the generated PDF to Browser
	$dompdf->stream("rincian gaji.pdf", array("Attachment" => false));
	//echo $cetak
?>
