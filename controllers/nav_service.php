<?php
	error_reporting(0);
	session_start();
	include_once "../models/autoloader.php";
	
	if($_GET["ajax"] == 1) {
		if($_GET["jenis"] == "rolling_user") {
			$sql = "SELECT * FROM t_user WHERE id = '" . $_SESSION["APP_USER_ID"] . "'";
			$res = mysqli_query($app_conn, $sql);
			$ds = mysqli_fetch_assoc($res);
			
			echo json_encode($ds);
		}
		exit;
	}
	
?>