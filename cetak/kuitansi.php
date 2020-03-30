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
	$record = PermohonanDanaModel::GetFullRecord01($_GET["id"]);
	$rincian = DetailPermohonanDanaBusinessLogic::GetList02($_GET["id"]);
	
	// Pejabat dari konfigurasi
	$konf_kpa = get_ttd_dokumen(3, "001", $record["tanggal"]);
	$konf_kaurkeu = get_ttd_dokumen(3, "002", $record["tanggal"]);
	
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
	
	$tulisan_nrp_kuitansi = "NRP";
	if($record["id_jenis_pegawai_kuitansi"] == 1)
		$tulisan_nrp_kuitansi = "NRP";
	else if($record["id_jenis_pegawai_kuitansi"] == 2)
		$tulisan_nrp_kuitansi = "NIP";
	else
		$tulisan_nrp_kuitansi = "NIK";
	
	$jumlah_total = 0;
	foreach ($rincian as $rinci) {
		$jumlah = $rinci->jumlah + $rinci->ppn + $rinci->pph;
		$jumlah_total += $jumlah;
	}
	
	$cetak = "
		<style>
			@page { margin: 3.5cm 2cm; }
		</style>
		
		<table width='100%' cellspacing='0' cellpadding='5' style='font-size: 9pt;'>
			<tr>
				<td style='border: solid 1px black;'>
				
					<table width='100%'>
						<tr>
							<td></td>
							<td width='100px'>TA</td>
							<td width='5px'>:</td>
							<td width='200px'>" . substr($record["tanggal"], 0, 4) . "</td>
						</tr>
						<tr>
							<td></td>
							<td>Nomor Bukti</td>
							<td>:</td>
							<td>" . $record["na_nomor"] . "/" . $record["na_bulan"] . "/" . $record["na_tahun"] . "/" . $record["na_divisi"] . "</td>
						</tr>
					</table>
					<br /><br /><br />
					<div style='font-weight: bold; text-align: center; text-decoration: underline;'>KUITANSI / BUKTI PEMBAYARAN</div>
					<br /><br /><br />
					<table width='100%'>
						<tr>
							<td width='100px' valign='top'>Sudah terima dari</td>
							<td width='5px' valign='top'>:</td>
							<td valign='top'>
								Kuasa Pengguna Anggaran / Pejabat Pembuat Komitmen
								SATKER RUMKIT BHAYANGKARA MEDAN
								640384
							</td>
						</tr>
						<tr>
							<td colspan='3'></td>
						</tr>
						<tr>
							<td valign='top'>Jumlah uang</td>
							<td valign='top'>:</td>
							<td valign='top'>Rp. " . number_format($jumlah_total, 0) . "</td>
							
						</tr>
						<tr>
							<td colspan='3'></td>
						</tr>
						<tr>
							<td valign='top'>Terbilang</td>
							<td valign='top'>:</td>
							<td valign='top'>**" . strtoupper(terbilang($jumlah_total)) . " RUPIAH**</td>
							
						</tr>
						<tr>
							<td colspan='3'></td>
						</tr>
						<tr>
							<td valign='top'>Untuk pembayaran</td>
							<td valign='top'>:</td>
							<td valign='top'>" . $record["keterangan"] . "</td>
							
						</tr>
					</table>
					<br /><br />
					<table width='100%' cellspacing='0' cellpadding='0'>
						<tr>
							<td></td>
							<td align='center' width='300px'>Medan, " . tanggal_indonesia_panjang($record["tanggal"]) . "</td>
						</tr>
						<tr>
							<td></td>
							<td align='center' style='font-weight: bold;'>" . $record["jabatan"] . "</td>
						</tr>
						<tr>
							<td style='height: 70px'></td>
							<td></td>
						</tr>
						<tr>
							<td></td>
							<td align='center' style='font-weight: bold; text-decoration: underline;'>" . $record["nama_pegawai"] . "</td>
						</tr>
						<tr>
							<td></td>
							<td align='center'>" . strtoupper($record["pangkat"]) . " " . $tulisan_nrp . " " . $record["nik"] . "</td>
						</tr>
					</table>
					
				</td>
			</tr>
			<tr>
				<td style='border: solid 1px black;'>
				
					<table width='100%' cellspacing='0' cellpadding='0' table-layout: fixed;' border='0'>
						<tr>
							<td align='center'>Setuju dibebankan pada mata anggaran berkenaan</td>
							<td></td>
						</tr>
						<tr>
							<td align='center' style='font-weight: bold;'></td>
							<td align='center'>Lunas dibayar Tgl. ................</td>
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
							<td align='center' style='' valign='top'>" . $konf_kpa["pangkat"] . " NRP " . $konf_kpa["nik"] . "</td>
							<td align='center' style='' valign='top'>" . $konf_kaurkeu["pangkat"] . " NRP " . $konf_kaurkeu["nik"] . "</td>
						</tr>
					</table>
					
				</td>
			</tr>
			<tr>
				<td style='border: solid 1px black;'>
					
					Barang/pekerjaan tersebut telah diterima/diselesaikan dengan lengkap dan baik
					<table width='100%' cellspacing='0' cellpadding='0' table-layout: fixed;' border='0'>
						<tr>
							<td align='center' style='font-weight: bold;' width='300px'>PEJABAT YANG BERTANGGUNG JAWAB</td>
							<td></td>
						</tr>
						<tr>
							<td style='height: 70px'></td>
							<td></td>
						</tr>
						<tr>
							<td align='center' style='font-weight: bold; text-decoration: underline;'>" . $record["nama_pegawai_kuitansi"] . "</td>
							<td></td>
						</tr>
						<tr>
							<td align='center' style=''>" . $record["pangkat_kuitansi"] . " " . $tulisan_nrp_kuitansi . " " . $record["nik_kuitansi"] . "</td>
							<td></td>
						</tr>
					</table>
					
				</td>
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
	$dompdf->stream("kuitansi.pdf", array("Attachment" => false));
	//echo $cetak
?>
