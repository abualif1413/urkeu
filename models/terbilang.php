<?php
function bilang($x) {
    $x = abs($x);
    $angka = array("", "satu", "dua", "tiga", "empat", "lima","enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
    $result = "";
	if ($x < 12) {
        $result = " ". $angka[$x];
    } else if ($x <20) {
        $result = bilang($x - 10). " belas";
    } else if ($x <100) {
        $result = bilang($x/10)." puluh". bilang($x % 10);
    } else if ($x <200) {
        $result = " Seratus" . bilang($x - 100);
    } else if ($x <1000) {
        $result = bilang($x/100) . " ratus" . bilang($x % 100);
    } else if ($x <2000) {
        $result = " Seribu" . bilang($x - 1000);
    } else if ($x <1000000) {
        $result = bilang($x/1000) . " ribu" . bilang($x % 1000);
    } else if ($x <1000000000) {
        $result = bilang($x/1000000) . " juta" . bilang($x % 1000000);
    } else if ($x <1000000000000) {
        $result = bilang($x/1000000000) . " milyar" . bilang(fmod($x,1000000000));
    } else if ($x <1000000000000000) {
        $result = bilang($x/1000000000000) . " trilyun" . bilang(fmod($x,1000000000000));
    }      
        return $result;
}
function terbilang($x) {
    if($x<0) {
        $hasil = "minus ". trim(bilang($x));
    }elseif($x==0){
		$hasil = "nol";
	}else {
        $hasil = trim(bilang($x));
    }
    return $hasil;
}
?>