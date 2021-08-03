<?php
use Cake\Routing\Router;
?>
<div class="col-md-10 ">
	<div class="prevew_pr">	
	    
		<table width="100%" border="1" >
			<tbody>
				<tr align="center">
				<td colspan="8"><?php echo $this->ERPfunction->viewheader();?></td>
				</tr>
				<tr align="center"><td colspan="8"><h2><strong>Mix Design</strong></h2></td></tr>
				<tr>
					<td colspan="1" > <strong> Project Code: </strong></td>
					<td colspan="2" > <?php echo $this->ERPfunction->get_projectcode($mix_row['project_id']);?> </td>
					<td colspan="1" > <strong> Project Name:</strong></td>
					<td colspan="4" > <?php echo $this->ERPfunction->get_projectname($mix_row['project_id']); ?> </td>
				</tr>
				<tr>
					<td colspan="1" > <strong> Asset Code: </strong></td>
					<td colspan="2" > <?php echo $this->ERPfunction->get_asset_code($mix_row['asset_id']);?> </td>
					<td colspan="1" > <strong> Asset Name:</strong></td>
					<td colspan="4" > <?php echo $this->ERPfunction->get_asset_name($mix_row['asset_id']); ?> </td>
				</tr>
				<tr>
					<td colspan="1" > <strong> Concrete Grade: </strong></td>
					<td colspan="7" > <?php echo $mix_row['concrete_grade'];?> </td>
				</tr>
				<tr>
					<td colspan="8"></td>
				</tr>
				<tr>
					<td><strong>Material Code</strong></td>
					<td colspan="5"><strong>Material / Item</strong></td>
					<td><strong>Unit</strong></td>
					<td><strong>Consumption in 1 CMT</strong></td>
				</tr>
				<?php 
					foreach($mix_details as $retrive_material){
				?>
				<tr>
					<td><?php echo $this->ERPfunction->get_materialitemcode($retrive_material['material_id']); ?></td>
					<td colspan="5"><?php echo $this->ERPfunction->get_material_title($retrive_material['material_id']);?></td>
					<td ><?php echo $this->ERPfunction->get_items_units($retrive_material['material_id']);?></td>
					<td><?php echo $retrive_material['consumption'];?> </td>
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
					<td align="center" colspan="3"><h3><strong> Made By </strong></h3>
						<?php
						if($mix_row['created_by']){
							echo $this->ERPfunction->get_user_name($mix_row['created_by']); 
						}
						?>
					</td>
					<td align="center" colspan="5"><h3><strong> Approved By </strong></h3>
						<?php
						if($mix_row['created_by']){
							echo $this->ERPfunction->get_user_name($mix_row['created_by']); 
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
				 <a href="../printmixdesign/<?php echo $mix_row["id"];?>" class="btn btn-default" id="print_this" target="_blank"><i class="icon-print"></i> Print</a>
				</div> 
			</div>
		</div>
	</div>
</div>
               