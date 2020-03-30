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
	$record = BelanjaHonorModel::GetFullRecord01($_GET["id"]);
	$rincian = BelanjaHonorModel::GetList03($_GET["id"]);
	
	// Pejabat dari konfigurasi
	$konf_kpa = get_ttd_dokumen(4, "001", $record["tanggal"]);
	$konf_kaurkeu = get_ttd_dokumen(4, "002", $record["tanggal"]);
	
	$tbl = "";
	$no = 0;
	$jumlah_sbu_honor = 0;
	$jumlah_jumlah_bruto = 0;
	$jumlah_pph = 0;
	$jumlah_jumlah_dibayarkan = 0;
	foreach ($rincian as $rinci) {
		$no++;
		$jumlah_sbu_honor += $rinci["sbu_honor"];
		$jumlah_jumlah_bruto += $rinci["jumlah_bruto"];
		$jumlah_pph += $rinci["pph"];
		$jumlah_jumlah_dibayarkan += $rinci["jumlah_dibayarkan"];
		
		if($no % 10 == 1) {
			$tbl .= "<tr style=' page-break-inside: avoid;'>";
		} else {
			$tbl .= "<tr style=' page-break-inside: avoid;'>";
		}		
			$tbl .= "<td align='right' style='height: 1.5cm;'>" . $no . "</td>";
			$tbl .= "<td>" . $rinci["nama_pegawai"] . "</td>";
			$tbl .= "<td>" . $rinci["pangkat"] . " / " . $rinci["nik"] . "</td>";
			$tbl .= "<td>" . $rinci["jabatan_struktural"] . "</td>";
			$tbl .= "<td>" . $rinci["jabatan_pengelola"] . "</td>";
			$tbl .= "<td align='right'>" . number_format($rinci["qty"], 0, ".", ",") . "</td>";
			$tbl .= "<td align='right'>" . number_format($rinci["sbu_honor"], 2, ".", ",") . "</td>";
			$tbl .= "<td align='right'>" . number_format($rinci["jumlah_bruto"], 2, ".", ",") . "</td>";
			$tbl .= "<td align='right'>" . number_format($rinci["pph"], 2, ".", ",") . "</td>";
			$tbl .= "<td align='right'>" . number_format($rinci["jumlah_dibayarkan"], 2, ".", ",") . "</td>";
			$tbl .= "<td>" . $no . " <div style='float: right;'>" . $rinci["no_rekening"] . "</div></td>";
		$tbl .= "</tr>";
	}
	$tbl .= "<tr style='font-style: italic; font-weight: bold;'>";
		$tbl .= "<td colspan='6' align='right'>JUMLAH</td>";
		$tbl .= "<td align='right'>" . number_format($jumlah_sbu_honor, 2, ".", ",") . "</td>";
		$tbl .= "<td align='right'>" . number_format($jumlah_jumlah_bruto, 2, ".", ",") . "</td>";
		$tbl .= "<td align='right'>" . number_format($jumlah_pph, 2, ".", ",") . "</td>";
		$tbl .= "<td align='right'>" . number_format($jumlah_jumlah_dibayarkan, 2, ".", ",") . "</td>";
		$tbl .= "<td align='left'></td>";
	$tbl .= "</tr>";
	
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
		<div style='text-align: center; font-family: serif; font-size: 12pt; font-weight: bold;'>DAFTAR NORMATIF PENERIMAAN " . $record["keterangan"] . "</div>
		<div style='text-align: center; font-family: serif; font-size: 12pt; font-weight: bold; text-decoration: underline;'>SATKER RUMAH SAKIT BHAYANGKARA TK. II MEDAN (640384)</div>
		<br /><br />
		<table width='100%' border='1' cellspacing='0' cellpadding='1' style='border-collapse: collapse; font-size: 8pt; text-transform: uppercase; page-break-inside: auto;'>
			<thead>
				<tr align='center'>
					<th width='30px'>NO</th>
					<th width='150px'>NAMA</th>
					<th width='150px'>PANGKAT/NRP</th>
					<th>JABATAN STRUKTURAL</th>
					<th>JABATAN PENGELOLA</th>
					<th width='50px'>JUMLAH<br />" . strtoupper($record["satuan"]) . "</th>
					<th width='65px'>SBU<br />HONOR</th>
					<th width='65px'>JUMLAH<br />BRUTO</th>
					<th width='55px'>PPh-21</th>
					<th width='70px'>JUMLAH<br />DIBAYARKAN</th>
					<th width='200px'>TANDA<br />TANGAN</th>
				</tr>
				<tr align='center'>
					<th width='30px'>1</th>
					<th>2</th>
					<th>3</th>
					<th>4</th>
					<th>5</th>
					<th>6</th>
					<th>7</th>
					<th>8</th>
					<th>9</th>
					<th>10</th>
					<th>11</th>
				</tr>
			</thead>
			<tbody>
				" . $tbl . "
			</tbody>
		</table>
		<br />
		<table width='100%' cellspacing='0' cellpadding='0' style='font-size: 9pt; table-layout: fixed; page-break-inside:avoid;' border='0'>
			<tr>
				<td align='center'>Mengetahui</td>
				<td align='center'>Medan, " . tanggal_indonesia_panjang($record["tanggal"]) . "</td>
			</tr>
			<tr>
				<td align='center' style='font-weight: bold;' valign='top'>" . $konf_kpa["judul_ttd"] . "</td>
				<td align='center' style='font-weight: bold;' valign='top'>" . $konf_kaurkeu["judul_ttd"] . "</td>
			</tr>
			<tr>
				<td style='height: 30px'></td>
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
	$dompdf->setPaper('A4', 'landscape');
	
	// Render the HTML as PDF
	$dompdf->render();
	$font = $dompdf->getFontMetrics()->get_font("helvetica", "bold");
	$dompdf->getCanvas()->page_text(10, 570, "Halaman: {PAGE_NUM} dari {PAGE_COUNT}", $font, 8, array(0,0,0));
	
	// Output the generated PDF to Browser
	$dompdf->stream("sptjb rincian.pdf", array("Attachment" => false));
	//echo $cetak
?>
