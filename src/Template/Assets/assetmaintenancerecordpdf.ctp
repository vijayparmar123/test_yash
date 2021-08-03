<?php 
	
	error_reporting(0);
		ob_clean();
		header('Content-type: application/pdf');
		header('Content-Disposition: inline; filename="AssetMaintenance.pdf"');
		header('Content-Transfer-Encoding: binary');
		header('Accept-Ranges: bytes');
		
		/* $mpdf	=	new mPDF('+aCJK'); */
		$mpdf	=	new mPDF('c','A4','','' , 5 , 5 , 10 , 0 , 0 , 0);
		
		$mpdf->WriteHTML('<html>');
		$mpdf->WriteHTML('<head>');
		$mpdf->WriteHTML('</head>');
		$mpdf->WriteHTML('<body>');	
		$mpdf->SetTitle('Asset Maintenance Records');	
		
		
		
		
		$mpdf->WriteHTML('<table width=100%>');
		$mpdf->WriteHTML("<tr align=left>");
		$mpdf->WriteHTML("<td align=left><b>Asset Maintenance Records</b></td>");
		$mpdf->WriteHTML("<td align=right><b>Date:".date("d-m-Y")."</b></td>");
		$mpdf->WriteHTML("</tr>");
		$mpdf->WriteHTML('</table>');
	
		
		$mpdf->WriteHTML('<center>');
		
		
		$mpdf->WriteHTML('<hr color="black">');		
		$mpdf->WriteHTML('<table border=1>');			
				
		unset($rows[0]);
		$header = array("Project Name","Date","A.M.O No.","Asset Group","Asset ID","Asset Name","Capacity","Identity/Vehi.No.","Maintenance Type","Amount of Expense","Payment");
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

