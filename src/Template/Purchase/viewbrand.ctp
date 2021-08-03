<?php
//$this->extend('/Common/menu')
?>
<div class="col-md-10" >
<div class="row">
	<div class="col-md-12">
		<div class="block">
			<div class="header">
				<h2>Brand List <a href="<?php echo $this->ERPfunction->action_link('Purchase','addbrand');?>" class="btn btn-default">Add New</a></h2>
			</div>
		
		<div class="content">
		<script>
		jQuery(document).ready(function() {
		jQuery('#brand_list').DataTable({responsive: true});
		} );
</script>
			<table id="brand_list"  class="dataTables_wrapper table table-striped table-hover">
				<thead>
					<tr>
						<th>#ID</th>
						<th>Brand Name</th>						
						<th>Material Code</th>						
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$i = 1;
						foreach($brand_list as $retrive_data)
						{
						?>
							<tr>								
								<td><?php echo $i;?></td>
								<td><?php echo $retrive_data['brand_name'];?></td>
								<td><?php echo $category[$retrive_data['material_type']]['material_code'];?></td>	
								<td>
								<?php 
								echo $this->Html->link(__('Edit'),array('action' => 'addbrand', $retrive_data['brand_id']),
								array('class'=>'btn btn-primary'));
								echo ' ';
								echo $this->Html->link(__('Delete'),array('action' => 'deletebrand',$retrive_data['brand_id']),
								array('class'=>'btn btnview btn-danger','confirm' => 'Are you sure you wish to delete this Record?'));
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