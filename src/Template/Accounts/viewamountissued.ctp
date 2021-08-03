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
// if(!$is_capable)
	// {
		// $this->ERPfunction->access_deniedmsg();
	// }
// else
// {
?>			
                <div class="block block-fill-white">
					<div class="head bg-default bg-light-rtl">
						<h2>View Income</h2>
						<div class="pull-right">
						<a href="<?php //echo $this->ERPfunction->action_link('Accounts','sitetransactions');?>" onclick = "javascript:window.close();" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
						</div>
					</div>
					
					<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
					<input type="hidden" name="user_action" class="form-control" value="<?php ?>"/>	
					
					 <div class="content controls">
						<div class="form-row">
                            <div class="col-md-2">Project Code </div>
                            <div class="col-md-4"><input type="text" name="project_code" id="project_code" value="<?php echo $this->ERPfunction->get_project_code($view_list['project_id']); ?>"
							class="form-control validate[required]"  readonly="true"/></div>
							<div class="col-md-2">Project Name </div>
                            <div class="col-md-4">
								<select class="select2"  disabled="disabled"   style="width: 100%;" name="project_id" id="project_id">
								<option value="">--Select Project--</Option>
								<?php 
									foreach($projects as $retrive_data)
									{
									?>
										<option value="<?php echo $retrive_data["project_id"];?>" <?php if($view_list['project_id'] == $retrive_data['project_id']) echo "selected=selected"; ?>>
										<?php echo $retrive_data["project_name"];?></option>
									<?php
									}
								?>
								</select>
							</div>
                        </div>
						<div class="form-row">
                            <div class="col-md-2">Voucher No</div>
                            <div class="col-md-4">
								<input type="text" name="voucher_no" id="voucher_no" readonly="true" class="form-control" value="<?php echo $view_list['voucher_no'];?>"/>
							</div>
                        
                            <div class="col-md-1 text-right">Date</div>
                            <div class="col-md-2"><input type="text" name="pr_date" readonly="true" id="pr_date" 
							 class="form-control" value="<?php echo $this->ERPfunction->get_date($view_list['date']);?>"/></div>
							 <div class="col-md-1 text-right">Time</div>
                            <div class="col-md-2"><input type="text" name="pr_time" readonly="true" id="pr_time" 
							value="<?php echo date('H:i');?>" class="form-control" value="<?php echo $view_list['date'];?>"/></div>
                        </div>
						<div class="form-row">
                            <div class="col-md-2">Account Name</div>
                            <div class="col-md-10">
								<select class="select2"  disabled="disabled"   style="width: 100%;" name="account_id" id="account_id">
								<option value="">--Select Account--</Option>
								 <?php 
									 foreach($account_list as $retrive_data)
									 {
										?>
										<option value="<?php echo $retrive_data["account_id"];?>" <?php if($view_list['account_id'] == $retrive_data['account_id']) echo "selected=selected"; ?>>
										<?php echo $retrive_data["account_name"];?></option>
									<?php
									 }
								 ?>
								</select>
							</div>
                        </div>
						
						<div class="form-row">
                            <div class="col-md-2">Account No</div>
                            <div class="col-md-4"><input type="text" name="account_no" id="account_no" 
							class="form-control validate[required]" value="<?php echo $view_list['account_no'];?>" readonly="true"/></div>
							<div class="col-md-2">Bank </div>
                            <div class="col-md-4"><input type="text" name="bank" id="bank" 
							class="form-control validate[required]" value="<?php echo $view_list['bank'];?>" readonly="true"/></div>
                        </div>
						
						<div class="form-row">
                            <div class="col-md-2">Branch </div>
                            <div class="col-md-4"><input type="text" name="branch" id="branch" 
							class="form-control validate[required]" value="<?php echo $view_list['branch'];?>" readonly="true"/></div>
							<div class="col-md-2">IFSC Code </div>
                            <div class="col-md-4"><input type="text" name="ifsc_code" id="ifsc_code" 
							class="form-control validate[required]" value="<?php echo $view_list['ifsc_code'];?>" readonly="true"/></div>
                        </div>
						
						<div class="form-row">
                            <div class="col-md-2">Amount Issued</div>
                            <div class="col-md-4"><input type="text" name="amount_issued" readonly="true" id="amount_issued" value="<?php echo $view_list['amount_issue'];?>"
							class="form-control validate[required]"/></div>
							<div class="col-md-2">Payment </div>
                            <div class="col-md-4">
								<select class="select2"  disabled="disabled"   style="width: 100%;" name="payment_type" id="payment_type" onchange="yesnoCheck(this);">
								<option value="cheque" <?php if($view_list['payment_type'] == 'cheque') echo "selected"; ?>>Cheque</Option>
								<option value="cash" <?php if($view_list['payment_type'] == 'cash') echo "selected"; ?>>Cash</Option>
								
								</select>
							</div>
                        </div>
						
						<div class="form-row" id="chequedetail">
                            <div class="col-md-2">Bank</div>
                            <div class="col-md-3"><input type="text" name="bnk" readonly="true" id="bnk" value="<?php echo $view_list['second_bank'];?>"
							class="form-control validate[required]" /></div>
							<div class="col-md-2">Cheque No</div>
                            <div class="col-md-2"><input type="text" name="cheque_no" readonly="true" id="cheque_no" value="<?php echo $view_list['cheque_no'];?>"
							class="form-control validate[required]" /></div>
							<div class="col-md-1 text-right">Date</div>
                            <div class="col-md-2"><input type="text" name="cheque_date" id="cheque_date" 
							value="<?php echo $this->ERPfunction->get_date($view_list['cheque_date']);?>" readonly="true" class="form-control"/></div>
                        </div>
						
						<div class="form-row" style="display:none;" id="cashdetail">
                            <div class="col-md-2">Receiver Name<span class="require-field">*</span> </div>
                            <div class="col-md-4"><input type="text" name="receiver" id="receiver" value=""
							class="form-control validate[required]" value=""/></div>
							
                        </div>
						
						<div class="form-row">
                            <div class="col-md-2">Remarks</div>
                            <div class="col-md-10">
								<textarea name="remark" id="remark" readonly="true" class="form-control validate[required]"><?php echo $view_list['remark'];?></textarea>
							</div>
                        </div>
						
						
					</div>
					
				<?php $this->Form->end(); ?>
				<?php  ?>
			</div>
	<?php //} ?>
         </div>
