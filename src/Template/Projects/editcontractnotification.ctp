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
	
	jQuery("body").on("change", "#project_id", function(event){ 
		var project_id  = jQuery(this).val() ;
		var curr_data = {	 						 					
	 					project_id : project_id,	 					
	 					};	 				
	 	 jQuery.ajax({
                 headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'inmanualpoprojectdetail'));?>",
                data:curr_data,
                async:false,
                success: function(response){					
					var json_obj = jQuery.parseJSON(response);					
					jQuery('#project_code').val(json_obj['project_code']);
					return false;
                },
                error: function (e) {
                     alert('Error');
					 console.log(e.responseText);
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
			<a href="javascript:void(0)" onclick = "javascript:window.close();" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
		</div>
	</div>

	
	<?php echo $this->Form->Create('form1',['id'=>'maintenance_notification_form','class'=>'form_horizontal formsize','method'=>'post','id'=>'user_form','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
	
	
	<div class="content controls">
		<div class="form-row">
			<div class="col-md-2">Project Name:</div>
			<div class="col-md-4">
				<select class="select2" required="true" style="width: 100%;" name="project_id" id="project_id">
				<option value="">--Select Project--</Option>
				<?php 
					foreach($project_data as $retrive_data)
					{
						$selected = ($data['project_id']==$retrive_data['project_id'])?"selected":"";
						echo '<option value="'.$retrive_data['project_id'].'"'.$selected.'>'.
						$retrive_data['project_name'].'</option>';
					}
				?>
				</select>
			</div>
			<div class="col-md-2" class="text-right">Project Code:<span class="require-field">*</span> :</div>
			<div class="col-md-4"><input type="text" name="project_code" id="project_code"
			class="form-control validate[required]" value="<?php echo $this->ERPfunction->get_projectcode($data['project_id']) ?>" readonly="true"/></div>
		</div>
			
				
		<div class="form-row">
			<div class="col-md-2">Message<span class="require-field">*</span> </div>
			<div class="col-md-10"><textarea name="messages" class="validate[required] form-control"><?php echo $data['message']; ?></textarea> </div>
		</div>
	 		
		<div class="form-row">
			<div class="col-md-2">Notifications Settings</div>
			<div class="col-md-2" style="text-align:left;">1 ) Event Date <span class="require-field">*</span> </div>
			<div class="col-md-3"><input type="text" name="event_date" value="<?php echo isset($data)?date("Y-m-d",strtotime($data['event_date'])):""; ?>" class="validate[required]" id="event_date"></div>
		</div>
		
		<div class="form-row">
			<div class="col-md-2"></div>
			<div class="col-md-2" style="text-align:left;">2 ) Notification Time <span class="require-field">*</span> </div>
			<div class="col-md-3"><input type="number" min="0" name="notification_time" value="<?php echo isset($data)?$data['time_before']:""; ?>" class="validate[required,number]" id="notification_time"></div>
			<div class="col-md-3"><b>Day before at 8:00 AM</b></div>
		</div>
		
		<div class="form-row">
			<div class="col-md-2"></div>
			<div class="col-md-2" style="text-align:left;">3 ) Type of Event <span class="require-field">*</span> </div>
			<div class="col-md-3">
				<select style="width: 100%;" class="select2" required="true"  name="event_type" id="event_type">
				<option value="single" <?php echo ($data['event_type']=="single")?"selected":""; ?>>Single</option>
				<option value="weekly" <?php echo ($data['event_type']=="weekly")?"selected":""; ?>>Weekly</option>
				<option value="monthly" <?php echo ($data['event_type']=="monthly")?"selected":""; ?>>Monthly</option>
				<option value="yearly" <?php echo ($data['event_type']=="yearly")?"selected":""; ?>>Yearly</option>
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