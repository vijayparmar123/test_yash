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
				<h2>Leave Summary </h2>
				<div class="pull-right">
				<a href="<?php echo $this->ERPfunction->action_link('Humanresource','index');?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
				</div>
			</div>
			
			<?php echo $this->Form->Create('form1',['id'=>'eq_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
					

                    <div class="content controls ">					
						<div class="form-row">
							<div class="col-md-2 text-right">Designation</div>
							<div class="col-md-4">								
								<select style="width: 100%;" class="select2" required="true"  name="designation[]" id="designation" multiple="multiple">
									<option value="All">All</Option>
									<?php 
										foreach($designations as $retrive_data)
										{
											echo '<option value="'.$retrive_data['role'].'">'.$retrive_data['title'].'</option>';
										}
										?>
								</select>
							</div>
							<div class="col-md-2">Full Name</div>
                            <div class="col-md-4"><input type="text" name="full_name" id="full_name" class="form-control" />
							</div>
						</div>
						<div class="form-row">
							<div class="col-md-2" style="text-align:right;">Month</div>
                            <div class="col-md-4">
								<select name="month" class="select2" required="true"  style="width:100%" id="month" multiple="multiple">
									<?php 
										foreach($months as $key => $value)
										{
											echo '<option value="'.$key.'">'.$value['name'].'</option>';
										}
									?>
									
								</select>
							</div>  
							<div class="col-md-2"> Year</div>
                            <div class="col-md-4">
								<select name="year" class="select2" required="true"  style="width:100%" id="year" multiple="multiple">
									<?php 
										for($i=2000;$i<2050;$i++)
										{
											echo '<option value="'.$i.'">'.$i.'</option>';
										}
									?>
									
								</select>
							</div>  
						</div>
						<div class="form-row">
							<div class="col-md-2 col-md-offset-1">
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
						<th>Month & Year</th>
						<th>Employee At</th>
						<th>Employee No</th>
						<th>Full Name</th>
						<th>Designation</th>
						<th>Leave Exc. Holidays,PL,CL & SL</th>						
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$i = 1;						
						foreach($employee_leaves as $retrive_data)
						{
						?>
							<tr>								
								<td><?php echo $this->ERPfunction->get_month_name($retrive_data['month']).' - '.$retrive_data['year'];?></td>
								<td><?php echo $retrive_data['employee_at'];?></td>
								<td><?php echo $this->ERPfunction->get_employee_no($retrive_data['employee_no']);?></td>
								<td><?php echo $retrive_data['full_name'];?></td>
								<td><?php echo $retrive_data['designation'];?></td>								
								<td><?php echo $retrive_data['leave_detail'];?></td>
								<td>
								<?php 
								echo $this->Html->link("<i class='icon-pencil'></i> Edit",array('action' => 'leavesheet', $retrive_data['leave_id']),
								array('class'=>'btn btn-primary btn-clean','escape'=>false));
								echo ' ';
								echo $this->Html->link("<i class='icon-eye-open'></i> View",array('action' => 'viewleavesheet', $retrive_data['leave_id']),
								array('class'=>'btn btn-info btn-clean','escape'=>false));
								echo ' ';
								echo $this->Html->link("<i class='icon-trash'></i> Delete",array('action' => 'deleteleave', $retrive_data['leave_id']),
								array('class'=>'btn  btn-danger btn-clean','escape'=>false,
								'confirm' => 'Are you sure you wish to delete this Record?'));
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