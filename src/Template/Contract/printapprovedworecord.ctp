<?php
	error_reporting(0);
	// $count = $previw_list->count();

	
	
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
	$mpdf->WriteHTML("<td align='left'><b>W. O. No. : </b></td><td colspan='12'>{$wo_data['wo_no']}</td>");
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
	
	//$target_date = ($wo_data['target_date'] != NULL)?date("d-m-Y",strtotime($wo_data['target_date'])):"";
	//$mpdf->WriteHTML("<tr>");
	//$mpdf->WriteHTML("<td align='left'><b>Target Date: </b></td><td colspan='12'>{$target_date}</td>");
	//$mpdf->WriteHTML("</tr>");
	// $mpdf->WriteHTML("<tr>");
	// $mpdf->WriteHTML("<td rowspan='2' align='center'><strong>Contract Item <br>No.</strong></td>");
	// $mpdf->WriteHTML("<td colspan='9' align='center'><strong>Work / Item</strong></td>");
	// $mpdf->WriteHTML("<td rowspan='2'><strong>Amount (Inclusive All)</strong></td>");
	// $mpdf->WriteHTML("<td rowspan='2'><strong>Target Date</strong></td>");
	// $mpdf->WriteHTML("</tr>");
	
	// $mpdf->WriteHTML("<tr>");
	// $mpdf->WriteHTML("<td><strong>Work Head</strong></td>");
	// $mpdf->WriteHTML("<td><strong>Description</strong></td>");
	// $mpdf->WriteHTML("<td><strong>Quantity</strong></td>");
	// $mpdf->WriteHTML("<td><strong>Unit</strong></td>");
	// $mpdf->WriteHTML("<td><strong>Unit Rate<br>(Rs.)</strong></td>");
	// $mpdf->WriteHTML("<td><strong>Dis<br>(%)</strong></td>");
	// $mpdf->WriteHTML("<td><strong>CGST<br>(%)</strong></td>");
	// $mpdf->WriteHTML("<td><strong>SGST<br>(%)</strong></td>");
	// $mpdf->WriteHTML("<td><strong>IGST<br>(%)</strong></td>");
	// $mpdf->WriteHTML("</tr>");
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td rowspan='2' width=60px align='center'><strong>Contract Item <br>No.</strong></td>");
	$mpdf->WriteHTML("<td colspan='9' align='center'><strong>Work / Item</strong></td>");
	$mpdf->WriteHTML("<td width=60px rowspan='2'><strong>Amount (Inclusive <br> All)</strong></td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td width=80px><strong>Work Head</strong></td>");
	$mpdf->WriteHTML("<td width=250px><strong>Description</strong></td>");
	$mpdf->WriteHTML("<td width=52px><strong>Quantity</strong></td>");
	$mpdf->WriteHTML("<td width=60px><strong>Unit</strong></td>");
	$mpdf->WriteHTML("<td width=60px><strong>Unit Rate<br>(Rs.)</strong></td>");
	$mpdf->WriteHTML("<td width=35px><strong>Dis<br>(%)</strong></td>");
	$mpdf->WriteHTML("<td width=35px><strong>CGST<br>(%)</strong></td>");
	$mpdf->WriteHTML("<td width=35px><strong>SGST<br>(%)</strong></td>");
	$mpdf->WriteHTML("<td width=35px><strong>IGST<br>(%)</strong></td>");
	$mpdf->WriteHTML("</tr>");
	$mpdf->WriteHTML("</table>");
	$mpdf->WriteHTML("<table>");
	$total_amount = 0;
	foreach($wod_data as $retrive_material)
	{
			$first_approved_by = $retrive_material['first_approved_by'];
			$second_approved_by = $retrive_material['approved_by'];
			$verified_by = $retrive_material['verified_by'];
			// $mpdf->WriteHTML("<tr>");
			// $mpdf->WriteHTML("<td>{$retrive_material['contract_no']}</td>");
			// $mpdf->WriteHTML("<td>{$this->ERPfunction->get_work_head_title($retrive_material['work_head'])}</td>");
			// $mpdf->WriteHTML("<td>{$retrive_material['material_name']}</td>");
			// $mpdf->WriteHTML("<td>{$retrive_material['quentity']}</td>");
			// $mpdf->WriteHTML("<td>{$retrive_material['unit']}</td>");
			// $mpdf->WriteHTML("<td>{$retrive_material['unit_rate']}</td>");
			// $mpdf->WriteHTML("<td>{$retrive_material['discount']}</td>");
			// $mpdf->WriteHTML("<td>{$retrive_material['cgst']}</td>");
			// $mpdf->WriteHTML("<td>{$retrive_material['sgst']}</td>");
			// $mpdf->WriteHTML("<td>{$retrive_material['igst']}</td>");
			// $mpdf->WriteHTML("<td>{$retrive_material['amount']}</td>");
			// $mpdf->WriteHTML("<td>{$retrive_material['target_date']->format('d-m-Y')}</td>");
			// $mpdf->WriteHTML("</tr>");
			$mpdf->WriteHTML("<tr>");
			$mpdf->WriteHTML("<td width=60px>{$retrive_material['contract_no']}</td>");
			$mpdf->WriteHTML("<td width=80px>{$this->ERPfunction->get_work_head_title($retrive_material['work_head'])}</td>");
			$mpdf->WriteHTML("<td width=248px>{$retrive_material['material_name']}</td>");
			$mpdf->WriteHTML("<td width=52px>{$retrive_material['quentity']}</td>");
			$mpdf->WriteHTML("<td width=60px>{$retrive_material['unit']}</td>");
			$mpdf->WriteHTML("<td width=60px>{$retrive_material['unit_rate']}</td>");
			$mpdf->WriteHTML("<td width=35px>{$retrive_material['discount']}</td>");
			$mpdf->WriteHTML("<td width=35px>{$retrive_material['cgst']}</td>");
			$mpdf->WriteHTML("<td width=35px>{$retrive_material['sgst']}</td>");
			$mpdf->WriteHTML("<td width=35px>{$retrive_material['igst']}</td>");
			$mpdf->WriteHTML("<td width=61px>{$retrive_material['amount']}</td>");
			$mpdf->WriteHTML("</tr>");
			$total_amount += $retrive_material['amount'];
	}
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='10' style='text-align:right'><strong>Total Amount</strong></td>");
	$mpdf->WriteHTML("<td><strong>{$total_amount}</strong></td>");
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
	$mpdf->WriteHTML("<td colspan='12' style='border-right:0'> <strong>Billing Address:</strong>F - 302, P. No. - 21, 22, Sumit Residency, Bhagyashree Ni Kharbi Road, Nagpur, Maharashtra - 440009.");
	}else if($wo_data["bill_mode"] == "haryana"){
	$mpdf->WriteHTML("<td colspan='12' style='border-right:0'> <strong>Billing Address:</strong>Porta Cabin No - 2, Pandit Deen Dayal Upadhaya University of Health Science Campus Site, Gate No - 2 Kutail, Kutail Village, Karnal, Haryana - 134115.");	
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
	$mpdf->WriteHTML("<td colspan='12' style='border-right:0'>17) {$this->ERPfunction->getconditionofpowo($wo_data["wo_date"],$wo_data["bill_mode"])}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='12' style='border-right:0'> 18) Payment will be done <strong>{$wo_data['payment_days']}</strong> days after date of delivery on site or bill submission which ever is later.</td>");
	$mpdf->WriteHTML("</tr>");
	
	}
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='12' style='border-right:0;color:#333333;font-size:14px;'><pre>{$wo_data['remarks']}</pre></td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td align='center' style='border-bottom:0;' valign='bottom' colspan='4' height='80px'>
	<font size='4'>{$this->ERPfunction->get_user_name($wo_data['created_by'])}<br><strong> Made By </strong></font>
	</td>");
	
	$mpdf->WriteHTML("<td align='center' style='border-bottom:0;' valign='bottom' colspan='4' height='80px'>
	<font size='4'>{$this->ERPfunction->get_user_name($verified_by)}<br><strong> Verified By </strong></font>
	</td>");
	
	$mpdf->WriteHTML("<td align='center' style='border-bottom:0;' valign='bottom' colspan='4' height='80px'>");
	$mpdf->WriteHTML("<font size='4'><strong>{$this->ERPfunction->getletterheadsign($wo_data['wo_date'],$wo_data['bill_mode'])}</strong><br><br>{$this->ERPfunction->get_user_name($first_approved_by)}<br><strong>Authorized Signatory-1</strong></font></td>");
	// $mpdf->WriteHTML("<td align='center' style='border-bottom:0;' valign='bottom' colspan='4' height='80px'>");
	// $mpdf->WriteHTML("<font size='4'><strong>For YashNand Engineers &<br> Contractors</strong><br><br>{$this->ERPfunction->get_user_name($second_approved_by)}<br><strong>Authorized Signature-2</strong></font></td>");
	$mpdf->WriteHTML("</tr>");
	
	
	
	$mpdf->WriteHTML("</table>");

	 $mpdf->WriteHTML("<br>");
	$mpdf->WriteHTML("<br>");
	$mpdf->WriteHTML('</body>');	
	$mpdf->WriteHTML('</html>');	
	$mpdf->Output();
	ob_end_flush();
	unset($mpdf);
	
	die;
?>