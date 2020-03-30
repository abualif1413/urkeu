<?php
	/**
	 * 
	 */
	class SPBY {
		var $id_spp_spm;
		var $tanggal;
		var $nomor; 
		var $kepada;
		var $setuju_lunas;
		var $penerima; 
		var $telp_penerima;
		var $pangkat_penerima;
		var $nik_penerima;
		var $sebutan_nik_penerima;
		var $user_insert;
	
		function __construct() {
			$this->id_spp_spm = 0;
			$this->tanggal = "";
			$this->nomor = ""; 
			$this->kepada = "";
			$this->setuju_lunas = "";
			$this->penerima = ""; 
			$this->user_insert = 0;
		}
		
		function Insert() {
			$db_delete = new DBConnection();
			$db_delete->perintahSQL = "DELETE FROM itbl_apps_spby WHERE id_spp_spm = ?";
			$db_delete->add_parameter("i", $this->id_spp_spm);
			$db_delete->execute_non_query();
			
			$db_insert = new DBConnection();
			$db_insert->perintahSQL = "
				INSERT INTO itbl_apps_spby (
					id_spp_spm, tanggal, nomor, 
					kepada, setuju_lunas, penerima, 
					telp_penerima, pangkat_penerima, nik_penerima, sebutan_nik_penerima,
					user_insert
				) VALUES(
					?, ?, ?, 
					?, ?, ?, 
					?, ?, ?, ?,
					?
				)
			";
			$db_insert->add_parameter("i", $this->id_spp_spm);
			$db_insert->add_parameter("s", $this->tanggal);
			$db_insert->add_parameter("s", $this->nomor);
			$db_insert->add_parameter("s", $this->kepada);
			$db_insert->add_parameter("s", $this->setuju_lunas);
			$db_insert->add_parameter("s", $this->penerima);
			$db_insert->add_parameter("s", $this->telp_penerima);
			$db_insert->add_parameter("s", $this->pangkat_penerima);
			$db_insert->add_parameter("s", $this->nik_penerima);
			$db_insert->add_parameter("s", $this->sebutan_nik_penerima);
			$db_insert->add_parameter("i", $this->user_insert);
			$db_insert->execute_non_query();
		}
	}
	
?>