<?php
	error_reporting(0);
	$created_by = isset($project_data['created_by'])?$this->ERPfunction->get_user_name($project_data['created_by']):'NA';
	$last_edit = isset($project_data['last_edit'])?date("m-d-Y H:i:s",strtotime($project_data['last_edit'])):'NA';
	$last_edit_by = isset($project_data['last_edit_by'])?$this->ERPfunction->get_user_name($project_data['last_edit_by']):'NA';

	ob_clean();
	header('Content-type: application/pdf');
	header('Content-Disposition: inline; filename="rmc_issue_slip.pdf"');
	header('Content-Transfer-Encoding: binary');
	header('Accept-Ranges: bytes');
	
	/* $mpdf	=	new mPDF('+aCJK'); */
	$mpdf	=	new mPDF('c','A4','','' , 5 , 5 , 10 , 0 , 0 , 0);
	
	$mpdf->WriteHTML('<html>');
	$mpdf->WriteHTML('<head>');
	$mpdf->WriteHTML('</head>');
	$mpdf->WriteHTML('<body>');	
	
	$mpdf->WriteHTML("<table>");
	$mpdf->WriteHTML("<tr><th align=left width=100%>RMC ISSUE SLIP</th>");
	$mpdf->WriteHTML("<th align=right >Date : ".date("d-m-Y H:i:s")."</th></tr>");	
	
	$mpdf->WriteHTML("</table>");
	$mpdf->WriteHTML("<hr/>");
	
	$mpdf->WriteHTML("<table>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td style='height:30px' width=50%>Project Code.: : {$data["project_code"]}</td>");	
	$mpdf->WriteHTML("<td width=50%>Project Name : {$this->ERPfunction->get_projectname($data['project_id'])}</td>");	
	$mpdf->WriteHTML("</tr>");	
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td style='height:30px' width=50%>RMC. I.S. No.: : {$data["isno"]}</td>");	
	$mpdf->WriteHTML("<td width=50%>Date : {$data['rmc_date']->format("d-m-Y")}</td>");	
	$mpdf->WriteHTML("</tr>");	
	
	$mpdf->WriteHTML("<tr>");	
	$mpdf->WriteHTML("<td style='height:30px' >Asset Name : {$this->ERPfunction->get_asset_name($data['asset_name'])}</td>");
	$mpdf->WriteHTML("<td>Asset ID : {$data['asset_code']}</td>");	
	$mpdf->WriteHTML("</tr>");
		
	$mpdf->WriteHTML("<tr>");	
	$mpdf->WriteHTML("<td style='height:30px' >Agency Name : {$this->ERPfunction->get_agency_name($data['agency_name'])}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");	
	$mpdf->WriteHTML("<td style='height:30px' >Asset Name : {$this->ERPfunction->get_asset_name($data['asset_name'])}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr><th align=left ><br></th></tr>");
	
	$mpdf->WriteHTML("<tr>");	
	$mpdf->WriteHTML("<td style='height:30px' >Operator's Name : {$data['operator_name']}</td>");
	$mpdf->WriteHTML("<td >Order By : {$data['order_by']}</td>");
	$mpdf->WriteHTML("</tr>");
		
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td style='height:30px' >Usage : {$data['rmc_usage']}</td>");	
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td style='height:30px' >Concrete Grade : {$data['concrete_grade']}</td>");	
	$mpdf->WriteHTML("<td style='height:30px' >Quantity Ordered : {$data['quantity_ordered']}</td>");	
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("</table>");
	$mpdf->WriteHTML("<br>");
	$mpdf->WriteHTML("<hr/>");
	$mpdf->WriteHTML("<br>");
	
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