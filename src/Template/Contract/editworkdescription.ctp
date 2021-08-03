<?php
//$this->extend('/Common/menu')
$project_id=isset($description_data['project_id'])?json_decode($description_data['project_id']):array();
	use Cake\Routing\Router;

?>
<script type="text/javascript">
var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;

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

    // Get WorkSubGroup dropdown data
	jQuery("body").on("change", "#work_group", function(event){	
		var material_code  = jQuery(this).val();
		var curr_data = {material_code : material_code};	 				
		jQuery.ajax({
			headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
			url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'getworksubgroup'));?>",
			data:curr_data,
			async:false,
			success: function(response){                    
				jQuery('#work_subgroup').html(response);
				jQuery('.select2').select2();
			},
			error: function(e) {
				console.log(e);
			}
		});
	}); 
});
</script>	

<div class="col-md-10" >	
	<?php 
		if(!$is_capable) {
			$this->ERPfunction->access_deniedmsg();
		}else {
	?>	
	<div class="block block-fill-white">					
		<div class="head bg-default bg-light-rtl">
			<h2>Edit Work Description</h2>
			<div class="pull-right">
				<a href="<?php echo $this->ERPfunction->action_link('Contract','workdescription');?>" class="btn btn-success"><span class="icon-arrow-left"> </span> Back</a>
			</div>
		</div>
					
		<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
		
		<div class="content controls">
			<div class="form-row">
				<div class="col-md-2">Work Group<span class="require-field">*</span>  :</div>
				<div class="col-md-4">
					<select class="select2" required="true" style="width: 100%;" id="work_group" name="work_group">
						<option value="">--Select Option--</option>
						<?php
							foreach($work_group as $retrive_data) { 
								$selected = ($retrive_data['work_group_id'] == $description_data['work_group'])?"selected":"";
								echo '<option value="'.$retrive_data['work_group_id'].'"'.$selected.'>'.$retrive_data['work_group_title'].'</option>';
							}
						?>
					</select>
				</div>
			</div>
			<div class="form-row">
				<div class="col-md-2">Work Sub-group<span class="require-field">*</span>  :</div>
				<div class="col-md-4">
					<select class="select2" required="true" style="width: 100%;" id="work_subgroup" name="work_sub_group">
						<option value="">--Select Option--</option>
						<?php
							foreach($work_sub_group as $retrive_data) { 
								$selected = ($retrive_data['sub_work_group_id'] == $description_data['work_sub_group'])?"selected":"";
								echo '<option value="'.$retrive_data['sub_work_group_id'].'"'.$selected.'>'.$retrive_data['sub_work_group_title'].'</option>';
							}
						?>
					</select>
				</div>
			</div>
			<div class="form-row">
				<div class="col-md-2">Description<span class="require-field">*</span>  :</div>
				<div class="col-md-4"><input type="text" name="category_title" value="<?php echo $description_data['category_title'];?>" class="form-control validate[required]" /></div>
			</div>
			
			<div class="form-row">
				<div class="col-md-2">Unit<span class="require-field">*</span>  :</div>
				<div class="col-md-4"><input type="text" name="unit" value="<?php echo $description_data['unit'];?>" class="form-control validate[required]"/></div>
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
							<select class="select2" style="width: 100%;" name="enabled_project_id[]" id="project_id" class="validate[required]">
								<option value="disabled">Disabled</Option>
								<option value="<?php echo $retrive_data['project_id'] ?>" 
								<?php
								// debug($project_id);die;
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

			<div class="form-row">
				<div class="col-md-2"></div>
				<div class="col-md-4"><button type="submit" class="btn btn-success">Save</button></div>
			</div>
		</div>
		<?php $this->Form->end(); ?>
	</div>
	<?php } ?>
	
</div>
