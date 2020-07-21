<?php

	class BelanjaHonorModel {
		
		static function GetList02($tgl_dari, $tgl_sampai, $uraian) {
			global $app_conn;
			$whr = "";
			
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
					t_belanja_honor a
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
                    CONCAT(2,'-',a.id) AS barcode,
                    a.id,
                    a.satuan,
                    a.tanggal,
                    a.nomor,
                    LPAD(a.na_nomor, 4, '0') AS na_nomor,
                    a.na_bulan,
                    a.na_tahun,
                    a.na_divisi,
                    a.keterangan,
                    a.id_pegawai_ybs,
                    a.diketahui_oleh,
                    a.id_pegawai_ybs_riwayat,
                    a.diketahui_oleh_riwayat,
                    a.kuasa_pengguna_anggaran,
                    a.no_sptjb,
                    a.jenis_belanja,
                    a.menyatakan,
                    a.user_insert,
                    b.nama_pegawai, c.golongan, d.pangkat, b_riwayat.jabatan, b_riwayat.nik,
                    b.id_jenis_pegawai, b1.id_jenis_pegawai AS id_jenis_pegawai_diketahui,
                    b1.nik AS nik_diketahui,
                    b1.nama_pegawai AS nama_pegawai_diketahui,
                    c1.golongan AS golongan_diketahui,
                    d1.pangkat AS pangkat_diketahui,
                    b1_riwayat.jabatan AS jabatan_diketahui
                FROM
                    t_belanja_honor a
                    LEFT JOIN t_pegawai b ON a.id_pegawai_ybs = b.id
                    LEFT JOIN itbl_apps_riwayat_pegawai b_riwayat ON a.id_pegawai_ybs_riwayat = b_riwayat.id_riwayat_pegawai
                    LEFT JOIN m_golongan c ON b_riwayat.id_golongan = c.id
                    LEFT JOIN m_pangkat_pegawai d ON b_riwayat.id_pangkat = d.id
                    LEFT JOIN t_pegawai b1 ON a.diketahui_oleh = b1.id
                    LEFT JOIN itbl_apps_riwayat_pegawai b1_riwayat ON a.diketahui_oleh_riwayat = b1_riwayat.id_riwayat_pegawai
                    LEFT JOIN m_golongan c1 ON b1_riwayat.id_golongan = c1.id
                    LEFT JOIN m_pangkat_pegawai d1 ON b1_riwayat.id_pangkat = d1.id
                WHERE
                    a.id='" . $id . "'
                ORDER BY
                    a.tanggal ASC, a.nomor ASC
			";
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
						CASE
							WHEN a.ada_pph = 1 THEN d.besar_pph
							ELSE 0
						END AS besar_pph,
						@pph := 
							CASE
								WHEN a.ada_pph = 0 THEN 0
								ELSE
									CASE
										WHEN b.id_jenis_pegawai = '3' THEN CEIL(@jumlah_bruto / 2 * 5 / 100)
										ELSE COALESCE(CEIL(@jumlah_bruto * d.besar_pph / 100), 0)
									END
							END AS pph,
						(@jumlah_bruto - @pph) AS jumlah_dibayarkan
				FROM
						t_detail_belanja_honor a
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
			$data = array(
				"sbu_honor" => 0,
				"jumlah_bruto" => 0,
				"pph" => 0,
				"jumlah_dibayarkan" => 0
			);
			$list = BelanjaHonorModel::GetList03($id_belanja_honor);
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