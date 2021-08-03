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
		});
		jQuery("body").on("change","#gst_no",function(event){
			var gstNo = jQuery(this).val();
			var curr_data = {
				gstNo : gstNo,
			};
			jQuery.ajax({
				headers: {
					'X-CSRF-Token': csrfToken
				},
				type : "POST",
				url : "<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'getvendorgstno')); ?>",
				data : curr_data,
				async : false,
				success : function(response) {
					if(response == gstNo) {
						alert("GST No already Exist...");return false;
					}
				},
				error : function(e) {
					alert(e);
				}
			});
			// alert(gstNo);return false;
		});
</script>	
<?php 
	$user_id=isset($user_data['user_id'])?$user_data['user_id']:'';
	// $vendor_group=isset($user_data['vendor_group'])?$user_data['vendor_group']:'';
	//$vendor_id=isset($user_data['vendor_id'])?$user_data['vendor_id']:$vendor_id;
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
	if(!$is_capable) {
		$this->ERPfunction->access_deniedmsg();
	}
	else {
?>              
<div class="block block-fill-white">
    <div class="block block-fill-white">
		<div class="head bg-default bg-light-rtl">
			<h2><?php echo $form_header;?> </h2>
			<div class="pull-right">
				<?php
					if(isset($user_data)){
				?>
				<a href="<?php //echo $this->ERPfunction->action_link('Accounts','index');?>" onclick = "javascript:window.close();" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
				<?php
					}
					else {
				?>
				<!-- <a href="<?php echo $this->ERPfunction->action_link($back_url,$back_page);?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a> -->
				<a href="<?php echo $this->request->base;?>/Purchase/index" class="btn btn-success">Back</a>
				<?php } ?>
			</div>
		</div>
        <div class="header">
            <h2><u>Vendor Information</u></h2>
        </div>
		<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
		<input type="hidden" name="user_action" class="form-control" value="<?php echo $user_action;?>"/>				
    	<div class="content controls">
			<!-- <div class="form-row">	
				<div class="col-md-2">Vendor Group<span class="require-field">*</span> :</div>
					<div class="col-md-4">	
						<select style="width: 100%;" class="select2" required="true"  name="vendor_group" id="vendor_group">
							<option>--Select Vendor Group--</option>
						</select>				
					</div> 
							
					<div class="col-md-2">Vendor ID</div>
					<div class="col-md-4"><input type="text" id="vendor_id" name="vendor_id" value="<?php //echo $vendor_id;?>" class="form-control"/></div>
				</div> -->
                <div class="form-row">
                    <div class="col-md-2">Vendor Name<span class="require-field">*</span></div>
                    <div class="col-md-10"><input type="text" name="vendor_name" value="<?php echo $vendor_name;?>" class="form-control validate[required,custom[onlyLetterSp]]"/></div>
                </div>
				<div class="form-row">
                    <div class="col-md-2">Vendor's Billing Address</div>
                	<div class="col-md-10">							
                    	<textarea name="vendor_billing_address"><?php echo $vendor_billing_address;?></textarea>                                                         
                    </div>
                </div>
				<div class="form-row">						
					<div class="col-md-2">Contact No (1)</div>
					<div class="col-md-4"><input type="text" name="contact_no1" value="<?php echo $contact_no1;?>" class="form-control"/></div>
					<div class="col-md-2">Contact No (2)</div>
					<div class="col-md-4"><input type="text" name="contact_no2" value="<?php echo $contact_no2;?>" class="form-control" value=""/></div>
				</div>
						
				<div class="form-row">
					<div class="col-md-2">E-mail ID<span class="require-field">*</span> </div>
					<!--<div class="col-md-10"><input type="text" name="email_id" value="<?php echo $email_id;?>" class="form-control validate[required,custom[email]]"/></div>-->
					<div class="col-md-10"><input type="text" name="email_id" value="<?php echo $email_id;?>" class="form-control validate[required]"/></div>
				</div>						
						
				<div class="form-row">
					<div class="col-md-2">PAN Card No</div>
					<div class="col-md-4"><input type="text" name="pancard_no" value="<?php echo $pancard_no;?>" class="form-control validate[required,custom[onlyLetterNumber]]"/></div>
					
					<div class="col-md-2">GST No</div>
					<div class="col-md-4"><input type="text" id="gst_no" name="gst_no" value="<?php echo $gst_no;?>" class="form-control validate[required,custom[onlyLetterNumber]]"/></div>
				</div>
				<div class="form-row">
					<div class="col-md-2">A/C No</div>
					<div class="col-md-4">
						<input type="text" name="ac_no" value="<?php echo $ac_no;?>" class="form-control validate[custom[onlyLetterNumber]]"/>
					</div>
					<div class="col-md-2">Bank</div>
					<div class="col-md-4"><input type="text" name="bank_name" value="<?php echo $bank_name;?>" class="form-control"/></div>
				</div>
				<div class="form-row">
					<div class="col-md-2">IFSC Code</div>
					<div class="col-md-4">
						<input type="text" name="ifsc_code" value="<?php echo $ifsc_code;?>" class="form-control validate[custom[onlyLetterNumber]]"/>
					</div>
					<div class="col-md-2">Branch Name</div>
					<div class="col-md-4"><input type="text" name="branch_name" value="<?php echo $branch_name;?>" class="form-control"/></div>
				</div>
						 		 
				<!-- <div class="form-row">
					<div class="col-md-2">Image:</div>
					<div class="col-md-4">
						<div class="input-group file">                                    
							<input type="text" name="new_image_nmae" class="form-control">
							<input type="hidden" name="old_image" value="<?php echo $image_url;?>" class="form-control">
							
							<input type="file" name="image_url">
							<span class="input-group-btn">
								<button type="button" class="btn btn-primary">Browse</button>
							</span>
						</div>                               
					</div>
				</div>	 -->

				<div class="form-row">	
					<hr>
					<div class="col-md-2"> Attach Documents</div>
					<div class="col-md-4">
						<input class="add_label form-control">
					</div>
					<div class="col-md-2">
						<a href="javascript:void(0)" class="create_field form-control" style="text-align:center;">+Add</a>
					</div>
				</div>
				<div class="form-row add_field">
					<?php 
						if($user_action == "edit") {
							$attached_files = isset($user_data["attach_file"])?json_decode($user_data["attach_file"]):'';
							$attached_label = isset($user_data["attach_file"])?json_decode(stripcslashes($user_data['attach_label'])):'';						
							if(!empty($attached_files)) {							
								$i = 0;
								foreach($attached_files as $file) {
					?>
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
					<hr>
					<div class="col-md-2"></div>
					<div class="col-md-4"><button type="submit" class="btn btn-primary" onclick="return ValidateExtension()"><?php echo $button_text;?></button></div>
				</div>
			</div>
			<?php $this->Form->end(); ?>
		</div>
		<?php } ?>
    </div>
<script>
var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
	$(".create_field").click(function() {
		var label = $(".add_label").val();
		if(label == '') {
			alert('Please enter document name');
			return false;
		}else {
		$(".add_label").val("");
		var field = "<div class='del_parent'><div class='form-row'><div class='col-md-2'>"+ label +"<input type='hidden' name='attach_label[]' value='"+label+"' class='form-control'></div><div class='col-md-4'><input type='file' name='attach_file[]' class='imageUpload'><span class='required red notice'></span></div><div class='col-md-2'><span class='del_file btn btn-danger'>x Remove</span></div></div></div>";
		$(".add_field").append(field);
		}
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
	$("body").on("click",".del_file",function() {
		$(this).parentsUntil('.del_parent').remove();
	});
</script>