<?php
use Cake\Routing\Router;
?>
<script type="text/javascript">
jQuery(document).ready(function() {
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
	jQuery("body").on("change", "#vendor_group", function(event){	 
	  var vendor_group  = jQuery(this).val();
		/* alert(product_id);
		return false; */
	   var curr_data = {	 						 					
	 					vendor_group : vendor_group,	 					
	 					};	 				
	 	 jQuery.ajax({
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

$user_id=isset($user_data['user_id'])?$user_data['user_id']:'';
// $vendor_group=isset($user_data['vendor_group'])?$user_data['vendor_group']:'';
$vendor_id=isset($user_data['vendor_id'])?$user_data['vendor_id']:'';
$vendor_name=isset($user_data['vendor_name'])?$user_data['vendor_name']:'';
$vendor_billing_address=isset($user_data['vendor_billing_address'])?$user_data['vendor_billing_address']:'';
$contact_no1=isset($user_data['contact_no1'])?$user_data['contact_no1']:'';
$contact_no2=isset($user_data['contact_no2'])?$user_data['contact_no2']:'';
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
$image_url=isset($user_data['image_url'])?$user_data['image_url']:'';


?>

<div class="col-md-10" >
<?php
if(!$is_capable)
{
	$this->ERPfunction->access_deniedmsg();
}
else{
?>               <div class="block block-fill-white">
				
                <div class="block block-fill-white">
					<div class="head bg-default bg-light-rtl">
						<h2>View Vendor</h2>
						<div class="pull-right">
						<a href="<?php //echo $this->ERPfunction->action_link('Purchase',$back);?>" onclick = "javascript:window.close();" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
						</div>
					</div>
                    <div class="header">
                        <h2><u>Vendor Information</u></h2>
                    </div>
					<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
				
					<input type="hidden" name="user_action" class="form-control" value="<?php echo $user_action;?>" disabled />	
					
                    <div class="content controls">
						
						<div class="form-row">
						<!-- 
                            <div class="col-md-2">Vendor Group<span class="require-field">*</span> :</div>
                            <div class="col-md-4">
								
								<select style="width: 100%;" class="select2" required="true"  name="vendor_group" id="vendor_group" disabled>
								<option>--Select Vendor Group--</option>
								<?php 
								// foreach($vendor_groups as $key => $retrive_data)
								// {
									// echo '<option value="'.$retrive_data['id'].'" '.$this->ERPfunction->selected($retrive_data['id'],$vendor_group).'>'.$this->ERPfunction->get_vendor_group_name($retrive_data['id']).'</option>';
								// }
								?>
								</select>
										
							</div>
                         -->
                            <div class="col-md-2">Vendor ID</div>
                            <div class="col-md-4"><input type="text" id="vendor_id" name="vendor_id" value="<?php echo $vendor_id;?>" class="form-control" disabled /></div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-2">Vendor Name<span class="require-field">*</span> </div>
                            <div class="col-md-10"><input type="text" name="vendor_name" value="<?php echo $vendor_name;?>" class="form-control validate[required]" disabled /></div>
                        </div>
						<div class="form-row">
                            <div class="col-md-2">Vendor's Billing Address </div>
                            <div class="col-md-10">							
                                <textarea name="vendor_billing_address" disabled ><?php echo $vendor_billing_address;?></textarea>                                                         
                            </div>
                        </div>
						 <div class="form-row">						
                            <div class="col-md-2">Contact No (1)</div>
                            <div class="col-md-4"><input type="text" name="contact_no1" value="<?php echo $contact_no1;?>" class="form-control" disabled /></div>
							<div class="col-md-2">Contact No (2)</div>
                            <div class="col-md-4"><input type="text" name="contact_no2" value="<?php echo $contact_no2;?>" class="form-control" value="" disabled /></div>
							
                        </div>
						
						<div class="form-row">
                            <div class="col-md-2">Email ID<span class="require-field">*</span> </div>
                            <div class="col-md-10"><input type="text" name="email_id" value="<?php echo $email_id;?>" class="form-control validate[required,custom[email]]" disabled /></div>
                         </div>						
						
						<div class="form-row">
                             <div class="col-md-2">PAN Card No</div>
                            <div class="col-md-4"><input type="text" name="pancard_no" value="<?php echo $pancard_no;?>" class="form-control" disabled /></div>
							
							<div class="col-md-2">GST No</div>
                            <div class="col-md-4"><input type="text" name="gst_no" value="<?php echo $gst_no;?>" class="form-control" disabled /></div>
                            <!--<div class="col-md-2">VAT/TIN No</div>
                            <div class="col-md-4">
								<input type="text" name="vat_tin_no" value="<?php //echo $vat_tin_no;?>" class="form-control" disabled />
							</div>-->
                         </div>
						 <!--<div class="form-row">
                             <div class="col-md-2">Service Tax No</div>
                            <div class="col-md-4"><input type="text" name="service_tax_no" value="<?php //echo $service_tax_no;?>" class="form-control" disabled /></div>
                        
                            <div class="col-md-2">CST No</div>
                            <div class="col-md-4">
								 <input type="text" name="cst_no" value="<?php //echo $cst_no;?>" class="form-control" disabled />
							</div>
                         </div>-->
						 <div class="form-row">
                             
                            <div class="col-md-2">A/C No</div>
                            <div class="col-md-4">
								<input type="text" name="ac_no" value="<?php echo $ac_no;?>" class="form-control" disabled />
							</div>
                             <div class="col-md-2">Bank</div>
                            <div class="col-md-4"><input type="text" name="bank_name" value="<?php echo $bank_name;?>" class="form-control" disabled /></div>
							
                         </div>
						 <div class="form-row">
                        
                            <div class="col-md-2">IFSC Code</div>
                            <div class="col-md-4">
								<input type="text" name="ifsc_code" value="<?php echo $ifsc_code;?>" class="form-control" disabled />
							</div>
							<div class="col-md-2">Branch</div>
                            <div class="col-md-4"><input type="text" name="branch_name" value="<?php echo $branch_name;?>" class="form-control" disabled /></div>
                         </div>
						  
						 <!--
						 <div class="form-row">
                            <div class="col-md-2">Image:</div>
                            <div class="col-md-4">
							<?php 
									// echo $this->Html->image($this->ERPfunction->get_vendor_image($user_id),
				// array('class'=>'userimage','height'=>'50px','width'=>'50px')); ?>
                                                             
                            </div>
                        </div>		-->				
						
						<div class="form-row add_field">
						<?php 
						if($user_action == "edit")
						{
						$attached_files = json_decode($user_data["attach_file"]);
						$attached_label = json_decode(stripcslashes($user_data['attach_label']));						
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
					<div class="col-md-7 pull-right">
						<br><br><br>
						<div class="col-md-4">
							<?php echo "Created By:{$this->ERPfunction->get_user_name($user_data['created_by'])}"; ?>
						</div>
						<div class="col-md-4">
							 <?php echo "Last Edited By:{$this->ERPfunction->get_user_name($user_data['last_edit_by'])}"; ?>
						</div>
						<div class="col-md-4">	
						<?php 
							if($this->ERPfunction->retrive_accessrights($role,'printVendor')==1) {
						?>					 
						  <a href="../printvendor/<?php echo $user_data["user_id"];?>" class="btn btn-default" id="print_this" target="_blank"><i class="icon-print"></i> Print</a>
						<?php 
						}
						?>
						</div> 
					</div>
				</div>
				
				</div>
			</div>
<?php } ?>
         </div>
