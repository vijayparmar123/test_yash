<?php
use Cake\Routing\Router;
?>
<script type="text/javascript">
var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;

jQuery(document).ready(function() {
	jQuery('#eq_form').validationEngine();
	
	jQuery("body").on("change", "#project_id", function(event){ 
		var project_id  = jQuery(this).val() ;
	    var curr_data = { project_id : project_id };	 				
		jQuery.ajax({
			headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
			url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'projectdetail'));?>",
			data:curr_data,
			async:false,
			success: function(response){					
				var json_obj = jQuery.parseJSON(response);					
				jQuery('#project_code').val(json_obj['project_code']);					
				return false;
			},
			error: function (e) {
				 alert('Error');
			}
		});	
	});
	
	jQuery("body").on("change", "#asset_group", function(event){	 
		var asset_group  = jQuery(this).val();
		jQuery('#asset_list').html("");
		var curr_data = {	 						 					
	 					asset_group : asset_group,	 					
	 					};	 				
		jQuery.ajax({
			headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
			url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'generateassetidname'));?>",
			data:curr_data,
			async:false,
			success: function(response){
				console.log(response);
				var json_obj = jQuery.parseJSON(response);	
				jQuery('#asset_code').val(json_obj['asset_code']);											
				jQuery('#asset_list').append(json_obj['name']);											
				return false;
			},
			error: function(e) {
				 console.log(e.responseText);
			}
		});	
	});
	
	jQuery('.datepick').datepicker({
		dateFormat: "yy-mm-dd",
		changeMonth: true,
	    changeYear: true,
	    yearRange:'-65:+0',
		maxDate: new Date(),
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
					jQuery('#model_no').val(json_obj['model_no']);	
					jQuery('#vehicle_no').val(json_obj['vehicle_no']);
					jQuery('#asset_group_id').val(json_obj['asset_group_id']);	
					jQuery('#asset_group_name').val(json_obj['asset_group_name']);
					jQuery('.select2').select2();
					return false;
                },
                error: function (e) {
                     alert('Error');
                }
            });	
	});
	
	jQuery("body").on("change", ".asset_km", function(event){
		count_km();
	});
	
	function count_km()
	{
		var start_km = $("#start_km").val();
		var stop_km = $("#stop_km").val();
		var intRegex = /^\d+$/;
		var floatRegex = /^((\d+(\.\d *)?)|((\d*\.)?\d+))$/;
		if((intRegex.test(start_km) || floatRegex.test(start_km)) && (intRegex.test(stop_km) || floatRegex.test(stop_km))) {
		   var difference = stop_km - start_km;
		   $("#usage_km").val(difference.toFixed(2));
		}else{
			$("#usage_km").val('');
		}
	}
	
	jQuery("body").on("change", ".asset_hr", function(event){
		count_hr();
	});
	
	function count_hr()
	{
		var start_hr = $("#start_hr").val();
		var stop_hr = $("#stop_hr").val();
		var intRegex = /^\d+$/;
		var floatRegex = /^((\d+(\.\d *)?)|((\d*\.)?\d+))$/;
		if((intRegex.test(start_hr) || floatRegex.test(start_hr)) && (intRegex.test(stop_hr) || floatRegex.test(stop_hr))) {
		   var difference = stop_hr - start_hr;
		   $("#usage_hr").val(difference.toFixed(2));
		}else{
			$("#usage_hr").val('');
		}
	}
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
		<h2>Equipment Log - Owned</h2>
		<div class="pull-right">
			<a href="<?php echo $this->ERPfunction->action_link('Assets','index');?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
		</div>
	</div>
	<?php echo $this->Form->Create('form1',['id'=>'eq_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
					
        <div class="content controls">
			<div class="form-row">
				<div class="col-md-2">Project Code:<span class="require-field">*</span> :</div>
				<div class="col-md-4">
					<input type="text" name="project_code" id="project_code" class="form-control validate[required]" value="<?php echo $this->ERPfunction->get_project_code($record['project_id']); ?>" readonly="true"/>
				</div>
				
				<div class="col-md-2">Project Name:</div>
				<div class="col-md-4">
					<select class="select2" required="true"  style="width: 100%;" name="project_id" id="project_id">
						<option value="">--Select Project--</Option>
						<?php 
							foreach($projects as $retrive_data)
							{
								$selected = ($retrive_data['project_id'] == $record['project_id'])?"selected":"";
								echo "<option value='".$retrive_data['project_id']."'".$selected.">".$retrive_data['project_name']."</option>";
															
							}
						?>
					</select>
				</div>
			</div>
			
			<div class="form-row">
				<div class="col-md-2">E.L No.<span class="require-field">*</span> :</div>
				<div class="col-md-4">
					<input name="el_date" value="<?php echo $record['el_no']; ?>" readonly="true" class="form-control">
				</div>			
				<div class="col-md-2">Date<span class="require-field">*</span> :</div>
				<div class="col-md-4">
					<input name="el_date" value="<?php echo date("d-m-Y",strtotime($record['date'])); ?>" class="datepick form-control validate[required]">
				</div>	
			</div>
			
			<div class="form-row" style="display:none;">
				<div class="col-md-offset-3 col-md-1">
					<input type="radio" name="ownership" class="toggle_box" value="rent">On Rent
				</div>
				<div class="col-md-4">
					<input type="radio" name="ownership" class="toggle_box" value="owned" checked>Owned
				</div>
			</div>
			
			<div class="form-row">
				<div class="col-md-2">Asset Group:<span class="require-field">*</span> :</div>
				<div class="col-md-4">
					<input class="form-control" type="hidden" name="asset_group" id="asset_group_id" value="<?php echo $record["asset_group_id"]; ?>" />
					<input class="form-control" readonly="true" id="asset_group_name" value="<?php echo $this->ERPfunction->get_asset_group_name($record["asset_group_id"]); ?>" />
				</div>
			
				<div class="col-md-2">Asset ID:</div>
				<div class="col-md-4">
					<input type="text" readonly="true" id="asset_code" name="asset_code" value="<?php echo $record['asset_code']; ?>" class="form-control"/>
				</div>
			</div>
			
			<div class="form-row">
				<div class="col-md-2">Asset Name<span class="require-field">*</span> :</div>
				<div class="col-md-10">
					<select style="width: 100%;" class="select2" required="true"  name="asset_name" id="asset_namelist">
						<option value=""> -- Select Asset-- </option>
						<?php 
						foreach($asset_list as $key=>$value)
						{
							$selected = ($key == $record['asset_id'])?"selected":"";
							echo '<option value="'.$key.'"'.$selected.'>'.$value.'</option>';
						}
						?>
					</select>
				</div>
			</div>
			
			<div class="form-row">
				<div class="col-md-2">Make:</div>
				<div class="col-md-4">
					<input type="text" id="asset_make" readonly="true" name="asset_make" value="<?php echo $record['asset_make']; ?>" class="form-control"/>
				</div>
				<div class="col-md-2">Asset Capacity:</div>
				<div class="col-md-4">
					<input type="text" readonly="true" id="capacity" name="capacity" value="<?php echo $record['asset_capacity']; ?>" class="form-control"/>
				</div>
			</div>
			
			<div class="form-row">
				<div class="col-md-2">Model No<span class="require-field">*</span>  :</div>
				<div class="col-md-4">
					<input type="text" name="model_no" readonly="true" id="model_no" value="<?php echo $record['asset_model']; ?>" class="form-control validate[required]"/>
				</div>
				<div class="col-md-2">Identity / Veh. No.</div>
				<div class="col-md-4">
					<input type="text" name="vehicle_no" readonly="true" id="vehicle_no" value="<?php echo $record['asset_identity']; ?>" class="form-control"/>
				</div>
			</div>
			
			<div class="form-row">
				<div class="col-md-2">Working Status<span class="require-field">*</span> :</div>
				<div class="col-md-10">
					<select style="width: 100%;" class="select2" required="true"  name="working_status" id="working_status">
						<option value="working" <?php echo ($record['working_status'] == "working")?"selected":""; ?>>Working</option>
						<option value="breakdown" <?php echo ($record['working_status'] == "breakdown")?"selected":""; ?>>Break Down</option>
						<option value="idle" <?php echo ($record['working_status'] == "idle")?"selected":""; ?>>Idle</option>
					</select>
				</div>
			</div>
			
			<div class="form-row">
				<div class="col-md-2">Duty Time(hr.)<span class="require-field">*</span> :</div>
				<div class="col-md-4">
					<input name="duty_time" id="duty_time" class="form-control validate[required,custom[number]]" value="<?php echo $record['duty_time']; ?>">
				</div>
				<div class="col-md-2">Breakdown Time(hr.)<span class="require-field">*</span> :</div>
				<div class="col-md-4">
					<input name="breakdown_time" id="breakdown_time" class="form-control validate[required,custom[number]]" value="<?php echo $record['breakdown_time']; ?>">
				</div>
			</div>
			
			<div class="form-row">
				<div class="col-md-2">Start (km)<span class="require-field">*</span> :</div>
				<div class="col-md-4">
					<input name="start_km" id="start_km" class="form-control validate[required,custom[number]] asset_km" value="<?php echo $record['start_km']; ?>">
				</div>
				<div class="col-md-2">Start (hr.)<span class="require-field">*</span> :</div>
				<div class="col-md-4">
					<input name="start_hr" id="start_hr" class="form-control validate[required,custom[number]] asset_hr" value="<?php echo $record['start_hr']; ?>">
				</div>
			</div>
			
			<div class="form-row">
				<div class="col-md-2">Stop (km)<span class="require-field">*</span> :</div>
				<div class="col-md-4">
					<input name="stop_km" id="stop_km" class="form-control validate[required,custom[number]] asset_km" value="<?php echo $record['stop_km']; ?>">
				</div>
				<div class="col-md-2">Stop (hr.)<span class="require-field">*</span> :</div>
				<div class="col-md-4">
					<input name="stop_hr" id="stop_hr" class="form-control validate[required,custom[number]] asset_hr" value="<?php echo $record['stop_hr']; ?>">
				</div>
			</div>
			
			<div class="form-row">
				<div class="col-md-2">Usage (km)<span class="require-field">*</span> :</div>
				<div class="col-md-4">
					<input name="usage_km" id="usage_km" readonly="true" class="form-control validate[required,custom[number]]" value="<?php echo $record['usage_km']; ?>">
				</div>
				<div class="col-md-2">Usage (hr.)<span class="require-field">*</span> :</div>
				<div class="col-md-4">
					<input name="usage_hr" id="usage_hr" readonly="true" class="form-control validate[required,custom[number]]" value="<?php echo $record['usage_hr']; ?>">
				</div>
			</div>
			
			<div class="form-row">
				<br>
				<div class="col-md-2">Driver Name<span class="require-field">*</span> :</div>
				<div class="col-md-4">
					<input name="driver_name" class="form-control validate[required]" value="<?php echo $record['driver_name']; ?>">
				</div>
			</div>
						
			<div class="form-row">
				<div class="col-md-2">Details of Usage :</div>
				<div class="col-md-10">
					<textarea name="usage_detail" class="form-control"><?php echo $record['usage_detail']; ?></textarea>
				</div>					
			</div>
						
			<div class="form-row">
				<br>
				<div class="col-md-2 col-md-offset-2">
					<button type="submit" value="Prepare E.L" class="btn btn-primary">Prepare E.L</button>
				</div>
				<br>
			</div>
        </div>
	<?php echo $this->Form->end();?>
</div>
<?php } ?>
</div>