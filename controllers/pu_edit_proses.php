<?php
	error_reporting(0);
	session_start();
	include_once "../models/autoloader.php";
	require_once "../twig-1x/lib/Twig/Autoloader.php";

	TUser::BelumLogin();

	if($_GET["push_spp_spm"] == 1) {
		$db = new DBConnection();
		$db->perintahSQL = "
			INSERT INTO itbl_apps_pu_detail (
				id_spp_spm, id_pu, user_insert
			) VALUES(
				?, ?, ?
			)
		";
		$db->add_parameter("i", $_GET["id"]);
		$db->add_parameter("i", $_GET["id_pu"]);
		$db->add_parameter("i", $_SESSION["APP_USER_ID"]);
		$db->execute_non_query();
		
		header("location:" . $_SERVER["PHP_SELF"] . "?dari=" . $_GET["dari"] . "&sampai=" . $_GET["sampai"] . "&id=" . $_GET["id_pu"]);
	}
	
	if($_GET["pop_spp_spm"] == 1) {
		$db = new DBConnection();
		$db->perintahSQL = "DELETE FROM itbl_apps_pu_detail WHERE id=?";
		$db->add_parameter("i", $_GET["id_pu_detail"]);
		$db->execute_non_query();
		
		header("location:" . $_SERVER["PHP_SELF"] . "?dari=" . $_GET["dari"] . "&sampai=" . $_GET["sampai"] . "&id=" . $_GET["id_pu"]);
	}
	
	if($_POST["save"] == "Save") {
		$db_pu = new DBConnection();
		$db_pu->perintahSQL = "
			UPDATE itbl_apps_pu SET
				tanggal=?, keterangan=?
			WHERE
				id=?
		";
		$db_pu->add_parameter("s", $_POST["tanggal"]);
		$db_pu->add_parameter("s", $_POST["keterangan"]);
		$db_pu->add_parameter("i", $_POST["id"]);
		$db_pu->execute_non_query();
		$db_pu = null;
		
		header("location:pu_host.php?id=" . $_POST["id"]);
	}
	
	if($_POST["save_pu_lain"] == "save_pu_lain") {
		$db = new DBConnection();
		$db->perintahSQL = "
			INSERT INTO itbl_apps_pu_lain (
				id_pu, keterangan, 
				jumlah, user_insert
			) VALUES(
				?, ?, 
				?, ?
			)
		";
		$db->add_parameter("s", $_POST["id"]);
		$db->add_parameter("s", $_POST["keterangan"]);
		$db->add_parameter("d", $_POST["jumlah"]);
		$db->add_parameter("s", $_SESSION["APP_USER_ID"]);
		$db->execute_non_query();
		
		header("location:" . $_SERVER["PHP_SELF"] . "?id=" . $_POST["id"]);
	}
	
	if($_GET["hapus_pu_lain"] == 1) {
		$db = new DBConnection();
		$db->perintahSQL = "DELETE FROM itbl_apps_pu_lain WHERE id = ?";
		$db->add_parameter("i", $_GET["id"]);
		$db->execute_non_query();
		
		header("location:" . $_SERVER["PHP_SELF"] . "?id=" . $_GET["id_pu"]);
	}
	
	$db_spp_spm = new DBConnection();
	$db_spp_spm->perintahSQL = "
		SELECT
			a.*
		FROM
			vw_daftar_spp_spm_belum_pu a
		WHERE
			a.tanggal BETWEEN ? AND ?
		ORDER BY
			a.id ASC
	";
	$db_spp_spm->add_parameter("s", $_GET["dari"]);
	$db_spp_spm->add_parameter("s", $_GET["sampai"]);
	$data_spp_spm = $db_spp_spm->execute_reader();
	$db_spp_spm = null;
	
	// PU Dari SPP/SPM
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
	
	// PU Dari PU Lain
	$db_pu_lain = new DBConnection();
	$db_pu_lain->perintahSQL = "
		SELECT
			id, id_pu, keterangan, 
			jumlah, user_insert
		FROM
			itbl_apps_pu_lain
		WHERE
			id_pu = ?
	";
	$db_pu_lain->add_parameter("i", $_GET["id"]);
	$data_pu_lain = $db_pu_lain->execute_reader();
	//print_r($data_pu_lain);
	$db_pu_lain = null;
	
	$db_pu = new DBConnection();
	$db_pu->perintahSQL = "SELECT * FROM itbl_apps_pu WHERE id = ?";
	$db_pu->add_parameter("i", $_GET["id"]);
	$ds_pu = $db_pu->execute_reader();
	$db_pu = null;
	
	/********************************************* Twig engine *********************************************/
	Twig_Autoloader::register();

	$loader = new Twig_Loader_Filesystem('../views');
	$twig = new Twig_Environment($loader);
	
	echo $twig->render('pu_edit_proses.php', array(
			'judul' => 'Assalamualaikum',
			'qs' => query_string_to_array($_SERVER["QUERY_STRING"]),
			'data_spp_spm' => $data_spp_spm,
			'data_spp_spm_push' => $data_spp_spm_push,
			'data_pu_lain' => $data_pu_lain,
			'data_pu' => $ds_pu[0]
		)
	);
	/********************************************* End Of : Twig Engine *********************************************/
?>