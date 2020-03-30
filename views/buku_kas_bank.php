{% extends "master/layout.php" %}

{% block content %}
	<script type="text/javascript" charset="utf-8">
		
	</script>
	<div class="row">
		<ol class="breadcrumb">
			<li><a href="#">
				<em class="fa fa-home"></em>
			</a></li>
			<li class="active">Buku Kas Bank</li>
		</ol>
	</div><!--/.row-->
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">Buku Kas Bank</h1>
		</div>
	</div><!--/.row-->
	<div class="panel panel-primary">
		<div class="panel-heading">Periode</div>
		<div class="panel-body">
			<form method="get" action="../cetak/buku_kas_bank.php" target="_blank" class="form-horizontal">
				<div class="form-group">
					<label class="control-label col-sm-1">Bulan</label>
					<div class="col-sm-11">
						<select class="form-control" name="bulan" id="bulan" pilihan="0">
							<option value="0">- Pilih Bulan -</option>
							{% for bln_k, bln_v in bulan %}
								<option value="{{ bln_k }}">{{ bln_v }}</option>
							{% endfor %}
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-1">Tahun</label>
					<div class="col-sm-2">
						<input type="text" name="tahun" id="tahun" class="form-control" />
					</div>
				</div>
				<hr />
				<button class="btn btn-primary"><i class="fa fa-search"></i> Tampilkan</button>
			</form>
		</div>
	</div>
	
	<hr />
	
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">Berita Acara Pemeriksaan Kas dan Rekonsiliasi</h1>
		</div>
	</div><!--/.row-->
	<div class="panel panel-primary">
		<div class="panel-heading">Periode</div>
		<div class="panel-body">
			<form method="get" action="../cetak/berita_acara_pemeriksaan_kas.php" target="_blank" class="form-horizontal">
				<div class="form-group">
					<label class="control-label col-sm-1">Bulan</label>
					<div class="col-sm-11">
						<select class="form-control" name="bulan" id="bulan" pilihan="0">
							<option value="0">- Pilih Bulan -</option>
							{% for bln_k, bln_v in bulan %}
								<option value="{{ bln_k }}">{{ bln_v }}</option>
							{% endfor %}
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-1">Tahun</label>
					<div class="col-sm-2">
						<input type="text" name="tahun" id="tahun" class="form-control" />
					</div>
				</div>
				<hr />
				<button class="btn btn-primary"><i class="fa fa-search"></i> Tampilkan</button>
			</form>
		</div>
	</div>
	
	<hr />
	
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">LPJ Bendahara Pengeluaran</h1>
		</div>
	</div><!--/.row-->
	<div class="panel panel-primary">
		<div class="panel-heading">Periode</div>
		<div class="panel-body">
			<form method="get" action="../cetak/lpj_bp.php" target="_blank" class="form-horizontal">
				<div class="form-group">
					<label class="control-label col-sm-1">Bulan</label>
					<div class="col-sm-11">
						<select class="form-control" name="bulan" id="bulan" pilihan="0">
							<option value="0">- Pilih Bulan -</option>
							{% for bln_k, bln_v in bulan %}
								<option value="{{ bln_k }}">{{ bln_v }}</option>
							{% endfor %}
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-1">Tahun</label>
					<div class="col-sm-2">
						<input type="text" name="tahun" id="tahun" class="form-control" />
					</div>
				</div>
				<hr />
				<button class="btn btn-primary"><i class="fa fa-search"></i> Tampilkan</button>
			</form>
		</div>
	</div>
{% endblock %}
