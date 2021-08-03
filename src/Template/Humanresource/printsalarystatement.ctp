<?php 
	
	error_reporting(0);
		ob_clean();
		header('Content-type: application/pdf');
		header('Content-Disposition: inline; filename="paymentsummary.pdf"');
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
					border-collapse:collapsed;
				}
				
				</style>');
		// $mpdf->WriteHTML($this->ERPfunction->viewheader_pdf());
		// $mpdf->WriteHTML("<h3 align=center><u>Payment Summary Report</u></h3>");
		$mpdf->WriteHTML('</head>');
		$mpdf->WriteHTML('<body>');	
		$mpdf->SetTitle('Payment Summary Report');	
		
		$mpdf->WriteHTML($this->ERPfunction->viewheader_pdf("2019-03-01"));
		
		$mpdf->WriteHTML("<hr/>");
		
		$mpdf->WriteHTML('<div class="header" style="font-size:22px;" align=center>
						<span><strong style="color:#449CD6;">Payment Summary Report</strong></span>
					</div>');	
		
		
		$mpdf->WriteHTML("<table width=100%>");
		$mpdf->WriteHTML("<tr align=left>");
		$mpdf->WriteHTML("<td align=left><b>Emp.Code</b> - ".$this->ERPfunction->get_user_pf_ref_no($user_id)."</td>");
		$mpdf->WriteHTML("<td align=right><b>From</b> : ".$from_date." UPTO ".$to_date."</td>");
		$mpdf->WriteHTML("</tr>");
		$mpdf->WriteHTML("<tr align=left>");
		$mpdf->WriteHTML("<td align=left><b>Emp.Name</b> - ".$this->ERPfunction->get_user_name($user_id)."</td>");
		$mpdf->WriteHTML("<td align=right><b>As on Date</b> : ".date("d-m-Y")."</td>");
		$mpdf->WriteHTML("</tr>");
		$mpdf->WriteHTML('</table>');
	
		
		$mpdf->WriteHTML('<center>');
		
		
		$mpdf->WriteHTML('<hr color="black">');		
		$mpdf->WriteHTML('<table border=1 width=100%>');			
		
		$mpdf->WriteHTML("<tr align=center style='background-color:#D99594'>");
		foreach($rows[0] as $head)
		{
			$mpdf->WriteHTML("<th align=left height=25px>{$head}</th>");
		}
		unset($rows[0]);
		$mpdf->WriteHTML("</tr>");
		
		$i = 1;
		foreach($rows as $row)
		{
			$align = "left";
			$bold = "";
			$bolde = "";
			if($i == 12)
			{
				$b_color = "#D99594";
				$bold = "<b>";
				$bolde = "</b>";
			}elseif($i > 12 && $i < 19){
				$b_color = "#FBD4B4";
			}elseif($i == 19){
				$b_color = "#FABF8F";
				$bold = "<b>";
				$bolde = "</b>";
			}elseif($i == 20){
				$b_color = "#C0504D";
				$align = "center";
				$bold = "<b>";
				$bolde = "</b>";
			}else{
				$b_color = "#F2DBDB";
			}
			$mpdf->WriteHTML("<tr align=center  style='background-color:".$b_color.";'>");
			foreach($row as $field)
			{
				$mpdf->WriteHTML("<td align=".$align." height=25px>{$bold}{$field}{$bolde}</td>");
			}
			$mpdf->WriteHTML("</tr>");
			$i++;
		}	
		
		$mpdf->WriteHTML('</table>');
		
		/* $mpdf->WriteHTML('</center>');
		$mpdf->WriteHTML('<br>');
		$mpdf->WriteHTML('<br>');
		$mpdf->WriteHTML('<br>');
		$mpdf->WriteHTML('<br>');
		$mpdf->WriteHTML('<br>');
		$mpdf->WriteHTML('<br>');
		$mpdf->WriteHTML('<br>');
		$mpdf->WriteHTML('<br>');
		$mpdf->WriteHTML('<br>');
		
		$mpdf->WriteHTML('<table width=20%>');
		$mpdf->WriteHTML("<tr align=center>");
		$mpdf->WriteHTML("<td align=center style='border-top:1px solid #333;'><p align=center>HR Manager<br>Authorized Signature</p>");
		$mpdf->WriteHTML("</tr>");
		$mpdf->WriteHTML('</table>'); */
		
		$mpdf->WriteHTML("<br/><br/><br/><br/><br/><br/>");
		$mpdf->WriteHTML('<div><div style="float:left;width:30%;">			
			<h2><span class="sign" style="border-top:1px solid">HR Manager</span></h2>
		</div>
		<div style="float:right;width:40%;text-align:right;">
			<h2><span class="sign" style="border-top:1px solid;">Authorized Signature</span></h2>
		</div>
	');
	
		$mpdf->WriteHTML("<hr/>");
		
		/* Footer Start */
		$mpdf->WriteHTML('<table width=100% style="font-size:12.50px;font-family: sans-serif;">');
		$mpdf->WriteHTML("<tr align=center>");
		$mpdf->WriteHTML("<td align=center><b>Registered Office</b> : 214/5, Khyati Complex, Near Mithakhali Underbridge, Ellisbridge, Ahmedabad, Gujarat - 380006.</td>");
		$mpdf->WriteHTML("</tr>");
		$mpdf->WriteHTML("<tr align=center>");
		$mpdf->WriteHTML("<td align=center><b>Corporate Office</b> : Plot No: 1003, Opp. Sarita Udhyan Gate, Near Samarpan College, Sector - 8 / D, Gandhinagar, Gujarat - 382007.</td>");
		$mpdf->WriteHTML("</tr>");
		$mpdf->WriteHTML("<tr align=center>");
		$mpdf->WriteHTML("<td align=center><b>Phone</b> : (0) 079-23244186 / 23240202 | <b>E-mail</b> : info@yashnandeng.com | <b>Website</b> : www.yashnandeng.com</td>");
		$mpdf->WriteHTML("</tr>");
		$mpdf->WriteHTML('</table>');
		/* Footer End */
		$mpdf->WriteHTML('</body>');
		$mpdf->WriteHTML('</html>');
		
		$mpdf->Output('SalaryStatement.pdf','D');
		ob_end_flush();
		unset($mpdf);

		exit();

?>

