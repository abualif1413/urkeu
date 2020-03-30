<?php
	
	class PegawaiModel {
		var $id;
		var $id_jenis_pegawai;
		var $id_pangkat;
		var $nama_pegawai;
		var $nik;
		var $id_golongan;
		var $no_rekening;
		var $npwp;
		var $gapok;
		
		function __construct() {
			$this->id = 0;
			$this->id_jenis_pegawai = 0;
			$this->id_pangkat = 0;
			$this->nama_pegawai = "";
			$this->nik = "";
			$this->id_golongan = 0;
			$this->no_rekening = "";
			$this->npwp = "";
		}
		
		static function GetPegawaiCombo01() {
			global $app_conn;
			$sql_jenis = "
				SELECT
					b.id, b.jenis_pegawai
				FROM
					t_pegawai a
					INNER JOIN m_jenis_pegawai b ON a.id_jenis_pegawai = b.id
				GROUP BY
					a.id_jenis_pegawai
				ORDER BY
					b.jenis_pegawai ASC
			";
			$res_jenis = mysqli_query($app_conn, $sql_jenis);
			$pegawai_combo = array();
			while ($ds_jenis = mysqli_fetch_assoc($res_jenis)) {
				$jenis = array();
				$jenis["jenis_pegawai"] = $ds_jenis["jenis_pegawai"];
				$jenis["rincian"] = array();
				$sql = "SELECT * FROM t_pegawai WHERE id_jenis_pegawai = '" . $ds_jenis["id"] . "' ORDER BY nama_pegawai ASC";
				$res = mysqli_query($app_conn, $sql);
				while ($ds = mysqli_fetch_assoc($res)) {
					$temp = new PegawaiModel();
					$temp->id = $ds["id"];
					$temp->id_jenis_pegawai = $ds["id_jenis_pegawai"];
					$temp->id_pangkat = $ds["id_pangkat"];
					$temp->nama_pegawai = $ds["nama_pegawai"];
					$temp->nik = $ds["nik"];
					$temp->id_golongan = $ds["id_golongan"];
					$temp->no_rekening = $ds["no_rekening"];
					$temp->npwp = $ds["npwp"];
					$temp->gapok = $ds["gapok"];
					array_push($jenis["rincian"], $temp);
				}
				array_push($pegawai_combo, $jenis);
			}
			
			return $pegawai_combo;
		}
	}
	
?>