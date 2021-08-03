<?php
	error_reporting(0);

	$cgst = 0;
	$sgst = 0;
	$igst = 0;
	$gross = 0;

	$party_first_number = $result = substr($data['party_gst_no'], 0, 2);
	$yashnand_first_number = $result = substr($data['yashnand_gst_no'], 0, 2);

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
	if($data['party_type'] == "temp_emp" )
	{
		$partyname = $this->ERPfunction->get_user_name($data['party_id']);
	}else{
		$partyname = (is_numeric($data['party_id']))?$this->ERPfunction->get_vendor_name($data['party_id']):$this->ERPfunction->get_agency_name_by_code($data['party_id']);
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
	
		
	
	// $mpdf->WriteHTML($this->ERPfunction->viewheader_pdf());
	// $mpdf->WriteHTML("<table width=100%  border=1 style='background-color:grey;'>");
	$mpdf->WriteHTML("<table width=100%  border=1>");
	
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='12' align='center'><h2><strong><u>{$partyname}</u></strong></u></h3></td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td align='left'><b>Client: </b></td><td colspan='11'>YashNand Engineers & Contractors PVT LTD [GST No. – {$data['yashnand_gst_no']}]</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td align='left'><b>Project Name: </b></td><td colspan='11'>{$this->ERPfunction->get_projectname($data['project_id'])}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td align='left'><b>Bill No: </b></td><td colspan='3'>{$data['bill_no']}</td>");
	$mpdf->WriteHTML("<td colspan='3' align='left'><b>Date : </b></td><td colspan='5'>".date("d/m/Y",strtotime($data['bill_date']))."</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td align='left'><b>Contact No.: </b></td><td colspan='3'>{$data['party_no1']}</td>");
	$mpdf->WriteHTML("<td colspan='3' align='left'><b>Bill Duration: </b></td><td colspan='5'>".date("d/m/Y",strtotime($data["bill_from_date"]))." to ".date("d/m/Y",strtotime($data["bill_to_date"]))."</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td align='left'><b>PAN Card No: </b></td><td colspan='3'>{$data['party_pan_no']}</td>");
	$mpdf->WriteHTML("<td colspan='3' align='left'><b>GST No : </b></td><td colspan='5'>{$data['party_gst_no']}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td align='left'><b>Our Abstract No.: </b></td><td colspan='3'>{$data['our_abstract_no']}</td>");
	$mpdf->WriteHTML("<td colspan='3' align='left'><b>WO No : </b></td><td colspan='5'>".$data['wo_no']."</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td align='left'><b>Type of Work: </b></td><td colspan='11'>{$data['type_of_work']}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='12' align='center'><h2><strong><u>ABSTRACT</u></strong></u></h3></td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td rowspan='2' align='center'><strong>Item No</strong></td>");
	$mpdf->WriteHTML("<td rowspan='2' align='center'><strong>Description</strong></td>");
	$mpdf->WriteHTML("<td rowspan='2' align='center'><strong>Unit</strong></td>");
	$mpdf->WriteHTML("<td colspan='3' align='center'><strong>Quantity</strong></td>");
	$mpdf->WriteHTML("<td rowspan='2' align='center'><strong>Applied Rate</strong></td>");
	$mpdf->WriteHTML("<td rowspan='2' align='center'><strong>Full Rate</strong></td>");
	if($data['type_of_bill'] == "Labour with Material"){
		$mpdf->WriteHTML("<td colspan='1' align='center'><strong>Amount</strong></td>");
	}else{
		$mpdf->WriteHTML("<td colspan='3' align='center'><strong>Amount</strong></td>");
	}
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td ><strong>This Bill</strong></td>");
	$mpdf->WriteHTML("<td align='center'><strong>Up To <br>Previous Bill</strong></td>");
	$mpdf->WriteHTML("<td ><strong>Till Date</strong></td>");
	if($data['type_of_bill'] != "Labour with Material"){
		$mpdf->WriteHTML("<td ><strong>This Bill</strong></td>");
		$mpdf->WriteHTML("<td align='center'><strong>Up To <br> Previous Bill</strong></td>");
	}
	$mpdf->WriteHTML("<td ><strong>Till Date</strong></td>");
	$mpdf->WriteHTML("</tr>");
	
	
	foreach($detail_data as $retrive_material)
	{			
			$mpdf->WriteHTML("<tr>");
			$mpdf->WriteHTML("<td align=center>{$retrive_material['item_no']}</td>");
			$mpdf->WriteHTML("<td  align=center>{$this->ERPfunction->get_category_title($retrive_material['description'])}</td>");
			$mpdf->WriteHTML("<td  align=center>{$retrive_material['unit']}</td>");
			$mpdf->WriteHTML("<td  align=right>{$retrive_material['quantity_this_bill']}</td>");
			$mpdf->WriteHTML("<td  align=right>{$retrive_material['quantity_previous_bill']}</td>");
			$mpdf->WriteHTML("<td  align=right>{$retrive_material['quantity_till_date']}</td>");
			$mpdf->WriteHTML("<td  align=right>{$retrive_material['rate']}</td>");
			$mpdf->WriteHTML("<td align=right>{$retrive_material['full_rate']}</td>");
			if($data['type_of_bill'] != "Labour with Material"){
				$mpdf->WriteHTML("<td  align=right>{$retrive_material['amount_this_bill']}</td>");
				$mpdf->WriteHTML("<td  align=right>{$retrive_material['amount_previous_bill']}</td>");
			}
			$mpdf->WriteHTML("<td  align=right>{$retrive_material['amount_till_date']}</td>");
			$mpdf->WriteHTML("</tr>");
			$total_amount += $retrive_material['amount'];
	}
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='8' style='text-align:right'><strong>Debit Note</strong></td>");
	if($data['type_of_bill'] != "Labour with Material"){
		$mpdf->WriteHTML("<td align=right>{$data['debit_this_bill']}</td>");
		$mpdf->WriteHTML("<td align=right>{$data['debit_previous_bill']}</td>");
	}
	$mpdf->WriteHTML("<td align=right>{$data['debit_till_date']}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='8' style='text-align:right'><strong>Reconciliation / Material Debit Note</strong></td>");
	if($data['type_of_bill'] != "Labour with Material"){
		$mpdf->WriteHTML("<td align=right>{$data['reconciliation_this_bill']}</td>");
		$mpdf->WriteHTML("<td align=right>{$data['reconciliation_previous_bill']}</td>");
	}
	$mpdf->WriteHTML("<td align=right>{$data['reconciliation_till_date']}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='8' style='text-align:right'><strong>GRAND TOTAL</strong></td>");
	if($data['type_of_bill'] != "Labour with Material"){
		$mpdf->WriteHTML("<td align=right>{$data['sum_a']}</td>");
		$mpdf->WriteHTML("<td align=right>{$data['sum_b']}</td>");
	}
	$mpdf->WriteHTML("<td align=right>{$data['sum_c']}</td>");
	$mpdf->WriteHTML("</tr>");

	if($data['type_of_bill'] == "Labour with Material"){
		$mpdf->WriteHTML("<tr>");
		$mpdf->WriteHTML("<td colspan='8' style='text-align:right'><strong>MATERIAL ADVANCE OR THIS BILL</strong></td>");
		$mpdf->WriteHTML("<td align=right>{$data['material_advance']}</td>");
		$mpdf->WriteHTML("</tr>");

		$mpdf->WriteHTML("<tr>");
		$mpdf->WriteHTML("<td colspan='8' style='text-align:right'><strong>AMOUNT - TILL DATE</strong></td>");
		$mpdf->WriteHTML("<td align=right>{$data['amount_till_date_labour']}</td>");
		$mpdf->WriteHTML("</tr>");

		$mpdf->WriteHTML("<tr>");
		$mpdf->WriteHTML("<td colspan='8' style='text-align:right'><strong>AMOUNT - UPTO PREVIOUS BILL</strong></td>");
		$mpdf->WriteHTML("<td align=right>{$data['amount_upto_previous_labour']}</td>");
		$mpdf->WriteHTML("</tr>");
	}
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='8' style='text-align:right'><strong>THIS BILL AMOUNT</strong></td>");
	$mpdf->WriteHTML("<td colspan=".(($data['type_of_bill'] != 'Labour with Material')?'4':'')." align=right>{$data['this_bill_amount']}</td>");
	$mpdf->WriteHTML("</tr>");
	
	if($cgst){
		$mpdf->WriteHTML("<tr>");
		$mpdf->WriteHTML("<td colspan=".(($data['type_of_bill'] != 'Labour with Material')?'8':'7')." style='text-align:right'><strong>CGST (%)</strong></td>");
		$mpdf->WriteHTML("<td align=right>{$data['cgst_percentage']}%</td>");
		$mpdf->WriteHTML("<td colspan=".(($data['type_of_bill'] != 'Labour with Material')?'3':'')." align=right>{$data['cgst']}</td>");
		$mpdf->WriteHTML("</tr>");
	}
	
	if($sgst){
		$mpdf->WriteHTML("<tr>");
		$mpdf->WriteHTML("<td colspan=".(($data['type_of_bill'] != 'Labour with Material')?'8':'7')." style='text-align:right'><strong>SGST (%)</strong></td>");
		$mpdf->WriteHTML("<td align=right>{$data['sgst_percentage']}%</td>");
		$mpdf->WriteHTML("<td colspan=".(($data['type_of_bill'] != 'Labour with Material')?'3':'')." align=right>{$data['sgst']}</td>");
		$mpdf->WriteHTML("</tr>");
	}
	
	if($igst){
		$mpdf->WriteHTML("<tr>");
		$mpdf->WriteHTML("<td colspan=".(($data['type_of_bill'] != 'Labour with Material')?'8':'7')." style='text-align:right'><strong>IGST (%)</strong></td>");
		$mpdf->WriteHTML("<td align=right>{$data['igst_percentage']}%</td>");
		$mpdf->WriteHTML("<td colspan=".(($data['type_of_bill'] != 'Labour with Material')?'3':'')." align=right>{$data['igst']}</td>");
		$mpdf->WriteHTML("</tr>");
	}
	
	if($gross){
		$mpdf->WriteHTML("<tr>");
		$mpdf->WriteHTML("<td colspan='8' style='text-align:right'><strong>GROSS AMOUNT</strong></td>");
		$mpdf->WriteHTML("<td colspan=".(($data['type_of_bill'] != 'Labour with Material')?'4':'')." align=right>{$data['gross_amount']}</td>");
		$mpdf->WriteHTML("</tr>");
	}
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan=".(($data['type_of_bill'] != 'Labour with Material')?'8':'7')." style='text-align:right'><strong>RETENTION MONEY</strong></td>");
	$mpdf->WriteHTML("<td align=right>{$data['retention_percentage']}%</td>");
	$mpdf->WriteHTML("<td colspan=".(($data['type_of_bill'] != 'Labour with Material')?'3':'')." align=right>{$data['retention_money']}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='8' style='text-align:right'><strong>NET AMOUNT</strong></td>");
	$mpdf->WriteHTML("<td colspan=".(($data['type_of_bill'] != 'Labour with Material')?'4':'')." align=right>{$data['net_amount']}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td align='center' colspan='13'><b>FOR OFFICE USE ONLY</b></td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='8' style='text-align:right'><strong>AMOUNT PAID</strong></td>");
	$mpdf->WriteHTML("<td colspan='4' align=right></td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("</table>");
	
	$mpdf->WriteHTML("<table>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td align='center' style='border-bottom:0;' valign='top' colspan='5' width=25% height='80px'>");
	$mpdf->WriteHTML("<font size='4'><strong><u>CONSTRUCTION MANAGER</u></strong></font></td>");
	
	$mpdf->WriteHTML("<td align='center' style='border-bottom:0;' valign='top' colspan='5' width=25% height='80px'>");
	$mpdf->WriteHTML("<font size='4'><strong><u>ACCOUNTANT</u></strong></font></td>");
	
	$mpdf->WriteHTML("<td align='center' style='border-bottom:0;' valign='top' colspan='5' width=25% height='80px'>");
	$mpdf->WriteHTML("<font size='4'><strong><u>BILLING ENGINEER</u></strong></font></td>");
	
	$mpdf->WriteHTML("<td align='center' style='border-bottom:0;' valign='top' colspan='5' width=25% height='80px'>");
	$mpdf->WriteHTML("<font size='4'><strong><u>PARTY/CONTRACTOR</u></strong></font></td>");
	$mpdf->WriteHTML("</tr>");
	$mpdf->WriteHTML("</table>");

	$mpdf->WriteHTML('</body>');	
	$mpdf->WriteHTML('</html>');	
	$mpdf->Output();
	ob_end_flush();
	unset($mpdf);
	
	die;
?>