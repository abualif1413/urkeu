{% extends "master/layout.php" %}

{% block content %}
	<script type="text/javascript" charset="utf-8">
		function go_edit(id) {
			document.location.href = "spp_spm_edit_proses.php?id=" + id;
		}
		
		function go_cetak(id) {
			document.location.href = "cetak_berkas_spp_spm_host.php?id=" + id;
		}
		
		function go_delete(id) {
			if(confirm("Anda yakin akan menghapus data ini?")) {
				document.location.href = "?delete=1&id=" + id;
			}
		}
		
		function go_spby(id) {
			if(confirm("Anda yakin akan membuat SPBy dari data ini?")) {
				document.location.href = "spby.php?id=" + id;
			}
		}
		
		$(function(){
			$("#scan_barcode").val("");
    		$("#scan_barcode").focus();
		});
	</script>
	<div class="row">
		<ol class="breadcrumb">
			<li><a href="#">
				<em class="fa fa-home"></em>
			</a></li>
			<li class="active">Edit SPP/SPM</li>
		</ol>
	</div><!--/.row-->
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">Edit SPP/SPM</h1>
		</div>
	</div><!--/.row-->
	<div class="panel panel-primary">
		<div class="panel-heading">Cari Data</div>
		<div class="panel-body">
			<form method="get" action="">
				<input type="text" class="form-control" name="scan_barcode" id="scan_barcode" placeholder="Klik didalam kotak ini untuk pencarian SPM melalui barcode" />
			</form>
			<hr />
			<form class="form-horizontal" action="" method="get">
				<div class="form-group">
					<label class="col-sm-1 control-label" for="penerima">Tanggal</label>
					<div class="col-sm-2">
						<input id="tgl_dari" name="tgl_dari" type="date" class="form-control" value="{{ qs.tgl_dari|date("Y-m-d") }}">
					</div>
					<div class="col-sm-2">
						<input id="tgl_sampai" name="tgl_sampai" type="date" class="form-control" value="{{ qs.tgl_sampai|date("Y-m-d") }}">
					</div>
					<label class="col-sm-1 control-label" for="penerima">Uraian</label>
					<div class="col-sm-6">
						<input id="uraian" name="uraian" type="text" class="form-control" value="{{ qs.uraian }}">
					</div>
				</div>
				<hr />
				<button type="submit" name="cari" id="cari" value="Cari" class="btn btn-primary"><i class="fa fa-search fa-lg">&nbsp;</i> Cari Data</button>
			</form>
		</div>
	</div>
	<div class="panel panel-primary">
		<div class="panel-heading">Data SPP/SPM</div>
		<div class="panel-body">
			<table width="100%" class="table table-condensed table-striped table-hover" cellspacing="0" cellpadding="0" style="font-size: 85%;">
				<thead>
					<tr class="bg-primary">
						<th width="50px"></th>
						<th width="50px"></th>
						<th width="50px"></th>
						<th width="50px"></th>
						<th width="30px">No.</th>
						<th width="100px">No. SPP/SPM</th>
						<th width="100px">No. SPBy</th>
						<th width="80px">Tanggal SPP/SPM</th>
						<th width="120px">Jenis Belanja</th>
						<th>Uraian</th>
						<th width="100px">Total</th>
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
							{% if data.id_pu_detail > 0 %}
								<td align="center"><button type="button" class="btn btn-primary btn-xs btn-block" onclick="go_spby({{ data.id }});"><i class="fa fa-money">&nbsp;</i> SPBy</button></td>
							{% else %}
								<td><strong>Belum PU</strong></td>
							{% endif %}
							<td align="right">{{ no }}</td>
							<td align="center">{{ data.nomor }}</td>
							<td align="center">{{ data.nomor_spby }}</td>
							<td align="center">{{ data.tanggal|date("d-m-Y") }}</td>
							<td align="left">{{ data.jenis_belanja }}</td>
							<td><strong>{{ data.nomor_belanja }}</strong><br />{{ data.keterangan }}</td>
							<td align="right">{{ data.total|number_format(2, ",", ".") }}</td>
						</tr>
					{% endfor %}
				</tbody>
			</table>
		</div>
	</div>
{% endblock %}
