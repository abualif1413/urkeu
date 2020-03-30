{% extends "master/layout_pop.php" %}

{% block content %}
<script type="text/javascript" charset="utf-8">
	function go_save() {
		var validasi = val_form_submit("frm_anggaran");
		if(validasi) {
			return confirm("Anda yakin akan menyimpan data anggaran ini?");
		} else {
			alert("Data yang diinput belum lengkap");
			return false;
		}
	}
</script>
<div class="alert alert-info">
	<div style="font-weight: bold; font-size: 130%;"><i class="fa fa-tags"></i> Input Data Anggaran</div>
	<div style="font-size: 90%;">{{ nomor_pagu }} - {{ nama_akun }}</div>
</div>
<form method="post" action="" id="frm_anggaran" onsubmit="return go_save();">
	<input type="hidden" name="id_coa" value="{{ qs.id_coa }}" />
	<input type="hidden" name="tahun" value="{{ qs.tahun }}" />
	<div class="col-sm-2 form-group">
		<label class="control-label">Qty</label>
		<input type="text" name="qty" id="qty" class="form-control form-required" value="{{ data.qty }}" />
	</div>
	<div class="col-sm-6 form-group">
		<label class="control-label">Satuan</label>
		<input type="text" name="satuan" id="satuan" class="form-control form-required" value="{{ data.satuan }}" />
	</div>
	<div class="col-sm-4 form-group">
		<label class="control-label">Jumlah</label>
		<input type="text" name="jumlah" id="jumlah" class="form-control form-required" value="{{ data.jumlah }}" />
	</div>
	<div class="col-sm-12 text-right">
		<br />
		<button type="submit" name="save" value="Save" class="btn btn-primary">Simpan</button>
	</div>
</form>
{% endblock %}
