<?php

require '../query/conn.php';;

$sql = "SELECT a.*,b.cust_f_name,b.cust_m_name,b.cust_l_name FROM `payments` a JOIN `customers` b ON a.cust_id = b.cust_id WHERE 1 ORDER by `payment_id` ASC";
$exe = mysqli_query($conn, $sql);
$count = mysqli_num_rows($exe);
	if ($count > 0) {
		echo'<table>';
			echo '<tr>';
				echo '<th>Sr No</th>';
				echo '<th>Customer Name</th>';
				echo '<th>Prev Bal</th>';
				echo '<th>Prev Out</th>';
				echo '<th>Amount Paid</th>';
				echo '<th>New Bal</th>';
				echo '<th>New Out</th>';
				echo '<th>Date</th>';
			echo '</tr>';

		$i = 1;
		while($row = mysqli_fetch_assoc($exe)){
			$payment_id	 = $row["payment_id"];
			$cust_id	 = $row["cust_id"];
			$cust_f_name	= $row["cust_f_name"];
			$cust_m_name	= $row["cust_m_name"];
			$cust_l_name	= $row["cust_l_name"];
			$pump_id	 = $row["pump_id"];
			$prev_bal	 = $row["prev_bal"];
			$prev_out	 = $row["prev_out"];
			$amount_paid	 = $row["amount_paid"];
			$new_bal	 = $row["new_bal"];
			$new_out	 = $row["new_out"];
			$date	 = $row["date"];
			$date = date("M-d, g:i a", strtotime($date));
			$last_updated	 = $row["last_updated"];
			$is_postpaid	 = $row["is_postpaid"];


			echo '<tr>';
				echo '<td>'.$i.'</td>';
				echo '<td>'.$cust_f_name.' '.$cust_m_name.' '.$cust_l_name.'</td>';
				echo '<td>'.$prev_bal.'</td>';
				echo '<td>'.$prev_out.'</td>';
				echo '<td>'.$amount_paid.'</td>';
				echo '<td>'.$new_bal.'</td>';
				echo '<td>'.$new_out.'</td>';
				echo '<td>'.$date.'</td>';
			echo '</tr>';
			$i++;
		}
		echo '</table>';
	}
?>