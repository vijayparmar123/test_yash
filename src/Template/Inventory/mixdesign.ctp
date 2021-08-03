<?php
use Cake\Routing\Router;
?>

<script type="text/javascript">
var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;

jQuery(document).ready(function() {
	jQuery('#user_form').validationEngine();
	
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
		
		jQuery.ajax({
			headers: {
					'X-CSRF-Token': csrfToken
				},
                       type: 'POST',
                      url: '<?php echo Router::url(["controller" => "Ajaxfunction","action" => "newrowrmcinventory"]);?>',
                     data : {row_id:row_id,project_id:project_id},
                     success: function (response)
                        {	
                            jQuery("tbody").append(response);
							jQuery('#material_id_'+row_id).select2();
							return false;
						},
                    error: function(e) {
                 alert("An error occurred: " + e.responseText);
                    console.log(e);
                }
       });
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
	
} );
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
						<h2><?php echo "Mix Design";?>  </h2>
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
						<div class="col-md-2">Concrete Grade<span class="require-field">*</span> </div>
                        <div class="col-md-4"><input type="text" name="concrete_grade" id="concrete_grade" value="" class="form-control validate[required]"/></div>
						</div>
						<div class="form-row">
						 
                            <table class="table table-bordered">
								<thead>
									<tr>
									<th>Material Code</th>
									<th>Material / Item</th>
									<th>Unit</th>									
									<th>Consumption in 1 CMT</th>									
									<th>Action</th>									
									</tr>
								</thead>
								<tbody>
									<tr id="row_id_0">
										<td><span id="material_code_0"></span>
										<input type="hidden" value="0" name="row_number" class="row_number">
										</td>
										<td>
											<select class="select2 material_id" style="width: 100%;" name="material[material_id][]" required="true" id="material_id_0" data-id="0">
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
										<span id="unit_name_0"></span>
										</td>
										<td>
										<input type="text" name="material[consumption][]" class="form-control validate[required,custom[number]]" id="consumption_0">
										</td>
										<td>
											<span class="trash btn btn-danger" data-id="0"><i class="fa fa-trash"></i> <span class="icon-trash"></span></span>
										</td>
									</tr>
								</tbody>
							</table>
							<button type="button" id="add_newrow" class="btn btn-default">Add New </button>
							
                        </div>
						<div class="add_field">
							
                        </div>
						<div class="form-row">
                            <div class="col-md-2"></div>
                            <div class="col-md-4"><button type="submit" class="btn btn-primary"><?php echo "Save";?></button></div>
                        </div>
					</div>
					
				<?php $this->Form->end(); ?>
			</div>
<?php } ?>
         </div>
