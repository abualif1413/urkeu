<?php
	error_reporting(0);
	session_start();
	include_once "../models/autoloader.php";
	require_once "../twig-1x/lib/Twig/Autoloader.php";
	
	$file = $_FILES["berkas"];
	$tmp_name = $file["tmp_name"];
	$name = $file["name"];
	
	// Cari ekstensi
	$pecah_name = explode(".", $name);
	$ekstensi = $pecah_name[(count($pecah_name) - 1)];
	
	$nama_file_fisik = session_id() . $name . "." . $ekstensi;
	
	move_uploaded_file($tmp_name, "../arsip_file/" . $nama_file_fisik);
	
	// Simpan ke database
	$db = new DBConnection();
	$db->perintahSQL = "
		INSERT INTO t_detail_arsip_berkas(
			id_arsip_berkas, nama_file, nama_file_fisik
		) VALUES(
			'" . $_POST["id_arsip_berkas"] . "', '" . $name . "', '" . $nama_file_fisik . "'
		)
	";
	
	$db->execute_non_query();
	
	echo "<script>";
		echo "window.top.window.load_file();";
	echo "</script>";
?>