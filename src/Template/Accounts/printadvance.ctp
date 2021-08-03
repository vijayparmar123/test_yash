.<?php
	error_reporting(0);

// $created_by = isset($erp_grn_details['created_by'])?$this->ERPfunction->get_user_name($erp_grn_details['created_by']):'NA';
// $last_edit = isset($erp_grn_details['last_edit'])?date("m-d-Y H:i:s",strtotime($erp_grn_details['last_edit'])):'NA';
// $last_edit_by = isset($erp_grn_details['last_edit_by'])?$this->ERPfunction->get_user_name($erp_grn_details['last_edit_by']):'NA';


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
					color : #333333;
					border:1;
					border-color :solid #333333;
					border-collapse:collapse;
				}
				td{
					border-top :0;
					border-right :1 solid;
					border-bottom :1 solid;
					border-left :0;
					border-color : #333333;
					padding:7px 0;
				}
				strong{
					color :#333333;
				}	
				#left
				{
				}
				#right
				{
					font-size:20px;
					color:#333333;
					height:100px;
					
				}
				#left , #right
				{
					float:left;
				}
				#address
				{
					border-bottom:1 solid #333333;
					text-align:center;
					font-size:20px;
					color:#333333;
					padding:5px 0;
				}
				#cv , #dt
				{
					float:left;
					color:#333333;
					font-size:15px;
					padding:5px 0;
				}
				#dt
				{
					margin-left:253px;
				}
				#debit
				{
					color:#333333;
				}
				#debit , #debit_name
				{
					float:left;
					font-size:15px;
				}
				#debit_name
				{
					color:#333333;
				}
				#f_left , #f_mid , #f_right
				{
					float:left;
					color:#333333;
					font-size:15px;
					padding:5px 0;
				}
				</style>');
	$mpdf->WriteHTML('</head>');
	$mpdf->WriteHTML('<body>');	
	// $mpdf->WriteHTML("<img style='margin-top:-30px' height=30% border='0' src='".WWW_ROOT ."img/logo/header.jpg'/>");
	$mpdf->WriteHTML("<div style='width:100%;border-bottom:1 solid #333333'><div id='left' style='width:100%;'>{$this->ERPfunction->viewheader_pdf($request_list['date'])}</div></div>");
	$mpdf->WriteHTML("<br>");
	
	$mpdf->WriteHTML("<div style='width:100%;padding:5px 0;'><div width=10% id='debit'>Project :</div><div width=90% id='debit_name' style='border-bottom:1 solid #333333;'>{$this->ERPfunction->get_projectname($request_list['project_id'])}</div></div>");
	
	$mpdf->WriteHTML("<div>");
		$mpdf->WriteHTML("<div style='width:100%;'><div id='cv' style='width:50%;'><span>Project Code : <u>{$this->ERPfunction->get_project_code($request_list['project_id'])}</u></span></div><div id='dt' style='width:30%;'><span>DATE : <u>{$this->ERPfunction->get_date($request_list['date'])}</u></span></div></div>");
		
		$mpdf->WriteHTML("<div style='width:100%;'><div id='cv' style='width:50%;'><span>Adv.R.No  : <u>{$request_list['advance_req_no']}</u></span></div><div id='dt' style='width:30%;'><span>Time : <u>".date('H:i',strtotime($request_list['time']))."</u></span></div></div>");
		
		
	$mpdf->WriteHTML("</div>");
	$mpdf->WriteHTML("<br>");
	$mpdf->WriteHTML("<div style='width:100%;padding:5px 0;border-bottom:1 solid #333333'></div>");
	$mpdf->WriteHTML("<br>");
	$mpdf->WriteHTML("<table width=100% border=1>");
	$mpdf->WriteHTML("<tbody>");
			
			
			$mpdf->WriteHTML("<tr>");
				$mpdf->WriteHTML("<td align='center' width=120px><b>Agency Id</b></td>");
				$mpdf->WriteHTML("<td align='center'><b>Agency Name</b></td>");
				$mpdf->WriteHTML("<td align='center' width=100px><b>No. of Labours on Site</b></td>");
				$mpdf->WriteHTML("<td align='center' width=120px><b>Advance (Rs.)</b></td>");
				$mpdf->WriteHTML("<td align='center' width=120px><b>TDS (Rs.)</b></td>");
				$mpdf->WriteHTML("<td align='center' width=120px><b>Net Amount (Rs.)</b></td>");
				
			$mpdf->WriteHTML("</tr>");
			
			
			
			foreach($detail_data as $req_data)
			{	
				$tds = $req_data['advance_rs'] * 1 /100;
				$net = $req_data['advance_rs'] - $tds;
				$mpdf->WriteHTML("<tr>");
					$mpdf->WriteHTML("<td align='center'>{$this->ERPfunction->get_agency_code($req_data['agency_id'])}</td>");
					$mpdf->WriteHTML("<td align='center'>{$this->ERPfunction->get_agency_name($req_data['agency_id'])}</td>");
					$mpdf->WriteHTML("<td align='center'>{$req_data['labor']}</td>");
					$mpdf->WriteHTML("<td align='center'>{$req_data['advance_rs']}</td>");					 
					$mpdf->WriteHTML("<td align='center'>{$tds}</td>");					 
					$mpdf->WriteHTML("<td align='center'>{$net}</td>");					 
				$mpdf->WriteHTML("</tr>");
			}
			
			 
			
			
	$mpdf->WriteHTML("</tbody>");
	$mpdf->WriteHTML("</table>");
	
	$mpdf->WriteHTML("<div style='border-bottom:1 solid grey;height:10px;'></div>");
	$mpdf->WriteHTML("<div style='width:100%;padding:5px 0;'><div id='f_left' style='width:50%;'><span width=30%>Made by:    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style='border-bottom:1 solid grey' width=100%>{$this->ERPfunction->get_user_name($request_list['created_by'])}</span></div><div id='f_mid' style='width:40%;'><span width=30%>Approved by:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style='border-bottom:1 solid grey;' width=70%>{$this->ERPfunction->get_user_name($detail_data[0]['approval_export_by'])}</span></div></div>");                                
		
	$mpdf->WriteHTML('</html>');	
	$mpdf->Output();
	ob_end_flush();
	unset($mpdf);
	
	die;
?>	
               