<?php
	error_reporting(0);
	$created_by = isset($project_data['created_by'])?$this->ERPfunction->get_user_name($project_data['created_by']):'NA';
	$last_edit = isset($project_data['last_edit'])?date("m-d-Y H:i:s",strtotime($project_data['last_edit'])):'NA';
	$last_edit_by = isset($project_data['last_edit_by'])?$this->ERPfunction->get_user_name($project_data['last_edit_by']):'NA';

	ob_clean();
	header('Content-type: application/pdf');
	header('Content-Disposition: inline; filename="SST_ALERT.pdf"');
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
				</style>');
	$mpdf->WriteHTML('</head>');
	$mpdf->WriteHTML('<body>');	
	$mpdf->WriteHTML($this->ERPfunction->viewheader_pdf($mix_row['created_date']));
	$mpdf->WriteHTML("<br>");
	
	$mpdf->WriteHTML("<table width=100% border=1>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='7' align='center' style='border-right:#333;'><h2><strong><u>Mix Design</u></strong></u></h3></td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='1' align='left'><strong>Project Code : </strong></td></td>");
	$mpdf->WriteHTML("<td colspan='1' align='left'>{$this->ERPfunction->get_projectcode($mix_row['project_id'])}</td>");
	$mpdf->WriteHTML("<td colspan='1' align='left'><strong>Project Name : </strong></td></td>");
	$mpdf->WriteHTML("<td colspan='4' align='left' style='border-right:#333;'>{$this->ERPfunction->get_projectname($mix_row['project_id'])}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='1' align='left'><strong>Asset Code : </strong></td></td>");
	$mpdf->WriteHTML("<td colspan='1' align='left'>{$this->ERPfunction->get_asset_code($mix_row['asset_id'])}</td>");
	$mpdf->WriteHTML("<td colspan='1' align='left'><strong>Asset Name : </strong></td></td>");
	$mpdf->WriteHTML("<td colspan='4' align='left' style='border-right:#333;'>{$this->ERPfunction->get_asset_name($mix_row['asset_id'])}</td>");
	$mpdf->WriteHTML("</tr>");	
		
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td><strong>Concrete Grade : </strong></td><td colspan='6' style='border-right:#333;'>{$mix_row['concrete_grade']}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td align='center'><strong> Material Code : </strong></td><td colspan='4' align='center'><strong>Material / Item</strong></td>");
	
	$mpdf->WriteHTML("<td align='center'><strong>Unit</strong></td>");
	$mpdf->WriteHTML("<td align='center' style='border-right:#333;'><strong>Consumption in 1 CMT</strong></td>");
	$mpdf->WriteHTML("</tr>");
	
	// $mpdf->WriteHTML("<tr>");
	// $mpdf->WriteHTML("<td><strong> Description </strong></td>");
	// $mpdf->WriteHTML("<td><strong>Make / Source </strong></td>");
	// $mpdf->WriteHTML("<td><strong>Qty./Weight </strong></td>");
	// $mpdf->WriteHTML("<td><strong>Unit</strong></td>");
	// $mpdf->WriteHTML("</tr>");
	
	foreach($mix_details as $retrive_material)
	{
			$mpdf->WriteHTML("<tr>");
			$mpdf->WriteHTML("<td>{$this->ERPfunction->get_materialitemcode($retrive_material['material_id'])}</td>");
			$mpdf->WriteHTML("<td colspan='4' align='center'>{$this->ERPfunction->get_material_title($retrive_material['material_id'])}</td>");
			$mpdf->WriteHTML("<td>{$this->ERPfunction->get_items_units($retrive_material['material_id'])}</td>");
			$mpdf->WriteHTML("<td style='border-right:#333;' align='center'>{$retrive_material['consumption']}</td>");
			$mpdf->WriteHTML("</tr>");
	}
	
	
	$mpdf->WriteHTML("<tr>");
	//$mpdf->WriteHTML("<td colspan='2' align='center' height='120px' valign='top'><h3><strong> Quantity Varified By </strong></h3><br><br><br>{$this->ERPfunction->get_user_name($data['quantity_varifiedy'])}</td>");
	$mpdf->WriteHTML("<td colspan='2' align='center' height='120px' valign='top'><h3><strong> Made By </strong></h3><br><br><br>{$this->ERPfunction->get_user_name($mix_row['created_by'])}</td>");
	$mpdf->WriteHTML("<td colspan='5' align='center' height='120px' valign='top' style='border-right:#333;'><h3><strong> Approved By </strong></h3><br><br><br>{$this->ERPfunction->get_user_name($mix_row['created_by'])}</td>");
	$mpdf->WriteHTML("</tr>");
	
	// $mpdf->WriteHTML("<tr>");
	// $mpdf->WriteHTML("<td colspan='2' align='center' style='border-bottom:#333;'>(Store In-charge/Weighbridge In-charge)</td>");
	// $mpdf->WriteHTML("<td colspan='3' align='center' style='border-bottom:#333;'>(Material Manager)</td>");
	// $mpdf->WriteHTML("<td colspan='2' align='center' style='border-right:#333; border-bottom:#333;'>(Construction Manager - Site2)</td>");
	// $mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("</table>");
	
	
	

	
	
	
	$mpdf->WriteHTML('</body>');	
	$mpdf->WriteHTML('</html>');	
	$mpdf->Output();
	ob_end_flush();
	unset($mpdf);
	
	die;
?>