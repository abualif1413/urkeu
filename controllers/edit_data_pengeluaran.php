<?php
	error_reporting(0);
	session_start();
	include_once "../models/autoloader.php";
	require_once "../twig-1x/lib/Twig/Autoloader.php";
	
	TUser::BelumLogin();
	
	if($_GET["delete"] == 1) {
		mysqli_query($app_conn, "DELETE FROM t_permohonan_dana WHERE id='" . $_GET["id"] . "'");
		header("location:" . $_SERVER["PHP_SELF"]);
	}
	$pd = isset($_GET["pd"]) ? $_GET["pd"] : "";
	$data = PermohonanDanaModel::GetList02_baru($_GET["tgl_dari"], $_GET["tgl_sampai"], $_GET["uraian"], $pd, $_GET["jenis"]);
	
	/********************************************* Twig engine *********************************************/
	Twig_Autoloader::register();

	$loader = new Twig_Loader_Filesystem('../views');
	$twig = new Twig_Environment($loader);
	
	echo $twig->render('edit_permohonan_dana.php', array(
			'judul' => 'Assalamualaikum',
			'tgl_dari' => $_GET["tgl_dari"],
			'tgl_sampai' => $_GET["tgl_sampai"],
			'uraian' => $_GET["uraian"],
			'data' => $data,
			'pd' => $_GET["pd"],
			'jenis' => $_GET["jenis"]
		)
	);
	/********************************************* End Of : Twig Engine *********************************************/
?>