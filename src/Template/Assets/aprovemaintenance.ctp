<?php
//$this->extend('/Common/menu')
use Cake\Routing\Router;
?>
<script type="text/javascript" >
	var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
	jQuery(document).ready(function() {
		jQuery('#maintenace_list').DataTable({responsive: true}); 
		jQuery("body").on("change", ".approve", function(event){
			var maintenace_id = jQuery(this).val();
			var expense_mode = jQuery("#mode_of_expense"+maintenace_id).val();			
			if(confirm('Are you Sure approve this Asset Maintenance Expense ?')) {
				if(confirm('Are you Sure approve this Asset Maintenance Expense ?')) {	
					var curr_data = {	 						 					
						maintenace_id : maintenace_id,	 					
						expense_mode : expense_mode,	 					
					}; 				
					jQuery.ajax({
						headers: {
							'X-CSRF-Token': csrfToken
						},
						type:"POST",
						url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'approvemaexpenses'));?>",
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
				}else {
					jQuery(this).removeAttr('checked');
					jQuery(this).parent().removeClass('checked');
						
				}
			}else {
				jQuery(this).removeAttr('checked');
				jQuery(this).parent().removeClass('checked');
					
			}
		});	
	});
</script>
 
<div class="col-md-10" >
<?php
if(!$is_capable)
{
	$this->ERPfunction->access_deniedmsg();
}
else{
?>    
<div class="row">
	<div class="col-md-12">
		<div class="block" style="width:auto;">
		<div class="head bg-default bg-light-rtl">
			<h2>Asset Maintenance Alert </h2>
			<div class="pull-right">
			<a href="<?php echo $this->ERPfunction->action_link('Assets','index');?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
			</div>
		</div>
		
		<div class="content">
		<div class="col-md-12 filter-form">
		<?php 
		$project_id = array();
		$project_id = isset($_POST['project_id'])?$_POST['project_id']:'';
		?>
		<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
			
			<div class="form-row">	
				<div class="col-md-2">Project:</div>
				<div class="col-md-4">
					<select class="select2" style="width: 100%;" name="project_id[]" id="project_id" multiple="multiple">
						<option value="All">All</Option>
						<?php 
							foreach($projects as $retrive_data)
							{
								$selected = (in_array($retrive_data['project_id'],$project_id)) ? "selected" : "";
								echo '<option value="'.$retrive_data['project_id'].'" '. $selected .'>'.$retrive_data['project_name'].'</option>';
							}
						?>
					</select>
				</div>
				<div class="col-md-2"> <div class="col-md-12"><input type="submit" name="go" id="go" class="btn btn-primary" value="Search"/></div></div>
			</div>
			
			<div class="form-row">
				
			</div>

		<?php echo $this->Form->end(); ?>
		</div>
	</div>
	
		<div class="content list custom-btn-clean">


			<table id="maintenace_list"  class="dataTables_wrapper table table-striped table-hover">
				<thead>
					<tr>
					<!-- <th>Project Code</th> -->
						<th>Project Name</th>
						<th>Date</th>
						<th>A.M.O No.</th>
						<th>Asset Group</th>
						<th>Asset ID</th>
						<th>Asset Name</th>
						<th>Capacity</th>
						<th>Identity<br>/Vehi.No.</th>						
						<th>Maintenance Type</th>						
						<th>Amount of Expense </th>						
						<th>Payment</th>
						<?php
						if($this->ERPfunction->retrive_accessrights($role,'deletemaintenance')==1 || $this->ERPfunction->retrive_accessrights($role,'addmaintenance')==1 || $this->ERPfunction->retrive_accessrights($role,'viewaddmaintenance')==1)
						{
						?>
						<th>Action</th>
						<?php
						}
						 if($this->ERPfunction->retrive_accessrights($role,'aprroveassetmaintence')==1)
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
						$i = 1;
						foreach($maintenace_list as $retrive_data)
						{
						?>
							<tr>
								<!--<td><?php echo $this->ERPfunction->get_projectcode($retrive_data["project_id"]); ?></td>-->
								<td><?php echo $this->ERPfunction->get_projectname($retrive_data["project_id"]); ?></td>
								<td><?php 
									 echo $this->ERPfunction->get_date($retrive_data['maintenance_date']);
									 ?>   
								</td>
								<td><?php echo $retrive_data['amo_no']; ?></td>
								<td><?php echo $this->ERPfunction->get_asset_group_name($retrive_data['asset_group']); ?></td>
								<td><?php echo $this->ERPfunction->get_asset_code($retrive_data['asset_id']);?></td>
								<td><?php echo $this->ERPfunction->get_asset_name($retrive_data["asset_id"]);?></td>
								<td><?php echo $this->ERPfunction->get_asset_capacity($retrive_data["asset_id"]);?></td>
								<td><?php echo $retrive_data["vehicle_no"];?></td>
								<td><?php echo ($retrive_data["maintenance_type"] == 1)?"Corrective / Breakdown":"Preventive / Routine";?></td>
								<td><?php echo $retrive_data['expense_amount']; ?></td>								
								<td><?php echo ($retrive_data['payment_by']==1)?"Cash":"Cheque"; ?></td><?php
								
								if($this->ERPfunction->retrive_accessrights($role,'deletemaintenance')==1 || $this->ERPfunction->retrive_accessrights($role,'addmaintenance')==1 || $this->ERPfunction->retrive_accessrights($role,'viewaddmaintenance')==1)
								{
								?>								
								<td>
								<?php 
								 if($this->ERPfunction->retrive_accessrights($role,'viewaddmaintenance')==1)
								{
								echo $this->Html->link("<i class='icon-eye-open'></i> View",array('action' => 'viewaddmaintenance',$retrive_data['maintenace_id']),
								array('class'=>'btn btn-primary btn-clean','escape'=>false));
								}
								if($this->ERPfunction->retrive_accessrights($role,'addmaintenance')==1)
								{
								echo $this->Html->link("<i class='icon-pencil'></i> Edit",array('action' => 'addmaintenance',$retrive_data['maintenace_id']),
								array('class'=>'btn btn-primary btn-clean','escape'=>false));
								}
								echo ' ';
								if($this->ERPfunction->retrive_accessrights($role,'deletemaintenance')==1)
								{
								echo $this->Html->link("<i class='icon-trash'></i> Delete",array('action' => 'deletemaintenance', $retrive_data['maintenace_id']),
								array('class'=>'btn btn-danger btn-clean','escape'=>false, 
								'confirm' => 'Are you sure you wish to Delete this Record?'));
								}
								echo ' ';
								?>
								</td>
								<?php
								}
								 if($this->ERPfunction->retrive_accessrights($role,'aprroveassetmaintence')==1)
								{
								?>
								<td> 
									<div class="checkbox">
									<?php 
									if($role =='erphead' || $role =='erpmanager' || $role =='erpoperator' || $role =='ceo' || $role == 'md' || $role == 'projectdirector' || $role == 'pmm' || $role == 'constructionmanager' || $role == 'billingengineer')
									{
									?>
										<label><input type="checkbox" class="approve" 
										value="<?php echo $retrive_data['maintenace_id'];?>" name="Approve"/> </label>
									<?php } ?>
									</div>
								</td>	
								<?php
								}
								?>
							</tr>
						<?php
						$i++;
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