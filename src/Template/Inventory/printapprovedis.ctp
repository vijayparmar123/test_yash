<?php
error_reporting(0);

if(!$is_capable)
{
	$this->ERPfunction->access_deniedmsg();
	die;
}

$created_by = isset($data['created_by'])?$this->ERPfunction->get_user_name($data['created_by']):'NA';
$last_edit = isset($data['last_edit'])?date("m-d-Y H:i:s",strtotime($data['last_edit'])):'NA';
$last_edit_by = isset($data['last_edit_by'])?$this->ERPfunction->get_user_name($data['last_edit_by']):'NA';


	ob_clean();
	header('Content-type: application/pdf');
	header('Content-Disposition: inline; filename="print_PR.pdf"');
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
	$mpdf->WriteHTML($this->ERPfunction->viewheader_pdf($data['is_date']));

	    
	$mpdf->WriteHTML("<table width=100%  >");
	$mpdf->WriteHTML("<tbody>");
	$mpdf->WriteHTML("<tr><td colspan='7' align='center' style='border-right:0'><h2><strong color=gray>ISSUE SLIP (I.S.)</strong></h2></td></tr>");
	$mpdf->WriteHTML("<tr>");
			// $mpdf->WriteHTML("<td colspan='2'><strong>Project Code : </strong><strong>{$this->ERPfunction->get_projectcode($data['project_id'])}</strong></td>");
			$mpdf->WriteHTML("<td colspan='7' style='border-right:0;'><strong>Project Name: </strong>{$this->ERPfunction->get_projectname($data['project_id'])}</td>");
	$mpdf->WriteHTML("</tr>");
	
			$mpdf->WriteHTML("<tr>");
				$mpdf->WriteHTML("<td colspan='3'><strong>I. S. No:</strong> {$data['is_no']}</td>");
				$mpdf->WriteHTML("<td align=left><strong>Date:</strong></td>
								<td colspan='3' style='border-right:0'>&nbsp;{$this->ERPfunction->get_date($data['is_date'])}</td>");
			$mpdf->WriteHTML("</tr>");	
			
			$is_asset = explode("_",$data['agency_name']);
			if(isset($is_asset[1]))
			{
				$agency =  $this->ERPfunction->get_asset_name($is_asset[1]);
			}else{
				$agency = $this->ERPfunction->get_vendor_name($data['agency_name']); 
			}
			
			$mpdf->WriteHTML("<tr>");			 
			$mpdf->WriteHTML("<td colspan='7' style='border-right:0;'><strong>Vendor/Asset Name</strong> : {$agency}</td>");
			$mpdf->WriteHTML("</tr>");
			
			$mpdf->WriteHTML("<tr>");
				$mpdf->WriteHTML("<td colspan='7' style='border-right:0;'><strong>The following Material (s) / Item (s) after approval of concerned user / their departments issued.</strong> </td>");
			$mpdf->WriteHTML("</tr>");
	
			$mpdf->WriteHTML("<tr>");
				$mpdf->WriteHTML("<td rowspan='2' width=1 align='center'><strong>Material Code</strong></td>
					<td colspan='4' align='center'><strong>Material / Item</strong></td>");
				$mpdf->WriteHTML("<td rowspan='2' ><strong>Name of Foreman</strong></td>
									<td rowspan='2' style='border-right:0;'><strong>Usage / Remarks</strong></td>");
			$mpdf->WriteHTML("</tr>");
	
			$mpdf->WriteHTML("<tr>");
				$mpdf->WriteHTML("<td colspan='2' width=300 align=center><strong>Description</strong></td>");
				$mpdf->WriteHTML("<td><strong>Quantity</strong></td>
								<td><strong>Unit</strong></td>");
			$mpdf->WriteHTML("</tr>");
			
			
			
				foreach($previw_list as $retrive_material)
				{
					$mpdf->WriteHTML("<tr>");
					$mpdf->WriteHTML("<td>{$this->ERPfunction->get_materialitemcode($retrive_material['material_id'])}</td>");
					$mpdf->WriteHTML("<td colspan='2'>{$this->ERPfunction->get_material_title($retrive_material['material_id'])}</td>");
					$mpdf->WriteHTML("<td>{$retrive_material['quantity']}</td>");
					$mpdf->WriteHTML("<td>{$this->ERPfunction->get_items_units($retrive_material['material_id'])}</td>");
					$mpdf->WriteHTML("<td>{$retrive_material['name_of_foreman']}</td>");
					$mpdf->WriteHTML("<td style='border-right:0;'>{$retrive_material['time_issue']}</td>");
					$mpdf->WriteHTML("</tr>");
				}
			
				
			
			$mpdf->WriteHTML("<tr>");
			$mpdf->WriteHTML("<td colspan='3' align='center'><h3><strong> Made By </strong></h3><br><br><br>({$this->ERPfunction->get_user_name($data['created_by'])})</td>");
			$un = $this->ERPfunction->get_user_name($data['approve_by']);
			$mpdf->WriteHTML("<td style='border-right:0;' colspan='4' align='center'><h3><strong> Approved By </strong></h3><br><br><br>");
			
						$approver = array();
			$ids = array();
						foreach($previw_list as $retrive_material){
							if(!in_array($retrive_material['approved_by'],$ids))
							{
							$approver[] = $this->ERPfunction->get_user_name($retrive_material['approved_by']);
							$ids[] = $retrive_material['approved_by'];
							}
						}
						
						foreach($approver as $app){
							$mpdf->WriteHTML($app . "<br>");
						}
						
			$mpdf->WriteHTML("&nbsp;</td>");
			$mpdf->WriteHTML("</tr>");
			
			// $mpdf->WriteHTML("<tr>");
			// $mpdf->WriteHTML("<td colspan='3' style='border-bottom:0' align='center'>(Material Manager)</td>");
			// $mpdf->WriteHTML("<td colspan='4' style='border-bottom:0;border-right:0;' align='center'>(Site Authority)</td>");
			// $mpdf->WriteHTML("</tr>");
	$mpdf->WriteHTML("</tbody>");
	$mpdf->WriteHTML("</table>");
	
	// $mpdf->WriteHTML("<br>");
	
	// $mpdf->WriteHTML("<br>");
	
/* 	$mpdf->WriteHTML("<table>");
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td style='height:30px' width=50%>Created By : {$created_by} </td>");
	$mpdf->WriteHTML("<td width=20%></td>");
	$mpdf->WriteHTML("<td style='height:30px' >Last Edited By : {$last_edit_by} </td>");	
	$mpdf->WriteHTML("</tr>");
	$mpdf->WriteHTML("</table>"); */
		
	$mpdf->WriteHTML('</html>');	
	$mpdf->Output();
	ob_end_flush();
	unset($mpdf);
	
	die;
?>	
               