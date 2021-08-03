.<?php
	error_reporting(0);

// $created_by = isset($erp_grn_details['created_by'])?$this->ERPfunction->get_user_name($erp_grn_details['created_by']):'NA';
// $last_edit = isset($erp_grn_details['last_edit'])?date("m-d-Y H:i:s",strtotime($erp_grn_details['last_edit'])):'NA';
// $last_edit_by = isset($erp_grn_details['last_edit_by'])?$this->ERPfunction->get_user_name($erp_grn_details['last_edit_by']):'NA';


	ob_clean();
	header('Content-type: application/pdf');
	header('Content-Disposition: inline; filename="rabill_information.pdf"');
	header('Content-Transfer-Encoding: binary');
	header('Accept-Ranges: bytes');
	
	/* $mpdf	=	new mPDF('+aCJK'); */
	$mpdf	=	new mPDF('c','A4','','' , 5 , 5 , 0 , 0 , 0 , 0);
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
					color : #333;
					border :1;
					border-color :gray;
					border-collapse:collapse;
				}
				td{
					padding:10px;
				}
				strong{
					color :#333;
				}		
				</style>');
	$mpdf->WriteHTML('</head>');
	$mpdf->WriteHTML('<body>');	
	// $mpdf->WriteHTML("<img style='margin-top:-30px' height=30% border='0' src='".WWW_ROOT ."img/logo/header.jpg'/>");
	$mpdf->WriteHTML($this->ERPfunction->viewheader_pdf($print_data['qty_taken_uptodate']));
	$mpdf->WriteHTML("<br>");
	$mpdf->WriteHTML("<table width=100%>");
	$mpdf->WriteHTML("<tbody>");
		// $mpdf->WriteHTML("<tr><td colspan='8' align='center'><h1><b>YashNand Engineers & Contractors</b></h1></td></tr>");
		$mpdf->WriteHTML("<tr><td colspan='8' align='center'><h2><b>R.A BILL INFORMATION</b></h2></td></tr>");
		$mpdf->WriteHTML("<tr>");
			// $mpdf->WriteHTML("<td colspan='2'><b>Project Code : </b>{$this->ERPfunction->get_projectcode($erp_grn_details['project_id'])}</td>");
			$mpdf->WriteHTML("<td colspan='8'><b><u>PROJECT INFORMATION</u></b></td>");
			$mpdf->WriteHTML("</tr>");
			
			
			
			$mpdf->WriteHTML("<tr>");
			// $mpdf->WriteHTML("<td colspan='2'><b>Project Code : </b>{$this->ERPfunction->get_projectcode($erp_grn_details['project_id'])}</td>");
			$mpdf->WriteHTML("<td><b>Project Code:</b></td><td colspan='3'>{$print_data['project_code']}</td>");
				$mpdf->WriteHTML("<td><b>Project Name:</b></td><td colspan='3'>{$this->ERPfunction->get_projectname($print_data['project_id'])}</td>");
			$mpdf->WriteHTML("</tr>");
	
			$mpdf->WriteHTML("<tr>");
				$mpdf->WriteHTML("<td><b>R.A Bill No:</b></td><td colspan='3'>{$print_data['ra_bill_no']}</td>");
				$mpdf->WriteHTML("<td><b>Qty.Taken upto Date:</b></td><td colspan='3'>{$this->ERPfunction->get_date($print_data['qty_taken_uptodate'])}</td>");
				
			$mpdf->WriteHTML("</tr>");
			
			$mpdf->WriteHTML("<tr>");
			$mpdf->WriteHTML("<td colspan='8'><b><u>AMOUNT OF THIS BILL</u></b></td>");
			$mpdf->WriteHTML("</tr>");
			
			$mpdf->WriteHTML("<tr>");
				$mpdf->WriteHTML("<td><b>Contract + Excess Amount:</b></td><td colspan='3'>{$print_data['contract_excess_amt']}</td>");
				$mpdf->WriteHTML("<td colspan='1' ><b>Extra Item Amount:</b></td><td colspan='3'>{$print_data['extra_item_amt']}</td>");
			$mpdf->WriteHTML("</tr>");
	
			$mpdf->WriteHTML("<tr>");
				
					$mpdf->WriteHTML("<td><b>Mobilization Advance:</b></td><td colspan='3' >{$print_data['mobilization_adv']}</td>");
				$mpdf->WriteHTML("<td colspan='1' ><b>Unmeasured Advance:</b> </td><td colspan='3'>{$print_data['unmeasured_adv']} </td>");
			$mpdf->WriteHTML("</tr>");
	
			$mpdf->WriteHTML("<tr>");
				$mpdf->WriteHTML("<td><b>Release of Deposite/W.H.:</b></td><td colspan='3' >{$print_data['release_deposite']}</td>");
				$mpdf->WriteHTML("<td colspan='1'><b>Others:</b></td><td colspan='3'>{$print_data['other_bill_amt']}</td>");
			$mpdf->WriteHTML("</tr>");
			
			$mpdf->WriteHTML("<tr>");
				$mpdf->WriteHTML("<td><b>R.A. Bill Amount:</b></td><td colspan='3' >{$print_data['total_bill_amt']}</td>");
				
			$mpdf->WriteHTML("</tr>");
			
			// deduction
			$mpdf->WriteHTML("<tr>");
			$mpdf->WriteHTML("<td colspan='8'><b><u>DEDUCTIONS</u></b></td>");
			$mpdf->WriteHTML("</tr>");
			
			$mpdf->WriteHTML("<tr>");
				$mpdf->WriteHTML("<td><b>Security Deposite(S.D):</b></td><td colspan='3'>{$print_data['security_deposite']}</td>");
				$mpdf->WriteHTML("<td colspan='1' ><b>T.D.S:</b></td><td colspan='3'>{$print_data['tds']}</td>");
			$mpdf->WriteHTML("</tr>");
			
			$mpdf->WriteHTML("<tr>");
				$mpdf->WriteHTML("<td><b>Other Deposite:</b></td><td colspan='3'>{$print_data['other_deposits']}</td>");
				$mpdf->WriteHTML("<td colspan='1' ><b>Other:</b></td><td colspan='3'>{$print_data['other_deduction']}</td>");
			$mpdf->WriteHTML("</tr>");
	
			$mpdf->WriteHTML("<tr>");
				
					$mpdf->WriteHTML("<td><b>Labour CESS:</b></td><td colspan='3' >{$print_data['labour_cess']}</td>");
				$mpdf->WriteHTML("<td colspan='1' ><b>VAT:</b> </td><td colspan='3'>{$print_data['vat']} </td>");
			$mpdf->WriteHTML("</tr>");
	
			$mpdf->WriteHTML("<tr>");
				$mpdf->WriteHTML("<td><b>Other Taxes:</b></td><td colspan='3' >{$print_data['other_taxes']}</td>");
				$mpdf->WriteHTML("<td colspan='1'><b>With Held:</b></td><td colspan='3'>{$print_data['with_held']}</td>");
			$mpdf->WriteHTML("</tr>");
			
			$mpdf->WriteHTML("<tr>");
				$mpdf->WriteHTML("<td><b>Deducted Amount:</b></td><td colspan='3' >{$print_data['total_deduction_amt']}</td>");
				
			$mpdf->WriteHTML("</tr>");
			//end deduction
			$mpdf->WriteHTML("<tr>");
			$mpdf->WriteHTML("<td colspan='8'><b><u>AMOUNT TO BE PAID</u></b></td>");
			$mpdf->WriteHTML("</tr>");
			
			$mpdf->WriteHTML("<tr>");
				$mpdf->WriteHTML("<td><b>Net Paid Amount:</b></td><td colspan='3' >{$print_data['total_paid_amt']}</td>");
			$mpdf->WriteHTML("</tr>");
			
			$mpdf->WriteHTML("<tr>");
				$mpdf->WriteHTML("<td><b>Date of Payment:</b></td><td colspan='3' >{$this->ERPfunction->get_date($print_data['date_of_payment'])}</td>");
			$mpdf->WriteHTML("</tr>");
			
			$mpdf->WriteHTML("<tr>");
			$mpdf->WriteHTML("<td colspan='8'><b>Comment: </b>{$print_data['comment']}</td>");
			$mpdf->WriteHTML("</tr>");

			
			$mpdf->WriteHTML("<tr>");
				$mpdf->WriteHTML("<td align='center' colspan='4'><br><br><br>");
						if($print_data['created_by'])
						{
							$mpdf->WriteHTML("{$this->ERPfunction->get_user_name($print_data['created_by'])}");
						}
					
				// $mpdf->WriteHTML("<h3><b> Quantity Varified By </b></h3></td>");
				// $mpdf->WriteHTML("<td align='center' colspan='4'><br><br><br>");
						// if($erp_grn_details['created_by']){
							// $mpdf->WriteHTML("{$this->ERPfunction->get_user_name($erp_grn_details['created_by'])}"); 
						// }
					
				$mpdf->WriteHTML("<h3><b> Prepared By </b></h3></td>");
				$mpdf->WriteHTML("<td align='center' colspan='4'><br><br><br>");
						
							if($print_data['last_edit_by']) 
							{
								$mpdf->WriteHTML("{$this->ERPfunction->get_user_name($print_data['last_edit_by'])}"); 
							}
						
				
				$mpdf->WriteHTML("<h3><b> Last Edited by </b></h3></td>");
			$mpdf->WriteHTML("</tr>");
			// $mpdf->WriteHTML("<tr>");
				// $mpdf->WriteHTML("<td align='center' colspan='2'><br><br><br> (Store In-charge/Weighbridge In-charge)</td>");
				// $mpdf->WriteHTML("<td align='center'  colspan='3'><br><br><br> (Material Manager)</td>");
				// $mpdf->WriteHTML("<td align='center'  colspan='3'><br><br><br> (Construction Manager)</td>");
			// $mpdf->WriteHTML("</tr>");
			
	$mpdf->WriteHTML("</tbody>");
	$mpdf->WriteHTML("</table>");
	
	/* $mpdf->WriteHTML("<br>");
	$mpdf->WriteHTML("<hr/>");
	$mpdf->WriteHTML("<br>");
	
	$mpdf->WriteHTML("<table>");
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td style='height:30px' width=50%>Created By : {$created_by} </td>");
	$mpdf->WriteHTML("<td style='height:30px' width=20%></td>");
	$mpdf->WriteHTML("<td style='height:30px' >Last Edited By : {$last_edit_by} </td>");	
	$mpdf->WriteHTML("</tr>");
	$mpdf->WriteHTML("</table>"); */
		
	$mpdf->WriteHTML('</html>');	
	$mpdf->Output();
	ob_end_flush();
	unset($mpdf);
	
	die;
?>	
               