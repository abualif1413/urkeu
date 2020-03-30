<?php
	//header('Content-Type: text/csv');
	//header('Content-Disposition: attachment; filename="payroll.csv"');
	header("Cache-Control: no-cache, no-store, must-revalidate");
	header("Content-Type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=payroll_gaji.xls");
	//session_start();
	include_once "../models/autoloader.php";
	include_once "../models/terbilang.php";
	
	// Inisialisasi isi cetak
	$record = BelanjaGajiModel::GetFullRecord01($_GET["id"]);
	$rincian = BelanjaGajiModel::GetList03($_GET["id"]);
	
	$tanggal_pecah = explode(" ", tanggal_indonesia_panjang($record["tanggal"]));
	$periode = strtoupper($tanggal_pecah[1] . " " . $tanggal_pecah[2]);
	
	$tbl = "";
	$no = 0;
	$jumlah_gapok = 0;
	$jumlah_potongan1 = 0;
	$jumlah_nilai_potongan1 = 0;
	$jumlah_potongan2 = 0;
	$jumlah_nilai_potongan2 = 0;
	$jumlah_potongan3 = 0;
	$jumlah_nilai_potongan3 = 0;
	$jumlah_potongan4 = 0;
	$jumlah_nilai_potongan4 = 0;
	$jumlah_pph = 0;
	$jumlah_pengurangan = 0;
	$jumlah_dibayarkan = 0;
	
	echo "<table>";
	//$fp = fopen('php://output', 'wb');
	foreach ($rincian as $rinci) {
		if($rinci["no_rekening"] != "") {
			$jumlah_gapok += $rinci["gapok"];
			$jumlah_potongan1 += $rinci["potongan1"];
			$jumlah_nilai_potongan1 += $rinci["nilai_potongan1"];
			$jumlah_potongan2 += $rinci["potongan2"];
			$jumlah_nilai_potongan2 += $rinci["nilai_potongan2"];
			$jumlah_potongan3 += $rinci["potongan3"];
			$jumlah_nilai_potongan3 += $rinci["nilai_potongan3"];
			$jumlah_potongan4 += $rinci["potongan4"];
			$jumlah_nilai_potongan4 += $rinci["nilai_potongan4"];
			$jumlah_pph += $rinci["nilai_pph"];
			$jumlah_pengurangan += $rinci["pengurangan"];
			$jumlah_dibayarkan += $rinci["total_dibayar"];
			$no++;
			//$data_csv = array($no, $rinci["nama_pegawai"], $rinci["total_dibayar"], strtoupper($rinci["no_rekening"]), "");
			//fputcsv($fp, $data_csv, $_GET["separator"]);
			echo "<tr>";
				echo "<td>" . $no . "</td>";
				echo "<td>" . $rinci["nama_pegawai"] . "</td>";
				echo "<td>" . $rinci["total_dibayar"] . "</td>";
				echo "<td>" . strtoupper($rinci["no_rekening"]) . "</td>";
				echo "<td></td>";
			echo "</tr>";
		}
	}
	echo "</table>";
	//fclose($fp);
?>
