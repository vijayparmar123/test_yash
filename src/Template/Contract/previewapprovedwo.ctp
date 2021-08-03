<?php
use Cake\Routing\Router;

$created_by = isset($wo_data['created_by'])?$this->ERPfunction->get_user_name($wo_data['created_by']):'NA';

?>
 
<style>
div.checker.disabled span, div.radio.disabled span {
   background: #B3B3B3; 
    color: black; 
}
pre{
	color: #333;
    font-size: 15px;
	font-family: 'Helvetica Neue', 'Helvetica', Arial, sans-serif;
}
</style>

<div class="col-md-10 ">
<div class="col-md-12">
	<div class="prevew_pr">		
	<?php 
		if(!empty($wo_data) ){			 
	?>	    
	<div id="scrolling-div">
		<table width="100%" border="1" >
			<tbody>
				<tr align="center"><td colspan="13"><?php echo $this->ERPfunction->viewheader_po($wo_data['wo_date'],$wo_data['bill_mode']);?></td></tr>
				<tr align="center"><td colspan="13"><h2><strong>Work Order (WO)</strong></h2></td></tr>
				<tr>
					<td colspan="4" > <strong> Project Code: <?php echo $this->ERPfunction->get_projectcode($wo_data['project_id']);?></strong></td>
					<td colspan="9" > <strong> Project Name: <?php echo $this->ERPfunction->get_projectname($wo_data['project_id']);?></strong></td>
				</tr>
				<tr>
					<td><strong>Project Address::</strong></td>
					<td colspan="11"><?php echo $this->ERPfunction->get_projectaddress($wo_data['project_id']); ?></td>
				</tr>
				<tr>
					<td colspan="2"><strong>Mode of Billing:</strong></td>
					<td colspan="5" > <?php echo ($wo_data['bill_mode'] == 'mp')?'Madhya Pradesh':ucfirst($wo_data['bill_mode']); ?></td>
					<td colspan="3"><strong>Date:</strong> </td>
					<td colspan="4" ><?php echo $this->ERPfunction->get_date($wo_data['wo_date']); ?>  </td>
					
				</tr>
				<tr>
					<td><strong>W. O. No:</strong></td>
					<td colspan="15" > <?php echo $wo_data['wo_no']; ?></td>					
				</tr>				
				<tr>
					<td><strong>Party's Name:</strong></td>
					<td colspan="5" ><?php echo (is_numeric($wo_data['party_userid']))?$this->ERPfunction->get_vendor_name($wo_data['party_userid']):$this->ERPfunction->get_vendor_name_by_code($wo_data['party_userid']); ?></td>
					<td colspan="3" ><strong>Party ID:</strong> </td>
					<td colspan="4"><?php echo $wo_data['party_id']; ?> </td>
				</tr>
				<tr>
					<td><strong>Party Addresss:</strong></td>
					<td colspan="12" ><?php echo $wo_data['party_address']; ?></td>
				</tr>
				<tr>
					<td><strong>Contact No: (1)</strong> </td>
					<td colspan="5"><?php echo $wo_data["party_no1"];?> </td>
					<td colspan="4" ><strong>Contact No: (2) </strong></td>
					<td colspan="3"> <?php echo $wo_data["party_no2"]; ?></td>
				</tr>
				<tr>
					<td><strong>E-mail Id:</strong></td>
					<td colspan="12" ><?php echo $wo_data["party_email"];?></td>
				</tr>
				<tr>
					<td colspan="2" ><strong>Pan Card No:</strong> </td>
					<td colspan="4"><?php echo $wo_data['party_pan_no']; ?> </td>
					<td colspan="4" ><strong>GST No:</strong></td>
					<td colspan="3"> <?php echo $wo_data['party_gst_no']; ?></td>
				</tr>
				<tr>
					<td colspan="2"><strong>Type of Contract:</strong></td>
					<td colspan="4" ><?php echo $this->ERPfunction->get_contract_title($wo_data['contract_type']);?></td>
					<td colspan="4"><strong>Payment Method:</strong></td>
					<td colspan="3" ><?php echo $wo_data['payment_method'];?></td>
				</tr>
				<!--<tr>
					<td><strong>Target Date:</strong></td>
					<td colspan="12" ><?php echo ($wo_data["target_date"] != NULL)?date("d-m-Y",strtotime($wo_data["target_date"])):"";?></td>
				</tr>-->
				<tr>
					
					<td colspan="12" ></td>
				</tr>
				
				<tr>
					<td align="center" rowspan="2" ><strong>Contract Item No.</strong></td>
					<td align="center" colspan="9"><strong>Work / Item</strong></td>
					<td rowspan="2" ><strong>Amount <br>(Inclusive All)</strong></td>
				</tr>
				<tr>
					<td><strong>Work Head</strong></td>
					<td><strong>Description</strong></td>
					<td><strong>Quantity</strong></td>
					<td><strong>Unit</strong></td>
					<td><strong>Unit Rate<br> (Rs.)</strong></td>
					<td>Dis<br>(%)</td>
					<td>CGST <br>(%)</td>
					<td>SGST<br>(%)</td>
					<td>IGST<br>(%)</td>	
				</tr>
				
				<?php 
					$total_amount = 0;
					foreach($wod_data as $retrive_material){
						$first_approved_by = $retrive_material['first_approved_by'];
						$verified_by = $retrive_material['verified_by'];
						$second_approved_by = $retrive_material['approved_by'];
				?>
				<tr>
					<td><?php echo $retrive_material['contract_no']; ?></td>
					<td><?php echo $this->ERPfunction->get_work_head_title($retrive_material['work_head']);?></td>
					<td><?php echo $retrive_material['material_name'];?></td>
					<td><?php echo $retrive_material['quentity'];?></td>
					<td><?php echo $retrive_material['unit'];?></td>
					<td><?php echo $retrive_material['unit_rate']; ?> </td>
					<td><?php echo $retrive_material['discount']; ?> </td>
					<td><?php echo $retrive_material['cgst']; ?> </td>
					<td><?php echo $retrive_material['sgst']; ?> </td>
					<td><?php echo $retrive_material['igst']; ?> </td>
					<td><?php echo $retrive_material['amount']; ?> </td>
				</tr>
				<?php 
				$total_amount += $retrive_material['amount'];
				} ?>
				<tr>
						<td colspan="10" class="text-right"><b>Total Amount</b></td>
						<td id="total_po_amount"><b><?php echo $total_amount; ?><b></td>
						
				</tr>
				<tr>
					<td rowspan="28" valign="top"><strong> Remarks/Note:</strong></td>
					<td colspan="12" > 1) The above mentioned amount includes following:</td>
				</tr>
				</tbody>
<!-- remarks 1 start -->
		<tbody id="remark_1" style="<?php echo ($wo_data['contract_type'] == 1 || $wo_data['contract_type'] == 3 || $wo_data['contract_type'] == 4)?'':'display:none' ?>">
				<tr>
					<td rowspan="24" valign="top"></td>
					
				</tr>
				<tr>
					<td align="right"><input type="checkbox" name="vehicle" value="Bike" <?php echo ($wo_data["taxes_duties"]) ? "checked" : "";?> disabled ></td>
					<td colspan="11" ><strong>All Taxes & Duties</strong></td>
				</tr>
				
				<tr>
					<td align="right"><input type="checkbox" name="vehicle" value="Bike" <?php echo ($wo_data["guarantee"]) ? "checked" : "";?> disabled ></td>
					<td colspan="11" ><strong>Guarantee up to  : &nbsp; &nbsp; &nbsp;<span><?php echo $wo_data["guarantee_time"];?></span></strong> </td>
				</tr>
				
				<tr>
					<td colspan="12" > 2) You are also binded to our Contract Conditions & Specifications with Client; which are provided to you. </td>
				</tr>
				<tr>
					<td colspan="12" > 3) If work will found unsatisfactory afterwards; agency has to correct it free of cost. </td>
				</tr>
				<tr>
					<td colspan="12" > 4) Material/item supplied must meet IS specifications; on failing to match with it or will found unsatisfactory after some days of delivery; supplier/party has to replace that free of cost and this PO will be considered as void. </td>
				</tr>
				<tr>
					<td colspan="12" > 5) Check Material Make / Brand with the make list provided to you and get its sample approved by our Engineer In-charge,PMC/TPI, Client and other consultant. </td>
				</tr>
				<tr>
					<td colspan="12" > 6) Manufacturer's Test Certificates are required for each batch of supply.</td>
				</tr>
				<tr>
					<td colspan="12" >7) Always get your work checked and verified by our Engineer In-charge, PMC/TPI, Client and other consultants also take their prior approval before starting work. </td>
				</tr>
				<tr>
					<td colspan="12" > 8) Quantity may vary up to any extend afterwards; payment will be done on actual supply & its acceptance. </td>
				</tr>
				<tr>
					<td colspan="12" > 9)  you will not revert back within 48 hrs, this WO will be considered as accepted by you. </td>
				</tr>
				<tr>
					<td colspan="12" > 10) In case of ambiguity; our Engineer In-charge’s decision will be final and party has to obey it. </td>
				</tr>
				<tr>
					<td colspan="12" > 11) All disputes subject to Ahmedabad Jurisdiction only. </td>
				</tr>
				<tr>
					<td colspan="12" > 12) All Tools, Tackles & Equipment for completing the work need to be procured by you at your cost. </td>
				</tr>
				<tr>
					<td colspan="12" > 13) Agency/party needs to maintain and obey all safety rules & standards. </td>
				</tr>
				<tr>
					<td colspan="12" > 14) For payment party will have to submit Invoice along with Work Order (WO), Measurement Sheet & Abstract duly signed by Construction Manager, Billing Engineer & Site Accountant. </td>
				
				<tr>
					<?php if($wo_data["bill_mode"] == "gujarat") {?>
					<td colspan="12" > <strong>Billing Address:</strong>214/5, Khyati Complex, Near Mithakhali Underbridge, Ellisbridge, Ahmedabad - 380006,Gujarat</td>
					<?php  }else if($wo_data["bill_mode"] == "mp"){ ?>
					<td colspan="12" > <strong>Billing Address:</strong><?php echo $this->ERPfunction->getmpbilladdress($wo_data["wo_date"]); ?></td>
					<?php }else if($wo_data["bill_mode"] == "maharastra"){  ?>
					<td colspan="12" > <strong>Billing Address:</strong>F - 302, P. No. - 21, 22, Sumit Residency, Bhagyashree Ni Kharbi Road, Nagpur, Maharashtra - 440009.</td>
					<?php }else if($wo_data["bill_mode"] == "haryana"){ ?>
					<td colspan="12" > <strong>Billing Address:</strong>Porta Cabin No - 2, Pandit Deen Dayal Upadhaya University of Health Science Campus Site, Gate No - 2 Kutail, Kutail Village, Karnal, Haryana - 134115.</td>
					<?php } ?>
				</tr>
				
				<tr>
					<td colspan="12" > <strong>Courier Address:</strong>  Plot No: 1003, Opp. Sarita Udhyan Gate, Near Samarpan College, Sector - 8 / D, Gandhinagar, Gujarat - 382007. </td>
				</tr>
				<tr>
					<td colspan="6" > <strong>PAN No.:</strong> <?php echo $this->ERPfunction->getstatepanno($wo_data["bill_mode"],$wo_data["wo_date"]); ?>  </td>
					<td colspan="6" > <strong>GST No.: <?php echo $wo_data["gstno"];?></strong> </td>
				</tr>
				
				<tr>
					<td colspan="13" >15) Your Invoice will be paid after deduction of advances, any type of debit notes, credit notes, retention money / security deposit, taxes etc.</td>
				</tr>
				<tr>
					<td colspan="13" > 16) Retention Money / Security Deposit will be deducted from every bills and will be released after satisfactory work completion.</td>
				</tr>
				<tr>
					<td colspan="13" > 17) <?php echo $this->ERPfunction->getconditionofpowo($wo_data["wo_date"],$wo_data["bill_mode"]); ?></td>
				</tr>
				<tr>
					<td colspan="13" > 18) Payment will be done <strong><?php echo $wo_data["payment_days"];?></strong> days after date of delivery on site or bill submission which ever is later.</td>
				</tr>
				<tr>
					<td colspan="13" >
					<pre style="background:none;border:0px;font-size:15px;padding:0;"><?php echo $wo_data["remarks"];?></pre>
					</td>
				</tr>
			</tbody>
<!-- remarks 1 end -->
<!-- remarks 2 start -->
			<tbody id="remark_2" style="<?php echo ($wo_data['contract_type'] == 5 || $wo_data['contract_type'] == 6 || $wo_data['contract_type'] == 7 || $wo_data['contract_type'] == 2)?'':'display:none' ?>">
				<tr>
					<td rowspan="27" valign="top"></td>
					
				</tr>
				<tr>
					<td align="right"><input type="checkbox" name="vehicle" value="Bike" <?php echo ($wo_data["taxes_duties"]) ? "checked" : "";?> disabled ></td>
					<td colspan="11" ><strong>All Taxes & Duties</strong></td>
				</tr>
				<tr>
					<td align="right"><input type="checkbox" name="vehicle" value="Bike" <?php echo ($wo_data["loading_transport"]) ? "checked" : "";?> disabled></td>
					<td colspan="11" ><strong>Loading & Transportation - F. O. R. at Place of Delivery</strong></td>
				</tr>
				<tr>
					<td align="right"><input type="checkbox" name="vehicle" value="Bike" <?php echo ($wo_data["unloading"]) ? "checked" : "";?> disabled ></td>
					<td colspan="11" ><strong>Unloading</strong></td>
				</tr>
				<tr>
					<td align="right"><input type="checkbox" name="vehicle" value="Bike" <?php echo ($wo_data["guarantee"] != "") ? "checked" : "";?> disabled ></td>
					<td colspan="11" ><strong>Guarantee up to  : &nbsp; &nbsp; &nbsp;<span><?php echo $wo_data["guarantee_time"];?></span></strong> </td>
				</tr>
				<tr>
					<td align="right"><input type="checkbox" name="vehicle" value="Bike" <?php echo ($wo_data["warrenty"] != "") ? "checked" : "";?> disabled ></td>
					<td colspan="11" ><strong>Replacement Warrenty up to  : &nbsp; &nbsp; &nbsp;<span><?php echo $wo_data["warrenty_time"];?></span></strong> </td>
				</tr>
				
				<tr>
					<td colspan="12" > 2) You are also binded to our Contract Conditions & Specifications with Client; which are provided to you. </td>
				</tr>
				<tr>
					<td colspan="12" > 3) If work will found unsatisfactory afterwards; agency has to correct it free of cost. </td>
				</tr>
				<tr>
					<td colspan="12" > 4) Material/item supplied must meet IS specifications; on failing to match with it or will found unsatisfactory after some days of delivery; supplier/party has to replace that free of cost and this WO will be considered as void. </td>
				</tr>
				<tr>
					<td colspan="12" > 5) Check Material Make / Brand with the make list provided to you and get its sample approved by our Engineer In-charge,PMC/TPI, Client and other consultant. </td>
				</tr>
				<tr>
					<td colspan="12" > 6) Manufacturer's Test Certificates are required for each batch of supply.</td>
				</tr>
				<tr>
					<td colspan="12" >7) Always get your work checked and verified by our Engineer In-charge, PMC/TPI, Client and other consultants also take their prior approval before starting work. </td>
				</tr>
				<tr>
					<td colspan="12" > 8) Quantity may vary up to any extend afterwards; payment will be done on actual supply & its acceptance. </td>
				</tr>
				<tr>
					<td colspan="12" > 9)  you will not revert back within 48 hrs, this WO will be considered as accepted by you. </td>
				</tr>
				<tr>
					<td colspan="12" > 10) In case of ambiguity; our Engineer In-charge’s decision will be final and party has to obey it. </td>
				</tr>
				<tr>
					<td colspan="12" > 11) All disputes subject to Ahmedabad Jurisdiction only. </td>
				</tr>
				<tr>
					<td colspan="12" > 12) All Tools, Tackles & Equipment for completing the work need to be procured by you at your cost. </td>
				</tr>
				<tr>
					<td colspan="12" > 13) Agency/party needs to maintain and obey all safety rules & standards. </td>
				</tr>
				<tr>
					<td colspan="12" > 14) For payment party will have to submit Invoice along with Work Order (WO), Measurement Sheet & Abstract duly signed by Construction Manager, Billing Engineer & Site Accountant. </td>
				</tr>
				<tr>
					<?php if($wo_data["bill_mode"] == "gujarat") {?>
					<td colspan="12" > <strong>Billing Address:</strong>214/5, Khyati Complex, Near Mithakhali Underbridge, Ellisbridge, Ahmedabad - 380006,Gujarat</td>
					<?php  }else if($wo_data["bill_mode"] == "mp"){ ?>
					<td colspan="12" > <strong>Billing Address:</strong><?php echo $this->ERPfunction->getmpbilladdress($wo_data["wo_date"]); ?></td>
					<?php }else if($wo_data["bill_mode"] == "maharastra"){  ?>
					<td colspan="12" > <strong>Billing Address:</strong>F - 302, P. No. - 21, 22, Sumit Residency, Bhagyashree Ni Kharbi Road, Nagpur, Maharashtra - 440009.</td>
					<?php }else if($wo_data["bill_mode"] == "haryana"){ ?>
					<td colspan="12" > <strong>Billing Address:</strong>Porta Cabin No - 2, Pandit Deen Dayal Upadhaya University of Health Science Campus Site, Gate No - 2 Kutail, Kutail Village, Karnal, Haryana - 134115.</td>
					<?php } ?>
				</tr>
				
				<tr>
					<td colspan="12" > <strong>Courier Address:</strong>  Plot No: 1003, Opp. Sarita Udhyan Gate, Near Samarpan College, Sector - 8 / D, Gandhinagar, Gujarat - 382007. </td>
				</tr>
				<tr>
					<td colspan="6" > <strong>PAN No.:</strong> <?php echo $this->ERPfunction->getstatepanno($wo_data["bill_mode"],$wo_data["wo_date"]); ?>  </td>
					<td colspan="6" > <strong>GST No.: <?php echo $wo_data["gstno"];?></strong> </td>
				</tr>
				<tr>
					<td colspan="13" >15) Your Invoice will be paid after deduction of advances, any type of debit notes, credit notes, retention money / security deposit, taxes etc.</td>
				</tr>
				<tr>
					<td colspan="13" > 16) Retention Money / Security Deposit will be deducted from every bills and will be released after satisfactory work completion.</td>
				</tr>
				<tr>
					<td colspan="13" > 17) <?php echo $this->ERPfunction->getconditionofpowo($wo_data["wo_date"],$wo_data["bill_mode"]); ?></td>
				</tr>
				<tr>
					<td colspan="13" > 18) Payment will be done <strong><?php echo $wo_data["payment_days"];?></strong> days after date of delivery on site or bill submission which ever is later.</td>
				</tr>
				<tr>
					<td colspan="13" >
					<pre style="background:none;border:0px;font-size:15px;padding:0;"><?php echo $wo_data["remarks"];?></pre>
					</td>
				</tr>
			</tbody>
			<tbody>
			
				
				 
				<tr>
					<td valign="bottom"  align="center"  height="120" colspan="4"> 					
					<font size="4"> (<?php echo $this->ERPfunction->get_user_name($wo_data["created_by"]);?>)<br><strong> Made By </strong></font> 
					</td>
					
					<td valign="bottom"  align="center"  height="120" colspan="4"> 					
					<font size="4"> (<?php echo $this->ERPfunction->get_user_name($verified_by);?>)<br><strong> Verified By </strong></font> 
					</td>
					
					<td valign="bottom" align="center" height="120" colspan="4"><font size="4"><strong><?php echo $this->ERPfunction->getletterheadsign($wo_data["wo_date"],$wo_data['bill_mode']); ?></strong><br><br>(<?php echo $this->ERPfunction->get_user_name($first_approved_by);?>)<br><strong>Authorized Signatory</strong></font></td>
					<!--<td valign="bottom" align="center" height="120" colspan="4"><font size="4"><strong>For YashNand Engineers & Contractors</strong><br><br>(<?php echo $this->ERPfunction->get_user_name($second_approved_by);?>)<br><strong>Authorized Signature-2</strong></font></td>-->
				</tr>
				
			</tbody>
		</table>
		</div>
	<?php  
		}
	?>
	<div class="row" style="font-style:italic;color:gray;">							
							<div class="col-md-7 pull-right">
								<br><br><br>
								<div class="col-md-2">						 
								  <a href="../printapprovedworecord/<?php echo $wo_data["wo_id"];?>" class="btn btn-default" id="print_this" target="_blank"><i class="icon-print"></i> Print</a>
								</div> 
							</div>
						</div>
						
						<!--<div class="row" style="font-style:italic;color:gray;">							
							<div class="col-md-7 pull-right">
								<br><br><br>
								<div class="col-md-2">						 
								  <a href="../printwoapproved/<?php //echo $wo_data["wo_id"];?>" class="btn btn-default" id="print_this" target="_blank"><i class="icon-print"></i> Print</a>
								</div> 
							</div>
						</div>-->
						
	</div>
	</div>
</div>
               