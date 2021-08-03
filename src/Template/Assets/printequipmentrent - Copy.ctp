<?php
	error_reporting(0);
	$created_by = isset($project_data['created_by'])?$this->ERPfunction->get_user_name($project_data['created_by']):'NA';
	$last_edit = isset($project_data['last_edit'])?date("m-d-Y H:i:s",strtotime($project_data['last_edit'])):'NA';
	$last_edit_by = isset($project_data['last_edit_by'])?$this->ERPfunction->get_user_name($project_data['last_edit_by']):'NA';

	ob_clean();
	header('Content-type: application/pdf');
	header('Content-Disposition: inline; filename="project_list.pdf"');
	header('Content-Transfer-Encoding: binary');
	header('Accept-Ranges: bytes');
	
	/* $mpdf	=	new mPDF('+aCJK'); */
	$mpdf	=	new mPDF('c','A4','','' , 5 , 5 , 10 , 0 , 0 , 0);
	
	$mpdf->WriteHTML('<html>');
	$mpdf->WriteHTML('<head>');
	$mpdf->WriteHTML('</head>');
	$mpdf->WriteHTML('<body>');	
	
	$mpdf->WriteHTML("<table>");
	$mpdf->WriteHTML("<tr><th align=left width=100%>Equipment Log</th>");
	$mpdf->WriteHTML("<th align=right >Date : ".date("d-m-Y H:i:s")."</th></tr>");	
	
	$mpdf->WriteHTML("</table>");
	$mpdf->WriteHTML("<hr/>");
	
	$mpdf->WriteHTML("<table>");
	/*$mpdf->WriteHTML("<tr><th align=left style='text-decoration:underline'>PROJECT DESCRIPTION</th></tr>");*/
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td style='height:30px' width=50%>Project Code : {$data["project_code"]}</td>");	
	$mpdf->WriteHTML("<td width=50%>Project Name : {$this->ERPfunction->get_projectname_by_code($data['project_code'])}</td>");	
	$mpdf->WriteHTML("</tr>");	
	
	$mpdf->WriteHTML("<tr>");	
	$mpdf->WriteHTML("<td style='height:30px' >E.L. NO : {$data['elno']}</td>");
	$mpdf->WriteHTML("<td>Date : ".date('d-m-Y',strtotime($data['el_date']))."</td>");	
	$mpdf->WriteHTML("</tr>");
	
	// $mpdf->WriteHTML("<tr>");	
	// $mpdf->WriteHTML("<td style='height:30px'> => ".ucfirst($data['ownership'])."</td>");
	// $mpdf->WriteHTML("</tr>");
	
		$mpdf->WriteHTML("<tr>");	
	$mpdf->WriteHTML("<td style='height:30px' >Asset Name : {$this->ERPfunction->get_asset_name($data['asset_name'])}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr><th align=left ><br></th></tr>");
	
	$mpdf->WriteHTML("<tr>");	
	$mpdf->WriteHTML("<td style='height:30px' >Driver Name : {$data['driver_name']}</td>");
	$mpdf->WriteHTML("<td >Vehicle No : {$data['vehicle_no']}</td>");
	$mpdf->WriteHTML("</tr>");
		
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td style='height:30px' >Usage : {$data['el_usage']}</td>");	
	$mpdf->WriteHTML("<td style='height:30px' >Unit of Usage : ".ucfirst($data['unit_usage']));	
	$mpdf->WriteHTML("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; Approved By : ".ucfirst($data['approved_by'])."</td>");	
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td style='height:30px' >Details of Usage: {$data['usage_detail']}</td>");	
	$mpdf->WriteHTML("</tr>");
	
	
	$mpdf->WriteHTML("</table>");
	
	$mpdf->WriteHTML('</body>');	
	$mpdf->WriteHTML('</html>');	
	$mpdf->Output();
	ob_end_flush();
	unset($mpdf);
	
	die;
?>