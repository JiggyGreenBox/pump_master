<?php

require '../query/conn.php';

if (isset($_GET['cust_id'])) {

	$cust_id = $_GET['cust_id'];

	$sql = "SELECT * FROM `payments` WHERE `cust_id` ='".$cust_id."' ";
	$exe = mysqli_query($conn, $sql);
	$count = mysqli_num_rows($exe);
	if ($count > 0) {


		echo '<table id="header-fixed"></table>';
		echo '<table id="table-1">';
		echo '<thead>';
			echo '<tr style="border:1px solid rgb(207,216,220);">';
				echo '<th>#</th>';
				echo '<th>Date</th>';
				echo '<th>Prev Bal</th>';
				echo '<th>Prev Out</th>';
				echo '<th>Amount Paid</th>';
				echo '<th>New Bal</th>';
				echo '<th>New Out</th>';
			echo '</tr>';
		echo '</thead>';


		echo '<tbody>';
		$i=1;
		while($row = mysqli_fetch_assoc($exe)){
			$cust_id	 = $row["cust_id"];
			$prev_bal	 = $row["prev_bal"];
			$prev_out	 = $row["prev_out"];
			$amount_paid	 = $row["amount_paid"];
			$new_bal	 = $row["new_bal"];
			$new_out	 = $row["new_out"];
			$date	 = $row["date"];
			$date = date("M-d, g:i a", strtotime($date));

			echo '<tr class="highlight">';
				echo '<td>'.$i.'</td>';
				echo '<td>'.$date.'</td>';
				echo '<td>'.$prev_bal.'</td>';
				echo '<td>'.$prev_out.'</td>';
				echo '<td>'.$amount_paid.'</td>';
				echo '<td>'.$new_bal.'</td>';
				echo '<td>'.$new_out.'</td>';
			echo '</tr>';
			$i++;
		}
		echo '</tbody>';
		echo '</table>';
	}
	
}
?>