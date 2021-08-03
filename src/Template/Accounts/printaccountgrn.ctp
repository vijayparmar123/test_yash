.<?php
	error_reporting(0);

$created_by = isset($erp_grn_details['created_by'])?$this->ERPfunction->get_user_name($erp_grn_details['created_by']):'NA';
$last_edit = isset($erp_grn_details['last_edit'])?date("d-m-Y H:i:s",strtotime($erp_grn_details['last_edit'])):'NA';
$last_edit_by = isset($erp_grn_details['last_edit_by'])?$this->ERPfunction->get_user_name($erp_grn_details['last_edit_by']):'NA';
$created_on = date("d-m-Y H:i:s",strtotime($erp_grn_details['created_date']));

	ob_clean();
	header('Content-type: application/pdf');
	header('Content-Disposition: inline; filename="vendor_information.pdf"');
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
	// $mpdf->WriteHTML("<img style='margin-top:-30px' height=30% border='0' src='".WWW_ROOT ."img/logo/header.jpg'/>");
	$mpdf->WriteHTML($this->ERPfunction->viewheader_pdf($erp_grn_details['grn_date']));
	$mpdf->WriteHTML("<br>");
	$mpdf->WriteHTML("<table width=100% border=1>");
	$mpdf->WriteHTML("<tbody>");
		// $mpdf->WriteHTML("<tr><td colspan='8' align='center'><h1><b>YashNand Engineers & Contractors</b></h1></td></tr>");
		$mpdf->WriteHTML("<tr><td colspan='8' align='center' style='border-right:0;'><h2><b>Goods Receipt Note (GRN)</b></h2></td></tr>");
		$mpdf->WriteHTML("<tr>");
			// $mpdf->WriteHTML("<td colspan='2'><b>Project Code : </b>{$this->ERPfunction->get_projectcode($erp_grn_details['project_id'])}</td>");
			$mpdf->WriteHTML("<td colspan='8' style='border-right:0;'><b>Project Name: </b>{$this->ERPfunction->get_projectname($erp_grn_details['project_id'])}</td>");
			$mpdf->WriteHTML("</tr>");
	
			$mpdf->WriteHTML("<tr>");
				$mpdf->WriteHTML("<td><b>GRN No:</b></td><td colspan='2'>{$erp_grn_details['grn_no']}</td>");
				$mpdf->WriteHTML("<td colspan='1'><b>Date:</b></td><td colspan='1'>{$this->ERPfunction->get_date($erp_grn_details['grn_date'])}</td>");
				$mpdf->WriteHTML("<td colspan='1' ><b>Time:</b> </td><td colspan='2' style='border-right:0;'> {$erp_grn_details['grn_time']}</td>");
			$mpdf->WriteHTML("</tr>");
	
			$mpdf->WriteHTML("<tr>");
				$mpdf->WriteHTML("<td><b>Vendor Name:</b></td><td colspan='3'>{$this->ERPfunction->get_vendor_name($erp_grn_details['vendor_userid'])}</td>");
				$mpdf->WriteHTML("<td colspan='1' ><b>Vendor ID:</b></td><td colspan='3' style='border-right:0;'>{$erp_grn_details['vendor_id']} </td>");
			$mpdf->WriteHTML("</tr>");
	
			$mpdf->WriteHTML("<tr>");
			if($erp_grn_details['pr_id']!="" || $erp_grn_details['po_id']!="")
			{
				if($erp_grn_details['pr_id']!="")
				{
					$mpdf->WriteHTML("<td><b>P.R. No:</b></td>
					<td colspan='2' >
					{$this->ERPfunction->get_pr_no($erp_grn_details['pr_id'])}
					</td>");
				}else{
					$mpdf->WriteHTML("<td><b>P.O. No:</b></td>
					<td colspan='2' >
					{$this->ERPfunction->get_po_no($erp_grn_details['po_id'])}
					</td>");
				}		
				$mpdf->WriteHTML("<td colspan='2' ></td><td colspan='3' style='border-right:0;'></td>");
			}
			$mpdf->WriteHTML("</tr>");
			
			$mpdf->WriteHTML("<tr>");
			$mpdf->WriteHTML("<td><b>Security Gate Pass No.:</b> </td><td colspan='3' style='border-right:0;'>{$erp_grn_details['security_gate_pass_no']} </td>");
			$mpdf->WriteHTML("<td colspan='1' ><b>Gate Pass Date:</b> </td><td colspan='3' style='border-right:0;'>{$this->ERPfunction->get_date($erp_grn_details['gate_pass_date'])} </td>");
			$mpdf->WriteHTML("</tr>");
			
			$mpdf->WriteHTML("<tr>");
			$mpdf->WriteHTML("<td><b>Challan No:</b> </td><td colspan='3' style='border-right:0;'>{$erp_grn_details['challan_no']} </td>");
			$mpdf->WriteHTML("<td colspan='1' ><b>Challan Date:</b> </td><td colspan='3' style='border-right:0;'>{$this->ERPfunction->get_date($erp_grn_details['challan_date'])} </td>");
			$mpdf->WriteHTML("</tr>");
	
			$mpdf->WriteHTML("<tr>");
				$mpdf->WriteHTML("<td><b>Driver's Name:</b></td><td colspan='2' >{$erp_grn_details['driver_name']}</td>");
				$mpdf->WriteHTML("<td colspan='2'><b>Vehicle's No:</b></td><td colspan='3' style='border-right:0;'>{$erp_grn_details['vehicle_no']}</td>");
			$mpdf->WriteHTML("</tr>");
			
			if( $erp_grn_details["payment_method"] == "Cash")
			{
			$mpdf->WriteHTML("<tr><td colspan='8' align='center'><font size='4'><b>If Paid in Cash Enter following information</b></font></td></tr>");
			$mpdf->WriteHTML("<tr>");
				$mpdf->WriteHTML("<td><b>Purchase Amt (Rs.):</b></td><td colspan='1' >{$erp_grn_details['purchase_amt']}</td>");
				$mpdf->WriteHTML("<td colspan='1' ><b>Freight (Rs.)</b> </td><td colspan='2'>{$erp_grn_details['freight']} </td>");
				$mpdf->WriteHTML("<td colspan='2'>Unloading (Rs.):</td><td colspan='1' style='border-right:0;'>{$erp_grn_details['unloading']}</td>");
			$mpdf->WriteHTML("</tr>");
			
			$mpdf->WriteHTML("<tr>");
				$mpdf->WriteHTML("<td><b>Voucher No:</b></td><td colspan='2' >{$erp_grn_details['vouchar_no']}</td>");
				$mpdf->WriteHTML("<td colspan='2' ><b>Total Amt Paid (Rs.):</b> </td><td colspan='3' style='border-right:0;'>{$erp_grn_details['total_amt']} </td>");
			$mpdf->WriteHTML("</tr>");
			}
			$mpdf->WriteHTML("<tr>");
				$mpdf->WriteHTML("<td align='center' rowspan='2' ><b>Material Code</b></td>");
				$mpdf->WriteHTML("<td align='center' colspan='2'><b>Material / Item</b></td>");
				$mpdf->WriteHTML("<td rowspan='2' ><b>Vendor / Royalty's Qty./Weight</b></td>");
				$mpdf->WriteHTML("<td rowspan='2' ><b>Actual Qty./Weight</b></td>");
				$mpdf->WriteHTML("<td rowspan='2' ><b>Difference (+/-)</b></td>");
				$mpdf->WriteHTML("<td rowspan='2' ><b>Unit</b></td>");
				$mpdf->WriteHTML("<td rowspan='2' style='border-right:0;'><b>Remark</b></td>");
			$mpdf->WriteHTML("</tr>");
			
			$mpdf->WriteHTML("<tr>");
				$mpdf->WriteHTML("<td><b>Description</b></td>");
				$mpdf->WriteHTML("<td><b>Make / Source</b></td>");
				
			$mpdf->WriteHTML("</tr>");
			$approve_time = array();
				
			foreach($previw_list as $retrive_material)
			{	
				if($retrive_material['is_static'])
				{
					$m_code = $retrive_material['m_code'];
					$mt = $retrive_material['material_name'];
					$brnd = $retrive_material['brand_name'];
					$unit = $retrive_material['static_unit'];
				}
				else
				{
					$m_code = $this->ERPfunction->get_materialitemcode($retrive_material['material_id']);
					$mt = $this->ERPfunction->get_material_title($retrive_material['material_id']);
					$brnd = $this->ERPfunction->get_brandname($retrive_material['brand_id']);
					$unit = $this->ERPfunction->get_items_units($retrive_material['material_id']);
				}
				$num = explode(":",$retrive_material['difference_qty']);
				$rounded_num = round($num[0],3);
				$num[0] = $rounded_num;
				$round_difference = implode(":",$num);
				
				$mpdf->WriteHTML("<tr>");
					$mpdf->WriteHTML("<td>{$m_code}</td>");
					$mpdf->WriteHTML("<td>{$mt}</td>");
					$mpdf->WriteHTML("<td>{$brnd}</td>");
					$mpdf->WriteHTML("<td>{$retrive_material['quantity']}</td>");
					$mpdf->WriteHTML("<td>{$retrive_material['actual_qty']}</td>");
					$mpdf->WriteHTML("<td>{$round_difference}</td>");
					$mpdf->WriteHTML("<td>{$unit}</td>");
					$mpdf->WriteHTML("<td style='border-right:0;'>{$retrive_material['remarks']}</td>");
					 
				$mpdf->WriteHTML("</tr>");
				$approve_time[] = date("d-m-Y",strtotime($retrive_material['approved_date']))." ".date("H:i:s",strtotime($retrive_material['approved_time']));
			}
				 
			$mpdf->WriteHTML("<tr>");
				$mpdf->WriteHTML("<td align='center' colspan='3'><br><br><br>");
						if($erp_grn_details['created_by'])
						{
							$mpdf->WriteHTML("{$this->ERPfunction->get_user_name($erp_grn_details['created_by'])}");
						}
					
				$mpdf->WriteHTML("<h3><b> Made By </b></h3></td>");
				/*$mpdf->WriteHTML("<td align='center' colspan='3' style='border-right:0;'><br><br><br>");
						if($erp_grn_details['created_by']){
							$mpdf->WriteHTML("{$this->ERPfunction->get_user_name($erp_grn_details['created_by'])}"); 
						}
					
				$mpdf->WriteHTML("<h3><b> Inspected By </b></h3></td>");*/
				$mpdf->WriteHTML("<td align='center' colspan='5'><br><br><br>");
						$i=0;
						$approve_time = array();
						$numItems = count($previw_list);
						foreach($previw_list as $retrive_material){
							if(++$i === $numItems) 
							{
								$mpdf->WriteHTML("{$this->ERPfunction->get_user_name($retrive_material['approved_by'])}"); 
							}
							$approve_time[] = date("Y-m-d",strtotime($retrive_material['approved_date']))." ".date("H:i:s",strtotime($retrive_material['approved_time']));
						}
				
				$mpdf->WriteHTML("<h3><b> Approved By </b></h3></td>");
			$mpdf->WriteHTML("</tr>");
			// $mpdf->WriteHTML("<tr>");
				// $mpdf->WriteHTML("<td align='center' colspan='3' ><br><br><br> (Store In-charge/Weighbridge In-charge)</td>");
				// /* $mpdf->WriteHTML("<td align='center'  colspan='3'><br><br><br> (Material Manager)</td>"); */
				// $mpdf->WriteHTML("<td align='center' style='border-right:0;border-left:1 solid' colspan='5'><br><br><br> (Construction Manager)</td>");
			// $mpdf->WriteHTML("</tr>");
			
	$mpdf->WriteHTML("</tbody>");
	$mpdf->WriteHTML("</table>");
	
	$mpdf->WriteHTML("<br>");
	$mpdf->WriteHTML("<hr/>");
	$mpdf->WriteHTML("<br>");
	if($erp_grn_details['approved_status'])
	{
		$approve_on = max($approve_time);
		$approve_on = date("d-m-Y H:i:s",strtotime($approve_on));
	}else{
		$approve_on = 'NA';
	}
	
	$mpdf->WriteHTML("<table>");
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td style='height:30px'>Prepare On : {$created_on} </td>");
	$mpdf->WriteHTML("<td style='height:30px'></td>");
	$mpdf->WriteHTML("<td style='height:30px' >Approve On : {$approve_on} </td>");	
	$mpdf->WriteHTML("</tr>");
	$mpdf->WriteHTML("</table>");
		
	$mpdf->WriteHTML('</html>');	
	$mpdf->Output();
	ob_end_flush();
	unset($mpdf);
	
	die;
?>	
               