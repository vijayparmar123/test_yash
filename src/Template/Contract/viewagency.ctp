<?php
use Cake\Routing\Router;
?>
<script type="text/javascript">
var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;

jQuery(document).ready(function() {
	jQuery('#user_form').validationEngine();
	jQuery('#date_of_birth,#as_on_date').datepicker({
		dateFormat: "dd-mm-yy",
		  changeMonth: true,
	        changeYear: true,
	        yearRange:'-65:+0',
	        onChangeMonthYear: function(year, month, inst) {
	            jQuery(this).val(month + "-" + year);
	        }
	}); 
	jQuery("body").on("change", "#vendor_group", function(event){	 
	  var vendor_group  = jQuery(this).val();
		/* alert(product_id);
		return false; */
	   var curr_data = {	 						 					
	 					vendor_group : vendor_group,	 					
	 					};	 				
	 	 jQuery.ajax({
                headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'generatevendorid'));?>",
                data:curr_data,
                async:false,
                success: function(response){					
					var json_obj = jQuery.parseJSON(response);					
					jQuery('#vendor_id').val(json_obj['vendor_id']);						
											
					return false;
                },
                error: function (e) {
                     alert('Error');
                }
            });	
	});
} );
</script>	
<?php 

// $user_id=isset($user_data['user_id'])?$user_data['user_id']:'';
$agency_id=isset($user_data['agency_id'])?$user_data['agency_id']:'';
$agency_name=isset($user_data['agency_name'])?$user_data['agency_name']:'';
$agency_billing_address=isset($user_data['agency_billing_address'])?$user_data['agency_billing_address']:'';
$contact_no=isset($user_data['contact_no'])?$user_data['contact_no']:'';
$email_id=isset($user_data['email_id'])?$user_data['email_id']:'';
$pancard_no=isset($user_data['pancard_no'])?$user_data['pancard_no']:'';
$vat_tin_no=isset($user_data['vat_tin_no'])?$user_data['vat_tin_no']:'';
$service_tax_no=isset($user_data['service_tax_no'])?$user_data['service_tax_no']:'';
$cst_no=isset($user_data['cst_no'])?$user_data['cst_no']:'';
$gst_no=isset($user_data['gst_no'])?$user_data['gst_no']:'';
$ac_no=isset($user_data['ac_no'])?$user_data['ac_no']:'';
$bank_name=isset($user_data['bank_name'])?$user_data['bank_name']:'';
$branch_name=isset($user_data['branch_name'])?$user_data['branch_name']:'';
$ifsc_code=isset($user_data['ifsc_code'])?$user_data['ifsc_code']:'';
$transfer_type=isset($user_data['transfer_type'])?$user_data['transfer_type']:'';
$image_url=isset($user_data['image_url'])?$user_data['image_url']:'';
$attach_account_detail=isset($user_data['attach_account_detail'])?$user_data['attach_account_detail']:'';
$created_by = isset($user_data['created_by'])?$this->ERPfunction->get_user_name($user_data['created_by']):'NA';
$last_edit = isset($user_data['last_edit'])?date("m-d-Y H:i:s",strtotime($user_data['last_edit'])):'NA';
$last_edit_by = isset($user_data['last_edit_by'])?$this->ERPfunction->get_user_name($user_data['last_edit_by']):'NA';


?>

<div class="col-md-10" >
				
                <div class="block block-fill-white">
					<div class="head bg-default bg-light-rtl">
						<h2><?php echo $form_header;?> </h2>
						<div class="pull-right">
						<a href="" onclick = "javascript:window.close();" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
						</div>
					</div>
					
                    <div class="header">
                        <h2><u>Agency Information</u></h2>
                    </div>
					<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
				
					<input type="hidden" name="user_action" class="form-control" value="" placeholder="0"  disabled />	
					
                    <div class="content controls">
						<div class="form-row">                       
                            <div class="col-md-2">Agency ID</div>
                            <div class="col-md-4"><input type="text" id="vendor_id" name="agency_id" readonly value="<?php echo $agency_id;?>" class="form-control" placeholder="0"  disabled /></div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-2">Agency Name<span class="require-field">*</span> </div>
                            <div class="col-md-5"><input type="text" name="agency_name" value="<?php echo $agency_name;?>" class="form-control validate[required]" placeholder="0"  disabled /></div>
                        </div>
						<div class="form-row">
                            <div class="col-md-2">Agency's Billing Address </div>
                            <div class="col-md-4">							
                                <textarea name="agency_billing_address" placeholder="0" disabled ><?php echo $agency_billing_address;?></textarea>                                                         
                            </div>
						<!--	
							<div class="col-md-2">
								Attach Account Detail :
							</div>
							<?php 
							if($edit)
							{ ?>
								<div class="col-md-3">								
									<input type="file" name="new_attach_account_detail">                                                         
								</div>								
								<div class="col-md-1">								
									<a href="<?php echo $this->request->base;?>/img/<?php echo $attach_account_detail;?>" target="_blank" class="btn btn-primary">View</a>
								</div>
						<?php }else{?>
							<div class="col-md-3">								
									<input type="file" name="attach_account_detail">                                                         
								</div>
							
					<?php } ?>
							-->
                        </div>
						
						
						<div class="form-row">						
                            <div class="col-md-2">Contact No</div>
                            <div class="col-md-5"><input type="text" name="contact_no" value="<?php echo $contact_no;?>" class="form-control" placeholder="0"  disabled /></div>
						</div>						
						<div class="form-row">
                            <div class="col-md-2">E-mail ID<span class="require-field">*</span> </div>
                            <div class="col-md-5"><input type="text" name="email_id" value="<?php echo $email_id;?>" class="form-control validate[required,custom[email]]" placeholder="0"  disabled /></div>
                         </div>						
						
						<div class="form-row">
                             <div class="col-md-2">PAN Card No</div>
                            <div class="col-md-4"><input type="text" name="pancard_no" value="<?php echo $pancard_no;?>" class="form-control" placeholder="0"  disabled /></div>
                        
                            <!--<div class="col-md-2">VAT/TIN No</div>
                            <div class="col-md-4">
								 <input type="text" name="vat_tin_no" value="<?php //echo $vat_tin_no;?>" class="form-control" placeholder="0"  disabled />
							</div>-->
                         </div>
						 <!--<div class="form-row">
                             <div class="col-md-2">Service Tax No</div>
                            <div class="col-md-4"><input type="text" name="service_tax_no" value="<?php //echo $service_tax_no;?>" class="form-control" placeholder="0"  disabled /></div>
                        
                            <div class="col-md-2">CST No</div>
                            <div class="col-md-4">
								 <input type="text" name="cst_no" value="<?php //echo $cst_no;?>" class="form-control" placeholder="0"  disabled />
							</div>
                         </div>-->
						 <div class="form-row">
                             <div class="col-md-2">GST No</div>
                            <div class="col-md-4"><input type="text" name="gst_no" value="<?php echo $gst_no;?>" class="form-control" placeholder="0"  disabled /></div>
                        
                            <div class="col-md-2">A/C No</div>
                            <div class="col-md-4">
								<input type="text" name="ac_no" value="<?php echo $ac_no;?>" class="form-control" placeholder="0"  disabled />
							</div>
                         </div>
						 <div class="form-row">
                             <div class="col-md-2">Bank</div>
                            <div class="col-md-4"><input type="text" name="bank_name" value="<?php echo $bank_name;?>" class="form-control" placeholder="0"  disabled /></div>
                        
                            <div class="col-md-2">IFSC Code</div>
                            <div class="col-md-4">
								<input type="text" name="ifsc_code" value="<?php echo $ifsc_code;?>" class="form-control" placeholder="0"  disabled />
							</div>
                         </div>
						 <div class="form-row">
                             <div class="col-md-2">Branch Name</div>
                            <div class="col-md-4"><input type="text" name="service_tax_no" value="<?php echo $branch_name;?>" class="form-control" placeholder=""  disabled /></div>
							<div class="col-md-2">Transfer Type</div>
							<div class="col-md-4">
                            <select class="select2" disabled="disabled"   style="width: 100%;" name="transfer_type">
								<option value="NEFT" <?php if($transfer_type == 'NEFT') echo "selected"; ?>>NEFT</Option>
								<option value="Transfer" <?php if($transfer_type == 'Transfer') echo "selected"; ?>>Transfer</Option>
								<option value="Single-Cheque" <?php if($transfer_type != ''){
								if($transfer_type == 'Single-Cheque'){ echo "selected";}}else{ echo "selected";} ?>>Single-Cheque</Option>
							</select>
							</div>
						</div>
						<div class="form-row add_field">
						<?php 
						if($edit)
						{
						$attached_files = json_decode($user_data["attach_file"]);
						$attached_label = json_decode(stripcslashes($user_data['attach_label']));						
						if(!empty($attached_files))
						{							
							$i = 0;
							foreach($attached_files as $file)
							{ ?>
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
											
				
				<?php $this->Form->end(); ?>
				<div class="row" style="font-style:italic;color:gray;">							
					<div class="col-md-6 pull-right">
						<div class="col-md-4">
							<?php echo "Created By:{$created_by}"; ?>
						</div>						
						<div class="col-md-4">
						  <?php echo "Last Edited By:{$last_edit_by}"; ?>
						</div>
						<div class="col-md-4">
							<a href="../printagency/<?php echo $user_data["id"]; ?>" target="_blank" class="btn btn-default">Print</a>
						</div>						
					</div>
				</div>
			 </div>
			</div>
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