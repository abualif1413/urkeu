<?php
	error_reporting(0);
	session_start();
	include_once "../models/autoloader.php";
	require_once "../twig-1x/lib/Twig/Autoloader.php";
	
	TUser::BelumLogin();
	
	if($_GET["delete"] == 1) {
		$obj = new SPPSPM();
		$obj->Record($_GET["id"]);
		$obj->Delete();
		
		header("location:" . $_SERVER["PHP_SELF"]);
	}
	
	if(isset($_GET["cari"])){
		$data = SPPSPM::GetList01($_GET["tgl_dari"], $_GET["tgl_sampai"], $_GET["uraian"]);
		echo "01";
	}
	elseif (isset($_GET["scan_barcode"])){
		$data = SPPSPM::GetList02($_GET["scan_barcode"]);
		echo "02";
	}
	
	/********************************************* Twig engine *********************************************/
	Twig_Autoloader::register();

	$loader = new Twig_Loader_Filesystem('../views');
	$twig = new Twig_Environment($loader);
	
	echo $twig->render('spp_spm_edit.php', array(
			'judul' => 'Assalamualaikum',
			'qs' => query_string_to_array($_SERVER["QUERY_STRING"]),
			'data' => $data
		)
	);
	/********************************************* End Of : Twig Engine *********************************************/
?>