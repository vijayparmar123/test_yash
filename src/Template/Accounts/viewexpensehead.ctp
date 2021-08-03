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
 <style>
	.action-btn
	{
		padding:6px 30px;
	}
 </style>
<?php 
// if(!$is_capable)
	// {
		// $this->ERPfunction->access_deniedmsg();
	// }
// else
// {
?>
<div class="row">
	<div class="col-md-12">
		<div class="block">
		<div class="head bg-default bg-light-rtl">
			<h2>Advance Request Alert</h2>
			<div class="pull-right">
			<a href="<?php echo $this->ERPfunction->action_link('Accounts','index');?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
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
						<th>Expense Head</th>
						<th>Expense Head Type</th>
						<?php
						if($this->ERPfunction->retrive_accessrights($role,'editexpensehead')==1)
						{ ?>
						<th>Edit</th>
						<?php } ?>
					</tr>
				</thead>
				<tbody>
					<?php
						
						foreach($expense_list as $request)
						{
							$date =  date('Y-m-d');
						?>
							<tr <?php echo (@$warning)?"class='show_warning'":"";?>>
								<Td><?php echo $request['expence_head_name']; ?></td>
                                <td><?php echo $request['expence_type'];?></td>
								
								
								<?php 
								if($this->ERPfunction->retrive_accessrights($role,'editexpensehead')==1)
								{ 
									echo "<td>";
									echo $this->Html->link("<i class='icon-pencil'></i> Edit",array('action' => 'editexpensehead', $request['expence_id']),
									array('class'=>'btn btn-primary btn-clean action-btn','target'=>'blank','escape'=>false));
									echo "</td>";
								}
								?>
							
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
 // } 
 ?>
</div>
<div class="modal fade " id="load_modal" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content"></div>
    </div>
	<?php } ?>
</div>