
<?php 
	
	error_reporting(0);
		ob_clean();
		header('Content-type: application/pdf');
		header('Content-Disposition: inline; filename="po_records.pdf"');
		header('Content-Transfer-Encoding: binary');
		header('Accept-Ranges: bytes');
		
		$mpdf	=	new mPDF('c','A4','','' , 5 , 5 , 0 , 0 , 0 , 0);
		
		$mpdf->WriteHTML('<html>');
		$mpdf->WriteHTML('<head>');
		$mpdf->WriteHTML('<style>
			table{
					font-family: sans-serif;
					font-size : 12px;	
					color : #333;
					border :1;
					border-color :gray;
					border-collapse:collapse;
				}
				td{
					border-top :0;
					border-right :1 solid;
					border-bottom :1 solid;
					border-left :0;
					border-color : #dedede;
					padding : 8px;
				}
				strong{
					color :#333;
				}		
				</style>');
		$mpdf->WriteHTML('</head>');
		$mpdf->WriteHTML('<body>');	
		$mpdf->SetTitle('PO Records');	
		$mpdf->WriteHTML($this->ERPfunction->viewheader_pdf());
	
		$mpdf->WriteHTML('<table width=100%>');
		$mpdf->WriteHTML("<tr align=left>");
		$mpdf->WriteHTML("<td align=left style='border-right:0'><b>PO Records List</b></td>");
		$mpdf->WriteHTML("<td align=right><b>Date:".date("Y-m-d")."</b></td>");
		$mpdf->WriteHTML("</tr>");
		$mpdf->WriteHTML('</table>');
	
		
		// $mpdf->WriteHTML('<center>');
		
		
		$mpdf->WriteHTML('<hr color="#dedede">');		
		$mpdf->WriteHTML('<table border=1  width=100% >');			
				
		unset($rows[0]);
		$header = array("P.O. No","P.O.Date","Project Name","Vendor Name","Material Name","Make/Source","Quantity","Unit","PO Type");
		
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
				$mpdf->WriteHTML("<td align=center style='border-color:black;'>{$field}</td>");
			}
			$mpdf->WriteHTML("</tr>");
		}	
		
		$mpdf->WriteHTML('</table>');
		
		// $mpdf->WriteHTML('</center>');
		$mpdf->WriteHTML('</body>');
		$mpdf->WriteHTML('</html>');
		
		$mpdf->Output();
		ob_end_flush();
		unset($mpdf);

		exit();

?>

