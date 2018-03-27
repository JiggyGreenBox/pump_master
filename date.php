<!DOCTYPE html>
<html>
<head>
	<title>Title</title>


	<script type="text/javascript" src="js/jquery.js"></script>

	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">

	<script
  src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"
  integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU="
  crossorigin="anonymous"></script>

	<script type="text/javascript">
		$(document).ready(function(){
			function mydate() {
				document.getElementById("dt").hidden=false;
				document.getElementById("ndt").hidden=true;
			}
			function mydate1() {
				d=new Date(document.getElementById("dt").value);
				dt=d.getDate();
				mn=d.getMonth();
				mn++;
				yy=d.getFullYear();
				document.getElementById("ndt").value=dt+"/"+mn+"/"+yy
				document.getElementById("ndt").hidden=false;
				document.getElementById("dt").hidden=true;
			}
			// mydate();


			$('#dt').on('change', mydate1);
			$('#ndt').on('click', mydate);
			$('#date_btn').on('click', mydate);
			


			$( "#date_ui" ).datepicker({ dateFormat: 'dd-mm-yy' });


			$('#click').on('click',function(){
								
				var j_new = $("#date_ui").datepicker("option", "dateFormat", "yy-mm-dd" ).val();
				$("#date_ui").datepicker("option", "dateFormat", "dd-mm-yy" );				
			});
		});
	</script>
	<style type="text/css">
		*{padding: 0;margin: 0;}
		#dt{text-indent: -500px;height:25px; width:200px;}		
	</style>
</head>
<body>

<input type="date" id="dt" hidden/>
<input type="text" id="ndt" value="<?php echo date('d-m-Y'); ?>"/>
<input type="button" id="date_btn" Value="Date" />

<br>
<br>
<input type="text" name="date" id="date_ui" value="<?php echo date('d-m-Y'); ?>">
<br>
<br>
<input type="date" id="chrome_date" value="<?php echo date('Y-m-d'); ?>">
<br>
<button id="click">Click</button>

</body>
</html>