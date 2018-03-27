<?php
if(!isset($_SESSION)) {
	session_start();
}
require '../query/conn.php';

$sql = "SELECT * FROM `customers` WHERE 1";
$exe = mysqli_query($conn, $sql);



$count = mysqli_num_rows($exe);
if ($count > 0) { 
	
	echo '<table id="header-fixed"></table>';
	echo '<table id="table-1">';

	echo '<thead>';
		echo '<tr style="border:1px solid rgb(207,216,220);">';
			echo '<th>Sr No</th>';
			echo '<th>Customer Name</th>';
			echo '<th>Phone</th>';
			// echo '<th>Pump ID</th>';
			echo '<th>Payment</th>';
			echo '<th>Balance</th>';
			echo '<th>Outstanding</th>';
			echo '<th>Last Updated</th>';
			if ($_SESSION['role'] == "admin") {
					echo '<th ></th>';
			}
		echo '</tr>';
	echo '</thead>';

	echo '<tbody>';
	$i=1;
	while($row = mysqli_fetch_assoc($exe)){
		$limit = false;
		$upper_limit = 0;
		
		$cust_id	 	= $row["cust_id"];
		$cust_f_name	= $row["cust_f_name"];
		$cust_m_name	= $row["cust_m_name"];
		$cust_l_name	= $row["cust_l_name"];
		$cust_ph_no	 	= $row["cust_ph_no"];
		$cust_company 	= $row["cust_company"];


		$cust_post_paid	= $row["cust_post_paid"];
		$cust_balance	= $row["cust_balance"];
		$cust_credit_limit	 = $row["cust_credit_limit"];
		$cust_outstanding	 = $row["cust_outstanding"];
		$cust_last_updated	 = $row["cust_last_updated"];
		$cust_last_updated = date("M-d, g:i a", strtotime($cust_last_updated));

		if ($cust_credit_limit == 0) {
			$cust_credit_limit = 1;
		}

		if($cust_company == ""){
			$cust_name 		 = ucwords($cust_f_name.' '.$cust_m_name.' '.$cust_l_name);	
		}
		else{
			$cust_name 		 = ucwords($cust_company);
		}

		if ($cust_post_paid == "Y") {
			$cust_post_paid = "Postpaid";
			$upper_limit = round(($cust_outstanding/$cust_credit_limit)*100);
		}
		else{
			$cust_post_paid = "Prepaid";

			if ($cust_balance < 10000) {
				$upper_limit = 90;
			}
		}

		if($upper_limit > 80){
			$limit = true;			
		}

		// for ($i=0; $i < 30; $i++) { 
		if($limit){
			echo '<tr custid="'.$cust_id.'" class="highlight red">';
		}else{
			echo '<tr custid="'.$cust_id.'" class="highlight">';
		}

				echo '<td>'.$i.'</td>';
				echo '<td>'.$cust_name.'</td>';
				echo '<td>'.$cust_ph_no.'</td>';
				// echo '<td>'.$cust_pump_id.'</td>';
				echo '<td>'.$cust_post_paid	.'</td>';
				echo '<td>'.$cust_balance.'</td>';
				echo '<td>'.$cust_outstanding.'</td>';
				echo '<td>'.$cust_last_updated.'</td>';
				if ($_SESSION['role'] == "admin") {
					echo '<td custid='.$cust_id.' class="edit" >Edit</td>';
				}
				
			echo '</tr>';
		// }
		$i++;
	}

	echo '</tbody>';
	echo '</table>';
}
?>
