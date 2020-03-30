<?php
	error_reporting(0);
	session_start();
	include_once "../models/autoloader.php";
	require_once "../twig-1x/lib/Twig/Autoloader.php";
	
	TUser::BelumLogin();
	
	if($_GET["hapus"] == 1) {
		$db = new DBConnection();
		$db->perintahSQL = "DELETE FROM itbl_apps_ttd_dokumen WHERE id = ?";
		$db->add_parameter("i", $_GET["id"]);
		$db->execute_non_query();
		
		header("location:" . $_SERVER["PHP_SELF"]);
	}
	
	$db_dokumen = new DBConnection();
	$db_dokumen->perintahSQL = "
		SELECT
			a.*
		FROM
			itbl_apps_dokumen a
		ORDER BY
			a.nama_dokumen ASC
	";
	$ds_dokumen = $db_dokumen->execute_reader();
	$db_dokumen = null;
	
	foreach ($ds_dokumen as &$ds_dok) {
		$db_ttd = new DBConnection();
		$db_ttd->perintahSQL = "
			SELECT
				a.*, b.nama_pegawai
			FROM
				itbl_apps_ttd_dokumen a
				LEFT JOIN t_pegawai b ON a.id_pegawai = b.id
			WHERE
				a.id_dokumen = ?
			ORDER BY
				a.tanggal ASC, a.kode_ttd ASC
		";
		$db_ttd->add_parameter("i", $ds_dok["id"]);
		$ds_dok["ttd"] = $db_ttd->execute_reader();
		$db_ttd = null;
	}
	/********************************************* Twig engine *********************************************/
	Twig_Autoloader::register();

	$loader = new Twig_Loader_Filesystem('../views');
	$twig = new Twig_Environment($loader);
	
	echo $twig->render('ttd_dokumen.php', array(
			'judul' => 'Assalamualaikum',
			'dokumen' => $ds_dokumen
		)
	);
	/********************************************* End Of : Twig Engine *********************************************/
?>