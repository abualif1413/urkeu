<?php
	error_reporting(0);
	session_start();
	include_once "../models/autoloader.php";
	require_once "../twig-1x/lib/Twig/Autoloader.php";
	
	TUser::BelumLogin();
	
	if($_GET["ajax"] == 1) {
		if($_GET["jenis"] == "go_edit") {
			$sql = "SELECT * FROM t_detail_belanja_honor WHERE id='" . $_GET["id"] . "'";
			$res = mysqli_query($app_conn, $sql);
			$ds = mysqli_fetch_assoc($res);
			
			echo json_encode($ds);
		}
		exit;
	}
	
	if($_POST["add"] == "Add") {
		$db = new DBConnection();
		$db->perintahSQL = "
			INSERT INTO t_detail_belanja_honor(
				id_belanja_honor, id_pegawai, jabatan_pengelola, qty,
				sbu_honor, user_insert
			) VALUES(
				0, ?, ?, ?,
				?, '" . $_SESSION["APP_USER_ID"] . "'
			)
		";
		$db->add_parameter("i", $_POST["id_pegawai"]);
		$db->add_parameter("s", $_POST["jabatan_pengelola"]);
		$db->add_parameter("i", $_POST["qty"]);
		$db->add_parameter("d", $_POST["sbu_honor"]);
		$db->execute_non_query();
		
		header("location:" . $_SERVER["PHP_SELF"]);
	}
	
	if($_POST["edit"] == "Edit") {
		$db = new DBConnection();
		$db->perintahSQL = "
			UPDATE t_detail_belanja_honor SET
				id_pegawai=?, jabatan_pengelola=?, qty=?,
				sbu_honor=?, user_insert='" . $_SESSION["APP_USER_ID"] ."'
			WHERE
				id=?
		";
		$db->add_parameter("i", $_POST["id_pegawai"]);
		$db->add_parameter("s", $_POST["jabatan_pengelola"]);
		$db->add_parameter("i", $_POST["qty"]);
		$db->add_parameter("d", $_POST["sbu_honor"]);
		$db->add_parameter("i", $_POST["id_detail"]);
		$db->execute_non_query();
		
		header("location:" . $_SERVER["PHP_SELF"]);
	}
	
	if($_GET["delete"] == 1) {
		$db = new DBConnection();
		$db->perintahSQL = "DELETE FROM t_detail_belanja_honor WHERE id=?";
		$db->add_parameter("i", $_GET["id"]);
		$db->execute_non_query();
		
		header("location:" . $_SERVER["PHP_SELF"]);
	}
	
	if($_POST["save"] == "Save") {
		$db = new DBConnection();
		$db->perintahSQL = "
			INSERT INTO t_belanja_honor(
				tanggal, nomor,
				na_nomor, na_bulan, na_tahun, na_divisi,
				keterangan, id_pegawai_ybs,
				diketahui_oleh, kuasa_pengguna_anggaran,
				no_sptjb, jenis_belanja, menyatakan, satuan, user_insert
			) VALUES(
				?, ?,
				?, ?, ?, ?,
				?, ?,
				?, ?,
				?, ?, ?, ?, ?
			)
		";
		$kosong = "";
		$db->add_parameter("s", $_POST["tanggal"]);
		$db->add_parameter("s", $kosong);
		
		$db->add_parameter("i", $_POST["na_nomor"]);
		$db->add_parameter("s", $_POST["na_bulan"]);
		$db->add_parameter("i", $_POST["na_tahun"]);
		$db->add_parameter("s", $_POST["na_divisi"]);
		
		$db->add_parameter("s", $_POST["keterangan"]);
		$db->add_parameter("i", $_POST["id_pegawai_ybs"]);
		
		$db->add_parameter("i", $_POST["diketahui_oleh"]);
		$db->add_parameter("i", $_POST["kuasa_pengguna_anggaran"]);
		
		$db->add_parameter("s", $_POST["no_sptjb"]);
		$db->add_parameter("s", $_POST["jenis_belanja"]);
		$db->add_parameter("s", $_POST["menyatakan"]);
		$db->add_parameter("s", $_POST["satuan"]);
		$db->add_parameter("s", $_SESSION["APP_USER_ID"]);
		$db->execute_non_query();
		
		$id_permohonan_dana = 0;
		$sql_data_terakhir = "SELECT * FROM t_belanja_honor WHERE user_insert='" . $_SESSION["APP_USER_ID"] . "' ORDER BY id DESC LIMIT 0, 1";
		$res_data_terakhir = mysqli_query($app_conn, $sql_data_terakhir);
		$ds_data_terakhir = mysqli_fetch_assoc($res_data_terakhir);
		$id_permohonan_dana = $ds_data_terakhir["id"];
		
		$sql_update = "UPDATE t_detail_belanja_honor SET id_belanja_honor='" . $id_permohonan_dana . "' WHERE id_belanja_honor='0' AND user_insert='" . $_SESSION["APP_USER_ID"] . "'";
		mysqli_query($app_conn, $sql_update);
		
		
		header("location:cetak_berkas_belanja_honor_host.php?id=" . $id_permohonan_dana);
	}
	
	$combo_pegawai = PegawaiModel::GetPegawaiCombo01();
	$sql_list = "
		SELECT
			a.id, b.nama_pegawai, a.qty, a.sbu_honor
		FROM
			t_detail_belanja_honor a
			LEFT JOIN t_pegawai b ON a.id_pegawai = b.id
		WHERE
			a.id_belanja_honor = '0' AND a.user_insert = '" . $_SESSION["APP_USER_ID"] . "'
		ORDER BY
			a.id ASC
	";
	$res_list = mysqli_query($app_conn, $sql_list);
	$list = array();
	while ($ds_list = mysqli_fetch_assoc($res_list)) {
		array_push($list, $ds_list);
	}
	$na_nomor = array();
	for($i=1; $i<=1000; $i++) {
		array_push($na_nomor, $i);
	}
	$na_bulan = array("I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X", "XI", "XII");
	
	/********************************************* Twig engine *********************************************/
	Twig_Autoloader::register();

	$loader = new Twig_Loader_Filesystem('../views');
	$twig = new Twig_Environment($loader);
	
	echo $twig->render('input_belanja_honor.php', array(
			'judul' => 'Assalamualaikum',
			'pajak' => $pajak,
			'combo_pegawai' => $combo_pegawai,
			'data_detail' => $data_detail,
			'na_nomor' => $na_nomor,
			'na_bulan' => $na_bulan,
			'list' => $list
		)
	);
	/********************************************* End Of : Twig Engine *********************************************/
?>