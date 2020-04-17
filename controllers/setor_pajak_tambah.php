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
				sppspm.id,
				sppspm.nomor AS no_sppspm,
				sppspm.nomor_na,
				sppspm.tanggal,
				sppspm.keterangan,
				sppspm.ppn,
				sppspm.pph,
				COALESCE(setppn.id_spp_spm, 0) AS setor_ppn,
				COALESCE(setpph.id_spp_spm, 0) AS setor_pph
			FROM
				vw_daftar_spp_spm sppspm
				LEFT JOIN itbl_apps_penyetoran_pajak_detail setppn ON sppspm.id = setppn.id_spp_spm AND setppn.jenis = 'ppn'
				LEFT JOIN itbl_apps_penyetoran_pajak_detail setpph ON sppspm.id = setpph.id_spp_spm AND setpph.jenis = 'pph'
			WHERE
				sppspm.tanggal BETWEEN ? AND ?
			ORDER BY
				sppspm.tanggal ASC, sppspm.nomor ASC
		";
		$db_data->add_parameter("s", $_GET["tgl_dari"]);
		$db_data->add_parameter("s", $_GET["tgl_sampai"]);
		$ds_data = $db_data->execute_reader();
		unset($db_data);
		$data = $ds_data;
	}
	
	if($_POST["tambah"] == "Tambah") {
		// Menambah yang diceklis PPN
		foreach ($_POST["chk_ppn"] as $chk_ppn) {
			$db_tambah = new DBConnection();
			$db_tambah->perintahSQL = "
				INSERT INTO itbl_apps_penyetoran_pajak_detail(id_spp_spm, jenis)
				VALUES(?, 'ppn')
			";
			$db_tambah->add_parameter("s", $chk_ppn);
			$db_tambah->execute_non_query();
			unset($db_tambah);
		}
		
		
		// Menambah yang diceklis PPh
		foreach ($_POST["chk_pph"] as $chk_pph) {
			$db_tambah = new DBConnection();
			$db_tambah->perintahSQL = "
				INSERT INTO itbl_apps_penyetoran_pajak_detail(id_spp_spm, jenis)
				VALUES(?, 'pph')
			";
			$db_tambah->add_parameter("s", $chk_pph);
			$db_tambah->execute_non_query();
			unset($db_tambah);
		}
		
		header("location:" . $_SERVER["PHP_SELF"]);
	}
	
	if($_POST["simpan"] == "Simpan") {
		$db_simpan = new DBConnection();
		$db_simpan->perintahSQL = "
			INSERT INTO itbl_apps_penyetoran_pajak(tanggal, nomor, keterangan)
			VALUES(?, ?, ?)
		";
		$db_simpan->add_parameter("s", $_POST["tgl_setor"]);
		$db_simpan->add_parameter("s", $_POST["nomor"]);
		$db_simpan->add_parameter("s", $_POST["keterangan"]);
		$db_simpan->execute_non_query();
		$id_penyetoran_pajak = $db_simpan->insert_id;
		unset($db_simpan);
		
		$db_update = new DBConnection();
		$db_update->perintahSQL = "
			UPDATE itbl_apps_penyetoran_pajak_detail SET id_penyetoran_pajak=? WHERE id_penyetoran_pajak IS NULL
		";
		$db_update->add_parameter("i", $id_penyetoran_pajak);
		$db_update->execute_non_query();
		unset($db_update);
		
		header("location:setor_pajak_cetak.php?id=" . $id_penyetoran_pajak);
	}
	
	if($_GET["hapus_item"] == 1) {
		$db_hapus_item = new DBConnection();
		$db_hapus_item->perintahSQL = "DELETE FROM itbl_apps_penyetoran_pajak_detail WHERE id_spp_spm=? AND jenis=?";
		$db_hapus_item->add_parameter("i", $_GET["id_sppspm"]);
		$db_hapus_item->add_parameter("s", $_GET["jenis"]);
		$db_hapus_item->execute_non_query();
		unset($db_hapus_item);
		
		header("location:" . $_SERVER["PHP_SELF"]);
	}
	
	$db_pilih = new DBConnection();
	$db_pilih->perintahSQL = "
		SELECT
			sppspm.id,
			sppspm.nomor AS no_sppspm,
			sppspm.nomor_na,
			sppspm.tanggal,
			sppspm.keterangan,
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
		WHERE
			setpajak.id_penyetoran_pajak IS NULL
		GROUP BY
			sppspm.id
		ORDER BY
			sppspm.tanggal ASC, sppspm.nomor ASC
	";
	$ds_pilih = $db_pilih->execute_reader();
	unset($db_pilih);
	
	/********************************************* Twig engine *********************************************/
	Twig_Autoloader::register();

	$loader = new Twig_Loader_Filesystem('../views');
	$twig = new Twig_Environment($loader);
	
	echo $twig->render('setor_pajak_tambah.php', array(
			'judul' => 'Assalamualaikum',
			'qs'	=> query_string_to_array($_SERVER["QUERY_STRING"]),
			'data'	=> $data,
			'pilih'	=> $ds_pilih
		)
	);
	/********************************************* End Of : Twig Engine *********************************************/
?>