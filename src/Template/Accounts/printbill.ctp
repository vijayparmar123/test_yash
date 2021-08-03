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
	$mpdf->WriteHTML("<tr><th align=left width=100%>BILL INFORMATION</th>");
	$mpdf->WriteHTML("<th align=right >Date : ".date("d-m-Y H:i:s")."</th></tr>");	
	
	$mpdf->WriteHTML("</table>");
	$mpdf->WriteHTML("<hr/>");
	
	$mpdf->WriteHTML("<table>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td style='height:30px' width=50%>Project Code : {$data['project_code']}</td>");	
	$mpdf->WriteHTML("<td width=50%>Project Name : {$this->ERPFunction->get_projectname($data['project_id'])}</td>");	
	$mpdf->WriteHTML("</tr>");	
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td style='height:30px' width=50%>Bill Inward No. : {$data['inward_bill_no']}</td>");
	$mpdf->WriteHTML("<td>Date : {$data['date']}</td>");	
	$mpdf->WriteHTML("</tr>");	
	
	$mpdf->WriteHTML("<tr>");	
	$mpdf->WriteHTML("<td style='height:30px' >P.O./W.O. No. : {$data['po_no']}</td>");
	$mpdf->WriteHTML("<td>Type Of Bill. : {$data['bill_type']}</td>");
	$mpdf->WriteHTML("</tr>");
		
	$mpdf->WriteHTML("<tr>");	
	$mpdf->WriteHTML("<td style='height:30px' >Party's Name : {$this->ERPFunction->get_vendor_name($data['party_name'])} </td>");
	$mpdf->WriteHTML("<td>Party's ID : {$data['party_id']}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");	
	$mpdf->WriteHTML("<td>Payment Method : {$data['payment_method']} </td>");
	//$mpdf->WriteHTML("<td>Attach Bill : &nbsp;&nbsp;{$this->Html->image($data['attachment_bill'],['width'=>'100px'])} </td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");	
	$mpdf->WriteHTML("<td style='height:30px' >Invoice No : {$data['invoice_no']}</td>");
	//$mpdf->WriteHTML("<td>Attach Gate Pass : &nbsp;&nbsp;{$this->Html->image($data['attachment_pass'],['width'=>'100px'])}</td>");
	$mpdf->WriteHTML("</tr>");
		
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td style='height:30px' >Bill Date : {$data['bill_date']}</td>");
	//$mpdf->WriteHTML("<td>Attach Measurement Sheet : &nbsp;&nbsp;{$this->Html->image($data['attachment_mmt_sheet'],['width'=>'100px'])}</td>");	
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td style='height:30px' >Total Amount : {$data['total_amt']}</td>");		
	$mpdf->WriteHTML("</tr>");
	
	
	
	$mpdf->WriteHTML("</table>");
	$mpdf->WriteHTML("<br>");
		/*$mpdf->WriteHTML("<hr/>");
	$mpdf->WriteHTML("<br>");

	$mpdf->WriteHTML("<table>");
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td>Attach Bill : </td><td>{$this->Html->image($data['attachment_bill'],['width'=>'100px',height=>'100px'])} </td>");
	$mpdf->WriteHTML("</tr><br><br>");
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td>Attach Gate Pass : </td><td>{$this->Html->image($data['attachment_pass'],['width'=>'100px',height=>'100px'])}</td>");
	$mpdf->WriteHTML("</tr><br><br>");
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td>Attach Measurement Sheet : </td><td>{$this->Html->image($data['attachment_mmt_sheet'],['width'=>'100px',height=>'100px'])}</td>");	
	$mpdf->WriteHTML("</tr><br><br>");
	
	$mpdf->WriteHTML("</table>"); */
	
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