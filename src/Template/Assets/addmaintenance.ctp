<?php
use Cake\Routing\Router;
?>
<script type="text/javascript">
var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;

jQuery(document).ready(function() {
	jQuery('#user_form').validationEngine();
	jQuery("body").on("change", "#project_id", function(event){ 
	 jQuery('#asset_namelist').html();
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
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'getprojectdetail'));?>",
                data:curr_data,
                async:false,
                success: function(response){					
					var json_obj = jQuery.parseJSON(response);					
					jQuery('#project_code').val(json_obj['project_code']);						
					jQuery('#prno').val(json_obj['prno']);								
					jQuery('#amo_no').val(json_obj['amo_no']);
					jQuery('#asset_namelist').html(json_obj['asset_list']);
					jQuery('#asset_namelist').prepend("<option value='' selected>--Select Asset--</option>");
					return false;
                },
                error: function (e) {
                     alert('Error');
                     console.log(e.responseText);
                }
            });	
	});
	
	jQuery('body').on('click','.trash',function(){
	  
		/* var row_id = jQuery(this).attr('data-id');		
		jQuery('table tr#row_id_'+row_id).remove();	 */
		jQuery(this).parents("tr").remove();
		var expense_sum = 0;
		jQuery('.amount').each(function(){
				var single_amount = jQuery(this).val();
				expense_sum = parseFloat(parseFloat(expense_sum)+parseFloat(single_amount));  
		});
		jQuery('.total_amount').html(expense_sum.toFixed(2));
		jQuery('#total_amount').val(expense_sum.toFixed(2));
		jQuery('#expense_amount').val(expense_sum.toFixed(2));
		return false;
	});
	
	jQuery("body").on("change", ".quantity, .rate, .gst", function(event){
		var row = $(this).attr('data-id');
		var qty = $('#quantity_'+row).val();
		var rate = $('#rate_'+row).val();
		var gst = $('#gst_'+row).val();
		var amount = 0;
		if(jQuery.isNumeric(qty) && jQuery.isNumeric(rate) && jQuery.isNumeric(gst)){
			amount = parseFloat((qty * rate) * (1+ gst/ 100));
			<!-- alert(amount); -->
			$("#amount_"+row).val(amount.toFixed(2));
			
			var expense_sum = 0;
			jQuery('.amount').each(function(){
					var single_amount = jQuery(this).val();
					expense_sum = parseFloat(parseFloat(expense_sum)+parseFloat(single_amount));  
			});
			jQuery('.total_amount').html(expense_sum.toFixed(2));
			jQuery('#total_amount').val(expense_sum.toFixed(2));
			jQuery('#expense_amount').val(expense_sum.toFixed(2));
		}
	});
	
	jQuery('#maintenance_date').datepicker({
			dateFormat: "dd-mm-yy",
			changeMonth: true,
			changeYear: true,
			yearRange:'-65:+0',
			maxDate: new Date(),
			onChangeMonthYear: function(year, month, inst) {
	            jQuery(this).val(month + "-" + year);
	        }
	}); 
	jQuery("body").on("change", "#asset_group", function(event){	 
	  var asset_group  = jQuery(this).val();
		 
	   var curr_data = {	 						 					
	 					asset_group : asset_group,	 					
	 					};	 				
	 	 jQuery.ajax({
               headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
				url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'groupbyassets'));?>",
				data:curr_data,
                async:false,
				success: function(response){					
					var json_obj = jQuery.parseJSON(response);					
					jQuery('#asset_namelist').html(json_obj['asset_list']);	
					jQuery('.select2').select2();					
					return false;
                },
                error: function (e) {
                     alert('Error');
                }
            });	
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
					jQuery('#deployed_to').val(json_obj['deployed_to']).change();					
					jQuery('#model_no').val(json_obj['model_no']);	
					jQuery('#vehicle_no').val(json_obj['vehicle_no']);	
					jQuery('#unit').val(json_obj['unit']);	
					jQuery('#asset_group_id').val(json_obj['asset_group_id']);	
					jQuery('#asset_group_name').val(json_obj['asset_group_name']);	
					jQuery('#quantity').val(json_obj['quantity']);	
					jQuery('.select2').select2();
					return false;
                },
                error: function (e) {
                     alert('Error');
                }
            });	
	});
		 
	jQuery('.viewmodal').click(function(){
			
			payid=jQuery(this).attr('id');
			jQuery('#modal-view').html('hello');
			 var model  = jQuery(this).attr('data-type') ;
		//alert(model);
		//return false;
	   var curr_data = {type : model};	 				
	 	 jQuery.ajax({
               headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'categorylist'));?>",
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
	 jQuery("body").on("click", "#btn-add-category", function(){		
		var category_name  = jQuery('#category_name').val() ;
		var model  = jQuery(this).attr('model');	
		/* alert(category_name + ' ' + model);
		return false; */
		if(category_name != "")
		{
			var curr_data = {					
					model : model,
					category_name: category_name				
					};
					
					jQuery.ajax({
					headers: {
						'X-CSRF-Token': csrfToken
					},
                type:"POST",
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'addcategory'));?>",
                data:curr_data,
                async:false,
                success: function(response){
					/* //alert(category_name + ' ' + model + ' ' + response);
		//return false; */
                     var json_obj = jQuery.parseJSON(response);					
						jQuery('.table').append(json_obj[0]);
						jQuery('#category_name').val("");						
						jQuery("#"+model).append(json_obj[1]);	
						jQuery('.select2').select2();
						return false;		
                },
                error: function (tab) {
                    alert('error');
                }
            });
					
					
		
		}
		else
		{
			alert("Please enter Category Name.");
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
		
		jQuery.ajax({
			headers: {
				'X-CSRF-Token': csrfToken
			},
            type: 'POST',
		    url: '<?php echo Router::url(["controller" => "Ajaxfunction","action" => "assetmaintenancerow"]);?>',
			data : {row_id:row_id},
			success: function (response)
			{	
				jQuery("tbody").append(response);
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
	
	jQuery("body").on("click", ".btn-delete-cat", function(event){
	 
	  event.preventDefault(); // disable normal link function so that it doesn't refresh the page
	  var docHeight = jQuery(document).height(); //grab the height of the page
	  var scrollTop = jQuery(window).scrollTop();
	  var cat_id  = jQuery(this).attr('id') ;
	  var model  = jQuery(this).attr('model') ;

	if(confirm("Are you sure want to delete this record?"))
		{
	   var curr_data = {	 						 					
	 					cat_id : cat_id,	 					
	 					};	 				
	 	 jQuery.ajax({
               headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'removecategory'));?>",
                data:curr_data,
                async:false,
                success: function(response){
						jQuery("#"+model+" option[value='"+cat_id+"']").remove();
                    	jQuery('tr#cat-'+cat_id).hide();
						jQuery('.select2').select2();
							return true; 	
                },
                error: function (tab) {
                    alert('error');
                }
            });
		}
	
  });
		
} );
</script>	
<?php 
/* 
$asset_code=isset($maintenace_data['asset_code'])?$maintenace_data['asset_code']:'';
$user_id=isset($maintenace_data['user_id'])?$maintenace_data['user_id']:''; */
 
$asset_group=isset($maintenace_data['asset_group'])?$maintenace_data['asset_group']:'';
$amo_no=isset($maintenace_data['amo_no'])?$maintenace_data['amo_no']:'';
$maintenance_date=isset($maintenace_data['maintenance_date'])?date("d-m-Y",strtotime($maintenace_data['maintenance_date'])):date("d-m-Y");
 $asset_name='';
 $asset_code='';
 $capacity='';
 $asset_make='';
 $deployed_to='';
if(isset($maintenace_data['asset_id'])){
$asset_name=$maintenace_data['asset_id'];
$asset_code=$this->ERPfunction->get_asset_code($maintenace_data['asset_id']);
$capacity=$this->ERPfunction->get_asset_capacity($maintenace_data['asset_id']);
$asset_make=$this->ERPfunction->get_asset_make($maintenace_data['asset_id']);
$deployed_to=$this->ERPfunction->get_projectname_by_asset($maintenace_data['asset_id']);
}
 
 
$quantity=isset($maintenace_data['quantity'])?$maintenace_data['quantity']:'';
$unit=isset($maintenace_data['unit'])?$maintenace_data['unit']:'';
$model_no=isset($maintenace_data['model_no'])?$maintenace_data['model_no']:'';
$vehicle_no=isset($maintenace_data['vehicle_no'])?$maintenace_data['vehicle_no']:'';
$expense_amount=isset($maintenace_data['expense_amount'])?$maintenace_data['expense_amount']:'';
$payment_by=isset($maintenace_data['payment_by'])?$maintenace_data['payment_by']:'1';
$supervised_by=isset($maintenace_data['supervised_by'])?$maintenace_data['supervised_by']:'';
$voucher_no=isset($maintenace_data['voucher_no'])?$maintenace_data['voucher_no']:'';
$desc_maintenance=isset($maintenace_data['desc_maintenance'])?$maintenace_data['desc_maintenance']:'';
$reason=isset($maintenace_data['reason'])?$maintenace_data['reason']:'';
$desc_amount=isset($maintenace_data['desc_amount'])?$maintenace_data['desc_amount']:'';
$project_code=isset($maintenace_data['project_code'])?$maintenace_data['project_code']:'';
$project_id=isset($maintenace_data['project_id'])?$maintenace_data['project_id']:'';
$maintenance_type=(isset($maintenace_data['maintenance_type']))?$maintenace_data['maintenance_type']:1;
$party_name=isset($maintenace_data['party_name'])?$maintenace_data['party_name']:'';
$created_date=isset($maintenace_data['created_date'])?date("Y-m-d",strtotime($maintenace_data['created_date'])):'';


?>
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
else{
?>    		
                <div class="block block-fill-white">				
					<div class="head bg-default bg-light-rtl">
						<h2><?php echo $form_header;?> </h2>
						<div class="pull-right">
						<a href="<?php echo $this->ERPfunction->action_link('Assets',$back);?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
						</div>
					</div>
			
                    <div class="header">
                        <h2><u>Asset Maintenance Expense Information</u></h2>
                    </div>
					<?php echo $this->Form->Create('form1',['id'=>'maintenance_form','class'=>'form_horizontal formsize','method'=>'post','id'=>'user_form','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
				
					<input type="hidden" name="asset_me_action" class="form-control" value="<?php echo $asset_me_action;?>"/>	
					
                    <div class="content controls">
						<div class="form-row">
                            <div class="col-md-2">Project Code:<span class="require-field">*</span> :</div>
                            <div class="col-md-4"><input type="text" name="project_code" id="project_code" value="<?php echo $project_code; ?>"
							class="form-control validate[required]" value="" readonly="true"/></div>
							<div class="col-md-2">Project Name:</div>
                            <div class="col-md-4">
								<select class="select2" required="true"  style="width: 100%;" name="project_id" id="project_id">
								<option value="">--Select Project--</Option>
								<?php 
									foreach($projects as $retrive_data)
									{?>
										<option value="<?php echo $retrive_data['project_id'];?>" <?php 
											if(isset($project_id)){
												if($project_id == $retrive_data['project_id'])
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
                            <div class="col-md-2">A. M. O. No.<span class="require-field">*</span> :</div>
                            <div class="col-md-4"><input type="text" name="amo_no" value="<?php echo $amo_no;?>" id="amo_no" class="form-control validate[required]"/></div>
							<div class="col-md-2">Date</div>
                            <div class="col-md-4"><input id="maintenance_date" type="text" name="maintenance_date" value="<?php echo $maintenance_date;?>" class="form-control"/></div>
                        </div>
						<div class="form-row">
                            <div class="col-md-2">Asset Group<span class="require-field">*</span> :</div>
                            <div class="col-md-4">
								<input class="form-control" type="hidden" name="asset_group" id="asset_group_id" value="<?php echo ($asset_group != "")?$asset_group:""; ?>" />
								<input class="form-control" readonly="true" id="asset_group_name" value="<?php echo ($asset_group != "")?$this->ERPfunction->get_asset_group_name($asset_group):""; ?>" />
								<!-- <select style="width: 100%;" class="select2" required="true"  name="asset_group" id="asset_group">
								<option>--Select Assets Group--</option>
								<?php 
							 
								// foreach($asset_groups as $key => $retrive_data)
								// {
									// echo '<option value="'.$retrive_data['id'].'" '.$this->ERPfunction->selected($retrive_data['id'],$asset_group).'>'.$this->ERPfunction->get_asset_group_name($retrive_data['id']).'</option>';
								// }
								 
								?>
								</select> -->
										
							</div>
                        
                            <div class="col-md-2">Asset ID</div>
                            <div class="col-md-4"><input type="text" readonly="true" id="asset_code" name="asset_code" value="<?php echo $asset_code;?>" class="form-control"/></div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-2">Asset Name<span class="require-field">*</span> :</div>
                            <div class="col-md-10">
								<select style="width: 100%;" class="select2" required="true"  name="asset_name" id="asset_namelist">
									<option> -- Select Assets List -- </option>
									<?php 
									// foreach($asset_names as $key => $retrive_data)
									// {
										// echo '<option value="'.$retrive_data['asset_id'].'" '.$this->ERPfunction->selected($retrive_data['asset_id'],$asset_name).'>'.$retrive_data['asset_name'].'</option>';
									// }
									?>
								</select>
							</div>
						</div>
						<div class="form-row">
                            <div class="col-md-2">Make:</div>
                            <div class="col-md-4">
								<input type="text" id="asset_make" readonly="true" name="asset_make" value="<?php echo $asset_make;?>" class="form-control"/>							
						 
							</div>
							<div class="col-md-2">Asset Capacity</div>
                            <div class="col-md-4"><input type="text"  readonly="true" id="capacity" name="capacity" value="<?php echo $capacity;?>" class="form-control"/></div>
                        </div>
						
						<div class="form-row">
                            <div class="col-md-2">Model No  :</div>
                            <div class="col-md-4"><input type="text" name="model_no" readonly="true" id="model_no" value="<?php echo $model_no;?>" class="form-control"/></div>
							<div class="col-md-2">Identity / Veh. No.</div>
                            <div class="col-md-4"><input type="text" name="vehicle_no" readonly="true" id="vehicle_no" value="<?php echo $vehicle_no;?>" class="form-control"/></div>
                        </div>						
					
						<div class="form-row">
							<div class="col-md-2">Maintenance Type</div>
							<div class="col-md-4">
								<select style="width:100%;" class="select2" name="maintenance_type" id="maintenance_type">
									<option value="0" <?php echo ($maintenance_type == 0)?"selected":""; ?>>Preventive / Routine</option>
									<option value="1" <?php echo ($maintenance_type == 1)?"selected":""; ?>>Corrective / Breakdown</option>
									
								</select>
							</div>
						</div>
						<div class="form-row">
						<div class="col-md-2">Party's Name</div>
						<div class="col-md-10">
							 <input type="text" name="party_name" value="<?php echo $party_name;?>" class="form-control"/>
						</div>
						</div>
						<div class="form-row">
                            <div class="col-md-2">Amount of Expense *</div>
                            <div class="col-md-4">
								 <input type="text" name="expense_amount" id="expense_amount" value="<?php echo $expense_amount;?>" readonly="true" class="form-control validate[required]"/>
							</div>
							
							<div class="col-md-2">Payment</div>
                            <div class="col-md-4">
								<select style="width: 100%;" class="" name="payment_by" id="payment_by">
									<option> -- Select Payment Method -- </option>
									<?php 
									foreach($pay_method as $key => $retrive_data)
									{
										echo '<option value="'.$retrive_data['id'].'" '.$this->ERPfunction->selected($retrive_data['id'],$payment_by).' >'.$retrive_data['title'].'</option>';
									}
									?>
								</select>
							</div>
						</div>
                        
						<div class="form-row">
						 <div class="col-md-2">Voch. No. / Inw. No. *</div>
                            <div class="col-md-4">
								<input type="text" name="voucher_no" value="<?php echo $voucher_no;?>" class="form-control validate[required]"/> 
							</div>
                             <div class="col-md-2">Supervised By *</div>
                            <div class="col-md-4">
								<input type="text" name="supervised_by" value="<?php echo $supervised_by;?>" class="form-control validate[required]"/>
								</div>
                         </div>
						
						
						<?php
						if($asset_me_action == 'edit' && $created_date < "2020-02-07"){
						?>
						<div class="form-row">
                            <div class="col-md-2"> </div>
                            <div class="col-md-3"><textarea name="desc_maintenance"><?php echo $desc_maintenance; ?></textarea> </div>
                            <div class="col-md-3"><textarea name="reason"><?php echo $reason; ?> </textarea> </div>
                            <div class="col-md-3"><textarea name="desc_amount"> <?php echo $desc_amount; ?></textarea> </div>
                        </div>
						<?php }else{?>
						<div class="form-row">
							<button type="button" id="add_newrow" class="btn  btn-success">Add New </button>
                            <table class="table table-bordered">
								<thead>
									<tr>
										<th style="width:50%">Material / Spares/ Tools/ Service / Others</th>
										<th>Quantity</th>
										<th>Unit</th>
										<th>Rate</th>
										<th>GST(%)</th>
										<th>Amount</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php
									
									$total_amount = 0;
									if($asset_me_action == 'edit'){
									$i = 0;
									foreach($maintenace_details as $retrive){
									$total_amount += $retrive["amount"];
									?>
									<tr id="row_id_<?php echo $i; ?>">
										<td>
											<input type="hidden" value="<?php echo $i; ?>" data-id="<?php echo $i; ?>" name="row_number" class="row_number">
											
											<input type="hidden" value="<?php echo $retrive['id']; ?>" data-id="<?php echo $i; ?>" name="description[detail_id][]">
											
											<input type="text" id="material_<?php echo $i; ?>" data-id="<?php echo $i; ?>" name="description[material][]" class="form-control validate[required]" value="<?php echo $retrive["material"]?>"/>
										</td>
										<td>
											<input type="text" id="quantity_<?php echo $i; ?>" data-id="<?php echo $i; ?>" name="description[quantity][]" class="form-control validate[required,custom[number]] quantity" value="<?php echo $retrive["quantity"]?>"/>
										</td>
										<td>
											<input type="text" id="unit_<?php echo $i; ?>" name="description[unit][]" data-id="<?php echo $i; ?>" class="form-control validate[required]" value="<?php echo $retrive["unit"]?>"/>
										</td>
										<td>
											<input type="text" name="description[rate][]" data-id="<?php echo $i; ?>" id="rate_<?php echo $i; ?>" class="form-control validate[required,custom[number]] rate" value="<?php echo $retrive["rate"]?>"/>
										</td>
										<td>
											<input type="text" id="gst_<?php echo $i; ?>" data-id="<?php echo $i; ?>" name="description[gst][]" class="form-control validate[required,custom[number]] gst" value="<?php echo $retrive["gst"]?>"/>
										</td>
										<td>
											<input type="text" id="amount_<?php echo $i; ?>" data-id="<?php echo $i; ?>" name="description[amount][]" class="form-control validate[required,custom[number]] amount" value="<?php echo $retrive["amount"]?>"/>
										</td>
										<td>
											<!--<span class="trash btn btn-danger" data-id="<?php echo $i; ?>"><i class="fa fa-trash"></i> Delete</span>-->
										</td>
									</tr>
									<?php $i++; }}else{?>
									<tr id="row_id_0">
										<td>
											<input type="hidden" value="0" data-id="0" name="row_number" class="row_number">
											<input type="text" id="material_0" data-id="0" name="description[material][]" class="form-control validate[required]"/>
										</td>
										<td>
											<input type="text" id="quantity_0" data-id="0" name="description[quantity][]" class="form-control validate[required,custom[number]] quantity"/>
										</td>
										<td>
											<input type="text" id="unit_0" name="description[unit][]" data-id="0" class="form-control validate[required]"/>
										</td>
										<td>
											<input type="text" name="description[rate][]" data-id="0" id="rate_0" class="form-control validate[required,custom[number]] rate"/>
										</td>
										<td>
											<input type="text" id="gst_0" data-id="0" name="description[gst][]" class="form-control validate[required,custom[number]] gst"/>
										</td>
										<td>
											<input type="text" id="amount_0" data-id="0" name="description[amount][]" class="form-control validate[required,custom[number]] amount"/>
										</td>
										<td>
											<span class="trash btn btn-danger" data-id="0"><i class="fa fa-trash"></i> Delete</span>
										</td>
									</tr>
									<?php }?>
								</tbody>
								<tfoot>
									<tr>
										<th></th>
										<th></th>
										<th></th>
										<th></th>
										<th><b>Total</b></th>
										<th><span class="total_amount"><?php echo $total_amount;?></span><input type="hidden" name="total_amount" id="total_amount" value="<?php echo $total_amount;?>"></th>
										<th></th>
									</tr>
								</tfoot>
							</table>
							
                        </div>
						
						<div class="form-row">
                            <div class="col-md-2"> Reason</div>
                            <div class="col-md-10"><textarea name="reason"><?php echo $reason; ?> </textarea> </div>
                        </div>
						
						<?php } ?>
						<div class="form-row">							
                            <div class="col-md-2"> Attach Documents</div>
                            <div class="col-md-4">
								<input class="add_label form-control">
							</div>
							<div class="col-md-1">
								<a href="javascript:void(0)" class="create_field form-control">+&nbsp;Add</a>
							</div>
						</div>
						
						<div class="form-row add_field">
						<?php 
						if($asset_me_action == "edit")
						{
						$attached_files = json_decode($maintenace_data["attachment"]);
						$attached_label = json_decode(stripcslashes($maintenace_data['attach_label']));						
						if(!empty($attached_files))
						{							
							$i = 0;
							foreach($attached_files as $file)
							{?>
								<div class='del_parent'>
									<div class='form-row'>
										<div class='col-md-2'>
											<?php echo $attached_label[$i];?>
											<input type='hidden' name='attach_label[]' value='<?php echo $attached_label[$i];?>' class='form-control'>
										</div>
										<div class='col-md-4'><a href="<?php echo $this->ERPfunction->get_signed_url($file);?>" class="btn btn-primary" target="_blank">View File</a>
										<input type='hidden' name='old_image_url[]' value='<?php echo $file;?>' class='form-control'></div>
										<div class='col-md-2'><span class='del_file btn btn-danger'>x Remove</span></div>
									</div>
								</div>							
							<?php $i++;
							}
						}
						}
						?>
						</div>	
					 
					 
						<div class="form-row">
                            <div class="col-md-2"></div>
                            <div class="col-md-4"><button type="submit" class="btn btn-primary" onclick="return ValidateExtension()"><?php echo $button_text;?></button></div>
							<div class="col-md-2"></div>
                            <div class="col-md-4"> </div>
                        </div>
				</div>
				<?php $this->Form->end(); ?>
			</div>
<?php } ?>			
</div>
<script>
$(".create_field").click(function(){
	var label = $(".add_label").val();
	$(".add_label").val("");
	var field = "<div class='del_parent'><div class='form-row'><div class='col-md-2'>"+ label +"<input type='hidden' name='attach_label[]' value='"+label+"' class='form-control'></div><div class='col-md-4'><input type='file' name='image_url[]' class='imageUpload'><span class='required red notice'></span></div><div class='col-md-2'><span class='del_file btn btn-danger'>x Remove</span></div></div></div>";
	$(".add_field").append(field);
});
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
$("body").on("click",".del_file",function(){
	$(this).parentsUntil('.del_parent').remove();
});


 jQuery('#asset_namelist').html();
	  var project_id  = jQuery("#project_id").val() ;
	  if(project_id != ''){
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
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'getprojectdetail'));?>",
                data:curr_data,
                async:false,
                success: function(response){					
					var json_obj = jQuery.parseJSON(response);					
					jQuery('#project_code').val(json_obj['project_code']);						
					jQuery('#prno').val(json_obj['prno']);								
					jQuery('#amo_no').val(json_obj['amo_no']);								
					jQuery('#asset_namelist').html(json_obj['asset_list']);	
					jQuery('#asset_namelist option[value=<?php echo $asset_name;?>]').attr("selected","selected");
					return false;
                },
                error: function (e) {
                     alert('Error');
                     console.log(e.responseText);
                }
            });	
	
	  }
</script>