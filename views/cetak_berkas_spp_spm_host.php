{% extends "master/layout.php" %}

{% block content %}
	<script type="text/javascript" charset="utf-8">
		$(document).ready(function() {
			myCalendar = new dhtmlXCalendarObject(["tanggal"]);
		});
		
		function go_cetak_spp(id) {
			window.open("../cetak/spp.php?id=" + id);
		}
		
		function go_cetak_spm(id) {
			window.open("../cetak/spm.php?id=" + id);
		}
		
		function go_cetak_spby(id) {
			window.open("../cetak/spby.php?id=" + id);
		}
	</script>
	<div class="row">
		<ol class="breadcrumb">
			<li><a href="#">
				<em class="fa fa-home"></em>
			</a></li>
			{% if pd == "" %}
				<li class="active">Cetak Berkas SPP - SPM - SPBy</li>
			{% else %}
				<li class="active">Cetak Berkas SPP - SPM - SPBy</li>
			{% endif %}
		</ol>
	</div><!--/.row-->
	<div class="row">
		<div class="col-lg-12">
			{% if pd == "" %}
				<h1 class="page-header">Cetak Berkas SPP - SPM - SPBy</h1>
			{% else %}
				<h1 class="page-header">Cetak Berkas SPP - SPM - SPBy</h1>
			{% endif %}
		</div>
	</div><!--/.row-->
	<div class="panel panel-primary">
		<div class="panel-heading">Keterangan Data SPP/SPM</div>
		<div class="panel-body">
			<div class="row">
				<div class="col-sm-2">No. SPP/SPM</div>
				<div class="col-sm-10"><strong>: {{ data.nomor }}</strong></div>
			</div>
			<div class="row">
				<div class="col-sm-2">Tgl. SPP/SPM</div>
				<div class="col-sm-10">
					<strong>: {{ data.tanggal_after_sp2d|date("d-m-Y") }}</strong>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-2">Sifat Pembayaran</div>
				<div class="col-sm-10"><strong>: {{ data.sifat_pembayaran }}</strong></div>
			</div>
			<div class="row">
				<div class="col-sm-2">Jenis Pembayaran</div>
				<div class="col-sm-10"><strong>: {{ data.jenis_pembayaran }}</strong></div>
			</div>
			<div class="row">
				<div class="col-sm-2">Tgl. Belanja</div>
				<div class="col-sm-10"><strong>: {{ data.tgl_belanja|date("d-m-Y") }}</strong></div>
			</div>
			<div class="row">
				<div class="col-sm-2">Jenis Belanja</div>
				<div class="col-sm-10"><strong>: {{ data.jenis_belanja }}</strong></div>
			</div>
			<div class="row">
				<div class="col-sm-2">No. NA Belanja</div>
				<div class="col-sm-10"><strong>: {{ data.nomor_na }}</strong></div>
			</div>
			<div class="row">
				<div class="col-sm-2">Uraian</div>
				<div class="col-sm-10"><strong>: {{ data.keterangan }}</strong></div>
			</div>
			<hr />
			<button type="button" name="reset" id="reset" value="Reset" class="btn btn-primary" onclick="go_cetak_spp({{ qs.id }});"><i class="fa fa-file">&nbsp;</i> Cetak SPP</button>
			<button type="button" name="reset" id="reset" value="Reset" class="btn btn-primary" onclick="go_cetak_spm({{ qs.id }})"><i class="fa fa-file">&nbsp;</i> Cetak SPM</button>
			{% if data.spby > 0 %}
				<button type="button" name="reset" id="reset" value="Reset" class="btn btn-primary" onclick="go_cetak_spby({{ qs.id }})"><i class="fa fa-file">&nbsp;</i> Cetak SPBy</button>
			{% endif %}
		</div>
	</div>
{% endblock %}
