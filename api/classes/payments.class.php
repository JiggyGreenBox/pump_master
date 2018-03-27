<?php
date_default_timezone_set("Asia/Kolkata");

class Payments
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
					//$this->getAllCarsShifts(0,$today);				
					break;
				case 1:
					// by cust id
					// $this->getCustPayments($this->_getParams[0]);
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
			$this->newPayment($this->_postParams);
		}
	}


	// private function getCustPayments($cust_id){

	// }



	private function newPayment($postParams){


		$cust_id	 		= $postParams['cust_id'];
		$cust_id	 		= trim($cust_id);

		$amount_paid		= $postParams['payment_amount'];
		$amount_paid		= trim($amount_paid);


		$payment_comment 	= $postParams['payment_comment'];
		$payment_comment 	= trim($payment_comment);
		if($payment_comment == ""){
			$payment_comment = NULL;
		}



		date_default_timezone_set("Asia/Kolkata");
		$time 				= date("H:i:s");

		$date 				= $postParams['payment_date']." ".$time;
		$last_updated		= $date;


		$new_out = 0;
		$new_bal = 0;


		$sql = "SELECT `cust_pump_id`,`cust_post_paid`,`cust_balance`,`cust_outstanding` FROM `customers` WHERE `cust_id` = '".$cust_id."' ";
		$this->_db->query($sql);
		$this->_db->execute();

		if($this->_db->rowCount() == 1)
		{
			$r = $this->_db->single();
			$pump_id	 		= $r["cust_pump_id"];
			$is_postpaid	 	= $r["cust_post_paid"];
			$prev_bal	 		= $r["cust_balance"];
			$prev_out			= $r["cust_outstanding"];
		}

		if ($is_postpaid == 'Y') {
			$new_out	 = round($prev_out - $amount_paid);
		}else{
			$new_bal	 = round($prev_bal + $amount_paid);
		}	

		

		


		$sql = "INSERT INTO `payments` (`cust_id`,`pump_id`,`prev_bal`,`prev_out`,`amount_paid`,`new_bal`,`new_out`,`date`,`last_updated`,`is_postpaid`,`comment`) 
				VALUES (:field1,:field2,:field3,:field4,:field5,:field6,:field7,:field8,:field9,:field10,:field11);";

		$this->_db->query($sql);

		$this->_db->bind(':field1', $cust_id);
		$this->_db->bind(':field2', $pump_id);
		$this->_db->bind(':field3', $prev_bal);
		$this->_db->bind(':field4', $prev_out);
		$this->_db->bind(':field5', $amount_paid);
		$this->_db->bind(':field6', $new_bal);
		$this->_db->bind(':field7', $new_out);
		$this->_db->bind(':field8', $date);
		$this->_db->bind(':field9', $last_updated);
		$this->_db->bind(':field10', $is_postpaid);
		$this->_db->bind(':field11', $payment_comment);

		$this->_db->execute();

		$sql = "UPDATE `customers` SET `cust_balance` = :field1,`cust_outstanding` = :field2,`cust_last_updated` = :field3 WHERE `cust_id` = '".$cust_id."' ;";

		$this->_db->query($sql);

		$this->_db->bind(':field1', $new_bal);
		$this->_db->bind(':field2', $new_out);
		$this->_db->bind(':field3', $last_updated);

		$this->_db->execute();

		$table_name	  = "customers";
		$last_updated = strtotime($last_updated); 

		$sql = "UPDATE `sync` SET `last_updated` = :field2 WHERE `table_name` = :field1;";

		$this->_db->query($sql);

		$this->_db->bind(':field1', $table_name);
		$this->_db->bind(':field2', $last_updated);

		$this->_db->execute();
	}
}
?>