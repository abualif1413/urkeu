<?php
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
	$rincian = BelanjaHonorModel::GetTotalList($_GET["id"]);
	
	$jumlah_total = $rincian["jumlah_dibayarkan"];
	$tbl_rincian = "<table width='90%' cellspacing='0' cellpadding='0' style='font-size: 11pt;'>";
		
		$tbl_rincian .= "<tr>";
			$tbl_rincian .= "<td>" . $record["keterangan"] . "</td>";
			$tbl_rincian .= "<td width='10px'>Rp</td>";
			$tbl_rincian .= "<td width='150px' align='right'>" . number_format($jumlah_total, 2, ".", ",") . "</td>";
		$tbl_rincian .= "</tr>";
		
		$tbl_rincian .= "<tr><td colspan='3' style='border-bottom: solid 1px black;'></td></tr>";
		$tbl_rincian .= "<tr style='font-weight: bold; font-style: italic;'>";
			$tbl_rincian .= "<td>Jumlah</td>";
			$tbl_rincian .= "<td>Rp</td>";
			$tbl_rincian .= "<td align='right'>" . number_format($jumlah_total, 2, ".", ",") . "</td>";
		$tbl_rincian .= "</tr>";
		$tbl_rincian .= "<tr><td colspan='3' style='height: 0.2cm;'></td></tr>";
		$tbl_rincian .= "<tr style='font-weight: bold;'>";
			$tbl_rincian .= "<td colspan='3' style='text-transform: capitalize;'>Terbilang : " . terbilang($jumlah_total) . " Rupiah</td>";
		$tbl_rincian .= "</tr>";
	$tbl_rincian .= "</table>";
	
	$cetak = "
		<style>
			@page { margin: 3.5cm 2cm; }
		</style>
		<div style='margin-bottom: 20px; position: fixed; top: -150px; left: 0px; right: 0px; font-size: 10pt;'>
			<div style='height: 1.5cm;'></div>
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
		<div style='font-weight: bold; text-align: center; font-size: 12pt; font-family: serif;'>NOTA AJUAN</div>
		<div style='font-weight: bold; text-align: center; font-size: 12pt; font-family: serif; text-decoration: overline;'>
			Nomor : B/NA-" . $record["na_nomor"] . "/" . $record["na_bulan"] . "/" . $record["na_tahun"] . "/" . $record["na_divisi"] . "
		</div>
		<br /><br /><br />
		<table cellspacing='0' cellpadding='0' style='font-size: 11pt;'>
			<tr>
				<td width='100px'>Kepada</td>
				<td width='10px'>:</td>
				<td>Yth. Kepala Rumkit Bhayangkara Tk. II Medan</td>
			</tr>
			<tr>
				<td colspan='3' style='height: 0.2cm;'></td>
			</tr>
			<tr>
				<td>Dari</td>
				<td>:</td>
				<td>" . $record["jabatan"] . "</td>
			</tr>
			<tr>
				<td colspan='3' style='height: 0.2cm;'></td>
			</tr>
			<tr>
				<td>Perihal</td>
				<td>:</td>
				<td>" . $record["keterangan"] . "</td>
			</tr>
		</table>
		<br />
		<ol style='font-size: 11pt;'>
			<li style='text-align: justify; margin-bottom: 10px;'>
				Rujukan :<br />
				<ol style='list-style-type: lower-alpha;'>
					<li>Dipa badan layanan umum TA. 2018 Rumkit Bhayangkara TK. II Medan Revisi Ke 01 dengan Nomor : SP DIPA-060.01.2.640384/2018 Tanggal 30 April 2018.</li>
					<li>Renja TA. 2018</li>
				</ol>
			</li>
			<li style='text-align: justify; margin-bottom: 10px;'>
				Sehubungan dengan rujukan diatas, bersama ini diajukan kepada KA pengajuan kebutuhan
				<b>" . $record["keterangan"] . "</b>
				Adapun rincian pengajuan sebagai berikut :<br /><br />
				" . $tbl_rincian . "
			</li>
			<li style='text-align: justify; margin-bottom: 10px;'>Demikian untuk menjadi periksa</li>
		</ol>
		<br />
		<table width='100%' cellspacing='0' cellpadding='0' style='font-size: 11pt;'>
			<tr>
				<td></td>
				<td align='center' width='300px'>Medan, " . tanggal_indonesia_panjang($record["tanggal"]) . "</td>
			</tr>
			<tr>
				<td></td>
				<td align='center' style='font-weight: bold;'>" . $record["jabatan"] . "</td>
			</tr>
			<tr>
				<td style='height: 50px'></td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td align='center' style='font-weight: bold; text-decoration: underline;'>" . $record["nama_pegawai"] . "</td>
			</tr>
			<tr>
				<td></td>
				<td align='center'>" . $record["pangkat"] . " NRP " . $record["nik"] . "</td>
			</tr>
		</table>
	";
	
	// instantiate and use the dompdf class
	$dompdf = new Dompdf();
	$dompdf->loadHtml($cetak);
	
	// (Optional) Setup the paper size and orientation
	$dompdf->setPaper('A4', 'portrait');
	
	// Render the HTML as PDF
	$dompdf->render();
	
	// Output the generated PDF to Browser
	$dompdf->stream("nota_dinas.pdf", array("Attachment" => false));
	//echo $cetak
?>