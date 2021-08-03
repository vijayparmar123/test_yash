<?php
use Cake\Routing\Router;
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
					//jQuery('#voucher_no').val(json_obj['prno']);
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
	
	jQuery("#add_newrow").click(function(){
		var row_length = 0;
		var row_length = jQuery(".row_number").length;
		if(row_length > 0)
		{
			var num = jQuery(".row_number:last").val();
			var row_id = parseInt(num) + 1;
		}
		else
		{
			var row_id = 0;
		}
		
		var sr_length = 0;
		sr_length = jQuery(".serial_no").length;
		if(sr_length > 0)
		{
			var num = jQuery(".serial_no:last").val();
			var sr_no = parseInt(num) + 1;
		}
		else
		{
			var sr_no = 1;
		}
		//alert('length:'+sr_length+' '+'value:'+sr_no);
		var action = 'add_newrow';
		jQuery.ajax({
						headers: {
							'X-CSRF-Token': csrfToken
						},
                       type: 'POST',
                      url: '<?php echo Router::url(["controller" => "Ajaxfunction","action" => "addnewexpenserow"]);?>',
                     data : {row_id:row_id , sr_no:sr_no},
                     success: function (response)
                        {	
                            jQuery("#expence_content").append(response);
							jQuery('#material_id_'+row_id).select2();
							jQuery('.delivery_date').datepicker({
								 changeMonth: true,
							  changeYear: true,
							  dateFormat: "dd-mm-yy"
							});
							return false;
						},
                    error: function(e) {
                 alert("An error occurred: " + e.responseText);
                    console.log(e);
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
	
	 // jQuery("body").on("change", "#total_amount", function(event){ 
	 
	  // var amount  = jQuery(this).val() ;
	  // alert(amount);
		// /* alert(product_id);
		// return false; */
	   // var curr_data = {	 						 					
	 					// amount : amount,	 					
	 					// };	 				
	 	 // jQuery.ajax({
                // headers: {
				// 	'X-CSRF-Token': csrfToken
				// },
                // type:"POST",
                // url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'convertnumbertowords'));?>",
                // data:curr_data,
                // async:false,
                // success: function(response){					
					//var json_obj = jQuery.parseJSON(response);					
					 // jQuery('#total_words').val(response);
					//return false;
					//alert(response);
                // },
                // error: function (e) {
                     // alert('Error');
                     // console.log(e.responseText);
                // }
            // });	
	// });
	
	jQuery("body").on("blur", ".amount_txt", function(event){ 
	
		// var len = jQuery(".amount_txt").length;
		// var i;
		// var amount;
		// var total=0;
		// for(i = 0; i < len; i++)
		// {
			// amount = jQuery("#amount_value_" + i).val();
			// if(amount == '')
			// {
				// amount = 0;
			// }
			// total = parseFloat(total) + parseFloat(amount); 
		// }
		// var abc = parseFloat(total);
		// jQuery("#total_amount").val(parseFloat(total));
		
		var amount_total = 0;
		jQuery('.amount_txt').each(function(){
				var single_amount = jQuery(this).val();
				if(single_amount == '')
				{
					single_amount = 0;
				}
				amount_total = parseFloat(parseFloat(amount_total)+parseFloat(single_amount));  
		});
		jQuery('#total_amount').val(amount_total);
		
		var curr_data = {	 						 					
	 					amount : amount_total,	 					
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
	
	$("body").on("click",".del_parent",function(){
		$(this).parents("tr").remove();
		
		var amount_total = 0;
		jQuery('.amount_txt').each(function(){
				var single_amount = jQuery(this).val();
				if(single_amount == '')
				{
					single_amount = 0;
				}
				amount_total = parseFloat(parseFloat(amount_total)+parseFloat(single_amount));  
		});
		jQuery('#total_amount').val(amount_total);
		
		var curr_data = {	 						 					
	 					amount : amount_total,	 					
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
			
			var i = 1;
			jQuery('.serial_no').each(function(){
					jQuery(this).val(i);
					i++;  
			});
			var a = 1;
			jQuery('.sr_div').each(function(){
					jQuery(this).html('');
					jQuery(this).html(a);
					a++;  
			});
		
	});
});
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
						<h2> ADD EXPENSE  </h2>
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
                            <div class="col-md-2">Account Name<span class="require-field">*</span></div>
                            <div class="col-md-10">
								<select class="select2"  required="true"   style="width: 100%;" name="account_id" id="account_id">
								<option value="">--Select Account--</Option>
								 <?php 
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
                            <div class="col-md-2">Voucher No<span class="require-field">*</span> </div>
                            <div class="col-md-4"><input type="text" name="voucher_no" id="voucher_no" value=""
							class="form-control validate[required]"/></div>
							<div class="col-md-2 text-right">Date<span class="require-field">*</span> </div>
                            <div class="col-md-4"><input type="text" name="pr_date" id="pr_date" 
							 class="form-control validate[required]"/></div>
                        </div>
						
						<div class="form-row">
                            <div class="col-md-2">Expense Head<span class="require-field">*</span></div>
                            <div class="col-md-10">
								<select class="select2"  required="true"   style="width: 100%;" name="expence_head" id="expence_head">
								<option value="">--Select Expence Head--</Option>
								<?php 
									foreach($expence_head_list as $retrive_data)
									{
										echo '<option value="'.$retrive_data['expence_id'].'">'.
										$retrive_data['expence_head_name'].'</option>';
									}
								?>
								</select>
							</div>
                        </div>
						
						<div class="form-row">
                            <div class="col-md-2">Given To<span class="require-field">*</span> </div>
                            <div class="col-md-4"><input type="text" name="given_to" id="given_to" value=""
							class="form-control validate[required]"/></div>
							<div class="col-md-2">Payment *</div>
                            <div class="col-md-4">
								<select class="select2"  required="true"   style="width: 100%;" name="payment_type" id="payment_type">
								<option value="cheque">Cheque</Option>
								<option value="cash" selected>Cash</Option>
								
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
						 
                            <table class="table table-bordered">
								<thead>
									<tr>
									<th>Sr.No</th>
									<th>Expense Description</th>								
									<th rowspan="2">Amount</th>
									<th>Action</th>
									</tr>
									<tr>
																
									</tr>
								</thead>
								<tfoot>
									<tr>
									<td colspan="2">
										<div class="col-md-12"><p class="text-center text-bold">Total Amount of Expense</p></div></td>
										<td class="col-md-3" colspan="2">
										<input type="text" name="total_amount" id="total_amount" value="0"
							class="form-control" readonly="true"/>
										</td>
									</tr>
									<tr>
										<td>In Words<span class="require-field">*</span></td>
										<td colspan="3"><input type="text" name="total_words" readonly="true" id="total_words" value=""
							class="form-control validate[required]"/></td>
									</tr>
								</tfoot>
								<tbody id="expence_content">
									<tr id="row_id_0">
										<td><span id="material_code_0" sr_no="1" class="sr_div">1</span>
										<input type="hidden" value="1" class="serial_no">
										<input type="hidden" value="0" class="row_number">
										</td>
											
										<td>
											<input type="text" name="expense[description][]"  value="" class="form-control validate[required]"/>
										</td>
										<td>
										<input type="text" name="expense[amount][]" id="amount_value_0"  value="" class="form-control amount_txt validate[required]" />
										</td>
										<td><a href="#" class="btn btn-danger del_parent">Delete</a>
										
										<!-- <td><span id="unit_name_0"></span></td> -->
			
										
									</tr>
								</tbody>
							</table>
							<button type="button" id="add_newrow" class="btn btn-default">Add New </button>
                        </div>
						<div class="form-row">
                            <div class="col-md-2"></div>
                            <div class="col-md-4"><button type="submit" class="btn btn-primary">Add Expence</button></div>
                        </div>
					</div>
					
				<?php $this->Form->end(); ?>
			</div>
<?php } ?>
         </div>
