{% extends "master/layout.php" %}

{% block content %}
<script type="text/javascript" charset="utf-8">
	function push_spp_spm(id) {
		document.location.href = "?push_spp_spm=1&id=" + id + "&dari={{ qs.dari }}&sampai={{ qs.sampai }}";
	}
	
	function pop_spp_spm(id_pu_detail) {
		document.location.href = "?pop_spp_spm=1&id_pu_detail=" + id_pu_detail + "&dari={{ qs.dari }}&sampai={{ qs.sampai }}";
	}
	
	function go_save() {
		var hasil_validasi = val_form_submit("frm_pu");
		if(hasil_validasi == true) {
			var jumlah_data = parseInt($("#jumlah_data").val());
			if(jumlah_data == 0) {
				alert("Tidak ada SPP/SPM yang dipilih");
				return false;
			} else {
				return confirm("Anda yakin akan menyimpan data PU ini?");
			}
		} else {
			alert("Tanggal dan keterangan PU harus diisi");
			return false;
		}
	}
</script>
<div class="row">
	<ol class="breadcrumb">
		<li><a href="#">
			<em class="fa fa-home"></em>
		</a></li>
		
		<li class="active">Keterangan PU (Pergeseran Uang)</li>
	</ol>
</div><!--/.row-->
<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">Keterangan PU (Pergeseran Uang)</h1>
	</div>
</div><!--/.row-->	
<div class="panel panel-primary">
	<div class="panel-heading">Data PU</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-sm-2">Tanggal</div>
			<div class="col-sm-10"><strong>: {{ pu.tanggal|date("d-m-Y") }}</strong></div>
		</div>
		<div class="row">
			<div class="col-sm-2">Keterangan</div>
			<div class="col-sm-10"><strong>: {{ pu.keterangan }}</strong></div>
		</div>
		<br />
		<div class="alert alert-info"><i class="fa fa-th-list"></i> Daftar SPP/SPM yang akan di PU</div>
		<table class="table table-condensed table-striped" style="font-size: 85%;">
			<thead class="bg-warning">
				<tr>
					<th width="30px" class="text-right">No.</th>
					<th width="120px">No. SPP / SPM</th>
					<th width="120px" class="text-center">Tgl. SPP / SPM</th>
					<th>Uraian</th>
					<th width="120px" class="text-right">Nilai</th>
				</tr>
			</thead>
			<tbody>
				{% set jumlah_data = 0 %}
				{% for data in data_spp_spm_push %}
					{% set jumlah_data = jumlah_data + 1 %}
					<tr>
						<td class="text-right">{{ data.nomor_urut_data }}</td>
						<td>{{ data.nomor }}</td>
						<td class="text-center">{{ data.tanggal|date("d-m-Y") }}</td>
						<td>{{ data.keterangan }}</td>
						<td class="text-right">{{ data.total|number_format(0, ".", ",") }}</td>
					</tr>
				{% endfor %}
			</tbody>
		</table>
	</div>
</div>
			
{% endblock %}
