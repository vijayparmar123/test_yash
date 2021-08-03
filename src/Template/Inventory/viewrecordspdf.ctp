<?php 
	
	error_reporting(0);
		ob_clean();
		header('Content-type: application/pdf');
		header('Content-Disposition: inline; filename="viewrecords.pdf"');
		header('Content-Transfer-Encoding: binary');
		header('Accept-Ranges: bytes');
		
		/* $mpdf	=	new mPDF('+aCJK'); */
		$mpdf	=	new mPDF('c','A4','','' , 5 , 5 , 0 , 0 , 0 , 0);
		
		$mpdf->WriteHTML('<html>');
		$mpdf->WriteHTML('<head>');		
		$mpdf->WriteHTML('<style>
				table{
						font-family: sans-serif;						
						color : #333;
						border :1;
						border-color :gray;
						border-collapse:collapse;
				}
			#maintbl{
				font-family: sans-serif;
				font-size : 35px;				
			}
			#maintbl td{				
				border-right :1 solid;
				border-bottom :1 solid;
				border-top :0;
				border-left :1 solid;	
				border-color : #dedede;
				padding-top:12px;	
				padding-bottom:12px;	
				
			}
			#maintbl th{				
				border-right :1 solid;
				border-bottom :1 solid;
				border-top :1 solid;
				border-left :1 solid;	
				border-color : #dedede;
				
			}			
		strong{
					color :#333;
				}	
		</style>');	

		$mpdf->WriteHTML('</head>');
		$mpdf->WriteHTML('<body>');	
		$mpdf->WriteHTML($this->ERPfunction->viewheader_pdf());
		$mpdf->WriteHTML('<hr color="black">');	
		$mpdf->SetTitle('View Records');	
		$mpdf->WriteHTML('<table width=100%>');
		$mpdf->WriteHTML("<tr align=left>");
		$mpdf->WriteHTML("<td align=left><b>View Records</b></td>");
		$mpdf->WriteHTML("<td align=right><b>Date:".date("Y-m-d")."</b></td>");
		$mpdf->WriteHTML("</tr>");
		$mpdf->WriteHTML('</table>');
		$mpdf->WriteHTML('<center>');
		
		// $mpdf->WriteHTML('<hr color="black">');	
		$mpdf->WriteHTML('<br>');	
		
		$mpdf->WriteHTML('<table id="maintbl" border=1 >');			
				
		unset($rows[0]);
		$header = array("#","Project Name","Material Code","Material Name",'Consume Type','Cost Group',"Max Purchase Level","Total Stock In","Total Stock Out","Symbolic Balance","Current Balance","Min Stock Level","Unit");
						
		$mpdf->WriteHTML("<tr align=center>");
		foreach($header as $head)
		{
			$mpdf->WriteHTML("<th align=center>{$head}</th>");
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

