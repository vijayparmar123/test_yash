<?php
use Cake\Routing\Router;
?>
<script type="text/javascript">
var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;

jQuery(document).ready(function() {
	jQuery('#user_form').validationEngine();
	jQuery('#is_date').datepicker({
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
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'inisprojectdetail'));?>",
                data:curr_data,
                async:false,
                success: function(response){					
					var json_obj = jQuery.parseJSON(response);					
					jQuery('#project_code').val(json_obj['project_code']);						
					jQuery('#is_no').val(json_obj['is_no']);
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
						project_id : project_id,
						stock : "yes"
	 					};	 				
	 	 jQuery.ajax(
			headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'getmaterialbrandlist'));?>",
                data:curr_data,
                async:false,
                success: function(response){			
					console.log(response);
					var json_obj = jQuery.parseJSON(response);				
					jQuery('#unit_name_'+row_id).html();
					jQuery('#unit_name_'+row_id).html(json_obj['unit_name']);
					jQuery('#material_code_'+row_id).html();
					jQuery('#material_code_'+row_id).html(json_obj['material_code']);
					jQuery('#opening_stock_'+row_id).html("");
					jQuery('#opening_stock_'+row_id).html(json_obj['opening_stock']);					
					return false;
                },
                error: function (e) {
                     alert('Error');
                }
            });
	
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
  
  jQuery('body').on('click','.trash',function(){
		var row_id = jQuery(this).attr('data-id');
		
		jQuery('table tr#row_id_'+row_id).remove();	
		return false;
	});
});
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
						<a href="<?php //echo $this->ERPfunction->action_link('Inventory',"approveis/{$searched_project}");?>" onclick = "javascript:window.close();" class="btn btn-success"><i class="icon-arrow-left"></i> Back</a>
						</div>
					</div>
					
					
					<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
										
					 <div class="content controls">
					 	
						<div class="form-row">
                            <div class="col-md-2">Project Code:<span class="require-field">*</span></div>
                            <div class="col-md-4"><input type="text" name="project_code" id="project_code" value="<?php echo $this->ERPfunction->get_projectcode($data["project_id"]);?>"
							class="form-control validate[required]" value="" readonly="true"/></div>
							<div class="col-md-2 text-right">Project Name*</div>
                            <div class="col-md-4">
								<select class="select2"  required="true"   style="width: 100%;" name="project_id" id="project_id">
								<option value="">--Select Project--</Option>
								<?php 
									foreach($projects as $retrive_data)
									{
										echo '<option value="'.$retrive_data['project_id'].'" '.$this->ERPfunction->selected($retrive_data['project_id'] , $data['project_id']).'>'.
										$retrive_data['project_name'].'</option>';
									}
								?>
								</select>
							</div>
                        </div>
						<div class="form-row">
                            <div class="col-md-2">I.S.No</div>
                            <div class="col-md-4">
								<input type="text" name="is_no" id="is_no" class="form-control" value="<?php echo $data["is_no"];?>" />
							</div>
                        
                            <div class="col-md-2">Date*</div>
                            <div class="col-md-2"><input type="text" onkeydown="return false" name="is_date" id="is_date" 
							value="<?php echo $data['is_date']->format('d-m-Y');?>" class="form-control validate[required]" value=""/></div>
							
                        </div>						
						<div class="form-row">
						<?php 
						/*
						$is_asset = explode("_",$data['agency_name']);
						if(isset($is_asset[1]))
						{
							echo $this->ERPfunction->get_asset_name($is_asset[1]);
						}else{
							echo $this->ERPfunction->get_agency_name($data['agency_name']); 
						} */
						?>
                            <div class="col-md-2" style="padding: 0;">Agency Name/Asset Name*</div>
                            <div class="col-md-10">
								<?php //echo $this->Form->select("agency_name",$agency_assets,["default"=>[$data['agency_name']],"class"=>"select2 asst_list","id"=>"","style"=>"width:100%;","required"=>true]);?>
								<select class="select2 asst_list" style="width: 100%;" name="agency_name" required="true">
								<option value="All">All</Option>
								<?php 
									// debug($data);die;
									foreach($vendor_list as $retrive_data)
									{
										$select = ($retrive_data['user_id'] == $data['agency_name'])?"selected":"";
										echo '<option value="'.$retrive_data['user_id'].'"'.$select.'>'.
										$retrive_data['vendor_name'].'</option>';
									}
									foreach($assets as $asset)
									{
										$select1 = ($data['agency_name'] == 'asst_'.$asset['asset_id'])?"selected":"";
										echo "<option value='asst_{$asset['asset_id']}' class='added_asset' ".$select1.">{$asset['asset_name']}</option>";
									}
								?>
							</select>
							</div>                        
                        </div>
						
						<div class="form-row">
                            <div class="col-md-12">
								<p>The following Material (s) / Item (s) after approval of concerned user / their departments issued.</p>
							</div>                                                 
                        </div>
						<div class="form-row">
						
                            <table class="table table-bordered">
								<thead>
									<tr>
									<th rowspan="2">Material Code</th>
									<th colspan="5" align="center">Material / Item</th>
									<th rowspan="2">Name of Foreman</th>
									<th rowspan="2">Usage / Remarks</th>
									</tr>
									<tr>
									<th style="max-width:35%;width:35%">Description</th>									
									<th>Opening Stock</th>
									<th>Quantity Issued</th>
									<th>Balance</th>				
									<th>Unit</th>
									</tr>
								</thead>
								<tbody>
								<?php 
									$i=0;									
									foreach($materials as $material)
									{ ?>
									<tr id="row_id_<?php echo $i;?>">
										<td><span id="material_code_<?php echo $i;?>"><?php echo $this->ERPfunction->get_material_item_code_bymaterialid($materials[$i]["material_id"]);?></span></td>
										<td>
											<select class="select2 material_id" required="true" style="width: 100%;" name="material[material_id][]" id="material_id_<?php echo $i;?>" data-id="<?php echo $i;?>">
												<option value="">--Select Material--</Option>
												<?php 
													foreach($material_list as $retrive_data)
													{
														echo '<option value="'.$retrive_data['material_id'].'" '.$this->ERPfunction->selected($retrive_data['material_id'],$materials[$i]['material_id']).' >'.
														$retrive_data['material_title'].'</option>';
													}
												?>
											</select>
										</td>										
										<td><span id="opening_stock_<?php echo $i;?>"><?php echo $this->ERPfunction->getmaterialbrandlist($materials[$i]["material_id"],$data["project_id"]);?></span></td>
										<td><input type="text" name="material[quantity][]" id="quantity_<?php echo $i;?>" value="<?php echo $materials[$i]["quantity"];?>" class="form-control change_bal" row="0" />
										<input type="hidden" name="material[old_quantity][]" value="<?php echo $materials[$i]["quantity"];?>" class="form-control change_bal" row="0" />
										</td>
										<td><input type="text" name="material[balance][]" id="balance_<?php echo $i;?>" class="form-control" value="<?php echo $materials[$i]["balance"];?>" style="padding:0;" readonly /></td>
										<td><span id="unit_name_<?php echo $i;?>"><?php echo $this->ERPfunction->get_items_units($materials[$i]["material_id"]);?></span></td>
										<td><input type="text" name="material[name_of_foreman][]" id="name_of_foreman_<?php echo $i;?>" value="<?php echo $materials[$i]["name_of_foreman"];?>" class="form-control" /></td>
										<td><input type="text" name="material[time_issue][]" id="time_issue_<?php echo $i;?>" value="<?php echo $materials[$i]["time_issue"];?>" class="form-control" /></td>
																				
									</tr>
									<input type="hidden" name="material[detail_id][]" value="<?php echo $materials[$i]["is_audit_detail_id"];?>">
									<?php $i++; } ?>
								</tbody>
							</table>
                        </div>
						<div class="form-row">
                            <div class="col-md-2"></div>
                            <div class="col-md-4"><button type="submit" class="btn btn-primary"><?php echo $button_text;?></button></div>
                        </div>
					
					
					<?php $this->Form->end(); ?>
					
					<div class="row">
						<div class="col-md-3 col-md-offset-6">
							Prepared By : <?php echo $this->ERPfunction->get_user_name($data["created_by"]);?>
						</div>
						<div class="col-md-2">
							Last Edited By : <?php echo ($data["last_edit"] != "")?$data["last_edit"]->format("d-m-Y"):"NA";?>
						</div>
					</div>
				</div>
			</div>			
<?php } ?>
         </div>

<script>
jQuery(document).ready(function(){
	jQuery("body").on("change",".change_bal",function(){
		var qty = $(this).val();
		var row = jQuery(this).attr("row");		
		var os = jQuery("#opening_stock_"+row).html();		
		var total = parseInt(os) - parseInt(qty);
		jQuery("#balance_"+row).val(total);
	});	
});
</script>