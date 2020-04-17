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
	
	$db_pilih = new DBConnection();
	$db_pilih->perintahSQL = "
		SELECT
			sppspm.id,
			sppspm.nomor AS no_sppspm,
			sppspm.nomor_na,
			sppspm.tanggal,
			sppspm.keterangan,
			SUM(
				CASE
					WHEN setpajak.jenis = 'ppn' THEN sppspm.ppn
					ELSE 0
				END
			) AS ppn,
			SUM(
				CASE
					WHEN setpajak.jenis = 'pph' THEN sppspm.pph
					ELSE 0
				END
			) AS pph
		FROM
			vw_daftar_spp_spm sppspm
			INNER JOIN itbl_apps_penyetoran_pajak_detail setpajak ON sppspm.id = setpajak.id_spp_spm
		WHERE
			setpajak.id_penyetoran_pajak = ?
		GROUP BY
			sppspm.id
		ORDER BY
			sppspm.tanggal ASC, sppspm.nomor ASC
	";
	$db_pilih->add_parameter("i", $_GET["id"]);
	$ds_pilih = $db_pilih->execute_reader();
	$isi = "";
	$total_ppn = 0;
	$total_pph = 0;
	$total_seluruh = 0;
	foreach ($ds_pilih as $pilih) {
		$total_ppn += $pilih["ppn"];
		$total_pph += $pilih["pph"];
		$total_seluruh += ($pilih["ppn"] + $pilih["pph"]);
		
		$isi .= "
			<tr>
				<td>" . $pilih["nomor_urut_data"] . "</td>
				<td>" . $pilih["no_sppspm"] . "</td>
				<td align='center'>" . tanggal_indonesia_pendek($pilih["tanggal"]) . "</td>
				<td>" . $pilih["nomor_na"] . "</td>
				<td>" . $pilih["keterangan"] . "</td>
				<td align='right'>" . number_format($pilih["ppn"], 2) . "</td>
				<td align='right'>" . number_format($pilih["pph"], 2) . "</td>
				<td align='right'>" . number_format($pilih["ppn"] + $pilih["pph"], 2) . "</td>
			</tr>
		";
	}
	$isi .= "
		<tr>
			<td colspan='5'></td>
			<td align='right'>" . number_format($total_ppn, 2) . "</td>
			<td align='right'>" . number_format($total_pph, 2) . "</td>
			<td align='right'>" . number_format($total_seluruh, 2) . "</td>
		</tr>
	";
	unset($db_pilih);
	
	$db_head = new DBConnection();
	$db_head->perintahSQL = "SELECT * FROM itbl_apps_penyetoran_pajak WHERE id=?";
	$db_head->add_parameter("i", $_GET["id"]);
	$head = $db_head->execute_reader();
	unset($db_head);
	
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
		<div style='text-align: center; font-family: serif; font-size: 12pt; font-weight: bold; text-decoration: underline;'>LAPORAN RINCIAN PENYETORAN PAJAK</div>
		<div style='text-align: center; font-family: serif; font-size: 10pt; font-weight: bold;'>Tanggal " . tanggal_indonesia_panjang($head[0]["tanggal"]) . "</div>
		
		<br />
		
		<table width='100%' cellspacing='0' cellpadding='1' border='1' style='font-size: 9pt;'>
			<thead>
				<tr>
					<th width='30px' align='center'>NO</th>
					<th width='70px' align='center'>SPP/SPM</th>
					<th width='80px' align='center'>TGL.<br />SPP / SPM</th>
					<th width='200px' align='center'>NO. N/A</th>
					<th>URAIAN</th>
					<th width='100px' align='center'>PPN</th>
					<th width='100px' align='center'>PPh</th>
					<th width='100px' align='center'>TOTAL</th>
				</tr>
			</thead>
			<tbody>
				" . $isi . "
			</tbody>
		</table>
	";
	
	// instantiate and use the dompdf class
	$dompdf = new Dompdf();
	$dompdf->loadHtml($cetak);
	
	// (Optional) Setup the paper size and orientation
	$dompdf->setPaper('a4', 'landscape');
	
	// Render the HTML as PDF
	$dompdf->render();
	//echo $cetak;
	
	// Output the generated PDF to Browser
	$dompdf->stream("Laporan PU dan Transaksi.pdf", array("Attachment" => false));
	//echo $cetak
?>
