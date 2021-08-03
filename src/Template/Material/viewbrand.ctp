<?php
//$this->extend('/Common/menu')
?>
<div class="col-md-10">
<div class="row">
	<div class="col-md-12">
		<div class="block">
			<div class="head bg-default bg-light-rtl">
				<h2>Brand List </h2>
				<div class="pull-right">
				<a href="<?php echo $this->ERPfunction->action_link('Purchase','addbrand');?>" class="btn btn-success"><span class="icon-plus"></span> Add New</a>
				</div>
			</div>
		<div class="content list custom-btn-clean">
		<script>
		jQuery(document).ready(function() {
		jQuery('#brand_list').DataTable({responsive: true});
		});
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
								echo $this->Html->link("<i class='icon-pencil'></i> Edit",array('controller'=>'purchase','action' => 'addbrand', $retrive_data['brand_id']),
								array('class'=>'btn btn-primary btn-clean','escape'=>false));
								echo ' ';
								echo $this->Html->link("<i class='icon-trash'></i> Delete",array('controller'=>'purchase','action' => 'deletebrand',$retrive_data['brand_id']),
								array('escape'=>false,'class'=>'btn btnview btn-danger btn-clean','confirm' => 'Are you sure you wish to delete this Record?'));
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