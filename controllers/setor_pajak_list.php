<?php
	error_reporting(0);
	session_start();
	include_once "../models/autoloader.php";
	require_once "../twig-1x/lib/Twig/Autoloader.php";
	
	TUser::BelumLogin();
	
	$data = array();
	if($_GET["cari"] == "Cari") {
		$db_data = new DBConnection();
		$db_data->perintahSQL = "
			SELECT
				pajak.id,
				pajak.tanggal,
				pajak.nomor,
				pajak.keterangan,
				GROUP_CONCAT(DISTINCT sppspm.nomor ORDER BY sppspm.nomor SEPARATOR ', ') AS no_sppspm,
				SUM(
					CASE
						WHEN setpajak.jenis = 'ppn' THEN sppspm.ppn
						ELSE 0
					END
				) AS ppn,
				SUM(
					CASE
						WHEN setpajak.jenis = 'pph' THEN sppspm.pph
						ELSE 0
					END
				) AS pph
			FROM
				vw_daftar_spp_spm sppspm
				INNER JOIN itbl_apps_penyetoran_pajak_detail setpajak ON sppspm.id = setpajak.id_spp_spm
				INNER JOIN itbl_apps_penyetoran_pajak pajak ON setpajak.id_penyetoran_pajak = pajak.id
			WHERE
				pajak.tanggal BETWEEN ? AND ?
			GROUP BY
				pajak.id
			ORDER BY
				pajak.tanggal ASC, pajak.id ASC
		";
		$db_data->add_parameter("s", $_GET["tgl_dari"]);
		$db_data->add_parameter("s", $_GET["tgl_sampai"]);
		$data = $db_data->execute_reader();
		unset($db_data);
	}
	
	if($_GET["hapus"] == 1) {
		$db_hapus = new DBConnection();
		$db_hapus->perintahSQL = "DELETE FROM itbl_apps_penyetoran_pajak WHERE id=?";
		$db_hapus->add_parameter("i", $_GET["id"]);
		$db_hapus->execute_non_query();
		unset($db_hapus);
		
		header("location:" . $_SERVER["PHP_SELF"]);
	}
	/********************************************* Twig engine *********************************************/
	Twig_Autoloader::register();

	$loader = new Twig_Loader_Filesystem('../views');
	$twig = new Twig_Environment($loader);
	
	echo $twig->render('setor_pajak_list.php', array(
			'judul'	=> 'Assalamualaikum',
			'qs'	=> query_string_to_array($_SERVER["QUERY_STRING"]),
			'data'	=> $data
		)
	);
	/********************************************* End Of : Twig Engine *********************************************/
?>