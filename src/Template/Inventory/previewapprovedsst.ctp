<?php
use Cake\Routing\Router;

$created_by = isset($erp_sst_details['created_by'])?$this->ERPfunction->get_user_name($erp_sst_details['created_by']):'NA';
$last_edit = isset($erp_sst_details['last_edit'])?date("m-d-Y H:i:s",strtotime($erp_sst_details['last_edit'])):'NA';
$last_edit_by = isset($erp_sst_details['last_edit_by'])?$this->ERPfunction->get_user_name($erp_sst_details['last_edit_by']):'NA';

if(!$is_capable)
	{
		$this->ERPfunction->access_deniedmsg();
	}
else
{

?>
 


<div class="col-md-10 ">
	<div class="prevew_pr">		
	<?php 
		if(!empty($erp_sst_details) ){
			 
	?>	
	    
		<table width="100%" border="1" >
			<tbody>
				<tr align="center">
				<td colspan="8"><?php echo $this->ERPfunction->viewheader($erp_sst_details['sst_date']);?></td>
				</tr>
				<tr align="center"><td colspan="8"><h2><strong>Site to Site Transfer (SST)</strong></h2></td></tr>
				<tr>
					<td colspan="1" > <strong> Project Code: </strong></td>
					<td colspan="2" > <?php echo $this->ERPfunction->get_projectcode($erp_sst_details['project_id']);?> </td>
					<td colspan="1" > <strong> Project Name:</strong></td>
					<td colspan="4" > <?php echo $this->ERPfunction->get_projectname($erp_sst_details['project_id']); ?> </td>
				</tr>
				<tr>
					<td><strong>S.S.T. No:</strong></td>
					<td colspan="3" > <?php echo $erp_sst_details['sst_no']; ?></td>
					<td><strong>Date:</strong> </td>
					<td> <?php echo $this->ERPfunction->get_date($erp_sst_details['sst_date']); ?>  </td>
					<td ><strong>Time:</strong></td>
					<td> <?php echo $erp_sst_details['sst_time']; ?></td>
				</tr>
				<tr>
					<td><strong>Transfer To:</strong></td>
					<td colspan="3" ><?php echo $this->ERPfunction->get_projectcode($erp_sst_details['transfer_to']);?></td>
					<td colspan="2"><strong>Project Name:</strong> </td>
					<td colspan="2"><?php echo $this->ERPfunction->get_projectname($erp_sst_details['transfer_to']); ?> </td>
				</tr>
				<tr>
					<td><strong>Driver's Name:</strong></td>
					<td colspan="3" ><?php echo $erp_sst_details['driver_name'];  ?></td>
					<td colspan="2" ><strong>Vehicle's No: </strong> </td>
					<td colspan="2"><?php echo $erp_sst_details['vehicle_no']; ?> </td>
				</tr>
				<tr>
					<td colspan="8">As per your requirement, we are transferring the following material (s) to you / your work site:</td>
				</tr>
				<tr>
					<td align="center" rowspan="2" ><strong>Material Code</strong></td>
					<td align="center" colspan="6"><strong>Material / Item</strong></td>
					<td rowspan="2" ><strong>Intimated By</strong></td>
				</tr>
				<tr>
					<td colspan="3"><strong>Description</strong></td>
					<td><strong>Make / Source</strong></td>
					<td><strong>Quantity</strong></td>
					<td><strong>Unit</strong></td>
					 
				</tr>
				<?php 
					foreach($previw_list as $retrive_material){
				?>
				<tr>
					<td><?php echo $this->ERPfunction->get_materialitemcode($retrive_material['material_id']); ?></td>
					<td colspan="3"><?php echo $this->ERPfunction->get_material_title($retrive_material['material_id']);?></td>
					<td><?php echo $this->ERPfunction->get_brandname($retrive_material['brand_id']);?></td>
					<td ><?php echo $retrive_material['quantity'];?></td>
					<td ><?php echo $this->ERPfunction->get_items_units($retrive_material['material_id']);?></td>
					<td><?php echo $retrive_material['intimated_by'];?> </td>
				</tr>
				<?php } ?>
				 
				<tr>
					<!--<td align="center" colspan="3"><h3><strong> Quantity Varified By </strong></h3>
						<?php
						//if($erp_sst_details['quantity_varifiedy']){
							//echo $this->ERPfunction->get_user_name($erp_sst_details['quantity_varifiedy']); 
						//}
						?>
					</td> -->
					<td align="center" colspan="4"><h3><strong> Made By </strong></h3>
						<?php
						if($erp_sst_details['created_by']){
							echo $this->ERPfunction->get_user_name($erp_sst_details['created_by']); 
						}
						?>
					</td>
					<td align="center" colspan="4"><h3><strong> Approved By </strong></h3>
						<?php
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
								echo $app . "<br>";
						}
						?>
					</td>
					
					
				</tr>
				
				
			</tbody>
		</table>
		<div class="row" style="font-style:italic;color:gray;padding-top:15px;">				<div class="col-md-6 pull-right">
				<div class="col-md-2"></div>
				<!-- <div class="col-md-4">
					<?php //echo "Created By: {$created_by}"; ?>
				</div>
				<div class="col-md-6">
					<?php //echo "Last Edited By: {$last_edit_by}"; ?>
				</div> -->
				<div class="col-md-2">						 
				 <a href="<?php echo $this->request->base ."/inventory/printapprovedsst/".$erp_sst_details["sst_id"];?>" class="btn btn-info" id="print_this" target="_blank"><i class="icon-print"></i> Print</a>
				</div> 
			</div>
		</div>
	<?php  
		}
	?>
	</div>
</div>
       <?php }?>        