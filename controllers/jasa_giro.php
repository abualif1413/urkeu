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
					a.*, b.id AS id_pagu, b.nomor_umum, b.acc_name
				FROM
					itbl_apps_jasa_giro a
					LEFT JOIN vw_coa b ON a.id_coa_pagu = b.id
				WHERE
					a.id=?
			";
			$db->add_parameter("i", $_GET["id"]);
			$data = $db->execute_reader();
			$db = null;
			
			echo json_encode($data[0]);
		} elseif ($_GET["jenis"] == "load_data_pagu") {
			$db = new DBConnection();
			$db->perintahSQL = "SELECT * FROM vw_coa WHERE id=?";
			$db->add_parameter("i", $_GET["id"]);
			$ds = $db->execute_reader();
			$db = null;
			
			$data = array(
				"nomor_umum" => $ds[0]["nomor_umum"],
				"acc_name" => $ds[0]["acc_name"]
			);
			
			echo json_encode($data);
		}
		exit;
	}
	
	if($_POST["save"] == "Save") {
		$db = new DBConnection();
		$db->perintahSQL = "
			INSERT INTO itbl_apps_jasa_giro (
				id_coa_pagu, tanggal, keterangan, 
				jumlah, user_insert
			) VALUES(
				?, ?, ?, 
				?, ?
			)
		";
		$db->add_parameter("i", $_POST["id_pagu"]);
		$db->add_parameter("s", $_POST["tanggal"]);
		$db->add_parameter("s", $_POST["keterangan"]);
		$db->add_parameter("d", $_POST["jumlah"]);
		$db->add_parameter("s", $_SESSION["APP_USER_ID"]);
		$db->execute_non_query();
		
		header("location:" . $_SERVER["PHP_SELF"]);
	} elseif($_POST["save"] == "Update") {
		$db = new DBConnection();
		$db->perintahSQL = "
			UPDATE itbl_apps_jasa_giro SET
				id_coa_pagu=?, tanggal=?, keterangan=?, 
				jumlah=?, user_insert=?
			WHERE
				id=?
		";
		$db->add_parameter("i", $_POST["id_pagu"]);
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
		$db->perintahSQL = "DELETE FROM itbl_apps_jasa_giro WHERE id = ?";
		$db->add_parameter("i", $_GET["id"]);
		$db->execute_non_query();
		
		header("location:" . $_SERVER["PHP_SELF"] . "?tgl_dari=" . $_GET["tgl_dari"] . "&tgl_sampai=" . $_GET["tgl_sampai"] . "&keterangan=" . $_GET["keterangan"]);
	}
	
	$db = new DBConnection();
	$db->perintahSQL = "
		SELECT
			a.id, a.tanggal, a.keterangan, 
			a.jumlah, a.user_insert, b.id AS id_coa, b.acc_name, b.nomor_coa
		FROM
			itbl_apps_jasa_giro a
			LEFT JOIN vw_coa b ON a.id_coa_pagu = b.id
		WHERE
			a.tanggal BETWEEN ? AND ?
			AND a.keterangan LIKE ?
	";
	$db->add_parameter("s", $_GET["tgl_dari"]);
	$db->add_parameter("s", $_GET["tgl_sampai"]);
	$db->add_parameter("s", "%" . $_GET["keterangan"] . "%");
	$data = $db->execute_reader();
	$db = null;
	foreach ($data as &$dt) {
		$objPagu = new PaguService();
		$objPagu->GenerateDaftarNomor($dt["id_coa"]);
		$nomor_pagu_ini = $objPagu->daftar_nomor[1]["nomor"] . " . " . $objPagu->daftar_nomor[3]["nomor"] . " . " . $objPagu->daftar_nomor[5]["nomor"];
		//$data_set["nomor_pagu"] = json_encode($objPagu->daftar_nomor);
		$dt["nomor_pagu"] = $nomor_pagu_ini;
	}
	
	//print_r($data);
	
	/********************************************* Twig engine *********************************************/
	Twig_Autoloader::register();

	$loader = new Twig_Loader_Filesystem('../views');
	$twig = new Twig_Environment($loader);
	
	echo $twig->render('jasa_giro.php', array(
			'judul' => 'Assalamualaikum',
			'data' => $data,
			'tgl_dari' => $_GET["tgl_dari"],
			'tgl_sampai' => $_GET["tgl_sampai"],
			'keterangan' => $_GET["keterangan"]
		)
	);
	/********************************************* End Of : Twig Engine *********************************************/
?>