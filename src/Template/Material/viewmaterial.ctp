<?php
//$this->extend('/Common/menu')
?>
<div class="col-md-10" >
<div class="row">
	<div class="col-md-12">
		<div class="block">
			<div class="head bg-default bg-light-rtl">
				<h2>Material List</h2>
				<div class="pull-right">
				<a href="<?php echo $this->ERPfunction->action_link('Material','addmaterial');?>" class="btn btn-success"><span class="icon-plus"></span> Add New</a>
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
						<th>Unit</th>						
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$i = 1;
						foreach($material_list as $retrive_data)
						{
						?>
							<tr>								
								<td><?php echo $category[$retrive_data['material_code']]['material_code'];?></td>
								<td><?php echo $retrive_data['material_title'];?></td>								
								<td><?php echo $this->ERPfunction->get_category_title($retrive_data['unit_id']);?></td>								
								<td>
								<?php 
								echo $this->Html->link("<i class='icon-pencil'></i> Edit",array('action' => 'addmaterial', $retrive_data['material_id']),
								array('class'=>'btn btn-primary btn-clean','escape'=>false));
								echo ' ';
								echo $this->Html->link("<i class='icon-trash'></i> Delete",array('action' => 'deletematerial',$retrive_data['material_id']),
								array('escape'=>false,'class'=>'btn btn-danger btn-clean','confirm' => 'Are you sure you wish to delete this Record?'));
								?>
								</td>
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