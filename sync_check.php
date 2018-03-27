<?php
date_default_timezone_set("Asia/Kolkata");
require 'query/conn.php';


function queryServer(){

	Global $conn;
	$proceed = false;
	$target_url = 'http://pumpmastertest.greenboxinnovations.in/api/sync/1';
		
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$target_url);
	curl_setopt($ch, CURLOPT_TIMEOUT, 40);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

	if($result = curl_exec ($ch)){
		$proceed = true;
	}
	curl_close ($ch);


	// server is up
	if($proceed){
		// echo $result;
		$output = json_decode($result, true);


		foreach ($output as $key => $value) {

			$sync_id 		= $value['sync_id'];
			$table_name 	= $value['table_name'];
			$id 			= $value['id'];
			$last_updated 	= $value['last_updated'];


			$sql 	= "SELECT * FROM `sync` WHERE `table_name` = '".$table_name."';";
			$exe = mysqli_query($conn, $sql);
			$row = mysqli_fetch_assoc($exe);

			if($table_name == 'rates' || $table_name == 'transactions'){
				// if($row['id'] > $id){
				// 	echo 'upload '.$table_name;
				// 	echo '<br>';
				// }

				// else if($row['id'] < $id){
				// 	echo 'download '.$table_name;
				// 	echo '<br>';
				// }
			}
			else{
				if($row['last_updated'] != $last_updated){
					echo 'download '.$table_name;
					echo '<br>';
					downloadTable($table_name, $last_updated);
				}
				// else{
				// 	echo 'nothing to download';
				// }
			}		
		}	
	}
	else{
		echo 'Something went Wrong';
	}
}

function updateServerId($table_name, $id){

	Global $conn;
	$proceed = false;	

	$post = [
		'table_name' => $table_name,
		'id' => $id		
	];
	$data_string = json_encode($post);

	$target_url = "http://pumpmastertest.greenboxinnovations.in/api/sync";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $target_url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 40);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);

	// if server is up and file is available {proceed = true}
	if($result = curl_exec ($ch)){
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		if($http_code == 200) {
			$proceed = true;			
		}		
	}
	else{
		echo 'no result';
	}
	curl_close($ch);

	if($proceed){
		echo 'proceed';
	}		
}


function downloadTable($table_name, $last_updated){

	Global $conn;
	$proceed = false;

	$target_url = "http://pumpmastertest.greenboxinnovations.in/mysql_uploads/".$table_name.".sql";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $target_url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 40);

	// if server is up and file is available {proceed = true}
	if($result = curl_exec ($ch)){
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		if($http_code == 200) {
			$proceed = true;			
		}		
	}	
	curl_close($ch);

	if($proceed){		
		$destination = "mysql_dump/".$table_name.".sql";
		$file = fopen($destination, "w+");
		fputs($file, $result);
		fclose($file);

		// linux
		// exec('/usr/bin/mysql -u"neon_online" --password="neon_online123!@#"  "neon_online" < '.$table_name.".sql".);

		// windows
		exec('C:/xampp/mysql/bin/mysql -u"root" --password="toor"  "pump_master" < '.$destination);

		// update local sync table
		switch ($table_name) {
			case 'cars':
				$prim_key = 'car_id';
				break;
			case 'users':
				$prim_key = 'user_id';
				break;
			case 'customers':
				$prim_key = 'cust_id';
				break;
			default:
				$prim_key = '';
				break;
		}


		if($prim_key != ''){
			$sql = "SELECT `".$prim_key."` as 'new_id' FROM `".$table_name."` WHERE 1 ORDER BY `".$prim_key."` DESC LIMIT 1;";
			$exe = mysqli_query($conn, $sql);
			$row = mysqli_fetch_assoc($exe);
			$new_id = $row['new_id'];


			$sql3 = "UPDATE `sync` SET `last_updated`= '".$last_updated."', `id` = '".$new_id."' WHERE `table_name` = '".$table_name."';";
			$exe3 = mysqli_query($conn ,$sql3);

			updateServerId($table_name, $new_id);
		}
	}	
}

queryServer();

?>