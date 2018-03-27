<div style="background-color: rgb(249,250,251);width: 500px;padding: 20px;border: 1px solid rgb(222,227,231);border-radius: 3px;">
	<div class="form_header">NEW CUSTOMER</div>
	<div><input type="text" id="in_cust_f_name" placeholder="First Name"></div>
	<div><input type="text" id="in_cust_m_name" placeholder="Middle Name"></div>
	<div><input type="text" id="in_cust_l_name" placeholder="Last Name"></div>

	<div style="margin-top: 25px;"></div>

	<div><input type="number" id="in_cust_ph_no" placeholder="Phone Number" onKeyPress="if(this.value.length==10) return false;"></div>

	<div style="margin-top: 25px;"></div>
	<div>
		<select id="select_is_postpaid">
			<option value="-1" selected disabled>Payment Type</option>
			<option value="Y">Postpaid</option>
			<option value="N">Prepaid</option>
		</select>
	</div>
	<div style="margin-top: 20px;"></div>
	<div><textarea style="padding: 5px;" id="in_cust_address" placeholder="Address"  rows="5" cols="28"></textarea></div>

	<div style="margin-top: 25px;"></div>
	<div><input type="number" id="in_cust_balance" placeholder="Current Balance" class="single_decimal" value=""></div>
	<div><input type="number" id="in_cust_outstanding" placeholder="Outstanding Amount" class="single_decimal"></div>
	<div><input type="number" id="in_cust_credit_limit" placeholder="Credit limit" class="single_decimal"></div>
	<div style="margin-top: 20px;"></div>
	<div style="margin-top: 20px;"></div>
	<!-- <div><button id="btn_cancel_cust">Cancel</button><button id="btn_new_cust">Create</button></div> -->
	<div><div class="mat_btn" id="btn_cancel_cust">CANCEL</div><div class="mat_btn" style="background-color: #0087C1;" id="btn_new_cust">CREATE</div></div>
</div>