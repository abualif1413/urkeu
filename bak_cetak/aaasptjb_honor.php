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
	$rincian = DetailPermohonanDanaBusinessLogic::GetList02($_GET["id"]);
	
	$menyatakan_pecah = explode("\n", $record["menyatakan"]);
	$menyatakan = "";
	foreach ($menyatakan_pecah as $mytk) {
		if(trim($mytk) != "")
			$menyatakan .= "<li style='margin-bottom: 5px;'>" . $mytk . "</li>";
	}
	
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
		<div style='text-align: center; font-family: serif; font-size: 15pt; font-weight: bold;'>Surat pernyataan Pertanggung Jawaban Belanja</div>
		<br /><br />
		<span style='font-size: 11pt;'>Yang bertanda tangan dibawah ini :</span><br /><br />
		<table style='font-size: 11pt;'>
			<tr>
				<td width='150px'>Nama</td>
				<td>:</td>
				<td>" . $record["nama_pegawai"] . "</td>
			</tr>
			<tr>
				<td>NIP</td>
				<td>:</td>
				<td>" . $record["nik"] . "</td>
			</tr>
			<tr>
				<td>Jabatan</td>
				<td>:</td>
				<td>" . $record["jabatan"] . "</td>
			</tr>
		</table>
		<br /><br />
		<span style='font-size: 11pt;'>Menyatakan dengan sesungguhnya :</span><br />
		<ol style='font-size: 11pt;'>
			<li style='margin-bottom: 5px;'>
				Perhitungan yang terdapat pada Pertanggung Jawaban Belanja Untuk Keperluan " . $record["keterangan"] . "
			</li>
			<li>
				Segala hal yang terjadi akibat adanya kelebihan atas pembayaran belanja tersebut menjadi tanggung jawab kami sepenuhnya,
				dan kami bersedia menyetorkan kelebihan tersebut ke Kas Negara.
			</li>
		</ol>
		Demikian pernyataan ini kami buat dengan sebenar-benarnya
		<br /><br /><br />
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
				<td align='center' style='font-weight: bold; text-decoration: underline'>" . $record["nama_pegawai"] . "</td>
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
	$dompdf->stream("sptjb.pdf", array("Attachment" => false));
	//echo $cetak
?>