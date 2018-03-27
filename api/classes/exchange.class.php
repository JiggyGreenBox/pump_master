<?php
date_default_timezone_set("Asia/Kolkata");

class Exchange
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
			// $today = date("Y-m-d");
			// switch ($size) {
			// 	case 0:
			// 		// $this->getAllCarsShifts(0,$today);					
			// 		break;
			// 	case 1:
			// 		// pass pump id
			// 		$this->getAllCarsPump($this->_getParams[0]);
			// 		break;
			// 	case 2:
			// 		//$this->getAllCarsShifts(0,$today);
			// 		break;
			// 	default:
			// 		$output = array();
			// 		$output['error'] = 'no date provided';
			// 		echo json_encode($output);
			// 		break;
			// }

			echo json_encode("working");
		}
		elseif ($this->_method == 'POST')
		{
			$this->postTransactions($this->_postParams);
		}
	}


	private function postTransactions($postParams){

		$size = sizeof($postParams);

		$result = array();
		foreach ($postParams as $row) {
			
			$trans_id 		= $row['trans_id'];			
			$pump_id 		= $row['pump_id'];			
			$cust_id 		= $row['cust_id'];			
			$car_id 		= $row['car_id'];			
			$user_id 		= $row['user_id'];			
			$receipt_no 	= $row['receipt_no'];			
			$shift 			= $row['shift'];			
			$fuel 			= $row['fuel'];			
			$amount 		= $row['amount'];			
			$rate 			= $row['rate'];			
			$liters 		= $row['liters'];			
			$billed 		= $row['billed'];			
			$date 			= $row['date'];			
			$last_updated 	= $row['last_updated'];			



			// $sql = "INSERT INTO `transactions` (`pump_id`,`cust_id`,`car_id`,`user_id`,`receipt_no`,`shift`,`fuel`,`amount`,`rate`,`liters`,`billed`,`date`,`last_updated`) VALUES (:field1,:field2,:field3,:field4,:field5,:field6,:field7,:field8,:field9,:field10,:field11,:field12,:field13);";

			// $this->_db->query($sql);
			
			// $this->_db->bind(':field1', $pump_id);
			// $this->_db->bind(':field2', $cust_id);
			// $this->_db->bind(':field3', $car_id);
			// $this->_db->bind(':field4', $user_id);
			// $this->_db->bind(':field5', $receipt_no);
			// $this->_db->bind(':field6', $shift);
			// $this->_db->bind(':field7', $fuel);
			// $this->_db->bind(':field8', $amount);
			// $this->_db->bind(':field9', $rate);
			// $this->_db->bind(':field10', $liters);
			// $this->_db->bind(':field11', $billed);
			// $this->_db->bind(':field12', $date);
			// $this->_db->bind(':field13', $last_updated);

			// $this->_db->execute();
			array_push($result, $trans_id);
		}		

		echo json_encode($result);
	}
}
?>