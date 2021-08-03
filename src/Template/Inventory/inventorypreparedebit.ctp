<?php
use Cake\Routing\Router;
?>

<script type="text/javascript">
var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
jQuery(document).ready(function() {
	jQuery('#user_form').validationEngine();
	jQuery('#debit_date').datepicker({
		dateFormat: "dd-mm-yy",
		  changeMonth: true,
	        changeYear: true,
	        yearRange:'-65:+0',
			minDate: -7,
			maxDate: new Date(),
	        onChangeMonthYear: function(year, month, inst) {
	            jQuery(this).val(month + "-" + year);
	        }                    
    });
	
	function count(row_id)
  {
		var qty = jQuery('#quantity_'+row_id).val();
		var rate = jQuery('#rate_'+row_id).val();
		var answer = 0;
		
		if(qty == '')
		{
			qty = 0;
		}
		
		if(rate == '')
		{
			rate = 0;
		}
		answer = parseFloat(qty*rate);
		
		jQuery('#single_amount_'+row_id).val(answer.toFixed(2));
		
		var amount_total = 0;
		jQuery('.amount_txt').each(function(){
				var single_amount = jQuery(this).val();
				if(single_amount == '')
				{
					single_amount = 0;
				}
				amount_total = parseFloat(parseFloat(amount_total)+parseFloat(single_amount));  
		});
		jQuery('#total_amount').val(amount_total.toFixed(2));
		
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
		
  }
  
  jQuery('body').on('blur','.quantity',function(){

		var row_id = jQuery(this).attr('data-id');
		count(row_id);
		
    });
	
	jQuery('body').on('blur','.rate',function(){

		var row_id = jQuery(this).attr('data-id');
		count(row_id);
		
    });
	
	jQuery("body").on("change", "#project_id", function(event){ 
	 
		var project_id  = jQuery(this).val() ;
		 
		var curr_data = {	 						 					
	 					project_id : project_id,	 					
	 					};	 				
	 	jQuery.ajax({
			headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'getprojectwisematerial'));?>",
                data:curr_data,
                async:false,
                success: function(response){		
					$(".material_id").select2("val", "");
					jQuery('select.material_id').empty();
					jQuery('select.material_id').append(response);
					return false;
                },
                error: function (e) {
                     alert('Error');
                }
            });	
	});
	
	jQuery("body").on("change", ".material_id", function(event){ 
	 var row_id = jQuery(this).attr('data-id');
	  var material_id  = jQuery(this).val() ;  
	   var curr_data = {	 						 					
	 					material_id : material_id,	 					
	 					};	 				
	 	 jQuery.ajax({
			headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'getmaterialbrandlist'));?>",
                data:curr_data,
                async:false,
                success: function(response){					
					var json_obj = jQuery.parseJSON(response);					
					
					jQuery('#unit_name_'+row_id).html();
					jQuery('#unit_'+row_id).html(json_obj['unit_name']);
					
					return false;
                },
                error: function (e) {
                     alert('Error');
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
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'inventorydebitnoteprojectdetail'));?>",
                data:curr_data,
                async:false,
                success: function(response){					
					var json_obj = jQuery.parseJSON(response);					
					jQuery('#project_code').val(json_obj['project_code']);						
					jQuery('#debit_no').val(json_obj['debitno']);
					return false;
                },
                error: function (e) {
                     alert('Error');
                     console.log(e.responseText);
                }
            });	
	});
		
	jQuery("#add_newrow").click(function(){
		var row_length = 0;
		var project_id = $("#project_id").val();
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
                      url: '<?php echo Router::url(["controller" => "Ajaxfunction","action" => "addnewinventorydebitrow"]);?>',
                     data : {row_id:row_id , sr_no:sr_no, project_id:project_id},
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
	
	jQuery("body").on("blur", ".amount_txt", function(event){ 
	
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
	
	jQuery("body").on("change", ".material_id", function(event){
	var material_id  = jQuery(this).val() ;
	var row_id  = jQuery(this).attr('data-id') ;
	
	var ids = [];
	$('select.material_id').not(this).each(function( index, value ) {
			if(jQuery(this).attr('value') != '')
			{
				ids.push(jQuery(this).attr('value'));
			}
	});
	if(jQuery.inArray( material_id, "["+ids+"]" ) >  -1){
		alert("You can't select same material again");
		$(this).select2('val', '');
		$("#material_code_"+row_id).html('');
		$("#unit_name_"+row_id).html('');
	}else{
		// alert('not selected');
	}
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
	
	jQuery("body").on("change", ".material_id", function(event){
		var project_id = jQuery("#project_id").val();
		var debit_date = jQuery("#debit_date").val();
		var party_id = jQuery("#party_id").val();
		var material_id = jQuery(this).val();
		var row_id = jQuery(this).attr("data-id");
		
		if(project_id != "" && debit_date != "" && party_id != "" && material_id != "" && row_id != "")
		{
			debitTillDateQuantity(project_id,debit_date,party_id,material_id,row_id);
		}else{
			jQuery("#till_date_qty_"+row_id).val(0);
		}
	  
	});
	
	jQuery("body").on("change", "#project_id,#party_id,#debit_date", function(event){
		var project_id = jQuery("#project_id").val();
		var debit_date = jQuery("#debit_date").val();
		var party_id = jQuery("#party_id").val();
		
		$( ".row_number" ).each(function() {
		  var row_id = $( this ).val();
		  var material_id = jQuery("#material_id_"+row_id).val();
		  
		  if(project_id != "" && debit_date != "" && party_id != "" && material_id != "" && row_id != "")
			{
				
				debitTillDateQuantity(project_id,debit_date,party_id,material_id,row_id);
			}else{
				jQuery("#till_date_qty_"+row_id).val(0);
			}
		});
	});
	
	function debitTillDateQuantity(project_id,debit_date,party_id,material_id,row_id)
	{
		var curr_data = {	 						 					
							project_id : project_id,	 					
							debit_date : debit_date,	 					
							party_id : party_id,	 					
							material_id : material_id,					
						};	 				
		jQuery.ajax({
			headers: {
				'X-CSRF-Token': csrfToken
			},
			type:"POST",
			url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'getdebittilldatequantity'));?>",
			data:curr_data,
			async:false,
			success: function(response){			
				jQuery("#till_date_qty_"+row_id).val(response);
			},
			error: function (e) {
				 alert('Error');
			}
		});
	}
	
	jQuery("body").on("change", ".quantity", function(event){
		var row = jQuery(this).attr("data-id");
		var debit_qty = jQuery(this).val();
		var till_date_qty = jQuery("#till_date_qty_"+row).val();
		
		var debit_qty = parseFloat(debit_qty);
		var till_date_qty = parseFloat(till_date_qty);
		
		if(debit_qty > till_date_qty)
		{
			jQuery(this).val('');
			alert("Not allow return quantity greater than till date issued quantity.");
			return false;
		}
	});
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
						<h2> ADD DEBIT NOTE  </h2>
						<div class="pull-right">
						<a href="<?php echo $this->ERPfunction->action_link('inventory','index');?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
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
                            <div class="col-md-2">Debit Note No<span class="require-field">*</span> </div>
                            <div class="col-md-4"><input type="text" name="debit_no" id="debit_no"
							class="form-control validate[required]"/></div>
							<div class="col-md-2 text-right">Date<span class="require-field">*</span> </div>
                            <div class="col-md-4"><input type="text" name="debit_date" id="debit_date" 
							 class="form-control validate[required]" value="<?php echo date('d-m-Y'); ?>"/></div>
                        </div>
						
						<div class="form-row">
                            <div class="col-md-2" style="padding: 0;">Vendor Name/Asset Name*</div>
                            <div class="col-md-10">
								<?php  echo $this->Form->select("agency_name",$vendor_assets,["empty"=>" ","class"=>"select2 asst_list","id"=>"party_id","style"=>"width:100%;","required"=>true]);?>
							</div>                        
                        </div>
						
						<div class="form-row">
                            <div class="col-md-2">Receiver's Name<span class="require-field">*</span> </div>
                            <div class="col-md-10">
								<input type="text" name="receiver_name" id="receiver_name" class="form-control validate[required]"/>
							</div>
							
                        </div>
						
						<div class="form-row">
                            <div class="col-md-2">Reason / Remarks<span class="require-field">*</span> </div>
                            <div class="col-md-10">
								<input type="text" name="reason" id="reason" placeholder="Misuse / Over-use / Lost / Other" class="form-control validate[required]"/>
							</div>
							
                        </div>
						
						<div class="form-row">
						 
                            <table class="table table-bordered">
								<thead>
									<tr>
										<th style="width:15%">Sr.No</th>
										<th style="width:30%">Description</th>
										<th style="width:15%">Net Issue Qty.</th>
										<th style="width:15%">Approx. Quantity</th>
										<th style="width:10%">Unit</th>
										<th style="width:15%">Approx. Rate</th>
										<th style="width:15%">Approx Amount</th>
										<th>Action</th>
									</tr>
									<tr>
																
									</tr>
								</thead>
								<tfoot>
									<tr>
									<td colspan="6">
										<div class="col-md-12"><p class="text-center text-bold">Total Amount of Debit</p></div></td>
										<td class="col-md-3" colspan="2">
										<input type="text" name="total_amount" id="total_amount" value="0"
							class="form-control" readonly="true"/>
										</td>
									</tr>
									<tr>
										<td>In Words<span class="require-field">*</span></td>
										<td colspan="7"><input type="text" name="total_words" readonly="true" id="total_words" value=""
							class="form-control validate[required]"/></td>
									</tr>
								</tfoot>
								<tbody id="expence_content">
									<tr id="row_id_0">
										<td style="width:10%">
											<span id="material_code_0" sr_no="1" class="sr_div">1</span>
											<input type="hidden" value="1" class="serial_no">
											<input type="hidden" value="0" class="row_number">
										</td>
											
										<td>
											<select class="select2 material_id" style="width: 100%;" name="debit[material_id][]" id="material_id_0" data-id="0">
												<option value="">--Select Material--</Option>
												<?php 
													foreach($material_list as $retrive_data)
													{
														echo '<option value="'.$retrive_data['material_id'].'">'.
														$retrive_data['material_title'].'</option>';
													}
												?>
											</select>
										</td>
										<td><input type="text" readonly="true" name="debit[till_date_qty][]" id="till_date_qty_0" value="" class="no-padding form-control"/></td>
										<td style="width:15%"> 
											<input type="text" name="debit[quantity][]" value="" class="quantity validate[required]" data-id="0" style="width:100%" id="quantity_0"/>
										</td>
										<td style="width:10%">
											<span id="unit_0"></span>
										</td>
										<td style="width:15%">
											<input type="text" name="debit[rate][]" class="rate" value="" data-id="0" id="rate_0" style="width:100%" />
										</td>
										
										<td style="width:15%">
											<input type="text" name="debit[single_amount][]" value="0" class="single_amount amount_txt" id="single_amount_0" style="width:100%"/></td>
										
										<td>
											<a href="#" class="btn btn-danger del_parent">Delete</a>
										</td>
									</tr>
								</tbody>
							</table>
							<button type="button" id="add_newrow" class="btn btn-default">Add New </button>
                        </div>
						
						<div class="form-row" STYLE="margin-top:15px;">
						<div class="col-md-2 text-right">Attach Documents</div>
						<div class="col-md-4">
							<input type="file" name="debit_doc" id="debit_doc" class="form-control imageUpload">
							<span class='required red notice'></span>
						</div>
						</div>
						
						<div class="form-row">
                            <div class="col-md-2"></div>
                            <div class="col-md-4"><button type="submit" class="btn btn-primary" onclick="return ValidateExtension()">Add Debit Note</button></div>
                        </div>
					</div>
					
				<?php $this->Form->end(); ?>
			</div>
<?php } ?>
         </div>
