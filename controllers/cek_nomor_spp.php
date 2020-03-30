<?php
	error_reporting(0);
	session_start();
	include_once "../models/autoloader.php";
	
	$db = new DBConnection();
	$db->perintahSQL = "
		SELECT
			LPAD(nomor.nomor_terakhir + 1, 5, '0') AS nomor_sekarang
		FROM
			(
				SELECT
					COALESCE(MAX(CAST(a.nomor AS UNSIGNED)), 0) AS nomor_terakhir
				FROM
					itbl_apps_spp_spm a
				WHERE
					YEAR(a.tanggal) = YEAR(?)
			) nomor
	";
	$db->add_parameter("s", $_GET["tanggal"]);
	$ds = $db->execute_reader();
	$db = null;
	
	$nomor = array("nomor" => $ds[0]["nomor_sekarang"]);
	
	echo json_encode($nomor);
	
?>