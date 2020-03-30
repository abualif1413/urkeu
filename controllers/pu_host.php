<?php
	error_reporting(0);
	session_start();
	include_once "../models/autoloader.php";
	require_once "../twig-1x/lib/Twig/Autoloader.php";

	TUser::BelumLogin();

	$db_spp_spm_push = new DBConnection();
	$db_spp_spm_push->perintahSQL = "
		SELECT
			a.*
		FROM
			vw_daftar_spp_spm_sudah_pu a
		WHERE
			a.id_pu = ?
		ORDER BY
			a.id_pu_detail ASC
	";
	$db_spp_spm_push->add_parameter("i", $_GET["id"]);
	$data_spp_spm_push = $db_spp_spm_push->execute_reader();
	$db_spp_spm_push = null;
	
	$db_pu = new DBConnection();
	$db_pu->perintahSQL = "SELECT * FROM itbl_apps_pu WHERE id=?";
	$db_pu->add_parameter("i", $_GET["id"]);
	$pu = $db_pu->execute_reader();
	$db_pu = null;
	
	/********************************************* Twig engine *********************************************/
	Twig_Autoloader::register();

	$loader = new Twig_Loader_Filesystem('../views');
	$twig = new Twig_Environment($loader);
	
	echo $twig->render('pu_host.php', array(
			'judul' => 'Assalamualaikum',
			'qs' => query_string_to_array($_SERVER["QUERY_STRING"]),
			'data_spp_spm_push' => $data_spp_spm_push,
			'pu' => $pu[0]
		)
	);
	/********************************************* End Of : Twig Engine *********************************************/
?>