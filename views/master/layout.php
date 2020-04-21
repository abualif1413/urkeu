<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Urkeu</title>
	<link href="../views/Lumino Admin Bootstrap Template/lumino/css/bootstrap.min.css" rel="stylesheet">
	<link href="../views/Lumino Admin Bootstrap Template/lumino/css/font-awesome.min.css" rel="stylesheet">
	<link href="../views/Lumino Admin Bootstrap Template/lumino/css/datepicker3.css" rel="stylesheet">
	<link href="../views/Lumino Admin Bootstrap Template/lumino/css/styles.css" rel="stylesheet">
	<link href="../select2/dist/css/select2.min.css" rel="stylesheet">
	
	<script src="../views/Lumino Admin Bootstrap Template/lumino/js/jquery-1.11.1.min.js"></script>
	<script src="../views/Lumino Admin Bootstrap Template/lumino/js/bootstrap.min.js"></script>
	<script src="../views/Lumino Admin Bootstrap Template/lumino/js/chart.min.js"></script>
	<script src="../views/Lumino Admin Bootstrap Template/lumino/js/chart-data.js"></script>
	<script src="../views/Lumino Admin Bootstrap Template/lumino/js/easypiechart.js"></script>
	<script src="../views/Lumino Admin Bootstrap Template/lumino/js/easypiechart-data.js"></script>
	<script src="../views/Lumino Admin Bootstrap Template/lumino/js/bootstrap-datepicker.js"></script>
	<script src="../views/Lumino Admin Bootstrap Template/lumino/js/custom.js"></script>
	<script src="../select2/dist/js/select2.full.min.js"></script>
	<script src="../js/app.js"></script>
	<script src="../js/accounting.js"></script>
	<script>
		function rolling_user() {
			$.ajax({
				url			: "nav_service.php",
				type		: "get",
				dataType	: "json",
				data		: "ajax=1&jenis=rolling_user",
				success		: function(r) {
					$("#profile-usertitle-name").html(r.nama);
					$("#profile-usertitle-status").html(r.username);
					if(r.id_role == 1) {
						$(".admin_nav").show();
					} else {
						$(".admin_nav").hide();
					}
				}
			});
		}
		
		function atur_select2() {
			$("select").each(function() {
				var pilihan = $(this).attr("pilihan");
				if(pilihan != "0") {
					$(this).select2();
				}
			});
		}
		
		$(document).ready(function() {
			atur_select2();
			rolling_user();
			atur_tbl_coa();
			$('[data-toggle="tooltip"]').tooltip();
			$('[data-toggle="popover"]').popover();
		});
	</script>
	
	<!--Custom Font-->
	<!--<link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">-->
	<!--[if lt IE 9]>
	<script src="js/html5shiv.js"></script>
	<script src="js/respond.min.js"></script>
	<![endif]-->
	
	<style type="text/css">
		@font-face {
		    font-family: 'app_sans';
		    src: url('../fonts/OpenSans-Regular.ttf');
		}
		@font-face {
		    font-family: 'coa_sans';
		    src: url('../fonts/OpenSans-Semibold.ttf');
		}
		
		body {
			font-family: "app_sans";
		}
	</style>
	
	<script type="text/javascript" charset="utf-8">
		
	</script>
</head>
<body>
	<nav class="navbar navbar-custom navbar-fixed-top" role="navigation">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#sidebar-collapse"><span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span></button>
				<a class="navbar-brand" href="#"><span>Urkeu Aplikasi</span></a>
			</div>
		</div><!-- /.container-fluid -->
	</nav>
	<div id="sidebar-collapse" class="col-sm-3 col-lg-2 sidebar">
		<div class="profile-sidebar bg-info">
			<div class="profile-usertitle">
				<div class="profile-usertitle-name" id="profile-usertitle-name">Username</div>
				<div class="profile-usertitle-status" style="color: white;"><span class="indicator label-success"></span><span id="profile-usertitle-status"></span></div>
			</div>
			<div class="clear"></div>
		</div>
		<div class="divider"></div>
		<ul class="nav menu">
			<li class="parent">
				<a data-toggle="collapse" href="#si_belanja_barang">
					<em class="fa fa-shopping-bag fa-lg">&nbsp;</em> Brg / Proc / Mtc
					<span data-toggle="collapse" href="#sub-item-1" class="icon pull-right"><em class="fa fa-plus"></em></span>
				</a>
				<ul class="children collapse" id="si_belanja_barang">
					<li><a class="" href="input_data_pengeluaran_dana.php"><span class="fa fa-plus-square">&nbsp;</span> Input Data</a></li>
					<li><a class="" href="edit_data_pengeluaran.php"><span class="fa fa-edit">&nbsp;</span> Edit Data</a></li>
				</ul>
			</li>
			<li class="parent">
				<a data-toggle="collapse" href="#si_perjalanan_dinas">
					<em class="fa fa-car fa-lg">&nbsp;</em> Perjalanan Dinas
					<span data-toggle="collapse" href="#sub-item-1" class="icon pull-right"><em class="fa fa-plus"></em></span>
				</a>
				<ul class="children collapse" id="si_perjalanan_dinas">
					<li><a class="" href="input_data_pengeluaran_dana.php?pd=1"><span class="fa fa-plus-square">&nbsp;</span> Input Data</a></li>
					<li><a class="" href="edit_data_pengeluaran.php?pd=1"><span class="fa fa-edit">&nbsp;</span> Edit Data</a></li>
				</ul>
			</li>
			<li class="parent ">
				<a data-toggle="collapse" href="#si_belanja_honor">
					<em class="fa fa-users fa-lg">&nbsp;</em> Belanja Honor
					<span data-toggle="collapse" href="#sub-item-1" class="icon pull-right"><em class="fa fa-plus"></em></span>
				</a>
				<ul class="children collapse" id="si_belanja_honor">
					<li><a class="" href="input_belanja_honor.php"><span class="fa fa-plus-square">&nbsp;</span> Input Data</a></li>
					<li><a class="" href="edit_belanja_honor.php"><span class="fa fa-edit">&nbsp;</span> Edit Data</a></li>
				</ul>
			</li>
			<li class="parent ">
				<a data-toggle="collapse" href="#si_belanja_gaji">
					<em class="fa fa-users fa-lg">&nbsp;</em> Belanja Gaji
					<span data-toggle="collapse" href="#sub-item-1" class="icon pull-right"><em class="fa fa-plus"></em></span>
				</a>
				<ul class="children collapse" id="si_belanja_gaji">
					<li><a class="" href="input_belanja_gaji.php"><span class="fa fa-plus-square">&nbsp;</span> Input Data</a></li>
					<li><a class="" href="edit_belanja_gaji.php"><span class="fa fa-edit">&nbsp;</span> Edit Data</a></li>
				</ul>
			</li>
			<li class="parent ">
				<a data-toggle="collapse" href="#si_spm_spp">
					<em class="fa fa-money fa-lg">&nbsp;</em> Pembayaran
					<span data-toggle="collapse" href="#sub-item-1" class="icon pull-right"><em class="fa fa-plus"></em></span>
				</a>
				<ul class="children collapse" id="si_spm_spp">
					<li><a class="" href="spp_spm_input.php"><span class="fa fa-plus-square">&nbsp;</span> Input SPP &amp; SPM</a></li>
					<li><a class="" href="spp_spm_edit.php"><span class="fa fa-edit">&nbsp;</span> Edit SPP &amp; SPM</a></li>
					<li><a class="" href="pu_input.php"><span class="fa fa-money">&nbsp;</span> PU Bank</a></li>
					<li><a class="" href="pu_edit.php"><span class="fa fa-edit">&nbsp;</span> Edit PU Bank</a></li>
					<li><a class="" href="penerimaan_lain.php"><span class="fa fa-external-link-square">&nbsp;</span> Penerimaan Lain</a></li>
					<li><a class="" href="pengeluaran_lain.php"><span class="fa fa-external-link-square">&nbsp;</span> Pengeluaran Lain</a></li>
					<li><a class="" href="pu_lain.php"><span class="fa fa-external-link-square">&nbsp;</span> PU Lain</a></li>
					<li><a class="" href="setor_pajak_list.php"><span class="fa fa-money">&nbsp;</span> Penyetoran pajak</a></li>
					<li><a class="" href="jasa_giro.php"><span class="fa fa-external-link-square">&nbsp;</span> Jasa Giro</a></li>
					<li><a class="" href="lap_pu_dan_transaksi.php"><span class="fa fa-book">&nbsp;</span> Lap. Transaksi</a></li>
					<li><a class="" href="buku_kas_bank.php"><span class="fa fa-book">&nbsp;</span> Buku Kas Bank</a></li>
					<li><a class="" href="laporan_rekening.php"><span class="fa fa-book">&nbsp;</span> Laporan Rek. Bank</a></li>
				</ul>
			</li>
			<li class="parent ">
				<a data-toggle="collapse" href="#si_tools">
					<em class="fa fa-wrench fa-lg">&nbsp;</em> Tools
					<span data-toggle="collapse" href="#sub-item-1" class="icon pull-right"><em class="fa fa-plus"></em></span>
				</a>
				<ul class="children collapse" id="si_tools">
					<li><a class="" href="tools_spby.php"><span class="fa fa-money">&nbsp;</span> Ubah Tgl. SPBy</a></li>
					<li><a class="" href="tools_spp_spm_vs_pagu.php"><span class="fa fa-edit">&nbsp;</span> Cek Angka Pagu</a></li>
				</ul>
			</li>
			<li class="parent ">
				<a data-toggle="collapse" href="#si_data_pagu">
					<em class="fa fa-briefcase fa-lg">&nbsp;</em> Data Pagu
					<span data-toggle="collapse" href="#sub-item-1" class="icon pull-right"><em class="fa fa-plus"></em></span>
				</a>
				<ul class="children collapse" id="si_data_pagu">
					<li><a class="" href="data_pagu_pengaturan_pagu.php"><span class="fa fa-plus-square">&nbsp;</span> Pengaturan Pagu</a></li>
					<li><a class="" href="data_pagu_manajemen_anggaran.php"><span class="fa fa-bar-chart">&nbsp;</span> Manajemen Angg.</a></li>
					<li><a class="" href="data_pagu_pratinjau.php"><span class="fa fa-edit">&nbsp;</span> Pratinjau</a></li>
				</ul>
			</li>
			<li class="admin_nav"><a href="list_pegawai.php"><em class="fa fa-address-card fa-lg">&nbsp;</em> Data Pegawai</a></li>
			<li class="admin_nav"><a href="list_pengguna.php"><em class="fa fa-user fa-lg">&nbsp;</em> Data Pengguna</a></li>
			<li><a href="list_data_arsip.php"><em class="fa fa-file fa-lg">&nbsp;</em> Arsip Data</a></li>
			<li class="parent ">
				<a data-toggle="collapse" href="#si_konfigurasi">
					<em class="fa fa-cogs fa-lg">&nbsp;</em> Konfigurasi
					<span data-toggle="collapse" href="#sub-item-1" class="icon pull-right"><em class="fa fa-plus"></em></span>
				</a>
				<ul class="children collapse" id="si_konfigurasi">
					<li><a class="" href="konfigurasi.php"><span class="fa fa-cubes">&nbsp;</span> Variabel Aplikasi</a></li>
					<li><a class="" href="ttd_dokumen.php"><span class="fa fa-handshake-o">&nbsp;</span> TTD Dokumen</a></li>
				</ul>
			</li>
			<li><a href="logout.php"><em class="fa fa-power-off fa-lg">&nbsp;</em> Logout</a></li>
		</ul>
	</div><!--/.sidebar-->
		
	<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
		{% block content %}{% endblock %}
	</div>	<!--/.main-->
		
</body>
</html>
