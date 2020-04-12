<?php
	error_reporting(0);
	session_start();
	include_once "../models/autoloader.php";
	require_once "../twig-1x/lib/Twig/Autoloader.php";
	
	$dbCOA = new DBConnection();
	$dbCOA->perintahSQL = "SELECT * FROM itbl_main_coa WHERE id >= 385 ORDER BY id ASC";
	$dsCOA = $dbCOA->execute_reader();
	$dbCOA = null;
	echo "<ul>";
	foreach ($dsCOA as $ds) {
		$acc_number_explode = explode(".", $ds["acc_number"]);
		foreach ($acc_number_explode as &$explode) {
			$int_number_split = intval($explode);
			$explode = str_pad($int_number_split, 5, "0", STR_PAD_LEFT);
		}
		$acc_number_baru = implode(".", $acc_number_explode);
		echo "<li>" . $ds["acc_number"] . " - " . $ds["acc_name"] . " " . $acc_number_baru . "</li>";
		$dbEditCOA = new DBConnection();
		$dbEditCOA->perintahSQL = "UPDATE itbl_main_coa SET acc_number=? WHERE id=?";
		$dbEditCOA->add_parameter("s", $acc_number_baru);
		$dbEditCOA->add_parameter("i", $ds["id"]);
		$dbEditCOA->execute_non_query();
		$dbEditCOA = null;
	}
	echo "</ul>";
?>
