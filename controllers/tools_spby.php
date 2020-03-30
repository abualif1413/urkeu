<?php
	error_reporting(0);
	session_start();
	include_once "../models/autoloader.php";
	require_once "../twig-1x/lib/Twig/Autoloader.php";
	
	TUser::BelumLogin();
	
	if($_POST["save"] == "save") {
		foreach ($_POST["spby"] as $spby) {
			$db = new DBConnection();
			$db->perintahSQL = "UPDATE itbl_apps_spby SET tanggal=? WHERE id_spp_spm=?";
			$db->add_parameter("s", $_POST["tgl_spby_baru"]);
			$db->add_parameter("i", $spby);
			$db->execute_non_query();
		}
		header("location:" . $_SERVER["PHP_SELF"]);
	}
	
	$data = SPPSPM::GetList01($_GET["tgl_dari"], $_GET["tgl_sampai"], $_GET["uraian"]);
	
	/********************************************* Twig engine *********************************************/
	Twig_Autoloader::register();

	$loader = new Twig_Loader_Filesystem('../views');
	$twig = new Twig_Environment($loader);
	
	echo $twig->render('tools_spby.php', array(
			'judul' => 'Assalamualaikum',
			'qs' => query_string_to_array($_SERVER["QUERY_STRING"]),
			'data' => $data
		)
	);
	/********************************************* End Of : Twig Engine *********************************************/
?>