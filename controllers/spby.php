<?php
	error_reporting(0);
	session_start();
	include_once "../models/autoloader.php";
	require_once "../twig-1x/lib/Twig/Autoloader.php";
	
	TUser::BelumLogin();
	
	if($_POST["save"] == "Save") {
		$obj = new SPBY();
		$obj->id_spp_spm = $_POST["id_spp_spm"];
		$obj->tanggal = $_POST["tanggal"];
		$obj->nomor = $_POST["nomor"]; 
		$obj->kepada = $_POST["kepada"];
		$obj->setuju_lunas = $_POST["setuju_lunas"];
		$obj->penerima = $_POST["penerima"];
		$obj->telp_penerima = $_POST["telp_penerima"]; 
		$obj->pangkat_penerima = $_POST["pangkat_penerima"]; 
		$obj->nik_penerima = $_POST["nik_penerima"]; 
		$obj->sebutan_nik_penerima = $_POST["sebutan_nik_penerima"];  
		$obj->user_insert = $_SESSION["APP_USER_ID"];
		$obj->Insert();
		header("location:cetak_berkas_spp_spm_host.php?id=" . $_POST["id_spp_spm"]);
	}
	
	$db_spby = new DBConnection();
	$db_spby->perintahSQL = "SELECT * FROM itbl_apps_spby WHERE id_spp_spm=?";
	$db_spby->add_parameter("i", $_GET["id"]);
	$ds_spby = $db_spby->execute_reader();
	$db_spby = null;
	/********************************************* Twig engine *********************************************/
	Twig_Autoloader::register();

	$loader = new Twig_Loader_Filesystem('../views');
	$twig = new Twig_Environment($loader);
	
	echo $twig->render('spby.php', array(
			'judul' => 'Assalamualaikum',
			'qs' => query_string_to_array($_SERVER["QUERY_STRING"]),
			'spby' => $ds_spby[0]
		)
	);
	/********************************************* End Of : Twig Engine *********************************************/
?>