{% extends "master/layout.php" %}

{% block content %}
	<script type="text/javascript" charset="utf-8">
		window.onload = function () {
			load_file();
		};
		
		function pilih_berkas() {
			$("#berkas").click();
		}
		
		function go_upload() {
			$("#frm_berkas").submit();
		}
		
		function load_file() {
			var id = {{ id }};
			$.ajax({
				url	: "",
				type : "get",
				dataType : "json",
				data : "ajax=1&jenis=load_file&id_arsip_berkas=" + id,
				success: function(r) {
					$("#filenya").html("");
					$.each(r, function(index, value) {
						var isi = "<a type='button' class='btn btn-sm btn-primary' href='../arsip_file/" + value.nama_file_fisik + "' target='_blank'><i class='fa fa-file'>&nbsp;</i> " + value.nama_file + "</a> ";
						$("#filenya").append(isi);
					});
				}
			});
		}
	</script>
	<div class="row">
		<ol class="breadcrumb">
			<li><a href="#">
				<em class="fa fa-home"></em>
			</a></li>
			<li class="active">Arsip Data</li>
		</ol>
	</div><!--/.row-->
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">Arsip Data</h1>
		</div>
	</div><!--/.row-->
	<div class="panel panel-primary">
		<div class="panel-heading">Kelola Data Arsip</div>
		<div class="panel-body">
			<form class="form-horizontal" action="" method="post">
				<input type="hidden" name="id" value="{{ id }}" />
				<div class="form-group">
					<label class="col-sm-2 control-label" for="penerima">Tanggal Berkas</label>
					<div class="col-sm-2">
						<input id="tanggal" name="tanggal" type="date" placeholder="" class="form-control" value="{{ arsip.tanggal|date("Y-m-d") }}">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="penerima">Keterangan Berkas</label>
					<div class="col-sm-10">
						<textarea name="keterangan" id="keterangan" class="form-control">{{ arsip.keterangan }}</textarea>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-2">
						<button type="button" class="btn btn-success" onclick="pilih_berkas();"><i class="fa fa-file fa-lg">&nbsp;</i> Pilih File</button>
					</div>
					<div class="col-sm-10" id="filenya"></div>
				</div>
				<hr />
				<button type="submit" name="save" value="Save" class="btn btn-primary btn-lg">Simpan Arsip</button>
			</form>
			<form method="post" enctype="multipart/form-data" id="frm_berkas" action="upload_berkas.php" target="target_upload" style="display: none;">
				<input type="text" name="id_arsip_berkas" value="{{ id }}" />
				<input type="file" name="berkas" id="berkas" onchange="go_upload();" />
			</form>
			<iframe name="target_upload" style="display: none;"></iframe>
		</div>
	</div>
{% endblock %}
