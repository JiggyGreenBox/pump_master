<?php

$table_array = ["cars","users","customers"];
$id_array = ["car_id","user_id","cust_id"];

$table = "users";
$id = 5;

$upload_dir = realpath('./') . '/mysql_uploads/';
$filename = $upload_dir .$table.'.sql';
$db_name = 'pump_master_test';


foreach ($table_array as $key => $value) {
	
	if($value == $table){

		$name_id = $id_array[$key];

		
		exec("/usr/bin/mysqldump -u\"pump_master_user\" --password=\"pump_master_user123!@#\"  -t \"".$db_name."\" \"".$table."\"  --where=\"".$name_id." > '".$id."' \" > ".$filename);
		// exec("/usr/bin/mysqldump -u\"pump_master_user\" --password=\"pump_master_user123!@#\"  -t \"".$db_name."\" \"".$table."\"  > ".$filename);

		// exec ("C:/xampp/mysql/bin/mysqldump -u\"".$user_name."\" --password=\"".$password."\" -t \"".$db_name."\" \"".$table."\"  --where=\"".$p_id." > '".$id."' \">".$filename);		
	}
}






?>