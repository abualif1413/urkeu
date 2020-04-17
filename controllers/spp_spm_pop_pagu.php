<?php
	error_reporting(0);
	session_start();
	include_once "../models/autoloader.php";
	require_once "../twig-1x/lib/Twig/Autoloader.php";
	
	TUser::BelumLogin();
	
	if($_GET["ajax"] == 1) {
		
		exit;
	}
	
	$db_coa_pagu = new DBConnection();
	$db_coa_pagu->perintahSQL = "SELECT * FROM vw_coa_pagu";
	$coa_pagu = $db_coa_pagu->execute_reader();
	
	$db_isi_pagu = new DBConnection();
	$db_isi_pagu->perintahSQL = "
		SELECT
			*
		FROM
			vw_coa
		WHERE
			acc_number LIKE CONCAT((SELECT acc_number FROM vw_coa WHERE id = '5'), '%')
			AND id <> '5'
	";
	$isi_pagu = $db_isi_pagu->execute_reader();
	$db_isi_pagu = null;
	
	
	/********************************************* Twig engine *********************************************/
	Twig_Autoloader::register();

	$loader = new Twig_Loader_Filesystem('../views');
	$twig = new Twig_Environment($loader);
	
	echo $twig->render('spp_spm_pop_pagu.php', array(
			'judul' => 'Assalamualaikum',
			'qs' => query_string_to_array($_SERVER["QUERY_STRING"]),
			'coa_pagu' => $coa_pagu,
			'isi_pagu' => $isi_pagu
		)
	);
	/********************************************* End Of : Twig Engine *********************************************/
?>