<?php
	error_reporting(0);
	session_start();
	include_once "../models/autoloader.php";
	include_once "../models/terbilang.php";
	
	//require_once "../dompdf-master/src/Dompdf.php";
	
	require_once '../dompdf/lib/html5lib/Parser.php';
	require_once '../dompdf/lib/php-font-lib/src/FontLib/Autoloader.php';
	require_once '../dompdf/lib/php-svg-lib/src/autoload.php';
	require_once '../dompdf/src/Autoloader.php';
	Dompdf\Autoloader::register();

	// reference the Dompdf namespace
	use Dompdf\Dompdf;
	
	// Inisialisasi isi cetak
	$tanggal_pertama_tahun = $_GET["tahun"] . "-01-01";
	$tanggal = $_GET["tahun"] . "-" . $_GET["bulan"] . "-01";
	$periode = $_GET["tahun"] . "-" . $_GET["bulan"];
	$bulan = semua_bulan();
	
	// Pejabat dari konfigurasi
	$konf_kpa = get_ttd_dokumen(9, "001", $tanggal);
	$konf_kaurkeu = get_ttd_dokumen(9, "002", $tanggal);
	
	// Mencari nomor pertama dari buku kas nya
	$db_nomor_buku = new DBConnection();
	$db_nomor_buku->perintahSQL = "
		SELECT
			'saldo' AS jenis, 1 AS urutan, COALESCE(a.id, 0) AS id, COALESCE(a.per_tgl, '') AS tanggal,
			'Saldo awal' AS keterangan,
			0 AS ppn, 0 AS pph, COALESCE(a.saldo, 0) AS total
		FROM
			itbl_apps_saldo_buku a
		WHERE
			a.per_tgl >= '" . $tanggal_pertama_tahun . "' AND a.per_tgl < '" . $tanggal . "'
			
		UNION
		
		SELECT
			'spp' AS jenis, 1 AS urutan, a.id, a.tanggal,
			CONCAT('Diterima dari rekening No. 0336-01-003220-30.1 berdasarkan SPP/SPM No, ', a.nomor, ' untuk pembayaran ', a.keterangan) AS keterangan,
			a.ppn, a.pph, a.total
		FROM
			vw_daftar_spp_spm a
		WHERE
			a.tanggal >= '" . $tanggal_pertama_tahun . "' AND a.tanggal < '" . $tanggal . "'
		
		UNION
		
		SELECT
			'pu' AS jenis, 2 AS urutan, a.id, a.tanggal,
			CONCAT('Pergeseran Uang : ', a.keterangan) AS keterangan,
			0 AS ppn, 0 AS pph, SUM(c.total) AS total
		FROM
			itbl_apps_pu a
			LEFT JOIN itbl_apps_pu_detail b ON a.id = b.id_pu
			LEFT JOIN vw_daftar_spp_spm c ON b.id_spp_spm = c.id
		WHERE
			a.tanggal >= '" . $tanggal_pertama_tahun . "' AND a.tanggal < '" . $tanggal . "'
		GROUP BY
			a.id
		
		UNION
		
		SELECT
			'terima_lain' AS jenis, 3 AS urutan, a.id, a.tanggal,
			a.keterangan,
			0 AS ppn, 0 AS pph, a.jumlah AS total
		FROM
			itbl_apps_penerimaan_lain a
		WHERE
			a.tanggal >= '" . $tanggal_pertama_tahun . "' AND a.tanggal < '" . $tanggal . "'
		
		UNION
		
		SELECT
			'spby' AS jenis, 4 AS urutan, a.id_spp_spm, a.tanggal,
			CONCAT('Dibayarkan ',b.keterangan, ' kepada ',a.penerima,' ',a.pangkat_penerima,' ',a.sebutan_nik_penerima,' ',a.nik_penerima,' telepon : ',a.telp_penerima) AS keterangan,
			b.ppn, b.pph, b.total
		FROM
			itbl_apps_spby a
			INNER JOIN vw_daftar_spp_spm b ON a.id_spp_spm = b.id
		WHERE
			a.tanggal >= '" . $tanggal_pertama_tahun . "' AND a.tanggal < '" . $tanggal . "'
		
		UNION
		
		SELECT
			'keluar_lain' AS jenis, 5 AS urutan, a.id, a.tanggal,
			a.keterangan,
			0 AS ppn, 0 AS pph, a.jumlah AS total
		FROM
			itbl_apps_pengeluaran_lain a
		WHERE
			a.tanggal >= '" . $tanggal_pertama_tahun . "' AND a.tanggal < '" . $tanggal . "'
			
		UNION
		
		SELECT
			'pajak' AS jenis, 6 AS urutan,
			pajak.id, CONCAT(pajak.tanggal, ' 00:00:00') AS tanggal, pajak.keterangan,
			SUM(
				CASE
					WHEN setpajak.jenis = 'ppn' THEN sppspm.ppn
					ELSE 0
				END
			) AS ppn,
			SUM(
				CASE
					WHEN setpajak.jenis = 'pph' THEN sppspm.pph
					ELSE 0
				END
			) AS pph, 0 AS total
		FROM
			vw_daftar_spp_spm sppspm
			INNER JOIN itbl_apps_penyetoran_pajak_detail setpajak ON sppspm.id = setpajak.id_spp_spm
			INNER JOIN itbl_apps_penyetoran_pajak pajak ON setpajak.id_penyetoran_pajak = pajak.id
		WHERE
			pajak.tanggal >= '" . $tanggal_pertama_tahun . "' AND pajak.tanggal < '" . $tanggal . "'
		GROUP BY
			pajak.id
	";
	$ds_nomor_buku = $db_nomor_buku->execute_reader();
	unset($db_nomor_buku);
	$nomor_buku = 0;
	foreach ($ds_nomor_buku as $dsnb) {
		if($dsnb["jenis"] != "pu") {
			$nomor_buku++;
		}
	}
	//$nomor_buku++;
	
	
	$sql_utama = "
		SELECT
			'saldo' AS jenis, 0 AS urutan, COALESCE(a.id, 0) AS id, COALESCE(a.per_tgl, '" . $tanggal . "') AS tanggal,
			'Saldo awal' AS keterangan,
			0 AS ppn, 0 AS pph, CONCAT(COALESCE(SUM(a.saldo), 0),'|',COALESCE(SUM(a.saldo_tunai), 0)) AS total
		FROM
			itbl_apps_saldo_buku a
		WHERE
			SUBSTR(a.per_tgl,1,7) = '" . $periode . "'
	
		UNION
		
		SELECT
			'spp' AS jenis, 1 AS urutan, a.id, a.tanggal,
			CONCAT('Diterima dari rekening No. 0336-01-003220-30.1 berdasarkan SPP/SPM No, ', a.nomor, ' untuk pembayaran ', a.keterangan) AS keterangan,
			a.ppn, a.pph, a.total
		FROM
			vw_daftar_spp_spm a
		WHERE
			SUBSTR(a.tanggal,1,7) = '" . $periode . "'
		
		UNION
		
		SELECT
			thepu.jenis, thepu.urutan, thepu.id, thepu.tanggal,
			GROUP_CONCAT(DISTINCT thepu.keterangan SEPARATOR ', ') AS keterangan,
			SUM(thepu.ppn) AS ppn, SUM(thepu.pph) AS pph, SUM(thepu.total) total
		FROM
			(
				SELECT
					'pu' AS jenis, 2 AS urutan, a.id, a.tanggal,
					CONCAT('Pergeseran Uang : ', a.keterangan) AS keterangan,
					0 AS ppn, 0 AS pph, SUM(c.total) AS total
				FROM
					itbl_apps_pu a
					LEFT JOIN itbl_apps_pu_detail b ON a.id = b.id_pu
					LEFT JOIN vw_daftar_spp_spm c ON b.id_spp_spm = c.id
				WHERE
					SUBSTR(a.tanggal,1,7) = '" . $periode . "'
				GROUP BY
					a.id
		
				UNION
		
				SELECT
					'pu' AS jenis, 2 AS urutan, a.id, a.tanggal,
					CONCAT('Pergeseran Uang : ', a.keterangan) AS keterangan,
					0 AS ppn, 0 AS pph, SUM(COALESCE(b.jumlah, 0)) AS total
				FROM
					itbl_apps_pu a
					LEFT JOIN itbl_apps_pu_lain b ON a.id = b.id_pu
				WHERE
					SUBSTR(a.tanggal,1,7) = '" . $periode . "'
				GROUP BY
					a.id
			) thepu
		GROUP BY
			thepu.id
		
		UNION
		
		SELECT
			'terima_lain' AS jenis, 3 AS urutan, a.id, a.tanggal,
			a.keterangan,
			0 AS ppn, 0 AS pph, a.jumlah AS total
		FROM
			itbl_apps_penerimaan_lain a
		WHERE
			SUBSTR(a.tanggal,1,7) = '" . $periode . "'
		
		UNION
		
		SELECT
			'spby' AS jenis, 4 AS urutan, a.id_spp_spm, a.tanggal,
			CONCAT('Dibayarkan ',b.keterangan, ' kepada ',a.penerima,' ',a.pangkat_penerima,' ',a.sebutan_nik_penerima,' ',a.nik_penerima,' telepon : ',a.telp_penerima) AS keterangan,
			b.ppn, b.pph, b.total
		FROM
			itbl_apps_spby a
			INNER JOIN vw_daftar_spp_spm b ON a.id_spp_spm = b.id
		WHERE
			SUBSTR(a.tanggal,1,7) = '" . $periode . "'
		
		UNION
		
		SELECT
			'keluar_lain' AS jenis, 5 AS urutan, a.id, a.tanggal,
			a.keterangan,
			0 AS ppn, 0 AS pph, a.jumlah AS total
		FROM
			itbl_apps_pengeluaran_lain a
		WHERE
			SUBSTR(a.tanggal,1,7) = '" . $periode . "'
				
		UNION
		
		SELECT
			'pajak' AS jenis, 6 AS urutan,
			pajak.id, CONCAT(pajak.tanggal, ' 00:00:00') AS tanggal, pajak.keterangan,
			SUM(
				CASE
					WHEN setpajak.jenis = 'ppn' THEN sppspm.ppn
					ELSE 0
				END
			) AS ppn,
			SUM(
				CASE
					WHEN setpajak.jenis = 'pph' THEN sppspm.pph
					ELSE 0
				END
			) AS pph, 0 AS total
		FROM
			vw_daftar_spp_spm sppspm
			INNER JOIN itbl_apps_penyetoran_pajak_detail setpajak ON sppspm.id = setpajak.id_spp_spm
			INNER JOIN itbl_apps_penyetoran_pajak pajak ON setpajak.id_penyetoran_pajak = pajak.id
		WHERE
			SUBSTR(pajak.tanggal, 1, 7) = '" . $periode . "'
		GROUP BY
			pajak.id
	";
	
	//echo "<pre>$sql_utama</pre>";
	//exit;
	
	$db_utama = new DBConnection();
	$db_utama->perintahSQL = "
		SELECT
			1 AS tipe, tanggal.*
		FROM
			(" . $sql_utama . ") tanggal
		GROUP BY
			tanggal.tanggal
		
		UNION ALL
		
		SELECT
			2 AS tipe, isi.*
		FROM
			(" . $sql_utama . ") isi
			
		ORDER BY
			tanggal ASC, urutan ASC, tipe ASC
	";
	$ds_buku = $db_utama->execute_reader();
	unset($db_utama);
	
	$isi = "";
	$no = $nomor_buku;
	$saldo_akhir = 0;
	$saldo_akhir_tunai = 0;
	
	$total_debet_saldo = 0;
	$total_debet_ppn = 0;
	$total_debet_pph = 0;
	$total_kredit_saldo = 0;
	$total_kredit_ppn = 0;
	$total_kredit_pph = 0;
	$total_jumlah_tunai_debet = 0;
	$total_jumlah_tunai_kredit = 0;
	$total_bank_saldo = 0;
	$total_bank_debet = 0;
	$total_bank_kredit = 0;
	foreach ($ds_buku as $dsb) {
		if($dsb["tipe"] == 1) {
			$isi .= "
				<tr style='font-weight: bold; font-style: italic;'>
					<td></td>
					<td align='center'>=== " . tanggal_indonesia_panjang($dsb["tanggal"]) . " ===</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
			";
		} else {
			if($dsb["jenis"] == "saldo") {
				$no++;
				$exp_saldo = explode("|", $dsb["total"]);
				$saldo = $exp_saldo[0];
				$saldo_tunai = $exp_saldo[1];
				$ppn = $dsb["ppn"];
				$pph = $dsb["pph"];
				$total = $saldo;
				
				$saldo_akhir += $saldo;
				$saldo_akhir_tunai += $saldo_tunai;
				
				
				$isi .= "
					<tr style='page-break-inside: avoid;'>
						<td align='center'>" . $no . "</td>
						<td>" . $dsb["keterangan"] . "</td>
						
						<td></td>
						<td></td>
						<td></td>
						
						<td align='right'>" . number_format($saldo, 2) . "</td>
						<td align='right'>" . number_format($ppn, 2) . "</td>
						<td align='right'>" . number_format($pph, 2) . "</td>
						
						<td align='right'>" . number_format($saldo_tunai, 2) . "</td>
						<td></td>
						<td></td>
						
						<td align='right'>" . number_format($saldo_akhir, 2) . "</td>
						<td align='right'>-</td>
						<td align='right'>" . number_format($total, 2) . "</td>
					</tr>
				";
				
				//$total_debet_saldo = 0;
				//$total_debet_ppn = 0;
				//$total_debet_pph = 0;
				
				$total_kredit_saldo += $saldo;
				$total_kredit_ppn += $ppn;
				$total_kredit_pph += $pph;
				
				//$total_jumlah_tunai_debet = 0;
				//$total_jumlah_tunai_kredit = 0;
				
				$total_bank_saldo += $saldo_akhir;
				//$total_bank_debet = 0;
				$total_bank_kredit += $total;
			} elseif($dsb["jenis"] == "spp") {
				$no++;
				$saldo = $dsb["total"] - $dsb["ppn"] - $dsb["pph"];
				$ppn = $dsb["ppn"];
				$pph = $dsb["pph"];
				$total = $dsb["total"];
				
				$saldo_akhir += $total;
				
				$isi .= "
					<tr style='page-break-inside: avoid;'>
						<td align='center'>" . $no . "</td>
						<td>" . $dsb["keterangan"] . "</td>
						
						<td></td>
						<td></td>
						<td></td>
						
						<td align='right'>" . number_format($saldo, 2) . "</td>
						<td align='right'>" . number_format($ppn, 2) . "</td>
						<td align='right'>" . number_format($pph, 2) . "</td>
						
						<td align='right'>" . number_format($saldo_akhir_tunai, 2) . "</td>
						<td></td>
						<td></td>
						
						<td align='right'>" . number_format($saldo_akhir, 2) . "</td>
						<td align='right'>-</td>
						<td align='right'>" . number_format($total, 2) . "</td>
					</tr>
				";
				
				//$total_debet_saldo = 0;
				//$total_debet_ppn = 0;
				//$total_debet_pph = 0;
				
				$total_kredit_saldo += $saldo;
				$total_kredit_ppn += $ppn;
				$total_kredit_pph += $pph;
				
				//$total_jumlah_tunai_debet = 0;
				//$total_jumlah_tunai_kredit = 0;
				
				$total_bank_saldo += $saldo_akhir;
				//$total_bank_debet = 0;
				$total_bank_kredit += $total;
			} elseif($dsb["jenis"] == "pu") {
				$total = $dsb["total"];
				
				$saldo_akhir -= $total;
				$saldo_akhir_tunai += $total;
				
				$isi .= "
					<tr style='font-weight: bold; text-transform: uppercase; page-break-inside: avoid;'>
						<td align='center'>PU</td>
						<td>" . $dsb["keterangan"] . "</td>
						
						<td></td>
						<td></td>
						<td></td>
						
						<td align='right'></td>
						<td align='right'></td>
						<td align='right'></td>
						
						<td align='right'>" . number_format($saldo_akhir_tunai, 2) . "</td>
						<td></td>
						<td align='right'>" . number_format($total, 2) . "</td>
						
						<td align='right'>" . number_format($saldo_akhir, 2) . "</td>
						<td align='right'>" . number_format($total, 2) . "</td>
						<td align='right'></td>
					</tr>
				";
				
				//$total_debet_saldo = 0;
				//$total_debet_ppn = 0;
				//$total_debet_pph = 0;
				
				//$total_kredit_saldo = 0;
				//$total_kredit_ppn = 0;
				//$total_kredit_pph = 0;
				
				//$total_jumlah_tunai_debet = 0;
				$total_jumlah_tunai_kredit += $total;
				
				//$total_bank_saldo = 0;
				$total_bank_debet += $total;
				//$total_bank_kredit = 0;
			} elseif($dsb["jenis"] == "terima_lain") {
				$no++;
				$saldo = $dsb["total"] - $dsb["ppn"] - $dsb["pph"];
				$ppn = $dsb["ppn"];
				$pph = $dsb["pph"];
				$total = $dsb["total"];
				
				$saldo_akhir += $total;
				
				$isi .= "
					<tr style='page-break-inside: avoid;'>
						<td align='center'>" . $no . "</td>
						<td>" . $dsb["keterangan"] . "</td>
						
						<td></td>
						<td></td>
						<td></td>
						
						<td align='right'>" . number_format($saldo, 2) . "</td>
						<td align='right'>" . number_format($ppn, 2) . "</td>
						<td align='right'>" . number_format($pph, 2) . "</td>
						
						<td align='right'>" . number_format($saldo_akhir_tunai, 2) . "</td>
						<td></td>
						<td></td>
						
						<td align='right'>" . number_format($saldo_akhir, 2) . "</td>
						<td align='right'>-</td>
						<td align='right'>" . number_format($total, 2) . "</td>
					</tr>
				";
				
				//$total_debet_saldo = 0;
				//$total_debet_ppn = 0;
				//$total_debet_pph = 0;
				
				$total_kredit_saldo += $saldo;
				$total_kredit_ppn += $ppn;
				$total_kredit_pph += $pph;
				
				//$total_jumlah_tunai_debet = 0;
				//$total_jumlah_tunai_kredit = 0;
				
				$total_bank_saldo += $saldo_akhir;
				//$total_bank_debet = 0;
				$total_bank_kredit += $total;
			} elseif($dsb["jenis"] == "spby") {
				$no++;
				$saldo = $dsb["total"] - $dsb["ppn"] - $dsb["pph"];
				$ppn = 0;
				$pph = 0;
				$total = $dsb["total"] - $dsb["ppn"] - $dsb["pph"];
				
				$saldo_akhir_tunai -= $total;
				
				$isi .= "
					<tr style=' page-break-inside: avoid;'>
						<td align='center'>" . $no . "</td>
						<td>" . $dsb["keterangan"] . "</td>
						
						<td align='right'>" . number_format($saldo, 2) . "</td>
						<td align='right'>" . number_format($ppn, 2) . "</td>
						<td align='right'>" . number_format($pph, 2) . "</td>
						
						<td></td>
						<td></td>
						<td></td>
						
						<td align='right'>" . number_format($saldo_akhir_tunai, 2) . "</td>
						<td align='right'>" . number_format($total, 2) . "</td>
						<td></td>
						
						<td align='right'>" . number_format($saldo_akhir, 2) . "</td>
						<td align='right'>-</td>
						<td></td>
					</tr>
				";
				
				$total_debet_saldo += $saldo;
				$total_debet_ppn += $ppn;
				$total_debet_pph += $pph;
				
				//$total_kredit_saldo = 0;
				//$total_kredit_ppn = 0;
				//$total_kredit_pph = 0;
				
				$total_jumlah_tunai_debet += $total;
				//$total_jumlah_tunai_kredit = 0;
				
				$total_bank_saldo += $saldo_akhir;
				//$total_bank_debet = 0;
				//$total_bank_kredit = 0;
			} elseif($dsb["jenis"] == "keluar_lain") {
				$no++;
				$saldo = $dsb["total"] - $dsb["ppn"] - $dsb["pph"];
				$ppn = $dsb["ppn"];
				$pph = $dsb["pph"];
				$total = $dsb["total"];
				
				$saldo_akhir_tunai -= $total;
				
				$isi .= "
					<tr style=' page-break-inside: avoid;'>
						<td align='center'>" . $no . "</td>
						<td>" . $dsb["keterangan"] . "</td>
						
						<td align='right'>" . number_format($saldo, 2) . "</td>
						<td align='right'>" . number_format($ppn, 2) . "</td>
						<td align='right'>" . number_format($pph, 2) . "</td>
						
						<td></td>
						<td></td>
						<td></td>
						
						<td align='right'>" . number_format($saldo_akhir_tunai, 2) . "</td>
						<td align='right'>" . number_format($total, 2) . "</td>
						<td></td>
						
						<td align='right'>" . number_format($saldo_akhir, 2) . "</td>
						<td align='right'>-</td>
						<td></td>
					</tr>
				";
				
				$total_debet_saldo += $saldo;
				$total_debet_ppn += $ppn;
				$total_debet_pph += $pph;
				
				//$total_kredit_saldo = 0;
				//$total_kredit_ppn = 0;
				//$total_kredit_pph = 0;
				
				$total_jumlah_tunai_debet += $total;
				//$total_jumlah_tunai_kredit = 0;
				
				$total_bank_saldo += $saldo_akhir;
				//$total_bank_debet = 0;
				//$total_bank_kredit = 0;
			} elseif($dsb["jenis"] == "pajak") {
				$no++;
				$saldo = 0;
				$ppn = $dsb["ppn"];
				$pph = $dsb["pph"];
				$total = $dsb["ppn"] + $dsb["pph"];
				
				$saldo_akhir_tunai -= $total;
				
				$isi .= "
					<tr style=' page-break-inside: avoid;'>
						<td align='center'>" . $no . "</td>
						<td>" . $dsb["keterangan"] . "</td>
						
						<td align='right'>" . number_format($saldo, 2) . "</td>
						<td align='right'>" . number_format($ppn, 2) . "</td>
						<td align='right'>" . number_format($pph, 2) . "</td>
						
						<td></td>
						<td></td>
						<td></td>
						
						<td align='right'>" . number_format($saldo_akhir_tunai, 2) . "</td>
						<td align='right'>" . number_format($total, 2) . "</td>
						<td></td>
						
						<td align='right'>" . number_format($saldo_akhir, 2) . "</td>
						<td align='right'>-</td>
						<td></td>
					</tr>
				";
				
				$total_debet_saldo += $saldo;
				$total_debet_ppn += $ppn;
				$total_debet_pph += $pph;
				
				//$total_kredit_saldo = 0;
				//$total_kredit_ppn = 0;
				//$total_kredit_pph = 0;
				
				$total_jumlah_tunai_debet += $total;
				//$total_jumlah_tunai_kredit = 0;
				
				$total_bank_saldo += $saldo_akhir;
				//$total_bank_debet = 0;
				//$total_bank_kredit = 0;
			}
		}
	}
	
	
	$isi .= "
		<tr style=' page-break-inside: avoid; font-weight: bold;'>
			<td align='center'></td>
			<td>TOTAL</td>
			
			<td align='right'>" . number_format($total_debet_saldo, 2) . "</td>
			<td align='right'>" . number_format($total_debet_ppn, 2) . "</td>
			<td align='right'>" . number_format($total_debet_pph, 2) . "</td>
			
			<td align='right'>" . number_format($total_kredit_saldo, 2) . "</td>
			<td align='right'>" . number_format($total_kredit_ppn, 2) . "</td>
			<td align='right'>" . number_format($total_kredit_pph, 2) . "</td>
			
			<td align='right'>" . number_format($saldo_akhir_tunai, 2) . "</td>
			<td align='right'>" . number_format($total_jumlah_tunai_debet, 2) . "</td>
			<td align='right'>" . number_format($total_jumlah_tunai_kredit, 2) . "</td>
			
			<td align='right'>" . number_format($saldo_akhir, 2) . "</td>
			<td align='right'>" . number_format($total_bank_debet, 2) . "</td>
			<td align='right'>" . number_format($total_bank_kredit, 2) . "</td>
		</tr>
	";
	$db_tanggal = null;
	$css = "
		<style>
			@page {
				margin: 0.5cm 0.5cm;
				size: 21cm 29.5cm landscape;
			}
		</style>
	";
	$cetak = $css . "
		
		
		<div id='isi_buku_kas'>
			<div style='margin-bottom: 20px; font-size: 10pt;'>
				<table width='500px' style='font-weight: bold;'>
					<tr>
						<td align='left'>KEPOLISIAN NEGARA REPUBLIK INDONESIA</td>
					</tr>
					<tr>
						<td align='left' style='padding-left: 70px;'>DAERAH SUMATERA UTARA</td>
					</tr>
					<tr>
						<td align='left' style='text-decoration: underline;'>RUMAH SAKIT BHAYANGKARA TK II MEDAN</td>
					</tr>
				</table>
			</div>
			<div style='text-align: center; font-family: serif; font-size: 12pt; font-weight: bold; text-decoration: underline;'>BUKU KAS BANK </div>
			<div style='text-align: center; font-family: serif; font-size: 10pt; font-weight: bold;'>TA. " . $_GET["tahun"] . "</div>
			<div style='text-align: center; font-family: serif; font-size: 10pt; font-weight: bold;'>" . $bulan[$_GET["bulan"]] . " " . $_GET["tahun"] . "</div>
			
			<div style='text-align: left; font-family: serif; font-size: 10pt; font-weight: bold;'>SATTAMA : POLDA SUMUT</div>
			<div style='text-align: left; font-family: serif; font-size: 10pt; font-weight: bold;'>SATKER : RUMAH SAKIT BHAYANGKARA TK II MEDAN</div>
			
			<br />
			
			<table width='100%' cellspacing='0' cellpadding='0' border='1' style='font-size: 8pt;'>
				<thead>
					<tr>
						<th rowspan='2' align='center'>NO.</th>
						<th rowspan='2'>URAIAN</th>
						
						<th colspan='3' align='center'>DEBET</th>
						
						<th colspan='3' align='center'>KREDIT</th>
						
						<th colspan='3' align='center'>JUMLAH TUNAI</th>
						
						<th colspan='3' align='center'>BRI REK : 0336-01-003221-30.7</th>
					</tr>
					<tr>
						<th width='90px' align='center'>SALDO</th>
						<th width='90px' align='center'>PPN</th>
						<th width='90px' align='center'>PPh</th>
						
						<th width='90px' align='center'>SALDO</th>
						<th width='90px' align='center'>PPN</th>
						<th width='90px' align='center'>PPh</th>
						
						
						<th width='70px' align='center'>SALDO</th>
						<th width='70px' align='center'>DEBET</th>
						<th width='70px' align='center'>KREDIT</th>
						
						<th width='70px' align='center'>SALDO</th>
						<th width='70px' align='center'>DEBET</th>
						<th width='70px' align='center'>KREDIT</th>
					</tr>
					<tr>
						<th align='center'>1</th>
						<th align='center'>2</th>
						<th align='center'>3</th>
						<th align='center'>4</th>
						<th align='center'>5</th>
						<th align='center'>6</th>
						<th align='center'>7</th>
						<th align='center'>8</th>
						<th align='center'>9</th>
						<th align='center'>10</th>
						<th align='center'>11</th>
						<th align='center'>12</th>
						<th align='center'>13</th>
						<th align='center'>14</th>
					</tr>
				</thead>
				<tbody>
					" . $isi . "
				</tbody>
			</table>
			<span style='font-size: 9pt;'>
				Pada hari ini " . nama_hari(tanggal_hari_terakhir($tanggal)) . " tanggal " . tanggal_indonesia_panjang(tanggal_hari_terakhir($tanggal)) . " untuk Buku Kas Bank Pengeluaran BLU
				ditutup dengan sisa sebesar :
				<ul>
					<li>Kas Tunai : Rp. " . number_format($saldo_akhir_tunai, 0) . ", -</li>
					<li>Kas Bank : Rp. " . number_format($saldo_akhir, 0) . ", -</li>
				</ul>
			</span>
			<br />
			<br />
			<table width='100%' cellspacing='0' cellpadding='0' style='font-size: 9pt; table-layout: fixed; page-break-inside: avoid;' border='0'>
				<tr>
					<td align='center'>Diketahui Oleh</td>
					<td align='center'>Medan, " . tanggal_indonesia_panjang(tanggal_hari_terakhir($tanggal)) . "</td>
				</tr>
				<tr>
					<td align='center' style='font-weight: bold;' valign='top'>" . $konf_kpa["judul_ttd"] . "</td>
					<td align='center' style='font-weight: bold;' valign='top'>" . $konf_kaurkeu["judul_ttd"] . "</td>
				</tr>
				<tr>
					<td style='height: 70px'></td>
					<td></td>
				</tr>
				<tr>
					<td align='center' style='font-weight: bold; text-decoration: underline;' valign='top'>" . $konf_kpa["nama_pegawai"] . "</td>
					<td align='center' style='font-weight: bold; text-decoration: underline;' valign='top'>" . $konf_kaurkeu["nama_pegawai"] . "</td>
				</tr>
				<tr>
					<td align='center' style='' valign='top'>" . $konf_kpa["pangkat"] . " " . $konf_kpa["sebutan_nrp"] . " " . $konf_kpa["nik"] . "</td>
					<td align='center' style='' valign='top'>" . $konf_kaurkeu["pangkat"] . " " . $konf_kaurkeu["sebutan_nrp"] . " " . $konf_kaurkeu["nik"] . "</td>
				</tr>
			</table>
		</div>
		
		
	";
	
	// instantiate and use the dompdf class
	//$dompdf = new Dompdf();
	//$dompdf->loadHtml($cetak);
	
	// (Optional) Setup the paper size and orientation
	//$dompdf->setPaper('legal', 'landscape');
	
	// Render the HTML as PDF
	//$dompdf->render();
	//echo $cetak;
	
	// Output the generated PDF to Browser
	//$dompdf->stream("rincian gaji.pdf", array("Attachment" => false));
	echo $cetak;
	
	/* Proses penyimpanan saldo akhir */
	$sql_delete = new DBConnection();
	$sql_delete->perintahSQL = "DELETE FROM itbl_apps_saldo_buku WHERE per_tgl=DATE_ADD('" . $tanggal . "',INTERVAL 1 MONTH)";
	$sql_delete->execute_non_query();
	
	$sql_simpan = new DBConnection();
	$sql_simpan->perintahSQL = "INSERT INTO itbl_apps_saldo_buku(per_tgl, saldo, saldo_tunai) VALUES(DATE_ADD(?,INTERVAL 1 MONTH), ?, ?)";
	$sql_simpan->add_parameter("s", $tanggal);
	$sql_simpan->add_parameter("d", $saldo_akhir);
	$sql_simpan->add_parameter("d", $saldo_akhir_tunai);
	$sql_simpan->execute_non_query();
	
	/* Proses penyimpanan hasil buku */
	$sql_delete_buku = new DBConnection();
	$sql_delete_buku->perintahSQL = "DELETE FROM itbl_apps_hasil_buku WHERE tanggal = '" . tanggal_hari_terakhir($tanggal) . "'";
	$sql_delete_buku->execute_non_query();
	
	$sql_simpan_buku = new DBConnection();
	$sql_simpan_buku->perintahSQL = "
		INSERT INTO itbl_apps_hasil_buku (
			tanggal, debet_saldo, 
			debet_ppn, debet_pph, kredit_saldo, 
			kredit_ppn, kredit_pph, tunai_debet, 
			tunai_kredit, bank_debet, bank_kredit
		) VALUES(
			?, ?, 
			?, ?, ?, 
			?, ?, ?, 
			?, ?, ?
		)
	";
	$sql_simpan_buku->add_parameter("s", tanggal_hari_terakhir($tanggal));
	$sql_simpan_buku->add_parameter("d", $total_debet_saldo);
	$sql_simpan_buku->add_parameter("d", $total_debet_ppn);
	$sql_simpan_buku->add_parameter("d", $total_debet_pph);
	$sql_simpan_buku->add_parameter("d", $total_kredit_saldo);
	$sql_simpan_buku->add_parameter("d", $total_kredit_ppn);
	$sql_simpan_buku->add_parameter("d", $total_kredit_pph);
	$sql_simpan_buku->add_parameter("d", $total_jumlah_tunai_debet);
	$sql_simpan_buku->add_parameter("d", $total_jumlah_tunai_kredit);
	$sql_simpan_buku->add_parameter("d", $total_bank_debet);
	$sql_simpan_buku->add_parameter("d", $total_bank_kredit);
	$sql_simpan_buku->execute_non_query();
?>
<!--<html>
	<head>
		<script src='../views/Lumino Admin Bootstrap Template/lumino/js/jquery-1.11.1.min.js'></script>
		<script type="text/javascript" charset="utf-8">
			$(function() {
				var isi = $("#isi_buku_kas").html();
				$("#buku").val(isi);
				$("#frm_buku").submit();
			});
		</script>
	</head>
	<body>
		<?php echo $cetak; ?>
	</body>
</html>-->