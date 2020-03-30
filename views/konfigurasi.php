{% extends "master/layout.php" %}

{% block content %}
	<script type="text/javascript" charset="utf-8">
		$(document).ready(function() {
			myCalendar = new dhtmlXCalendarObject(["tanggal"]);
		});
		
		function go_edit(kunci, nilai, tgl_berlaku) {
			$.ajax({
				url			: "",
				type		: "get",
				dataType	: "text",
				data		: "ajax=1&jenis=go_edit&kunci=" + kunci + "&nilai=" + nilai + "&tgl_berlaku=" + tgl_berlaku,
				success		: function(r) {
					// do nothing
				}
			});
		}
	</script>
	<div class="row">
		<ol class="breadcrumb">
			<li><a href="#">
				<em class="fa fa-home"></em>
			</a></li>
			<li class="active">Konfigurasi</li>
		</ol>
	</div><!--/.row-->
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">Konfigurasi</h1>
		</div>
	</div><!--/.row-->
	<div class="panel panel-primary">
		<div class="panel-heading">Konfigurasi Data</div>
		<div class="panel-body">
			<table class="table table-condensed table-hover">
				{% for config in config %}
					{% if config.jenis == 1 %}
						<tr>
							<td colspan="2" style="height: 50px;">&nbsp;</td>
						</tr>
						<tr>
							<td colspan="2"><strong>{{ config.keterangan }}</strong><button class="btn btn-xs btn-primary pull-right">Tambah</button></td>
						</tr>
					{% else %}
						<tr>
							<td>
								<input type="text" class="form-control" value="{{ config.nilai }}" name="{{ config.kunci }}" name="{{ config.kunci }}" />
							</td>
							<td width="200px"><strong>Berlaku : {{ config.tgl_berlaku|date("d-m-Y") }}</strong></td>
						</tr>
					{% endif %}
				{% endfor %}
			</table>
		</div>
	</div>
{% endblock %}
