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
	$record = BelanjaHonorModel::GetFullRecord01($_GET["id"]);
	$rincian = BelanjaHonorModel::GetTotalList($_GET["id"]);
	
	// Pejabat dari konfigurasi
	$konf_kpa = get_ttd_dokumen(2, "001", $record["tanggal"]);
	$konf_kaurkeu = get_ttd_dokumen(2, "002", $record["tanggal"]);
	
	// Variable dari konfigurasi
	$dipa = get_variable_konfigurasi("dipa", $record["tanggal"]);
	
	$tbl = "";
	$no = 0;
	$total_jumlah = $rincian["jumlah_dibayarkan"];
	$total_ppn = 0;
	$total_pph = $rincian["pph"];
	
		$no++;
		$tbl .= "<tr>";
			$tbl .= "<td align='right'>1</td>";
			$tbl .= "<td>Pegawai</td>";
			$tbl .= "<td>" . $record["keterangan"] . "</td>";
			$tbl .= "<td></td>";
			$tbl .= "<td></td>";
			$tbl .= "<td align='right'>" . number_format($total_jumlah, 2, ".", ",") . "</td>";
			$tbl .= "<td align='right'>" . number_format($total_ppn, 2, ".", ",") . "</td>";
			$tbl .= "<td align='right'>" . number_format($total_pph, 2, ".", ",") . "</td>";
		$tbl .= "</tr>";
	
	$tbl .= "<tr style='font-weight: bold;'>";
		$tbl .= "<td align='center' colspan='5'>JUMLAH RINCIAN</td>";
		$tbl .= "<td align='right'>" . number_format($total_jumlah, 2, ".", ",") . "</td>";
		$tbl .= "<td align='right'>" . number_format($total_ppn, 2, ".", ",") . "</td>";
		$tbl .= "<td align='right'>" . number_format($total_pph, 2, ".", ",") . "</td>";
	$tbl .= "</tr>";
	$tbl .= "<tr style='font-weight: bold;'>";
		$tbl .= "<td align='center' colspan='6'>JUMLAH YANG DIBAYARKAN SELURUHNYA</td>";
		$tbl .= "<td align='right' colspan='2'>" . number_format($total_jumlah + $total_ppn + $total_pph, 2, ".", ",") . "</td>";
	$tbl .= "</tr>";
	
	$cetak = "
		<style>
			@page { margin: 3.5cm 2cm; }
		</style>
		<div style='margin-bottom: 20px; position: fixed; top: -150px; left: 0px; right: 0px; font-size: 10pt;'>
			<div style='height: 1.5cm;'></div>
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
		<div style='text-align: center; font-family: serif; font-size: 12pt; font-weight: bold; text-decoration: underline'>SURAT PERNYATAAN PERTANGGUNG JAWABAN BELANJA</div>
		<div style='text-align: center; font-family: serif; font-size: 12pt;'>Nomor : SPTJB/" . $record["na_nomor"] . "/" . $record["na_bulan"] . "/" . $record["na_tahun"] . "/RS. Bhayangkara Tk II Medan</div>
		<br /><br />
		<table cellspacing='0' cellpadding='0' style='text-transform: uppercase; font-size: 9pt; margin-left: 20px;' width='100%'>
			<tr>
				<td width='200px'>1.&nbsp;&nbsp;&nbsp;Kode Satuan Kerja</td>
				<td width='10px'>:</td>
				<td>640384</td>
			</tr>
			<tr>
				<td width='200px'>2.&nbsp;&nbsp;&nbsp;Nama Satuan Kerja</td>
				<td width='10px'>:</td>
				<td>Rumah Sakit Bhayangkara Tk II Medan</td>
			</tr>
			<tr>
				<td width='200px'>3.&nbsp;&nbsp;&nbsp;Tanggal / No. DIPA</td>
				<td width='10px'>:</td>
				<td>" . tanggal_indonesia_panjang($dipa["tgl_berlaku"]) . " / " . $dipa["nilai"] . "</td>
			</tr>
			<tr>
				<td width='200px'>4.&nbsp;&nbsp;&nbsp;Klasifikasi Anggaran</td>
				<td width='10px'>:</td>
				<td>Belanja Jasa</td>
			</tr>
		</table>
		<hr style='border: solid 1px black;'>
		<span style='font-size: 9pt;'>
			Yang bertanda tangan dibawah ini Kuasa Pengguna Anggaran (KPA) Satuan Kerja Rumah Sakit Bhayangkara Tk II Medan, Menyatakan bahwa saya
			bartanggung jawab penuh atas segala pengeluaran yang telah dibayar lunas oleh Bendahara Pengeluaran kepada yang berhak menerima dengan perincian sebagai berikut
		</span><br /><br />
		<table width='100%' border='1' cellspacing='0' cellpadding='1' style='border-collapse: collapse; font-size: 8pt;'>
			<thead>
				<tr align='center'>
					<th rowspan='2'>No.</th>
					<th rowspan='2'>PENERIMA</th>
					<th rowspan='2'>URAIAN</th>
					<th colspan='2'>BUKTI FAKTUR</th>
					<th rowspan='2'>JUMLAH</th>
					<th colspan='2'>Pajak Dipungut Bendahara<br />Pengeluaran</th>
				</tr>
				<tr align='center'>
					<th>TANGGAL</th>
					<th>NOMOR</th>
					
					<th>PPN</th>
					<th>PPh</th>
				</tr>
			</thead>
			<tbody>
				" . $tbl . "
			</tbody>
		</table>
		<br />
		<span style='font-size: 9pt;'>
			Bukti-bukti belanja tersebut diatas disimpan sesuai ketentuan yang berlaku pada Satker Rumah Sakit Bhayangkara Tk II Medan untuk kelengkapan administrasi
			dan keperluan pemeriksaan aparat pengawasan fungsional.<br />
			Demikian Surat pernyataan ini dibuat dengan sebenarnya
		</span>
		<br /><br /><br />
		<table width='100%' cellspacing='0' cellpadding='0' style='font-size: 9pt; table-layout: fixed;' border='0'>
			<tr>
				<td align='center' style='font-weight: bold;'></td>
				<td align='center'>Medan, " . tanggal_indonesia_panjang($record["tanggal"]) . "</td>
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
	
	// Output the generated PDF to Browser
	$dompdf->stream("sptjb rincian.pdf", array("Attachment" => false));
	//echo $cetak
?>