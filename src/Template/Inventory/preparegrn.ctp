<?php
use Cake\Routing\Router;
?>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('#user_form').validationEngine();
	jQuery('#grn_date').datepicker({
		dateFormat: "dd-mm-yy",
		  changeMonth: true,
	        changeYear: true,
	        yearRange:'-65:+0',
	        onChangeMonthYear: function(year, month, inst) {
	            jQuery(this).val(month + "-" + year);
	        }                    
    });
	
	jQuery("body").on("change", "#project_id", function(event){ 
	 jQuery('#po_list').html("<option value='' selected>Select PO</option>");
	  var project_id  = jQuery(this).val() ;
		/* alert(product_id);
		return false; */
	   var curr_data = {	 						 					
	 					project_id : project_id,	 					
	 					};	 				
	 	 jQuery.ajax({
                type:"POST",
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'ingrnprojectdetaillppo'));?>",
                data:curr_data,
                async:false,
                success: function(response){					
					var json_obj = jQuery.parseJSON(response);					
					jQuery('#project_code').val(json_obj['project_code']);						
					// jQuery('#grn_no').val(json_obj['grn_no']);						
					jQuery('#po_list').append(json_obj['po_data']);						
					/* jQuery('#entered_pr_id').val(json_obj['project_code'] + "/PR/");	 */
					return false;
                },
                error: function (e) {
                     alert('Error');
					 console.log(e.responseText);
                }
            });	
	});
	jQuery("body").on("change", "#pr_id", function(event){ 
		var pr_id  = jQuery(this).val() ;
		/* alert(product_id);
		return false; */
	   var curr_data = {	 						 					
	 					pr_id : pr_id,	 					
	 					};	 				
	 	 jQuery.ajax({
                type:"POST",
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'loadgrnitems'));?>",
                data:curr_data,
                async:false,
                success: function(response){					
					var json_obj = jQuery.parseJSON(response);		
						
					jQuery('#contact_no1').val(json_obj['contact_no1']);						
					jQuery('#contact_no2').val(json_obj['contact_no2']);						
					jQuery('.table tbody').html('');
					jQuery('.table tbody').html(json_obj['pritems']);	
					return false;
                },
                error: function (e) {
                     alert('Error');
                }
            });	
	});
	jQuery("body").on("change", "#vendor_userid", function(event){ 
		 var vendor_userid  = jQuery(this).val() ;
		/* alert(product_id);
		return false; */
	   var curr_data = {	 						 					
	 					vendor_userid : vendor_userid,	 					
	 					};	 				
	 	 jQuery.ajax({
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

	jQuery('.delivery_date').datepicker({
		 changeMonth: true,
      changeYear: true,
	  dateFormat: "dd-mm-yy"
	});
	
	 jQuery('body').on('blur','.actualy_qty',function(){ 	
		var row_id = jQuery(this).attr('data-id');
			var qty = jQuery('#quantity_'+row_id).val();
			var actual_qty = jQuery(this).val();
			var amount = 0;
			var diff = actual_qty - qty;
			if(diff > 0)
			{
				jQuery('#difference_qty_'+row_id).val(diff + " : More");
			}else{
				jQuery('#difference_qty_'+row_id).val(diff + " : Less");
			}
			
    });
	
	jQuery("body").on("change", "input[type=radio][name=payment_method]", function(event){ 
	
		var payment_method = jQuery(this).val();
		
		if(payment_method == 'Cash')
		{
			//alert('hello' + payment_method);
			//jQuery(".paymeny_block").css({ display: "block" });
			jQuery(".paymeny_block").fadeIn('slow');
		}
		else
			jQuery(".paymeny_block").fadeOut('slow');
	});
	
	jQuery("body").on("change","#po_list",function(){
		jQuery('#add_row').html("");
	  var po_id  = jQuery(this).val() ;
		/* alert(po_id);
		return false; */
	   var curr_data = {
	 					po_id : po_id,	 					
	 					};	 				
	 	 jQuery.ajax({
                type:"POST",
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'getpoitems'));?>",
                data:curr_data,
                async:false,
                success: function(response){					
					var json_obj = jQuery.parseJSON(response);					
						jQuery('#add_row').append(json_obj['po_data']);									
						jQuery('#vendor_id').val(json_obj['vendor_id']);
						jQuery("#vendor_userid").val(json_obj['vendor']).change();
										
					return false;
                },
                error: function (e) {
					console.log(e.responseText);
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
                      url: '<?php echo Router::url(["controller" => "Ajaxfunction","action" => "addnewrow"]);?>',
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
		
	jQuery("body").on("click",".del_item",function(){
		jQuery(this).parents("tr").remove();
	});
	
	jQuery("#remove_po").click(function(){
		if(confirm("Are you sure you want to remove selected P.O.'s remaining quantity."))
		{
			if(confirm("Are you sure you want to remove selected P.O.'s remaining quantity."))
			{
				var po_id = $("#po_list").val();
				
				var url = '<?php echo $this->request->base . "/ajaxfunction/removepofromgrn";?>';
				data = {po_id : po_id};
				$.ajax({
					url : url,
					type : "POST",
					data : data,
					success : function(result){
						alert("P.O. quantity removed successfully.");
						$("#pr_id option:selected").remove();
						$("#po_list").change();
					},
					error : function(e){
						console.log(e.responseText);
					}
				});
			}
		}
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
						<h2><?php echo $form_header;?> </h2>
						<div class="pull-right">
						<a href="<?php echo $this->ERPfunction->action_link('Inventory','index');?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
						</div>
					</div>
		
					<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
					<input type="hidden" name="user_action" class="form-control" value="<?php echo $user_action;?>"/>	
					
					 <div class="content controls">
						<div class="form-row">
                            <div class="col-md-2">Project Code<span class="require-field"></span> </div>
                            <div class="col-md-4"><input type="text" name="project_code" id="project_code" value="<?php echo (isset($selected_pl))?$this->ERPfunction->get_projectcode($po_data["project_id"]):"";?>"
							class="form-control validate[required]" value="" readonly="true"/></div>
							<div class="col-md-2">Project Name*</div>
                            <div class="col-md-4">
								<select class="select2"  required="true"   style="width: 100%;" name="project_id" id="project_id">
								<option value="">--Select Project--</Option>
								<?php 
									foreach($projects as $retrive_data)
									{
										echo '<option value="'.$retrive_data['project_id'].'" '.(($selected_pl && $po_data["project_id"] == $retrive_data["project_id"])?"selected":"").'>'.
										$retrive_data['project_name'].'</option>';
									}
								?>
								</select>
							</div>
                        </div>
						<div class="form-row">
                            <!--<div class="col-md-2">G.R.N. No.</div>
                            <div class="col-md-4">
								<input type="text" name="grn_no" id="grn_no" class="form-control" value="<?php echo (isset($selected_pl)) ? $this->ERPfunction->get_projectcode($po_data["project_id"]). $auto_grn_no : ""; ?> "/>
							</div>-->
                        
                            <div class="col-md-2 text-right">Date*</div>
                            <div class="col-md-2"><input type="text" name="grn_date" id="grn_date" value="" class="form-control validate[required]" value=""/></div>
							<div class="col-md-1 text-right">Time*</div>
                            <div class="col-md-2"><input type="text" name="grn_time" id="grn_time" value="" class="form-control validate[required]" value=""/></div>
                        </div>
						<div class="form-row">
                           <div class="col-md-2">Pending P.O. No.*</div>
                            <div class="col-md-4">
								 <select class="select2" required="true" style="width:100%" id="po_list" name="po_id">
									<?php if(isset($po_data)){ ?>
									<option value="<?php echo $po_data["po_id"]; ?>"><?php echo $po_data["po_no"] ?></option>
									<?php } ?>
								</select> 
							</div>
							<?php
							if($role == "erphead" || $role == "erpmanager" || $role == "constructionmanager")
							{ ?>
							<a href="javascript:void(0)" id="remove_po" class="btn btn-danger btn-xs" title="Remove P.R. from list"><span class="icon-trash"></span> </a>
							<?php } ?>
								<!-- <div class="col-md-2">P.R. No.</div>
								<div class="col-md-4">
									<input class="form-group" name="entered_pr_id" id="entered_pr_id">								
								</div> -->
							<!--
							<div class="col-md-2">Attach Challan/bill</div>
							<div class="col-md-4">
								<input type="file" name="challan_bill" class="form-control">
							</div>
							-->
						</div>
						<div class="form-row">
                            <div class="col-md-2">Vendor Name</div>
                            <div class="col-md-4">
								<?php 
								
								?>
								<select class="select2"  required="true"   style="width: 100%;" name="vendor_userid" id="vendor_userid">
								<option value="">--Select Vendor--</Option>
								<?php 
									foreach($vendor_department as $retrive_data)
								{echo '<option value="'.$retrive_data['user_id'].'">'.
										$this->ERPfunction->get_vendor_name($retrive_data['user_id']).'</option>';									
									
								}
								?>
								</select>
							</div>
                        
                             <div class="col-md-2">Vendor ID</div>
                            <div class="col-md-4">
								<input type="text" name="vendor_id" id="vendor_id" value="" class="form-control" value=""/>
							</div>
                        </div>					
						
						<div class="form-row">
                            <div class="col-md-2">Challan No*</div>
							<div class="col-md-4">
								<input type="text" name="challan_no" id="challan_no" class="form-control validate[required]" value=""/>
							</div>
							<!--
							<div class="col-md-2">Attach Gate Pass</div>
							<div class="col-md-4">
								<input type="file" name="gate_pass" class="form-control">
							</div>
							-->
                        </div>
						<div class="form-row">
                            <div class="col-md-2">Driver's Name*</div>
                            <div class="col-md-4">
								<input type="text" name="driver_name" id="driver_name" class="form-control validate[required]" value=""/>
							</div>
                        
                            <div class="col-md-2">Vehicle's No*</div>
							<div class="col-md-4">
								<input type="text" name="vehicle_no" id="vehicle_no" value="" class="form-control validate[required]"/>
							</div>
                        </div>
						<div class="form-row">
                            <div class="col-md-2">Payment Method</div>
                            <div class="col-md-4">
								<div class="radiobox-inline">
                                    <label><input type="radio" name="payment_method" value="Cheque" checked="checked" /> Cheque</label>
                                </div>
                                <div class="radiobox-inline">
                                    <label><input type="radio" name="payment_method" value="Cash" /> Cash</label>
                                </div> 
							</div>
						</div>							
						<div class="paymeny_block" style="display:none;">
							<div class="form-row">
								<div class="col-md-2">Purchase Amt (Rs.)</div>
								<div class="col-md-3">
									<input type="text" name="purchase_amt" id="purchase_amt" class="total_amt form-control" value="0"/>
								</div>
								 <div class="col-md-1 text-right" style="padding-right: 0px;">Freight (Rs.)</div>
								<div class="col-md-2">
									<input type="text" name="freight" id="freight" class="total_amt form-control" value="0"/>
								</div>
								 <div class="col-md-1">Unloading(Rs.)</div>
								<div class="col-md-3">
									<input type="text" name="unloading" id="unloading" class="total_amt form-control" value="0"/>
								</div>
							</div>
							<div class="form-row">
								<div class="col-md-2">Voucher No</div>
								<div class="col-md-4">
									<input type="text" name="vouchar_no" id="vouchar_no" class="form-control" value=""/>
								</div>
								<div class="col-md-2">Total Amt Paid (Rs.)</div>
								<div class="col-md-4">
									<input type="text" name="total_amt" id="total_amt" class="form-control" value=""/>
									<br>
								</div>
							</div>
						</div>						
						<div class="form-row">							
	                            <div class="col-md-2"> Attach Documents</div>
	                            <div class="col-md-4">
									<input class="add_label form-control">
								</div>
								<div class="col-md-2">
									<a href="javascript:void(0)" class="create_field form-control text-center">+&nbsp;Add</a>
								</div>
								<!--
								<div class="col-md-1">Remarks</div>
	                            <div class="col-md-4">
									<input type="text" name="remarks" id="remarks" value="" class="form-control"/>
								</div> -->
						</div>
						<div class="add_field">
							<?php 
							if($user_action == "edit")
							{
							$attached_files = json_decode($update_inward["attach_file"]);
							$attached_label = json_decode(stripcslashes($update_inward['attach_label']));						
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
											<div class='col-md-4'><a href="<?php echo $this->request->base;?>/img/users_images/<?php echo $file;?>" class="btn btn-primary" target="_blank">View File</a>
											<input type='hidden' name='old_attach_file[]' value='<?php echo $file;?>' class='form-control'></div>
											<div class='col-md-2'><span class='del_file btn btn-danger'>x Remove</span></div>
										</div>
									</div>							
								<?php $i++;
								}
							}
							}
							?>
						</div>
						
						<br>						
						<div class="form-row" style="padding-top:15px;">
                            <table class="table table-bordered">
								<thead>
									<tr>
									<th rowspan="2">Material Code</th>
									<th colspan="2">Material / Item</th>
									<th rowspan="2">Vendor's Qty./Weight</th>
									<th rowspan="2">Actual Qty. / Weight</th>
									<th rowspan="2">Difference (+/-)</th>
									<th rowspan="2">Unit</th>
								<!-- <th rowspan="2">Remarks by Inspector</th> -->
									<th rowspan="2">Delete</th>
									</tr>
									<tr>
									<th style="width: 400px;">Description</th>
									<th>Make / Source</th>
									
									</tr>
								</thead>
								<tbody id="add_row">
								<?php 
									if(isset($selected_pl))
									{
										echo $row;
									}
								?>
								</tbody>
							</table>
                        </div>
					<!-- <div class="form-row">
                            <div class="col-md-1 pull-right"><button type="button" id="add_newrow" class="btn btn-primary">Add New</button></div>
                        </div> -->
						<div class="form-row">
                            <div class="col-md-2"></div>
                            <div class="col-md-4"><button type="submit" class="btn btn-primary"><?php echo $button_text;?></button></div>
                            
							<!-- <div class="col-md-4 pull-right"><a href="javascript:void(0);" data-url='<?php //echo $this->request->base ."/Ajaxfunction/printgrn";?>' id="print_this" class="btn btn-info"><span class="icon-print"></span> Print</a></div> -->
                       	</div>
					</div>
					
				<?php $this->Form->end(); ?>
			</div>
<?php }?>
         </div>
<script>
$(".create_field").click(function(){
	var label = $(".add_label").val();
	if(label == "")
	{
		alert("Type Challan Name (Challan Date).");
		$(".add_label").focus();
		return false;
	}
	$(".add_label").val("");
	var field = "<div class='del_parent'><div class='form-row'><div class='col-md-2'>"+ label +"<input type='hidden' name='attach_label[]' value='"+label+"' class='form-control'></div><div class='col-md-4'><input type='file' name='attach_file[]'></div><div class='col-md-2'><span class='del_file btn btn-danger'>x Remove</span></div></div></div>";
	$(".add_field").append(field);
});

$("body").on("click",".del_file",function(){
	$(this).parentsUntil('.del_parent').remove();
});
</script>	 
<script>
$(document).ready(function(){
	$(".total_amt").change(function(){		
		var purchase = parseInt($("#purchase_amt").val());
		var freight = parseInt($("#freight").val());
		var unloading = parseInt($("#unloading").val());
		var total = purchase + freight + unloading;
		$("#total_amt").val(total);
	});
	
	$("#print_this").click(function(){		
		var url = $(this).attr('data-url');
		var frm = $('#user_form');
		window.open(url + "?data="+frm.serialize(),"_blank");
		 
		/* $.ajax({
			type : "POST",
			url : url,
			data : frm.serialize(),
			success : function(response){
					// alert(response);
					console.log(response);
			},
			error : function(e){
				console.log(e.responseText);
			}
		}); */ 
		
/* 		$(function() {
		  $('form#user_form').trigger('submit');
		});
	}); 
	
	$(function() {
  $('form#user_form').submit(function(event) {
		event.preventDefault(); // Prevent the form from submitting via the browser
		var form = $(this);
		var data = form.serialize();
	
		 var url = $("#print_this").attr('data-url');
		$.ajax({
		  type: "POST",
		  url: url,
		  data: data,
		  success : function(response){
					alert(response);
					console.log(response);
			},
			error : function(e){
				console.log(e.responseText);
			}
		});
	  }); */
	});
	
});
</script>
