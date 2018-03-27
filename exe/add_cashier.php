<?php

require '../query/conn.php';

if(!isset($_SESSION))
{
	session_start();
}



function updateSyncTable($table_name, $id, $unix){
	Global $conn;	

	// get previous id
	$sql 	= "SELECT * FROM `sync` WHERE `table_name` = '".$table_name."';";
	$exe = mysqli_query($conn ,$sql);

	if(mysqli_num_rows($exe) > 0){
		$row = mysqli_fetch_assoc($exe);
		$old_id = $row['id'];
	}
	
	
	// $upload_dir =  realpath(__DIR__ . '/../../mysql_uploads');
	// $filename = $upload_dir ."/".$table_name.'.sql';

	$upload_dir =  realpath(__DIR__ . '/../');
	$filename = $upload_dir ."/mysql_uploads/".$table_name.'.sql';

	$db_name = "pump_master_test";
	
	exec("/usr/bin/mysqldump -u\"pump_master_user\" --password=\"pump_master_user123!@#\"  -t \"".$db_name."\" \"".$table_name."\"  --where=\"".$id." > '".$old_id."' \" > ".$filename);

	// if exists update else insert
	$sql1 = "SELECT MAX(".$id.") as d FROM ".$table_name." WHERE 1 LIMIT 1;";
	$exe1 = mysqli_query($conn ,$sql1);
	if(mysqli_num_rows($exe1) > 0){

		$row1 = mysqli_fetch_assoc($exe1);
		$new_id = $row1['d'];

		$sql3 = "UPDATE `sync` SET `last_updated`= '".$unix."', `id` = '".$new_id."' WHERE `table_name` = '".$table_name."';";
		$exe3 = mysqli_query($conn ,$sql3);
	}	
}



if(isset($_POST['new_cashier'])){

	$sql = "SELECT * FROM `users` WHERE `user_pump_id` = '".$_SESSION['pump_id']."' AND `name` = '".$_POST['new_cashier']."' ;";
	$exe = mysqli_query($conn, $sql);
	$count = mysqli_num_rows($exe);

	if ($count < 1) {
		$sql = "INSERT INTO `users`(`name`,`pass`,`user_pump_id`,`role`) VALUES('".$_POST['new_cashier']."','12345','".$_SESSION['pump_id']."','operator') ;";
		$exe = mysqli_query($conn, $sql);		

		echo'User added successfully';

		date_default_timezone_set("Asia/Kolkata");
		$date_new = date("Y-m-d H:i:s");
		$unix = strtotime($date_new);

		updateSyncTable("users", "user_id", $unix);
	}else{
		echo'Duplicate name';
	}	
	
}






?>