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
	$dari = $_GET["dari"];
	$sampai = $_GET["sampai"];
	$id_coa = $_GET["id_coa"];
	$bln_lalu = $_GET["bln_lalu"];
	//$tanggal = $_GET["tahun"] . "-" . $_GET["bulan"] . "-01";
	//$periode = $_GET["tahun"] . "-" . $_GET["bulan"];
	$bulan = semua_bulan();
	
	$db_data = new DBConnection();
	if($bln_lalu == 0) {
		$db_data->perintahSQL = "
			SELECT
				1 AS jenis, a.nomor AS no_sppspm, a.tanggal AS tgl_sppspm, a.total AS nilai_sppspm, SUM(b.nilai) nilai_lra,
				d.nomor AS no_spby, d.tanggal AS tgl_spby, a.keterangan
			FROM
				vw_daftar_spp_spm a
				LEFT JOIN itbl_apps_coa_spp_spm b ON a.id = b.id_spp_spm
				LEFT JOIN itbl_main_coa c ON b.id_coa_pagu = c.id
				LEFT JOIN itbl_apps_spby d ON a.id = d.id_spp_spm
			WHERE
				a.tanggal BETWEEN '" . $dari . "' AND '" . $sampai . "'
				AND (
					c.acc_number LIKE CONCAT((SELECT acc_number FROM vw_coa WHERE id = " . $id_coa . "),'.%')
					OR
					c.id = " . $id_coa . "
				)
			GROUP BY
				a.id
			
			UNION 
			
			SELECT
				2 AS jenis, 'Jasa Giro' AS no_sppspm, jg.tanggal, jg.jumlah AS nilai_sppspm, jg.jumlah AS nilai_lra,
				'' AS no_spby, jg.tanggal AS tgl_spby, jg.keterangan
			FROM
				itbl_apps_jasa_giro jg
				LEFT JOIN itbl_main_coa coa ON jg.id_coa_pagu = coa.id
			WHERE
				jg.tanggal BETWEEN '" . $dari . "' AND '" . $sampai . "'
				AND (
					coa.acc_number LIKE CONCAT((SELECT acc_number FROM vw_coa WHERE id = " . $id_coa . "),'.%')
					OR
					coa.id = " . $id_coa . "
				)
			
			ORDER BY
				tgl_sppspm ASC, no_sppspm ASC
		";
	} elseif ($bln_lalu == 1) {
		$db_data->perintahSQL = "
			SELECT
				1 AS jenis, a.nomor AS no_sppspm, a.tanggal AS tgl_sppspm, a.total AS nilai_sppspm, SUM(b.nilai) nilai_lra,
				d.nomor AS no_spby, d.tanggal AS tgl_spby, a.keterangan
			FROM
				vw_daftar_spp_spm a
				LEFT JOIN itbl_apps_coa_spp_spm b ON a.id = b.id_spp_spm
				LEFT JOIN itbl_main_coa c ON b.id_coa_pagu = c.id
				LEFT JOIN itbl_apps_spby d ON a.id = d.id_spp_spm
			WHERE
				a.tanggal BETWEEN '" . $dari . "' AND DATE_ADD('" . $sampai . "',INTERVAL -1 DAY)
				AND (
					c.acc_number LIKE CONCAT((SELECT acc_number FROM vw_coa WHERE id = " . $id_coa . "),'.%')
					OR
					c.id = " . $id_coa . "
				)
			GROUP BY
				a.id
			
			UNION 
			
			SELECT
				2 AS jenis, 'Jasa Giro' AS no_sppspm, jg.tanggal, jg.jumlah AS nilai_sppspm, jg.jumlah AS nilai_lra,
				'' AS no_spby, jg.tanggal AS tgl_spby, jg.keterangan
			FROM
				itbl_apps_jasa_giro jg
				LEFT JOIN itbl_main_coa coa ON jg.id_coa_pagu = coa.id
			WHERE
				jg.tanggal BETWEEN '" . $dari . "' AND DATE_ADD('" . $sampai . "',INTERVAL -1 DAY)
				AND (
					coa.acc_number LIKE CONCAT((SELECT acc_number FROM vw_coa WHERE id = " . $id_coa . "),'.%')
					OR
					coa.id = " . $id_coa . "
				)
			
			ORDER BY
				tgl_sppspm ASC, no_sppspm ASC
		";
	}
	$ds_data = $db_data->execute_reader();
	$isi = "";
	$totalan_sppspm = 0;
	$totalan_lra = 0;
	foreach ($ds_data as $dset) {
		$totalan_sppspm += $dset["nilai_sppspm"];
		$totalan_lra += $dset["nilai_lra"];
		
		$tgl_spby = "";
		$dibayar = 0;
		if($dset["tgl_spby"] != "") {
			$tgl_spby = tanggal_indonesia_pendek($dset["tgl_spby"]);
		}
		
		$no++;
		$isi .= "
			<tr style='page-break-inside: avoid;'>
				<td align='center'>" . $no . "</td>
				<td align='center'>" . $dset["no_sppspm"] . "</td>
				<td align='center'>" . tanggal_indonesia_pendek($dset["tgl_sppspm"]) . "</td>
				<td align='right'>" . number_format($dset["nilai_sppspm"], 2) . "</td>
				<td align='right'>" . number_format($dset["nilai_lra"], 2) . "</td>
				<td align='center'>" . $dset["no_spby"] . "</td>
				<td align='center'>" . $tgl_spby . "</td>
				<td>" . $dset["keterangan"] . "</td>
			</tr>
		";
	}
	$isi .= "
		<tr style='page-break-inside: avoid;'>
			<td align='center'></td>
			<td align='center'>TOTAL</td>
			<td align='center'></td>
			<td align='right'>" . number_format($totalan_sppspm, 2) . "</td>
			<td align='right'>" . number_format($totalan_lra, 2) . "</td>
			<td align='center'></td>
			<td align='center'></td>
			<td></td>
		</tr>
	";
	unset($db_data);
	
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
		<div style='text-align: center; font-family: serif; font-size: 12pt; font-weight: bold; text-decoration: underline;'>LAPORAN RINCIAN REALISASI ANGGARAN</div>
		<div style='text-align: center; font-family: serif; font-size: 12pt; font-weight: bold; text-decoration: underline;'>AKUN PAGU : </div>
		<div style='text-align: center; font-family: serif; font-size: 10pt; font-weight: bold;'>Tanggal " . tanggal_indonesia_panjang($dari) . " S/D " . tanggal_indonesia_panjang($sampai) . "</div>
		
		<br />
		
		<table width='100%' cellspacing='0' cellpadding='1' border='1' style='font-size: 9pt;'>
			<thead>
				<tr>
					<th rowspan='2' width='30px' align='center'>NO</th>
					<th rowspan='2' width='70px' align='center'>SPP/SPM</th>
					<th rowspan='2' width='80px' align='center'>TGL.<br />SPP / SPM</th>
					<th colspan='2' align='center'>NILAI</th>
					<th rowspan='2' width='100px' align='center'>SPBy</th>
					<th rowspan='2' width='70px' align='center'>TGL.<br />SPBy</th>
					<th rowspan='2'>URAIAN</th>
				</tr>
				<tr>
					<th width='90px' align='center'>SPP / SPM</th>
					<th width='90px' align='center'>LRA</th>
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
