<?php
	error_reporting(0);
	session_start();
	include_once "../models/autoloader.php";
	require_once "../twig-1x/lib/Twig/Autoloader.php";
	
	TUser::BelumLogin();
	
	$db_isi_pagu = new DBConnection();
	$db_isi_pagu->perintahSQL = "
		SELECT
			*
		FROM
			vw_coa
		WHERE
			acc_number LIKE CONCAT((SELECT acc_number FROM vw_coa WHERE id = '" . $_GET["id_header"] . "'), '%')
			AND id <> '" . $_GET["id_header"] . "'
	";
	$isi_pagu = $db_isi_pagu->execute_reader();
	foreach ($isi_pagu as &$ispg) {
		$sampai = $_GET["per_tgl"];
		$dari = substr($sampai, 0, 7) . "-01";
		$awal_tahun = substr($sampai, 0, 4) . "-01-01";
		
		$ispg["jumlah"] = PaguService::GetTotalAnggaran($ispg["id"], $_GET["tahun"]);
		
		// Mencari realisasi
		$db_realisasi = new DBConnection();
		$db_realisasi->perintahSQL = "
			SELECT
			func_realisasi_pagu(?, ?, ?) AS saat_ini,
			func_realisasi_pagu(?, DATE_ADD(?,INTERVAL -1 DAY), ?) AS bulan_lalu
		";
		$db_realisasi->add_parameter("s", $dari);
		$db_realisasi->add_parameter("s", $sampai);
		$db_realisasi->add_parameter("i", $ispg["id"]);
		$db_realisasi->add_parameter("s", $awal_tahun);
		$db_realisasi->add_parameter("s", $dari);
		$db_realisasi->add_parameter("i", $ispg["id"]);
		$ds_realisasi = $db_realisasi->execute_reader();
		$db_realisasi = null;
		
		$ispg["bulan_lalu"] = $ds_realisasi[0]["bulan_lalu"];
		$ispg["saat_ini"] = $ds_realisasi[0]["saat_ini"];
		$ispg["jumlah_realisasi"] = $ds_realisasi[0]["bulan_lalu"] + $ds_realisasi[0]["saat_ini"];
		$ispg["sisa"] = $ispg["jumlah"] - $ispg["jumlah_realisasi"];
	}
	$db_isi_pagu = null;
	/********************************************* Twig engine *********************************************/
	Twig_Autoloader::register();

	$loader = new Twig_Loader_Filesystem('../views');
	$twig = new Twig_Environment($loader);
	
	echo $twig->render('data_pagu_cetak_lra.php', array(
			'judul' => 'Assalamualaikum',
			'qs' => query_string_to_array($_SERVER["QUERY_STRING"]),
			'isi_pagu' => $isi_pagu
		)
	);
	/********************************************* End Of : Twig Engine *********************************************/
?>
