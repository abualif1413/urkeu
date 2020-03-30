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
	$record = BelanjaGajiModel::GetFullRecord01($_GET["id"]);
	$rincian = BelanjaGajiModel::GetList03($_GET["id"]);
	
	// Pejabat dari konfigurasi
	$konf_kaurkeu = get_ttd_dokumen(5, "002", $record["tanggal"]);
	$konf_normgaji_dikoleh = get_ttd_dokumen(5, "001", $record["tanggal"]);
	$konf_normgaji_menget = get_ttd_dokumen(5, "003", $record["tanggal"]);
	
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
			$tbl .= "<td align='right' width='30px' style='height: 2.5cm;'>" . $no . "</td>";
			$tbl .= "<td width='120px'>" . $rinci["nama_pegawai"] . "</td>";
			$tbl .= "<td width='120px'>" . $rinci["jabatan"] . "</td>";
			$tbl .= "<td align='right' width='70px'>" . number_format($rinci["gapok"], 0, ".", ",") . "</td>";
			$tbl .= "<td align='right' width='30px'>" . number_format($rinci["potongan1"], 0, ".", ",") . "</td>";
			$tbl .= "<td align='right' width='70px'>" . number_format($rinci["nilai_potongan1"], 0, ".", ",") . "</td>";
			$tbl .= "<td align='right' width='30px'>" . number_format($rinci["potongan2"], 0, ".", ",") . "</td>";
			$tbl .= "<td align='right' width='70px'>" . number_format($rinci["nilai_potongan2"], 0, ".", ",") . "</td>";
			$tbl .= "<td align='right' width='30px'>" . number_format($rinci["potongan3"], 0, ".", ",") . "</td>";
			$tbl .= "<td align='right' width='70px'>" . number_format($rinci["nilai_potongan3"], 0, ".", ",") . "</td>";
			$tbl .= "<td align='right' width='30px'>" . number_format($rinci["potongan4"], 0, ".", ",") . "</td>";
			$tbl .= "<td align='right' width='70px'>" . number_format($rinci["nilai_potongan4"], 0, ".", ",") . "</td>";
			$tbl .= "<td align='right' width='70px'>" . number_format($rinci["nilai_pph"], 0, ".", ",") . "</td>";
			$tbl .= "<td align='right' width='70px'>" . number_format($rinci["pengurangan"], 0, ".", ",") . "</td>";
			$tbl .= "<td align='right' width='70px'>" . number_format($rinci["total_dibayar"], 0, ".", ",") . "</td>";
			$tbl .= "<td>" . $no . ". <div style='float: right;'>" . $rinci["no_rekening"] . "</div></td>";
		$tbl .= "</tr>";
	}
	$tbl .= "<tr style='font-style: italic; font-weight: bold; background-color: yellow;'>";
		$tbl .= "<td colspan='3' align='right'>JUMLAH</td>";
		$tbl .= "<td align='right'>" . number_format($jumlah_gapok, 2, ".", ",") . "</td>";
		$tbl .= "<td align='right'>" . number_format($jumlah_potongan1, 2, ".", ",") . "</td>";
		$tbl .= "<td align='right'>" . number_format($jumlah_nilai_potongan1, 2, ".", ",") . "</td>";
		$tbl .= "<td align='right'>" . number_format($jumlah_potongan2, 2, ".", ",") . "</td>";
		$tbl .= "<td align='right'>" . number_format($jumlah_nilai_potongan2, 2, ".", ",") . "</td>";
		$tbl .= "<td align='right'>" . number_format($jumlah_potongan3, 2, ".", ",") . "</td>";
		$tbl .= "<td align='right'>" . number_format($jumlah_nilai_potongan3, 2, ".", ",") . "</td>";
		$tbl .= "<td align='right'>" . number_format($jumlah_potongan4, 2, ".", ",") . "</td>";
		$tbl .= "<td align='right'>" . number_format($jumlah_nilai_potongan4, 2, ".", ",") . "</td>";
		$tbl .= "<td align='right'>" . number_format($jumlah_pph, 2, ".", ",") . "</td>";
		$tbl .= "<td align='right'>" . number_format($jumlah_pengurangan, 2, ".", ",") . "</td>";
		$tbl .= "<td align='right'>" . number_format($jumlah_dibayarkan, 2, ".", ",") . "</td>";
		$tbl .= "<td></td>";
	$tbl .= "</tr>";
	
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
		<div style='text-align: center; font-family: serif; font-size: 12pt; font-weight: bold;'>PEMBAYARAN PENGHASILAN DOKTER KONSULTAN DAN</div>
		<div style='text-align: center; font-family: serif; font-size: 12pt; font-weight: bold;'>PEGAWAI BADAN LAYANAN UMUM (BLU) NON ASN</div>
		<div style='text-align: center; font-family: serif; font-size: 12pt; font-weight: bold; text-decoration: underline;'>SATKER RUMKIT BHAYANGKARA TK. II MEDAN BULAN " . $periode . "</div>
		<br /><br />
		<table width='88%' border='1' cellspacing='0' cellpadding='2' style='border-collapse: collapse; font-size: 8pt; text-transform: uppercase;'>
			<thead style='background-color: yellow;'>
				<tr align='center'>
					<th>NO</th>
					<th>NAMA</th>
					<th>JABATAN</th>
					<th>GAJI PER BULAN</th>
					<th colspan='2'>LMBT/CPT<br />(<1-2 JAM)<br />SAKIT (> 3 HR)<br />UMROH / HAJI / KAGM LAIN (/HR)</th>
					<th colspan='2'>LMBT/CPT<br />(> 2 JAM)</th>
					<th colspan='2'>CUTI / IJIN</th>
					<th colspan='2'>TK / HR</th>
					<th>PPh - 21</th>
					<th>JUMLAH PENGURANGAN</th>
					<th>GAJI DIBAYARKAN</th>
					<th>TANDA TANGAN</th>
				</tr>
				
			</thead>
			<tbody>
				" . $tbl . "
			</tbody>
		</table>
		<br />
		<table width='100%' cellspacing='0' cellpadding='0' style='font-size: 9pt; table-layout: fixed;' border='0'>
			<tr>
				<td align='center'>Diketahui Oleh :</td>
				<td align='center'>Medan, " . tanggal_indonesia_panjang($record["tanggal"]) . "</td>
			</tr>
			<tr>
				<td align='center' style='font-weight: bold;' valign='top'>" . $konf_normgaji_dikoleh["judul_ttd"] . "</td>
				<td align='center' style='font-weight: bold;' valign='top'>" . $konf_kaurkeu["judul_ttd"] . "</td>
			</tr>
			<tr>
				<td style='height: 35px'></td>
				<td></td>
			</tr>
			<tr>
				<td align='center' style='font-weight: bold; text-decoration: underline;' valign='top'>" . $konf_normgaji_dikoleh["nama_pegawai"] . "</td>
				<td align='center' style='font-weight: bold; text-decoration: underline;' valign='top'>" . $konf_kaurkeu["nama_pegawai"] . "</td>
			</tr>
			<tr>
				<td align='center' style='' valign='top'>" . $konf_normgaji_dikoleh["pangkat"] . " NRP " . $konf_normgaji_dikoleh["nik"] . "</td>
				<td align='center' style='' valign='top'>" . $konf_kaurkeu["pangkat"] . " NRP " . $konf_kaurkeu["nik"] . "</td>
			</tr>
		</table>
		<table width='100%' cellspacing='0' cellpadding='0' style='font-size: 9pt; table-layout: fixed;' border='0'>
			<tr>
				<td align='center'></td>
				<td align='center'>Mengetahui</td>
				<td align='center'></td>
			</tr>
			<tr>
				<td align='center'></td>
				<td align='center' style='font-weight: bold;'>" . $konf_normgaji_menget["judul_ttd"] . "</td>
				<td align='center' style='font-weight: bold;'></td>
			</tr>
			<tr>
				<td align='center'></td>
				<td style='height: 35px'></td>
				<td></td>
			</tr>
			<tr>
				<td align='center'></td>
				<td align='center' style='font-weight: bold; text-decoration: underline;'>" . $konf_normgaji_menget["nama_pegawai"] . "</td>
				<td align='center' style='font-weight: bold; text-decoration: underline;'></td>
			</tr>
			<tr>
				<td align='center'></td>
				<td align='center' style=''>" . $konf_normgaji_menget["pangkat"] . " NRP " . $konf_normgaji_menget["nik"] . "</td>
				<td align='center' style=''></td>
			</tr>
		</table>
	";
	
	// instantiate and use the dompdf class
	$dompdf = new Dompdf();
	$dompdf->loadHtml($cetak);
	
	// (Optional) Setup the paper size and orientation
	$dompdf->setPaper('legal', 'landscape');
	
	// Render the HTML as PDF
	$dompdf->render();
	//echo $cetak;
	
	// Output the generated PDF to Browser
	$dompdf->stream("rincian gaji.pdf", array("Attachment" => false));
	//echo $cetak
?>
