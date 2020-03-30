{% extends "master/layout_pop.php" %}

{% block content %}
<script type="text/javascript" charset="utf-8">
	function go_save() {
		var validasi = val_form_submit("frm_anggaran");
		if(validasi) {
			return confirm("Anda yakin akan menyimpan data tanda tangan ini?");
		} else {
			alert("Data yang diinput belum lengkap");
			return false;
		}
	}
</script>
<div class="alert alert-info">
	<div style="font-weight: bold; font-size: 130%;"><i class="fa fa-tags"></i> Tambah TTD Dokumen</div>
</div>
<form method="post" action="" id="frm_anggaran" onsubmit="return go_save();" style="padding: 20px;">
	<input type="hidden" name="id" value="{{ ttd.id }}" />
	<div class="row">
		<div class="col-sm-9 form-group">
			<label class="control-label">Nama Dokumen</label>
			<input type="text" name="nama_dokumen" id="nama_dokumen" class="form-control" readonly="readonly" value="{{ ttd.nama_dokumen }}" />
		</div>
		<div class="col-sm-3 form-group">
			<label class="control-label">Kode</label>
			<input type="text" name="kode_ttd" id="kode_ttd" class="form-control" readonly="readonly" value="{{ ttd.kode_ttd }}" />
		</div>
	</div>
	<div class="row">
		<div class="col-sm-3 form-group">
			<label class="control-label">Per Tanggal</label>
			<input type="date" name="tanggal" id="tanggal" class="form-control form-required" />
		</div>
		<div class="col-sm-9 form-group">
			<label class="control-label">Keterangan (Ketikkan || untuk memberi baris baru)</label>
			<input type="text" name="judul_ttd" id="judul_ttd" class="form-control form-required" />
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12 form-group">
			<label class="control-label">Penandatangan</label>
			<select name="id_pegawai" id="id_pegawai" class="form-control form-required">
				<option value=""></option>
				<option value="0">- Tidak ada penandatangan -</option>
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
	<hr />
	<button type="submit" class="btn btn-primary" name="save" value="Save"><i class="fa fa-save"></i> Simpan</button>
</form>
{% endblock %}
