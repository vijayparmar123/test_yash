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
	// $mpdf->WriteHTML($this->ERPfunction->viewheader_pdf($data['created_date']));
	$mpdf->WriteHTML($this->ERPfunction->viewheader_pdf());
	$mpdf->WriteHTML("<table>");
	$mpdf->WriteHTML("<tr><th align=left width=100%>VENDOR INFORMATION</th>");
	$mpdf->WriteHTML("<th align=right >Date : ".date("d-m-Y H:i:s")."</th></tr>");	
	
	$mpdf->WriteHTML("</table>");
	$mpdf->WriteHTML("<hr/>");
	
	$mpdf->WriteHTML("<table>");
	
	$mpdf->WriteHTML("<tr>");
	/* $mpdf->WriteHTML("<td style='height:30px' width=50%>Vendor Group : {$this->ERPfunction->get_vendor_group_name($data['vendor_group'])}</td>");	 */
	$mpdf->WriteHTML("<td width=50%><b>Vendor ID :</b> {$data['vendor_id']}</td>");	
	$mpdf->WriteHTML("</tr>");	
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td style='height:30px' width=50%><b>Vendor Name :</b> {$data['vendor_name']}</td>");	
	$mpdf->WriteHTML("</tr>");	
	
	$mpdf->WriteHTML("<tr>");	
	$mpdf->WriteHTML("<td style='height:30px' ><b>Vendor's Billing Address :</b> {$data['vendor_billing_address']}</td>");
	$mpdf->WriteHTML("</tr>");
		
	$mpdf->WriteHTML("<tr>");	
	$mpdf->WriteHTML("<td style='height:30px' ><b>Contact No. 1 :</b> {$data['contact_no1']} </td>");
	$mpdf->WriteHTML("<td><b>Contact No. 2 :</b> {$data['contact_no2']}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");	
	$mpdf->WriteHTML("<td style='height:30px' ><b>Email ID :</b> {$data['email_id']} </td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");	
	$mpdf->WriteHTML("<td style='height:30px' ><b>PAN Card No :</b> {$data['pancard_no']}</td>");
	$mpdf->WriteHTML("<td style='height:30px' ><b>GST No :</b> {$data['gst_no']}</td>");
	//$mpdf->WriteHTML("<td ><b>VAT/TIN No :</b> {$data['vat_tin_no']}</td>");
	$mpdf->WriteHTML("</tr>");
		
	// $mpdf->WriteHTML("<tr>");
	// $mpdf->WriteHTML("<td style='height:30px' ><b>Service Tax No :</b> {$data['service_tax_no']}</td>");
	// $mpdf->WriteHTML("<td style='height:30px' ><b>CST No :</b> {$data['cst_no']}</td>");	
	// $mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td style='height:30px' ><b>A/C No :</b> {$data['ac_no']}</td>");
	$mpdf->WriteHTML("<td style='height:30px' ><b>Bank :</b> {$data['bank_name']}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td style='height:30px' ><b>IFSC Code :</b> {$data['ifsc_code']}</td>");	
	$mpdf->WriteHTML("</tr>");

	/* $mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td>Image : </td><td>{$this->Html->image($this->ERPfunction->get_vendor_image($data['user_id']),['width'=>'100px','height'=>'100px'])}</td>");		
	$mpdf->WriteHTML("</tr>"); */
	
	
	
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
	
	$mpdf->WriteHTML("<table width=100% border=1>");
	
	if(!empty($data["quantity"]))
	{
		$driver_name = json_decode($data["driver_name"]);
		$tmo = json_decode($data["tmno"]);
		$time_in = json_decode($data["time_in"]);
		$time_out = json_decode($data["time_out"]);
		$quantity = json_decode($data["quantity"]);
		$received_by = json_decode($data["received_by"]);
		$challan = json_decode($data["challan"]);								
		$size = count($tmo);
		$mpdf->WriteHTML("<tr>");
		$mpdf->WriteHTML("<th>TM's No</th><th>Driver's Name</th><th>Time In</th><th>Time Out</th><th>Quantity<br>(In Cum)</th><th>Received By</th>");	
		$mpdf->WriteHTML("</tr>");
		
		for($i=0;$i<$size;$i++)
		{
			$mpdf->WriteHTML("<tr>");
				$mpdf->WriteHTML("<td align=center>{$tmo[$i]}</td>");
				$mpdf->WriteHTML("<td align=center>{$driver_name[$i]}</td>");
				$mpdf->WriteHTML("<td align=center>{$time_in[$i]}</td>");
				$mpdf->WriteHTML("<td align=center>{$time_out[$i]}</td>");
				$mpdf->WriteHTML("<td align=center>{$quantity[$i]}</td>");
				$mpdf->WriteHTML("<td align=center>{$received_by[$i]}</td>");			
			$mpdf->WriteHTML("</tr>");
		}
	}
	
	$mpdf->WriteHTML("</table>");
	
	$mpdf->WriteHTML('</body>');	
	$mpdf->WriteHTML('</html>');	
	$mpdf->Output();
	ob_end_flush();
	unset($mpdf);
	
	die;
?>