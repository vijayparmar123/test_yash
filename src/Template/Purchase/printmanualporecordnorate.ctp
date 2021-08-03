<?php
	error_reporting(0);
	$created_by = isset($project_data['created_by'])?$this->ERPfunction->get_user_name($project_data['created_by']):'NA';
	$last_edit = isset($project_data['last_edit'])?date("m-d-Y H:i:s",strtotime($project_data['last_edit'])):'NA';
	$last_edit_by = isset($project_data['last_edit_by'])?$this->ERPfunction->get_user_name($project_data['last_edit_by']):'NA';

	ob_clean();
	header('Content-type: application/pdf');
	header('Content-Disposition: inline; filename="vendor_information.pdf"');
	header('Content-Transfer-Encoding: binary');
	header('Accept-Ranges: bytes');
	
	/* $mpdf	=	new mPDF('+aCJK'); */
	$mpdf	=	new mPDF('c','A4','','' , 5 , 5 , 0 , 0 , 0 , 0);
	
	
	/* $mpdf->SetHeader("<img border='0' src='".WWW_ROOT."img\logo\yashNand_logo.png'/>|Center Text|{PAGENO}"); */
	// $mpdf->SetHTMLHeader("<div width='10px' style='float:left;text-align: left; font-weight: bold;'>
	// <img border='0' src='".WWW_ROOT ."img/logo/yashNand_logo.png'/>	
	// </div>
	// <div width='20px' style='text-align: center; font-weight: bold;'>
	// <span style='font-size:40px'>YASH-NAND</span><br><span style='font-size:20px'><i><u>Engineers & Contractors</u></i></span>
	// </div>");

	
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
	$mpdf->WriteHTML($this->ERPfunction->viewheader_pdf());
	
	
	
	$mpdf->WriteHTML("<table width=100% border=1>");
	
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='13'  style='border-right:0' align='center'><h2><strong><u>Purchase Order</u> (<u>P.O.</u>)</strong></u></h3></td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='2' align='left'><b>Project Code : </b>{$this->ERPfunction->get_projectcode($data['project_id'])}</td>");
	$mpdf->WriteHTML("<td colspan='11' align='left' style='border-right:0'><b>Project Name : </b>{$this->ERPfunction->get_projectname($data['project_id'])}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td align='left'><b>P. O. No. : </b></td><td colspan='3'>{$data['po_no']}</td>");
	$mpdf->WriteHTML("<td colspan='2' align='left'><b>Date : </b></td><td colspan='3'>".date("d-m-Y",strtotime($data['po_date']))."</td>");
	$mpdf->WriteHTML("<td colspan='2' align='left'><b>Time : </b></td><td colspan='2'  style='border-right:0'>{$data['po_time']}</td>");
	$mpdf->WriteHTML("</tr>");
	$bill_mode = ($data['bill_mode'] == 'mp')?'Madhya Pradesh':ucfirst($data['bill_mode']);
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td align='left'><b>Billing Mode : </b></td><td colspan='4'>{$bill_mode}</td>");
	$mpdf->WriteHTML("<td colspan='3' align='left'><b>Usage : </b></td>");
	$mpdf->WriteHTML("<td colspan='5' align='left'>".ucfirst(str_replace('_',' ',$data['usage_name']))."</td>");
	$mpdf->WriteHTML("</tr>");
	
	
	if($data['agency_id'])
	{
		$mpdf->WriteHTML("<tr>");
		$mpdf->WriteHTML("<td align='left'><b>Debit from Agency : </b></td><td colspan='12'>{$this->ERPfunction->get_agency_name($data['agency_id'])}</td>");
		$mpdf->WriteHTML("</tr>");
	}
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td align='left'><b>Vendor Name : </b></td><td colspan='4'>{$this->ERPfunction->get_vendor_name($data['vendor_userid'])}</td>");
	$mpdf->WriteHTML("<td colspan='3' align='left'><b>Vendor ID : </b></td>");
	$mpdf->WriteHTML("<td colspan='5' align='left' style='border-right:0'>{$data['vendor_id']}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td align='left'><b>Vendor Address : </b></td><td colspan='12' style='border-right:0'>{$data['vendor_address']}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td align='left'><b>PAN No</b></td><td colspan='4'>".$this->ERPfunction->get_vendor_detail($data['vendor_userid'],'pancard_no')."</td>");
	$mpdf->WriteHTML("<td colspan='3' align='left'><b>GST No</b></td>");
	$mpdf->WriteHTML("<td colspan='5' align='left'>".$this->ERPfunction->get_vendor_detail($data['vendor_userid'],'gst_no')."</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td align='left' ><b>Place of Delivery : </b></td><td colspan='12' style='border-right:0'>{$data['vendor_delivery_address']}</td>");
	$mpdf->WriteHTML("</tr>");
	
	// $mpdf->WriteHTML("<tr>");
	// //$mpdf->WriteHTML("<td align='left'><b>P. R. No. : </b></td><td colspan='4'>{$this->ERPfunction->get_pr_no($data['pr_id'])}</td>");
	// $mpdf->WriteHTML("<td align='left'></td><td colspan='4'></td>");
	// $mpdf->WriteHTML("<td colspan='4' align='left'><b>Contact No: (1) : </b></td>");
	// $mpdf->WriteHTML("<td colspan='4' align='left'>{$data['contact_no1']}</td>");
	// $mpdf->WriteHTML("</tr>");
	
	// $mpdf->WriteHTML("<tr>");
	// $mpdf->WriteHTML("<td align='left'><b>Payment Method : </b></td><td colspan='4'>{$data['payment_method']}</td>");
	// $mpdf->WriteHTML("<td colspan='4' align='left'><b>Contact No: (2) : </b></td>");
	// $mpdf->WriteHTML("<td colspan='4' align='left'>{$data['contact_no2']}</td>");
	// $mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td align='left'><b>Site Contact No:(1)</b></td>");
	$mpdf->WriteHTML("<td colspan='4' align='left'>{$data['contact_no1']}</td>");
	$mpdf->WriteHTML("<td colspan='3' align='left'><b>Site Contact No:(2)</b></td>");
	$mpdf->WriteHTML("<td colspan='5' align='left'>{$data['contact_no2']}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$delivery_date =  ($data['delivery_date'] != NULL)?date("d-m-Y",strtotime($data['delivery_date'])):""; 
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td align='left'><b>Payment Method</b></td><td colspan='4'>{$data['payment_method']}</td>");
	$mpdf->WriteHTML("<td colspan='3' align='left'><b>Delivery Date</b></td>");
	$mpdf->WriteHTML("<td colspan='5' align='left'>{$delivery_date}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	//$mpdf->WriteHTML("<td rowspan='2' align='center'><strong>Material Code </strong></td>");
	$mpdf->WriteHTML("<td colspan='13' align='center' style='border-right:0'><strong>Material / Item</strong></td>");
	// $mpdf->WriteHTML("<td rowspan='2'><strong>Amount (Inclusive All)</strong></td>");
	// $mpdf->WriteHTML("<td rowspan='2'><strong>Final Rate<br>(Inclusive All)</strong></td>");
	// $mpdf->WriteHTML("<td rowspan='2'><strong>Delivery Date (Planned)</strong></td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td><strong>Description</strong></td>");
	$mpdf->WriteHTML("<td><strong>HSN Code</strong></td>");
	$mpdf->WriteHTML("<td colspan='6'><strong>Make / Source</strong></td>");
	$mpdf->WriteHTML("<td colspan='3'><strong>Quantity</strong></td>");
	$mpdf->WriteHTML("<td><strong>Unit</strong></td>");
	// $mpdf->WriteHTML("<td><strong>Unit Rate<br>(Rs.)</strong></td>");
	// $mpdf->WriteHTML("<td><strong>Dis<br>(%)</strong></td>");
	// $mpdf->WriteHTML("<td><strong>P&F<br>(%)</strong></td>");
	// $mpdf->WriteHTML("<td><strong>Ex<br>(%)</strong></td>");
	// $mpdf->WriteHTML("<td><strong>Other Taxes<br>(%)</strong></td>");
	$mpdf->WriteHTML("</tr>");
	foreach($previw_list as $retrive_material)
	{
			$m_code =  is_numeric($retrive_material['material_id'])?$this->ERPfunction->get_materialitemcode($retrive_material['material_id']):$retrive_material['m_code']; 
			$mt = is_numeric($retrive_material['material_id'])?$this->ERPfunction->get_material_title($retrive_material['material_id']):$retrive_material['material_id'];
		    $brnd =  is_numeric($retrive_material['brand_id'])?$this->ERPfunction->get_brandname($retrive_material['brand_id']):$retrive_material['brand_id'];
			$unit_name =  is_numeric($retrive_material['material_id'])?$this->ERPfunction->get_items_units($retrive_material['material_id']):$retrive_material['static_unit'];
		
			$mpdf->WriteHTML("<tr>");
			//$mpdf->WriteHTML("<td >{$m_code}</td>");
			$mpdf->WriteHTML("<td width=217px><center>(".$m_code.")<br>".$mt."</center></td>");
			$mpdf->WriteHTML("<td>{$retrive_material['hsn_code']}</td>");
			$mpdf->WriteHTML("<td colspan='6'>{$brnd}</td>");
			$mpdf->WriteHTML("<td colspan='3'>{$retrive_material['quantity']}</td>");
			$mpdf->WriteHTML("<td>{$this->ERPfunction->get_items_units($retrive_material['material_id'])}</td>");
			// $mpdf->WriteHTML("<td>{$retrive_material['unit_price']}</td>");
			// $mpdf->WriteHTML("<td>{$retrive_material['discount']}</td>");
			// $mpdf->WriteHTML("<td>{$retrive_material['transportation']}</td>");
			// $mpdf->WriteHTML("<td>{$retrive_material['exice']}</td>");
			// $mpdf->WriteHTML("<td>{$retrive_material['other_tax']}</td>");
			// $mpdf->WriteHTML("<td>{$retrive_material['amount']}</td>");
			// $mpdf->WriteHTML("<td>{$retrive_material['single_amount']}</td>");
			// $mpdf->WriteHTML("<td>{$retrive_material['delivery_date']->format('d-m-Y')}</td>");
			$mpdf->WriteHTML("</tr>");
	}
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td rowspan='17' valign='top' style='border-bottom:0;'><strong> Remarks/Note:</strong></td>");
	$mpdf->WriteHTML("<td colspan='12' style='border-right:0'> 1) The above mentioned rate includes following:</td>");
	$mpdf->WriteHTML("</tr>");
	
	if($data["taxes_duties"])
	{
		$mpdf->WriteHTML("<tr>");
		$mpdf->WriteHTML("<td align='right'>&#10003;</td>");
		$mpdf->WriteHTML("<td colspan='11' style='border-right:0'><strong>All Taxes & Duties</strong></td>");
		$mpdf->WriteHTML("</tr>");
	}
	if($data["loading_transport"])
	{
		$mpdf->WriteHTML("<tr>");
		$mpdf->WriteHTML("<td align='right'>&#10003;</td>");
		$mpdf->WriteHTML("<td colspan='11' style='border-right:0'><strong>Loading & Transportation - F. O. R. at Place of Delivery</strong></td>");
		$mpdf->WriteHTML("</tr>");
	}
	if($data["unloading"])
	{
		$mpdf->WriteHTML("<tr>");
		$mpdf->WriteHTML("<td align='right'>&#10003;</td>");
		$mpdf->WriteHTML("<td colspan='11' style='border-right:0'><strong>Unloading</strong></td>");
		$mpdf->WriteHTML("</tr>");
	}
	if($data["warranty"] != "")
	{		
		$mpdf->WriteHTML("<tr>");
		$mpdf->WriteHTML("<td align='right'>&#10003;</td>");
		$mpdf->WriteHTML("<td colspan='11' style='border-right:0'><strong>Replacement Warranty up to {$data['warranty']}</strong></td>");
		$mpdf->WriteHTML("</tr>");
	}
	
	/* if($data["loading_transport"] != 1)	
	{		
		$mpdf->WriteHTML("<tr>");
		$mpdf->WriteHTML("<td align='right'>&#10003;</td>");
		$mpdf->WriteHTML("<td colspan='11' style='border-right:0'><strong>Loading & Transportation will be Paid Extra Amount (Rs.):  {$data['extra_transport']}</strong></td>");
		$mpdf->WriteHTML("</tr>");
	} */
	/* $mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='12' style='border-right:0'> 2) The above mentioned rate includes Note - 4 f. o. r. above mentioned delivery address.</td>");
	$mpdf->WriteHTML("</tr>"); */
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='12' style='border-right:0'> 2) Material/item supplied must meet IS specifications; on failing to match with it or will found unsatisfactory after some days of delivery; supplier/party has to replace that free of cost and this PO will be considered as void.</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='12' style='border-right:0'> 3) Manufacturer's Test Certificates are required for each batch of supply.</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='12' style='border-right:0'> 4) Quantity may vary up to any extend afterwards; payment will be done on actual supply & its acceptance.</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='12' style='border-right:0'> 5) If you will not revert back within 48 hrs, this PO will be considered as accepted by you.</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='12' style='border-right:0'> 6) In case of ambiguity; our Engineer In-charge’s decision will be final and party has to obey it.</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='12' style='border-right:0'> 7) All disputes subject to Ahmedabad Jurisdiction only.</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='12' style='border-right:0'> 8) Party will have to send <strong>Invoice and Purchase Order (PO) along with Material / Item.</strong> Payment will be processed after receiving approval from project authorities, <strong>Goods Receipt Note and/or Weight Pass.</strong></td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	if($data["bill_mode"] == "gujarat"){
	$mpdf->WriteHTML("<td colspan='12' style='border-right:0'> <strong>Billing Address: </strong>214/5, Khyati Complex, Near Mithakhali Underbridge, Ellisbridge, Ahmedabad - 380006,Gujarat</td>");
	}else if($data["bill_mode"] == "mp"){
	$mpdf->WriteHTML("<td colspan='12' style='border-right:0'> <strong>Billing Address: </strong>A-312, The Bellaire Campus, Abbas Nagar Road, Near Asharam Square, Gandhinagar, Bhopal,M.P. - 462036.</td>");
	}
	else{
	$mpdf->WriteHTML("<td colspan='12' style='border-right:0'> <strong>Billing Address: </strong>F - 302, P. No. - 21, 22, Sumit Residency, Bhagyashree Ni Kharbi Road, Nagpur, Maharashtra - 440009.</td>");
	}
	$mpdf->WriteHTML("</tr>");
	
	// $mpdf->WriteHTML("<tr>");
	// $mpdf->WriteHTML("<td colspan='12' style='border-right:0'> <strong>Courier Address:</strong> Plot No: 1003, Opp. Sarita Udhyan Gate, Near Samarpan College, Sector - 8 / D, Gandhinagar, Gujarat - 382007.</td>");
	// $mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='6'> <strong>PAN No.:</strong> AAAFY3210E</td>");
	$mpdf->WriteHTML("<td colspan='6' style='border-right:0'> <strong>GST No.:</strong> {$data["gstno"]}</td>");
	//$mpdf->WriteHTML("<td colspan='6' style='border-right:0'> <strong>Service Tax No.:</strong> AAAFY3210EST001</td>");
	$mpdf->WriteHTML("</tr>");
	
	// $mpdf->WriteHTML("<tr>");
	// $mpdf->WriteHTML("<td colspan='6' style='border-right:0'> <strong>VAT/TIN No.:</strong>{$data['vatno']}</td>");
	// $mpdf->WriteHTML("<td colspan='6' style='border-right:0'> <strong>CST No.:</strong> {$data["cstno"]}</td>");
	// $mpdf->WriteHTML("</tr>");
	
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='12' style='border-right:0'> 9) YashNand Engineers & Contractors has right to cancel order any time without any prior notice.</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='12' style='border-right:0'> 10) Payment will be done <strong>{$data['payment_days']}</strong> days after date of delivery on site or bill submission which ever is later.</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td align='center' style='border-bottom:0;' valign='bottom' colspan='6' height='80px'>
	<font size='4'>({$this->ERPfunction->get_user_name($data['created_by'])})<br><strong> Prepared by </strong></font>
	</td>");
	$mpdf->WriteHTML("<td align='center' style='border-bottom:0;' valign='bottom' colspan='6' height='80px'><font size='4'><strong>For YashNand Engineers & Contractors</strong></font><br><br><br>");
						// $approver = array();
						// $ids = array();
						// foreach($previw_list as $retrive_material){
							// if(!in_array($retrive_material['approved_by'],$ids))
							// {
							// $approver[] = $this->ERPfunction->get_user_name($retrive_material['approved_by']);
							// $ids[] = $retrive_material['approved_by'];
							// }
						// }
						
						// foreach($approver as $app){
							// $mpdf->WriteHTML($app . "<br>");
						// }
						foreach($previw_list as $retrive_material)
						{
							if($retrive_material['first_approved_by'])
							{
								$mpdf->WriteHTML($this->ERPfunction->get_user_name($retrive_material['first_approved_by'])."<br>");
								break;
							}
						}
	$mpdf->WriteHTML("<font size='4'><strong>Authorized Signature</strong></font></td>");
	$mpdf->WriteHTML("</tr>");
	
	
	
	$mpdf->WriteHTML("</table>");
	

	
	$mpdf->WriteHTML("<table width=100% border=1>");
	
	if(!empty($data["quantity"]))
	{
		$mpdf->WriteHTML("<br>");
		$mpdf->WriteHTML("<hr/>");
		$mpdf->WriteHTML("<br>");
		$driver_name = json_decode($data["driver_name"]);
		$tmo = json_decode($data["tmno"]);
		$time_in = json_decode($data["time_in"]);
		$time_out = json_decode($data["time_out"]);
		$quantity = json_decode($data["quantity"]);
		$received_by = json_decode($data["received_by"]);
		$challan = json_decode($data["challan"]);								
		$size = count($tmo);
		$mpdf->WriteHTML("<tr>");
		$mpdf->WriteHTML("<th>TM's No</th><th>Driver's Name</th><th>Time In</th><th>Time Out</th><th>Quantity<br>(In Cum)</th><th>Received By</th>");	
		$mpdf->WriteHTML("</tr>");
		
		for($i=0;$i<$size;$i++)
		{
			$mpdf->WriteHTML("<tr>");
				$mpdf->WriteHTML("<td align=center>{$tmo[$i]}</td>");
				$mpdf->WriteHTML("<td align=center>{$driver_name[$i]}</td>");
				$mpdf->WriteHTML("<td align=center>{$time_in[$i]}</td>");
				$mpdf->WriteHTML("<td align=center>{$time_out[$i]}</td>");
				$mpdf->WriteHTML("<td align=center>{$quantity[$i]}</td>");
				$mpdf->WriteHTML("<td align=center>{$received_by[$i]}</td>");			
			$mpdf->WriteHTML("</tr>");
		}
	}
	
	$mpdf->WriteHTML("</table>");
	
	$mpdf->WriteHTML('</body>');	
	$mpdf->WriteHTML('</html>');	
	$mpdf->Output();
	ob_end_flush();
	unset($mpdf);
	
	die;
?>