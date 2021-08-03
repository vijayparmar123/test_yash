<?php
use Cake\Routing\Router;
?>

<script type="text/javascript">
var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
jQuery(document).ready(function() {
	jQuery('#user_form').validationEngine();
	jQuery('#pr_date,#as_on_date').datepicker({
		dateFormat: "dd-mm-yy",
		  changeMonth: true,
	        changeYear: true,
	        yearRange:'-65:+0',
			maxDate: new Date(),
	        onChangeMonthYear: function(year, month, inst) {
	            jQuery(this).val(month + "-" + year);
	        }                    
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
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'getprojectwisematerial'));?>",
                data:curr_data,
                async:false,
                success: function(response){					
					jQuery('select.material_id').empty();
					jQuery('select.material_id').append(response);
					return false;
                },
                error: function (e) {
                     alert('Error');
                }
            });	
	});
	
	jQuery("body").on("change", ".material_id", function(event){ 
	 
	  var material_id  = jQuery(this).val();
	  var row_id  = jQuery(this).attr('data-id');
	  var project_id  = jQuery("#project_id").val();
	  
      if(project_id != "" && material_id != "")
	  {		  
	   var curr_data = {	 						 					
	 					project_id : project_id,material_id : material_id	 					
	 					};	 				
	 	 jQuery.ajax({
				headers: {
					'X-CSRF-Token': csrfToken
				},
                type:"POST",
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'projectwisematerialdetail'));?>",
                data:curr_data,
                async:false,
                success: function(response){					
					var json_obj = jQuery.parseJSON(response);					
					jQuery('#current_balance_'+row_id).val(json_obj['current_stock']);						
					jQuery('#min_stock_level_'+row_id).val(json_obj['min_stock_level']);						
					return false;
                },
                error: function (e) {
                     alert('Error');
                     console.log(e.responseText);
                }
            });
	  }			
	});
	
	jQuery("body").on("change", ".material_id", function(event){
	var material_id  = jQuery(this).val() ;
	var row_id  = jQuery(this).attr('data-id') ;
	
	var ids = [];
	$('select.material_id').not(this).each(function( index, value ) {
			if(jQuery(this).attr('value') != '')
			{
				ids.push(jQuery(this).attr('value'));
			}
	});
	if(jQuery.inArray( material_id, "["+ids+"]" ) >  -1){
		alert("You can't select same material again");
		$(this).select2('val', '');
		$("#material_code_"+row_id).html('');
		$("#unit_name_"+row_id).html('');
	}else{
		// alert('not selected');
	}
  });
	
	jQuery("#add_newrow").click(function(){
		//jQuery(this).attr("disabled", "disabled");
		var row_type = jQuery(".row_type:checked").val();
		//var row_id = jQuery("tbody > tr").length;
		var row_len = jQuery(".row_number").length;
		if(row_len > 0)
			{
				var num = jQuery(".row_number:last").val();
				var row_id = parseInt(num) + 1;
			}
			else
			{
				var row_id = 0;
			}
		var project_id = $("#project_id").val();
		var action = 'add_newrow';
		if(row_type == "textfield")
		{
			var class_len = jQuery(".text_data").length;
			if(class_len > 0)
			{
				var last_code = jQuery(".text_data:last").val();
			}
			else
			{
				var last_code = 0;
			}
		}
		else 
		{
			var last_code = 0;
		}
		jQuery.ajax({
			headers: {
					'X-CSRF-Token': csrfToken
				},
                       type: 'POST',
                      url: '<?php echo Router::url(["controller" => "Ajaxfunction","action" => "addnewrow"]);?>',
                     data : {row_id:row_id,row_type:row_type,last_code:last_code,project_id:project_id},
                     success: function (response)
                        {	
                            jQuery("tbody").append(response);
							//jQuery('#material_id_'+row_id).select2();
							jQuery('.delivery_date').datepicker({
								 changeMonth: true,
							  changeYear: true,
							  dateFormat: "dd-mm-yy"
							});
							jQuery('#material_id_'+row_id).select2();
							jQuery('#brand_id_'+row_id).select2();
							return false;
						},
                    error: function(e) {
                 alert("An error occurred: " + e.responseText);
                    console.log(e);
                }
       });
	});
	jQuery('.delivery_date').datepicker({
		 changeMonth: true,
      changeYear: true,
	  dateFormat: "dd-mm-yy"
	});
	jQuery("body").on("change", ".material_id", function(event){ 
	 var row_id = jQuery(this).attr('data-id');
	  var material_id  = jQuery(this).val() ;
	  var project_id  = jQuery("#project_id").val() ;
	  if(project_id == "")
	  {
		alert("Please Select Project First");  
		return false;
	  }
		/* alert(material_id);
		return false;  */  
	   var curr_data = {	 						 					
	 					material_id : material_id,	 					
	 					};	 				
	 	 jQuery.ajax({
			headers: {
					'X-CSRF-Token': csrfToken
				},
                type:"POST",
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'getmaterialbrandlist'));?>",
                data:curr_data,
                async:false,
                success: function(response){					
					var json_obj = jQuery.parseJSON(response);					
					
					jQuery('#brand_id_'+row_id).html();
					jQuery('#brand_id_'+row_id).html(json_obj['itemlist']);
					jQuery('#brand_id_'+row_id).select2();
					jQuery('#unit_name_'+row_id).html();
					jQuery('#unit_name_'+row_id).html(json_obj['unit_name']);
					jQuery('#material_code_'+row_id).html();
					jQuery('#material_code_'+row_id).html(json_obj['material_code']);					
					return false;
                },
                error: function (e) {
                     alert('Error');
                }
            });
	
  });
  
  jQuery('body').on('click','.trash',function(){
	  
		/* var row_id = jQuery(this).attr('data-id');		
		jQuery('table tr#row_id_'+row_id).remove();	 */
		jQuery(this).parents("tr").remove();
		return false;
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
	var field = "<div class='del_parent'><div class='form-row'><div class='col-md-2'>"+ label +"<input type='hidden' name='attach_label[]' value='"+label+"' class='form-control'></div><div class='col-md-4'><input type='file' name='attach_file[]' class='imageUpload'><span class='required red notice'></span></div><div class='col-md-2'><span class='del_file btn btn-danger'>x Remove</span></div></div></div>";
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
						<h2><?php echo $form_header;?>  </h2>
						<div class="pull-right">
						<a href="<?php echo $this->ERPfunction->action_link($back_url,$back_page);?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
						</div>
					</div>
					
					<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
					<input type="hidden" name="user_action" class="form-control" value="<?php echo $user_action;?>"/>	
					
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
                            <!--<div class="col-md-2">P.R.No</div>
                            <div class="col-md-4">
								<input type="text" name="prno" id="prno" value="" class="form-control" value=""/>
							</div>-->
                        
                            <div class="col-md-2 text-right">Date</div>
                            <div class="col-md-4"><input type="text" name="pr_date" id="pr_date" 
							value="<?php echo $this->ERPfunction->get_date(date('Y-m-d'));?>" onkeydown="return false" class="form-control" value=""/></div>
							 <div class="col-md-2 text-right">Time</div>
                            <div class="col-md-4"><input type="text" name="pr_time" id="pr_time" 
							value="<?php echo date('H:i');?>" class="form-control" value=""/></div>
                        </div>
					<!-- <div class="form-row">
                            <div class="col-md-2">Raised From:</div>
                            <div class="col-md-4">
								<?php 
								
								?>
								<select class="select2"  required="true"   style="width: 100%;" name="raise_from" id="raise_from">
								<option value="">--Select Project--</Option>
								<?php 
									// foreach($raise_from as $key => $data)
								// {
									// echo '<optgroup label="'.$this->ERPfunction->get_rolename($key).'" style = "text-transform: capitalize;">';
									// foreach($data as $user_data)
									// {
										// echo '<option value="'.$user_data['user_id'].'">'.
										// $this->ERPfunction->get_user_name($user_data['user_id']).'</option>';									
									// }
									// echo '</optgroup>';
								// }
								?>
								</select>
							</div>
                        
                             <div class="col-md-2">Contact No: (1)</div>
                            <div class="col-md-4">
								<input type="text" name="contact_no1" value="" class="form-control" value=""/>
							</div>
                        </div> -->
					<!-- <div class="form-row">
                            <div class="col-md-2">Forwarded To:</div>
                            <div class="col-md-4">
								<select class="select2"  required="true"   style="width: 100%;" name="forword_to">
								<option value="">--Select Project--</Option>
								<?php 
									// foreach($purchase_department as $retrive_data)
									// {
										// echo '<option value="'.$retrive_data['user_id'].'">'.
										// $this->ERPfunction->get_user_name($retrive_data['user_id']).'</option>';
									// }
								?>
								</select>
							</div>
                        
                            <div class="col-md-2">Contact No: (2)</div>
							<div class="col-md-4">
								<input type="text" name="contact_no2" value="" class="form-control" value=""/>
							</div>
                        </div> -->
						<div class="form-row">
							<div class="col-md-2">Contact No (1) *</div>
                            <div class="col-md-4">
								<input type="text" name="contact_no1" value="" class="form-control validate[required]" value=""/>
							</div>
							<div class="col-md-2">Contact No (2) *</div>
							<div class="col-md-4">
								<input type="text" name="contact_no2" value="" class="form-control validate[required]" value=""/>
							</div>
						</div>
						<div class="form-row">
						 
                            <table class="table table-bordered">
								<thead>
									<tr>
									<th rowspan="2">Material Code</th>
									<th colspan="6">Material / Item</th>
									<th rowspan="2">Delivery<br>Date<br>(Planned)</th>
									<th rowspan="2">Remarks</th>
									<th rowspan="2">Usage</th>
									<th rowspan="2">Action</th>
									</tr>
									<tr>
									<th style="width: 100%;">Description</th>
									<th>Make / Source</th>
									<th>Current Balance</th>
									<th>Min. Stock Level</th>
									<th>Quantity</th>
									<th>Unit</th>									
									</tr>
								</thead>
								<tbody>
									<tr id="row_id_0">
										<td><span id="material_code_0"></span>
										<input type="hidden" value="" name="material[m_code][]" id="m_code_0">
										<input type="hidden" value="0" name="row_number" class="row_number">
										</td>
										<td>
											<select class="select2 material_id" required="true" style="width: 100%;" name="material[material_id][]" id="material_id_0" data-id="0">
												<option value="">--Select Material--</Option>
												<?php 
													foreach($material_list as $retrive_data)
													{
														echo '<option value="'.$retrive_data['material_id'].'">'.
														$retrive_data['material_title'].'</option>';
													}
												?>
											</select>
										</td>
										<td>
											<select class="select2"  required="true"   name="material[brand_id][]" style="width: 100%;" id="brand_id_0">
												<option value="">--Select Item--</Option>												
											</select>
										</td>
										<td><input type="text" id="current_balance_0" class="form-control" style="padding: 0;width: 52px;" readonly="true"/></td>
										<td><input type="text" id="min_stock_level_0" class="form-control" style="padding: 0;width: 52px;" readonly="true"/></td>
										
										<td><input type="text" name="material[quantity][]" id="quantity_0" value="" class="form-control" style="padding: 0;width: 52px;"/></td>
										
										<td>
										<span id="unit_name_0"></span>
										<input type="hidden" value="" name="material[static_unit][]" id="static_unit_0" class="form-control" style="width:80px;">
										</td>
										
										<td><input type="text" name="material[delivery_date][]" id = "delivery_date_0" value="" class="form-control delivery_date" style="padding: 0;width: 67px;"/></td>
										<td style="padding: 2px;"><input type="text" name="material[name_of_subcontractor][]" id="name_of_subcontractor_0" value="" class="form-control" style="padding: 0;min-width: 53px;" /></td>
										<td style="padding: 2px;"><input type="text" name="material[usage][]" id="usage_0" value="" class="form-control" style="padding: 0;min-width: 53px;" /></td>
										<td>
											<span class="trash btn btn-danger" data-id="0"><i class="fa fa-trash"></i> Delete</span>
										</td>
									</tr>
								</tbody>
							</table>
							<button type="button" id="add_newrow" class="btn btn-default">Add New </button>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input name="row_type" type="radio" checked="checked" class="row_type" value="dropdown">Dropdown
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input name="row_type" type="radio" class="row_type" value="textfield">Text Field
							
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
                            <div class="col-md-4"><button type="submit" class="btn btn-primary" onclick="return ValidateExtension()" ><?php echo $button_text;?></button></div>
                        </div>
					</div>
					
				<?php $this->Form->end(); ?>
			</div>
<?php } ?>
         </div>
