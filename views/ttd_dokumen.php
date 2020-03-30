{% extends "master/layout.php" %}

{% block content %}
	<script type="text/javascript" charset="utf-8">
		function go_edit(id) {
			var width = 800;
	        var height = 500;
	        var top = (window.screen.height / 2) - ((height / 2) + 50);
	        var left = (window.screen.width / 2) - ((width / 2) + 10);
	        
	        window.open("ttd_dokumen_pop_edit.php?id=" + id, "", "top=" + top + ",left=" + left + ",width=" + width + ",height=" + height + ",toolbar=no,menubar=no,scrollbars=yes,location=no,directories=no");
		}
		
		function go_input(id) {
			var width = 800;
	        var height = 500;
	        var top = (window.screen.height / 2) - ((height / 2) + 50);
	        var left = (window.screen.width / 2) - ((width / 2) + 10);
	        
	        window.open("ttd_dokumen_pop_input.php?id=" + id, "", "top=" + top + ",left=" + left + ",width=" + width + ",height=" + height + ",toolbar=no,menubar=no,scrollbars=yes,location=no,directories=no");
		}
		
		function go_delete(id) {
			if(confirm("Anda yakin akan menghapus data penandatangan ini?")) {
				document.location.href = "?hapus=1&id=" + id;
			}
		}
	</script>
	<div class="row">
		<ol class="breadcrumb">
			<li><a href="#">
				<em class="fa fa-home"></em>
			</a></li>
			<li class="active">Tanda Tangan Dokumen</li>
		</ol>
	</div><!--/.row-->
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">Tanda Tangan Dokumen</h1>
		</div>
	</div><!--/.row-->
	<div class="panel panel-primary articles">
		<div class="panel-heading">
			Tanda Tangan Dokumen
		</div>
		<div class="panel-body articles-container">
			{% for dokumen in dokumen %}
				<div class="bg-warning" style="padding: 5px; font-weight: bold;">Dokumen : {{ dokumen.nama_dokumen }}</div>
				<table class="table table-striped table-condensed" style="table-layout: fixed; font-size: 85%;">
					<thead class="bg-info">
						<tr>
							<th width="100px">Tgl. Berlaku</th>
							<th width="100px">Kode</th>
							<th>Keterangan</th>
							<th>Penanda Tangan</th>
							<th width="40px"></th>
							<th width="40px"></th>
							<th width="40px"></th>
						</tr>
					</thead>
					<tbody>
						{% for ttd in dokumen.ttd %}
							<tr>
								<td>{{ ttd.tanggal|date("d-m-Y") }}</td>
								<td>{{ ttd.kode_ttd }}</td>
								<td>{{ ttd.judul_ttd }}</td>
								<td>{{ ttd.nama_pegawai }}</td>
								<td>
									<button class="btn btn-xs btn-warning btn-block" title="Ubah data penandatangan" onclick="go_edit({{ ttd.id }});"><i class="fa fa-edit"></i></button>
								</td>
								<td>
									<button class="btn btn-xs btn-success btn-block" title="Tambah data penandatangan untuk kode {{ ttd.kode_ttd }}" onclick="go_input({{ ttd.id }});"><i class="fa fa-plus"></i></button>
								</td>
								<td>
									<button class="btn btn-xs btn-danger btn-block" title="Hapus data penandatangan" onclick="go_delete({{ ttd.id }});"><i class="fa fa-trash"></i></button>
								</td>
							</tr>
						{% endfor %}
					</tbody>
				</table>
				<br />
			{% endfor %}
		</div>
	</div><!--End .articles-->
{% endblock %}
