<?php
use Cake\Routing\Router;
?>
<script type="text/javascript">
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
			<a href="<?php echo $this->ERPfunction->action_link('Projects',$back);?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
		</div>
	</div>

	
	<?php echo $this->Form->Create('form1',['id'=>'maintenance_notification_form','class'=>'form_horizontal formsize','method'=>'post','id'=>'user_form','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
	
	
	<div class="content controls">	
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