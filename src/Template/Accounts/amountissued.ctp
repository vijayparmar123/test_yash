<?php
use Cake\Routing\Router;
?>

<script>
   function yesnoCheck(that) {
       if (that.value == "cash") {
           //alert("check");
           document.getElementById("cashdetail").style.display = "block";
		   document.getElementById("chequedetail").style.display = "none";
       } else {
           document.getElementById("chequedetail").style.display = "block";
		   document.getElementById("cashdetail").style.display = "none";
       }
   }
</script>
<script type="text/javascript">
var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
jQuery(document).ready(function() {
	jQuery('#user_form').validationEngine();
	jQuery('#pr_date,#as_on_date').datepicker({
		dateFormat: "dd-mm-yy",
		  changeMonth: true,
	        changeYear: true,
	        yearRange:'-65:+0',
	        onChangeMonthYear: function(year, month, inst) {
	            jQuery(this).val(month + "-" + year);
	        }                    
    });
	
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
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'projectdetailaccount'));?>",
                data:curr_data,
                async:false,
                success: function(response){					
					var json_obj = jQuery.parseJSON(response);					
					jQuery('#project_code').val(json_obj['project_code']);												
					return false;
                },
                error: function (e) {
                     alert('Error');
                     console.log(e.responseText);
                }
            });	
	});
	
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
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'accountbyproject'));?>",
                data:curr_data,
                async:false,
                success: function(response){					
					// var json_obj = jQuery.parseJSON(response);					
					// jQuery('#project_code').val(json_obj['project_code']);												
					// return false;
					//jquery('#account_id').
					//$("#account_id").append("<option value=''>select</option>");
					$('#account_id').html(response);
					$("#account_id").prepend("<option value='' selected>--Select Account--</option>");
                },
                error: function (e) {
                     alert('Error');
                     console.log(e.responseText);
                }
            });	
	});
	
	jQuery('.delivery_date').datepicker({
		 changeMonth: true,
      changeYear: true,
	  dateFormat: "dd-mm-yy"
	});
	
	jQuery("body").on("change", "#account_id", function(event){ 
	 
	  var account_id  = jQuery(this).val() ;
		/* alert(product_id);
		return false; */
	   var curr_data = {	 						 					
	 					account_id : account_id,	 					
	 					};	 				
	 	 jQuery.ajax({
                headers: {
					'X-CSRF-Token': csrfToken
				},
                type:"POST",
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'accountdetail'));?>",
                data:curr_data,
                async:false,
                success: function(response){					
					var json_obj = jQuery.parseJSON(response);					
					jQuery('#account_no').val(json_obj['account_no']);
					jQuery('#bank').val(json_obj['bank']);
					jQuery('#branch').val(json_obj['branch']);
					jQuery('#ifsc_code').val(json_obj['ifsc_code']);
					return false;
                },
                error: function (e) {
                     alert('Error');
                     console.log(e.responseText);
                }
            });	
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
{
?>			
                <div class="block block-fill-white">
					<div class="head bg-default bg-light-rtl">
						<h2>Amount Issued</h2>
						<div class="pull-right">
						<a href="<?php echo $this->ERPfunction->action_link('Accounts','index');?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
						</div>
					</div>
					
					<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
					<input type="hidden" name="user_action" class="form-control" value="<?php ?>"/>	
					
					 <div class="content controls">
						<div class="form-row">
                            <div class="col-md-2">Project Code<span class="require-field">*</span> </div>
                            <div class="col-md-4"><input type="text" name="project_code" id="project_code" value=""
							class="form-control validate[required]" value="" readonly="true"/></div>
							<div class="col-md-2">Project Name *</div>
                            <div class="col-md-4">
								<select class="select2"  required="true"   style="width: 100%;" name="project_id" id="project_id">
								<option value="">--Select Project--</Option>
								<?php 
									foreach($projects as $retrive_data)
									{
										echo '<option value="'.$retrive_data['project_id'].'">'.
										$retrive_data['project_name'].'</option>';
									}
								?>
								</select>
							</div>
                        </div>
						<div class="form-row">
                            <div class="col-md-2">Voucher No *</div>
                            <div class="col-md-4">
								<input type="text" name="voucher_no" id="voucher_no" class="form-control validate[required]" value=""/>
							</div>
                        
                            <div class="col-md-1 text-right">Date</div>
                            <div class="col-md-2"><input type="text" name="pr_date" id="pr_date" 
							value="<?php echo $this->ERPfunction->get_date(date('Y-m-d'));?>" class="form-control" value=""/></div>
							 <div class="col-md-1 text-right">Time</div>
                            <div class="col-md-2"><input type="text" name="pr_time" id="pr_time" 
							value="<?php echo date('H:i');?>" class="form-control" value=""/></div>
                        </div>
						<div class="form-row">
                            <div class="col-md-2">Account Name<span class="require-field">*</span></div>
                            <div class="col-md-10">
								<select class="select2"  required="true"   style="width: 100%;" name="account_id" id="account_id">
								<option value="">--Select Account--</Option>
								// <?php 
									// foreach($account_list as $retrive_data)
									// {
										// echo '<option value="'.$retrive_data['account_id'].'">'.
										// $retrive_data['account_name'].'</option>';
									// }
								// ?>
								</select>
							</div>
                        </div>
						
						<div class="form-row">
                            <div class="col-md-2">Account No<span class="require-field">*</span> </div>
                            <div class="col-md-4"><input type="text" name="account_no" id="account_no" value=""
							class="form-control validate[required]" value="" readonly="true"/></div>
							<div class="col-md-2">Bank </div>
                            <div class="col-md-4"><input type="text" name="bank" id="bank" value=""
							class="form-control validate[required]" value="" readonly="true"/></div>
                        </div>
						
						<div class="form-row">
                            <div class="col-md-2">Branch<span class="require-field">*</span> </div>
                            <div class="col-md-4"><input type="text" name="branch" id="branch" value=""
							class="form-control validate[required]" value="" readonly="true"/></div>
							<div class="col-md-2">IFSC Code </div>
                            <div class="col-md-4"><input type="text" name="ifsc_code" id="ifsc_code" value=""
							class="form-control validate[required]" value="" readonly="true"/></div>
                        </div>
						
						<div class="form-row">
                            <div class="col-md-2">Amount Issued<span class="require-field">*</span> </div>
                            <div class="col-md-4"><input type="text" name="amount_issued" id="amount_issued" value=""
							class="form-control validate[required]"/></div>
							<div class="col-md-2">Payment *</div>
                            <div class="col-md-4">
								<select class="select2"  required="true"   style="width: 100%;" name="payment_type" id="payment_type" onchange="yesnoCheck(this);">
								<option value="cheque">Cheque</Option>
								<option value="cash">Cash</Option>
								
								</select>
							</div>
                        </div>
						
						<div class="form-row" id="chequedetail">
                            <div class="col-md-2">Bank<span class="require-field">*</span> </div>
                            <div class="col-md-3"><input type="text" name="bnk" id="bnk" value=""
							class="form-control validate[required]" value=""/></div>
							<div class="col-md-2">Cheque No<span class="require-field">*</span> </div>
                            <div class="col-md-2"><input type="text" name="cheque_no" id="cheque_no" value=""
							class="form-control validate[required]" value=""/></div>
							<div class="col-md-1 text-right">Date</div>
                            <div class="col-md-2"><input type="text" name="cheque_date" id="cheque_date" 
							value="<?php echo $this->ERPfunction->get_date(date('Y-m-d'));?>" class="form-control"/></div>
                        </div>
						
						<div class="form-row" style="display:none;" id="cashdetail">
                            <div class="col-md-2">Receiver Name<span class="require-field">*</span> </div>
                            <div class="col-md-4"><input type="text" name="receiver" id="receiver" value=""
							class="form-control validate[required]" value=""/></div>
							
                        </div>
						
						<div class="form-row">
                            <div class="col-md-2">Remarks</div>
                            <div class="col-md-10">
								<textarea name="remark" id="remark" class="form-control"></textarea>
							</div>
                        </div>
						
						<div class="form-row">
                            <div class="col-md-2"></div>
                            <div class="col-md-4"><button type="submit" class="btn btn-primary">Amount Issued</button></div>
                        </div>
					</div>
					
				<?php $this->Form->end(); ?>
			</div>
	<?php } ?>
         </div>
