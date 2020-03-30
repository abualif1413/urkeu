{% extends "master/layout.php" %}

{% block content %}
	<script type="text/javascript" charset="utf-8">
		$(document).ready(function() {
			myCalendar = new dhtmlXCalendarObject(["tanggal"]);
		});
		
		function go_edit(id) {
			document.location.href = "edit_data_pengeluaran_proses.php?id=" + id + "&pd={{ pd }}";
		}
		
		function go_cetak(id) {
			document.location.href = "cetak_berkas_host.php?id=" + id + "&pd={{ pd }}";
		}
		
		function go_delete(id) {
			if(confirm("Anda yakin akan menghapus data ini?")) {
				document.location.href = "?delete=1&id=" + id + "&pd={{ pd }}";
			}
		}
	</script>
	<div class="row">
		<ol class="breadcrumb">
			<li><a href="#">
				<em class="fa fa-home"></em>
			</a></li>
			{% if pd == "" %}
				<li class="active">Edit Belanja Barang</li>
			{% else %}
				<li class="active">Edit Belanja Perjalanan Dinas</li>
			{% endif %}
		</ol>
	</div><!--/.row-->
	<div class="row">
		<div class="col-lg-12">
			{% if pd == "" %}
				<h1 class="page-header">Edit Belanja Barang</h1>
			{% else %}
				<h1 class="page-header">Edit Belanja Perjalanan Dinas</h1>
			{% endif %}
		</div>
	</div><!--/.row-->
	<div class="panel panel-primary">
		<div class="panel-heading">Cari Data</div>
		<div class="panel-body">
			<form class="form-horizontal" action="" method="get">
				<input type="hidden" name="pd" value="{{ pd }}" />
				<div class="form-group">
					<label class="col-sm-1 control-label" for="penerima">Tanggal</label>
					<div class="col-sm-2">
						<input id="tgl_dari" name="tgl_dari" type="date" class="form-control" value="{{ tgl_dari|date("Y-m-d") }}">
					</div>
					<div class="col-sm-2">
						<input id="tgl_sampai" name="tgl_sampai" type="date" class="form-control" value="{{ tgl_sampai|date("Y-m-d") }}">
					</div>
					<label class="col-sm-1 control-label" for="penerima">Uraian</label>
					<div class="col-sm-6">
						<input id="uraian" name="uraian" type="text" class="form-control" value="{{ uraian }}">
					</div>
				</div>
				{% if pd != 1 %}
					<div class="form-group">
						<label class="col-sm-1 control-label" for="penerima">Jenis</label>
						<div class="col-sm-11">
							<div class="checkbox">
								{% set checked = "" %}
								{% for jenis in jenis %}
									{% if jenis == "belanja barang" %}
										{% set checked = "checked='checked'" %}
									{% endif %}
								{% endfor %}
								<label>
									<input type="checkbox" name="jenis[]" value="belanja barang" {{ checked }} /> Belanja Barang
								</label>
							</div>
							<div class="checkbox">
								{% set checked = "" %}
								{% for jenis in jenis %}
									{% if jenis == "belanja pengadaan" %}
										{% set checked = "checked='checked'" %}
									{% endif %}
								{% endfor %}
								<label>
									<input type="checkbox" name="jenis[]" value="belanja pengadaan" {{ checked }} /> Belanja Pengadaan
								</label>
							</div>
							<div class="checkbox">
								{% set checked = "" %}
								{% for jenis in jenis %}
									{% if jenis == "belanja pemeliharaan" %}
										{% set checked = "checked='checked'" %}
									{% endif %}
								{% endfor %}
								<label>
									<input type="checkbox" name="jenis[]" value="belanja pemeliharaan" {{ checked }} /> Belanja Pemeliharaan
								</label>
							</div>
						</div>
					</div>
				{% endif %}
				<hr />
				<button type="submit" name="cari" id="cari" value="Cari" class="btn btn-primary"><i class="fa fa-search fa-lg">&nbsp;</i> Cari Data</button>
			</form>
		</div>
	</div>
	<div class="panel panel-primary">
		<div class="panel-heading">Data Belanja Barang</div>
		<div class="panel-body">
			<table width="100%" class="table table-condensed table-striped table-hover" cellspacing="0" cellpadding="0" style="font-size: 85%;">
				<thead>
					<tr class="bg-primary">
						<th width="50px"></th>
						<th width="50px"></th>
						<th width="50px"></th>
						<th width="30px">No.</th>
						<th width="80px">Tanggal</th>
						<th width="200px">No. Berkas</th>
						<th>Uraian</th>
						<th width="150px">Pegawai YBS</th>
					</tr>
				</thead>
				<tbody>
					{% set no = 0 %}
					{% for data in data %}
						{% set no = no + 1 %}
						<tr>
							<td align="center"><button type="button" class="btn btn-danger btn-xs btn-block" onclick="go_delete({{ data.id }});"><i class="fa fa-trash">&nbsp;</i> Hapus</button></td>
							<td align="center"><button type="button" class="btn btn-success btn-xs btn-block" onclick="go_edit({{ data.id }});"><i class="fa fa-pencil">&nbsp;</i> Ubah</button></td>
							<td align="center"><button type="button" class="btn btn-primary btn-xs btn-block" onclick="go_cetak({{ data.id }});"><i class="fa fa-print">&nbsp;</i> Cetak</button></td>
							<td align="right">{{ no }}</td>
							<td align="center">{{ data.tanggal|date("d-m-Y") }}</td>
							<td align="left">{{ data.na_nomor }}/{{ data.na_bulan }}/{{ data.na_tahun }}/{{ data.na_divisi }}</td>
							<td>{{ data.keterangan }}</td>
							<td>{{ data.nama_pegawai }}</td>
						</tr>
					{% endfor %}
				</tbody>
			</table>
		</div>
	</div>
{% endblock %}
