{% extends "master/layout_pop.php" %}

{% block content %}
<script type="text/javascript" charset="utf-8">
	function pilih_pagu(id) {
		window.opener.window.pilih_id_pagu(id);
		window.close();
	}
</script>
<form method="get" action="" style="padding: 20px;">
	<div class="row">
		<div class="col-sm-11">
			<select class="form-control" pilihan="0" name="id_header" id="id_header">
				<option value=""></option>
				{% for coa_pagu in coa_pagu %}
					{% if coa_pagu.id == qs.id_header %}
						<option value="{{ coa_pagu.id }}" selected="selected">{{ coa_pagu.nomor_coa }} - {{ coa_pagu.acc_name }}</option>
					{% else %}
						<option value="{{ coa_pagu.id }}">{{ coa_pagu.nomor_coa }} - {{ coa_pagu.acc_name }}</option>
					{% endif %}
				{% endfor %}
			</select>
		</div>
		<div class="col-sm-1">
			<button class="btn btn-primary btn-block" type="submit" onclick=""><i class="fa fa-search"></i> OK</button>
		</div>
	</div>
</form>
<table class="table table-condensed table-striped table-hover tbl-coa">
	<thead>
		<tr>
			<th>Nama Akun</th>
			<th width="30px"></th>
		</tr>
	</thead>
	<tbody>
		{% for isi in isi_pagu %}
			<tr class="accnumber" level="{{ isi.lvl }}">
				<td class="accisi">{{ isi.nomor_umum }} - {{ isi.acc_name }}</td>
				<td>
					{% if isi.jlh_anak == 0 %}
						<button class="btn btn-xs btn-success btn-block" onclick="pilih_pagu({{ isi.id }});"><i class="fa fa-chevron-right"></i></button>
					{% endif %}
				</td>
			</tr>
		{% endfor %}
	</tbody>
</table>			
{% endblock %}
