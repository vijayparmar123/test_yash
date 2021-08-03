<?php
//$this->extend('/Common/menu')
?>
<div class="col-md-10" >

<div class="col-md-12" >	
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
				<h2>Work Head List</h2>
				<div class="pull-right">
				<a href="<?php echo $this->ERPfunction->action_link('Purchase','index');?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
				</div>
			</div>
		
		<div class="content list custom-btn-clean">
		<script>
		jQuery(document).ready(function() {
		jQuery('#user_list').DataTable({responsive: true,		 
				"aoColumns":[
					{"bSortable": true,sWidth:"20%"},
					{"bSortable": true,sWidth:"20%"},
					{"bSortable": true,sWidth:"20%"},					
					{"bSortable": false,sWidth:"5%"}]
			});
		} );
</script>
			<table id="user_list"  class="dataTables_wrapper table table-striped table-hover">
				<thead>
					<tr>
						<th>Code</th>
						<th>Type of Contract</th>
						<th>Work Head</th>
						<th>Edit / View</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$i = 1;
						foreach($head_list as $retrive_data)
						{
						?>
							<tr>
								<td><?php echo $retrive_data['work_head_code'];?></td>
								<td><?php echo $this->ERPfunction->get_contract_title($retrive_data['type_of_contract']);?></td>
								<td><?php echo $retrive_data['work_head_title']; ?></td>
								
								<td>
								<?php 
								if($this->ERPfunction->retrive_accessrights($role,'workheadlist')==1)
								{
									echo $this->Html->link("<i class='icon-eye-open'></i> View",array('action' => 'viewworkhead', $retrive_data['work_head_id']),
									array('escape'=>false,'class'=>'btn btn-info btn-clean'));
									echo ' ';
								}
								if($this->ERPfunction->retrive_accessrights($role,'editworkhead')==1)
								{
									echo $this->Html->link("<i class='icon-pencil'></i> Edit",array('action' => 'editworkhead', $retrive_data['work_head_id']),
									array('escape'=>false,'class'=>'btn btn-primary btn-clean'));
								}
								?>
								</td>
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