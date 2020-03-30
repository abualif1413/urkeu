<?php
	error_reporting(0);
	session_start();
	include_once "../models/autoloader.php";
	require_once "../twig-1x/lib/Twig/Autoloader.php";
	
	TUser::BelumLogin();
	
	$db = new DBConnection();
	$db->perintahSQL = "
		SELECT
			a.*, COALESCE(b.id_spp_spm, 0) AS spby
		FROM
			vw_daftar_spp_spm a
			LEFT JOIN itbl_apps_spby b ON a.id = b.id_spp_spm
		WHERE
			a.id = ?
	";
	$db->add_parameter("i", $_GET["id"]);
	$data = $db->execute_reader();
	$db = null;
	
	/********************************************* Twig engine *********************************************/
	Twig_Autoloader::register();

	$loader = new Twig_Loader_Filesystem('../views');
	$twig = new Twig_Environment($loader);
	
	echo $twig->render('cetak_berkas_spp_spm_host.php', array(
			'judul' => 'Assalamualaikum',
			'qs' => query_string_to_array($_SERVER["QUERY_STRING"]),
			'data' => $data[0]
		)
	);
	/********************************************* End Of : Twig Engine *********************************************/
?>