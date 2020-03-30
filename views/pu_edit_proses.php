{% extends "master/layout.php" %}

{% block content %}
<script type="text/javascript" charset="utf-8">
	function push_spp_spm(id) {
		document.location.href = "?push_spp_spm=1&id=" + id + "&dari={{ qs.dari }}&sampai={{ qs.sampai }}&id_pu={{ qs.id }}";
	}
	
	function pop_spp_spm(id_pu_detail) {
		document.location.href = "?pop_spp_spm=1&id_pu_detail=" + id_pu_detail + "&dari={{ qs.dari }}&sampai={{ qs.sampai }}&id_pu={{ qs.id }}";
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
	
	function go_save_pu_lain() {
		var hasil_validasi = val_form_submit("frm_transaksi");
		if(hasil_validasi == true) {
    		return confirm("Anda yakin akan menyimpan data ini?");
    	} else {
    		alert("Data yang diinput belum lengkap");
    		return false;
    	}
	}
	
	function go_delete_pu_lain(id) {
		if(confirm("Anda yakin akan menghapus data ini?")) {
			document.location.href = "?hapus_pu_lain=1&id=" + id + "&id_pu={{ qs.id }}";
		}
	}
</script>
<div class="row">
	<ol class="breadcrumb">
		<li><a href="#">
			<em class="fa fa-home"></em>
		</a></li>
		
		<li class="active">Edit PU (Pergeseran Uang)</li>
	</ol>
</div><!--/.row-->
<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">Edit PU (Pergeseran Uang)</h1>
	</div>
</div><!--/.row-->	
<div class="panel panel-primary">
	<div class="panel-heading">Data SPP / SPM</div>
	<div class="panel-body">
		<form method="get" class="form-horizontal">
			<input type="hidden" name="id" value="{{ qs.id }}" />
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
		<ul class="nav nav-tabs">
			<li class="active"><a data-toggle="tab" href="#dari_spm">Ambil Dari SPM</a></li>
			<li><a data-toggle="tab" href="#dari_lain">PU Lain-Lain</a></li>
		</ul>
		<div class="tab-content">
			<div id="dari_spm" class="tab-pane fade in active">
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
			</div>
			<div id="dari_lain" class="tab-pane">
				<div class="panel panel-primary">
					<div class="alert alert-info">Tambah Data PU Lain-Lain</div>
					<div class="panel-body">
						<form method="post" action="" id="frm_transaksi" onsubmit="return go_save_pu_lain();">
							<input type="hidden" class="form-control" name="id" value="{{ qs.id }}" style="text-align: center;" />
							<div class="row">
								<div class="col-sm-4 form-group">
									<label class="control-label">Jumlah</label>
									<input type="text" class="form-control form-required" name="jumlah" id="jumlah" />
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12 form-group">
									<label class="control-label">Keterangan</label>
									<input type="text" class="form-control form-required" name="keterangan" id="keterangan" />
								</div>
							</div>
							<hr />
							<button class="btn btn-primary" type="submit" id="save" name="save_pu_lain" value="save_pu_lain">Simpan</button>
							<button class="btn btn-success" type="button" id="reset" onclick="go_reset();">Reset</button>
						</form>
					</div>
				</div>
				<div class="panel panel-primary">
					<div class="alert alert-info">Daftar PU Lain-Lain</div>
					<div class="panel-body">
						<table class="table table-condensed table-striped">
							<thead>
								<tr class="bg-success">
									<th width="50px"></th>
									<th width="30px" class="text-right">No.</th>
									<th width="100px" class="text-right">Jumlah</th>
									<th>Keterangan</th>
								</tr>
							</thead>
							<tbody>
								{% set no = 0 %}
								{% for data in data_pu_lain %}
									{% set no = no + 1 %}
									<tr>
										<td align="center"><button type="button" class="btn btn-danger btn-xs btn-block" onclick="go_delete_pu_lain({{ data.id }});"><i class="fa fa-trash">&nbsp;</i> Hapus</button></td>
										<td align="right">{{ no }}</td>
										<td align="right">{{ data.jumlah|number_format(2) }}</td>
										<td>{{ data.keterangan }}</td>
									</tr>
								{% endfor %}
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<hr />
		<form method="post" action="" id="frm_pu" onsubmit="return go_save();">
			<input type="hidden" name="jumlah_data" id="jumlah_data" value="{{ jumlah_data }}" />
			<input type="hidden" name="id" value="{{ qs.id }}" />
			<div class="row" style="padding: 0px 20px;">
				<div class="col-sm-2 form-group">
					<label class="control-label">Tanggal PU</label>
					<input type="date" name="tanggal" id="tanggal" class="form-control form-required" value="{{ data_pu.tanggal|date("Y-m-d") }}" />
				</div>
				<div class="col-sm-10 form-group">
					<label class="control-label">Keterangan</label>
					<input type="text" name="keterangan" id="keterangan" class="form-control form-required" value="{{ data_pu.keterangan }}" />
				</div>
			</div>
			<hr />
			<button type="submit" class="btn btn-primary" name="save" value="Save"><i class="fa fa-save"></i> Simpan</button>
		</form>
	</div>
</div>
			
{% endblock %}
