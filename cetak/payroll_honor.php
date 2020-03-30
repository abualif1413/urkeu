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
	$rincian = BelanjaHonorModel::GetList03($_GET["id"]);
	
	// Pejabat dari konfigurasi
	$konf_kaurkeu = get_pejabat_konfigurasi("kaurkeu", $record["tanggal"]);
	
	$tbl = "";
	$no = 0;
	$jumlah_sbu_honor = 0;
	$jumlah_jumlah_bruto = 0;
	$jumlah_pph = 0;
	$jumlah_jumlah_dibayarkan = 0;
	foreach ($rincian as $rinci) {
		if($rinci["no_rekening"] != "") {
			$no++;
			$jumlah_sbu_honor += $rinci["sbu_honor"];
			$jumlah_jumlah_bruto += $rinci["jumlah_bruto"];
			$jumlah_pph += $rinci["pph"];
			$jumlah_jumlah_dibayarkan += $rinci["jumlah_dibayarkan"];
			$tbl .= "<tr>";
				$tbl .= "<td align='right'>" . $no . "</td>";
				$tbl .= "<td>" . $rinci["nama_pegawai"] . "</td>";
				$tbl .= "<td align='right'>" . number_format($rinci["jumlah_dibayarkan"], 2, ".", ",") . "</td>";
				$tbl .= "<td>" . $no . ". <div style='float: right;'>" . $rinci["no_rekening"] . "</div></td>";
				$tbl .= "<td></td>";
			$tbl .= "</tr>";
		}
	}
	/*$tbl .= "<tr style='font-style: italic; font-weight: bold;'>";
		$tbl .= "<td colspan='6' align='right'>JUMLAH</td>";
		$tbl .= "<td align='right'>" . number_format($jumlah_sbu_honor, 2, ".", ",") . "</td>";
		$tbl .= "<td align='right'>" . number_format($jumlah_jumlah_bruto, 2, ".", ",") . "</td>";
		$tbl .= "<td align='right'>" . number_format($jumlah_pph, 2, ".", ",") . "</td>";
		$tbl .= "<td align='right'>" . number_format($jumlah_jumlah_dibayarkan, 2, ".", ",") . "</td>";
		$tbl .= "<td align='left'></td>";
	$tbl .= "</tr>";*/
	
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
		<div style='text-align: center; font-family: serif; font-size: 12pt; font-weight: bold;'>DAFTAR PAYROLL PENERIMAAN " . $record["keterangan"] . "</div>
		<div style='text-align: center; font-family: serif; font-size: 12pt; font-weight: bold; text-decoration: underline;'>SATKER RUMAH SAKIT BHAYANGKARA TK. II MEDAN (640384)</div>
		<br /><br />
		<table width='100%' border='1' cellspacing='0' cellpadding='2' style='border-collapse: collapse; font-size: 8pt; text-transform: uppercase;'>
			<thead>
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
				<td align='center' style='font-weight: bold;'>" . $konf_kaurkeu["jabatan"] . "</td>
			</tr>
			<tr>
				<td align='center' style='font-weight: bold;'></td>
				<td align='center' style='font-weight: bold;'>BENDAHARA PENGELUARAN</td>
			</tr>
			<tr>
				<td style='height: 70px'></td>
				<td></td>
			</tr>
			<tr>
				<td align='center' style='font-weight: bold; text-decoration: underline;'></td>
				<td align='center' style='font-weight: bold; text-decoration: underline;'>" . $konf_kaurkeu["nama_pegawai"] . "</td>
			</tr>
			<tr>
				<td align='center' style=''></td>
				<td align='center' style=''>" . $konf_kaurkeu["pangkat"] . " " . $konf_kaurkeu["sebutan_nrp"] . " " . $konf_kaurkeu["nik"] . "</td>
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
	$dompdf->stream("sptjb rincian.pdf", array("Attachment" => false));
	//echo $cetak
?>