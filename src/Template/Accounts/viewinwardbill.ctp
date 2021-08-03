<?php
$project_code=isset($update_inward)?$update_inward['project_code']:'';
$inward_bill_no=isset($update_inward)?$update_inward['inward_bill_no']:'';
$date=isset($update_inward)?date('Y-m-d',strtotime($update_inward['date'])):date('Y-m-d');
$time=isset($update_inward)?$update_inward['time']: date_default_timezone_set('Asia/Kolkata'); date("H:i:g");
$po_no=isset($update_inward)?$update_inward['po_no']:'';
$bill_type=isset($update_inward)?$update_inward['bill_type']:'';
$party_name=isset($update_inward)?$update_inward['party_name']:'';
$party_id=isset($update_inward)?$update_inward['party_id']:'';
$payment_method=isset($update_inward)?$update_inward['payment_method']:'';
$attachment_bill=isset($update_inward)?$update_inward['attachment_bill']:'';
$invoice_no=isset($update_inward)?$update_inward['invoice_no']:'';
$attachment_pass=isset($update_inward)?$update_inward['attachment_pass']:'';
$bill_date=isset($update_inward)?date('Y-m-d',strtotime($update_inward['bill_date'])):'';
$attachment_mmt_sheet=isset($update_inward)?$update_inward['attachment_mmt_sheet']:'';
$total_amt=isset($update_inward)?$update_inward['total_amt']:'';


?>



<?php
use Cake\Routing\Router;
?>
<script type="text/javascript">
var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
jQuery(document).ready(function() {
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
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'getinwardbill'));?>",
                data:curr_data,
                async:false,
                success: function(response){	
                			
					var json_obj = jQuery.parseJSON(response);	
					
					jQuery('#project_code').val(json_obj['project_code']);						
					//jQuery('#prno').val(json_obj['prno']);	
					$('#reference_no').attr('value',json_obj.reference_no);
					$('#po_id').attr('value',json_obj.po_no);


					//return false;
                },
                error: function (e) {
                     alert('Error');
                }
            });	
	});
	//jQuery('#user_form').validationEngine();
	jQuery('#date_of_birth,#as_on_date').datepicker({
		dateFormat: "dd-mm-yy",
		  changeMonth: true,
	        changeYear: true,
	        yearRange:'-65:+0',
	        onChangeMonthYear: function(year, month, inst) {
	            jQuery(this).val(month + "-" + year);
	        }
                    
                }); 
} );
</script>	


<div class="col-md-10" >
				
                <div class="block block-fill-white">					
					<div class="head bg-default bg-light-rtl">
						<h2><?php echo $form_header;?>  </h2>
						<div class="pull-right">
						<a href="<?php echo $this->ERPfunction->action_link('Accounts','index');?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
						</div>
					</div>
					
                    <div class="header">
                        <h2><u>Personal Information</u></h2>
                    </div>
					<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
					

                    <div class="content controls">
						<div class="form-row">
                            <div class="col-md-2">Project Code:<span class="require-field">*</span> :</div>
                            <div class="col-md-4"><input type="text" name="project_code" id="project_code" value="<?php echo $project_code;?>"
							class="form-control validate[required]" value="" readonly="true"/></div>
							<div class="col-md-2">Project Name:</div>
                            <div class="col-md-4">
								<select class="select2" required="true"  style="width: 100%;" name="project_id" id="project_id">
								<option value="">--Select Project--</Option>
								<?php 
									foreach($projects as $retrive_data)
									{
										?>
<option value="<?php echo $retrive_data['project_id'];?>"<?php 
								if(isset($update_inward)){
									if($update_inward['project_id'] == $retrive_data['project_id']){
										echo 'selected="selected"';
									}
								}
		

						?> ><?php echo $retrive_data['project_code'].' '.$retrive_data['project_name']; ?> </option>
										<?php
										
									}
								?>
								</select>
							</div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-2">Bill Inward No<span class="require-field">*</span> :</div>
                            <div class="col-md-4"><input type="text" name="inward_bill_no" value="<?php echo $inward_bill_no; ?>" id="reference_no" class="form-control validate[required]" /></div>
                        
                            <div class="col-md-2">Date</div>
                            
                            <div class="col-md-2"><input type="text" name="date" value="<?php echo $date; ?>" id = "" class="form-control"/></div>

                            

                           

                            <div class="col-md-2"><input type="text" name="time" value="<?php echo $time; ?>" id = "" class="form-control"/></div>
                        </div>						
						
						 <div class="form-row">						
                            <div class="col-md-2">P.O./W.O. No :</div>
                            <div class="col-md-4"><input type="text" name="po_no" value="<?php echo $po_no; ?>" id = "po_id" class="form-control" /></div>
							<div class="col-md-2">Type Of Bill</div>
                            <div class="col-md-4">
								<select name="bill_type" class="select2" required="true"  style="width:100%;">

									<?php 
										$billtype=array(
															'Material/Item'=>'Material/Item',
															'Labour'=>'Labour',
															'Labour with Material/Item'=>'Labour with Material/Item',
															'Asset'=>'Asset',
															
														);

									
									foreach($billtype as $bill_key => $bill_value){
										?>
									<option value="<?php echo $bill_key ;?>" <?php
														if(isset($update_inward)){
															if($update_inward['bill_type']== $bill_key){
																echo 'selected="selected"';
															}
														}

											 ?> ><?php echo $bill_value; ?></option>
									<?php
								}
								?>
								</select>
							</div>
							
                        </div>
						 <div class="form-row">
							
                            <div class="col-md-2">Party's Name:</div>
                            <div class="col-md-4">
                            	<select name="party_name" class="select2" required="true"  style="width:100%;" id="select_party">
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
										if(!empty($agency_list)){
											foreach($agency_list as $agency){ ?>
												<option value="<?php echo $agency['agency_id']; ?>" dataid="<?php echo $agency['agency_id'];?>" <?php 
																if(isset($update_inward)){
																	if($update_inward['party_name'] == $agency['agency_id']){
																		echo 'selected="selected"';
																	}
																}

													?> ><?php echo $agency['agency_name'];?></option>
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
							<div class="col-md-2">Party's ID</div>
                            <div class="col-md-4">
                            	<input type="text" name="party_id" value="<?php echo $party_id; ?>" id="party_id" class="form-control"/>
                            	
                            </div>							
                        </div>	
						<div class="form-row">
                            <div class="col-md-2">Payment Method</div>
                            <div class="col-md-4">
                            	<select name="payment_method" class="select2" required="true"  style="width:100%;">
                            	<?php
                            		$payment=array(
                            						'cash'=>'Cash',
                            						'cheque'=>'Cheque'
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

						<div class="col-md-2">Attach Bill</div>
                            <div class="col-md-4">
                            	<input type="hidden" value="<?php echo $attachment_bill; ?>" name="old_bill">
                            	<input type="file" name="attachment_bill" value="" class="form-control"/>
                            </div>   

                        </div>					
												
						<div class="form-row">
                            <div class="col-md-2">Invoice No</div>
                            <div class="col-md-4"><input type="text" name="invoice_no" value="<?php echo $invoice_no;?>" class="form-control"/></div>
                        
                            <div class="col-md-2">Attach GatePass</div>
                            <div class="col-md-4">
                            	<input type="hidden" value="<?php echo $attachment_pass; ?>" name="old_pass">
                            	<input type="file" name="attachment_pass" id="" value="" class="form-control"/>
                            </div>
                        </div>



                        <div class="form-row">
                            <div class="col-md-2">Bill Date</div>
                            <div class="col-md-4"><input type="text" name="bill_date" value="<?php echo $bill_date; ?>" id="as_on_date" class="form-control"/></div>
                        
                            <div class="col-md-2">Attach Measurement Sheet</div>
                            <div class="col-md-4">
                            	<input type="hidden" value="<?php echo $attachment_mmt_sheet;?>" name="old_mmt_sheet">
                            	<input type="file" name="attachment_mmt_sheet" id="" value="" class="form-control"/>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-2">Total Amount</div>
                            <div class="col-md-4"><input type="text" name="total_amt" value="<?php echo $total_amt; ?>" id="" class="form-control"/></div>
                        
                            
                        </div>



						<div class="form-row">
                            <div class="col-md-2"></div>
                            <div class="col-md-4"><button type="submit" class="btn btn-primary"><?php echo $button_text;?></button></div>
                        </div>
				</div>
				<?php $this->Form->end(); ?>
			</div>
         </div>