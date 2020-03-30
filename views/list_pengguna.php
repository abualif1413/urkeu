{% extends "master/layout.php" %}

{% block content %}
	<script type="text/javascript" charset="utf-8">
		$(document).ready(function() {
			myCalendar = new dhtmlXCalendarObject(["tanggal"]);
		});
		
		function go_edit(id) {
			document.location.href = "data_pengguna.php?id=" + id;
		}
		
		function go_cetak(id) {
			document.location.href = "cetak_berkas_belanja_honor_host.php?id=" + id;
		}
	</script>
	<div class="row">
		<ol class="breadcrumb">
			<li><a href="#">
				<em class="fa fa-home"></em>
			</a></li>
			<li class="active">Edit Pengguna</li>
		</ol>
	</div><!--/.row-->
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">Edit Pengguna</h1>
		</div>
	</div><!--/.row-->
	<div class="panel panel-primary">
		<div class="panel-heading">Cari Data</div>
		<div class="panel-body">
			<form class="form-horizontal" action="" method="get">
				<div class="form-group">
					<div class="col-sm-12">
						<input id="src" name="src" type="text" class="form-control" value="{{ src }}">
					</div>
				</div>
				<hr />
				<button type="submit" name="cari" id="cari" value="Cari" class="btn btn-primary"><i class="fa fa-search fa-lg">&nbsp;</i> Cari Data</button>
			</form>
		</div>
	</div>
	<div class="panel panel-primary">
		<div class="panel-heading">Data Pengguna</div>
		<div class="panel-body">
			<a class="btn btn-primary" href="data_pengguna.php">Tambah Data Pengguna</a>
			<hr />
			<table width="100%" class="table table-condensed table-striped table-hover" cellspacing="0" cellpadding="0">
				<thead>
					<tr class="bg-success">
						<th width="50px"></th>
						<th width="50px"></th>
						<th width="30px">No.</th>
						<th width="300px">Username</th>
						<th>Nama</th>
					</tr>
				</thead>
				<tbody>
					{% set no = 0 %}
					{% for data in pengguna %}
						{% set no = no + 1 %}
						<tr>
							<td align="center"><button type="button" class="btn btn-danger btn-xs btn-block" onclick="go_delete({{ data.id }});"><i class="fa fa-trash">&nbsp;</i> Hapus</button></td>
							<td align="center"><button type="button" class="btn btn-success btn-xs btn-block" onclick="go_edit({{ data.id }});"><i class="fa fa-pencil">&nbsp;</i> Ubah</button></td>
							<td>{{ no }}</td>
							<td>{{ data.username }}</td>
							<td>{{ data.nama }}</td>
						</tr>
					{% endfor %}
				</tbody>
			</table>
		</div>
	</div>
{% endblock %}
