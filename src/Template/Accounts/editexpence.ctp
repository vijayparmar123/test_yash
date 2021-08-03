<?php
use Cake\Routing\Router;

if(!$is_capable)
	{
		$this->ERPfunction->access_deniedmsg();
	}
else
{

?>

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
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'expenceprojectdetail'));?>",
                data:curr_data,
                async:false,
                success: function(response){					
					var json_obj = jQuery.parseJSON(response);					
					jQuery('#project_code').val(json_obj['project_code']);						
					jQuery('#prno').val(json_obj['prno']);
					jQuery('#voucher_no').val(json_obj['prno']);
					return false;
                },
                error: function (e) {
                     alert('Error');
                     console.log(e.responseText);
                }
            });	
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
	jQuery('.delivery_date').datepicker({
		 changeMonth: true,
      changeYear: true,
	  dateFormat: "dd-mm-yy"
	});
	
  
  jQuery("body").on("blur", ".amount_txt", function(event){ 
	
		var len = jQuery(".amount_txt").length;
		var i;
		var amount;
		var total=0;
		for(i = 0; i < len; i++)
		{
			amount = jQuery("#amount_value_" + i).val();
			if(amount == '')
			{
				amount = 0;
			}
			total = parseFloat(total) + parseFloat(amount); 
		}
		jQuery("#total_amount").val(parseFloat(total));
		
		var curr_data = {	 						 					
	 					amount : total,	 					
	 					};
		jQuery.ajax({
                headers: {
					'X-CSRF-Token': csrfToken
				},
                type:"POST",
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'convertnumbertowords'));?>",
                data:curr_data,
                async:false,
                success: function(response){					
					// var json_obj = jQuery.parseJSON(response);					
					 jQuery('#total_words').val("INR" + " " +response + " " + "only");
					// return false;
					//alert(response);
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
				
                <div class="block block-fill-white">
					<div class="head bg-default bg-light-rtl">
						<h2> Edit Expense </h2>
						<div class="pull-right">
						<a href="<?php //echo $this->ERPfunction->action_link('Accounts','expencealert');?>" onclick = "javascript:window.close();" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
						</div>
					</div>
					
					<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
					<input type="hidden" name="user_action" class="form-control" value="<?php ?>"/>	
					
					 <div class="content controls">
						<div class="form-row">
                            <div class="col-md-2">Project Code<span class="require-field">*</span> </div>
                            <div class="col-md-4"><input type="text" name="project_code" id="project_code" 
							class="form-control validate[required]" value="<?php echo $this->ERPfunction->get_project_code($expence_list['project_id']); ?>" readonly="true"/></div>
							<div class="col-md-2">Project Name *</div>
                            <div class="col-md-4">
								<select class="select2"  required="true"   style="width: 100%;" name="project_id" id="project_id">
								<option value="">--Select Project--</Option>
								<?php 
									foreach($projects as $retrive_data)
									{
									?>
										<option value="<?php echo $retrive_data["project_id"];?>" <?php if($expence_list['project_id'] == $retrive_data['project_id']) echo "selected=selected"; ?>>
										<?php echo $retrive_data["project_name"];?></option>
									<?php
									}
								?>
								</select>
							</div>
                        </div>
						<div class="form-row">
                            <div class="col-md-2">Account Name<span class="require-field">*</span></div>
                            <div class="col-md-10">
								<select class="select2"  required="true"   style="width: 100%;" name="account_id" id="account_id">
								<option value="">--Select Account--</Option>
								<?php 
									foreach($account_list as $retrive_data)
									{
								?>
									<option value="<?php echo $retrive_data["account_id"];?>" <?php if($expence_list['account_id'] == $retrive_data['account_id']) echo "selected=selected"; ?>>
										<?php echo $retrive_data["account_name"];?></option>
							
								<?php
									}
								?>
								</select>
							</div>
                        </div>
						
						<div class="form-row">
                            <div class="col-md-2">Account No<span class="require-field">*</span> </div>
                            <div class="col-md-4"><input type="text" name="account_no" id="account_no" 
							class="form-control validate[required]" value="<?php echo $expence_list['account_no']; ?>" readonly="true"/></div>
							<div class="col-md-2">Bank </div>
                            <div class="col-md-4"><input type="text" name="bank" id="bank" 
							class="form-control validate[required]" value="<?php echo $expence_list['bank']; ?>" readonly="true"/></div>
                        </div>
						
						<div class="form-row">
                            <div class="col-md-2">Voucher No<span class="require-field">*</span> </div>
                            <div class="col-md-4"><input type="text" name="voucher_no" id="voucher_no" 
							class="form-control validate[required]" value="<?php echo $expence_list['voucher_no']; ?>" /></div>
							<div class="col-md-2 text-right">Date</div>
                            <div class="col-md-4"><input type="text" name="pr_date" id="pr_date" class="form-control" value="<?php echo $this->ERPfunction->get_date($expence_list['date']); ?>"/></div>
                        </div>
						
						<div class="form-row">
                            <div class="col-md-2">Expence Head<span class="require-field">*</span></div>
                            <div class="col-md-10">
								<select class="select2"  required="true"   style="width: 100%;" name="expence_head" id="expence_head">
								<option value="">--Select Expence Head--</Option>
								<?php 
									foreach($expence_head_list as $retrive_data)
									{
								?>
										<option value="<?php echo $retrive_data["expence_id"];?>" <?php if($expence_list['expence_head'] == $retrive_data['expence_id']) echo "selected=selected"; ?>>
										<?php echo $retrive_data["expence_head_name"];?></option>
								<?php
									}
								?>
								</select>
							</div>
                        </div>
						
						<div class="form-row">
                            <div class="col-md-2">Given To<span class="require-field">*</span> </div>
                            <div class="col-md-4"><input type="text" name="given_to" id="given_to" value="<?php echo $expence_list['given_to']; ?>"
							class="form-control validate[required]"/></div>
							<div class="col-md-2">Payment *</div>
                            <div class="col-md-4">
								<select class="select2"  required="true"   style="width: 100%;" name="payment_type" id="payment_type">
								<option value="cheque" <?php echo ($expence_list['payment_type'] == 'cheque')?'selected':''; ?>>Cheque</Option>
								<option value="cash" <?php echo ($expence_list['payment_type'] == 'cash')?'selected':''; ?>>Cash</Option>
								
								</select>
							</div>
                        </div>
					<!-- <div class="form-row">
                            <div class="col-md-2">Raised From:</div>
                            <div class="col-md-4">
								<?php 
								
								?>
								<select class="select2"  required="true"   style="width: 100%;" name="raise_from" id="raise_from">
								<option value="">--Select Project--</Option>
								<?php 
									// foreach($raise_from as $key => $data)
								// {
									// echo '<optgroup label="'.$this->ERPfunction->get_rolename($key).'" style = "text-transform: capitalize;">';
									// foreach($data as $user_data)
									// {
										// echo '<option value="'.$user_data['user_id'].'">'.
										// $this->ERPfunction->get_user_name($user_data['user_id']).'</option>';									
									// }
									// echo '</optgroup>';
								// }
								?>
								</select>
							</div>
                        
                             <div class="col-md-2">Contact No: (1)</div>
                            <div class="col-md-4">
								<input type="text" name="contact_no1" value="" class="form-control" value=""/>
							</div>
                        </div> -->
					<!-- <div class="form-row">
                            <div class="col-md-2">Forwarded To:</div>
                            <div class="col-md-4">
								<select class="select2"  required="true"   style="width: 100%;" name="forword_to">
								<option value="">--Select Project--</Option>
								<?php 
									// foreach($purchase_department as $retrive_data)
									// {
										// echo '<option value="'.$retrive_data['user_id'].'">'.
										// $this->ERPfunction->get_user_name($retrive_data['user_id']).'</option>';
									// }
								?>
								</select>
							</div>
                        
                            <div class="col-md-2">Contact No: (2)</div>
							<div class="col-md-4">
								<input type="text" name="contact_no2" value="" class="form-control" value=""/>
							</div>
                        </div> -->
						
						<div class="form-row">
						 <!-- <button type="button" id="add_newrow" class="btn btn-default">Add New </button> -->
                            <table class="table table-bordered">
								<thead>
									<tr>
									<th>Sr.No</th>
									<th>Expense Description</th>								
									<th rowspan="2">Amount</th>
									</tr>
									<tr>
																
									</tr>
								</thead>
								<tfoot>
									<tr>
									<td colspan="2">
										<div class="col-md-12"><p class="text-center text-bold">Total Amount of Expense</p></div></td>
										<td class="col-md-3">
										<?php
										$i=0;
										$numItems = count($detail_data);
										foreach($detail_data as $retrive_data)
										{
										if(++$i === $numItems) 
										{
										?>
										<input type="text" name="total_amount" id="total_amount" value="<?php echo $retrive_data['expence_total']; ?>"
							class="form-control" readonly="true"/>
										<?php }} ?>
										</td>
									</tr>
									<tr>
										<td>In Words<span class="require-field">*</span></td>
										<td colspan="2"><input type="text" name="total_words" readonly="true" id="total_words" value="<?php echo $retrive_data['expence_toatl_word']; ?>"
							class="form-control validate[required]"/></td>
									</tr>
								</tfoot>
								<tbody>
								<?php 
								$i = 0;
									foreach($detail_data as $retrive_data)
									{
										
										 $i++;
								?>
									<tr id="row_id_0">
										<td><span id="material_code_0" sr_no="<?php echo $i; ?>"><?php echo $i; ?></span></td>
				
										<td>
											<input type="text" name="expense[description][]"  value="<?php echo $retrive_data['expence_description']; ?>" class="form-control"/>
										</td>
										<td>
										<input type="text" name="expense[amount][]" id="amount_value_<?php echo $i - 1; ?>"  value="<?php echo $retrive_data['expence_amount']; ?>" class="form-control validate[required] amount_txt" />
										<input type="hidden" name="expense[id][]" id="id"  value="<?php echo $retrive_data['detail_id']; ?>" class="form-control validate[required] id" />
										</td>
										
										
										<!-- <td><span id="unit_name_0"></span></td> -->
			
										
									</tr>
									<?php } ?>
								</tbody>
							</table>
                        </div>
						<div class="form-row">
                            <div class="col-md-2"></div>
                            <div class="col-md-4"><button type="submit" class="btn btn-primary">Add Expence</button></div>
                        </div>
					</div>
					
				<?php $this->Form->end(); ?>
				<div class="row" style="font-style:italic;color:gray;">							
					<div class="col-md-7 pull-right">
						<br><br><br>
						<div class="col-md-4">
							<?php echo "Made By:".$this->ERPfunction->get_user_name($retrive_data['created_by']); ?>
						</div>
						<div class="col-md-4">
							 <?php echo "Approved By:".$this->ERPfunction->get_user_name($retrive_data['approval_by_cmpdmd']); ?>
						</div>
						 <!-- <div class="col-md-4">						 
						  <a href="../printbill/<?php echo $retrive_data['detail_id'];?>" class="btn btn-default" id="print_this" target="_blank"><i class="icon-print"></i> Print</a>
						</div>-->
					</div>
				</div></div>
			</div>

         </div>
<?php } ?>