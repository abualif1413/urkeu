<?php
	error_reporting(0);
	session_start();
	include_once "../models/autoloader.php";
	require_once "../twig-1x/lib/Twig/Autoloader.php";
	
	TUser::BelumLogin();
	
	if($_GET["delete"] == 1) {
		$db = new DBConnection();
		$db->perintahSQL = "DELETE FROM itbl_apps_pu WHERE id=?";
		$db->add_parameter("i", $_GET["id"]);
		$db->execute_non_query();
		
		header("location:" . $_SERVER["PHP_SELF"]);
	}
	
	// Mencari role nya
	$whr = "";
	$sql_user = "SELECT * FROM t_user WHERE id = '" . $_SESSION["APP_USER_ID"] . "'";
	$res_user = mysqli_query($app_conn, $sql_user);
	$ds_user = mysqli_fetch_assoc($res_user);
	if($ds_user["id_role"] == 2) {
		$whr .= " AND a.user_insert='" . $_SESSION["APP_USER_ID"] . "' ";
	}
	
	$db = new DBConnection();
	$db->perintahSQL = "
		SELECT
			*,
			(
				SELECT
					COUNT(*)
				FROM
					itbl_apps_pu_detail ai
					LEFT JOIN itbl_apps_spby bi ON ai.id_spp_spm = bi.id_spp_spm
				WHERE
					ai.id_pu = a.id
					AND bi.id_spp_spm IS NOT NULL
			) AS dispby
		FROM
			itbl_apps_pu a
		WHERE
			a.tanggal BETWEEN ? AND ? AND a.keterangan LIKE ? " . $whr;
	$db->add_parameter("s", $_GET["tgl_dari"]);
	$db->add_parameter("s", $_GET["tgl_sampai"]);
	$db->add_parameter("s", "%" . $_GET["keterangan"] . "%");
	$ds = $db->execute_reader();
	$db = null;
	
	/********************************************* Twig engine *********************************************/
	Twig_Autoloader::register();

	$loader = new Twig_Loader_Filesystem('../views');
	$twig = new Twig_Environment($loader);
	
	echo $twig->render('pu_edit.php', array(
			'judul' => 'Assalamualaikum',
			'qs' => query_string_to_array($_SERVER["QUERY_STRING"]),
			'data' => $ds
		)
	);
	/********************************************* End Of : Twig Engine *********************************************/
?>