<?php
//$this->extend('/Common/menu')
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
<div class="row">
	<div class="col-md-12">
		<div class="block" style="width:auto;">			
			<div class="head bg-default bg-light-rtl">
				<h2>Edit Projects</h2>
				<div class="pull-right">
				<a href="<?php echo $this->ERPfunction->action_link('Projects','index');?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
				</div>
			</div>
		
			
		<?php echo $this->Form->Create('form1',['id'=>'eq_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
			        <div class="content controls">											
						<div class="form-row">
							<div class="col-md-2">Project Code</div>
							<div class="col-md-4"><input name="project_code" class="form-control"></div>
							<div class="col-md-2">Project Name</div>
							<div class="col-md-4">
							<select class="select2" style="width: 100%;" name="project_id[]" id="project_id" multiple="multiple">
								<option value="all">All</Option>
								<?php 
									foreach($projects_list as $retrive_data)
									{?>
										<option value="<?php echo $retrive_data['project_id'];?>" <?php 
											if(isset($update_inward)){
												if($update_inward['project_id'] == $retrive_data['project_id'])
												{
													echo 'selected="selected"';
												}
			
											}?> >
											<?php echo $retrive_data['project_name']; ?> </option>
										<?php										
									}
								?>
								</select>
							</div>
						</div>
						<div class="form-row">
							<div class="col-md-2">Client Name</div>
							<div class="col-md-4"><input name="client_name" class="form-control"></div>			
							<div class="col-md-2">Project Status</div>
							<div class="col-md-4"><input name="project_status" class="form-control"></div>				
						</div>
						<div class="form-row">
							<div class="col-md-2">State</div>
							<div class="col-md-4"><input name="state" class="form-control"></div>			
							<div class="col-md-2 col-md-offset-2">
									<button type="submit" name="search" value="Search" class="btn btn-primary">Search</button>
							</div>
						</div>
					</div>
					<?php echo $this->Form->end();?>		
		
		<div class="content list custom-btn-clean">
		<script>
		jQuery(document).ready(function() {	
			jQuery('#user_list').DataTable({responsive: true});
		} );
</script>
			<table id="user_list"  class="dataTables_wrapper table table-striped table-hover">
				<thead>
					<tr>
						<th><?php echo __('Project Code');?></th>
						<th><?php echo __('Project Name');?></th>
						<th><?php echo __('Project Status');?></th>
						<th><?php echo __('Client\'s Name');?></th>
						<th><?php echo __('Contract Amount');?></th>			
						<th><?php echo __('Contract Start Date');?></th>			
						<th><?php echo __('Contract End Date');?></th>			
						<th><?php echo __('Revised Amount');?></th>			
						<th><?php echo __('Revised Completion Date');?></th>			
						<th><?php echo __('Total  of RA  Bill (Rs.)');?></th>			
						<th><?php echo __('Total Price Variation (Rs.)');?></th>			
						<th><?php echo __('Total Work Done (Rs.)');?></th>			
						<th><?php echo __('% Work Done');?></th>			
						<th><?php echo __('Action');?></th>
					</tr>
				</thead>
				<tbody>
					<?php
						if(!empty($projects))
						{
							foreach($projects as $retrive_dara)
							{
								echo '<tr>';
								echo '<td>';
								echo $retrive_dara['project_code'];
								echo '</td>';
								echo '<td>'.$retrive_dara['project_name'].'</td>';
								echo '<td>'.$retrive_dara['project_status'].'</td>';
								echo '<td>'.$retrive_dara['client_name'].'</td>';
								echo '<td>'.$retrive_dara['contract_amount'].'</td>';
								echo '<td>'.$this->ERPfunction->get_date($retrive_dara['contract_start_date']).'</td>';
								echo '<td>'.$this->ERPfunction->get_date($retrive_dara['contract_end_date']).'</td>';
								echo '<td>'.$retrive_dara['revise_amount'].'</td>';
								echo '<td>'.(($retrive_dara['exten_cmp_date'] == "1970-01-01") ? "NA" :$this->ERPfunction->get_date($retrive_dara['exten_cmp_date'])).'</td>';
								echo '<td>'.$ra = $this->ERPfunction->get_total_rabills($retrive_dara['project_id']).'</td>';
								echo '<td>'.$pv = $this->ERPfunction->get_total_pricevariation($retrive_dara['project_id']).'</td>';
								echo '<td>'.($ra+$pv).'</td>';
								echo '<td>'.round($this->ERPfunction->get_total_work_done($retrive_dara['project_id'],$ra,$retrive_dara['revise_amount']),2).' %</td>';
								
								echo "<td>".$this->Html->link("<i class='icon-pencil'></i> Edit",array('action' => 'edit', $retrive_dara['project_id']),array('escape'=>false,'class'=>' btn btnview btn-clean btn-info')).
								"&nbsp;";
								/* echo $this->Html->link("<i class='icon-trash'></i> Delete",array('action' => 'delete', $retrive_dara['project_id']),
								array('escape'=>false,'class'=>'btn btnview btn-danger btn-clean','confirm' => 'Are you sure you wish to delete this Record?'))." ";
								
								echo ' '.$this->Html->link("<i class='icon-eye-open'></i> View",array('action' => 'viewproject', $retrive_dara['project_id']),
								array('escape'=>false,'class'=>' btn btnview btn-info btn-clean'));
								*/
								echo "</td>";
								echo '</tr>';
							}
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
