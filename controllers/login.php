<?php
	error_reporting(0);
	session_start();
	include_once "../models/autoloader.php";
	require_once "../twig-1x/lib/Twig/Autoloader.php";
	
	TUser::SudahLogin();
	
	if($_POST["login"] == "Login") {
		$userObj = TUser::GoLogin($_POST["username"], $_POST["password"]);
		if($userObj->id > 0) {
			$_SESSION["APP_USER_ID"] = $userObj->id;
			header("location:" . $_SERVER["PHP_SELF"] . "?login_berhasil=1");
		} else {
			header("location:" . $_SERVER["PHP_SELF"] . "?login_berhasil=-1");
		}
	}
	
	/********************************************* Twig engine *********************************************/
	Twig_Autoloader::register();

	$loader = new Twig_Loader_Filesystem('../views');
	$twig = new Twig_Environment($loader);
	
	echo $twig->render('login.php', array(
			'judul' => 'Assalamualaikum',
			'login_berhasil' => $_GET["login_berhasil"]
		)
	);
	/********************************************* End Of : Twig Engine *********************************************/
?>
