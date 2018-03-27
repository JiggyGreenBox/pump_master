<?php
	
	require '../query/conn.php';
	
	if (isset($_GET['cust_id'])) {
		$cust_id = $_GET['cust_id'];

		$sql = "SELECT cust_company, cust_f_name, cust_m_name, cust_l_name, cust_ph_no,
						cust_post_paid, cust_balance, cust_outstanding 
				FROM `customers` 
				WHERE `cust_id` = '".$cust_id."' ";
		$exe = mysqli_query($conn, $sql);

		while($row = mysqli_fetch_assoc($exe)){
			$cust_f_name	 = $row["cust_f_name"];
			$cust_m_name	 = $row["cust_m_name"];
			$cust_l_name	 = $row["cust_l_name"];
			$cust_ph_no	 	 = $row["cust_ph_no"];
			$cust_post_paid	 = $row["cust_post_paid"];
			$cust_balance	 = $row["cust_balance"];
			$cust_outstanding	 = $row["cust_outstanding"];
			$cust_company	 = $row['cust_company'];

			if($cust_company == ""){
				$cust_name 		 = ucwords($cust_f_name.' '.$cust_m_name.' '.$cust_l_name); 	 
			}
			else{
				$cust_name 		 = ucwords($cust_company);
			}			
		}

		echo'<div id="name">'.$cust_name.'</div>';



		if ($cust_post_paid == 'Y') {
			echo'<div id="bal">OUTSTANDING '.$cust_outstanding.'</div>';
		}else{
			echo'<div id="bal">BALANCE <span>'.$cust_balance.'</span></div>';
		}

		echo '<div id="ph_no">'.$cust_ph_no.'</div>';

		echo'<input type="hidden" id="cust_post_paid" value="'.$cust_post_paid.'"></input>';



		// echo'<button id="btn_add_payment" custid="'.$cust_id.'">Payment</button>';

	}

?>