<?php
	error_reporting(0);
	session_start();
	include_once "../models/autoloader.php";
	require_once "../twig-1x/lib/Twig/Autoloader.php";
	
	$sql_cek_data = "
		SELECT
			uji.*
		FROM
			(
				SELECT tanggal, na_nomor, na_bulan, na_tahun, na_divisi FROM t_permohonan_dana
				UNION
				SELECT tanggal, na_nomor, na_bulan, na_tahun, na_divisi FROM t_belanja_honor
				UNION
				SELECT tanggal, na_nomor, na_bulan, na_tahun, na_divisi FROM t_belanja_gaji
			) uji
		WHERE
			SUBSTR(uji.tanggal,1,4) = '" . substr($_GET["tanggal"], 0, 4) . "'
		ORDER BY
			uji.na_nomor DESC
		LIMIT
			0, 1
	";
	$res_cek_data = mysqli_query($app_conn, $sql_cek_data);
	$na_nomor = 0;
	while ($ds_cek_data = mysqli_fetch_assoc($res_cek_data)) {
		$na_nomor = $ds_cek_data["na_nomor"];
	}
	$na_nomor += 1;
	
	$pecah_tanggal = explode("-", $_GET["tanggal"]);
	$romawi = array(
		"01" => "I", "02" => "II", "03" => "III", "04" => "IV", "05" => "V", "06" => "VI",
		"07" => "VII", "08" => "VIII", "09" => "IX", "10" => "X", "11" => "XI", "12" => "XII"
	);
	
	$kembali = array(
		"na_nomor" => $na_nomor,
		"na_bulan" => $romawi[$pecah_tanggal[1]],
		"na_tahun" => $pecah_tanggal[0]
	);
	
	echo json_encode($kembali);
?>