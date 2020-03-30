<?php
	error_reporting(0);
	session_start();
	include_once "../models/autoloader.php";
	require_once "../twig-1x/lib/Twig/Autoloader.php";
	
	TUser::BelumLogin();
	
	if($_GET["ajax"] == 1) {
		if($_GET["jenis"] == "go_edit") {
			$sql = "SELECT * FROM t_detail_belanja_gaji WHERE id='" . $_GET["id"] . "'";
			$res = mysqli_query($app_conn, $sql);
			$ds = mysqli_fetch_assoc($res);
			
			echo json_encode($ds);
		} elseif ($_GET["jenis"] == "get_gapok") {
			$sql = "SELECT * FROM t_pegawai WHERE id='" . $_GET["id"] . "'";
			$res = mysqli_query($app_conn, $sql);
			$ds = mysqli_fetch_assoc($res);
			
			echo json_encode($ds);
		}
		exit;
	}
	
	if($_POST["add"] == "Add") {
		$db = new DBConnection();
		$db->perintahSQL = "
			INSERT INTO t_detail_belanja_gaji(
				id_belanja_gaji, id_pegawai, gapok, potongan1, potongan2, potongan3, potongan4,
				user_insert
			) VALUES(
				?, ?, ?, ?, ?, ?, ?,
				'" . $_SESSION["APP_USER_ID"] . "'
			)
		";
		$db->add_parameter("i", $_POST["id"]);
		$db->add_parameter("i", $_POST["id_pegawai"]);
		$db->add_parameter("i", $_POST["gapok"]);
		$db->add_parameter("s", $_POST["potongan1"]);
		$db->add_parameter("s", $_POST["potongan2"]);
		$db->add_parameter("s", $_POST["potongan3"]);
		$db->add_parameter("s", $_POST["potongan4"]);
		$db->execute_non_query();
		
		header("location:" . $_SERVER["PHP_SELF"] . "?id=" . $_POST["id"]);
	}
	
	if($_POST["edit"] == "Edit") {
		$db = new DBConnection();
		$db->perintahSQL = "
			UPDATE t_detail_belanja_gaji SET
				id_pegawai=?, gapok=?, potongan1=?, potongan2=?, potongan3=?, potongan4=?,
				user_insert='" . $_SESSION["APP_USER_ID"] ."'
			WHERE
				id=?
		";
		$db->add_parameter("i", $_POST["id_pegawai"]);
		$db->add_parameter("i", $_POST["gapok"]);
		$db->add_parameter("s", $_POST["potongan1"]);
		$db->add_parameter("s", $_POST["potongan2"]);
		$db->add_parameter("s", $_POST["potongan3"]);
		$db->add_parameter("s", $_POST["potongan4"]);
		$db->add_parameter("i", $_POST["id_detail"]);
		$db->execute_non_query();
		
		header("location:" . $_SERVER["PHP_SELF"] . "?id=" . $_POST["id"]);
	}
	
	if($_GET["delete"] == 1) {
		$db = new DBConnection();
		$db->perintahSQL = "DELETE FROM t_detail_belanja_gaji WHERE id=?";
		$db->add_parameter("i", $_GET["id"]);
		$db->execute_non_query();
		
		header("location:" . $_SERVER["PHP_SELF"]);
	}
	
	if($_POST["save"] == "Save") {
		$db = new DBConnection();
		$db->perintahSQL = "
			UPDATE t_belanja_gaji SET
				tanggal=?, nomor=?,
				na_nomor=?, na_bulan=?, na_tahun=?, na_divisi=?,
				keterangan=?, id_pegawai_ybs=?,
				diketahui_oleh=?, kuasa_pengguna_anggaran=?,
				no_sptjb=?, jenis_belanja=?, menyatakan=?, satuan=?, user_insert=?
			WHERE
				id=?
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
		
		$db->add_parameter("i", $_POST["id"]);
		
		$db->execute_non_query();
		
		header("location:cetak_berkas_belanja_gaji_host.php?id=" . $_POST["id"]);
	}
	
	$combo_pegawai = PegawaiModel::GetPegawaiCombo01();
	$sql_list = "
		SELECT
			a.id, b.nama_pegawai, a.gapok, a.potongan1, a.potongan2, a.potongan3, a.potongan4
		FROM
			t_detail_belanja_gaji a
			LEFT JOIN t_pegawai b ON a.id_pegawai = b.id
		WHERE
			a.id_belanja_gaji = '" . $_GET["id"] . "'
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
	$data = BelanjaGajiModel::GetFullRecord01($_GET["id"]);
	/********************************************* Twig engine *********************************************/
	Twig_Autoloader::register();

	$loader = new Twig_Loader_Filesystem('../views');
	$twig = new Twig_Environment($loader);
	
	echo $twig->render('edit_belanja_gaji_proses.php', array(
			'judul' => 'Assalamualaikum',
			'pajak' => $pajak,
			'combo_pegawai' => $combo_pegawai,
			'data_detail' => $data_detail,
			'na_nomor' => $na_nomor,
			'na_bulan' => $na_bulan,
			'list' => $list,
			'id' => $_GET["id"],
			'data' => $data
		)
	);
	/********************************************* End Of : Twig Engine *********************************************/
?>