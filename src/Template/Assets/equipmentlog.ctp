<?php
use Cake\Routing\Router;
?>
<script type="text/javascript">
var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;

jQuery(document).ready(function() {
	jQuery('#eq_form').validationEngine();
	jQuery("body").on("change", "#project_id", function(event){ 
	 
	  var project_id  = jQuery(this).val() ;
		/* alert(product_id);
		return false; */
	   var curr_data = {	 						 					
	 					project_id : project_id,	 					
	 					};	 				
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
					jQuery('#prno').val(json_obj['prno']);						
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
		/* alert(product_id);
		return false; */
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
	
	//jQuery('#user_form').validationEngine();
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
} );
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
						<a href="<?php echo $this->ERPfunction->action_link('Assets','index');?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
						</div>
					</div>
                    <div class="header">
                        <h2></h2>
                    </div>
					<?php echo $this->Form->Create('form1',['id'=>'eq_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
					

                    <div class="content controls">
						<div class="form-row">
                            <div class="col-md-2">Project Code:<span class="require-field">*</span> :</div>
                            <div class="col-md-4"><input type="text" name="project_code" id="project_code" value="<?php echo ($edit)?$data["project_code"]:""; ?>"
							class="form-control validate[required]" value="" readonly="true"/></div>
							<div class="col-md-2">Project Name:</div>
                            <div class="col-md-4">
								<select class="select2" required="true"  style="width: 100%;" name="project_id" id="project_id">
								<option value="">--Select Project--</Option>
								<?php 
									foreach($projects as $retrive_data)
									{?>
										<option value="<?php echo $retrive_data['project_id'];?>" <?php 
											if(isset($data)){
												if($retrive_data['project_code'] == $data['project_code'])
												{
													echo 'selected="selected"';
												}
			
											}?> >
											<?php echo $retrive_data['project_name']; ?> </option>
										<?php
										
									}
								?>
								</select>
							</div>
                        </div>
						<div class="form-row">
							<?php if($edit) { ?>
                            <div class="col-md-2">E.L No.:</div>
                            <div class="col-md-4">
								<input name="elno" class="form-control" value="<?php echo ($edit)?$data["elno"]:$elno;?>" readonly>
							</div>
							<?php } ?>							
                            <div class="col-md-2">Date<span class="require-field">*</span> :</div>
                            <div class="col-md-4">
								<input name="el_date" class="datepick form-control validate[required]" value="<?php echo ($edit)?$data["el_date"]->format("Y-m-d"):"";?>">
							</div>	
						</div>
						<div class="form-row" style="display:none;">
                            <div class="col-md-offset-3 col-md-1">
								<input type="radio" name="ownership" class="toggle_box" value="rent" <?php echo($edit && $data["ownership"] == "rent")?"checked":"checked";?>>On Rent
							</div>
							<div class="col-md-4">
								<input type="radio" name="ownership" class="toggle_box" value="owned" <?php echo($edit && $data["ownership"] == "owned")?"checked":"";?>>Owned
							</div>
						</div>
						<div id="owned_box">
						<div class="form-row">
                            <div class="col-md-2">Asset Group<span class="require-field">*</span> :</div>
                            <div class="col-md-4">
								<select style="width: 100%;" class="select2" required="true"  name="asset_group" id="asset_group">
								<option>--Select Assets Group--</option>
								<?php 							
									foreach($asset_groups as $key => $retrive_data)
									{
										echo '<option value="'.$retrive_data['id'].'" '.$this->ERPfunction->selected($retrive_data['id'],$data["asset_group"]).'>'.$this->ERPfunction->get_asset_group_name($retrive_data['id']).'</option>';
									}								
								?>
								</select>
							</div>							
                            <div class="col-md-2">Asset ID</div>
                            <div class="col-md-4">
								<input type="text" readonly="true" id="asset_code" name="asset_code" value="<?php echo ($edit)?$data["asset_code"]:"";?>" class="form-control"/>
							</div>	
						</div>
						<div class="form-row">
                            <div class="col-md-2">Asset Name<span class="require-field">*</span> :</div>
                            <div class="col-md-4">
								<select name="asset_name" id="asset_list" class="form-control validate[required]">
								<?php 
								if($edit)
								{ 
									foreach($asset_list as $retrive_data)
									{ 
										$selected = ($retrive_data['asset_id'] == $data["asset_name"])?"selected":"";
										
										echo '<option value="'.$retrive_data['asset_id'].'" '.$selected.'>'.$retrive_data["asset_name"].'</option>';
									}
								}
								?>
								</select>
							</div>
						</div>					
						</div>
						<div id="rent_box">
						<div class="form-row">
                            <div class="col-md-2">Asset Name<span class="require-field">*</span> :</div>
                            <!--<div class="col-md-4">
								<input name="asset_name" id="asset_list" class="form-control validate[required]" value="<?php echo ($edit)?$data["asset_name"]:"";?>">
							</div>-->
							<div class="col-md-4">
								<select name="asset_name" required="true" style="width:100%" id="asset_list" class="select2">
								<option value="">-- Select Asset --</option>
								<?php 
								if($edit)
								{ 
									foreach($asset_list as $retrive_data)
									{ 
										$selected = ($retrive_data['asset_id'] == $data["asset_name"])?"selected":"";
										
										echo '<option value="'.$retrive_data['asset_id'].'" '.$selected.'>'.$retrive_data["asset_name"].'</option>';
									}
								}else{
									foreach($asset_list as $retrive_data)
									{ 										
										echo '<option value="'.$retrive_data['asset_id'].'">'.$retrive_data["asset_name"].'</option>';
									}
								}
								?>
								</select>
							</div>
							<br>
						</div>	
						</div>						
						<div class="form-row">
							<br>
                            <div class="col-md-2">Driver Name<span class="require-field">*</span> :</div>
                            <div class="col-md-4">
								<input name="driver_name" class="form-control validate[required]" value="<?php echo ($edit)?$data["driver_name"]:"";?>">
							</div>
							<div class="col-md-2">Vehicle No:<span class="require-field">*</span> :</div>
                            <div class="col-md-4">
								<input name="vehicle_no" class="form-control validate[required]" value="<?php echo ($edit)?$data["vehicle_no"]:"";?>">
							</div>
						</div>
						<div class="form-row">
                            <div class="col-md-2">Usage<span class="require-field">*</span> :</div>
                            <div class="col-md-2">
								<input name="el_usage" class="form-control validate[required]" value="<?php echo ($edit)?$data["el_usage"]:"";?>">
							</div>
							<div class="col-md-2">Unit of Usage<span class="require-field">*</span> :</div>
                            <div class="col-md-2">
								<select name="unit_usage" class="form-control validate[required]">
									<option value="hr" <?php echo ($edit && $data["unit_usage"] == "hr")?"selected":"";?>>Hr.</option>									
									<option value="days" <?php echo ($edit && $data["unit_usage"] == "days")?"selected":"";?>>Days</option>									
									<option value="nos" <?php echo ($edit && $data["unit_usage"] == "nos")?"selected":"";?>>Nos.</option>									
								</select>
							</div>
							<div class="col-md-2">Approved By<span class="require-field">*</span> :</div>
                            <div class="col-md-2">
								<input name="approved_by" class="form-control validate[required]" value="<?php echo ($edit)?$data["approved_by"]:"";?>">
							</div>
						</div>
						<div class="form-row">
                            <div class="col-md-2">Details of Usage :</div>
                            <div class="col-md-8">
								<input name="usage_detail" class="form-control" value="<?php echo ($edit)?$data["usage_detail"]:"";?>">
							</div>					
						</div>
						
						<div class="form-row">
						<br>
                            <div class="col-md-2 col-md-offset-2">
								<button type="submit" value="Prepare E.L" class="btn btn-primary"><?php echo $button_text; ?></button>
							</div>
							<div class="col-md-2 col-md-offset-2">
								<a href="<?php echo $this->request->base;?>/Assets/equipmentlogrecord" class="btn btn-primary" >Cancel</a>
							</div>
							<br>
						</div>
                </div>
			<?php echo $this->Form->end();?>
		</div>
<?php } ?>
</div>
<script>
$(document).ready(function(){
	$("#owned_box").hide();
	// $("#rent_box input,#rent_box select").attr("disabled", "disabled");
	
	$(".toggle_box").click(function(){
		var box = $(this).val();
		if(box == "rent")
		{
			$("#owned_box").hide();
			$("#rent_box").show();	
			$("#owned_box input,#owned_box select").attr("disabled", "disabled");	
			$("#rent_box input,#rent_box select").removeAttr("disabled");	
		}else{
			$("#owned_box").show();
			$("#rent_box").hide();
			$("#rent_box input,#rent_box select").attr("disabled", "disabled");
			$("#owned_box input,#owned_box select").removeAttr("disabled");
		}
	});
});
</script>
<?php
if($edit && $data["ownership"] == "rent")
{?>
<script>
$(document).ready(function(){
	$("#owned_box").hide();
	$("#rent_box").show();	
	$("#owned_box input,#owned_box select").attr("disabled", "disabled");	
	$("#rent_box input,#rent_box select").removeAttr("disabled");	
});
</script>
<?php 
}
else{ ?>
<script>
$(document).ready(function(){
	$("#owned_box").hide();
	$("#rent_box").show();
	// $("#rent_box input,#rent_box select").attr("disabled", "disabled");
	// $("#owned_box input,#owned_box select").removeAttr("disabled");
});
</script>
<?php }
 ?>