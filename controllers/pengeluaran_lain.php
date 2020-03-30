<?php
	error_reporting(0);
	session_start();
	include_once "../models/autoloader.php";
	require_once "../twig-1x/lib/Twig/Autoloader.php";
	
	TUser::BelumLogin();
	
	if($_GET["ajax"] == 1) {
		if($_GET["jenis"] == "go_edit") {
			$db = new DBConnection();
			$db->perintahSQL = "
				SELECT
					*
				FROM
					itbl_apps_pengeluaran_lain
				WHERE
					id=?
			";
			$db->add_parameter("i", $_GET["id"]);
			$data = $db->execute_reader();
			$db = null;
			
			echo json_encode($data[0]);
		}
		exit;
	}
	
	if($_POST["save"] == "Save") {
		$db = new DBConnection();
		$db->perintahSQL = "
			INSERT INTO itbl_apps_pengeluaran_lain (
				tanggal, keterangan, 
				jumlah, user_insert
			) VALUES(
				?, ?, 
				?, ?
			)
		";
		$db->add_parameter("s", $_POST["tanggal"]);
		$db->add_parameter("s", $_POST["keterangan"]);
		$db->add_parameter("d", $_POST["jumlah"]);
		$db->add_parameter("s", $_SESSION["APP_USER_ID"]);
		$db->execute_non_query();
		
		header("location:" . $_SERVER["PHP_SELF"]);
	} elseif($_POST["save"] == "Update") {
		$db = new DBConnection();
		$db->perintahSQL = "
			UPDATE itbl_apps_pengeluaran_lain SET
				tanggal=?, keterangan=?, 
				jumlah=?, user_insert=?
			WHERE
				id=?
		";
		$db->add_parameter("s", $_POST["tanggal"]);
		$db->add_parameter("s", $_POST["keterangan"]);
		$db->add_parameter("d", $_POST["jumlah"]);
		$db->add_parameter("s", $_SESSION["APP_USER_ID"]);
		$db->add_parameter("i", $_POST["id"]);
		$db->execute_non_query();
		
		header("location:" . $_SERVER["PHP_SELF"]);
	}
	
	if($_GET["hapus"] == 1) {
		$db = new DBConnection();
		$db->perintahSQL = "DELETE FROM itbl_apps_pengeluaran_lain WHERE id = ?";
		$db->add_parameter("i", $_GET["id"]);
		$db->execute_non_query();
		
		header("location:" . $_SERVER["PHP_SELF"] . "?tgl_dari=" . $_GET["tgl_dari"] . "&tgl_sampai=" . $_GET["tgl_sampai"] . "&keterangan=" . $_GET["keterangan"]);
	}
	
	$db = new DBConnection();
	$db->perintahSQL = "
		SELECT
			id, tanggal, keterangan, 
			jumlah, user_insert
		FROM
			itbl_apps_pengeluaran_lain
		WHERE
			tanggal BETWEEN ? AND ?
			AND keterangan LIKE ?
	";
	$db->add_parameter("s", $_GET["tgl_dari"]);
	$db->add_parameter("s", $_GET["tgl_sampai"]);
	$db->add_parameter("s", "%" . $_GET["keterangan"] . "%");
	$data = $db->execute_reader();
	$db = null;
	
	//print_r($data);
	
	/********************************************* Twig engine *********************************************/
	Twig_Autoloader::register();

	$loader = new Twig_Loader_Filesystem('../views');
	$twig = new Twig_Environment($loader);
	
	echo $twig->render('pengeluaran_lain.php', array(
			'judul' => 'Assalamualaikum',
			'data' => $data,
			'tgl_dari' => $_GET["tgl_dari"],
			'tgl_sampai' => $_GET["tgl_sampai"],
			'keterangan' => $_GET["keterangan"]
		)
	);
	/********************************************* End Of : Twig Engine *********************************************/
?>