<?php
	error_reporting(0);
	

	
	ob_clean();
	header('Content-type: application/pdf');
	header('Content-Disposition: inline; filename="payhistory.pdf"');
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
	$mpdf->WriteHTML("<td colspan='13' align='center'><h2><strong><u>Pay Structure History</u></strong></u></h3></td>");
	$mpdf->WriteHTML("</tr>");
	
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td align='center'><strong>Sr.No </strong></td>");
	$mpdf->WriteHTML("<td align='center'><strong>Aff. Date</strong></td>");
	$mpdf->WriteHTML("<td align='center'><strong>Pay Type</strong></td>");
	$mpdf->WriteHTML("<td align='center'><strong>CTC Month</strong></td>");
	$mpdf->WriteHTML("<td align='center'><strong>CTC Year</strong></td>");
	$mpdf->WriteHTML("<td align='center'><strong>Account No.</strong></td>");
	$mpdf->WriteHTML("</tr>");
	
	$i = 1;
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td align='center'>{$i}</td>");
	if($user_data["is_pay_structure_change"] == 1)
	{
		$mpdf->WriteHTML("<td align='center'>{$user_data['change_date']->format('d-m-Y')}</td>");
	}
	else
	{
		$mpdf->WriteHTML("<td align='center'>{$user_data['date_of_joining']->format('d-m-Y')}</td>");
	}
	// $mpdf->WriteHTML("<td align='center'>{$this->ERPfunction->get_category_title($user_data['designation'])}</td>");
	$mpdf->WriteHTML("<td align='center'>{$user_data['pay_type']}</td>");
	$mpdf->WriteHTML("<td align='center'>{$user_data['total_salary']}</td>");
	$mpdf->WriteHTML("<td align='center'>{$user_data['ctc']}</td>");
	$mpdf->WriteHTML("<td align='center'>{$user_data['ac_no']}</td>");
	$mpdf->WriteHTML("</tr>");
			
	
	
	if(!empty($history))
	{
		foreach($history as $data)
		{
			$date = ($data['old_date'] != '')?$data['old_date']->format('d-m-Y'):$data['old_date'];
			$i++;
			$mpdf->WriteHTML("<tr>");
			$mpdf->WriteHTML("<td align='center'>{$i}</td>");
			$mpdf->WriteHTML("<td align='center'>{$date}</td>");
			// $mpdf->WriteHTML("<td align='center'>{$this->ERPfunction->get_category_title($data['designation'])}</td>");
			$mpdf->WriteHTML("<td align='center'>{$data['pay_type']}</td>");
			$mpdf->WriteHTML("<td align='center'>{$data['total_salary']}</td>");
			$mpdf->WriteHTML("<td align='center'>{$data['ctc']}</td>");
			$mpdf->WriteHTML("<td align='center'>{$data['ac_no']}</td>");
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