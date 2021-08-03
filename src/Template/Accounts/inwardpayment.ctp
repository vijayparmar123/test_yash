<?php
use Cake\Routing\Router;

?>
<div class="col-md-10" >
<?php 
if(!$is_capable)
	{
		$this->ERPfunction->access_deniedmsg();
	}
 else
 {
?>
<?php //echo $this->element('breadcrumbs'); ?>
<div class="row">
	<div class="col-md-12">
		<div class="block">
			<div class="header bg-default bg-light-rtl">
				<h2 style="color:#FFFFFF;">Payment Notification</h2>
				<div class="pull-right">
					<a href="<?php echo $this->ERPfunction->action_link('Accounts','index');?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
				</div>
			</div>
		
		  <div class="block block-fill-white">
		  <script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('.datep').datepicker({
		dateFormat: "dd-mm-yy",
		  changeMonth: true,
	        changeYear: true,
	        yearRange:'-65:+0',
	        onChangeMonthYear: function(year, month, inst) {
	            jQuery(this).val(month + "-" + year);
	        }                    
    });
	
	jQuery('#user_form').validationEngine();
});
</script>
		<script>
			var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
		jQuery(document).ready(function() {
			jQuery("body").on("change", ".party", function(event){ 
	 
				var party_type  = jQuery(this).val() ;
				/* alert(party_type);
				return false; */
				var curr_data = {	 						 					
								party_type : party_type,	 					
								};	 				
					jQuery.ajax({
						headers: {
					'X-CSRF-Token': csrfToken
				},
                type:"POST",
						url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'paymentparty'));?>",
						data:curr_data,
						async:false,
						success: function(response){	
							//alert(response);
							jQuery("#party_section").empty();
							jQuery("#party_section").html(response);
							jQuery('#party_id').select2();
							jQuery('#project_id').select2();
							jQuery("#payment_section table tbody").html('');
						},
						error: function (e) {
							 alert('Error');
						}
					});	
				});
				
				// jQuery("body").on("change", ".payment", function(event){ 
	 
				// var payment_type  = jQuery(this).val();
				// var party_id  = jQuery('#party_id').val();
				// var party_type  = jQuery('.party:checked').val();
				// var curr_data = {	 						 					
								// payment_type : payment_type,party_id : party_id,party_type : party_type	 					
								// };	 				
					// jQuery.ajax({
						// headers: {
					'X-CSRF-Token': csrfToken
				},
                type:"POST",
						// url:"<?php //echo Router::url(array('controller'=>'Ajaxfunction','action'=>'paymentrow'));?>",
						// data:curr_data,
						// async:false,
						// success: function(response){	
							
						// jQuery("#payment_section").empty();
						// jQuery("#payment_section").html(response);
						// jQuery('#transfer_type').select2();					
						// jQuery('#project_id').select2();					
							 
							// return false;
						// },
						// error: function (e) {
							 // alert('Error');
						// }
					// });	
				// });
				
				jQuery("body").on("change", "#party_id", function(event){ 
	 
				var party_id  = jQuery(this).val();
				var party_type  = jQuery('.party:checked').val();
				var payment_type  = jQuery('.payment:checked').val();
				var party_name = $(this).find('option:selected').attr('party-name');
				jQuery("#party_name").val(party_name);
				// if(party_type == 'newparty')
				// {
					// return false;
				// }
				if(party_id != '')
				{
					var party_email = $(this).find('option:selected').attr('dataid');
					$('#party_email').attr('value',party_email);
				}
				else
				{
					$('#party_email').val('');
				}
				if(payment_type == 'advance')
				{
					return false;
				}
				var curr_data = {party_id : party_id , party_type : party_type , payment_type : payment_type};
				
					jQuery.ajax({
						headers: {
					'X-CSRF-Token': csrfToken
				},
                type:"POST",
						url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'inwardpartydetail'));?>",
						data:curr_data,
						async:false,
						success: function(response){
								if(payment_type != 'advance')
								{
									//jQuery("#payment_section table tbody").html(response);
									//jQuery("#payment_section").html(response);
									//jQuery('#transfer_type').select2();					
							 
									return false;
								}
						
						},
						error: function (e) {
							 alert('Error');
						}
					});	
				});
				
				  jQuery('body').on('click','.trash',function(){
						jQuery(this).parents("tr").remove();
						return false;
					});
		} );
		</script>
		
		<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
					<div class="content controls">
					
					<div class="form-row">
						<div class="col-md-12 text-center">
							<h1><u><b>Send Payment Notification</b></u></h1>
						</div>
					</div>
					
					<div class="form-row">
					<div class="col-md-8"></div>
                         <div class="col-md-2">Cheque Date: </div>
						 <div class="col-md-2">
							<input type="text" class="form-control text-center datep" name="cheque_date" value="<?php echo date("d-m-Y"); ?>">
						 </div>
                    </div>
					
					<div class="form-row">
							<div class="col-md-3"></div>
							<div class="col-md-8">
						<div class="form-row" id="radiogroup">
						<div class="col-md-4">
	<input type="radio" name="party_type" id="old_party" checked class="party" value="oldparty">Party from ERP List
						</div>
						<div class="col-md-4">
	<input type="radio" name="party_type" id="new_party" class="party" value="newparty">Party from Inward Bills
						</div>
						</div>
							</div>
							
					</div>
						
					<div class="form-row" id="party_section">
						<div class="col-md-2" style="padding-bottom:15px;">Party's Name*</div>
						<div class="col-md-10" style="padding-bottom:15px;">
						<select name="party_id" class="select2" id="party_id" required="true" style="width: 100%;">
							<option value="">--select party--</option>
							<?php
                            			if($vendor_info){
                            				foreach($vendor_info as $vendor_row){
                            					?>
													<option value="<?php echo $vendor_row['user_id']; ?>" dataid="<?php echo $vendor_row['email_id'];?>" party-name="vandor" <?php 
																if(isset($update_inward)){
																	if($update_inward['party_name'] == $vendor_row['user_id']){
																		echo 'selected="selected"';
																	}
																}

													?> ><?php echo $vendor_row['vendor_name'];?></option>

                            					<?php
                            				}
                            			}
										// if(!empty($agency_list))
										// {
										// 	foreach($agency_list as $agency){ ?>
												<!-- <option value="<?php //echo $agency['agency_id']; ?>" dataid="<?php //echo $agency['email_id'];?>" party-name="agency" -->
												<?php  
													// if(isset($update_inward)){
													// 	if($update_inward['party_name'] == $agency['agency_id']){
													// 		echo 'selected="selected"';
													// 	}
													// }
												?> 
												<!-- > -->
												<?php //echo $agency['agency_name'];?></option>
											<?php	
										// 	}
										// }
                            		?>
						</select>
						</div>
						<div class="col-md-2" style="padding-bottom:15px;">Party's E-mail ID</div>
						<div class="col-md-10" style="padding-bottom:15px;">
						<input type="text" name="party_email" id="party_email" class="form-control">
						<input type="hidden" name="party_name" id="party_name" class="form-control">
						</div>
					</div>
					
					
						
					<div class="form-row">
							<div class="col-md-3"></div>
							<div class="col-md-8">
						<div class="form-row" id="radiogroup">
						<div class="col-md-4">
	<input type="radio" name="payment_type" id="advance" checked class="payment" value="advance">Advance Payment
						</div>
						<div class="col-md-4">
	<input type="radio" name="payment_type" id="invoice" class="payment" value="invoice">Invoice Payment
						</div>
						</div>
							</div>
							
					</div>
					
					<div class="form-row" id="payment_section">
						<div class="col-md-12" style="margin-bottom:40px;">
							<div class="col-md-6">
								<div class="col-md-4 text-right">Bank Name:*</div>
								<div class="col-md-8">
								<input type="text" class="form-control validate[required]" name="bank_name">
								</div>
							</div>
							<div class="col-md-6">
							<div class="col-md-4 text-right">Cheque No:*</div>
								<div class="col-md-8">
								<input type="text" class="form-control validate[required]" name="cheque_no">
								</div>
							</div>
						</div>
						
						<div class="col-md-12" style="margin-bottom:40px;">
						<div class="col-md-6">
								<div class="col-md-4 text-right">Cheque Amount(Rs.):*</div>
								<div class="col-md-8">
								<input type="text" class="form-control validate[required]" name="cheque_amount">
								</div>
							</div>
						</div>
						<div class="col-md-12" style="margin-bottom:40px;">
								<div class="col-md-2 text-right">Transfer Type:*</div>
								<div class="col-md-10">
								<select class="select2" required="true"   style="width: 100%;" name="transfer_type">
									<option value="RTGS">RTGS</Option>
									<option value="NEFT">NEFT</Option>
									<!--<option value="Transfer">Transfer</Option>-->
									<option value="Single-Cheque">Single-Cheque</Option>
									<!--<option value="office">Please Collect Cheque from Corporate Office</Option>-->
								</select>
								</div>
						</div>
						<div class="col-md-12">
						<div class="col-md-2">Assign Project:*</div>
                            <div class="col-md-10">
								<select class="select2" id="project_id" required="true" multiple="multiple" style="width: 100%;" name="assign_projects[]">
								<option value="">--Select Project--</Option>
								<?php 
									foreach($projects as $retrive_data)
									{
										echo '<option value="'.$retrive_data['project_id'].'" 
										'.$this->ERPfunction->multiselected($retrive_data['project_id'],$assign_projects).'>'.
										$retrive_data['project_code'].' '.$retrive_data['project_name'].'</option>';
									}
								?>
							</select>
							</div>
						</div>
					</div>
					
					<div class="form-row" STYLE="margin-top:25px;">
						<div class="col-md-2 text-right">Attach Documents</div>
						<div class="col-md-4">
							<input type="file" name="inward_doc" id="inward_doc" class="form-control">
						</div>
					</div>
					<div class="form-row" STYLE="margin-top:25px;">
					<div class="col-md-4"></div>
						<div class="col-md-2">
							<input type="submit" name="send" class=" btn btn-primary" value="SEND NOTIFICATION"> 
						</div>
						<div class="col-md-2">
							<input type="button" name="cancel" class=" btn btn-primary" value="CANCEL"> 
						</div>
					</div>
			</div>
					
				<?php $this->Form->end(); ?>
			
			
			
			
			
			
			</div> <!-- 2nd content END -->
			
		</div>
		</div>
	</div>
</div>
<?php } ?>
</div>
