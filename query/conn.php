<?php

	$host_name 	= 'localhost';
	// $user_name 	= 'pump_master_user';
	// $password 	= 'pump_master_user123!@#';
	$user_name 	= 'root';
	$password 	= 'toor';
	$db_name 	= 'pump_master';

	$conn = mysqli_connect($host_name, $user_name, $password, $db_name);

	if($conn == FALSE)
	{
		echo 'ERROR: '.mysqli_connect_error($conn);
	}
?>