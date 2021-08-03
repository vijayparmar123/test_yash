<?php

$project_code=(isset($update_rabill))?$update_rabill['project_code']:'';
$ra_bill_no=(isset($update_rabill))?$update_rabill['ra_bill_no']:'';
$qty_taken_uptodate=(isset($update_rabill))?date('d-m-Y',strtotime($update_rabill['qty_taken_uptodate'])):'';
$contract_excess_amt=(isset($update_rabill))?$update_rabill['contract_excess_amt']:0;
$extra_item_amt=(isset($update_rabill))?$update_rabill['extra_item_amt']:0;
$mobilization_adv=(isset($update_rabill))?$update_rabill['mobilization_adv']:0;
$unmeasured_adv=(isset($update_rabill))?$update_rabill['unmeasured_adv']:0;
$release_deposite=(isset($update_rabill))?$update_rabill['release_deposite']:0;
$other_bill_amt=(isset($update_rabill))?$update_rabill['other_bill_amt']:0;
$total_bill_amt=(isset($update_rabill))?$update_rabill['total_bill_amt']:0;
$security_deposite=(isset($update_rabill))?$update_rabill['security_deposite']:0;
$other_deposits=(isset($update_rabill) && $update_rabill['other_deposits'] != NULL)?$update_rabill['other_deposits']:0;
$other_deduction=(isset($update_rabill) && $update_rabill['other_deduction'] != NULL)?$update_rabill['other_deduction']:0;
$tds=(isset($update_rabill))?$update_rabill['tds']:0;
$labour_cess=(isset($update_rabill))?$update_rabill['labour_cess']:0;
$vat=(isset($update_rabill))?$update_rabill['vat']:0;
$other_taxes=(isset($update_rabill))?$update_rabill['other_taxes']:0;
$with_held=(isset($update_rabill))?$update_rabill['with_held']:0;
$total_deduction_amt=(isset($update_rabill))?$update_rabill['total_deduction_amt']:0;
$total_paid_amt=(isset($update_rabill))?$update_rabill['total_paid_amt']:0;
$date_of_payment=(isset($update_rabill))?date('d-m-y',strtotime($update_rabill['date_of_payment'])):0;
$attachment_doc=(isset($update_rabill))?$update_rabill['attachment_doc']:"";
$attachment_excel=(isset($update_rabill))?$update_rabill['attachment_excel']:'';
$comment=(isset($update_rabill))?$update_rabill['comment']:'';
$created_by = isset($update_rabill['created_by'])?$this->ERPfunction->get_user_name($update_rabill['created_by']):'NA';
$last_edit = isset($update_rabill['last_edit'])?date("d-m-Y H:i:s",strtotime($update_rabill['last_edit'])):'NA';
$last_edit_by = isset($update_rabill['last_edit_by'])?$this->ERPfunction->get_user_name($update_rabill['last_edit_by']):'NA';

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
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'getrabillproject'));?>",
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
	jQuery('#date_of_birth,#as_on_date,#date_payment').datepicker({
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
						if(isset($update_rabill)){
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
                        <h2><u>Project Information</u></h2>
                    </div>
					<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
					

                    <div class="content controls">
						<div class="form-row">
                            <div class="col-md-2">Project Code:<span class="require-field">*</span> :</div>
                            <div class="col-md-4"><input type="text" name="project_code" id="project_code" value="<?php echo $project_code; ?>"
							class="form-control validate[required]" value="" readonly="true" /></div>
							<div class="col-md-2">Project Name:</div>
                            <div class="col-md-4">
								<select class="select2" required="true"  style="width: 100%;" name="project_id" id="project_id">
								<option value="">--Select Project--</Option>
								<?php 
									foreach($projects as $retrive_data)
									{
										?>
<option value="<?php echo $retrive_data['project_id'];?>" <?php 
						if(isset($update_rabill)){
							if($update_rabill['project_id'] == $retrive_data['project_id']){
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
                            <div class="col-md-2">R.A Bill No<span class="require-field">*</span> :</div>
                            <div class="col-md-4"><input type="text" name="ra_bill_no" value="<?php echo $ra_bill_no; ?>" id="reference_no" class="form-control validate[required]" /></div>
                        
                            <div class="col-md-2">Qty.Taken upto Date *</div>
                            <div class="col-md-4"><input type="text" name="qty_taken_uptodate" value="<?php echo $qty_taken_uptodate; ?>" id = "date_of_birth" class="form-control validate[required]" /></div>
                        </div>						
						

 						<div class="header">
                        <h2><u>Amount Of This Bill</u></h2>
                    </div>

						 <div class="form-row">						
                            <div class="col-md-2">Contract + Excess Amount:</div>
                            <div class="col-md-4"><input type="text" name="contract_excess_amt" value="<?php echo $contract_excess_amt; ?>" id = "" class="amt1 ra_total form-control"/></div>
							<div class="col-md-2">Extra Item Amount</div>
                            <div class="col-md-4">
								<input type="text" name="extra_item_amt" value="<?php echo $extra_item_amt; ?>" id = "" class="amt2 ra_total form-control" />
							</div>
							
                        </div>
						 <div class="form-row">
							
                            <div class="col-md-2">Mobilization Advance</div>
                            <div class="col-md-4"><input type="text" name="mobilization_adv" value="<?php echo $mobilization_adv; ?>" class="amt3 ra_total form-control"/></div>
							<div class="col-md-2">Unmeasured Advance</div>
                            <div class="col-md-4"><input type="text" name="unmeasured_adv" id="" value="<?php echo $unmeasured_adv; ?>" class="amt4 ra_total form-control" /></div>							
                        </div>	



                         <div class="form-row">
							
                            <div class="col-md-2">Release of Deposite/W.H.</div>
                            <div class="col-md-4"><input type="text" name="release_deposite" value="<?php echo $release_deposite; ?>" class="amt5 ra_total form-control" /></div>
							<div class="col-md-2">Others</div>
                            <div class="col-md-4"><input type="text" name="other_bill_amt" id="" value="<?php echo $other_bill_amt; ?>" class="amt6 ra_total form-control" /></div>							
                        </div>	

                         <div class="form-row">
							
                            <div class="col-md-2">R.A. Bill Amount</div>
                            <div class="col-md-4"><input type="text" name="total_bill_amt" value="<?php echo $total_bill_amt; ?>" id="ra_amount" class="net_total form-control" /></div>
													
                        </div>	

                        <div class="header">
                        <h2><u>Deductions</u></h2>
                    </div>


					 <div class="form-row">						
                            <div class="col-md-2">Security Deposite(S.D)</div>
                            <div class="col-md-4"><input type="text" name="security_deposite" value="<?php echo $security_deposite; ?>" id = "" class="damt1 d_change form-control"  /></div>
							<div class="col-md-2">T.D.S</div>
                            <div class="col-md-4">
								<input type="text" name="tds" value="<?php echo $tds; ?>" id = "" class="damt2 d_change form-control" />
							</div>
							
                        </div>
						<div class="form-row">						
                            <div class="col-md-2">Other Deposite</div>
                            <div class="col-md-4"><input type="text" name="other_deposits" value="<?php echo $other_deposits; ?>" id = "" class="damt7 d_change form-control"  /></div>
							<div class="col-md-2">Other</div>
                            <div class="col-md-4">
								<input type="text" name="other_deduction" value="<?php echo $other_deduction; ?>" id = "" class="damt8 d_change form-control" />
							</div>
							
                        </div>
						 <div class="form-row">
							
                            <div class="col-md-2">Labour CESS</div>
                            <div class="col-md-4"><input type="text" name="labour_cess" value="<?php echo $labour_cess; ?>" class="damt3 d_change form-control" /></div>
							<div class="col-md-2">VAT</div>
                            <div class="col-md-4"><input type="text" name="vat" id="" value="<?php echo $vat; ?>" class="damt4 d_change form-control" /></div>							
                        </div>	



                         <div class="form-row">
							
                            <div class="col-md-2">Other Taxes</div>
                            <div class="col-md-4"><input type="text" name="other_taxes" value="<?php echo $other_taxes; ?>" class="damt5 d_change form-control" /></div>
							<div class="col-md-2">With Held</div>
                            <div class="col-md-4"><input type="text" name="with_held" id="" value="<?php echo $with_held; ?>" class="damt6 d_change form-control" /></div>							
                        </div>	

                         <div class="form-row">
							
                            <div class="col-md-2">Deducted Amount</div>
                            <div class="col-md-4"><input type="text" name="total_deduction_amt" value="<?php echo $total_deduction_amt; ?>" id="d_total" class=" net_total form-control" /></div>
													
                        </div>	

                         <div class="header">
                        <h2><u>Amount to be Paid</u></h2>
                    </div>

						 <div class="form-row">
							
                            <div class="col-md-2">Net Paid Amount</div>							
                            <div class="col-md-2">
								<div class="input-group">
                                    <input type="text" name="total_paid_amt" value="<?php echo $total_paid_amt; ?>" id="net_amount" class="form-control" />	
                                </div>
							</div>	
							<!--	<div class="col-md-2 input-group-addon" style="width:auto;"><a href="javascript:void(0);" id="count_total">Calculate</a></div>	-->					
							
						<!-- <div class="col-md-2">Attach Bill Excel File</div>
							<input type="hidden" value="<?php //echo $attachment_excel; ?>" name="old_ex">
                            <div class="col-md-4"><input type="file" name="attachment_excel" id="" value="" class="form-control" placeholder="0" /></div> -->							
                        </div>

                        <div class="form-row">
							
                            <div class="col-md-2">Date of Payment</div>
                            <div class="col-md-4"><input type="text" name="date_of_payment" value="<?php echo $date_of_payment; ?>" id="date_payment" class="form-control" /></div>
						<!-- <div class="col-md-2">Attach Bill Document</div>
							<input type="hidden" value="<?php //echo $attachment_doc; ?>" name="old_doc">
                            <div class="col-md-4"><input type="file" name="attachment_doc" id="" value="" class="form-control" placeholder="0" /></div> -->
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
						$attached_files = json_decode($update_rabill["attach_file"]);
						$attached_label = json_decode(stripcslashes($update_rabill['attach_label']));						
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
										<div class='col-md-4'><a href="<?php echo $this->ERpfuncion->get_signed_url($file);?>" class="btn btn-primary" target="_blank">View File</a>
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
$(".ra_total").change(function(){
	var amt1 = $(".amt1").val();
	var amt2 = $(".amt2").val();
	var amt3 = $(".amt3").val();
	var amt4 = $(".amt4").val();
	var amt5 = $(".amt5").val();
	var amt6 = $(".amt6").val();
	
	var total = parseInt(amt1)+parseInt(amt2)+parseInt(amt3)+parseInt(amt4)+parseInt(amt5)+parseInt(amt6);
	$("#ra_amount").val(total);
	
	var ra_total = $("#ra_amount").val();
	var d_total = $("#d_total").val();
	var net_total = parseInt(ra_total)-parseInt(d_total);
	$("#net_amount").val(net_total);
});
 
$(".d_change").change(function(){
	var damt1 = $(".damt1").val();
	var damt2 = $(".damt2").val();
	var damt3 = $(".damt3").val();
	var damt4 = $(".damt4").val();
	var damt5 = $(".damt5").val();
	var damt6 = $(".damt6").val();
	var damt7 = $(".damt7").val();
	var damt8 = $(".damt8").val();
	
	var total = parseInt(damt1)+parseInt(damt2)+parseInt(damt3)+parseInt(damt4)+parseInt(damt5)+parseInt(damt6)+parseInt(damt7)+parseInt(damt8);
	$("#d_total").val(total);
	
	var ra_total = $("#ra_amount").val();
	var d_total = $("#d_total").val();
	var net_total = parseInt(ra_total)-parseInt(d_total);
	$("#net_amount").val(net_total);
});

$("#count_total").click(function(){
	var ra_total_amt = $("#ra_amount").val();
	var d_total_amt = $("#d_total").val();
	var net_total_amt =parseInt(ra_total_amt) - parseInt(d_total_amt);
	$("#net_amount").val(net_total_amt);
});

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