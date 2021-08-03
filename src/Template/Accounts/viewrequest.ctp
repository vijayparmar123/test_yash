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
 
<script type="text/javascript">
var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
 jQuery(document).ready(function() {
	// jQuery("body").on("change","#approv_cm",function(){
	
	// var status = $(this).val();
		// if(status == 'approve')
		// {
				// if(confirm("Are you sure,you want to Unapprove this record?"))
				// {
					//approve = true;
					// var id = $(this).attr("data_id");
					// var curr_data = {id:id};
					// $.ajax({
						// type : "POST",
						 // url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'unapprovebycm'));?>",
						// data:curr_data,
						// async:false,
						// success : function(result)
							// {
								
							// },
						// error : function(e)
							// {
								
								// console.log(e.responseText);
							// }
					// });
					
				// }			
		// }
		// else
		// {
					//approve = true;
					// var id = $(this).attr("data_id");
					// var curr_data = {id:id};
					// $.ajax({
						// type : "POST",
						 // url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'approvebycm'));?>",
						// data:curr_data,
						// async:false,
						// success : function(result)
							// {
								
							// },
						// error : function(e)
							// {
								
								// console.log(e.responseText);
							// }
					// });
		// }
		
		
	// });
	
	
	jQuery("body").on("change","#approv_pd",function(){
	
		//var approve = false;
		if(confirm("Are you sure,you want to approve this record?"))
		{
			if(confirm("Are you sure,you want to approve this record?"))
			{
				if(confirm("Are you sure,you want to approve this record?"))
				{
					//approve = true;
					var id = $(this).attr("data_id");
					var curr_data = {id:id};
					$.ajax({
						type : "POST",
						 url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'approvebypd'));?>",
						data:curr_data,
						async:false,
						success : function(result)
							{
								
							},
						error : function(e)
							{
								
								console.log(e.responseText);
							}
					});
					
				}			
			}
		}
		
	});
	jQuery(document).ready(function() {
			
			
			// jQuery('#load_modal').on('hidden', function () {
			  // $(this).removeData('modal');
			// });
			
			/* jQuery('.viewmodal').click(function(){			 */
			jQuery('body').on('click','.viewmodal',function(){
			
				if($(".ch_pend").is(":checked")) {
				
				request_id=jQuery('.ch_pend:checked').map(function() {	return this.attributes.data_id.textContent;
																			}).get();
				advance_rs=jQuery('.ch_pend:checked').map(function() {	return this.attributes.advance.textContent;
																			}).get();
				request_id = JSON.stringify(request_id);
				
				advance_rs = JSON.stringify(advance_rs);
				//quantity=jQuery(this).attr('quantity');
				
				jQuery('#modal-view').html();
				var model  = jQuery(this).attr('data-type') ;
				//var asset_id  = jQuery(this).attr('asset_id') ;
				
					urlstring ="<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'advancetransfer'));?>";
				
				var curr_data = {request_id:request_id, advance_rs:advance_rs };	 				
					jQuery.ajax({
						headers: {
					'X-CSRF-Token': csrfToken
				},
                type:"POST",
						url:urlstring,
						data:curr_data,
						async:false,
						success: function(response){                    
							jQuery('.modal-content').html(response);					
						},
						beforeSend:function(){
									jQuery('#modal-view').html('<center><img src=../images/4.gif width=120px><div><h3>Loading...</h3></div></center>');
								},
						error: function(e) {
								console.log(e.responseText);
								 }
					});	
				}					
			});
			
			jQuery('body').on('click','.multiple_approve',function(){
			
				if($(".approv_cmpd").is(":checked")) {
				request_id=jQuery('.approv_cmpd:checked').map(function() {	return this.attributes.data_id.textContent;
																			}).get();
				
				request_id = JSON.stringify(request_id);
				
				
				//quantity=jQuery(this).attr('quantity');
				
				//jQuery('#modal-view').html();
				//var model  = jQuery(this).attr('data-type') ;
				//var asset_id  = jQuery(this).attr('asset_id') ;
				
					urlstring ="<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'multipleapprovecmpd'));?>";
				
				var curr_data = {request_id:request_id };	 				
					jQuery.ajax({
						headers: {
					'X-CSRF-Token': csrfToken
				},
                type:"POST",
						url:urlstring,
						data:curr_data,
						async:false,
						success: function(response){                    
												 window.location.reload();
						},
						error: function(e) {
								console.log(e.responseText);
								 }
					});	
				}					
			});
		} );
	});
</script>
<?php 
if(!$is_capable)
	{
		$this->ERPfunction->access_deniedmsg();
	}
else
{
?>
<div class="row">
	<div class="col-md-12">
		<div class="block">
		<div class="head bg-default bg-light-rtl">
			<h2>Advance Alert</h2>
			<div class="pull-right">
			<a href="<?php echo $this->ERPfunction->action_link('Accounts','index');?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
			</div>
		</div>
		
			
					
		<div class="content list custom-btn-clean">
		<script>
		jQuery(document).ready(function() {
		jQuery('#request_list').DataTable({"aaSorting": [[ 4, "asc" ]]});
		} );
</script>
			<table id="request_list"  class="dataTables_wrapper table table-striped table-hover">
				<thead>
					<tr>
						<th>Project Name</th>
						<th>Date</th>
						<th>Time</th>
						<th>Vendor ID</th>
						<th>Vendor's Name</th>
						<th>Transfer Type</th>
						<th>No of Labours on Site</th>
						<th>Advance (Rs.)</th>
						<?php
						if($this->ERPfunction->retrive_accessrights($role,'viewrequest')==1)
						{
						?>
						<th>Edit / Delete</th>
						<?php
						 }
						if($this->ERPfunction->retrive_accessrights($role,'approve_advance_cm_md_pd')==1)
						{
						?>
						<th>Approval by C.M. or P.D</th>
						<?php
						}
						// if($role == 'erphead' || $role == 'ceo' || $role == 'md' || $role == 'projectdirector' || $role == 'accounthead' || $role == 'senioraccountant' || $role == 'constructionmanager')
						// {
						?>
						<!-- <th>Approval by P.D.</th> -->
						<?php 
						// }
						?>
						<?php
						if($this->ERPfunction->retrive_accessrights($role,'approve_advance_sr_ac')==1)
						{
						?>
						<th>Approval & Export by Sr. A/C or A/C Head</th> 
						<?php
						}
						?>
						
					</tr>
				</thead>
				<tbody>
					<?php
						
						foreach($request_list as $request)
						{
							$date =  date('Y-m-d');
							// $pending_days = date('Y-m-d', strtotime( $date. " + {$inward_bill_row['credit_period']} days"));
							// $now = time();
							// $datediff = $now - $pending_days;
							// $daysRemaining = floor($datediff/(60*60*24));
							// echo $daysRemaining;
							
							// $pending_days = date('Y-m-d', strtotime( $date. " + {$inward_bill_row['credit_period']} days"));
							// $datediff = $inward_bill_row["bill_date"]->format("Y-m-d") - $date;
							// $days_diff = floor($datediff/(60*60*24));
							
							// $date1 = new DateTime($inward_bill_row["bill_date"]->format("Y-m-d"));
							// $date2 = new DateTime($date);
							// $diff = $date2->diff($date1)->format("%r%a");
							// $warning = false;
							// $rem = $diff + intval($inward_bill_row["credit_period"]);
							// $style = "";
							// if($rem < 0)							
							// {
								// $warning = true;																
							// }
							
						?>
							<tr <?php echo (@$warning)?"class='show_warning'":"";?>>
								
								<Td><?php echo $this->ERPfunction->get_projectname($request['project_id']); ?></td>
								<td><?php echo date('d-m-Y', strtotime($request['date']));?></td>
								<td><?php echo date('H:i', strtotime($request['time'])); ?></td>
								<td><?php echo $request['agency_id'];?></td>
								<td><?php echo $this->ERPfunction->get_vendor_name($request['agency_id']);?></td>
								<td><?php echo $this->ERPfunction->get_agency_transfer_type($request['agency_id']);?></td>
                                <td><?php echo $request['labor'];?></td>
                                <td><?php echo $request['advance_rs'];?></td>
								
                                <td><?php 
								if($this->ERPfunction->retrive_accessrights($role,'editrequest')==1)
								{
								echo $this->Html->link("<i class='icon-pencil'></i> Edit",array('action' => 'editrequest', $request['request_id']),
									array('class'=>'btn btn-primary btn-clean action-btn','escape'=>false));
								}
								if($this->ERPfunction->retrive_accessrights($role,'viewrequest')==1)
								{
								echo $this->Html->link("<i class='icon-pencil'></i> View",array('action' => 'requestview', $request['request_id'],'viewrequest'),
									array('class'=>'btn btn-primary btn-clean action-btn','escape'=>false));
								}
								echo ' ';
								if($this->ERPfunction->retrive_accessrights($role,'deleterequest')==1)
								{
								echo $this->Html->link('<i class="icon-trash"></i> Remove',array('action' => 'deleterequest', $request['id']),
								array('class'=>'btn btn-danger btn-clean action-btn','escape'=> false,
								'confirm' => 'Are you sure you wish to remove this Record?'));
								}	
								?></td>
								
								<?php
								if($this->ERPfunction->retrive_accessrights($role,'approve_advance_cm_md_pd')==1)
								{
								?>
                               <td align="center"><input type="checkbox" name="approv_cmpd[]" class="approv_cmpd" id="approv_cmpd" value=""  data_id="<?php echo $request['cmpd_approval'] == 1 ? '' : $request['id']; ?>" <?php echo ($request['cmpd_approval']==1 ? 'checked' : '');?> <?php echo ($request['cmpd_approval']==1 ? 'DISABLED' : '');?> ></td>
								<?php
									}
									// if($role == 'erphead' || $role == 'ceo' || $role == 'md' || $role == 'projectdirector' || $role == 'accounthead' || $role == 'senioraccountant' || $role == 'constructionmanager')
									// {
								?>
								<!-- <td align="center"><input type="checkbox" name="ch_pend[]" id="approv_pd" value=""   data_id="<?php //echo $request['id']; ?>" <?php //echo ($request['approval_by_pd']==1 ? 'checked' : '' );?> <?php //echo ($request['approval_by_pd']==1 ? 'DISABLED' : '' );?>></td> -->
								<?php
									// }
								?>
								

								
								
								<?php
								if($this->ERPfunction->retrive_accessrights($role,'approve_advance_sr_ac')==1)
								{
								?>
								<td align="center"><input type="checkbox" name="ch_pend[]" id="approval_export" value="" class="ch_pend" advance="<?php echo $request['advance_rs'];  ?>" data_id="<?php echo $request['approval_export'] == 1 ? '' : $request['id']; ?>"  <?php echo ($request['approval_export']==1 ? 'checked' : '');?> <?php echo ($request['approval_export']==1 ? 'DISABLED' : '');?> <?php echo ($request['cmpd_approval']==1 ? '' : 'DISABLED');?>></td>
								<?php 
								}
								?>
							</tr>
						<?php
						}
					?>
					<script>
					$(function(){
						
						/* $('.ch_pend').click(function() { */
						$('#abc').click(function() {
        					 /* if($(this).is(":checked")) { */
        					 if($(".ch_pend").is(":checked")) {							 
								 
								 if(confirm("Are you sure you want to approve this?"))
								 {
									if(confirm("Are you sure you want to approve this?"))
									{
									/* var tally = prompt("Please Enter Tally Inward No:");									
									 if(tally != "" && tally != " ")
									 { */
									 var transfer_type = prompt("Please Enter Transfer Type:");																				
										if(transfer_type != "" && transfer_type != " " && transfer_type != null)
										{
										var paid_amount = prompt("Please Enter Cheque Amount:");																				
										if(paid_amount != "" && paid_amount != " " && paid_amount != null)
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
																			}).get()
														get_id = JSON.stringify(get_id);
														data={i_id:get_id,
															/* tally:tally, */
															paid_amount : paid_amount,												
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
																},
																beforeSend:function(){
																	$(this).hide();
																},
																complete:function(e){
																	console.log(e.responseText);
																	// location.reload();
																}
														});	
													}else{
														alert("Please Enter Bank");
													}
												}else{
													alert("Please Enter Cheque No.");
												}									
										}else{
									alert("Please Enter Paid Amount");
									}
									}else{
									alert("Please Select Transfer Type");
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
				</tbody>
			</table>
		</div>
		<div class="content">
			<div class="col-md-2 pull-right">
			<?php 
				if($this->ERPfunction->retrive_accessrights($role,'approve_advance_sr_ac')==1)
				{
			?>
			<button type="button" data-toggle="modal" 
								data-target="#load_modal" class="btn btn-success viewmodal">Accept/Paid </button>
								<?php } ?>
			</div>
			<div class="col-md-2 pull-right">
			<?php 
				if($this->ERPfunction->retrive_accessrights($role,'approve_advance_cm_md_pd')==1)
				{
			?>
			<button type="button" class="btn btn-success multiple_approve">Approve </button>
								<?php } ?>
			</div>
		</div>
		
		</div>
	</div>
</div>
<?php
  } 
 ?>
</div>
<div class="modal fade " id="load_modal" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content"></div>
    </div>
	<?php } ?>
</div>