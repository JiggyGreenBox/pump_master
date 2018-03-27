<!DOCTYPE html>
<html>
<head>
	<title>Title</title>
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			// setInterval(function(){
			// 	$('#d1 .driver_time').fadeIn(500);
			// 	$('#d1 .driver_time').fadeOut(500);
			// 	$('#d1 .driver_time').fadeIn(500);
			// 	$('#d1 .driver_time').fadeOut(500);
			// },2000);
		});
	</script>
	<style type="text/css">
		*{padding: 0;margin: 0;}
		body{background-color: black;color: white;font-family: helvetica;font-weight: bold;}
		.main_4{width: 50%;display: inline-block;height: 50vh;margin-right: -4px;vertical-align: top;}


		#d1{}
		/*driver*/
		.driver_main{width: 80%;margin: 0 auto;color: rgb(255,201,14);position: relative;top: 50%;transform: translateY(-50%);}		
		.driver_lap{font-size: 4.5em;}
		.driver_time{font-size: 6.4em;}
		.driver_name{font-size: 3.5em;text-align: right;}
		.driver_kart{font-size: 3em;text-align: right;}
		
	</style>
</head>
<body>

<div class="main_4" id="d1">
	<div class="driver_main">	
		<div class="driver_lap">LAP 1</div>
		<div class="driver_time">1:05:345</div>	
		<div class="driver_name">AKSHAY</div>	
		<div class="driver_kart">KART 02</div>
	</div>
</div>

<div class="main_4" id="d2" >
	<div class="driver_main">	
		<div class="driver_lap">LAP 3</div>
		<div class="driver_time">49:345</div>	
		<div class="driver_name">VINAY</div>	
		<div class="driver_kart">KART 05</div>
	</div>	
</div>

<div class="main_4" id="d3">
	<div class="driver_main">	
		<div class="driver_lap">LAP 2</div>
		<div class="driver_time">1:34:345</div>	
		<div class="driver_name">RAHUL</div>	
		<div class="driver_kart">KART 06</div>
	</div>
</div>

<div class="main_4" id="d4">
	<div class="driver_main">	
		<div class="driver_lap">LAP 4</div>
		<div class="driver_time">58:345</div>	
		<div class="driver_name">NARENDRA</div>	
		<div class="driver_kart">KART 01</div>
	</div>
</div>

</body>
</html>