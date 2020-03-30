{% extends "master/layout.php" %}

{% block content %}
	<div class="row">
		<ol class="breadcrumb">
			<li><a href="#">
				<em class="fa fa-home"></em>
			</a></li>
			<li class="active">Edit Belanja Gaji</li>
		</ol>
	</div><!--/.row-->
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">Edit Belanja Gaji</h1>
		</div>
	</div><!--/.row-->
	<script type="text/javascript" charset="utf-8">
		$(document).ready(function() {
			myCalendar = new dhtmlXCalendarObject(["tanggal"]);
		});
		
		function go_submit() {
			var penerima = $("#penerima").val();
			var jumlah = $("#jumlah").val();
			var uraian = $("#uraian").val();
			if(penerima == "" || jumlah == "" || uraian == "") {
				alert("Input belum lengkap");
				return false;
			} else {
				return true
			}
		}
		
		function go_delete(id) {
			if(confirm("Yakin akan menghapus data?")) {
				document.location.href = "?delete=1&id=" + id;
			}
		}
		
		function get_gapok() {
			var query_string = {};
    		query_string['ajax'] = 1;
    		query_string['jenis'] = "get_gapok";
    		query_string['id'] = $("#id_pegawai").val();
    		$.ajax({
    			url			: "",
    			type		: "get",
    			dataType	: "json",
    			data		: $.param(query_string),
    			success		: function(r){
    				$("#gapok").val(r.gapok);
    			}
    		});
		}
		
		function go_edit(id) {
			var query_string = {};
    		query_string['ajax'] = 1;
    		query_string['jenis'] = "go_edit";
    		query_string['id'] = id;
    		$.ajax({
    			url			: "",
    			type		: "get",
    			dataType	: "json",
    			data		: $.param(query_string),
    			success		: function(r){
    				$("#id_detail").val(r.id);
    				$("#id_pegawai").val(r.id_pegawai).trigger('change');
    				$("#gapok").val(r.gapok);
    				$("#potongan1").val(r.potongan1);
    				$("#potongan2").val(r.potongan2);
    				$("#potongan3").val(r.potongan3);
    				$("#potongan4").val(r.potongan4);
    				$("#add").hide();
    				$("#edit").show();
    			}
    		});
		}
		
		function go_reset() {
			$("#id_detail").val("");
			$("#id_pegawai").val("").trigger('change');
			$("#gapok").val("");
			$("#potongan1").val("");
			$("#potongan2").val("");
			$("#potongan3").val("");
			$("#potongan4").val("");
			$("#add").show();
    		$("#edit").hide();
		}
	</script>
	<div class="panel panel-primary">
		<div class="panel-heading">Rincian Belanja Gaji</div>
		<div class="panel-body">
			<form class="form-horizontal" action="" method="post">
				<input type="hidden" name="id_detail" id="id_detail" />
				<input type="hidden" name="id" value="{{ id }}" />
				<div class="form-group">
					<label class="col-sm-2 control-label" for="penerima">Pegawai</label>
					<div class="col-sm-6">
						<select name="id_pegawai" id="id_pegawai" class="form-control" onchange="get_gapok();">
							<option value=""></option>
							{% for combo in combo_pegawai %}
								<optgroup label="{{ combo.jenis_pegawai }}">
								{% for rincian in combo.rincian %}
									<option value="{{ rincian.id }}">{{ rincian.nama_pegawai }} - Gapok : {{ rincian.gapok|number_format(0, ".", ",") }}</option>
								{% endfor %}
								</optgroup>
							{% endfor %}
						</select>
					</div>
					<label class="col-sm-2 control-label">Gaji Pokok</label>
					<div class="col-sm-2">
						<input type="text" name="gapok" id="gapok" placeholder="Gapok" class="form-control" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">LMBT/CPT (<1-2 JAM) SAKIT (> 3 HR) UMROH/HAJI/KAGM LAIN (/HR)</label>
					<div class="col-sm-2">
						<input type="text" name="potongan1" id="potongan1" placeholder="Potongan 1" class="form-control" />
					</div>
					<label class="col-sm-2 control-label">LMBT/CPT (> 2 JAM)</label>
					<div class="col-sm-2">
						<input type="text" name="potongan2" id="potongan2" placeholder="Potongan 2" class="form-control" />
					</div>
					<label class="col-sm-2 control-label">Cuti / Ijin</label>
					<div class="col-sm-2">
						<input type="text" name="potongan3" id="potongan3" placeholder="Potongan 3" class="form-control" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">T/K</label>
					<div class="col-sm-2">
						<input type="text" name="potongan4" id="potongan4" placeholder="Potongan 4" class="form-control" />
					</div>
				</div>
				<hr />
				<button type="submit" name="add" id="add" value="Add" class="btn btn-primary"><i class="fa fa-plus fa-lg">&nbsp;</i> Tambah Rincian</button>
				<button type="submit" name="edit" id="edit" value="Edit" class="btn btn-warning" style="display: none;"><i class="fa fa-edit fa-lg">&nbsp;</i> Ubah Rincian</button>
				<button type="button" name="reset" id="reset" value="Reset" class="btn btn-success" onclick="go_reset();"><i class="fa fa-edit fa-lg">&nbsp;</i> Reset</button>
			</form>
			<hr />
			<div class="table-responsive">
				<table width="100%" class="table table-condensed table-striped table-hover" cellspacing="0" cellpadding="0">
					<thead>
						<tr class="bg-info">
							<th width="20px"></th>
							<th width="20px"></th>
							<th width="30px">No.</th>
							<th>Nama Pegawai</th>
							<th width="100px">Gaji Pokok</th>
							<th width="100px">Potongan 1</th>
							<th width="100px">Potongan 2</th>
							<th width="100px">Potongan 3</th>
							<th width="100px">Potongan 4</th>
						</tr>
					</thead>
					<tbody>
						{% set no = 0 %}
						{% for list in list %}
							{% set no = no + 1 %}
							<tr>
								<td><button class="btn btn-xs btn-warning btn-block" onclick="go_delete({{ list.id }});"><i class="fa fa-trash"></i> Delete</button></td>
								<td><button class="btn btn-xs btn-success btn-block" onclick="go_edit({{ list.id }});"><i class="fa fa-edit"></i> Edit</button></td>
								<td align="right">{{ no }}</td>
								<td>{{ list.nama_pegawai }}</td>
								<td align="right">{{ list.gapok|number_format(0, ".", ",") }}</td>
								<td align="right">{{ list.potongan1 }} Hari</td>
								<td align="right">{{ list.potongan2 }} Hari</td>
								<td align="right">{{ list.potongan3 }} Hari</td>
								<td align="right">{{ list.potongan4 }} Hari</td>
							</tr>
						{% endfor %}
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="panel panel-primary">
		<div class="panel-heading">Detail Belanja Gaji</div>
		<div class="panel-body">
			<form class="form-horizontal" action="" method="post">
				<input type="hidden" name="id" value="{{ id }}" />
				<div class="form-group">
					<label class="col-sm-2 control-label" for="penerima">Satuan</label>
					<div class="col-sm-3">
						<input type="text" name="satuan" id="satuan" class="form-control" value="{{ data.satuan }}" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="penerima">Tanggal</label>
					<div class="col-sm-3">
						<input id="penerima" name="tanggal" type="date" placeholder="Tanggal" class="form-control" value="{{ data.tanggal|date("Y-m-d") }}">
					</div>
					<label class="col-sm-2 control-label" for="penerima">Nomor</label>
					<div class="col-sm-1">
						<input type="text" name="na_nomor" id="na_nomor" class="form-control" value="{{ data.na_nomor }}" />
					</div>
					<div class="col-sm-1">
						<input type="text" name="na_bulan" id="na_bulan" class="form-control" value="{{ data.na_bulan }}" />
					</div>
					<div class="col-sm-1">
						<input type="text" name="na_tahun" id="na_tahun" class="form-control" value="{{ data.na_tahun }}" />
					</div>
					<div class="col-sm-2">
						<input type="text" name="na_divisi" id="na_divisi" class="form-control" value="{{ data.na_divisi }}" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="penerima">Dari</label>
					<div class="col-sm-10">
						<input type="text" name="no_sptjb" id="no_sptjb" class="form-control" value="{{ data.no_sptjb }}" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="penerima">Pegawai YBS</label>
					<div class="col-sm-10">
						<select name="id_pegawai_ybs" id="id_pegawai_ybs" class="form-control">
							<option value=""></option>
							{% for combo in combo_pegawai %}
								<optgroup label="{{ combo.jenis_pegawai }}">
								{% for rincian in combo.rincian %}
									{% set selected = "" %}
									{% if rincian.id == data.id_pegawai_ybs %}
										{% set selected = "selected='selected'" %}
									{% endif %}
									<option value="{{ rincian.id }}" {{ selected }}>{{ rincian.nama_pegawai }}</option>
								{% endfor %}
								</optgroup>
							{% endfor %}
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="penerima">Diketahui Oleh</label>
					<div class="col-sm-10">
						<select name="diketahui_oleh" id="diketahui_oleh" class="form-control">
							<option value=""></option>
							{% for combo in combo_pegawai %}
								<optgroup label="{{ combo.jenis_pegawai }}">
								{% for rincian in combo.rincian %}
									{% set selected = "" %}
									{% if rincian.id == data.diketahui_oleh %}
										{% set selected = "selected='selected'" %}
									{% endif %}
									<option value="{{ rincian.id }}" {{ selected }}>{{ rincian.nama_pegawai }}</option>
								{% endfor %}
								</optgroup>
							{% endfor %}
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="penerima">Keperluan</label>
					<div class="col-sm-10">
						<textarea name="keterangan" id="keterangan" class="form-control">{{ data.keterangan }}</textarea>
					</div>
				</div>
				<hr />
				<button type="submit" name="save" value="Save" class="btn btn-primary"><i class="fa fa-save fa-lg">&nbsp;</i> Simpan Data Belanja Gaji</button>
			</form>
		</div>
	</div>
{% endblock %}
