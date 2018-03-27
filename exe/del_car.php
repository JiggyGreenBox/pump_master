<?php
if(!isset($_SESSION))
{
	session_start();
}
require '../query/conn.php';
	
	if (isset($_POST['car_id'])) {
		$car_id = $_POST['car_id'];

		$sql5 = "UPDATE `cars` SET `status` = 'inactive'  WHERE `car_id` = '".$car_id."' ;";
		$exe5 = mysqli_query($conn, $sql5);

		echo'success';
	}
	
?>