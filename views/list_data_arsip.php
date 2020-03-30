{% extends "master/layout.php" %}

{% block content %}
	<script type="text/javascript" charset="utf-8">
		
	</script>
	<div class="row">
		<ol class="breadcrumb">
			<li><a href="#">
				<em class="fa fa-home"></em>
			</a></li>
			<li class="active">Arsip Data</li>
		</ol>
	</div><!--/.row-->
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">Arsip Data</h1>
		</div>
	</div><!--/.row-->
	<div class="panel panel-default articles">
		<div class="panel-heading">
			Daftar Arsip&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<a class="btn btn-primary" href="arsip_data.php">Tambah Arsip</a>
		</div>
		<div class="panel-body articles-container">
			{% for arsip in arsip %}
				<div class="article border-bottom">
					<div class="col-xs-12">
						<div class="row">
							<div class="col-xs-2 col-md-2 date">
								<div class="large">{{ arsip.tanggal|date("d") }}</div>
								<div class="text-muted">{{ arsip.tanggal|date("M Y") }}</div>
							</div>
							<div class="col-xs-10 col-md-10">
								<h4><a href="arsip_data.php?id={{ arsip.id }}">Detail</a></h4>
								<p>{{ arsip.keterangan }}</p>
							</div>
						</div>
					</div>
					<div class="clear"></div>
				</div><!--End .article-->
			{% endfor %}
		</div>
	</div><!--End .articles-->
{% endblock %}
