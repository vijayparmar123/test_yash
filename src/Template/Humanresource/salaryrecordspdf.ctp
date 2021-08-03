<?php 
	
	error_reporting(0);
		ob_clean();
		header('Content-type: application/pdf');
		header('Content-Disposition: inline; filename="salary_records.pdf"');
		header('Content-Transfer-Encoding: binary');
		header('Accept-Ranges: bytes');
		
		/* $mpdf	=	new mPDF('+aCJK'); */
		$mpdf	=	new mPDF('c','A4','','' , 5 , 5 , 10 , 0 , 0 , 0);
		
		$mpdf->WriteHTML('<html>');
		$mpdf->WriteHTML('<head>');
			$mpdf->WriteHTML('<style>
				table, .header,span.sign{
					font-family: sans-serif;
					font-size : 12px;	
					color : #444;
				}
				.count td, .count th{
				 padding : 6px;	
				 border-bottom : 1px solid #d5d5d5;
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
					padding : 8px;
				}
				strong{
					color :#333;
				}
				</style>');
		$mpdf->WriteHTML('</head>');
		$mpdf->WriteHTML('<body>');	
		$mpdf->WriteHTML($this->ERPfunction->viewheader_pdf());
		$mpdf->SetTitle('Salary Records List');	
		
	
		$mpdf->WriteHTML('<center>');
		
		
		$mpdf->WriteHTML('<hr color="black">');		
		$mpdf->WriteHTML('<table border=1>');			
				
		unset($rows[0]);
		$header = array("Employee No","Employee Name","Designation","Pay Type","Employed at","Month & Year","Payable Days","CTC(Month)(Rs.)","NetPay(Rs.)","A/C No.","Bank","Branch","IFSC Code");
		
		$mpdf->WriteHTML("<tr align=center>");
		foreach($header as $head)
		{
			$mpdf->WriteHTML("<th align=center>{$head}</th>");
		}
		$mpdf->WriteHTML("</tr>");
		
		foreach($rows as $row)
		{
			$mpdf->WriteHTML("<tr align=center>");
			foreach($row as $field)
			{
				$mpdf->WriteHTML("<td align=center>{$field}</td>");
			}
			$mpdf->WriteHTML("</tr>");
		}	
		
		$mpdf->WriteHTML('</table>');
		
		$mpdf->WriteHTML('</center>');
		$mpdf->WriteHTML('</html>');
		
		$mpdf->Output();
ob_end_flush();
unset($mpdf);

exit();

?>

