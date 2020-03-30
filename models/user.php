<?php
	
	class TUser {
		var $id;
		var $id_role;
		var $username;
		var $password;
		var $nama;
		
		function __construct() {
			$this->id = 0;
			$this->id_role = 0;
			$this->username = "";
			$this->password = "";
			$this->nama = "";
		}
		
		static function GoLogin($username, $password) {
			global $app_conn;
			
			$userObj = new TUser();
			$sql = "SELECT * FROM t_user WHERE MD5(username) = MD5('" . $username . "') AND password = MD5('" . $password . "')";
			$res = mysqli_query($app_conn, $sql) or die(mysqli_error($app_conn));
			while ($ds = mysqli_fetch_assoc($res)) {
				$userObj->id = $ds["id"];
				$userObj->id_role = $ds["id_role"];
				$userObj->username = $ds["username"];
				$userObj->password = $ds["password"];
				$userObj->nama = $ds["nama"];
			}
			
			return $userObj;
		}
		
		static function BelumLogin() {
			if(empty($_SESSION["APP_USER_ID"])) {
				header("location:login.php");
			}
		}
		
		static function SudahLogin() {
			if(isset($_SESSION["APP_USER_ID"])) {
				header("location:input_data_pengeluaran_dana.php");
			}
		}
	}
	
?>
