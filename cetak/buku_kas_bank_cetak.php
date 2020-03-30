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
	
	$cetak = "
		<style>
			@page { margin: 0.5cm 0.5cm; }
		</style>
		" . $_POST["buku"] . "
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
