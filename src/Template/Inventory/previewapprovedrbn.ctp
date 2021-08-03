<?php
use Cake\Routing\Router;

$created_by = isset($erp_rbn_details['created_by'])?$this->ERPfunction->get_user_name($erp_rbn_details['created_by']):'NA';
$last_edit = isset($erp_rbn_details['last_edit'])?date("m-d-Y H:i:s",strtotime($erp_rbn_details['last_edit'])):'NA';
$last_edit_by = isset($erp_rbn_details['last_edit_by'])?$this->ERPfunction->get_user_name($erp_rbn_details['last_edit_by']):'NA';

?>
<div class="col-md-10 ">
<?php 
if(!$is_capable)
	{
		$this->ERPfunction->access_deniedmsg();
	}
else
{ 
?>
	<div class="prevew_pr">		
	<?php 
		if(!empty($erp_rbn_details) ){
	?>	    
		<table width="100%" border="1" >
			<tbody>
				<tr align="center"><td colspan="9"><?php echo $this->ERPfunction->viewheader($erp_rbn_details['rbn_date']);?></td></tr>
				<tr align="center"><td colspan="9"><h2><strong>Returned Back Note (RBN)</strong></h2></td></tr>
				<tr>
					<td colspan="1" > <strong> Project Code: </strong></td>
					<td colspan="2" > <?php echo $this->ERPfunction->get_projectcode($erp_rbn_details['project_id']);?> </td>
					<td colspan="2" > <strong> Project Name:</strong></td>
					<td colspan="4" > <?php echo $this->ERPfunction->get_projectname($erp_rbn_details['project_id'])/*.', &nbsp;'.$this->ERPfunction->get_projectaddress($erp_rbn_details['project_id']).', &nbsp;'.$this->ERPfunction->get_projectcity($erp_rbn_details['project_id']) ;*/ ?> </td>
				</tr>
				<tr>
					<td><strong>R.B.N. No:</strong></td>
					<td colspan="6" > <?php echo $erp_rbn_details['rbn_no']; ?></td>
					<td><strong>Date:</strong> </td>
					<td> <?php echo $this->ERPfunction->get_date($erp_rbn_details['rbn_date']); ?></td>
					 
				</tr>
				<tr>
					<td><strong>Vendor / Asset Name:</strong></td>
					<td colspan="8"><?php echo $this->ERPfunction->get_vendor_name($erp_rbn_details['agency_name']); ?> </td>
				</tr>
				<tr>
					<td colspan="9">We are accepting following <strong> material (s) which is in good condition & working </strong>back, which was issued to you: </td>
					 
				</tr>
				 
				<tr>
					<td align="center" rowspan="2" ><strong>Material Code</strong></td>
					<td align="center" colspan="6"><strong>Material / Item</strong></td>
					<!--<td rowspan="2"><strong>Returned By</strong></td>-->
					<td rowspan="2" ><strong>Name of Foreman</strong></td>
					<td rowspan="2" ><strong>Usage / Remarks</strong></td>
					<!--<td rowspan="2" ><strong>Reason for Returning</strong></td>-->
				</tr>
				<tr>
					<td colspan="4" style="width:39%"><strong>Description</strong></td>
					<td><strong>Quantity Returned</strong></td>
					<td><strong>Unit</strong></td>
					 
				</tr>
				<?php 
					foreach($previw_list as $retrive_material){
				?>
				<tr>
					<td><?php echo $this->ERPfunction->get_materialitemcode($retrive_material['material_id']); ?></td>
					<td colspan="4" ><?php echo $this->ERPfunction->get_material_title($retrive_material['material_id']);?></td>
					 
					<td><?php echo $retrive_material['quantity_reurn'];?></td>
					<td><?php echo $this->ERPfunction->get_items_units($retrive_material['material_id']);?></td>
					<!-- <td ><?php //echo $this->ERPfunction->get_user_name($retrive_material['return_by']); ?></td>-->
					<td ><?php echo $retrive_material['name_of_foreman'] ; ?></td>
					<td ><?php echo $retrive_material['time_of_return'] ; ?></td>					
					<!--<td><?php //echo $retrive_material['return_reason'];?> </td>-->
				</tr>
				<?php } ?>
				 
				<tr>
					<!-- <td align="center" colspan="2"><h3><strong> Quantity & Condition Checked By </strong></h3>
						<?php
						// if($erp_rbn_details['quantity_checkby']){
							// echo $this->ERPfunction->get_user_name($erp_rbn_details['quantity_checkby']); 
						// }
						?>
					</td> -->
					<td align="center" colspan="5"><h3><strong> Made By </strong></h3>
						<?php
						if($erp_rbn_details['created_by']){
							echo $this->ERPfunction->get_user_name($erp_rbn_details['created_by']); 
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
					<!-- <td align="center" colspan="2"><h3><strong> Returned Back By </strong></h3>
						<?php
						// if($erp_rbn_details['return_backby']){
							// echo $this->ERPfunction->get_user_name($erp_rbn_details['return_backby']); 
						// }
						?>
					</td> -->
				</tr>
				
			</tbody>
		</table>
		<div class="row" style="font-style:italic;color:gray;padding-top:15px;">
			
				
				<!-- <div class="col-md-4">
					<?php //echo "Created By: {$created_by}"; ?>
				</div>
				<div class="col-md-6">
					<?php //echo "Last Edited By: {$last_edit_by}"; ?>
				</div> -->
				<div class="col-md-1 pull-right">			 
					  <a href="<?php echo $this->request->base ."/inventory/printapprovedrbn/".$erp_rbn_details["rbn_id"];?>" class="btn btn-info" id="print_this" target="_blank"><i class="icon-print"></i> Print</a>
				</div>
			
		</div>
	<?php  
		}
	?>
	</div>
<?php } ?>
</div>
               