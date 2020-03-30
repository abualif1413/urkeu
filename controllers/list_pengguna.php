<?php
	error_reporting(0);
	session_start();
	include_once "../models/autoloader.php";
	require_once "../twig-1x/lib/Twig/Autoloader.php";
	
	TUser::BelumLogin();
	
	$sql_pengguna = "SELECT * FROM t_user WHERE id_role <> 1 AND (username LIKE '%" . $_GET["src"] . "%' OR nama LIKE '%" . $_GET["src"] . "%') ORDER BY username ASC";
	$res_pengguna = mysqli_query($app_conn, $sql_pengguna);
	$pengguna = array();
	while ($ds_pengguna = mysqli_fetch_assoc($res_pengguna)) {
		array_push($pengguna, $ds_pengguna);
	}
	
	/********************************************* Twig engine *********************************************/
	Twig_Autoloader::register();

	$loader = new Twig_Loader_Filesystem('../views');
	$twig = new Twig_Environment($loader);
	
	echo $twig->render('list_pengguna.php', array(
			'judul' => 'Assalamualaikum',
			'src' => $_GET["src"],
			'pengguna' => $pengguna
		)
	);
	/********************************************* End Of : Twig Engine *********************************************/
?>