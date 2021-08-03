.<?php
	error_reporting(0);

$created_by = isset($erp_grn_details['created_by'])?$this->ERPfunction->get_user_name($erp_grn_details['created_by']):'NA';
$last_edit = isset($erp_grn_details['last_edit'])?date("m-d-Y H:i:s",strtotime($erp_grn_details['last_edit'])):'NA';
$last_edit_by = isset($erp_grn_details['last_edit_by'])?$this->ERPfunction->get_user_name($erp_grn_details['last_edit_by']):'NA';


	ob_clean();
	header('Content-type: application/pdf');
	header('Content-Disposition: inline; filename="INWARD CORRESPONDENCE.pdf"');
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
					border-top :0;
					border-right :1 solid;
					border-bottom :1 solid;
					border-left :0;
					border-color : #dedede;
					padding:8px;
				}
				strong{
					color :#333;
				}		
				</style>');
	$mpdf->WriteHTML('</head>');
	$mpdf->WriteHTML('<body>');	
	
	$mpdf->WriteHTML($this->ERPfunction->viewheader_pdf($print_data['inward_date']));
	$mpdf->WriteHTML("<br>");
	$mpdf->WriteHTML("<table width=100% border=1>");
	$mpdf->WriteHTML("<tbody>");
		
		$mpdf->WriteHTML("<tr><td colspan='8' align='center'><h2><b>INWARD CORRESPONDENCE</b></h2></td></tr>");
		$mpdf->WriteHTML("<tr>");
			
			$mpdf->WriteHTML("<td colspan='8'><b>Project Name: </b>{$this->ERPfunction->get_projectname($print_data['project_id'])}</td>");
			$mpdf->WriteHTML("</tr>");
	
			$mpdf->WriteHTML("<tr>");
				$mpdf->WriteHTML("<td width=5px><b>Inward No:</b></td><td colspan='3'>{$print_data['out_inward_no']}</td>");
				$mpdf->WriteHTML("<td><b>Date:</b></td><td colspan='3'>{$this->ERPfunction->get_date($print_data['inward_date'])}</td>");
				
			$mpdf->WriteHTML("</tr>");
	
			$mpdf->WriteHTML("<tr>");
				$mpdf->WriteHTML("<td><b>Their Ref. No:</b></td><td colspan='3'>{$print_data['reference_no']}</td>");
				$mpdf->WriteHTML("<td colspan='1' ><b>Ref.Date:</b></td><td colspan='3'>{$this->ERPfunction->get_date($print_data['date'])}</td>");
			$mpdf->WriteHTML("</tr>");
	
			$mpdf->WriteHTML("<tr>");
				
					$mpdf->WriteHTML("<td><b>Agency Name:</b></td>
					<td colspan='3' >
					{$this->ERPfunction->get_category_title($print_data['agency_name'])}
					</td>");
						
				$mpdf->WriteHTML("<td colspan='1' ><b>Type of Agency:</b> </td><td colspan='3'>{$this->ERPfunction->get_category_title($print_data['agency_client_name'])} </td>");
			$mpdf->WriteHTML("</tr>");
	
			$mpdf->WriteHTML("<tr>");
				$mpdf->WriteHTML("<td><b>Written By:</b></td><td colspan='3' >{$this->ERPfunction->get_category_title($print_data['written_by'])}</td>");
				$mpdf->WriteHTML("<td colspan='1'><b>Designation:</b></td><td colspan='3'>{$this->ERPfunction->get_category_title($print_data['designation'])}</td>");
			$mpdf->WriteHTML("</tr>");
			
			$mpdf->WriteHTML("<tr>");
				$mpdf->WriteHTML("<td><b>Subject:</b></td><td colspan='3' >{$print_data['subject']}</td>");
				$mpdf->WriteHTML("<td colspan='1'><b>Enclosures:</b></td><td colspan='3'>{$print_data['enclosures']}</td>");
			$mpdf->WriteHTML("</tr>");
			
			$mpdf->WriteHTML("<tr>");
			
			$mpdf->WriteHTML("<td colspan='8'><b>Comment: </b>{$print_data['comment']}</td>");
			$mpdf->WriteHTML("</tr>");
			
			$mpdf->WriteHTML("<tr>");
				$mpdf->WriteHTML("<td align='center' colspan='4'><br><br><br>");
						if($print_data['created_by'])
						{
							$mpdf->WriteHTML("{$this->ERPfunction->get_user_name($print_data['created_by'])}");
						}
										
				$mpdf->WriteHTML("<h3><b> Prepared By </b></h3></td>");
				$mpdf->WriteHTML("<td align='center' colspan='4'><br><br><br>");
						
							if($print_data['last_edit_by']) 
							{
								$mpdf->WriteHTML("{$this->ERPfunction->get_user_name($print_data['last_edit_by'])}"); 
							}
						
				
				$mpdf->WriteHTML("<h3><b> Last Edited By </b></h3></td>");
			$mpdf->WriteHTML("</tr>");
			
			
	$mpdf->WriteHTML("</tbody>");
	$mpdf->WriteHTML("</table>");
		
	$mpdf->WriteHTML('</html>');	
	$mpdf->Output();
	ob_end_flush();
	unset($mpdf);
	
	die;
?>	
               