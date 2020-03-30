<?php
	error_reporting(0);
	session_start();
	include_once "../models/autoloader.php";
	require_once "../twig-1x/lib/Twig/Autoloader.php";
	
	if($_GET["ajax"] == 1) {
		if($_GET["jenis"] == "go_edit") {
			$db = new DBConnection();
			$db->perintahSQL = "UPDATE c_konfigurasi SET nilai=? WHERE kunci=? AND tgl_berlaku=?";
			$db->add_parameter("s", $_GET["nilai"]);
			$db->add_parameter("s", $_GET["kunci"]);
			$db->add_parameter("s", $_GET["tgl_berlaku"]);
			$db->execute_non_query();
		}
		exit;
	}
	
	// Data konfigurasi
	$db_config = new DBConnection();
	$db_config->perintahSQL = "
		SELECT
			config.*
		FROM
			(
				SELECT *, 1 AS jenis FROM vw_config_pejabat WHERE nama_pegawai IS NULL GROUP BY kunci
				UNION ALL
				SELECT *, 2 AS jenis FROM vw_config_pejabat WHERE nama_pegawai IS NULL
			) config
		ORDER BY
			config.kunci ASC, config.jenis ASC, config.tgl_berlaku ASC
	";
	$ds_config = $db_config->execute_reader();
	$db_config = null;
	
	$combo_pegawai = PegawaiModel::GetPegawaiCombo01();
	
	/********************************************* Twig engine *********************************************/
	Twig_Autoloader::register();

	$loader = new Twig_Loader_Filesystem('../views');
	$twig = new Twig_Environment($loader);
	
	echo $twig->render('konfigurasi.php', array(
			'judul' => 'Assalamualaikum',
			'combo_pegawai' => $combo_pegawai,
			'config' => $ds_config
		)
	);
	/********************************************* End Of : Twig Engine *********************************************/
?>