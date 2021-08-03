<?php
	error_reporting(0);

$created_by = isset($data['created_by'])?$this->ERPfunction->get_user_name($data['created_by']):'NA';
$last_edit = isset($data['last_edit'])?date("m-d-Y H:i:s",strtotime($data['last_edit'])):'NA';
$last_edit_by = isset($data['last_edit_by'])?$this->ERPfunction->get_user_name($data['last_edit_by']):'NA';


	ob_clean();
	header('Content-type: application/pdf');
	header('Content-Disposition: inline; filename="print_PR.pdf"');
	header('Content-Transfer-Encoding: binary');
	header('Accept-Ranges: bytes');
	
	/* $mpdf	=	new mPDF('+aCJK'); */
	$mpdf	=	new mPDF('c','A4','','' , 5 , 5 , 0 , 0 , 0 , 0);
/*	$mpdf->SetHTMLHeader("<div width='10px' style='float:left;text-align: left; font-weight: bold;'>
	<img border='0' src='".WWW_ROOT ."img/logo/yashNand_logo.png'/>	
	</div>
	<div width='20px' style='text-align: center; font-weight: bold;'>
	<span style='font-size:40px'>YASH-NAND</span><br><span style='font-size:20px'><i><u>Engineers & Contractors</u></i></span>
	</div>"); */
	
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
					font-size : 18px;	
					color : #444;
					border :1;
					border-color :gray;
					border-collapse:collapse;
				}
				td{
					border-top :0;
					border-right :1 solid;
					border-bottom :1 solid;
					border-left :1 solid;
					border-color : #dedede;
					padding : 8px;
				}
				strong{
					color :#333;
				}
				</style>');
	$mpdf->WriteHTML('</head>');
	$mpdf->WriteHTML('<body>');	
	$mpdf->WriteHTML($this->ERPfunction->viewheader_pdf($data['pr_date']));

	$mpdf->WriteHTML("<br>");
	// $mpdf->WriteHTML("<br>");
	// $mpdf->WriteHTML("<br>");
	    
	$mpdf->WriteHTML("<table width=100% border=1>");
	$mpdf->WriteHTML("<tbody>");
		// $mpdf->WriteHTML("<tr><td colspan='7' align='center'><h1><strong>YashNand Engineers & Contractors</strong></h1></td></tr>");
		$mpdf->WriteHTML("<tr><td colspan='11' align='center'><h2><strong>Purchase Requisition (P.R.)</strong></h2></td></tr>");
		$mpdf->WriteHTML("<tr>");
			// $mpdf->WriteHTML("<td colspan='2'><strong>Project Code : </strong><strong>{$this->ERPfunction->get_projectcode($data['project_id'])}</strong></td>");
			$mpdf->WriteHTML("<td colspan='11'><strong>Project Name: </strong>{$this->ERPfunction->get_projectname($data['project_id'])}</td>");
			$mpdf->WriteHTML("</tr>");
	
			$mpdf->WriteHTML("<tr>");
				$mpdf->WriteHTML("<td colspan='2'><strong>P. R. No:</strong> {$data['prno']}</td>");
				$mpdf->WriteHTML("<td colspan='3' align=right><strong>Date:</strong></td><td colspan='2'>&nbsp;{$this->ERPfunction->get_date($data['pr_date'])}</td>");
				$mpdf->WriteHTML("<td colspan='2' align=right><strong>Time:</strong></td><td colspan='2' >&nbsp;{$data['pr_time']}</td>");
			$mpdf->WriteHTML("</tr>");
	
			$mpdf->WriteHTML("<tr>");
				$mpdf->WriteHTML("<td colspan='11'><strong>Contact No: (1)</strong> : {$data['contact_no1']} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Contact No: (2) </strong> : {$data['contact_no2']} </td>");
				$mpdf->WriteHTML("");
			$mpdf->WriteHTML("</tr>");
	
			$mpdf->WriteHTML("<tr>");
				$mpdf->WriteHTML("<td rowspan='2' align='center'><strong>Material Code</strong></td>
					<td colspan='4' align='center'><strong>Material / Item</strong></td>");
				$mpdf->WriteHTML("<td rowspan='2' ><strong>Delivery<br>Date<br>(Planned)</strong></td><td rowspan='2'><strong>Remarks</strong></td><td rowspan='2'><strong>Usage</strong></td><td rowspan='2'><strong>Mode of Purchase</strong></td><td rowspan='2'><strong>Purchased Quantity</strong></td><td rowspan='2'><strong>Purchase Remarks</strong></td>");
			$mpdf->WriteHTML("</tr>");
	
			$mpdf->WriteHTML("<tr>");
				$mpdf->WriteHTML("<td width=440><strong>Description</strong></td><td><strong>Make / Source</strong></td>");
				$mpdf->WriteHTML("<td><strong>Quantity</strong></td><td><strong>Unit</strong></td>");
			$mpdf->WriteHTML("</tr>");
			
			
			
				foreach($previw_list as $retrive_material)
				{
					if(is_numeric($retrive_material['material_id']) && $retrive_material['material_id'] != 0)
					{
						$m_code = $this->ERPfunction->get_materialitemcode($retrive_material['material_id']);
						$mt = $this->ERPfunction->get_material_title($retrive_material['material_id']);
						$brnd = $this->ERPfunction->get_brandname($retrive_material['brand_id']);
						$unit = $this->ERPfunction->get_items_units($retrive_material['material_id']);
					}
					else
					{
						$m_code = $retrive_material['m_code'];
						$mt = $retrive_material['material_name'];
						$brnd = $retrive_material['brand_name'];
						$unit = $retrive_material['static_unit'];
					}
					$mode_of_purchase = ($retrive_material['show_in_purchase'])?"Central":"Local";
					$approved_by = $retrive_material['approved_by'];
					$mpdf->WriteHTML("<tr>");
					$mpdf->WriteHTML("<td>{$m_code}</td>");
					$mpdf->WriteHTML("<td>{$mt}</td>");
					$mpdf->WriteHTML("<td>{$brnd}</td>");
					$mpdf->WriteHTML("<td>{$retrive_material['quantity']}</td>");
					$mpdf->WriteHTML("<td>{$unit}</td>");
					$mpdf->WriteHTML("<td>{$this->ERPfunction->get_date($retrive_material['delivery_date'])}</td>");
					$mpdf->WriteHTML("<td>{$retrive_material['name_of_subcontractor']}</td>");
					$mpdf->WriteHTML("<td>{$retrive_material['usages']}</td>");
					$mpdf->WriteHTML("<td>{$mode_of_purchase}</td>");
					$mpdf->WriteHTML("<td>".$this->ERPfunction->get_pr_purchased_quantity_by_po($retrive_material["pr_material_id"])."</td>");
					$mpdf->WriteHTML("<td>".$this->ERPfunction->get_purchase_remarks_of_pr_material($retrive_material["pr_material_id"])."".$this->ERPfunction->get_pono_by_pr_material($retrive_material["pr_material_id"])."</td>");
					$mpdf->WriteHTML("</tr>");
				}
			
			
			
			$mpdf->WriteHTML("<tr>");
			$mpdf->WriteHTML("<td colspan='5' align='center'><h3><strong> Prepared By </strong></h3><br><br><br>{$this->ERPfunction->get_user_name($data['created_by'])}</td>");
			echo $un = $this->ERPfunction->get_user_name($approved_by);	
			$mpdf->WriteHTML("<td colspan='6' align='center'><h3><strong> Approved By </strong></h3><br><br>");
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
			
			
							
		
			
	$mpdf->WriteHTML("</tbody>");
	$mpdf->WriteHTML("</table>");
	
	// $mpdf->WriteHTML("<br>");
	
	// $mpdf->WriteHTML("<br>");
	
/* 	$mpdf->WriteHTML("<table>");
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td style='height:30px' width=50%>Created By : {$created_by} </td>");
	$mpdf->WriteHTML("<td width=20%></td>");
	$mpdf->WriteHTML("<td style='height:30px' >Last Edited By : {$last_edit_by} </td>");	
	$mpdf->WriteHTML("</tr>");
	$mpdf->WriteHTML("</table>"); */
	$mpdf->WriteHTML("<p>Checked By : {$this->ERPfunction->get_user_name($previw_list[0]['purchase_first_approveby'])}</p>");
	$mpdf->WriteHTML("<p>Date : ".date("d-m-Y",strtotime($previw_list[0]['purchase_first_approve_date']))."</p>");	
	$mpdf->WriteHTML('</html>');	
	$mpdf->Output();
	ob_end_flush();
	unset($mpdf);
	
	die;
?>	
               