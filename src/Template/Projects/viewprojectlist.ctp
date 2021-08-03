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
<div class="col-md-12">
<div class="row">
	
		<div class="block">			
			<div class="head bg-default bg-light-rtl">
				<h2>Project List</h2>
				<div class="pull-right">
				<a href="<?php echo $this->ERPfunction->action_link($back_url,$back_page);?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
				</div>
			</div>
			
		<?php echo $this->Form->Create('form1',['id'=>'eq_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
			        <div class="content">
						<div class="col-md-12 filter-form">
						<div class="form-row">
							
							<div class="col-md-2 text-right">Date From :</div>						
						<div class="col-md-4"><input name="date_from" class="form-control datepicker"></div>
						<div class="col-md-2 text-right">Date To :</div>						
						<div class="col-md-4"><input name="date_to" class="form-control datepicker"></div>	
						</div>
					
						<div class="form-row">
							
							<div class="col-md-2 text-right">Project Name</div>
							<div class="col-md-4">
							<select class="select2" style="width: 100%;" name="project_id[]" id="project_id" multiple="multiple">
								<option value="all">All</Option>
								<?php 
									foreach($projects_list as $retrive_data)
									{ ?>
										<option value="<?php echo $retrive_data['project_id'];?>">
											<?php echo $retrive_data['project_name']; ?> </option>
										<?php										
									}
								?>
								</select>
							</div>
							<div class="col-md-2 text-right">Project Status</div>
							<div class="col-md-4">
								<!--<input name="project_status" class="form-control">-->
							<select class="select2" style="width: 100%;" name="project_status[]" multiple="multiple">
									<option value="all">All</Option>
									<option value="On Going">On Going</option>
									<option value="Physically Completed">Physically Completed</option>
									<option value="Fully Completed">Fully Completed</option>
							</select>
							</div>
						</div>
						<div class="form-row">
							<div class="col-md-2 text-right">Client Name</div>
							<div class="col-md-4"><input name="client_name" class="form-control"></div>
							<div class="col-md-2 text-right">Project Code</div>
							<div class="col-md-4"><input name="project_code" class="form-control"></div>			
						</div>
								
						<div class="form-row">
								<div class="col-md-2 text-right">State</div>
							<div class="col-md-4"><input name="state" class="form-control"></div>			
						</div>
								
					<div class="form-row">
						<div class="col-md-4 col-md-offset-2">
							<button type="submit" name="search" value="Search" class="btn btn-primary">Search</button>
						</div>
											
					</div>
					</div>
					</div>
					<?php echo $this->Form->end();?>	
		
		
		<div class="content list custom-btn-clean">
		<script>
		jQuery(document).ready(function() {	
			jQuery('#user_list').DataTable({responsive: true,"ordering": false,});
		} );
</script>
			<table id="user_list"  class="dataTables_wrapper table table-striped table-hover">
				<thead>
					<tr>
						<th>Project Code</th>
						<th>Project Name</th>
						<th>Project Status</th>
						<th>Client\'s Name</th>
						<th>Contract Amount</th>			
						<th>Contract Start Date</th>			
						<th>Contract End Date</th>			
						<th>Revised Amount</th>			
						<th>Revised Completion Date</th>			
						<th>Total  of RA  Bill (Rs.)</th>			
						<th>Total Price Variation (Rs.)</th>			
						<th>Total Work Done (Rs.)</th>			
						<th>% Work Done</th>			
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
					if(!empty($projects))
					{
						$going = array();
						$physically = array();
						$fully = array();
						foreach($projects as $project_list)
						{
							if($project_list["project_status"] == "On Going")
							{
								$going[] = $project_list;
							}
							else if($project_list["project_status"] == "Physically Completed")
							{
								$physically[] = $project_list;
							}
							else
							{
								$fully[] = $project_list;
							}
						}
						$merge = array_merge($going, $physically);
					
						$project_info = array_merge($merge, $fully);
					}
					$rows = array();
					$rows[] = array("Project Code","Project Name","Project Status","Clients Name","Contract Amount","Contract Start Date","Contract End Date","Revised Amount","Revised Completion Date","Total  of RA  Bill (Rs.)","Total Price Variation (Rs.)","Total Work Done (Rs.)","% Work Done");
					if(!empty($projects))
						{
							foreach($project_info as $retrive_dara)
							{ $csv = array();
								echo '<tr>';
								echo '<td>';
								echo $csv[] = $retrive_dara['project_code'];
								echo '</td>';
								echo '<td>'.($csv[] = $retrive_dara['project_name']) .'</td>';
								echo '<td>'.($csv[] = $retrive_dara["project_status"]).'</td>';
								echo '<td>'.($csv[] = $retrive_dara['client_name']) .'</td>';
								echo '<td>'.($csv[] = number_format($retrive_dara['contract_amount'],2,'.','')).'</td>';
								echo '<td>'.($csv[] = $this->ERPfunction->get_date($retrive_dara['contract_start_date'])) .'</td>';
								echo '<td>'.($csv[] = $this->ERPfunction->get_date($retrive_dara['contract_end_date'])) .'</td>';
								echo '<td>'.($csv[] = number_format($retrive_dara['revise_amount'],2,'.','')) .'</td>';
								echo '<td>'.($csv[] = $this->ERPfunction->get_date($retrive_dara['exten_cmp_date'])) .'</td>';
								echo '<td>'.($csv[] = $ra = $this->ERPfunction->get_total_rabills($retrive_dara['project_id'])) .'</td>';
								echo '<td>'.($csv[] = $pv = $this->ERPfunction->get_total_pricevariation($retrive_dara['project_id'])) .'</td>';
								echo '<td>'.($csv[] = $total_work_done = number_format(($ra+$pv),2,'.','')) .'</td>';
								
								//$total_work_done = $this->ERPfunction->get_total_work_done($retrive_dara['project_id'],$ra,$retrive_dara['revise_amount']);
								
								echo '<td>'.($csv[] = $this->ERPfunction->work_done($total_work_done,$retrive_dara['revise_amount'])) .'</td>';
								if($this->ERPfunction->retrive_accessrights($role,'viewprojectlist')==1)
								{
								echo "<td>".$this->Html->link("<i class='icon-pencil'></i> View",array('action' => 'viewproject', $retrive_dara['project_id']),array('escape'=>false,'class'=>' btn btnview btn-clean btn-primary'));
								}
								if($this->ERPfunction->retrive_accessrights($role,'edit')==1)
								{
									echo " ".$this->Html->link("<i class='icon-pencil'></i> Edit",array('action' => 'edit', $retrive_dara['project_id']),array('escape'=>false,'class'=>' btn btnview btn-clean btn-info'));
								}
								/* echo ' '.$this->Html->link("<i class='icon-eye-open'></i> Stock",array('action' => 'viewstock', $retrive_dara['project_id']),
								array('escape'=>false,'class'=>' btn btnview btn-warning btn-clean'));
								*/
								echo "</td>";
								echo '</tr>';
								$rows[] = $csv;							
							}
						}
					?>
				</tbody>
			</table>
		<div class="content">
			<div class="col-md-2"><a href="javascript:void(0);" class="btn btn-success" id="fullscreen" url='<?php echo $_SERVER['REQUEST_URI']; ?>' onClick="DoFullScreen()" >View Full Screen</a></div>
			<div class="col-md-2">
			<!-- <form method="post">
				<input type="hidden" name="rows" value='<?php echo serialize($rows);?>'>
				<input type="submit" class="btn btn-success" value="Export To Excel" name="export_csv">
			</form> -->
			<?php 
				echo $this->Form->create("",["type"=>"file","class"=>"validateForm form-horizontal","role"=>"form","id"=>"form"]);
			?>
			<input type="hidden" name="rows" value='<?php echo serialize($rows);?>'>
			<input type="submit" class="btn btn-success" value="Export To Excel" name="export_csv">
			<?php 
				echo $this->Form->end();
			?>
			</div>
			<div class="col-md-2">
			<!-- <form method="post">
				<input type="hidden" name="rows" value='<?php echo serialize($rows);?>'>
				<input type="submit" class="btn btn-success" value="Export To PDF" name="export_pdf">
			</form> -->
			<?php 
				echo $this->Form->create("",["type"=>"file","class"=>"validateForm form-horizontal","role"=>"form","id"=>"form"]);
			?>
			<input type="hidden" name="rows" value='<?php echo serialize($rows);?>'>
			<input type="submit" class="btn btn-success" value="Export To PDF" name="export_pdf">
			<?php 
				echo $this->Form->end();
			?>
			</div>
		</div>		
		</div>
		</div>
	</div>
</div>
<?php } ?>	
</div>


<script>


</script>