{% extends "master/layout.php" %}

{% block content %}
	<script type="text/javascript" charset="utf-8">
		$(document).ready(function() {
			myCalendar = new dhtmlXCalendarObject(["tanggal"]);
		});
		
		function go_cetak_nota_dinas(id) {
			window.open("../cetak/nota_dinas_gaji.php?id=" + id);
		}
		
		function go_cetak_sptjb(id) {
			window.open("../cetak/sptjb_gaji.php?id=" + id);
		}
		
		function go_cetak_sppjb_rincian(id) {
			window.open("../cetak/sppjb_rincian_gaji.php?id=" + id);
		}
		
		function go_cetak_sptjb_rincian(id) {
			window.open("../cetak/sptjb_rincian_gaji.php?id=" + id);
		}
		
		function go_cetak_daftar_normatif(id) {
			window.open("../cetak/rincian_gaji.php?id=" + id);
		}
		
		function go_cetak_payroll_gaji(id) {
			window.open("../cetak/payroll_gaji.php?id=" + id);
		}
		
		function go_cetak_payroll_gaji_excel(id) {
			window.open("../cetak/excel_payroll_gaji.php?id=" + id);
		}
		
		function go_cetak_csv(id) {
			var separator = $("#separator").val();
			if(separator != "," && separator != ";") {
				alert("Separator harus koma (,) atau titik koma (;)");
			} else {
				window.open("../cetak/csv_payroll_gaji.php?id=" + id + "&separator=" + separator);
			}
		}
	</script>
	<div class="row">
		<ol class="breadcrumb">
			<li><a href="#">
				<em class="fa fa-home"></em>
			</a></li>
			<li class="active">Cetak Berkas Belanja Gaji</li>
		</ol>
	</div><!--/.row-->
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">Cetak Berkas Belanja Gaji</h1>
		</div>
	</div><!--/.row-->
	<div class="panel panel-primary">
		<div class="panel-heading">Keterangan Data Belanja Gaji</div>
		<div class="panel-body">
			<div class="row">
				<div class="col-sm-2">Tanggal</div>
				<div class="col-sm-10"><strong>: {{ record.tanggal|date("d-m-Y") }}</strong></div>
			</div>
			<div class="row">
				<div class="col-sm-2">No. Nota Ajuan</div>
				<div class="col-sm-10"><strong>: B/NA-{{ record.na_nomor }}/{{ record.na_bulan }}/{{ record.na_tahun }}/{{ record.na_divisi }}</strong></div>
			</div>
			<div class="row">
				<div class="col-sm-2">No. SPPJB</div>
				<div class="col-sm-10"><strong>: SPPJB/{{ record.na_nomor }}/{{ record.na_bulan }}/{{ record.na_tahun }}/{{ record.na_divisi }}</strong></div>
			</div>
			<div class="row">
				<div class="col-sm-2">No. SPTJB</div>
				<div class="col-sm-10"><strong>: SPTJB/{{ record.na_nomor }}/{{ record.na_bulan }}/{{ record.na_tahun }}/RS. Bhayangkara Tk II Medan</strong></div>
			</div>
			<div class="row">
				<div class="col-sm-2">Uraian</div>
				<div class="col-sm-10"><strong>: {{ record.keterangan }}</strong></div>
			</div>
			<div class="row">
				<div class="col-sm-2">Pemohon</div>
				<div class="col-sm-10"><strong>: {{ record.pangkat }} {{ record.nama_pegawai }} NIP/NRP : {{ record.nik }}</strong></div>
			</div>
			<hr />
			<div class="row">
				<div class="col-sm-12">
					<button type="button" name="reset" id="reset" value="Reset" class="btn btn-primary" onclick="go_cetak_nota_dinas({{ id }});"><i class="fa fa-file">&nbsp;</i> Cetak Nota Ajuan</button>
					<!--<button type="button" name="reset" id="reset" value="Reset" class="btn btn-primary" onclick="go_cetak_sptjb({{ id }})"><i class="fa fa-file">&nbsp;</i> Cetak SPTJB - UP</button>-->
					<button type="button" name="reset" id="reset" value="Reset" class="btn btn-primary" onclick="go_cetak_sppjb_rincian({{ id }})"><i class="fa fa-file">&nbsp;</i> Cetak SPTJB Pengajuan</button>
					<button type="button" name="reset" id="reset" value="Reset" class="btn btn-primary" onclick="go_cetak_sptjb_rincian({{ id }})"><i class="fa fa-file">&nbsp;</i> Cetak SPTJB Rincian</button>
					<button type="button" name="reset" id="reset" value="Reset" class="btn btn-primary" onclick="go_cetak_daftar_normatif({{ id }})"><i class="fa fa-file">&nbsp;</i> Cetak Daftar Normatif</button>
				</div>
			</div>
			<hr />
			<div class="row">
				<div class="col-sm-12">
					<div class="dropdown">
						<button type="button" name="reset" id="reset" value="Reset" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
							<i class="fa fa-file">&nbsp;</i> Cetak Payroll
							<span class="caret"></span>
						</button>
						<ul class="dropdown-menu">
							<li><a href="#" onclick="go_cetak_payroll_gaji({{ id }});">Print PDF</a></li>
							<li><a href="#" onclick="go_cetak_payroll_gaji_excel({{ id }});">Download Excel</a></li>
						</ul>
					</div>
				</div>
			</div>
			<hr />
			<div class="row">
				<div class="col-sm-2">
					<div class="input-group">
						<input type="text" class="form-control" placeholder="Separator" id="separator">
						<div class="input-group-btn">
							<button class="btn btn-primary" type="button" onclick="go_cetak_csv({{ id }})"><i class="fa fa-file">&nbsp;</i> CSV</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
{% endblock %}
