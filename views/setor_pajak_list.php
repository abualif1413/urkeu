{% extends "master/layout.php" %}

{% block content %}
	<script type="text/javascript" charset="utf-8">
		function go_edit(id) {
			document.location.href = "setor_pajak_edit.php?id=" + id;
		}
		
		function go_cetak(id) {
			document.location.href = "setor_pajak_cetak.php?id=" + id;
		}
		
		function go_delete(id) {
			if(confirm("Anda yakin akan menghapus data ini?")) {
				document.location.href = "?hapus=1&id=" + id;
			}
		}		
	</script>
	<div class="row">
		<ol class="breadcrumb">
			<li><a href="#">
				<em class="fa fa-home"></em>
			</a></li>
			<li class="active">Daftar riwayat penyetoran pajak</li>
		</ol>
	</div><!--/.row-->
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">Daftar riwayat penyetoran pajak</h1>
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
				</div>
				<hr />
				<button type="submit" name="cari" id="cari" value="Cari" class="btn btn-primary"><i class="fa fa-search fa-lg">&nbsp;</i> Cari Data</button>
				<button type="button" name="tambah" id="tambah" value="Tambah" class="btn btn-primary pull-right" onclick="document.location.href='setor_pajak_tambah.php';"><i class="fa fa-plus fa-lg">&nbsp;</i> Tambah data penyetoran pajak</button>
			</form>
		</div>
	</div>
	<div class="panel panel-primary">
		<div class="panel-heading">Data penyetoran pajak</div>
		<div class="panel-body">
			<table width="100%" class="table table-condensed table-striped table-hover" cellspacing="0" cellpadding="0" style="font-size: 80%;">
				<thead class="bg-primary">
					<tr>
						<th width="50px"></th>
						<th width="50px"></th>
						<th width="50px"></th>
						<th width="30px">No.</th>
						<th width="100px">Tgl. Setor</th>
						<th>Uraian / Keterangan</th>
						<th width="150px">No. Referensi</th>
						<th width="100px" style="text-align: right;">PPN</th>
						<th width="100px" style="text-align: right;">PPh</th>
					</tr>
				</thead>
				<tbody>
					{% for data in data %}
						<tr>
							<td align="center"><button type="button" class="btn btn-danger btn-xs btn-block" onclick="go_delete({{ data.id }});"><i class="fa fa-trash">&nbsp;</i> Hapus</button></td>
							<td align="center"><button type="button" class="btn btn-success btn-xs btn-block" onclick="go_edit({{ data.id }});"><i class="fa fa-pencil">&nbsp;</i> Ubah</button></td>
							<td align="center"><button type="button" class="btn btn-primary btn-xs btn-block" onclick="go_cetak({{ data.id }});"><i class="fa fa-print">&nbsp;</i> Cetak</button></td>
							<td>{{ data.nomor_urut_data }}</td>
							<td>{{ data.tanggal|date("d-m-Y") }}</td>
							<td>{{ data.keterangan }}</td>
							<td>{{ data.nomor }}</td>
							<td align="right">{{ data.ppn|number_format(2, ".", ",") }}</td>
							<td align="right">{{ data.pph|number_format(2, ".", ",") }}</td>
						</tr>
					{% endfor %}
				</tbody>
			</table>
		</div>
	</div>
{% endblock %}
