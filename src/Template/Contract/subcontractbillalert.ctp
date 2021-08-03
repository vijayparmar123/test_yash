<?php
use Cake\Routing\Router;
?>
<div class="col-md-10" >
<?php
// if(!$is_capable)
// {
	// $this->ERPfunction->access_deniedmsg();
// }
// else{
?>              
<div class="col-md-12">
<div class="row">
	
		<div class="block">
			<div class="head bg-default bg-light-rtl">
				<h2>Labour Bill Alert</h2>
				<div class="pull-right">
					<a href="<?php echo $this->ERPfunction->action_link($back_url,$back_page);?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
				</div>
			</div>
			
		<div class="content list custom-btn-clean">
		<script>
		var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;

		jQuery(document).ready(function() {
		jQuery('#sub_contract_list').DataTable({responsive: true});
		
		jQuery("body").on("click", ".first_approve_contract", function(event){
			var id = jQuery(this).val();
				
			if(confirm('Are you Sure approve first step of this Record?'))
			{
				var curr_data = {	 						 					
									id : id,	 					
								};	 				
				 jQuery.ajax({
						headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
						url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'firstapprovesubcontract'));?>",
						data:curr_data,
						async:false,
						success: function(response){					
							 location.reload();
							return false;
						},
						error: function (e) {
							 alert('Error');
						}
				});
			}
			else
			{				
				 jQuery(this).removeAttr('checked');
				 jQuery(this).parent().removeClass('checked');
			}
			});
		
		jQuery("body").on("click", "#approve_contract", function(event){
			var id = jQuery(this).val();
				
			if(confirm('Are you Sure approve this Record?'))
			{
				var curr_data = {	 						 					
									id : id,	 					
								};	 				
				 jQuery.ajax({
						headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
						url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'approvesubcontract'));?>",
						data:curr_data,
						async:false,
						success: function(response){					
							 location.reload();
							return false;
						},
						error: function (e) {
							 alert('Error');
						}
				});
			}
			else
			{				
				 jQuery(this).removeAttr('checked');
				 jQuery(this).parent().removeClass('checked');
			}
			});
		
		} );
</script>
			<table id="sub_contract_list"  class="dataTables_wrapper table table-striped table-hover">
				<thead>
					<tr>
						<th>Bill Date</th>
						<th>Bill No</th>
						<th>Party's Name</th>						
						<th>Type of<br>Bill</th>					
						<th>Type of Work</th>					
						<th>Gross Amount</th>					
						<th>Retention Money</th>					
						<th>Net Amount</th>					
						<th>Action</th>
						<?php 
						if($this->ERPfunction->retrive_accessrights($role,'approve1subcontractbill')==1)
						{
						?>
						<th>Approve</th>
						<?php
						}
						?>
						<?php
						if($this->ERPfunction->retrive_accessrights($role,'approve2subcontractbill')==1)
						{
						?>
						<th>Approve</th>
						<?php
						}
						?>
					</tr>
				</thead>
				<tbody>
					<?php	
						foreach($sub_contract_data as $retrive_data)
						{	
							if($retrive_data['party_type'] == "temp_emp" )
							{
								$partyname = $this->ERPfunction->get_user_name($retrive_data['party_id']);
							}else{
								$partyname = (is_numeric($retrive_data['party_id']))?$this->ERPfunction->get_vendor_name($retrive_data['party_id']):$this->ERPfunction->get_vendor_name_by_code($retrive_data['party_id']);
							}
						?>
							<tr>								
								<td><?php echo date("d-m-Y",strtotime($retrive_data['bill_date']));?></td>
								<td><?php echo $retrive_data['bill_no'];?></td>
								<td><?php echo $partyname;?></td>
								<td><?php echo $retrive_data['type_of_bill'];?></td>
								<td><?php echo $retrive_data['type_of_work'];?></td>
								<td><?php echo $retrive_data['gross_amount'];?></td>
								<td><?php echo $retrive_data['retention_money'];?></td>
								<td><?php echo $retrive_data['net_amount'];?></td>
								
								<td>
								<?php 
								if($this->ERPfunction->retrive_accessrights($role,'subcontractbillalert')==1)
								{
									echo $this->Html->link("<i class='icon-eye-open'></i> View",array('action' => 'previewsubcontract', $retrive_data['id']),
									array('escape'=>false,'target'=>'blank','class'=>'btn btn-info btn-clean'));
									echo ' ';
								}
								if($this->ERPfunction->retrive_accessrights($role,'editsubcontractbill')==1)
								{
									echo $this->Html->link("<i class='icon-pencil'></i> Edit",array('action' => 'editsubcontractbill', $retrive_data['id']),
									array('escape'=>false,'target'=>'blank','class'=>'btn btn-primary btn-clean'));
									echo ' ';
								}
								if($this->ERPfunction->retrive_accessrights($role,'deletesubcontractbill')==1)
								{
									echo $this->Html->link("<i class='icon-pencil'></i> Delete",array('action' => 'deletesubcontractbill', $retrive_data['id']),
									array('escape'=>false,'confirm'=>'Are you sure you want to delete this record?','class'=>'btn btn-danger btn-clean'));
									echo ' ';
								}
								?>
								</td>
								<?php 
								if($this->ERPfunction->retrive_accessrights($role,'approve1subcontractbill')==1)
								{
								?>
								<td>
								<div class='checkbox'>
							<label><input type='checkbox' value='<?php echo $retrive_data['id'];?>' name='first_approve_contract' class="first_approve_contract" id="first_approve_contract"
							<?php echo (($retrive_data['first_approval']==1) ? 'checked' : '');?> <?php echo (($retrive_data['first_approval']==1) ? 'DISABLED' : '');?>
							data-id="<?php echo $retrive_data['id']?>"/></label>
								</div>
								</td>
								<?php } ?>
								
								<?php 
								if($this->ERPfunction->retrive_accessrights($role,'approve2subcontractbill')==1)
								{
								?>
								<td>
								<div class='checkbox'>
							<label><input type='checkbox' value='<?php echo $retrive_data['id']?>' name='approve_contract' class="approve_contract" id="approve_contract" data-id="<?php echo $retrive_data['id']?>" <?php echo ($retrive_data['first_approval']===1 ? '' : 'DISABLED');?>/></label>
								</div>
								</td>
								<?php } ?>
								
								
							</tr>
						<?php
						}
						?>
				</tbody>
			</table>
			
		</div>
		</div>
	</div>
</div>
<?php
 //}
 ?>
</div>
