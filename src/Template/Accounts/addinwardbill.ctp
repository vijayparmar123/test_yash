<?php
	$project_code=isset($update_inward)?$update_inward['project_code']:'';
	$inward_bill_no=isset($update_inward)?$update_inward['inward_bill_no']:'';
	$date=isset($update_inward)?date('d-m-Y',strtotime($update_inward['date'])):$current_date;
	$time=isset($update_inward)?$update_inward['time']:$current_time;
	$po_no=isset($update_inward)?$update_inward['po_no']:'';
	$bill_type=isset($update_inward)?$update_inward['bill_type']:'';
	$party_name=isset($update_inward)?$update_inward['party_name']:'';
	$party_id=isset($update_inward)?$update_inward['party_id']:'';
	$gst_no = isset($update_inward)?$update_inward['gst_no']:'';
	$payment_method=isset($update_inward)?$update_inward['payment_method']:'';
	$attachment_bill=isset($update_inward)?$update_inward['attachment_bill']:'';
	$invoice_no=isset($update_inward)?$update_inward['invoice_no']:'';
	$attachment_pass=isset($update_inward)?$update_inward['attachment_pass']:'';
	$bill_date=isset($update_inward)?date('d-m-Y',strtotime($update_inward['bill_date'])):'';
	$attachment_mmt_sheet=isset($update_inward)?$update_inward['attachment_mmt_sheet']:'';
	$credit_period=isset($update_inward)?$update_inward['credit_period']:'';
	$total_amt=isset($update_inward)?$update_inward['total_amt']:'';
	$qty_checked_by=isset($update_inward)?$update_inward['qty_checked_by']:'';
	$rate_checked_by=isset($update_inward)?$update_inward['rate_checked_by']:'';
	$remarks = isset($update_inward)?$update_inward['remarks']:'';
	
	$party_type=isset($update_inward)?$update_inward['party_type']:'';
	$new_party_name=isset($update_inward)?$update_inward['new_party_name']:'';
	$created_by=isset($update_inward)?$update_inward['created_by']:'';
	$last_edited_by=isset($update_inward)?$update_inward['last_edit_by']:'';
	$checked_by=(isset($update_inward) && $update_inward['checked_by'] != NULL)?$this->ERPfunction->get_full_user_name($update_inward['checked_by']):'';
	$approved_by=(isset($update_inward) && $update_inward['accept_by'] != NULL)?$this->ERPfunction->get_full_user_name($update_inward['accept_by']):'';
?>



<?php
	use Cake\Routing\Router;
?>
<script type="text/javascript">

var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
	jQuery(document).ready(function() {
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
				url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'getinwardbill'));?>",
				data:curr_data,
				async:false,
				success: function(response){
					var json_obj = jQuery.parseJSON(response);
					jQuery('#project_code').val(json_obj['project_code']);						
					//jQuery('#prno').val(json_obj['prno']);
					<?php
							// if(isset($update_inward))
							// {
					?>
					$('#reference_no').attr('value',json_obj.reference_no);
					<?php
							// }
					?>
					//$('#po_id').attr('value',json_obj.po_no);
					//return false;
				},
				error: function (e) {
					alert('Error');
                }
            });	
		});
		jQuery('#user_form').validationEngine();
		jQuery('#date1,#as_on_date').datepicker({
			dateFormat: "dd-mm-yy",
			changeMonth: true,
			changeYear: true,
			yearRange:'-65:+0',
			onChangeMonthYear: function(year, month, inst) {
				jQuery(this).val(month + "-" + year);
			}		
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
	
		jQuery("body").on("click", "#add_qty_checkedby", function(){	
			jQuery.ajax({
				headers: {
					'X-CSRF-Token': csrfToken
				},
                type:"POST",
				url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'addqtycheckedby'));?>",
				async:false,
				success: function(response){
					jQuery('#load_modal_checkedby .modal-content').html(response);		
				},
				error: function (tab) {
					alert('error');
				}
			});
		});
	
		jQuery("body").on("click", "#add_rate_checkedby", function(){	
			jQuery.ajax({
				headers: {
					'X-CSRF-Token': csrfToken
				},
                type:"POST",
				url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'addratecheckedby'));?>",
				async:false,
				success: function(response){
					jQuery('#load_modal_checkedby .modal-content').html(response);		
				},
				error: function (tab) {
					alert('error');
				}
			});
		});
	
		jQuery("body").on("click", "#save-qty-checked-by", function(){
			var qty_checked_by = jQuery("#qty-checked-by").val();
			if(qty_checked_by == "") {
				alert("Please Enter Name.");
				return false;
			}
			var curr_data = { category_title : qty_checked_by };			
			jQuery.ajax({
				headers: {
					'X-CSRF-Token': csrfToken
				},
                type:"POST",
				url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'saveqtycheckedby'));?>",
				data:curr_data,
				async:false,
				success: function(response){
					var json_obj = jQuery.parseJSON(response);
					jQuery('#qty-checked-by').val('');		
					jQuery('#qty-checked-listing tbody').append(json_obj['listing_data']);		
					jQuery('#qty-checkedby-category').append(json_obj['dropdown_data']);		
				},
				error: function (tab) {
					alert('error');
				}
			});
		});
	
		jQuery("body").on("click", "#save-rate-checked-by", function(){
			var rate_checked_by = jQuery("#rate-checked-by").val();
			if(rate_checked_by == "") {
				alert("Please Enter Name.");
				return false;
			}
			var curr_data = { category_title : rate_checked_by };	
			jQuery.ajax({
				headers: {
					'X-CSRF-Token': csrfToken
				},
                type:"POST",
				url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'saveratecheckedby'));?>",
				data:curr_data,
				async:false,
				success: function(response){
					var json_obj = jQuery.parseJSON(response);
					jQuery('#rate-checked-by').val('');		
					jQuery('#rate-checked-listing tbody').append(json_obj['listing_data']);		
					jQuery('#rate-checkedby-category').append(json_obj['dropdown_data']);		
				},
				error: function (tab) {
					alert('error');
				}
			});
		});

		jQuery("body").on("change","#select_party",function(event) {
			var party_id = $(this).val();
			var curr_data = {
				party_id:party_id
			}
			$.ajax({
				headers: {
					'X-CSRF-Token': csrfToken
				},
				type : "POST",
				url : "<?php echo Router::url(['controller' => "Ajaxfunction" , "action" => "partygstno"]); ?>",
				data : curr_data,
				async : false,
				success : function(response) {
					jQuery("#gst_no").val(response);
				},
				error : function(error) {

				}
			})
		});
	});
</script>	

<div class="modal fade" id="load_modal_checkedby" role="dialog">
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
	<div class="col-md-12" >              
		<div class="block block-fill-white">					
			<div class="head bg-default bg-light-rtl">
				<h2><?php echo $form_header;?>  </h2>
				<div class="pull-right">
					<?php
						if(isset($update_inward)){
					?>
					<a href="<?php //echo $_SERVER["HTTP_REFERER"];?>" onclick = "javascript:window.close();" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
					<?php
						}else {
					?>
					<a href="<?php echo $this->ERPfunction->action_link($back_url,'index');?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
					<?php } ?>
				</div>
			</div>
					
			<div class="header">
				<h2><u>Personal Information</u></h2>
			</div>
			<?php echo $this->Form->Create('form1',['id'=>'user_form','name'=>'user_form','novalidate'=>'false','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
			<div class="content controls">
				<div class="form-row">
					<div class="col-md-2">Project Code</div>
					<div class="col-md-4"><input type="text" name="project_code" id="project_code" value="<?php echo $project_code;?>"
					class="form-control validate[required]" value="" readonly="true"/></div>
					<div class="col-md-2">Project Name*</div>
					<div class="col-md-4">
						<select class="select2" required="true"  style="width: 100%;" name="project_id" id="project_id">
							<option value="">--Select Project--</Option>
							<?php 
								foreach($projects as $retrive_data) {
							?>
							<option value="<?php echo $retrive_data['project_id'];?>"<?php 
							if(isset($update_inward)){
								if($update_inward['project_id'] == $retrive_data['project_id']){
									echo 'selected="selected"';
								}
							}
							?> ><?php echo $retrive_data['project_name']; ?> </option>
							<?php		
								}
							?>
						</select>
					</div>
				</div>
				
				<div class="form-row">
					<?php
						// if(isset($update_inward))
						// {
					?>
					<div class="col-md-2">Bill Inward No </div>
					<div class="col-md-4"><input type="text" name="inward_bill_no" value="<?php echo $inward_bill_no; ?>" readonly="true" id="reference_no" class="form-control validate[required]" /></div>
					<?php
						// }
					?>
					<div class="col-md-2">Date</div>
					<div class="col-md-2"><input type="text" name="date" value="<?php echo $date; ?>" id = "date" readonly="true" class="form-control"/></div>
					<div class="col-md-2">Time&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="time" value="<?php echo $time; ?>" id = "" class="form-control" readonly="true" style="display: inline-block;width: 75px;"/></div>
				</div>						
					
				<div class="form-row">						
					<!--<div class="col-md-2">P.O./W.O. No </div>
					<div class="col-md-4"><input type="text" name="po_no" value="<?php //echo $po_no; ?>" id = "po_id" class="form-control" /></div>-->
					<div class="col-md-2">Type of Bill*</div>
					<div class="col-md-4">
						<select name="bill_type" class="select2" required="true"  style="width:100%;">
							<?php 
								$billtype=array(
									// 'Material/Item'=>'Material/Item',
									// 'Labour'=>'Labour',
									// 'Labour with Material/Item'=>'Labour with Material/Item',
									// 'Asset'=>'Asset',
									// "Others" => "Others"
													
									'All'=>'All',
									'Material/Item'=>'Material/Item',
									'Labour'=>'Labour',
									'Labour with Material/Item'=>'Labour with Material/Item',
									'Asset Maintenance'=>'Asset Maintenance',
									'Asset Purchase'=>'Asset Purchase',
									'Transport'=>'Transport',
									'Other'=>'Other',
									'Debit Note'=>'Debit Note',
									'Credit Note'=>'Credit Note',
									'Sub-Contract'=>'Sub-Contract',
									'YNEC Sales Bill'=>'YNEC Sales Bill',
									'YNEC E-way Bill'=>'YNEC E-way Bill',
									'Consultation'=>'Consultation',
									'Safety Material'=>'Safety Material'
								);

								
								foreach($billtype as $bill_key => $bill_value){
							?>
							<option value="<?php echo $bill_key ;?>" 
								<?php
									if(isset($update_inward)){
										if($update_inward['bill_type']== $bill_key){
											echo 'selected="selected"';
										}
									}

								?> 
							> <?php echo $bill_value; ?></option>
							<?php
								}
							?>
						</select>
					</div>		
				</div>
				<!-- <div class="form-row"> <hr/>
					<div class="col-md-2">Party Type</div>								
					<div class="col-md-2" id="radiogroup">
						<input type="radio" name="party_type"  id="default" class="change_party" value="old" <?php echo ($party_type == "old" || $party_type == "")?"checked":"";?>> Party from ERP List
					</div>
					<div class="col-md-2" id="radiogroup">
						<input type="radio" name="party_type" class="change_party" value="inwardbillparty" <?php echo ($party_type == "inwardbillparty")?"checked":"";?>> Party from Inward Bills
					</div>
					<div class="col-md-2" id="radiogroup">
						<input type="radio" name="party_type" class="change_party" value="new" <?php echo ($party_type == "new")?"checked":"";?>> New Party
					</div>
				</div> -->
				<input type="hidden" name="party_type"  id="default" class="change_party" value="old">
				
				<div class="form-row" id="new_party" style="display:<?php echo ($party_type == "new")?"block":"none";?>;">							
					<div class="col-md-2">Party's Name*</div>
					<div class="col-md-4">
						<input type="text" name="new_party_name" class="validate[required]" value="<?php echo $new_party_name;?>">
					</div>
				</div>

				<div class="form-row" id="old_party" style="display:<?php echo ($party_type == "old")?"block":"none";?>;">							
					<div class="col-md-2">Party's Name*</div>
					<div class="col-md-4">
						<select name="old_party" class="select2" <?php echo ($party_type == "old")? "required=true":"";?> style="width:100%;" id="select_party">
							<option value="">--Party Name--</option>
							<?php
								if($vendor_info){
									foreach($vendor_info as $vendor_row){
										?>
											<option value="<?php echo $vendor_row['user_id']; ?>" dataid="<?php echo $vendor_row['vendor_id'];?>" <?php 
														if(isset($update_inward)){
															if($update_inward['party_name'] == $vendor_row['user_id']){
																echo 'selected="selected"';
															}
														}

											?> ><?php echo $vendor_row['vendor_name'];?></option>

										<?php
									}
								}
								// if(!empty($agency_list))
								// {
								// 	foreach($agency_list as $agency){ ?>
										<!-- <option value="<?php //echo $agency['agency_id']; ?>" dataid="<?php //echo $agency['agency_id'];?>"  -->
										<?php 
											// if(isset($update_inward)){
											// 	if($update_inward['party_name'] == $agency['agency_id']){
											// 		echo 'selected="selected"';
											// 	}
											// }
										?> 
											<!-- > -->
											<?php //echo $agency['agency_name'];?></option>
									<?php	
								// 	}
								// }
							?>
							
							

						</select>

						<script type="text/javascript">
							$(function(){
								$('#select_party').change(function(){
									var optionSelected = $(this).find('option:selected').attr('dataid');	
									$('#party_id').attr('value',optionSelected);	
								});
							});
						</script>

					</div>
					<div class="col-md-2">Party's ID</div>
					<div class="col-md-4">
						<input type="text" name="party_id" readonly="true" value="<?php echo $party_id; ?>" id="party_id" class="form-control"/>
					</div>
				</div>

				<div class="form-row">
					<div class="col-md-2">Party GST No.</div>							
					<div class="col-md-4">
						<input type="text" name="gst_no" readonly="true" value="<?php echo $gst_no; ?>" id="gst_no">
					</div>
				</div>
					
				<div class="form-row" id="inward_party" style="display:<?php echo ($party_type == "inwardbillparty")?"block":"none";?>;">
					<div class="col-md-2">Party's Name*</div>
					<div class="col-md-4">
						<select name="inward_party" class="select2" <?php echo ($party_type == "inwardbillparty")? "required=true":"";?> style="width:100%;" id="select_inward_party">
							<option value="">--Party Name--</option>
							<?php
								if($inward_party){
									foreach($inward_party as $retrive_data){
										?>
										<option value="<?php echo $retrive_data; ?>"
										<?php 
											if(isset($update_inward)){
												if($update_inward['party_name'] == $retrive_data){
													echo 'selected="selected"';
												}
											}
										?>
										
										><?php echo $retrive_data; ?></option>

										<?php
									}
								}
							?>
						</select>

						<script type="text/javascript">
							$(function(){
								$('#select_party').change(function(){
									var optionSelected = $(this).find('option:selected').attr('dataid');
									$('#party_id').attr('value',optionSelected);	
								});
							});
						</script>
					</div>
					<!--<div class="col-md-2">Party's ID</div>
					<div class="col-md-4">
						<input type="text" name="party_id" value="<?php //echo $party_id; ?>" id="party_id" class="form-control"/>
						
					</div>-->
				</div>
					
				<div class="form-row"> <hr/>
					<div class="col-md-2">Payment Method</div>
					<div class="col-md-4">
						<select name="payment_method" class="select2" required="true"  style="width:100%;">
						<?php
							$payment=array(
											'cheque'=>'Cheque',
											'cash'=>'Cash'
									);

							foreach($payment as $pay_key=>$pay_val){
						?>
						<option value="<?php echo $pay_key;?>" <?php 
								if(isset($update_inward)){
									if($update_inward['payment_method'] == $pay_key){
										echo 'selected="selected"';
									}
								}
							?> ><?php echo $pay_val; ?></option>
						<?php 
							}
						?>
						</select>

					</div>  

					<div class="col-md-2">Invoice No*</div>
					<div class="col-md-4"><input type="text" name="invoice_no" value="<?php echo $invoice_no;?>" class="form-control validate[required]"/></div>
					<!--
					<div class="col-md-2">Attach Bill</div>
						<div class="col-md-4">
							<input type="hidden" value="<?php echo $attachment_bill; ?>" name="old_bill">
							<input type="file" name="attachment_bill" value="" class="form-control"/>
						</div>   
					-->	
				</div>					
										
				<div class="form-row">
					
				<!--
					<div class="col-md-2">Attach GatePass</div>
					<div class="col-md-4">
						<input type="hidden" value="<?php echo $attachment_pass; ?>" name="old_pass">
						<input type="file" name="attachment_pass" id="" value="" class="form-control"/>
					</div>
				-->
				</div>



				<div class="form-row">
					<div class="col-md-2">Bill Date*</div>
					<div class="col-md-4"><input type="text" name="bill_date" value="<?php echo $bill_date; ?>" id="as_on_date" class="form-control validate[required]"/></div>
	
					<!-- <div class="col-md-2">Attach Measurement Sheet</div>
					<div class="col-md-4">
						<input type="hidden" value="<?php echo $attachment_mmt_sheet;?>" name="old_mmt_sheet">
						<input type="file" name="attachment_mmt_sheet" id="" value="" class="form-control"/>
					</div> -->
				<div class="col-md-2">Total Amount*</div>
					<div class="col-md-4"><input type="text" name="total_amt" value="<?php echo $total_amt; ?>" id="" class="form-control validate[required]"/></div>
				</div>

				<div class="form-row">
					<?php 
						if($user_action == "edit") { ?>
							<div class="col-md-2">Credit Period*</div>
					<div class="col-md-4"><input type="text" name="credit_period" value="<?php echo $credit_period; ?>" id = "credit_period" class="form-control validate[required]" /></div>
							<?php 
						}
					?>
					
					
				</div>

				<div class="form-row">							
					<div class="col-md-2"> Attach Documents</div>
					<div class="col-md-4">
						<input class="add_label form-control" placeholder="Enter file name">
					</div>
					<div class="col-md-1">
						<a href="javascript:void(0)" class="create_field form-control">+&nbsp;Add</a>
					</div>
				</div>

				<div class="form-row add_field">
					<?php 
						if($user_action == "edit") {
							$attached_files = json_decode($update_inward["attach_file"]);
							$attached_label = json_decode(stripcslashes($update_inward['attach_label']));						
							if(!empty($attached_files)) {							
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
					<?php
						// if($user_action == "edit") { ?>
							<!-- <div class="col-md-2">Qty. Checked By</div>
							<div class="col-md-3">
								<select class="select2" required="true" style="width: 100%;" id="qty-checkedby-category" name="qty_checkedby">
									<option value=""><?php echo __('--Select User--'); ?></option>
									<?php 
									// if($user_action == "edit"){
										// $subgrouplist = $this->ERPfunction->get_material_subgroup($material_code);
										
										// foreach($checkedby_data as $retrive_data)
										// {
										// 	$selected = ($user_action == "edit" && $qty_checked_by == $retrive_data['cat_id'])?"selected":"";
											
										// 	echo '<option value ="'.$retrive_data['cat_id'].'"'.$selected.'>'.$retrive_data['category_title'].'</option>';
										// }
									// }
										
									?>
								</select>
							</div>
							
							<div class="col-md-1">
							<?php //if($role == "erphead"){ ?>
								<button type="button" id="add_qty_checkedby" data-type="sub-category" data-toggle="modal" 
								data-target="#load_modal_checkedby" class="btn btn-default add_more_subcategory" style="">Add More </button>
								<?php //} ?>				
							</div> -->
							
							<!-- <div class="col-md-2">Rate Checked By</div>
							<div class="col-md-3">
								<select class="select2" required="true" style="width: 100%;" id="rate-checkedby-category" name="rate_checkedby">
									<option value=""><?php echo __('--Select User--'); ?></option>
									<?php 
									// if($user_action == "edit"){
										// $subgrouplist = $this->ERPfunction->get_material_subgroup($material_code);
										// foreach($ratecheckedby_data as $retrive_data)
										// {
										// 	$selected = ($user_action == "edit" && $rate_checked_by == $retrive_data['cat_id'])?"selected":"";
											
										// 	echo '<option value ="'.$retrive_data['cat_id'].'"'.$selected.'>'.$retrive_data['category_title'].'</option>';
										// }
									// }
										
									?>
								</select>
							</div>
							<div class="col-md-1">
								<?php //if($role == "erphead"){ ?>
									<button type="button" id="add_rate_checkedby" data-type="sub-category" data-toggle="modal" 
									data-target="#load_modal_checkedby" class="btn btn-default add_more_subcategory" style="">Add More </button>
								<?php //} ?>
							</div>
							<?php
				 		// } 
					?> -->
				</div>	

				<div class="form-row">
					<div class="col-md-2">Remarks</div>
					<div class="col-md-4">
						<textarea name="remarks" placeholder="GRN Nos, MRN Nos OR Asset Maintenance Nos" id="remarks" cols="10" rows="5" class="form-control"><?php echo $remarks;?></textarea>
					</div>
				</div>
					
				<div class="form-row">
					<div class="col-md-2"></div>
					<div class="col-md-4">
						<button type="submit" class="btn btn-primary" onclick="return ValidateExtension()"><?php echo $button_text;?></button>
					</div>
				</div>
				<?php
					if($user_action == "edit") {
				?>
				<div class="row" style="font-style:italic;color:gray;">							
					<div class="col-md-12 pull-right">
						<br><br><br>
						<div class="col-md-3">
							<?php echo "<b>Created By:</b> ".$this->ERPfunction->get_full_user_name($created_by); ?>
						</div>
						<div class="col-md-3">
							<?php echo "<b>Last Edited By:</b> ".$this->ERPfunction->get_full_user_name($last_edited_by); ?>
						</div>
						<div class="col-md-3">
							<?php echo "<b>Checked By:</b> ".$checked_by; ?>
						</div>
						<div class="col-md-3">
							<?php echo "<b>Accepted by:</b> ".$approved_by; ?>
						</div>
					</div>
				</div>
				<?php } ?>
			</div>
			<?php $this->Form->end(); ?>
		</div>
	</div>
	<?php } ?>
</div>
		 
<script>

var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
	$(".create_field").click(function(){
		var label = $(".add_label").val();
		if(label == "") {
			alert("Please enter file name.");
			return false;
		}
		$(".add_label").val("");
		var field = "<div class='del_parent'><div class='form-row'><div class='col-md-2'>"+ label +"<input type='hidden' name='attach_label[]' value='"+label+"' class='form-control'></div><div class='col-md-4'><input type='file' name='attach_file[]' class='imageUpload'><span class='required red notice'></span></div><div class='col-md-2'><span class='del_file btn btn-danger'>x Remove</span></div></div></div>";
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

	$("body").on("click",".change_party",function(){
		var val = $(this).val()
		if(val == "new") {
			$("#new_party").show();
			$("#old_party").hide();
			$("#inward_party").hide();
			$("#select_party").removeAttr("required");
			$("#select_inward_party").removeAttr("required");
		}else if(val == "old"){
			$("#select_party").attr("required",true);
			$("#select_inward_party").removeAttr("required");
			$("#old_party").show();
			$("#new_party").hide();
			$("#inward_party").hide();
		}else{
			$("#select_party").removeAttr("required");
			$("#select_inward_party").attr("required",true);
			$("#inward_party").show();
			$("#new_party").hide();
			$("#old_party").hide();
		}
	});
	<?php 
	if(!isset($update_inward))
	{ ?>
		$(document).ready(function(){			
			$("#old_party").show();
			$("#select_party").attr("required",true);
		});
	<?php } ?>
</script>