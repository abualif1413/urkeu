<?php
	error_reporting(0);
	session_start();
	include_once "../models/autoloader.php";
	
	// Apakah dia sudah di SPBy?
	$db_sudah_spby = new DBConnection();
	$db_sudah_spby->perintahSQL = "SELECT * FROM itbl_apps_spby WHERE id_spp_spm = ?";
	$db_sudah_spby->add_parameter("i", $_GET["id_spp_spm"]);
	$ds_sudah_spby = $db_sudah_spby->execute_reader();
	$db_sudah_spby = null;
	
	if(count($ds_sudah_spby) == 0) {
		$db = new DBConnection();
		$db->perintahSQL = "
			SELECT
				LPAD(nomor.nomor_terakhir + 1, 5, '0') AS nomor_sekarang
			FROM
				(
					SELECT
						COALESCE(MAX(CAST(a.nomor AS UNSIGNED)), 0) AS nomor_terakhir
					FROM
						itbl_apps_spby a
					WHERE
						YEAR(a.tanggal) = YEAR(?)
				) nomor
		";
		$db->add_parameter("s", $_GET["tanggal"]);
		$ds = $db->execute_reader();
		$db = null;
		
		$nomor = array("nomor" => $ds[0]["nomor_sekarang"]);
		
		echo json_encode($nomor);
	} else {
		$nomor = array("nomor" => $ds_sudah_spby[0]["nomor"]);
	}
	
?>