<?php
	error_reporting(0);
	$created_by = isset($project_data['created_by'])?$this->ERPfunction->get_user_name($project_data['created_by']):'NA';
	$last_edit = isset($project_data['last_edit'])?date("d-m-Y H:i",strtotime($project_data['last_edit'])):'NA';
	$last_edit_by = isset($project_data['last_edit_by'])?$this->ERPfunction->get_user_name($project_data['last_edit_by']):'NA';

	ob_clean();
	header('Content-type: application/pdf');
	header('Content-Disposition: inline; filename="project_list.pdf"');
	header('Content-Transfer-Encoding: binary');
	header('Accept-Ranges: bytes');
	
	/* $mpdf	=	new mPDF('+aCJK'); */
	$mpdf	=	new mPDF('c','A4','','' , 5 , 5 , 0 , 0 , 0 , 0);
	
	$mpdf->WriteHTML('<html>');
	$mpdf->WriteHTML('<head>');
		$mpdf->WriteHTML('<style>
				table{
					font-family: sans-serif;
					font-size : 12px;	
					color : #444;					
					width : 100%;
				}
				td{					
					padding : 8px;
				}
				strong{
					color :#333;
				}
				</style>');
	$mpdf->WriteHTML('</head>');
	$mpdf->WriteHTML('<body>');	
	$mpdf->WriteHTML($this->ERPfunction->viewheader_pdf($project_data['created_date']));
	$mpdf->WriteHTML("<hr/>");
	$mpdf->WriteHTML("<table>");
	$mpdf->WriteHTML("<tr><th align=left>PROJECT INFORMATION</th>");
	$mpdf->WriteHTML("<th align=right>Date : ".date("d-m-Y H:i:s")."</th></tr>");	
	
	$mpdf->WriteHTML("</table>");
	$mpdf->WriteHTML("<hr/>");
	
	$mpdf->WriteHTML("<table>");
	$mpdf->WriteHTML("<tr><th align=left style='text-decoration:underline'>PROJECT DESCRIPTION</th></tr>");
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td style='height:30px' width=50%><b>Project Code</b> : {$project_data['project_code']}</td>");	
	$mpdf->WriteHTML("<td width=50%><b>Project Name</b> : {$project_data['project_name']}</td>");	
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$actual_amount=isset($project_data['actual_amount'])?$project_data['actual_amount']:'0';
	$actual_cmp_date =isset($project_data['actual_cmp_date'])?$project_data['actual_cmp_date']:'0';
	$actual_cmp_date =($actual_cmp_date != '0')?$this->ERPfunction->get_date($project_data['actual_cmp_date']):'0';
	$status = ($actual_cmp_date == 0 || $actual_amount == 0 ) ? "On Going":"Completed";
	
	$mpdf->WriteHTML("<td style='height:30px' width=50%> <b>Project Status</b> : {$status}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");	
	$mpdf->WriteHTML("<td style='height:30px' ><b>Client's Name</b> : {$project_data['client_name']}</td>");
	$mpdf->WriteHTML("<td><b>Project Address</b> : {$project_data['project_address']}</td>");	
	$mpdf->WriteHTML("</tr>");
	
		
	$mpdf->WriteHTML("<tr><th align=left style='text-decoration:underline'><br>ADDRESS</th></tr>");
	
	$mpdf->WriteHTML("<tr>");	
	$mpdf->WriteHTML("<td style='height:30px' ><b>City</b> : {$project_data['city']}</td>");
	$mpdf->WriteHTML("<td><b>District</b> : {$project_data['district']}</td>");	
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");	
	$mpdf->WriteHTML("<td style='height:30px' ><b>State</b> : {$project_data['state']}</td>");
	$mpdf->WriteHTML("<td><b>PIN Code</b> : {$project_data['pincode']}</td>");	
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr><th align=left style='text-decoration:underline'><br>OTHER INFO</th></tr>");
	
	$mpdf->WriteHTML("<tr>");	
	$mpdf->WriteHTML("<td style='height:30px' ><b>Work Description</b> : {$project_data['work_description']}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td style='height:30px' ><b>Contract Start Date</b> : {$this->ERPfunction->get_date($project_data['contract_start_date'])}</td>");	
	$mpdf->WriteHTML("<td style='height:30px' ><b>Contract End Date</b> : {$this->ERPfunction->get_date($project_data['contract_end_date'])}</td>");	
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td style='height:30px' ><b>Contract Amount</b>: {$project_data['contract_amount']}</td>");	
	$mpdf->WriteHTML("<td style='height:30px' ><b>Defect Liability Period</b> : {$project_data['defect_liability_period']}</td>");	
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td style='height:30px' ><b>Project Director</b>: {$this->ERPfunction->get_user_name($project_data['project_director'])}</td>");	
	$mpdf->WriteHTML("<td><b>Construction Manager</b> : {$this->ERPfunction->get_user_name($project_data['conttruction_manager'])}</td>");	
	$mpdf->WriteHTML("</tr>");
		
	// $mpdf->WriteHTML("<tr>");
	// $mpdf->WriteHTML("<td>Project Director: {$project_data['project_director']}</td>");	
	// $mpdf->WriteHTML("<td>Construction Manager : {$project_data['conttruction_manager']}</td>");	
	// $mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td style='height:30px' ><br>As On Date for Following Information : {$this->ERPfunction->get_date($project_data['date_of_information'])}</td>");	
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td style='height:30px' ><b>Excess Amount</b> : {$project_data['excess_amount']}</td>");	
	$mpdf->WriteHTML("<td><b>Extra Item Amount</b> : {$project_data['extra_item_amount']}</td>");	
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td style='height:30px' ><b>Revised Amount</b> : {$project_data['revise_amount']}</td>");	
	$mpdf->WriteHTML("<td><b>Extended Completion</b> : {$this->ERPfunction->get_date($project_data['exten_cmp_date'])}</td>");	
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td style='height:30px' ><b>Ref. Letter No</b> : {$project_data['ref_letter_no']}</td>");	
	$mpdf->WriteHTML("<td><b>Ref. Date</b> : {$this->ERPfunction->get_date($project_data['ref_date'])}</td>");	
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td style='height:30px' ><b>Actual Amount (Rs.)</b> : {$project_data['actual_amount']}</td>");	
	$mpdf->WriteHTML("<td><b>Actual Completion Date</b> : {$this->ERPfunction->get_date($project_data['actual_cmp_date'])}</td>");	
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("</table>");
	$mpdf->WriteHTML("<hr/>");
	$mpdf->WriteHTML("<table>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td color=gray>Created By : {$created_by}</td>");	
	/*$mpdf->WriteHTML("<td color=gray width=100%>Last Edited On : {$last_edit}</td>"); */
	$mpdf->WriteHTML("<td color=gray align=right>Last Edit By : {$last_edit_by}</td>");	
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("</table>");
	
	$mpdf->WriteHTML('</body>');	
	$mpdf->WriteHTML('</html>');	
	$mpdf->Output();
	ob_end_flush();
	unset($mpdf);
	
	die;
?>