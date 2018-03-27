<?php
date_default_timezone_set("Asia/Kolkata");

class users
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
			
			switch ($size) {
				case 0:
					//$this->getUsers();
					break;
				case 1:
					$this->getUsers($this->_getParams[0]);
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
		}
	}

	private function getUsers($pump_id){
		$sql = "SELECT * FROM `users` WHERE `user_pump_id` = '".$pump_id."' AND `role` = 'operator' ;";
		$this->_db->query($sql);
		$this->_db->execute();

		$output=array();
		$json=array();
		
		if($this->_db->rowCount() > 0)
		{
			$r = $this->_db->resultset();
			foreach ($r as $row) {
				
				$json["user_id"]		= $row["user_id"];
				$json["name"]	 		= $row["name"];
				$json["user_pump_id"]	= $row["user_pump_id"];
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