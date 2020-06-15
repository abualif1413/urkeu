<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Urkeu</title>
	<link href="{{ url('lumino-template/lumino/css/bootstrap.css') }}" rel="stylesheet">
	<link href="{{ url('lumino-template/lumino/css/font-awesome.min.css') }}" rel="stylesheet">
	<link href="{{ url('lumino-template/lumino/css/datepicker3.css') }}" rel="stylesheet">
	<link href="{{ url('lumino-template/lumino/css/styles.css') }}" rel="stylesheet">
	
	<script src="{{ url('lumino-template/lumino/js/jquery-1.11.1.min.js') }}"></script>
	<script src="{{ url('lumino-template/lumino/js/bootstrap.min.js') }}"></script>
	<script src="{{ url('lumino-template/lumino/js/chart.min.js') }}"></script>
	<script src="{{ url('lumino-template/lumino/js/chart-data.js') }}"></script>
	<script src="{{ url('lumino-template/lumino/js/easypiechart.js') }}"></script>
	<script src="{{ url('lumino-template/lumino/js/easypiechart-data.js') }}"></script>
	<script src="{{ url('lumino-template/lumino/js/bootstrap-datepicker.js') }}"></script>
	<script src="{{ url('lumino-template/lumino/js/custom.js') }}"></script>
	<script>
		
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
<body style="padding: 2px;">
	@yield('content')
</body>
</html>
