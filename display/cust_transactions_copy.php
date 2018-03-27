<?php


require '../query/conn.php';

if (isset($_GET['cust_id'])) {

	$cust_id = $_GET['cust_id'];

	$sql = "SELECT * FROM `transactions` WHERE `cust_id` = '".$cust_id."' ";
	$exe = mysqli_query($conn, $sql);
	$count = mysqli_num_rows($exe);
	if ($count > 0) {
		
		echo'<table>';
			echo '<tr>';
				echo '<th>Trans ID</th>';
				echo '<th>Pump ID</th>';
				echo '<th>Cust ID</th>';
				echo '<th>Car ID</th>';
				echo '<th>Amount</th>';
				echo '<th>Date Time</th>';
			echo '</tr>';

			while($row = mysqli_fetch_assoc($exe)){

				$trans_id	 	= $row["trans_id"];
				$pump_id	 	= $row["pump_id"];
				$cust_id	 	= $row["cust_id"];
				$car_id	 		= $row["car_id"];
				$amount	 		= $row["amount"];
				$date	 		= $row["date"];
				$last_updated	= $row["last_updated"];


				
				echo '<tr class="highlight">';
					echo '<td>'.$trans_id.'</td>';
					echo '<td>'.$pump_id.'</td>';
					echo '<td>'.$cust_id.'</td>';
					echo '<td>'.$car_id.'</td>';
					echo '<td>'.$amount.'</td>';
					echo '<td>'.$date.'</td>';
				echo '</tr>';
			}
		echo'</table>';
	}
}
?>