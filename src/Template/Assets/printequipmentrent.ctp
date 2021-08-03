<?php
error_reporting(0);

$created_by = isset($data['created_by'])?$this->ERPfunction->get_user_name($data['created_by']):'NA';
$expense_amount = ($data['payment_by'] == 1)?"Cash":"Cheque";
	ob_clean();
	header('Content-type: application/pdf');
	header('Content-Disposition: inline; filename="equipmentLogRent.pdf"');
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
	$mpdf->WriteHTML($this->ERPfunction->viewheader_pdf($data['el_date']));
	$mpdf->WriteHTML("<br>");
	$mpdf->WriteHTML("<table width=100% border=1>");
	$mpdf->WriteHTML("<tbody>");
	$mpdf->WriteHTML("<tr><td colspan='8' align='center' style='border-right:0;'><h2><b>Equipment Log - Rent</b></h2></td></tr>");
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='8' style='border-right:0;'><b>Project Name: </b>{$this->ERPfunction->get_projectname($data['project_id'])}</td>");
	$mpdf->WriteHTML("</tr>");

	$mpdf->WriteHTML("<tr>");
		$mpdf->WriteHTML("<td><b>E.L No.:</b></td><td colspan='2' >{$data['elno']}</td>");
		$mpdf->WriteHTML("<td colspan='2'><b>Date:</b></td><td colspan='3' style='border-right:0;'>{$this->ERPfunction->get_date($data['el_date'])}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='8' style='border-right:0;'><b>Asset Name: </b>{$this->ERPfunction->get_asset_name($data['asset_name'])}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td><b>Driver Name:</b></td><td colspan='2' >{$data['driver_name']}</td>");
	$mpdf->WriteHTML("<td colspan='2'><b>Vehicle No:</b></td><td colspan='3' style='border-right:0;'>{$data['vehicle_no']}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td><b>Usage:</b></td><td colspan='2' >{$data['el_usage']}</td>");
	$mpdf->WriteHTML("<td colspan='2'><b>Unit of Usage:</b></td><td colspan='3' style='border-right:0;'>{$data['unit_usage']}</td>");
	$mpdf->WriteHTML("</tr>");

	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='8' style='border-right:0;'><b>Maintenance Type: </b>{$data['usage_detail']}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
		$mpdf->WriteHTML("<td align='center' colspan='3'><br><br><br>");
		$mpdf->WriteHTML("{$created_by}");
		$mpdf->WriteHTML("<h3><b> Made By </b></h3></td>");
		$mpdf->WriteHTML("<td align='center' colspan='5' style='border-right:0;'><br><br><br>");
		$mpdf->WriteHTML("{$data['approved_by']}"); 
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
               