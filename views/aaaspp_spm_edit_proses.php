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
    
    function pilih_id_belanja(id_belanja) {
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
    		data		: "ajax=1&jenis=load_data_belanja&id=" + id_belanja,
    		success		: function(r) {
    			$("#pnl_tanggal").html(r.tanggal);
    			$("#pnl_nomor").html(r.nomor);
    			$("#pnl_jenis").html(r.jenis_belanja);
    			$("#pnl_keperluan").html(r.keterangan);
    			$("#pnl_total").html(r.total.toLocaleString());
    			$("#jenis_belanja").val(r.jenis_belanja);
    		}
    	});
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
    
    function go_save() {
    	var hasil_validasi = val_form_submit("frm_spp_spm");
    	if(hasil_validasi == true) {
    		return confirm("Anda yakin akan menyimpan data ini?");
    	} else {
    		alert("Data yang diinput belum lengkap");
    		return false;
    	}
    }
    
    $(function() {
    	pilih_id_belanja($("#id_belanja").val());
    	pilih_id_pagu($("#id_pagu").val());
    });
</script>
<div class="row">
	<ol class="breadcrumb">
		<li><a href="#">
			<em class="fa fa-home"></em>
		</a></li>
		
		<li class="active">Edit SPP dan SPM</li>
	</ol>
</div><!--/.row-->
<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">Edit SPP dan SPM</h1>
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
		<input type="text" name="ket_pagu" id="ket_pagu" readonly="readonly" class="form-control" />
	</div>
</div>
<div class="panel panel-primary">
	<div class="panel-heading">Data SPP & SPM</div>
	<div class="panel-body">
		<form method="post" action="" id="frm_spp_spm" onsubmit="return go_save();">
			<input type="hidden" class="form-required" name="id" id="id" value="{{ obj.id }}" />
			<input type="hidden" class="form-required" name="id_belanja" id="id_belanja" value="{{ obj.id_belanja }}" />
			<input type="hidden" class="form-required" name="id_pagu" id="id_pagu" value="{{ obj.id_coa_pagu }}" />
			<input type="hidden" class="form-required" name="jenis_belanja" id="jenis_belanja" value="{{ obj.jenis_belanja }}" />
			<div class="row">
				<div class="col-sm-3 form-group">
					<label class="control-label">Nomor</label>
					<input type="text" name="nomor" id="nomor" class="form-control form-required" value="{{ obj.nomor }}" />
				</div>
				<div class="col-sm-2 form-group">
					<label class="control-label">Tanggal</label>
					<input type="date" name="tanggal" id="tanggal" class="form-control form-required" value="{{ obj.tanggal|date("Y-m-d") }}" />
				</div>
			</div>
			<div class="row">
				<div class="col-sm-6 form-group">
					<label class="control-label">Sifat Pembayaran</label>
					<input type="text" name="sifat" id="sifat" class="form-control form-required" value="{{ obj.sifat_pembayaran }}" />
				</div>
				<div class="col-sm-6 form-group">
					<label class="control-label">Jenis Pembayaran</label>
					<input type="text" name="jenis" id="jenis" class="form-control form-required" value="{{ obj.jenis_pembayaran }}" />
				</div>
			</div>
			<hr />
			<button type="submit" name="save" id="save" value="Save" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
		</form>
	</div>
</div>
			
{% endblock %}
