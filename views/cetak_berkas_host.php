{% extends "master/layout.php" %}

{% block content %}
	<script type="text/javascript" charset="utf-8">
		$(document).ready(function() {
			myCalendar = new dhtmlXCalendarObject(["tanggal"]);
		});
		
		function go_cetak_nota_dinas(id) {
			window.open("../cetak/nota_dinas.php?id=" + id);
		}
		
		function go_cetak_sptjb(id) {
			window.open("../cetak/sptjb.php?id=" + id);
		}
		
		function go_cetak_sppjb_rincian(id) {
			window.open("../cetak/sppjb_rincian.php?id=" + id);
		}
		
		function go_cetak_sptjb_rincian(id) {
			window.open("../cetak/sptjb_rincian.php?id=" + id);
		}
		
		function go_cetak_kuitansi(id) {
			window.open("../cetak/kuitansi.php?id=" + id);
		}
		
		function go_cetak_daftar_normatif(id) {
			window.open("../cetak/daftar_normatif_barang.php?id=" + id);
		}
		
		function go_cetak_payroll_honor(id) {
			window.open("../cetak/payroll_honor_barang.php?id=" + id);
		}
		
		function go_cetak_csv(id) {
			var separator = $("#separator").val();
			if(separator != "," && separator != ";") {
				alert("Separator harus koma (,) atau titik koma (;)");
			} else {
				window.open("../cetak/csv_payroll_honor_barang.php?id=" + id + "&separator=" + separator);
			}
		}
		
		function val_pecah() {
			var ppn = $("#pecah_ppn").val();
			var pph = $("#pecah_pph").val();
			var bruto = $("#pecah_bruto").val();
			var seharusnya = {{ seharusnya }};
			
			if(bruto == "") {
				alert("Bruto tidak boleh kosong");
			} else {
				if(!$.isNumeric(bruto)) {
					alert("Format bruto salah");
				} else {
					var diinput = parseFloat(bruto) + parseFloat(ppn) + parseFloat(pph);
					if(diinput > seharusnya) {
						alert("Nilai pemecahan pembayaran tidak boleh melebihi nilai belanja sebenarnya");
					} else {
						if(confirm("Anda yakin akan menyimpan data pemecahan pembayaran ini?")) {
							document.location.href = "?pecah_pembayaran=1&ppn=" + ppn + "&pph=" + pph + "&bruto=" + bruto + "&id={{ id }}&pd={{ pd }}";
						}
					}
				}
			}
		}
		
		function pop_pecah_pembayaran(id) {
			if(confirm("Anda yakin akan menghapus pemecahan pembayaran ini?")) {
				document.location.href = "?pop_pecah_pembayaran=1&id_pecah=" + id + "&id={{ id }}&pd={{ pd }}";
			}
		}
	</script>
	<div class="row">
		<ol class="breadcrumb">
			<li><a href="#">
				<em class="fa fa-home"></em>
			</a></li>
			{% if pd == "" %}
				<li class="active">Cetak Berkas Belanja Barang</li>
			{% else %}
				<li class="active">Cetak Berkas Belanja Perjalanan Dinas</li>
			{% endif %}
		</ol>
	</div><!--/.row-->
	<div class="row">
		<div class="col-lg-12">
			{% if pd == "" %}
				<h1 class="page-header">Cetak Berkas Belanja Barang</h1>
			{% else %}
				<h1 class="page-header">Cetak Berkas Belanja Perjalanan Dinas</h1>
			{% endif %}
		</div>
	</div><!--/.row-->
	<div class="panel panel-primary">
		<div class="panel-heading">Keterangan Data Belanja Barang</div>
		<div class="panel-body">
			<div class="row">
				<div class="col-sm-2">Tanggal</div>
				<div class="col-sm-10"><strong>: {{ record.tanggal|date("d-m-Y") }}</strong></div>
			</div>
			<div class="row">
				<div class="col-sm-2">No. Nota Ajuan</div>
				<div class="col-sm-10"><strong>: B/NA-{{ record.na_nomor }}/{{ record.na_bulan }}/{{ record.na_tahun }}/{{ record.na_divisi }}</strong></div>
			</div>
			<div class="row">
				<div class="col-sm-2">No. SPPJB</div>
				<div class="col-sm-10"><strong>: SPPJB/{{ record.na_nomor }}/{{ record.na_bulan }}/{{ record.na_tahun }}/{{ record.na_divisi }}</strong></div>
			</div>
			<div class="row">
				<div class="col-sm-2">No. SPTJB</div>
				<div class="col-sm-10"><strong>: SPTJB/{{ record.na_nomor }}/{{ record.na_bulan }}/{{ record.na_tahun }}/RS. Bhayangkara Tk II Medan</strong></div>
			</div>
			<div class="row">
				<div class="col-sm-2">Uraian</div>
				<div class="col-sm-10"><strong>: {{ record.keterangan }}</strong></div>
			</div>
			<div class="row">
				<div class="col-sm-2">Pemohon</div>
				<div class="col-sm-10"><strong>: {{ record.pangkat }} {{ record.nama_pegawai }} NIP/NRP : {{ record.nik }}</strong></div>
			</div>
			<hr />
			<p>
				<h3>Pecah Pembayaran</h3>
				Pecah pembayaran adalah fitur ketika proses pembayaran nota ajuan ini (SPM) terpecah menjadi lebih dari 1
				dan masing-masing item pembayaran akan mewakili satu SPM. Ketentuan nya adalah jika nota ajuan ini memiliki pajak
				(PPN atau PPh) maka pajak tersebut harus dibayarkan pada item pembayaran pertama
			</p>
			<table class="table table-bordered" style="width: 50%; table-layout: fixed;">
				<thead class="bg-info">
					<tr>
						<th>PPN</th>
						<th>PPh</th>
						<th>Bruto</th>
						<th width="50px"></th>
					</tr>
				</thead>
				<tbody>
					{% for bayar in pembayaran %}
						<tr>
							<td align="right">{{ bayar.ppn|number_format(0, ".", ",") }}</td>
							<td align="right">{{ bayar.pph|number_format(0, ".", ",") }}</td>
							<td align="right">{{ bayar.bruto|number_format(0, ".", ",") }}</td>
							<td><button type="button" class="btn btn-warning btn-xs btn-block" onclick="pop_pecah_pembayaran({{ bayar.id }});"><i class="fa fa-trash"></i></button></td>
						</tr>
					{% endfor %}
					{% if seharusnya > 0 %}
						<tr>
							<td>
								<input type="text" class="form-control" name="pecah_ppn" id="pecah_ppn" readonly="readonly" value="{{ ppn }}" style="text-align: right; color: red;" />
							</td>
							<td>
								<input type="text" class="form-control" name="pecah_pph" id="pecah_pph" readonly="readonly" value="{{ pph }}" style="text-align: right; color: red;" />
							</td>
							<td>
								<input type="text" class="form-control" name="pecah_bruto" id="pecah_bruto" value="{{ akan_bayar }}" style="text-align: right; color: red;" />
							</td>
							<td></td>
						</tr>
						<tr>
							<td colspan="4">
								<button class="btn btn-sm btn-primary" type="button" onclick="val_pecah();"><i class="fa fa-save"></i> Simpan Data Pecah Pembayaran</button>
							</td>
						</tr>
					{% endif %}
				</tbody>
			</table>
			<hr />
			<button type="button" name="reset" id="reset" value="Reset" class="btn btn-primary" onclick="go_cetak_nota_dinas({{ id }});"><i class="fa fa-file">&nbsp;</i> Cetak Nota Ajuan</button>
			<button type="button" name="reset" id="reset" value="Reset" class="btn btn-primary" onclick="go_cetak_sptjb({{ id }})"><i class="fa fa-file">&nbsp;</i> Cetak SPTJB - UP</button>
			<button type="button" name="reset" id="reset" value="Reset" class="btn btn-primary" onclick="go_cetak_sppjb_rincian({{ id }})"><i class="fa fa-file">&nbsp;</i> Cetak SPTJB Pengajuan</button>
			<button type="button" name="reset" id="reset" value="Reset" class="btn btn-primary" onclick="go_cetak_sptjb_rincian({{ id }})"><i class="fa fa-file">&nbsp;</i> Cetak SPTJB Rincian</button>
			<button type="button" name="reset" id="reset" value="Reset" class="btn btn-primary" onclick="go_cetak_kuitansi({{ id }})"><i class="fa fa-file">&nbsp;</i> Cetak Kuitansi</button>
			{% if pd != "" %}
				<button type="button" name="reset" id="reset" value="Reset" class="btn btn-primary" onclick="go_cetak_daftar_normatif({{ id }})"><i class="fa fa-file">&nbsp;</i> Cetak Daftar Normatif</button>
				<button type="button" name="reset" id="reset" value="Reset" class="btn btn-primary" onclick="go_cetak_payroll_honor({{ id }})"><i class="fa fa-file">&nbsp;</i> Cetak Payroll</button>
				<div class="row">
					<div class="col-sm-2">
						<div class="input-group">
							<input type="text" class="form-control" placeholder="Separator" id="separator">
							<div class="input-group-btn">
								<button class="btn btn-primary" type="button" onclick="go_cetak_csv({{ id }})"><i class="fa fa-file">&nbsp;</i> CSV</button>
							</div>
						</div>
					</div>
				</div>
			{% endif %}
		</div>
	</div>
{% endblock %}
