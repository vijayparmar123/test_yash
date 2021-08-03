<?php
use Cake\Routing\Router;
error_reporting(0);
$curr_date = "{$data['year']}-{$data['month']}-01";
$curr_date = date("Y-m-d",strtotime($curr_date));
?>
			
<?php
ob_clean();
	header('Content-type: application/pdf');
	header('Content-Disposition: inline; filename="print_is.pdf"');
	header('Content-Transfer-Encoding: binary');
	header('Accept-Ranges: bytes');
	
	/* $mpdf	=	new mPDF('+aCJK'); */
	$mpdf	=	new mPDF('c','A4','','' , 5 , 5 , 0 , 0 , 0 , 0);
	
	
	$mpdf->WriteHTML('<html>');
	$mpdf->WriteHTML('<head>');
	$mpdf->WriteHTML('<style>
				table, .header,span.sign{
					font-family: sans-serif;
					font-size : 12px;	
					color : #444;
				}
				.count td, .count th{
				 
				 border-bottom : 1px solid #d5d5d5;
				 height:40px;
				}
				
				
				#t1{					
					border :0;
					border-color :gray;
					border-collapse:collapse;
				}
				#t1 td{
					/* border-top :0;
					border-right :1 solid;
					border-bottom :1 solid;
					border-left :0;
					border-color : #dedede; */
					padding : 6px;
				}
				strong{
					color :#333;
				}
				</style>');
	$mpdf->WriteHTML('</head>');
	$mpdf->WriteHTML('<body>');			
	$mpdf->WriteHTML($this->ERPfunction->viewheader_pdf());
	
	$dateObj = DateTime::createFromFormat('!m', $data["month"]);
	$monthName = $dateObj->format('F'); 
	
	$mpdf->WriteHTML('<div class="header" style="font-size:22px;" align=center>
					<span><strong style="color:#449CD6;">Pay Slip for '.$monthName ."-". date('y',strtotime($curr_date)).'</strong></span>
				</div>');	
	$mpdf->WriteHTML("<hr/>");	
	$mpdf->WriteHTML("<table id='t1' width=100%  >");
	$mpdf->WriteHTML("<tbody>");
	
	$mpdf->WriteHTML("<tr>");
	// $mpdf->WriteHTML("<td><strong>Employee No :</strong> {$this->ERPfunction->get_employee_no($data["user_id"])}</td>");
	$mpdf->WriteHTML("<td><strong>Employee No :</strong> {$this->ERPFunction->get_user_identy_number($data["user_id"])}</td>");
	$mpdf->WriteHTML("<td><strong>Full Name :</strong> {$this->ERPfunction->get_full_user_name($data["user_id"])}</td>");
	$mpdf->WriteHTML("<td><strong>Date of Issue :</strong> ".date("d-m-y",strtotime($data["created_date"]))."</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td><strong>Employee At :</strong> {$this->ERPfunction->get_projectname($data["employee_at"])}</td>");
	$mpdf->WriteHTML("<td><strong>Designation :</strong> {$this->ERPfunction->get_category_title($data["designation"])}</td>");
	// $mpdf->WriteHTML("<td><strong>Date of Joining :</strong> ".date("d-m-y",strtotime($data["date_of_joining"]))."</td>");
	$mpdf->WriteHTML("<td><strong>Pay Type :</strong> ".$this->ERPFunction->get_pay_type($data["pay_type"])."</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	//$mpdf->WriteHTML("<td><strong>E. P. F. No :</strong> {$data["epf_no"]}</td>");
	// $mpdf->WriteHTML("<td><strong>Payable Days :</strong> {$data["payable_days"]}</td>");
	$mpdf->WriteHTML("<td><strong>Aadhar Card No :</strong> {$data["adhaar_card_no"]}</td>");
	$mpdf->WriteHTML("<td><strong>PAN Card No :</strong> {$data["pan_card_no"]}</td>");
	$mpdf->WriteHTML("<td><strong>Date of Birth :</strong>".date("d-m-y",strtotime($this->ERPfunction->get_user_birthdate($data["user_id"])))."</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	//$mpdf->WriteHTML("<td><strong>ESI Number  :</strong> {$data["esi_no"]}</td>");
	$mpdf->WriteHTML("<td><strong>Payable Days :</strong> {$data["payable_days"]}</td>");
	$mpdf->WriteHTML("<td><strong>Total No. of Days :</strong> {$data["total_days"]}</td>");
	// $mpdf->WriteHTML("<td><strong>C.T.C(Year) :</strong> {$this->ERPFunction->get_user_ctc_year($data["user_id"])}</td>");
	$mpdf->WriteHTML("</tr>");
	$is_epf = $this->ERPfunction->get_user_is_epf($data["user_id"]);
	$is_esi = $this->ERPfunction->get_user_is_esi($data["user_id"]);
	
	if($is_epf != 'no' || $is_esi != 'no')
	{
		$mpdf->WriteHTML("<tr>");
		if($is_epf != 'no')
		{
		$mpdf->WriteHTML("<td><strong>EPF No :</strong> {$this->ERPfunction->get_user_epf_no($data["user_id"])}</td>");
		$mpdf->WriteHTML("<td><strong>UAN No :</strong> {$this->ERPfunction->get_user_uan_no($data["user_id"])}</td>");
		}
		if($is_esi != 'no')
		{
		$mpdf->WriteHTML("<td><strong>ESI No :</strong> {$this->ERPfunction->get_user_esi_no($data["user_id"])}</td>");
		}
		$mpdf->WriteHTML("</tr>");
	}
	
	// $mpdf->WriteHTML("<tr>");
	// $mpdf->WriteHTML("<td><strong>PF Slip Ref. No. :</strong> {$this->ERPfunction->get_user_pf_ref_no($data["user_id"])}</td>");
	// $mpdf->WriteHTML("</tr>");
	
	// $mpdf->WriteHTML("<tr>");
	// $mpdf->WriteHTML("<td><strong>A/C No.  :</strong> {$data["ac_no"]}</td>");
	// $mpdf->WriteHTML("<td><strong>Bank :</strong> {$data["bank"]}</td>");
	// $mpdf->WriteHTML("</tr>");
	
	// $mpdf->WriteHTML("<tr>");
	// $mpdf->WriteHTML("<td><strong>IFSC Code  :</strong> {$data["ifsc_code"]}</td>");
	// $mpdf->WriteHTML("<td colspan=2><strong>Branch :</strong> {$data["branch"]}</td>");	
	// $mpdf->WriteHTML("</tr>");
	$mpdf->WriteHTML("</tbody>");
	$mpdf->WriteHTML("</table>");
	$mpdf->WriteHTML("<hr/>");	
	
	$display_epf = ($is_epf != 'no')?"":"style='display:none;'";
	$display_esi = ($is_esi != 'no')?"":"style='display:none;'";
	
	// $padding_label = if($is_epf != 'no' && $is_esi != 'no'){ echo "padding-bottom:182px";}else if($is_epf == 'no' && $is_esi == 'no'){ echo "padding-bottom: 262px";}else if($is_epf != 'no' && $is_esi == 'no'){ echo "padding-bottom: 222px";}else{echo "padding-bottom: 222px";};
	
	// $padding_val = if($is_epf != 'no' && $is_esi != 'no'){ echo "padding-bottom:182px";}else if($is_epf == 'no' && $is_esi == 'no'){ echo "padding-bottom: 262px";}else if($is_epf != 'no' && $is_esi == 'no'){ echo "padding-bottom: 222px";}else{echo "padding-bottom: 222px";};
	
	
	$mpdf->WriteHTML('<div style="float:left; width: 50%;">
	<table width=100% class="count" style="background:#F2DBDB">
		<thead>
			<tr>
				<th colspan=4 style="background: #D99594;border-bottom:0">
					EARNINGS
				</th>
			</tr>
			<tr>
				<th align=left>Salary Head</th>
				<th></th>
				<th></th>
				<th width="95px" align=right>Amount (Rs.)</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>Basic Salary</td>
				<td></td>
				<td></td>
				<td align="center" width="95px">'.$data["basic_pay_amount"].'</td>
			</tr>
			<tr>
				<td>Dearness Allowance (D.A.)</td>
				<td></td>
				<td></td>
				<td align="center" >'.$data["da_amount"].'</td>
			</tr>
			<tr>
				<td>House Rent Allowance (H.R.A.)</td>
				<td></td>
				<td></td>
				<td align="center" id="acco_allowance_amt">'.$data["acco_amount"].'</td>
			</tr>
			<tr>
				<td>Conveyance Allowance</td>
				<td></td>
				<td></td>
				<td align="center" >'.$data["hra_amount"].'</td>
			</tr>
			<tr>
				<td>Transportation Allowance (T.A.)</td>
				<td></td>
				<td></td>
				<td align="center" id="trans_allowance_amount">'.$data["transport_amount"].'</td>
			</tr>
			<tr>
				<td>Special Allowance</td>
				<td></td>
				<td></td>
				<td align="center" id="food_allowance_amount">'.$data["food_amount"].'</td>
			</tr>
			<tr>
				<td>Medical Allowance</td>
				<td></td>
				<td></td>
				<td align="center" >'.$data["medical_amount"].'</td>
			</tr>
			<tr>
				<td>Mobile Allowance</td>	
				<td></td>
				<td></td>
				<td align="center" id="mobile_allowance_amt">'.$data["mobile_amount"].'</td>
			</tr>
			<tr>
			<td style="border-bottom:0">Other Allowance</td>
				<td></td>
				<td></td>
				<td align="center" style="padding-right: 0px;border-bottom:0">'.$data["other_allowance_amount"].'</td>
			</tr>
			<tr>
			<td style="border-bottom:0">Perfomance Incentives</td>
				<td></td>
				<td></td>
				<td align="center" style="padding-right: 0px;border-bottom:0">'.$data["incentive_amount"].'</td>
			</tr>
			<tr>
			<td style="border-bottom:0">Salary Difference</td>
				<td></td>
				<td></td>
				<td align="center" style="padding-right: 0px;border-bottom:0">'.$data["salary_diff_amount"].'</td>
			</tr>
			</tbody>
			<tfoot>
			<tr>
				<td colspan=3 style="background: #D99594;border-bottom:0">					
					<strong>TOTAL EARNINGS <span style="float:right" id="total_earning"></span></strong>
				</td>
				<td align="center" style="background: #D99594;border-bottom:0"><strong>'.$data["total_earning"].'</strong></td>				
			</tr>
			</tfoot>
			</table>
		</div>
		
		<div style="float: right; width: 50%;">
		<div style="border-left:1px solid #dedede">
			<table width=100% class="count" style="background:#FBD4B4">
			<thead>
				<tr style="background:#FABF8F">
					<th colspan=4 style="border-bottom:0">DEDUCTIONS</th>					
				</tr>
				<tr>
					<th align="left">Salary Head</th>					
					<th></th>
					<th></th>
					<th align="center">Amount(Rs.)</th>
				</tr>
			</thead>
			<tbody>
				<tr >
					<td colspan=2>Professional Tax</td>
					<td></td>
					<td align="center">
						'.$data["pro_tax"].'
					</td>
				</tr>'.(($is_epf != 'no')?'				
				<tr>
					<td colspan=2>Employee Provident Fund (E.P.F.)</td>
					<td></td>
					<td align="center">
						'.$data["epf"].'
					</td>
				</tr>':'').''.(($is_esi != 'no')?'
				<tr>
					<td colspan=2>Employee State Insurance (E.S.I.)</td>
					<td></td>
					<td align="center">
						'.$data["esi"].'
					</td>
				</tr>':'').'
				<tr>
					<td colspan=2>Loan Repayment / Advance</td>
					<td></td>
					<td align="center">
						'.$data["loan_payment"].'
					</td>
				</tr>
				<tr>
					<td>Mobile Bill Recovery</td>
					'. (($data["mobile_cug"] != 0) ? '<td colspan=2 align="left" style="background:#FABF8F;"><span class="label label-danger"> Limit : '.$data["mobile_cug"].'</span></td>' : '<td colspan=2></td>' ) .'
					<td align="center">
						'.$data["mobile_bill_recovery"].'
					</td>
				</tr>
				<tr>
					<td colspan=2>Tax Deducted at Source (T.D.S.)</td>
					<td></td>
					<td align="center">
						'.$data["tax_deducted_source"].'
					</td>
				</tr>'.(($is_epf != 'no' && $is_esi != 'no')?'
				<tr>
					<td colspan=2 style="padding-bottom: 182px;border-bottom:0">Others</td>
					<td style="border-bottom:0"></td>
					<td align="center" style="padding-bottom: 182px;border-bottom:0">
						'.$data["others"].'
					</td>
				</tr>':(($is_epf == 'no' && $is_esi == 'no')?'
				<tr>
					<td colspan=2 style="padding-bottom: 262px;border-bottom:0">Others</td>
					<td style="border-bottom:0"></td>
					<td align="center" style="padding-bottom: 262px;border-bottom:0">
						'.$data["others"].'
					</td>
				</tr>
				':'
				<tr>
					<td colspan=2 style="padding-bottom: 222px;border-bottom:0">Others</td>
					<td style="border-bottom:0"></td>
					<td align="center" style="padding-bottom: 222px;border-bottom:0">
						'.$data["others"].'
					</td>
				</tr>
				')).'							
			</tbody>
			<tfoot>
				<tr>
					<th align="left" colspan=3 style="background:#FABF8F;border-bottom:0">TOTAL DEDUCTIONS</th>
					<th style="background:#FABF8F;"><span style="float:right" id="net_pay">'.$data["total_deduction"].'</span></th>
				</tr>				
			</tfoot>
			</table>		
		  </div>	
		 <table width=100% class="count">
			<tfoot>
			<tr>
				<th align="left" style="background:#ffac30;">NET PAY</th>
				<th style="background:#ffac30;width:86px">'.$data["net_pay"].'</th>
			</tr>
			</tfoot>
		</table>
		</div>');
	$mpdf->WriteHTML("<br/><br/><br/>");
	$mpdf->WriteHTML('<div><div style="float:left;width:30%;">			
			<h2><span class="sign" style="border-top:1px solid">HR Manager</span></h2>
		</div>
		<div style="float:right;width:40%;text-align:right;">
			<h2><span class="sign" style="border-top:1px solid;">Authorized Signature</span></h2>
		</div>
	');
	
	
	$mpdf->WriteHTML("</body>");
	$mpdf->WriteHTML("</html>");



	$mpdf->Output();
	ob_end_flush();
	unset($mpdf);

	exit();
	die;



?>
