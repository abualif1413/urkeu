{% extends "layout_page/home.php" %}

{% block content %}
	<script type="text/javascript" charset="utf-8">
		var myLayout;
		var myGridTempInput;
		
		function showLayout() {
			var tinggi_layout = window.innerHeight - 195;
			$("#layoutObj").css("height", tinggi_layout);
			myLayout = new dhtmlXLayoutObject({
				parent: "layoutObj",
				pattern: "2U",
				cells: [
					{id: "a", text: "Daftar Pengeluaran Dana"},
					{id: "b", text: "Input Data Pengeluaran Dana", width: 500}
				]
			});
			
			myLayout.cells("b").attachObject("innerInputData");
		}
		
		function showGridTempInput() {
			myGridTempInput = new dhtmlXGridObject("innerInputData_gridMaterial");
			myGridTempInput.setImagePath("../../../codebase/imgs/")
			myGridTempInput.setHeader("No., Nama Material, Qty, Satuan, Harga @, Keterangan"); //the headers of columns
			myGridTempInput.setInitWidths("30, 200, 80, 100, 120, 200");           //the widths of columns
			myGridTempInput.setColAlign("right,left, right, left, right, left");        //the alignment of columns
			myGridTempInput.setColTypes("ro, ro, ro, ro, ro, ro");                 //the types of columns
			myGridTempInput.setColSorting("int, str, int, str, int, str");           //the sorting types
			myGridTempInput.init();      							   //finishes initialization and renders the grid on the page 
			
		}
		
		$(document).ready(function() {
			showLayout();
			showGridTempInput();
		});
	</script>
    <div id="layoutObj" style="width: 100%;"></div>
    <div id="innerInputData" style="overflow: auto; width: 100%; height: 100%;">
    	<div id="innerInputData_gridMaterial" style="width: 750px;"></div>
    	<div id="innerInputData_tempInput" style="width: 750px;"></div>
    </div>
{% endblock %}
