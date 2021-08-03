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
				<h2>Opening Stock</h2>
				<div class="pull-right">
				<a href="<?php echo $this->ERPfunction->action_link('Usermanage','index');?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
				</div>
			</div>
			
		<?php echo $this->Form->Create('form1',['id'=>'eq_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
			    		
		<div class="content list">
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
						<!-- <th><?php echo __('Client\'s Name');?></th> -->
						<!-- <th><?php echo __('Contract Amount');?></th>	-->		
						<!--<th><?php echo __('Contract Start Date');?></th>			
						<!--<th><?php echo __('Contract End Date');?></th>			
						<!--<th><?php echo __('Revised Amount');?></th>			
						<th><?php echo __('Revised Completion Date');?></th>			
						<th><?php echo __('Total  of RA  Bill (Rs.)');?></th>			
						<th><?php echo __('Total Price Variation (Rs.)');?></th>			
						<th><?php echo __('Total Work Done (Rs.)');?></th>			
						<th><?php echo __('% Work Done');?></th>	-->		
						<?php
						if($this->ERPfunction->retrive_accessrights($role,'openingstock')==1 )
						{
						?>
						<th><?php echo __('Action');?></th>
						<?php } ?>
					</tr>
				</thead>
				<tbody>
					<?php
					$rows = array();
					$rows[] = array("Project Code","Project Name","Project Status","Clients Name","Contract Amount","Contract Start Date","Contract End Date","Revised Amount","Revised Completion Date","Total  of RA  Bill (Rs.)","Total Price Variation (Rs.)","Total Work Done (Rs.)","% Work Done");
					if(!empty($projects))
						{
							foreach($projects as $retrive_dara)
							{ $csv = array();
								echo '<tr>';
								echo '<td>';
								echo $csv[] = $retrive_dara['project_code'];
								echo '</td>';
								echo '<td>'.($csv[] = $retrive_dara['project_name']) .'</td>';
								echo '<td>'.($csv[] = $retrive_dara['project_status']) .'</td>';
								/*
								echo '<td>'.($csv[] = (($retrive_dara["actual_amount"] == 0 || $retrive_dara["actual_cmp_date"] == "" ) ? "On Going":"Completed")) .'</td>';*/
								
								// echo '<td>'.($csv[] = $retrive_dara['client_name']) .'</td>';
								// echo '<td>'.($csv[] = $retrive_dara['contract_amount']) .'</td>';
								// echo '<td>'.($csv[] = $this->ERPfunction->get_date($retrive_dara['contract_start_date'])) .'</td>';
								// echo '<td>'.($csv[] = $this->ERPfunction->get_date($retrive_dara['contract_end_date'])) .'</td>';
								// echo '<td>'.($csv[] = $retrive_dara['revise_amount']) .'</td>';
								// echo '<td>'.($csv[] = $this->ERPfunction->get_date($retrive_dara['exten_cmp_date'])) .'</td>';
								// echo '<td>'.($csv[] = $ra = $this->ERPfunction->get_total_rabills($retrive_dara['project_id'])) .'</td>';
								// echo '<td>'.($csv[] = $pv = $this->ERPfunction->get_total_pricevariation($retrive_dara['project_id'])) .'</td>';
								// echo '<td>'.($csv[] = $total_work_done = ($ra+$pv)) .'</td>';
								
								//$total_work_done = $this->ERPfunction->get_total_work_done($retrive_dara['project_id'],$ra,$retrive_dara['revise_amount']);
								
								// echo '<td>'.($csv[] = $this->ERPfunction->work_done($total_work_done,$retrive_dara['revise_amount'])) .'</td>';	
								if($this->ERPfunction->retrive_accessrights($role,'openingstock')==1 )
								{								
								echo '<td>'.$this->Html->link("Add Opening Stock",array('action' => 'openingstock', $retrive_dara['project_id']),array('class'=>' btn btnview btn-info'));
								
								echo "</td>";
								}
								echo '</tr>';
								$rows[] = $csv;							
							}
						}
					?>
				</tbody>
			</table>
			<!--
		<div class="content">
			<div class="col-md-2"><a href="javascript:void(0);" class="btn btn-success" id="fullscreen" url='<?php echo $_SERVER['REQUEST_URI']; ?>' onClick="DoFullScreen()" >View Full Screen</a></div>
			<div class="col-md-2">
			<form method="post">
				<input type="hidden" name="rows" value='<?php echo serialize($rows);?>'>
				<input type="submit" class="btn btn-success" value="Export To Excel" name="export_csv">
			</form>
			</div>
			<div class="col-md-2">
			<form method="post">
				<input type="hidden" name="rows" value='<?php echo serialize($rows);?>'>
				<input type="submit" class="btn btn-success" value="Export To PDF" name="export_pdf">
			</form>
			</div>
		</div>
		-->		
		</div>
		</div>
	</div>
</div>
<?php } ?>	
</div>
