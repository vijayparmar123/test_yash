<?php
error_reporting(0);

$created_by = isset($maintenace_data['created_by'])?$this->ERPfunction->get_user_name($maintenace_data['created_by']):'NA';
$approved_by = (isset($maintenace_data['approved_status']) && ($maintenace_data['approved_status'] == 1))?$this->ERPfunction->get_user_name($maintenace_data['approve_by']):'NA';
$maintenance_type = ($maintenace_data['maintenance_type'])?"Corrective / Breakdown":"Preventive / Routine";
$expense_amount = ($maintenace_data['payment_by'] == 1)?"Cash":"Cheque";
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
	$mpdf->WriteHTML($this->ERPfunction->viewheader_pdf($maintenace_data['maintenance_date']));
	$mpdf->WriteHTML("<br>");
	$mpdf->WriteHTML("<table width=100% border=1>");
	$mpdf->WriteHTML("<tbody>");
	$mpdf->WriteHTML("<tr><td colspan='8' align='center' style='border-right:0;'><h2><b>Asset Maintenance</b></h2></td></tr>");
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='8' style='border-right:0;'><b>Project Name: </b>{$this->ERPfunction->get_projectname($maintenace_data['project_id'])}</td>");
	$mpdf->WriteHTML("</tr>");

	$mpdf->WriteHTML("<tr>");
		$mpdf->WriteHTML("<td><b>A.M.O. No.:</b></td><td colspan='2' >{$maintenace_data['amo_no']}</td>");
		$mpdf->WriteHTML("<td colspan='2'><b>Date:</b></td><td colspan='3' style='border-right:0;'>{$this->ERPfunction->get_date($maintenace_data['maintenance_date'])}</td>");
	$mpdf->WriteHTML("</tr>");

	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td><b>Asset Group:</b></td><td colspan='2' >{$this->ERPfunction->get_asset_group_name($maintenace_data['asset_group'])}</td>");
	$mpdf->WriteHTML("<td colspan='2'><b>Asset ID:</b></td><td colspan='3' style='border-right:0;'>{$this->ERPfunction->get_asset_code($maintenace_data['asset_id'])}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='8' style='border-right:0;'><b>Asset Name: </b>{$this->ERPfunction->get_asset_name($maintenace_data['asset_id'])}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td><b>Asset Make:</b></td><td colspan='2' >{$this->ERPfunction->get_asset_make($maintenace_data['asset_id'])}</td>");
	$mpdf->WriteHTML("<td colspan='2'><b>Asset Capacity:</b></td><td colspan='3' style='border-right:0;'>{$this->ERPfunction->get_asset_capacity($maintenace_data['asset_id'])}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td><b>Model No:</b></td><td colspan='2' >{$maintenace_data['model_no']}</td>");
	$mpdf->WriteHTML("<td colspan='2'><b>Identity / Veh. No.:</b></td><td colspan='3' style='border-right:0;'>{$maintenace_data['vehicle_no']}</td>");
	$mpdf->WriteHTML("</tr>");

	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='8' style='border-right:0;'><b>Maintenance Type: </b>{$maintenance_type}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='8' style='border-right:0;'><b>Party's Name: </b>{$maintenace_data['party_name']}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td><b>Amount of Expense:</b></td><td colspan='2' >{$maintenace_data['expense_amount']}</td>");
	$mpdf->WriteHTML("<td colspan='2'><b>Payment:</b></td><td colspan='3' style='border-right:0;'>{$expense_amount}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td><b>Voch. No. / Inw. No.:</b></td><td colspan='2' >{$maintenace_data['voucher_no']}</td>");
	$mpdf->WriteHTML("<td colspan='2'><b>Supervised By:</b></td><td colspan='3' style='border-right:0;'>{$maintenace_data['supervised_by']}</td>");
	$mpdf->WriteHTML("</tr>");
	$mpdf->WriteHTML("</table>");
	$mpdf->WriteHTML("<table width='100%'>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='8' style='border-right:0;'><b><center>Description</center></b></td>");
	$mpdf->WriteHTML("</tr>");
	$created_date=date("Y-m-d",strtotime($maintenace_data['created_date']));
	if($created_date < "2020-02-07"){
		$mpdf->WriteHTML("<tr>");
		$mpdf->WriteHTML("<td><b>Material / Spares/ Tools / Service / Others</b></td>");
		$mpdf->WriteHTML("<td colspan='3'><b>Reason</b></td>");
		$mpdf->WriteHTML("<td colspan='4' style='border-right:0;'><b>Amount</b></td>");
		$mpdf->WriteHTML("</tr>");
		
		$mpdf->WriteHTML("<tr>");
		$mpdf->WriteHTML("<td><pre>{$maintenace_data['desc_maintenance']}</pre></td>");
		$mpdf->WriteHTML("<td colspan='3'><pre>{$maintenace_data['reason']}</pre></td>");
		$mpdf->WriteHTML("<td colspan='4' style='border-right:0;'><pre>{$maintenace_data['desc_amount']}</pre></td>");
		$mpdf->WriteHTML("</tr>");
	}else{
		$mpdf->WriteHTML("<tr>");
		$mpdf->WriteHTML("<td><b>Material / Spares/ Tools/ Service / Others</b></td>");
		$mpdf->WriteHTML("<td><b>Quantity</b></td>");
		$mpdf->WriteHTML("<td><b>Unit</b></td>");
		$mpdf->WriteHTML("<td><b>Rate</b></td>");
		$mpdf->WriteHTML("<td><b>GST(%)</b></td>");
		$mpdf->WriteHTML("<td colspan='3' style='border-right:0;'><b>Amount</b></td>");
		$mpdf->WriteHTML("</tr>");
		$total_amount = 0;
		foreach($maintenace_details as $retrive)
		{
			$total_amount += $retrive['amount'];
			$mpdf->WriteHTML("<tr>");
			$mpdf->WriteHTML("<td>{$retrive['material']}</td>");
			$mpdf->WriteHTML("<td>{$retrive['quantity']}</td>");
			$mpdf->WriteHTML("<td>{$retrive['unit']}</td>");
			$mpdf->WriteHTML("<td>{$retrive['rate']}</td>");
			$mpdf->WriteHTML("<td>{$retrive['gst']}</td>");
			$mpdf->WriteHTML("<td colspan='3' style='border-right:0;'>{$retrive['amount']}</td>");
			$mpdf->WriteHTML("</tr>");
		}
		
		$mpdf->WriteHTML("<tr>");
		$mpdf->WriteHTML("<td></td>");
		$mpdf->WriteHTML("<td></td>");
		$mpdf->WriteHTML("<td></td>");
		$mpdf->WriteHTML("<td></td>");
		$mpdf->WriteHTML("<td><b>Total</b></td>");
		$mpdf->WriteHTML("<td colspan='3' style='border-right:0;'><b>{$total_amount}</b></td>");
		$mpdf->WriteHTML("</tr>");
			
		$mpdf->WriteHTML("<tr>");
		$mpdf->WriteHTML("<td colspan='8' style='border-right:0;'><b>Reason: </b>{$maintenace_data['reason']}</td>");
		$mpdf->WriteHTML("</tr>");
	}
	$mpdf->WriteHTML("</table>");
	$mpdf->WriteHTML("<table width='100%'>");
	$mpdf->WriteHTML("<tr>");
		$mpdf->WriteHTML("<td align='center' colspan='3'><br><br><br>");
		$mpdf->WriteHTML("{$created_by}");
		$mpdf->WriteHTML("<h3><b> Made By </b></h3></td>");
		$mpdf->WriteHTML("<td align='center' colspan='5' style='border-right:0;'><br><br><br>");
		$mpdf->WriteHTML("{$approved_by}"); 
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
               