<?php
use Cake\Routing\Router;
?>
<script type="text/javascript">
var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;

jQuery(document).ready(function() {
	
	jQuery('#user_form').validationEngine();
	jQuery('#event_date').datepicker({
		dateFormat: "dd-mm-yy",
		  changeMonth: true,
	        changeYear: true,
	        yearRange:'-65:+0',
	        onChangeMonthYear: function(year, month, inst) {
	            jQuery(this).val(month + "-" + year);
	        }                    
    });
	
	jQuery("body").on("change", "#asset_namelist", function(event){	 
	var asset_name  = jQuery(this).val();
		 
	var curr_data = {	 						 					
						asset_name : asset_name,	 					
					};	 				
		jQuery.ajax({
			headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
			url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'namebyassetdata'));?>",
			data:curr_data,
			async:false,
			success: function(response){					
				var json_obj = jQuery.parseJSON(response);					
				jQuery('#asset_code').val(json_obj['asset_code']);					
				jQuery('#capacity').val(json_obj['capacity']);					
				jQuery('#asset_make').val(json_obj['asset_make']);	
				jQuery('#deployed_to').val(json_obj['deployed_to_id']).change();	
				jQuery('.deploy_to_project').val(json_obj['deployed_to_id']);	
				jQuery('#model_no').val(json_obj['model_no']);	
				jQuery('#vehicle_no').val(json_obj['vehicle_no']);	
				jQuery('#unit').val(json_obj['unit']);	
				jQuery('#asset_group_id').val(json_obj['asset_group_id']);	
				jQuery('#asset_group_name').val(json_obj['asset_group_name']);	
				jQuery('#quantity').val(json_obj['quantity']);	
				jQuery('.select2').select2();
				return false;
			},
			error: function (e) {
				 alert('Error');
			}
		});	
	});
});
</script>
<div class="col-md-10" >
<?php
if(!$is_capable)
{
	$this->ERPfunction->access_deniedmsg();
}
else{
?>    		
<div class="block block-fill-white">				
	<div class="head bg-default bg-light-rtl">
		<h2><?php echo $form_header;?> </h2>
		<div class="pull-right">
			<a href="<?php echo $this->ERPfunction->action_link('Assets',$back);?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
		</div>
	</div>

	
	<?php echo $this->Form->Create('form1',['id'=>'maintenance_notification_form','class'=>'form_horizontal formsize','method'=>'post','id'=>'user_form','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
	
	
	<div class="content controls">
		<div class="form-row">
			<div class="col-md-2">Asset Name<span class="require-field">*</span></div>
			<div class="col-md-4">
				<select style="width: 100%;" class="select2" required="true"  name="asset_name" id="asset_namelist">
					<option value="">Select Assets</option>
					<?php 
					foreach($asset_names as $key => $retrive_data)
					{
						echo '<option value="'.$retrive_data['asset_id'].'">'.$retrive_data['asset_name'].'</option>';
					}
					?>
				</select>
			</div>
			
			<div class="col-md-2">Asset ID</div>
			<div class="col-md-4">
				<input type="text" readonly="true" id="asset_code" name="asset_code" value="" class="form-control"/>
			</div>
		</div>
		<div class="form-row">
			<div class="col-md-2">Make</div>
			<div class="col-md-4">
				<input type="text" id="asset_make" readonly="true" name="asset_make" value="" class="form-control"/>	
			</div>
			
			<div class="col-md-2">Asset Capacity</div>
			<div class="col-md-4">
				<input type="text" readonly="true" id="capacity" name="capacity" value="" class="form-control"/>
			</div>
		</div>
		
		
		<div class="form-row">
			<div class="col-md-2">Model No</div>
			<div class="col-md-4"><input type="text" readonly="true" name="model_no" id="model_no" value="" class="form-control validate[required]"/></div>
			<div class="col-md-2">Identity / Veh. No.</div>
			<div class="col-md-4"><input type="text" readonly="true" name="vehicle_no" id="vehicle_no" value="" class="form-control"/></div>
		</div>	
		
		<div class="form-row">
			<div class="col-md-2">Deployed To</div>
			<div class="col-md-10">
			<input type="hidden" class="form-control deploy_to_project" name="deploy_to_project" value="">
			 <select style="width: 100%;" class="select2" disabled="disabled" readonly="true" required="true"  name="deployed_to" id="deployed_to">
				<option>--Select Project --</option>
				<?php 
			 
				foreach($project_data as $key => $retrive_data)
				{
					echo $retrive_data['project_id'];
					echo '<option value="'.$retrive_data['project_id'].'" '.$this->ERPfunction->selected($retrive_data['project_id'],$deployed_to).'>'.$this->ERPfunction->get_projectname($retrive_data['project_id']).'</option>';
				}  
				?>
				</select> 
			</div>
		</div> 
		
		
		<div class="form-row">
			<div class="col-md-2">Message<span class="require-field">*</span> </div>
			<div class="col-md-10"><textarea name="messages" class="validate[required] form-control"></textarea> </div>
		</div>
	 		
		<div class="form-row">
			<div class="col-md-2">Notifications Settings</div>
			<div class="col-md-2" style="text-align:left;">1 ) Event Date <span class="require-field">*</span> </div>
			<div class="col-md-3"><input type="text" name="event_date" class="validate[required]" id="event_date"></div>
		</div>
		
		<div class="form-row">
			<div class="col-md-2"></div>
			<div class="col-md-2" style="text-align:left;">2 ) Notification Time <span class="require-field">*</span> </div>
			<div class="col-md-3"><input type="number" min="0" name="notification_time" class="validate[required,number]" id="notification_time"></div>
			<div class="col-md-3"><b>Day before at 8:00 AM</b></div>
		</div>
		
		<div class="form-row">
			<div class="col-md-2"></div>
			<div class="col-md-2" style="text-align:left;">3 ) Type of Event <span class="require-field">*</span> </div>
			<div class="col-md-3">
				<select style="width: 100%;" class="select2" required="true"  name="event_type" id="event_type">
				<option value="single">Single</option>
				<option value="weekly">Weekly</option>
				<option value="monthly">Monthly</option>
				<option value="yearly">Yearly</option>
				</select>
			</div>
		</div>
		
		<div class="form-row">
			<div class="col-md-2"></div>
			<div class="col-md-4"><button type="submit" class="btn btn-primary">Save</button></div>
			<div class="col-md-2"></div>
			<div class="col-md-4"> </div>
		</div>
</div>
<?php $this->Form->end(); ?>
</div>
<?php 
} 
?>			
</div>