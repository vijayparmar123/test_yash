.<?php
	error_reporting(0);

$created_by = isset($erp_grn_details['created_by'])?$this->ERPfunction->get_user_name($erp_grn_details['created_by']):'NA';
$last_edit = isset($erp_grn_details['last_edit'])?date("m-d-Y H:i:s",strtotime($erp_grn_details['last_edit'])):'NA';
$last_edit_by = isset($erp_grn_details['last_edit_by'])?$this->ERPfunction->get_user_name($erp_grn_details['last_edit_by']):'NA';


	ob_clean();
	header('Content-type: application/pdf');
	header('Content-Disposition: inline; filename="expence_list.pdf"');
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
					color : grey;
					border:1;
					border-color :solid gray;
					border-collapse:collapse;
				}
				td{
					border-top :0;
					border-right :1 solid;
					border-bottom :1 solid;
					border-left :0;
					border-color : grey;
					padding:7px 0;
				}
				strong{
					color :#333;
				}	
				#left
				{
				}
				#right
				{
					font-size:20px;
					color:grey;
					height:100px;
					
				}
				#left , #right
				{
					float:left;
				}
				#address
				{
					border-bottom:1 solid grey;
					text-align:center;
					font-size:20px;
					color:grey;
					padding:5px 0;
				}
				#cv , #dt
				{
					float:left;
					color:grey;
					font-size:15px;
					padding:5px 0;
				}
				#dt
				{
					margin-left:253px;
				}
				#debit
				{
					color:grey;
				}
				#debit , #debit_name
				{
					float:left;
					font-size:15px;
				}
				#debit_name
				{
					color:grey;
				}
				#f_left , #f_mid , #f_right
				{
					float:left;
					color:grey;
					font-size:15px;
					padding:5px 0;
				}
				</style>');
	$mpdf->WriteHTML('</head>');
	$mpdf->WriteHTML('<body>');	
	// $mpdf->WriteHTML("<img style='margin-top:-30px' height=30% border='0' src='".WWW_ROOT ."img/logo/header.jpg'/>");
	$mpdf->WriteHTML("<div style='width:100%;border-bottom:1 solid grey'><div id='left' style='width:80%;'>{$this->ERPfunction->viewheader_pdf($erp_expence_list['date'])}</div><div id='right' style='width:20%;'><div style='height:50px;'></div><div>PAYMENT VOUCHER</div></div></div>");
	$mpdf->WriteHTML("<div width=100% id='address'><span>Reg.Office : 214/5,Khyati Complex,B/h.Thakor Vas,Mithakhali,Ahmedabad-380006.</span></div>");
	$mpdf->WriteHTML("<br>");
	
	$mpdf->WriteHTML("<div style='width:100%;padding:5px 0;'><div width=10% id='debit'>Project :</div><div width=90% id='debit_name' style='border-bottom:1 solid grey;'>{$this->ERPfunction->get_projectname($erp_expence_list['project_id'])}</div></div>");
	
	$mpdf->WriteHTML("<div>");
		$mpdf->WriteHTML("<div style='width:100%;'><div id='cv' style='width:50%;'><span>CV NO : <u>{$erp_expence_list['voucher_no']}</u></span></div><div id='dt' style='width:30%;'><span>DATE : <u>{$this->ERPfunction->get_date($erp_expence_list['date'])}</u></span></div></div>");
		
		$mpdf->WriteHTML("<div style='width:100%;padding:5px 0;'><div width=10% id='debit'>Debit to :</div><div width=90% id='debit_name' style='border-bottom:1 solid grey;'>{$this->ERPfunction->expence_head_name($erp_expence_list['expence_head'])}</div></div>");
		
		$mpdf->WriteHTML("<div style='width:100%;padding:5px 0;'><div width=10% id='debit'>Paid to :</div><div width=90% id='debit_name' style='border-bottom:1 solid grey;'>{$erp_expence_list['given_to']}</div></div>");

	$mpdf->WriteHTML("</div>");
	$mpdf->WriteHTML("<br>");
	$mpdf->WriteHTML("<div style='width:100%;padding:5px 0;border-bottom:1 solid grey'></div>");
	$mpdf->WriteHTML("<br>");
	$mpdf->WriteHTML("<table width=100% border=1>");
	$mpdf->WriteHTML("<tbody>");
		// $mpdf->WriteHTML("<tr><td colspan='8' align='center'><h1><b>YashNand Engineers & Contractors</b></h1></td></tr>");
		// $mpdf->WriteHTML("<tr><td colspan='8' align='center'><h2><b>Expence</b></h2></td></tr>");
		// $mpdf->WriteHTML("<tr>");
			////$mpdf->WriteHTML("<td colspan='2'><b>Project Code : </b>{$this->ERPfunction->get_projectcode($erp_grn_details['project_id'])}</td>");
			// $mpdf->WriteHTML("<td colspan='4'><b>Project Name: </b></td><td colspan='4'>{$this->ERPfunction->get_projectname($erp_expence_list['project_id'])}</td>");
			// $mpdf->WriteHTML("</tr>");
	
			// $mpdf->WriteHTML("<tr>");
				// $mpdf->WriteHTML("<td><b>Account Name:</b></td><td colspan='3'>{$this->ERPfunction->account_name($erp_expence_list['account_id'])}</td>");
				// $mpdf->WriteHTML("<td colspan='1'><b>Date:</b></td><td colspan='3'>{$this->ERPfunction->get_date($erp_expence_list['date'])}</td>");
				////$mpdf->WriteHTML("<td colspan='1' ><b>Time:</b> </td><td colspan='2' > {$erp_grn_details['grn_time']}</td>");
			// $mpdf->WriteHTML("</tr>");
	
			// $mpdf->WriteHTML("<tr>");
				// $mpdf->WriteHTML("<td><b>Account No:</b></td><td colspan='3'>{$erp_expence_list['account_no']}</td>");
				// $mpdf->WriteHTML("<td colspan='1' ><b>Voucher No:</b></td><td colspan='3'>{$erp_expence_list['voucher_no']} </td>");
			// $mpdf->WriteHTML("</tr>");
	
			// $mpdf->WriteHTML("<tr>");
					// $mpdf->WriteHTML("<td><b>Bank:</b></td><td colspan='3' >{$erp_expence_list['bank']}</td>");
					// $mpdf->WriteHTML("<td colspan='1'><b>Expence Head:</b></td><td colspan='3' >{$this->ERPfunction->expence_head_name($erp_expence_list['expence_head'])}</td>");
			// $mpdf->WriteHTML("</tr>");
	
			// $mpdf->WriteHTML("<tr>");
				// $mpdf->WriteHTML("<td><b>Given To:</b></td><td colspan='3' >{$erp_expence_list['given_to']}</td>");
				// $mpdf->WriteHTML("<td colspan='1'><b>Payment:</b></td><td colspan='3'>{$erp_expence_list['payment_type']}</td>");
			// $mpdf->WriteHTML("</tr>");
			
			
			$mpdf->WriteHTML("<tr>");
				//$mpdf->WriteHTML("<td align='center'  ><b>Material Code</b></td>");
				$mpdf->WriteHTML("<td align='center'><b>Sr.No</b></td>");
				$mpdf->WriteHTML("<td align='center' colspan='5'><b>Narration</b></td>");
				//$mpdf->WriteHTML("<td  ></td>");
				$mpdf->WriteHTML("<td colspan='2' align='center'><b>Amount(Rs.)</b></td>");
				//$mpdf->WriteHTML("<td  ></td>");
				//$mpdf->WriteHTML("<td  ><b>Unit</b></td>");
				// $mpdf->WriteHTML("<td rowspan='2' ><b>Remarks by Inspector</b></td>");
			$mpdf->WriteHTML("</tr>");
			
			// $mpdf->WriteHTML("<tr>");
				// $mpdf->WriteHTML("<td colspan='2'><b>Description</b></td>");
				// $mpdf->WriteHTML("<td><b>Make / Source</b></td>");
				
			// $mpdf->WriteHTML("</tr>");
			$i = 0;
			foreach($detail_list as $retrive_material)
			{	
				$i++;
				$mpdf->WriteHTML("<tr>");
					$mpdf->WriteHTML("<td align='center'>{$i}</td>");
					$mpdf->WriteHTML("<td align='center' colspan='5'>{$retrive_material['expence_description']}</td>");
					$mpdf->WriteHTML("<td colspan='2' align='center'>{$retrive_material['expence_amount']}</td>");
					//$mpdf->WriteHTML("<td colspan='1'></td>");
					// $mpdf->WriteHTML("<td>{$this->ERPfunction->get_brandname($retrive_material['brand_id'])}</td>");
					// $mpdf->WriteHTML("<td>{$retrive_material['quantity']}</td>");
					// $mpdf->WriteHTML("<td>{$retrive_material['actual_qty']}</td>");
					// $mpdf->WriteHTML("<td>{$retrive_material['difference_qty']}</td>");
					// $mpdf->WriteHTML("<td>{$this->ERPfunction->get_items_units($retrive_material['material_id'])}</td>");
					// $mpdf->WriteHTML("<td>{$retrive_material['remarks']}</td>");
					 
				$mpdf->WriteHTML("</tr>");
			}
			$mpdf->WriteHTML("<tr>");
			$mpdf->WriteHTML("<td style='border-right:none;'><b>Rupees: </b></td>");
			$i=0;
						$numItems = count($detail_list);
						foreach($detail_list as $retrive_material){
							if(++$i === $numItems) 
							{
				 $mpdf->WriteHTML("<td colspan='4'>{$retrive_material['expence_toatl_word']}</td>");
							}
						}
				 $mpdf->WriteHTML("<td align='center'><b>Total</b></td>");
				 $i=0;
						$numItems = count($detail_list);
						foreach($detail_list as $retrive_material){
							if(++$i === $numItems) 
							{
				 $mpdf->WriteHTML("<td  colspan='2' align='center'>{$retrive_material['expence_total']}</td>");
				 //$mpdf->WriteHTML("<td colspan='1'></td>");
							}
						}
			 $mpdf->WriteHTML("</tr>");
			 
			 // $mpdf->WriteHTML("<tr>");
			// $mpdf->WriteHTML("<td><b>In Words: </b></td>");
			// $i=0;
						// $numItems = count($detail_list);
						// foreach($detail_list as $retrive_material){
							// if(++$i === $numItems) 
							// {
				 // $mpdf->WriteHTML("<td align='center' colspan='7'>{$retrive_material['expence_toatl_word']}</td>");
							// }
						// }
			// $mpdf->WriteHTML("</tr>");
				 
			 //$mpdf->WriteHTML("<tr>");
				// $mpdf->WriteHTML("<td align='center' colspan='4'><br><br><br>");
						// if($erp_grn_details['created_by'])
						// {
							// $mpdf->WriteHTML("{$this->ERPfunction->get_user_name($erp_grn_details['created_by'])}");
						// }
					
				// $mpdf->WriteHTML("<h3><b> Quantity Varified By </b></h3></td>");
				 // $mpdf->WriteHTML("<td align='center' colspan='4'><br><br><br>");
						// $i=0;
						// $numItems = count($detail_list);
						// foreach($detail_list as $retrive_material){
							// if(++$i === $numItems) 
							// {
								// $mpdf->WriteHTML("{$this->ERPfunction->get_user_name($retrive_material['created_by'])}"); 
							// }
						// }
					
				// $mpdf->WriteHTML("<h3><b> Made By </b></h3></td>");
				// $mpdf->WriteHTML("<td align='center' colspan='4'><br><br><br>");
						// $i=0;
						// $numItems = count($detail_list);
						// foreach($detail_list as $retrive_material){
							// if(++$i === $numItems) 
							// {
								// $mpdf->WriteHTML("{$this->ERPfunction->get_user_name($retrive_material['approval_by_cmpdmd'])}"); 
							// }
						// }
				
				// $mpdf->WriteHTML("<h3><b> Approved By </b></h3></td>");
				
			// $mpdf->WriteHTML("</tr>");
			
			
	$mpdf->WriteHTML("</tbody>");
	$mpdf->WriteHTML("</table>");
	$mpdf->WriteHTML("<div style='border-bottom:1 solid grey;height:10px;'></div>");
	$mpdf->WriteHTML("<div style='width:100%;padding:5px 0;'><div id='f_left' style='width:40%;'><span width=30%>Prepared by:</span><span style='border-bottom:1 solid grey' width=70%>{$this->ERPfunction->get_user_name($retrive_material['created_by'])}</span></div><div id='f_mid' style='width:40%;'><span width=30%>Approved by:</span><span style='border-bottom:1 solid grey;' width=70%>{$this->ERPfunction->get_user_name($retrive_material['approval_by_cmpdmd'])}</span></div><div id='f_right' width=20%><div style='width:50%;height:50px;border:1 solid grey;'></div><div width=100%>Received by:</div></div></div>");                                                    
		
	/* $mpdf->WriteHTML("<br>");
	$mpdf->WriteHTML("<hr/>");
	$mpdf->WriteHTML("<br>");
	
	$mpdf->WriteHTML("<table>");
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td style='height:30px' width=50%>Created By : {$created_by} </td>");
	$mpdf->WriteHTML("<td style='height:30px' width=20%></td>");
	$mpdf->WriteHTML("<td style='height:30px' >Last Edited By : {$last_edit_by} </td>");	
	$mpdf->WriteHTML("</tr>");
	$mpdf->WriteHTML("</table>"); */
		
	$mpdf->WriteHTML('</html>');	
	$mpdf->Output();
	ob_end_flush();
	unset($mpdf);
	
	die;
?>	
               