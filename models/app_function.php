<?php
	function semua_bulan() {
		$bulan = array(
			"01" => "Januari",
			"02" => "Februari",
			"03" => "Maret",
			
			"04" => "April",
			"05" => "Mei",
			"06" => "Juni",
			
			"07" => "Juli",
			"08" => "Agustus",
			"09" => "September",
			
			"10" => "Oktober",
			"11" => "November",
			"12" => "Desember"
		);
		
		return $bulan;
	}
	
	function query_string_to_array($query_string) {
		$array = array();
		parse_str($query_string, $array);
		return $array;
	}
	
	function tanggal_indonesia_panjang($tanggal) {
		$pecah_tanggal = explode(" ", $tanggal);
		$tanggal = $pecah_tanggal[0];
		$pecah_tanggal = explode("-", $tanggal);
		
		$bulans = array("01" => "Januari", "02" => "Februari", "03" => "Maret", "04" => "April", "05" => "Mei", "06" => "Juni",
						"07" => "Juli", "08" => "Agustus", "09" => "September", "10" => "Oktober", "11" => "November", "12" => "Desember");
		
		$tgl_indonesia = $pecah_tanggal[2] . " " . $bulans[$pecah_tanggal[1]] . " " . $pecah_tanggal[0];
		
		return $tgl_indonesia;
	}
	
	function tanggal_indonesia_pendek($tanggal) {
		$pecah_tanggal = explode(" ", $tanggal);
		$tanggal = $pecah_tanggal[0];
		$pecah_tanggal = explode("-", $tanggal);
		
		$bulans = array("01" => "Januari", "02" => "Februari", "03" => "Maret", "04" => "April", "05" => "Mei", "06" => "Juni",
						"07" => "Juli", "08" => "Agustus", "09" => "September", "10" => "Oktober", "11" => "November", "12" => "Desember");
		
		$tgl_indonesia = $pecah_tanggal[2] . "-" . $pecah_tanggal[1] . "-" . $pecah_tanggal[0];
		
		return $tgl_indonesia;
	}
	
	function tanggal_hari_terakhir($tanggal) {
		$db = new DBConnection();
		$db->perintahSQL = "SELECT LAST_DAY(?) AS tanggal";
		$db->add_parameter("s", $tanggal);
		$ds = $db->execute_reader();
		$db = null;
		
		return $ds[0]["tanggal"];
	}
	
	function nama_hari($tanggal) {
		$db = new DBConnection();
		$db->perintahSQL = "SELECT DAYOFWEEK(?) AS hari";
		$db->add_parameter("s", $tanggal);
		$ds = $db->execute_reader();
		$db = null;
		
		switch ($ds[0]["hari"]) {
			case 1:
				return "Minggu";
				break;
			case 2:
				return "Senin";
				break;
			case 3:
				return "Selasa";
				break;
			case 4:
				return "Rabu";
				break;
			case 5:
				return "Kamis";
				break;
			case 6:
				return "Jumat";
				break;
			case 7:
				return "Sabtu";
				break;
			default:
				
				break;
		}
	}
	
	function load_config($index) {
		$sql = "SELECT * FROM m_config WHERE m_config.index='" . $index . "'";
		$res = mysql_query($sql);
		$ds = mysql_fetch_assoc($res);
		
		return $ds["value"];
	}
	
	function get_pejabat_konfigurasi($kunci, $tanggal_dokumen) {
		global $app_conn;
		$sql = "
			SELECT
				a.*
			FROM
				vw_config_pejabat a
			WHERE
				a.kunci = '" . $kunci . "' AND a.tgl_berlaku <= '" . $tanggal_dokumen . "'
			ORDER BY
				a.tgl_berlaku DESC
			LIMIT
			 0,1
		";
		$res = mysqli_query($app_conn, $sql);
		$ds = mysqli_fetch_assoc($res);
		
		return $ds;
	}
	
	function get_ttd_dokumen($id_dokumen, $kode_ttd, $tgl_dokumen) {
		global $app_conn;
		$sql = "
			SELECT
				a.kode_ttd, b.nama_pegawai, c.pangkat, b.nik,
				CASE
					WHEN b.id_jenis_pegawai = 1 THEN 'NRP'
					WHEN b.id_jenis_pegawai IN (2, 3, 4) THEN 'NIP'
					WHEN b.id_jenis_pegawai IN (5) THEN 'NIK'
					ELSE ''
				END AS sebutan_nrp, b.jabatan, a.tanggal, REPLACE(a.judul_ttd, '||', '<br />') AS judul_ttd
			FROM
				itbl_apps_ttd_dokumen a
				LEFT JOIN t_pegawai b ON a.id_pegawai = b.id
				LEFT JOIN m_pangkat_pegawai c ON b.id_pangkat = c.id
			WHERE
				a.id_dokumen = '" . $id_dokumen . "' AND a.kode_ttd = '" . $kode_ttd . "' AND a.tanggal <= '" . $tgl_dokumen . "'
			ORDER BY
				a.tanggal DESC
			LIMIT
				0, 1
		";
		$res = mysqli_query($app_conn, $sql);
		$ds = mysqli_fetch_assoc($res);
		
		return $ds;
	}
	
	function get_variable_konfigurasi($kunci, $tanggal_dokumen) {
		global $app_conn;
		$sql = "
			SELECT
				a.*
			FROM
				vw_config_pejabat a
			WHERE
				a.kunci = '" . $kunci . "' AND a.tgl_berlaku <= '" . $tanggal_dokumen . "'
			ORDER BY
				a.tgl_berlaku DESC
			LIMIT
			 0,1
		";
		$res = mysqli_query($app_conn, $sql);
		$ds = mysqli_fetch_assoc($res);
		$ds["nilai"] = str_replace("||", "<br />", $ds["nilai"]);
		
		return $ds;
	}
?>