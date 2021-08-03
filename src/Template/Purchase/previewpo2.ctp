<?php
	use Cake\Routing\Router;
	//var_dump($erp_po_details);die;
	$count = $previw_list->count();
	$i = 0;
	foreach($previw_list as $retrive_material) {
		$i++;
		if($count === $i) {
			$approve_date = $retrive_material["approved_date"];
		}
	}
	$created_by = isset($erp_po_details['created_by'])?$this->ERPfunction->get_user_name($erp_po_details['created_by']):'NA';
	$last_edit = isset($erp_po_details['last_edit'])?date("m-d-Y H:i:s",strtotime($erp_po_details['last_edit'])):'NA';
	$last_edit_by = isset($erp_po_details['last_edit_by'])?$this->ERPfunction->get_user_name($erp_po_details['last_edit_by']):'NA';
	$approved_time = !empty($approve_date)?date("d-m-Y",strtotime($approve_date)):'NA';
?>
<style>
    div.checker.disabled span,
    div.radio.disabled span {
        color: #333 !important;
    }

    pre {
        color: #333;
        font-size: 15px;
        font-family: 'Helvetica Neue', 'Helvetica', Arial, sans-serif;
    }

</style>


<div class="col-md-10 ">
    <div class="col-md-12 ">
        <div class="prevew_pr">
            <?php 
				if(!empty($erp_po_details) ){
					$po_type=$erp_po_details['po_purchase_type'];
					if($po_type == "po") {
						$type_name = "PO";
					}elseif($po_type == "manual_po") {
						$type_name = "Manual PO";
					}elseif($po_type == "local_po"){
						$type_name = "Local PO";
					}
			?>
            <div id="scrolling-div">
                <table width="100%" border="1">
                    <tbody>
                        <tr align="center">
                            <td colspan="13">
                                <?php echo $this->ERPfunction->viewheader_po($erp_po_details['po_date'],$erp_po_details['bill_mode']);?>
                            </td>
                        </tr>
                        <tr align="center">
                            <td colspan="13">
                                <h2><strong>Purchase Order (PO)</strong></h2>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>P. O. No:</strong></td>
                            <td colspan="5"> <?php echo $erp_po_details['po_no']; ?></td>
                            <td colspan="3"><strong>Date:</strong> </td>
                            <td colspan="6"><?php echo $this->ERPfunction->get_date($erp_po_details['po_date']); ?>
                            </td>
                        </tr>
                         <tr>
                            <td> <strong> Project Name: </strong></td>
                            <td colspan="12"><strong><?php echo $this->ERPfunction->get_projectname($erp_po_details['project_id'])/*.', &nbsp;'.$this->ERPfunction->get_projectaddress($erp_po_details['project_id']).', &nbsp;'.$this->ERPfunction->get_projectcity($erp_po_details['project_id']) ;*/?></strong>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>P.O. Type:</strong></td>
                            <td colspan="5"><?php echo $type_name;?></td>
                            <td colspan="3"> <strong> Project Code: </strong></td>
                            <td colspan="5"><strong><?php echo $this->ERPfunction->get_projectcode($erp_po_details['project_id']);?></strong>
                            </td>
                            
                        </tr>
                        <tr>
                            <td><strong>Billing Mode:</strong></td>
                            <td colspan="5">
                                <?php echo ($erp_po_details['bill_mode'] == 'mp')?'Madhya Pradesh':ucfirst($erp_po_details['bill_mode']); ?>
                            </td>
                            <td colspan="3"><strong>Usage:</strong> </td>
                            <td colspan="4"><?php echo ucfirst(str_replace('_',' ',$erp_po_details['usage_name'])); ?>
                            </td>
                        </tr>
                        <?php
							if($erp_po_details['agency_id']){
						?>
                        <tr>
                            <td><strong>Debit from Agency:</strong></td>
                            <td colspan="12">
                                <?php echo $this->ERPfunction->get_agency_name($erp_po_details['agency_id']); ?></td>
                        </tr>
                        <?php
							}
						?>
                        <tr>
                            <td><strong>Vendor Name:</strong></td>
                            <td colspan="5">
                                <?php echo $this->ERPfunction->get_vendor_name($erp_po_details['vendor_userid']); ?>
                            </td>
                            <td colspan="3"><strong>Vendor ID:</strong> </td>
                            <td colspan="4"><?php echo $erp_po_details['vendor_id']; ?> </td>
                        </tr>
                        <tr>
                            <td><strong>Vendor Addresss:</strong></td>
                            <td colspan="12"><?php echo $erp_po_details['vendor_address']; ?></td>
                        </tr>
                        <tr>
                            <td><strong>PAN No</strong> </td>
                            <td colspan="5">
                                <?php echo $this->ERPfunction->get_vendor_detail($erp_po_details['vendor_userid'],'pancard_no');?>
                            </td>
                            <td colspan="4"><strong>GST No </strong></td>
                            <td colspan="3">
                                <?php echo $this->ERPfunction->get_vendor_detail($erp_po_details['vendor_userid'],'gst_no'); ?>
                            </td>
                        </tr>
                        <tr>
                            <!--<td><strong>P. R. No:</strong></td>
						<td colspan="5" ><?php //echo $this->ERPfunction->get_pr_no($erp_po_details['pr_id']); ?></td>  -->
                            <td><strong>Contact No: (1)</strong> </td>
                            <td colspan="5">
                                <?php echo $this->ERPfunction->get_vendor_contact($erp_po_details["vendor_userid"],"one");?>
                            </td>
                            <td colspan="4"><strong>Contact No: (2) </strong></td>
                            <td colspan="3">
                                <?php echo $this->ERPfunction->get_vendor_contact($erp_po_details["vendor_userid"],"two"); ?>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Place of Delivery:</strong></td>
                            <td colspan="12">
                                <?php echo $this->ERPfunction->get_projectaddress(($erp_po_details['delivery_type'] == 'via')?$erp_po_details["delivery_project"]:$erp_po_details["project_id"]);
							/*echo $this->ERPfunction->get_projectaddress($erp_po_details['project_id']).', &nbsp;'.$this->ERPfunction->get_projectcity($erp_po_details['project_id']);*/
							?>
                            </td>
                        </tr>
                        <tr>
                            <?php 
							$count = $previw_list->count();
							$i = 0;
							foreach($previw_list as $retrive_material) {
								$i++;
								if($count === $i) {
									$pr_mid = $retrive_material["pr_mid"];
								}
							}					
						?>
                            <!--<td><strong>P. R. No:</strong></td>
						<td colspan="5" ><?php //echo $this->ERPfunction->get_pr_no($erp_po_details['pr_id']); ?></td>  -->
                            <td><strong>Site Contact No: (1)</strong> </td>
                            <td colspan="5"><?php echo $erp_po_details["contact_no1"]; ?> </td>
                            <td colspan="3"><strong>Site Contact No: (2) </strong></td>
                            <td colspan="4"> <?php echo $erp_po_details["contact_no2"]; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Payment Method:</strong></td>
                            <td colspan="5"><?php echo $erp_po_details['payment_method'];?></td>
                            <td colspan="3"><strong>Mode of GST:</strong></td>
                            <td colspan="4"><?php echo $erp_po_details['mode_of_gst'];?></td>
                            <!--<td colspan="4" ><strong>Delivery Date</strong></td>
						<td colspan="2"> <?php echo ($erp_po_details['delivery_date']!= NULL)?date("d-m-Y",strtotime($erp_po_details['delivery_date'])):""; ?></td>-->
                        </tr>
                        <!--<tr>
						<td><strong>Other Taxes:</strong></td>
						<td colspan="12" ><?php //echo $erp_po_details['other_tax'];?></td>
					</tr>-->
                        <tr>
                            <td align="center" rowspan="2" style="display:none;"><strong>Material Code</strong></td>
                            <td align="center" colspan="11"><strong>Material / Item</strong></td>
                            <td rowspan="2" align="center" style="max-width:40px;"><strong>Amount (Inclusive All)</strong></td>
                            <td rowspan="2" align="center" style="max-width:30px;"><strong>Final Rate<br>(Inclusive All)</strong></td>
                        </tr>
                        <tr>
                            <td colspan="5" align="center"  style="min-width:445px;"><strong>Description</strong></td>
                            <!--<td><strong>HSN Code</strong></td>-->
                            <td align="center" style="width:30px;"><strong>Make / Source</strong></td>
                            <td align="center"><strong>Quantity</strong></td>
                            <td style="max-width:10px;" align="center"><strong>Unit</strong></td>
                            <td style="max-width:20px;"><strong>Unit Rate
                                    <!--(including Loading, Unloading, Transport and All Taxes)--><br> (Rs.)</strong>
                            </td>
                            <td style="max-width:40px;" align="center"><strong>Discount<br>(%)</strong></td>
                            <!-- <td>CGST<br>(%)</td>
                            <td>SGST<br>(%)</td>
                            <td>IGST<br>(%)</td> -->
                            <td align="center" style="max-width:10px;"><strong>GST<br>(%)</strong></td>
                        </tr>
                        <?php 
						$total_amount = 0;
						foreach($previw_list as $retrive_material){
                            $description = isset($retrive_material['description'])?$retrive_material['description']:'NA';
					?>
                        <tr>
                            <td style="display:none;">
                                <?php echo is_numeric($retrive_material['material_id'])?$this->ERPfunction->get_materialitemcode($retrive_material['material_id']):$retrive_material['m_code']; ?>
                            </td>
                            <td colspan="5">
                                <center>
                                    (<?php echo is_numeric($retrive_material['material_id'])?$this->ERPfunction->get_materialitemcode($retrive_material['material_id']):$retrive_material['m_code'];?>)<br><?php echo is_numeric($retrive_material['material_id'])?$this->ERPfunction->get_material_title($retrive_material['material_id']):$retrive_material['material_id'];?>
                                    (<?php echo $description; ?>)
                                </center>
                            </td>
                            <!--<td><?php echo $retrive_material['hsn_code']; ?> </td>-->
                            <td align="center"><?php echo is_numeric($retrive_material['brand_id'])?$this->ERPfunction->get_brandname($retrive_material['brand_id']):$retrive_material['brand_id'];?>
                            </td>
                            <td><?php echo $retrive_material['quantity'];?></td>
                            <td><?php echo is_numeric($retrive_material['material_id'])?$this->ERPfunction->get_items_units($retrive_material['material_id']):$retrive_material['static_unit'];?>
                            </td>
                            <td><?php echo $retrive_material['unit_price']; ?> </td>
                            <td align="center"><?php echo $retrive_material['discount']; ?> </td>
                            <!-- <td><?php //echo $retrive_material['transportation']; ?> </td>
                            <td><?php //echo $retrive_material['exice']; ?> </td>
                            <td><?php //echo $retrive_material['other_tax']; ?> </td> -->
                            <td align="center"><?php echo $retrive_material['gst']; ?> </td>
                            <td align="center"><?php echo $retrive_material['amount']; ?> </td>
                            <td align="center"><?php echo $retrive_material['single_amount']; ?> </td>
                        </tr>
                        <?php 
						$total_amount += $retrive_material['amount'];
						} 
					?>
                        <tr>
                            <td colspan="11" class="text-right"><b>Total Amount</b></td>
                            <td id="total_po_amount" colspan='1' align="center"><b><?php echo $total_amount; ?><b></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td rowspan="31" valign="top"><strong> Remarks/Note:</strong></td>
                            <td colspan="12"> 1) The above mentioned amount includes following:</td>
                        <tr>
                        <tr>
                            <td align="right"><input type="checkbox" disabled="disabled" name="vehicle" value="Bike"
                                    <?php echo ($erp_po_details["taxes_duties"]) ? "checked" : "";?>></td>
                            <td colspan="11"><strong>All Taxes & Duties</strong></td>
                        </tr>
                        <tr>
                            <td align="right"><input type="checkbox" disabled="disabled" name="vehicle" value="Bike"
                                    <?php echo ($erp_po_details["loading_transport"]) ? "checked" : "";?>></td>
                            <td colspan="11"><strong>Loading & Transportation - F. O. R. at Place of Delivery</strong>
                            </td>
                        </tr>
                        <tr>
                            <td align="right"><input type="checkbox" disabled="disabled" name="vehicle" value="Bike"
                                    <?php echo ($erp_po_details["unloading"]) ? "checked" : "";?>></td>
                            <td colspan="11"><strong>Unloading</strong></td>
                        </tr>
                        <tr>
                            <td align="right"><input type="checkbox" disabled="disabled" name="vehicle" value="Bike"
                                    <?php echo ($erp_po_details["warranty"] != "") ? "checked" : "";?>></td>
                            <td colspan="11"><strong>Replacement Warrenty up to : &nbsp; &nbsp;
                                    &nbsp;<span><?php echo $erp_po_details["warranty"];?></span></strong> </td>
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
						// }
					?>
                        <tr>
                            <td colspan="12"> 2) Material/item supplied must meet IS specifications; on failing to match
                                with it or will found unsatisfactory after some days of delivery; supplier/party has to
                                replace that free of cost and this PO will be considered as void. </td>
                        <tr>
                        <tr>
                            <td colspan="12"> 3) Manufacturer's Test Certificates are required for each batch of supply.
                            </td>
                        <tr>
                        <tr>
                            <td colspan="12"> 4) Quantity may vary up to any extend afterwards; payment will be done on
                                actual supply & its acceptance. </td>
                        <tr>
                        <tr>
                            <td colspan="12"> 5) If you will not revert back within 48 hrs, this PO will be considered
                                as accepted by you. </td>
                        <tr>
                        <tr>
                            <td colspan="12"> 6) In case of ambiguity; our Engineer In-charge’s decision will be final
                                and party has to obey it. </td>
                        <tr>
                        <tr>
                            <td colspan="12"> 7) All disputes subject to Ahmedabad Jurisdiction only. </td>
                        <tr>
                        <tr>
                            <td colspan="12"> 8) Party will have to send <strong>Invoice and Purchase Order (PO) along
                                    with Material / Item.</strong> Payment will be processed after receiving approval
                                from project authorities, <strong>Goods Receipt Note and/or Weight Pass.</strong></td>
                        <tr>
                        <tr>
                            <?php if($erp_po_details["bill_mode"] == "gujarat") {?>
                            <td colspan="12"> <strong>Billing Address:</strong>214/5, Khyati Complex, Near Mithakhali
                                Underbridge, Ellisbridge, Ahmedabad - 380006,Gujarat</td>
                            <?php  }else if($erp_po_details["bill_mode"] == "mp"){ ?>
                            <td colspan="12"> <strong>Billing
                                    Address:</strong><?php echo $this->ERPfunction->getmpbilladdress($erp_po_details["po_date"]); ?>
                            </td>
                            <?php }else if($erp_po_details["bill_mode"] == "maharastra"){  ?>
                            <td colspan="12"> <strong>Billing Address:</strong>F - 302, P. No. - 21, 22, Sumit
                                Residency, Bhagyashree Ni Kharbi Road, Nagpur, Maharashtra - 440009.</td>
                            <?php }else if($erp_po_details["bill_mode"] == "haryana"){ ?>
                            <td colspan="12"> <strong>Billing Address:</strong>Porta Cabin No - 2, Pandit Deen Dayal
                                Upadhaya University of Health Science Campus Site, Gate No - 2 Kutail, Kutail Village,
                                Karnal, Haryana - 134115.</td>
                            <?php } ?>
                        </tr>
                        <!--<tr>
						<td colspan="12" > <strong>Courier Address:</strong>  Plot No: 1003, Opp. Sarita Udhyan Gate, Near Samarpan College, Sector - 8 / D, Gandhinagar, Gujarat - 382007. </td>
					<tr>-->
                        <tr>
                            <td colspan="6"> <strong>PAN No.:</strong>
                                <?php echo $this->ERPfunction->getstatepanno($erp_po_details["bill_mode"],$erp_po_details["po_date"]); ?>
                            </td>
                            <td colspan="6"> <strong>GST No.: <?php echo $erp_po_details["gstno"];?></strong> </td>
                            <!--<td colspan="6" ><strong>Service Tax No.: AAAFY3210EST001</strong>  </td>-->
                        <tr>
                            <!--<tr>
						<td colspan="6" ><strong>VAT/TIN No.:</strong> <?php //echo $erp_po_details["vatno"];?></strong></td>
						<td colspan="6" > <strong>CST No.: <?php //echo $erp_po_details["cstno"];?></strong> </td>
					<tr>-->
                        <tr>
                            <td colspan="12"> 9)
                                <?php echo $this->ERPfunction->getconditionofpowo($erp_po_details["po_date"],$erp_po_details["bill_mode"]); ?>
                            </td>
                        <tr>
                        <tr>
                            <td colspan="13"> 10) Payment will be done
                                <strong><?php echo $erp_po_details["payment_days"];?></strong> days after date of
                                delivery on site or bill submission which ever is later.</td>
                        <tr>
                        <tr>
                            <td colspan="13">
                                <pre
                                    style="background:none;border:0px;font-size:15px;padding:0;"><?php echo $erp_po_details["remarks"];?></pre>
                            </td>
                        </tr>
                        <tr>
                            <td valign="bottom" align="center" height="120" colspan="6">
                                <font size="4">
                                    (<?php echo $this->ERPfunction->get_user_name($erp_po_details["created_by"]);?>)<br><strong>
                                        Prepared by </strong></font>
                            </td>
                            <td valign="bottom" align="center" height="120" colspan="6">
                                <font size="4">
                                    <strong><?php echo $this->ERPfunction->getletterheadsign($erp_po_details["po_date"],$erp_po_details['bill_mode']); ?></strong>
                                </font><br><br>
                                <?php 
								// $approver = array();
								// $ids = array();
								// foreach($previw_list as $retrive_material){
								// if(!in_array($retrive_material['approved_by'],$ids))
								// {
									// $approver[] = $this->ERPfunction->get_user_name($retrive_material['approved_by']);
									// $ids[] = $retrive_material['approved_by'];
									// }
								// }
									
								// foreach($approver as $app){
								// echo $app . "<br>";
							// }
							?><?php 
								foreach($previw_list as $retrive_material) {
									// if($retrive_material['first_approved_by']) {
										echo "<font size='4'>".$this->ERPfunction->get_user_name($retrive_material['verified_by'])."</font>";
										break;
									// }
								}
							?><br>
                                <font size="4"><strong>Authorized Signatory</strong></font>
                            </td>
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
						<?php echo "Created By: {$created_by}"; echo "<br>";
						// echo $this->ERPfunction->get_formate_date($erp_po_details['created_date']);
                        $createdDate = isset($erp_po_details['created_date'])?$this->ERPfunction->get_formate_date($erp_po_details['created_date']):'NA';
						echo $createdDate;
                        ?>
                    </div>
                    <div class="col-md-3">
						<?php echo "Last Edited By: {$last_edit_by}";
						echo "<br>";
                        $lastEdit = isset($erp_po_details['last_edit'])?$this->ERPfunction->get_formate_date($erp_po_details['last_edit']):'NA';
						// echo $this->ERPfunction->get_formate_date($erp_po_details['last_edit']);
						echo $lastEdit;
                        ?>
                    </div>
					<div class="col-md-3">
                        <?php 		 
                            foreach($previw_list as $retrive_material) {
                                echo "Verified by:".$this->ERPfunction->get_user_name($retrive_material['verified_by']);
                                echo "<br>";
                                $verifiedBy = isset($retrive_material['verified_date'])?$this->ERPfunction->get_formate_date($retrive_material['verified_date']):'NA';
                                echo $verifiedBy;
                                break;
                            }
                        ?>
                    </div>
                    <!-- <div class="col-md-3">
                        <?php echo "Approved Date: {$approved_time}"; ?>
                    </div> -->
                    <!-- Preview Original Page for Ammend record -->
                    <div class="col-md-3">
                        <?php
                            if(!empty($erp_po_details['related_po_id'])) {
                                $related_po = $erp_po_details['related_po_id'];
                                $related_po = explode(",",$related_po);
                                $i = 1;
                                foreach($related_po as $po_id) {
                        ?>						
                            <div class="col-md-2 pull-left">
                                <br><br><br>
                                <div class="col-md-2">						 
                                    <a href="../previewpo2/<?php echo $po_id;?>" class="btn btn-default" id="print_this" target="_blank"><i class="icon-print"></i> View <?php echo $i; ?></a>
                                </div> 
                            </div>
                            
                            <?php
                                $i++;
                                }
                            }
						?>
                        <a href="<?php echo $this->request->base;?>/Purchase/printporecord2/<?php echo $erp_po_details["po_id"];?>"
                            class="btn btn-default" id="print_this" target="_blank"><i class="icon-print"></i> Print</a>
                    </div>
					<br><br><br>
                    <div class="col-md-3">
                        <?php 		 
                            foreach($previw_list as $retrive_material) {
                                echo "First Approved by:".$this->ERPfunction->get_user_name($retrive_material['first_approved_by']);
                                echo "<br>";
                                $firstApproveBy = isset($retrive_material['first_approved_date'])?$this->ERPfunction->get_formate_date($retrive_material['first_approved_date']):'NA';
                                echo $firstApproveBy;
                                break;
                            }
                        ?>
                    </div>
					<div class="col-md-3">
                        <?php 		 
                            foreach($previw_list as $retrive_material) {
                                echo "Final Approved by:".$this->ERPfunction->get_user_name($retrive_material['approved_by']);
                                echo "<br>";
                                $finalApproveDate = isset($retrive_material['approved_date'])?$this->ERPfunction->get_formate_date($retrive_material['approved_date']):'NA';
                                echo $finalApproveDate;
                                break;
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
