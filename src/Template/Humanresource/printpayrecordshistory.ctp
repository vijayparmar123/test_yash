<?php
	error_reporting(0);
	

	
	ob_clean();
	header('Content-type: application/pdf');
	header('Content-Disposition: inline; filename="payrecordshistory.pdf"');
	header('Content-Transfer-Encoding: binary');
	header('Accept-Ranges: bytes');


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
	
		
	
	$mpdf->WriteHTML($this->ERPfunction->viewheader_pdf("2019-03-01"));
	$mpdf->WriteHTML("<strong>Employee Name : {$this->ERPfunction->get_user_name($user_id)}</strong>");
	$mpdf->WriteHTML("<table width=100%  border=1>");
	
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan='13' align='center'><h2><strong><u>Pay Records History</u></strong></u></h3></td>");
	$mpdf->WriteHTML("</tr>");
	
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td align='center'><strong>Employee No</strong></td>");
	$mpdf->WriteHTML("<td align='center'><strong>Employee Name</strong></td>");
	$mpdf->WriteHTML("<td align='center'><strong>Designation</strong></td>");
	$mpdf->WriteHTML("<td align='center'><strong>Pay Type</strong></td>");
	$mpdf->WriteHTML("<td align='center'><strong>Employed at</strong></td>");
	$mpdf->WriteHTML("<td align='center'><strong>Month & Year</strong></td>");
	$mpdf->WriteHTML("<td align='center'><strong>Payable Days</strong></td>");
	$mpdf->WriteHTML("<td align='center'><strong>CTC (Month)(Rs.)</strong></td>");
	$mpdf->WriteHTML("<td align='center'><strong>Net Pay(Rs.)</strong></td>");
	$mpdf->WriteHTML("<td align='center'><strong>A/C No.</strong></td>");
	$mpdf->WriteHTML("<td align='center'><strong>Bank</strong></td>");
	$mpdf->WriteHTML("<td align='center'><strong>Branch</strong></td>");
	$mpdf->WriteHTML("<td align='center'><strong>IFSC Code</strong></td>");
	$mpdf->WriteHTML("</tr>");
	
	
	if(!empty($salary_data))
	{
		foreach($salary_data as $retrive_data)
		{
			$curr_date = "{$retrive_data['year']}-{$retrive_data['month']}-01";
			$curr_date = date("Y-m-d",strtotime($curr_date));
			$ctc_month = $retrive_data["basic_pay_ctc"] + $retrive_data["da_ctc"] + $retrive_data["hra_ctc"] + $retrive_data["medical_ctc"] + $retrive_data["food_ctc"] + $retrive_data["transport_ctc"] + $retrive_data["acco_ctc"] + $retrive_data["mobile_ctc"];
			
			$mpdf->WriteHTML("<tr>");
			$mpdf->WriteHTML("<td align='center'>{$retrive_data["erp_users"]['user_id']}</td>");
			$mpdf->WriteHTML("<td align='center'>".$retrive_data['erp_users']['first_name'] ." ". $retrive_data['erp_users']['last_name']."</td>");
			$mpdf->WriteHTML("<td align='center'>{$this->ERPfunction->get_category_title($retrive_data["erp_users"]['designation'])}</td>");
			$mpdf->WriteHTML("<td align='center'>".ucwords($retrive_data["erp_users"]["pay_type"])."</td>");
			$mpdf->WriteHTML("<td align='center'>{$this->ERPfunction->get_projectname($retrive_data["erp_users"]["employee_at"])}</td>");
			$mpdf->WriteHTML("<td align='center'>".date("M",strtotime($curr_date))."/".date("Y",strtotime($curr_date))."</td>");
			$mpdf->WriteHTML("<td align='center'>{$retrive_data["payable_days"]}</td>");
			$mpdf->WriteHTML("<td align='center'>{$ctc_month}</td>");
			$mpdf->WriteHTML("<td align='center'>{$retrive_data["net_pay"]}</td>");
			$mpdf->WriteHTML("<td align='center'>{$retrive_data['erp_users']["ac_no"]}</td>");
			$mpdf->WriteHTML("<td align='center'>{$retrive_data['erp_users']["bank"]}</td>");
			$mpdf->WriteHTML("<td align='center'>{$retrive_data['erp_users']["branch"]}</td>");
			$mpdf->WriteHTML("<td align='center'>{$retrive_data['erp_users']["ifsc_code"]}</td>");
			$mpdf->WriteHTML("</tr>");
		}
	}
	
	$mpdf->WriteHTML("</table>");
	$mpdf->WriteHTML('</body>');	
	$mpdf->WriteHTML('</html>');	
	$mpdf->Output();
	ob_end_flush();
	unset($mpdf);
	
	die;
?>