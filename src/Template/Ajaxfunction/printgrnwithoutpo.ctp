<?php
	error_reporting(0);
	
	ob_clean();
	header('Content-type: application/pdf');
	header('Content-Disposition: inline; filename="grn.pdf"');
	header('Content-Transfer-Encoding: binary');
	header('Accept-Ranges: bytes');

	
	/* $mpdf	=	new mPDF('+aCJK'); */
	$mpdf	=	new mPDF('c','A4','','' , 5 , 5 , 0 , 0 , 0 , 0);
	
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
					font-size : 14px;	
					color : #444;
					border :1;
					border-color :gray;
					border-collapse:collapse;
				}
				td{
					border-top :1 solid;
					border-right :1 solid;
					border-bottom :0;
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
	$mpdf->WriteHTML($this->ERPfunction->viewheader_pdf());	  
	$mpdf->WriteHTML("<br>");	  
	$mpdf->WriteHTML("<table width=100% border=1>");
	$mpdf->WriteHTML("<tbody>");
		// $mpdf->WriteHTML("<tr><td colspan='8' align='center'><h1><b>YashNand Engineers & Contractors</b></h1></td></tr>");
		$mpdf->WriteHTML("<tr><td colspan='8' align='center' style='border-top:1 solid;border-right:1 solid'><h2><b>Goods Receipt Note (GRN)</b></h2></td></tr>");
		$mpdf->WriteHTML("<tr>");
			$mpdf->WriteHTML("<td colspan='2'><b>Project Code : </b>{$this->ERPfunction->get_projectcode($erp_grn_details['project_id'])}</td>");
			$mpdf->WriteHTML("<td colspan='6' style='border-right:1 solid'><b>Project Name: </b>{$this->ERPfunction->get_projectname($erp_grn_details['project_id'])}</td>");
			$mpdf->WriteHTML("</tr>");
	
			$mpdf->WriteHTML("<tr>");
				$mpdf->WriteHTML("<td><b>GRN No:</b></td><td colspan='3'>{$erp_grn_details['grn_no']}</td>");
				$mpdf->WriteHTML("<td colspan='1'><b>Date:</b></td><td colspan='1'>{$this->ERPfunction->get_date($erp_grn_details['grn_date'])}</td>");
				$mpdf->WriteHTML("<td colspan='1'><b>Time:</b> </td><td colspan='1' style='border-right:1 solid'> {$erp_grn_details['grn_time']}</td>");
			$mpdf->WriteHTML("</tr>");
	
			$mpdf->WriteHTML("<tr>");
				$mpdf->WriteHTML("<td><b>Vendor Name:</b></td><td colspan='3'>{$this->ERPfunction->get_vendor_name($erp_grn_details['vendor_userid'])}</td>");
				$mpdf->WriteHTML("<td colspan='1'><b>Vendor ID:</b></td><td colspan='3' style='border-right:1 solid'>{$erp_grn_details['vendor_id']} </td>");
			$mpdf->WriteHTML("</tr>");
	
			$mpdf->WriteHTML("<tr>");
				$mpdf->WriteHTML("<td><b>P.R. No:</b></td>
					<td colspan='2' >{$this->ERPfunction->get_pr_no($erp_grn_details['pr_id'])}</td>");
				$mpdf->WriteHTML("<td colspan='2' ><b>Challan No:</b> </td><td colspan='3'  style='border-right:1 solid'>{$erp_grn_details['challan_no']} </td>");
			$mpdf->WriteHTML("</tr>");
	
			$mpdf->WriteHTML("<tr>");
				$mpdf->WriteHTML("<td><b>Driver's Name:</b></td><td colspan='2' >{$erp_grn_details['driver_name']}</td>");
				$mpdf->WriteHTML("<td colspan='2'><b>Vehicle's No:</b></td><td colspan='3' style='border-right:1 solid'>{$erp_grn_details['vehicle_no']}</td>");
			$mpdf->WriteHTML("</tr>");
			
			$mpdf->WriteHTML("<tr>");
				$mpdf->WriteHTML("<td><b>Payment Method :</b></td><td colspan='7'  style='border-right:1 solid'>{$erp_grn_details['payment_method']}</td>");
			$mpdf->WriteHTML("</tr>");
			
			if( $erp_grn_details["payment_method"] == "Cash")
			{
			$mpdf->WriteHTML("<tr><td colspan='8' align='center'><font size='4'><b>If Paid in Cash Enter following information</b></font></td></tr>");
			$mpdf->WriteHTML("<tr>");
				$mpdf->WriteHTML("<td><b>Purchase Amt (Rs.):</b></td><td colspan='1' >{$erp_grn_details['purchase_amt']}</td>");
				$mpdf->WriteHTML("<td colspan='1' ><b>Freight (Rs.)</b> </td><td colspan='2'>{$erp_grn_details['freight']} </td>");
				$mpdf->WriteHTML("<td colspan='2'>Unloading (Rs.):</td><td colspan='1' style='border-right:1 solid'>{$erp_grn_details['unloading']}</td>");
			$mpdf->WriteHTML("</tr>");
			
			$mpdf->WriteHTML("<tr>");
				$mpdf->WriteHTML("<td><b>Voucher No:</b></td><td colspan='2' >{$erp_grn_details['vouchar_no']}</td>");
				$mpdf->WriteHTML("<td colspan='2' ><b>Total Amt Paid (Rs.):</b> </td><td colspan='3'  style='border-right:1 solid'>{$erp_grn_details['total_amt']} </td>");
			$mpdf->WriteHTML("</tr>");
			}
			$mpdf->WriteHTML("<tr>");
				$mpdf->WriteHTML("<td align='center' rowspan='2' ><b>Material Code</b></td>");
				$mpdf->WriteHTML("<td align='center' colspan='3'><b>Material / Item</b></td>");
				$mpdf->WriteHTML("<td rowspan='2' ><b>Vendor / Royalty's Qty./Weight</b></td>");
				$mpdf->WriteHTML("<td rowspan='2' ><b>Actual Qty./Weight</b></td>");
				$mpdf->WriteHTML("<td rowspan='2' ><b>Difference (+/-)</b></td>");
				$mpdf->WriteHTML("<td rowspan='2' style='border-right:1 solid'><b>Unit</b></td>");
				// $mpdf->WriteHTML("<td rowspan='2' ><b>Remarks by Inspector</b></td>");
			$mpdf->WriteHTML("</tr>");
			
			$mpdf->WriteHTML("<tr>");
				$mpdf->WriteHTML("<td colspan='2' width=300 align=center><b>Description</b></td>");
				$mpdf->WriteHTML("<td><b>Make / Source</b></td>");
				
			$mpdf->WriteHTML("</tr>");
				
			// foreach($erp_grn_details['material'] as $retrive_material)
			for($i=0;$i< sizeof($erp_grn_details['material']['material_id']);$i++)
			{	
				$mpdf->WriteHTML("<tr>");
				$mpdf->WriteHTML("<td>{$this->ERPfunction->get_materialitemcode($erp_grn_details['material']['material_id'][$i])}</td>");
				$mpdf->WriteHTML("<td colspan='2'>{$this->ERPfunction->get_material_title($erp_grn_details['material']['material_id'][$i])}</td>");
				$mpdf->WriteHTML("<td>{$this->ERPfunction->get_brandname($erp_grn_details['material']['brand_id'][$i])}</td>");
				$mpdf->WriteHTML("<td>{$erp_grn_details['material']['quantity'][$i]}</td>");
				$mpdf->WriteHTML("<td>{$erp_grn_details['material']['actual_qty'][$i]}</td>");
				$mpdf->WriteHTML("<td>{$erp_grn_details['material']['difference_qty'][$i]}</td>");
				$mpdf->WriteHTML("<td   style='border-right:1 solid'>{$this->ERPfunction->get_items_units($erp_grn_details['material']['material_id'][$i])}</td>");
				// $mpdf->WriteHTML("<td>{$erp_grn_details['material']['remarks'][$i]}</td>");					 
				$mpdf->WriteHTML("</tr>");
			}
				
			
				
			$mpdf->WriteHTML("<tr>");
				/* $mpdf->WriteHTML("<td align='center' colspan='2'><br><br><br><h3>");
						if($erp_grn_details['created_by'])
						{
							$mpdf->WriteHTML("{$this->ERPfunction->get_user_name($erp_grn_details['created_by'])}");
						}
					
				$mpdf->WriteHTML("<b> Quantity Varified By </b></h3></td>"); */
				$mpdf->WriteHTML("<td align='center' colspan='3'><br><br><br>");
				$user_id = $this->request->session()->read('user_id');
						// if($erp_grn_details['created_by']){
							$mpdf->WriteHTML("{$this->ERPfunction->get_user_name($user_id)}"); 
						// }
						/* if($erp_grn_details['created_by']){
							$mpdf->WriteHTML("{$this->ERPfunction->get_user_name($erp_grn_details['created_by'])}"); 
						} */
					
				$mpdf->WriteHTML("<h3><b> Made By </b></h3></td>");
				$mpdf->WriteHTML("<td align='center' colspan='5' style='border-right:1 solid'><br><br><br>");
					
						if($erp_grn_details['approve_by']){
							$mpdf->WriteHTML("{$this->ERPfunction->get_user_name($erp_grn_details['approve_by'])}"); 
						}
				
				$mpdf->WriteHTML("<h3><b> Approveed By </b></h3></td>");
			$mpdf->WriteHTML("</tr>");
			$mpdf->WriteHTML("<tr>");
				$mpdf->WriteHTML("<td align='center' colspan='2'><br><br> (Store In-charge/Weighbridge In-charge)</td>");
				$mpdf->WriteHTML("<td align='center'  colspan='3'><br><br> (Material Manager)</td>");
				$mpdf->WriteHTML("<td align='center'  colspan='3' style='border-right:1 solid'><br><br> (Construction Manager)</td>");
			$mpdf->WriteHTML("</tr>");
			
	$mpdf->WriteHTML("</tbody>");
	$mpdf->WriteHTML("</table>");
	
		
	$mpdf->WriteHTML('</html>');	
	$mpdf->Output("grn.pdf",'I');
	ob_end_flush();
	unset($mpdf);
	
	die;
?>	
               