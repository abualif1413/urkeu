<?php
	error_reporting(0);
	session_start();
	include_once "../models/autoloader.php";
	require_once "../twig-1x/lib/Twig/Autoloader.php";
	
	TUser::BelumLogin();
	
	if($_GET["pecah_pembayaran"] == 1) {
		$db = new DBConnection();
		$db->perintahSQL = "
			INSERT INTO t_permohonan_dana_pembayaran (
				id_belanja_barang, ppn, 
				pph, bruto
			) VALUES(
				?, ?, 
				?, ?
			)
		";
		$db->add_parameter("i", $_GET["id"]);
		$db->add_parameter("d", $_GET["ppn"]);
		$db->add_parameter("d", $_GET["pph"]);
		$db->add_parameter("d", $_GET["bruto"]);
		$db->execute_non_query();
		
		header("location:" . $_SERVER["PHP_SELF"] . "?id=" . $_GET["id"] . "&pd=" . $_GET["pd"]);
	}
	
	if($_GET["pop_pecah_pembayaran"] == 1) {
		$db = new DBConnection();
		$db->perintahSQL = "DELETE FROM t_permohonan_dana_pembayaran WHERE id=?";
		$db->add_parameter("i", $_GET["id_pecah"]);
		$db->execute_non_query();
		
		header("location:" . $_SERVER["PHP_SELF"] . "?id=" . $_GET["id"] . "&pd=" . $_GET["pd"]);
	}
	
	$record = PermohonanDanaModel::GetFullRecord01($_GET["id"]);
	
	$db_pembayaran = new DBConnection();
	$db_pembayaran->perintahSQL = "SELECT * FROM t_permohonan_dana_pembayaran WHERE id_belanja_barang = ?";
	$db_pembayaran->add_parameter("i", $_GET["id"]);
	$ds_pembayaran = $db_pembayaran->execute_reader();
	$db_pembayaran = null;
	
	$ppn = $record["total_ppn"];
	$pph = $record["total_pph"];
	$bruto = $record["total_netto"] - $ppn - $pph;
	$seharusnya = $bruto + $ppn + $pph;
	$telah_bayar = 0;
	foreach ($ds_pembayaran as $bayar) {
		$ppn -= $bayar["ppn"];
		$pph -= $bayar["pph"];
		$telah_bayar += ($bayar["bruto"] + $bayar["ppn"] + $bayar["pph"]);
		$seharusnya -= $telah_bayar;
	}
	$akan_bayar = ($seharusnya - $ppn -  $pph);
	
	/********************************************* Twig engine *********************************************/
	Twig_Autoloader::register();

	$loader = new Twig_Loader_Filesystem('../views');
	$twig = new Twig_Environment($loader);
	
	echo $twig->render('cetak_berkas_host.php', array(
			'judul' => 'Assalamualaikum',
			'id' => $_GET["id"],
			'record' => $record,
			'pd' => $_GET["pd"],
			'ppn' => $ppn,
			'pph' => $pph,
			'bruto' => $bruto,
			'seharusnya' => $seharusnya,
			'pembayaran' => $ds_pembayaran,
			'akan_bayar' => $akan_bayar
		)
	);
	/********************************************* End Of : Twig Engine *********************************************/
?>