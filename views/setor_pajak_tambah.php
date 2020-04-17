{% extends "master/layout.php" %}

{% block content %}
	<script type="text/javascript" charset="utf-8">
		function go_edit(id) {
			//document.location.href = "spp_spm_edit_proses.php?id=" + id;
		}
		
		function go_cetak(id) {
			//document.location.href = "cetak_berkas_spp_spm_host.php?id=" + id;
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
				document.location.href = "?hapus_item=1&id_sppspm=" + id_sppspm + "&jenis=" + jenis;
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
			<li class="active">Tambah data penyetoran pajak</li>
		</ol>
	</div><!--/.row-->
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">Tambah data penyetoran pajak</h1>
		</div>
	</div><!--/.row-->
	<div class="panel panel-primary">
		<div class="panel-heading">Cari Data SPM yang akan disetor pajak nya</div>
		<div class="panel-body">
			<form class="form-horizontal" action="" method="get">
				<div class="form-group">
					<label class="col-sm-1 control-label" for="penerima">Tanggal</label>
					<div class="col-sm-2">
						<input id="tgl_dari" name="tgl_dari" type="date" class="form-control" value="{{ qs.tgl_dari|date("Y-m-d") }}">
					</div>
					<div class="col-sm-2">
						<input id="tgl_sampai" name="tgl_sampai" type="date" class="form-control" value="{{ qs.tgl_sampai|date("Y-m-d") }}">
					</div>
				</div>
				<hr />
				<button type="submit" name="cari" id="cari" value="Cari" class="btn btn-primary"><i class="fa fa-search fa-lg">&nbsp;</i> Cari Data</button>
			</form>
			<hr />
			<form method="post" action="">
				<table width="100%" class="table table-condensed table-striped table-hover" cellspacing="0" cellpadding="0" style="font-size: 80%;">
					<thead class="bg-primary">
						<tr>
							<th width="30px">No</th>
							<th width="100px">No. SPP / SPM</th>
							<th width="100px">Tgl. SPP / SPM</th>
							<th width="200px">No. Nota Ajuan</th>
							<th>Uraian</th>
							
							<th width="100px" style="text-align: right;">PPN</th>
							<th width="10px" align="right"><input type="checkbox" id="chk_all_ppn" onclick="chk_all_ppn_go();" /></th>
							
							<th width="100px" style="text-align: right;">PPh</th>
							<th width="10px" align="right"><input type="checkbox" id="chk_all_pph" onclick="chk_all_pph_go();" /></th>
						</tr>
					</thead>
					<tbody>
						{% for data in data %}
							<tr>
								<td>{{ data.nomor_urut_data }}</td>
								<td>{{ data.no_sppspm }}</td>
								<td>{{ data.tanggal|date("d-m-Y") }}</td>
								<td>{{ data.nomor_na }}</td>
								<td style="text-align: justify;">{{ data.keterangan }}</td>
								
								<td align="right">{{ data.ppn|number_format(2, ".", ",") }}</td>
								<td>
									{% if data.ppn > 0 %}
										{% if data.setor_ppn == 0 %}
											<input type="checkbox" name="chk_ppn[]" class="chk_ppn" value="{{ data.id }}" id_spp_spm="{{ data.id }}" nilai="{{ data.ppn }}" />
										{% else %}
											<i class="fa fa-check fa-lg"></i>
										{% endif %}
									{% endif %}
								</td>
								
								<td align="right">{{ data.pph|number_format(2, ".", ",") }}</td>
								<td>
									{% if data.pph > 0 %}
										{% if data.setor_pph == 0 %}
											<input type="checkbox" name="chk_pph[]" class="chk_pph" value="{{ data.id }}" id_spp_spm="{{ data.id }}" nilai="{{ data.pph }}" />
										{% else %}
											<i class="fa fa-check fa-lg"></i>
										{% endif %}
									{% endif %}
								</td>
							</tr>
						{% endfor %}
					</tbody>
					<thead class="bg-primary">
						<th colspan="5">Total terpilih</th>
						
						<th id="total_ppn_terpilih" style="text-align: right;"></th>
						<th></th>
						
						<th id="total_pph_terpilih" style="text-align: right;"></th>
						<th></th>
					</thead>
				</table>
				<hr />
				<button type="submit" name="tambah" value="Tambah" class="btn btn-primary">Tambah</button>
			</form>
		</div>
	</div>
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
						<th width="10px" align="right"></th>
						
						<th width="100px" style="text-align: right;">PPh</th>
						<th width="10px" align="right"></th>
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
							<td>
								{% if data.ppn > 0 %}
									<button type="button" class="btn btn-warning btn-xs" onclick="go_hapus({{ data.id }}, 'ppn');"><i class="fa fa-trash"></i></button>
								{% endif %}
							</td>
							
							<td align="right">{{ data.pph|number_format(2, ".", ",") }}</td>
							<td>
								{% if data.pph > 0 %}
									<button type="button" class="btn btn-warning btn-xs" onclick="go_hapus({{ data.id }}, 'pph');"><i class="fa fa-trash"></i></button>
								{% endif %}
							</td>
						</tr>
					{% endfor %}
				</tbody>
				<thead class="bg-primary">
					<th colspan="5">Total akan disetor</th>
					
					<th style="text-align: right;">{{ total_akan_setor_ppn|number_format(2, ".", ",") }}</th>
					<th></th>
					
					<th style="text-align: right;">{{ total_akan_setor_pph|number_format(2, ".", ",") }}</th>
					<th></th>
				</thead>
			</table>
			<form method="post" id="frm_setor_pajak" action="" onsubmit="return go_simpan();">
				<div class="row">
					<div class="form-group col-sm-2">
						<label>Tgl. penyetoran pajak</label>
						<input type="date" name="tgl_setor" id="tgl_setor" class="form-control form-required" />
					</div>
					<div class="form-group col-sm-10">
						<label>No. Referensi</label>
						<input type="nomor" name="nomor" id="nomor" class="form-control form-required" />
					</div>
				</div>
				<div class="row">
					<div class="form-group col-sm-12">
						<label>Uraian / keterangan</label>
						<textarea class="form-control form-required" name="keterangan" id="keterangan"></textarea>
					</div>
				</div>
				<div class="row">
					<div class="form-group col-sm-12">
						<button type="submit" class="btn btn-primary" name="simpan" value="Simpan"><i class="fa fa-lg fa-save"></i> Simpan</button>
					</div>
				</div>
			</form>
		</div>
	</div>
{% endblock %}
