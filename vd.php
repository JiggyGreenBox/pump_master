<?php

require 'query/conn.php';

$sql = "SELECT `cust_id` FROM `customers` WHERE `cust_post_paid` = 'Y';";
$exe = mysqli_query($conn, $sql);
while($row = mysqli_fetch_assoc($exe)){

		$cust_id = $row['cust_id'];

		$sql1 = "SELECT SUM(`amount`) as amount FROM `transactions` WHERE `cust_id` = ".$cust_id." AND `billed` = 'N';";
		$exe1 = mysqli_query($conn, $sql1);
		$row1 = mysqli_fetch_assoc($exe1);

		$amount = $row1['amount'];


		$sql2 = "UPDATE `customers` SET `cust_outstanding` = '".$amount."' WHERE `cust_id` = ".$row['cust_id']."  ;";
		$exe2 = mysqli_query($conn, $sql2);


}

$sql = "SELECT `cust_id` FROM `customers` WHERE `cust_post_paid` = 'N';";
$exe = mysqli_query($conn, $sql);
while($row = mysqli_fetch_assoc($exe)){

		$cust_id = $row['cust_id'];

		$sql1 = "SELECT SUM(`amount`) as amount FROM `transactions` WHERE `cust_id` = ".$cust_id." AND `billed` = 'N';";
		$exe1 = mysqli_query($conn, $sql1);
		$row1 = mysqli_fetch_assoc($exe1);

		$amount = $row1['amount'];


		$sql2 = "UPDATE `customers` SET `cust_balance` = 100000-'".$amount."' WHERE `cust_id` = ".$row['cust_id']."  ;";
		$exe2 = mysqli_query($conn, $sql2);


}

?>