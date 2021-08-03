<?php
	error_reporting(0);

// $created_by = isset($data['created_by'])?$this->ERPfunction->get_user_name($data['created_by']):'NA';
// $last_edit = isset($data['last_edit'])?date("m-d-Y H:i:s",strtotime($data['last_edit'])):'NA';
// $last_edit_by = isset($data['last_edit_by'])?$this->ERPfunction->get_user_name($data['last_edit_by']):'NA';


	ob_clean();
	header('Content-type: application/pdf');
	header('Content-Disposition: inline; filename="print_OS.pdf"');
	header('Content-Transfer-Encoding: binary');
	header('Accept-Ranges: bytes');
	
	/* $mpdf	=	new mPDF('+aCJK'); */
	$mpdf	=	new mPDF('c','A4','','' , 5 , 5 , 0 , 0 , 0 , 0);
	// $mpdf->SetHTMLHeader("<div width='10px' style='float:left;text-align: left; font-weight: bold;'>
	// <img border='0' src='".WWW_ROOT ."img/logo/yashNand_logo.png'/>	
	// </div>
	// <div width='20px' style='text-align: center; font-weight: bold;'>
	// <span style='font-size:40px'>YASH-NAND</span><br><span style='font-size:20px'><i><u>Engineers & Contractors</u></i></span>
	// </div>");
	
	$mpdf->WriteHTML('<html>');
	$mpdf->WriteHTML('<head>');
	/* $mpdf->WriteHTML('<style>
				table{
				 font-family: sans-serif;					
				}
				td{
					border-right :1 solid;
					border-bottom :1 solid;
					border-top :0;
					border-left :0;					
				}			
				</style>'); */
	$mpdf->WriteHTML('<style>
				table{
				 font-family: sans-serif;
				font-size : 15px;	
					color : #444;
					border :1;
					border-color :gray;
					border-collapse:collapse;
				}
				td{
					border-right :1 solid;
					border-bottom :1 solid;
					border-top :0;
					border-left :0;
					border-color : #777;
					padding:10px;
				}				
	</style>');
	$mpdf->WriteHTML('</head>');
	$mpdf->WriteHTML('<body>');			
	$mpdf->WriteHTML($this->ERPfunction->viewheader_pdf());

	    
	$mpdf->WriteHTML("<table width=100% border=1>");
	$mpdf->WriteHTML("<tbody>");
		// $mpdf->WriteHTML("<tr><td colspan='7' align='center'><h1><strong>YashNand Engineers & Contractors</strong></h1></td></tr>");
		$mpdf->WriteHTML("<tr><td colspan='7' align='center' style='border-right:0;'><h2><strong>Opening Stock (O.S.)</strong></h2></td></tr>");
		$mpdf->WriteHTML("<tr>");
			// $mpdf->WriteHTML("<td colspan='2'><strong>Project Code : </strong><strong>{$this->ERPfunction->get_projectcode($data['project_id'])}</strong></td>");
			$mpdf->WriteHTML("<td colspan='7' style='border-right:0;'><strong>Project Name: </strong>{$this->ERPfunction->get_projectname($project_id)}</td>");
			$mpdf->WriteHTML("</tr>");
	
			$mpdf->WriteHTML("<tr>");
				$mpdf->WriteHTML("<td colspan='2'><strong>Project Code:</strong> {$this->ERPfunction->get_projectcode($project_id)}</td>");
				$mpdf->WriteHTML("<td colspan='2' align=right><strong>Date</strong></td><td style='border-right:0;' colspan='3'>&nbsp;{$this->ERPfunction->get_date(date("d-m-Y"))}</td>");
				//$mpdf->WriteHTML("<td colspan='' align=right><strong>Time:</strong></td><td colspan='2'  style='border-right:0;' >&nbsp;{$data['pr_time']}</td>");
			$mpdf->WriteHTML("</tr>");
	
			// $mpdf->WriteHTML("<tr>");
				// $mpdf->WriteHTML("<td colspan='7' style='border-right:0;'><strong>Contact No: (1)</strong> : {$data['contact_no1']} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Contact No: (2) </strong> : {$data['contact_no2']} </td>");
				// $mpdf->WriteHTML("");
			// $mpdf->WriteHTML("</tr>");
	
			$mpdf->WriteHTML("<tr>");
				$mpdf->WriteHTML("<td rowspan='2' align='center'><strong>Material Code</strong></td>
					<td colspan='4' align='center'><strong>Material / Item</strong></td><td rowspan='2' align='center'><strong>Remarks</strong></td>");
				
			$mpdf->WriteHTML("</tr>");
	
			$mpdf->WriteHTML("<tr>");
				$mpdf->WriteHTML("<td width=440><strong>Description</strong></td>");
				$mpdf->WriteHTML("<td colspan='2'><strong>Quantity</strong></td><td><strong>Unit</strong></td>");
			$mpdf->WriteHTML("</tr>");
			
			
			
				foreach($previw_list as $retrive_material)
				{
					$mpdf->WriteHTML("<tr>");
						$mpdf->WriteHTML("<td>{$this->ERPfunction->get_materialitemcode($retrive_material['material_id'])}</td>");
						$mpdf->WriteHTML("<td>{$this->ERPfunction->get_material_title($retrive_material['material_id'])}</td>");
						//$mpdf->WriteHTML("<td>{$this->ERPfunction->get_brandname($retrive_material['brand_id'])}</td>");
						$mpdf->WriteHTML("<td colspan='2'>{$retrive_material['quantity']}</td>");
						$mpdf->WriteHTML("<td>{$this->ERPfunction->get_items_units($retrive_material['material_id'])}</td>");
						$mpdf->WriteHTML("<td>{$retrive_material['note']}</td>");
						//$mpdf->WriteHTML("<td>{$retrive_material['delivery_date']}</td>");
						//$mpdf->WriteHTML("<td style='border-right:0;'>{$retrive_material['name_of_subcontractor']}</td>");
						$mpdf->WriteHTML("</tr>");
				}
			
			
			
			// $mpdf->WriteHTML("<tr>");
			// $mpdf->WriteHTML("<td colspan='2' align='center'><br><br><br>({$this->ERPfunction->get_user_name($data['created_by'])})<h3><strong> Prepared By </strong></h3></td>");
			// $un = $this->ERPfunction->get_user_name($data['approve_by']);
			// $mpdf->WriteHTML("<td style='border-right:0;' colspan='5' align='center'><br><br>".(($un == "User Not Found")? '<br>':$us)."<h3><strong> Approved By </strong></h3></td>");
			// $mpdf->WriteHTML("</tr>");
			
			// $mpdf->WriteHTML("<tr>");
			// $mpdf->WriteHTML("<td colspan='2' style='border-bottom:0' align='center'>(Material Manager)</td>");
			// $mpdf->WriteHTML("<td colspan='5' style='border-bottom:0;' align='center'>(Construction Manager)</td>");
			// $mpdf->WriteHTML("</tr>");
			
	$mpdf->WriteHTML("</tbody>");
	$mpdf->WriteHTML("</table>");
	
	$mpdf->WriteHTML("<br>");
	
	$mpdf->WriteHTML("<br>");
	
 	/* $mpdf->WriteHTML("<table>");
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td style='height:30px' width=50%>Prepared By : {$created_by} </td>");
	$mpdf->WriteHTML("<td width=20%></td>");
	$mpdf->WriteHTML("<td style='height:30px' >Last Edited By : {$last_edit_by} </td>");	
	$mpdf->WriteHTML("</tr>");
	$mpdf->WriteHTML("</table>");$mpdf->WriteHTML("<table>"); */
	
	
		
	$mpdf->WriteHTML('</html>');	
	$mpdf->Output();
	ob_end_flush();
	unset($mpdf);
	
	die;
?>	
               