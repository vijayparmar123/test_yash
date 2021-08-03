<?php
	error_reporting(0);
	

	
	ob_clean();
	header('Content-type: application/pdf');
	header('Content-Disposition: inline; filename="deploymenthistory.pdf"');
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
	$mpdf->WriteHTML("<td colspan='13' align='center'><h2><strong><u>Transfer History</u></strong></u></h3></td>");
	$mpdf->WriteHTML("</tr>");
	
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td align='center' width=150px><strong>Sr.No </strong></td>");
	$mpdf->WriteHTML("<td align='center' width=450px><strong>Project Name</strong></td>");
	$mpdf->WriteHTML("<td align='center' width=200px><strong>Transfer Date</strong></td>");
	$mpdf->WriteHTML("</tr>");
	
	$i = 1;
	foreach($user_data as $urow)
	{
			$mpdf->WriteHTML("<tr>");
			$mpdf->WriteHTML("<td align='center'>{$i}</td>");
			$mpdf->WriteHTML("<td align='center'>{$this->ERPfunction->get_projectname($first_project)}</td>");
			$mpdf->WriteHTML("<td align='center'>{$urow['date_of_joining']->format('d-m-Y')}</td>");
			$mpdf->WriteHTML("</tr>");
			$i++;
	}
	
	if(!empty($data))
	{
		foreach($data as $row)
		{
			$transfer_date = date('d-m-Y',strtotime($row['transfer_date']));
			$mpdf->WriteHTML("<tr>");
			$mpdf->WriteHTML("<td align='center'>{$i}</td>");
			$mpdf->WriteHTML("<td align='center'>{$this->ERPfunction->get_projectname($row['new_project'])}</td>");
			$mpdf->WriteHTML("<td align='center'>{$transfer_date}</td>");
			$mpdf->WriteHTML("</tr>");
			$i++;
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