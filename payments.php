<?php
require 'exe/lock.php';
?>
<!DOCTYPE html>
<html>
<head>
	<title>Payments</title>

	<!-- favicons -->
	<link rel="apple-touch-icon" sizes="57x57" href="css/favi/apple-icon-57x57.png">
	<link rel="apple-touch-icon" sizes="60x60" href="css/favi/apple-icon-60x60.png">
	<link rel="apple-touch-icon" sizes="72x72" href="css/favi/apple-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="76x76" href="css/favi/apple-icon-76x76.png">
	<link rel="apple-touch-icon" sizes="114x114" href="css/favi/apple-icon-114x114.png">
	<link rel="apple-touch-icon" sizes="120x120" href="css/favi/apple-icon-120x120.png">
	<link rel="apple-touch-icon" sizes="144x144" href="css/favi/apple-icon-144x144.png">
	<link rel="apple-touch-icon" sizes="152x152" href="css/favi/apple-icon-152x152.png">
	<link rel="apple-touch-icon" sizes="180x180" href="css/favi/apple-icon-180x180.png">
	<link rel="icon" type="image/png" sizes="192x192"  href="css/favi/android-icon-192x192.png">
	<link rel="icon" type="image/png" sizes="32x32" href="css/favi/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="96x96" href="css/favi/favicon-96x96.png">
	<link rel="icon" type="image/png" sizes="16x16" href="css/favi/favicon-16x16.png">
	<link rel="manifest" href="css/favi/manifest.json">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-TileImage" content="css/favi/ms-icon-144x144.png">
	<meta name="theme-color" content="#ffffff">


	<link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Roboto+Slab" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<style type="text/css">
		*{padding: 0;margin: 0;}
		/*#transactions_table{background-color: yellow;}*/
		.tab{display: inline-block;font-weight: 600;padding: 5px;margin-right: 10px;color: rgb(150,150,150);border-bottom: 3px solid transparent;}
		/*.tab_active{border-bottom: 3px solid orange;color: orange;}
		.tab:hover{border-bottom: 3px solid orange;cursor: pointer;}*/
		/*.tab_active{border-bottom: 3px solid rgb(41,169,201);color: rgb(41,169,201);}
		.tab:hover{border-bottom: 3px solid rgb(41,169,201);cursor: pointer;}*/
		.tab_active{border-bottom: 3px solid rgb(29,182,167);color: rgb(29,182,167);}
		.tab:hover{border-bottom: 3px solid rgb(29,182,167);cursor: pointer;}

		th{text-align: left;}
		th.th_num{text-align: right;padding-left: 10px;}
		/*tr:nth-child(odd){background-color: rgb(207,216,220);}*/
		tr:nth-child(odd){background-color: rgb(222,228,231);}
		/*tr:nth-child(odd){background-color: rgb();}*/

		/*.td_num{text-align: right;}*/
		#display{padding-top: 50px;margin-bottom: 30px;}
		#name{font-family: 'Roboto Slab', serif;font-size: 40px;margin-bottom: 10px;}
		#ph_no{font-weight: 600; color: rgb(100,100,100);}
		#bal{font-weight: 700;color: rgb(100,100,100);}
		/*#bal span{font-weight: 700;color: rgb(100,100,100);}*/

		/*car plate no*/
		input[type=number]::-webkit-inner-spin-button, 
		input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none;margin: 0; }
		#in_car_no_plate_state{width: 30px;text-transform:uppercase;}		
		#in_car_no_plate_city{width: 20px;}
		#in_car_no_plate_letter{width: 30px;text-transform:uppercase;}
		#in_car_no_plate_number{width: 40px;}

		input{padding: 5px;}
		select{padding: 5px;margin-top: 5px;width: 250px;}
		.form_header{font-weight: 700;margin: 10px auto;padding-left: 2px;color: rgb(100,100,100);}


		.transactions_fab{background: url('css/icons/ic_edit.png') no-repeat center center;}
		.cars_fab{background: url('css/icons/ic_car.png') no-repeat center center;}
		.payments_fab{background: url('css/icons/ic_pay.png') no-repeat center center;}



		 .change_this{width:300px;}

		/*change_this*/
		@media only screen and (max-width: 1360px) {			
			.change_this{width: 200px;}
		}
	</style>

	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/main.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){

			// functions
			function windowScroll(){
				if(scrollFunc){
					var offset = $(this).scrollTop();					

					if (offset >= tableOffset && $fixedHeader.is(":hidden")) {
						$fixedHeader.show();
					}
					else if (offset < tableOffset) {
						$fixedHeader.hide();
					}
				}
			}

			function scrollInit(){
				scrollFunc = true;
				var table = $("#table-1");
				if (table.length) {
				  tableOffset = $("#table-1").offset().top;
				}
				
				$header 	= $("#table-1 > thead").clone();
				$fixedHeader = $("#header-fixed").append($header);
			}

			function init(){
				$('#display').load('display/view_payments.php', scrollInit);
			}

			// globals
			init();
			var scrollFunc = false;
			var tableOffset, $header, $fixedHeader;

			// window 
			$(window).bind("scroll", windowScroll);

			// click functions
		});
	</script>
</head>
<body>

<!-- app nav -->
<div id="app_bar">  
	<div id="menu">
		<img src="css/icons/ic_menu.png">
	</div>
	<div id="app_name"><a href="index.php">PumpMaster</a></div>
</div>

<!-- side nav -->
<?php 
	$active_page = 'customers';
	require'nav.php';
?>


<!-- wrapper -->
<div id="wrapper">
	<div id="wrapper_content">
		<div id="display"></div>		
	</div>
</div>

<!-- snackbar -->
<div id="snackbar"></div>

<!-- fab -->
<!-- <div id="fab"></div> -->


</body>
</html>