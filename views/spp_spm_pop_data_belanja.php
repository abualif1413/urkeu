{% extends "master/layout_pop.php" %}

{% block content %}
<script type="text/javascript" charset="utf-8">
	function pilih_id_belanja(id_belanja, jenis_belanja) {
		window.opener.window.pilih_id_belanja(id_belanja, jenis_belanja);
		window.close();
	}
</script>
<form method="get" action="" style="padding: 10px;">
	<div class="row">
		<div class="col-sm-6 form-group">
			<label class="control-label">Keterangan / Nomor</label>
			<input type="text" name="cari" id="cari" class="form-control" placeholder="Pencarian Data" value="{{ qs.cari }}" />
		</div>
		<div class="col-sm-2 form-group">
			<label class="control-label">Dari</label>
			<input type="date" name="dari" id="dari" class="form-control" value="{{ qs.dari }}" />
		</div>
		<div class="col-sm-2 form-group">
			<label class="control-label">Sampai</label>
			<input type="date" name="sampai" id="sampai" class="form-control" value="{{ qs.sampai }}" />
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12 form-group">
			<button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Cari Data</button>
		</div>
	</div>
</form>
<table class="table table-condensed table-hover" style="font-size: 85%; text-transform: uppercase;">
	<thead>
		<tr class="bg-primary">
			<th width="40px" class="text-right">No.</th>
			<th width="80px" class="text-center">Tanggal</th>
			<th width="280px">Nomor</th>
			<th width="200px">Jenis Belanja</th>
			<th>Keperluan</th>
			<th width="120px" class="text-right">Total Belanja</th>
			<th width="30px"></th>
		</tr>
	</thead>
	<tbody>
		{% for belanja in belanja %}
			<tr>
				<td class="text-right">{{ belanja.nomor_urut_data }}</td>
				<td class="text-center">{{ belanja.tanggal|date("d-m-Y") }}</td>
				<td>{{ belanja.nomor }}</td>
				<td>{{ belanja.jenis_belanja }}</td>
				<td>{{ belanja.keterangan }}</td>
				<td class="text-right"><strong>{{ belanja.total|number_format(2, ".", ",") }}</strong></td>
				<td><button class="btn btn-xs btn-block btn-success" title="Klik untuk memilih" onclick="pilih_id_belanja({{ belanja.id }}, '{{ belanja.jenis_belanja }}');"><i class="fa fa-chevron-right"></i></button></td>
			</tr>
		{% endfor %}
	</tbody>
</table>			
{% endblock %}
