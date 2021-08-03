<?php
	error_reporting(0);
	$created_by = isset($project_data['created_by'])?$this->ERPfunction->get_user_name($project_data['created_by']):'NA';
	$last_edit = isset($project_data['last_edit'])?date("m-d-Y H:i:s",strtotime($project_data['last_edit'])):'NA';
	$last_edit_by = isset($project_data['last_edit_by'])?$this->ERPfunction->get_user_name($project_data['last_edit_by']):'NA';

	ob_clean();
	header('Content-type: application/pdf');
	header('Content-Disposition: inline; filename="MRN_ALERT.pdf"');
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
	$mpdf->WriteHTML($this->ERPfunction->viewheader_pdf());
	$mpdf->WriteHTML("<br>");
	
	$mpdf->WriteHTML("<table width=100% border=1>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='7' align='center'><h2><strong><u>Material Return Note (MRN)</u></strong></u></h3></td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='1' align='left'><strong>Project Code : </strong></td></td>");
	$mpdf->WriteHTML("<td colspan='1' align='left'>{$this->ERPfunction->get_projectcode($data['project_id'])}</td>");
	$mpdf->WriteHTML("<td colspan='1' align='left'><strong>Project Name : </strong></td></td>");
	$mpdf->WriteHTML("<td colspan='4' align='left'>{$this->ERPfunction->get_projectname($data['project_id'])}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td><strong>MRN No :</strong></td><td colspan='2'>{$data['mrn_no']}</td>");
	$mpdf->WriteHTML("<td><strong>Date : </strong></td><td>{$this->ERPfunction->get_date($data['mrn_date'])}</td>");
	$mpdf->WriteHTML("<td><strong>Time : </strong></td><td>{$data['mrn_time']}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td><strong>Vendor Name : </strong></td><td colspan='2'>{$this->ERPfunction->get_vendor_name($data['vendor_user'])}</td>");
	$mpdf->WriteHTML("<td><strong>Vendor ID : </strong></td>");
	$mpdf->WriteHTML("<td colspan='3'>{$data['vendor_id']}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td><strong>Driver's Name : </strong></td><td colspan='2'>{$data['driver_name']}</td>");
	$mpdf->WriteHTML("<td colspan='2'><strong>Vehicle's No: </strong> </td>");
	$mpdf->WriteHTML("<td colspan='2'>{$data['vehicle_no']}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td rowspan='2' align='center'><strong> Material Code : </strong></td><td colspan='2' align='center'><strong>Material / Item</strong></td>");
	$mpdf->WriteHTML("<td colspan='2' rowspan='2'><strong>Qty./Weight </strong></td>");
	$mpdf->WriteHTML("<td rowspan='2'><strong>Unit</strong></td>");
	$mpdf->WriteHTML("<td rowspan='2'><strong>Remarks for Return</strong></td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td><strong> Description </strong></td>");
	$mpdf->WriteHTML("<td><strong>Make / Source </strong></td>");
	$mpdf->WriteHTML("</tr>");
	
	foreach($previw_list as $retrive_material)
	{
			$mpdf->WriteHTML("<tr>");
			$mpdf->WriteHTML("<td>{$this->ERPfunction->get_materialitemcode($retrive_material['material_id'])}</td>");
			$mpdf->WriteHTML("<td>{$this->ERPfunction->get_material_title($retrive_material['material_id'])}</td>");
			$mpdf->WriteHTML("<td>{$this->ERPfunction->get_brandname($retrive_material['brand_id'])}</td>");
			$mpdf->WriteHTML("<td colspan='2'>{$retrive_material['quantity']}</td>");
			$mpdf->WriteHTML("<td>{$this->ERPfunction->get_items_units($retrive_material['material_id'])}</td>");
			$mpdf->WriteHTML("<td>{$retrive_material['remarks']}</td>");
			$mpdf->WriteHTML("</tr>");
	}
	
	
	$mpdf->WriteHTML("<tr>");
			$mpdf->WriteHTML("<td colspan='3' align='center'><h3><strong> Made By </strong></h3><br><br><br>({$this->ERPfunction->get_user_name($data['created_by'])})</td>");
			$un = $this->ERPfunction->get_user_name($data['approve_by']);
			$mpdf->WriteHTML("<td style='border-right:0;' colspan='4' align='center'><h3><strong> Approved By </strong></h3><br><br><br>");
			
						$approver = array();
			$ids = array();
						foreach($previw_list as $retrive_material){
							if(!in_array($retrive_material['approved_by'],$ids))
							{
							$approver[] = $this->ERPfunction->get_user_name($retrive_material['approved_by']);
							$ids[] = $retrive_material['approved_by'];
							}
						}
						
						foreach($approver as $app){
							$mpdf->WriteHTML($app . "<br>");
						}
						
			$mpdf->WriteHTML("</td>");
			$mpdf->WriteHTML("</tr>");
	
	// $mpdf->WriteHTML("<tr>");
	// $mpdf->WriteHTML("<td colspan='2' align='center'>(Store In-charge/Weighbridge In-charge)</td>");
	// $mpdf->WriteHTML("<td colspan='3' align='center'>(Material Manager)</td>");
	// $mpdf->WriteHTML("<td colspan='2' align='center'>(Construction Manager)</td>");
	// $mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("</table>");
	
	
	

	
	
	
	$mpdf->WriteHTML('</body>');	
	$mpdf->WriteHTML('</html>');	
	$mpdf->Output();
	ob_end_flush();
	unset($mpdf);
	
	die;
?>