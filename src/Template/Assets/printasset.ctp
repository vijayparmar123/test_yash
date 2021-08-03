<?php
	error_reporting(0);
	
	ob_clean();
	header('Content-type: application/pdf');
	header('Content-Disposition: inline; filename="Asset.pdf"');
	header('Content-Transfer-Encoding: binary');
	header('Accept-Ranges: bytes');

	$road_tax_status=isset($asset_data['road_tax_status'])?$asset_data['road_tax_status']:0;
	$insurance_status=isset($asset_data['insurance_status'])?$asset_data['insurance_status']:0;
	$fitness_status=isset($asset_data['fitness_status'])?$asset_data['fitness_status']:0;
	$passing_registration_status=isset($asset_data['passing_registration_status'])?$asset_data['passing_registration_status']:0;

	$due_date_reg=($passing_registration_status)?date("d-m-Y",strtotime($asset_data['due_date_reg'])):'';
	$due_date_fitness=($fitness_status)?date("d-m-Y",strtotime($asset_data['due_date_fitness'])):'';
	$due_date_road_tax=($road_tax_status)?date("d-m-Y",strtotime($asset_data['due_date_road_tax'])):'';
	   
	$due_date_insurance=($insurance_status)?date("d-m-Y",strtotime($asset_data['due_date_insurance'])):''; 

	// $mpdf	=	new mPDF('+aCJK');
	$mpdf	=	new mPDF('c','A4','','' , '4' , '4' , 0 , 0 , 0 , 0);

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
				pre{
					font-family: sans-serif;
					font-size : 12px;	
					color : #444;
				}
				td{
					border-right :1 solid;
					border-bottom :1 solid;
					border-top :0;
					border-left :0;
					border-color : #777;
				}
				
				</style>');
	$mpdf->WriteHTML('</head>');
	$mpdf->WriteHTML('<body>');	
	
		
	
	$mpdf->WriteHTML($this->ERPfunction->viewheader_pdf($asset_data['purchase_date']));
	$mpdf->WriteHTML("<table width=100%  border=1>");
	
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='13' align='center'><h2><strong><u>Asset</u></strong></h3></td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='2' align='left'><b>Asset Group : </b>{$this->ERPfunction->get_asset_group_name($asset_data['asset_group'])}</td>");
	$mpdf->WriteHTML("<td colspan='11' align='left'><b>Asset ID : </b>{$asset_data['asset_code']}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='2' align='left'><b>Asset Name : </b>{$asset_data['asset_name']}</td>");
	$mpdf->WriteHTML("<td colspan='11' align='left'><b>Asset Capacity : </b>{$asset_data['capacity']}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td align='left'><b>Make: </b></td><td colspan='12'>{$this->ERPfunction->get_category_title($asset_data['asset_make'])}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='2' align='left'><b>Vendor's Name : </b>{$this->ERPfunction->get_vendor_name($asset_data['vendor_name'])}</td>");
	$mpdf->WriteHTML("<td colspan='11' align='left'><b>Vendor's ID : </b>{$asset_data['vendor_id']}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='2' align='left'><b>Model No : </b>{$asset_data['model_no']}</td>");
	$mpdf->WriteHTML("<td colspan='11' align='left'><b>Identity / Veh. No. : </b>{$asset_data['vehicle_no']}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='2' align='left'><b>Date of Purchase : </b>".date("d-m-Y",strtotime($asset_data['purchase_date']))."</td>");
	$mpdf->WriteHTML("<td colspan='11' align='left'><b>Amount of Purchase : </b>{$asset_data['purchase_amount']}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='2' align='left'><b>P.O. No. : </b>".$asset_data['po_no']."</td>");
	$mpdf->WriteHTML("<td colspan='11' align='left'><b>Warranty Period : </b>{$asset_data['warranty_period']}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='2' align='left'><b>Due Date of Road Tax : </b>{$due_date_road_tax}</td>");
	$mpdf->WriteHTML("<td colspan='11' align='left'><b>Due Date of Passing / Registration : </b>{$due_date_reg}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='2' align='left'><b>Due Date of Fitness : </b>{$due_date_fitness}</td>");
	$mpdf->WriteHTML("<td colspan='11' align='left'><b>Due Date of Insurance : </b>{$due_date_insurance}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='2' align='left'><b>Payment  : </b>{$asset_data['payment']}</td>");
	$mpdf->WriteHTML("<td colspan='11' align='left'><b>Voch. No. / Inw. No. : </b>{$asset_data['voucher_no']}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td align='left'><b>Description: </b></td><td colspan='12'>{$asset_data['description']}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("</table>");
	
	$mpdf->WriteHTML('</body>');	
	$mpdf->WriteHTML('</html>');	
	$mpdf->Output();
	ob_end_flush();
	unset($mpdf);
	
	die;
?>