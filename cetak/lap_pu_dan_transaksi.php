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
	//$tanggal = $_GET["tahun"] . "-" . $_GET["bulan"] . "-01";
	//$periode = $_GET["tahun"] . "-" . $_GET["bulan"];
	$bulan = semua_bulan();
	
	// Pejabat dari konfigurasi
	$konf_kpa = get_ttd_dokumen(11, "002", $sampai);
	
	$isi = "";
	
	$db = new DBConnection();
	$db->perintahSQL = "
		SELECT
			c.id, c.nomor AS nomor_spp_spm, c.tanggal AS tgl_spp_spm,
			c.total, c.ppn, c.pph,
			COALESCE(d.nomor, '') AS nomor_spby, COALESCE(d.tanggal, '') AS tgl_spby,
			c.keterangan
		FROM
			itbl_apps_pu a
			LEFT JOIN itbl_apps_pu_detail b ON a.id = b.id_pu
			INNER JOIN vw_daftar_spp_spm c ON b.id_spp_spm = c.id
			LEFT JOIN itbl_apps_spby d ON c.id = d.id_spp_spm
		WHERE
			a.tanggal BETWEEN ? AND ?
		ORDER BY
			c.nomor ASC
	";
	$db->add_parameter("s", $dari);
	$db->add_parameter("s", $sampai);
	$ds = $db->execute_reader();
	$no = 0;
	$jumlah_bruto = 0;
	$jumlah_ppn = 0;
	$jumlah_pph = 0;
	$jumlah_dibayarkan = 0;
	foreach ($ds as &$dset) {
		$tgl_spby = "";
		$dibayar = 0;
		if($dset["tgl_spby"] != "") {
			$tgl_spby = tanggal_indonesia_pendek($dset["tgl_spby"]);
			$dibayar = $dset["total"];
		}
		
		$jumlah_bruto += $dset["total"] - $dset["ppn"] - $dset["pph"];
		$jumlah_ppn += $dset["ppn"];
		$jumlah_pph += $dset["pph"];
		$jumlah_dibayarkan += $dibayar;
		
		$no++;
		$isi .= "
			<tr style='page-break-inside: avoid;'>
				<td align='center'>" . $no . "</td>
				<td align='center'>" . $dset["nomor_spp_spm"] . "</td>
				<td align='center'>" . tanggal_indonesia_pendek($dset["tgl_spp_spm"]) . "</td>
				<td align='right'>" . number_format($dset["total"] - $dset["ppn"] - $dset["pph"], 2) . "</td>
				<td align='right'>" . number_format($dset["ppn"], 2) . "</td>
				<td align='right'>" . number_format($dset["pph"], 2) . "</td>
				<td align='center'>" . $dset["nomor_spby"] . "</td>
				<td align='center'>" . $tgl_spby . "</td>
				<td align='right'>" . number_format($dibayar, 2) . "</td>
				<td>" . $dset["keterangan"] . "</td>
			</tr>
		";
	}
	$isi .= "
		<tr style='font-weight: bold; page-break-inside: avoid;'>
			<td colspan='3'>JUMLAH PERGESERAN UANG</td>
			<td align='right'>" . number_format($jumlah_bruto, 2) . "</td>
			<td align='right'>" . number_format($jumlah_ppn, 2) . "</td>
			<td align='right'>" . number_format($jumlah_pph, 2) . "</td>
			<td colspan='2'>JUMLAH DIBAYARKAN</td>
			<td align='right'>" . number_format($jumlah_dibayarkan, 2) . "</td>
			<td></td>
		</tr>
	";
	$db = null;
	
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
		<div style='text-align: center; font-family: serif; font-size: 12pt; font-weight: bold; text-decoration: underline;'>LAPORAN TRANSAKSI UANG</div>
		<div style='text-align: center; font-family: serif; font-size: 10pt; font-weight: bold;'>Tanggal " . tanggal_indonesia_panjang($dari) . " S/D " . tanggal_indonesia_panjang($sampai) . "</div>
		
		<br />
		
		<table width='100%' cellspacing='0' cellpadding='1' border='1' style='font-size: 9pt;'>
			<thead>
				<tr>
					<th rowspan='2' width='30px' align='center'>NO</th>
					<th rowspan='2' width='70px' align='center'>SPP/SPM</th>
					<th rowspan='2' width='80px' align='center'>TGL.<br />SPP / SPM</th>
					<th colspan='3' align='center'>NOMINAL SPP / SPM</th>
					<th rowspan='2' width='100px' align='center'>SPBy</th>
					<th rowspan='2' width='70px' align='center'>TGL.<br />SPBy</th>
					<th rowspan='2' width='90px' align='center'>BAYAR</th>
					<th rowspan='2'>URAIAN</th>
				</tr>
				<tr>
					<th width='90px' align='center'>BAYAR</th>
					<th width='90px' align='center'>PPN</th>
					<th width='90px' align='center'>PPh</th>
				</tr>
			</thead>
			<tbody>
				" . $isi . "
			</tbody>
		</table>
		<br />
		<br />
		<table width='100%' cellspacing='0' cellpadding='0' style='font-size: 9pt; table-layout: fixed; page-break-inside: avoid;' border='0'>
			<tr>
				<td align='center'></td>
				<td align='center'>Medan, " . tanggal_indonesia_panjang($sampai) . "</td>
			</tr>
			<tr>
				<td align='center' style='font-weight: bold;' valign='top'></td>
				<td align='center' style='font-weight: bold;' valign='top'>" . $konf_kpa["judul_ttd"] . "</td>
			</tr>
			<tr>
				<td style='height: 70px'></td>
				<td></td>
			</tr>
			<tr>
				<td align='center' style='font-weight: bold; text-decoration: underline;' valign='top'></td>
				<td align='center' style='font-weight: bold; text-decoration: underline;' valign='top'>" . $konf_kpa["nama_pegawai"] . "</td>
			</tr>
			<tr>
				<td align='center' style='' valign='top'></td>
				<td align='center' style='' valign='top'>" . $konf_kpa["pangkat"] . " " . $konf_kpa["sebutan_nrp"] . " " . $konf_kpa["nik"] . "</td>
			</tr>
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
