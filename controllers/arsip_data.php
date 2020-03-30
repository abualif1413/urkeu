<?php
	error_reporting(0);
	session_start();
	include_once "../models/autoloader.php";
	require_once "../twig-1x/lib/Twig/Autoloader.php";
	
	if($_GET["ajax"] == 1) {
		if($_GET["jenis"] == "load_file") {
			$sql = "SELECT * FROM t_detail_arsip_berkas WHERE id_arsip_berkas='" . $_GET["id_arsip_berkas"] . "'";
			$res = mysqli_query($app_conn, $sql);
			$filenya = array();
			while ($ds = mysqli_fetch_assoc($res)) {
				array_push($filenya, $ds);
			}
			
			echo json_encode($filenya);
		}
		exit;
	}
	
	if($_POST["save"] == "Save") {
		if($_POST["id"] == "0") {
			$db = new DBConnection();
			$db->perintahSQL = "
				INSERT INTO t_arsip_berkas(
					tanggal, keterangan
				) VALUES(
					?, ?
				)
			";
			$db->add_parameter("s", $_POST["tanggal"]);
			$db->add_parameter("s", $_POST["keterangan"]);
			
			$db->execute_non_query();
			
			// Cari id terkahir
			$sql = "UPDATE t_detail_arsip_berkas SET id_arsip_berkas=(SELECT MAX(id) FROM t_arsip_berkas) WHERE id_arsip_berkas=0";
			mysqli_query($app_conn, $sql);
			
			header("location:list_data_arsip.php");
		} else {
			$db = new DBConnection();
			$db->perintahSQL = "
				UPDATE t_arsip_berkas SET
					tanggal=?, keterangan=?
				WHERE
					id=?
			";
			$db->add_parameter("s", $_POST["tanggal"]);
			$db->add_parameter("s", $_POST["keterangan"]);
			$db->add_parameter("i", $_POST["id"]);
			
			$db->execute_non_query();
			
			header("location:list_data_arsip.php");
		}
		
	}
	
	TUser::BelumLogin();
	
	$sql_arsip = "SELECT * FROM t_arsip_berkas WHERE id='" . $_GET["id"] . "'";
	$res_arsip = mysqli_query($app_conn, $sql_arsip);
	$ds_arsip = mysqli_fetch_assoc($res_arsip);
	
	$id = 0;
	if(!empty($_GET["id"])) {
		$id = $_GET["id"];
	}
	
	/********************************************* Twig engine *********************************************/
	Twig_Autoloader::register();

	$loader = new Twig_Loader_Filesystem('../views');
	$twig = new Twig_Environment($loader);
	
	echo $twig->render('arsip_data.php', array(
			'judul' => 'Assalamualaikum',
			'id' => $id,
			'arsip' => $ds_arsip
		)
	);
	/********************************************* End Of : Twig Engine *********************************************/
?>