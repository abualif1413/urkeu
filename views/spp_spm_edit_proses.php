{% extends "master/layout.php" %}

{% block content %}
<script type="text/javascript" charset="utf-8">
	function pilih_belanja(){
		var width = 1200;
        var height = 500;
        var top = (window.screen.height / 2) - ((height / 2) + 50);
        var left = (window.screen.width / 2) - ((width / 2) + 10);
        
        window.open("spp_spm_pop_data_belanja.php", "", "top=" + top + ",left=" + left + ",width=" + width + ",height=" + height + ",toolbar=no,menubar=no,scrollbars=yes,location=no,directories=no");
    }
    
    function pilih_pagu(){
		var width = 1200;
        var height = 500;
        var top = (window.screen.height / 2) - ((height / 2) + 50);
        var left = (window.screen.width / 2) - ((width / 2) + 10);
        
        window.open("spp_spm_pop_pagu.php", "", "top=" + top + ",left=" + left + ",width=" + width + ",height=" + height + ",toolbar=no,menubar=no,scrollbars=yes,location=no,directories=no");
    }
    
    function pilih_id_belanja(id_belanja, jenis_belanja, id_pecah_bayar=0) {
    	$("#id_belanja").val(id_belanja);
    	$("#pnl_tanggal").html("<i>Loading...</i>");
		$("#pnl_nomor").html("<i>Loading...</i>");
		$("#pnl_jenis").html("<i>Loading...</i>");
		$("#pnl_keperluan").html("<i>Loading...</i>");
		$("#pnl_total").html("<i>Loading...</i>");
		$("#jenis_belanja").val("");
    	$.ajax({
    		url			: "",
    		type		: "get",
    		dataType	: "json",
    		data		: "ajax=1&jenis=load_data_belanja&id=" + id_belanja + "&jenis_belanja=" + jenis_belanja,
    		success		: function(r) {
    			var format_total = accounting.formatMoney(r.total, "Rp ", 0);
    			
    			$("#pnl_tanggal").html(r.tanggal);
    			$("#pnl_nomor").html(r.nomor);
    			$("#pnl_jenis").html(r.jenis_belanja);
    			$("#pnl_keperluan").html(r.keterangan);
    			$("#pnl_total").html(format_total);
    			$("#jenis_belanja").val(r.jenis_belanja);
    			$("#total_belanja").val(r.total);
    			
    			if(r.pembayaran.length > 0) {
    				$("#total_belanja").val("");
    				var isi_total = "<table class='table table-striped'>";
    				isi_total += "<td colspan='2' class='bg-info'>Pembayaran telah dipecah menjadi " + r.pembayaran.length + ". Pilih yang akan dibayar</td>";
    				$.each(r.pembayaran, function(k, v) {
    					var format_total = accounting.formatMoney(v.total, "Rp ", 0);
    					var format_bruto = accounting.formatMoney(v.bruto, "Rp ", 0);
    					var format_ppn = accounting.formatMoney(v.ppn, "Rp ", 0);
    					var format_pph = accounting.formatMoney(v.pph, "Rp ", 0);
    					
    					if(v.id == id_pecah_bayar) {
    						$("#total_belanja").val(v.total);
    						isi_total += "<tr>";
	    						isi_total += "<td width='30px' align='center'>";
	    							isi_total += "<input type='radio' name='pecah_bayar' checked='checked' value='" + v.id + "' id='pecah_" + v.id + "' total='" + v.total + "' onclick='pilih_pecah_bayar(" + v.id + ", " + v.total + ")' />";
	    						isi_total += "</td>";
	    						isi_total += "<td>";
	    							isi_total += "<b>" + format_total + "</b>";
	    						isi_total += "</td>";
	    					isi_total += "</tr>";
    					} else {
    						isi_total += "<tr>";
	    						isi_total += "<td width='30px' align='center'>";
	    							isi_total += "<input type='radio' name='pecah_bayar' value='" + v.id + "' id='pecah_" + v.id + "' total='" + v.total + "' onclick='pilih_pecah_bayar(" + v.id + ", " + v.total + ")' />";
	    						isi_total += "</td>";
	    						isi_total += "<td>";
	    							isi_total += "<b>" + format_total + " (Tagihan: " + format_bruto + "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;PPN: " + format_ppn + "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;PPh: " + format_pph + ")</b>";
	    						isi_total += "</td>";
	    					isi_total += "</tr>";
    					}
    				});
    				isi_total += "</table>";
    				$("#pnl_total").html(isi_total);
    			}
    		}
    	});
    }
    
    function pilih_pecah_bayar(id_pecah_bayar, total) {
    	$("#total_belanja").val(total);
    	$("#id_pecah_bayar").val(id_pecah_bayar);
    }
    
    function pilih_id_pagu(id_pagu) {
    	$("#id_pagu").val(id_pagu);
    	$("#ket_pagu").val("Loading...");
    	$.ajax({
    		url			: "",
    		type		: "get",
    		dataType	: "json",
    		data		: "ajax=1&jenis=load_data_pagu&id=" + id_pagu,
    		success		: function(r) {
    			$("#ket_pagu").val(r.nomor_umum + " - " + r.acc_name);
    		}
    	});
    }
    
    function add_pagu() {
    	var id_pagu = $("#id_pagu").val();
    	var nilai_pagu = $("#nilai_pagu").val();
    	var id_spp_spm = $("#id").val();
    	
    	if(id_pagu != "" && $.isNumeric(nilai_pagu) && $.isNumeric(nilai_pagu) > 0) {
    		$.ajax({
	    		url			: "",
	    		type		: "get",
	    		dataType	: "text",
	    		data		: "ajax=1&jenis=add_pagu&id_pagu=" + id_pagu + "&nilai_pagu=" + nilai_pagu + "&id_spp_spm=" + id_spp_spm,
	    		success		: function(r) {
	    			load_pagu_terpilih();
	    			$("#id_pagu").val("");
	    			$("#ket_pagu").val("");
	    			$("#nilai_pagu").val("");
	    		}
	    	});
    	} else {
    		alert("Isikan data pagu dengan benar");
    	}
    }
    
    function load_pagu_terpilih() {
    	var id_spp_spm = $("#id").val();
    	$.ajax({
    		url			: "",
    		type		: "get",
    		dataType	: "json",
    		data		: "ajax=1&jenis=load_pagu_terpilih&id_spp_spm=" + id_spp_spm,
    		success		: function(r) {
    			var total = 0;
    			var isi = "";
    			$.each(r, function(index, item){
    				var format_nilai = accounting.formatMoney(item.nilai, "Rp ", 0);
    				
    				total += parseFloat(item.nilai);
    				isi += "<tr>";
    					isi += "<td align='right'>" + item.nomor_urut_data + "</td>";
    					isi += "<td>" + item.nomor_pagu + " - " + item.acc_name + "</td>";
    					isi += "<td align='right'>" + format_nilai + "</td>";
    					isi += "<td><button class='btn btn-xs btn-warning btn-block' title='Hapus data' onclick='hapus_pagu_terpilih(" + item.id + ")'><i class='fa fa-trash'></i></button></td>";
    				isi += "</tr>";
    			});
    			var format_total = accounting.formatMoney(total, "Rp ", 0);
    			
    			$("#body_pagu_terpilih").html(isi);
    			$("#total_pagu_terpilih").html(format_total);
    			$("#total_pilihan_pagu").val(total);
    		}
    	});
    }
    
    function hapus_pagu_terpilih(id) {
    	$.ajax({
    		url			: "",
    		type		: "get",
    		dataType	: "text",
    		data		: "ajax=1&jenis=hapus_pagu_terpilih&id=" + id,
    		success		: function(r) {
    			load_pagu_terpilih();
    			$("#id_pagu").val("");
    			$("#ket_pagu").val("");
    			$("#nilai_pagu").val("");
    		}
    	});
    }
    
    function go_save() {
    	var hasil_validasi = val_form_submit("frm_spp_spm");
    	var total_pagu = parseFloat($("#total_pilihan_pagu").val());
    	var total_belanja = parseFloat($("#total_belanja").val());
    	if(hasil_validasi == true) {
    		if(total_pagu != total_belanja) {
    			alert("Nilai pagu harus sama dengan total belanja");
    			return false;
    		} else {
    			return confirm("Anda yakin akan menyimpan data ini?");
    		}
    	} else {
    		alert("Data yang diinput belum lengkap");
    		return false;
    	}
    }
    
    $(function(){
    	load_pagu_terpilih();
    	pilih_id_belanja($("#id_belanja").val(), $("#jenis_belanja").val(), {{ obj.id_pecah_bayar }});
    });
</script>
<div class="row">
	<ol class="breadcrumb">
		<li><a href="#">
			<em class="fa fa-home"></em>
		</a></li>
		
		<li class="active">Ubah SPP dan SPM</li>
	</ol>
</div><!--/.row-->
<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">Ubah SPP dan SPM</h1>
	</div>
</div><!--/.row-->	
<div class="panel panel-primary">
	<div class="panel-heading">Data Belanja</div>
	<div class="panel-body">
		<button class="btn btn-primary btn-sm" onclick="pilih_belanja();">Ambil Data Belanja</button>
		<br />
		<br />
		<table class="table table-condensed" style="font-size: 87%; text-transform: uppercase;">
			<tr>
				<td width="120px" style="font-weight: bold;">Tanggal</td>
				<td width="20px">:</td>
				<td id="pnl_tanggal"></td>
			</tr>
			<tr>
				<td style="font-weight: bold;">Nomor</td>
				<td>:</td>
				<td id="pnl_nomor"></td>
			</tr>
			<tr>
				<td style="font-weight: bold;">Jenis Belanja</td>
				<td>:</td>
				<td id="pnl_jenis"></td>
			</tr>
			<tr>
				<td style="font-weight: bold;">Keperluan</td>
				<td>:</td>
				<td id="pnl_keperluan"></td>
			</tr>
			<tr>
				<td style="font-weight: bold;">Total</td>
				<td>:</td>
				<td id="pnl_total"></td>
			</tr>
		</table>
	</div>
</div>
<div class="panel panel-primary">
	<div class="panel-heading">Data Pagu</div>
	<div class="panel-body">
		<button class="btn btn-primary btn-sm" onclick="pilih_pagu();">Ambil Data Pagu</button>
		<br />
		<br />
		<form method="post" action="">
			<input type="hidden" class="form-required" name="id_pagu" id="id_pagu" />
			<div class="row">
				<div class="col-sm-9 form-group">
					<label class="control-label">Akun Pagu</label>
					<input type="text" name="ket_pagu" id="ket_pagu" readonly="readonly" class="form-control" />
				</div>
				<div class="col-sm-3 form-group">
					<label class="control-label">Nilai</label>
					<input type="text" name="nilai_pagu" id="nilai_pagu" class="form-control text-right" />
				</div>
			</div>
			<button class="btn btn-primary" type="button" onclick="add_pagu();"><i class="fa fa-plus"></i> Tambah</button>
		</form>
		<hr />
		<table class="table table-condensed table-striped">
			<thead>
				<tr class="bg-primary">
					<th width="40px" class="text-right">No</th>
					<th>Akun Pagu</th>
					<th width="130px" class="text-right">Nilai</th>
					<th width="40px"></th>
				</tr>
			</thead>
			<tbody id="body_pagu_terpilih"></tbody>
			<thead>
				<tr class="bg-primary">
					<th colspan="2">TOTAL</th>
					<th class="text-right" id="total_pagu_terpilih"></th>
					<th></th>
				</tr>
			</thead>
		</table>
	</div>
</div>
<div class="panel panel-primary">
	<div class="panel-heading">Data SPP & SPM</div>
	<div class="panel-body">
		<form method="post" action="" id="frm_spp_spm" onsubmit="return go_save();">
			<input type="hidden" class="form-required" name="id" id="id" value="{{ obj.id }}" />
			<input type="hidden" class="form-required" name="id_belanja" id="id_belanja" value="{{ obj.id_belanja }}" />
			<input type="hidden" class="form-required" name="jenis_belanja" id="jenis_belanja" value="{{ obj.jenis_belanja }}" />
			<input type="hidden" name="total_belanja" id="total_belanja" />
			<input type="hidden" name="id_pecah_bayar" id="id_pecah_bayar" />
			<input type="hidden" name="total_pilihan_pagu" id="total_pilihan_pagu" />
			<div class="row">
				<div class="col-sm-3 form-group">
					<label class="control-label">Nomor</label>
					<input type="text" name="nomor" id="nomor" class="form-control form-required" readonly="readonly" value="{{ obj.nomor }}" />
				</div>
				<div class="col-sm-2 form-group">
					<label class="control-label">Tanggal</label>
					<input type="date" name="tanggal" id="tanggal" class="form-control form-required" value="{{ obj.tanggal|date("Y-m-d") }}" />
				</div>
			</div>
			<div class="row">
				<div class="col-sm-6 form-group">
					<label class="control-label">Sifat Pembayaran</label>
					<input type="text" name="sifat" id="sifat" class="form-control form-required" readonly="readonly" value="{{ obj.sifat_pembayaran }}" />
				</div>
				<div class="col-sm-6 form-group">
					<label class="control-label">Jenis Pembayaran</label>
					<input type="text" name="jenis" id="jenis" class="form-control form-required" readonly="readonly" value="{{ obj.jenis_pembayaran }}" />
				</div>
			</div>
			<hr />
			<button type="submit" name="save" id="save" value="Save" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
		</form>
	</div>
</div>
			
{% endblock %}
