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
	$record = BelanjaGajiModel::GetFullRecord01($_GET["id"]);
	$rincian = BelanjaGajiModel::GetList03($_GET["id"]);
	
	$tanggal_pecah = explode(" ", tanggal_indonesia_panjang($record["tanggal"]));
	$periode = strtoupper($tanggal_pecah[1] . " " . $tanggal_pecah[2]);
	
	$tbl = "";
	$no = 0;
	$jumlah_gapok = 0;
	$jumlah_potongan1 = 0;
	$jumlah_nilai_potongan1 = 0;
	$jumlah_potongan2 = 0;
	$jumlah_nilai_potongan2 = 0;
	$jumlah_potongan3 = 0;
	$jumlah_nilai_potongan3 = 0;
	$jumlah_potongan4 = 0;
	$jumlah_nilai_potongan4 = 0;
	$jumlah_pph = 0;
	$jumlah_pengurangan = 0;
	$jumlah_dibayarkan = 0;
	
	foreach ($rincian as $rinci) {
		$jumlah_gapok += $rinci["gapok"];
		$jumlah_potongan1 += $rinci["potongan1"];
		$jumlah_nilai_potongan1 += $rinci["nilai_potongan1"];
		$jumlah_potongan2 += $rinci["potongan2"];
		$jumlah_nilai_potongan2 += $rinci["nilai_potongan2"];
		$jumlah_potongan3 += $rinci["potongan3"];
		$jumlah_nilai_potongan3 += $rinci["nilai_potongan3"];
		$jumlah_potongan4 += $rinci["potongan4"];
		$jumlah_nilai_potongan4 += $rinci["nilai_potongan4"];
		$jumlah_pph += $rinci["nilai_pph"];
		$jumlah_pengurangan += $rinci["pengurangan"];
		$jumlah_dibayarkan += $rinci["total_dibayar"];
		$no++;
		$tbl .= "<tr>";
			$tbl .= "<td align='right' style='height: 1.0cm;'>" . $no . "</td>";
			$tbl .= "<td>" . $rinci["nama_pegawai"] . "</td>";
			$tbl .= "<td align='right'>" . number_format($rinci["total_dibayar"], 0, ".", ",") . "</td>";
			$tbl .= "<td>" . $no . ". <div style='float: right;'>" . $rinci["no_rekening"] . "</div></td>";
			$tbl .= "<td></td>";
		$tbl .= "</tr>";
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
		<div style='text-align: center; font-family: serif; font-size: 12pt; font-weight: bold;'>PEMBAYARAN PENGHASILAN DOKTER KONSULTAN DAN</div>
		<div style='text-align: center; font-family: serif; font-size: 12pt; font-weight: bold;'>PEGAWAI BADAN LAYANAN UMUM (BLU) NON ASN</div>
		<div style='text-align: center; font-family: serif; font-size: 12pt; font-weight: bold; text-decoration: underline;'>SATKER RUMKIT BHAYANGKARA TK. II MEDAN BULAN " . $periode . "</div>
		<br /><br />
		<table width='93%' border='1' cellspacing='0' cellpadding='2' style='border-collapse: collapse; font-size: 8pt; text-transform: uppercase;'>
			<thead style='background-color: yellow;'>
				<tr align='center'>
					<th width='30px'>NO</th>
					<th>NAMA</th>
					<th width='70px'>JUMLAH<br />DIBAYARKAN</th>
					<th>NO. REKENING</th>
					<th>KETERANGAN</th>
				</tr>
				
			</thead>
			<tbody>
				" . $tbl . "
			</tbody>
		</table>
		<br />
		<table width='100%' cellspacing='0' cellpadding='0' style='font-size: 9pt; table-layout: fixed;' border='0'>
			<tr>
				<td align='center'></td>
				<td align='center'>Medan, " . tanggal_indonesia_panjang($record["tanggal"]) . "</td>
			</tr>
			<tr>
				<td align='center' style='font-weight: bold;'></td>
				<td align='center' style='font-weight: bold;'>KAUR KEUANGAN</td>
			</tr>
			<tr>
				<td align='center' style='font-weight: bold;'></td>
				<td align='center' style='font-weight: bold;'>BENDAHARA PENGELUARAN</td>
			</tr>
			<tr>
				<td style='height: 50px'></td>
				<td></td>
			</tr>
			<tr>
				<td align='center' style='font-weight: bold; text-decoration: underline;'></td>
				<td align='center' style='font-weight: bold; text-decoration: underline;'>JULIADI</td>
			</tr>
			<tr>
				<td align='center' style=''></td>
				<td align='center' style=''>BRIPKA NRP 84110420</td>
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
	$dompdf->stream("rincian gaji.pdf", array("Attachment" => false));
	//echo $cetak
?>