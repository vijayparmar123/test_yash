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
	$mpdf	=	new mPDF('c','A4','','' , 5 , 5 , 10 , 0 , 0 , 0);
	
	$mpdf->WriteHTML('<html>');
	$mpdf->WriteHTML('<head>');
	$mpdf->WriteHTML($this->ERPfunction->viewheader_pdf());
	$mpdf->WriteHTML('</head>');
	$mpdf->WriteHTML('<body>');	
	
	$mpdf->WriteHTML("<table>");
	$mpdf->WriteHTML("<tr><th align=left width=100%>CANDIDATE INFORMATION</th>");
	$mpdf->WriteHTML("<th align=right >Date : ".date("d-m-Y")."</th></tr>");	
	
	$mpdf->WriteHTML("</table>");
	$mpdf->WriteHTML("<hr/>");
	
	$mpdf->WriteHTML("<table>");
	$mpdf->WriteHTML("<tr>");	
	$mpdf->WriteHTML("<td style='text-align:center;width:100%;'>{$this->Html->image($this->ERPfunction->get_employee_image($data['user_id']),['width'=>'100px','height'=>'100px'])}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td style='height:30px;padding-right:30px;' width=40%>Employee No. : {$id}</td>");	
		
	$mpdf->WriteHTML("<td style='height:30px;padding-right:30px;' width=40%> Candudate Id : {$data['user_identy_id']}</td>");	
	$mpdf->WriteHTML("</tr>");
	$mpdf->WriteHTML("<tr>");
	
	$mpdf->WriteHTML("</tr>");
	$mpdf->WriteHTML("</table>");
	
	$mpdf->WriteHTML("<hr>");
	
	$mpdf->WriteHTML("<table>");
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td style='font-size:16px;text-transform:uppercase;font-weight:bold;'>Personal Information</td>");
	$mpdf->WriteHTML("</tr>");
	$mpdf->WriteHTML("</table>");
	
	$mpdf->WriteHTML("<table>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td style='height:30px' width=40% >First Name : {$data['first_name']}</td>");
	$mpdf->WriteHTML("<td width=40% >Middle Name : {$data['middle_name']}</td>");
	$mpdf->WriteHTML("<td>Last Name : {$data['last_name']}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td style='height:30px' width=40%>Date Of Birth : {$data['date_of_birth']}</td>");
	$mpdf->WriteHTML("<td width=40%>Education : {$data['education']}</td>");
	$mpdf->WriteHTML("<td width=40%>Year Of Passing : {$data['year_of_passing']}</td>");
	$mpdf->WriteHTML("</tr>");
	$as_date = date('d-m-Y',strtotime($data['as_on_date']));
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td style='height:30px'>Gender : {$data['gender']}</td>");
	$mpdf->WriteHTML("<td>Marital Status. : {$data['marital_status']}</td>");
	$mpdf->WriteHTML("<td>Passport Number. : {$data['passport_no']}");
	// $mpdf->WriteHTML("<td>Experience : {$data['experience']}</td>");
	// $mpdf->WriteHTML("<td>As on Date : {$as_date}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td style='height:30px' width=50%>PAN Card No. : {$data['pan_card_no']}</td>");
	$mpdf->WriteHTML("<td  width=50%>Driving Lincence No. : {$data['driving_licence_no']}</td>");
	$mpdf->WriteHTML("<td  width=50%>Adhaar Card No. : {$data['adhaar_card_no']}</td>");
	$mpdf->WriteHTML("</tr>");
	
	
	
	
	$mpdf->WriteHTML("</table>");
	
	$mpdf->WriteHTML("<hr>");
	
	$mpdf->WriteHTML("<table>");
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td style='font-size:16px;text-transform:uppercase;font-weight:bold;'>Contact Information</td>");
	$mpdf->WriteHTML("</tr>");
	$mpdf->WriteHTML("</table>");
	
	$mpdf->WriteHTML("<table>");
	$mpdf->WriteHTML("<tr>");	
	$mpdf->WriteHTML("<td style='height:30px' >Mobile No. : {$data['mobile_no']} </td>");
	$mpdf->WriteHTML("<td width=50%>E-mail ID : {$data['email_id']}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");	
	$mpdf->WriteHTML("<td style='height:30px'>Address : {$data['employee_address']} </td>");
	$mpdf->WriteHTML("</tr>");
	$mpdf->WriteHTML("</table>");
	
	$mpdf->WriteHTML("<hr>");
	$mpdf->WriteHTML("<table>");
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td style='font-size:16px;text-transform:uppercase;font-weight:bold;'>Interview Information</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td style='height:30px' width=40% >Interview Date : {$data['interview']}</td>");
	$mpdf->WriteHTML("<td width=40% >Interview By : {$data['interview_by']}</td>");
	$mpdf->WriteHTML("<td>Interview Grade : {$data['inerview_grade']}</td>");
	$mpdf->WriteHTML("</tr>");

	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td>Interview Comment : {$data['interview_comment']} </td>");
	$mpdf->WriteHTML("</tr>");

	$mpdf->WriteHTML("</table>");
	
	$extra_payment = explode(',',$data['extra_payment']);
	$incentive_includes = explode(',',$data['incentive_includes']);
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td style='text-transform:capitalize;'>");
	foreach($extra_payment as $extra_payments)
		{
			$mpdf->WriteHTML("{$extra_payments}");
			$mpdf->WriteHTML("<br>");
		}	
	$mpdf->WriteHTML("</td>");
	$mpdf->WriteHTML("<td style='text-transform:capitalize;'>");
	foreach($incentive_includes as $incentive_include)
	{	
			$mpdf->WriteHTML("{$incentive_include}");
			$mpdf->WriteHTML("<br>");
		
	}
	$mpdf->WriteHTML("</td>");
	$mpdf->WriteHTML("</tr>");
	
	
	// $mpdf->WriteHTML("<tr>");	
	// $mpdf->WriteHTML("<td>Transportation Expense : </td>");
	// $mpdf->WriteHTML("<td>Transportation Expense</td>");
	// $mpdf->WriteHTML("</tr>");
	
	// $mpdf->WriteHTML("<tr>");	
	// $mpdf->WriteHTML("<td>Accomodation</td>");
	// $mpdf->WriteHTML("<td>Accomodation - Self</td>");
	// $mpdf->WriteHTML("</tr>");
	
	// $mpdf->WriteHTML("<tr>");	
	// $mpdf->WriteHTML("<td>Foot Expense</td>");
	// $mpdf->WriteHTML("<td>Accomodation - Company Provided</td>");
	// $mpdf->WriteHTML("</tr>");
	
	// $mpdf->WriteHTML("<tr>");	
	// $mpdf->WriteHTML("<td>Mobile Bills</td>");
	// $mpdf->WriteHTML("<td>Foot Expense</td>");
	// $mpdf->WriteHTML("</tr>");
	
	// $mpdf->WriteHTML("<tr>");	
	// $mpdf->WriteHTML("<td>Perquisites</td>");
	// $mpdf->WriteHTML("<td>Mobile Bills</td>");
	// $mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("</table>");
	
	
	//$mpdf->WriteHTML("<br>");
	$mpdf->WriteHTML("<hr>");
	
	
	$mpdf->WriteHTML('</body>');	
	$mpdf->WriteHTML('</html>');	
	$mpdf->Output();
	ob_end_flush();
	unset($mpdf);
	
	die;
?>