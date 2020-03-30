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
	
	$menyatakan_pecah = explode("\n", $record["menyatakan"]);
	$menyatakan = "";
	foreach ($menyatakan_pecah as $mytk) {
		if(trim($mytk) != "")
			$menyatakan .= "<li style='margin-bottom: 5px;'>" . $mytk . "</li>";
	}
	
	$jumlah_total = $rincian["jumlah_dibayarkan"];
		
	
	$tulisan_nrp = "NRP";
	if($record["id_jenis_pegawai"] == 1)
		$tulisan_nrp = "NRP";
	else if($record["id_jenis_pegawai"] == 2)
		$tulisan_nrp = "NIP";
	else
		$tulisan_nrp = "NIK";
	
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
					<td align='left' style='padding-left: 50px;'>DAERAH SUMATERA UTARA</td>
				</tr>
				<tr>
					<td align='left' style='text-decoration: underline;'>RUMAH SAKIT BHAYANGKARA TK II MEDAN</td>
				</tr>
			</table>
		</div>
		<div style='text-align: center; font-size: 15pt; font-weight: bold; text-decoration: underline;'>Surat pernyataan Tanggung Jawab Belanja Uang Persediaan</div>
		<br /><br />
		<span style='font-size: 11pt;'>Yang bertanda tangan dibawah ini :</span><br /><br />
		<table style='font-size: 11pt;'>
			<tr>
				<td width='150px'>NAMA</td>
				<td>:</td>
				<td>" . $record["nama_pegawai"] . "</td>
			</tr>
			<tr>
				<td>PANGKAT / " . $tulisan_nrp . "</td>
				<td>:</td>
				<td>" . strtoupper($record["pangkat"]) . " / " . $record["nik"] . "</td>
			</tr>
			<tr>
				<td>JABATAN</td>
				<td>:</td>
				<td>" . $record["jabatan"] . "</td>
			</tr>
		</table>
		<br /><br />
		
		<p style='text-align: justify; text-indent: 2em;'>
			Menyatakan dengan sesungguhnya bahwa saya bertanggung jawab penuh atas
			pencairan dan Penggunaan Dana Pembayaran " . $record["keterangan"] . " Sebesar Rp. " . number_format($jumlah_total, 0) . ",- (" . terbilang($jumlah_total) . " rupiah)
			termasuk bertanggung jawab terhadap kebenaran perhitungan dan penyaluran kepada yang berhak menerima.
		</p>
		<p style='text-align: justify; text-indent: 2em;'>
			Apabila dikemudian hari, atas pencairan dan Penggunaan Dana " . $record["keterangan"] . " mengakibatkan terjadinya kerugian negara
			maka saya bersedia dituntut Penggantian Kerugian Negara tersebut sesuai dengan ketentuan peraturan perundang – undangan.
		</p>
		<p style='text-align: justify; text-indent: 2em;'>
			Bukti – bukti pengeluaran terkait dengan pembanyaran " . $record["keterangan"] . "
			tersebut disimpan sesuai dengan ketentuan pada Satuan Kerja kami, untuk kelengkapan administrasi dan keperluan pemeriksaan aparat pengawas Fungsional.
		</p>
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
				<td align='center'>" . strtoupper($record["pangkat"]) . " " . $tulisan_nrp . " " . $record["nik"] . "</td>
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