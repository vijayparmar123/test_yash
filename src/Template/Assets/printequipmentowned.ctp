<?php
error_reporting(0);

$created_by = isset($record['crated_by'])?$this->ERPfunction->get_user_name($record['crated_by']):'NA';
if($record['working_status'] == "working")
{
	$working_status = "Working";
}elseif($record['working_status'] == "breakdown"){
	$working_status = "Break Down";
}else{
	$working_status = "Idle";
}
$expense_amount = ($record['payment_by'] == 1)?"Cash":"Cheque";
	ob_clean();
	header('Content-type: application/pdf');
	header('Content-Disposition: inline; filename="assetMaintenance.pdf"');
	header('Content-Transfer-Encoding: binary');
	header('Accept-Ranges: bytes');
	
	$mpdf	=	new mPDF('c','A4','','' , 5 , 5 , 0 , 0 , 0 , 0);
	
	
	$mpdf->WriteHTML('<html>');
	$mpdf->WriteHTML('<head>');
	$mpdf->WriteHTML('<style>
			table{
					font-family: sans-serif;
					font-size : 12px;	
					color : #444;
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
					padding:8px 0 0 8px;
				}
				strong{
					color :#333;
				}
				pre{
					background-color:white;
					border:none;
					font-family: "Helvetica Neue", "Helvetica", Arial, sans-serif";
					font-size: 14px;
				}
				</style>');
	$mpdf->WriteHTML('</head>');
	$mpdf->WriteHTML('<body>');	
	$mpdf->WriteHTML($this->ERPfunction->viewheader_pdf($record['date']));
	$mpdf->WriteHTML("<br>");
	$mpdf->WriteHTML("<table width=100% border=1>");
	$mpdf->WriteHTML("<tbody>");
	$mpdf->WriteHTML("<tr><td colspan='8' align='center' style='border-right:0;'><h2><b>Equipment Log - Owned</b></h2></td></tr>");
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='8' style='border-right:0;'><b>Project Name: </b>{$this->ERPfunction->get_projectname($record['project_id'])}</td>");
	$mpdf->WriteHTML("</tr>");

	$mpdf->WriteHTML("<tr>");
		$mpdf->WriteHTML("<td><b>E.L No.:</b></td><td colspan='2' >{$record['el_no']}</td>");
		$mpdf->WriteHTML("<td colspan='2'><b>Date:</b></td><td colspan='3' style='border-right:0;'>{$this->ERPfunction->get_date($record['date'])}</td>");
	$mpdf->WriteHTML("</tr>");

	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td><b>Asset Group:</b></td><td colspan='2' >{$this->ERPfunction->get_asset_group_name($record["asset_group_id"])}</td>");
	$mpdf->WriteHTML("<td colspan='2'><b>Asset ID:</b></td><td colspan='3' style='border-right:0;'>{$record['asset_code']}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='8' style='border-right:0;'><b>Asset Name: </b>{$this->ERPfunction->get_asset_name($record['asset_id'])}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td><b>Asset Make:</b></td><td colspan='2' >{$record['asset_make']}</td>");
	$mpdf->WriteHTML("<td colspan='2'><b>Asset Capacity:</b></td><td colspan='3' style='border-right:0;'>{$record['asset_capacity']}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td><b>Model No:</b></td><td colspan='2' >{$record['asset_model']}</td>");
	$mpdf->WriteHTML("<td colspan='2'><b>Identity / Veh. No.:</b></td><td colspan='3' style='border-right:0;'>{$record['asset_identity']}</td>");
	$mpdf->WriteHTML("</tr>");

	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='8' style='border-right:0;'><b>Working Status:</b>{$working_status}</td>");
	$mpdf->WriteHTML("</tr>");
		
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td><b>Duty Time(hr.):</b></td><td colspan='2' >{$record['duty_time']}</td>");
	$mpdf->WriteHTML("<td colspan='2'><b>Breakdown Time(hr.):</b></td><td colspan='3' style='border-right:0;'>{$record['breakdown_time']}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td><b>Start (km):</b></td><td colspan='2' >{$record['start_km']}</td>");
	$mpdf->WriteHTML("<td colspan='2'><b>Start (hr):</b></td><td colspan='3' style='border-right:0;'>{$record['start_hr']}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td><b>Stop (km):</b></td><td colspan='2' >{$record['stop_km']}</td>");
	$mpdf->WriteHTML("<td colspan='2'><b>Stop (hr):</b></td><td colspan='3' style='border-right:0;'>{$record['stop_hr']}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td><b>Usage (km):</b></td><td colspan='2' >{$record['usage_km']}</td>");
	$mpdf->WriteHTML("<td colspan='2'><b>Usage (hr):</b></td><td colspan='3' style='border-right:0;'>{$record['usage_hr']}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='8' style='border-right:0;'><b>Driver Name: </b>{$record['driver_name']}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='8' style='border-right:0;'><b>Details of Usage: </b>{$record['usage_detail']}</td>");
	$mpdf->WriteHTML("</tr>");
		
	$mpdf->WriteHTML("<tr>");
		$mpdf->WriteHTML("<td align='center' colspan='3'><br><br><br>");
		$mpdf->WriteHTML("{$created_by}");
		$mpdf->WriteHTML("<h3><b> Made By </b></h3></td>");
		$mpdf->WriteHTML("<td align='center' colspan='5' style='border-right:0;'><br><br><br>");
		$mpdf->WriteHTML("{$created_by}"); 
		$mpdf->WriteHTML("<h3><b> Approved By </b></h3></td>");
	$mpdf->WriteHTML("</tr>");
			
	$mpdf->WriteHTML("</tbody>");
	$mpdf->WriteHTML("</table>");
	
	$mpdf->WriteHTML('</html>');	
	$mpdf->Output();
	ob_end_flush();
	unset($mpdf);
	
	die;
?>	
               