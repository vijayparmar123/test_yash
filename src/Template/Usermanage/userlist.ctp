<?php
//$this->extend('/Common/menu')
?>
<div class="col-md-10" >

<div class="col-md-12" >	
<?php 
if(!$is_capable)
	{
		$this->ERPfunction->access_deniedmsg();
	}
else
{
?>	

<div class="row">
	<div class="col-md-12">
		<div class="block">			
			<div class="head bg-default bg-light-rtl">
				<h2>User List</h2>
				<div class="pull-right">
				<a href="<?php echo $this->ERPfunction->action_link('Usermanage','index');?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
				</div>
			</div>
		
		<div class="content list custom-btn-clean">
		<script>
		jQuery(document).ready(function() {
		jQuery('#user_list').DataTable({responsive: true,		 
				// "aoColumns":[
					// {"bSortable": true,sWidth:"1%"},
					// {"bSortable": true,sWidth:"20%"},
					// {"bSortable": true,sWidth:"20%"},
					// {"bSortable": true},					
					// {"bSortable": false,sWidth:"5%"}]
			});
		} );
</script>
			<table id="user_list"  class="dataTables_wrapper table table-striped table-hover">
				<thead>
					<tr>
						<!-- <th>Photo</th> -->
						<th>&nbsp;User Name</th>
					
						<th>Designation</th>
						<th>Alloted Projects</th>
						<?php
						if($this->ERPfunction->retrive_accessrights($role,'adduser')==1 || $this->ERPfunction->retrive_accessrights($role,'removeuser')==1)
						{
						?>
						<th>Action</th>
						<?php } ?>
					</tr>
				</thead>
				<tbody>
					<?php
						$i = 1;
						foreach($user_list as $retrive_data)
						{
						?>
							<tr>
								<!-- <td><?php 
									echo $this->Html->image($this->ERPfunction->get_user_image($retrive_data['user_id']),
				array('class'=>'img-circle','height'=>'50px','width'=>'50px')); ?>
								</td> -->
								<td>
								<?php echo $retrive_data['username'];?> 
								<?php //echo $retrive_data['last_name'];?></td>
						
								<td><?php echo $this->ERPfunction->get_designation($retrive_data['role']); ?></td>
								<td><?php echo $this->ERPfunction->get_user_projects($retrive_data['user_id']);
								?></td>
								<?php
								if($this->ERPfunction->retrive_accessrights($role,'adduser')==1 || $this->ERPfunction->retrive_accessrights($role,'removeuser')==1)
								{
								?>
								<td>
								<?php 
								if($this->ERPfunction->retrive_accessrights($role,'adduser')==1 )
								{	
								echo $this->Html->link("<i class='icon-pencil'></i> Edit",array('action' => 'add', $retrive_data['user_id']),
								array('class'=>'btn btn-primary btn-clean','escape'=> false));
								echo ' ';
								}
								if($this->ERPfunction->retrive_accessrights($role,'removeuser')==1 )
								{	
								echo $this->Html->link('<i class="icon-trash"></i> Remove &nbsp;',array('action' => 'remove', $retrive_data['user_id']),
								array('class'=>'btn btn-danger btn-clean','escape'=> false,
								'confirm' => 'Are you sure you wish to remove this Record?'));
								}
								?>
								</td>
								<?php } ?>
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