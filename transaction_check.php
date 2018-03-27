<?php

date_default_timezone_set("Asia/Kolkata");
require 'query/conn.php';


$target_url = 'http://pumpmastertest.greenboxinnovations.in/api/exchange';


function getTransactionsJson(){
	Global $conn;
	$json = array();
	// get all transactions on the local machine
	$sql = "SELECT * FROM `transactions` WHERE 1 order by `trans_id` DESC limit 10;";
	$exe = mysqli_query($conn, $sql);
	while($row = mysqli_fetch_assoc($exe)){
		$json_row = array();
		$json_row['trans_id'] 		= $row['trans_id'];
		$json_row['pump_id'] 		= $row['pump_id'];
		$json_row['cust_id'] 		= $row['cust_id'];
		$json_row['car_id'] 		= $row['car_id'];
		$json_row['receipt_no'] 	= $row['receipt_no'];
		$json_row['shift'] 			= $row['shift'];
		$json_row['fuel'] 			= $row['fuel'];
		$json_row['amount'] 		= $row['amount'];
		$json_row['rate'] 			= $row['rate'];
		$json_row['liters'] 		= $row['liters'];
		$json_row['billed'] 		= $row['billed'];
		$json_row['date'] 			= $row['date'];
		$json_row['last_updated'] 	= $row['last_updated'];
		array_push($json, $json_row);
	}
	return json_encode($json, JSON_NUMERIC_CHECK);
}


$payload = getTransactionsJson();

$proceed = false;
$ch = curl_init();
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch, CURLOPT_URL,$target_url);
curl_setopt($ch, CURLOPT_TIMEOUT, 40);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

if($result = curl_exec ($ch)){
	$proceed = true;
}
curl_close ($ch);


// server is up
if($proceed){
	$output = json_decode($result, true);
	// print_r($output);

	foreach ($output as $key => $value) {
		$trans_id = $value;
		// $sql = "DELETE FROM `transactions` WHERE `trans_id` = '".$trans_id."';";
		// $exe = mysqli_query($conn, $sql);
	}
}
else{
	echo 'something went wrong';
}

?>