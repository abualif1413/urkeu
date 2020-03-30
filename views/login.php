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
	
	<!--Custom Font-->
	<link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
	<!--[if lt IE 9]>
	<script src="js/html5shiv.js"></script>
	<script src="js/respond.min.js"></script>
	<![endif]-->
	
</head>
<body>
	<div class="row">
		<div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-4 col-md-offset-4">
			<div class="login-panel panel panel-primary">
				<div class="panel-heading"><strong><i class="fa fa-key fa-lg"></i> Aplikasi Urkeu - Log in</strong></div>
				<div class="panel-body">
					<form role="form" action="" method="post">
						<fieldset>
							{% if login_berhasil == -1 %}
								<div class="alert bg-warning" role="alert"><em class="fa fa-lg fa-warning">&nbsp;</em> Username dan password tidak ditemukan</div>
							{% endif %}
							<div class="form-group">
								<input class="form-control" placeholder="Username" name="username" type="username" autofocus="" />
							</div>
							<div class="form-group">
								<input class="form-control" placeholder="Password" name="password" type="password" />
							</div>							
							<button type="submit" class="btn btn-primary" name="login" id="login" value="Login"><i class="fa fa-sign-in fa-lg">&nbsp;</i> Login</button>
						</fieldset>
					</form>
				</div>
			</div>
		</div><!-- /.col-->
	</div><!-- /.row -->	
	

<script src="../views/Lumino Admin Bootstrap Template/lumino/js/jquery-1.11.1.min.js"></script>
<script src="../views/Lumino Admin Bootstrap Template/lumino/js/bootstrap.min.js"></script>
</body>
</html>
