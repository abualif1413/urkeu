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
	
	// Data SPP SPM
	$db = new DBConnection();
	$db->perintahSQL = "
		SELECT
			a.id, a.tanggal, a.nomor, a.jenis_pembayaran, a.sifat_pembayaran,
			a.total, a.keterangan, a.jenis_belanja, a.nomor_na AS nomor_belanja,
			c.tanggal AS tgl_spby, c.nomor AS nomor_spby, c.kepada, c.setuju_lunas, c.penerima,
			pangkat_penerima, nik_penerima, sebutan_nik_penerima
		FROM
			vw_daftar_spp_spm a
			LEFT JOIN itbl_apps_spby c ON a.id = c.id_spp_spm
		WHERE
			a.id = ?
	";
	$db->add_parameter("i", $_GET["id"]);
	$ds = $db->execute_reader();
	$data_spp_spm = $ds[0];
	$db = null;
	
	// Pejabat dari konfigurasi
	$konf_001 = get_ttd_dokumen(8, "001", $data_spp_spm["tgl_spby"]);
	$konf_003 = get_ttd_dokumen(8, "003", $data_spp_spm["tgl_spby"]);
	
	// COA SPP SPM
	$isi_table_coa_spp_spm = "";
	$susunan_pagu = PaguService::SusunanPagu($_GET["id"]);
	$kode_fungsi = "";
	$kode_kegiatan = "";
	$no = 0;
	$jumlah_pagu_sd_lalu = 0;
	$jumlah_pagu_ini = 0;
	$jumlah_pagu_sd_ini = 0;
	$kode_pagu = array();
	foreach ($susunan_pagu as $sp) {
		$nilai_sd_lalu = PaguService::GetTotalSDLalu($_GET["id"], $sp["id_nomor"]);
		$jumlah_pagu_sd_lalu += $nilai_sd_lalu;
		$jumlah_pagu_ini += $sp["nilai"];
		$jumlah_pagu_sd_ini += ($nilai_sd_lalu + $sp["nilai"]);
		$kode_fungsi = $sp["kode_fungsi"];
		$kode_kegiatan = $sp["kode_kegiatan"];
		
		$temp_kode_pagu = explode(" . ", $sp["nomor"]);
		array_push($kode_pagu, $temp_kode_pagu[2]);
	}
	$db = null;
	
	// Pejabat Penandatangan
	$pejabat_bendahara = get_pejabat_konfigurasi("kaurkeu", $data_spp_spm["tanggal"]);
	$pejabat_kpa = get_pejabat_konfigurasi("kpa", $data_spp_spm["tanggal"]);
	
	$cetak = "
		<title>SPP</title>
		<style>
			@page { margin: 0.5cm 1cm 0.5cm 0.5cm; }
		</style>
		<div style='margin: 0px; padding: 0px; font-size: 9pt; font-family: sans-serif;'>
			<table width='100%' cellspacing='0' cellpadding='3' border='1'>
				<tr>
					<td style='border-bottom: double 3px;'>
						<div style='text-align: center; font-weight: bold; font-size: 105%;'>KEPOLISIAN NEGARA REPUBLIK INDONESIA</div>
						<div style='text-align: center; font-weight: bold; font-size: 110%; text-decoration: underline;'>RUMKIT BHAYANGKARA MEDAN</div>
						<br />
						<div style='text-align: center; font-weight: bold; font-size: 105%; text-decoration: underline;'>SURAT PERINTAH BAYAR</div>
						<br />
						<div style='text-align: center;'>
							Tanggal : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							Nomor : " . $data_spp_spm["nomor_spby"] . "
						</div>
						<br /><br />
					</td>
				</tr>
				<tr>
					<td style='line-height: 20px;'>
						Saya yang bertanda tangan di bawah ini selaku Pejabat Pembuat Komitmen memerintahkan<br />
						Bendahara Pengeluaran agar melakukan pembayaran sejumlah :<br />
						Rp. " . number_format($data_spp_spm["total"], 0) . ",-
					</td>
				</tr>
				<tr>
					<td style='text-transform: uppercase; border-bottom: double 3px;'>
						(***" . terbilang($data_spp_spm["total"]) . "***)
						<br /><br />
					</td>
				</tr>
				<tr>
					<td style='border-bottom: double 3px; line-height: 20px;'>
						<table>
							<tr>
								<td>Kepada</td>
								<td>:</td>
								<td>" . $data_spp_spm["kepada"] . "</td>
							</tr>
							<tr>
								<td>Untuk Pembayaran</td>
								<td>:</td>
								<td>" . $data_spp_spm["keterangan"] . "</td>
							</tr>
						</table>
						<br /><br /><br />
						Atas Dasar :
						<table>
							<tr>
								<td width='250px'>1. Kuitansi / Bukti Pembelian</td>
								<td>:</td>
								<td>" . $data_spp_spm["nomor_belanja"] . "</td>
							</tr>
							<tr>
								<td>2. Nota / Bukti Penerimaan Barang / Jasa /<br />(Bukti Lainnya)</td>
								<td>:</td>
								<td></td>
							</tr>
						</table>
						<br /><br /><br />
						Dibebankan Pada :
						<table>
							<tr>
								<td>Kegiatan, Output,  MAK</td>
								<td>:</td>
								<td>" . $kode_kegiatan . "</td>
							</tr>
							<tr>
								<td>Kode</td>
								<td>:</td>
								<td>" . implode(", ", $kode_pagu) . "</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td style='line-height: 20px;'>
						<table width='100%'>
							<tr>
								<td></td>
								<td></td>
								<td>Medan, &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
							</tr>
							<tr>
								<td>" . $konf_001["judul_ttd"] . " &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
								<td>Diterima tanggal &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
								<td>" . $konf_003["judul_ttd"] . "</td>
							</tr>
							<tr>
								<td style='height: 2cm;'></td>
								<td></td>
								<td></td>
							</tr>
							<tr>
								<td>" . $konf_001["nama_pegawai"] . "</td>
								<td>" . $data_spp_spm["penerima"] . "</td>
								<td>" . $konf_003["nama_pegawai"] . "</td>
							</tr>
							<tr>
								<td>" . $konf_001["pangkat"] . " " . $konf_001["sebutan_nrp"] . " " . $konf_001["nik"] . "</td>
								<td>" . $data_spp_spm["pangkat_penerima"] . " " . $data_spp_spm["sebutan_nik_penerima"] . " " . $data_spp_spm["nik_penerima"] . "</td>
								<td>" . $konf_003["pangkat"] . " " . $konf_003["sebutan_nrp"] . " " . $konf_003["nik"] . "</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</div>
	";
	
	// instantiate and use the dompdf class
	$dompdf = new Dompdf();
	$dompdf->loadHtml($cetak);
	
	// (Optional) Setup the paper size and orientation
	$dompdf->setPaper('A4', 'portrait');
	
	// Render the HTML as PDF
	$dompdf->render();
	
	// Output the generated PDF to Browser
	$dompdf->stream("spby.pdf", array("Attachment" => false));
	//echo $cetak
?>