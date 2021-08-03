<?php
use Cake\Routing\Router;
?>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('#user_form').validationEngine();
	jQuery('#sst_date').datepicker({
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
		/* alert(product_id);
		return false; */
	   var curr_data = {	 						 					
	 					project_id : project_id,	 					
	 					};	 				
	 	 jQuery.ajax({
                type:"POST",
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'projectdetailsst'));?>",
                data:curr_data,
                async:false,
                success: function(response){					
					var json_obj = jQuery.parseJSON(response);					
					jQuery('#project_code').val(json_obj['project_code']);						
					jQuery('#sst_no').val(json_obj['sst_no']);						
					return false;
                },
                error: function (e) {
                     alert('Error');
                }
            });	
	});
	jQuery("body").on("change", "#transfer_to", function(event){ 
	 
	  var project_id  = jQuery(this).val() ;
		/* alert(product_id);
		return false; */
	   var curr_data = {	 						 					
	 					project_id : project_id,	 					
	 					};	 				
	 	 jQuery.ajax({
                type:"POST",
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'projectdetailtransferto'));?>",
                data:curr_data,
                async:false,
                success: function(response){					
					var json_obj = jQuery.parseJSON(response);					
					jQuery('#transfer_to_projct_code').val(json_obj['project_code']);					
											
					return false;
                },
                error: function (e) {
                     alert('Error');
                }
            });	
	});
	jQuery("#add_newrow").click(function(){
		//jQuery(this).attr("disabled", "disabled");
		var row_id = jQuery("tbody > tr").length;
		var action = 'add_newrow';
		jQuery.ajax({
                       type: 'POST',
                      url: '<?php echo Router::url(["controller" => "Ajaxfunction","action" => "addnewrowinsst"]);?>',
                     data : {row_id:row_id},
                     success: function (response)
                        {	
                            jQuery("tbody").append(response);
							jQuery('#material_id_'+row_id).select2();
							jQuery('.delivery_date').datepicker({
								 changeMonth: true,
							  changeYear: true,
							  dateFormat: "dd-mm-yy"
							});
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
  
  /////////////////////////////////////////////////////////////////////////
	
	$( ".row_number" ).each(function() {
	  var project_id = jQuery("#project_id").val();
	  var date = jQuery("#sst_date").val();
	  var row_id = $( this ).val();
	  var material_id = jQuery("#material_id_"+row_id).val();
	  if(project_id != "" && date != "" && material_id != "" && row_id != "")
		{
			materialTillDateQuantity(project_id,date,material_id,row_id);
		}else{
			jQuery("#till_date_qty_"+row_id).html(0);
		}
	});
		
	jQuery("body").on("change", ".material_id", function(event){
		var project_id = jQuery("#project_id").val();
		var date = jQuery("#sst_date").val();
		var material_id = jQuery(this).val();
		var row_id = jQuery(this).attr("data-id");
		
		if(project_id != "" && date != "" && material_id != "" && row_id != "")
		{
			materialTillDateQuantity(project_id,date,material_id,row_id);
		}else{
			jQuery("#till_date_qty_"+row_id).html(0);
		}
	  
	});
	
	jQuery("body").on("change", "#sst_date", function(event){
		var project_id = jQuery("#project_id").val();
		var date = jQuery("#sst_date").val();
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
    
	// $( ".row_number" ).each(function() {
		// var project_id = jQuery('#project_id').val();
		// var row_id = jQuery(this).val();
		// var material_id = jQuery("#material_id_"+row_id).val();
		
		// if(project_id != "" && material_id != "" && row_id != "")
		// {
			// getMaterialStock(project_id,material_id,row_id);
		// }else{
			// jQuery("#till_date_qty_"+row_id).val(0);
		// }
	// });
	
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
	
	jQuery("body").on("change", ".transfer_qty", function(event){
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
  
  jQuery('body').on('click','.trash',function(){
		/* var row_id = jQuery(this).attr('data-id');
		jQuery('table tr#row_id_'+row_id).remove(); */	
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
						<h2><?php echo $form_header;?></h2>
						<div class="pull-right">
						<a href="<?php //echo $this->ERPfunction->action_link('Inventory','index');?>" onclick = "javascript:window.close();" class="btn btn-success"><i class="icon-arrow-left"></i> Back</a>
						</div>
					</div>
					
					<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
					<input type="hidden" name="user_action" class="form-control" value="<?php echo $user_action;?>"/>	
					
					 <div class="content controls">
						<div class="form-row">
                            <div class="col-md-2">Project Code</div>
                            <div class="col-md-4"><input type="text" name="project_code" id="project_code"
							class="form-control" value="<?php echo $this->ERPfunction->get_projectcode($sst_data['project_id']);?>" readonly="true"/></div>
							<div class="col-md-2">Project Name<span class="require-field">*</span></div>
                            <div class="col-md-4">
								<select class="select2"  required="true"   style="width: 100%;" name="project_id" id="project_id">
								<option value="">--Select Project--</Option>
								<?php 
									foreach($projects as $retrive_data)
									{
										echo '<option value="'.$retrive_data['project_id'].'" '.(($retrive_data['project_id'] == $sst_data['project_id'])?'selected':'').'>'.
										$retrive_data['project_name'].'</option>';
									}
								?>
								</select>
							</div>
                        </div>
						<div class="form-row">
                            <div class="col-md-2">S.S.T. No</div>
                            <div class="col-md-4">
								<input type="text" name="sst_no" id="sst_no" value="<?php echo $sst_data['sst_no']; ?>" class="form-control"/>
							</div>
                        
                            <div class="col-md-1">Date<span class="require-field">*</span></div>
                            <div class="col-md-2"><input type="text" onkeydown="return false" value="<?php echo date("d-m-Y",strtotime($sst_data['sst_date'])); ?>" name="sst_date" id="sst_date" 
							 class="form-control validate[required]"/>
							</div>
							<div class="col-md-1">Time<span class="require-field">*</span></div>
                            <div class="col-md-2"><input type="text" name="sst_time" value="<?php echo $sst_data['sst_time']; ?>" id="rbn_date" 
							 class="form-control validate[required]" value=""/>
							</div>
						</div>	
						<fieldset>
						<legend>Transfer To</legend>
						<div class="form-row">
                            <div class="col-md-2">Project Code</div>
                            <div class="col-md-4">
								<input type="text" name="transfer_to_projct_code" id="transfer_to_projct_code" value="<?php echo $this->ERPfunction->get_projectcode($sst_data['transfer_to']);?>" class="form-control"/>
							</div>  
							<div class="col-md-2">Project Name<span class="require-field">*</span></div>
                            <div class="col-md-4">
								<select class="select2"  required="true"   style="width: 100%;" name="transfer_to" id="transfer_to">
								<option value="">--Select Project--</Option>
								<?php 
									foreach($transfer_projects as $retrive_data)
									{
										echo '<option value="'.$retrive_data['project_id'].'" '.(($retrive_data['project_id'] == $sst_data['transfer_to'])?'selected':'').'>'.
										$retrive_data['project_name'].'</option>';
									}
								?>
								</select>
							</div>
                        </div>	
						</fieldset>
						<hr/>
						<div class="form-row">
                            <div class="col-md-2">Driver's Name<span class="require-field">*</span></div>
							 <div class="col-md-4">
								<input type="text" name="driver_name" value="<?php echo $sst_data['driver_name']; ?>" class="form-control validate[required]"/>
							</div> 
							<div class="col-md-2">Vehicle's No<span class="require-field">*</span></div>
							 <div class="col-md-4">
								<input type="text" name="vehicle_no" value="<?php echo $sst_data['vehicle_no']; ?>" class="form-control validate[required]"/>
							</div> 
                        </div>
						<div class="form-row">
                            <div class="col-md-12">As per your requirement, we are transferring the following material (s) to you / 
							your work site</div>
							 
                        </div>
					<!-- <div class="form-row">
                            <div class="col-md-2">Quantity Varified By</div>
                            <div class="col-md-4">
								<select class="select2"  required="true"   style="width: 100%;" name="quantity_varifiedy">
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
                        
                            <div class="col-md-2">Transferred By</div>
							<div class="col-md-4">
								<select class="select2"  required="true"   style="width: 100%;" name="transfer_by">
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
                            <div class="col-md-2">Approved By</div>
                            <div class="col-md-4">
								<select class="select2"  required="true"   style="width: 100%;" name="approved_by">
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
							<div class="col-md-2">Received By</div>
                            <div class="col-md-4">
								<select class="select2"  required="true"   style="width: 100%;" name="received_by">
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
                        </div> -->
						
						<div class="form-row">
						 
                            <table class="table table-bordered">
								<thead>
									<tr>
									<th rowspan="2">Material Code</th>
									<th colspan="5">Material / Item</th>
									<th rowspan="2">Intimated By/Approved By</th>
								<!-- <th rowspan="2">Reason for Transfer</th> -->
									
									</tr>
									<tr>
									<th>Description</th>									
									<th>Make/Source</th>
									<th>Till Date Balance Qty.</th>									
									<th>Quantity</th>																	
									<th>Unit</th>																	
									</tr>
								</thead>
								<tbody>
								<?php
								$i = 0;
								foreach($detail_data as $detail)
								{
								?>
									<tr id="row_id_<?php echo $i; ?>">
										<input type='hidden' value='<?php echo $i; ?>' name='row_number' class='row_number'>
										<td><span id="material_code_<?php echo $i; ?>"><?php echo $this->ERPfunction->get_material_item_code_bymaterialid($detail['material_id']);?></span></td>
										<td class="col-md-3">
											<select class="select2 material_id" style="width: 100%;" name="material[material_id][]" id="material_id_<?php echo $i; ?>" data-id="<?php echo $i; ?>">
												<option value="">--Select Material--</Option>
												<?php 
													foreach($material_list as $retrive_data)
													{
														echo '<option value="'.$retrive_data['material_id'].'" '.(($retrive_data['material_id'] == $detail['material_id']) ? 'selected':'').'>'.
														$retrive_data['material_title'].'</option>';
													}
												?>
											</select>
										</td>
										<td>
											<select class="select2"  required="true"   name="material[brand_id][]" style="width: 100%;" id="brand_id_<?php echo $i; ?>">
												<option value="">--Select Item--</Option>
												<option value="<?php echo $detail['brand_id'];?>" selected><?php echo $this->ERPfunction->get_brandname($detail['brand_id']);?></Option>												
											</select>
										</td>
										<td><input type="text" readonly="true" name="material[till_date_qty][]" id="till_date_qty_<?php echo $i; ?>" value="" class="no-padding form-control"/></td>
										<td class="col-md-2"><input type="text" name="material[quantity][]" id="quantity_<?php echo $i; ?>" row-id="<?php echo $i; ?>" value="<?php echo $detail['quantity'];?>" class="form-control validate[required] transfer_qty"/></td>
										<td><span id="unit_name_<?php echo $i; ?>"><?php echo $this->ERPfunction->get_items_units($detail['material_id']);?></span></td>
										<input type="hidden" value="<?php echo $detail['sst_detail_id']; ?>" name="material[detail_id][]">
										<td><input type="text" name="material[intimated_by][]" id = "intimated_by_<?php echo $i; ?>" value="<?php echo $detail['intimated_by'];?>" class="form-control"/></td>
									<!-- <td><input type="text" name="material[transfer_reason][]" id = "transfer_reason_0" value="" class="form-control"/></td> -->
										
									</tr>
									<?php
						$i++;
						}
						?>
								</tbody>
							</table>
                        </div>
						
						<div class="form-row">
                            <div class="col-md-2"></div>
                            <div class="col-md-4"><button type="submit" class="btn btn-primary"><?php echo $button_text;?></button></div>
                        </div>
					</div>
					
				<?php $this->Form->end(); ?>
			</div>
<?php } ?>
         </div>