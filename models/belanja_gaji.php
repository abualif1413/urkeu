<?php

	class BelanjaGajiModel {
		
		static function GetList02($tgl_dari, $tgl_sampai, $uraian) {
			global $app_conn;
			$sql = "
				SELECT
					a.*, b.nama_pegawai
				FROM
					t_belanja_gaji a
					LEFT JOIN t_pegawai b ON a.id_pegawai_ybs = b.id
				WHERE
					1=1 AND a.tanggal BETWEEN '" . $tgl_dari . "' AND '" . $tgl_sampai . "' AND
					(a.keterangan LIKE '%" . $uraian . "%' OR REPLACE(b.nama_pegawai,' ','') LIKE '%" . $uraian . "%')
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
					CONCAT(3,'-',a.id) AS barcode,
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
					a.kuasa_pengguna_anggaran,
					a.no_sptjb,
					a.jenis_belanja,
					a.menyatakan,
					a.user_insert,
					b.nama_pegawai, c.golongan, d.pangkat, b.jabatan, b.nik,
					b.id_jenis_pegawai, b1.id_jenis_pegawai AS id_jenis_pegawai_diketahui,
					b1.nik AS nik_diketahui,
					b1.nama_pegawai AS nama_pegawai_diketahui,
					c1.golongan AS golongan_diketahui,
					d1.pangkat AS pangkat_diketahui,
					b1.jabatan AS jabatan_diketahui
				FROM
					t_belanja_gaji a
					LEFT JOIN t_pegawai b ON a.id_pegawai_ybs = b.id
					LEFT JOIN m_golongan c ON b.id_golongan = c.id
					LEFT JOIN m_pangkat_pegawai d ON b.id_pangkat = d.id
					LEFT JOIN t_pegawai b1 ON a.diketahui_oleh = b1.id
					LEFT JOIN m_golongan c1 ON b1.id_golongan = c1.id
					LEFT JOIN m_pangkat_pegawai d1 ON b1.id_pangkat = d1.id
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
	
		static function GetList03($id_belanja_gaji) {
			global $app_conn;
			$sql = "
				SELECT
					a.id, b.nama_pegawai, b.no_rekening, b.jabatan,
					a.gapok,
					a.potongan1,
					(@nilai_potongan1 := a.gapok * a.potongan1 * 0.5 / 100) AS nilai_potongan1,
					a.potongan2,
					(@nilai_potongan2 := a.gapok * a.potongan2 * 0.75 / 100) AS nilai_potongan2,
					a.potongan3,
					(@nilai_potongan3 := a.gapok * 1 / 100 * a.potongan3) AS nilai_potongan3,
					a.potongan4,
					(@nilai_potongan4 := a.gapok * 3 / 100 * a.potongan4) AS nilai_potongan4,
					(@pengurangan1 := @nilai_potongan1 + @nilai_potongan2 + @nilai_potongan3 + @nilai_potongan4) AS pengurangan1,
					(@nilai_pph :=
						CASE
							WHEN b.id_jenis_pegawai = '3' THEN CEIL((a.gapok - @pengurangan1) / 2 * 5 / 100)
							ELSE COALESCE((a.gapok - @pengurangan1) * c.besar_pph / 100, 0)
						END ) AS nilai_pph,
					(@pengurangan := @nilai_potongan1 + @nilai_potongan2 + @nilai_potongan3 + @nilai_potongan4 + @nilai_pph) AS pengurangan,
					(a.gapok - @pengurangan) AS total_dibayar
				FROM
					t_detail_belanja_gaji a
					LEFT JOIN t_pegawai b ON a.id_pegawai = b.id
					CROSS JOIN (SELECT @nilai_potongan1 := 0) AS nilai_potongan1
					CROSS JOIN (SELECT @nilai_potongan2 := 0) AS nilai_potongan2
					CROSS JOIN (SELECT @nilai_potongan3 := 0) AS nilai_potongan3
					CROSS JOIN (SELECT @nilai_potongan4 := 0) AS nilai_potongan4
					CROSS JOIN (SELECT @nilai_pph := 0) AS nilai_pph
					CROSS JOIN (SELECT @pengurangan1 := 0) AS pengurangan1
					CROSS JOIN (SELECT @pengurangan := 0) AS pengurangan
					LEFT JOIN m_golongan c ON b.id_golongan = c.id
				WHERE
					a.id_belanja_gaji = '" . $id_belanja_gaji . "'
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
		
		static function GetTotalList($id_belanja_gaji) {
			global $app_conn;
			$data = array(
				"gapok" => 0,
				"nilai_pph" => 0,
				"total_dibayar" => 0
			);
			$list = BelanjaGajiModel::GetList03($id_belanja_gaji);
			foreach ($list as $ls) {
				$data["gapok"] += $ls["gapok"];
				$data["nilai_pph"] += $ls["nilai_pph"];
				$data["total_dibayar"] += $ls["total_dibayar"];
			}
			
			return $data;
		}
	
	}
	
?>