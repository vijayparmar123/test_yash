<?php
	error_reporting(0);
	$created_by = isset($user_data['created_by'])?$this->ERPfunction->get_user_name($user_data['created_by']):'NA';
	$last_edit_by = isset($user_data['last_edit_by'])?$this->ERPfunction->get_user_name($user_data['last_edit_by']):'NA';

	ob_clean();
	header('Content-type: application/pdf');
	header('Content-Disposition: inline; filename="project_list.pdf"');
	header('Content-Transfer-Encoding: binary');
	header('Accept-Ranges: bytes');
	
	/* $mpdf	=	new mPDF('+aCJK'); */
	$mpdf	=	new mPDF('c','A4','','' , 5 , 5 , 10 , 0 , 0 , 0);
	
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
					border-top :1 solid;
					border-right :1 solid;
					border-bottom :1 solid;
					border-left :0;
					border-color : #dedede;
					width:100%;
					padding:10px;
				}
				strong{
					color :#333;
				}		
				</style>');
	$mpdf->WriteHTML('</head>');
	$mpdf->WriteHTML('<body>');	
	$mpdf->WriteHTML($this->ERPfunction->viewheader_pdf($user_data['created_date']));
	$mpdf->WriteHTML("<table>");
	$mpdf->WriteHTML("<tr><th align=left width=100%>AGENCY INFORMATION</th>");
	$mpdf->WriteHTML("<th align=right >Date : ".date("d-m-Y H:i")."</th></tr>");	
	
	$mpdf->WriteHTML("</table>");
	$mpdf->WriteHTML("<hr/>");
	$mpdf->WriteHTML("<table>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td width=50% style='height:40px'>Agency ID : {$agency_id}</td>");
	$mpdf->WriteHTML("</tr>");	
	
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td width=50% style='height:40px'>Agency Name : {$user_data['agency_name']}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");	
	$mpdf->WriteHTML("<td style='height:40px'>Agency's Billing Address  : {$user_data['agency_billing_address']}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");	
	$mpdf->WriteHTML("<td style='height:40px'>Contact No : {$user_data['contact_no']}</td>");	
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");	
	$mpdf->WriteHTML("<td style='height:40px'>Email : {$user_data['email_id']}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");	
	$mpdf->WriteHTML("<td style='height:40px'>PAN Card No : {$user_data['pancard_no']}</td>");
	//$mpdf->WriteHTML("<td>VAT/TIN No : {$user_data['vat_tin_no']}</td>");
	$mpdf->WriteHTML("</tr>");
	
	// $mpdf->WriteHTML("<tr>");
	// $mpdf->WriteHTML("<td style='height:40px'>Service Tax No : {$user_data['service_tax_no']}</td>");	
	// $mpdf->WriteHTML("<td>CST No : {$user_data['cst_no']}</td>");	
	// $mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td style='height:40px'>GST No : {$user_data['gst_no']}</td>");	
	$mpdf->WriteHTML("<td>A/C No : {$user_data['ac_no']}</td>");	
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td style='height:40px'>Bank : {$user_data['bank_name']}</td>");	
	$mpdf->WriteHTML("<td>IFSC Code : {$user_data['ifsc_code']}</td>");	
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td style='height:40px'>Branch Name : {$user_data['branch_name']}</td>");	
	$mpdf->WriteHTML("<td>Transfer Type : {$user_data['transfer_type']}</td>");	
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("</table>");
	$mpdf->WriteHTML("<hr/>");
	$mpdf->WriteHTML("<table>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td color=gray width=100%>Created By : {$created_by}</td>");	
	$mpdf->WriteHTML("<td color=gray>Last Edit By : {$last_edit_by}</td>");	
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("</table>");
	
	
	$mpdf->WriteHTML('</body>');	
	$mpdf->WriteHTML('</html>');	
	$mpdf->Output();
	ob_end_flush();
	unset($mpdf);
	
	die;
?>