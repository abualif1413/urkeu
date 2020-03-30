<?php
	session_start();
	require_once "../twig-1x/lib/Twig/Autoloader.php";
	
	/********************************************* Twig engine *********************************************/
	Twig_Autoloader::register();

	$loader = new Twig_Loader_Filesystem('../views/layout_page');
	$twig = new Twig_Environment($loader);
	
	echo $twig->render('home.php', array(
			'judul' => 'Assalamualaikum'
		)
	);
	/********************************************* End Of : Twig Engine *********************************************/
?>