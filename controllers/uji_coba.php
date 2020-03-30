<?php
	//error_reporting(0);
	session_start();
	include_once "../models/autoloader.php";
	require_once "../twig-1x/lib/Twig/Autoloader.php";
	
	echo "Assalamualaikum uji coba...";
	
	echo "<hr />";
	
	$db = new DBConnection();
	$db->perintahSQL = "SELECT * FROM vw_coa WHERE parent_id = ? AND posisi = ?";
	$db->add_parameter("i", 1);
	$db->add_parameter("s", "neraca");
	$ds = $db->execute_reader_cadangan();
	print_r($ds);
	
	echo "<hr />";
	
	$susunan_pagu = PaguService::SusunanPagu(11);
	print_r($susunan_pagu);
?>
