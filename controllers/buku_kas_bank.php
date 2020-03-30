<?php
	error_reporting(0);
	session_start();
	include_once "../models/autoloader.php";
	require_once "../twig-1x/lib/Twig/Autoloader.php";
	
	TUser::BelumLogin();
	
	
	
	/********************************************* Twig engine *********************************************/
	Twig_Autoloader::register();

	$loader = new Twig_Loader_Filesystem('../views');
	$twig = new Twig_Environment($loader);
	
	echo $twig->render('buku_kas_bank.php', array(
			'judul' => 'Assalamualaikum',
			'bulan' => semua_bulan()
		)
	);
	/********************************************* End Of : Twig Engine *********************************************/
?>