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
				}
				
				</style>');
	$mpdf->WriteHTML('</head>');
	$mpdf->WriteHTML('<body>');	
	
		
	$mpdf->WriteHTML("<table width=100%  border=0>");
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td align='center' style='border:none;' colspan='13' height='120px'>");
	$mpdf->WriteHTML("</td>");
	$mpdf->WriteHTML("</tr>");
	$mpdf->WriteHTML("</table>");
	
	// $mpdf->WriteHTML($this->ERPfunction->viewheader_pdf());
	// $mpdf->WriteHTML("<table width=100%  border=1 style='background-color:grey;'>");
	$mpdf->WriteHTML("<table width=100% border=1>");
	
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td align='left'><h2><strong>Bill No:</strong>{$data['bill_no']}</h2></td>");
	$mpdf->WriteHTML("<td align='right' style='border-left:1px'><h2><strong>Date:</strong>".date('d/m/Y')."</h2></td>");
	$mpdf->WriteHTML("</tr>");
	$mpdf->WriteHTML("</table>");
	$mpdf->WriteHTML("<table width=100%>");
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td align='left' colspan='13'><b>To,</b></td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td align='left' colspan='13'><b>Project Manager</b></td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td align='left' colspan='13'>YashNand Engineers & Contractors PVT LTD,</td>");
	$mpdf->WriteHTML("</tr>");
	
	if($data['bill_mode'] == "gujarat")
	{
		$mpdf->WriteHTML("<tr>");
		$mpdf->WriteHTML("<td align='left' colspan='13'>214/5, Khyati Complex,</td>");
		$mpdf->WriteHTML("</tr>");
		
		$mpdf->WriteHTML("<tr>");
		$mpdf->WriteHTML("<td align='left' colspan='13'>Near Mithakhali Underbridge,</td>");
		$mpdf->WriteHTML("</tr>");
		
		$mpdf->WriteHTML("<tr>");
		$mpdf->WriteHTML("<td align='left' colspan='13'>Ellisbridge,</td>");
		$mpdf->WriteHTML("</tr>");
		
		$mpdf->WriteHTML("<tr>");
		$mpdf->WriteHTML("<td align='left' colspan='13'>Ahmedabad - 380006,Gujarat</td>");
		$mpdf->WriteHTML("</tr>");
	}
	
	if($data['bill_mode'] == "mp")
	{		
		$mpdf->WriteHTML("<tr>");
		$mpdf->WriteHTML("<td align='left' colspan='13'>A-312, The Bellaire Campus,</td>");
		$mpdf->WriteHTML("</tr>");
		
		$mpdf->WriteHTML("<tr>");
		$mpdf->WriteHTML("<td align='left' colspan='13'>Abbas Nagar Road,</td>");
		$mpdf->WriteHTML("</tr>");
		
		$mpdf->WriteHTML("<tr>");
		$mpdf->WriteHTML("<td align='left' colspan='13'>Near Asharam Square,</td>");
		$mpdf->WriteHTML("</tr>");
		
		$mpdf->WriteHTML("<tr>");
		$mpdf->WriteHTML("<td align='left' colspan='13'>Gandhinagar, Bhopal,M.P. - 462036.</td>");
		$mpdf->WriteHTML("</tr>");
	}
	
	if($data['bill_mode'] == "maharastra")
	{
		$mpdf->WriteHTML("<tr>");
		$mpdf->WriteHTML("<td align='left' colspan='13'>F - 302, P. No. - 21, 22,</td>");
		$mpdf->WriteHTML("</tr>");
		
		$mpdf->WriteHTML("<tr>");
		$mpdf->WriteHTML("<td align='left' colspan='13'>Sumit Residency,</td>");
		$mpdf->WriteHTML("</tr>");
		
		$mpdf->WriteHTML("<tr>");
		$mpdf->WriteHTML("<td align='left' colspan='13'>Bhagyashree Ni Kharbi Road,</td>");
		$mpdf->WriteHTML("</tr>");
		
		$mpdf->WriteHTML("<tr>");
		$mpdf->WriteHTML("<td align='left' colspan='13'>Nagpur, Maharashtra - 440009.</td>");
		$mpdf->WriteHTML("</tr>");
	}
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td align='left' colspan='13'><b>Project Name:- </b>{$this->ERPfunction->get_projectname($data['project_id'])}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td align='left' colspan='13'><b>GST No.- </b>{$data['yashnand_gst_no']}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td align='left' colspan='13'><b>Type of Work:- </b>{$data['type_of_work']}</td>");
	$mpdf->WriteHTML("</tr>");
	$mpdf->WriteHTML("</table>");
	$mpdf->WriteHTML("<table width=100%  border=1>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='13' align='center'><h2><strong><u>INVOICE</u></strong></u></h3></td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td align='center'><strong>Item No</strong></td>");
	$mpdf->WriteHTML("<td align='center'><strong>Description</strong></td>");
	$mpdf->WriteHTML("<td align='center'><strong>Unit</strong></td>");
	$mpdf->WriteHTML("<td align='center'><strong>This Bill Qty.</strong></td>");
	$mpdf->WriteHTML("<td align='center'><strong>Rate</strong></td>");
	$mpdf->WriteHTML("<td align='center'><strong>This Bill Amount</strong></td>");
	$mpdf->WriteHTML("</tr>");
	
	
				
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td align=center>1</td>");
	$mpdf->WriteHTML("<td align=center>[As per Abstract Sheet]</td>");
	$mpdf->WriteHTML("<td align=center>[As per Abstract Sheet]</td>");
	$mpdf->WriteHTML("<td align=center>[As per Abstract Sheet]</td>");
	$mpdf->WriteHTML("<td align=center>[As per Abstract Sheet]</td>");
	$mpdf->WriteHTML("<td align=right>{$data['this_bill_amount']}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='4' style='text-align:center'><strong>THIS BILL AMOUNT</strong></td>");
	$mpdf->WriteHTML("<td align=right colspan='2'>{$data['this_bill_amount']}</td>");
	$mpdf->WriteHTML("</tr>");
	
	if($cgst){
		$mpdf->WriteHTML("<tr>");
		$mpdf->WriteHTML("<td colspan='4' style='text-align:center'><strong>CGST</strong></td>");
		$mpdf->WriteHTML("<td align=right>{$data['cgst_percentage']}%</td>");
		$mpdf->WriteHTML("<td align=right>{$data['cgst']}</td>");
		$mpdf->WriteHTML("</tr>");
	}
	
	if($sgst){
		$mpdf->WriteHTML("<tr>");
		$mpdf->WriteHTML("<td colspan='4' style='text-align:center'><strong>SGST</strong></td>");
		$mpdf->WriteHTML("<td align=right>{$data['sgst_percentage']}%</td>");
		$mpdf->WriteHTML("<td align=right>{$data['sgst']}</td>");
		$mpdf->WriteHTML("</tr>");
	}
	
	if($igst){
		$mpdf->WriteHTML("<tr>");
		$mpdf->WriteHTML("<td colspan='4' style='text-align:right'><strong>IGST</strong></td>");
		$mpdf->WriteHTML("<td align=right>{$data['igst_percentage']}%</td>");
		$mpdf->WriteHTML("<td align=right>{$data['igst']}</td>");
		$mpdf->WriteHTML("</tr>");
	}
	
	if($gross){
		$mpdf->WriteHTML("<tr>");
		$mpdf->WriteHTML("<td colspan='4' style='text-align:center'><strong>GROSS AMOUNT</strong></td>");
		$mpdf->WriteHTML("<td colspan='2' align=right>{$data['gross_amount']}</td>");
		$mpdf->WriteHTML("</tr>");
	}
	
	/*$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='4' style='text-align:center'><strong>RETENTION MONEY</strong></td>");
	$mpdf->WriteHTML("<td align=right>{$data['retention_percentage']}%</td>");
	$mpdf->WriteHTML("<td align=right>{$data['retention_money']}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='4' style='text-align:center'><strong>NET AMOUNT</strong></td>");
	$mpdf->WriteHTML("<td colspan='2' align=right>{$data['net_amount']}</td>");
	$mpdf->WriteHTML("</tr>");*/
	
	$mpdf->WriteHTML("</table>");
	
	$mpdf->WriteHTML("<table width=100%>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='13' height='20px'></td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td align='right' style='border-bottom:0;' colspan='13'>");
	$mpdf->WriteHTML("<font size='4'><strong>For, {$partyname}</strong></font></td>");	
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='13' height='25px'></td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='13' height='25px'></td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='13' height='25px'></td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td align='right' style='border-bottom:0;' colspan='13'>");
	$mpdf->WriteHTML("<font size='4'><strong>Authorised Signatory</strong></font></td>");	
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("</table>");

	$mpdf->WriteHTML('</body>');	
	$mpdf->WriteHTML('</html>');	
	$mpdf->Output();
	ob_end_flush();
	unset($mpdf);
	
	die;
?>