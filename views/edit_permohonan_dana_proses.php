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
		$(function() {
			go_load_rincian_barang();
			go_load_rincian_normatif();
			pilih_pic_rekanan();
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
		
		/*
		 | ----------------------------------------------------------------------------------------------------------------------------------------
		 | Bagian penambahan rincian barang
		 | Saat ini dipisah karena penambahan fitur pengecekan pajak dari data rekanan
		 | ----------------------------------------------------------------------------------------------------------------------------------------
		 */
		
		function go_add_rincian_barang() {
			var formData = new FormData(document.querySelector("#frm_rincian_barang"));
			formData.append("id_permohonan_dana", {{ obj_pengeluaran.id }});
			formData.append("user_id", "{{ user_id }}");

			if(formData.get("penerima") == "" || formData.get("qty") == "" || formData.get("satuan") == "") {
				alert("Peneriman, qty, dan satuan harus diisi");
				return false;
			} else {
				$.ajax({
		            url         : "../urlrvl/api/PengeluaranDana/TambahDetail",
		            type        : "post",
		            dataType    : "json",
		            data        : formData,
		            processData : false,
		            contentType : false,
		            success:function(r, textStatus, jqXHR){
		                go_load_rincian_barang();
		                go_reset();
		            },
		            error: function(jqXHR, textStatus, errorThrown){
		                alert("Error : " + textStatus);
		            }
		        });
		        return false;
			}
		}
		
		function go_load_rincian_barang() {
			$.get("../urlrvl/api/PengeluaranDana/GetDetailList/{{ obj_pengeluaran.id }}", function() {})
				.done(function(r) {
					var isi = "";
					$.each(r.data, function(i, v) {
						isi += "<tr>";
							isi += "<td><button class='btn btn-xs btn-warning btn-block' onclick='go_delete(" + v.id + ");'><i class='fa fa-trash'></i></button></td>";
							isi += "<td><button class='btn btn-xs btn-success btn-block' onclick='go_edit(" + v.id + ")'><i class='fa fa-edit'></i></button></td>";
							isi += "<td>" + (i + 1) + "</td>";
							isi += "<td>" + v.penerima + "</td>";
							isi += "<td>" + v.qty + "</td>";
							isi += "<td>" + v.satuan + "</td>";
							isi += "<td>" + accounting.format(v.harga_satuan) + "</td>";
							isi += "<td>" + v.uraian + "</td>";
						isi += "</tr>";
					});
					$("#tbody_rincian").html(isi);
				})
				.fail(function() {
					alert("Error saat load rincian barang");
				});
		}
		
		function go_delete(id) {
			if(confirm("Yakin akan menghapus data?")) {
				$.get("../urlrvl/api/PengeluaranDana/hapusDetail/" + id, function() {})
					.done(function(r) {
						go_load_rincian_barang();
						go_reset();
					})
					.fail(function() {
						alert("Error saat hapus rincian barang");
					});
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
		
		/*
		 | ----------------------------------------------------------------------------------------------------------------------------------------
		 */
		
		
		
		
		
		
		
		/*
		 | ----------------------------------------------------------------------------------------------------------------------------------------
		 | Bagian penambahan rincian normatif barang
		 | Saat ini dipisah karena penambahan fitur pengecekan pajak dari data rekanan
		 | ----------------------------------------------------------------------------------------------------------------------------------------
		 */
		
		function go_add_normatif() {
			var formData = new FormData(document.querySelector("#frm_normatif_barang"));
			formData.append("id_permohonan_dana", {{ obj_pengeluaran.id }});
			formData.append("user_id", "{{ user_id }}");
			
			if(formData.get("normatif_id_pegawai") == "" || formData.get("normatif_qty") == "" || formData.get("normatif_sbu_honor") == "" || formData.get("normatif_jabatan_pengelola") == "") {
				alert("Data normatif belum lengkap");
				
				return false;
			} else {
				$.ajax({
		            url         : "../urlrvl/api/PengeluaranDana/TambahDetailNormatif",
		            type        : "post",
		            dataType    : "json",
		            data        : formData,
		            processData : false,
		            contentType : false,
		            success:function(r, textStatus, jqXHR){
		                go_load_rincian_normatif();
		                go_reset_normatif();
		            },
		            error: function(jqXHR, textStatus, errorThrown){
		                alert("Error : " + textStatus);
		            }
		        });
		        
		        return false;
			}
		}
		
		function go_load_rincian_normatif() {
			$.get("../urlrvl/api/PengeluaranDana/GetDetailNormatifList/{{ obj_pengeluaran.id }}", function() {})
				.done(function(r) {
					var isi = "";
					$.each(r.data, function(i, v) {
						isi += "<tr>";
							isi += "<td><button class='btn btn-xs btn-warning btn-block' onclick='go_delete_normatif(" + v.id + ");'><i class='fa fa-trash'></i></button></td>";
							isi += "<td><button class='btn btn-xs btn-success btn-block' onclick='go_edit_normatif(" + v.id + ")'><i class='fa fa-edit'></i></button></td>";
							isi += "<td>" + (i + 1) + "</td>";
							isi += "<td>" + v.nama_pegawai + "</td>";
							isi += "<td>" + v.qty + "</td>";
							isi += "<td>" + accounting.format(v.sbu_honor) + "</td>";
						isi += "</tr>";
					});
					$("#tbody_normatif").html(isi);
				})
				.fail(function() {
					alert("Error saat load rincian barang");
				});
		}
		
		function go_delete_normatif(id) {
			if(confirm("Yakin akan menghapus data?")) {
				$.get("../urlrvl/api/PengeluaranDana/hapusDetailNormatif/" + id, function() {})
					.done(function(r) {
						go_load_rincian_normatif();
						go_reset_normatif();
					})
					.fail(function() {
						alert("Error saat hapus rincian barang");
					});
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
		
		/*
		 | ----------------------------------------------------------------------------------------------------------------------------------------
		 */
		
		
		
		
		
		
		
		
		
		/*
		 | ----------------------------------------------------------------------------------------------------------------------------------------
		 | Bagian pemilihan rekanan
		 | Ketika rekanan -> PIC nya dipilih, maka akan menentukan bentuk penginputan PPN dan PPh nya
		 | Kemudian juga menset di bagian utama nya agar bisa disimpan ke database data pic rekanannya
		 | ----------------------------------------------------------------------------------------------------------------------------------------
		 */
		
		function pilih_pic_rekanan() {
			var id_assoc_pic_rekanan = $("#rekanan").val();
			
			if(id_assoc_pic_rekanan != "") {
				var pic_rekanan = id_assoc_pic_rekanan.split("-");
				var id_data_rekanan = pic_rekanan[0];
				var id_data_rekanan_pic = pic_rekanan[1];
				
				$("#id_data_rekanan_pic").val(id_data_rekanan_pic);
				
				$.get("../urlrvl/api/PengeluaranDana/GetRekananPIC/" + id_data_rekanan_pic, function() {})
					.done(function(r) {
						var resp = r.data;
						$("#nama_rekanan").val(resp.nama_rekanan);
						$("#nama_rekanan_pic").val(resp.nama_pic);
						
						switch(resp.kena_ppn.toUpperCase()) {
							case "YES" :
								$("#ppn").prop("checked", true);
								$("#ppn").hide();
								$("#tanda_kena_ppn").removeClass("fa-remove");
								$("#tanda_kena_ppn").addClass("fa-check");
								break;
							case "NO" :
								$("#ppn").prop("checked", false);
								$("#ppn").hide();
								$("#tanda_kena_ppn").removeClass("fa-check");
								$("#tanda_kena_ppn").addClass("fa-remove");
								break;
							case "YES_NO" :
								$("#ppn").prop("checked", false);
								$("#ppn").show();
								$("#tanda_kena_ppn").removeClass("fa-check");
								$("#tanda_kena_ppn").removeClass("fa-remove");
								break;
						}
						switch(resp.kena_pph.toUpperCase()) {
							case "YES" :
								$("#pph").prop("checked", true);
								$("#pph").hide();
								$("#tanda_kena_pph").removeClass("fa-remove");
								$("#tanda_kena_pph").addClass("fa-check");
								break;
							case "NO" :
								$("#pph").prop("checked", false);
								$("#pph").hide();
								$("#tanda_kena_pph").removeClass("fa-check");
								$("#tanda_kena_pph").addClass("fa-remove");
								break;
							case "YES_NO" :
								$("#pph").prop("checked", false);
								$("#pph").show();
								$("#tanda_kena_pph").removeClass("fa-check");
								$("#tanda_kena_pph").removeClass("fa-remove");
								break;
						}
					})
					.fail(function() {
						alert("Error saat ambil data PIC rekanan");
					});
			} else {
				$("#id_data_rekanan_pic").val("");
				$("#nama_rekanan").val("");
				$("#nama_rekanan_pic").val("");
			}
		}
		
		/*
		 | ----------------------------------------------------------------------------------------------------------------------------------------
		 */
		
		
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
		
		function go_reset_normatif() {
			$("#normatif_id_detail").val("");
			$("#normatif_id_pegawai").val("").trigger('change');
			$("#normatif_jabatan_pengelola").val("");
			$("#normatif_qty").val("");
			$("#normatif_sbu_honor").val("");
			$("#add_normatif").show();
    		$("#edit_normatif").hide();
		}
		
		
		function pilih_pegawai(id_value, id_show){
			var width = 1200;
	        var height = 500;
	        var top = (window.screen.height / 2) - ((height / 2) + 50);
	        var left = (window.screen.width / 2) - ((width / 2) + 10);
	        
	        window.open("../urlrvl/AmbilDataPegawai?id_value=" + id_value + "&id_show=" + id_show, "", "top=" + top + ",left=" + left + ",width=" + width + ",height=" + height + ",toolbar=no,menubar=no,scrollbars=yes,location=no,directories=no");
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
					<div class="panel-heading">Pilih Rekanan</div>
					<div class="panel-body">
						<select name="rekanan" id="rekanan" class="form-control" onchange="pilih_pic_rekanan();">
							<option value="">- Pilih Rekanan -</option>
							{% for rek in rekanan %}
								{% if rek.jenis == 1 %}
									<optgroup label="{{ rek.nama_perusahaan }}">
								{% elseif rek.jenis == 2 %}
										{% set dipilih = "" %}
										{% if rek.id_data_rekanan_pic == obj_pengeluaran.id_data_rekanan_pic %}
											{% set dipilih = "selected='selected'" %}
										{% endif %}
										<option value="{{ rek.id_assoc }}" {{ dipilih }}>{{ rek.nama_pic }}</option>
								{% elseif rek.jenis == 3 %}
									</optgroup>
								{% endif %}
							{% endfor %}
						</select>
					</div>
				</div>
				<div class="panel panel-primary">
					<div class="panel-heading">Rincian Barang</div>
					<div class="panel-body">
						<form class="form-horizontal" action="" method="post" id="frm_rincian_barang" onsubmit="return go_add_rincian_barang();">
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
									<label class="checkbox-inline control-label"><input type="checkbox" name="ppn" id="ppn" value="1"><i id="tanda_kena_ppn" class="fa"></i> PPN</label>
									<label class="checkbox-inline control-label"><input type="checkbox" name="pph" id="pph" value="1"><i id="tanda_kena_pph" class="fa"></i> PPh</label>
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
								<tbody id="tbody_rincian">
								
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
					<form class="form-horizontal" action="" method="post" id="frm_normatif_barang" onsubmit="return go_add_normatif();">
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
							<tbody id="tbody_normatif">
								
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
				<input type="hidden" name="id_data_rekanan_pic" id="id_data_rekanan_pic" value="" />
				<div class="form-group">
					<label class="col-sm-2 control-label" for="penerima">Rekanan</label>
					<div class="col-sm-10">
						<input type="text" name="nama_rekanan" id="nama_rekanan" class="form-control" readonly="readonly" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="penerima">P.I.C</label>
					<div class="col-sm-10">
						<input type="text" name="nama_rekanan_pic" id="nama_rekanan_pic" class="form-control" readonly="readonly" />
					</div>
				</div>
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
						<div class="input-group">
							<input type="hidden" class="form-control" placeholder="Search" name="id_pegawai" id="id_pegawai" value="{{ obj_pengeluaran.id_pegawai_ybs }}-{{ obj_pengeluaran.id_pegawai_ybs_riwayat }}">
							<input type="text" class="form-control" placeholder="Search" name="pegawai_ybs" id="pegawai_ybs" readonly="readonly" value="{{ obj_pengeluaran.nama_pegawai }} - {{ obj_pengeluaran.jabatan }}">
							<div class="input-group-btn">
								<button class="btn btn-primary" type="button" onclick="pilih_pegawai('id_pegawai', 'pegawai_ybs');">
									<i class="fa fa-search"></i>
								</button>
							</div>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="penerima">Diketahui Oleh</label>
					<div class="col-sm-10">
						<div class="input-group">
							<input type="hidden" class="form-control" placeholder="Search" name="diketahui_oleh" id="diketahui_oleh" value="{{ obj_pengeluaran.diketahui_oleh }}-{{ obj_pengeluaran.diketahui_oleh_riwayat }}">
							<input type="text" class="form-control" placeholder="Search" name="pegawai_do" id="pegawai_do" readonly="readonly" value="{{ obj_pengeluaran.nama_pegawai_diketahui }} - {{ obj_pengeluaran.jabatan_diketahui }}">
							<div class="input-group-btn">
								<button class="btn btn-primary" type="button" onclick="pilih_pegawai('diketahui_oleh', 'pegawai_do');">
									<i class="fa fa-search"></i>
								</button>
							</div>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="penerima">Pejabat Kwitansi</label>
					<div class="col-sm-10">
						<div class="input-group">
							<input type="hidden" class="form-control" placeholder="Search" name="kuasa_pengguna_anggaran" id="kuasa_pengguna_anggaran" value="{{ obj_pengeluaran.kuasa_pengguna_anggaran }}-{{ obj_pengeluaran.kuasa_pengguna_anggaran_riwayat }}">
							<input type="text" class="form-control" placeholder="Search" name="pegawai_kpa" id="pegawai_kpa" readonly="readonly" value="{{ obj_pengeluaran.nama_pegawai_kuitansi }} - {{ obj_pengeluaran.jabatan_kuitansi }}">
							<div class="input-group-btn">
								<button class="btn btn-primary" type="button" onclick="pilih_pegawai('kuasa_pengguna_anggaran', 'pegawai_kpa');">
									<i class="fa fa-search"></i>
								</button>
							</div>
						</div>
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
