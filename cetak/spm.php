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
	/*$db = new DBConnection();
	$db->perintahSQL = "
		SELECT
			a.id, a.tanggal, a.nomor, a.jenis_pembayaran, a.sifat_pembayaran,
			b.total, b.keterangan, b.jenis_belanja
		FROM
			itbl_apps_spp_spm a
			LEFT JOIN vw_belanja_barang b ON a.id_belanja = b.id
		WHERE
			a.id = ?
	";
	$db->add_parameter("i", $_GET["id"]);
	$ds = $db->execute_reader();
	$data_spp_spm = $ds[0];
	$db = null;*/
	
	$sql = "
		SELECT
			a.id, a.tanggal, a.nomor, a.jenis_pembayaran, a.sifat_pembayaran,
			a.total, a.keterangan, a.jenis_belanja
		FROM
			vw_daftar_spp_spm a
		WHERE
			a.id = '" . $_GET["id"] . "'
	";
	$res = mysqli_query($app_conn, $sql);
	$ds = mysqli_fetch_assoc($res);
	$data_spp_spm = $ds;
	
	// Pejabat dari konfigurasi
	$konf_002 = get_ttd_dokumen(7, "002", $data_spp_spm["tanggal"]);
	
	// Variable dari konfigurasi
	$dipa = get_variable_konfigurasi("dipa", $data_spp_spm["tanggal"]);
	$dasar_pembayaran = get_variable_konfigurasi("dasarpembayaran", $data_spp_spm["tanggal"]);
	
	// COA SPP SPM
	$isi_table_coa_spp_spm = "";
	$susunan_pagu = PaguService::SusunanPagu($_GET["id"]);
	$kode_fungsi = "";
	$kode_kegiatan = "";
	$no = 0;
	$jumlah_pagu_sd_lalu = 0;
	$jumlah_pagu_ini = 0;
	$jumlah_pagu_sd_ini = 0;
	$nomor_pagu = "";
	$table_data = "";
	$ctr_baris = 0;
	foreach ($susunan_pagu as $sp) {
		$ctr_baris++;
		$nilai_sd_lalu = PaguService::GetTotalSDLalu($_GET["id"], $sp["id_nomor"]);
		$jumlah_pagu_sd_lalu += $nilai_sd_lalu;
		$jumlah_pagu_ini += $sp["nilai"];
		$jumlah_pagu_sd_ini += ($nilai_sd_lalu + $sp["nilai"]);
		$kode_fungsi = $sp["kode_fungsi"];
		$kode_kegiatan = $sp["kode_kegiatan"];
		$nomor_pagu = $sp["nomor"];
		
		$table_data .= "
			<tr>
				<td align='center' style='border-left: solid 1px black; padding: 1px 2px;'>" . $sp["nomor"] . "</td>
				<td align='right' style='border-left: solid 1px black; padding: 1px 2px;'>" . number_format($sp["nilai"], 0) . ", -</td>
				<td style='border-left: solid 1px black; padding: 1px 2px;'></td>
				<td style='border-left: solid 1px black; padding: 1px 2px;'></td>
			</tr>
		";
	}
	
	$max_row = 20;
	if($ctr_baris < $max_row) {
		for($i = 1; $i<=($max_row - $ctr_baris); $i++) {
			$table_data .= "
				<tr>
					<td align='center' style='border-left: solid 1px black; padding: 1px 2px;'>&nbsp;</td>
					<td align='right' style='border-left: solid 1px black; padding: 1px 2px;'>&nbsp;</td>
					<td style='border-left: solid 1px black; padding: 1px 2px;'>&nbsp;</td>
					<td style='border-left: solid 1px black; padding: 1px 2px;'>&nbsp;</td>
				</tr>
			";
		}
	}
	
	$table_data .= "
		<tr>
			<td align='center' style='border: solid 1px black; padding: 1px 2px;'>Jumlah Pengeluaran</td>
			<td align='right' style='border: solid 1px black; padding: 1px 2px;'>" . number_format($jumlah_pagu_ini, 0) . ", -</td>
			<td style='border: solid 1px black; padding: 1px 2px;'>Jumlah Potongan</td>
			<td style='border: solid 1px black; padding: 1px 2px;'></td>
		</tr>
	";
	
	$kode_fungsi_explode = explode(".", $kode_fungsi);
	$nomor_pagu_explode = explode(" . ", $nomor_pagu);
	
	/* Kebutuhan barcode */
	$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
	$site_name = $protocol . $_SERVER["SERVER_NAME"] . "/urkeu";
	/* ================= */
	
	$cetak = "
		<title>SPP</title>
		<style>
			@page { margin: 0.5cm 1cm 0.5cm 0.5cm; }
		</style>
		<div style='margin: 0px; padding: 0px; font-size: 8pt; font-family: sans-serif;'>
			<table width='100%' cellspacing='0' cellpadding='0' border='1'>
				<tr>
					<td style='padding: 2px 0px;' valign='top'>
						<div style='font-weight: bold; font-size: 9pt; text-align: center;'>KEPOLISIAN NEGARA REPUBLIK INDONESIA</div>
						<div style='font-weight: bold; font-size: 12pt; text-align: center;'>SURAT PERINTAH MEMBAYAR</div>
						<div style='font-weight: bold; font-size: 12pt; text-align: center;'>Tanggal : " . tanggal_indonesia_panjang($data_spp_spm["tanggal"]) . "&nbsp;&nbsp;&nbsp; Nomor : " . $data_spp_spm["nomor"] . "</div>
					</td>
				</tr>
				<tr>
					<td style='padding: 2px 2px;' valign='top'>
						Kuasa Bendahara Umum Negara, Kantor Pelayanan Perbendaharaan Negara M E D A N   I (004)
					</td>
				</tr>
				<tr>
					<td style='padding: 2px 2px;' valign='top'>
						Agar melakukan pembayaran sejumlah Rp. " . number_format($data_spp_spm["total"]) . "
					</td>
				</tr>
				<tr>
					<td style='padding: 2px 2px;' valign='top'>
						***" . terbilang($data_spp_spm["total"]) . "***
					</td>
				</tr>
				<tr>
					<td style='padding: 2px 2px;' valign='top'>
						<table width='100%' cellspacing='0' cellpadding='0' style='table-layout: fixed;'>
							<tr>
								<td align='center'>
									Jenis SPM : 07 LANGSUNG
								</td>
								<td align='center'>
									Cara Bayar : 2 GIRO BANK
								</td>
								<td align='center'>
									Tahun Anggaran : " . substr($data_spp_spm["tanggal"], 0, 4) . "
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td style='padding: 2px 2px;' valign='top'>
						<table width='100%'>
							<tr>
								<td width='40%' style='padding: 2px; text-align: left;' valign='top'>
									Dasar Pembayaran<br />
									" . $dasar_pembayaran["nilai"] . "<br />
									NO.DIPA-" . $dipa["nilai"] . "
								</td>
								<td style='padding: 2px;' valign='top'>
									<table width='100%' cellspacing='0' cellpadding='0'>
										<tr style='font-weight: bold'>
											<td width='100px'>Satker</td>
											<td width='100px'>Kewenangan</td>
											<td>Nama Satker</td>
										</tr>
										<tr>
											<td>640384</td>
											<td>KD</td>
											<td>RUMKIR BHAYANGKARA MEDAN</td>
										</tr>
									</table>
									<div style='height: 20px'></div>
									<table width='100%' cellspacing='0' cellpadding='0' style='table-layout: fixed;'>
										<tr style='font-weight: bold'>
											<td>Fungsi,</td>
											<td>Sub Fungsi,</td>
											<td>BA,</td>
											<td>Unit ES. I,</td>
											<td>Program</td>
										</tr>
										<tr>
											<td>03</td>
											<td>" . $kode_fungsi_explode[2] . "</td>
											<td>" . $kode_fungsi_explode[0] . "</td>
											<td>" . $kode_fungsi_explode[1] . "</td>
											<td>" . $kode_fungsi_explode[2] . "</td>
										</tr>
									</table>
									<table width='100%' cellspacing='0' cellpadding='0' style='table-layout: fixed;'>
										<tr style='font-weight: bold'>
											<td>Kegiatan,</td>
											<td>Output,</td>
											<td>Lokasi,</td>
										</tr>
										<tr>
											<td>" . $kode_kegiatan . "</td>
											<td>" . $nomor_pagu_explode[1] . "</td>
											<td>07.51</td>
										</tr>
									</table>
									<table width='100%' cellspacing='0' cellpadding='0'>
										<tr>
											<td width='170px'>Jenis Pembayaran</td>
											<td width='10px' align='center'>:</td>
											<td>" . $data_spp_spm["jenis_pembayaran"] . "</td>
										</tr>
										<tr>
											<td>Sifat Pembayaran</td>
											<td align='center'>:</td>
											<td>" . $data_spp_spm["sifat_pembayaran"] . "</td>
										</tr>
										<tr>
											<td style='height: 50px;' valign='top'>Sumber Dana / Cara Penarikan</td>
											<td align='center' valign='top'>:</td>
											<td valign='top'>06.0 BLU / BLU</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td valign='top'>
						<table width='100%' cellspacing='0' cellpadding='0' border='0' style='border-collapse: collapse;'>
							<tr>
								<td colspan='2' align='center' style='border: solid 1px black;'>PENGELUARAN</td>
								<td colspan='2' align='center' style='border: solid 1px black;'>POTONGAN</td>
							</tr>
							<tr>
								<td width='25%' align='center' style='border: solid 1px black;'>Jenis Belanja</td>
								<td width='25%' align='center' style='border: solid 1px black;'>Jumlah Uang</td>
								<td width='25%' align='center' style='border: solid 1px black;'>BA.Unit.Lok.Akun.Satker</td>
								<td width='25%' align='center' style='border: solid 1px black;'>Jumlah Uang</td>
							</tr>
							" . $table_data . "
							<tr>
								<td colspan='3' align='right'>Rp.</td>
								<td style='border: solid 1px black; padding: 1px 2px;' align='right'>" . number_format($data_spp_spm["total"], 0) . ", -</td>
							</tr>
						</table>
						<div style='height: 20px;'></div>
						<div style='padding: 2px 5px;'>
							<table width='100%' cellspacing='0' cellpadding='0'>
								<tr>
									<td width='150px'>Kepada</td>
									<td width='10px' align='center'>:</td>
									<td>
										RPL 004 RS BHAYANGKARA 640384_2
									</td>
								</tr>
								<tr>
									<td>NPWP</td>
									<td align='center'>:</td>
									<td>
										00.028.829.0-121.000
									</td>
								</tr>
								<tr>
									<td>Rekening</td>
									<td align='center'>:</td>
									<td>
										0336-01-003221-30.7
									</td>
								</tr>
								<tr>
									<td>Bank / Pos</td>
									<td align='center'>:</td>
									<td>
										PT.BANK RAKYAT INDONESIA (Persero) Tbk. KC MEDAN I JL. ISKANDAR MUDA NO. 18/173 MEDAN
									</td>
								</tr>
								<tr>
									<td style='height: 100px;' valign='top'>Uraian</td>
									<td align='center' valign='top'>:</td>
									<td valign='top'>
										" . $data_spp_spm["keterangan"] . "
									</td>
								</tr>
							</table>
						</div>
					</td>
				</tr>
				<tr>
					<td style='padding: 2px 2px;' valign='top'>
						<table width='100%' cellspacing='0' cellpadding='0'>
							<tr>
								<td width='50%' valign='top'>
									<ul style='margin-left: -20px; font-size: 7pt;'>
										<li>
											Semua bukti-bukti pendukung untuk Belanja Pegawai telah diuji dan dinyatakan memenuhi persyaratan
											untuk dilakukan pembayaran atas beban APBN, selanjutnya bukti-bukti pendukung dimaksud disimpan dan
											ditatausahakan oleh Pejabat Penandatangan SPM.
										</li>
										<li>
											Kebenaran perhitungan dan isi yang tertuang dalam SPM ini menjadi tanggung jawab Pejabat Penandatangan SPM.
										</li>
									</ul>
									<b></b><br />
									<img src='" . $site_name . "/models/barcode.php?text=" . $data_spp_spm["id"] . "&print=false&size=50&sizefactor=2' />
									<br />
								</td>
								<td valign='top'>
									<table width='100%' cellspacing='0' cellpadding='0'>
										<tr align='center'>
											<td>KOTA MEDAN, " . tanggal_indonesia_panjang($data_spp_spm["tanggal"]) . "</td>
										</tr>
										<tr align='center'>
											<td>" . $konf_002["judul_ttd"] . "</td>
										</tr>
										<tr>
											<td style='height: 1.5cm;'></td>
										</tr>
										<tr align='center'>
											<td>" . $konf_002["nama_pegawai"] . "</td>
										</tr>
										<tr align='center'>
											<td>" . $konf_002["pangkat"] . " " . $konf_002["sebutan_nrp"] . " " . $konf_002["nik"] . "</td>
										</tr>
									</table>
								</td>
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
	$dompdf->set_option('isRemoteEnabled', TRUE);
	
	// (Optional) Setup the paper size and orientation
	$dompdf->setPaper('A4', 'portrait');
	
	// Render the HTML as PDF
	$dompdf->render();
	
	// Output the generated PDF to Browser
	$dompdf->stream("spp.pdf", array("Attachment" => false));
	//echo $cetak
?>