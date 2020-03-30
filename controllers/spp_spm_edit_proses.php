<?php
	error_reporting(0);
	session_start();
	include_once "../models/autoloader.php";
	require_once "../twig-1x/lib/Twig/Autoloader.php";
	
	TUser::BelumLogin();
	
	if($_GET["ajax"] == 1) {
		if($_GET["jenis"] == "load_data_belanja") {
			$db = new DBConnection();
			switch ($_GET["jenis_belanja"]) {
				case 'belanja barang':
				case 'belanja perjalanan dinas':
				case 'belanja pemeliharaan':
				case 'belanja pengadaan':
					$db->perintahSQL = "SELECT * FROM vw_belanja_barang WHERE id=?";
					break;
				case 'belanja honor':
					$db->perintahSQL = "SELECT * FROM vw_belanja_honor WHERE id=?";
					break;
				case 'belanja gaji':
					$db->perintahSQL = "SELECT * FROM vw_belanja_gaji WHERE id=?";
					break;
				default:
					
					break;
			}
			
			$db->add_parameter("i", $_GET["id"]);
			$ds = $db->execute_reader();
			$data_belanja = $ds[0];
			// Mencari apakah dia ada pemecahan pembayaran dan jika dia hanya belanja non honor dan gaji
			if($_GET["jenis_belanja"] == "belanja barang" || $_GET["jenis_belanja"] == "belanja perjalanan dinas" ||
				$_GET["jenis_belanja"] == "belanja pemeliharaan" || $_GET["jenis_belanja"] == "belanja pengadaan") {
					$db_pecah_bayar = new DBConnection();
					$db_pecah_bayar->perintahSQL = "SELECT *, (bruto + ppn + pph) AS total FROM t_permohonan_dana_pembayaran WHERE id_belanja_barang = ?";
					$db_pecah_bayar->add_parameter("i", $_GET["id"]);
					$pembayaran = $db_pecah_bayar->execute_reader();
					$data_belanja["pembayaran"] = $pembayaran;
				}
			
			$db = null;
			
			echo json_encode($data_belanja);
		} elseif ($_GET["jenis"] == "load_data_pagu") {
			$db = new DBConnection();
			$db->perintahSQL = "SELECT * FROM vw_coa WHERE id=?";
			$db->add_parameter("i", $_GET["id"]);
			$ds = $db->execute_reader();
			$db = null;
			
			$data = array(
				"nomor_umum" => $ds[0]["nomor_umum"],
				"acc_name" => $ds[0]["acc_name"]
			);
			
			echo json_encode($data);
		} elseif($_GET["jenis"] == "add_pagu") {
			$db = new DBConnection();
			$db->perintahSQL = "
				INSERT INTO itbl_apps_coa_spp_spm (
					id_spp_spm, id_coa_pagu, 
					nilai, user_insert
				) VALUES(
					?, ?, 
					?, ?
				)
			";
			$db->add_parameter("i", $_GET["id_spp_spm"]);
			$db->add_parameter("i", $_GET["id_pagu"]);
			$db->add_parameter("d", $_GET["nilai_pagu"]);
			$db->add_parameter("i", $_SESSION["APP_USER_ID"]);
			$db->execute_non_query();
		} elseif($_GET["jenis"] == "load_pagu_terpilih") {
			$db = new DBConnection();
			$db->perintahSQL = "
				SELECT
					a.id, b.id AS id_coa, b.acc_name, b.nomor_coa, a.nilai
				FROM
					itbl_apps_coa_spp_spm a
					INNER JOIN vw_coa b ON a.id_coa_pagu = b.id
				WHERE
					a.id_spp_spm=?
				ORDER BY
					a.id ASC
			";
			$db->add_parameter("i", $_GET["id_spp_spm"]);
			$ds = $db->execute_reader();
			foreach ($ds as &$data_set) {
				$objPagu = new PaguService();
				$objPagu->GenerateDaftarNomor($data_set["id_coa"]);
				$nomor_pagu_ini = $objPagu->daftar_nomor[3]["nomor"] . " . " . $objPagu->daftar_nomor[1]["nomor"] . " . " . $objPagu->daftar_nomor[0]["nomor"];
				$data_set["nomor_pagu"] = $nomor_pagu_ini;
			}
			$db = null;
			
			echo json_encode($ds);
		} elseif($_GET["jenis"] == "hapus_pagu_terpilih") {
			$db = new DBConnection();
			$db->perintahSQL = "DELETE FROM itbl_apps_coa_spp_spm WHERE id=?";
			$db->add_parameter("i", $_GET["id"]);
			$db->execute_non_query();
			$db = null;
		}
		exit;
	}
	
	if($_POST["save"] == "Save") {
		$obj = new SPPSPM();
		$obj->Record($_POST["id"]);
		$obj->id_belanja = $_POST["id_belanja"];
		$obj->id_pecah_bayar = $_POST["id_pecah_bayar"];
		$obj->jenis_belanja = $_POST["jenis_belanja"]; 
		$obj->id_coa_pagu = $_POST["id_pagu"];
		$obj->nomor = $_POST["nomor"];
		$obj->tanggal = $_POST["tanggal"]; 
		$obj->sifat_pembayaran = $_POST["sifat"];
		$obj->jenis_pembayaran = $_POST["jenis"];
		$obj->user_insert = $_SESSION["APP_USER_ID"];
		$obj->Update();
		
		header("location:cetak_berkas_spp_spm_host.php?id=" . $_POST["id"]);
	}
	
	$objSPM = new SPPSPM();
	$objSPM->Record($_GET["id"]);
	
	/********************************************* Twig engine *********************************************/
	Twig_Autoloader::register();

	$loader = new Twig_Loader_Filesystem('../views');
	$twig = new Twig_Environment($loader);
	
	echo $twig->render('spp_spm_edit_proses.php', array(
			'judul' => 'Assalamualaikum',
			'obj' => $objSPM
		)
	);
	/********************************************* End Of : Twig Engine *********************************************/
?>