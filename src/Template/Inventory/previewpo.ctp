<?php
use Cake\Routing\Router;

$created_by = isset($erp_po_details['created_by'])?$this->ERPfunction->get_user_name($erp_po_details['created_by']):'NA';
$last_edit = isset($erp_po_details['last_edit'])?date("m-d-Y H:i:s",strtotime($erp_po_details['last_edit'])):'NA';
$last_edit_by = isset($erp_po_details['last_edit_by'])?$this->ERPfunction->get_user_name($erp_po_details['last_edit_by']):'NA';

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
		if(!empty($erp_po_details) ){			 
	?>	    
	<div id="scrolling-div">
		<table width="100%" border="1" >
			<tbody>
				<tr align="center"><td colspan="13"><?php echo $this->ERPfunction->viewheader();?></td></tr>
				<tr align="center"><td colspan="13"><h2><strong>Purchase Order (PO)</strong></h2></td></tr>
				<tr>
					<td colspan="4" > <strong> Project Code: <?php echo $this->ERPfunction->get_projectcode($erp_po_details['project_id']);?></strong></td>
					<td colspan="9" > <strong> Project Name: <?php echo $this->ERPfunction->get_projectname($erp_po_details['project_id'])/*.', &nbsp;'.$this->ERPfunction->get_projectaddress($erp_po_details['project_id']).', &nbsp;'.$this->ERPfunction->get_projectcity($erp_po_details['project_id'])*/;?></strong></td>
				</tr>
				<tr>
					<td><strong>P. O. No:</strong></td>
					<td colspan="3" > <?php echo $erp_po_details['po_no']; ?></td>
					<td colspan="2"><strong>Date:</strong> </td>
					<td colspan="3" ><?php echo $this->ERPfunction->get_date($erp_po_details['po_date']); ?>  </td>
					<td colspan="1" ><strong>Time:</strong></td>
					<td colspan="3" ><?php echo $erp_po_details['po_time']; ?></td>
				</tr>
				<tr>
					<td><strong>Vendor Name:</strong></td>
					<td colspan="5" ><?php echo $this->ERPfunction->get_vendor_name($erp_po_details['vendor_userid']); ?></td>
					<td colspan="3" ><strong>Vendor ID:</strong> </td>
					<td colspan="4"><?php echo $erp_po_details['vendor_id']; ?> </td>
				</tr>
				<tr>
					<td><strong>Vendor Addresss:</strong></td>
					<td colspan="12" ><?php echo $erp_po_details['vendor_address']; ?></td>
				</tr>
				<tr>
					<td><strong>PAN No</strong> </td>
					<td colspan="5"><?php echo $this->ERPfunction->get_vendor_detail($erp_po_details['vendor_userid'],'pancard_no');?> </td>
					<td colspan="4" ><strong>GST No </strong></td>
					<td colspan="3"> <?php echo $this->ERPfunction->get_vendor_detail($erp_po_details['vendor_userid'],'gst_no'); ?></td>
				</tr>
				<tr>
					<!--<td><strong>P. R. No:</strong></td>
					<td colspan="5" ><?php //echo $this->ERPfunction->get_pr_no($erp_po_details['pr_id']); ?></td>  -->
					<td><strong>Contact No: (1)</strong> </td>
					<td colspan="5"><?php echo $this->ERPfunction->get_vendor_contact($erp_po_details["vendor_userid"],"one");?> </td>
					<td colspan="4" ><strong>Contact No: (2) </strong></td>
					<td colspan="3"> <?php echo $this->ERPfunction->get_vendor_contact($erp_po_details["vendor_userid"],"two"); ?></td>
				</tr>
				<tr>
					<td><strong>Place of Delivery:</strong></td>
					<td colspan="12" ><?php echo $this->ERPfunction->get_projectaddress($erp_po_details["project_id"]);?></td>
				</tr>
				<tr>
				<?php 
					$count = $previw_list->count();
					$i = 0;
					foreach($previw_list as $retrive_material)
					{
						$i++;
						if($count === $i)
						{
							$pr_mid = $retrive_material["pr_mid"];
						}
					}
					
				?>
					<!--<td><strong>P. R. No:</strong></td>
					<td colspan="5" ><?php //echo $this->ERPfunction->get_pr_no($erp_po_details['pr_id']); ?></td>  -->
					<td colspan="2" ><strong>Site Contact No: (1)</strong> </td>
					<!--<td colspan="4"><?php //echo $this->ERPfunction->get_pr_contact($pr_mid,"one"); ?> </td>-->
					<td colspan="4"><?php echo $erp_po_details['contact_no1']; ?> </td>
					<td colspan="4" ><strong>Site Contact No: (2) </strong></td>
					<!-- <td colspan="3"> <?php //echo $this->ERPfunction->get_pr_contact($pr_mid,"two"); ?></td> -->
					<td colspan="3"> <?php echo $erp_po_details['contact_no2']; ?></td>
				</tr>
				<tr>
					<td colspan="2"><strong>Payment Method:</strong></td>
					<td colspan="4" ><?php echo $erp_po_details['payment_method'];?></td>
					<td colspan="4" ><strong>Delivery Date</strong></td>
					<td colspan="3"> <?php echo ($erp_po_details['delivery_date']!= NULL)?date("d-m-Y",strtotime($erp_po_details['delivery_date'])):""; ?></td>
				</tr>
				
				<tr>
					<td><strong>Other Taxes:</strong></td>
					<td colspan="12" ><?php echo $erp_po_details['other_tax'];?></td>
				</tr>
				
				<tr>
					<td align="center" rowspan="2" ><strong>Material Code</strong></td>
					<td align="center" colspan="9"><strong>Material / Item</strong></td>
					<td rowspan="2" ><strong>Amount (Inclusive All)</strong></td>
					<td rowspan="2">Final Rate<br>(Inclusive All)</td>
				</tr>
				<tr>
					<td><strong>Description</strong></td>
					<td><strong>Make / Source</strong></td>
					<td><strong>Quantity</strong></td>
					<td><strong>Unit</strong></td>
					<td><strong>Unit Rate<!--(including Loading, Unloading, Transport and All Taxes)--><br> (Rs.)</strong></td>
					<td>Dis<br>(%)</td>
					<td>P&F <br>(%)</td>
					<td>Ex<br>(%)</td>
					<td>Other Taxes<br>(%)</td>	
				</tr>
				
				<?php 
					$total_amount = 0;
					foreach($previw_list as $retrive_material){
				?>
				<tr>
					<td><?php echo is_numeric($retrive_material['material_id'])?$this->ERPfunction->get_materialitemcode($retrive_material['material_id']):$retrive_material['m_code']; ?></td>
					<td><?php echo is_numeric($retrive_material['material_id'])?$this->ERPfunction->get_material_title($retrive_material['material_id']):$retrive_material['material_id'];?></td>
					<td><?php echo is_numeric($retrive_material['brand_id'])?$this->ERPfunction->get_brandname($retrive_material['brand_id']):$retrive_material['brand_id'];?></td>
					<td><?php echo $retrive_material['quantity'];?></td>
					<td><?php echo is_numeric($retrive_material['material_id'])?$this->ERPfunction->get_items_units($retrive_material['material_id']):$retrive_material['static_unit'];?></td>
					<td><?php echo $retrive_material['unit_price']; ?> </td>
					<td><?php echo $retrive_material['discount']; ?> </td>
					<td><?php echo $retrive_material['transportation']; ?> </td>
					<td><?php echo $retrive_material['exice']; ?> </td>
					<td><?php echo $retrive_material['other_tax']; ?> </td>
					<td><?php echo $retrive_material['amount']; ?> </td>
					<td><?php echo $retrive_material['single_amount']; ?> </td>
				</tr>
				<?php 
				$total_amount += $retrive_material['amount'];
				} ?>
				<tr>
						<td colspan="10" class="text-right"><b>Total Amount</b></td>
						<td id="total_po_amount"><b><?php echo $total_amount; ?><b></td>
						<td></td>
				</tr>
				<tr>
					<td rowspan="32" valign="top"><strong> Remarks/Note:</strong></td>
					<td colspan="12" > 1) The above mentioned rate includes following:</td>
				<tr>
				<tr>
					<td align="right"><input type="checkbox" name="vehicle" value="Bike" <?php echo ($erp_po_details["taxes_duties"]) ? "checked" : "";?> disabled ></td>
					<td colspan="11" ><strong>All Taxes & Duties</strong></td>
				</tr>
				<tr>
					<td align="right"><input type="checkbox" name="vehicle" value="Bike" <?php echo ($erp_po_details["loading_transport"]) ? "checked" : "";?> disabled></td>
					<td colspan="11" ><strong>Loading & Transportation - F. O. R. at Place of Delivery</strong></td>
				</tr>
				<tr>
					<td align="right"><input type="checkbox" name="vehicle" value="Bike" <?php echo ($erp_po_details["unloading"]) ? "checked" : "";?> disabled ></td>
					<td colspan="11" ><strong>Unloading</strong></td>
				</tr>
				<tr>
					<td align="right"><input type="checkbox" name="vehicle" value="Bike" <?php echo ($erp_po_details["warranty"] != "") ? "checked" : "";?> disabled ></td>
					<td colspan="11" ><strong>Replacement Warrenty up to  : &nbsp; &nbsp; &nbsp;<span><?php echo $erp_po_details["warranty"];?></span></strong> </td>
				</tr>
				<?php 
				// if($erp_po_details["loading_transport"] != 1)
				// {
				?>
				<!--<tr>
					<td colspan="12" > 
						1.1) Loading & Transportation will be Paid Extra Amount (Rs.): <?php echo $erp_po_details["extra_transport"];?>
					</td>
				<tr>-->
				<?php 
				//}
				?>
			<!--	<tr>
					<td colspan="12" > 2) The above mentioned rate includes Note - 4 f. o. r. above mentioned delivery address. </td>
				<tr>-->
				<tr>
					<td colspan="12" > 2) If material/item supplied must meet IS specifications; on failing to match with it or will found unsatisfactory after some days of delivery; supplier/party has to replace that free of cost and this PO will be considered as void. </td>
				<tr>
				<tr>
					<td colspan="12" > 3) Manufacturer's Test Certificates are required for each batch of supply. </td>
				<tr>
				<tr>
					<td colspan="12" > 4) Quantity may vary up to any extend afterwards; payment will be done on actual supply & its acceptance. </td>
				<tr>
				<tr>
					<td colspan="12" > 5) If you will not revert back within 48 hrs, this PO will be considered as accepted by you. </td>
				<tr>
				
				<tr>
					<td colspan="12" > 6) In case of ambiguity; our Engineer In-charge’s decision will be final and party has to obey it. </td>
				<tr>
				
				<tr>
					<td colspan="12" > 7) All disputes subject to Ahmedabad Jurisdiction only. </td>
				<tr>
				
				<tr>
					<td colspan="12" > 8) For payment party will have to submit <strong>Invoice along with Purchase Order (PO), Gate Pass - Goods Inward, Goods Receipt Note or/and Rejection Memo and/or Weight Pass. </strong></td>
				<tr>
				
				<tr>
					<?php /*if($erp_po_details["bill_address"] == "gj") {*/?>
					<td colspan="12" > <strong>Billing Address: </strong> 214/5, Khyati Complex, Near Mithakhali Underbridge, Ellisbridge, Ahmedabad - 380006.</td>
					<?php /* }else{ ?>
					<td colspan="12" > <strong>Billing Address: </strong>A-312, The Bellaire Camps, Near Asharam Square, Acharpura Road, Gandhinagar, Bhopal, Madhya Pradesh - 462036.</td>
					<?php } */ ?>
				<tr>
				
				<tr>
					<td colspan="12" > <strong>Courier Address:</strong>  Plot No: 1003, Opp. Sarita Udhyan Gate, Near Samarpan College, Sector - 8 / D, Gandhinagar, Gujarat - 382007. </td>
				<tr>
				<tr>
					<td colspan="6" > <strong>PAN No.:</strong> AAAFY3210E  </td>
					<td colspan="6" ><strong>Service Tax No.: AAAFY3210EST001</strong>  </td>
				<tr>
				<tr>
					<td colspan="6" ><strong>VAT/TIN No.:</strong> <?php echo $erp_po_details["vatno"];?></strong></td>
					<td colspan="6" > <strong>CST No.: <?php echo $erp_po_details["cstno"];?></strong> </td>
				<tr>
				<tr>
					<td colspan="13" >9) YashNand Engineers & Contractors has right to cancel order any time without any prior notice.</td>
				</tr>
				<tr>
					<td colspan="13" > 10) Payment will be done <strong><?php echo $erp_po_details["payment_days"];?></strong> days after date of delivery on site or bill submission which ever is later.</td>
				</tr>

				<tr>
					<td colspan="13" >
					<pre style="background:none;border:0px;font-size:15px;padding:0;"><?php echo $erp_po_details["remarks"];?></pre>
					</td>
				</tr>
				 
				<tr>
					<td valign="bottom"  align="center"  height="120" colspan="5"> 					
					<font size="4"> (<?php echo $this->ERPfunction->get_user_name($erp_po_details["created_by"]);?>)<br><strong> Prepared by </strong></font> 
					</td>
					<td valign="bottom" align="center" height="120" colspan="8">
					<?php 
						foreach($previw_list as $retrive_material)
						{
							if($retrive_material['first_approved_by'])
							{
								echo "<font size='4'>".$this->ERPfunction->get_user_name($retrive_material['first_approved_by'])."</font>";
								break;
							}
						}
					?><br>
					<font size="4"><strong>Authorized Signatory</strong></font> </td>
				</tr>
				
			</tbody>
		</table>
		</div>
	<?php  
		}
	?>
	<div class="row" style="font-style:italic;color:gray;">							
							<div class="col-md-12 pull-right">
								<br><br><br>
								<div class="col-md-3">
									<?php echo "Created By: {$created_by}"; ?>
								</div>
								<div class="col-md-3">
									 <?php echo "Last Edited By: {$last_edit_by}"; ?>
								</div>
								<div class="col-md-3"> 
									 <?php 
										foreach($previw_list as $retrive_material)
										{
											if($retrive_material['first_approved'])
											{
												echo "Varified by:".$this->ERPfunction->get_user_name($retrive_material['first_approved']);
												break;
											}
										}
									?>
									 
								</div>
								<div class="col-md-2">						 
								  <a href="../printporecord/<?php echo $erp_po_details["po_id"];?>" class="btn btn-default" id="print_this" target="_blank"><i class="icon-print"></i> Print</a>
								</div> 
							</div>
						</div>
	</div>
	</div>
</div>
               