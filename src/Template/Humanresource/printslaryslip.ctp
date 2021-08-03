<?php
use Cake\Routing\Router;
error_reporting(0);
?>
 
	
<?php 

$employee_id=isset($employee_data['employee_id'])?$employee_data['employee_id']:'';
$full_name=isset($employee_data['full_name'])?$employee_data['full_name']:'';
$date_of_issue=isset($employee_data['date_of_issue'])?$this->ERPfunction->get_date($employee_data['date_of_issue']):date('d-m-Y');
$designation=isset($employee_data['designation'])?$employee_data['designation']:'';
$employee_at=isset($employee_data['employee_at'])?$employee_data['employee_at']:'';
$epf_no=isset($employee_data['epf_no'])?$employee_data['epf_no']:'';
$esi_no=isset($employee_data['esi_no'])?$employee_data['esi_no']:'';
$date_of_birth=isset($employee_data['date_of_birth'])?$this->ERPfunction->get_date($employee_data['date_of_birth']):'';
$date_of_joining=isset($employee_data['date_of_joining'])?$this->ERPfunction->get_date($employee_data['date_of_joining']):'';
$payable_days=isset($employee_data['payable_days'])?$employee_data['payable_days']:'';
$cons_pay=isset($employee_data['cons_pay'])?$employee_data['cons_pay']:'';
$pay_rate=isset($employee_data['pay_rate'])?$employee_data['pay_rate']:'';
$pancard_no=isset($employee_data['pancard_no'])?$employee_data['pancard_no']:'';
$payment=isset($employee_data['payment'])?$employee_data['payment']:'';
$da_rate=isset($employee_data['da_rate'])?$employee_data['da_rate']:'';
//$da_rate=isset($employee_data['da_rate'])?$employee_data['da_rate']:'';
$pay_wa_1 = (isset($employee_data['pay_wa']) && $employee_data['pay_wa'][0] != "") ? $employee_data['pay_wa'][0] : "0";
$pay_wa_2 = (isset($employee_data['pay_wa']) && $employee_data['pay_wa'][1] != "") ? $employee_data['pay_wa'][1] : "0";
$da_sp_1 = (isset($employee_data['da_sp']) && $employee_data['da_sp'][0] != "") ? $employee_data['pay_wa'][0] : "0";
$da_sp_2 = (isset($employee_data['da_sp']) && $employee_data['da_sp'][1] != "") ? $employee_data['pay_wa'][1] : "0";
$hra_all_1 = (isset($employee_data['hra_all']) && $employee_data['hra_all'][0] != "") ? $employee_data['hra_all'][0] : "0";
$hra_all_2 = (isset($employee_data['hra_all']) && $employee_data['hra_all'][1] != "") ? $employee_data['hra_all'][1] : "0";
$conva_ta_1 = (isset($employee_data['conva_ta']) && $employee_data['conva_ta'][0] != "") ? $employee_data['conva_ta'][0] : "0";
$conva_ta_2 = (isset($employee_data['conva_ta']) && $employee_data['conva_ta'][1] != "") ? $employee_data['conva_ta'][1] : "0";
$total_1 = (isset($employee_data['total']) && $employee_data['total'][0] != "") ? $employee_data['total'][0] : "0";
$total_2 = (isset($employee_data['total']) && $employee_data['total'][1] != "") ? $employee_data['total'][1] : "0";
$pf_advance_1 = (isset($employee_data['pf_advance']) && $employee_data['pf_advance'][0] != "") ? $employee_data['pf_advance'][0] : "0";
$pf_advance_2 = (isset($employee_data['pf_advance']) && $employee_data['pf_advance'][1] != "") ? $employee_data['pf_advance'][1] : "0";
$esi_glwf_1 = (isset($employee_data['esi_glwf']) && $employee_data['esi_glwf'][0] != "") ? $employee_data['esi_glwf'][0] : "0";
$esi_glwf_2 = (isset($employee_data['esi_glwf']) && $employee_data['esi_glwf'][1] != "") ? $employee_data['esi_glwf'][1] : "0";
$pt_1 = (isset($employee_data['pt']) && $employee_data['pt'][0] != "") ? $employee_data['pt'][0] : "0";
$pt_2 = (isset($employee_data['pt']) && $employee_data['pt'][1] != "") ? $employee_data['pt'][1] : "0";
$ln_1 = (isset($employee_data['ln']) && $employee_data['ln'][0] != "") ? $employee_data['ln'][0] : "0";
$ln_2 = (isset($employee_data['ln']) && $employee_data['ln'][1] != "") ? $employee_data['ln'][1] : "0";
$total_deduction_row_1 = (isset($employee_data['total_deduction_row']) && $employee_data['total_deduction_row'][0] != "") ? $employee_data['total_deduction_row'][0] : "0";
$total_deduction_row_2 = (isset($employee_data['total_deduction_row']) && $employee_data['total_deduction_row'][1] != "") ? $employee_data['total_deduction_row'][1] : "0";
$total_earning = (isset($employee_data['total_earning']) && $employee_data['total_earning'] != "") ? $employee_data['total_earning'] : "0.00";
$total_deduction = (isset($employee_data['total_deduction']) && $employee_data['total_deduction'] != "") ? $employee_data['total_deduction'] : "0.00";
$net_pay = (isset($employee_data['net_pay']) && $employee_data['net_pay'] != "") ? $employee_data['net_pay'] : "0.00";
$uan = (isset($employee_data['uan']) && $employee_data['uan'] != "") ? $employee_data['uan'] : "";
?>

<?php 	
	error_reporting(0);
	ob_clean();
	header('Content-type: application/pdf');
	header('Content-Disposition: inline; filename="ra_bills.pdf"');
	header('Content-Transfer-Encoding: binary');
	header('Accept-Ranges: bytes');
	
	$mpdf	=	new mPDF('c','A4','','' , 5 , 5 , 10 , 0 , 0 , 0);
	ob_start();
	// $mpdf->WriteHTML("<style> td{border:1px solid red;}</style>");
	
	$mpdf	=	new mPDF('c','A4','','' , 5 , 5 , 10 , 0 , 0 , 0);
	$mpdf->WriteHTML('<html>');
	$mpdf->WriteHTML('<head>');
	$mpdf->WriteHTML('</head>');
	$mpdf->WriteHTML('<body>');	
	$mpdf->SetTitle('Salary Slip');
	
	$mpdf->WriteHTML('<table width=100%>');
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td align=center><h3>YashNand Engineers & Contractors</h3>");	
	$mpdf->WriteHTML("</tr>");
	$mpdf->WriteHTML("<tr>");	
	$mpdf->WriteHTML("<td align=center><strong>Address : </strong>  214/5, Khyati Complex, Near Mithakhali Underbridge, Ellisbridge, Ahmedabad.</td>");	
	$mpdf->WriteHTML("</tr>");
	$mpdf->WriteHTML('</table>');
	
	$mpdf->WriteHTML('<hr/>');
	
	$mpdf->WriteHTML('<table width=100%>');
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td width=100px>Full Name :</td><td align=left> {$full_name}</td>");
	$mpdf->WriteHTML("<td width=120px>Employee No. :</td><td align=left> {$employee_id}</td>");
	$mpdf->WriteHTML("<td width=120px>Date of Issue :</td><td align=left> {$date_of_issue}</td>");
	$mpdf->WriteHTML("</tr>");	
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td>Designation :</td><td align=left> {$designation}</td>");
	$mpdf->WriteHTML("<td>Employee_at :</td><td align=left> {$employee_at}</td>");
	$mpdf->WriteHTML("<td>E. P. F. No :</td><td align=left> {$epf_no}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td>ESI Number :</td><td align=left> {$esi_no}</td>");
	$mpdf->WriteHTML("<td>Date of Birth :</td><td align=left> {$date_of_birth}</td>");
	$mpdf->WriteHTML("<td>Date of Joining :</td><td align=left> {$date_of_joining}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td>Payable Days :</td><td align=left> {$payable_days}</td>");
	$mpdf->WriteHTML("<td>Cons. Pay :</td><td align=left> {$cons_pay}</td>");
	$mpdf->WriteHTML("<td>Pay Rate :</td><td align=left> {$pay_rate}</td>");
	$mpdf->WriteHTML("</tr>");	
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td>PAN Card No :</td><td align=left> {$pancard_no}</td>");
	$mpdf->WriteHTML("<td>Payment :</td><td align=left> {$payment}</td>");
	$mpdf->WriteHTML("<td>DA Rate :</td><td align=left> {$da_rate}</td>");
	$mpdf->WriteHTML("</tr>");	
	$mpdf->WriteHTML('</table>');
	
	$mpdf->WriteHTML('<hr/>');
	
	$mpdf->WriteHTML('<table>');
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td width=350>");
	$mpdf->WriteHTML('<table border=1 style="border-collapse:collapse;">');
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td height=90 >PAY WA & LA ALL - 6</td>");
	$mpdf->WriteHTML("<td>DA SP. ALL. ALL - 7</td>");
	$mpdf->WriteHTML("<td>HRA ALL - 5 OTH. ALL.</td>");
	$mpdf->WriteHTML("<td>CONV TA</th>");
	$mpdf->WriteHTML("<td>TOTAL EARNINGS</td>");
	$mpdf->WriteHTML("</tr>");
		
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td>{$pay_wa_1}</td>");
	$mpdf->WriteHTML("<td>{$da_sp_1}</td>");
	$mpdf->WriteHTML("<td>{$hra_all_1}</td>");
	$mpdf->WriteHTML("<td>{$conva_ta_1}</td>");
	$mpdf->WriteHTML("<td>{$total_1}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td>{$pay_wa_2}</td>");
	$mpdf->WriteHTML("<td>{$da_sp_2}</td>");
	$mpdf->WriteHTML("<td>{$hra_all_2}</td>");
	$mpdf->WriteHTML("<td>{$conva_ta_2}</td>");
	$mpdf->WriteHTML("<td>{$total_2}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan=5 align=right>TOTAL EARNING : {$total_earning} </td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan=5 align=right>&nbsp;</td>");
	$mpdf->WriteHTML("</tr>");
	
	
	$mpdf->WriteHTML('</table>');	
	$mpdf->WriteHTML("</td>");	
	
	$mpdf->WriteHTML("<td>");	
	$mpdf->WriteHTML('<table border=1 style="border-collapse:collapse;">');
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td height=90>P.F. ADVANCE</td>");
	$mpdf->WriteHTML("<td>ESI GLWF</td>");
	$mpdf->WriteHTML("<td>PT, I. TAX</td>");
	$mpdf->WriteHTML("<td>Ln/Oth/FD /Tr/Adv</td>");
	$mpdf->WriteHTML("<td>TOTAL DEDUCTION</td>");
	$mpdf->WriteHTML("</tr>");	
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td>{$pf_advance_1}</td>");
	$mpdf->WriteHTML("<td>{$esi_glwf_1}</td>");
	$mpdf->WriteHTML("<td>{$pt_1}</td>");
	$mpdf->WriteHTML("<td>{$ln_1}</td>");
	$mpdf->WriteHTML("<td>{$total_deduction_row_1}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td>{$pf_advance_2}</td>");
	$mpdf->WriteHTML("<td>{$esi_glwf_2}</td>");
	$mpdf->WriteHTML("<td>{$pt_2}</td>");
	$mpdf->WriteHTML("<td>{$ln_2}</td>");
	$mpdf->WriteHTML("<td>{$total_deduction_row_2}</td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan=5 align=right>TOTAL DEDUCTION : {$total_deduction} </td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML("<tr>");
	$mpdf->WriteHTML("<td colspan=5 align=right>NET PAY : {$net_pay} </td>");
	$mpdf->WriteHTML("</tr>");
	
	$mpdf->WriteHTML('</table>');	
	$mpdf->WriteHTML("</td>");
	
	$mpdf->WriteHTML("</tr>");	
	$mpdf->WriteHTML('</table>');
?>


<?php 
	$mpdf->Output();
	ob_end_flush();
	unset($mpdf);

	exit();
?>

<?php die; ?>