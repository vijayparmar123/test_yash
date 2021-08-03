<?php 
	
	error_reporting(0);
		ob_clean();
		header('Content-type: application/pdf');
		header('Content-Disposition: inline; filename="account_list.pdf"');
		header('Content-Transfer-Encoding: binary');
		header('Accept-Ranges: bytes');
		
		/* $mpdf	=	new mPDF('+aCJK'); */
		$mpdf	=	new mPDF('c','A4','','' , 5 , 5 , 0 , 0 , 0 , 0);
		
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
				td{
					border-top :0;
					border-right :1 solid;
					border-bottom :1 solid;
					border-left :1 solid;
					border-color : #dedede;
					padding : 8px;
				}
				strong{
					color :#333;
				}
				</style>');
		$mpdf->WriteHTML('</head>');
		$mpdf->WriteHTML('<body>');	
		$mpdf->WriteHTML($this->ERPfunction->viewheader_pdf());
		
		$mpdf->WriteHTML('<table width=100%>');
		$mpdf->WriteHTML("<tr align=left>");
		$mpdf->WriteHTML("<td align=left><b>Bill Records</b></td>");
		$mpdf->WriteHTML("<td align=right><b>Date:".date("Y-m-d")."</b></td>");
		$mpdf->WriteHTML("</tr>");
		$mpdf->WriteHTML('</table>');
	
		
		$mpdf->WriteHTML('<center>');
		
		
		$mpdf->WriteHTML('<hr color="black">');		
		$mpdf->WriteHTML('<table border=1>');			
				
		unset($rows[0]);
		/* $header = array("Project Code","Inward Bill No","Inward Bill Type","Date","Total Amount","Pay Status","Bill Date","Credit Period","Remaining Days","Partys Name"); */
		$header = array("Project Name","Inward Bill No","Inward Date","Party's Name","Invoice No","Bill Date","Bill Amount","Credit Period","Type of Bill","Pay Status");
					
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

