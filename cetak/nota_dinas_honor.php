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
	$rincian = BelanjaHonorModel::GetTotalList($_GET["id"]);
	
	// Variable dari konfigurasi
	$dipa = get_variable_konfigurasi("dipa", $record["tanggal"]);
	$ta = explode("-", $record["tanggal"]);
	
	$tulisan_diketahui_oleh = "Diketahui Oleh";
	
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
	
	if($record["nama_pegawai_diketahui"] == "") {
		$tulisan_diketahui_oleh = "";
		$tulisan_nrp_diketahui = "";
	}
	
	
	$jumlah_total = $rincian["jumlah_bruto"];
	
	/* Kebutuhan barcode */
	$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
	$site_name = $protocol . $_SERVER["SERVER_NAME"] . "/urkeu";
	/* ================= */
	
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
					<td align='left' style='padding-left: 55px;'>DAERAH SUMATERA UTARA</td>
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
				<td>" . $record["no_sptjb"] . "</td>
			</tr>
			<tr>
				<td colspan='3' style='height: 0.2cm;'></td>
			</tr>
			<tr>
				<td>Perihal</td>
				<td>:</td>
				<td style=''><div style='border-bottom: solid 1px black; padding-bottom: 2px;'>" . $record["keterangan"] . "</div></td>
			</tr>
		</table>
		<br />
		<ol style='font-size: 11pt;'>
			<li style='text-align: justify; margin-bottom: 10px;'>
				Rujukan :<br />
				<ol style='list-style-type: lower-alpha;'>
					<li>Dipa badan layanan umum TA. " . $ta[0] . " Rumkit Bhayangkara TK. II Medan Revisi Ke 01 dengan Nomor : SP DIPA-" . $dipa["nilai"] . " Tanggal " . tanggal_indonesia_panjang($dipa["tgl_berlaku"]) . ".</li>
					<li>Renja TA. " . $ta[0] . "</li>
					<li>Keputusan Menteri Keuangan Republik Indonesia No.680/KMK.05/2016 Tentang penetapan Rumah Sakit Bhayangkara Tk II Medan sebagai Instansi PK BLU</li>
				</ol>
			</li>
			<li style='text-align: justify; margin-bottom: 10px;'>
				Sehubungan dengan rujukan diatas, bersama ini diajukan kepada KA pengajuan kebutuhan
				<b>" . $record["keterangan"] . "</b>
				sebesar : Rp. " . number_format($jumlah_total, 0) . " (" . terbilang($jumlah_total) . " rupiah), adapun rincian terlampir.
			</li>
			<li style='text-align: justify; margin-bottom: 10px;'>Demikian untuk menjadi periksa</li>
		</ol>
		<br />
		<table width='100%' cellspacing='0' cellpadding='0' style='font-size: 11pt; table-layout: fixed;'>
			<tr>
				<td align='center'>" . $tulisan_diketahui_oleh . "</td>
				<td align='center'>Medan, " . tanggal_indonesia_panjang($record["tanggal"]) . "</td>
			</tr>
			<tr>
				<td align='center' style='font-weight: bold;'>" . $record["jabatan_diketahui"] . "</td>
				<td align='center' style='font-weight: bold;'>" . $record["jabatan"] . "</td>
			</tr>
			<tr>
				<td style='height: 50px'></td>
				<td></td>
			</tr>
			<tr>
				<td align='center' style='font-weight: bold; text-decoration: underline;'>" . $record["nama_pegawai_diketahui"] . "</td>
				<td align='center' style='font-weight: bold; text-decoration: underline;'>" . $record["nama_pegawai"] . "</td>
			</tr>
			<tr>
				<td align='center' stye='text-transform: uppercase;'>" . strtoupper($record["pangkat_diketahui"]) . " " . $tulisan_nrp_diketahui . " " . $record["nik_diketahui"] . "</td>
				<td align='center' stye='text-transform: uppercase;'>" . strtoupper($record["pangkat"]) . " " . $tulisan_nrp . " " . $record["nik"] . "</td>
			</tr>
		</table>
		<br /><br /><br />
		<b></b><br />
		<img src='" . $site_name . "/models/barcode.php?text=" . $record["barcode"] . "&print=false&size=50&sizefactor=2' />
	";
	
	// instantiate and use the dompdf class
	$dompdf = new Dompdf();
	$dompdf->loadHtml($cetak);
	$dompdf->set_option('isRemoteEnabled', TRUE);
	
	// (Optional) Setup the paper size and orientation
	$dompdf->setPaper('A4', 'portrait');
	
	// Render the HTML as PDF
	$dompdf->render();
	
	// Output the generated PDF to Browser
	$dompdf->stream("nota_dinas.pdf", array("Attachment" => false));
	//echo $cetak
?>