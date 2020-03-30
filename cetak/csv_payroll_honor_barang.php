<?php
	header('Content-Type: text/csv');
	header('Content-Disposition: attachment; filename="payroll.csv"');
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
	$rincian = PermohonanDanaModel::GetList03($_GET["id"]);
	
	$tbl = "";
	$no = 0;
	$jumlah_sbu_honor = 0;
	$jumlah_jumlah_bruto = 0;
	$jumlah_pph = 0;
	$jumlah_jumlah_dibayarkan = 0;
	$fp = fopen('php://output', 'wb');
	foreach ($rincian as $rinci) {
		if($rinci["no_rekening"] != "") {
			$no++;
			$jumlah_sbu_honor += $rinci["sbu_honor"];
			$jumlah_jumlah_bruto += $rinci["jumlah_bruto"];
			$jumlah_pph += $rinci["pph"];
			$jumlah_jumlah_dibayarkan += $rinci["jumlah_dibayarkan"];
			$data_csv = array($no, $rinci["nama_pegawai"], $rinci["jumlah_dibayarkan"], strtoupper($rinci["no_rekening"]), "");
			fputcsv($fp, $data_csv, $_GET["separator"]);
			/*$tbl .= "<tr>";
				$tbl .= "<td align='right'>" . $no . "</td>";
				$tbl .= "<td>" . $rinci["nama_pegawai"] . "</td>";
				$tbl .= "<td align='right'>" . number_format($rinci["jumlah_dibayarkan"], 2, ".", ",") . "</td>";
				$tbl .= "<td>" . $no . ". <div style='float: right;'>" . $rinci["no_rekening"] . "</div></td>";
				$tbl .= "<td></td>";
			$tbl .= "</tr>";*/
			
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
?>