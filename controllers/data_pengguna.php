<?php
	error_reporting(0);
	session_start();
	include_once "../models/autoloader.php";
	require_once "../twig-1x/lib/Twig/Autoloader.php";
	
	TUser::BelumLogin();
	
	if($_POST["save"] == "Save") {
		if($_POST["id"] != "") {
			// Maka dia edit data
			$db = new DBConnection();
			$db->perintahSQL = "
				UPDATE t_user SET
					nama=?, username=?, password=MD5(?)
				WHERE
					id=?
			";
			$db->add_parameter("s", $_POST["nama"]);
			$db->add_parameter("s", $_POST["username"]);
			$db->add_parameter("s", $_POST["pwd1"]);
			$db->add_parameter("i", $_POST["id"]);
			$db->execute_non_query();
			
			header("location:list_pengguna.php");
		} else {
			// Maka dia tambah data
			// Maka dia edit data
			$db = new DBConnection();
			$db->perintahSQL = "
				INSERT INTO t_user(
					nama, username, password, id_role
				) VALUES(
					?, ?, MD5(?), 2
				)
			";
			$db->add_parameter("s", $_POST["nama"]);
			$db->add_parameter("s", $_POST["username"]);
			$db->add_parameter("s", $_POST["pwd1"]);
			$db->execute_non_query();
			
			header("location:list_pengguna.php");
		}
	}
	
	$sql_data = "SELECT * FROM t_user WHERE id='" . $_GET["id"] . "'";
	$res_data = mysqli_query($app_conn, $sql_data);
	$ds_data = mysqli_fetch_assoc($res_data);
	
	/********************************************* Twig engine *********************************************/
	Twig_Autoloader::register();

	$loader = new Twig_Loader_Filesystem('../views');
	$twig = new Twig_Environment($loader);
	
	echo $twig->render('data_pengguna.php', array(
			'judul' => 'Assalamualaikum',
			'id' => $_GET["id"],
			'data' => $ds_data
		)
	);
	/********************************************* End Of : Twig Engine *********************************************/
?>