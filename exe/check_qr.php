<?php


date_default_timezone_set("Asia/Kolkata");
require '../query/conn.php';

$json = file_get_contents('php://input');
$obj = json_decode($json,true);

$qr = addslashes($obj['qr']);

// if(isset($_GET['j'])){
// 	$qr = 1;
// }

$json = array();

if($qr != ""){

	// get details from cars
	$sql1 = "SELECT * FROM `cars` WHERE `car_qr_code` = '".$qr."';";
	$exe1 = mysqli_query($conn ,$sql1);

	if(mysqli_num_rows($exe1) > 0){
		// fetch results
		$pre_row = mysqli_fetch_assoc($exe1);
		// details from CARS
		$cust_id 	= $pre_row['car_cust_id'];
		$car_id 	= $pre_row['car_id'];
		$fuel_type 	= $pre_row['car_fuel_type'];
		if($fuel_type == "petrol"){
			$isPetrol = true;
		}
		else{
			$isPetrol = false;
		}
		$car_no 	= $pre_row['car_no_plate'];

		// details from CUSTOMERS
		$sql = "SELECT * FROM `customers` WHERE '".$cust_id."';";
		$exe = mysqli_query($conn ,$sql);
		$row = mysqli_fetch_assoc($exe);

		// display company or name
		$display_name = ucwords($row['cust_company']);
		if($display_name == ""){
			$display_name = ucwords($row['cust_f_name']." ".$row['cust_l_name']);
		}


		// encode results
		$json['success'] 	= true;
		$json['cust_id'] 	= $cust_id;
		$json['cust_name'] 	= $display_name;
		$json['isPetrol'] 	= $isPetrol;
		$json['car_no'] 	= $car_no;
		$json['car_id'] 	= $car_id;

	}
	else{
		// no matches in cars db
		$json['success'] = false;
		$json['msg'] = 'Invalid QR-Code';
	}		
}
else{
	// QR is empty
	$json['success'] = false;
	$json['msg'] = "Empty QR-Code";
}

echo json_encode($json, JSON_NUMERIC_CHECK);

?>