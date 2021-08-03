<?php

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Europe/London');

if (PHP_SAPI == 'cli')
	die('This example should only be run from a Web Browser');


$objPHPExcel = new PHPExcel();

// Set document properties

$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
							 ->setLastModifiedBy("Maarten Balliauw")
							 ->setTitle("Office 2007 XLSX Test Document")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Test result file");


for($i=5;$i<=100;$i++)
{
	$objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(20);
}



$objPHPExcel->getActiveSheet()->getStyle('A5:Q100')
    ->getAlignment()
	->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)
    ->setWrapText(true);
	

/* $objPHPExcel->getActiveSheet()->getStyle('A5')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED); */
$objPHPExcel->getActiveSheet()->getStyle('A5')->getFill()
->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
$objPHPExcel->getActiveSheet()->getStyle('C8:L8')->getFill()
->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
$objPHPExcel->getActiveSheet()->getStyle('O8:Q8')->getFill()
->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
$objPHPExcel->getActiveSheet()->getStyle('E9:L9')->getFill()
->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
$objPHPExcel->getActiveSheet()->getStyle('O9:Q9')->getFill()
->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
$objPHPExcel->getActiveSheet()->getStyle('O9:Q9')->getFill()
->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
$objPHPExcel->getActiveSheet()->getStyle('J13:N13')->getFill()
->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');





 $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
 $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(13);
 $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(13);
 $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(13);
 /* $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true); */

$html1 = '<p>
<h1 align="center"><font size="25"><strong>YASH-NAND Engineers & Contractors</strong></font></h1>
<h2><font size="15"><strong><u>Invitation to Quote</u></strong></font></h2>
</p>';
$wizard = new PHPExcel_Helper_HTML;
$richText = $wizard->toRichTextObject($html1);

$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A1', $richText);
$objPHPExcel->getActiveSheet()->mergeCells('A1:Q4');


/* $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(48); */
// $objPHPExcel->getActiveSheet()->getRowDimension(1)->setRowHeight(-1);

$objPHPExcel->getActiveSheet()->getStyle('A1:Q4')
    ->getAlignment()
	->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
	->setVertical(PHPExcel_Style_Alignment::VERTICAL_JUSTIFY)
    ->setWrapText(true);

$html2 = "<strong><u>NOTE: Please Enter Details in Only Yellow Marking.</u></strong>";
$richText = $wizard->toRichTextObject($html2);

$objPHPExcel->getActiveSheet()
    ->setCellValue('A5', $richText);
$objPHPExcel->getActiveSheet()->mergeCells('A5:Q5');
$objPHPExcel->getActiveSheet()->getStyle('A5:Q5')
	->getAlignment()
	->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
	->setVertical(PHPExcel_Style_Alignment::VERTICAL_JUSTIFY)
    ->setWrapText(true);

$objPHPExcel->getActiveSheet()->getStyle('C12')
	->getAlignment()
	->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
	->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)
    ->setWrapText(true);

$html3 = "<strong>Project Code :</strong>";
$objPHPExcel->getActiveSheet()
    ->setCellValue('A6', $wizard->toRichTextObject($html3));
$objPHPExcel->getActiveSheet()->mergeCells('A6:B6');


$html4 = $this->ERPfunction->get_projectcode($pr_data['project_id']);
$objPHPExcel->getActiveSheet()
    ->setCellValue('C6', $wizard->toRichTextObject($html4));
$objPHPExcel->getActiveSheet()->mergeCells('C6:F6');

$html = "<strong>Project Name</strong>";
$objPHPExcel->getActiveSheet()
    ->setCellValue('g6', $wizard->toRichTextObject($html));
$objPHPExcel->getActiveSheet()->mergeCells('G6:H6');


$html = $this->ERPfunction->get_projectname($pr_data['project_id']);
$objPHPExcel->getActiveSheet()
    ->setCellValue('I6', $wizard->toRichTextObject($html));
$objPHPExcel->getActiveSheet()->mergeCells('I6:Q6');


$html = "<strong>P.R. No :</strong>";
$objPHPExcel->getActiveSheet()
    ->setCellValue('A7', $wizard->toRichTextObject($html));
$objPHPExcel->getActiveSheet()->mergeCells('A7:B7');

$html = $pr_data['prno'];
$objPHPExcel->getActiveSheet()
    ->setCellValue('C7', $wizard->toRichTextObject($html));
$objPHPExcel->getActiveSheet()->mergeCells('C7:L7');

$html = "<strong>Date :</strong>";
$objPHPExcel->getActiveSheet()
    ->setCellValue('M7', $wizard->toRichTextObject($html));
$objPHPExcel->getActiveSheet()->mergeCells('M7:N7');

$html = $pr_data['pr_date']->format("d-m-Y");
$objPHPExcel->getActiveSheet()
    ->setCellValue('O7', $wizard->toRichTextObject($html));
// $objPHPExcel->getActiveSheet()->mergeCells('i7:j7');

$html = "<strong>Time :</strong>";
$objPHPExcel->getActiveSheet()
    ->setCellValue('P7', $wizard->toRichTextObject($html));
// $objPHPExcel->getActiveSheet()->mergeCells('h7:i7');

$html = $pr_data['pr_time'];
$objPHPExcel->getActiveSheet()
    ->setCellValue('Q7', $wizard->toRichTextObject($html));
// $objPHPExcel->getActiveSheet()->mergeCells('l7:n7');

$html = "<strong>Vendor Name:</strong>";
$objPHPExcel->getActiveSheet()
    ->setCellValue('A8', $wizard->toRichTextObject($html));
$objPHPExcel->getActiveSheet()->mergeCells('A8:B8');

$html = "";
$objPHPExcel->getActiveSheet()
    ->setCellValue('C8', $wizard->toRichTextObject($html));
$objPHPExcel->getActiveSheet()->mergeCells('C8:L8');

$html = "<strong>Vendor ID:</strong>";
$objPHPExcel->getActiveSheet()
    ->setCellValue('M8', $wizard->toRichTextObject($html));
$objPHPExcel->getActiveSheet()->mergeCells('M8:N8');

$html = "";
$objPHPExcel->getActiveSheet()
    ->setCellValue('O8', $wizard->toRichTextObject($html));
$objPHPExcel->getActiveSheet()->mergeCells('O8:Q8');

$html = "<strong>Vendor's</strong>";
$objPHPExcel->getActiveSheet()
    ->setCellValue('A9', $wizard->toRichTextObject($html));
$objPHPExcel->getActiveSheet()->mergeCells('A9:B9');

$html = "<strong>Mobile No.(1):</strong>";
$objPHPExcel->getActiveSheet()
    ->setCellValue('C9', $wizard->toRichTextObject($html));
$objPHPExcel->getActiveSheet()->mergeCells('C9:D9');

$html = "";
$objPHPExcel->getActiveSheet()
    ->setCellValue('e9', $wizard->toRichTextObject($html));
$objPHPExcel->getActiveSheet()->mergeCells('E9:L9');

$html = "<strong>Mobile No.(2):</strong>";
$objPHPExcel->getActiveSheet()
    ->setCellValue('M9', $wizard->toRichTextObject($html));
$objPHPExcel->getActiveSheet()->mergeCells('M9:N9');

$html = "";
$objPHPExcel->getActiveSheet()
    ->setCellValue('O9', $wizard->toRichTextObject($html));
$objPHPExcel->getActiveSheet()->mergeCells('O9:Q9');

$html = "<strong>Place Of Delivery:</strong>";
$objPHPExcel->getActiveSheet()
    ->setCellValue('A10', $wizard->toRichTextObject($html));
$objPHPExcel->getActiveSheet()->mergeCells('A10:B10');

$html = $this->ERPfunction->get_projectaddress($pr_data['project_id']);
$objPHPExcel->getActiveSheet()
    ->setCellValue('c10', $wizard->toRichTextObject($html));
$objPHPExcel->getActiveSheet()->mergeCells('C10:Q10');
	
$html = "<strong>Contact(1):</strong>";
$objPHPExcel->getActiveSheet()
    ->setCellValue('A11', $wizard->toRichTextObject($html));
$objPHPExcel->getActiveSheet()->mergeCells('A11:B11');

$html = $pr_data['contact_no1'];
$objPHPExcel->getActiveSheet()
    ->setCellValue('C11', $wizard->toRichTextObject($html));
$objPHPExcel->getActiveSheet()->mergeCells('C11:L11');

$html = "<strong>Contact(2):</strong>";
$objPHPExcel->getActiveSheet()
    ->setCellValue('M11', $wizard->toRichTextObject($html));
$objPHPExcel->getActiveSheet()->mergeCells('M11:N11');

$html = $pr_data['contact_no2'];
$objPHPExcel->getActiveSheet()
    ->setCellValue('O11', $wizard->toRichTextObject($html));
$objPHPExcel->getActiveSheet()->mergeCells('O11:P11');


$html = "<strong>Material Code</strong>";
$objPHPExcel->getActiveSheet()
    ->setCellValue('A12', $wizard->toRichTextObject($html));
$objPHPExcel->getActiveSheet()->mergeCells('A12:B13');

$html = "<strong>Material / Item</strong>";
$objPHPExcel->getActiveSheet()
	->setCellValue('C12', $wizard->toRichTextObject($html));
$objPHPExcel->getActiveSheet()->mergeCells('C12:N12');


$html = "<strong>Description</strong>";
$objPHPExcel->getActiveSheet()
	->setCellValue('C13', $wizard->toRichTextObject($html));
$objPHPExcel->getActiveSheet()->mergeCells('C13:F13');

$html = "<strong>Make<br/>/Source</strong>";
$objPHPExcel->getActiveSheet()
	->setCellValue('G13', $wizard->toRichTextObject($html));
	
$html = "<strong>Quantity</strong>";
$objPHPExcel->getActiveSheet()
	->setCellValue('H13', $wizard->toRichTextObject($html));
	
$html = "<strong>Unit</strong>";
$objPHPExcel->getActiveSheet()
	->setCellValue('I13', $wizard->toRichTextObject($html));
	
$html = "<strong>Unit<br/>Rate(Rs.)</strong>";
$objPHPExcel->getActiveSheet()
	->setCellValue('J13', $wizard->toRichTextObject($html));
	
$html = "<strong>Dis.(%)</strong>";
$objPHPExcel->getActiveSheet()
	->setCellValue('K13', $wizard->toRichTextObject($html));
	
$html = "<strong>CGST(%)</strong>";
$objPHPExcel->getActiveSheet()
	->setCellValue('L13', $wizard->toRichTextObject($html));
	
$html = "<strong>SGST(%)</strong>";
$objPHPExcel->getActiveSheet()
	->setCellValue('M13', $wizard->toRichTextObject($html));
	
$html = "<strong>IGST(%)</strong>";
$objPHPExcel->getActiveSheet()
	->setCellValue('N13', $wizard->toRichTextObject($html));
	
$html = "<strong>Amount<br>(Inclusive All)</strong>";
$objPHPExcel->getActiveSheet()
	->setCellValue('O12', $wizard->toRichTextObject($html));
$objPHPExcel->getActiveSheet()->mergeCells('O12:O13');

$html = "<strong>Final Rate<br>(Inclusive All)</strong>";
$objPHPExcel->getActiveSheet()
	->setCellValue('P12', $wizard->toRichTextObject($html));
$objPHPExcel->getActiveSheet()->mergeCells('P12:P13');	

$html = "<strong>Delivery Date<br>(Planned)</strong>";
$objPHPExcel->getActiveSheet()
	->setCellValue('Q12', $wizard->toRichTextObject($html));
$objPHPExcel->getActiveSheet()->mergeCells('Q12:Q13');		
	
$i = 14;
if(!empty($prm_data))
{
	foreach($prm_data as $prm)
	{
		if(is_numeric($prm['material_id']) && $prm['material_id'] != 0)
		{
			$m_code = $this->ERPfunction->get_materialitemcode($prm['material_id']);
			$mt = $this->ERPfunction->get_material_title($prm['material_id']);
			$brnd = $this->ERPfunction->get_brandname($prm['brand_id']);
			$unit = $this->ERPfunction->get_items_units($prm['material_id']);
		}
		else
		{
			$m_code = $prm['m_code'];
			$mt = $prm['material_name'];
			$brnd = $prm['brand_name'];
			$unit = $prm['static_unit'];
		}
						
		$objPHPExcel->getActiveSheet()
		->setCellValue("A{$i}", $wizard->toRichTextObject($m_code));
		$objPHPExcel->getActiveSheet()->mergeCells("A{$i}:B{$i}");	
		
		$objPHPExcel->getActiveSheet()
		->setCellValue("C{$i}", $wizard->toRichTextObject($mt));
		$objPHPExcel->getActiveSheet()->mergeCells("C{$i}:F{$i}");	
		
		$objPHPExcel->getActiveSheet()
		->setCellValue("G{$i}", $wizard->toRichTextObject($brnd));
		
		$objPHPExcel->getActiveSheet()
		->setCellValue("H{$i}", $wizard->toRichTextObject($prm["quantity"]));		
		
		$objPHPExcel->getActiveSheet()
		->setCellValue("I{$i}", $wizard->toRichTextObject($this->ERPfunction->get_items_units($prm["material_id"])));		
		
		$objPHPExcel->getActiveSheet()
		->setCellValue("J{$i}", "");
		
		$objPHPExcel->getActiveSheet()
		->setCellValue("K{$i}", "");
		
		$objPHPExcel->getActiveSheet()
		->setCellValue("L{$i}", "");
		
		$objPHPExcel->getActiveSheet()
		->setCellValue("M{$i}", "");
		
		$objPHPExcel->getActiveSheet()
		->setCellValue("N{$i}", "");
		
		$objPHPExcel->getActiveSheet()
		->setCellValue("O{$i}", "");
		
		$objPHPExcel->getActiveSheet()
		->setCellValue("P{$i}", "");
		
		$objPHPExcel->getActiveSheet()
		->setCellValue("Q{$i}", $wizard->toRichTextObject(date_format($prm['delivery_date'],'d-m-Y')));		
		$i++;
	}	
}

$x = $i - 1 ;

$objPHPExcel->getActiveSheet()->getStyle("J14:N{$x}")->getFill()
->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');

 $styleArray_thin = array(
      'borders' => array(
          'allborders' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN,
			  'color' => array('argb' => '000000')
          )
      )
  );

// $objPHPExcel->getDefaultStyle("A14:Q{$i}")->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle("A12:Q{$x}")->applyFromArray($styleArray_thin);

	
$html = "<strong>Remarks/Note:</strong>";
$objPHPExcel->getActiveSheet()
	->setCellValue("A{$i}", $wizard->toRichTextObject($html));
$j = $i + 5;
$objPHPExcel->getActiveSheet()->mergeCells("A{$i}:B{$j}");		

$html = "1) The above mentioned rate includes following: ";
$objPHPExcel->getActiveSheet()
	->setCellValue("C{$i}", $wizard->toRichTextObject($html));
$objPHPExcel->getActiveSheet()->mergeCells("C{$i}:Q{$i}");		

$i++;
$j = $i+3;
$html = "";
// $objPHPExcel->getActiveSheet()
	// ->setCellValue("C{$i}", "");
	$objPHPExcel->getActiveSheet()->getStyle("c{$i}")->getFill()
->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
$objPHPExcel->getActiveSheet()->getStyle("c{$i}")->applyFromArray($styleArray_thin);
// $objPHPExcel->getActiveSheet()->mergeCells("C{$i}:C{$j}");	


$html = "<strong>All Taxes & Duties</strong>";
$objPHPExcel->getActiveSheet()
	->setCellValue("D{$i}", $wizard->toRichTextObject($html));
$objPHPExcel->getActiveSheet()->mergeCells("D{$i}:Q{$i}");

$objPHPExcel->getActiveSheet()->getStyle("D{$i}")->getFont()->setBold(true);

$i++;
$objPHPExcel->getActiveSheet()->getStyle("c{$i}")->getFill()
->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
$objPHPExcel->getActiveSheet()->getStyle("c{$i}")->applyFromArray($styleArray_thin);
$html = "<strong>Loading & Transportation - F. O. R. at Place of Delivery</strong>";
$objPHPExcel->getActiveSheet()
	->setCellValue("D{$i}", $wizard->toRichTextObject($html));
$objPHPExcel->getActiveSheet()->mergeCells("D{$i}:Q{$i}");

$objPHPExcel->getActiveSheet()->getStyle("D{$i}")->getFont()->setBold(true);

$i++;
$objPHPExcel->getActiveSheet()->getStyle("c{$i}")->getFill()
->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
$objPHPExcel->getActiveSheet()->getStyle("c{$i}")->applyFromArray($styleArray_thin);
$html = "<strong>Unloading</strong>";
$objPHPExcel->getActiveSheet()
	->setCellValue("D{$i}", $wizard->toRichTextObject($html));
$objPHPExcel->getActiveSheet()->mergeCells("D{$i}:Q{$i}");

$i++;
$html = "<strong>Replacement Warrenty up to : </strong>";
$objPHPExcel->getActiveSheet()
	->setCellValue("D{$i}", $wizard->toRichTextObject($html));
$objPHPExcel->getActiveSheet()->mergeCells("D{$i}:G{$i}");

$html = "";
$objPHPExcel->getActiveSheet()
	->setCellValue("H{$i}", $wizard->toRichTextObject($html));
$objPHPExcel->getActiveSheet()->mergeCells("H{$i}:I{$i}");

$objPHPExcel->getActiveSheet()->getStyle("H{$i}:I{$i}")->getFill()
->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');

$html = "";
$objPHPExcel->getActiveSheet()
	->setCellValue("J{$i}", $wizard->toRichTextObject($html));
$objPHPExcel->getActiveSheet()->mergeCells("J{$i}:Q{$i}");
	
$objPHPExcel->getActiveSheet()->getStyle("H{$i}:I{$i}")->applyFromArray($styleArray_thin);
	
// $i++;
// $html = "1.1) Loading & Transportation will be Paid Extra Amount (Rs.): ";
// $objPHPExcel->getActiveSheet()
	// ->setCellValue("C{$i}", $wizard->toRichTextObject($html));
// $objPHPExcel->getActiveSheet()->mergeCells("C{$i}:H{$i}");	

// $i++;
// $html = "2) The above mentioned rate includes Note - 4 f. o. r. above mentioned delivery address.";
// $objPHPExcel->getActiveSheet()
	// ->setCellValue("C{$i}", $wizard->toRichTextObject($html));
// $objPHPExcel->getActiveSheet()->mergeCells("C{$i}:Q{$i}");	

// $i++;
// $html = "3) If material/item will found unsatisfactory after some days of delivery; supplier/party has to replace that.";
// $objPHPExcel->getActiveSheet()
	// ->setCellValue("C{$i}", $wizard->toRichTextObject($html));
// $objPHPExcel->getActiveSheet()->mergeCells("C{$i}:Q{$i}");	

// $i++;
// $html = "4) Manufacturer's Test Certificates are required for each batch of supply.";
// $objPHPExcel->getActiveSheet()
	// ->setCellValue("C{$i}", $wizard->toRichTextObject($html));
// $objPHPExcel->getActiveSheet()->mergeCells("C{$i}:Q{$i}");	

// $i++;
// $html = "5) No Extra Charge will be paid for waiting.";
// $objPHPExcel->getActiveSheet()
	// ->setCellValue("C{$i}", $wizard->toRichTextObject($html));
// $objPHPExcel->getActiveSheet()->mergeCells("C{$i}:Q{$i}");	

// $i++;
// $html = "6) For payment party will have to submit Invoice along with Purchase Order (PO), Gate Pass - Goods Inward, Goods Receipt Note or/and Rejection Memo and/or Weight Pass.";
// $objPHPExcel->getActiveSheet()
	// ->setCellValue("C{$i}", $wizard->toRichTextObject($html));
// $objPHPExcel->getActiveSheet()->mergeCells("C{$i}:Q{$i}");	

// $i++;
// $html = "<strong>Billing Address: 214/5, Khyati Complex, Near Mithakhali Underbridge, Ellisbridge, Ahmedabad - 380006.</strong>";
// $objPHPExcel->getActiveSheet()
	// ->setCellValue("C{$i}", $wizard->toRichTextObject($html));
// $objPHPExcel->getActiveSheet()->mergeCells("C{$i}:Q{$i}");	

// $i++;
// $html = "<strong>Courier Address: Plot No: 1003, Opp. Sarita Udhyan Gate, Near Samarpan College, Sector - 8/D, Gandhinagar, Gujarat - 382007.</strong>";
// $objPHPExcel->getActiveSheet()
	// ->setCellValue("C{$i}", $wizard->toRichTextObject($html));
// $objPHPExcel->getActiveSheet()->mergeCells("C{$i}:Q{$i}");

// $i++;
// $html = "<strong>PAN No.: AAAFY3210E</strong>";
// $objPHPExcel->getActiveSheet()
	// ->setCellValue("C{$i}", $wizard->toRichTextObject($html));
// $objPHPExcel->getActiveSheet()->mergeCells("C{$i}:I{$i}");	

// $html = "<strong>Service Tax No.: AAAFY3210EST001</strong>";
// $objPHPExcel->getActiveSheet()
	// ->setCellValue("J{$i}", $wizard->toRichTextObject($html));
// $objPHPExcel->getActiveSheet()->mergeCells("J{$i}:Q{$i}");

// $i++;
// $html = "<strong>VAT/TIN No.: </strong>";
// $objPHPExcel->getActiveSheet()
	// ->setCellValue("C{$i}", $wizard->toRichTextObject($html));
// $objPHPExcel->getActiveSheet()->mergeCells("C{$i}:D{$i}");

// $html = "";
// $objPHPExcel->getActiveSheet()
	// ->setCellValue("D{$i}", $wizard->toRichTextObject($html));
// $objPHPExcel->getActiveSheet()->mergeCells("D{$i}:H{$i}");

// $objPHPExcel->getActiveSheet()->getStyle("D{$i}:H{$i}")->applyFromArray($styleArray_thin);

// $objPHPExcel->getActiveSheet()->getStyle("D{$i}:H{$i}")->getFill()
// ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');

// $html = "<strong>CST No.: </strong>";
// $objPHPExcel->getActiveSheet()
	// ->setCellValue("I{$i}", $wizard->toRichTextObject($html));
// $objPHPExcel->getActiveSheet()->mergeCells("I{$i}:K{$i}");	

// $html = "";
// $objPHPExcel->getActiveSheet()
	// ->setCellValue("J{$i}", $wizard->toRichTextObject($html));
// $objPHPExcel->getActiveSheet()->mergeCells("J{$i}:O{$i}");

// $objPHPExcel->getActiveSheet()->getStyle("J{$i}:O{$i}")->getFill()
// ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');

// $objPHPExcel->getActiveSheet()->getStyle("J{$i}:O{$i}")->applyFromArray($styleArray_thin);

// $i++;
// $html = "<strong>6) YashNand has right to cancel order anytime.</strong>";
// $objPHPExcel->getActiveSheet()
	// ->setCellValue("C{$i}", $wizard->toRichTextObject($html));
// $objPHPExcel->getActiveSheet()->mergeCells("C{$i}:Q{$i}");	

$i++;
$html = "<strong>2) Payment will be done</strong>";
$objPHPExcel->getActiveSheet()
	->setCellValue("C{$i}", $wizard->toRichTextObject($html));
$objPHPExcel->getActiveSheet()->mergeCells("C{$i}:D{$i}");	


$objPHPExcel->getActiveSheet()->getStyle("E{$i}")->getFill()
->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');

$objPHPExcel->getActiveSheet()->getStyle("E{$i}")->applyFromArray($styleArray_thin);


$html = "<strong>days after date of delivery on site or of bill submission which ever is later.</strong>";
$objPHPExcel->getActiveSheet()
	->setCellValue("f{$i}", $wizard->toRichTextObject($html));
$objPHPExcel->getActiveSheet()->mergeCells("f{$i}:q{$i}");	
	
	

$objPHPExcel->getActiveSheet()->getStyle("C8")->applyFromArray($styleArray_thin);
$objPHPExcel->getActiveSheet()->getStyle("O8")->applyFromArray($styleArray_thin);
$objPHPExcel->getActiveSheet()->getStyle("E9")->applyFromArray($styleArray_thin);
$objPHPExcel->getActiveSheet()->getStyle("O9")->applyFromArray($styleArray_thin);
	
 // $styleArray = array(
      // 'borders' => array(
          // 'allborders' => array(
              // 'style' => PHPExcel_Style_Border::BORDER_THIN,
			  // 'color' => array('argb' => 'FFFFFF')
          // )
      // )
  // );
 
// $objPHPExcel->getDefaultStyle("A1:Q{$i}")->applyFromArray($styleArray);
	
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(25);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setItalic(true);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setName('Calibri');	

$objPHPExcel->getActiveSheet()->getStyle('A5')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A5')->getFont()->setSize(15);
$objPHPExcel->getActiveSheet()->getStyle('A5')->getFont()->setUnderline(true);
	
$objPHPExcel->getActiveSheet()->getStyle('A6:A12')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('G6')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('M7')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('M8')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('M9')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('M11')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('C12')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('C12:Q13')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('D18:D21')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('C28:K31')->getFont()->setBold(true);

	
	
// $objPHPExcel->getActiveSheet()->removeColumn('R');
// $objPHPExcel->getActiveSheet()->removeColumn('S');
// $objPHPExcel->getActiveSheet()->removeColumn('T');
// $objPHPExcel->getActiveSheet()->removeColumn('U');
// $objPHPExcel->getActiveSheet()->removeColumn('V');
// $objPHPExcel->getActiveSheet()->removeColumn('W');
// $objPHPExcel->getActiveSheet()->removeColumn('X');
// $objPHPExcel->getActiveSheet()->removeColumn('Y');
// $objPHPExcel->getActiveSheet()->removeColumn('Z');
	

// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Quotation');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

	
	
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Quotation.xlsx"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
  // $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$objWriter->save('php://output');
exit;
