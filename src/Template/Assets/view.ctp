<?php
//$this->extend('/Common/menu')
?>
<div class="col-md-10" >
<?php
if(!$is_capable)
{
	$this->ERPfunction->access_deniedmsg();
}
else{
?>    
<?php echo $this->element('breadcrumbs'); ?>
<div class="row">
	<div class="col-md-12">
		<div class="block">
			<div class="header">
				<h2>View Vendor List <a href="<?php echo $this->ERPfunction->action_link('Vendor','index');?>" class="btn btn-default">Add New</a></h2>
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
						<th>Vendor Group</th>
						<th>Vendor Id</th>
						<th>Vendor Name</th>
						<th>Contact No(1)</th>						
						<th>Email Id</th>						
						<th>Status</th>						
						<th>Last Removed</th>	
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
									echo $this->Html->image($this->ERPfunction->get_vendor_image($retrive_data['user_id']),
				array('class'=>'userimage','height'=>'50px','width'=>'50px')); ?>
								</td>
								<td><?php echo $this->ERPfunction->get_vendor_group_name($retrive_data['vendor_group']);?></td>
								<td><?php echo $retrive_data['vendor_id'];?></td>
								<td><?php echo $retrive_data['vendor_name'];?></td>
								<td><?php echo $retrive_data['contact_no1'];?></td>
								<td><?php echo $retrive_data['email_id'];?></td>								
								<td>
								<?php echo $this->ERPfunction->get_vendor_status($retrive_data['user_id']);?>								
								</td>
								<td>																
								<?php echo $this->ERPfunction->get_vendor_remove_date($retrive_data['user_id']);?>
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
<?php } ?>
</div>