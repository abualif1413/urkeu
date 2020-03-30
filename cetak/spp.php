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
	$konf_001 = get_ttd_dokumen(6, "001", $data_spp_spm["tanggal"]);
	$konf_002 = get_ttd_dokumen(6, "002", $data_spp_spm["tanggal"]);
	
	// Variable dari konfigurasi
	$dipa = get_variable_konfigurasi("dipa", $data_spp_spm["tanggal"]);
	
	// COA SPP SPM
	$isi_table_coa_spp_spm = "";
	$susunan_pagu = PaguService::SusunanPagu($_GET["id"]);
	$kode_fungsi = "";
	$kode_kegiatan = "";
	$no = 0;
	$jumlah_pagu_dalam_dipa = 0;
	$jumlah_pagu_sd_lalu = 0;
	$jumlah_pagu_ini = 0;
	$jumlah_pagu_sd_ini = 0;
	$jumlah_sisa_dana = 0;
	foreach ($susunan_pagu as $sp) {
		$pagu_dalam_dipa = PaguService::GetTotalAnggaran($sp["id_nomor"], substr($data_spp_spm["tanggal"], 0, 4));
		$nilai_sd_lalu = PaguService::GetTotalSDLalu($_GET["id"], $sp["id_nomor"]);
		$jumlah_pagu_dalam_dipa += $pagu_dalam_dipa;
		$jumlah_pagu_sd_lalu += $nilai_sd_lalu;
		$jumlah_pagu_ini += $sp["nilai"];
		$jumlah_pagu_sd_ini += ($nilai_sd_lalu + $sp["nilai"]);
		$jumlah_sisa_dana += ($pagu_dalam_dipa - ($nilai_sd_lalu + $sp["nilai"]));
		$kode_fungsi = $sp["kode_fungsi"];
		$kode_kegiatan = $sp["kode_kegiatan"];
		
		$isi_table_coa_spp_spm .= "
			<tr>
				<td align='right' style='border-top: solid 0px; border-bottom: solid 0px;'>" . (++$no) . "</td>
				<td align='center' style='border-top: solid 0px; border-bottom: solid 0px;'>" . $sp["nomor"] . "</td>
				<td align='right' style='border-top: solid 0px; border-bottom: solid 0px;'>" . number_format($pagu_dalam_dipa, 0) . ",-</td>
				<td align='right' style='border-top: solid 0px; border-bottom: solid 0px;'>" . number_format($nilai_sd_lalu, 0) . ",-</td>
				<td align='right' style='border-top: solid 0px; border-bottom: solid 0px;'>" . number_format($sp["nilai"], 0) . ",-</td>
				<td align='right' style='border-top: solid 0px; border-bottom: solid 0px;'>" . number_format($nilai_sd_lalu + $sp["nilai"], 0) . ",-</td>
				<td align='right' style='border-top: solid 0px; border-bottom: solid 0px;'>" . number_format($pagu_dalam_dipa - ($nilai_sd_lalu + $sp["nilai"]), 0) . ",-</td>
			</tr>
		";
	}
	
	$isi_table_coa_spp_spm .= "
		<tr>
			<td align='right'></td>
			<td>Jumlah</td>
			<td align='right'>" . number_format($jumlah_pagu_dalam_dipa, 0) . ",-</td>
			<td align='right'>" . number_format($jumlah_pagu_sd_lalu, 0) . ",-</td>
			<td align='right'>" . number_format($jumlah_pagu_ini, 0) . ",-</td>
			<td align='right'>" . number_format($jumlah_pagu_sd_ini, 0) . ",-</td>
			<td align='right'>" . number_format($jumlah_sisa_dana, 0) . ",-</td>
		</tr>
	";
	$db = null;
	
	$cetak = "
		<title>SPP</title>
		<style>
			@page { margin: 0.5cm 1cm 0.5cm 0.5cm; }
		</style>
		<div style='margin: 0px; padding: 0px; font-size: 8pt; font-family: sans-serif;'>
			<table width='100%' cellspacing='0' cellpadding='0' style='' border='0'>
				<tr>
					<td></td>
					<td width='270px'>
						<div style='font-weight: bold; text-decoration: underline; font-family: serif; font-size: 11pt; margin-bottom: 5px;'>SURAT PERMINTAAN PEMBAYARAN</div>
						<table cellspacing='0' cellpadding='0' border='0'>
							<tr>
								<td>Tanggal</td>
								<td width='10px' align='center'>:</td>
								<td>" . tanggal_indonesia_panjang($data_spp_spm["tanggal"]) . "&nbsp;&nbsp;&nbsp;Nomor : " . $data_spp_spm["nomor"] . "</td>
							</tr>
							<tr>
								<td>Sifat Pembayaran</td>
								<td align='center'>:</td>
								<td>" . $data_spp_spm["sifat_pembayaran"] . "</td>
							</tr>
							<tr>
								<td>Jenis Pembayaran</td>
								<td align='center'>:</td>
								<td>" . $data_spp_spm["jenis_pembayaran"] . "</td>
							</tr>
						</table>
					</td>
					<td></td>
				</tr>
			</table>
			<div style='height: 30px;'></div>
			<table width='100%' cellspacing='0' cellpadding='0' border='1' style='border-collapse: collapse;'>
				<tr>
					<td style='padding: 5px 5px;'>
						<table width='100%' cellspacing='0' cellpadding='0'>
							<tr>
								<td valign='top'>
									<table width='100%' cellspacing='0' cellpadding='0' border='0'>
										<tr>
											<td width='130px' valign='top'>1. Departemen / Lembaga</td>
											<td width='10px' align='center' valign='top'>:</td>
											<td valign='top'>KEPOLISIAN NEGARA REPUBLIK INDONESIA (060)</td>
										</tr>
										<tr>
											<td valign='top'>2. Unit Organisasi</td>
											<td align='center' valign='top'>:</td>
											<td valign='top'>KEPOLISIAN NEGARA REPUBLIK INDONESIA (01)</td>
										</tr>
										<tr>
											<td valign='top'>3. Kantor / Satker</td>
											<td align='center' valign='top'>:</td>
											<td valign='top'>RUMKIT BHAYANGKARA MEDAN (640384)</td>
										</tr>
										<tr>
											<td valign='top'>4. Lokasi</td>
											<td align='center' valign='top'>:</td>
											<td valign='top'>SUMATERA UTARA (07)</td>
										</tr>
										<tr>
											<td valign='top'>5. Tempat</td>
											<td align='center' valign='top'>:</td>
											<td valign='top'>KOTA MEDAN (51)</td>
										</tr>
										<tr>
											<td valign='top'>6. Alamat</td>
											<td align='center' valign='top'>:</td>
											<td valign='top'>MEDAN</td>
										</tr>
									</table>
								</td>
								<td width='2%'></td>
								<td valign='top' width='45%'>
									<table width='100%' cellspacing='0' cellpadding='0' border='0'>
										<tr>
											<td width='150px' valign='top'>7. Kegiatan</td>
											<td width='10px' align='center' valign='top'>:</td>
											<td valign='top'>Dukungan Pelayanan Internal Polri</td>
										</tr>
										<tr>
											<td valign='top'>8. Kode Kegiatan</td>
											<td align='center' valign='top'>:</td>
											<td valign='top'>" . $kode_kegiatan . "</td>
										</tr>
										<tr>
											<td valign='top'>9. Kode Fungsi</td>
											<td align='center' valign='top'>:</td>
											<td valign='top'>" . $kode_fungsi . "</td>
										</tr>
										<tr>
											<td valign='top'>10. Kewenangan Pelaksanaan</td>
											<td align='center' valign='top'>:</td>
											<td valign='top'>(KD) Kantor Daerah</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td style='padding: 5px 2px; height: 800px;' valign='top'>
						<p style='padding: 0px 5px;'>
							Kepada<br />
							Yth. Pejabat Penandatangan Surat Perintah Membayar<br />
							RUMKIT BHAYANGKARA MEDAN<br />
							di KOTA MEDAN
							<div style='padding: 0px 10px; margin: 0px;'>
								Berdasarkan DIPA Nomor : DIPA-" . $dipa["nilai"] . ", " . tanggal_indonesia_pendek($dipa["tgl_berlaku"]) . ",  bersama ini kami ajukan permintaan pembayaran sebagai berikut : <br />
								<table width='100%' cellspacing='0' cellpadding='0'>
									<tr>
										<td width='200px' valign='top'>1. Jumlah pembayaran yang dimintakan</td>
										<td width='10px' align='center' valign='top'>:</td>
										<td valign='top'>
											Rp. " . number_format($data_spp_spm["total"], 0) . ",-<br />
											(***" . terbilang($data_spp_spm["total"]) . "***)
										</td>
									</tr>
									<tr>
										<td width='200px' valign='top'>2. Untuk keperluan</td>
										<td width='10px' align='center' valign='top'>:</td>
										<td valign='top'>
											" . $data_spp_spm["keterangan"] . "
										</td>
									</tr>
									<tr>
										<td width='200px' valign='top'>3. Jenis Belanja</td>
										<td width='10px' align='center' valign='top'>:</td>
										<td valign='top'>
											
										</td>
									</tr>
									<tr>
										<td width='200px' valign='top'>4. Atas Nama</td>
										<td width='10px' align='center' valign='top'>:</td>
										<td valign='top'>
											RPL 004 RS BHAYANGKARA 640384_2
										</td>
									</tr>
									<tr>
										<td width='200px' valign='top'>5. Alamat</td>
										<td width='10px' align='center' valign='top'>:</td>
										<td valign='top'>
											JL.K.H. Wahid Hasyim No.1 Medan
										</td>
									</tr>
									<tr>
										<td width='200px' valign='top'>6. Mempunyai rekening</td>
										<td width='10px' align='center' valign='top'>:</td>
										<td valign='top'>
											PT.BANK RAKYAT INDONESIA (Persero) Tbk. KC MEDAN I JL. ISKANDAR MUDA NO. 18/173 MEDAN<br />
											Nomor rekening : 0336-01-003221-30.7
										</td>
									</tr>
									<tr>
										<td width='200px' valign='top'>7. Nomor dan Tanggal SPK Kontrak</td>
										<td width='10px' align='center' valign='top'>:</td>
										<td valign='top'>
											
										</td>
									</tr>
									<tr>
										<td width='200px' valign='top'>8. Nilai SPK/Kontrak</td>
										<td width='10px' align='center' valign='top'>:</td>
										<td valign='top'>
											Rp. 0
										</td>
									</tr>
									<tr>
										<td width='200px' valign='top'>9. Dengan penjelasan</td>
										<td width='10px' align='center' valign='top'>:</td>
										<td valign='top'>
											
										</td>
									</tr>
								</table>
								<br />
								<table width='100%' cellspacing='0' cellpadding='1' border='1' style='font-size: 6.5pt;'>
									<tr>
										<td width='20px' align='center'>NO</td>
										<td align='center'>KEGIATAN / OUTPUT / MAK (AKUN 6 DIGIT) BERSANGKUTAN</td>
										<td width='100px' align='center'>PAGU DALAM DIPA / SKPA<br />(Rp.)</td>
										<td width='100px' align='center'>SPP/SPM S.D LALU<br />(Rp.)</td>
										<td width='100px' align='center'>SPP INI<br />(Rp.)</td>
										<td width='100px' align='center'>JUMLAH S.D SPP INI<br />(Rp.)</td>
										<td width='100px' align='center'>SISA DANA<br />(Rp.)</td>
									</tr>
									" . $isi_table_coa_spp_spm . "
									<tr>
										<td colspan='2' align='center'>UANG PERSEDIAAN</td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
									</tr>
								</table>
							</div>
						</p>
						<div style='height: 20px;'></div>
						<div style='padding: 0px 5px;'>
							<table width='100%' cellspacing='0' cellpadding='0'>
								<tr>
									<td></td>
									<td width='10%'></td>
									<td>KOTA MEDAN, Tanggal seperti diatas</td>
								</tr>
								<tr>
									<td>" . $konf_001["judul_ttd"] . "</td>
									<td></td>
									<td>" . $konf_002["judul_ttd"] . "</td>
								</tr>
								<tr>
									<td>Pada tanggal, " . tanggal_indonesia_panjang($data_spp_spm["tanggal"]) . "</td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td style='height: 1.5cm;'></td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td>" . $konf_001["nama_pegawai"] . "</td>
									<td></td>
									<td>" . $konf_002["nama_pegawai"] . "</td>
								</tr>
								<tr>
									<td>" . $konf_001["pangkat"] . " " . $konf_001["sebutan_nrp"] . " " . $konf_001["nik"] . "</td>
									<td></td>
									<td>" . $konf_002["pangkat"] . " " . $konf_002["sebutan_nrp"] . " " . $konf_002["nik"] . "</td>
								</tr>
							</table>
						</div>
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
	$dompdf->stream("spp.pdf", array("Attachment" => false));
	//echo $cetak
?>