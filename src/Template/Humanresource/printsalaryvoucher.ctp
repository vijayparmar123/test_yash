<?php
	error_reporting(0);

$created_by = isset($data['created_by'])?$this->ERPfunction->get_user_name($data['created_by']):'NA';
$last_edit = isset($data['updated_date'])?date("m-d-Y H:i:s",strtotime($data['updated_date'])):'NA';
$last_edit_by = isset($data['updated_by'])?$this->ERPfunction->get_user_name($data['updated_by']):'NA';

$salary_date = "{$year}-{$month}-01";
$salary_date = date("Y-m-d",strtotime($salary_date));
$dateObj = DateTime::createFromFormat('!m', $month);
$monthName = $dateObj->format('F');


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
	$mpdf->WriteHTML("<div style='width:100%;border-bottom:1 solid grey'><div id='left' style='width:80%;'>{$this->ERPfunction->viewheader_pdf($data['created_date'])}</div><div id='right' style='width:20%;'><div style='height:50px;'></div><div>PAYMENT VOUCHER</div></div></div>");
	$mpdf->WriteHTML("<div width=100% id='address'><span>Reg.Office : 214/5,Khyati Complex,B/h.Thakor Vas,Mithakhali,Ahmedabad-380006.</span></div>");
	$mpdf->WriteHTML("<br>");
	
	$mpdf->WriteHTML("<div style='width:100%;padding:5px 0;'><div width=10% id='debit'>Project :</div><div width=90% id='debit_name' style='border-bottom:1 solid grey;'>{$this->ERPFunction->get_user_employee_at($user_id)}</div></div>");
	
	$mpdf->WriteHTML("<div>");
		$mpdf->WriteHTML("<div style='width:100%;'><div id='cv' style='width:50%;'><span>CV NO : <u>{$month}-{$year}</u></span></div><div id='dt' style='width:30%;'><span>DATE : <u>{$this->ERPfunction->get_date($data['created_date'])}</u></span></div></div>");
		
		$mpdf->WriteHTML("<div style='width:100%;padding:5px 0;'><div width=10% id='debit'>Debit to :</div><div width=90% id='debit_name' style='border-bottom:1 solid grey;'>Conveyance</div></div>");
		
		$mpdf->WriteHTML("<div style='width:100%;padding:5px 0;'><div width=10% id='debit'>Paid to :</div><div width=90% id='debit_name' style='border-bottom:1 solid grey;'>{$this->ERPFunction->get_user_bank_name($user_id)}</div></div>");

	$mpdf->WriteHTML("</div>");
	$mpdf->WriteHTML("<br>");
	$mpdf->WriteHTML("<div style='width:100%;padding:5px 0;border-bottom:1 solid grey'></div>");
	$mpdf->WriteHTML("<br>");
	################################ Table ######################################################
	$mpdf->WriteHTML("<table width=100% border=1>");
	$mpdf->WriteHTML("<tbody>");
			
	$mpdf->WriteHTML("<tr>");
		$mpdf->WriteHTML("<td align='center'><b>Sr.No</b></td>");
		$mpdf->WriteHTML("<td align='center' colspan='5'><b>Narration</b></td>");
		$mpdf->WriteHTML("<td colspan='2' align='center'><b>Amount(Rs.)</b></td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
		$mpdf->WriteHTML("<td align='center'><b>1</b></td>");
		$mpdf->WriteHTML("<td align='center' colspan='5'><b>Conveyance Paid towards service given as {$this->ERPfunction->get_user_designation($user_id)} for {$monthName}-".date('Y',strtotime($salary_date))."</b></td>");
		$mpdf->WriteHTML("<td colspan='2' align='center'><b>".round(($data["payable_days"] / $total_days) * $data["basic_salary"])."</b></td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
		$mpdf->WriteHTML("<td align='center'><b>2</b></td>");
		$mpdf->WriteHTML("<td align='center' colspan='5'><b>Advance</b></td>");
		$mpdf->WriteHTML("<td colspan='2' align='center'><b>{$data['loan_payment']}</b></td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
		$mpdf->WriteHTML("<td align='center'><b>3</b></td>");
		$mpdf->WriteHTML("<td align='center' colspan='5'><b>Other Deductions</b></td>");
		$mpdf->WriteHTML("<td colspan='2' align='center'><b>{$data['others']}</b></td>");
	$mpdf->WriteHTML("</tr>");
			
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td align='center' style='border-right:none;'></td>");
	$mpdf->WriteHTML("<td align='right' colspan='5' style='border-left:none;'><b>Total</b></td>");
	$mpdf->WriteHTML("<td colspan='2' align='center'><b>{$data["net_pay"]}</b></td>");
	
	$mpdf->WriteHTML("</tr>");
			 
			
			
	$mpdf->WriteHTML("</tbody>");
	$mpdf->WriteHTML("</table>");
	################################ Table ######################################################
	
	$mpdf->WriteHTML("<div style='border-bottom:1 solid grey;height:10px;'></div>");
	$mpdf->WriteHTML("<div style='width:100%;padding:5px 0;'><div id='f_left' style='width:40%;'><span width=30%>Prepared by:</span><span style='border-bottom:1 solid grey' width=70%>{$this->ERPfunction->get_user_name($data['created_by'])}</span></div><div id='f_mid' style='width:40%;'><span width=30%>Approved by:</span><span style='border-bottom:1 solid grey;' width=70%>{$this->ERPfunction->get_user_name($data['approved_by'])}</span></div><div id='f_right' width=20%><div style='width:50%;height:50px;border:1 solid grey;'></div><div width=100%>Received by:</div></div></div>");                                                    
		
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
               