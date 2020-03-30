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
	
	// Pejabat dari konfigurasi
	$konf_kpa = get_ttd_dokumen(12, "001", $tanggal);
	$konf_kaurkeu = get_ttd_dokumen(12, "002", $tanggal);
	
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
			@page { margin: 1cm 1.5cm; }
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
		<div style='text-align: center; font-family: serif; font-size: 12pt; font-weight: bold; text-decoration: underline;'>BERITA ACARA PEMERIKSAAN KAS DAN REKONSILIASI</div>
		<br />
		Pada hari ini, " . nama_hari(tanggal_hari_terakhir($tanggal)) . " tanggal " . tanggal_indonesia_panjang(tanggal_hari_terakhir($tanggal)) . ",
		kami selaku Kuasa Pengguna Anggaran telah melakukan pemeriksaan kas Bendahara Pengeluaran dengan nomor rekening:  Terlampir ,
		dengan posisi saldo Buku Kas Umum sebesar Rp. " . number_format(($saldo_akhir + $saldo_awal_tunai + $saldo_akhir_tunai), 0) . ", - dan nomor bukti terakhir: Adapun hasil pemeriksaan kas adalah sebagai berikut:
		
		<br />
		<br />
		
		<table border='0' cellspacing='0' cellpadding='0' style='font-size: 9pt;'>
			<tr>
				<td colspan='4'>I. Hasil Pemeriksaan Pembukuan Bendahara :</td>
			</tr>
			<tr>
				<td colspan='4' style='padding-left: 30px;'>A. Saldo Kas Bendahara</td>
			</tr>
			<tr>
				<td style='padding-left: 60px;' width='300px'>1. Saldo BP Kas (Tunai dan Bank)</td>
				<td align='right' width='100px'>Rp. " . number_format(($saldo_akhir + $saldo_awal_tunai + $saldo_akhir_tunai), 2) . "</td>
				<td align='right' width='100px'></td>
				<td align='right' width='100px'></td>
			</tr>
			<tr>
				<td style='padding-left: 60px;'>2. Saldo BP BPP</td>
				<td align='right'>Rp. 0</td>
				<td align='right'></td>
				<td align='right'></td>
			</tr>
			<tr>
				<td style='padding-left: 60px;'>3. Saldo BP Uang Muka (Voucher)</td>
				<td align='right'>Rp. 0</td>
				<td align='right'></td>
				<td align='right'></td>
			</tr>
			<tr>
				<td colspan='4' style='padding-left: 60px;'><hr /></td>
			</tr>
			<tr>
				<td style='padding-left: 60px;'>4. Jumlah (A.1 + A.2 + A.3)</td>
				<td align='right'></td>
				<td align='right'>Rp. " . number_format(($saldo_akhir + $saldo_awal_tunai + $saldo_akhir_tunai), 2) . "</td>
				<td align='right'></td>
			</tr>
			
			<tr>
				<td colspan='4' style='padding-left: 60px;'>&nbsp;</td>
			</tr>
			
			<tr>
				<td colspan='4' style='padding-left: 30px;'>B. Saldo Kas tersebut pada huruf A, terdiri dari:</td>
			</tr>
			<tr>
				<td style='padding-left: 60px;'>1. Saldo BP UP</td>
				<td align='right' width='100px'>Rp. 0</td>
				<td align='right' width='100px'></td>
				<td align='right' width='100px'></td>
			</tr>
			<tr>
				<td style='padding-left: 60px;'>2. Saldo BP LS-Bendahara</td>
				<td align='right' width='100px'>Rp. " . number_format(($saldo_awal_tunai + $saldo_awal) + ($dpp_masuk - $dpp_keluar), 2) . "</td>
				<td align='right' width='100px'></td>
				<td align='right' width='100px'></td>
			</tr>
			<tr>
				<td style='padding-left: 60px;'>3. Saldo BP Pajak</td>
				<td align='right' width='100px'>Rp. " . number_format(($ppn_masuk + $pph_masuk) - ($ppn_keluar + $pph_keluar), 2) . "</td>
				<td align='right' width='100px'></td>
				<td align='right' width='100px'></td>
			</tr>
			<tr>
				<td style='padding-left: 60px;'>4. Saldo BP Lain-Lain</td>
				<td align='right' width='100px'>Rp. " . number_format(($penerimaan_lain - $pengeluaran_lain), 2) . "</td>
				<td align='right' width='100px'></td>
				<td align='right' width='100px'></td>
			</tr>
			<tr>
				<td colspan='4' style='padding-left: 60px;'><hr /></td>
			</tr>
			<tr>
				<td style='padding-left: 60px;'>5. Jumlah (B.1 + B.2 + B.3 + B.4)</td>
				<td align='right'></td>
				<td align='right'>Rp. " . number_format(($saldo_akhir + $saldo_awal_tunai + $saldo_akhir_tunai), 2) . "</td>
				<td align='right'></td>
			</tr>
			<tr>
				<td colspan='4' style='padding-left: 30px;'><hr /></td>
			</tr>
			<tr>
				<td style='padding-left: 30px;'>C. Selisih Pembukuan (A.4 - B.5)</td>
				<td align='right'></td>
				<td align='right'></td>
				<td align='right'>Rp. 0</td>
			</tr>
			
			<tr>
				<td colspan='4' style='padding-left: 60px;'>&nbsp;</td>
			</tr>
			
			<tr>
				<td colspan='4'>II. Hasil Pemeriksaan Kas :</td>
			</tr>
			<tr>
				<td colspan='4' style='padding-left: 30px;'>A. Kas Yang Dikuasai Bendahara</td>
			</tr>
			<tr>
				<td style='padding-left: 60px;'>1. Uang Tunai Di Brankas Bendahara</td>
				<td align='right' width='100px'>Rp. " . number_format($saldo_akhir_tunai + $saldo_awal_tunai, 2) . "</td>
				<td align='right' width='100px'></td>
				<td align='right' width='100px'></td>
			</tr>
			<tr>
				<td style='padding-left: 60px;'>2. Uang Di Rekening Bank Bendahara</td>
				<td align='right' width='100px'>Rp. " . number_format($saldo_akhir, 2) . "</td>
				<td align='right' width='100px'></td>
				<td align='right' width='100px'></td>
			</tr>
			<tr>
				<td colspan='4' style='padding-left: 60px;'><hr /></td>
			</tr>
			<tr>
				<td style='padding-left: 60px;'>3. Jumlah Kas (A.1 + A.2)</td>
				<td align='right'></td>
				<td align='right'>Rp. " . number_format(($saldo_akhir + $saldo_akhir_tunai + $saldo_awal_tunai), 2) . "</td>
				<td align='right'></td>
			</tr>
			
			<tr>
				<td colspan='4' style='padding-left: 30px;'><hr /></td>
			</tr>
			
			<tr>
				<td style='padding-left: 30px;'>B. Selisih Kas (I.A.1 - II.A.3)</td>
				<td align='right'></td>
				<td align='right'></td>
				<td align='right'>Rp. 0</td>
			</tr>
			
			<tr>
				<td colspan='4' style='padding-left: 30px;'><hr /></td>
			</tr>
			
			<tr>
				<td colspan='4'>III. Hasil Rekonsiliasi Internal (Bendahara dengan UAKPA) :</td>
			</tr>
			<tr>
				<td colspan='4' style='padding-left: 30px;'>A. Pembukaan UP Menurut Bendahara :</td>
			</tr>
			<tr>
				<td style='padding-left: 60px;'>1. Saldo UP</td>
				<td align='right' width='100px'>Rp. 0</td>
				<td align='right' width='100px'></td>
				<td align='right' width='100px'></td>
			</tr>
			<tr>
				<td style='padding-left: 60px;'>2. Kuitansi UP Yang Belum DI SP2D Kan</td>
				<td align='right' width='100px'>Rp. 0</td>
				<td align='right' width='100px'></td>
				<td align='right' width='100px'></td>
			</tr>
			<tr>
				<td colspan='4' style='padding-left: 60px;'><hr /></td>
			</tr>
			<tr>
				<td style='padding-left: 60px;'>3. Jumlah UP dan Kuitansi UP (A.1 + A.2)</td>
				<td align='right'></td>
				<td align='right'>Rp. 0</td>
				<td align='right'></td>
			</tr>
			<tr>
				<td colspan='2' style='padding-left: 30px;'>B. Pembukaan UP Menurut UAKPA :</td>
				<td align='right'>Rp. 0</td>
				<td align='right'></td>
			</tr>
			<tr>
				<td colspan='4' style='padding-left: 30px;'><hr /></td>
			</tr>
			<tr>
				<td colspan='3' style='padding-left: 30px;'>C. Selisih UP Pembukuan Bendahara dengan UAKPA (A.3 - B) :</td>
				<td align='right'>Rp. 0</td>
			</tr>
			
			<tr>
				<td colspan='4' style='padding-left: 30px;'><hr /></td>
			</tr>
			
			<tr>
				<td colspan='4'>IV. Penjelasan Atas Selisih :</td>
			</tr>
			<tr>
				<td colspan='4' style='padding-left: 30px;'>A. Selisih Kas (II.B) :</td>
			</tr>
			<tr>
				<td colspan='4' style='padding-left: 30px;'>B. Selisih Pembukuan UP (III.C) :</td>
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
