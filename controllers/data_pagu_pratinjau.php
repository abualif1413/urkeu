<?php
	error_reporting(0);
	session_start();
	include_once "../models/autoloader.php";
	require_once "../twig-1x/lib/Twig/Autoloader.php";
	
	TUser::BelumLogin();
	
	if($_GET["ajax"] == 1) {
		if($_GET["jenis"] == "load_nama_head") {
			$db = new DBConnection();
			$db->perintahSQL = "SELECT * FROM vw_coa WHERE id=?";
			$db->add_parameter("i", $_GET["id"]);
			$ds = $db->execute_reader();
			$db = null;
			
			echo $ds[0]["nomor_coa"] . " - " . $ds[0]["acc_name"];
		}
		exit;
	}
	
	if($_POST["save"] == "Save") {
		$db = new DBConnection();
		$db->perintahSQL = "CALL proc_insert_coa(?, ?, ?)";
		$db->add_parameter("i", $_POST["parent_id"]);
		$db->add_parameter("s", $_POST["nama"]);
		$db->add_parameter("s", $_POST["nomor"]);
		$db->execute_non_query();
		
		header("location:" . $_SERVER["PHP_SELF"] . "?id_header=" . $_POST["parent_utama"]);
	}
	
	if($_GET["delete"] == 1) {
		$db = new DBConnection();
		$db->perintahSQL = "CALL proc_delete_coa(?)";
		$db->add_parameter("i", $_GET["id"]);
		$db->execute_non_query();
		
		header("location:" . $_SERVER["PHP_SELF"] . "?id_header=" . $_GET["parent_utama"]);
	}
	
	$db_coa_pagu = new DBConnection();
	$db_coa_pagu->perintahSQL = "SELECT * FROM vw_coa_pagu";
	$coa_pagu = $db_coa_pagu->execute_reader();
	
	$db_isi_pagu = new DBConnection();
	$db_isi_pagu->perintahSQL = "
		SELECT
			*
		FROM
			vw_coa
		WHERE
			acc_number LIKE CONCAT((SELECT acc_number FROM vw_coa WHERE id = '" . $_GET["id_header"] . "'), '%')
			AND id <> '" . $_GET["id_header"] . "'
	";
	$isi_pagu = $db_isi_pagu->execute_reader();
	foreach ($isi_pagu as &$ispg) {
		$ispg["jumlah"] = PaguService::GetTotalAnggaran($ispg["id"], $_GET["tahun"]);
	}
	$db_isi_pagu = null;
	/********************************************* Twig engine *********************************************/
	Twig_Autoloader::register();

	$loader = new Twig_Loader_Filesystem('../views');
	$twig = new Twig_Environment($loader);
	
	echo $twig->render('data_pagu_pratinjau.php', array(
			'judul' => 'Assalamualaikum',
			'qs' => query_string_to_array($_SERVER["QUERY_STRING"]),
			'coa_pagu' => $coa_pagu,
			'isi_pagu' => $isi_pagu
		)
	);
	/********************************************* End Of : Twig Engine *********************************************/
?>
