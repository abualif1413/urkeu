function atur_tbl_coa() {
	$(".tbl-coa .accnumber").each(function() {
		var level = parseInt($(this).attr("level"));
		var jarak_kiri = level * 20;
		var warna = ["", "#2c1c6e", "#ba4c4c", "#225c22", "#bcbc4b", "#e02626", "#000000", "rgb(25, 147, 121)", "rgb(97, 122, 155)"];
		var ukuran = [0, 120, 115, 110, 105, 100, 95, 85, 80, 75];
		$(this).css({
			"padding-left": jarak_kiri + "px",
			"font-family": "coa_sans",
			"color": warna[level],
			"font-size": ukuran[level] + "%",
			"text-transform": "uppercase"
		});
		var isi = $(this).find(".accisi");
		$(isi).css({
			"padding-left": jarak_kiri + "px"
		});
	});
}

function val_form_submit(id_form) {
	var kosong = 0;
	$("#" + id_form + " .form-required").each(function() {
		var nilai = $(this).val();
		if(nilai == "") {
			kosong++;
		}
	});
	
	if(kosong == 0)
		return true;
	else
		return false;
}