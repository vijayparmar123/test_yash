<?php
use Cake\Routing\Router;
?>
<script type="text/javascript">
var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;

jQuery(document).ready(function() {
	jQuery('#user_form').validationEngine();
	jQuery('#rbn_date').datepicker({
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
			headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'projectdetailrbn'));?>",
                data:curr_data,
                async:false,
                success: function(response){					
					var json_obj = jQuery.parseJSON(response);					
					jQuery('#project_code').val(json_obj['project_code']);						
					jQuery('#rbn_no').val(json_obj['rbn_no']);
					jQuery('.added_asset').remove();
					jQuery('.asst_list').append(json_obj['assets']);	
					jQuery('.select2').select2('destroy');
					jQuery('.select2').select2();					
					return false;
                },
                error: function (e) {
                     alert('Error');
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

<div class="col-md-10" >
<?php 
if(!$is_capable)
	{
		$this->ERPfunction->access_deniedmsg();
	}
else
{
$search_project_id = isset($this->request->params["pass"]["1"])?$this->request->params["pass"]["1"]:"";
?>				
                <div class="block block-fill-white">					
					<div class="head bg-default bg-light-rtl">
						<h2><?php echo $form_header;?></h2>
						<div class="pull-right">
						<a href="<?php //echo $this->ERPfunction->action_link('Inventory',"approverbn/{$search_project_id}");?>" onclick = "javascript:window.close();" class="btn btn-success"><i class="icon-arrow-left"></i> Back</a>
						</div>
					</div>
					
					<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
					<input type="hidden" name="user_action" class="form-control" value="<?php echo $user_action;?>"/>	
					
					 <div class="content controls">
						<div class="form-row">
                            <div class="col-md-2">Project Code<span class="require-field">*</span></div>
                            <div class="col-md-4"><input type="text" name="project_code" id="project_code"
							class="form-control validate[required]" value="<?php echo $this->ERPfunction->get_projectcode($rbndata['project_id']);?>" readonly="true"/></div>
							<div class="col-md-2">Project Name*</div>
                            <div class="col-md-4">
								<select class="select2"  required="true"   style="width: 100%;" name="" id="project_id" disabled >
								<option value="">--Select Project--</Option>
								<?php 
									foreach($projects as $retrive_data)
									{
										echo '<option value="'.$retrive_data['project_id'].'" '.(($retrive_data['project_id'] == $rbndata['project_id'])?'selected':'').'>'.
										$retrive_data['project_name'].'</option>';
									}
								?>
								</select>
								<input type="hidden" name="project_id" value="<?php echo $rbndata['project_id'];?>">
							</div>
                        </div>
						<div class="form-row">
                            <div class="col-md-2">R.B.N. No</div>
                            <div class="col-md-4">
								<input type="text" name="rbn_no" id="rbn_no" value="<?php echo $rbndata['rbn_no'];?>" class="form-control" readonly />
							</div>
                        
                            <div class="col-md-2">Date*</div>
                            <div class="col-md-4"><input type="text" name="rbn_date" onkeydown="return false" id="rbn_date" value="<?php echo $rbndata['rbn_date']->format('d-m-Y');?>" class="validate[required] form-control"/>
							</div>
						</div>						
						<div class="form-row">
                            <div class="col-md-2">Vendor / Asset Name*</div>
                            <div class="col-md-10">
								<?php  echo $this->Form->select("agency_name",$vendor_list,["default"=>[$rbndata['agency_name']],"class"=>"select2 asst_list","style"=>"width:100%","id"=>"","required"=>true]);?>
							</div>  							
                        </div>						
					
						<div class="form-row">
                            <table class="table table-bordered">
								<thead>
									<tr>
									<th rowspan="2">Material Code</th>
									<th colspan="4">Material / Item</th>
									<th rowspan="2">Name of Foreman</th>
									<th rowspan="2">Usage / Remarks</th>
									</tr>
									<tr>
									<th style="width:39%;max-width:39%">Description</th>																						
									<th>Make/Source</th>
									<th>Quantity</th>	
									<th>Unit</th>																	
									</tr>
								</thead>
								<tbody>
									<?php
										$i = 0;
										// debug($materials);
								foreach($items as $materials)
								{
									foreach($materials as $material)
									{ 
										 ?>
								
									<tr id="row_id_<?php echo $i;?>">
										<td><span id="material_code_<?php echo $i;?>"><?php echo $this->ERPfunction->get_material_item_code_bymaterialid($material['material_id']);?></span></td>
										<td>
											<select class="select2 material_id" style="width: 100%;" name="material[material_id][]" id="material_id_<?php echo $i;?>" data-id="<?php echo $i;?>">
												<option value="">--Select Material--</Option>
												<?php 
													foreach($material_list as $retrive_data)
													{
														echo '<option value="'.$retrive_data['material_id'].'" '.(($retrive_data['material_id'] == $material['material_id']) ? 'selected':'').'>'.
														$retrive_data['material_title'].'</option>';
													}
												?>
											</select>
										</td>
										<td>
											<select class="select2"  required="true"   name="material[brand_id][]" style="width: 100%;" id="brand_id_<?php echo $i;?>">
												<option value="">--Select Item--</Option>								
												<option value="<?php echo $material['brand_id'];?>" selected><?php echo $this->ERPfunction->get_brandname($material['brand_id']);?></Option>								
											</select>
										</td>
										<td><input type="text" name="material[quantity_reurn][]" id="quantity_reurn_<?php echo $i;?>" value="<?php echo $material['quantity_reurn'];?>" class="no-padding form-control"/>
										<input type="hidden" name="material[old_quantity_reurn][]" id="quantity_reurn_<?php echo $i;?>" value="<?php echo $material['quantity_reurn'];?>" class="no-padding form-control"/>
										</td>
										<td><span id="unit_name_<?php echo $i;?>"><?php echo $this->ERPfunction->get_items_units($material['material_id']);?></span></td>
										<td><input type="text" name="material[name_of_foreman][]" id = "name_of_foreman_<?php echo $i;?>" value="<?php echo $material['name_of_foreman'];?>" class="form-control"/></td>
										<td><input type="text" name="material[time_of_return][]" id = "time_of_return_<?php echo $i;?>" value="<?php echo $material['time_of_return'];?>" class="form-control"/></td>
										<input type="hidden" name="detail_id[]" value="<?php echo $material["rbn_detail_id"];?>">
									</tr>
									<?php $i++;
									 } 
									} ?>
								</tbody>
							</table>
                        </div>
						<div class="form-row">
                            <div class="col-md-2"></div>
                            <div class="col-md-4"><button type="submit" class="btn btn-primary"><?php echo $button_text;?></button></div>
                        </div>
					
					
				<?php $this->Form->end(); ?>
				
				<div class="row">
					<div class="col-md-3 text-right col-md-offset-5">
						Prepared By : <?php echo $this->ERPfunction->get_user_name($rbndata["created_by"]);?>
					</div>
					<div class="col-md-4 text-center">
						Last Edited By : <?php echo $this->ERPfunction->get_user_name($rbndata["last_edit_by"]);?>
					</div>
				</div>
			</div>
			</div>
<?php }?>
         </div>