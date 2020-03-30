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
				id_spp_spm, user_insert
			) VALUES(
				?, ?
			)
		";
		$db->add_parameter("i", $_GET["id"]);
		$db->add_parameter("i", $_SESSION["APP_USER_ID"]);
		$db->execute_non_query();
		
		header("location:" . $_SERVER["PHP_SELF"] . "?dari=" . $_GET["dari"] . "&sampai=" . $_GET["sampai"]);
	}
	
	if($_GET["pop_spp_spm"] == 1) {
		$db = new DBConnection();
		$db->perintahSQL = "DELETE FROM itbl_apps_pu_detail WHERE id=?";
		$db->add_parameter("i", $_GET["id_pu_detail"]);
		$db->execute_non_query();
		
		header("location:" . $_SERVER["PHP_SELF"] . "?dari=" . $_GET["dari"] . "&sampai=" . $_GET["sampai"]);
	}
	
	if($_POST["save"] == "Save") {
		$db_pu = new DBConnection();
		$db_pu->perintahSQL = "
			INSERT INTO itbl_apps_pu (
				tanggal, keterangan, 
				user_insert
			) VALUES(
				?, ?, 
				?
			)
		";
		$db_pu->add_parameter("s", $_POST["tanggal"]);
		$db_pu->add_parameter("s", $_POST["keterangan"]);
		$db_pu->add_parameter("i", $_SESSION["APP_USER_ID"]);
		$db_pu->execute_non_query();
		$id_pu = $db_pu->insert_id;
		$db_pu = null;
		
		$db_pu_detail = new DBConnection();
		$db_pu_detail->perintahSQL = "UPDATE itbl_apps_pu_detail SET id_pu=? WHERE id_pu IS NULL AND user_insert=?";
		$db_pu_detail->add_parameter("i", $id_pu);
		$db_pu_detail->add_parameter("i", $_SESSION["APP_USER_ID"]);
		$db_pu_detail->execute_non_query();
		
		header("location:pu_host.php?id=" . $id_pu);
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
	
	$db_spp_spm_push = new DBConnection();
	$db_spp_spm_push->perintahSQL = "
		SELECT
			a.*
		FROM
			vw_daftar_spp_spm_sudah_pu a
		WHERE
			a.id_pu IS NULL AND a.user_insert_pu = ?
		ORDER BY
			a.id_pu_detail ASC
	";
	$db_spp_spm_push->add_parameter("i", $_SESSION["APP_USER_ID"]);
	$data_spp_spm_push = $db_spp_spm_push->execute_reader();
	$db_spp_spm_push = null;
	
	/********************************************* Twig engine *********************************************/
	Twig_Autoloader::register();

	$loader = new Twig_Loader_Filesystem('../views');
	$twig = new Twig_Environment($loader);
	
	echo $twig->render('pu_input.php', array(
			'judul' => 'Assalamualaikum',
			'qs' => query_string_to_array($_SERVER["QUERY_STRING"]),
			'data_spp_spm' => $data_spp_spm,
			'data_spp_spm_push' => $data_spp_spm_push
		)
	);
	/********************************************* End Of : Twig Engine *********************************************/
?>