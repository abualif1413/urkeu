<?php
	/**
	 * 
	 */
	class PaguService {
		var $daftar_nomor; 
		
		function __construct() {
			$this->daftar_nomor = array();
		}
		
		function GenerateDaftarNomor($id_coa) {
			$this->RecurseCoa($id_coa);
			
			// Menghilangkan yang kosong-kosong dari daftar
			$daftar_baru = array();
			foreach ($this->daftar_nomor as $daftar) {
				if($daftar["nomor"] != "") {
					array_push($daftar_baru, $daftar);
				}
			}
			
			// Balikkan nomor nya
			$this->daftar_nomor = array_reverse($daftar_baru);
		}
		
		function RecurseCoa($id_coa) {
			$db = new DBConnection();
			$db->perintahSQL = "SELECT * FROM itbl_main_coa WHERE id = ?";
			$db->add_parameter("i", $id_coa);
			$ds = $db->execute_reader();
			$db = null;
			
			$parent_id = $ds[0]["parent_id"];
			$isi_daftar = array(
				"id" => $ds[0]["id"],
				"nomor" => $ds[0]["nomor_umum"]
			);
			array_push($this->daftar_nomor, $isi_daftar);
			
			if($parent_id != 0) {
				$this->RecurseCoa($parent_id);
			}
		}
		
		static function SusunanPagu($id_spp_spm) {
			$coa_pagu = array();
			$isi_table_coa_spp_spm = "";
			$db = new DBConnection();
			$db->perintahSQL = "
				SELECT
					a.id, a.id_coa_pagu, a.nilai
				FROM
					itbl_apps_coa_spp_spm a
				WHERE
					a.id_spp_spm = ?
				ORDER BY
					a.id ASC
			";
			$db->add_parameter("i", $id_spp_spm);
			$ds = $db->execute_reader();
			$no = 0;
			$jumlah_pagu_ini = 0;
			foreach ($ds as $ds) {
				$objPagu = new PaguService();
				$objPagu->GenerateDaftarNomor($ds["id_coa_pagu"]);
				//echo print_r($objPagu->daftar_nomor);
				$nomor_pagu_ini = $objPagu->daftar_nomor[1]["nomor"] . " . " . $objPagu->daftar_nomor[3]["nomor"] . " . " . $objPagu->daftar_nomor[count($objPagu->daftar_nomor) - 1]["nomor"];
				$nilai_pagu_ini = $ds["nilai"];
				$id_nomor_pagu_ini = $objPagu->daftar_nomor[count($objPagu->daftar_nomor) - 1]["id"];
				$kode_fungsi_pagu_ini = $objPagu->daftar_nomor[0]["nomor"];
				$kode_kegiatan_pagu_ini = $objPagu->daftar_nomor[1]["nomor"];
				
				$ketemu = 0;
				foreach ($coa_pagu as &$cp) {
					if($cp["nomor"] == $nomor_pagu_ini) {
						$ketemu = 1;
						$cp["nilai"] += $nilai_pagu_ini;
					}
				}
				if($ketemu == 0) {
					array_push($coa_pagu, array(
						"nomor" => $nomor_pagu_ini,
						"nilai" => $nilai_pagu_ini,
						"id_nomor" => $id_nomor_pagu_ini,
						"kode_fungsi" => $kode_fungsi_pagu_ini,
						"kode_kegiatan" => $kode_kegiatan_pagu_ini
					));
				}
			}
			
			return $coa_pagu;
		}

		static function GetTotalSDLalu($id_spp_spm, $id_coa_pagu) {
			$db = new DBConnection();
			$db->perintahSQL = "
				SELECT
					a.id, b.id_coa_pagu, c.acc_number, COALESCE(SUM(b.nilai), 0) AS total
				FROM
					itbl_apps_spp_spm a
					LEFT JOIN itbl_apps_coa_spp_spm b ON a.id = b.id_spp_spm
					LEFT JOIN itbl_main_coa c ON b.id_coa_pagu = c.id
				WHERE
					YEAR(a.tanggal) = (SELECT YEAR(tanggal) FROM itbl_apps_spp_spm WHERE id = ?)
					AND a.id < ?
					AND c.acc_number LIKE CONCAT((SELECT acc_number FROM vw_coa WHERE id = ?),'%')
			";
			$db->add_parameter("i", $id_spp_spm);
			$db->add_parameter("i", $id_spp_spm);
			$db->add_parameter("i", $id_coa_pagu);
			$ds = $db->execute_reader();
			$db = null;
			
			return $ds[0]["total"];
		}
		
		static function GetTotalAnggaran($id_coa_pagu, $tahun) {
			$db = new DBConnection();
			$db->perintahSQL = "
				SELECT
					a.id, a.acc_number, a.acc_name, COALESCE(b.jumlah, 0) AS jumlah
				FROM
					itbl_main_coa a
					LEFT JOIN itbl_apps_anggaran b ON (a.id = b.id_coa AND b.tahun = ?)
				WHERE
					a.acc_number LIKE CONCAT(
						(SELECT acc_number FROM itbl_main_coa WHERE id = ?)
					,'%')
			";
			$db->add_parameter("i", $tahun);
			$db->add_parameter("i", $id_coa_pagu);
			$ds = $db->execute_reader();
			$db = null;
			$jumlah = 0;
			foreach ($ds as $set) {
				$jumlah += $set["jumlah"];
			}
			
			return $jumlah;
		}
	}
	
?>