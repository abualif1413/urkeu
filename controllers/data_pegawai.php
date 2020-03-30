<?php
	error_reporting(0);
	session_start();
	include_once "../models/autoloader.php";
	require_once "../twig-1x/lib/Twig/Autoloader.php";
	
	TUser::BelumLogin();
	
	if($_POST["save"] == "Save") {
		if($_POST["id"] != "") {
			// Maka dia edit data
			// Maka dia tambah data
			$db = new DBConnection();
			$db->perintahSQL = "
				UPDATE t_pegawai SET
					id_jenis_pegawai=?, id_pangkat=?, nama_pegawai=?,
					nik=?, id_golongan=?, no_rekening=?,
					nama_rekening=?, npwp=?, jabatan=?,
					jenis_kelamin=?, tempat_lahir=?, tgl_lahir=?,
					id_agama=?, alamat=?, kode_pos=?,
					pendidikan=?, gapok=?
				WHERE
					id=?
			";
			$db->add_parameter("i", $_POST["id_jenis_pegawai"]);
			$db->add_parameter("i", $_POST["id_pangkat"]);
			$db->add_parameter("s", $_POST["nama_pegawai"]);
			
			$db->add_parameter("s", $_POST["nik"]);
			$db->add_parameter("i", $_POST["id_golongan"]);
			$db->add_parameter("s", $_POST["no_rekening"]);
			
			$db->add_parameter("s", $_POST["nama_rekening"]);
			$db->add_parameter("s", $_POST["npwp"]);
			$db->add_parameter("s", $_POST["jabatan"]);
			
			$db->add_parameter("s", $_POST["jenis_kelamin"]);
			$db->add_parameter("s", $_POST["tempat_lahir"]);
			$db->add_parameter("s", $_POST["tgl_lahir"]);
			
			$db->add_parameter("i", $_POST["id_agama"]);
			$db->add_parameter("s", $_POST["alamat"]);
			$db->add_parameter("s", $_POST["kode_pos"]);
			
			$db->add_parameter("s", $_POST["pendidikan"]);
			$db->add_parameter("s", $_POST["gapok"]);
			
			$db->add_parameter("i", $_POST["id"]);
			
			$db->execute_non_query();
			
			header("location:list_pegawai.php");
		} else {
			// Maka dia tambah data
			$db = new DBConnection();
			$db->perintahSQL = "
				INSERT INTO t_pegawai(
					id_jenis_pegawai, id_pangkat, nama_pegawai,
					nik, id_golongan, no_rekening,
					nama_rekening, npwp, jabatan,
					jenis_kelamin, tempat_lahir, tgl_lahir,
					id_agama, alamat, kode_pos,
					pendidikan, gapok
				) VALUES (
					?, ?, ?,
					?, ?, ?,
					?, ?, ?,
					?, ?, ?,
					?, ?, ?,
					?, ?
				)
			";
			$db->add_parameter("i", $_POST["id_jenis_pegawai"]);
			$db->add_parameter("i", $_POST["id_pangkat"]);
			$db->add_parameter("s", $_POST["nama_pegawai"]);
			
			$db->add_parameter("s", $_POST["nik"]);
			$db->add_parameter("i", $_POST["id_golongan"]);
			$db->add_parameter("s", $_POST["no_rekening"]);
			
			$db->add_parameter("s", $_POST["nama_rekening"]);
			$db->add_parameter("s", $_POST["npwp"]);
			$db->add_parameter("s", $_POST["jabatan"]);
			
			$db->add_parameter("s", $_POST["jenis_kelamin"]);
			$db->add_parameter("s", $_POST["tempat_lahir"]);
			$db->add_parameter("s", $_POST["tgl_lahir"]);
			
			$db->add_parameter("i", $_POST["id_agama"]);
			$db->add_parameter("s", $_POST["alamat"]);
			$db->add_parameter("s", $_POST["kode_pos"]);
			
			$db->add_parameter("s", $_POST["pendidikan"]);
			$db->add_parameter("s", $_POST["gapok"]);
			
			$db->execute_non_query();
			
			header("location:list_pegawai.php");
		}
	}
	
	// Load golongan
	$sql_golongan = "SELECT * FROM m_golongan ORDER BY id ASC";
	$res_golongan = mysqli_query($app_conn,$sql_golongan);
	$golongan = array();
	while ($ds_golongan = mysqli_fetch_assoc($res_golongan)) {
		array_push($golongan, $ds_golongan);
	}
	
	// Load pangkat
	$sql_pangkat = "SELECT * FROM m_pangkat_pegawai ORDER BY id ASC";
	$res_pangkat = mysqli_query($app_conn, $sql_pangkat);
	$pangkat = array();
	while ($ds_pangkat = mysqli_fetch_assoc($res_pangkat)) {
		array_push($pangkat, $ds_pangkat);
	}
	
	// Load Agama
	$sql_agama = "SELECT * FROM m_agama ORDER BY id ASC";
	$res_agama = mysqli_query($app_conn, $sql_agama);
	$agama = array();
	while ($ds_agama = mysqli_fetch_assoc($res_agama)) {
		array_push($agama, $ds_agama);
	}
	
	// Load jenis kelamin
	$jenis_kelamin = array();
	array_push($jenis_kelamin, array("kode" => "L", "jenkel" => "Laki-Laki"));
	array_push($jenis_kelamin, array("kode" => "P", "jenkel" => "Perempuan"));
	
	// Load pegawai
	$sql_pegawai = "SELECT * FROM t_pegawai WHERE id='" . $_GET["id"] . "'";
	$res_pegawai = mysqli_query($app_conn, $sql_pegawai);
	$ds_pegawai = mysqli_fetch_assoc($res_pegawai);
	
	// Load jenis pegawai
	$sql_jenis_pegawai = "SELECT * FROM m_jenis_pegawai ORDER BY id ASC";
	$res_jenis_pegawai = mysqli_query($app_conn, $sql_jenis_pegawai);
	$jenis_pegawai = array();
	while ($ds_jenis_pegawai = mysqli_fetch_assoc($res_jenis_pegawai)) {
		array_push($jenis_pegawai, $ds_jenis_pegawai);
	}
	
	/********************************************* Twig engine *********************************************/
	Twig_Autoloader::register();

	$loader = new Twig_Loader_Filesystem('../views');
	$twig = new Twig_Environment($loader);
	
	echo $twig->render('data_pegawai.php', array(
			'judul' => 'Assalamualaikum',
			'id' => $_GET["id"],
			'golongan' => $golongan,
			'pangkat' => $pangkat,
			'agama' => $agama,
			'jenis_kelamin' => $jenis_kelamin,
			'pegawai' => $ds_pegawai,
			'jenis_pegawai' => $jenis_pegawai
		)
	);
	/********************************************* End Of : Twig Engine *********************************************/
?>