{% extends "master/layout.php" %}

{% block content %}
	<script type="text/javascript" charset="utf-8">
		$(document).ready(function() {
			myCalendar = new dhtmlXCalendarObject(["tanggal"]);
		});
		
		function go_save() {
			var tgl_spby_baru = $("#tgl_spby_baru").val();
			var diceklis = 0;
			$(".chk").each(function() {
				if($(this).prop("checked")) {
					diceklis++;
				}
			});
			if(tgl_spby_baru == "") {
				alert("Isikan tgl SPBy yang baru");
				return false;
			} else {
				if(diceklis == 0) {
					alert("Tidak ada data yang dipilih");
					return false;
				} else {
					return confirm("Anda yakin akan mengubah tanggal SPBy ini?");
				}
			}
		}
	</script>
	<div class="row">
		<ol class="breadcrumb">
			<li><a href="#">
				<em class="fa fa-home"></em>
			</a></li>
			<li class="active">Edit Tgl. SPBy</li>
		</ol>
	</div><!--/.row-->
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">Edit Tgl. SPBy</h1>
		</div>
	</div><!--/.row-->
	<div class="panel panel-primary">
		<div class="panel-heading">Cari Data</div>
		<div class="panel-body">
			<form class="form-horizontal" action="" method="get">
				<div class="form-group">
					<label class="col-sm-1 control-label" for="penerima">Tanggal SPP/SPM</label>
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
			<form method="post" action="" class="form-horizontal" onsubmit="return go_save();">
				<div class="form-group">
					<label class="col-sm-2 control-label" for="penerima">Ubah Tgl SPBy Menjadi</label>
					<div class="col-sm-2">
						<input id="tgl_spby_baru" name="tgl_spby_baru" type="date" class="form-control" value="">
					</div>
				</div>
				<hr />
				<div class="alert alert-info"><i class="fa fa-lg fa-check-square"></i> Ceklis data <b>SPP/SPM</b> dibawah untuk memilih data yang akan diubah tanggal SPBy nya</div>
				<table width="100%" class="table table-condensed table-striped table-hover" cellspacing="0" cellpadding="0" style="font-size: 85%;">
					<thead>
						<tr class="bg-primary">
							<th width="20px"></th>
							<th width="30px">No.</th>
							<th width="100px">No. / Tgl.<br />SPP/SPM</th>
							<th width="100px">No. / Tgl.<br />SPBy</th>
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
								<td align="center">
									<input type="checkbox" name="spby[]" value="{{ data.id }}" class="chk" />
								</td>
								<td align="right">{{ no }}</td>
								<td align="center"><b>{{ data.nomor }}</b><br />{{ data.tanggal|date("d-m-Y") }}</td>
								<td align="center"><b>{{ data.nomor_spby }}</b><br />{{ data.tgl_spby|date("d-m-Y") }}</td>
								<td align="left">{{ data.jenis_belanja }}</td>
								<td><strong>{{ data.nomor_belanja }}</strong><br />{{ data.keterangan }}</td>
								<td align="right">{{ data.total|number_format(2, ",", ".") }}</td>
							</tr>
						{% endfor %}
					</tbody>
				</table>
				<hr />
				<button type="submit" class="btn btn-primary" name="save" value="save">Ubah tanggal SPBy</button>
			</form>
		</div>
	</div>
{% endblock %}
