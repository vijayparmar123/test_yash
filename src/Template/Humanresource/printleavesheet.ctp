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
	$mpdf->WriteHTML('</head>');
	$mpdf->WriteHTML('<body>');	
	
	$mpdf->WriteHTML("<table>");
	$mpdf->WriteHTML("<tr><th align=left width=100%>LEAVE INFORMATION</th>");
	$mpdf->WriteHTML("<th align=right >Date : ".date("d-m-Y H:i:s")."</th></tr>");	
	
	$mpdf->WriteHTML("</table>");
	$mpdf->WriteHTML("<hr/>");
	
	$mpdf->WriteHTML("<table>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td style='height:30px' width=50%>Employee No : {$data['employee_no']}</td>");		
	$mpdf->WriteHTML("</tr>");	
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td style='height:30px' width=50%>Employed at : {$data['employee_at']}</td>");	
	$mpdf->WriteHTML("</tr>");	
	
	$mpdf->WriteHTML("<tr>");	
	$mpdf->WriteHTML("<td style='height:30px' >Full Name : {$data['full_name']}</td>");
	$mpdf->WriteHTML("</tr>");
		
	$mpdf->WriteHTML("<tr>");	
	$mpdf->WriteHTML("<td style='height:30px' >Designation : {$data['designation']} </td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");	
	$mpdf->WriteHTML("<td style='height:30px' >Month : {$data['month']} </td>");
	$mpdf->WriteHTML("<td style='height:30px' >Year : {$data['year']} </td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");	
	$mpdf->WriteHTML("<td style='height:30px' >Leave excluding Holidays, PL, SL & C : {$data['leave_detail']}</td>");
	$mpdf->WriteHTML("</tr>");
		

	
	
	
	$mpdf->WriteHTML("</table>");
	$mpdf->WriteHTML("<br>");
	$mpdf->WriteHTML("<hr/>");
	$mpdf->WriteHTML("<br>");
	
	$mpdf->WriteHTML("<table>");
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td style='height:30px' width=50%>Created By : {$this->ERPfunction->get_user_name($data['created_by'])} </td>");
	$mpdf->WriteHTML("<td style='height:30px' >Last Edited By : {$this->ERPfunction->get_user_name($data['last_edit_by'])} </td>");	
	$mpdf->WriteHTML("</tr>");
	$mpdf->WriteHTML("</table>");
	
	$mpdf->WriteHTML('</body>');	
	$mpdf->WriteHTML('</html>');	
	$mpdf->Output();
	ob_end_flush();
	unset($mpdf);
	
	die;
?>