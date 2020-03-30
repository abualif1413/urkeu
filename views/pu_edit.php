{% extends "master/layout.php" %}

{% block content %}
	<script type="text/javascript" charset="utf-8">
		$(document).ready(function() {
			myCalendar = new dhtmlXCalendarObject(["tanggal"]);
		});
		
		function go_edit(id) {
			document.location.href = "pu_edit_proses.php?id=" + id;
		}
		
		function go_delete(id) {
			if(confirm("Anda yakin akan menghapus data ini?")) {
				document.location.href = "?delete=1&id=" + id;
			}
		}
	</script>
	<div class="row">
		<ol class="breadcrumb">
			<li><a href="#">
				<em class="fa fa-home"></em>
			</a></li>
			<li class="active">Edit PU Bank</li>
		</ol>
	</div><!--/.row-->
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">Edit PU Bank</h1>
		</div>
	</div><!--/.row-->
	<div class="panel panel-primary">
		<div class="panel-heading">Cari Data</div>
		<div class="panel-body">
			<form class="form-horizontal" action="" method="get">
				<div class="form-group">
					<label class="col-sm-1 control-label" for="penerima">Tanggal</label>
					<div class="col-sm-2">
						<input id="tgl_dari" name="tgl_dari" type="date" class="form-control" value="{{ qs.tgl_dari|date("Y-m-d") }}">
					</div>
					<div class="col-sm-2">
						<input id="tgl_sampai" name="tgl_sampai" type="date" class="form-control" value="{{ qs.tgl_sampai|date("Y-m-d") }}">
					</div>
					<label class="col-sm-1 control-label" for="penerima">Keterangan</label>
					<div class="col-sm-6">
						<input id="uraian" name="keterangan" type="text" class="form-control" value="{{ qs.uraian }}">
					</div>
				</div>
				<hr />
				<button type="submit" name="cari" id="cari" value="Cari" class="btn btn-primary"><i class="fa fa-search fa-lg">&nbsp;</i> Cari Data</button>
			</form>
		</div>
	</div>
	<div class="panel panel-primary">
		<div class="panel-heading">Data PU</div>
		<div class="panel-body">
			<table width="100%" class="table table-condensed table-striped table-hover" cellspacing="0" cellpadding="0" style="font-size: 85%;">
				<thead>
					<tr class="bg-primary">
						<th width="50px"></th>
						<th width="50px"></th>
						<th width="30px">No.</th>
						<th width="80px">Tanggal</th>
						<th>Uraian</th>
					</tr>
				</thead>
				<tbody>
					{% set no = 0 %}
					{% for data in data %}
						{% set no = no + 1 %}
						<tr>
							{% if data.dispby == 0 %}
								<td align="center"><button type="button" class="btn btn-danger btn-xs btn-block" onclick="go_delete({{ data.id }});"><i class="fa fa-trash">&nbsp;</i> Hapus</button></td>
							{% else %}
								<td align="center"><button type="button" class="btn btn-default btn-xs btn-block disabled">Di SPBy</button></td>
							{% endif %}
							<td align="center"><button type="button" class="btn btn-success btn-xs btn-block" onclick="go_edit({{ data.id }});"><i class="fa fa-pencil">&nbsp;</i> Ubah</button></td>
							<td align="right">{{ data.nomor_urut_data }}</td>
							<td align="center">{{ data.tanggal|date("d-m-Y") }}</td>
							<td>{{ data.keterangan }}</td>
						</tr>
					{% endfor %}
				</tbody>
			</table>
		</div>
	</div>
{% endblock %}
