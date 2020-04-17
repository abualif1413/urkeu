{% extends "master/layout.php" %}

{% block content %}
	<script type="text/javascript" charset="utf-8">
		function go_edit() {
			document.location.href = "setor_pajak_edit.php?id={{ head.id }}";
		}
		
		function go_cetak() {
			window.open("../cetak/setor_pajak.php?id={{ head.id }}");
		}
		
		function go_delete(id) {
			if(confirm("Anda yakin akan menghapus data ini?")) {
				//document.location.href = "?delete=1&id=" + id;
			}
		}
		
		function chk_all_ppn_go() {
			$(".chk_ppn").prop("checked", $("#chk_all_ppn").prop("checked"));
		}	
		
		function chk_all_pph_go() {
			$(".chk_pph").prop("checked", $("#chk_all_pph").prop("checked"));
		}
		
		function count_total_ppn_checked() {
			var total = 0;
			$(".chk_ppn").each(function(){
				if($(this).prop("checked")) {
					var nilai = parseFloat($(this).attr("nilai"));
					total += nilai;
				}
			});
			
			$("#total_ppn_terpilih").html(accounting.format(total, 2));
		}
		
		function count_total_pph_checked() {
			var total = 0;
			$(".chk_pph").each(function(){
				if($(this).prop("checked")) {
					var nilai = parseFloat($(this).attr("nilai"));
					total += nilai;
				}
			});
			
			$("#total_pph_terpilih").html(accounting.format(total, 2));
		}
		
		function go_simpan() {
			if(val_form_submit("frm_setor_pajak")) {
				return confirm("Anda yakin akan menyimpan data ini");
			} else {
				alert("Data yang diisi belum lengkap");
				
				return false;
			}
		}
		
		function go_hapus(id_sppspm, jenis) {
			if(confirm("Anda yakin menghapus data ini?")) {
				document.location.href = "?hapus_item=1&id_sppspm=" + id_sppspm + "&jenis=" + jenis + "&id={{ head.id }}";
			}
		}
		
		setInterval(count_total_ppn_checked, 100);
		setInterval(count_total_pph_checked, 100);
	</script>
	<div class="row">
		<ol class="breadcrumb">
			<li><a href="#">
				<em class="fa fa-home"></em>
			</a></li>
			<li class="active">Cetak data penyetoran pajak</li>
		</ol>
	</div><!--/.row-->
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">Cetak data penyetoran pajak</h1>
		</div>
	</div><!--/.row-->
	<div class="panel panel-primary">
		<div class="panel-heading">Data penyetoran pajak</div>
		<div class="panel-body">
			<table width="100%" class="table table-condensed table-striped table-hover" cellspacing="0" cellpadding="0" style="font-size: 80%;">
				<thead class="bg-primary">
					<tr>
						<th width="30px">No</th>
						<th width="100px">No. SPP / SPM</th>
						<th width="100px">Tgl. SPP / SPM</th>
						<th width="200px">No. Nota Ajuan</th>
						<th>Uraian</th>
						
						<th width="100px" style="text-align: right;">PPN</th>
						
						
						<th width="100px" style="text-align: right;">PPh</th>
						
					</tr>
				</thead>
				<tbody>
					{% set total_akan_setor_ppn = 0 %}
					{% set total_akan_setor_pph = 0 %}
					{% for data in pilih %}
						{% set total_akan_setor_ppn = total_akan_setor_ppn + data.ppn %}
						{% set total_akan_setor_pph = total_akan_setor_pph + data.pph %}
						<tr>
							<td>{{ data.nomor_urut_data }}</td>
							<td>{{ data.no_sppspm }}</td>
							<td>{{ data.tanggal|date("d-m-Y") }}</td>
							<td>{{ data.nomor_na }}</td>
							<td style="text-align: justify;">{{ data.keterangan }}</td>
							
							<td align="right">{{ data.ppn|number_format(2, ".", ",") }}</td>
							
							
							<td align="right">{{ data.pph|number_format(2, ".", ",") }}</td>
							
						</tr>
					{% endfor %}
				</tbody>
				<thead class="bg-primary">
					<th colspan="5">Total akan disetor</th>
					
					<th style="text-align: right;">{{ total_akan_setor_ppn|number_format(2, ".", ",") }}</th>
					
					
					<th style="text-align: right;">{{ total_akan_setor_pph|number_format(2, ".", ",") }}</th>
					
				</thead>
			</table>
			<form method="post" id="frm_setor_pajak" action="" onsubmit="return go_simpan();">
				<input type="hidden" name="id" value="{{ head.id }}" />
				<div class="row">
					<div class="form-group col-sm-2">
						<label>Tgl. penyetoran pajak</label>
						<input type="date" name="tgl_setor" id="tgl_setor" class="form-control form-required" value="{{ head.tanggal }}" readonly="readonly" />
					</div>
					<div class="form-group col-sm-10">
						<label>No. Referensi</label>
						<input type="nomor" name="nomor" id="nomor" class="form-control form-required" value="{{ head.nomor }}" readonly="readonly" />
					</div>
				</div>
				<div class="row">
					<div class="form-group col-sm-12">
						<label>Uraian / keterangan</label>
						<textarea class="form-control form-required" name="keterangan" id="keterangan" readonly="readonly">{{ head.keterangan }}</textarea>
					</div>
				</div>
				<div class="row">
					<div class="form-group col-sm-12">
						<button type="button" class="btn btn-primary" onclick="go_cetak();"><i class="fa fa-lg fa-print"></i> Cetak</button>
						<button type="button" class="btn btn-warning" onclick="go_edit();"><i class="fa fa-lg fa-edit"></i> Edit</button>
					</div>
				</div>
			</form>
		</div>
	</div>
{% endblock %}
