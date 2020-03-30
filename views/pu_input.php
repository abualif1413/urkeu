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
		
		<li class="active">Input PU (Pergeseran Uang)</li>
	</ol>
</div><!--/.row-->
<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">Input PU (Pergeseran Uang)</h1>
	</div>
</div><!--/.row-->	
<div class="panel panel-primary">
	<div class="panel-heading">Data SPP / SPM</div>
	<div class="panel-body">
		<form method="get" class="form-horizontal">
			<div class="form-group">
				<label class="control-label col-sm-1">Tanggal</label>
				<div class="col-sm-2">
					<input type="date" name="dari" id="dari" class="form-control" value="{{ qs.dari }}" />
				</div>
				<div class="col-sm-2">
					<input type="date" name="sampai" id="sampai" class="form-control" value="{{ qs.sampai }}" />
				</div>
			</div>
			<hr />
			<button class="btn btn-primary"><i class="fa fa-search"></i> Cari</button>
		</form>
		<hr />
		<table class="table table-condensed table-striped" style="font-size: 85%;">
			<thead class="bg-warning">
				<tr>
					<th width="30px" class="text-right">No.</th>
					<th width="120px">No. SPP / SPM</th>
					<th width="120px" class="text-center">Tgl. SPP / SPM</th>
					<th>Uraian</th>
					<th width="120px" class="text-right">Nilai</th>
					<th width="50px"></th>
				</tr>
			</thead>
			<tbody>
				{% for data in data_spp_spm %}
					<tr>
						<td class="text-right">{{ data.nomor_urut_data }}</td>
						<td>{{ data.nomor }}</td>
						<td class="text-center">{{ data.tanggal|date("d-m-Y") }}</td>
						<td>{{ data.keterangan }}</td>
						<td class="text-right">{{ data.total|number_format(0, ".", ",") }}</td>
						<td>
							<button class="btn btn-xs btn-primary btn-block" onclick="push_spp_spm({{ data.id }});"><i class="fa fa-chevron-right"></i></button>
						</td>
					</tr>
				{% endfor %}
			</tbody>
		</table>
	</div>
</div>
<div class="panel panel-primary">
	<div class="panel-heading">Data PU</div>
	<div class="panel-body">
		<div class="alert alert-info"><i class="fa fa-th-list"></i> Daftar SPP/SPM yang akan di PU</div>
		<table class="table table-condensed table-striped" style="font-size: 85%;">
			<thead class="bg-warning">
				<tr>
					<th width="30px" class="text-right">No.</th>
					<th width="120px">No. SPP / SPM</th>
					<th width="120px" class="text-center">Tgl. SPP / SPM</th>
					<th>Uraian</th>
					<th width="120px" class="text-right">Nilai</th>
					<th width="50px"></th>
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
						<td>
							<button class="btn btn-xs btn-warning btn-block" onclick="pop_spp_spm({{ data.id_pu_detail }});"><i class="fa fa-trash"></i></button>
						</td>
					</tr>
				{% endfor %}
			</tbody>
		</table>
		<hr />
		<form method="post" action="" id="frm_pu" onsubmit="return go_save();">
			<input type="hidden" name="jumlah_data" id="jumlah_data" value="{{ jumlah_data }}" />
			<div class="row" style="padding: 0px 20px;">
				<div class="col-sm-2 form-group">
					<label class="control-label">Tanggal PU</label>
					<input type="date" name="tanggal" id="tanggal" class="form-control form-required" />
				</div>
				<div class="col-sm-10 form-group">
					<label class="control-label">Keterangan</label>
					<input type="text" name="keterangan" id="keterangan" class="form-control form-required" />
				</div>
			</div>
			<hr />
			<button type="submit" class="btn btn-primary" name="save" value="Save"><i class="fa fa-save"></i> Simpan</button>
		</form>
	</div>
</div>
			
{% endblock %}
