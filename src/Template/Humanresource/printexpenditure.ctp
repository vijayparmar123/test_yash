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
	$mpdf->WriteHTML("<td colspan='13' align='center'><h2><strong><u>Expenditure History</u></strong></u></h3></td>");
	$mpdf->WriteHTML("</tr>");
	
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td align='center'><strong>Clam Period</strong></td>");
	$mpdf->WriteHTML("<td align='center'><strong>Travel Charge</strong></td>");
	$mpdf->WriteHTML("<td align='center'><strong>House Charge</strong></td>");
	$mpdf->WriteHTML("<td align='center'><strong>Mobile Charge</strong></td>");
	$mpdf->WriteHTML("<td align='center'><strong>Food Charge</strong></td>");
	$mpdf->WriteHTML("<td align='center'><strong>Other Charge.</strong></td>");
	$mpdf->WriteHTML("<td align='center'><strong>Total Amount.</strong></td>");
	$mpdf->WriteHTML("</tr>");
	
	
			"<td>{$row['clam_period']}</td>
					<td>{$row['travel_charge']}</td>
					<td>{$row['house_charge']}</td>
					<td>{$row['mobile_charge']}</td>
					<td>{$row['food_charge']}</td>
					<td>{$row['other_charge']}</td>
					<td>{$row['total_amount']}</td>";
	
	
	if(!empty($data))
	{
		foreach($data as $row)
		{
			
			$mpdf->WriteHTML("<tr>");
			$mpdf->WriteHTML("<td align='center'>{$row['clam_period']}</td>");
			$mpdf->WriteHTML("<td align='center'>{$row['travel_charge']}</td>");
			// $mpdf->WriteHTML("<td align='center'>{$this->ERPfunction->get_category_title($data['designation'])}</td>");
			$mpdf->WriteHTML("<td align='center'>{$row['house_charge']}</td>");
			$mpdf->WriteHTML("<td align='center'>{$row['mobile_charge']}</td>");
			$mpdf->WriteHTML("<td align='center'>{$row['food_charge']}</td>");
			$mpdf->WriteHTML("<td align='center'>{$row['other_charge']}</td>");
			$mpdf->WriteHTML("<td align='center'>{$row['total_amount']}</td>");
			
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