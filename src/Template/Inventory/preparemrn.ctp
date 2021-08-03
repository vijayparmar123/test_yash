<?php
use Cake\Routing\Router;
?>
<script type="text/javascript">
var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;

jQuery(document).ready(function() {
	jQuery('#user_form').validationEngine();
	
	jQuery('#mrn_date').datepicker({
		dateFormat: "dd-mm-yy",
		  changeMonth: true,
	        changeYear: true,
	        yearRange:'-65:+0',
			maxDate: new Date(),
			minDate: -7,
	        onChangeMonthYear: function(year, month, inst) {
	            jQuery(this).val(month + "-" + year);
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
  
	jQuery("body").on("change", "#vendor_userid", function(event){ 
		 var vendor_userid  = jQuery(this).val() ;
		/* alert(product_id);
		return false; */
	   var curr_data = {	 						 					
	 					vendor_userid : vendor_userid,	 					
	 					};	 				
	 	 jQuery.ajax({
			headers: {
					'X-CSRF-Token': csrfToken
				},
                type:"POST",
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'vendordetail'));?>",
                data:curr_data,
                async:false,
                success: function(response){					
					var json_obj = jQuery.parseJSON(response);					
					jQuery('#vendor_id').val(json_obj['vendor_id']);						
					jQuery('#vendor_address').val(json_obj['address_1']);						
					jQuery('#vendor_delivery_address').val(json_obj['delivery_place']);						
					return false;
                },
                error: function (e) {
                     alert('Error');
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
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'inmrnprojectdetail'));?>",
                data:curr_data,
                async:false,
                success: function(response){					
					var json_obj = jQuery.parseJSON(response);					
					jQuery('#project_code').val(json_obj['project_code']);						
					jQuery('#mrn_no').val(json_obj['mrn_no']);						
					return false;
                },
                error: function (e) {
                     alert('Error');
                }
            });	
	});
	
	jQuery('.viewmodal').click(function(){
			jQuery('.modal-content').html('');
			var project_id = jQuery('#project_id').val();
			//alert(project_id);return false;
			if(project_id == '')
			{
				alert('Please select project.');
				return false;
			}
			var curr_data = {project_id : project_id};	 				
			jQuery.ajax({
				headers: {
					'X-CSRF-Token': csrfToken
				},
                type:"POST",
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'projectmaterial'));?>",
                data:curr_data,
                async:false,
                success: function(response){                    
					jQuery('.modal-content').html(response);
					jQuery('.select2').select2();
                },
                beforeSend:function(){
							jQuery('#modal-view').html('<center><img src=../images/4.gif width=120px><div><h3>Loading...</h3></div></center>');
						},
		        error: function(e) {
		                console.log(e);
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
					$('.material_id').select2("val", "");
					jQuery('select.material_id').empty();
					jQuery('select.material_id').append(response);
					return false;
                },
                error: function (e) {
                     alert('Error');
                }
            });	
	});
	
	/////////////////////////////////////////////////////////////////////////
	jQuery("body").on("change", ".material_id", function(event){
		var project_id = jQuery("#project_id").val();
		var date = jQuery("#mrn_date").val();
		var material_id = jQuery(this).val();
		var row_id = jQuery(this).attr("data-id");
		
		if(project_id != "" && date != "" && material_id != "" && row_id != "")
		{
			materialTillDateQuantity(project_id,date,material_id,row_id);
		}else{
			jQuery("#till_date_qty_"+row_id).html(0);
		}
	  
	});
	
	jQuery("body").on("change", "#mrn_date", function(event){
		var project_id = jQuery("#project_id").val();
		var date = jQuery("#mrn_date").val();
		$( ".row_number" ).each(function() {
		  var row_id = $( this ).val();
		  var material_id = jQuery("#material_id_"+row_id).val();
		  if(project_id != "" && date != "" && material_id != "" && row_id != "")
			{
				materialTillDateQuantity(project_id,date,material_id,row_id);
			}else{
				jQuery("#till_date_qty_"+row_id).html(0);
			}
		});
	});
	
	function materialTillDateQuantity(project_id,date,material_id,row_id)
	{
		var curr_data = {	 						 					
							project_id : project_id,	 					
							date : date,	 					
							material_id : material_id,
							excluding_record : "no",
						};	 				
		jQuery.ajax({
			headers: {
					'X-CSRF-Token': csrfToken
				},
			type:"POST",
			url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'getmaterialstilldatestock'));?>",
			data:curr_data,
			async:false,
			success: function(response){			
				jQuery("#till_date_qty_"+row_id).val(response);
			},
			error: function (e) {
				 alert('Error');
			}
		});
	}
	/////////////////////////////////////////////////////////////////////////
	
	
	// jQuery("body").on("change", ".material_id", function(event){ 
		// var project_id = jQuery('#project_id').val();
		// var material_id = jQuery(this).val();
		// var row_id = jQuery(this).attr('data-id');
		// if(project_id != '' && material_id != '' && row_id != '')
		// {
			// getMaterialStock(project_id,material_id,row_id);
		// }else{
			// jQuery("#till_date_qty_"+row_id).val(0);
		// }
	// });
	
	// jQuery("body").on("change", "#project_id", function(event){
		// var project_id = jQuery('#project_id').val();
		// $( ".row_number" ).each(function() {
			// var row_id = jQuery(this).val();
			// var material_id = jQuery("#material_id_"+row_id).val();
			
			// if(project_id != "" && material_id != "" && row_id != "")
			// {
				// getMaterialStock(project_id,material_id,row_id);
			// }else{
				// jQuery("#till_date_qty_"+row_id).val(0);
			// }
		// });
	// });
	
	// function getMaterialStock(project_id,material_id,row_id)
	// {
		// var curr_data = {	 						 					
							// project_id : project_id,	 					
							// material_id : material_id,					
						// };	 				
		// jQuery.ajax({
			// type:"POST",
			// url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'getmaterialstock'));?>",
			// data:curr_data,
			// async:false,
			// success: function(response){			
				// jQuery("#till_date_qty_"+row_id).val(response);
			// },
			// error: function (e) {
				 // alert('Error');
			// }
		// });
	// }
	
	jQuery("body").on("change", ".return_qty", function(event){
		var row = jQuery(this).attr("row-id");
		var return_qty = jQuery(this).val();
		var till_date_qty = jQuery("#till_date_qty_"+row).val();
		
		var return_qty = parseFloat(return_qty);
		var till_date_qty = parseFloat(till_date_qty);
		
		if(return_qty > till_date_qty)
		{
			jQuery(this).val('');
			alert("Not allow return quantity greater than till date issued quantity.");
			return false;
		}
	});
	
	jQuery("#add_newrow").click(function(){
		//jQuery(this).attr("disabled", "disabled");
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
		// alert(row_id);
		var project_id = $("#project_id").val();
		var action = 'add_newrow';
		jQuery("#add_newrow").hide();
		jQuery.ajax({
			headers: {
					'X-CSRF-Token': csrfToken
				},
                       type: 'POST',
                      url: '<?php echo Router::url(["controller" => "Ajaxfunction","action" => "addnewrowinmrn"]);?>',
                     data : {row_id:row_id,project_id:project_id},
                     success: function (response)
                        {	
                            jQuery("tbody").append(response);
							jQuery('#material_id_'+row_id).select2();
							jQuery('#brand_id_'+row_id).select2();
							jQuery('.delivery_date').datepicker({
								 changeMonth: true,
							  changeYear: true,
							  dateFormat: "dd-mm-yy"
							});
							jQuery("#add_newrow").show();
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
		var row_id = jQuery(this).attr('data-id');		
		jQuery('table tr#row_id_'+row_id).remove();		
		return false;
	});
	
} );
</script>	
<div class="modal fade " id="load_modal" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content"></div>
    </div>
</div>
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
						<h2><?php echo $form_header;?></h2>
						<div class="pull-right">
						<a href="<?php echo $this->ERPfunction->action_link('Inventory','index');?>" class="btn btn-success"><i class="icon-arrow-left"></i> Back</a>
						</div>
					</div>
					
					<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
					<input type="hidden" name="user_action" class="form-control" value="<?php echo $user_action;?>"/>	
					
					 <div class="content controls">
						<div class="form-row">
                            <div class="col-md-2">Project Code</div>
                            <div class="col-md-4"><input type="text" name="project_code" id="project_code" value=""
							class="form-control validate[required]" value="" readonly="true"/></div>
							<div class="col-md-2">Project Name*</div>
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
                            <!--<div class="col-md-2">M.R.N. No</div>
                            <div class="col-md-4">
								<input type="text" name="mrn_no" id="mrn_no" value="" class="form-control" value=""/>
							</div>-->
                        
                            <div class="col-md-2">Date*</div>
                            <div class="col-md-4"><input type="text" onkeydown="return false" name="mrn_date" id="mrn_date" 
							value="" class="form-control validate[required]" value=""/></div>
							
							<div class="col-md-2">Time*</div>
                            <div class="col-md-2"><input type="text" name="mrn_time" id="mrn_time" 
							value="" class="form-control validate[required]"/></div>
							
                        </div>						
						<div class="form-row">
                            <div class="col-md-2">Vendor Name*</div>
                            <div class="col-md-4">
								<select class="select2"  required="true"   style="width: 100%;" name="vendor_user" id="vendor_userid">
								<option value="">--Select Vendor--</Option>
								<?php 
									foreach($vendor_department as $retrive_data)
									{
										echo '<option value="'.$retrive_data['user_id'].'">'.
										$this->ERPfunction->get_vendor_name($retrive_data['user_id']).'</option>';
									}
								?>
								</select>
							</div>  
							<div class="col-md-2">Vendor ID</div>
                            <div class="col-md-4">
								<input type="text" name="vendor_id" id="vendor_id" value="" class="form-control"/>
							</div>  
                        </div>
						
						<div class="form-row">
                            <div class="col-md-2">Driver's Name*</div>
                            <div class="col-md-4">
								<input type="text" name="driver_name" value="" class="form-control validate[required]"/>
							</div>  
							<div class="col-md-2">Vehicle's No*</div>
                            <div class="col-md-4">
								<input type="text" name="vehicle_no" value="" class="form-control validate[required]"/>
							</div>  
                        </div>
						<!--
						<div class="form-row">
                            <div class="col-md-2">Quantity Varified By</div>
                            <div class="col-md-4">
								<select class="select2"  required="true"   style="width: 100%;" name="quality_varifiedby">
								<option value="">--Select user--</Option>
								<?php 
									// foreach($ceo_department as $retrive_data)
									// {
										// echo '<option value="'.$retrive_data['user_id'].'">'.
										// $this->ERPfunction->get_user_name($retrive_data['user_id']).'</option>';
									// }
								?>
								</select>
							</div>
                        
                            <div class="col-md-2">Inspected By</div>
							<div class="col-md-4">
								<select class="select2"  required="true"   style="width: 100%;" name="inspected_by">
								<option value="">--Select user--</Option>
								<?php 
									// foreach($ceo_department as $retrive_data)
									// {
										// echo '<option value="'.$retrive_data['user_id'].'">'.
										// $this->ERPfunction->get_user_name($retrive_data['user_id']).'</option>';
									// }
								?>
								</select>
							</div>
                        </div>
						<div class="form-row">
                            <div class="col-md-2">Authorized By</div>
                            <div class="col-md-4">
								<select class="select2"  required="true"   style="width: 100%;" name="authorise_by">
								<option value="">--Select user--</Option>
								<?php 
									// foreach($ceo_department as $retrive_data)
									// {
										// echo '<option value="'.$retrive_data['user_id'].'">'.
										// $this->ERPfunction->get_user_name($retrive_data['user_id']).'</option>';
									// }
								?>
								</select>
							</div>                          
                        </div>
						-->
						<div class="form-row">
						 
                            <table class="table table-bordered">
								<thead>
									<tr>
									<th rowspan="2">Material Code</th>
									<th colspan="2">Material / Item</th>
									<th>Till Date Balance Qty.</th>
									<th rowspan="2" style="width:15px;">Returned<br>Qty./Weight.</th>
									<th rowspan="2">Unit</th>
									<th rowspan="2">Reason for Return</th>									
									<th rowspan="2">Action</th>
									</tr>
									<tr>
									<th style="width:43%;">Description</th>									
									<th>Make/Source</th>																	
									</tr>
								</thead>
								<tbody>
									<tr id="row_id_0">
										<input type='hidden' value='0' name='row_number' class='row_number'>
										<td><span id="material_code_0"></span></td>
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
										<td><input type="text" readonly="true" name="material[till_date_qty][]" id="till_date_qty_0" value="" class="no-padding form-control"/></td>
										<td><input type="text" name="material[quantity][]" row-id="0" id="quantity_0" value="" style="padding: 0;" class="form-control validate[required] return_qty"/></td>
										<td><span id="unit_name_0"></span></td>
										<td><input type="text" name="material[remarks][]" id = "remarks_0" value="" class="form-control"/></td>
										
										<td>
											<span class="trash btn btn-danger" data-id="0"><i class="fa fa-trash"></i> Delete</span>
										</td>
									</tr>
								</tbody>
							</table>
							<button type="button" id="add_newrow" class="btn btn-default">Add New </button>
							
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						
							<!--<button type="button" id="material_add" data-type="material_add" data-toggle="modal" 
							data-target="#load_modal" class="btn btn-default viewmodal" style="">Add Material </button>-->
                        </div>
						<div class="form-row">
                            <div class="col-md-2"></div>
                            <div class="col-md-4"><button type="submit" class="btn btn-primary"><?php echo $button_text;?></button></div>
                        </div>
					</div>
					
				<?php $this->Form->end(); ?>
			</div>
<?php }?>
         </div>