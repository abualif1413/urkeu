<?php
	error_reporting(0);
	session_start();
	include_once "../models/autoloader.php";
	require_once "../twig-1x/lib/Twig/Autoloader.php";
	
	TUser::BelumLogin();
	
	if($_GET["ajax"] == 1) {
		
		exit;
	}
	
	
	if($_GET["dari"] != "" || $_GET["sampai"] != "" || $_GET["cari"] != "") {
		$whr = "";
		if($_GET["dari"] != "" && $_GET["sampai"] != "") {
			$whr .= " AND tanggal BETWEEN '" . $_GET["dari"] . "' AND '" . $_GET["sampai"] . "' ";
		}
		
		$db = new DBConnection();
		$db->perintahSQL = "
			SELECT
				belanja.*
			FROM
				(
					SELECT * FROM vw_belanja_barang WHERE (nomor LIKE ? OR keterangan LIKE ? OR jenis_belanja LIKE ?) " . $whr . "
					UNION ALL
					SELECT * FROM vw_belanja_honor WHERE (nomor LIKE ? OR keterangan LIKE ? OR jenis_belanja LIKE ?) " . $whr . "
					UNION ALL
					SELECT * FROM vw_belanja_gaji WHERE (nomor LIKE ? OR keterangan LIKE ? OR jenis_belanja LIKE ?) " . $whr . "
				) belanja
		";
		$db->add_parameter("s", "%" . $_GET["cari"] . "%");
		$db->add_parameter("s", "%" . $_GET["cari"] . "%");
		$db->add_parameter("s", "%" . $_GET["cari"] . "%");
		$db->add_parameter("s", "%" . $_GET["cari"] . "%");
		$db->add_parameter("s", "%" . $_GET["cari"] . "%");
		$db->add_parameter("s", "%" . $_GET["cari"] . "%");
		$db->add_parameter("s", "%" . $_GET["cari"] . "%");
		$db->add_parameter("s", "%" . $_GET["cari"] . "%");
		$db->add_parameter("s", "%" . $_GET["cari"] . "%");
		$belanja = $db->execute_reader();
		$db = null;
	}
	
	/********************************************* Twig engine *********************************************/
	Twig_Autoloader::register();

	$loader = new Twig_Loader_Filesystem('../views');
	$twig = new Twig_Environment($loader);
	
	echo $twig->render('spp_spm_pop_data_belanja.php', array(
			'judul' => 'Assalamualaikum',
			'belanja' => $belanja,
			'qs' => query_string_to_array($_SERVER["QUERY_STRING"]),
		)
	);
	/********************************************* End Of : Twig Engine *********************************************/
?>