<?php 
	
	error_reporting(0);
		ob_clean();
		header('Content-type: application/pdf');
		header('Content-Disposition: inline; filename="inward_list.pdf"');
		header('Content-Transfer-Encoding: binary');
		header('Accept-Ranges: bytes');
		
		/* $mpdf	=	new mPDF('+aCJK'); */
		$mpdf	=	new mPDF('c','A4','','' , 5 , 5 , 0 , 0 , 0 , 0);
		
		$mpdf->WriteHTML('<html>');
		$mpdf->WriteHTML('<head>');		
		/*$mpdf->WriteHTML('<style>
				table{
				 font-family: sans-serif;									 
				}
			#maintbl td{
				border-right :1 solid;
				border-bottom :1 solid;
				border-top :0;
				border-left :0;			
			}
		</style>');		*/
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
					border-right :1 solid;
					border-bottom :1 solid;
					border-top :0;
					border-left :0;
					border-color : #777;
					padding:8px;
				}
				
				</style>');
		$mpdf->WriteHTML('</head>');
		$mpdf->WriteHTML('<body>');	
		$mpdf->WriteHTML($this->ERPfunction->viewheader_pdf());
		$mpdf->WriteHTML('<hr color="black">');	
		$mpdf->SetTitle('Approved P.R. List');	
		$mpdf->WriteHTML('<table width=100%>');
		$mpdf->WriteHTML("<tr align=left>");
		$mpdf->WriteHTML("<td align=left style='border-right:0'><b>Approved P.R. List</b></td>");
		$mpdf->WriteHTML("<td align=right><b>Date:".date("Y-m-d")."</b></td>");
		$mpdf->WriteHTML("</tr>");
		$mpdf->WriteHTML('</table>');
		$mpdf->WriteHTML('<center>');
		
		$mpdf->WriteHTML('<hr color="black">');	
		
		$mpdf->WriteHTML('<table id="maintbl" border=0>');			
				
		unset($rows[0]);
		$header = array("#ID","Project Code","P.R. NO.","Date Time","Material Code","Material Name","Make/Source","Quanity","Unit","Mode of Purchase","Delivery/Date");
			
		$mpdf->WriteHTML("<tr align=center>");
		foreach($header as $head)
		{
			$mpdf->WriteHTML("<th align=center style='border-bottom:1px solid'>{$head}</th>");
		}
		$mpdf->WriteHTML("</tr>");
		$i =1;
		foreach($rows as $row)
		{
			$mpdf->WriteHTML("<tr align=center>");
			$mpdf->WriteHTML("<td align=center>{$i}</td>");
			foreach($row as $field)
			{				
				$mpdf->WriteHTML("<td align=center>{$field}</td>");
			}
			$mpdf->WriteHTML("</tr>");
			$i++;
		}	
		
		$mpdf->WriteHTML('</table>');
		
		$mpdf->WriteHTML('</center>');
		$mpdf->WriteHTML('</html>');
		
		$mpdf->Output();
		ob_end_flush();
		unset($mpdf);

		exit();

?>

