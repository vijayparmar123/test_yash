<?php
use Cake\Routing\Router;
?>
<script type="text/javascript">
var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;

jQuery(document).ready(function() {
	jQuery('#asset_form').validationEngine();
	jQuery('#date_of_purchase').datepicker({
		dateFormat: "dd-mm-yy",
		  changeMonth: true,
	        changeYear: true,
	        yearRange:'-65:+0',
	        onChangeMonthYear: function(year, month, inst) {
	            jQuery(this).val(month + "-" + year);
	        }
	}); 
	jQuery('.datepick').datepicker({dateFormat: "yy-mm-dd"});
	
	jQuery("body").on("change", "#asset_group", function(event){	 
	  var asset_group  = jQuery(this).val();
	  var asset_code  = jQuery("#asset_code").val();
		/* alert(product_id);
		return false; */
	   var curr_data = {	 						 					
	 					asset_group : asset_group, asset_code : asset_code	 					
	 					};	 				
	 	 jQuery.ajax({
                headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
				<?php if($asset_action == 'edit'){?>
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'generateassetidedit'));?>",
				<?php }else{ ?>
				url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'generateassetid'));?>",
				<?php } ?>
				
				data:curr_data,
                async:false,
				success: function(response){					
					var json_obj = jQuery.parseJSON(response);					
					jQuery('#asset_code').val(json_obj['asset_code']);						
											
					return false;
                },
                error: function (e) {
                     alert('Error');
                }
            });	
	});
		 
	jQuery("body").on("change", "#vendor_list", function(event){	 
	  var vendor_id  = jQuery(this).val();
		/* alert(product_id);
		return false; */
	   var curr_data = {	 						 					
	 					vendor_id : vendor_id,	 					
	 					};	 				
	 	 jQuery.ajax({
                headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
				url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'getvendorid'));?>",
				data:curr_data,
                async:false,
				success: function(response){					
										
					jQuery('#vendor_id').val(response);						
											
					return false;
                },
                error: function (e) {
					alert('error');
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
		
	$("#asset_group").change(function(){
		var ast_id = $(this).val();
		if(ast_id == 3 || ast_id == 4)
		{
			$(".rto_fld").show("slow");
		}else{
			$(".rto_fld").hide("slow");
		}
	});
	
	jQuery('.add_group').click(function(){
			jQuery('#modal-view').html('hello');
			jQuery('.modal-content').html(''); 				
			jQuery.ajax({
				headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
				url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'assetgroup'));?>",
				async:false,
				success: function(response){                    
					jQuery('#load_modal1 .modal-content').html(response);
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
	
	jQuery("body").on("click", ".btn-edit-item", function(event){
	 
	  event.preventDefault(); // disable normal link function so that it doesn't refresh the page
	  var docHeight = jQuery(document).height(); //grab the height of the page
	  var scrollTop = jQuery(window).scrollTop();
	  var group_id  = jQuery(this).attr('id') ;

	   var curr_data = {	 						 					
	 					group_id : group_id,
	 					};	 				
		jQuery.ajax({
			headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
			url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'editassetgroup'));?>",
			data:curr_data,
			async:false,
			success: function(response){
					// jQuery('#term-'+term_id)
					jQuery('tr#cat-'+group_id).html(response);
			},
			error: function (tab) {
				alert('error');
			}
		});
  });
  
  jQuery("body").on("click", ".btn-group-update-cancel", function(event){
	 
	  event.preventDefault(); // disable normal link function so that it doesn't refresh the page
	  var docHeight = jQuery(document).height(); //grab the height of the page
	  var scrollTop = jQuery(window).scrollTop();
	  var group_id  = jQuery(this).attr('id') ;
	 
	   var curr_data = {	 						 					
	 					group_id : group_id,
	 					};	 				
		jQuery.ajax({
			headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
			url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'cancelassetgroupsave'));?>",
			data:curr_data,
			async:false,
			success: function(response){
				jQuery('tr#cat-'+group_id).html(response);
			},
			error: function (e) {
				alert('error');
				console.log(e.responseText);
			}
		});
  });
  
  jQuery("body").on("click", ".btn-group-update", function(event){
	 
	  event.preventDefault(); // disable normal link function so that it doesn't refresh the page
	  var docHeight = jQuery(document).height(); //grab the height of the page
	  var scrollTop = jQuery(window).scrollTop();
	  var group_id  = jQuery(this).attr('id') ;
	  var group_code  = jQuery('#cat-'+group_id+' #group_code').val();
	  var group_title  = jQuery('#cat-'+group_id+' #group_title').val();
		
	   var curr_data = {	 						 					
	 					group_id : group_id,
						group_code : group_code,
						group_title : group_title,
	 					};	 				
		jQuery.ajax({
			headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
			url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'updateassetgroup'));?>",
			data:curr_data,
			async:false,
			success: function(response){
				jQuery('tr#cat-'+group_id).html(response);
				$('#asset_group option[value="'+group_id+'"]').detach();
				var newOption = new Option(group_title, group_id, false, false);
				$('#asset_group').append(newOption).trigger('change');
			},
			error: function (tab) {
				alert('error');
			}
		});
  });
  
  jQuery("body").on("click", "#btn-add-group", function(){		
		var item_code  = jQuery('#item_code1').val() ;
		var item_name  = jQuery('#item_name1').val() ;
		var model  = jQuery(this).attr('model');	
		/* alert(category_name + ' ' + model);
		return false; */
		if(item_code != "" && item_name != "")
		{
			var curr_data = {					
					item_code : item_code,
					item_name: item_name				
					};
					
					jQuery.ajax({
                headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'addassetgroup'));?>",
                data:curr_data,
                async:false,
                success: function(response){
					/* //alert(category_name + ' ' + model + ' ' + response);
		//return false; */
                     var json_obj = jQuery.parseJSON(response);					
						jQuery('.table').append(json_obj['row']);
						jQuery('#item_code1').val("");						
						jQuery('#item_name1').val("");						
						jQuery("#asset_group").append(json_obj['options']);	
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
			alert("Please fill all the fields.");
		}
	});
	
	jQuery("body").on("change", "#road_tax_status", function(event){
		var road_tax_status = $(this).val();
		if(road_tax_status == 1)
		{
			$("#due_date_road_tax_div").css("visibility","visible");
			$(".due_date_road_tax").addClass("validate[required]");
		}else{
			$("#due_date_road_tax_div").css("visibility","hidden");
			$(".due_date_road_tax").removeClass("validate[required]");
		}
		
	});
	
	jQuery("body").on("change", "#passing_registration_status", function(event){
		var passing_registration_status = $(this).val();
		if(passing_registration_status == 1)
		{
			$("#passing_registration_div").css("visibility","visible");
			$(".due_date_reg").addClass("validate[required]");
		}else{
			$("#passing_registration_div").css("visibility","hidden");
			$(".due_date_reg").removeClass("validate[required]");
		}
		
	});
	
	jQuery("body").on("change", "#fitness_status", function(event){
		var fitness_status = $(this).val();
		if(fitness_status == 1)
		{
			$("#due_date_fitness_div").css("visibility","visible");
			$(".due_date_fitness").addClass("validate[required]");
		}else{
			$("#due_date_fitness_div").css("visibility","hidden");
			$(".due_date_fitness").removeClass("validate[required]");
		}
		
	});
	
	jQuery("body").on("change", "#insurance_status", function(event){
		var insurance_status = $(this).val();
		if(insurance_status == 1)
		{
			$("#due_date_insurance_div").css("visibility","visible");
			$(".due_date_insurance").addClass("validate[required]");
		}else{
			$("#due_date_insurance_div").css("visibility","hidden");
			$(".due_date_insurance").removeClass("validate[required]");
		}
		
	});
	
} );
</script>	
<div class="modal fade " id="load_modal1" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content"></div>
    </div>
</div>
<?php 
/* 
$asset_code=isset($asset_data['asset_code'])?$asset_data['asset_code']:'';
$user_id=isset($asset_data['user_id'])?$asset_data['user_id']:''; */
$asset_group=isset($asset_data['asset_group'])?$asset_data['asset_group']:'';
// if($asset_group != "")
// {
	// $show = ($asset_group == 3 || $asset_group == 4 ) ? "" : "style='display:none;'" ;	
// }
// else
// {
	// $show = "style='display:none;'";
// }
$asset_code=isset($asset_data['asset_code'])?$asset_data['asset_code']:'';
$asset_name=isset($asset_data['asset_name'])?$asset_data['asset_name']:'';
$capacity=isset($asset_data['capacity'])?$asset_data['capacity']:'';
$asset_make=isset($asset_data['asset_make'])?$asset_data['asset_make']:'';
$purchase_quantity=isset($asset_data['purchase_quantity'])?$asset_data['purchase_quantity']:'';
$quantity=isset($asset_data['quantity'])?$asset_data['quantity']:'';
$unit=isset($asset_data['unit'])?$asset_data['unit']:'Nos.';
$model_no=isset($asset_data['model_no'])?$asset_data['model_no']:'';
$vehicle_no=isset($asset_data['vehicle_no'])?$asset_data['vehicle_no']:'';
$purchase_date=isset($asset_data['purchase_date'])?$asset_data['purchase_date']:'';
$purchase_amount=isset($asset_data['purchase_amount'])?$asset_data['purchase_amount']:'';
$po_no=isset($asset_data['po_no'])?$asset_data['po_no']:'';
$warranty_period=isset($asset_data['warranty_period'])?$asset_data['warranty_period']:'';
$payment=isset($asset_data['payment'])?$asset_data['payment']:'';
$voucher_no=isset($asset_data['voucher_no'])?$asset_data['voucher_no']:'';
$deployed_to=isset($asset_data['deployed_to'])?$asset_data['deployed_to']:'';  
$description=isset($asset_data['description'])?$asset_data['description']:''; 
$vendor_name=isset($asset_data['vendor_name'])?$asset_data['vendor_name']:''; 
$vendor_id=isset($asset_data['vendor_id'])?$asset_data['vendor_id']:''; 
$rto_reg_no=isset($asset_data['rto_reg_no'])?$asset_data['rto_reg_no']:'';  


$road_tax_status=isset($asset_data['road_tax_status'])?$asset_data['road_tax_status']:0;
$insurance_status=isset($asset_data['insurance_status'])?$asset_data['insurance_status']:0;
$fitness_status=isset($asset_data['fitness_status'])?$asset_data['fitness_status']:0;
$passing_registration_status=isset($asset_data['passing_registration_status'])?$asset_data['passing_registration_status']:0;

$due_date_reg=($passing_registration_status)?date("d-m-Y",strtotime($asset_data['due_date_reg'])):'';
$due_date_fitness=($fitness_status)?date("d-m-Y",strtotime($asset_data['due_date_fitness'])):'';
$due_date_road_tax=($road_tax_status)?date("d-m-Y",strtotime($asset_data['due_date_road_tax'])):'';
  
$insurance_company=isset($asset_data['insurance_company'])?$asset_data['insurance_company']:'';  
$due_date_insurance=($insurance_status)?date("d-m-Y",strtotime($asset_data['due_date_insurance'])):'';  
$operational_status=isset($asset_data['operational_status'])?$asset_data['operational_status']:'';  
$created_by = isset($asset_data)?$this->ERPfunction->get_full_user_name($asset_data["created_by"]):'';
$last_edit_by = isset($asset_data)?$this->ERPfunction->get_full_user_name($asset_data["last_edited_by"]):'';
$last_edit_on = isset($asset_data)?date("d-m-Y",strtotime($asset_data["last_edit_date"])):'';

?>
<style>
.add-make .modal-body{
	 max-height: 350px;
    overflow-y: scroll;
}
</style>
<div class="modal fade " id="load_modal" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content add-make"></div>
    </div>
</div>
<div class="col-md-10" >
<?php
// if(!$is_capable)
// {
	// $this->ERPfunction->access_deniedmsg();
// }
// else{
?>               <div class="block block-fill-white">
					<div class="head bg-default bg-light-rtl">
						<h2><?php echo $form_header;?></h2>
						<div class="pull-right">
							<a href="<?php echo $this->ERPfunction->action_link('Assets','index');?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
						</div>
					</div>
									
                    <div class="header">
                        <h2><u>Assets Information</u></h2>
                    </div>
					<?php echo $this->Form->Create('form1',['id'=>'asset_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
				
					<input type="hidden" name="asset_action" class="form-control" value="<?php echo $asset_action;?>"/>	
					
                    <div class="content controls">
						<div class="form-row">
                            <div class="col-md-2">Asset Group<span class="require-field">*</span> :</div>
                            <div class="col-md-4">
								
								<select style="width: 100%;" class="select2" required="true"  name="asset_group" id="asset_group" disabled>
								<option value="">Select Asset Group</option>
								<?php 
								foreach($asset_groups as $key => $retrive_data)
								{
									echo '<option value="'.$retrive_data['id'].'" '.$this->ERPfunction->selected($retrive_data['id'],$asset_group).'>'.$this->ERPfunction->get_asset_group_name($retrive_data['id']).'</option>';
								}
								?>
								</select>
										
							</div>
							<?php if($asset_action == "edit"){ ?>
                            <div class="col-md-2">Asset ID</div>
                            <div class="col-md-4"><input type="text" readonly="true" id="asset_code" name="asset_code" value="<?php echo $asset_code;?>" class="form-control"/></div>
							<?php } ?>
                        </div>
                        <div class="form-row">
                            <div class="col-md-2">Asset Name<span class="require-field">*</span> :</div>
                            <div class="col-md-4"><input disabled type="text" name="asset_name" value="<?php echo $asset_name;?>" class="form-control validate[required]"/></div>
							<div class="col-md-2">Asset Capacity</div>
                            <div class="col-md-4"><input disabled type="text" name="capacity" value="<?php echo $capacity;?>" class="form-control"/></div>
                        </div>
						<div class="form-row">
                            <div class="col-md-2">Make:</div>
                            <div class="col-md-6">	
								<select disabled style="width: 100%;" class="select2" required="true"  name="asset_make" id="make_in">
								<option>--Select Make --</option>
								<?php 
								 if(isset($makelist)){
                                        foreach($makelist as $make_info){
                                        ?>
                                   <option value="<?php echo $make_info['cat_id'];?>" <?php                                            
                                                if($asset_make == $make_info['cat_id']){
                                                    echo 'selected="selected"';
                                                }else{
                                                    echo '';
                                                }
                                            
                                        
                                        ?> ><?php echo $make_info['category_title'];?></option>
                                            <?php             
                                        }
                                    }
								?>
								</select> 
							</div>
							
							
                        </div>
						<div class="form-row">						
                            <div class="col-md-2">Vendor's Name *</div>
                            <div class="col-md-4">
							<?php 
								echo $this->Form->select("vendor_name",$vendor_list,["empty"=>"Select Vendor Name","default"=>$vendor_name,"class"=>"select2","id"=>"vendor_list","disabled"=>"disabled","style"=>"width:100%"]);
							?>	
							</div>
							<div class="col-md-2">Vendor's ID</div>
                            <div class="col-md-4"><input type="text" readonly name="vendor_id" value="<?php echo $vendor_id;?>" class="form-control" id="vendor_id" value=""/></div>
							
                        </div>
						
						<div class="form-row" style="display:none;">						
                            <div class="col-md-2">Purchased Quantity *</div>
                            <div class="col-md-4"><input disabled type="text" name="purchase_quantity" id="purchase_quantity" value="1" class="form-control validate[required]"/></div>
							<div class="col-md-2">Unit *</div>
                            <div class="col-md-4"><input disabled type="text" name="unit" value="<?php echo $unit;?>" class="form-control validate[required]" /></div>
							
                        </div>
						
						<div class="form-row">
                            <div class="col-md-2">Model No</div>
                            <div class="col-md-4"><input disabled type="text" name="model_no" value="<?php echo $model_no;?>" class="form-control"/></div>
							<div class="col-md-2">Identity / Veh. No.</div>
                            <div class="col-md-4"><input disabled type="text" name="vehicle_no" value="<?php echo $vehicle_no;?>" class="form-control"/></div>
                        </div>						
						
						<div class="form-row">
                            <div class="col-md-2">Date of Purchase *</div>
                            <div class="col-md-4"><input disabled id="date_of_purchase" type="text" name="purchase_date" value="<?php echo $purchase_date;?>" class="form-control validate[required]"/></div>
                        
                            <div class="col-md-2">Amount of Purchase *</div>
                            <div class="col-md-4">
								 <input type="text" disabled name="purchase_amount" value="<?php echo $purchase_amount;?>" class="form-control validate[required]"/>
							</div>
						</div>
                        
						<div class="form-row">
                             <div class="col-md-2">P.O. No.</div>
                            <div class="col-md-4"><input disabled type="text" name="po_no" value="<?php echo $po_no;?>" class="form-control"/></div>
                        
                            <div class="col-md-2">Warranty Period</div>
                            <div class="col-md-4">
							 <input type="text" disabled name="warranty_period" value="<?php echo $warranty_period;?>" class="form-control"/> 
							</div>
						</div>
						<div class="form-row rto_fld" style="display:none;">
                             <div class="col-md-2">RTO Registration No.</div>
                            <div class="col-md-4"><input disabled type="text" name="rto_reg_no" value="<?php echo $rto_reg_no;?>" class="form-control"/></div>
                        
                            <div class="col-md-2">Due Date of Registration</div>
                            <div class="col-md-4">
							 <input type="text" disabled name="due_date_reg_old" value="<?php echo $due_date_reg;?>" class="datepick form-control"/> 
							</div>
						</div>
						<div class="form-row">
                             <!--<div class="col-md-2">Insurance Company</div>
                            <div class="col-md-4"><input type="text" name="insurance_company" value="<?php echo $insurance_company;?>" class="form-control"/></div>-->
							
							<div class="col-md-2">Due Date of Road Tax</div>
							<div class="col-md-2">
								<select disabled name="road_tax_status" id="road_tax_status">
									<option value="0" <?php echo ($road_tax_status == 0)?"selected":"";?>>No</option>
									<option value="1" <?php echo ($road_tax_status == 1)?"selected":"";?>>Yes</option>
								</select>
							</div>
                            <div class="col-md-2" id="due_date_road_tax_div" style="visibility:<?php echo ($road_tax_status)?"visible":"hidden";?>">
							 <input disabled type="text" name="due_date_road_tax" value="<?php echo $due_date_road_tax; ?>" class="datepick form-control due_date_road_tax"/> 
							</div>
							
                            <div class="col-md-2">Due Date of Passing / Registration</div>
                            <div class="col-md-2">
								<select disabled name="passing_registration_status" id="passing_registration_status">
									<option value="0" <?php echo ($passing_registration_status == 0)?"selected":"";?>>No</option>
									<option value="1" <?php echo ($passing_registration_status == 1)?"selected":"";?>>Yes</option>
								</select>
							</div>
                            <div class="col-md-2" id="passing_registration_div" style="visibility:<?php echo ($passing_registration_status)?"visible":"hidden";?>">
							 <input type="text" disabled name="due_date_reg" value="<?php echo $due_date_reg;?>" class="datepick form-control due_date_reg"/> 
							</div>
						</div>
						<div class="form-row">
                             <!--<div class="col-md-2">Insurance Company</div>
                            <div class="col-md-4"><input type="text" name="insurance_company" value="<?php echo $insurance_company;?>" class="form-control"/></div>-->
							
							<div class="col-md-2">Due Date of Fitness</div>
                            <div class="col-md-2">
								<select disabled name="fitness_status" id="fitness_status">
									<option value="0" <?php echo ($fitness_status == 0)?"selected":"";?>>No</option>
									<option value="1" <?php echo ($fitness_status == 1)?"selected":"";?>>Yes</option>
								</select>
							</div>
                            <div class="col-md-2" id="due_date_fitness_div" style="visibility:<?php echo ($fitness_status)?"visible":"hidden";?>">
							 <input type="text" disabled name="due_date_fitness" value="<?php echo $due_date_fitness; ?>" class="datepick form-control due_date_fitness"/> 
							</div>
							
                            <div class="col-md-2">Due Date of Insurance</div>
                            <div class="col-md-2">
								<select disabled name="insurance_status" id="insurance_status">
									<option value="0" <?php echo ($insurance_status == 0)?"selected":"";?>>No</option>
									<option value="1" <?php echo ($insurance_status == 1)?"selected":"";?>>Yes</option>
								</select>
							</div>
                            <div class="col-md-2" id="due_date_insurance_div" style="visibility:<?php echo ($insurance_status)?"visible":"hidden";?>">
							 <input disabled type="text" name="due_date_insurance" value="<?php echo $due_date_insurance; ?>" class="datepick form-control due_date_insurance"/> 
							</div>
						</div>
						
						<div class="form-row">
                             <div class="col-md-2">Payment *</div>
                            <div class="col-md-4">
								<select disabled name="payment" class="form-control">
									<option value="cash">Cash</option>
									<option value="cheque" selected>Cheque</option>
								</select>
							</div>
                        
                            <div class="col-md-2">Voch. No. / Inw. No. *</div>
                            <div class="col-md-4">
								<input disabled type="text" name="voucher_no" value="<?php echo $voucher_no;?>" class="form-control validate[required]"/> 
							</div>
                         </div>
						<div class="form-row" style="display:none;">
                            <div class="col-md-2">Deployed To *</div>
                            <div class="col-md-10">
								<select disabled style="width: 100%;" class="select2" required="true"  name="deployed_to" id="deployed_to">
								<option value="">Select Project</option>
								<?php 
							 
								foreach($project_data as $key => $retrive_data)
								{
									echo $retrive_data['project_id'];
									echo '<option value="'.$retrive_data['project_id'].'" '.$this->ERPfunction->selected($retrive_data['project_id'],2).'>'.$this->ERPfunction->get_projectname($retrive_data['project_id']).'</option>';
								}  
								?>
								</select>
							</div>
                        </div>
						<div class="form-row" style="display:none;">						
                            <div class="col-md-2">Deployed Quantity *</div>
                            <div class="col-md-4"><input disabled type="text" name="quantity" id="deployed_quantity" value="1" class="form-control validate[required]"/></div>
						</div>
						
						<div class="form-row" style="display:none;">
                             <div class="col-md-2">Operational Status</div>
							 <div class="col-md-4">
								<select disabled name="operational_status" class="form-control">
									<option value="working" <?php echo ($operational_status == "working")?"selected":"";?>>Working</option>
									<option value="notworking" <?php echo ($operational_status == "notworking")?"selected":"";?>>Not Working</option>
								</select>
							</div>
						</div>
						<div class="form-row">
                            <div class="col-md-2">Description</div>
                            <div class="col-md-10">
						
								<input disabled type="text" name="description" value="<?php echo $description;?>" class="form-control"/> 
							</div>
                        </div>	
						<div class="form-row">							
                            <div class="col-md-2"> Asset Image </div>
                            <div class="col-md-4"> 
								<a href="<?php echo $this->ERPfunction->get_signed_url($asset_data['asset_image']);?>" download="<?php echo $asset_data['asset_image'];?>" class="btn btn-info btn-clean"><i class="icon-download-alt"></i><?php echo $asset_data['asset_image'];?></a>
							</div>
							
						</div>						
						<div class="form-row">							
                            <div class="col-md-2"> Attached Documents</div>
                            
							
						</div>
						<div class="form-row add_field">
						<?php 
						if($asset_action == "edit")
						{
						$attached_files = json_decode($asset_data["attach_file"]);
						$attached_label = json_decode(stripcslashes($asset_data['attach_label']));						
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
										<input type='hidden' name='old_attach_file[]' value='<?php echo $file;?>' class='form-control'></div>
										<div class='col-md-2'></div>
									</div>
								</div>							
							<?php $i++;
							}
						}
						}
						?>
						</div>
						
						
						
						<?php
					if($asset_action == 'edit')
					{
					?>
						<div class="form-row">
                            <div class="col-md-3"><?php echo "Created By :".$created_by; ?></div>
                            <div class="col-md-3"><?php echo "Last Edited By :".$last_edit_by; ?></div>
                            <div class="col-md-3"><?php echo "Last Edited On :".$last_edit_on; ?></div>
							<div class="col-md-3">						 
							  <a href="../printasset/<?php echo $asset_data['asset_id'];?>" class="btn btn-default" id="print_this" target="_blank"><i class="icon-print"></i> Print</a>
							</div> 
                        </div>
					<?php
					}
					?>
				</div>
				<?php $this->Form->end(); ?>
			</div>
<?php //} ?>
 </div>
<script>
$(".create_field").click(function(){
	var label = $(".add_label").val();
	$(".add_label").val("");
	var field = "<div class='del_parent'><div class='form-row'><div class='col-md-2'>"+ label +"<input type='hidden' name='attach_label[]' value='"+label+"' class='form-control'></div><div class='col-md-4'><input type='file' name='attach_file[]'></div><div class='col-md-2'><span class='del_file btn btn-danger'>x Remove</span></div></div></div>";
	$(".add_field").append(field);
});

$("body").on("click",".del_file",function(){
	$(this).parentsUntil('.del_parent').remove();
});

$("#purchase_quantity").change(function(){
	var pqty = $(this).val();
	$("#deployed_quantity").val(pqty);
});
</script>