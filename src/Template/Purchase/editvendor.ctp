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
<div class="row">
	<div class="col-md-12">
		<div class="block">
			<div class="head bg-default bg-light-rtl">
				<h2>Manage Vendor </h2>
				<div class="pull-right">
				<a href="<?php echo $this->ERPfunction->action_link('Purchase','index');?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
				</div>
			</div>
			<?php echo $this->Form->Create('form1',['id'=>'eq_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
			 <div class="content controls">											
						<div class="form-row">
							<div class="col-md-2 text-right">Vendor ID</div>
							<div class="col-md-4">
								<input name="vendor_id" class="form-control">
							</div>
							<!--
							<div class="col-md-2 text-right">Vendor Group</div>
							<div class="col-md-4">
								<?php
									// $groups = $this->ERPfunction->asset_group();									
									// echo "<select class='select2' name='vendor_group[]' style='width:100%' multiple='multiple'>";
										// echo "<option value='All'>All</option>";								
										// foreach($vendor_groups as $key => $retrive_data)
										// {
											// echo '<option value="'.$retrive_data['id'].'" '.$this->ERPfunction->selected($retrive_data['id'],$vendor_group).'>'.$this->ERPfunction->get_vendor_group_name($retrive_data['id']).'</option>';
										// }								
									// echo "</select>";
								?>
							</div>-->
						</div>
						<div class="form-row">
							<div class="col-md-2 text-right">Vendor Name</div>
							<div class="col-md-4">
								<input name="vendor_name" class="form-control">
							</div>
							<div class="col-md-2 text-right">
								<button type="submit" name="search" value="Search" class="btn btn-primary">Search</button>
							</div>							
						</div>		
			</div>		
		<?php echo $this->Form->end();?>
		
		
		<div class="content list custom-btn-clean">
		<script>
		jQuery(document).ready(function() {
		jQuery('#user_list').DataTable({responsive: true});
		} );
</script>
			<table id="user_list"  class="dataTables_wrapper table table-striped table-hover">
				<thead>
					<tr>
						<!-- <th>Image</th> 
						<th>Vendor Group</th>-->
						<th>Vendor ID</th>
						<th>Vendor Name</th>
						<th>Contact No(1)</th>						
						<th>Contact No(2)</th>						
						<th>Address</th>						
						<th>Email Id</th>						
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
								<!--<td><?php 
									//echo $this->Html->image($this->ERPfunction->get_vendor_image($retrive_data['user_id']),
				//array('class'=>'userimage','height'=>'50px','width'=>'50px')); ?>
								</td>
								<td><?php // echo $this->ERPfunction->get_vendor_group_name($retrive_data['vendor_group']);?></td>
									-->
								<td><?php echo $retrive_data['vendor_id'];?></td>
								<td><?php echo $retrive_data['vendor_name'];?></td>
								<td><?php echo $retrive_data['contact_no1'];?></td>
								<td><?php echo $retrive_data['contact_no2'];?></td>
								<td><?php echo $retrive_data['vendor_billing_address'];?></td>
								<td><?php echo $retrive_data['email_id'];?></td>								
								<td>
								<?php 
								echo $this->Html->link("<i class='icon-pencil'></i> Edit",array('action' => 'addvendor', $retrive_data['user_id']),
								array('class'=>'btn btn-primary btn-clean','escape'=>false));
								echo ' ';
								/* echo $this->Html->link("<i class='icon-trash'></i> Delete",array('action' => 'removevendor', $retrive_data['user_id']),
								array('class'=>'btn  btn-danger btn-clean','escape'=>false,
								'confirm' => 'Are you sure you wish to remove this Record?'));		 */						
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
<?php } ?>
</div>