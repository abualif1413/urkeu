<?php
	error_reporting(0);
	session_start();
	include_once "../models/autoloader.php";
	require_once "../twig-1x/lib/Twig/Autoloader.php";
	
	TUser::BelumLogin();
	
	if($_GET["ajax"] == 1) {
		if($_GET["jenis"] == "go_edit") {
			$obj = new DetailPermohonanDanaModel();
			$obj->Record($_GET["id"]);
			echo json_encode($obj);
		} elseif($_GET["jenis"] == "go_edit_normatif") {
			$sql = "SELECT * FROM t_detail_permohonan_dana_normatif WHERE id='" . $_GET["id"] . "'";
			$res = mysqli_query($app_conn, $sql);
			$ds = mysqli_fetch_assoc($res);
			
			echo json_encode($ds);
		}
		exit;
	}
	
	if($_POST["add"] == "Add") {
		$ppn = 0;
		$pph = 0;
		if(isset($_POST["ppn"])) {
			$ppn = 1;
		}
		if(isset($_POST["pph"])) {
			$pph = 1;
		}
		$obj = new DetailPermohonanDanaModel();
		$obj->penerima = $_POST["penerima"];
		$obj->qty = $_POST["qty"];
		$obj->satuan = $_POST["satuan"];
		$obj->harga_satuan = $_POST["harga_satuan"];
		$obj->uraian = $_POST["uraian"];
		$obj->no_faktur = $_POST["no_faktur"];
		$obj->tgl_faktur = $_POST["tgl_faktur"];
		$obj->ppn = $ppn;
		$obj->pph = $pph;
		$obj->Insert();
		header("location:" . $_SERVER["PHP_SELF"] . "?pd=" . $_POST["pd"]);
	}
	
	if($_POST["add_normatif"] == "Add") {
		$db = new DBConnection();
		$db->perintahSQL = "
			INSERT INTO t_detail_permohonan_dana_normatif(
				id_belanja_honor, id_pegawai, jabatan_pengelola, qty,
				sbu_honor, user_insert
			) VALUES(
				0, ?, ?, ?,
				?, '" . $_SESSION["APP_USER_ID"] . "'
			)
		";
		$db->add_parameter("i", $_POST["normatif_id_pegawai"]);
		$db->add_parameter("s", $_POST["normatif_jabatan_pengelola"]);
		$db->add_parameter("i", $_POST["normatif_qty"]);
		$db->add_parameter("d", $_POST["normatif_sbu_honor"]);
		$db->execute_non_query();
		
		header("location:" . $_SERVER["PHP_SELF"] . "?pd=" . $_POST["pd"]);
	}
	
	if($_POST["edit"] == "Edit") {
		$ppn = 0;
		$pph = 0;
		if(isset($_POST["ppn"])) {
			$ppn = 1;
		}
		if(isset($_POST["pph"])) {
			$pph = 1;
		}
		$obj = new DetailPermohonanDanaModel();
		$obj->penerima = $_POST["penerima"];
		$obj->qty = $_POST["qty"];
		$obj->satuan = $_POST["satuan"];
		$obj->harga_satuan = $_POST["harga_satuan"];
		$obj->uraian = $_POST["uraian"];
		$obj->no_faktur = $_POST["no_faktur"];
		$obj->tgl_faktur = $_POST["tgl_faktur"];
		$obj->ppn = $ppn;
		$obj->pph = $pph;
		$obj->Update($_POST["id_detail"]);
		header("location:" . $_SERVER["PHP_SELF"] . "?pd=" . $_POST["pd"]);
	}
	
	if($_POST["edit_normatif"] == "Edit") {
		$db = new DBConnection();
		$db->perintahSQL = "
			UPDATE t_detail_permohonan_dana_normatif SET
				id_pegawai=?, jabatan_pengelola=?, qty=?,
				sbu_honor=?, user_insert='" . $_SESSION["APP_USER_ID"] ."'
			WHERE
				id=?
		";
		$db->add_parameter("i", $_POST["normatif_id_pegawai"]);
		$db->add_parameter("s", $_POST["normatif_jabatan_pengelola"]);
		$db->add_parameter("i", $_POST["normatif_qty"]);
		$db->add_parameter("d", $_POST["normatif_sbu_honor"]);
		$db->add_parameter("i", $_POST["normatif_id_detail"]);
		$db->execute_non_query();
		
		header("location:" . $_SERVER["PHP_SELF"] . "?pd=" . $_POST["pd"]);
	}
	
	if($_GET["delete"] == 1) {
		$obj = new DetailPermohonanDanaModel();
		$obj->Delete($_GET["id"]);
		header("location:" . $_SERVER["PHP_SELF"] . "?pd=" . $_GET["pd"]);
	}
	
	if($_GET["delete_normatif"] == 1) {
		$db = new DBConnection();
		$db->perintahSQL = "DELETE FROM t_detail_permohonan_dana_normatif WHERE id=?";
		$db->add_parameter("i", $_GET["id"]);
		$db->execute_non_query();
		
		header("location:" . $_SERVER["PHP_SELF"] . "?pd=" . $_GET["pd"]);
	}
	
	if($_POST["save"] == "Save") {
		$obj = new PermohonanDanaModel();
		$obj->tanggal = $_POST["tanggal"];
		$obj->nomor = "";
		$obj->na_nomor = $_POST["na_nomor"];
		$obj->na_bulan = $_POST["na_bulan"];
		$obj->na_tahun = $_POST["na_tahun"];
		$obj->na_divisi = $_POST["na_divisi"];
		$obj->keterangan = $_POST["keperluan"];
		$obj->id_pegawai_ybs = $_POST["id_pegawai"];
		$obj->diketahui_oleh = $_POST["diketahui_oleh"];
		$obj->kuasa_pengguna_anggaran = $_POST["kuasa_pengguna_anggaran"];
		$obj->no_sptjb = $_POST["no_sptjb"];
		$obj->jenis_belanja = $_POST["jenis_belanja"];
		$obj->menyatakan = $_POST["menyatakan"];
		$obj->Insert();
		$obj->KaitkanDetail();
		
		// Kaitkan daftar normatif
		$sql_update = "UPDATE t_detail_permohonan_dana_normatif SET id_belanja_honor='" . $obj->id_yg_diinsert . "' WHERE id_belanja_honor='0' AND user_insert='" . $_SESSION["APP_USER_ID"] . "'";
		mysqli_query($app_conn, $sql_update);
		
		header("location:cetak_berkas_host.php?id=" . $obj->id_yg_diinsert . "&pd=" . $_POST["pd"]);
	}
	
	$pajak = array();
	$sql_pajak = "SELECT * FROM m_jenis_pajak ORDER BY keterangan ASC";
	$res_pajak = mysqli_query($app_conn, $sql_pajak);
	while ($ds_pajak = mysqli_fetch_assoc($res_pajak)) {
		array_push($pajak, $ds_pajak);
	}
	
	$combo_pegawai = PegawaiModel::GetPegawaiCombo01();
	$na_nomor = array();
	for($i=1; $i<=1000; $i++) {
		array_push($na_nomor, $i);
	}
	$na_bulan = array("I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X", "XI", "XII");
	$data_detail = DetailPermohonanDanaBusinessLogic::GetList01($_SESSION["APP_USER_ID"]);
	
	// List daftar normatif
	$sql_list_normatif = "
		SELECT
			a.id, b.nama_pegawai, a.qty, a.sbu_honor
		FROM
			t_detail_permohonan_dana_normatif a
			LEFT JOIN t_pegawai b ON a.id_pegawai = b.id
		WHERE
			a.id_belanja_honor = '0' AND a.user_insert = '" . $_SESSION["APP_USER_ID"] . "'
		ORDER BY
			a.id ASC
	";
	$res_list_normatif = mysqli_query($app_conn, $sql_list_normatif);
	$list_normatif = array();
	while ($ds_list_normatif = mysqli_fetch_assoc($res_list_normatif)) {
		array_push($list_normatif, $ds_list_normatif);
	}
	
	/********************************************* Twig engine *********************************************/
	Twig_Autoloader::register();

	$loader = new Twig_Loader_Filesystem('../views');
	$twig = new Twig_Environment($loader);
	
	echo $twig->render('inp_permohonan_dana.php', array(
			'judul' => 'Assalamualaikum',
			'pajak' => $pajak,
			'combo_pegawai' => $combo_pegawai,
			'data_detail' => $data_detail,
			'list_normatif' => $list_normatif,
			'na_nomor' => $na_nomor,
			'na_bulan' => $na_bulan,
			'pd' => $_GET["pd"]
		)
	);
	/********************************************* End Of : Twig Engine *********************************************/
?>