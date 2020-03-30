{% extends "master/layout_pop.php" %}

{% block content %}
<script type="text/javascript" charset="utf-8">
	function pilih_pagu(id) {
		window.opener.window.pilih_id_pagu(id);
		window.close();
	}
</script>
{% if qs.id_header != "" %}
	<table class="table table-condensed table-striped table-bordered tbl-coa">
		<thead class="bg-warning">
			<tr>
				<th class="text-left">Nama Akun</th>
				<th width="120px" class="text-right">Anggaran</th>
				<th width="120px" class="text-right">S/D<br />Bulan Lalu</th>
				<th width="120px" class="text-right">S/D<br />{{ qs.per_tgl|date("d-m-Y") }}</th>
				<th width="120px" class="text-right">Total</th>
				<th width="120px" class="text-right">Sisa</th>
			</tr>
		</thead>
		<tbody>
			{% for isi in isi_pagu %}
				<tr class="accnumber" level="{{ isi.lvl }}">
					<td class="accisi">{{ isi.nomor_umum }} - {{ isi.acc_name }}</td>
					<td class="text-right">{{ isi.jumlah|number_format(0, ".", ",") }},-</td>
					<td class="text-right">{{ isi.bulan_lalu|number_format(0, ".", ",") }},-</td>
					<td class="text-right">{{ isi.saat_ini|number_format(0, ".", ",") }},-</td>
					<td class="text-right">{{ isi.jumlah_realisasi|number_format(0, ".", ",") }},-</td>
					<td class="text-right">{{ isi.sisa|number_format(0, ".", ",") }},-</td>
				</tr>
			{% endfor %}
		</tbody>
	</table>
{% endif %}		
{% endblock %}
