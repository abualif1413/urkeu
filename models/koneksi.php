<?php
	// Development
	
	$host		= "localhost";
	$user		= "root";
	$pass		= "";
	$db_name	= "urkeu";
	
	
	// Live server centos
	/*
	$host		= "localhost";
	$user		= "juliadi_db";
	$pass		= "juliadi";
	$db_name	= "juliadi";
	*/
	
	// Live server windows
	
	/*$host		= "localhost";
	$user		= "root";
	$pass		= "urkeu_db";
	$db_name	= "juliadi";*/
	
	
	$app_conn		= mysqli_connect($host, $user, $pass) or die(mysql_error());
	$app_select_db	= mysqli_select_db($app_conn, $db_name) or die(mysql_error());
	
	class DBConnection {
		var $namaDB;
		var $hostMySQL;
		var $userMySQL;
		var $passMySQL;
		var $perintahSQL;
		var $parameters;
		var $bind_param;
		
		var $insert_id;
		
		function __construct() {
			global $host, $user, $pass, $db_name;
			
			$this->hostMySQL = $host;
			$this->userMySQL = $user;
			$this->passMySQL = $pass;
			$this->namaDB = $db_name;
			
			$this->parameters = array();
		}
		
		function add_parameter($tipe_data, $value) {
			$temp = array("tipe_data"=>$tipe_data, "value"=>$value);
			
			array_push($this->parameters, $temp);
		}
		
		function execute_non_query() {
			$mysqli = new mysqli($this->hostMySQL, $this->userMySQL, $this->passMySQL, $this->namaDB);
	        $mysqli->set_charset("utf8");
	        $s_save = $mysqli->prepare($this->perintahSQL);
			
			/* =====================
			 * Generate Bind Param : menggenerate bind_param menggunakan fungsi eval : http://php.net/manual/en/function.eval.php
			 * ===================== */
			$arr_data_type = array();
			$arr_value = array();
			$arr_value_string_bind = array();
			$index = 0;
			foreach ($this->parameters as $param) {
				array_push($arr_data_type, $param["tipe_data"]);
				array_push($arr_value, $param["value"]);
				array_push($arr_value_string_bind, '$arr_value[' . $index . ']');
				$index++;
			}
			$this->bind_param = '
				$s_save->bind_param(
					"' . implode("", $arr_data_type) . '",
					' . implode(",", $arr_value_string_bind) . '
				);
			';
			/* ===================== End Of Generate Bind Param ===================== */
			
			if(count($this->parameters) > 0){
				eval($this->bind_param);
			}
	        $s_save->execute();
			$this->insert_id = $s_save->insert_id;
	        $s_save->free_result();
	        $s_save->Close();
	        $mysqli->close();
		}

		function execute_reader_cadangan() {
			$mysqli = new mysqli($this->hostMySQL, $this->userMySQL, $this->passMySQL, $this->namaDB);
	        $mysqli->set_charset("utf8");
	        $s_save = $mysqli->prepare($this->perintahSQL);
			
			/* =====================
			 * Generate Bind Param : menggenerate bind_param menggunakan fungsi eval : http://php.net/manual/en/function.eval.php
			 * ===================== */
			$arr_data_type = array();
			$arr_value = array();
			$arr_value_string_bind = array();
			$index = 0;
			foreach ($this->parameters as $param) {
				array_push($arr_data_type, $param["tipe_data"]);
				array_push($arr_value, $param["value"]);
				array_push($arr_value_string_bind, '$arr_value[' . $index . ']');
				$index++;
			}
			$this->bind_param = '
				$s_save->bind_param(
					"' . implode("", $arr_data_type) . '",
					' . implode(",", $arr_value_string_bind) . '
				);
			';
			/* ===================== End Of Generate Bind Param ===================== */
			
			if(count($this->parameters) > 0){
				eval($this->bind_param);
			}
	        $s_save->execute();
			
			/* ambil result set metadata */
			$result_metadata = $s_save->result_metadata();
			$fields = $result_metadata->fetch_fields();
			
			/* ambil sql result nya */
			$result_data = array();
			$s_save_bind_result_string = '$s_save->bind_result(';
			foreach ($fields as $index => $value) {
				if($index == 0) {
					$s_save_bind_result_string .= '$col_' . $value->name;
				} else {
					$s_save_bind_result_string .= ', ' . '$col_' . $value->name;
				}
			}
			$s_save_bind_result_string .= ');';
			eval($s_save_bind_result_string);
			while ($s_save->fetch()) {
				$data_temp = array();
				foreach ($fields as $field) {
					$fetchin_field = '$data_temp[$field->name] = $col_' . $field->name . ';';
					eval($fetchin_field);
				}
				array_push($result_data, $data_temp);
			}
			
			
	        $s_save->free_result();
	        $s_save->close();
			$result_metadata->close();
	        $mysqli->close();
			
			// Menambahkan kolom nomor
			for($i=0; $i<count($result_data); $i++) {
				$result_data[$i]["nomor_urut_data"] = ($i+1);
			}
			
			return $result_data;
		}
	
		function execute_reader() {
			global $app_conn;
			
			$sql_baru = "";
			$index_param = 0;
			for($i=0; $i<strlen($this->perintahSQL); $i++) {
				if(substr($this->perintahSQL, $i, 1) == "?") {
					$sql_baru .= "'" . $this->parameters[$index_param]["value"] . "'";
					$index_param++;
				} else {
					$sql_baru .= substr($this->perintahSQL, $i, 1);
				}
			}
			
			$res = mysqli_query($app_conn, $sql_baru) or die(mysqli_error($app_conn) . "<br /><pre>" . $this->perintahSQL . "</pre>");
			$rs = array();
			while($ds = mysqli_fetch_assoc($res)) {
				array_push($rs, $ds);
			}
			
			// Menambahkan kolom nomor
			for($i=0; $i<count($rs); $i++) {
				$rs[$i]["nomor_urut_data"] = ($i+1);
			}
			
			return $rs;
		}
	}
?>