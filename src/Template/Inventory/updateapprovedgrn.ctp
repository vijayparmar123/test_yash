<?php
use Cake\Routing\Router; 

$project_code = isset($update_grn['project_code'])?$update_grn['project_code']:'';

$project_id = isset($update_grn['project_id'])?$update_grn['project_id']:'';
$grn_no = isset($update_grn['grn_no'])?$update_grn['grn_no']:'';

$reference_no=isset($update_grn['reference_no'])?$update_grn['reference_no']:'';
$time=isset($update_grn['grn_time'])?$update_grn['grn_time']:'';
$date=isset($update_grn['grn_date'])?date('Y-m-d',strtotime($update_grn['grn_date'])):'';
$vendor_name = isset($update_grn['vendor_userid'])?$update_grn['vendor_userid']:'';
$vendor_id = isset($update_grn['vendor_id'])?$update_grn['vendor_id']:'';
$po_id = isset($update_grn['po_id'])?$update_grn['po_id']:'';
$pr_id = isset($update_grn['pr_id'])?$update_grn['pr_id']:'';
$challan_no = isset($update_grn['challan_no'])?$update_grn['challan_no']:'';
$driver_name = isset($update_grn['driver_name'])?$update_grn['driver_name']:'';
$vehicle_no = isset($update_grn['vehicle_no'])?$update_grn['vehicle_no']:'';
$payment_method = isset($update_grn['payment_method'])?$update_grn['payment_method']:'';
$remarks = isset($update_grn['remarks'])?$update_grn['remarks']:'';
$purchase_amt = isset($update_grn['purchase_amt'])?$update_grn['purchase_amt']:'';
$freight = isset($update_grn['freight'])?$update_grn['freight']:'';
$unloading = isset($update_grn['unloading'])?$update_grn['unloading']:'';
$vouchar_no = isset($update_grn['vouchar_no'])?$update_grn['vouchar_no']:'';
$total_amt = isset($update_grn['total_amt'])?$update_grn['total_amt']:'';

$agency_name=isset($update_grn['agency_name'])?$update_grn['agency_name']:'';
$written_by=isset($update_grn['written_by'])?$update_grn['written_by']:'';
$agency_client_name=isset($update_grn['agency_client_name'])?$update_grn['agency_client_name']:'';
$designation=isset($update_grn['designation'])?$update_grn['designation']:'';
$subject=isset($update_grn['subject'])?$update_grn['subject']:'';
$enclosures=isset($update_grn['enclosures'])?$update_grn['enclosures']:'';
$out_inward_no=isset($update_grn['out_inward_no'])?$update_grn['out_inward_no']:'';
$inward_date=isset($update_grn['inward_date'])?date('Y-m-d',strtotime($update_grn['inward_date'])):'';
$comment=isset($update_grn['comment'])?$update_grn['comment']:'';
$image_old=(isset($update_grn['attachment']))?$update_grn['attachment']:'';
$created_by = isset($update_grn['created_by'])?$this->ERPfunction->get_user_name($update_grn['created_by']):'NA';
$last_edit = isset($update_grn['last_edit'])?date("m-d-Y H:i:s",strtotime($update_grn['last_edit'])):'NA';
$last_edit_by = isset($update_grn['last_edit_by'])?$this->ERPfunction->get_user_name($update_grn['last_edit_by']):'NA';

?>
<script type="text/javascript">
jQuery(document).ready(function() {
	//jQuery('#user_form').validationEngine();
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
					jQuery('#grn_no').val(json_obj['grn_no']);						
					jQuery('#po_list').append(json_obj['po_data']);						
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
	// debug($update_grn);die;
?>				
                <div class="block block-fill-white">
					<div class="head bg-default bg-light-rtl">
						<h2><?php echo $form_header;?> </h2>
						<div class="pull-right">
						<a href="<?php echo $this->ERPfunction->action_link('Inventory','viewgrn');?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
						</div>
					</div>
		
					<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
					<input type="hidden" name="user_action" class="form-control" value="<?php echo $user_action;?>"/>	
					
					 <div class="content controls">
						<div class="form-row">
                            <div class="col-md-2">Project Code*</div>
                            <div class="col-md-4"><input type="text" name="project_code" id="project_code" value="<?php echo (isset($selected_pl))?$this->ERPfunction->get_projectcode($project_id):"";?>"
							class="form-control validate[required]" value="" readonly="true"/></div>
							<div class="col-md-2">Project Name</div>
                            <div class="col-md-4">
								<select class="select2"  required="true"   style="width: 100%;" name="project_id" id="project_id">
								<option value="">--Select Project--</Option>
								<?php 
									foreach($projects as $retrive_data)
									{
										echo '<option value="'.$retrive_data['project_id'].'" '.(($project_id == $retrive_data["project_id"])?"selected":"").'>'.
										$retrive_data['project_name'].'</option>';
									}
								?>
								</select>
							</div>
                        </div>
						<div class="form-row">
                            <div class="col-md-2">G.R.N. / G. R. N. L. P. No.</div>
                            <div class="col-md-4">
								<input type="text" name="grn_no" id="grn_no" class="form-control" value="<?php echo (isset($selected_pl)) ? /*$this->ERPfunction->get_projectcode($grn_no).*/ $grn_no : ""; ?> "/>
							</div>
                        
                            <div class="col-md-1">Date</div>
                            <div class="col-md-2">
								<input type="text" name="grn_date" id="grn_date" value="<?php echo date("d-m-Y",strtotime($date));?>" class="form-control"/>
							</div>
							<div class="col-md-1">Time</div>
                            <div class="col-md-2"><input type="text" name="grn_time" id="grn_time" value="<?php echo $time;?>" class="form-control"/></div>
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
								{echo '<option value="'.$retrive_data['user_id'].'"'.(($vendor_name == $retrive_data["user_id"])?"selected":"").'>'.
										$this->ERPfunction->get_vendor_name($retrive_data['user_id']).'</option>';									
									
								}
								?>
								</select>
							</div>
                        
                             <div class="col-md-2">Vendor ID</div>
                            <div class="col-md-4">
								<input type="text" name="vendor_id" id="vendor_id" value="<?php echo $vendor_id;?>" class="form-control" />
							</div>
                        </div>						
						<div class="form-row">
                            <div class="col-md-2"><?php echo ($po_id != "") ? "P.O. No.":"P.R. No.";?></div>
                            <div class="col-md-4">
								<select class="select2"  required="true"   style="width:100%" id="po_list" name="po_id">
								<?php
								/* if(!empty($po_id)) */
								if($po_id != "")
								{
									$data = $this->ERPfunction->get_po_records($po_id);
									// var_dump($data);die;
									$po_no = $data['po_no'];
									 if(isset($po_id)){ ?>
									<option value="<?php echo $po_id; ?>"><?php echo (!empty($po_no))?$po_no:"";?></option>
									<?php } 
									}
								if($pr_id != "")
								{
									$data = $this->ERPfunction->get_pr_records($pr_id);
									// var_dump($data);die;
									$prno = $data['prno'];
									 if(isset($pr_id)){ ?>
									<option value="<?php echo $pr_id; ?>"><?php echo (!empty($prno))?$prno:"";?></option>
									<?php } 
								}
								?>
								
								</select>
								
								
							</div>
							<!--
							<div class="col-md-2">Attach Challan/bill</div>
							<div class="col-md-4">
								<input type="file" name="challan_bill" class="form-control">
							</div>
							-->
						</div>
						<div class="form-row">
                            <div class="col-md-2">Challan No</div>
							<div class="col-md-4">
								<input type="text" name="challan_no" id="challan_no" class="form-control" value="<?php echo $challan_no;?>"/>
							</div>
							<!--
							<div class="col-md-2">Attach Gate Pass</div>
							<div class="col-md-4">
								<input type="file" name="gate_pass" class="form-control">
							</div>
							-->
                        </div>
						<div class="form-row">
                            <div class="col-md-2">Driver's Name</div>
                            <div class="col-md-4">
								<input type="text" name="driver_name" id="driver_name" class="form-control" value="<?php echo $driver_name;?>"/>
							</div>
                        
                            <div class="col-md-2">Vehicle's No</div>
							<div class="col-md-4">
								<input type="text" name="vehicle_no" id="vehicle_no" value="<?php echo $vehicle_no;?>" class="form-control"/>
							</div>
                        </div>
						<div class="form-row">
                            <div class="col-md-2">Payment Method</div>
                            <div class="col-md-4">
								<div class="radiobox-inline">
                                    <label><input type="radio" name="payment_method" value="Cheque" <?php echo ($payment_method == "Cheque")?'checked':'';?>/> Cheque</label>
                                </div>
                                <div class="radiobox-inline">
                                    <label><input type="radio" name="payment_method" value="Cash" <?php echo ($payment_method == "Cash")?'checked':'';?>/> Cash</label>
                                </div> 
							</div>
						</div>
							
						<div class="paymeny_block" style="display:none;">
						<div class="form-row">
                            <div class="col-md-2">Purchase Amt (Rs.)</div>
                            <div class="col-md-3">
								<input type="text" name="purchase_amt" id="purchase_amt" class="total_amt form-control" value="<?php echo $purchase_amt;?>"/>
							</div>
							 <div class="col-md-1">Freight (Rs.)</div>
                            <div class="col-md-2">
								<input type="text" name="freight" id="freight" class="total_amt form-control" value="<?php echo $freight;?>"/>
							</div>
							 <div class="col-md-1">Unloading(Rs.)</div>
                            <div class="col-md-3">
								<input type="text" name="unloading" id="unloading" class="total_amt form-control" value="<?php echo $unloading;?>"/>
							</div>
                        </div>
						<div class="form-row">
                            <div class="col-md-2">Voucher No</div>
                            <div class="col-md-4">
								<input type="text" name="vouchar_no" id="vouchar_no" class="form-control" value="<?php echo $vouchar_no;?>"/>
							</div>
							<div class="col-md-2">Total Amt Paid (Rs.)</div>
                            <div class="col-md-4">
								<input type="text" name="total_amt" id="total_amt" class="form-control" value="<?php echo $total_amt;?>"/>
							</div>
                        </div>
						<br>
						</div>
						<div class="form-row" style="padding:15px 0 0 60px;">
                            <table class="table table-bordered" style="color:#333!important;">
								<thead>
									<tr>
									<th rowspan="2">Material Code</th>
									<th colspan="2">Material / Item</th>
									<th rowspan="2">Vendor's Qty./Weight</th>
									<th rowspan="2">Actual Qty. / Weight</th>
									<th rowspan="2">Difference (+/-)</th>
									<th rowspan="2">Unit</th>
								<!-- <th rowspan="2">Remarks by Inspector</th> -->
									</tr>
									<tr>
									<th>Description</th>
									<th>Make/ Source</th>
									
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
						<div class="form-row">			<br>				
	                            <div class="col-md-2"> Attach Documents</div>
	                            <div class="col-md-4">
									<input class="add_label form-control">
								</div>
								<div class="col-md-1">
									<a href="javascript:void(0)" class="create_field form-control">+&nbsp;Add</a>
								</div>
								<!-- <div class="col-md-1">Remarks</div>
	                            <div class="col-md-4">
									<input type="text" name="remarks" id="remarks" value="<?php //echo $remarks;?>" class="form-control"/>
								</div> -->
							</div>
							<div class="add_field">
							<?php 
							if($selected_pl)
							{
							$attached_files = json_decode($update_grn["attach_file"]);
							$attached_label = json_decode(stripcslashes($update_grn['attach_label']));						
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
							
					<!-- <div class="form-row">
                            <div class="col-md-1 pull-right"><button type="button" id="add_newrow" class="btn btn-primary">Add New</button></div>
                        </div> -->
						<div class="form-row">
                            <div class="col-md-2"></div>
                            <div class="col-md-4"><br><br><button type="submit" class="btn btn-primary">Update G.R.N</button></div>
                        </div>
						<div class="form-row">
                            <div class="col-md-2"></div>
                            <div class="col-md-4 pull-right text-right"><i>Created By : <?php echo $created_by;?></i></div>
                        </div>
					</div>
					
				<?php $this->Form->end(); ?>
			</div>
<?php }?>
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
});
</script>
