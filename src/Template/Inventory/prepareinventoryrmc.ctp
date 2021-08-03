<?php
use Cake\Routing\Router;
?>

<script type="text/javascript">
var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;

jQuery(document).ready(function() {
	jQuery('#user_form').validationEngine();
	jQuery('#rmc_date').datepicker({
		dateFormat: "dd-mm-yy",
		  changeMonth: true,
	        changeYear: true,
	        yearRange:'-65:+0',
			maxDate: new Date(),
	        onChangeMonthYear: function(year, month, inst) {
	            jQuery(this).val(month + "-" + year);
	        }                    
    });
	
	jQuery("body").on("change", "#asset_id", function(event){ 
	 var project_id = $("#project_id").val();
	  if(project_id == '')
	  {
		alert('Please select project first');
		$("#asset_id").select2("val","");
		return false;
	  }
	  var asset_name  = jQuery(this).val() ;
		/* alert(product_id);
		return false; */
	   var curr_data = {	 						 					
	 					asset_name : asset_name,	 					
	 					};	 				
	 	 jQuery.ajax({
			headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'getassetid'));?>",
                data:curr_data,
                async:false,
                success: function(response){					
					var json_obj = jQuery.parseJSON(response);					
					jQuery('#asset_code').val(json_obj['asset_code']);					
					return false;
                },
                error: function (e) {
                     alert('Error');
                     console.log(e.responseText);
                }
            });	
	});
	
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
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'projectdetailpr'));?>",
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
                     console.log(e.responseText);
                }
            });	
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
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'loadrmcprojectasset'));?>",
                data:curr_data,
                async:false,
                success: function(response){
					var result = jQuery.parseJSON(response)
					jQuery('#asset_id').select2("val","");
					jQuery('select#asset_id').empty();
					jQuery('select#asset_id').append(result);
					return false;
                },
                error: function (e) {
                     alert('Error');
                }
            });	
	});
	
	jQuery("body").on("change", "input[type=file]", function () {
		
		var file = this.files[0];
		//File Size Check
		if (file.size > 51200000) {
			alert("Too large file Size. Only file smaller than 25 MB can be uploaded.");
			$(this).replaceWith('<input type="file" name="attach_file[]">');
			return false;
		}
	});
	
	$("body").on("click",".del_file",function(){
		$(this).parentsUntil('.del_parent').remove();
	});

	$(".create_field").click(function(){
	var label = $(".add_label").val();
	if(label == "")
	{
		alert("Please Type Attachment Name.");
		$(".add_label").focus();
		return false;
	}
	$(".add_label").val("");
	var field = "<div class='del_parent'><div class='form-row'><div class='col-md-2'>"+ label +"<input type='hidden' name='attach_label[]' value='"+label+"' class='form-control'></div><div class='col-md-4'><input type='file' name='attach_file[]'class='imageUpload'><span class='required red notice'></span></div><div class='col-md-2'><span class='del_file btn btn-danger'>x Remove</span></div></div></div>";
	$(".add_field").append(field);
});
} );
function ValidateExtension(){
		m=0;
		$('.imageUpload').each(function(){
			if($(this).val() != '') {
				var imageUpload=$(this).val();
				var allowedFiles = ["jpeg","jpg","png","pdf","csv"];
				
				var regex = new RegExp("([a-zA-Z0-9\s_\\.\-:])+(" + allowedFiles.join('|') + ")$");
				if (!regex.test(imageUpload.toLowerCase())) {
					$(this).siblings('.notice').html("<?php echo $this->request->session()->read('image_validation'); ?>");
					m++;
				}
				else{
					$(this).siblings('.notice').html(" ");
				}
			}
		});
			if(m>0){
			return false;
			}
        }
</script>	

<div class="col-md-10" >
<?php 
if(!$is_capable)
	{
		$this->ERPfunction->access_deniedmsg();
	}
else
{
?>				
                <div class="block block-fill-white">
					<div class="head bg-default bg-light-rtl">
						<h2><?php echo 'Prepare RMC Issue';?>  </h2>
						<div class="pull-right">
						<a href="<?php echo $this->ERPfunction->action_link('Inventory','index');?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
						</div>
					</div>
					
					<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>	
					
					 <div class="content controls">
						<div class="form-row">
                            <div class="col-md-2">Project Code<span class="require-field">*</span> </div>
                            <div class="col-md-4"><input type="text" name="project_code" id="project_code" value=""
							class="form-control validate[required]" value="" readonly="true"/></div>
							<div class="col-md-2">Project Name *</div>
                            <div class="col-md-4">
								<select class="select2"  required="true"   style="width: 100%;" name="project_id" id="project_id">
								<option value="">--Select Project--</Option>
								<?php 
									foreach($projects as $retrive_data)
									{
										echo '<option value="'.$retrive_data['project_id'].'">'.
										$retrive_data['project_name'].'</option>';
									}
								?>
								</select>
							</div>
                        </div>
						<div class="form-row">                        
                            <div class="col-md-2 text-right">Date</div>
                            <div class="col-md-4"><input type="text" name="rmc_date" id="rmc_date" 
							value="<?php echo $this->ERPfunction->get_date(date('Y-m-d'));?>" onkeydown="return false" class="form-control" value=""/></div>
                        </div>
						<div class="form-row">
                            <div class="col-md-2">Asset Code<span class="require-field">*</span> </div>
                            <div class="col-md-4"><input type="text" name="asset_code" id="asset_code" value=""
							class="form-control validate[required]" value="" readonly="true"/></div>
							<div class="col-md-2">Asset Name *</div>
                            <div class="col-md-4">
								<select class="select2"  required="true"   style="width: 100%;" name="asset_id" id="asset_id">
								<option value="">--Select Asset--</Option>
								<?php 
									foreach($asset_names as $retrive_data)
									{
										echo '<option value="'.$retrive_data['asset_id'].'">'.
										$retrive_data['asset_name'].'</option>';
									}
								?>
								</select>
							</div>
                        </div>
						<div class="form-row">
                            <div class="col-md-2" style="padding: 0;">Vendor Name/Asset Name*</div>
                            <div class="col-md-10">
								<?php  echo $this->Form->select("agency_name",$vendor_list,["empty"=>" ","class"=>"select2 asst_list","id"=>"","style"=>"width:100%;","required"=>true]);?>
							</div>  
						</div>
                        <div class="form-row">
							<div class="col-md-2">Operator's Name<span class="require-field">*</span> </div>
							<div class="col-md-4"><input type="text" name="operator_name" id="operator_name" value="" class="form-control validate[required]" value=""/></div>
							<div class="col-md-2">Order By<span class="require-field">*</span> </div>
							<div class="col-md-4"><input type="text" name="order_by" id="order_by" value="" class="form-control validate[required]" value=""/></div>
						</div>
						<div class="form-row">
						<div class="col-md-2">Usage</div>
						<div class="col-md-4"><textarea name="usage" id="usage" class="form-control"></textarea></div>
						</div>
						
						<div class="form-row">
							<div class="col-md-2">Concrete Grade *</div>
                            <div class="col-md-4">
								<select class="select2" required="true" style="width: 100%;" name="concrete_grade" id="concrete_grade">
								<option value="">--Select Concretegrade--</Option>
								<?php 
									foreach($concrete_grade as $retrive_data)
									{
										echo '<option value="'.$retrive_data['id'].'">'.
										$retrive_data['concrete_grade'].'</option>';
									}
								?>
								</select>
							</div>
							<div class="col-md-2">Total Quantity Supplied (cum)<span class="require-field">*</span> </div>
							<div class="col-md-4"><input type="text" name="total_quantity" id="total_quantity" value="" class="form-control validate[required,custom[number]]"/></div>
                        </div>
						
						<div class="form-row">
						<div class="col-md-2">Start Time<span class="require-field">*</span> </div>
						<div class="col-md-4"><input type="text" name="start_time" id="start_time" value="" class="form-control validate[required]"/></div>
						<div class="col-md-2">End Time<span class="require-field">*</span> </div>
						<div class="col-md-4"><input type="text" name="end_time" id="end_time" value="" class="form-control validate[required]"/></div>
						</div>
						
						<div class="form-row">							
	                            <div class="col-md-2"> Attachment</div>
	                            <div class="col-md-4">
									<input class="add_label form-control">
								</div>
								<div class="col-md-2">
									<a href="javascript:void(0)" class="create_field text-center form-control">+&nbsp;Add</a>
								</div>
								
						</div>
						<div class="add_field">
							
                        </div>
						<div class="form-row">
                            <div class="col-md-2"></div>
                            <div class="col-md-4"><button type="submit" class="btn btn-primary" onclick="return ValidateExtension()">Prepare R.M.C. Issue</button></div>
                        </div>
					</div>
					
				<?php $this->Form->end(); ?>
			</div>
<?php } ?>
         </div>
