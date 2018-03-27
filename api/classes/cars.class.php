<?php
date_default_timezone_set("Asia/Kolkata");

class Cars
{
	private $_db;
	private $_method;
	private $_getParams = null;
	private $_postParams = null;

	public function __construct($db,$method,$getParams,$postParams){

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
					// pass pump id
					$this->getAllCarsPump($this->_getParams[0]);
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
			$this->addCars($this->_postParams);
		}
	}


	private function addCars($postParams){
		
		$car_brand	 = $postParams['car_brand'];
		$car_brand	 = trim($car_brand);

		$car_sub_brand	 = $postParams['car_sub_brand'];
		$car_sub_brand	 = trim($car_sub_brand);

		$car_no_plate	 = $postParams['car_no_plate'];
		$car_no_plate	 = trim($car_no_plate);

		$car_fuel_type	 = $postParams['car_fuel_type'];
		$car_fuel_type	 = trim($car_fuel_type);

		$car_cust_id  	 = $postParams['cust_id'];; 
		// $car_cust_id	 = $postParams['car_cust_id'];
		// $car_cust_id	 = trim($car_cust_id);

		$car_qr_code	 = $postParams['car_qr_code'];
		$car_qr_code	 = trim($car_qr_code);



		$sql = "INSERT INTO `cars` (`car_brand`,`car_sub_brand`,`car_no_plate`,`car_fuel_type`,`car_cust_id`,`car_qr_code`) VALUES (:field1,:field2,:field3,:field4,:field5,:field6);";

		$this->_db->query($sql);

		$this->_db->bind(':field1', $car_brand);
		$this->_db->bind(':field2', $car_sub_brand);
		$this->_db->bind(':field3', $car_no_plate);
		$this->_db->bind(':field4', $car_fuel_type);
		$this->_db->bind(':field5', $car_cust_id);
		$this->_db->bind(':field6', $car_qr_code);

		$this->_db->execute();


		date_default_timezone_set("Asia/Kolkata");
		$date = date("Y-m-d H:i:s");		

		$table_name	  = "cars";
		$id           = "car_id";
		$unix = strtotime($date); 

		$this->updateSyncTable($table_name,$id,$unix);
	}



	private function getAllCarsPump($pump_id) {

		$sql = "SELECT * FROM `cars` WHERE `car_pump_id` = '".$pump_id."';";		
		$this->_db->query($sql);
		$this->_db->execute();

		$output=array();
		

		if($this->_db->rowCount() > 0)
		{
			$r = $this->_db->resultset();
			foreach ($r as $row) {
				$json=array();
				$json["car_id"]	 		 = $row["car_id"];
				$json["car_pump_id"]	 = $row["car_pump_id"];
				$json["car_brand"]	     = $row["car_brand"];
				$json["car_sub_brand"]	 = $row["car_sub_brand"];
				$json["car_no_plate"]	 = $row["car_no_plate"];
				$json["car_fuel_type"]	 = $row["car_fuel_type"];
				$json["car_cust_id"]	 = $row["car_cust_id"];
				$json["car_qr_code"]	 = $row["car_qr_code"];
				array_push($output, $json);
			}

		}
		echo json_encode($output,JSON_NUMERIC_CHECK);
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