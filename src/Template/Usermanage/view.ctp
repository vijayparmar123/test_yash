<?php
//$this->extend('/Common/menu')
?>
<div class="col-md-10" >
<?php 
if(!$is_capable)
	{
		$this->ERPfunction->access_deniedmsg();
	}
else
{
?>	
<?php /**/?>
<div class="col-md-12">
<div class="row">
	
		<div class="block">			
			<div class="head bg-default bg-light-rtl">
				<h2>User Records</h2>
				<div class="pull-right">
				<a href="<?php echo $this->ERPfunction->action_link('Usermanage','index');?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
				</div>
			</div>
			
			
			<div class="content">
		<div class="col-md-12 filter-form">
			<?php 
				$project_id = array();
				$role = array();
				$vendor_userid_a = array();
				 $project_id = isset($_POST['project_id'])?$_POST['project_id']:'';
				 $from_date = isset($_POST['from_date'])?$_POST['from_date']:'';
				 $to_date = isset($_POST['to_date'])?$_POST['to_date']:'';
				 $role = isset($_POST['role'])?$_POST['role']:'';
				 $vendor_userid_a = isset($_POST['vendor_userid'])?$_POST['vendor_userid']:'';
			?>
		<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
					
					<div class="form-row">	
						<!-- <div class="col-md-2">GRN.No:</div>
                        <div class="col-md-4">
							<input type="text" name="po_no" id="po_no" value="" class="form-control"/>
						</div>					
						-->
						<div class="col-md-2">Designation:</div>
                        <div class="col-md-4">
							<select class="select2" required="true"  style="width: 100%;" name="role[]" multiple="multiple">
									<option value="All" selected>All</Option>
									<?php 
										foreach($designations as $retrive_data)
										{
											$selected = (in_array($retrive_data['role'],$role)) ? "selected" : "";
											echo '<option value="'.$retrive_data['role'].'" '. $selected .'>'.$retrive_data['title'].'</option>';
										}
									?>
								</select>
						</div>
						
						<div class="col-md-2">Alloted Projects:</div>
                        <div class="col-md-4">
							<select class="select2" style="width: 100%;" name="project_id[]" id="project_id" multiple="multiple">
								<option value="All" selected>All</Option>
								<?php 
									foreach($projects as $retrive_data)
									{
										$selected = (in_array($retrive_data['project_id'],$project_id)) ? "selected" : "";
										echo '<option value="'.$retrive_data['project_id'].'" '. $selected .'>'.$retrive_data['project_name'].'</option>';
									}
								?>
							</select>
						</div>
						
                    </div>
					
					<div class="form-row">	
						
						<div class="col-md-2">Status :</div>
                        <div class="col-md-4">
							<select class="select2"  style="width: 100%;" name="status" id="status">
								
								<option value="active">Active</Option>
								<option value="removed">Removed</Option>
							</select>
						</div>
						<div class="col-md-2 text-right">User Name</div>
						<div class="col-md-4"><input name="user_name" class="form-control" /></div>
						
					</div>
						
					<div class="form-row">
						<div class="col-md-2"> <div class="col-md-12"><input type="submit" name="go" id="go" class="btn btn-primary" value="Search"/></div></div>
					</div>
		
		<?php $this->Form->end(); ?>
			</div>
			</div>
			
		<div class="content  list custom-btn-clean">
		<script>
		jQuery(document).ready(function() {
		jQuery('#user_list').DataTable({responsive: true});
		} );
</script>
			<table id="user_list"  class="dataTables_wrapper table table-striped table-hover">
				<thead>
					<tr>
						<th>Photo</th>
						<!-- <th>First Name</th>
						<th>Last Name</th> -->
						<th>User Name</th>
						<th>Designation</th>
						<th>Alloted Projects</th>
						<th>Status</th>
						<th>Last Remove</th>
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
				array('class'=>'img-circle','height'=>'50px','width'=>'50px')); ?>
								</td>
								<td>
						<!--	<td><?php //echo $retrive_data['first_name'];?></td>
								<td><?php //echo $retrive_data['last_name'];?></td> -->
								<?php echo $retrive_data['username'];?></td>
								<td><?php echo $this->ERPfunction->get_designation($retrive_data['role']);
								?></td>
								<td><?php echo $this->ERPfunction->get_user_projects($retrive_data['user_id']);
								?></td>
								<td>
								<?php echo $this->ERPfunction->get_user_status($retrive_data['user_id']);?>								
								</td>
								<td>																
								<?php echo $this->ERPfunction->get_user_remove_date($retrive_data['user_id']);?>
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