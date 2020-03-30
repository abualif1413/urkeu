<?php
	error_reporting(0);
	session_start();
	include_once "../models/autoloader.php";
	require_once "../twig-1x/lib/Twig/Autoloader.php";
	
	TUser::BelumLogin();
	
	if($_POST["save"] == "Save") {
		$db_hapus = new DBConnection();
		$db_hapus->perintahSQL = "DELETE FROM itbl_apps_saldo_rekening_bank WHERE periode=?";
		$db_hapus->add_parameter("s", $_POST["periode"]);
		$db_hapus->execute_non_query();
		$db_hapus = null;
		
		$db_insert = new DBConnection();
		$db_insert->perintahSQL = "SELECT rek.* FROM itbl_apps_rekening_bank rek";
		$rekening = $db_insert->execute_reader();
		$db_insert = null;
		foreach ($rekening as $rek) {
			$db = new DBConnection();
			$db->perintahSQL = "
				INSERT INTO itbl_apps_saldo_rekening_bank(
					periode, id_rekening, saldo
				) VALUES(
					?, ?, ?
				)
			";
			$db->add_parameter("s", $_POST["periode"]);
			$db->add_parameter("i", $rek["id"]);
			$db->add_parameter("d", $_POST["saldo_" . $rek["id"]]);
			$db->execute_non_query();
		}
		
		// Split periode supaya dapat bulan dan tahun nya
		$bulan_tahun = explode("-", $_POST["periode"]);
		
		header("location:" . $_SERVER["PHP_SELF"] . "?bulan=" . $bulan_tahun[1] . "&tahun=" . $bulan_tahun[0]);
	}
	
	$periode = $_GET["tahun"] . "-" . $_GET["bulan"];
	
	$db = new DBConnection();
	$db->perintahSQL = "
		SELECT
			rek.*, COALESCE(sld.saldo, 0) saldo
		FROM
			itbl_apps_rekening_bank rek
			LEFT JOIN itbl_apps_saldo_rekening_bank sld ON (rek.id = sld.id_rekening AND sld.periode = ?)
	";
	$db->add_parameter("s", $periode);
	$rekening = $db->execute_reader();
	$db = null;
	
	/********************************************* Twig engine *********************************************/
	Twig_Autoloader::register();

	$loader = new Twig_Loader_Filesystem('../views');
	$twig = new Twig_Environment($loader);
	
	echo $twig->render('laporan_rekening.php', array(
			'judul' => 'Assalamualaikum',
			'bulan' => semua_bulan(),
			'qs' => query_string_to_array($_SERVER["QUERY_STRING"]),
			'periode' => $periode,
			'rekening' => $rekening
		)
	);
	/********************************************* End Of : Twig Engine *********************************************/
?>