{% extends "master/layout.php" %}

{% block content %}
<script type="text/javascript" charset="utf-8">
	function val_save() {
		var hasil_validasi = val_form_submit("frm_spby");
		if(hasil_validasi) {
			return confirm("Anda yakin akan menyimpan data ini?");
		} else {
			alert("Data SPBy belum lengkap");
			return false;
		}
	}
	
	// Cek nomor berkas
	function cek_nomor_berkas() {
		var tanggal = $("#tanggal").val();
		//alert(tanggal);
		if(tanggal != "") {
			$.ajax({
				url			: "cek_nomor_spby.php",
				data		: "id_spp_spm={{ qs.id }}&tanggal=" + tanggal,
				type		: "get",
				dataType	: "json",
				success		: function(r) {
					$("#nomor").val(r.nomor);
				}
			});
		} else {
			$("#nomor").val("");
		}
	}
	setInterval(cek_nomor_berkas, 1000);
	// End Of : Cek Nomor Berkas
</script>
<div class="row">
	<ol class="breadcrumb">
		<li><a href="#">
			<em class="fa fa-home"></em>
		</a></li>
		
		<li class="active">Input SPBy</li>
	</ol>
</div><!--/.row-->
<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">Input SPBy</h1>
	</div>
</div><!--/.row-->	
<div class="panel panel-primary">
	<div class="panel-heading">Data SPBy</div>
	<div class="panel-body">
		<button class="btn btn-primary btn-sm" onclick="document.location.href='spp_spm_edit.php';"><i class="fa fa-chevron-left"></i> Kembali</button>
		<br />
		<br />
		<form class="form-horizontal" method="post" action="" onsubmit="return val_save();" id="frm_spby">
			<input type="hidden" name="id_spp_spm" value="{{ qs.id }}" />
			<div class="form-group">
				<label class="control-label col-sm-2">Tanggal</label>
				<div class="col-sm-2">
					<input type="date" name="tanggal" id="tanggal" class="form-control text-center form-required" value="{{ spby.tanggal|date("Y-m-d") }}" />
				</div>
				<label class="control-label col-sm-2">Setuju Lunas</label>
				<div class="col-sm-2">
					<input type="date" name="setuju_lunas" id="setuju_lunas" class="form-control text-center form-required" value="{{ spby.setuju_lunas|date("Y-m-d") }}" />
				</div>
				<label class="control-label col-sm-2">Nomor</label>
				<div class="col-sm-2">
					<input type="text" name="nomor" id="nomor" class="form-control form-required" value="{{ spby.nomor }}" />
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2">Kepada</label>
				<div class="col-sm-10">
					<input type="text" name="kepada" id="kepada" class="form-control form-required" value="{{ spby.kepada }}" />
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2">Penerima Uang</label>
				<div class="col-sm-10">
					<input type="text" name="penerima" id="penerima" class="form-control form-required" value="{{ spby.penerima }}" />
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2">Pangkat Penerima Uang</label>
				<div class="col-sm-4">
					<input type="text" name="pangkat_penerima" id="pangkat_penerima" class="form-control form-required" value="{{ spby.pangkat_penerima }}" />
				</div>
				<label class="control-label col-sm-2">NIK Penerima Uang</label>
				<div class="col-sm-4">
					<input type="text" name="nik_penerima" id="nik_penerima" class="form-control form-required" value="{{ spby.nik_penerima }}" />
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2">Sebutan NIK (NRP / NIK / NIP)</label>
				<div class="col-sm-2">
					<input type="text" name="sebutan_nik_penerima" id="sebutan_nik_penerima" class="form-control form-required" value="{{ spby.sebutan_nik_penerima }}" />
				</div>
				<label class="control-label col-sm-2">Telepon / Kontak</label>
				<div class="col-sm-6">
					<input type="text" name="telp_penerima" id="telp_penerima" class="form-control form-required" value="{{ spby.telp_penerima }}" />
				</div>
			</div>
			<hr />
			<button type="submit" class="btn btn-primary" name="save" value="Save"><i class="fa fa-save"></i> Simpan</button>
		</form>
	</div>
</div>
			
{% endblock %}
