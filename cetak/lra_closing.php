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
	$tanggal = $_GET["per_tgl"];
	
	// Pejabat dari konfigurasi
	$konf_kpa = get_ttd_dokumen(10, "001", $tanggal);
	$konf_kaurkeu = get_ttd_dokumen(10, "002", $tanggal);
	
	// Variable dari konfigurasi
	$dipa = get_variable_konfigurasi("dipa", $tanggal);
	
	$db_isi_pagu = new DBConnection();
	$db_isi_pagu->perintahSQL = "
		SELECT
			*
		FROM
			vw_coa_closing
		WHERE
			acc_number LIKE CONCAT((SELECT acc_number FROM vw_coa_closing WHERE id = '" . $_GET["id_header"] . "' AND tahun='" . $_GET["tahun"] . "'), '%')
			AND id <> '" . $_GET["id_header"] . "' AND tahun = '" . $_GET["tahun"] . "';
	";
	$isi_pagu = $db_isi_pagu->execute_reader();
	$isi = "";
	
	$total_pagu_revisi = 0;
	$total_pagu = 0;
	$total_realisasi_bulan_lalu = 0;
	$total_realisasi_bulan_ini = 0;
	$total_total_realisasi = 0;
	$total_sisa_dana = 0;
	foreach ($isi_pagu as &$ispg) {
		$sampai = $tanggal;
		$dari = substr($sampai, 0, 7) . "-01";
		$awal_tahun = substr($sampai, 0, 4) . "-01-01";
		
		$ispg["jumlah"] = PaguService::GetTotalAnggaranClosing($ispg["id"], $_GET["tahun"]);
		
		// Mencari realisasi
		$db_realisasi = new DBConnection();
		$db_realisasi->perintahSQL = "
			SELECT
			func_realisasi_pagu_closing(?, ?, ?, ?) AS saat_ini,
			func_realisasi_pagu_closing(?, DATE_ADD(?,INTERVAL -1 DAY), ?, ?) AS bulan_lalu
		";
		$db_realisasi->add_parameter("s", $dari);
		$db_realisasi->add_parameter("s", $sampai);
		$db_realisasi->add_parameter("i", $ispg["id"]);
		$db_realisasi->add_parameter("i", $_GET["tahun"]);
		$db_realisasi->add_parameter("s", $awal_tahun);
		$db_realisasi->add_parameter("s", $dari);
		$db_realisasi->add_parameter("i", $ispg["id"]);
		$db_realisasi->add_parameter("i", $_GET["tahun"]);
		$ds_realisasi = $db_realisasi->execute_reader();
		$db_realisasi = null;
		
		$jumlah = $ispg["jumlah"];
		$level = $ispg["lvl"];
		
		$ispg["bulan_lalu"] = $ds_realisasi[0]["bulan_lalu"];
		$ispg["persentase_bulan_lalu"] = ($jumlah > 0) ? ($ispg["bulan_lalu"] / $jumlah * 100) : 0;
		$ispg["saat_ini"] = $ds_realisasi[0]["saat_ini"];
		$ispg["persentase_saat_ini"] = ($jumlah > 0) ? ($ispg["saat_ini"] / $jumlah * 100) : 0;
		$ispg["jumlah_realisasi"] = $ds_realisasi[0]["bulan_lalu"] + $ds_realisasi[0]["saat_ini"];
		$ispg["persentase_jumlah_realisasi"] = ($jumlah > 0) ? ($ispg["jumlah_realisasi"] / $jumlah * 100) : 0;
		$ispg["sisa"] = $ispg["jumlah"] - $ispg["jumlah_realisasi"];
		$ispg["persentase_sisa"] = ($jumlah > 0) ? ($ispg["sisa"] / $jumlah * 100) : 0;
		
		$warna = array(
					"", "#2c1c6e", "#ba4c4c", "#225c22", "#bcbc4b", "#e02626", "#000000", "rgb(25, 147, 121)", "rgb(97, 122, 155)",
					"#2c1c6e", "#ba4c4c", "#225c22", "#bcbc4b", "#e02626", "#000000", "rgb(25, 147, 121)", "rgb(97, 122, 155)"
				);
		$ukuran = array(
					0, 110, 107, 105, 102, 100, 97, 95, 92, 90, 87, 85, 82, 80
				);
		$padding = ($level - 1) * 5;
		$isi .= "
			<tr style='page-break-inside: avoid; color: " . $warna[$level] . ";'>
				<td style='border-color: black;'>" . $ispg["nomor_umum"] . "</td>
				<td style='padding-left: " . $padding . "px; border-color: black;'>" . $ispg["acc_name"] . "</td>
				<td align='right' style='border-color: black;'>" . number_format($ispg["jumlah"], 2) . "</td>
				<td align='right' style='border-color: black;'>" . number_format($ispg["jumlah"], 2) . "</td>
				<td align='right' style='border-color: black;'>" . number_format($ispg["bulan_lalu"], 2) . "</td>
				<td align='right' style='border-color: black;'>" . number_format($ispg["persentase_bulan_lalu"], 2) . "</td>
				<td align='right' style='border-color: black;'>" . number_format($ispg["saat_ini"], 2) . "</td>
				<td align='right' style='border-color: black;'>" . number_format($ispg["persentase_saat_ini"], 2) . "</td>
				<td align='right' style='border-color: black;'>" . number_format($ispg["jumlah_realisasi"], 2) . "</td>
				<td align='right' style='border-color: black;'>" . number_format($ispg["persentase_jumlah_realisasi"], 2) . "</td>
				<td align='right' style='border-color: black;'>" . number_format($ispg["sisa"], 2) . "</td>
				<td align='right' style='border-color: black;'>" . number_format($ispg["persentase_sisa"], 2) . "</td>
			</tr>
		";
		
		if($ispg["lvl"] == 1) {
			$total_pagu_revisi += $ispg["jumlah"];
			$total_pagu += $ispg["jumlah"];
			$total_realisasi_bulan_lalu += $ispg["bulan_lalu"];
			$total_realisasi_bulan_ini += $ispg["saat_ini"];
			$total_total_realisasi += $ispg["jumlah_realisasi"];
			$total_sisa_dana += $ispg["sisa"];
		}
	}
	
	$total_persentase_bulan_lalu = ($total_pagu > 0) ? ($total_realisasi_bulan_lalu / $total_pagu * 100) : 0;
	$total_persentase_bulan_ini = ($total_pagu > 0) ? ($total_realisasi_bulan_ini / $total_pagu * 100) : 0;
	$total_persentase_realisasi = ($total_pagu > 0) ? ($total_total_realisasi / $total_pagu * 100) : 0;
	$total_persentase_sisa_dana = ($total_pagu > 0) ? ($total_sisa_dana / $total_pagu * 100) : 0;
	$isi .= "
		<tr style='page-break-inside: avoid; color: black; font-weight: bold;'>
			<td style='border-color: black;'></td>
			<td style='border-color: black;'>TOTAL</td>
			<td align='right' style='border-color: black;'>" . number_format($total_pagu_revisi, 2) . "</td>
			<td align='right' style='border-color: black;'>" . number_format($total_pagu, 2) . "</td>
			<td align='right' style='border-color: black;'>" . number_format($total_realisasi_bulan_lalu, 2) . "</td>
			<td align='right' style='border-color: black;'>" . number_format($total_persentase_bulan_lalu, 2) . "</td>
			<td align='right' style='border-color: black;'>" . number_format($total_realisasi_bulan_ini, 2) . "</td>
			<td align='right' style='border-color: black;'>" . number_format($total_persentase_bulan_ini, 2) . "</td>
			<td align='right' style='border-color: black;'>" . number_format($total_total_realisasi, 2) . "</td>
			<td align='right' style='border-color: black;'>" . number_format($total_persentase_realisasi, 2) . "</td>
			<td align='right' style='border-color: black;'>" . number_format($total_sisa_dana, 2) . "</td>
			<td align='right' style='border-color: black;'>" . number_format($total_persentase_sisa_dana, 2) . "</td>
		</tr>
	";
	$db_isi_pagu = null;
	
	
	$cetak = "
		<style>
			@page { margin: 0.5cm 0.5cm; }
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
		<div style='text-align: center; font-family: serif; font-size: 12pt; font-weight: bold; text-decoration: underline;'>LAPORAN REALISASI ANGGARAN TAHUN " . substr($tanggal, 0, 4) . "</div>
		<div style='text-align: center; font-family: serif; font-size: 10pt; font-weight: bold;'>Per Tanggal : " . tanggal_indonesia_panjang($tanggal) . "</div>
		
		<table style='font-size: 10pt;' cellspacing='0' cellpadding='0'>
			<tr>
				<td>Satker</td>
				<td style='padding: 0px 2px;'>:</td>
				<td>RUMKIT BHAYANGKARA TK II MEDAN</td>
			</tr>
			<tr>
				<td>Lokasi / Propinsi</td>
				<td style='padding: 0px 2px;'>:</td>
				<td>MEDAN / SUMATERA UTARA</td>
			</tr>
			<tr>
				<td>Bagian Anggaran</td>
				<td style='padding: 0px 2px;'>:</td>
				<td>(60 APBN TA. " . substr($tanggal, 0, 4) . ") RUPIAH MURNI / BLU</td>
			</tr>
			<tr>
				<td>No. SP. DIPA</td>
				<td style='padding: 0px 2px;'>:</td>
				<td>" . $dipa["nilai"] . ", " . tanggal_indonesia_panjang($dipa["tgl_berlaku"]) . "</td>
			</tr>
		</table>
		
		<br />
		
		<table width='85%' cellspacing='0' cellpadding='1' border='1' style='font-size: 9pt; page-break-inside: auto;'>
			<thead>
				<tr>
					<th rowspan='2' align='center' width='70px'>KODE</th>
					<th rowspan='2' align='center'>URAIAN</th>
					<th rowspan='2' align='center' width='100px'>PAGU REVISI</th>
					<th rowspan='2' align='center' width='100px'>PAGU</th>
					
					<th colspan='2' align='center'>REALISASI S/D BULAN LALU</th>
					
					<th colspan='2' align='center'>REALISASI S/D TGL.<br />" . tanggal_indonesia_pendek($tanggal) . "</th>
					
					<th colspan='2' align='center'>TOTAL REALISASI</th>
					
					<th colspan='2' align='center'>SISA DANA</th>
					
				</tr>
				<tr>
					<th align='center' width='100px'>TOTAL</th>
					<th align='center' width='50px'>%</th>
					
					<th align='center' width='100px'>TOTAL</th>
					<th align='center' width='50px'>%</th>
					
					<th align='center' width='100px'>TOTAL</th>
					<th align='center' width='50px'>%</th>
					
					<th align='center' width='100px'>TOTAL</th>
					<th align='center' width='50px'>%</th>
				</tr>
			</thead>
			<tbody>
				" . $isi . "
			</tbody>
		</table>
		
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
	";
	
	// instantiate and use the dompdf class
	$dompdf = new Dompdf();
	$dompdf->loadHtml($cetak);
	
	// (Optional) Setup the paper size and orientation
	$dompdf->setPaper('legal', 'landscape');
	
	// Render the HTML as PDF
	$dompdf->render();
	//echo $cetak;
	
	// Output the generated PDF to Browser
	$dompdf->stream("rincian gaji.pdf", array("Attachment" => false));
	//echo $cetak
?>
