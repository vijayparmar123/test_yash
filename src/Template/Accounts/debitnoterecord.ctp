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
	jQuery("body").on("change","#approv_cm",function(){
	
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
						 url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'expenceapprovebycm'));?>",
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
						 url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'expenceapprovebypd'));?>",
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
	
	jQuery("body").on("change","#approv_accountant",function(){
	
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
						 url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'expenceapprovebyaccountant'));?>",
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
				request_id = JSON.stringify(request_id);
				
				//quantity=jQuery(this).attr('quantity');
				
				jQuery('#modal-view').html();
				var model  = jQuery(this).attr('data-type') ;
				//var asset_id  = jQuery(this).attr('asset_id') ;
				
					urlstring ="<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'advancetransfer'));?>";
				
				var curr_data = {request_id:request_id};	 				
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
		} );
		
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
					// jQuery('#prno').val(json_obj['prno']);
					// jQuery('#voucher_no').val(json_obj['prno']);
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
	
	jQuery('body').on('click','.cmpdmd_approve',function(){
			
				if($(".cmpdmd").is(":checked")) {
				request_id=jQuery('.cmpdmd:checked').map(function() {	return this.attributes.data_id.textContent;
																			}).get();
				request_id = JSON.stringify(request_id);
				
				
				urlstring ="<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'cmpdmdapprovedebit'));?>";
				
				var curr_data = {request_id:request_id};	 				
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
			
			jQuery('body').on('click','.accountant_approve',function(){
			
				if($(".account").is(":checked")) {
				request_id=jQuery('.account:checked').map(function() {	return this.attributes.data_id.textContent;
																			}).get();
				request_id = JSON.stringify(request_id);
				
				urlstring ="<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'accountantapprovedebit'));?>";
				
				var curr_data = {request_id:request_id};	 				
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
		
	});
</script>
<div class="row">
	<div class="col-md-12">
		<div class="block">
		<div class="head bg-default bg-light-rtl">
			<h2>DEBIT NOTE RECORDS</h2>
			<div class="pull-right">
			<a href="<?php echo $this->ERPfunction->action_link('Accounts','index');?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
			</div>
		</div>
		
			<div class="content">
		<div class="col-md-12 filter-form">
			<?php 
				$project_id = array();
				$agency_id = array();
				$adv_r_no = array();
				 $project_id = isset($_POST['project_id'])?$_POST['project_id']:'';
				 $from_date = isset($_POST['from_date'])?$_POST['from_date']:'';
				 $to_date = isset($_POST['to_date'])?$_POST['to_date']:'';
				 $agency_id = isset($_POST['id'])?$_POST['id']:'';
				 $adv_r_no = isset($_POST['advance_req_no'])?$_POST['advance_req_no']:'';
			?>
		<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
					
						
					<div class="form-row">
							<div class="col-md-2">Project Name</div>
                            <div class="col-md-4">
								<select class="select2" style="width: 100%;" name="project_id[]" id="project_id">
								<option value="All">All</Option>
								<?php 
									foreach($projects as $retrive_data)
									{
										echo '<option value="'.$retrive_data['project_id'].'">'.
										$retrive_data['project_name'].'</option>';
									}
								?>
								</select>
							</div>
							
							<div class="col-md-2">Party Name</div>
                            <div class="col-md-4">
								<select class="select2" style="width: 100%;" name="party_id" id="party_id">
								<option value="All">All</Option>
								<?php
                            			if($vendor_info){
                            				foreach($vendor_info as $vendor_row){
                            					?>
													<option value="<?php echo $vendor_row['user_id']; ?>" dataid="vendor" <?php 
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
												<!-- <option value="<?php// echo $agency['agency_id']; ?>" dataid="agency"  -->
												<?php 
													// if(isset($update_inward)){
													// 	if($update_inward['party_name'] == $agency['agency_id']){
													// 		echo 'selected="selected"';
													// 	}
													// }
												?> 
												<!-- > -->
												<?php// echo $agency['agency_name'];?></option>
											<?php	
										// 	}
										// }
                            		?>
								</select>
							</div>
                    </div>
                    	
					<div class="form-row">
						<div class="col-md-2"> <div class="col-md-12"><input type="submit" name="go" id="go" class="btn btn-primary" value="Search"/></div></div>
					</div>
		
		<?php echo $this->Form->end(); ?>
			</div>
			</div>
					
		<div class="content list custom-btn-clean">
		<script>
		jQuery(document).ready(function() {
		jQuery('#request_list').DataTable({responsive: true});
		} );
</script>

			<table id="request_list"  class="dataTables_wrapper table table-striped table-hover">
				<thead>
					<tr>
						<th>Debit Note No</th>
						<th>Date</th>
						<th>Receiver Party Name</th>
						<th>Given To</th>
						<th>Total Amount of Debit(Rs)</th>
						<th>Attachment</th>
						<?php
						if($this->ERPfunction->retrive_accessrights($role,'debitnoterecord')==1)
						{
						?>
						<th>View / Delete</th>
						 <?php } ?>
						
					</tr>
				</thead>
				<tbody>
					<?php

						if(!empty($debit_list))
						{
						$rows = array();
						$rows[] = array("Debit Note No","Date","Receiver Party Name","Given To","Total Amount of Debit(Rs)","Attachment");
					
						foreach($debit_list as $debit)
						{
							$is_approve = $this->ERPfunction->check_debit_approve($debit['debit_id']);
							if($is_approve != 0)
							{
							$csv = array();
							$date =  date('Y-m-d');
														
						?>
							<tr>
								<Td><?php echo ($csv[] = $debit['debit_note_no']); ?></td>
								<td><?php echo ($csv[] = date('d-m-Y', strtotime($debit['date'])));?></td>
								<?php if(is_numeric($debit['debit_to'])){ ?>
								<td><?php echo ($csv[] = $this->ERPfunction->get_vendor_name($debit['debit_to']));?></td>
								<?php } else { ?>
								<td><?php echo ($csv[] = $this->ERPfunction->get_agency_name_by_code($debit['debit_to']));?></td>
								<?php } ?>
								<Td><?php echo ($csv[] = $debit['receiver_name']); ?></td>
								<td><?php echo ($csv[] = $this->ERPfunction->get_total_debit($debit['debit_id']));?></td>
								<?php $csv[] = $debit['attachment']; ?>
								<td>
								<?php if($debit['attachment'] != ""){ ?>
								<a href="<?php echo $this->request->base;?>/img/<?php echo $debit['attachment'];?>" download="<?php echo $debit['attachment'];?>" class="btn btn-info btn-clean"><i class="icon-download-alt"></i><?php echo $debit['attachment'];?></a>
								<?php } ?>
								</td>
     
                                <td><?php 
								if($this->ERPfunction->retrive_accessrights($role,'debitnoterecord')==1)
								{
								echo $this->Html->link("<i class='icon-pencil'></i> View",array('action' => 'viewdebit', $debit['debit_id']),
									array('class'=>'btn btn-primary btn-clean action-btn','target'=>'blank','escape'=>false));
								echo ' ';
								}
								if($this->ERPfunction->retrive_accessrights($role,'deletedebitnote')==1)
								{
								echo $this->Html->link('<i class="icon-trash"></i> Remove',array('action' => 'deletedebit', $debit['debit_id']),
								array('class'=>'btn btn-danger btn-clean action-btn','escape'=> false,
								'confirm' => 'Are you sure you wish to remove this Record?'));
								}
								?></td>
								
							</tr>
						<?php
						$rows[] = $csv;
						}
						}
						}
					?>
					
				</tbody>
			</table>
		</div>
		
		</div>
	</div>
</div>
<div class="modal fade " id="load_modal" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content"></div>
    </div>
</div>
<?php
  } 
 ?>
</div>
