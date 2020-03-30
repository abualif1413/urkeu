<?php

	class DetailPermohonanDanaModel {
		var $id;
		var $id_permohonan_dana;
		var $id_jenis_pajak;
		var $penerima;
		var $qty;
		var $satuan;
		var $harga_satuan;
		var $jumlah;
		var $uraian;
		var $no_faktur;
		var $tgl_faktur;
		var $ppn;
		var $pph;
		var $user_insert;
		
		function __construct() {
			$this->id = 0;
			$this->id_permohonan_dana = 0;
			$this->id_jenis_pajak = 0;
			$this->penerima = "";
			$this->qty = 0;
			$this->satuan = "";
			$this->harga_satuan = 0;
			$this->jumlah = 0;
			$this->uraian = "";
			$this->no_faktur = "";
			$this->tgl_faktur = "";
			$this->ppn = 0;
			$this->pph = 0;
			$this->user_insert = "";
		}
		
		function Record($id) {
			global $app_conn;
			$sql = "SELECT * FROM t_detail_permohonan_dana WHERE id='" . $id . "'";
			$res = mysqli_query($app_conn, $sql);
			while ($ds = mysqli_fetch_assoc($res)) {
				$this->id = $ds["id"];
				$this->id_permohonan_dana = $ds["id_permohonan_dana"];
				$this->id_jenis_pajak = $ds["id_jenis_pajak"];
				$this->penerima = $ds["penerima"];
				$this->qty = $ds["qty"];
				$this->satuan = $ds["satuan"];
				$this->harga_satuan = $ds["harga_satuan"];
				$this->jumlah = $ds["jumlah"];
				$this->uraian = $ds["uraian"];
				$this->no_faktur = $ds["no_faktur"];
				$this->tgl_faktur = $ds["tgl_faktur"];
				$this->ppn = $ds["ppn"];
				$this->pph = $ds["pph"];
				$this->user_insert = $ds["user_insert"];
			}
		}
		
		function Insert() {
			$db = new DBConnection();
			$db->perintahSQL = "
				INSERT INTO t_detail_permohonan_dana(
					id_permohonan_dana,
					id_jenis_pajak,
					penerima,
					qty, satuan, harga_satuan,
					jumlah, uraian,
					no_faktur, tgl_faktur,
					ppn, pph,
					user_insert
				) VALUES(
					?,
					?,
					?,
					?, ?, ?,
					?, ?,
					?, ?,
					?, ?,
					?
				)
			";
			$db->add_parameter("i", $this->id_permohonan_dana);
			$db->add_parameter("i", $this->id_jenis_pajak);
			$db->add_parameter("s", $this->penerima);
			
			$db->add_parameter("d", $this->qty);
			$db->add_parameter("s", $this->satuan);
			$db->add_parameter("d", $this->harga_satuan);
			
			$db->add_parameter("d", $this->jumlah);
			$db->add_parameter("s", $this->uraian);
			
			$db->add_parameter("s", $this->no_faktur);
			$db->add_parameter("s", $this->tgl_faktur);
			
			$db->add_parameter("s", $this->ppn);
			$db->add_parameter("s", $this->pph);
			
			$db->add_parameter("s", $_SESSION["APP_USER_ID"]);
			$db->execute_non_query();
		}
		
		function Update($id) {
			$db = new DBConnection();
			$db->perintahSQL = "
				UPDATE t_detail_permohonan_dana SET
					id_permohonan_dana=?,
					id_jenis_pajak=?,
					penerima=?,
					qty=?, satuan=?, harga_satuan=?,
					jumlah=?, uraian=?,
					no_faktur=?, tgl_faktur=?,
					ppn=?, pph=?
				WHERE
					id=?
			";
			$db->add_parameter("i", $this->id_permohonan_dana);
			$db->add_parameter("i", $this->id_jenis_pajak);
			$db->add_parameter("s", $this->penerima);
			
			$db->add_parameter("d", $this->qty);
			$db->add_parameter("s", $this->satuan);
			$db->add_parameter("d", $this->harga_satuan);
			
			$db->add_parameter("d", $this->jumlah);
			$db->add_parameter("s", $this->uraian);
			
			$db->add_parameter("s", $this->no_faktur);
			$db->add_parameter("s", $this->tgl_faktur);
			
			$db->add_parameter("s", $this->ppn);
			$db->add_parameter("s", $this->pph);
			
			$db->add_parameter("i", $id);
			$db->execute_non_query();
		}
		
		function Delete($id) {
			$db = new DBConnection();
			$db->perintahSQL = "DELETE FROM t_detail_permohonan_dana WHERE id=?";
			$db->add_parameter("i", $id);
			$db->execute_non_query();
		}
	}
	
	class DetailPermohonanDanaBusinessLogic extends DetailPermohonanDanaModel {
		var $jenis_pajak;
		var $ppn;
		var $pph;
		
		function __construct() {
			parent::__construct();
			$this->jenis_pajak = "";
			$this->ppn = 0;
			$this->pph = 0;
		}
		
		static function GetList01($user_insert) {
			global $app_conn;
			$sql = "
				SELECT
					datanya.id, datanya.qty, datanya.satuan, datanya.harga_satuan, datanya.id_jenis_pajak, datanya.penerima,
					(datanya.qty * datanya.harga_satuan) AS jumlah, datanya.uraian, datanya.user_insert,
					datanya.jenis_pajak, SUM(datanya.besar_ppn) AS ppn, SUM(datanya.besar_pph) AS pph
				FROM
					(
						SELECT
							a.*, b.keterangan AS jenis_pajak,
							COALESCE(ppn.besar, 0) AS persen_ppn, COALESCE(pph.besar, 0) AS persen_pph,
							@ppn := CASE
								WHEN (a.qty * a.harga_satuan) >= 2000000 THEN CEIL((a.qty * a.harga_satuan) / 11)
								ELSE 0
							END AS besar_ppn, 
							CASE
								WHEN (a.qty * a.harga_satuan) >= 1000000 THEN CEIL((((a.qty * a.harga_satuan) - @ppn) * 1.5 / 100))
								ELSE 0
							END AS besar_pph
						FROM
							t_detail_permohonan_dana a	
							LEFT JOIN m_jenis_pajak b ON a.id_jenis_pajak = b.id
							LEFT JOIN m_memiliki_pajak c ON b.id = c.id_jenis_pajak
							LEFT JOIN m_pajak ppn ON c.id_pajak = ppn.id AND ppn.tipe = 'PPN'
							LEFT JOIN m_pajak pph ON c.id_pajak = pph.id AND pph.tipe = 'PPh'
							CROSS JOIN (SELECT @ppn := 0) ppn
						WHERE
							(a.id_permohonan_dana IS NULL OR a.id_permohonan_dana = 0) AND a.user_insert = '" . $user_insert . "'
					) datanya
				GROUP BY
					datanya.id
			";
			$res = mysqli_query($app_conn, $sql);
			$data = array();
			while ($ds = mysqli_fetch_assoc($res)) {
				$temp = new DetailPermohonanDanaBusinessLogic();
				$temp->id = $ds["id"];
				$temp->id_jenis_pajak = $ds["id_jenis_pajak"];
				$temp->penerima = $ds["penerima"];
				$temp->qty = $ds["qty"];
				$temp->satuan = $ds["satuan"];
				$temp->harga_satuan = $ds["harga_satuan"];
				$temp->jumlah = $ds["jumlah"];
				$temp->uraian = $ds["uraian"];
				$temp->user_insert = $ds["user_insert"];
				$temp->jenis_pajak = $ds["jenis_pajak"];
				$temp->ppn = $ds["ppn"];
				$temp->pph = $ds["pph"];
				array_push($data, $temp);
			}
			
			return $data;
		}

		static function GetList02($id_permohonan_dana) {
			global $app_conn;
			$sql = "
				SELECT
					datanya.id, datanya.id_jenis_pajak, datanya.penerima,
					datanya.qty, datanya.satuan, datanya.harga_satuan,
					(datanya.qty * datanya.harga_satuan) AS jumlah, datanya.uraian, datanya.tgl_faktur, datanya.no_faktur, datanya.user_insert,
					datanya.jenis_pajak, SUM(datanya.besar_ppn) AS ppn, SUM(datanya.besar_pph) AS pph
				FROM
					(
						SELECT
							a.*, b.keterangan AS jenis_pajak,
							COALESCE(ppn.besar, 0) AS persen_ppn, COALESCE(pph.besar, 0) AS persen_pph,
							@ppn := CASE
								WHEN a.ppn = 1 THEN CEIL((a.qty * a.harga_satuan) / 10)
								ELSE 0
							END AS besar_ppn, 
							CASE
								WHEN a.pph = 1 THEN CEIL((((a.qty * a.harga_satuan)) * 1.5 / 100))
								ELSE 0
							END AS besar_pph
						FROM
							t_detail_permohonan_dana a	
							LEFT JOIN m_jenis_pajak b ON a.id_jenis_pajak = b.id
							LEFT JOIN m_memiliki_pajak c ON b.id = c.id_jenis_pajak
							LEFT JOIN m_pajak ppn ON c.id_pajak = ppn.id AND ppn.tipe = 'PPN'
							LEFT JOIN m_pajak pph ON c.id_pajak = pph.id AND pph.tipe = 'PPh'
							CROSS JOIN (SELECT @ppn := 0) ppn
						WHERE
							a.id_permohonan_dana = '" . $id_permohonan_dana . "'
					) datanya
				GROUP BY
					datanya.id
			";
			$res = mysqli_query($app_conn, $sql);
			$data = array();
			while ($ds = mysqli_fetch_assoc($res)) {
				$temp = new DetailPermohonanDanaBusinessLogic();
				$temp->id = $ds["id"];
				$temp->id_jenis_pajak = $ds["id_jenis_pajak"];
				$temp->penerima = $ds["penerima"];
				$temp->qty = $ds["qty"];
				$temp->satuan = $ds["satuan"];
				$temp->harga_satuan = $ds["harga_satuan"];
				$temp->jumlah = $ds["jumlah"];
				$temp->uraian = $ds["uraian"];
				$temp->no_faktur = $ds["no_faktur"];
				$temp->tgl_faktur = $ds["tgl_faktur"];
				$temp->user_insert = $ds["user_insert"];
				$temp->jenis_pajak = $ds["jenis_pajak"];
				$temp->ppn = $ds["ppn"];
				$temp->pph = $ds["pph"];
				array_push($data, $temp);
			}
			
			return $data;
		}
	}
	
	class PermohonanDanaModel {
		var $id;
		var $tanggal;
		var $nomor;
		var $na_nomor;
		var $na_bulan;
		var $na_tahun;
		var $na_divisi;
		var $keterangan;
		var $id_pegawai_ybs;
		var $diketahui_oleh;
		var $kuasa_pengguna_anggaran;
		var $no_sptjb;
		var $jenis_belanja;
		var $menyatakan;
		var $user_insert;
		
		var $id_yg_diinsert;
		
		function __construct() {
			$this->id = 0;
			$this->tanggal = "";
			$this->nomor = "";
			$this->na_nomor = 0;
			$this->na_bulan = "";
			$this->na_tahun = 0;
			$this->na_divisi = "";
			$this->keterangan = "";
			$this->id_pegawai_ybs = 0;
			$this->diketahui_oleh = 0;
			$this->kuasa_pengguna_anggaran = 0;
			$this->no_sptjb = "";
			$this->jenis_belanja = "";
			$this->menyatakan = "";
			$this->user_insert = "";
		}
		
		function Record($id) {
			global $app_conn;
			$sql = "SELECT * FROM t_permohonan_dana WHERE id='" . $id . "'";
			$res = mysqli_query($app_conn, $sql);
			while ($ds = mysqli_fetch_assoc($res)) {
				$this->id = $ds["id"];
				$this->tanggal = $ds["tanggal"];
				$this->nomor = $ds["nomor"];
				$this->na_nomor = $ds["na_nomor"];
				$this->na_bulan = $ds["na_bulan"];
				$this->na_tahun = $ds["na_tahun"];
				$this->na_divisi = $ds["na_divisi"];
				$this->keterangan = $ds["keterangan"];
				$this->id_pegawai_ybs = $ds["id_pegawai_ybs"];
				$this->diketahui_oleh = $ds["diketahui_oleh"];
				$this->kuasa_pengguna_anggaran = $ds["kuasa_pengguna_anggaran"];
				$this->no_sptjb = $ds["no_sptjb"];
				$this->jenis_belanja = $ds["jenis_belanja"];
				$this->menyatakan = $ds["menyatakan"];
				$this->user_insert = $ds["user_insert"];
			}
		}
		
		function Insert() {
			global $app_conn;
			$db = new DBConnection();
			$db->perintahSQL = "
				INSERT INTO t_permohonan_dana(
					tanggal, nomor,
					na_nomor, na_bulan, na_tahun, na_divisi,
					keterangan, id_pegawai_ybs,
					diketahui_oleh, kuasa_pengguna_anggaran,
					no_sptjb, jenis_belanja, menyatakan, user_insert
				) VALUES(
					?, ?,
					?, ?, ?, ?,
					?, ?,
					?, ?,
					?, ?, ?, ?
				)
			";
			$db->add_parameter("s", $this->tanggal);
			$db->add_parameter("s", $this->nomor);
			
			$db->add_parameter("i", $this->na_nomor);
			$db->add_parameter("s", $this->na_bulan);
			$db->add_parameter("i", $this->na_tahun);
			$db->add_parameter("s", $this->na_divisi);
			
			$db->add_parameter("s", $this->keterangan);
			$db->add_parameter("i", $this->id_pegawai_ybs);
			
			$db->add_parameter("i", $this->diketahui_oleh);
			$db->add_parameter("i", $this->kuasa_pengguna_anggaran);
			
			$db->add_parameter("s", $this->no_sptjb);
			$db->add_parameter("s", $this->jenis_belanja);
			$db->add_parameter("s", $this->menyatakan);
			$db->add_parameter("s", $_SESSION["APP_USER_ID"]);
			$db->execute_non_query();
			
			$id_permohonan_dana = 0;
			$sql_data_terakhir = "SELECT * FROM t_permohonan_dana WHERE user_insert='" . $_SESSION["APP_USER_ID"] . "' ORDER BY id DESC LIMIT 0, 1";
			$res_data_terakhir = mysqli_query($app_conn, $sql_data_terakhir);
			$ds_data_terakhir = mysqli_fetch_assoc($res_data_terakhir);
			$id_permohonan_dana = $ds_data_terakhir["id"];
			
			$this->id_yg_diinsert = $id_permohonan_dana;
		}
		
		function Update($id) {
			$db = new DBConnection();
			$db->perintahSQL = "
				UPDATE t_permohonan_dana SET
					tanggal=?, nomor=?,
					na_nomor=?, na_bulan=?, na_tahun=?, na_divisi=?,
					keterangan=?, id_pegawai_ybs=?,
					diketahui_oleh=?, kuasa_pengguna_anggaran=?,
					no_sptjb=?, jenis_belanja=?, menyatakan=?, user_insert=?
				WHERE
					id=?
			";
			$db->add_parameter("s", $this->tanggal);
			$db->add_parameter("s", $this->nomor);
			
			$db->add_parameter("i", $this->na_nomor);
			$db->add_parameter("s", $this->na_bulan);
			$db->add_parameter("i", $this->na_tahun);
			$db->add_parameter("s", $this->na_divisi);
			
			$db->add_parameter("s", $this->keterangan);
			$db->add_parameter("i", $this->id_pegawai_ybs);
			
			$db->add_parameter("i", $this->diketahui_oleh);
			$db->add_parameter("i", $this->kuasa_pengguna_anggaran);
			
			$db->add_parameter("s", $this->no_sptjb);
			$db->add_parameter("s", $this->jenis_belanja);
			$db->add_parameter("s", $this->menyatakan);
			$db->add_parameter("s", $_SESSION["APP_USER_ID"]);
			
			$db->add_parameter("i", $id);
			$db->execute_non_query();
		}
		
		function Delete($id) {
			$db = new DBConnection();
			$db->perintahSQL = "DELETE FROM t_permohonan_dana WHERE id=?";
			$db->add_parameter("i", $id);
			$db->execute_non_query();
		}
		
		function KaitkanDetail() {
			global $app_conn;
			$id_permohonan_dana = 0;
			$sql_data_terakhir = "SELECT * FROM t_permohonan_dana WHERE user_insert='" . $_SESSION["APP_USER_ID"] . "' ORDER BY id DESC LIMIT 0, 1";
			$res_data_terakhir = mysqli_query($app_conn, $sql_data_terakhir);
			$ds_data_terakhir = mysqli_fetch_assoc($res_data_terakhir);
			$id_permohonan_dana = $ds_data_terakhir["id"];
			
			$db = new DBConnection();
			$db->perintahSQL = "
				UPDATE t_detail_permohonan_dana SET
					id_permohonan_dana=?
				WHERE
					(id_permohonan_dana IS NULL OR id_permohonan_dana = 0) AND user_insert=?
			";
			$db->add_parameter("i", $id_permohonan_dana);
			$db->add_parameter("s", $_SESSION["APP_USER_ID"]);
			$db->execute_non_query();
		}
		
		static function GetList01() {
			global $app_conn;
			$sql = "
				SELECT
					a.*, b.nama_pegawai
				FROM
					t_permohonan_dana a
					LEFT JOIN t_pegawai b ON a.id_pegawai_ybs = b.id
				WHERE
					1=1
				ORDER BY
					a.tanggal ASC, a.nomor ASC
			";
			$res = mysqli_query($app_conn, $sql);
			$data = array();
			while ($ds = mysqli_fetch_assoc($res)) {
				array_push($data, $ds);
			}
			
			return $data;
		}
		
		static function GetList02($tgl_dari, $tgl_sampai, $uraian, $pd) {
			global $app_conn;
			$whr = "";
			
			if($pd != "") {
				$whr .= " AND a.jenis_belanja = 'belanja perjalanan dinas' ";
			} else {
				$whr .= " AND a.jenis_belanja <> 'belanja perjalanan dinas' ";
			}
			
			// Mencari role nya
			$sql_user = "SELECT * FROM t_user WHERE id = '" . $_SESSION["APP_USER_ID"] . "'";
			$res_user = mysqli_query($app_conn, $sql_user);
			$ds_user = mysqli_fetch_assoc($res_user);
			if($ds_user["id_role"] == 2) {
				$whr .= " AND a.user_insert='" . $_SESSION["APP_USER_ID"] . "' ";
			}
			
			$sql = "
				SELECT
					a.*, b.nama_pegawai
				FROM
					t_permohonan_dana a
					LEFT JOIN t_pegawai b ON a.id_pegawai_ybs = b.id
				WHERE
					1=1 AND a.tanggal BETWEEN '" . $tgl_dari . "' AND '" . $tgl_sampai . "' AND
					(a.keterangan LIKE '%" . $uraian . "%' OR REPLACE(b.nama_pegawai,' ','') LIKE '%" . $uraian . "%')
					" . $whr . "
				ORDER BY
					a.tanggal ASC, a.nomor ASC
			";
			$res = mysqli_query($app_conn, $sql);
			$data = array();
			while ($ds = mysqli_fetch_assoc($res)) {
				array_push($data, $ds);
			}
			
			return $data;
		}
		
		static function GetList02_baru($tgl_dari, $tgl_sampai, $uraian, $pd, $jenis) {
			global $app_conn;
			$whr = "";
			
			if($pd != "") {
				$whr .= " AND a.jenis_belanja = 'belanja perjalanan dinas' ";
			} else {
				foreach ($jenis as &$jns) {
					$jns = "'" . $jns . "'";
				}
				
				$whr .= " AND a.jenis_belanja IN (" . implode(", ", $jenis) . ") ";
			}
			
			// Mencari role nya
			$sql_user = "SELECT * FROM t_user WHERE id = '" . $_SESSION["APP_USER_ID"] . "'";
			$res_user = mysqli_query($app_conn, $sql_user);
			$ds_user = mysqli_fetch_assoc($res_user);
			if($ds_user["id_role"] == 2) {
				$whr .= " AND a.user_insert='" . $_SESSION["APP_USER_ID"] . "' ";
			}
			
			$sql = "
				SELECT
					a.*, b.nama_pegawai
				FROM
					t_permohonan_dana a
					LEFT JOIN t_pegawai b ON a.id_pegawai_ybs = b.id
				WHERE
					1=1 AND a.tanggal BETWEEN '" . $tgl_dari . "' AND '" . $tgl_sampai . "' AND
					(a.keterangan LIKE '%" . $uraian . "%' OR REPLACE(b.nama_pegawai,' ','') LIKE '%" . $uraian . "%')
					" . $whr . "
				ORDER BY
					a.tanggal ASC, a.nomor ASC
			";
			$res = mysqli_query($app_conn, $sql);
			$data = array();
			while ($ds = mysqli_fetch_assoc($res)) {
				array_push($data, $ds);
			}
			
			return $data;
		}
		
		static function GetFullRecord01($id) {
			global $app_conn;
			$sql = "
				SELECT
					CONCAT(1,'-',a.id) AS barcode,
					a.id,
					a.tanggal,
					a.nomor,
					LPAD(a.na_nomor, 4, '0') AS na_nomor,
					a.na_bulan,
					a.na_tahun,
					a.na_divisi,
					a.keterangan,
					a.id_pegawai_ybs,
					a.diketahui_oleh,
					a.kuasa_pengguna_anggaran,
					a.no_sptjb,
					a.jenis_belanja,
					a.menyatakan,
					a.user_insert,
					b.nama_pegawai, c.golongan, d.pangkat, b.jabatan, b.nik,
					b.id_jenis_pegawai, b1.id_jenis_pegawai AS id_jenis_pegawai_diketahui, b2.id_jenis_pegawai AS id_jenis_pegawai_kuitansi,
					
					b1.nik AS nik_diketahui,
					b1.nama_pegawai AS nama_pegawai_diketahui,
					c1.golongan AS golongan_diketahui,
					d1.pangkat AS pangkat_diketahui,
					b1.jabatan AS jabatan_diketahui,
					
					b2.nik AS nik_kuitansi,
					b2.nama_pegawai AS nama_pegawai_kuitansi,
					c2.golongan AS golongan_kuitansi,
					d2.pangkat AS pangkat_kuitansi,
					b2.jabatan AS jabatan_kuitansi,
					func_total_belanja_barang(a.id) AS total_netto,
					func_total_ppn_belanja_barang(a.id) AS total_ppn,
					func_total_pph_belanja_barang(a.id) AS total_pph
				FROM
					t_permohonan_dana a
					LEFT JOIN t_pegawai b ON a.id_pegawai_ybs = b.id
					LEFT JOIN m_golongan c ON b.id_golongan = c.id
					LEFT JOIN m_pangkat_pegawai d ON b.id_pangkat = d.id
					LEFT JOIN t_pegawai b1 ON a.diketahui_oleh = b1.id
					LEFT JOIN m_golongan c1 ON b1.id_golongan = c1.id
					LEFT JOIN m_pangkat_pegawai d1 ON b1.id_pangkat = d1.id
					
					LEFT JOIN t_pegawai b2 ON a.kuasa_pengguna_anggaran = b2.id
					LEFT JOIN m_golongan c2 ON b2.id_golongan = c2.id
					LEFT JOIN m_pangkat_pegawai d2 ON b2.id_pangkat = d2.id
				WHERE
					a.id='" . $id . "'
				ORDER BY
					a.tanggal ASC, a.nomor ASC
			";
			//echo "<pre>$sql</pre>";
			$res = mysqli_query($app_conn, $sql);
			$data = array();
			$ds = mysqli_fetch_assoc($res);
			
			return $ds;
		}
		
		static function GetList03($id_belanja_honor) {
			global $app_conn;
			$sql = "
				SELECT
						a.id, b.nama_pegawai, b.no_rekening, c.pangkat,
						b.nik, b.jabatan AS jabatan_struktural, a.jabatan_pengelola,
						a.qty, a.sbu_honor,
						@jumlah_bruto := (a.qty * a.sbu_honor) AS jumlah_bruto,
						d.besar_pph,
						@pph := 
							CASE
								WHEN b.id_jenis_pegawai = '3' THEN CEIL(@jumlah_bruto / 2 * 5 / 100)
								ELSE COALESCE(CEIL(@jumlah_bruto * d.besar_pph / 100), 0)
							END AS pph,
						(@jumlah_bruto - @pph) AS jumlah_dibayarkan
				FROM
						t_detail_permohonan_dana_normatif a
						LEFT JOIN t_pegawai b ON a.id_pegawai = b.id
						LEFT JOIN m_pangkat_pegawai c ON b.id_pangkat = c.id
						LEFT JOIN m_golongan d ON b.id_golongan = d.id
						CROSS JOIN (SELECT @jumlah_bruto := 0) AS jumlah_bruto
						CROSS JOIN (SELECT @pph := 0) AS pph
				WHERE
						a.id_belanja_honor = '" . $id_belanja_honor . "'
				ORDER BY
						a.id ASC
			";
			$res = mysqli_query($app_conn, $sql);
			$data = array();
			while ($ds = mysqli_fetch_assoc($res)) {
				array_push($data, $ds);
			}
			
			return $data;
		}
		
		static function GetTotalList($id_belanja_honor) {
			global $app_conn;
			$data = array(
				"sbu_honor" => 0,
				"jumlah_bruto" => 0,
				"pph" => 0,
				"jumlah_dibayarkan" => 0
			);
			$list = PermohonanDanaModel::GetList03($id_belanja_honor);
			foreach ($list as $ls) {
				$data["sbu_honor"] += $ls["sbu_honor"];
				$data["jumlah_bruto"] += $ls["jumlah_bruto"];
				$data["pph"] += $ls["pph"];
				$data["jumlah_dibayarkan"] += $ls["jumlah_dibayarkan"];
			}
			
			return $data;
		}
	}
	
?>
