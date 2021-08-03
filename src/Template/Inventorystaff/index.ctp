<?php
//$this->extend('/Common/menu')
?>
<div class="col-md-10" >
<?php /**/?>
<?php echo $this->element('breadcrumbs'); ?>
<div class="row">
	<div class="col-md-12">
		<div class="block">
			<div class="header">
				<h2>Inventory Staff List <a href="<?php echo $this->ERPfunction->action_link('Inventorystaff','add');?>" class="btn btn-default">Add New</a></h2>
			</div>
		
		<div class="content">
		<script>
		jQuery(document).ready(function() {
		jQuery('#user_list').DataTable({responsive: true});
		} );
</script>
			<table id="user_list"  class="dataTables_wrapper table table-striped table-hover">
				<thead>
					<tr>
						<th>Image</th>
						<th>First Name</th>
						<th>Last Name</th>
						<th>Username</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$i = 1;
						foreach($user_list as $retrive_data)
						{
						?>
							<tr>
								<td><?php 
									echo $this->Html->image($this->ERPfunction->get_user_image($retrive_data['user_id']),
				array('class'=>'userimage','height'=>'50px','width'=>'50px')); ?>
								</td>
								<td><?php echo $retrive_data['first_name'];?></td>
								<td><?php echo $retrive_data['last_name'];?></td>
								<td><?php echo $retrive_data['email_id'];?></td>
								<td>
								<?php 
								echo $this->Html->link(__('Edit'),array('action' => 'add', $retrive_data['user_id']),
								array('class'=>'btn btn-primary'));
								echo ' ';
								echo $this->Html->link(__('Delete'),array('action' => 'delete', $retrive_data['user_id']),
								array('class'=>'btn  btn-danger',
								'confirm' => 'Are you sure you wish to delete this Record?'))
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