<?php
date_default_timezone_set("Asia/Kolkata");

class Transactions
{
	private $_db;
	private $_method;
	private $_getParams = null;
	private $_postParams = null;

	public function __construct($db, $method, $getParams, $postParams){

		$this->_db = $db->getInstance();
		$this->_method = $method;
		$this->_getParams = $getParams;
		$size = sizeof($this->_getParams);
		$this->_postParams = $postParams;

		if($this->_method=='GET')
		{
			$today = date("Y-m-d");
			switch ($size) {
				case 0:
					// $this->getAllCarsShifts(0,$today);				
					break;
				case 1:
					//$this->getAllCarsShifts(0,$today);
					break;
				case 2:
					//$this->getAllCarsShifts(0,$today);
					break;
				default:
					$output = array();
					$output['error'] = 'no date provided';
					echo json_encode($output);
					break;
			}
		}
		elseif ($this->_method == 'POST')
		{
			if ($this->_getParams[0] == 'backup')
			{
				$this->postTransactionBackup($this->_postParams);
			}

			if ($this->_getParams[0] == 'regular')
			{
				$this->postTransaction($this->_postParams);
			}

			if ($this->_getParams[0] == 'android')
			{
				$this->postAndroidTransaction($this->_postParams);
			}	

			if ($this->_getParams[0] == 'rates')
			{
				$this->postRates($this->_postParams);
			}	
			if ($this->_getParams[0] == 'delete')
			{
				$this->deleteTransaction($this->_postParams);
			}		
		}
	}


	private function postTransaction($postParams){

		if(!isset($_SESSION))
		{
			session_start();
		}

		date_default_timezone_set("Asia/Kolkata");

		$valid = false;
		$output = array();


		$type 		= $postParams['type'];

		$pump_id		 = $postParams['pump_id'];
		$pump_id		 = trim($pump_id);

		if ($pump_id == -2) {
			$pump_id = $_SESSION['pump_id'];
		}

		$cust_id		 = $postParams['cust_id'];
		$cust_id	 	 = trim($cust_id);

		$car_id			 = $postParams['car_id'];
		$car_id	 		 = trim($car_id);



		if ($car_id == -1) {
			$fuel 			= $postParams['fuel'];
			$car_no_plate 	= $postParams['car_no_plate'];


			$sql = "INSERT INTO `cars` (`car_brand`,`car_sub_brand`,`car_no_plate`,`car_pump_id`,`car_fuel_type`,`car_cust_id`) VALUES ('unknown','unknown',:field1,:field4,:field2,:field3);";

			$this->_db->query($sql);
			$this->_db->bind(':field1', $car_no_plate);
			$this->_db->bind(':field2', $fuel);
			$this->_db->bind(':field3', $cust_id);
			$this->_db->bind(':field4', $pump_id);
			$this->_db->execute();


			$sql = "SELECT `car_id` FROM `cars` WHERE `car_no_plate` = '".$car_no_plate."';";
			$this->_db->query($sql);
			$this->_db->execute();
			$row = $this->_db->single();
			$car_id = $row['car_id'];
		}
		else{
			$sql = "SELECT `car_fuel_type` FROM `cars` WHERE `car_id` = '".$car_id."';";		
			$this->_db->query($sql);
			$r = $this->_db->single();
			$fuel = $r['car_fuel_type'];
		}

		$receipt_no	 = $postParams['receipt_no'];
		$receipt_no	 = trim($receipt_no);

		if (($receipt_no != 0)&&($type == 'new')) {

			$sql = "SELECT `cust_id` FROM `receipt_books` WHERE '".$receipt_no."' BETWEEN `min` AND `max` AND '".$receipt_no."' NOT IN (SELECT `receipt_no` FROM `transactions` WHERE `receipt_no` = '".$receipt_no."') ;";
			$this->_db->query($sql);
			$this->_db->execute();

			if($this->_db->rowCount() > 0){
				$r = $this->_db->single();

				if ($cust_id == $r['cust_id']) {
					$valid = true;
				}
				else{
					$output['success'] = false;
					$output['msg'] = 'Wrong Receipt No';
				}			
			}
			else{
				$output['success'] = false;
				$output['msg'] = 'Wrong Receipt No';
			}
		}
		else{
			$valid = true;
		}

		$user_id			 = $postParams['user_id'];
		$user_id	 		 = trim($user_id);
		

		$is_postpaid	 = $postParams['is_postpaid'];

		$amount		 	 = $postParams['amount'];
		$liters		 	 = number_format($postParams['liters'],2);

		$rate 			 = $postParams['rate'];
	
		$date = date("Y-m-d H:i:s" , strtotime($postParams['date']));

		if ($user_id == -2) {
			$user_id = $_SESSION['user_id'];			
			$time = date("H:i:s");
			$date = $postParams['date']." ".$time;
		}

		$this->updateOldRate($rate, $date, $fuel, $pump_id);

		$last_updated	 = $date;		

		$trans_id 	= $postParams['trans_id'];

		if ($postParams['shift'] != NULL) {
			$shift	 		 = $postParams['shift'];
		}else{
			$output['success'] = false;
			$valid = false;
		}

		if ($valid) {
			if ($type == 'new') {
				
				$sql = "INSERT INTO `transactions` (`pump_id`,`cust_id`,`car_id`,`user_id`,`receipt_no`,`fuel`,`amount`,`rate`,`liters`,`date`,`last_updated`,`shift`) 
						VALUES (:field1,:field2,:field3,:field4,:field5,:field6,:field7,:field8,:field9,:field10,:field11,:field12);";

				$this->_db->query($sql);

				$this->_db->bind(':field1', $pump_id);
				$this->_db->bind(':field2', $cust_id);
				$this->_db->bind(':field3', $car_id);
				$this->_db->bind(':field4', $user_id);
				$this->_db->bind(':field5', $receipt_no);
				$this->_db->bind(':field6', $fuel);
				$this->_db->bind(':field7', $amount);
				$this->_db->bind(':field8', $rate);
				$this->_db->bind(':field9', $liters);
				$this->_db->bind(':field10', $date);
				$this->_db->bind(':field11', $last_updated);
				$this->_db->bind(':field12', $shift);
				$this->_db->execute();


				if ($is_postpaid) {
					$sql = "UPDATE `customers` SET `cust_outstanding` = `cust_outstanding`+ '".$amount."' WHERE `cust_id` = '".$cust_id."' ;";
				}else{
					$sql = "UPDATE `customers` SET `cust_balance` = `cust_balance`- '".$amount."' WHERE `cust_id` = '".$cust_id."' ;";
				}
				$this->_db->query($sql);
				$this->_db->execute();

			}
			else{

				$sql = "SELECT `amount` FROM `transactions` WHERE `trans_id` = '".$trans_id."';";
				$this->_db->query($sql);
				$this->_db->execute();
				$row = $this->_db->single();
				$old_amount = $row['amount'];


				$last_updated = date("Y-m-d H:i:s");


				$sql = "UPDATE `transactions` SET `amount`= :field1,`rate`= :field2, `shift` = :field4, `liters`= :field3,`date` = :field5, `last_updated`= :field6 WHERE `trans_id` = :field7 ;";

				$this->_db->query($sql);

				$this->_db->bind(':field1', $amount);
				$this->_db->bind(':field2', $rate);
				$this->_db->bind(':field3', $liters);
				$this->_db->bind(':field4', $shift);
				$this->_db->bind(':field5', $date);
				$this->_db->bind(':field6', $last_updated);
				$this->_db->bind(':field7', $trans_id);
				$this->_db->execute();


				$new_amount = $amount - $old_amount;


				if ($is_postpaid) {
					$sql = "UPDATE `customers` SET `cust_outstanding` = `cust_outstanding`+ '".$new_amount."' WHERE `cust_id` = '".$cust_id."' ;";
				}else{
					$sql = "UPDATE `customers` SET `cust_balance` = `cust_balance`- '".$new_amount."' WHERE `cust_id` = '".$cust_id."' ;";
				}
				$this->_db->query($sql);
				$this->_db->execute();

			}


			$table_name	  = "transactions";
			$id           = "trans_id";
			$unix = strtotime($last_updated); 

			$this->updateSyncTable($table_name, $id, $unix);

		
			$output['success'] = true;
		}

		
		echo json_encode($output);
	}

	private function postTransactionBackup($postParams){

		date_default_timezone_set("Asia/Kolkata");

		$data = $postParams['backup_data']; 

		$output = array();
		$response = array();

		foreach ($data as $obj) {

			$json = array();

			$json['trans_id']= $obj['trans_id'];

			$pump_id		 = $obj['pump_id'];
			$pump_id		 = trim($pump_id);

			$cust_id		 = $obj['cust_id'];
			$cust_id	 	 = trim($cust_id);

			$car_id			 = $obj['car_id'];
			$car_id	 		 = trim($car_id);

			if ($car_id == -1) {
				$fuel = $postParams['fuel'];
			}else{
				$sql = "SELECT `car_fuel_type` FROM `cars` WHERE `car_id` = '".$car_id."';";		
				$this->_db->query($sql);
				$r = $this->_db->single();
				$fuel = $r['car_fuel_type'];
			}

			$user_id			 = $postParams['user_id'];
			$user_id	 		 = trim($user_id);

			$is_postpaid	 = $obj['is_postpaid'];
		
			$date = date("Y-m-d H:i:s" , strtotime($obj['date']));

			$last_updated	 = $date;		

			$amount		 	 = $postParams['amount'];
			$liters		 	 = $postParams['liters'];

			$rate 			 = $amount/$liters;
			$rate 			 = number_format(round($rate,2),2) ;
			//////////////////////////////
			///////////////////////////////
			// CHANGE THIS to $rate  = $postParams['rate']
			////////////////////////////
			///////////////////////////////
			////////////////////////////////
			///////////////////////////////

				
			$sql = "INSERT INTO `transactions` (`pump_id`,`cust_id`,`car_id`,`amount`,`date`,`last_updated`,`liters`,`rate`,`fuel`,`user_id`) VALUES (:field1,:field2,:field3,:field4,:field5,:field6,:field7,:field8,:field9,:field10);";

			$this->_db->query($sql);

			$this->_db->bind(':field1', $pump_id);
			$this->_db->bind(':field2', $cust_id);
			$this->_db->bind(':field3', $car_id);
			$this->_db->bind(':field4', $amount);
			$this->_db->bind(':field5', $date);
			$this->_db->bind(':field6', $last_updated);
		    $this->_db->bind(':field7', $liters);
		    $this->_db->bind(':field8', $rate);
		    $this->_db->bind(':field9', $fuel);
		    $this->_db->bind(':field10',$user_id);

			$this->_db->execute();

			if ($is_postpaid) {
				$sql = "UPDATE `customers` SET `cust_outstanding` = `cust_outstanding`+ '".$amount."' WHERE `cust_id` = '".$cust_id."' ;";
			}else{
				$sql = "UPDATE `customers` SET `cust_balance` = `cust_balance`- '".$amount."' WHERE `cust_id` = '".$cust_id."' ;";
			}


			$this->_db->query($sql);
			$this->_db->execute();


			$table_name	  = "customers";
			$last_updated = strtotime($last_updated); 

			$sql = "UPDATE `sync` SET `last_updated` = :field2 WHERE `table_name` = :field1;";

			$this->_db->query($sql);

			$this->_db->bind(':field1', $table_name);
			$this->_db->bind(':field2', $last_updated);

			$this->_db->execute();

			array_push($response, $json);

			$output['success'] = true;

			$table_name	  = "transactions";
			$id           = "trans_id";
			$unix = strtotime($last_updated); 

			$this->updateSyncTable($table_name,$id,$unix);

		}

		$output['data'] = $response;
		echo json_encode($output);
	}


	private function postAndroidTransaction($postParams){

		date_default_timezone_set("Asia/Kolkata");

		$valid = false;
		$output = array();


		$pump_id		= trim($postParams['pump_id']);		

		$car_id			= trim($postParams['car_id']);		

		$cust_id		= trim($postParams['cust_id']);		

		$isPetrol		= $postParams['isPetrol'];
		$fuel 			= $isPetrol ? 'petrol' : 'diesel';

		$user_id		= trim($postParams['user_id']);		


		$amount		 	= trim($postParams['amount']);		
		$liters		 	= number_format($postParams['liters'],2);

		$rate 			= trim($postParams['fuel_rate']);
		
		$pre_shift	 	= trim($postParams['shift']);
		$shift 			= ($pre_shift == "a") ? 1 : 2;

		$date 			= date("Y-m-d H:i:s");
		$last_updated	= $date;


		$sql_pre = "SELECT `last_updated` FROM `transactions` 
					WHERE `pump_id` = :field1
					AND `cust_id` = :field2
					AND `car_id` = :field3
					AND date(`date`) = :field4;";
		$this->_db->query($sql_pre);
		$this->_db->bind(':field1', $pump_id);
		$this->_db->bind(':field2', $cust_id);
		$this->_db->bind(':field3', $car_id);
		$this->_db->bind(':field4', date('Y-m-d'));
		$this->_db->execute();
		
		if($this->_db->rowCount() > 0){
			$r = $this->_db->single();


			$last_found = $r['last_updated'];

			$diff = strtotime($date) - strtotime($last_found);

			// 20 seconds
			if($diff > 20){
				$valid = true;
			}			
		}
		else{
			$valid = true;
		}

		if($valid){
			$sql = "INSERT INTO `transactions` (`pump_id`,`cust_id`,`car_id`,`user_id`,`fuel`,`amount`,`rate`,`liters`,`date`,`last_updated`,`shift`) 
							VALUES (:field1,:field2,:field3,:field4,:field6,:field7,:field8,:field9,:field10,:field11,:field12);";

			$this->_db->query($sql);

			$this->_db->bind(':field1', $pump_id);
			$this->_db->bind(':field2', $cust_id);
			$this->_db->bind(':field3', $car_id);
			$this->_db->bind(':field4', $user_id);		
			$this->_db->bind(':field6', $fuel);
			$this->_db->bind(':field7', $amount);
			$this->_db->bind(':field8', $rate);
			$this->_db->bind(':field9', $liters);
			$this->_db->bind(':field10', $date);
			$this->_db->bind(':field11', $last_updated);
			$this->_db->bind(':field12', $shift);
			$this->_db->execute();

			$output['success'] = true;	

			$table_name	  = "transactions";
			$id           = "trans_id";
			$unix = strtotime($last_updated); 

			$this->updateSyncTable($table_name,$id,$unix);
		}
		else{
			$output['success'] 	= false;		
			$output['msg'] 		= "Something went wrong";		
		}		
		echo json_encode($output);
	}

	private function postRates($postParams){
		date_default_timezone_set("Asia/Kolkata");

		if(!isset($_SESSION))
		{
			session_start();
		}

		$output = array();
		if(isset($postParams['pump_id'])){			
			$pump_id 			= $postParams['pump_id'];
			$output['source'] 	= 'Android';
		}
		else{
			$pump_id 			= $_SESSION['pump_id'];
			$output['source'] 	= 'Web';
		}

		$petrol 	= trim($postParams['petrol']);
		$diesel		= trim($postParams['diesel']);
		
		$date = date("Y-m-d");

		$sql1 = "SELECT * FROM `rates` WHERE `date` = '".$date."';";	
		$this->_db->query($sql1);
		$this->_db->execute();		

		if($this->_db->rowCount() == 0){
			$sql = "INSERT INTO `rates` (`pump_id`,`petrol`,`diesel`,`date`) VALUES (:field1,:field2,:field3,:field4);";
			$this->_db->query($sql);

			$this->_db->bind(':field1', $pump_id);
			$this->_db->bind(':field2', $petrol);
			$this->_db->bind(':field3', $diesel);
			$this->_db->bind(':field4', $date);

			$this->_db->execute();

			
			$output['success'] = true;
			$output['msg'] = 'Rates updated successfully!';

			$table_name	  = "rates";
			$id           = "rate_id";
			$date_new = date("Y-m-d H:i:s");
			$unix = strtotime($date_new); 

			$this->updateSyncTable($table_name,$id,$unix);
			
		}
		else{
			$output['success'] = false;
			$output['msg'] = 'Rates already Set!';
		}
		echo json_encode($output);
	}


	private function updateOldRate($rate, $date, $fuel_type, $pump_id){
		$check_date = date("Y-m-d" , strtotime($date));

		if($fuel_type == 'petrol'){
			$sql = "SELECT `petrol` FROM `rates` WHERE `date` = '".$check_date."';";
		}
		else if($fuel_type == 'diesel'){
			$sql = "SELECT `diesel` FROM `rates` WHERE `date` = '".$check_date."';";
		}

		$this->_db->query($sql);
		$this->_db->execute();		

		// rate not found 
		// insert into rates		
		if($this->_db->rowCount() == 0)
		{
			if($fuel_type == 'petrol'){

				$sql = "INSERT INTO `rates` (`pump_id`,`petrol`,`date`) VALUES (:field1,:field2,:field3);";
				$this->_db->query($sql);
				$this->_db->bind(':field1', $pump_id);
				$this->_db->bind(':field2', $rate);
				$this->_db->bind(':field3', $date);
				$this->_db->execute();
			}
			else if($fuel_type == 'diesel'){

				$sql = "INSERT INTO `rates` (`pump_id`,`diesel`,`date`) VALUES (:field1,:field2,:field3);";
				$this->_db->query($sql);
				$this->_db->bind(':field1', $pump_id);
				$this->_db->bind(':field2', $rate);
				$this->_db->bind(':field3', $date);
				$this->_db->execute();
			}

			$table_name	  = "rates";
			$id           = "rate_id";
			$date_new = date("Y-m-d H:i:s");
			$unix = strtotime($date_new);

			$this->updateSyncTable($table_name,$id,$unix);
		}
		else{
			$row = $this->_db->single();
			if($fuel_type == 'petrol'){
				if($row["petrol"] == '0.00'){

					$sql = "UPDATE `rates` SET `petrol` = :field2 
							WHERE `pump_id` = :field1 AND `date` = :field3;";
					$this->_db->query($sql);
					$this->_db->bind(':field1', $pump_id);
					$this->_db->bind(':field2', $rate);
					$this->_db->bind(':field3', $check_date);
					$this->_db->execute();
				}
			}
			else if($fuel_type == 'diesel'){
				if($row["diesel"] == '0.00'){

					$sql = "UPDATE `rates` SET `diesel` = :field2 
							WHERE `pump_id` = :field1 AND `date` = :field3;";
					$this->_db->query($sql);
					$this->_db->bind(':field1', $pump_id);
					$this->_db->bind(':field2', $rate);
					$this->_db->bind(':field3', $check_date);
					$this->_db->execute();
				}
			}
		}

		
	}

	private function deleteTransaction($postParams){
		
		$trans_id = $postParams['trans_id'];


		$sql = "SELECT `amount`,`cust_id` FROM `transactions` WHERE `trans_id` = '".$trans_id."';";
		$this->_db->query($sql);
		$r = $this->_db->single();
		$amount = $r['amount'];
		$cust_id = $r['cust_id'];


		$sql = "SELECT `cust_post_paid` FROM `customers` WHERE `cust_id` = '".$cust_id."'  ;";
		$this->_db->query($sql);
		$r = $this->_db->single();
		$cust_post_paid = $r['cust_post_paid'];
		if ($cust_post_paid == 'Y') {
			$sql = "UPDATE `customers` SET `cust_outstanding` = `cust_outstanding`-'".$amount."' WHERE `cust_id` = '".$cust_id."' ;";
		}else{
			$sql = "UPDATE `customers` SET `cust_balance` = `cust_balance`+'".$amount."' WHERE `cust_id` = '".$cust_id."' ;";
		}

		$this->_db->query($sql);
		$this->_db->execute();


		$sql = "DELETE FROM `transactions` WHERE `trans_id` = '".$trans_id."' ";
		$this->_db->query($sql);
		$this->_db->execute();

		echo'Transaction deleted Successfully';
	}

	private function updateSyncTable($table_name, $id, $unix){
		date_default_timezone_set("Asia/Kolkata");
		$date = date("Y-m-d H:i:s");

		// get previous id
		$sql2 	= "SELECT * FROM `sync` WHERE `table_name` = '".$table_name."';";
		$this->_db->query($sql2);
		$this->_db->execute();
		$r2 = $this->_db->single();
		$old_id = $r2['id'];
		
		$upload_dir =  realpath(__DIR__ . '/../../mysql_uploads');
		$filename = $upload_dir ."/".$table_name.'.sql';
		$db_name = "pump_master_test";
		
		exec("/usr/bin/mysqldump -u\"pump_master_user\" --password=\"pump_master_user123!@#\"  -t \"".$db_name."\" \"".$table_name."\"  --where=\"".$id." > '".$old_id."' \" > ".$filename);

		$sql = "UPDATE `sync` SET `last_updated`= '".$unix."' WHERE `table_name` = '".$table_name."';";
		
		$this->_db->query($sql);
		$this->_db->execute();

		
	}
}
?>