{% extends "master/layout.php" %}

{% block content %}
	<script type="text/javascript" charset="utf-8">
		function val_view() {
			var id_header = $("#id_header").val();
			var tahun = $("#tahun").val();
			if(id_header == "" || tahun == "") {
				alert("Pilih head dan tahun anggaran");
				return false
			}
			return true
		}
		
		function go_anggaran(id_coa) {
			var width = 800;
	        var height = 300;
	        var top = (window.screen.height / 2) - ((height / 2) + 50);
	        var left = (window.screen.width / 2) - ((width / 2) + 10);
	        
	        window.open("data_pagu_manajemen_anggaran_pop_input.php?id_coa=" + id_coa + "&tahun={{ qs.tahun }}", "", "top=" + top + ",left=" + left + ",width=" + width + ",height=" + height + ",toolbar=no,menubar=no,scrollbars=yes,location=no,directories=no");
		}
	</script>
	<div class="row">
		<ol class="breadcrumb">
			<li><a href="#">
				<em class="fa fa-home"></em>
			</a></li>
			<li class="active">Manajemen Anggaran</li>
		</ol>
	</div><!--/.row-->
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">Manajemen Anggaran</h1>
		</div>
	</div><!--/.row-->
	<script type="text/javascript" charset="utf-8">
	
	</script>
	<div class="panel panel-primary">
		<div class="panel-heading">Header Pagu</div>
		<div class="panel-body">
			<form method="get" action="" class="form-horizontal" onsubmit="return val_view();">
				<div class="form-group">
					<label class="control-label col-sm-1">Head</label>
					<div class="col-sm-11">
						<select class="form-control" pilihan="0" name="id_header" id="id_header">
							<option value=""></option>
							{% for coa_pagu in coa_pagu %}
								{% if coa_pagu.id == qs.id_header %}
									<option value="{{ coa_pagu.id }}" selected="selected">{{ coa_pagu.nomor_coa }} - {{ coa_pagu.acc_name }}</option>
								{% else %}
									<option value="{{ coa_pagu.id }}">{{ coa_pagu.nomor_coa }} - {{ coa_pagu.acc_name }}</option>
								{% endif %}
							{% endfor %}
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-1">Tahun</label>
					<div class="col-sm-2">
						<input type="text" name="tahun" id="tahun" class="form-control" placeholder="Thn" value="{{ qs.tahun }}" />
					</div>
				</div>
				<hr />
				<button class="btn btn-primary" type="submit" onclick=""><i class="fa fa-search"></i> OK</button>
			</form>
		</div>
	</div>
	{% if qs.id_header != "" %}
		<div class="panel panel-primary">
			<div class="panel-heading">Rincian Pagu</div>
			<div class="panel-body">
				{% if closing == 1 %}
					<div class="alert alert-warning"><strong><i class="fa fa-warning"></i> Sudah Closing!</strong> Data pagu pada tahun ini telah closing dan tidak dapat diubah lagi</div>
				{% endif %}
				<table class="table table-condensed table-striped tbl-coa">
					<thead class="bg-warning">
						<tr>
							<th>Nama Akun</th>
							<th width="80px" class="text-right">Qty</th>
							<th width="100px">Satuan</th>
							<th width="120px" class="text-right">Anggaran</th>
							<th width="50px"></th>
						</tr>
					</thead>
					<tbody>
						{% for isi in isi_pagu %}
							<tr class="accnumber" level="{{ isi.lvl }}">
								<td class="accisi">{{ isi.nomor_umum }} - {{ isi.acc_name }}</td>
								{% if isi.jlh_anak == 0 %}
									<td class="text-right">{{ isi.qty|number_format(2, ".", ",") }}</td>
									<td>{{ isi.satuan }}</td>
									<td class="text-right">{{ isi.jumlah|number_format(0, ".", ",") }},-</td>
									<td>
										{% if closing == 0 %}
											<button class="btn btn-xs btn-primary btn-block" onclick="go_anggaran({{ isi.id }});"><i class="fa fa-tags"></i></button>
										{% endif %}
									</td>
								{% else %}
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								{% endif %}
							</tr>
						{% endfor %}
					</tbody>
				</table>
			</div>
		</div>
	{% endif %}
{% endblock %}