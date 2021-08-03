<?php
//$this->extend('/Common/menu')
$project_id=isset($description_data['project_id'])?json_decode($description_data['project_id']):array();
?>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('#user_form').validationEngine();
	jQuery('#date_of_birth,#as_on_date').datepicker({
		dateFormat: "dd-mm-yy",
		changeMonth: true,
		changeYear: true,
		yearRange:'-65:+0',
		onChangeMonthYear: function(year, month, inst) {
			jQuery(this).val(month + "-" + year);
		}                    
	}); 
});
</script>	

<div class="col-md-10" >	
	<?php 
	//if(!$is_capable)
		//{
			//$this->ERPfunction->access_deniedmsg();
		//}
	//else
	//{
	?>			
	<div class="block block-fill-white">					
		<div class="head bg-default bg-light-rtl">
			<h2>View Work Description</h2>
			<div class="pull-right">
				<a href="<?php echo $this->ERPfunction->action_link('Contract','workdescription');?>" class="btn btn-success"><span class="icon-arrow-left"> </span> Back</a>
			</div>
		</div>
		<!-- View Work Description Form -->
		<div class="content controls">
			<div class="form-row">
				<div class="col-md-2">Work Group :</div>
				<div class="col-md-4"><input type="text" name="work_group" value="<?php echo $this->ERPfunction->getWorkGroupName($description_data['work_group']);?>" class="form-control validate[required]" readonly="true" /></div>
			</div>
			<div class="form-row">
				<div class="col-md-2">Work Sub-Group :</div>
				<div class="col-md-4"><input type="text" name="work_subgroup" value="<?php echo $this->ERPfunction->getWorkSubGroupName($description_data['work_sub_group']);?>" class="form-control validate[required]" readonly="true" /></div>
			</div>
			<div class="form-row">
				<div class="col-md-2">Description :</div>
				<div class="col-md-4"><input type="text" name="head_code" value="<?php echo $description_data['category_title'];?>" class="form-control validate[required]" readonly="true" /></div>
			</div>
			<!-- <div class="form-row">
				<div class="col-md-2">Project:</div>
				<div class="col-md-4"><input type="text" name="project" value="<?php echo $this->ERPfunction->get_projectname($description_data['project_id']);?>" class="form-control validate[required]" readonly="true" /></div>
			</div> -->
			<div class="form-row">
				<div class="col-md-2">Unit<span class="require-field">*</span>  :</div>
				<div class="col-md-4"><input type="text" name="unit" value="<?php echo $description_data['unit'];?>" class="form-control validate[required]" readonly="true"/></div>
			</div>
			<table class='table-bordered dataTables_wrapper table table-striped table-hover' style='width:100%;'>
				<thead>
					<tr>
						<th>Project Name</th>
						<th>Status</th>
					</tr>
				</thead>
				<tbody>
					<?php
						foreach($projects as $retrive_data) {
					?>
					<tr>
						<td><?php echo $retrive_data['project_name']; ?></td>
						<td>
							<select class="select2" disabled style="width: 100%;" name="enabled_project_id[]" id="project_id" class="validate[required]">
								<option value="disabled">Disabled</Option>
								<option value="<?php echo $retrive_data['project_id'] ?>" 
								<?php                                            
									if(in_array($retrive_data['project_id'],$project_id)){
										echo 'selected="selected"';
									}else{
										echo '';
									}
								?>
								>Enabled</Option>
							</select>		
						</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
	<?php //} ?>
</div>