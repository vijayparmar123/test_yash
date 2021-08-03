<?php
use Cake\Routing\Router;

$created_by = isset($erp_mrn_details['created_by'])?$this->ERPfunction->get_user_name($erp_mrn_details['created_by']):'NA';
$last_edit = isset($erp_mrn_details['last_edit'])?date("m-d-Y H:i:s",strtotime($erp_mrn_details['last_edit'])):'NA';
$last_edit_by = isset($erp_mrn_details['last_edit_by'])?$this->ERPfunction->get_user_name($erp_mrn_details['last_edit_by']):'NA';

?>
 


<div class="col-md-10 ">
	<div class="prevew_pr">		
	<?php 
		if(!empty($erp_mrn_details) ){
			 
	?>	
	    
		<table width="100%" border="1" >
			<tbody>
				<tr align="center">
				<td colspan="8"><?php echo $this->ERPfunction->viewheader($erp_mrn_details['mrn_date']);?></td>
				</tr>
				<tr align="center"><td colspan="7"><h2><strong>Material Return Note (MRN)</strong></h2></td></tr>
				<tr>
					<td colspan="1" > <strong> Project Code: </strong></td>
					<td colspan="1" > <?php echo $this->ERPfunction->get_projectcode($erp_mrn_details['project_id']);?> </td>
					<td colspan="1" > <strong> Project Name:</strong></td>
					<td colspan="4" > <?php echo $this->ERPfunction->get_projectname($erp_mrn_details['project_id']).', &nbsp;'.$this->ERPfunction->get_projectaddress($erp_mrn_details['project_id']).', &nbsp;'.$this->ERPfunction->get_projectcity($erp_mrn_details['project_id']) ;?> </td>
				</tr>
				<tr>
					<td><strong>MRN No:</strong></td>
					<td colspan="2" > <?php echo $erp_mrn_details['mrn_no']; ?></td>
					<td><strong>Date:</strong> </td>
					<td> <?php echo $this->ERPfunction->get_date($erp_mrn_details['mrn_date']); ?>  </td>
					<td ><strong>Time:</strong></td>
					<td> <?php echo $erp_mrn_details['mrn_time']; ?></td>
				</tr>
				<tr>
					<td><strong>Vendor Name:</strong></td>
					<td colspan="2" ><?php echo $this->ERPfunction->get_vendor_name($erp_mrn_details['vendor_user']); ?></td>
					<td><strong>Vendor ID:</strong> </td>
					<td colspan="3"><?php echo $erp_mrn_details['vendor_id']; ?> </td>
				</tr>
				<tr>
					<td><strong>Driver's Name:</strong></td>
					<td colspan="2" ><?php echo $erp_mrn_details['driver_name'];  ?></td>
					<td colspan="2" ><strong>Vehicle's No: </strong> </td>
					<td colspan="2"><?php echo $erp_mrn_details['vehicle_no']; ?> </td>
				</tr>
				<tr>
					<td align="center" rowspan="2" ><strong>Material Code</strong></td>
					<td align="center" colspan="2"><strong>Material / Item</strong></td>
					<td rowspan="2" colspan="2" ><strong>Qty./Weight</strong></td>
					<td rowspan="2" ><strong>Unit</strong></td>
					<td rowspan="2" ><strong>Remarks for Return</strong></td>
				</tr>
				<tr>
					<td><strong>Description</strong></td>
					<td><strong>Make / Source</strong></td>
					 
				</tr>
				<?php 
					foreach($previw_list as $retrive_material){
				?>
				<tr>
					<td><?php echo $this->ERPfunction->get_materialitemcode($retrive_material['material_id']); ?></td>
					<td><?php echo $this->ERPfunction->get_material_title($retrive_material['material_id']);?></td>
					<td><?php echo $this->ERPfunction->get_brandname($retrive_material['brand_id']);?></td>
					<td colspan="2"><?php echo $retrive_material['quantity'];?></td>
					<td><?php echo $this->ERPfunction->get_items_units($retrive_material['material_id']);?></td>
					<td><?php echo $retrive_material['remarks'];?> </td>
				</tr>
				<?php } ?>
				 
				<tr>
					<!--<td align="center" colspan="2"><h3><strong> Quantity Varified By </strong></h3>
						<?php
						//if($erp_mrn_details['quality_varifiedby']){
							//echo $this->ERPfunction->get_user_name($erp_mrn_details['quality_varifiedby']); 
						//}
						?>
					</td> -->
					<td align="center" colspan="4"><h3><strong> Made By </strong></h3>
						<?php
						if($erp_mrn_details['created_by']){
							echo $this->ERPfunction->get_user_name($erp_mrn_details['created_by']); 
						}
						?>
					</td>
					<td align="center" colspan="4"><h3><strong> Approved By </strong></h3>
						<?php
						if($erp_mrn_details['approve_executives']){
							echo $this->ERPfunction->get_user_name($erp_mrn_details['approve_executives']); 
						}
						?>
					</td>
				</tr>
				
			</tbody>
		</table>
			<div class="row" style="font-style:italic;color:gray;">							
							<div class="col-md-7 pull-right">
								<br><br><br>
								<!--<div class="col-md-4">
									<?php //echo "Created By: {$created_by}"; ?>
								</div>
								<div class="col-md-6">
									 <?php //echo "Last Edited By: {$last_edit_by}"; ?>
								</div> -->
								<div class="col-md-2">						 
								  <a href="../printmrn/<?php echo $erp_mrn_details["mrn_id"];?>" class="btn btn-default" id="print_this" target="_blank"><i class="icon-print"></i> Print</a>
								</div> 
							</div>
						</div>
		
	<?php  
		}
	?>
	</div>
</div>
               