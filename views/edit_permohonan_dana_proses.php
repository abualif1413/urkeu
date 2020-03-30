{% extends "master/layout.php" %}

{% block content %}
	<div class="row">
		<ol class="breadcrumb">
			<li><a href="#">
				<em class="fa fa-home"></em>
			</a></li>
			{% if pd == "" %}
				<li class="active">Ubah Belanja Barang</li>
			{% else %}
				<li class="active">Ubah Belanja Perjalanan Dinas</li>
			{% endif %}
		</ol>
	</div><!--/.row-->
	<div class="row">
		<div class="col-lg-12">
			{% if pd == "" %}
				<h1 class="page-header">Ubah Belanja Barang</h1>
			{% else %}
				<h1 class="page-header">Ubah Belanja Perjalanan Dinas</h1>
			{% endif %}
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
				document.location.href = "?delete=1&id=" + id + "&id_pengeluaran={{ obj_pengeluaran.id }}";
			}
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
    				$("#penerima").val(r.penerima);
    				$("#qty").val(r.qty);
    				$("#satuan").val(r.satuan);
    				$("#harga_satuan").val(r.harga_satuan);
    				$("#uraian").val(r.uraian);
					$("#no_faktur").val(r.no_faktur);
					$("#tgl_faktur").val(r.tgl_faktur);
					$("#ppn").prop("checked", 0);
					$("#pph").prop("checked", 0);
					if(r.ppn == 1) {
						$("#ppn").prop("checked", 1);
					}
					if(r.pph == 1) {
						$("#pph").prop("checked", 1);
					}
    				$("#add").hide();
    				$("#edit").show();
    			}
    		});
		}
		
		function go_reset() {
			$("#id_detail").val("");
			$("#penerima").val("");
			$("#qty").val("");
			$("#satuan").val("");
			$("#harga_satuan").val("");
			$("#uraian").val("");
			$("#no_faktur").val("");
			$("#tgl_faktur").val("");
			$("#ppn").prop("checked", 0);
			$("#pph").prop("checked", 0);
			$("#add").show();
    		$("#edit").hide();
		}
		
		function go_delete_normatif(id) {
			if(confirm("Yakin akan menghapus data?")) {
				document.location.href = "?delete_normatif=1&id=" + id + "&id_pengeluaran={{ obj_pengeluaran.id }}";
			}
		}
		
		function go_edit_normatif(id) {
			var query_string = {};
    		query_string['ajax'] = 1;
    		query_string['jenis'] = "go_edit_normatif";
    		query_string['id'] = id;
    		$.ajax({
    			url			: "",
    			type		: "get",
    			dataType	: "json",
    			data		: $.param(query_string),
    			success		: function(r){
    				$("#normatif_id_detail").val(r.id);
    				$("#normatif_id_pegawai").val(r.id_pegawai).trigger('change');
    				$("#normatif_jabatan_pengelola").val(r.jabatan_pengelola);
    				$("#normatif_qty").val(r.qty);
    				$("#normatif_sbu_honor").val(r.sbu_honor);
    				$("#add_normatif").hide();
    				$("#edit_normatif").show();
    			}
    		});
		}
		
		function go_reset_normatif() {
			$("#normatif_id_detail").val("");
			$("#normatif_id_pegawai").val("").trigger('change');
			$("#normatif_jabatan_pengelola").val("");
			$("#normatif_qty").val("");
			$("#normatif_sbu_honor").val("");
			$("#add_normatif").show();
    		$("#edit_normatif").hide();
		}
	</script>
	<ul class="nav nav-tabs">
		<li class="active"><a data-toggle="tab" href="#rincian_barang">Rincian Barang</a></li>
		{% if pd == "1" %}
			<li><a data-toggle="tab" href="#daftar_normatif">Daftar Normatif</a></li>
		{% endif %}
	</ul>
	
	<div class="tab-content">
		<div id="rincian_barang" class="tab-pane fade in active">
			<!-- Rincian Barang -->
				<div class="panel panel-primary">
					<div class="panel-heading">Rincian Barang</div>
					<div class="panel-body">
						<form class="form-horizontal" action="" method="post">
							<input type="hidden" name="pd" value="{{ pd }}" />
							<input type="hidden" name="id_pengeluaran" value="{{ obj_pengeluaran.id }}" />
							<input type="hidden" name="id_detail" id="id_detail" />
							<div class="form-group">
								<label class="col-sm-2 control-label" for="penerima">Penerima</label>
								<div class="col-sm-10">
									<input id="penerima" name="penerima" type="text" placeholder="Penerima" class="form-control">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">Qty</label>
								<div class="col-sm-2">
									<input type="text" name="qty" id="qty" placeholder="Qty" class="form-control" />
								</div>
								<div class="col-sm-3">
									<input type="text" name="satuan" id="satuan" placeholder="Satuan" class="form-control" />
								</div>
								<label class="col-sm-2 control-label">Harga @</label>
								<div class="col-sm-3">
									<input type="text" name="harga_satuan" id="harga_satuan" placeholder="Harga @" class="form-control" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">Faktur</label>
								<div class="col-sm-2">
									<input type="date" name="tgl_faktur" id="tgl_faktur" placeholder="Tanggal" class="form-control" />
								</div>
								<div class="col-sm-3">
									<input type="text" name="no_faktur" id="no_faktur" placeholder="Nomor" class="form-control" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">Pengenaan Pajak</label>
								<div class="col-sm-5">
									<label class="checkbox-inline control-label"><input type="checkbox" name="ppn" id="ppn" value="1">PPN</label>
									<label class="checkbox-inline control-label"><input type="checkbox" name="pph" id="pph" value="1">PPh</label>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="penerima">Uraian / Material</label>
								<div class="col-sm-10">
									<textarea name="uraian" id="uraian" class="form-control"></textarea>
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
										<th width="30px"></th>
										<th width="30px"></th>
										<th width="30px">No.</th>
										<th width="200px">Penerima</th>
										<th width="100px">Qty</th>
										<th width="150px">Satuan</th>
										<th width="100px">Harga @</th>
										<th>Uraian / Material</th>
									</tr>
								</thead>
								<tbody>
									{% set no = 0 %}
									{% for data_detail in data_detail %}
										{% set no = no + 1 %}
										<tr>
											<td><button class="btn btn-xs btn-warning" onclick="go_delete({{ data_detail.id }});"><i class="fa fa-trash"></i> Delete</button></td>
											<td><button class="btn btn-xs btn-success" onclick="go_edit({{ data_detail.id }});"><i class="fa fa-edit"></i> Edit</button></td>
											<td align="right">{{ no }}</td>
											<td>{{ data_detail.penerima }}</td>
											<td>{{ data_detail.qty }}</td>
											<td>{{ data_detail.satuan }}</td>
											<td align="right">{{ data_detail.harga_satuan|number_format(2, ".", ",") }}</td>
											<td>{{ data_detail.uraian }}</td>
										</tr>
									{% endfor %}
								</tbody>
							</table>
						</div>
					</div>
				</div>
			<!-- End Of Rincian Barang -->
		</div>
		<div id="daftar_normatif" class="tab-pane fade">
			<!-- Daftar Normatif -->
			<div class="panel panel-primary">
				<div class="panel-heading">Daftar Normatif</div>
				<div class="panel-body">
					<form class="form-horizontal" action="" method="post">
						<input type="hidden" name="pd" value="{{ pd }}" />
						<input type="hidden" name="id_pengeluaran" value="{{ obj_pengeluaran.id }}" />
						<input type="hidden" name="normatif_id_detail" id="normatif_id_detail" />
						<div class="form-group">
							<label class="col-sm-2 control-label" for="penerima">Pegawai</label>
							<div class="col-sm-10">
								<select name="normatif_id_pegawai" id="normatif_id_pegawai" class="form-control" style="width: 100%;">
									<option value=""></option>
									{% for combo in combo_pegawai %}
										<optgroup label="{{ combo.jenis_pegawai }}">
										{% for rincian in combo.rincian %}
											<option value="{{ rincian.id }}">{{ rincian.nama_pegawai }}</option>
										{% endfor %}
										</optgroup>
									{% endfor %}
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">Qty</label>
							<div class="col-sm-2">
								<input type="text" name="normatif_qty" id="normatif_qty" placeholder="Qty" class="form-control" />
							</div>
							<label class="col-sm-2 control-label">Honor</label>
							<div class="col-sm-3">
								<input type="text" name="normatif_sbu_honor" id="normatif_sbu_honor" placeholder="Harga @" class="form-control" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">Jabatan Pengelola</label>
							<div class="col-sm-10">
								<input type="text" name="normatif_jabatan_pengelola" id="normatif_jabatan_pengelola" placeholder="Jabatan Pengelola" class="form-control" />
							</div>
						</div>
						<hr />
						<button type="submit" name="add_normatif" id="add_normatif" value="Add" class="btn btn-primary"><i class="fa fa-plus fa-lg">&nbsp;</i> Tambah Rincian</button>
						<button type="submit" name="edit_normatif" id="edit_normatif" value="Edit" class="btn btn-warning" style="display: none;"><i class="fa fa-edit fa-lg">&nbsp;</i> Ubah Rincian</button>
						<button type="button" name="reset_normatif" id="reset_normatif" value="Reset" class="btn btn-success" onclick="go_reset_normatif();"><i class="fa fa-edit fa-lg">&nbsp;</i> Reset</button>
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
									<th width="100px">Qty</th>
									<th width="150px">Honor</th>
								</tr>
							</thead>
							<tbody>
								{% set no = 0 %}
								{% for list in list_normatif %}
									{% set no = no + 1 %}
									<tr>
										<td><button class="btn btn-xs btn-warning btn-block" onclick="go_delete_normatif({{ list.id }});"><i class="fa fa-trash"></i> Delete</button></td>
										<td><button class="btn btn-xs btn-success btn-block" onclick="go_edit_normatif({{ list.id }});"><i class="fa fa-edit"></i> Edit</button></td>
										<td align="right">{{ no }}</td>
										<td>{{ list.nama_pegawai }}</td>
										<td>{{ list.qty }}</td>
										<td align="right">{{ list.sbu_honor|number_format(2, ".", ",") }}</td>
									</tr>
								{% endfor %}
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<!-- End Of Daftar Normatif -->
		</div>
	</div>
	<div class="panel panel-primary">
		<div class="panel-heading">Rincian Belanja</div>
		<div class="panel-body">
			<form class="form-horizontal" action="" method="post">
				<input type="hidden" name="pd" value="{{ pd }}" />
				<input type="hidden" name="id_pengeluaran" value="{{ obj_pengeluaran.id }}" />
				<div class="form-group">
					<label class="col-sm-2 control-label" for="penerima">Tanggal</label>
					<div class="col-sm-3">
						<input id="tanggal" name="tanggal" type="date" placeholder="Tanggal" class="form-control" value="{{ obj_pengeluaran.tanggal|date("Y-m-d") }}">
					</div>
					<label class="col-sm-2 control-label" for="penerima">Nomor</label>
					<div class="col-sm-1">
						<input type="text" name="na_nomor" id="na_nomor" class="form-control" value="{{ obj_pengeluaran.na_nomor }}" />
					</div>
					<div class="col-sm-1">
						<input type="text" name="na_bulan" id="na_bulan" class="form-control" value="{{ obj_pengeluaran.na_bulan }}" placeholder="cth : VIII" />
					</div>
					<div class="col-sm-1">
						<input type="text" name="na_tahun" id="na_tahun" class="form-control" value="{{ obj_pengeluaran.na_tahun }}" placeholder="cth : 2018" />
					</div>
					<div class="col-sm-2">
						<input type="text" name="na_divisi" id="na_divisi" class="form-control" value="{{ obj_pengeluaran.na_divisi }}" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="penerima">Dari</label>
					<div class="col-sm-10">
						<input type="text" name="no_sptjb" id="no_sptjb" class="form-control" value="{{ obj_pengeluaran.no_sptjb }}" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="penerima">Pegawai YBS</label>
					<div class="col-sm-10">
						<select name="id_pegawai" id="id_pegawai" class="form-control">
							<option value=""></option>
							{% for combo in combo_pegawai %}
								<optgroup label="{{ combo.jenis_pegawai }}">
								{% for rincian in combo.rincian %}
									{% set selected = "" %}
									{% if rincian.id == obj_pengeluaran.id_pegawai_ybs %}
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
									{% if rincian.id == obj_pengeluaran.diketahui_oleh %}
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
					<label class="col-sm-2 control-label" for="penerima">Pejabat Kwitansi</label>
					<div class="col-sm-10">
						<select name="kuasa_pengguna_anggaran" id="kuasa_pengguna_anggaran" class="form-control">
							<option value=""></option>
							{% for combo in combo_pegawai %}
								<optgroup label="{{ combo.jenis_pegawai }}">
								{% for rincian in combo.rincian %}
									{% set selected = "" %}
									{% if rincian.id == obj_pengeluaran.kuasa_pengguna_anggaran %}
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
						<textarea name="keperluan" id="keperluan" class="form-control">{{ obj_pengeluaran.keterangan }}</textarea>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="penerima">Jenis Belanja</label>
					<div class="col-sm-10">
						<select name="jenis_belanja" id="jenis_belanja" class="form-control">
							<option value=""></option>
							{% if pd == "" %}
								<option value="belanja barang" {% if obj_pengeluaran.jenis_belanja == "belanja barang" %} selected="selected" {% endif %}>Belanja Barang</option>
								<option value="belanja pengadaan" {% if obj_pengeluaran.jenis_belanja == "belanja pengadaan" %} selected="selected" {% endif %}>Belanja Pengadaan</option>
								<option value="belanja pemeliharaan" {% if obj_pengeluaran.jenis_belanja == "belanja pemeliharaan" %} selected="selected" {% endif %}>Belanja Pemeliharaan</option>
							{% else %}
								<option value="belanja perjalanan dinas" {% if obj_pengeluaran.jenis_belanja == "belanja perjalanan dinas" %} selected="selected" {% endif %}>Belanja Perjalanan Dinas</option>
							{% endif %}
						</select>
					</div>
				</div>
				<hr />
				<button type="submit" name="save" value="Save" class="btn btn-primary"><i class="fa fa-save fa-lg">&nbsp;</i> Simpan Data Belanja Barang</button>
			</form>
		</div>
	</div>
{% endblock %}
