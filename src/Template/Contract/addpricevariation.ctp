<?php

$project_code=(isset($update_variation))?$update_variation['project_code']:'';
$bill_no=(isset($update_variation))?$update_variation['bill_no']:'';
$upto_date=(isset($update_variation))?date('d-m-Y',strtotime($update_variation['upto_date'])):'';
$bill_amt=(isset($update_variation))?$update_variation['bill_amt']:'';
$attachment_excel=(isset($update_variation))?$update_variation['attachment_excel']:'';
$attachment_doc=(isset($update_variation))?$update_variation['attachment_doc']:'';
$total_deduction_amt=(isset($update_variation))?$update_variation['total_deduction_amt']:'';
$paid_amt=(isset($update_variation))?$update_variation['paid_amt']:'';
$payment_date=(isset($update_variation))?date('d-m-Y',strtotime($update_variation['payment_date'])):'';
$comment=(isset($update_variation))?$update_variation['comment']:'';
$created_by = isset($update_variation['created_by'])?$this->ERPfunction->get_user_name($update_variation['created_by']):'NA';
$last_edit = isset($update_variation['last_edit'])?date("d-m-Y H:i:s",strtotime($update_variation['last_edit'])):'NA';
$last_edit_by = isset($update_variation['last_edit_by'])?$this->ERPfunction->get_user_name($update_variation['last_edit_by']):'NA';

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
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'getpricevariationno'));?>",
                data:curr_data,
                async:false,
                success: function(response){					
					var json_obj = jQuery.parseJSON(response);	
					
					jQuery('#project_code').val(json_obj['project_code']);						
					//jQuery('#prno').val(json_obj['prno']);	
					$('#reference_no').attr('value',json_obj.reference_no);


					//return false;
                },
                error: function (e) {
                     alert('Error');
                }
            });	
	});
	
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
} );

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
</script>	


<div class="col-md-10" >
	<?php 
if(!$is_capable)
	{
		$this->ERPfunction->access_deniedmsg();
	}
else
{ ?>				
                <div class="block block-fill-white">					
					<div class="head bg-default bg-light-rtl">
						<h2><?php echo $form_header;?> </h2>
						<div class="pull-right">
						<?php
						if(isset($update_variation)){
						?>
						<a href="<?php //echo $this->ERPfunction->action_link('Contract',$back);?>" onclick = "javascript:window.close();" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
						<?php
						}
						else
						{
						?>
						<a href="<?php echo $this->ERPfunction->action_link('Contract',$back);?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
						<?php } ?>
						</div>
					</div>
					
                    <div class="header">
                        <h2><u>Price Variation Information</u></h2>
                    </div>
					<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
					

                    <div class="content controls">
						<div class="form-row">
                            <div class="col-md-2">Project Code:<span class="require-field">*</span> :</div>
                            <div class="col-md-4"><input type="text" name="project_code" id="project_code" value="<?php echo $project_code;?>"
							class="form-control validate[required]" value="" readonly="true" placeholder="0" /></div>
							<div class="col-md-2">Project Name:</div>
                            <div class="col-md-4">
								<select class="select2" required="true"  style="width: 100%;" name="project_id" id="project_id">
								<option value="">--Select Project--</Option>
								<?php 
									foreach($projects as $retrive_data)
									{
										?>
<option value="<?php echo $retrive_data['project_id'];?>" <?php 
													if(isset($update_variation)){
														if($update_variation['project_id'] == $retrive_data['project_id']){
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
                            <div class="col-md-2">Price Variation Bill No:<span class="require-field">*</span> :</div>
                            <div class="col-md-4"><input type="text" name="bill_no" value="<?php echo $bill_no; ?>" id="reference_no" class="form-control validate[required]"  placeholder="0" /></div>
                        
                            <div class="col-md-2">Upto Date *</div>
                            <div class="col-md-4"><input type="text" name="upto_date" value="<?php echo $upto_date; ?>" id="date_of_birth" class="form-control validate[required]" placeholder="0" /></div>
                        </div>						
						<!--
						 <div class="form-row">						
								<div class="col-md-2">Amount of this Bill :</div>
								<div class="col-md-4"><input type="text" name="bill_amt" value="<?php  echo $bill_amt;?>" id = "" class="form-control"  placeholder="0" /></div>
								 <input type="hidden" value="<?php echo $attachment_excel; ?>" name="old_ex">
								<div class="col-md-2">Attach Bill(Excel File):</div>
								<div class="col-md-4">
									<input type="file" name="attachment_excel" value="" class="form-control" placeholder="0" />
								</div>							
							</div>
						 <div class="form-row">							
                            <div class="col-md-2">Total Deductions Amount:</div>
                            <div class="col-md-4"><input type="text" name="total_deduction_amt" value="<?php echo $total_deduction_amt; ?>" class="form-control" placeholder="0" /></div>
                            <input type="hidden" value="<?php echo $attachment_doc; ?>" name="old_doc">
							<div class="col-md-2">Attach Bill(Document File):</div>
                            <div class="col-md-4"><input type="file" name="attachment_doc" id="" value="" class="form-control" placeholder="0" /></div>							
                        </div>	
						-->	 
						<div class="form-row">
                            <div class="col-md-2">Amount to be paid *</div>
                            <div class="col-md-4"><input type="text" name="paid_amt" 
							value="<?php echo $paid_amt; ?>" class="form-control validate[required]" placeholder="0" /></div>                        
                       
                            <div class="col-md-2">Date of Payment *</div>
                            <div class="col-md-4"><input type="text" name="payment_date" value="<?php echo $payment_date; ?>" id="as_on_date" class="form-control validate[required]" placeholder="0" /></div>
                                                
                        </div>

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
						if($user_action == "edit")
						{
						$attached_files = json_decode($update_variation["attach_file"]);
						$attached_label = json_decode(stripcslashes($update_variation['attach_label']));						
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
                            <div class="col-md-2">Comment Box</div>
                            <div class="col-md-10">
							<textarea name="comment" class="form-control"><?php echo $comment; ?></textarea>
							</div>
                        
                        </div>
						
						<div class="form-row">
                            <div class="col-md-2"></div>
                            <div class="col-md-4"><button type="submit" class="btn btn-primary" onclick="return ValidateExtension()"><?php echo $button_text;?></button></div>
                        </div>
				
				<?php $this->Form->end(); ?>
				<div class="row" style="font-style:italic;color:gray;">							
					<div class="col-md-6 pull-right">
						<div class="col-md-4">
							<?php echo "Created By:{$created_by}"; ?>
						</div>
						<div class="col-md-4">
							<?php echo "Last Edited On:{$last_edit}"; ?>
						</div>
						<div class="col-md-4">
						  <?php echo "Last Edited By:{$last_edit_by}"; ?>
						</div> 
					</div>
				</div>
			  </div>
			</div>
<?php } ?>
         </div>
<script>
$(".create_field").click(function(){
	var label = $(".add_label").val();
	$(".add_label").val("");
	var field = "<div class='del_parent'><div class='form-row'><div class='col-md-2'>"+ label +"<input type='hidden' name='attach_label[]' value='"+label+"' class='form-control'></div><div class='col-md-4'><input type='file' name='attach_file[]' class='imageUpload'><span class='required red notice'></span></div><div class='col-md-2'><span class='del_file btn btn-danger'>x Remove</span></div></div></div>";
	$(".add_field").append(field);
});

$("body").on("click",".del_file",function(){
	$(this).parentsUntil('.del_parent').remove();
});
</script>