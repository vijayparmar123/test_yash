<?php
	error_reporting(0);
	
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
	$mpdf->WriteHTML("<td colspan='12' align='center'><h2><strong><u>{$this->ERPFunction->get_user_bank_name($user_id)}</u></strong></u></h3></td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td align='left' colspan='1'><b>Client: </b></td><td colspan='11'>YASHNAND ENGINEERS AND CONTRACTORS PVT. LTD.</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td align='left'><b>Site: </b></td><td colspan='11'>{$this->ERPFunction->get_user_employee_at($user_id)}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td align='left'><b>Bill No: </b></td><td colspan='3'>{$month}-{$year}</td>");
	$mpdf->WriteHTML("<td colspan='3' align='left'><b>Date : </b></td><td colspan='5'>".date("d-m-Y",strtotime($data['created_date']))."</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td align='left'><b>Pan Card No.: </b></td><td colspan='3'>{$this->ERPFunction->get_user_pan_card($user_id)}</td>");
	$mpdf->WriteHTML("<td colspan='3' align='left'><b>GST No.: </b></td><td colspan='5'>Not Given</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td align='left'><b>Contact No.: </b></td><td colspan='11'>{$this->ERPFunction->get_user_contact_no($user_id)}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td align='left'><b>Type of Work: </b></td><td colspan='11'>{$this->ERPfunction->get_user_designation($user_id)}</td>");
	$mpdf->WriteHTML("</tr>");
	$mpdf->WriteHTML("</table>");
	$mpdf->WriteHTML("<table width=100%>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='12' align='center'><h2><strong><u>ABSTRACT</u></strong></u></h3></td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td align='center'><strong>Item No</strong></td>");
	$mpdf->WriteHTML("<td align='center' width='470px'><strong>Description</strong></td>");
	$mpdf->WriteHTML("<td align='center'><strong>Unit</strong></td>");
	$mpdf->WriteHTML("<td align='center'><strong>This Bill Qty.</strong></td>");
	$mpdf->WriteHTML("<td align='center'><strong>Rate</strong></td>");
	$mpdf->WriteHTML("<td align='center'><strong>This Bill Amount</strong></td>");
	$mpdf->WriteHTML("</tr>");
				
	$this_bill_qty = number_format((float)$data['payable_days'] / $total_days, 2, '.', '');
	$this_bill_amount = number_format((float)$this_bill_qty * $data['basic_salary'], 2, '.', '');
	$grand_total = $this_bill_amount - $data['others'];
	$paid_amount = $grand_total - $data['loan_paymennt'];
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td align=center>1</td>");
	$mpdf->WriteHTML("<td><strong>Service Charge - {$this->ERPfunction->get_user_designation($user_id)}</strong></td>");
	$mpdf->WriteHTML("<td width=60px align=center>{$month}</td>");
	$mpdf->WriteHTML("<td width=57px align=right>".number_format((float)$data['payable_days'] / $total_days, 2, '.', '')."</td>");
	$mpdf->WriteHTML("<td width=80px align=right>{$data['basic_salary']}</td>");
	$mpdf->WriteHTML("<td width=57px align=right>". number_format((float)$this_bill_qty * $data['basic_salary'], 2, '.', '')."</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td align=center>2</td>");
	$mpdf->WriteHTML("<td colspan='4' style='text-align:left'><strong>Debit Note</strong></td>");
	$mpdf->WriteHTML("<td align=right>{$data['others']}</td>");
	$mpdf->WriteHTML("</tr>");
		
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td align=center>3</td>");
	$mpdf->WriteHTML("<td colspan='4' style='text-align:left'><strong>Reconciliation / Material Debit Note</strong></td>");
	$mpdf->WriteHTML("<td align=right>0.00</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='5' style='text-align:right'><strong>GRAND TOTAL</strong></td>");
	$mpdf->WriteHTML("<td align=right>{$grand_total}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='5' style='text-align:right'><strong>TOTAL AMOUNT THIS BILL</strong></td>");
	$mpdf->WriteHTML("<td align=right>{$grand_total}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='5' style='text-align:right'><strong>RETENTION MONEY FOR THIS BILL</strong></td>");
	$mpdf->WriteHTML("<td align=right>0.00</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='5' style='text-align:right'><strong>NET AMOUNT OF THIS BILL</strong></td>");
	$mpdf->WriteHTML("<td align=right>{$grand_total}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td align='center' colspan='12'><b>FOR OFFICE USE ONLY</b></td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='5' style='text-align:right'><strong>ADVANCE/UPAD IN CHEQUE</strong></td>");
	$mpdf->WriteHTML("<td align=right>{$data['loan_payment']}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='5' style='text-align:right'><strong>TOTAL AMOUNT TO BE PAID</strong></td>");
	$mpdf->WriteHTML("<td align=right>{$paid_amount}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("</table>");
	
	$mpdf->WriteHTML("<table>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td align='center' style='border-bottom:0;' valign='top' colspan='6' width=60% height='80px'>");
	$mpdf->WriteHTML("<font size='4'><strong><u>PROJECT MANAGER</u></strong></font></td>");
	
	$mpdf->WriteHTML("<td align='center' style='border-bottom:0;' valign='top' colspan='5' width=50% height='80px'>");
	$mpdf->WriteHTML("<font size='4'><strong><u>ACCOUNTANT</u></strong></font></td>");
	
	$mpdf->WriteHTML("<td align='center' style='border-bottom:0;' valign='top' colspan='5' width=25% height='80px'>");
	$mpdf->WriteHTML("<font size='4'><strong><u>CONTRACTOR</u></strong></font></td>");
	
	// $mpdf->WriteHTML("<td align='center' style='border-bottom:0;' valign='top' colspan='5' width=25% height='80px'>");
	// $mpdf->WriteHTML("<font size='4'><strong><u>PARTY/CONTRACTOR</u></strong></font></td>");
	$mpdf->WriteHTML("</tr>");
	$mpdf->WriteHTML("</table>");

	$mpdf->WriteHTML('</body>');	
	$mpdf->WriteHTML('</html>');	
	$mpdf->Output();
	ob_end_flush();
	unset($mpdf);
	
	die;
?>