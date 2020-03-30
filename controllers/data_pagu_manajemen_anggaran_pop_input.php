<?php
	error_reporting(0);
	session_start();
	include_once "../models/autoloader.php";
	require_once "../twig-1x/lib/Twig/Autoloader.php";
	
	if($_POST["save"] == "Save") {
		$db = new DBConnection();
		$db->perintahSQL = "CALL proc_insert_anggaran(?, ?, ?, ?, ?)";
		$db->add_parameter("i", $_POST["id_coa"]);
		$db->add_parameter("i", $_POST["tahun"]);
		$db->add_parameter("d", $_POST["qty"]);
		$db->add_parameter("s", $_POST["satuan"]);
		$db->add_parameter("d", $_POST["jumlah"]);
		$db->execute_non_query();
		
		echo "
			<script>
				window.close();
				window.opener.window.location.reload();
			</script>
		";
	}
	
	$db = new DBConnection();
	$db->perintahSQL = "SELECT * FROM vw_coa WHERE id=?";
	$db->add_parameter("i", $_GET["id_coa"]);
	$ds = $db->execute_reader();
	$db = null;
	
	$db_data = new DBConnection();
	$db_data->perintahSQL = "SELECT * FROM itbl_apps_anggaran WHERE id_coa=? AND tahun=?";
	$db_data->add_parameter("i", $_GET["id_coa"]);
	$db_data->add_parameter("i", $_GET["tahun"]);
	$ds_data = $db_data->execute_reader();
	$db_data = null;
	
	$objPagu = new PaguService();
	$objPagu->GenerateDaftarNomor($_GET["id_coa"]);
	$nomor_pagu_ini = $objPagu->daftar_nomor[3]["nomor"] . " . " . $objPagu->daftar_nomor[1]["nomor"] . " . " . $objPagu->daftar_nomor[0]["nomor"];
	
	/********************************************* Twig engine *********************************************/
	Twig_Autoloader::register();

	$loader = new Twig_Loader_Filesystem('../views');
	$twig = new Twig_Environment($loader);
	
	echo $twig->render('data_pagu_manajemen_anggaran_pop_input.php', array(
			'judul' => 'Assalamualaikum',
			'qs' => query_string_to_array($_SERVER["QUERY_STRING"]),
			'nama_akun' => $ds[0]["acc_name"],
			'nomor_pagu' => $nomor_pagu_ini,
			'data' => $ds_data[0]
		)
	);
	/********************************************* End Of : Twig Engine *********************************************/
?>
