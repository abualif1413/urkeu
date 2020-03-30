<?php
	error_reporting(0);
	session_start();
	include_once "../models/autoloader.php";
	require_once "../twig-1x/lib/Twig/Autoloader.php";
	
	TUser::BelumLogin();
	
	$record = BelanjaHonorModel::GetFullRecord01($_GET["id"]);
	
	/********************************************* Twig engine *********************************************/
	Twig_Autoloader::register();

	$loader = new Twig_Loader_Filesystem('../views');
	$twig = new Twig_Environment($loader);
	
	echo $twig->render('cetak_berkas_belanja_honor_host.php', array(
			'judul' => 'Assalamualaikum',
			'id' => $_GET["id"],
			'record' => $record
		)
	);
	/********************************************* End Of : Twig Engine *********************************************/
?>