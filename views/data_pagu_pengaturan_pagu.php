{% extends "master/layout.php" %}

{% block content %}
	<script type="text/javascript" charset="utf-8">
		function load_nama_head() {
			var id = $("#parent_id").val();
			$.ajax({
				url			: "",
				type		: "get",
				dataType	: "text",
				data		: "ajax=1&jenis=load_nama_head&id=" + id,
				success		: function(r) {
					$("#head").val(r);
				}
			});
		}
		
		function val_save() {
			var nomor = $("#nomor").val();
			var nama = $("#nama").val();
			
			if(nomor == "" || nama == "") {
				alert("Isikan nomor dan nama akun");
				return false;
			} else {
				return confirm("Anda yakin akan menyimpan data ini?");
			}
		}
		
		function val_delete(id) {
			if(confirm("Anda yakin akan menghapus data ini?")) {
				document.location.href = "?delete=1&id=" + id + "&parent_utama={{ qs.id_header }}";
			}
		}
		
		function go_tambah_sub(parent_id) {
			$("#parent_id").val(parent_id);
			$("#head").val("");
			$("#nomor").val("");
			$("#nama").val("");
			$("#save").attr("disabled", "disabled");
			$("#save").removeClass("btn-primary");
			$("#save").addClass("btn-default");
			$.when(load_nama_head()).done(function() {
				$("#save").removeAttr("disabled");
				$("#save").removeClass("btn-default");
				$("#save").addClass("btn-primary");
			});
		}
		
		$(function() {
			load_nama_head();
		})
	</script>
	<div class="row">
		<ol class="breadcrumb">
			<li><a href="#">
				<em class="fa fa-home"></em>
			</a></li>
			<li class="active">Pengaturan Pagu</li>
		</ol>
	</div><!--/.row-->
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">Pengaturan Pagu</h1>
		</div>
	</div><!--/.row-->
	<script type="text/javascript" charset="utf-8">
	
	</script>
	<div class="panel panel-primary">
		<div class="panel-heading">Header Pagu</div>
		<div class="panel-body">
			<form method="get" action="">
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
				<div class="col-sm-1">
					<button class="btn btn-primary btn-block" type="submit" onclick=""><i class="fa fa-search"></i> OK</button>
				</div>
			</form>
		</div>
	</div>
	{% if qs.id_header != "" %}
		<div class="panel panel-primary">
			<div class="panel-heading">Rincian Pagu</div>
			<div class="panel-body">
				<form method="post" action="">
					<input type="hidden" name="parent_utama" id="parent_utama" value="{{ qs.id_header }}" />
					<input type="hidden" name="parent_id" id="parent_id" value="{{ qs.id_header }}" />
					<h3>Data Akun Pagu</h3>
					<br />
					<div class="col-sm-12">
						<div class="form-group">
							<label class="control-label">Head</label>
							<input type="text" name="head" id="head" class="form-control" readonly="readonly" />
						</div>
					</div>
					<div class="col-sm-4">
						<div class="form-group">
							<label class="control-label">No. Akun</label>
							<input type="text" name="nomor" id="nomor" class="form-control" />
						</div>
					</div>
					<div class="col-sm-8">
						<div class="form-group">
							<label class="control-label">Nama Akun</label>
							<input type="text" name="nama" id="nama" class="form-control" />
						</div>
					</div>
					<div class="col-sm-12">
						<div class="form-group">
							<button type="submit" name="save" id="save" value="Save" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah</button>
						</div>
					</div>
					<div style="clear: both;"></div>
					<hr />
					<br />
				</form>
				<table class="table table-condensed table-striped tbl-coa">
					<thead>
						<tr>
							<th>Nama Akun</th>
							<th width="30px"></th>
							<th width="30px"></th>
						</tr>
					</thead>
					<tbody>
						{% for isi in isi_pagu %}
							<tr>
								<td class="accnumber" level="{{ isi.lvl }}">{{ isi.nomor_umum }} - {{ isi.acc_name }}</td>
								<td>
									<button class="btn btn-xs btn-success btn-block" onclick="go_tambah_sub({{ isi.id }});"><i class="fa fa-plus"></i></button>
								</td>
								<td>
									<button class="btn btn-xs btn-danger btn-block" onclick="val_delete({{ isi.id }});"><i class="fa fa-trash"></i></button>
								</td>
							</tr>
						{% endfor %}
					</tbody>
				</table>
			</div>
		</div>
	{% endif %}
{% endblock %}