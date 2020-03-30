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
		
		function val_save() {
			var nama = $("#nama").val();
			var username = $("#username").val();
			var pwd1 = $("#pwd1").val();
			var pwd2 = $("#pwd2").val();
			
			if(nama == "" || username == "" || pwd1 == "" || pwd2 == "") {
				alert("Isikan semua data");
				return false;
			} else {
				if(pwd1 != pwd2) {
					alert("Password dan ulangi password harus sama");
					return false;
				}
			}
		}
	</script>
	<div class="row">
		<ol class="breadcrumb">
			<li><a href="#">
				<em class="fa fa-home"></em>
			</a></li>
			<li class="active">Data Pengguna</li>
		</ol>
	</div><!--/.row-->
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">Data Pengguna</h1>
		</div>
	</div><!--/.row-->
	<div class="panel panel-primary">
		<div class="panel-heading">Kelola Data Pengguna</div>
		<div class="panel-body">
			<form class="form-horizontal" action="" method="post" onsubmit="return val_save();">
				<input type="hidden" name="id" value="{{ id }}" />
				<div class="form-group">
					<label class="col-sm-2 control-label" for="penerima">Nama</label>
					<div class="col-sm-4">
						<input type="text" name="nama" id="nama" class="form-control" value="{{ data.nama }}" />
					</div>
					<label class="col-sm-2 control-label" for="penerima">Username</label>
					<div class="col-sm-4">
						<input type="text" name="username" id="username" class="form-control" value="{{ data.username }}" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="penerima">Password</label>
					<div class="col-sm-4">
						<input type="password" name="pwd1" id="pwd1" class="form-control" />
					</div>
					<label class="col-sm-2 control-label" for="penerima">Ulangi Password</label>
					<div class="col-sm-4">
						<input type="password" name="pwd2" id="pwd2" class="form-control" />
					</div>
				</div>
				<hr />
				<button type="submit" name="save" id="save" value="Save" class="btn btn-primary"><i class="fa fa-save fa-lg">&nbsp;</i> Simpan Data Pengguna</button>
			</form>
		</div>
	</div>
{% endblock %}
