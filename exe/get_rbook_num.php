<?php

require '../query/conn.php';


function getCustId($rbook_num, &$conn){

	$sql = "SELECT 1 FROM `transactions` WHERE `receipt_no` =  ".$rbook_num." ";
	$exe = mysqli_query($conn, $sql);
	if(mysqli_num_rows($exe) < 1){
		$sql = "SELECT cust_id FROM `receipt_books` WHERE ".$rbook_num." BETWEEN `min` and `max`";
		$exe = mysqli_query($conn, $sql);
		if(mysqli_num_rows($exe) == 1){
			$row = mysqli_fetch_assoc($exe);
			$cust_id	 = $row["cust_id"];	
		}
		else{
			$cust_id = -1;
		}
	}
	else{
		$cust_id = -1;
	}
	return $cust_id;
}




if(isset($_GET['rbook_num'])){

	$rbook_num = $_GET['rbook_num'];

	// get cust id from rbook range
	$cust_id = getCustId($rbook_num, $conn);	
	
	if($cust_id != -1){

		// use cust id to get name
		$sql = "SELECT cust_company,cust_f_name, cust_m_name, cust_l_name, cust_post_paid FROM `customers` WHERE `cust_id` = '".$cust_id."';";
		$exe = mysqli_query($conn, $sql);
		$row = mysqli_fetch_assoc($exe);


		$cust_post_paid	 = $row["cust_post_paid"];
		$cust_company	 = $row["cust_company"];
		if($cust_company == ""){
			$cust_f_name	 = $row["cust_f_name"];
			$cust_m_name	 = $row["cust_m_name"];
			$cust_l_name	 = $row["cust_l_name"];
			
			$full_name = ucwords($cust_f_name." ".$cust_m_name." ".$cust_l_name);
		}
		else{
			$full_name = ucwords($cust_company);
		}

		

		// print name
		echo '<div style="margin-top:20px;margin-bottom:15px;font-size:20px;">'.$full_name.'</div>';


		// get customer cars
		$sql = "SELECT car_id, car_no_plate,car_fuel_type FROM `cars` WHERE `car_cust_id` = '".$cust_id."' AND `status` = 'active';";
		$exe = mysqli_query($conn, $sql);
		echo '<select id="sel_car" style="margin-bottom:6px;">';
		echo '<option value="-1" ftype="invalid_fuel">Choose Car</option>';
		while($row = mysqli_fetch_assoc($exe)){
			$car_id	 		= $row["car_id"];
			$car_no_plate	= $row["car_no_plate"];
			$car_fuel_type	= $row["car_fuel_type"];
			echo '<option value="'.$car_id.'" ftype="'.$car_fuel_type.'">'.$car_no_plate.'</option>';
		}
		echo '<option value="-1" ftype="unknown">Unknown</option>';
		echo '</select>';

		// NO VALIDATION
		echo '<div id="form_unknown_car">
			<div>
				<input type="text" placeholder="Car Number" onKeyPress="if(this.value.length==112 return false;" id="in_car_no_plate_number">
			</div>
			<div >
				<input type="radio" name="unknown_fuel" value="petrol" checked>Petrol
  				<input type="radio" name="unknown_fuel" value="diesel">Diesel
			</div>
		</div>';

		/* WITH VALIDATION
		echo '<div id="form_unknown_car">
			<div>
				<input type="text" value="MH" maxlength="2" id="in_car_no_plate_state" class="only_letter" tabindex="7">
				<input type="number" value="12" id="in_car_no_plate_city" onKeyPress="if(this.value.length==2) return false;" tabindex="8">
				<input type="text" maxlength="3" placeholder="**" class="only_letter" id="in_car_no_plate_letter" tabindex="9">
				<input type="number" placeholder="****" onKeyPress="if(this.value.length==4) return false;" id="in_car_no_plate_number" tabindex="10">
			</div>
			<div >
				<input type="radio" name="unknown_fuel" value="petrol" checked>Petrol
  				<input type="radio" name="unknown_fuel" value="diesel">Diesel
			</div>
		</div>';
		*/

 		echo '<div><input type="hidden" id="cust_post_paid" custid="'.$cust_id.'" value="'.$cust_post_paid.'"></div>';

		// show input boxes
		echo '<div><input type="number" id="m_trans_rs" placeholder="Rupees" class="single_decimal_twodigit" tabindex="11"></div>';
		echo '<div><input type="number" id="m_trans_lit" placeholder="Litres" class="single_decimal_twodigit" tabindex="12"></div>';
		echo '<div style="margin-top:20px;"><div class="mat_btn" id="btn_manual_t_cancel">CANCEL</div><div class="mat_btn" style="background-color: #0087C1;" id="btn_manual_t_confirm" tabindex="9">CONFIRM</div></div>';
	}
	else{
		// no id found
		echo 'nothing found';
	}		
}


?>