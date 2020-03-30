<?php
	error_reporting(0);
	session_start();
	include_once "../models/autoloader.php";
	require_once "../twig-1x/lib/Twig/Autoloader.php";
	
	TUser::BelumLogin();
	
	// Load data pegawai
	$sql_pegawai = "
		SELECT
			a.*, a.nama_pegawai, b.golongan, c.pangkat, a.jabatan
		FROM
			t_pegawai a
			LEFT JOIN m_golongan b ON a.id_golongan = b.id
			LEFT JOIN m_pangkat_pegawai c ON a.id_pangkat = c.id
		WHERE
			(a.nama_pegawai LIKE '%" . $_GET["src"] . "%'
			OR b.golongan LIKE '%" . $_GET["src"] . "%'
			OR c.pangkat LIKE '%" . $_GET["src"] . "%'
			OR a.jabatan LIKE '%" . $_GET["src"] . "%')
		ORDER BY
			a.nama_pegawai ASC
	";
	$res_pegawai = mysqli_query($app_conn, $sql_pegawai);
	$pegawai = array();
	while ($ds_pegawai = mysqli_fetch_assoc($res_pegawai)) {
		array_push($pegawai, $ds_pegawai);
	}
	
	/********************************************* Twig engine *********************************************/
	Twig_Autoloader::register();

	$loader = new Twig_Loader_Filesystem('../views');
	$twig = new Twig_Environment($loader);
	
	echo $twig->render('list_pegawai.php', array(
			'judul' => 'Assalamualaikum',
			'src' => $_GET["src"],
			'pegawai' => $pegawai
		)
	);
	/********************************************* End Of : Twig Engine *********************************************/
?>
