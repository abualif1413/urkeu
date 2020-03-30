{% extends "master/layout.php" %}

{% block content %}
	<script type="text/javascript" charset="utf-8">
		function go_cetak() {
			window.open("../cetak/laporan_rekening.php?bulan={{ qs.bulan }}&tahun={{ qs.tahun }}");
		}
	</script>
	<div class="row">
		<ol class="breadcrumb">
			<li><a href="#">
				<em class="fa fa-home"></em>
			</a></li>
			<li class="active">Laporan Rekening</li>
		</ol>
	</div><!--/.row-->
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">Laporan Rekening</h1>
		</div>
	</div><!--/.row-->
	<div class="panel panel-primary">
		<div class="panel-heading">Periode</div>
		<div class="panel-body">
			<form method="get" action="" class="form-horizontal">
				<div class="form-group">
					<label class="control-label col-sm-1">Bulan</label>
					<div class="col-sm-11">
						<select class="form-control" name="bulan" id="bulan" pilihan="0">
							<option value="0">- Pilih Bulan -</option>
							{% for bln_k, bln_v in bulan %}
								{% set selected = "" %}
								{% if qs.bulan == bln_k %}
									{% set selected = "selected='selected'" %}
								{% endif %}
								<option value="{{ bln_k }}" {{ selected }}>{{ bln_v }}</option>
							{% endfor %}
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-1">Tahun</label>
					<div class="col-sm-2">
						<input type="text" name="tahun" id="tahun" class="form-control" value="{{ qs.tahun }}" />
					</div>
				</div>
				<hr />
				<button class="btn btn-primary"><i class="fa fa-search"></i> Tampilkan</button>
			</form>
		</div>
	</div>
	{% if qs.bulan != "" and qs.tahun != "" %}
		<form method="post" action="">
			<input type="hidden" name="periode" value="{{ periode }}" />
			<div class="panel panel-primary">
				<div class="panel-heading">Daftar Rekening</div>
				<div class="panel-body">
					<table class="table table-striped table-condensed">
						<thead class="bg-success">
							<tr>
								<th width="30px">No.</th>
								<th width="200px">No. Rekening</th>
								<th>Atas Nama</th>
								<th width="200px">Cabang</th>
								<th width="200px">Jumlah Saldo</th>
							</tr>
						</thead>
						<tbody>
							{% set no = 0 %}
							{% for rek in rekening %}
								{% set no = no + 1 %}
								<tr>
									<td align="center">{{ no }}</td>
									<td>{{ rek.no_rekening }}</td>
									<td>{{ rek.an_rekening }}</td>
									<td>{{ rek.cabang_bank }}</td>
									{% if rek.jenis != "pengeluaran" %}
										<td>
											<input type="text" class="form-control" value="{{ rek.saldo }}" name="saldo_{{ rek.id }}" />
										</td>
									{% else %}
										<td>
											<input type="text" class="form-control" disabled="disabled" value="{{ rek.saldo }}" name="saldo_{{ rek.id }}" />
										</td>
									{% endif %}
								</tr>
							{% endfor %}
						</tbody>
					</table>
					<hr />
					<button class="btn btn-primary" type="submit" name="save" value="Save"><i class="fa fa-save"></i> Simpan</button>
					<button class="btn btn-primary" type="button" onclick="go_cetak();"><i class="fa fa-print"></i> Cetak Laporan Rekening</button>
				</div>
			</div>
		</form>
	{% endif %}
{% endblock %}
