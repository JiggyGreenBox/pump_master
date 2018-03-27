<?php

class Sync
{
	private $_db;
	private $_method;	
	private $_getParams = null;
	private $_postParams = null;

	public function __construct($db,$method,$getParams,$postParams){

		$this->_db = $db->getInstance();
		$this->_method = $method;
		$this->_getParams = $getParams;
		$this->_postParams = $postParams;

		$size = sizeof($this->_getParams);

		if($this->_method == 'GET'){
					
			$this->getAllSyncTable($this->_getParams[0]);
			
		} else if ($this->_method == 'POST') {

			$table_name = $this->_postParams['table_name'];
			$id = $this->_postParams['id'];
			$this->updateSyncFromLocal($table_name,$id);
		}
	}
	

	private function getAllSyncTable($pump_id){

		$sql = "SELECT * FROM `sync` WHERE `pump_id` = '".$pump_id."'";
		$this->_db->query($sql);
		$r = $this->_db->resultset();

		$json = array();		

		foreach($r as $row)
		{
			array_push($json, $row);
		}
		echo json_encode($json);
	}


	private function updateSyncFromLocal($table_name,$id){
		$json = array();	

		$sql = "UPDATE `sync` SET `id` = '".$id."' WHERE `table_name` = '".$table_name."';";
		
		$this->_db->query($sql);
		$this->_db->execute();		
		$json['success'] =true;

		echo json_encode($json);
	}

	private function updateSyncTable($table_name){
		date_default_timezone_set("Asia/Kolkata");
		$date = date("Y-m-d H:i:s");
		$unix = strtotime($date);

		//if exists update else insert
		$sql = "SELECT 1 FROM `sync` WHERE `table_name` = '".$table_name."';";

		$this->_db->query($sql);
		$this->_db->execute();

		if($this->_db->rowCount() == 0)
		{
			$sql = "INSERT INTO `sync`(`table_name`, `last_updated`) VALUES ('".$table_name."','".$unix."');";			
		}else{
			$sql = "UPDATE `sync` SET `last_updated`= '".$unix."' WHERE `table_name` = '".$table_name."';";
		}
		$this->_db->query($sql);
		$this->_db->execute();
		
	}
}

?>