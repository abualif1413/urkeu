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
	$tanggal = $_GET["tahun"] . "-" . $_GET["bulan"] . "-01";
	$periode = $_GET["tahun"] . "-" . $_GET["bulan"];
	$bulan = semua_bulan();
	$rencana_aja = tanggal_indonesia_panjang($tanggal);
	$rencana_aja_arr = explode(" ", $rencana_aja);
	$periode_panjang = $rencana_aja_arr[1] . " " . $rencana_aja_arr[2];
	
	// Variable dari konfigurasi
	$dipa = get_variable_konfigurasi("dipa", $tanggal);
	
	// Pejabat dari konfigurasi
	$konf_kpa = get_ttd_dokumen(13, "001", $tanggal);
	$konf_kaurkeu = get_ttd_dokumen(13, "002", $tanggal);
	
	$sql_ambil_buku = new DBConnection();
	$sql_ambil_buku->perintahSQL = "
		SELECT
			a.*, b.saldo, b.saldo_tunai
		FROM
			itbl_apps_hasil_buku a
			LEFT JOIN itbl_apps_saldo_buku b ON DATE_FORMAT(a.tanggal,'%Y-%m-01') = b.per_tgl
		WHERE
			tanggal = '" . tanggal_hari_terakhir($tanggal) . "'
	";
	
	$ambil_buku = $sql_ambil_buku->execute_reader();
	$sql_ambil_buku = null;
	
	if(count($ambil_buku) > 0) {
		$saldo_awal_tunai = $ambil_buku[0]["saldo_tunai"];
		$saldo_awal = $ambil_buku[0]["saldo"];
		$total_debet_saldo = $ambil_buku[0]["debet_saldo"];
		$total_debet_ppn = $ambil_buku[0]["debet_ppn"];
		$total_debet_pph = $ambil_buku[0]["debet_pph"];
		$total_kredit_saldo = $ambil_buku[0]["kredit_saldo"];
		$total_kredit_ppn = $ambil_buku[0]["kredit_ppn"];
		$total_kredit_pph = $ambil_buku[0]["kredit_pph"];
		$total_jumlah_tunai_debet = $ambil_buku[0]["tunai_debet"];
		$total_jumlah_tunai_kredit = $ambil_buku[0]["tunai_kredit"];
		$total_bank_debet = $ambil_buku[0]["bank_debet"];
		$total_bank_kredit = $ambil_buku[0]["bank_kredit"];
		$saldo_akhir_tunai = $total_jumlah_tunai_kredit - $total_jumlah_tunai_debet;
		$saldo_akhir = $total_bank_kredit - $total_bank_debet;
	}
	
	// Rincian Buku hasil SPP/SPM/SPBy
	$sql_rincian_buku = new DBConnection();
	$sql_rincian_buku->perintahSQL = "
		SELECT
			SUM(trx.dpp_masuk) AS dpp_masuk, SUM(trx.ppn_masuk) AS ppn_masuk, SUM(trx.pph_masuk) AS pph_masuk,
			SUM(trx.dpp_keluar) AS dpp_keluar, SUM(trx.ppn_keluar) AS ppn_keluar, SUM(trx.pph_keluar) AS pph_keluar
		FROM
			(
				SELECT
					(sppspm.total - sppspm.ppn - sppspm.pph) AS dpp_masuk, sppspm.ppn AS ppn_masuk, sppspm.pph AS pph_masuk,
					CASE
						WHEN spby.id_spp_spm IS NOT NULL THEN COALESCE(sppspm.total - sppspm.ppn - sppspm.pph)
						ELSE 0
					END AS dpp_keluar,
					CASE
						WHEN spby.id_spp_spm IS NOT NULL THEN COALESCE(sppspm.ppn)
						ELSE 0
					END AS ppn_keluar,
					CASE
						WHEN spby.id_spp_spm IS NOT NULL THEN COALESCE(sppspm.pph)
						ELSE 0
					END AS pph_keluar
				FROM
					vw_daftar_spp_spm sppspm
					LEFT JOIN itbl_apps_spby spby ON sppspm.id = spby.id_spp_spm AND spby.tanggal BETWEEN '" . $tanggal . "' AND LAST_DAY('" . $tanggal . "')
				WHERE
					sppspm.tanggal BETWEEN '" . $tanggal . "' AND LAST_DAY('" . $tanggal . "')
			) trx
	";
	$rincian_buku = $sql_rincian_buku->execute_reader();
	if(count($rincian_buku) > 0) {
		$dpp_masuk = $rincian_buku[0]["dpp_masuk"];
		$ppn_masuk = $rincian_buku[0]["ppn_masuk"];
		$pph_masuk = $rincian_buku[0]["pph_masuk"];
		$dpp_keluar = $rincian_buku[0]["dpp_keluar"];
		$ppn_keluar = $rincian_buku[0]["ppn_keluar"];
		$pph_keluar = $rincian_buku[0]["pph_keluar"];
	}
	
	// Rincian Buku Lain-Lain
	$sql_rincian_buku_lain = new DBConnection();
	$sql_rincian_buku_lain->perintahSQL = "
		SELECT
			(SELECT SUM(jumlah) terima_lain FROM itbl_apps_penerimaan_lain WHERE tanggal BETWEEN '" . $tanggal . "' AND LAST_DAY('" . $tanggal . "')) AS terima_lain,
			(SELECT SUM(jumlah) keluar_lain FROM itbl_apps_pengeluaran_lain WHERE tanggal BETWEEN '" . $tanggal . "' AND LAST_DAY('" . $tanggal . "')) AS keluar_lain
	";
	$rincian_buku_lain = $sql_rincian_buku_lain->execute_reader();
	if(count($rincian_buku_lain) > 0) {
		$penerimaan_lain = $rincian_buku_lain[0]["terima_lain"];
		$pengeluaran_lain = $rincian_buku_lain[0]["keluar_lain"];
	}
	
	$cetak = "
		<style>
			@page { margin: 1cm 0.5cm; }
		</style>
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
		
		<table width='100%' border='1' style='font-size: 10pt; font-weight: bold;' cellspacing='0'>
			<tr>
				<td width='100px' align='center'>Form LPJ<br />Pengeluaran</td>
				<td align='center'>LAPORAN PERTANGGUNG JAWABAN BENDAHARA PENGELUARAN<br />Bulan " . $periode_panjang . "</td>
				<td width='100px' align='center'>Tahun Anggaran<br />" . $_GET["tahun"] . "</td>
			</tr>
		</table>
		<br />
		<table cellspacing='0' cellpadding='0' style='font-size: 9pt;'>
			<tr>
				<td width='270px'>Kementerian/Lembaga</td>
				<td width='10px'>:</td>
				<td>KEPOLISIAN NEGARA REPUBLIK INDONESIA</td>
			</tr>
			<tr>
				<td>Unit Organisasi</td>
				<td>:</td>
				<td>Kepolisian Negara Republik Indonesia</td>
			</tr>
			<tr>
				<td>Provinsi/Kab/Kota</td>
				<td>:</td>
				<td>Kota Medan</td>
			</tr>
			<tr>
				<td>Satuan Kerja</td>
				<td>:</td>
				<td>RUMKIT BHAYANGKARA MEDAN</td>
			</tr>
			<tr>
				<td>Alamat / No. Telp</td>
				<td>:</td>
				<td>JL. K.H. WAHID HASYM NO. 01</td>
			</tr>
			<tr>
				<td>No Krws & Kewenangan</td>
				<td>:</td>
				<td>Kantor Daerah</td>
			</tr>
			<tr>
				<td>Dokumen</td>
				<td>:</td>
				<td>DIPA</td>
			</tr>
			<tr>
				<td>Nomor Dokumen</td>
				<td>:</td>
				<td>" . $dipa["nilai"] . "</td>
			</tr>
			<tr>
				<td>Tanggal Dokumen</td>
				<td>:</td>
				<td>" . tanggal_indonesia_panjang($dipa["tgl_berlaku"]) . "</td>
			</tr>
			<tr>
				<td>Tahun Anggaran</td>
				<td>:</td>
				<td>" . $_GET["tahun"] . "</td>
			</tr>
			<tr>
				<td>KPPN</td>
				<td>:</td>
				<td>MEDAN I</td>
			</tr>
		</table>
		<br />
		<div style='font-weight: bold; font-size: 9pt;'>I. Keadaan Pembukuan bulan pelaporan dengan saldo akhir pada BKU sebesar Rp. " . number_format($saldo_akhir + $saldo_awal_tunai + $saldo_akhir_tunai, 0) . " dan Nomor Bukti terakhir Nomor</div>
		<table width='100%' cellspacing='0' cellpadding='1' style='font-size: 8pt;' border='1'>
			<tr style='font-weight: bold;'>
				<td width='30px' align='center'>NO.</td>
				<td align='center'>JENIS BUKU PEMBANTU</td>
				<td align='center' width='100px'>SALDO AWAL</td>
				<td align='center' width='100px'>PENAMBAHAN</td>
				<td align='center' width='100px'>PENGURANGAN</td>
				<td align='center' width='100px'>SALDO AKHIR</td>
			</tr>
			<tr style='font-size: 5pt;'>
				<td align='center'>1</td>
				<td align='center'>2</td>
				<td align='center'>3</td>
				<td align='center'>4</td>
				<td align='center'>5</td>
				<td align='center'>6</td>
			</tr>
			<tr style='font-weight: bold;'>
				<td align='center'>A.</td>
				<td align='left'>BP Kas, BPP, dan UM Perjadin</td>
				<td align='right'>" . number_format($saldo_awal_tunai + $saldo_awal, 2) . "</td>
				<td align='right'>" . number_format($total_kredit_saldo + $total_kredit_ppn + $total_kredit_pph - $saldo_awal, 2) . "</td>
				<td align='right'>" . number_format($total_debet_saldo + $total_debet_ppn + $total_debet_pph, 2) . "</td>
				<td align='right'>" . number_format(($saldo_awal_tunai + $saldo_awal) + ($total_kredit_saldo + $total_kredit_ppn + $total_kredit_pph - $saldo_awal) - ($total_debet_saldo + $total_debet_ppn + $total_debet_pph), 2) . "</td>
			</tr>
			<tr style=''>
				<td align='center' style='border-top: solid 0px; border-bottom: solid 0px;'></td>
				<td align='left' style='border-top: solid 0px; border-bottom: solid 0px;'>1. BP Kas (tunai dan bank)</td>
				<td align='right' style='border-top: solid 0px; border-bottom: solid 0px;'>" . number_format($saldo_awal_tunai + $saldo_awal, 2) . "</td>
				<td align='right' style='border-top: solid 0px; border-bottom: solid 0px;'>" . number_format($total_kredit_saldo + $total_kredit_ppn + $total_kredit_pph - $saldo_awal, 2) . "</td>
				<td align='right' style='border-top: solid 0px; border-bottom: solid 0px;'>" . number_format($total_debet_saldo + $total_debet_ppn + $total_debet_pph, 2) . "</td>
				<td align='right' style='border-top: solid 0px; border-bottom: solid 0px;'>" . number_format(($saldo_awal_tunai + $saldo_awal) + ($total_kredit_saldo + $total_kredit_ppn + $total_kredit_pph - $saldo_awal) - ($total_debet_saldo + $total_debet_ppn + $total_debet_pph), 2) . "</td>
			</tr>
			<tr style=''>
				<td align='center' style='border-top: solid 0px; border-bottom: solid 0px;'></td>
				<td align='left' style='border-top: solid 0px; border-bottom: solid 0px;'>2. BP Uang Muka/Voucher</td>
				<td align='right' style='border-top: solid 0px; border-bottom: solid 0px;'>" . number_format(0, 2) . "</td>
				<td align='right' style='border-top: solid 0px; border-bottom: solid 0px;'>" . number_format(0, 2) . "</td>
				<td align='right' style='border-top: solid 0px; border-bottom: solid 0px;'>" . number_format(0, 2) . "</td>
				<td align='right' style='border-top: solid 0px; border-bottom: solid 0px;'>" . number_format(0, 2) . "</td>
			</tr>
			<tr style=''>
				<td align='center' style='border-top: solid 0px; border-bottom: solid 0px;'></td>
				<td align='left' style='border-top: solid 0px; border-bottom: solid 0px;'>3. BP BPP (Kas pada BPP)</td>
				<td align='right' style='border-top: solid 0px; border-bottom: solid 0px;'>" . number_format(0, 2) . "</td>
				<td align='right' style='border-top: solid 0px; border-bottom: solid 0px;'>" . number_format(0, 2) . "</td>
				<td align='right' style='border-top: solid 0px; border-bottom: solid 0px;'>" . number_format(0, 2) . "</td>
				<td align='right' style='border-top: solid 0px; border-bottom: solid 0px;'>" . number_format(0, 2) . "</td>
			</tr>
			
			<tr style='font-weight: bold;'>
				<td align='center'>B.</td>
				<td align='left'>BP Selain Kas, BPP, dan UM Perjadin</td>
				<td align='right'>" . number_format($saldo_awal_tunai + $saldo_awal, 2) . "</td>
				<td align='right'>" . number_format($total_kredit_saldo + $total_kredit_ppn + $total_kredit_pph - $saldo_awal, 2) . "</td>
				<td align='right'>" . number_format($total_debet_saldo + $total_debet_ppn + $total_debet_pph, 2) . "</td>
				<td align='right'>" . number_format(($saldo_awal_tunai + $saldo_awal) + ($total_kredit_saldo + $total_kredit_ppn + $total_kredit_pph - $saldo_awal) - ($total_debet_saldo + $total_debet_ppn + $total_debet_pph), 2) . "</td>
			</tr>
			<tr style=''>
				<td align='center' style='border-top: solid 0px; border-bottom: solid 0px;'></td>
				<td align='left' style='border-top: solid 0px; border-bottom: solid 0px;'>1. BP UP *)</td>
				<td align='right' style='border-top: solid 0px; border-bottom: solid 0px;'>" . number_format(0, 2) . "</td>
				<td align='right' style='border-top: solid 0px; border-bottom: solid 0px;'>" . number_format(0, 2) . "</td>
				<td align='right' style='border-top: solid 0px; border-bottom: solid 0px;'>" . number_format(0, 2) . "</td>
				<td align='right' style='border-top: solid 0px; border-bottom: solid 0px;'>" . number_format(0, 2) . "</td>
			</tr>
			<tr style=''>
				<td align='center' style='border-top: solid 0px; border-bottom: solid 0px;'></td>
				<td align='left' style='border-top: solid 0px; border-bottom: solid 0px;'>2. BP LS-Bendahara</td>
				<td align='right' style='border-top: solid 0px; border-bottom: solid 0px;'>" . number_format($saldo_awal_tunai + $saldo_awal, 2) . "</td>
				<td align='right' style='border-top: solid 0px; border-bottom: solid 0px;'>" . number_format($dpp_masuk, 2) . "</td>
				<td align='right' style='border-top: solid 0px; border-bottom: solid 0px;'>" . number_format($dpp_keluar, 2) . "</td>
				<td align='right' style='border-top: solid 0px; border-bottom: solid 0px;'>" . number_format(($saldo_awal_tunai + $saldo_awal) + ($dpp_masuk - $dpp_keluar), 2) . "</td>
			</tr>
			<tr style=''>
				<td align='center' style='border-top: solid 0px; border-bottom: solid 0px;'></td>
				<td align='left' style='border-top: solid 0px; border-bottom: solid 0px;'>3. BP Pajak</td>
				<td align='right' style='border-top: solid 0px; border-bottom: solid 0px;'>" . number_format(0, 2) . "</td>
				<td align='right' style='border-top: solid 0px; border-bottom: solid 0px;'>" . number_format(($ppn_masuk + $pph_masuk), 2) . "</td>
				<td align='right' style='border-top: solid 0px; border-bottom: solid 0px;'>" . number_format(($ppn_keluar + $pph_keluar), 2) . "</td>
				<td align='right' style='border-top: solid 0px; border-bottom: solid 0px;'>" . number_format((($ppn_masuk + $pph_masuk) - ($ppn_keluar + $pph_keluar)), 2) . "</td>
			</tr>
			<tr style=''>
				<td align='center' style='border-top: solid 0px;'></td>
				<td align='left' style='border-top: solid 0px;'>1. BP Lain-Lain</td>
				<td align='right' style='border-top: solid 0px;'>" . number_format(0, 2) . "</td>
				<td align='right' style='border-top: solid 0px;'>" . number_format($penerimaan_lain, 2) . "</td>
				<td align='right' style='border-top: solid 0px;'>" . number_format($pengeluaran_lain, 2) . "</td>
				<td align='right' style='border-top: solid 0px;'>" . number_format(($penerimaan_lain - $pengeluaran_lain), 2) . "</td>
			</tr>
		</table>
		<div style='font-size: 7pt;'>*jumlah pengurangan pada BP UP sudah termasuk kuitansi UP yang belum di-SPM-GU-kan sebesar Rp0</div>
		<br />
		<div style='font-weight: bold; font-size: 9pt;'>II. Keadaan kas pada akhir bulan pelaporan</div>
		<table cellspacing='0' cellpadding='0' style='font-size: 9pt;' width='90%'>
			<tr>
				<td>1. Uang Tunai di brankas</td>
				<td width='120px' align='right'>Rp. " . number_format($saldo_akhir_tunai + $saldo_awal_tunai, 2) . "</td>
			</tr>
			<tr>
				<td>2. Uang di rekening bank (terlampir Daftar Rincian Kas di Rekening)</td>
				<td align='right'>Rp. " . number_format($saldo_akhir, 2) . "</td>
			</tr>
			<tr>
				<td colspan='2'><hr /></td>
			</tr>
				<td>3. Jumlah Kas</td>
				<td align='right'>Rp. " . number_format(($saldo_akhir_tunai + $saldo_awal_tunai + $saldo_akhir), 2) . "</td>
			</tr>
		</table>
		<div style='height: 3px;'></div>
		<div style='font-weight: bold; font-size: 9pt;'>III. Selisih Kas</div>
		<table cellspacing='0' cellpadding='0' style='font-size: 9pt;' width='90%'>
			<tr>
				<td>1. Saldo Akhir BP Kas (I.A 1 kolom (6))</td>
				<td width='120px' align='right'>Rp. " . number_format(($saldo_awal_tunai + $saldo_awal) + ($total_kredit_saldo + $total_kredit_ppn + $total_kredit_pph - $saldo_awal) - ($total_debet_saldo + $total_debet_ppn + $total_debet_pph), 2) . "</td>
			</tr>
			<tr>
				<td>2. Jumlah Kas (II.3)</td>
				<td align='right'>Rp. " . number_format(($saldo_akhir_tunai + $saldo_awal_tunai + $saldo_akhir), 2) . "</td>
			</tr>
			<tr>
				<td colspan='2'><hr /></td>
			</tr>
				<td>3. Selisih Kas</td>
				<td align='right'>Rp. " . number_format(
					(($saldo_awal_tunai + $saldo_awal) + ($total_kredit_saldo + $total_kredit_ppn + $total_kredit_pph - $saldo_awal) - ($total_debet_saldo + $total_debet_ppn + $total_debet_pph))
					-
					($saldo_akhir_tunai + $saldo_awal_tunai + $saldo_akhir)
					, 2
					) . "</td>
			</tr>
		</table>
		<div style='height: 3px;'></div>
		<div style='font-weight: bold; font-size: 9pt;'>IV. Hasil rekonsiliasi internal dengan UAKPA</div>
		<table cellspacing='0' cellpadding='0' style='font-size: 9pt;' width='90%'>
			<tr>
				<td>1. Saldo UP</td>
				<td width='120px' align='right'>Rp. " . number_format(0, 2) . "</td>
			</tr>
			<tr>
				<td>2. Kuitansi UP</td>
				<td align='right'>Rp. " . number_format(0, 2) . "</td>
			</tr>
			<tr>
				<td colspan='2'><hr /></td>
			</tr>
			<tr>
				<td>3. Jumlah UP</td>
				<td align='right'>Rp. " . number_format(0, 2) . "</td>
			</tr>
			<tr>
				<td>4. Saldo UP Menurut UAKPA</td>
				<td align='right'>Rp. " . number_format(0, 2) . "</td>
			</tr>
			<tr>
				<td colspan='2'><hr /></td>
			</tr>
			<tr>
				<td>5. Selisih Pembukuan UP</td>
				<td align='right'>Rp. " . number_format(0, 2) . "</td>
			</tr>
		</table>
		<div style='height: 3px;'></div>
		<div style='font-weight: bold; font-size: 9pt;'>V. Penjelasan selisih kas dan/atau selisih pembukuan (apabila ada):</div>
		<table cellspacing='0' cellpadding='0' style='font-size: 9pt;' width='90%'>
			<tr>
				<td>1. --Tidak Ada--</td>
				<td width='120px' align='right'></td>
			</tr>
			<tr>
				<td>2. --Tidak Ada--</td>
				<td align='right'></td>
			</tr>
		</table>
		
		<br />
		<br />
		<table width='100%' cellspacing='0' cellpadding='0' style='font-size: 9pt; table-layout: fixed; page-break-inside: avoid;' border='0'>
			<tr>
				<td align='center'></td>
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
	";
	
	// instantiate and use the dompdf class
	$dompdf = new Dompdf();
	$dompdf->loadHtml($cetak);
	
	// (Optional) Setup the paper size and orientation
	$dompdf->setPaper('A4', 'portrait');
	
	// Render the HTML as PDF
	$dompdf->render();
	//echo $cetak;
	
	// Output the generated PDF to Browser
	$dompdf->stream("rincian gaji.pdf", array("Attachment" => false));
	//echo $cetak
?>
