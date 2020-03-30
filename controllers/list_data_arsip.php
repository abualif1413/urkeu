<?php
	error_reporting(0);
	session_start();
	include_once "../models/autoloader.php";
	require_once "../twig-1x/lib/Twig/Autoloader.php";
	
	TUser::BelumLogin();
	
	// Load Arsip
	$sql = "SELECT * FROM t_arsip_berkas ORDER BY tanggal ASC, id ASC LIMIT 0, 20";
	$res = mysqli_query($app_conn, $sql);
	$arsip = array();
	while ($ds = mysqli_fetch_assoc($res)) {
		array_push($arsip, $ds);
	}
	
	/********************************************* Twig engine *********************************************/
	Twig_Autoloader::register();

	$loader = new Twig_Loader_Filesystem('../views');
	$twig = new Twig_Environment($loader);
	
	echo $twig->render('list_data_arsip.php', array(
			'judul' => 'Assalamualaikum',
			'id' => $id,
			'arsip' => $arsip
		)
	);
	/********************************************* End Of : Twig Engine *********************************************/
?>