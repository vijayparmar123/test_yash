<?php
	error_reporting(0);
	// $count = $previw_list->count();

	$party_first_number = $result = substr($wo_data['party_gst_no'], 0, 2);
	$yashnand_first_number = $result = substr($wo_data['yashnand_gst_no'], 0, 2);

	if(is_numeric($party_first_number))
	{
		if($party_first_number === $yashnand_first_number)
		{
			$cgst = 1;
			$sgst = 1;
			$gross = 1;
		}else{
			$igst = 1;
			$gross = 1;
		}
	}

	$created_by = isset($project_data['created_by'])?$this->ERPfunction->get_user_name($project_data['created_by']):'NA';
	$last_edit = isset($project_data['last_edit'])?date("m-d-Y H:i:s",strtotime($project_data['last_edit'])):'NA';
	$last_edit_by = isset($project_data['last_edit_by'])?$this->ERPfunction->get_user_name($project_data['last_edit_by']):'NA';
	$approved_time = !empty($approve_date)?date("m-d-Y",strtotime($approve_date)):'NA';
	
	$remark_1 = 0;
	$remark_2 = 0;
	if($wo_data['contract_type'] == 1 || $wo_data['contract_type'] == 3 || $wo_data['contract_type'] == 4)
	{
		$remark_1 = 1;
		
		$rw = 20;
		if($wo_data["taxes_duties"])
		{
			$rw++;
		}
		if($wo_data["guarantee"])
		{
			$rw++;
		}
	}
	else{
		$remark_2 = 1;
		
		$rw = 23;
		if($wo_data["taxes_duties"])
		{
			$rw++;
		}
		if($wo_data["loading_transport"])
		{
			$rw++;
		}
		if($wo_data["unloading"])
		{
			$rw++;
		}
		if($wo_data["guarantee"])
		{
			$rw++;
		}
		if($wo_data["warrenty"])
		{
			$rw++;
		}
	}
	
	
	ob_clean();
	header('Content-type: application/pdf');
	header('Content-Disposition: inline; filename="popr.pdf"');
	header('Content-Transfer-Encoding: binary');
	header('Accept-Ranges: bytes');


	// $mpdf	=	new mPDF('+aCJK');
	$mpdf	=	new mPDF('c','A4','','' , '4' , '4' , 0 , 0 , 0 , 0);

	$mpdf->WriteHTML('<html>');
	$mpdf->WriteHTML('<head>');
	$mpdf->WriteHTML('<style>
				table{
				 font-family: sans-serif;
				font-size : 12px;	
					color : #444;
					border :1;
					border-color :gray;
					border-collapse:collapse;
				}
				pre{
					font-family: sans-serif;
					font-size : 12px;	
					color : #444;
				}
				td{
					border-right :1 solid;
					border-bottom :1 solid;
					border-top :0;
					border-left :0;
					border-color : #777;
				}
				
				</style>');
	$mpdf->WriteHTML('</head>');
	$mpdf->WriteHTML('<body>');	
	
		
	
	$mpdf->WriteHTML($this->ERPfunction->viewheader_po($wo_data['wo_date'],$wo_data['bill_mode']));
	$mpdf->WriteHTML("<table width=100%  border=1>");
	
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='13' align='center'><h2><strong><u>Work Order</u> (<u>W.O.</u>)</strong></u></h3></td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='2' align='left'><b>Project Code : </b>{$this->ERPfunction->get_projectcode($wo_data['project_id'])}</td>");
	$mpdf->WriteHTML("<td colspan='11' align='left'><b>Project Name : </b>{$this->ERPfunction->get_projectname($wo_data['project_id'])}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td align='left'><b>Project Address : </b></td><td colspan='12'>{$this->ERPfunction->get_projectaddress($wo_data['project_id'])}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$bill_mode = ($wo_data['bill_mode'] == 'mp')?'Madhya Pradesh':ucfirst($wo_data['bill_mode']);
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td align='left'><b>Mode of Billing : </b></td><td colspan='3'>{$bill_mode}</td>");
	$mpdf->WriteHTML("<td colspan='4' align='left'><b>Date : </b></td><td colspan='5'>".date("d-m-Y",strtotime($wo_data['wo_date']))."</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td align='left'><b>W. O. No. : </b></td><td colspan='2'>{$wo_data['wo_no']}</td>");
	$mpdf->WriteHTML("<td align='left'><b>Yashnand GST No. : </b></td><td colspan='12'>{$wo_data['yashnand_gst_no']}</td>");
	$mpdf->WriteHTML("</tr>");
	$party_name = (is_numeric($wo_data['party_userid']))?$this->ERPfunction->get_vendor_name($wo_data['party_userid']):$this->ERPfunction->get_agency_name_by_code($wo_data['party_userid']);
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td align='left'><b>Party's Name : </b></td><td colspan='4'>{$party_name}</td>");
	$mpdf->WriteHTML("<td colspan='3' align='left'><b>Party ID : </b></td>");
	$mpdf->WriteHTML("<td colspan='5' align='left'>{$wo_data['party_id']}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td align='left'><b>Party Address : </b></td><td colspan='12'>{$wo_data['party_address']}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td align='left'><b> Contact No:(1)</b></td><td colspan='4'>{$wo_data['party_no1']}</td>");
	$mpdf->WriteHTML("<td colspan='3' align='left'><b>Contact No:(2)</b></td>");
	$mpdf->WriteHTML("<td colspan='5' align='left'>{$wo_data['party_no2']}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td align='left'><b>E-mail Id : </b></td><td colspan='12'>{$wo_data['party_email']}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td align='left'><b>Pan Card No:</b></td><td colspan='4'>{$wo_data['party_pan_no']}</td>");
	$mpdf->WriteHTML("<td colspan='3' align='left'><b>GST No:</b></td>");
	$mpdf->WriteHTML("<td colspan='5' align='left'>{$wo_data['party_gst_no']}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td align='left'><b>Type of Contract:</b></td><td colspan='4'>{$this->ERPfunction->get_contract_title($wo_data['contract_type'])}</td>");
	$mpdf->WriteHTML("<td colspan='3' align='left'><b>Payment Method:</b></td>");
	$mpdf->WriteHTML("<td colspan='5' align='left'>{$wo_data['payment_method']}</td>");
	$mpdf->WriteHTML("</tr>");

	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td align='left'><b>Type of Work:</b></td><td colspan='12'>{$this->ERPfunction->get_planning_work_head_title($wo_data['work_type'])}</td>");
	$mpdf->WriteHTML("</tr>");
	$mpdf->WriteHTML("</table>");
	$mpdf->WriteHTML("<table width=100%  border=1>");
	
	//$target_date = ($wo_data['target_date'] != NULL)?date("d-m-Y",strtotime($wo_data['target_date'])):"";
	//$mpdf->WriteHTML("<tr>");
	//$mpdf->WriteHTML("<td align='left'><b>Target Date: </b></td><td colspan='12'>{$target_date}</td>");
	//$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td rowspan='2' width=60px align='center'><strong>Item No</strong></td>");
	$mpdf->WriteHTML("<td rowspan='2' width=200px align='center'><strong>Work Description</strong></td>");
	$mpdf->WriteHTML("<td rowspan='2' width=200px align='center'><strong>Detail Description</strong></td>");
	$mpdf->WriteHTML("<td colspan='3' align='center'><strong>Quantity</strong></td>");
	$mpdf->WriteHTML("<td rowspan='2' width=60px align='center'><strong>Unit</strong></td>");
	// $mpdf->WriteHTML("<td rowspan='2' width=60px align='center'><strong>Unit Rate</strong></td>");
	// $mpdf->WriteHTML("<td colspan='3' align='center'><strong>Amount</strong></td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td width=55px><strong>This WO Quantity</strong></td>");
	$mpdf->WriteHTML("<td width=81px align='center'><strong>Up To <br>Previous WO Quantity</strong></td>");
	$mpdf->WriteHTML("<td width=55px><strong>Till Date WO Quantity</strong></td>");
	// $mpdf->WriteHTML("<td width=55px><strong>This WO Amount (Rs.)</strong></td>");
	// $mpdf->WriteHTML("<td width=55px><strong>Till Date WO Amount (Rs.)</strong></td>");
	$mpdf->WriteHTML("</tr>");
	$total_amount = 0;
	$total_amount_till_date = 0;
	foreach($wod_data as $retrive_material)
	{
			$first_approved_by = $retrive_material['first_approved_by'];
			$verified_by = $retrive_material['verified_by'];
			$second_approved_by = $retrive_material['approved_by'];
			
			$mpdf->WriteHTML("<tr>");
			$mpdf->WriteHTML("<td >{$retrive_material['contract_no']}</td>");
			$mpdf->WriteHTML("<td >{$this->ERPfunction->get_category_title($retrive_material['material_name'])}</td>");
			$mpdf->WriteHTML("<td >{$retrive_material['detail_description']}</td>");
			$mpdf->WriteHTML("<td >{$retrive_material['quentity']}</td>");
			$mpdf->WriteHTML("<td >{$retrive_material['quantity_upto_previous']}</td>");
			$mpdf->WriteHTML("<td >{$retrive_material['till_date_quantity']}</td>");
			$mpdf->WriteHTML("<td >{$retrive_material['unit']}</td>");
			// $mpdf->WriteHTML("<td >{$retrive_material['unit_rate']}</td>");
			// $mpdf->WriteHTML("<td >{$retrive_material['amount']}</td>");
			// $mpdf->WriteHTML("<td >{$retrive_material['amount_till_date']}</td>");
			//$mpdf->WriteHTML("<td width=70px>{$retrive_material['target_date']->format('d-m-Y')}</td>");
			$mpdf->WriteHTML("</tr>");
			// $total_amount += $retrive_material['amount'];
			// $total_amount_till_date += $retrive_material['amount_till_date'];
	}
	
	$mpdf->WriteHTML("<tr>");
	// $mpdf->WriteHTML("<td colspan='8' style='text-align:center'><strong>Total Amount</strong></td>");
	// $mpdf->WriteHTML("<td><strong>{$total_amount}</strong></td>");
	// $mpdf->WriteHTML("<td><strong>{$total_amount_till_date}</strong></td>");
	$mpdf->WriteHTML("</tr>");
	/*if($cgst){
		$mpdf->WriteHTML("<tr>");
		$mpdf->WriteHTML("<td colspan='7' style='text-align:center'><strong>CGST(%)</strong></td>");
		$mpdf->WriteHTML("<td><strong>{$wo_data['cgst_percentage']}%</strong></td>");
		$mpdf->WriteHTML("<td><strong>{$wo_data['cgst']}</strong></td>");
		$mpdf->WriteHTML("<td><strong>{$wo_data['till_date_cgst']}</strong></td>");
		$mpdf->WriteHTML("</tr>");
	}
	if($sgst){
		$mpdf->WriteHTML("<tr>");
		$mpdf->WriteHTML("<td colspan='7' style='text-align:center'><strong>SGST(%)</strong></td>");
		$mpdf->WriteHTML("<td><strong>{$wo_data['sgst_percentage']}%</strong></td>");
		$mpdf->WriteHTML("<td><strong>{$wo_data['sgst']}</strong></td>");
		$mpdf->WriteHTML("<td><strong>{$wo_data['till_date_sgst']}</strong></td>");
		$mpdf->WriteHTML("</tr>");
	}
	if($igst){
		$mpdf->WriteHTML("<tr>");
		$mpdf->WriteHTML("<td colspan='7' style='text-align:center'><strong>IGST(%)</strong></td>");
		$mpdf->WriteHTML("<td><strong>{$wo_data['igst_percentage']}%</strong></td>");
		$mpdf->WriteHTML("<td><strong>{$wo_data['igst']}</strong></td>");
		$mpdf->WriteHTML("<td><strong>{$wo_data['till_date_igst']}</strong></td>");
		$mpdf->WriteHTML("</tr>");
	}*/
	$mpdf->WriteHTML("<tr>");
	/*$mpdf->WriteHTML("<td colspan='8' style='text-align:center'><strong>Net Amount</strong></td>");
	$mpdf->WriteHTML("<td><strong>{$wo_data['net_amount']}</strong></td>");
	$mpdf->WriteHTML("<td><strong>{$wo_data['till_date_net_amount']}</strong></td>");*/
	$mpdf->WriteHTML("</tr>");
	$mpdf->WriteHTML("</table>");
	$mpdf->WriteHTML("<table>");
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td rowspan='{$rw}' style='border-bottom:0' valign='top'><strong> Remarks/Note:</strong></td>");
	$mpdf->WriteHTML("<td colspan='12' style='border-right:0'> 1) The above mentioned rate includes following:</td>");
	$mpdf->WriteHTML("</tr>");
	if($remark_1)
	{
		if($wo_data["taxes_duties"])
		{
			$mpdf->WriteHTML("<tr>");
			$mpdf->WriteHTML("<td align='right'>&#10003;</td>");
			$mpdf->WriteHTML("<td colspan='11' style='border-right:0'><strong>All Taxes & Duties</strong></td>");
			$mpdf->WriteHTML("</tr>");
		}
		
		if($wo_data['guarantee'])
		{
			$mpdf->WriteHTML("<tr>");
			$mpdf->WriteHTML("<td align='right'>&#10003;</td>");
			$mpdf->WriteHTML("<td colspan='11' style='border-right:0'><strong>Guarantee up to {$wo_data['guarantee_time']}</strong></td>");
			$mpdf->WriteHTML("</tr>");
		}
				
		$mpdf->WriteHTML("<tr>");
		$mpdf->WriteHTML("<td colspan='12' style='border-right:0'> 2) You are also binded to our Contract Conditions & Specifications with Client; which are provided to you.</td>");
		$mpdf->WriteHTML("</tr>");
		
		$mpdf->WriteHTML("<tr>");
		$mpdf->WriteHTML("<td colspan='12' style='border-right:0'> 3) If work will found unsatisfactory afterwards; agency/party has to correct it free of cost.</td>");
		$mpdf->WriteHTML("</tr>");
		
		$mpdf->WriteHTML("<tr>");
		$mpdf->WriteHTML("<td colspan='12' style='border-right:0'> 4) Always get your work checked and verified by our Engineer In-charge, PMC/TPI, Client and other consultants also take their prior approval before starting work.</td>");
		$mpdf->WriteHTML("</tr>");
		
		$mpdf->WriteHTML("<tr>");
		$mpdf->WriteHTML("<td colspan='12' style='border-right:0'> 5) Quantity may vary up to any extend afterwards; payment will be done on actual supply & its acceptance.</td>");
		$mpdf->WriteHTML("</tr>");
		
		$mpdf->WriteHTML("<tr>");
		$mpdf->WriteHTML("<td colspan='12' style='border-right:0'> 6) If you will not revert back within 48 hrs, this WO will be considered as accepted by you.</strong></td>");
		$mpdf->WriteHTML("</tr>");
		
		$mpdf->WriteHTML("<tr>");
		$mpdf->WriteHTML("<td colspan='12' style='border-right:0'> 7) In case of ambiguity; our Engineer In-charge’s decision will be final and party has to obey it.</strong></td>");
		$mpdf->WriteHTML("</tr>");
		
		$mpdf->WriteHTML("<tr>");
		$mpdf->WriteHTML("<td colspan='12' style='border-right:0'> 8) All disputes subject to Ahmedabad Jurisdiction only.</strong></td>");
		$mpdf->WriteHTML("</tr>");
		
		$mpdf->WriteHTML("<tr>");
		$mpdf->WriteHTML("<td colspan='12' style='border-right:0'> 9) All Tools, Tackles & Equipment for completing the work need to be procured by you at your cost.</strong></td>");
		$mpdf->WriteHTML("</tr>");
		
		$mpdf->WriteHTML("<tr>");
		$mpdf->WriteHTML("<td colspan='12' style='border-right:0'> 10) Agency/party needs to maintain and obey all safety rules & standards.</strong></td>");
		$mpdf->WriteHTML("</tr>");
		
		$mpdf->WriteHTML("<tr>");
		$mpdf->WriteHTML("<td colspan='12' style='border-right:0'> 11) For payment party will have to submit <strong> Invoice along with Work Order (WO), Measurement Sheet along with Abstract duly signed by Construction Manager, Billing Engineer & Site Accountant.</strong> </td>");
		$mpdf->WriteHTML("</tr>");
		
		$mpdf->WriteHTML("<tr>");
		if($wo_data["bill_mode"] == "gujarat"){
		$mpdf->WriteHTML("<td colspan='12' style='border-right:0'> <strong>Billing Address:</strong>214/5, Khyati Complex, Near Mithakhali Underbridge, Ellisbridge, Ahmedabad - 380006,Gujarat</td>");
		}else if($wo_data["bill_mode"] == "mp"){
		$mpdf->WriteHTML("<td colspan='12' style='border-right:0'> <strong>Billing Address:</strong>A-312, The Bellaire Campus, Abbas Nagar Road, Near Asharam Square, Gandhinagar, Bhopal,M.P. - 462036.</td>");
		}else if($wo_data["bill_mode"] == "maharastra"){
		$mpdf->WriteHTML("<td colspan='12' style='border-right:0'> <strong>Billing Address:</strong>F - 302, P. No. - 21, 22, Sumit Residency, Bhagyashree Ni Kharbi Road, Nagpur, Maharashtra - 440009.</td>");
		}else if($wo_data["bill_mode"] == "haryana"){
		$mpdf->WriteHTML("<td colspan='12' style='border-right:0'> <strong>Billing Address:</strong>Porta Cabin No - 2, Pandit Deen Dayal Upadhaya University of Health Science Campus Site, Gate No - 2 Kutail, Kutail Village, Karnal, Haryana - 134115.</td>");
		}
		$mpdf->WriteHTML("</tr>");
		
		$mpdf->WriteHTML("<tr>");
		$mpdf->WriteHTML("<td colspan='12' style='border-right:0'> <strong>Courier Address:</strong> Plot No: 1003, Opp. Sarita Udhyan Gate, Near Samarpan College, Sector - 8 / D, Gandhinagar, Gujarat - 382007.</td>");
		$mpdf->WriteHTML("</tr>");
		
		$mpdf->WriteHTML("<tr>");
		$mpdf->WriteHTML("<td colspan='6'> <strong>PAN No.:</strong> ".$this->ERPfunction->getstatepanno($wo_data["bill_mode"],$wo_data["wo_date"])."</td>");
		$mpdf->WriteHTML("<td colspan='6' style='border-right:0'> <strong>GST No.:</strong> {$wo_data["gstno"]}</td>");
		$mpdf->WriteHTML("</tr>");
		
		$mpdf->WriteHTML("<tr>");
		$mpdf->WriteHTML("<td colspan='12' style='border-right:0'>12) Your Invoice will be paid after deduction of advances, any type of debit notes, credit notes, retention money / security deposit, taxes etc.</td>");
		$mpdf->WriteHTML("</tr>");
		
		$mpdf->WriteHTML("<tr>");
		$mpdf->WriteHTML("<td colspan='12' style='border-right:0'>13) Retention Money / Security Deposit will be deducted from every bills and will be released after satisfactory work completion.</td>");
		$mpdf->WriteHTML("</tr>");
		
		$mpdf->WriteHTML("<tr>");
		$mpdf->WriteHTML("<td colspan='12' style='border-right:0'>14) {$this->ERPfunction->getconditionofpowo($wo_data["wo_date"],$wo_data["bill_mode"])}</td>");
		$mpdf->WriteHTML("</tr>");
		
		$mpdf->WriteHTML("<tr>");
		$mpdf->WriteHTML("<td colspan='12' style='border-right:0'> 15) Payment will be done <strong>{$wo_data['payment_days']}</strong> days after date of delivery on site or bill submission which ever is later.</td>");
		$mpdf->WriteHTML("</tr>");
	
	}
	
	if($remark_2)
	{
		
	if($wo_data["taxes_duties"])
	{
		$mpdf->WriteHTML("<tr>");
		$mpdf->WriteHTML("<td align='right'>&#10003;</td>");
		$mpdf->WriteHTML("<td colspan='11' style='border-right:0'><strong>All Taxes & Duties</strong></td>");
		$mpdf->WriteHTML("</tr>");
	}
	if($wo_data["loading_transport"])
	{
		$mpdf->WriteHTML("<tr>");
		$mpdf->WriteHTML("<td align='right'>&#10003;</td>");
		$mpdf->WriteHTML("<td colspan='11' style='border-right:0'><strong>Loading & Transportation - F. O. R. at Place of Delivery</strong></td>");
		$mpdf->WriteHTML("</tr>");
	}
	if($wo_data["unloading"])
	{
		$mpdf->WriteHTML("<tr>");
		$mpdf->WriteHTML("<td align='right'>&#10003;</td>");
		$mpdf->WriteHTML("<td colspan='11' style='border-right:0'><strong>Unloading</strong></td>");
		$mpdf->WriteHTML("</tr>");
	}
	if($wo_data["guarantee"])
	{		
		$mpdf->WriteHTML("<tr>");
		$mpdf->WriteHTML("<td align='right'>&#10003;</td>");
		$mpdf->WriteHTML("<td colspan='11' style='border-right:0'><strong>Guarantee up to {$wo_data['guarantee_time']}</strong></td>");
		$mpdf->WriteHTML("</tr>");
	}
	
	if($wo_data["warrenty"] != "")
	{		
		$mpdf->WriteHTML("<tr>");
		$mpdf->WriteHTML("<td align='right'>&#10003;</td>");
		$mpdf->WriteHTML("<td colspan='11' style='border-right:0'><strong>Replacement Warranty up to {$wo_data['warrenty_time']}</strong></td>");
		$mpdf->WriteHTML("</tr>");
	}
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='12' style='border-right:0'> 2) You are also binded to our Contract Conditions & Specifications with Client; which are provided to you.</td>");
	$mpdf->WriteHTML("</tr>"); 
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='12' style='border-right:0'> 3) If work will found unsatisfactory afterwards; agency has to correct it free of cost.</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='12' style='border-right:0'> 4) Material/item supplied must meet IS specifications; on failing to match with it or will found unsatisfactory after some days of delivery; supplier/party has to replace that free of cost and this WO will be considered as void.</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='12' style='border-right:0'> 5) Check Material Make / Brand with the make list provided to you and get its sample approved by our Engineer In-charge,PMC/TPI, Client and other consultant.</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='12' style='border-right:0'> 6) Manufacturer's Test Certificates are required for each batch of supply.</strong></td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='12' style='border-right:0'> 7) Always get your work checked and verified by our Engineer In-charge, PMC/TPI, Client and other consultants also take their prior approval before starting work.</strong></td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='12' style='border-right:0'> 8) Quantity may vary up to any extend afterwards; payment will be done on actual supply & its acceptance.</strong></td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='12' style='border-right:0'> 9) If you will not revert back within 48 hrs, this WO will be considered as accepted by you.</strong></td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='12' style='border-right:0'> 10) In case of ambiguity; our Engineer In-charge’s decision will be final and party has to obey it.</strong></td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='12' style='border-right:0'> 11) All disputes subject to Ahmedabad Jurisdiction only.</strong></td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='12' style='border-right:0'> 12) All Tools, Tackles & Equipment for completing the work need to be procured by you at your cost.</strong></td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='12' style='border-right:0'> 13) Agency/party needs to maintain and obey all safety rules & standards.</strong></td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='12' style='border-right:0'> 14) For payment party will have to submit Invoice along with Work Order (WO), Measurement Sheet & Abstract duly signed by Construction Manager, Billing Engineer & Site Accountant.</strong></td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	if($wo_data["bill_mode"] == "gujarat"){
	$mpdf->WriteHTML("<td colspan='12' style='border-right:0'> <strong>Billing Address:</strong>214/5, Khyati Complex, Near Mithakhali Underbridge, Ellisbridge, Ahmedabad - 380006,Gujarat</td>");
	}else if($wo_data["bill_mode"] == "mp"){
	$mpdf->WriteHTML("<td colspan='12' style='border-right:0'> <strong>Billing Address:</strong>{$this->ERPfunction->getmpbilladdress($wo_data["wo_date"])}</td>");
	}else if($wo_data["bill_mode"] == "maharastra"){
	$mpdf->WriteHTML("<td colspan='12' style='border-right:0'> <strong>Billing Address:</strong>F - 302, P. No. - 21, 22, Sumit Residency, Bhagyashree Ni Kharbi Road, Nagpur, Maharashtra - 440009.</td>");
	}else if($wo_data["bill_mode"] == "haryana"){
	$mpdf->WriteHTML("<td colspan='12' style='border-right:0'> <strong>Billing Address:</strong>Porta Cabin No - 2, Pandit Deen Dayal Upadhaya University of Health Science Campus Site, Gate No - 2 Kutail, Kutail Village, Karnal, Haryana - 134115.</td>");	
	}
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='12' style='border-right:0'> <strong>Courier Address:</strong> Plot No: 1003, Opp. Sarita Udhyan Gate, Near Samarpan College, Sector - 8 / D, Gandhinagar, Gujarat - 382007.</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='6'> <strong>PAN No.:</strong> ".$this->ERPfunction->getstatepanno($wo_data["bill_mode"],$wo_data["wo_date"])."</td>");
	$mpdf->WriteHTML("<td colspan='6' style='border-right:0'> <strong>GST No.:</strong> {$wo_data["gstno"]}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='12' style='border-right:0'>15) Your Invoice will be paid after deduction of advances, any type of debit notes, credit notes, retention money / security deposit, taxes etc.</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='12' style='border-right:0'>16) Retention Money / Security Deposit will be deducted from every bills and will be released after satisfactory work completion.</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='12' style='border-right:0'>17) YashNand has right to cancel order anytime.</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='12' style='border-right:0'> 18) Payment will be done <strong>{$wo_data['payment_days']}</strong> days after date of delivery on site or bill submission which ever is later.</td>");
	$mpdf->WriteHTML("</tr>");
	
	}
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='12' style='border-right:0;color:#333333;font-size:14px;'><pre>{$wo_data['remarks']}</pre></td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td align='center' style='border-bottom:0;' valign='bottom' colspan='8' height='80px'>
	<font size='4'>{$this->ERPfunction->get_user_name($wo_data['created_by'])}<br><strong> Made By </strong></font>
	</td>");
	
	// $mpdf->WriteHTML("<td align='center' style='border-bottom:0;' valign='bottom' colspan='4' height='80px'>
	// <font size='4'>{$this->ERPfunction->get_user_name($verified_by)}<br><strong> Verified By </strong></font>
	// </td>");
	
	$mpdf->WriteHTML("<td align='center' style='border-bottom:0;' valign='bottom' colspan='4' height='80px'>");
	$mpdf->WriteHTML("<font size='4'><strong>{$this->ERPfunction->getletterheadsign($wo_data['wo_date'],$wo_data['bill_mode'])}</strong><br><br>{$this->ERPfunction->get_user_name($first_approved_by)}<br><strong>Authorized Signatory</strong></font></td>");
	// $mpdf->WriteHTML("<td align='center' style='border-bottom:0;' valign='bottom' colspan='4' height='80px'>");
	// $mpdf->WriteHTML("<font size='4'><strong>For YashNand Engineers & Contractors</strong><br><br>{$this->ERPfunction->get_user_name($second_approved_by)}<br><strong>Authorized Signature-2</strong></font></td>");
	$mpdf->WriteHTML("</tr>");
	
	
	
	$mpdf->WriteHTML("</table>");

	 $mpdf->WriteHTML("<br>");
	$mpdf->WriteHTML("<br>");
	if($approved_time != 'NA')
	{
	$mpdf->WriteHTML("<table style='border:none;'>");
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td style='height:30px; border:none;' width=50% >Approved Date : {$approved_time} </td>");
	$mpdf->WriteHTML("</tr>");
	$mpdf->WriteHTML("</table>"); 
	}
	$mpdf->WriteHTML('</body>');	
	$mpdf->WriteHTML('</html>');	
	$mpdf->Output();
	ob_end_flush();
	unset($mpdf);
	
	die;
?>