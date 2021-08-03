.<?php
	error_reporting(0);

// $created_by = isset($erp_grn_details['created_by'])?$this->ERPfunction->get_user_name($erp_grn_details['created_by']):'NA';
// $last_edit = isset($erp_grn_details['last_edit'])?date("m-d-Y H:i:s",strtotime($erp_grn_details['last_edit'])):'NA';
// $last_edit_by = isset($erp_grn_details['last_edit_by'])?$this->ERPfunction->get_user_name($erp_grn_details['last_edit_by']):'NA';


	ob_clean();
	header('Content-type: application/pdf');
	header('Content-Disposition: inline; filename="PRICE VARIATION INFORMATION.pdf"');
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
					border-top :0;
					border-right :1 solid;
					border-bottom :1 solid;
					border-left :0;
					border-color : #dedede;
					padding:8px;
				}
				strong{
					color :#333;
				}		
				</style>');
	$mpdf->WriteHTML('</head>');
	$mpdf->WriteHTML('<body>');	
	// $mpdf->WriteHTML("<img style='margin-top:-30px' height=30% border='0' src='".WWW_ROOT ."img/logo/header.jpg'/>");
	$mpdf->WriteHTML($this->ERPfunction->viewheader_pdf($variation['upto_date']));
	$mpdf->WriteHTML("<br>");
	$mpdf->WriteHTML("<table width=100% border=1>");
	$mpdf->WriteHTML("<tbody>");
		// $mpdf->WriteHTML("<tr><td colspan='8' align='center'><h1><b>YashNand Engineers & Contractors</b></h1></td></tr>");
		$mpdf->WriteHTML("<tr><td colspan='8' align='center'><h2><b>PRICE VARIATION INFORMATION</b></h2></td></tr>");
		$mpdf->WriteHTML("<tr>");
			// $mpdf->WriteHTML("<td colspan='2'><b>Project Code : </b>{$this->ERPfunction->get_projectcode($erp_grn_details['project_id'])}</td>");
			$mpdf->WriteHTML("<td colspan='8'><b>Project Name: </b>{$this->ERPfunction->get_projectname($variation['project_id'])}</td>");
			$mpdf->WriteHTML("</tr>");
	
			$mpdf->WriteHTML("<tr>");
				$mpdf->WriteHTML("<td><b>Price Variation Bill No:</b></td><td colspan='3'>{$variation['bill_no']}</td>");
				$mpdf->WriteHTML("<td><b>Upto Date:</b></td><td colspan='3'>{$this->ERPfunction->get_date($variation['upto_date'])}</td>");
				
			$mpdf->WriteHTML("</tr>");
	
			// $mpdf->WriteHTML("<tr>");
				// $mpdf->WriteHTML("<td><b>Amount of this Bill:</b></td><td colspan='3'>{$variation['bill_amt']}</td>");
				// $mpdf->WriteHTML("<td colspan='1' ><b>Total Deductions Amount:</b></td><td colspan='3'>{$variation['total_deduction_amt']}</td>");
			// $mpdf->WriteHTML("</tr>");
	
			$mpdf->WriteHTML("<tr>");
				
					$mpdf->WriteHTML("<td><b>Amount to be paid:</b></td>
					<td colspan='2' >
					{$variation['paid_amt']}
					</td>");
						
				$mpdf->WriteHTML("<td colspan='2' ><b>Date of Payment:</b> </td><td colspan='3'>{$this->ERPfunction->get_date($variation['payment_date'])} </td>");
			$mpdf->WriteHTML("</tr>");
	
			
			
			$mpdf->WriteHTML("<tr>");
			// $mpdf->WriteHTML("<td colspan='2'><b>Project Code : </b>{$this->ERPfunction->get_projectcode($erp_grn_details['project_id'])}</td>");
			$mpdf->WriteHTML("<td colspan='8'><b>Comment Box: </b>{$variation['comment']}</td>");
			$mpdf->WriteHTML("</tr>");
			
			$mpdf->WriteHTML("<tr>");
				$mpdf->WriteHTML("<td align='center' colspan='4'><br><br><br>");
						if($variation['created_by'])
						{
							$mpdf->WriteHTML("{$this->ERPfunction->get_user_name($variation['created_by'])}");
						}
					
				// $mpdf->WriteHTML("<h3><b> Quantity Varified By </b></h3></td>");
				// $mpdf->WriteHTML("<td align='center' colspan='4'><br><br><br>");
						// if($erp_grn_details['created_by']){
							// $mpdf->WriteHTML("{$this->ERPfunction->get_user_name($erp_grn_details['created_by'])}"); 
						// }
					
				$mpdf->WriteHTML("<h3><b> Prepared By </b></h3></td>");
				$mpdf->WriteHTML("<td align='center' colspan='4'><br><br><br>");
						
							if($variation['last_edit_by']) 
							{
								$mpdf->WriteHTML("{$this->ERPfunction->get_user_name($variation['last_edit_by'])}"); 
							}
						
				
				$mpdf->WriteHTML("<h3><b> Last Edited By </b></h3></td>");
			$mpdf->WriteHTML("</tr>");
			
			
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
               