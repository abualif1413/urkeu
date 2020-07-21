{% extends "master/layout.php" %}

{% block content %}
	<div class="row">
		<ol class="breadcrumb">
			<li><a href="#">
				<em class="fa fa-home"></em>
			</a></li>
			<li class="active">Belanja Honor</li>
		</ol>
	</div><!--/.row-->
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">Belanja Honor</h1>
		</div>
	</div><!--/.row-->
	<script type="text/javascript" charset="utf-8">
		// Cek nomor berkas
		function cek_nomor_berkas() {
			var tanggal = $("#tgl_uraian").val();
			//alert(tanggal);
			if(tanggal != "") {
				$.ajax({
					url			: "cek_nomor_berkas.php",
					data		: "tanggal=" + tanggal,
					type		: "get",
					dataType	: "json",
					success		: function(r) {
						$("#na_nomor").val(r.na_nomor);
						$("#na_bulan").val(r.na_bulan);
						$("#na_tahun").val(r.na_tahun);
					}
				});
			} else {
				$("#na_nomor").val("");
				$("#na_bulan").val("");
				$("#na_tahun").val("");
			}
		}
		setInterval(cek_nomor_berkas, 1000);
		// End Of : Cek Nomor Berkas
		
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
    				$("#id_pegawai").val(r.id_pegawai).trigger('change');;
    				$("#jabatan_pengelola").val(r.jabatan_pengelola);
    				$("#qty").val(r.qty);
    				$("#sbu_honor").val(r.sbu_honor);
    				$("#add").hide();
    				$("#edit").show();
    			}
    		});
		}
		
		function go_reset() {
			$("#id_detail").val("");
			$("#id_pegawai").val("").trigger('change');
			$("#jabatan_pengelola").val("");
			$("#qty").val("");
			$("#sbu_honor").val("");
			$("#add").show();
    		$("#edit").hide();
		}
		
		function pilih_pegawai(id_value, id_show){
            var width = 1200;
            var height = 500;
            var top = (window.screen.height / 2) - ((height / 2) + 50);
            var left = (window.screen.width / 2) - ((width / 2) + 10);
            
            window.open("../urlrvl/AmbilDataPegawai?id_value=" + id_value + "&id_show=" + id_show, "", "top=" + top + ",left=" + left + ",width=" + width + ",height=" + height + ",toolbar=no,menubar=no,scrollbars=yes,location=no,directories=no");
        }
	</script>
	<div class="panel panel-primary">
		<div class="panel-heading">Rincian Belanja Honor</div>
		<div class="panel-body">
			<form class="form-horizontal" action="" method="post">
				<input type="hidden" name="id_detail" id="id_detail" />
				<div class="form-group">
					<label class="col-sm-2 control-label" for="penerima">Pegawai</label>
					<div class="col-sm-10">
						<select name="id_pegawai" id="id_pegawai" class="form-control">
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
						<input type="text" name="qty" id="qty" placeholder="Qty" class="form-control" />
					</div>
					<label class="col-sm-2 control-label">SBU Honor</label>
					<div class="col-sm-3">
						<input type="text" name="sbu_honor" id="sbu_honor" placeholder="Harga @" class="form-control" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">Jabatan Pengelola</label>
					<div class="col-sm-10">
						<input type="text" name="jabatan_pengelola" id="jabatan_pengelola" placeholder="Jabatan Pengelola" class="form-control" />
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
							<th width="100px">Qty</th>
							<th width="150px">SBU Honor</th>
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
								<td>{{ list.qty }}</td>
								<td align="right">{{ list.sbu_honor|number_format(2, ".", ",") }}</td>
							</tr>
						{% endfor %}
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="panel panel-primary">
		<div class="panel-heading">Detail Belanja Honor</div>
		<div class="panel-body">
			<form class="form-horizontal" action="" method="post">
				<div class="form-group">
					<label class="col-sm-2 control-label" for="penerima">Satuan</label>
					<div class="col-sm-3">
						<input type="text" name="satuan" id="satuan" class="form-control" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="penerima">Tanggal</label>
					<div class="col-sm-3">
						<input id="tgl_uraian" name="tanggal" type="date" placeholder="Tanggal" class="form-control">
					</div>
					<label class="col-sm-2 control-label" for="penerima">Nomor</label>
					<div class="col-sm-1">
						<input type="text" name="na_nomor" id="na_nomor" class="form-control" />
					</div>
					<div class="col-sm-1">
						<input type="text" name="na_bulan" id="na_bulan" class="form-control" />
					</div>
					<div class="col-sm-1">
						<input type="text" name="na_tahun" id="na_tahun" class="form-control" />
					</div>
					<div class="col-sm-2">
						<input type="text" name="na_divisi" id="na_divisi" class="form-control" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="penerima">Dari</label>
					<div class="col-sm-10">
						<input type="text" name="no_sptjb" id="no_sptjb" class="form-control" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="penerima">Pegawai YBS</label>
					<div class="col-sm-10">
						<!--<select name="id_pegawai_ybs" id="id_pegawai_ybs" class="form-control">
							<option value=""></option>
							{% for combo in combo_pegawai %}
								<optgroup label="{{ combo.jenis_pegawai }}">
								{% for rincian in combo.rincian %}
									<option value="{{ rincian.id }}">{{ rincian.nama_pegawai }}</option>
								{% endfor %}
								</optgroup>
							{% endfor %}
						</select>-->
						<div class="input-group">
                            <input type="hidden" class="form-control" placeholder="Search" name="id_pegawai_ybs" id="id_pegawai_ybs">
                            <input type="text" class="form-control" placeholder="Search" name="pegawai_ybs" id="pegawai_ybs" readonly="readonly">
                            <div class="input-group-btn">
                                <button class="btn btn-primary" type="button" onclick="pilih_pegawai('id_pegawai_ybs', 'pegawai_ybs');">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="penerima">Diketahui Oleh</label>
					<div class="col-sm-10">
						<!--<select name="diketahui_oleh" id="diketahui_oleh" class="form-control">
							<option value=""></option>
							{% for combo in combo_pegawai %}
								<optgroup label="{{ combo.jenis_pegawai }}">
								{% for rincian in combo.rincian %}
									<option value="{{ rincian.id }}">{{ rincian.nama_pegawai }}</option>
								{% endfor %}
								</optgroup>
							{% endfor %}
						</select>-->
						<div class="input-group">
                            <input type="hidden" class="form-control" placeholder="Search" name="diketahui_oleh" id="diketahui_oleh">
                            <input type="text" class="form-control" placeholder="Search" name="pegawai_do" id="pegawai_do" readonly="readonly">
                            <div class="input-group-btn">
                                <button class="btn btn-primary" type="button" onclick="pilih_pegawai('diketahui_oleh', 'pegawai_do');">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="penerima">Keperluan</label>
					<div class="col-sm-10">
						<textarea name="keterangan" id="keterangan" class="form-control"></textarea>
					</div>
				</div>
				<hr />
				<button type="submit" name="save" value="Save" class="btn btn-primary"><i class="fa fa-save fa-lg">&nbsp;</i> Simpan Data Belanja Honor</button>
			</form>
		</div>
	</div>
{% endblock %}
