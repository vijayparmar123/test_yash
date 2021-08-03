<?php
	use Cake\Routing\Router;
	$woApprove1 = $this->ERPfunction->retrive_accessrights($role,'approve1planningwoalert');
	$woApprove2 = $this->ERPfunction->retrive_accessrights($role,'approve2planningwoalert');
?>
<div class="col-md-10" >
<?php
if(!$is_capable)
{
	$this->ERPfunction->access_deniedmsg();
}
else{
?>              
<div class="col-md-12">
<div class="row">
	
		<div class="block">
			<div class="head bg-default bg-light-rtl">
				<h2>W.O. Alert</h2>
				<div class="pull-right">
					<a href="<?php echo $this->ERPfunction->action_link('contract','planningmenu');?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
				</div>
			</div>
			
		<div class="content list custom-btn-clean">
		<script>
		var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;

		jQuery(document).ready(function() {
		jQuery('#wo_list').DataTable({responsive: true});
		
		jQuery("body").on("click", "#first_approve_wo", function(event){
			var wo_id = jQuery(this).val();
				
			if(confirm('Are you Sure approve first step of this W.O.?'))
			{
				var curr_data = {	 						 					
									wo_id : wo_id,	 					
								};	 				
				 jQuery.ajax({
						headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
						url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'firstapproveplanningwo'));?>",
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
			
			jQuery("body").on("click", "#verify_wo", function(event){
			var wo_id = jQuery(this).val();
			if(confirm('Are you sure you want to verify this W.O.?'))
			{
				var curr_data = {	 						 					
									wo_id : wo_id,	 					
								};	 				
				 jQuery.ajax({
						headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
						url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'verifyeplanningwo'));?>",
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
		
		jQuery("body").on("click", "#approve_wo", function(event){
			var wo_id = jQuery(this).val();
				
			if(confirm('Are you Sure approve this W.O.?'))
			{
				var curr_data = {	 						 					
									wo_id : wo_id,	 					
								};	 				
				 jQuery.ajax({
						headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
						url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'approveplanningwo'));?>",
						data:curr_data,
						async:false,
						success: function(response){
								if(response == "email_issue")
								{
									alert("There is a problem with email format");
									location.reload();
									return false;
								}else{
									alert("WO approve successfully.");
									location.reload();
									return false;
								}
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
	<table id="wo_list"  class="dataTables_wrapper table table-striped table-hover">
		<thead>
			<tr>
				<th>Date</th>
				<th>W.O. No</th>
				<th>Project Name</th>
				<th>Party Name</th>						
				<th>Type of<br>Contract</th>					
				<th>Action</th>
				<?php 
				// if($this->ERPfunction->retrive_accessrights($role,'verifyplanningwoalert')==1)
				// {
				// ?>
				<!--<th>Verify</th>-->
				// <?php
				// }
				// if($this->ERPfunction->retrive_accessrights($role,'approve1planningwoalert')==1)
				// {
				?>
				<th>Approve</th>
				<?php
				// }
				// if($this->ERPfunction->retrive_accessrights($role,'approve2planningwoalert')==1)
				// {
				?>
				<th>Final Approve</th>
				<?php
				// }
				?>
			</tr>
		</thead>
		<tbody>
			<?php	
				foreach($wo_date as $retrive_data)
				{	
				$first_step = $this->ERPfunction->get_planningwo_firststep_approve_value($retrive_data['wo_id']);
				$verified = $this->ERPfunction->get_planningwo_verified_value($retrive_data['wo_id']);
				?>
					<tr>								
						<td><?php echo date("d-m-Y",strtotime($retrive_data['wo_date']));?></td>
						<td><?php echo $retrive_data['wo_no'];?></td>	
						<td><?php echo $this->ERPfunction->get_projectname($retrive_data['project_id']);?></td>
						<?php if(is_numeric($retrive_data['party_userid'])){ ?>
						<td><?php echo $this->ERPfunction->get_vendor_name($retrive_data['party_userid']);?></td>
						<?php } else { ?>
						<td><?php echo $this->ERPfunction->get_agency_name_by_code($retrive_data['party_userid']);?></td>
						<?php } ?>
						<td><?php echo $this->ERPfunction->get_contract_title($retrive_data['contract_type']);?></td>
						
						<td>
						<?php 
						if($this->ERPfunction->retrive_accessrights($role,'planningapprovewo')==1)
						{
							echo $this->Html->link("<i class='icon-eye-open'></i> View",array('action' => 'previewplanningwo', $retrive_data['wo_id']),
							array('escape'=>false,'target'=>'blank','class'=>'btn btn-info btn-clean'));
							echo ' ';
						}
						if($this->ERPfunction->retrive_accessrights($role,'editplanningwo')==1)
						{
							echo $this->Html->link("<i class='icon-pencil'></i> Edit",array('action' => 'editplanningwo', $retrive_data['wo_id']),
							array('escape'=>false,'class'=>'btn btn-primary btn-clean'));
							echo ' ';
						}
						if($this->ERPfunction->retrive_accessrights($role,'deleteplanningwo')==1)
						{
							echo $this->Html->link("<i class='icon-pencil'></i> Delete",array('action' => 'deleteplanningwo', $retrive_data['wo_id']),
							array('escape'=>false,'class'=>'btn btn-danger btn-clean'));
							echo ' ';
						}
						?>
						</td>
						<?php
						// if($this->ERPfunction->retrive_accessrights($role,'verifyplanningwoalert')==1)
						// {
						?>
						<!--<td>
						<div class='checkbox'>
							<label><input type='checkbox' value='<?php //echo $retrive_data['wo_id']?>' name='verify_wo' class="verify_wo" id="verify_wo" data-id="<?php echo $retrive_data['wo_id']?>" <?php echo (($verified==1) ? 'checked' : '');?> <?php echo (($verified==1) ? 'DISABLED' : '');?>/></label>
						</div>
						</td>-->
						<?php //} ?>
						<td>
							<?php 
								if($this->ERPfunction->retrive_accessrights($role,'approve1planningwoalert')==1) {
							?>
							<div class='checkbox'>
								<label><input type='checkbox' value='<?php echo $retrive_data['wo_id'];?>' name='first_approve_wo' class="first_approve_wo" id="first_approve_wo"
								<?php echo (($first_step==1) ? 'checked' : '');?> <?php echo (($first_step==1) ? 'DISABLED' : '');?> data-id="<?php echo $retrive_data['wo_id']?>"/></label>
							</div>	
							<?php
								}else { 
							?>
							<div class='checkbox'>
								<label><input type='checkbox' disabled value='<?php echo $retrive_data['wo_id'];?>' name='first_approve_wo' class="first_approve_wo" id="first_approve_wo"
								<?php echo (($first_step==1) ? 'checked' : '');?> <?php echo (($first_step==1) ? 'DISABLED' : '');?> data-id="<?php echo $retrive_data['wo_id']?>"/></label>
							</div>
							<?php
								}
							?>
						</td>
						<td>
							<?php
								if($this->ERPfunction->retrive_accessrights($role,'approve2planningwoalert')==1) {
							?>
							
							<div class='checkbox'>
								<label><input type='checkbox' value='<?php echo $retrive_data['wo_id']?>' name='approve_wo' class="approve_wo" id="approve_wo" data-id="<?php echo $retrive_data['wo_id']?>" <?php echo ($first_step===1 ? '' : 'DISABLED');?>/></label>
							</div>
							
							<?php }else { ?>
								<div class='checkbox'>
								<label><input type='checkbox' disabled value='<?php echo $retrive_data['wo_id']?>' name='approve_wo' class="approve_wo" id="approve_wo" data-id="<?php echo $retrive_data['wo_id']?>" <?php echo ($first_step===1 ? '' : 'DISABLED');?>/></label>
							</div>
						<?php } ?>
						</td>
						</td>
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
<?php } ?>
</div>
