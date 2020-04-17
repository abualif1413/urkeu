<?php
	class SPPSPM {
		var $id;
		var $id_belanja;
		var $id_pecah_bayar;
		var $jenis_belanja; 
		var $nomor;
		var $nomor_after_sp2d;
		var $tanggal; 
		var $tanggal_after_sp2d;
		var $sifat_pembayaran;
		var $jenis_pembayaran;
		var $user_insert;
		
		var $insert_id;
		
		function __construct() {
			$this->id = 0;
			$this->id_belanja = 0;
			$this->id_pecah_bayar = 0;
			$this->jenis_belanja = ""; 
			$this->nomor = "";
			$this->nomor_after_sp2d = "";
			$this->tanggal = ""; 
			$this->tanggal_after_sp2d = "";
			$this->sifat_pembayaran = "";
			$this->jenis_pembayaran = "";
			$this->user_insert = "";
		}
		
		function Record($id) {
			$db = new DBConnection();
			$db->perintahSQL = "
				SELECT
					id, id_belanja, id_pecah_bayar, jenis_belanja, 
					nomor, tanggal,
					nomor_after_sp2d, tanggal_after_sp2d,
					sifat_pembayaran, jenis_pembayaran, user_insert
				FROM
					itbl_apps_spp_spm
				WHERE
					id=?
			";
			$db->add_parameter("i", $id);
			$ds = $db->execute_reader();
			foreach ($ds as $ds) {
				$this->id = $ds["id"];
				$this->id_belanja = $ds["id_belanja"];
				$this->id_pecah_bayar = $ds["id_pecah_bayar"];
				$this->jenis_belanja = $ds["jenis_belanja"]; 
				$this->nomor = $ds["nomor"];
				$this->nomor_after_sp2d = $ds["nomor_after_sp2d"];
				$this->tanggal = $ds["tanggal"]; 
				$this->tanggal_after_sp2d = $ds["tanggal_after_sp2d"];
				$this->sifat_pembayaran = $ds["sifat_pembayaran"];
				$this->jenis_pembayaran = $ds["jenis_pembayaran"];
				$this->user_insert = $ds["user_insert"];
			}
			$db = null;
		}
		
		function Insert() {
			$db = new DBConnection();
			$db->perintahSQL = "
				INSERT INTO itbl_apps_spp_spm (
					id_belanja, id_pecah_bayar, jenis_belanja, 
					nomor, tanggal, 
					nomor_after_sp2d, tanggal_after_sp2d,
					sifat_pembayaran, jenis_pembayaran, user_insert
				) VALUES(
					?, ?, ?, 
					?, ?,
					?, ?,  
					?, ?, ?
				)
			";
			$db->add_parameter("i", $this->id_belanja);
			$db->add_parameter("i", $this->id_pecah_bayar);
			$db->add_parameter("s", $this->jenis_belanja);
			$db->add_parameter("s", $this->nomor);
			$db->add_parameter("s", $this->tanggal);
			$db->add_parameter("s", $this->nomor_after_sp2d);
			$db->add_parameter("s", $this->tanggal_after_sp2d);
			$db->add_parameter("s", $this->sifat_pembayaran);
			$db->add_parameter("s", $this->jenis_pembayaran);
			$db->add_parameter("s", $this->user_insert);
			$db->execute_non_query();
			$this->insert_id = $db->insert_id;
			$db = null;
		}
		
		function Update() {
			$db = new DBConnection();
			$db->perintahSQL = "
				UPDATE itbl_apps_spp_spm SET
					id_belanja=?, id_pecah_bayar=?, jenis_belanja=?, 
					nomor=?, tanggal=?,
					nomor_after_sp2d=?, tanggal_after_sp2d=?,  
					sifat_pembayaran=?, jenis_pembayaran=?, user_insert=?
				WHERE
					id = ?
			";
			$db->add_parameter("i", $this->id_belanja);
			$db->add_parameter("i", $this->id_pecah_bayar);
			$db->add_parameter("s", $this->jenis_belanja);
			$db->add_parameter("s", $this->nomor);
			$db->add_parameter("s", $this->tanggal);
			$db->add_parameter("s", $this->nomor_after_sp2d);
			$db->add_parameter("s", $this->tanggal_after_sp2d);
			$db->add_parameter("s", $this->sifat_pembayaran);
			$db->add_parameter("s", $this->jenis_pembayaran);
			$db->add_parameter("s", $this->user_insert);
			$db->add_parameter("i", $this->id);
			$db->execute_non_query();
			$db = null;
		}
		
		function Delete() {
			$db = new DBConnection();
			$db->perintahSQL = "DELETE FROM itbl_apps_spp_spm WHERE id = ?";
			$db->add_parameter("i", $this->id);
			$db->execute_non_query();
			$db = null;
		}
		
		static function GetList01($dari, $sampai, $uraian) {
			global $app_conn;
			
			$whr = "";			
			//$whr .= " a.tanggal_after_sp2d BETWEEN '" . $dari . " 00:00:00' AND '" . $sampai . " 23:59:59' AND (a.keterangan LIKE '%" . $uraian . "%' OR a.nomor_na LIKE '%" . $uraian . "%') ";
			$whr .= " a.tanggal BETWEEN '" . $dari . " 00:00:00' AND '" . $sampai . " 23:59:59' AND (a.keterangan LIKE '%" . $uraian . "%' OR a.nomor_na LIKE '%" . $uraian . "%') ";
			
			$sql = "
				SELECT
					a.id, a.nomor, a.tanggal,
					a.jenis_belanja, a.keterangan, a.total, a.ppn, a.pph, a.nomor_na AS nomor_belanja,
					COALESCE(c.id, 0) AS id_pu_detail,
					d.nomor AS nomor_spby, d.tanggal AS tgl_spby
				FROM
					vw_daftar_spp_spm a
					LEFT JOIN itbl_apps_pu_detail c ON a.id = c.id_spp_spm
					LEFT JOIN itbl_apps_spby d ON a.id = d.id_spp_spm
				WHERE
					" . $whr . "
				ORDER BY
					a.id ASC
			";
			
			$res = mysqli_query($app_conn, $sql) or die(mysqli_error($app_conn));
			$kembali = array();
			while ($ds = mysqli_fetch_assoc($res)) {
				array_push($kembali, $ds);
			}
			
			return $kembali;
		}
		
		static function GetList02($id) {
			global $app_conn;
			
			$sql = "
				SELECT
					a.id, a.nomor, a.tanggal,
					a.jenis_belanja, a.keterangan, a.total, a.ppn, a.pph, a.nomor_na AS nomor_belanja,
					COALESCE(c.id, 0) AS id_pu_detail,
					d.nomor AS nomor_spby, d.tanggal AS tgl_spby
				FROM
					vw_daftar_spp_spm a
					LEFT JOIN itbl_apps_pu_detail c ON a.id = c.id_spp_spm
					LEFT JOIN itbl_apps_spby d ON a.id = d.id_spp_spm
				WHERE
					a.id='" . $id . "'
				ORDER BY
					a.id ASC
			";
			
			$res = mysqli_query($app_conn, $sql) or die(mysqli_error($app_conn));
			$kembali = array();
			while ($ds = mysqli_fetch_assoc($res)) {
				array_push($kembali, $ds);
			}
			
			return $kembali;
		}
	}
	
?>