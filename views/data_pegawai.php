{% extends "master/layout.php" %}

{% block content %}
	<script type="text/javascript" charset="utf-8">
		$(document).ready(function() {
			myCalendar = new dhtmlXCalendarObject(["tanggal"]);
		});
		
		function go_edit(id) {
			document.location.href = "edit_data_pengeluaran_proses.php?id=" + id;
		}
		
		function go_cetak(id) {
			document.location.href = "cetak_berkas_host.php?id=" + id;
		}
	</script>
	<div class="row">
		<ol class="breadcrumb">
			<li><a href="#">
				<em class="fa fa-home"></em>
			</a></li>
			<li class="active">Data Pegawai</li>
		</ol>
	</div><!--/.row-->
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">Data Pegawai</h1>
		</div>
	</div><!--/.row-->
	<div class="panel panel-primary">
		<div class="panel-heading">Kelola Data Pegawai</div>
		<div class="panel-body">
			<form class="form-horizontal" action="" method="post">
				<input type="hidden" name="id" value="{{ id }}" />
				<div class="form-group">
					<label class="col-sm-2 control-label" for="penerima">Jenis Pegawai</label>
					<div class="col-sm-4">
						<select name="id_jenis_pegawai" id="id_jenis_pegawai" class="form-control">
							<option value=""></option>
							{% for jenis_pegawai in jenis_pegawai %}
								{% set selected = "" %}
								{% if jenis_pegawai.id == pegawai.id_jenis_pegawai %}
									{% set selected = "selected='selected'" %}
								{% endif %}
								<option value="{{ jenis_pegawai.id }}" {{ selected }}>{{ jenis_pegawai.jenis_pegawai }}</option>
							{% endfor %}
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="penerima">NIP / NRP / NIK</label>
					<div class="col-sm-6">
						<input id="nik" name="nik" type="text" placeholder="NIP / NRP / NIK" class="form-control" value="{{ pegawai.nik }}">
					</div>
					<label class="col-sm-2 control-label" for="penerima">Gaji Pokok</label>
					<div class="col-sm-2">
						<input id="gapok" name="gapok" type="text" placeholder="Kode Gapok" class="form-control" value="{{ pegawai.gapok }}">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="penerima">Nama Pegawai</label>
					<div class="col-sm-10">
						<input id="nama_pegawai" name="nama_pegawai" type="text" placeholder="Nama Pegawai" class="form-control" value="{{ pegawai.nama_pegawai }}">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="penerima">Golongan</label>
					<div class="col-sm-3">
						<select id="id_golongan" name="id_golongan" class="form-control">
							<option value=""></option>
							{% for golongan in golongan %}
								{% set selected = "" %}
								{% if golongan.id == pegawai.id_golongan %}
									{% set selected = "selected='selected'" %}
								{% endif %}
								<option value="{{ golongan.id }}" {{ selected }}>{{ golongan.golongan }}</option>
							{% endfor %}
						</select>
					</div>
					<label class="col-sm-2 control-label" for="penerima">Pangkat</label>
					<div class="col-sm-5">
						<select id="id_pangkat" name="id_pangkat" class="form-control">
							<option value=""></option>
							{% for pangkat in pangkat %}
								{% set selected = "" %}
								{% if pangkat.id == pegawai.id_pangkat %}
									{% set selected = "selected='selected'" %}
								{% endif %}
								<option value="{{ pangkat.id }}" {{ selected }}>{{ pangkat.pangkat }}</option>
							{% endfor %}
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="penerima">No. Rekening</label>
					<div class="col-sm-3">
						<input id="no_rekening" name="no_rekening" type="text" placeholder="No. Rekening" class="form-control" value="{{ pegawai.no_rekening }}">
					</div>
					<label class="col-sm-2 control-label" for="penerima">A.N. Rekening</label>
					<div class="col-sm-5">
						<input id="nama_rekening" name="nama_rekening" type="text" placeholder="A.N. Rekening" class="form-control" value="{{ pegawai.nama_rekening }}">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="penerima">NPWP</label>
					<div class="col-sm-5">
						<input id="npwp" name="npwp" type="text" placeholder="NPWP" class="form-control" value="{{ pegawai.npwp }}">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="penerima">Jabatan</label>
					<div class="col-sm-10">
						<input id="jabatan" name="jabatan" type="text" placeholder="Jabatan" class="form-control" value="{{ pegawai.jabatan }}">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="penerima">Jenis Kelamin</label>
					<div class="col-sm-2">
						<select name="jenis_kelamin" id="jenis_kelamin" class="form-control">
							<option value=""></option>
							{% for jenis_kelamin in jenis_kelamin %}
								{% set selected = "" %}
								{% if jenis_kelamin.kode == pegawai.jenis_kelamin %}
									{% set selected = "selected='selected'" %}
								{% endif %}
								<option value="{{ jenis_kelamin.kode }}" {{ selected }}>{{ jenis_kelamin.jenkel }}</option>
							{% endfor %}
						</select>
					</div>
					<label class="col-sm-2 control-label" for="penerima">Tempat / Tgl. Lahir</label>
					<div class="col-sm-4">
						<input id="tempat_lahir" name="tempat_lahir" type="text" placeholder="Tempat Lahir" class="form-control" value="{{ pegawai.tempat_lahir }}">
					</div>
					<div class="col-sm-2">
						<input id="tgl_lahir" name="tgl_lahir" type="date" placeholder="" class="form-control" value="{{ pegawai.tgl_lahir|date("Y-m-d") }}">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="penerima">Agama</label>
					<div class="col-sm-3">
						<select name="id_agama" id="id_agama" class="form-control">
							<option value=""></option>
							{% for agama in agama %}
								{% set selected = "" %}
								{% if agama.id == pegawai.id_agama %}
									{% set selected = "selected='selected'" %}
								{% endif %}
								<option value="{{ agama.id }}" {{ selected }}>{{ agama.agama }}</option>
							{% endfor %}
						</select>
					</div>
					<label class="col-sm-2 control-label" for="penerima">Pendidikan</label>
					<div class="col-sm-5">
						<input id="pendidikan" name="pendidikan" type="text" placeholder="Pendidikan" class="form-control" value="{{ pegawai.pendidikan }}">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="penerima">Alamat</label>
					<div class="col-sm-5">
						<input id="alamat" name="alamat" type="text" placeholder="Alamat" class="form-control" value="{{ pegawai.alamat }}">
					</div>
					<label class="col-sm-2 control-label" for="penerima">Kode Pos</label>
					<div class="col-sm-3">
						<input id="kode_pos" name="kode_pos" type="text" placeholder="Kode Pos" class="form-control" value="{{ pegawai.kode_pos }}">
					</div>
				</div>
				<hr />
				<button type="submit" name="save" id="save" value="Save" class="btn btn-primary"><i class="fa fa-save fa-lg">&nbsp;</i> Simpan Data Pegawai</button>
			</form>
		</div>
	</div>
{% endblock %}
