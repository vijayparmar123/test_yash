<?php
//$this->extend('/Common/menu')
?>
<div class="col-md-10" >
<div class="row">
	<div class="col-md-12">
		<div class="block">				
			<div class="head bg-default bg-light-rtl">
				<h2>View Stock OF <?php echo $projct_title;?></h2>
				<div class="pull-right">
				<a href="<?php echo $this->ERPfunction->action_link('Projects','viewprojectlist');?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
				</div>
			</div>
		<div class="content list custom-btn-clean">
		<script>
		jQuery(document).ready(function() {
		jQuery('#material_list').DataTable({responsive: true});
		} );
</script>
			<table id="material_list"  class="dataTables_wrapper table table-striped table-hover">
				<thead>
					<tr>
						<th>Material Code</th>
						<th>Material Name</th>						
						<th>Qty</th>						
						<th>Unit</th>						
						
					</tr>
				</thead>
				<tbody>
					<?php
						$i = 1;
						foreach($material_list as $retrive_data)
						{
						?>
							<tr>								
								<td><?php echo $this->ERPfunction->get_material_item_code_bymaterialid($retrive_data['material_id']);?></td>
								<td><?php echo $this->ERPfunction->get_material_title($retrive_data['material_id']);?></td>								
								<td><?php echo $retrive_data['quantity'];?></td>									
								<td><?php echo $this->ERPfunction->get_items_units($retrive_data['material_id']);?></td>									
							</tr>
						<?php
						$i++;
						}
					?>
				</tbody>
			</table>
		</div>
		</div>
	</div>
</div>
</div>