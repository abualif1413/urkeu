<!DOCTYPE html>
<html>
<head>
	<title>Powered By : DhtmlX</title>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
	<link rel="stylesheet" type="text/css" href="../dhtmlx/codebase/fonts/font_roboto/roboto.css"/>
	<link rel="stylesheet" type="text/css" href="../dhtmlx/codebase/dhtmlx.css"/>
	<link rel="stylesheet" type="text/css" href="../css/main.css?"/>
	<script src="../dhtmlx/codebase/dhtmlx.js"></script>
	<script src="../js/jquery-1.11.0.min.js"></script>
	<style>
		html, body {
			width: 100%;
			height: 100%;
			margin: 0px;
			padding: 0px;
		}
	</style>
	<script>
		var myRibbon;
		function doOnLoad() {
			myRibbon = new dhtmlXRibbon({
				parent: "ribbonObj",
				icons_path: "../views/layout_page/ribbon_icons/",
				json: "../views/layout_page/ribbon_menu.json"
			});
			
			myRibbon.attachEvent("onClick", function(id) {
				if(id == "item_3_1") {
					if(confirm("Anda yakin akan logout")) {
						document.location.href = "logout.php";
					}
				} else if(id == "item_1_1") {
					document.location.href = "input_data_pengeluaran_dana.php";
				} else if(id == "item_1_2") {
					document.location.href = "edit_data_pengeluaran.php";
				}
			});
		};
	</script>
</head>
<body onload="doOnLoad();">
	<div id="ribbonObj"></div>
	<div class="content">{% block content %}{% endblock %}</div>
</body>
</html>
