<?php
if(!isset($_SESSION))
{
	session_start();
}
 
require '../query/conn.php';

if (isset($_GET['cust_id'])) {

	$cust_id = $_GET['cust_id'];

	$sql = "SELECT * FROM `invoices` WHERE `cust_id` ='".$cust_id."' AND `status` = 'Y' ORDER BY `invoice_no` DESC";
	$exe = mysqli_query($conn, $sql);
	$count = mysqli_num_rows($exe);
	if ($count > 0) {

		echo '<table id="header-fixed"></table>';
		echo '<table id="table-1">';
		echo '<thead>';
			echo '<tr style="border:1px solid rgb(207,216,220);">';
				echo '<th>#</th>';
				echo '<th>Invoice No</th>';
				echo '<th>From</th>';
				echo '<th>To</th>';
				echo '<th>Amount</th>';
				echo '<th>Generated</th>';
				if ($_SESSION['role'] == "admin") {
					echo '<th></th>';
				}
			echo '</tr>';
		echo '</thead>';

 
		echo '<tbody>';
		$i=1;
		while($row = mysqli_fetch_assoc($exe)){


			echo '<tr class="highlight invoice" custid='.$cust_id.' >';
				echo '<td >'.$i.'</td>';
				echo '<td class="invoice_no">'.$row["invoice_no"].'</td>';
				echo '<td class="from">'.$row["from"].'</td>';
				// echo '<td>'.date("d-m-Y", strtotime($row['from'])).'</td>';
				echo '<td class="to">'.$row["to"].'</td>';
				// echo '<td>'.date("d-m-Y", strtotime($row['to'])).'</td>';
				echo '<td>'.$row["amount"].'</td>';
				echo '<td>'.date("M-d, g:i a", strtotime($row['date'])).'</td>';
				if (($_SESSION['role'] == "admin")&&($i == 1)) {
					echo '<td class="delete_invoice" id="'.$row['in_id'].'"></td>';	
				}
			echo '</tr>';
			$i++;

		}
		echo '</tbody>';
		echo '</table>';
	}
	
}
?>