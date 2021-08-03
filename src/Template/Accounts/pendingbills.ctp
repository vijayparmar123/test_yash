<?php
//$this->extend('/Common/menu')
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
<!--<div class="row">-->
	<div class="col-md-12">
		<div class="block">
		<div class="head bg-default bg-light-rtl">
			<h2>Pending Bills</h2>
			<div class="pull-right">
			<a href="<?php echo $this->ERPfunction->action_link('Accounts','index');?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
			</div>
		</div>
		
		<?php //echo $this->Form->Create('form1',['id'=>'eq_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
					

                   <!-- <div class="content controls">		
						<div class="form-row">
							<div class="col-md-2 text-right" id="pr_left">Inward Date Form</div>
							<div class="col-md-4"><input name="date_from" class="datepick" /></div>
							<div class="col-md-2 text-right" id="pr_left">Inward Date To</div>
							<div class="col-md-4"><input name="date_to" class="datepick" /></div>
						</div>
						<div class="form-row">
							<div class="col-md-2 text-right" id="pr_left">Bill Date Form</div>
							<div class="col-md-4"><input name="bill_date_from" class="datepick" /></div>
							<div class="col-md-2 text-right" id="pr_left">Bill Date To</div>
							<div class="col-md-4"><input name="bill_date_to" class="datepick" /></div>
						</div>
						
						<div class="form-row">
							<div class="col-md-2 text-right" id="pr_left">Party's Name</div>
							<div class="col-md-4">
							<select class="select2" style="width: 100%;" name="party_id" id="party_id" >
							<option value="All">-- Select Party --</option>
							<?php
							// if($vendor_info){
                            				// foreach($vendor_info as $vendor_row){
                            					// ?>
													// <option value="<?php //echo $vendor_row['user_id']; ?>" dataid="<?php //echo $vendor_row['vendor_id'];?>" <?php 
																// if(isset($update_inward)){
																	// if($update_inward['party_name'] == $vendor_row['user_id']){
																		// echo 'selected="selected"';
																	// }
																// }

													// ?> ><?php //echo $vendor_row['vendor_name'];?></option>

                            					// <?php
                            				// }
                            			// }
										?>
										</select>
							</div>
							<div class="col-md-2 text-right" id="pr_left">Project Name</div>
							<div class="col-md-4">
							<select class="select2" style="width: 100%;" name="project_id" id="project_id" >
								<option value="All">All</Option>
								<?php 
									// foreach($projects as $retrive_data)
									// {
										// ?>
										// <option value="<?php //echo $retrive_data['project_id'];?>" <?php 
											// if(isset($update_inward)){
												// if($update_inward['project_id'] == $retrive_data['project_id'])
												// {
													// echo 'selected="selected"';
												// }
			
											// }?> >
											// <?php //echo $retrive_data['project_name']; ?> </option>
										// <?php										
									// } 
									?>
								</select>
							</div>
						</div>
						<div class="form-row">
							<div class="col-md-2 text-right" id="pr_left">Type of Bill</div>
                            <div class="col-md-4">
								<select name="bill_type" class="select2" style="width:100%;" >

									<?php 
										// $billtype=array(
															// 'All'=>'All',
															// 'Material/Item'=>'Material/Item',
															// 'Labour'=>'Labour',
															// 'Labour with Material/Item'=>'Labour with Material/Item',
															// 'Asset Maintenance'=>'Asset Maintenance',
															// 'Asset Purchase'=>'Asset Purchase',
															// 'Transport'=>'Transport',
															// 'Other'=>'Other',
															
														// );

									
									//foreach($billtype as $bill_key => $bill_value){
										?>
									<option value="<?php //echo $bill_key ;?>" <?php
														//if(isset($update_inward)){
															//if($update_inward['bill_type']== $bill_key){
																//echo 'selected="selected"';
															//}
														//}

											 ?> ><?php //echo $bill_value; ?></option>
									<?php
								//}
								?>
								</select>
							</div>
							
								<div class="col-md-2 text-right" id="pr_left">Payment Method</div>
							<div class="col-md-4">
							<select class="select2"   style="width: 100%;" name="payment_mod" id="payment_type" >
								<option value="All">-- Select Payment --</Option>
								<option value="cheque">Cheque</Option>
								<option value="cash">Cash</Option>
								
								</select>
							</div>
						</div>
						<div class="form-row">
							<div class="col-md-2 text-right" id="pr_left">Inward Bill No.</div>
							<div class="col-md-4"><input name="bill_no" class="" /></div>
							<div class="col-md-2 text-right" id="pr_left">Invoice No.</div>
							<div class="col-md-4"><input name="invoice_no" class="form-control"></div>
						</div>
						
						<div class="form-row">
							<div class="col-md-2 text-right" id="pr_left">P.O./W.O. No.</div>
							<div class="col-md-4"><input name="powono" class="form-control"></div>
						</div>
						
						
						<div class="form-row">
							<div class="col-md-2 col-md-offset-2">
								<button type="submit" name="search" value="Search" class="btn btn-primary">Search</button>
							</div>
						</div>
					</div>-->
					<?php //echo $this->Form->end();?>	
	<!--				
<input type="hidden" id="i_date_from" value="<?php //echo isset($_POST["date_from"]) ? $_POST["date_from"] : "";?>">
<input type="hidden" id="i_date_to" value="<?php //echo isset($_POST["date_to"]) ? $_POST["date_to"] : "";?>">
<input type="hidden" id="b_date_from" value="<?php //echo isset($_POST["bill_date_from"]) ? $_POST["bill_date_from"] : "";?>">
<input type="hidden" id="b_date_to" value="<?php //echo isset($_POST["bill_date_to"]) ? $_POST["bill_date_to"] : "";?>">
<input type="hidden" id="party" value="<?php //echo isset($_POST["party_id"]) ? $_POST["party_id"] : "";?>">
<input type="hidden" id="pro_id" value="<?php //echo isset($_POST["project_id"]) ? $_POST["project_id"] : "";?>">
<input type="hidden" id="bill_type" value="<?php //echo isset($_POST["bill_type"]) ? $_POST["bill_type"] : "";?>">
<input type="hidden" id="payment" value="<?php //echo isset($_POST["payment_mod"]) ? $_POST["payment_mod"] : "";?>">
<input type="hidden" id="bill_no" value="<?php //echo isset($_POST["bill_no"]) ? $_POST["bill_no"] : "";?>">
<input type="hidden" id="invoice_no" value="<?php //echo isset($_POST["invoice_no"]) ? $_POST["invoice_no"] : "";?>">
<input type="hidden" id="po_wo" value="<?php //echo isset($_POST["powono"]) ? $_POST["powono"] : "";?>">-->
		
		<div class="content list custom-btn-clean" style="overflow-x:scroll">
		<script>
		jQuery(document).ready(function() {
			// var i_date_from  = jQuery("#i_date_from").val();
			// var i_date_to  = jQuery("#i_date_to").val();
			// var b_date_from  = jQuery("#b_date_from").val();
			// var b_date_to  = jQuery("#b_date_to").val();
			// var party  = jQuery("#party").val();
			// var pr_name  = jQuery("#pro_id").val();
			// var bill_type  = jQuery("#bill_type").val();
			// var payment  = jQuery("#payment").val();
			// var bill_no  = jQuery("#bill_no").val();
			// var invoice_no  = jQuery("#invoice_no").val();
			// var po_wo  = jQuery("#po_wo").val();
			
			var selected = [];
		jQuery('#user_list').DataTable({
			"order": [[ 11, "asc" ]],
			responsive: false,
			"processing": true,
			"serverSide": true,
			"ajax": "../Ajaxfunction/pendingbilllistdata",
			// "ajax": {
					// "url": "../Ajaxfunction/pendingbilllistdata",
					// "type": "POST",
					// "data": {
								// "date_from": i_date_from,
								// "date_to": i_date_to,
								// "bill_date_from": b_date_from,
								// "bill_date_to": b_date_to,
								// "party_id": party,
								// "project_id": pr_name,
								// "bill_type": bill_type,
								// "payment_mod": payment,
								// "bill_no": bill_no,
								// "invoice_no": invoice_no,
								// "powono": po_wo
							// }
					// },
			"rowCallback": function( row, data ) {
		
					if ( jQuery.inArray(data.DT_RowId, selected) !== -1 ) {
						jQuery(row).addClass('selected');
					}
				},
			});
		jQuery('.datepick').datepicker({
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
			<table id="user_list" cellspacing="0" class="dataTables_wrapper table table-striped table-hover">
				<thead>
					<tr>
						<th>Inward Date</th>
						<th>Inward Time</th>
						<th>Project Name</th>
						<th>Bill Inward No</th>
						<th>Party's Name</th>						
						<th>Type of Bill</th>
						<th>Invoice No</th>						
						<th>Mode of Payment</th>
						<th>Total Amount</th>
						<th>Bill Date</th>
						<th>Credit Period</th>
						<th>Diff(+/-)</th>
						<th>Qty. Checked By</th>
						<th>Rate Checked By</th>
						<th>Edit/View</th>
						<th>Accept/Paid</th>
					</tr>
				</thead>
				
			</table>
			<script>
				var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
					$(function(){
						
						/* $('.ch_pend').click(function() { */
						/* $('#accept_paid').click(function() { */
						$('body').on("click","#accept_paid",function() {
        					 /* if($(this).is(":checked")) { */

        					 if($(".ch_pend").is(":checked")) {							 
								 
								 if(confirm("Are you sure you want to approve this?"))
								 {
									if(confirm("Are you sure you want to approve this?"))
									{
									/* var tally = prompt("Please Enter Tally Inward No:");									
									 if(tally != "" && tally != " ")
									 { */
										var paid_amount = prompt("Please Enter Cheque Amount:");																				
										if(paid_amount != "" && paid_amount != " " && paid_amount != null)
										{
											var cheque_date = prompt("Please Enter Cheque Date:");																				
										if(cheque_date != "" && cheque_date != " " && cheque_date != null)
										{
											 var cheque_no = prompt("Please Enter Cheque No.:");
											if(cheque_no != "" && cheque_no != " " && cheque_no != null)
												{
													var bank = prompt("Please Enter Bank Name:");
													if(bank != "" && bank != " " && bank != null)
													{
														/* get_id=$(this).attr("dataid"); */
														var get_id = $('.ch_pend:checked').map(function() {
																				return this.attributes.dataid.textContent;
																			}).get();														
														get_id = JSON.stringify(get_id);
														data={i_id:get_id,
															/* tally:tally, */
															paid_amount : paid_amount,
															cheque_date : cheque_date,
															cheque_no:cheque_no,
															bank:bank										
														};			
														
												
														jQuery.ajax({
														headers: {
															'X-CSRF-Token': csrfToken
														},
														type:"POST",
														url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'inwardacceptbillmultiple'));?>", /*inwardacceptbill*/
																	data:data,
																	async:false,
																	success: function(response){	
																		
																	},
																error: function (e) {
																		console.log(e.responseText);
																},
																beforeSend:function(){
																	$(this).hide();
																},
																complete:function(e){
																	console.log(e.responseText);
																	 location.reload(); 
																}
														});	
													}else{
														alert("Please Enter Bank");
													}
												}else{
													alert("Please Enter Cheque No.");
												}
												}else{
									alert("Please Enter Cheque Date");
									}
										}else{
									alert("Please Enter Paid Amount");
									}
									/* }else{
									alert("Please Enter Tally Inward No.");
									} */
									}
        						}
        						}
   						 });

					});
					</script>
		</div>
		<div class="content">
			<div class="col-md-2 pull-right"><a href="javascript:void(0);" class="btn btn-success" id="accept_paid">Accept/Paid</a></div>
		</div>
		
		</div>
	</div>
<!--</div>-->
<?php } ?>
</div>