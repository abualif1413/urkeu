{% extends "master/layout.php" %}

{% block content %}
<script type="text/javascript" charset="utf-8">
	function go_save() {
		var hasil_validasi = val_form_submit("frm_transaksi");
		if(hasil_validasi == true) {
    		return confirm("Anda yakin akan menyimpan data ini?");
    	} else {
    		alert("Data yang diinput belum lengkap");
    		return false;
    	}
	}
	
	function go_edit(id) {
		$.ajax({
			url			: "",
			type		: "get",
			dataType	: "json",
			data		: "ajax=1&jenis=go_edit&id=" + id,
			success		: function(r) {
				$("#id").val(r.id);
				$("#tanggal").val(r.tanggal);
				$("#jumlah").val(r.jumlah);
				$("#keterangan").val(r.keterangan);
				$("#save").val("Update");
				$("#save").html("Ubah");
				document.body.scrollTop = 0; // For Safari
  				document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
			}
		});
	}
	
	function go_delete(id) {
		if(confirm("Anda yakin akan menghapus data ini?")) {
			var tgl_dari = "{{ tgl_dari }}";
			var tgl_sampai = "{{ tgl_sampai }}";
			var keterangan = "{{ keterangan }}";
			document.location.href = "?hapus=1&id=" + id + "&tgl_dari=" + tgl_dari + "&tgl_sampai=" + tgl_sampai + "&keterangan=" + keterangan;
		}
	}
	
	function go_reset() {
		$("#id").val("");
		$("#tanggal").val("");
		$("#jumlah").val("");
		$("#keterangan").val("");
		$("#save").val("Save");
		$("#save").html("Simpan");
	}
</script>
<div class="row">
	<ol class="breadcrumb">
		<li><a href="#">
			<em class="fa fa-home"></em>
		</a></li>
		
		<li class="active">PU Lain-Lain</li>
	</ol>
</div><!--/.row-->
<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">PU Lain-Lain</h1>
	</div>
</div><!--/.row-->	
<div class="panel panel-primary">
	<div class="panel-heading">Data PU Lain-Lain</div>
	<div class="panel-body">
		<form method="post" action="" id="frm_transaksi" onsubmit="return go_save();">
			<input type="hidden" class="form-control" name="id" id="id" style="text-align: center;" />
			<div class="row">
				<div class="col-sm-3 form-group">
					<label class="control-label">Tanggal</label>
					<input type="date" class="form-control form-required" name="tanggal" id="tanggal" style="text-align: center;" />
				</div>
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
			<button class="btn btn-primary" type="submit" id="save" name="save" value="Save">Simpan</button>
			<button class="btn btn-success" type="button" id="reset" onclick="go_reset();">Reset</button>
		</form>
	</div>
</div>
<div class="panel panel-primary">
	<div class="panel-heading">Cari Data</div>
	<div class="panel-body">
		<form class="form-horizontal" action="" method="get">
			<div class="form-group">
				<label class="col-sm-1 control-label" for="penerima">Tanggal</label>
				<div class="col-sm-2">
					<input id="tgl_dari" name="tgl_dari" type="date" class="form-control" value="{{ tgl_dari|date("Y-m-d") }}">
				</div>
				<div class="col-sm-2">
					<input id="tgl_sampai" name="tgl_sampai" type="date" class="form-control" value="{{ tgl_sampai|date("Y-m-d") }}">
				</div>
				<label class="col-sm-1 control-label" for="penerima">Keterangan</label>
				<div class="col-sm-6">
					<input id="uraian" name="keterangan" type="text" class="form-control" value="{{ keterangan }}">
				</div>
			</div>
			<hr />
			<button type="submit" name="cari" id="cari" value="Cari" class="btn btn-primary"><i class="fa fa-search fa-lg">&nbsp;</i> Cari Data</button>
		</form>
	</div>
</div>
<div class="panel panel-primary">
	<div class="panel-heading">Daftar PU Lain-Lain</div>
	<div class="panel-body">
		<table class="table table-condensed table-striped">
			<thead>
				<tr class="bg-success">
					<th width="50px"></th>
					<th width="50px"></th>
					<th width="30px" class="text-right">No.</th>
					<th width="150px" class="text-center">Tanggal</th>
					<th width="100px" class="text-right">Jumlah</th>
					<th>Keterangan</th>
				</tr>
			</thead>
			<tbody>
				{% set no = 0 %}
				{% for data in data %}
					{% set no = no + 1 %}
					<tr>
						<td align="center"><button type="button" class="btn btn-danger btn-xs btn-block" onclick="go_delete({{ data.id }});"><i class="fa fa-trash">&nbsp;</i> Hapus</button></td>
						<td align="center"><button type="button" class="btn btn-success btn-xs btn-block" onclick="go_edit({{ data.id }});"><i class="fa fa-pencil">&nbsp;</i> Ubah</button></td>
						<td align="right">{{ no }}</td>
						<td align="center">{{ data.tanggal|date("d-m-Y") }}</td>
						<td align="right">{{ data.jumlah|number_format(2) }}</td>
						<td>{{ data.keterangan }}</td>
					</tr>
				{% endfor %}
			</tbody>
		</table>
	</div>
</div>
{% endblock %}
