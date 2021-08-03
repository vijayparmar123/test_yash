<?php
use Cake\Routing\Router;
//$this->extend('/Common/menu')
echo $this->Html->script('plugins/fullcalendar/fullcalendar.min.js');
echo $this->Html->css('fullcalendar/fullcalendar.css');

$user_id = $this->request->session()->read('user_id');
$role = $this->ERPfunction->get_user_role($user_id);
//var_dump($role);
if(isset($date_from)){
	$date_from=date('Y-m-d',strtotime($date_from));
}
if(isset($date_to)){
	$date_to=date('Y-m-d',strtotime($date_to));
}
?>
<style>
	#boxes , #calendar
	{
		padding:0;
	}
</style>
<div class="col-md-10">
	<div class="block"> <!--  block-drop-shadow bg-default bg-light-rtl -->
	<div class="head" id="head" style="padding-left: 0;padding-top:0;margin-left: -13px;">
		<div class="col-lg-2 col-md-2 col-xs-6 col-sm-2 info-box">
			<a href="<?php echo $this->ERPfunction->action_link('Usermanage');?>">
				<div class="panel info-box panel-white no-margin  block-drop-shadow bg-default bg-light-rtl">
					<div class="panel-body member  text-center">
						<span>
						<?php 
						echo $this->Html->image('icon/Users-Management.png',
					array('class'=>'userimage','height'=>'50px','width'=>'80px'));
						?>				
						</span>
						<span class="text-center infobox-bottom">											
								<span class="info-box-title">
									<strong class="counter "><?php echo $this->ERPfunction->count_users();?></strong> USERS</span>						
						</span>
					</div>					
				</div>		
			</a>
		</div>
		<div class="col-lg-2 col-md-2 col-xs-6 col-sm-2 info-box">
			<a href="<?php echo $this->ERPfunction->action_link('projects/viewprojectlist');?>">
				<div class="panel info-box panel-white no-margin  block-drop-shadow bg-default bg-light-rtl">
					<div class="panel-body member  text-center">
						<span>
						<?php 
						echo $this->Html->image('icon/project.png',
					array('class'=>'userimage','height'=>'50px','width'=>'80px'));
						?>				
						</span>
						<span class="text-center infobox-bottom">											
								<span class="info-box-title"><strong class="counter"><?php echo $this->ERPfunction->count_projects();?></strong> PROJECTS</span>						
						</span>
					</div>					
					</div>		
			</a>
		</div>
		<div class="col-lg-2 col-md-2 col-xs-6 col-sm-2 info-box">
			<a href="<?php echo $this->ERPfunction->action_link('Assets');?>">
				<div class="panel info-box panel-white no-margin block-drop-shadow bg-default bg-light-rtl">
					<div class="panel-body member  text-center">
						<span>
						<?php 
						echo $this->Html->image('icon/asset.png',
					array('class'=>'userimage','height'=>'50px','width'=>'80px'));
						?>				
						</span>
						<span class="text-center infobox-bottom">											
								<span class="info-box-title"><strong class="counter"><?php echo $this->ERPfunction->count_assets();?></strong>  ASSETS</span>						
						</span>
					</div>					
					</div>
			</a>
		</div>
		<div class="col-lg-2 col-md-2 col-xs-6 col-sm-2 info-box">
			<a href="<?php echo $this->ERPfunction->action_link('Accounts/acceptbills');?>">
				<div class="panel info-box panel-white no-margin block-drop-shadow bg-default bg-light-rtl">
					<div class="panel-body member  text-center">
						<span>
						<?php 
						echo $this->Html->image('icon/bill.png',
					array('class'=>'userimage','height'=>'50px','width'=>'80px'));
						?>				
						</span>
						<span class="text-center infobox-bottom">											
								<span class="info-box-title"><strong class="counter"><?php echo $this->ERPfunction->count_inward_pending_bills();?></strong>  PENDING BILLS</span>						
						</span>
					</div>					
					</div>
			</a>
		</div>
		<div class="col-lg-2 col-md-2 col-xs-6 col-sm-2 info-box">
			<a href="<?php echo $this->ERPfunction->action_link('Contract/agencylist');?>">
				<div class="panel info-box panel-white no-margin  block-drop-shadow bg-default bg-light-rtl">
					<div class="panel-body member  text-center">
						<span>
						<?php 
						echo $this->Html->image('icon/agency.png',
					array('class'=>'userimage','height'=>'50px','width'=>'80px'));
						?>				
						</span>
						<span class="text-center infobox-bottom">											
								<span class="info-box-title"><strong class="counter"><?php echo $this->ERPfunction->count_agency();?></strong>  AGENCY</span>						
						</span>
					</div>					
					</div>		
			</a>
		</div>
		<div class="col-lg-2 col-md-2 col-xs-6 col-sm-2 info-box">
			<a href="<?php echo $this->ERPfunction->action_link('Purchase/viewvendor');?>">
				<div class="panel info-box panel-white no-margin block-drop-shadow bg-default bg-light-rtl">
					<div class="panel-body member  text-center">
						<span>
						<?php 
						echo $this->Html->image('icon/vendor.png',
					array('class'=>'userimage','height'=>'50px','width'=>'80px'));
						?>				
						</span>
						<span class="text-center infobox-bottom">											
								<span class="info-box-title"><strong class="counter"><?php echo $this->ERPfunction->count_vendors();?></strong> VENDORS</span>						
						</span>
					</div>					
					</div>
			</a>
		</div>
	</div>
	<div class="clearfix"></div>
	<div class="row">
		<div class="col-md-12">
			<div class="block">
				<!--<div class="head bg-default bg-light-rtl">
					<h2>View Rights </h2>
					
				</div>-->
				
				<div class="content">
					<div class="col-md-12 filter-form no-padding">
					<?php echo $this->Form->Create('form1',['id'=>'material_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
						<div class="col-md-3 no-padding">
							<div class="form-row">						
								<div class="col-md-6">Project Code<span class="require-field"> *</span></div>
								<div class="col-md-6 no-padding">
								
									<input type="text" name="project_code" id="project_code" value="<?php echo (isset($pro_code))?$pro_code:"";?>"
							        class="form-control validate[required]" value="" readonly="true"/>
								</div>
							</div>
						</div>
						<div class="col-md-3 no-padding">
							<div class="form-row">						
								<div class="col-md-6">Project Name<span class="require-field"> *</span></div>
								<div class="col-md-6 no-padding">
								
									<select class="select2 validate[required]" id="project_id" required="true"  style="width: 100%;" name="project_id">
										<option value="">--Select Project--</Option>
										<?php 
											foreach($projects as $retrive_data)
											{
												echo '<option value="'.$retrive_data['project_id'].'" '.$this->ERPfunction->selected($project_id,$retrive_data['project_id']).'>'.
												$retrive_data['project_name'].'</option>';
											}
										?>
									</select>
								</div>
							</div>
						</div>
						<div class="col-md-5 no-padding">
							<div class="form-row">						
								<div class="col-md-3">Date From<span class="require-field"> *</span></div>
								<div class="col-md-3 no-padding">
								<input name="date_from" class="form-control validate[required] datep" value="<?php echo (isset($date_from))?date('d-m-Y',strtotime($date_from)):date('dd-mm-yy'); ?>">
									
								</div>
											
								<div class="col-md-3">Date To<span class="require-field"> *</span></div>
								<div class="col-md-3 no-padding">
								<input name="date_to" class="form-control validate[required] datep" value="<?php echo (isset($date_to))?date('d-m-Y',strtotime($date_to)):date('dd-mm-yy'); ?>">
									
								</div>
							</div>
						</div>
						<div class="col-md-1 no-padding">
							<div class="form-row">	
								<button type="submit" name="search" value="Search" class="btn btn-primary">Go</button>
							</div>
						</div>
					<?php $this->Form->end(); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
<div class="row" style="margin-top: 10px;">
	<div class="col-md-6 col-sm-6 col-xs-12">
		<!--<div id="calendar" class="fc fc-ltr bg-default bg-light-rtl block-drop-shadow col-xs-12 col-md-12 col-sm-12" style="border-radius: 3px;">
		</div> -->
		<?php
		if($role == 'erphead' || $role == 'ceo' || $role == 'md' || $role == 'erpoperator' || $role == 'erpmanager' || $role == 'projectdirector' || $role == 'constructionmanager' || $role == 'billingengineer' || $role == 'financemanager' || $role == 'accounthead')
		{
			if(isset($project_id)){
			?>
		<div class="block block-drop-shadow dashboard col-xs-12 col-md-12 col-sm-12" id="boxes">
			<div class="head bg-default bg-light-rtl">
				<span>
					<?php 
						echo $this->Html->image('icon/request.png',
						array('class'=>'pull-left','width'=>'40px'));
					?>				
				</span>
				<h2>&nbsp;&nbsp; Project Details</h2>                        
			</div>
			<div class="block block-fill-white no-margin">
			<div class="content list-dash list">				
				  <table class="table dashboard_info_box">
						
						<tbody>
							<?php $project_details = $this->ERPfunction->dashboard_project ($project_id); 
								if(!empty($project_details)){
									
							?>
							<tr>
								<td class='border-right'>Contract Start Date</td>
								<td class='border-right' ><?php echo $this->ERPfunction->get_date($project_details[0]['contract_start_date']); ?></td>
								<td class='border-right'>Project status</td>
								<?php
									if($project_details[0]['project_status']=="On Going"){
										$bg="#4CAF50";
										$font="#ffffff";
									}
									else if($project_details[0]['project_status']=="Physically Completed"){
										$bg="#ffffff";
										$font="#C82334";
									}
									else{
										$bg="#ffffff";
										$font="#4CAF50";
									}
								?>
								
								<td style="<?php  echo "background:".$bg. ";color:".$font.";";?>"><?php echo $project_details[0]['project_status']; ?></td>
							</tr>
							<tr>
								<td class='border-right'>Contract End Date</td>
								<td  class='border-right'><?php echo $this->ERPfunction->get_date($project_details[0]['contract_end_date']); ?></td>
								<td class='border-right'>Revice Completion Date</td>
								<td><?php echo $this->ERPfunction->get_date($project_details[0]['actual_cmp_date']) ; ?></td>
							</tr>
							<tr>
								<?php if($role == 'constructionmanager' || $role == 'billingengineer') { 
										echo '<td colspan="4" class="border-right center">Income</td>';
									 }	
									else{
										echo '<td colspan="2" class="border-right center">Income</td>';
										echo '<td colspan="2" class="center" >Expense</td>';
									}
									?>
							</tr>
							<tr>
							<?php if($role == 'constructionmanager' || $role == 'billingengineer') { ?>
									<td colspan='3' class='border-right'>Revised Amount</td>
									<td colspan='1' class='border-right'><?php echo number_format($project_details[0]['revise_amount'],2,'.','') ; ?></td>
							<?php	}		else{ ?>
										<td class='border-right'>Revised Amount</td>
										<td  class='border-right'><?php echo number_format($project_details[0]['revise_amount'],2,'.','') ; ?></td>
										<td class='border-right'>Material Purchesed (Rs)</td>
										<td><?php echo $material_total= $this->ERPfunction->dashboard_project_expense($project_details[0]['project_id'],'Material/Item'); ?></td>
							<?php	} 	?>
							
								
							</tr>
							<tr>
							<?php if($role == 'constructionmanager' || $role == 'billingengineer') { ?>
									<td colspan='3' class='border-right'>Total of RA</td>
									<td colspan='1' class='border-right'><?php echo $ra= $this->ERPfunction->get_total_rabills($project_details[0]['project_id']); ?></td>
							<?php	}		else{ ?>
									<td class='border-right'>Total of RA</td>
									<td class='border-right'><?php echo $ra= $this->ERPfunction->get_total_rabills($project_details[0]['project_id']); ?></td>
									<td class='border-right'>Labour Incurred (Rs)</td>
									<td><?php
									echo $labour_total= $this->ERPfunction->dashboard_project_expense($project_details[0]['project_id'],'Labour with Material/Item,Labour'); ?></td>
							<?php	} 	?>
							
								
							</tr>
							<tr>
							<?php if($role == 'constructionmanager' || $role == 'billingengineer') { ?>
									<td colspan='3' class='border-right'>Total Price Varition </td>
									<td colspan='1' class='border-right'><?php  $pv = $this->ERPfunction->get_total_pricevariation($project_details[0]['project_id']); ?></td>
							<?php	}		else{ ?>
									<td class='border-right'>Total Price Varition </td>
									<?php $pv=$this->ERPfunction->get_total_pricevariation($project_details[0]['project_id']); echo "<td class='border-right'> {$pv} </td>"; ?>
									<td class='border-right'>Other expense</td>
									<td><?php echo $other= $this->ERPfunction->dashboard_project_expense($project_details[0]['project_id'],'Other'); ?></td>
							<?php	} 	?>
							
								
							</tr>
							<tr>
							<?php if($role == 'constructionmanager' || $role == 'billingengineer') { ?>
									<td colspan='3' class='border-right'>Total Work Done</td>
									<td colspan='1' class='border-right'><?php echo $total_work_done = number_format(($ra+$pv),2,'.',''); ?></td>
							<?php	}		else{ ?>
									<td class='border-right'>Total Work Done</td>
									<td class='border-right'><?php echo $total_work_done = number_format(($ra+$pv),2,'.',''); ?></td>
									<td class='border-right'>Total expense</td>
									<td><?php echo $total_work_done1 = number_format(($material_total+$labour_total+$other),2,'.',''); ?></td>
							<?php	} 	?>
								
							</tr>
							<tr>
								<td class='border-right'>(%)Work Done(%)</td>
								<td class='border-right'><?php echo $total_work=$this->ERPfunction->work_done($total_work_done,$project_details[0]['revise_amount']); ?></td>
								<td></td>
								<td></td>
							</tr>
							<tr>
								
								<td colspan="4"><?php
								$diff=100-round($total_work);
								
								$chart_array=array();
								$chart_array[] = array('Total','Count');

									$chart_array[]=array("Total Work Done",round($total_work));
									$chart_array[]=array("Total Work Remain",(int)$diff);



												$options = Array(
												'title'=>'Total Work',
												'pieHole' => 0.5,
												'pieSliceText' => 'value'
										); 

									  include_once WWW_ROOT.'chart'.DS.'GoogleCharts.class.php';
									  $GoogleCharts=new GoogleCharts;


									?>

									  <?php

											  $chart = $GoogleCharts->load( 'pie' , 'chart_div' )->get( $chart_array , $options );

											  if(count($chart_array) > 1){
											  ?>

												 <div id="chart_div" style="width: 100%; height: 400px;"></div>
											<?php
										}else{
											?>
												<div class="alert alert-info"><h2 align="center"><?php echo __('Result Not Found');?> !</h2></div>
											<?php
										}
										?>


									  <!-- Javascript -->
									  <script type="text/javascript" src="https://www.google.com/jsapi"></script>
									  <script type="text/javascript">
												<?php echo $chart;?>
											</script>


								
								</td>
							</tr>
								<?php } ?>
					  </tbody>
				  </table>                                    
			</div>			
			</div>			
		</div>
		
		
		<?php 
		}
		}
		if($role == 'erphead' || $role == 'ceo' || $role == 'md' || $role == 'erpoperator' || $role == 'erpmanager' || $role == 'projectdirector' || $role == 'constructionmanager' || $role == 'billingengineer' )
		{
			if(isset($project_id)){
			?>
		<div class="block block-drop-shadow dashboard col-xs-12 col-md-12 col-sm-12" id="boxes">
			<div class="head bg-default bg-light-rtl">
				<span>
					<?php 
						echo $this->Html->image('icon/request.png',
						array('class'=>'pull-left','width'=>'40px'));
					?>				
				</span>
				<h2>&nbsp;&nbsp; Contract Admin  &nbsp;&nbsp;&nbsp;&nbsp; Date From: <?php echo date('d-m-Y',strtotime($date_from)); ?>&nbsp;&nbsp; Date To: <?php echo  date('d-m-Y',strtotime($date_to)); ?></h2>                        
			</div>
			<div class="block block-fill-white no-margin">
			<div class="content list-dash list">				
				  <table class="table dashboard_info_box">
						
						<tbody>
							<?php $contract = $this->ERPfunction->dashboard_project($project_id); 
							if(!empty($contract)){
									
							?>
							<tr>
								<td class='border-right center'>Description</td>
								<td>View Details</td>
								
							</tr>
							<tr>
								<td class='border-right'>View R.A Bills</td>
								<td><?php echo "<a href='{$this->request->base}/Contract/viewrabill/{$project_id}/{$date_from}/{$date_to}'><i class='icon-eye-open' style='padding-left:10px;font-size:20px'></i></a>" ?></td>
								
							</tr>
							<tr>
								<td class='border-right'>View Price Variation</td>
								<td><?php echo "<a href='{$this->request->base}/Contract/viewpricevariation/{$project_id}/{$date_from}/{$date_to}'><i class='icon-eye-open' style='padding-left:10px;font-size:20px'></i></a>" ?></td>
							</tr>
							<tr>
								<td class='border-right'>View Inward Correspondence</td>
								<td><?php echo "<a href='{$this->request->base}/Contract/viewinwardlist/{$project_id}/{$date_from}/{$date_to}'><i class='icon-eye-open' style='padding-left:10px;font-size:20px'></i></a>" ?></td>
							</tr>
							<tr>
								<td class='border-right'>View Outward Correspondence</td>
								<td><?php echo "<a href='{$this->request->base}/Contract/viewoutwardlist/{$project_id}/{$date_from}/{$date_to}'><i class='icon-eye-open' style='padding-left:10px;font-size:20px'></i></a>" ?></td>
							</tr>
							<tr>
								<td class='border-right'>View Drawing Record</td>
								<td><?php echo "<a href='{$this->request->base}/Contract/drawingrecords/{$project_id}'><i class='icon-eye-open' style='padding-left:10px;font-size:20px'></i></a>" ?></td>
							</tr>
							<tr>
								<td class='border-right'>View Sub-contractor Bills</td>
								<td><?php echo "<a href='{$this->request->base}/Contract/subcontractrecords/{$project_id}/{$date_from}/{$date_to}'><i class='icon-eye-open' style='padding-left:10px;font-size:20px'></i></a>" ?></td>
							</tr>
							
								<?php } ?>
					  </tbody>
				  </table>                                    
			</div>			
			</div>			
		</div>
		
		
		<?php 
		}
		
		}
		if($role == 'erphead' || $role == 'ceo' || $role == 'md' || $role == 'erpoperator' || $role == 'erpmanager' || $role == 'projectdirector' || $role == 'constructionmanager' || $role == 'billingengineer' || $role == 'asset-inventoryhead' || $role == 'materialmanager' || $role == 'assistantpmm' || $role == 'pmm' || $role == 'financemanager' || $role == 'accounthead' || $role == 'senioraccountant' || $role == 'siteaccountant')
		{
			if(isset($project_id)){
		?>
		<div class="block block-drop-shadow dashboard col-xs-12 col-sm-12 col-md-12" id="boxes">
			<div class="head bg-default bg-light-rtl">
				<span>
					<?php 
						echo $this->Html->image('icon/asset.png',
						array('class'=>'pull-left','width'=>'40px'));
					?>				
				</span>
				<h2>&nbsp;&nbsp; Assets  &nbsp;&nbsp;&nbsp;&nbsp; Date From: <?php echo date('d-m-Y',strtotime($date_from)); ?>&nbsp;&nbsp; Date To: <?php echo  date('d-m-Y',strtotime($date_to)); ?></h2>        
			</div>
			<div class="block block-fill-white no-margin">
			<div class="content list-dash list">				
				  <table class="table dashboard_info_box">
						<thead>
							<tr>								
								<th>Asset Name</th>
								<th>Transfered From</th>
								<th>Transfered To</th>
								<th>Transfered Date</th>
								<th>Transfered Status</th>
							</tr>	
							</thead>
							<tbody>
							<?php $Asset_Purchase_Transfer_data = $this->ERPfunction->dashboard_Asset_Purchase_Transfer_list($project_id,$date_from,$date_to); 
							/* debug($Asset_Purchase_Transfer_data);
							die; */
							if(!empty($Asset_Purchase_Transfer_data))
							{
								
								foreach($Asset_Purchase_Transfer_data as $Asset_Purchase_Transfer)
								{
									
									$Asset_Purchase_Transfer = array_merge($Asset_Purchase_Transfer,$Asset_Purchase_Transfer["erp_assets_history"]);
									
									if($Asset_Purchase_Transfer['accepted']==1){
										$Asset_Purchase_Transfer['accepted']="Compeleted";
									}
									else{
										$Asset_Purchase_Transfer['accepted']="A Waiting";
									}
									
									echo "<tr>										
										<td class='block-fill-white'>{$Asset_Purchase_Transfer['asset_name']}</td>
										<td>{$this->ERPfunction->get_projectname($Asset_Purchase_Transfer['old_project'])}</td>
										<td>{$this->ERPfunction->get_projectname($Asset_Purchase_Transfer['new_project'])}</td>
										
										<td>{$this->ERPfunction->get_date($Asset_Purchase_Transfer['created_date'])}".','. date('H:i',strtotime($Asset_Purchase_Transfer['created_date']))."</td>
										<td>{$Asset_Purchase_Transfer['accepted']}</td>
										</tr>";
								}
								if($role == 'asset-inventoryhead' || $role == 'materialmanager') { 
									}		
								else{ 
									echo "<tr>										
										<td class='border-right' colspan='4'>View Asset Records	</td>
										<td><a href='{$this->request->base}/Assets/assetrecord'><i class='icon-eye-open' style='padding-left:10px;font-size:20px'></i></a></td>
									  </tr>
									  <tr>										
										<td class='border-right' colspan='4'>View RMC Issue Records	</td>
										<td><a href='{$this->request->base}/Assets/rmcissuerecord/{$project_id}/{$date_from}/{$date_to}'><i class='icon-eye-open' style='padding-left:10px;font-size:20px'></i></a></td>
									  </tr>
										";					
								} 	
								
								
							}else{
								echo '<tr style="color:#000 !important">
										<td colspan="5">No Asset Purchase & Transfer Record Found.</td>						
									</tr>';
							}
							
							
							?>
					  </tbody>
				  </table>                                    
			</div>			
			</div>			
		</div>
		<?php 
		}
		}
		
		/*
		<div class="block block-drop-shadow dashboard">
			<div class="head bg-default bg-light-rtl">
				<span>
					<?php 
						echo $this->Html->image('icon/price.png',
						array('class'=>'pull-left','width'=>'40px'));
					?>				
				</span>
				<h2>&nbsp;&nbsp; Latest GRN</h2>        
			</div>
			<div class="block block-fill-white no-margin">
			<div class="content list-dash list">				
				  <table class="table dashboard_info_box">
						<tbody>
							<tr>								
								<th>GRN No.</th>
								<th>Project Name</th>
								<th>Created Date</th>
							</tr>	
							<?php $GRN_data = $this->ERPfunction->dashboard_Latest_GRN_list(); 
							if(!empty($GRN_data))
							{
								foreach($GRN_data as $GRN)
								{
									echo "<tr>										
										<td class='block-fill-white'>{$GRN['grn_no']}</td>
										<td>{$this->ERPfunction->get_projectname($GRN['project_id'])}</td>
										<td>{$GRN['created_date']}</td>
										</tr>";
								}
							}else{
								echo '<tr style="color:#000 !important">
										<td colspan="3">No GRN Record Found.</td>						
									</tr>';
							}
							
							
							?>
					  </tbody>
				  </table>                                    
			</div>			
			</div>			
		</div>
		*/ ?>
	</div>
	
	
	<div class="col-md-6 col-sm-6 col-xs-12 pull-right">
		<?php 
		/* if($role == 'erphead' || $role == 'erpmanager' || $role == 'ceo' || $role == 'md' || $role == 'projectdirector' || $role == 'projectcoordinator' || $role == 'accounthead' || $role == 'senioraccountant' || $role == 'purchasehead' || $role == 'purchasemanager' || $role == 'constructionmanager')
		{ */
		?>
		<div class="block block-drop-shadow dashboard col-xs-12 col-sm-12 col-md-12" id="boxes">
			<div class="head bg-default bg-light-rtl">
				<span>
					<?php 
						echo $this->Html->image('icon/alerts.png',
						array('class'=>'pull-left','width'=>'40px'));
					?>				
				</span>
				<h2>&nbsp;&nbsp;Alerts Records</h2>                        
			</div>
			<div class="block block-fill-white no-margin">
			<div class="content list-dash list">				
				  <table class="table dashboard_info_box">
						<thead>
							<tr>
								<th>Alert Type</th>
								<th>Alerts</th>
							</tr>	
							</thead>
							<tbody>
							
							<?php $alerts = $this->ERPfunction->dashboard_alert_list($role); 
							if(!empty($alerts))
							{
							
								if($role == 'erphead' || $role == 'ceo' || $role == 'md' || $role == 'erpoperator' || $role == 'erpmanager' || $role == 'projectdirector'|| $role == 'purchasehead' || $role == 'purchasemanager' || $role == 'constructionmanager' || $role == 'billingengineer' || $role == 'asset-inventoryhead' || $role == 'materialmanager')
								{
									echo "<tr>
										<td><a href='{$this->request->base}/Purchase/approvedpr'>Purchase Request Alerts -Purchase</a></td>
										<td><span class='custom-circle-icon'>{$alerts['pr_alert_purches']}</span></td>
										</tr>";
								}
								if($role == 'erphead' || $role == 'ceo' || $role == 'md' || $role == 'erpoperator' || $role == 'erpmanager' || $role == 'projectdirector'|| $role == 'purchasehead'|| $role == 'purchasemanager')
								{
									echo "<tr>
										<td><a href='{$this->request->base}/Inventory/approvedpr'>Purchase Request Alerts- Inventory</a></td>
										<td><span class='custom-circle-icon'>{$alerts['pr_alert_inv']}</span></td>
										</tr>";
									echo "<tr>
										<td ><a href='{$this->request->base}/Inventory/approvepo'>Purchase Order Alerts</a></td>
										<td><span class='custom-circle-icon'>{$alerts['po_alert']}</span></td>
										</tr>";	
									echo "<tr>
										<td ><a href='{$this->request->base}/Purchase/manualapprovepo'>Purchase Order Alerts (Manual)</a></td>
										<td><span class='custom-circle-icon'>{$alerts['po_manual_alert']}</span></td>
										</tr>";	
								}
								if($role == 'erphead' || $role == 'ceo' || $role == 'md' || $role == 'erpoperator' || $role == 'erpmanager' || $role == 'projectdirector'|| $role == 'purchasehead' || $role == 'purchasemanager' || $role == 'constructionmanager' || $role == 'billingengineer' || $role == 'asset-inventoryhead' || $role == 'materialmanager')
								{
									echo "<tr>
										<td><a href='{$this->request->base}/Inventory/approvegrn'>G.R.N. Alerts</a></td>
										<td><span class='custom-circle-icon'>{$alerts['grn_alert']}</span></td>
										</tr>";	
									echo "<tr>
										<td><a href='{$this->request->base}/Inventory/approvemrn'>M.R.N. Alerts</a></td>
										<td><span class='custom-circle-icon'>{$alerts['mrn_alert']}</span></td>
										</tr>";
								}
								if($role == 'constructionmanager' || $role == 'billingengineer' || $role == 'asset-inventoryhead' || $role == 'materialmanager')
								{
									echo "<tr>
										<td><a href='{$this->request->base}/Inventory/approvesst'>S.S.T. Alerts</a></td>
										<td><span class='custom-circle-icon'>{$alerts['sst_alert']}</td>
										</tr>";
								}
								if($role == 'constructionmanager' || $role == 'billingengineer' || $role == 'pmm' || $role == 'assistantpmm')
								{
									echo "<tr>
										<td><a href='{$this->request->base}/Assets/aprovemaintenance'>Asset Maintenance Alert		</a></td>
										<td><span class='custom-circle-icon'>{$alerts['maintenace_list']}</td>
										</tr>";
									echo "<tr>
										<td><a href='{$this->request->base}/Assets/rmcissuealert'>RMC Issue Alert		</a></td>
										<td><span class='custom-circle-icon'>{$alerts['rmc_issue']}</td>
										</tr>";
								}
								if($role == 'constructionmanager' || $role == 'billingengineer' )
								{
									echo "<tr>
										<td><a href='{$this->request->base}/Contract/subcontractbillalert'>Sub-contractors Bills Alert	</a></td>
										<td><span class='custom-circle-icon'>{$alerts['sub_contract_alerts']}</td>
										</tr>";	
								}
								if($role == 'constructionmanager' || $role == 'billingengineer' || $role == 'financemanager' || $role == 'accounthead' || $role == 'senioraccountant' || $role == 'siteaccountant')
								{
									echo "<tr>
										<td><a href='{$this->request->base}/Accounts/debitnotealert/'>Debit Note Alert		</a></td>
										<td><span class='custom-circle-icon'>{$alerts['debit_note']}</td>
										</tr>";
									echo "<tr>
										<td><a href='{$this->request->base}/Accounts/viewrequest/'>Advance Alert</a></td>
										<td><span class='custom-circle-icon'>{$alerts['advance_alert']}</td>
										</tr>";
									echo "<tr>
										<td><a href='{$this->request->base}/Accounts/expencealert/'>Expense Alert</a></td>
										<td><span class='custom-circle-icon'>{$alerts['expense_alert']}</td>
										</tr>";
								}
									
							}
							
							?>
					  </tbody>
				  </table>                                    
			</div>			
			</div>			
		</div>
		<?php 
		/* }  */
		
		if($role == 'erphead' || $role == 'ceo' || $role == 'md' || $role == 'erpoperator' || $role == 'erpmanager' || $role == 'projectdirector' || $role == 'constructionmanager' || $role == 'billingengineer' || $role == 'purchasehead' || $role == 'purchasemanager' || $role == 'financemanager' || $role == 'accounthead' || $role == 'senioraccountant' || $role == 'siteaccountant' )
		{
			if(isset($project_id)){
			?>
			<div class="block block-drop-shadow dashboard col-xs-12 col-md-12 col-sm-12" id="boxes">
				<div class="head bg-default bg-light-rtl">
					<span>
						<?php 
							echo $this->Html->image('icon/request.png',
							array('class'=>'pull-left','width'=>'40px'));
						?>				
					</span>
					<h2>&nbsp;&nbsp; Purchase Order (P.O.)  &nbsp;&nbsp;&nbsp;&nbsp; Date From: <?php echo date('d-m-Y',strtotime($date_from)); ?>&nbsp;&nbsp; Date To: <?php  echo date('d-m-Y',strtotime($date_to)); ?></h2>                        
				</div>
				<div class="block block-fill-white no-margin">
				<div class="content list-dash list">
						<table class="table dashboard_info_box">
							<?php
								echo "<tr>
									<td  class='border-right'>Nos. Of P.O Approved</td>
									<td><a href='{$this->request->base}/Purchase/viewporecords/{$project_id}/{$date_from}/{$date_to}'>".$this->ERPfunction->dashboard_po_count($project_id,$date_from,$date_to)."<i class='icon-eye-open' style='padding-left:10px;font-size:20px' ></i> </a></td>
											
									<tr>";
							?>
						</table>			
					  <table class="table dashboard_info_box">
							
							<thead>
								<tr>								
									<th>PO No</th>
									<th>PO Date</th>
									<th>Vendor's Name</th>
									<th>Amount</th>
								</tr>
							</thead>
							<tbody>
								<?php $po_data = $this->ERPfunction->dashboard_po_list($project_id,$date_from,$date_to);
									$po_manual_data = $this->ERPfunction->dashboard_po_manual_list($project_id,$date_from,$date_to);
								if(!empty($po_data) || !empty($po_manual_data))
								{
									$i=0;
									foreach($po_data as $po)
									{
										if($i<=2)
										{
										/* echo "<tr>										
								
											<td>{$this->ERPfunction->get_projectcode($po['project_id'])}</td>
											<td>{$this->ERPfunction->get_projectname($po['project_id'])}</td>
											<td>{$this->ERPfunction->get_date($po['created_date'])}".','. date('H:i',strtotime($po['created_date']))."</td>
											<td><span class='custom-circle-icon'>{$po['count']}</span></td>
											</tr>"; */
											
										echo "<tr>										
								
											<td><a href='{$this->request->base}/purchase/previewpo2/{$po['po_id']}'>".$po['po_no']."</a></td>
											<td>{$this->ERPfunction->get_date($po['created_date'])}".','. date('H:i',strtotime($po['created_date']))."</td>
											<td>{$this->ERPfunction->get_vendor_name($po['vendor_userid'])}</td>
											<td><span class='custom'>{$this->ERPfunction->get_po_amount($po['po_id'],$date_from,$date_to)}</span></td>
											</tr>";
										}
										$i++;
									
									}
									if($i<3){
										foreach($po_manual_data as $po_manual)
										{
											if($i<3){
												echo "<tr>										
									
												<td><a href='{$this->request->base}/purchase/manualpreviewpo/{$po_manual['po_id']}'>".$po_manual['po_no']."</a></td>
												<td>{$this->ERPfunction->get_date($po_manual['created_date'])}".','. date('H:i',strtotime($po_manual['created_date']))."</td>
												<td>{$this->ERPfunction->get_vendor_name($po_manual['vendor_userid'])}</td>
												<td><span class='custom'>{$this->ERPfunction->get_po_manual_amount($po_manual['po_id'],$date_from,$date_to)}</span></td>
												</tr>";
											}
											$i++;
										}
										
									}
								}else{
									echo '<tr style="color:#000 !important">
											<td colspan="3">No P.O Record Found.</td>						
										</tr>';
								}
								
								
								?>
						  </tbody>
					  </table>                                    
				</div>			
				</div>			
			</div>
		<?php } 
	}
	
	if($role == 'erphead' || $role == 'ceo' || $role == 'md' || $role == 'erpoperator' || $role == 'erpmanager' || $role == 'projectdirector' || $role == 'constructionmanager' || $role == 'billingengineer' || $role == 'purchasehead' || $role == 'purchasemanager' || $role == 'financemanager' || $role == 'accounthead' || $role == 'senioraccountant' || $role == 'siteaccountant' )
		{
			if(isset($project_id)){
			?>
			<div class="block block-drop-shadow dashboard col-xs-12 col-md-12 col-sm-12" id="boxes">
				<div class="head bg-default bg-light-rtl">
					<span>
						<?php 
							echo $this->Html->image('icon/request.png',
							array('class'=>'pull-left','width'=>'40px'));
						?>				
					</span>
					<h2>&nbsp;&nbsp; Work Order (W.O.)  &nbsp;&nbsp;&nbsp;&nbsp; Date From: <?php echo date('d-m-Y',strtotime($date_from)); ?>&nbsp;&nbsp; Date To: <?php echo  date('d-m-Y',strtotime($date_to)); ?></h2>                        
				</div>
				<div class="block block-fill-white no-margin">
				<div class="content list-dash list">
						<table class="table dashboard_info_box">
							<?php
							$count=count($this->ERPfunction->dashboard_wo_list($project_id,$date_from,$date_to));
							
								echo "<tr>
									<td  class='border-right'>Nos. Of W.O Approved</td>
									<td><a href='{$this->request->base}/Contract/worecords/{$project_id}/{$date_from}/{$date_to}'>".$count."<i class='icon-eye-open' style='padding-left:10px;font-size:20px' ></i> </a></td>
											
									<tr>";
							?>
						</table>			
					  <table class="table dashboard_info_box">
							
							<thead>
								<tr>								
									<th>WO No</th>
									<th>WO Date</th>
									<th>Vendor's Name</th>
									<th>Amount</th>
								</tr>
							</thead>
							<tbody>
								<?php $wo_data = $this->ERPfunction->dashboard_wo_list($project_id,$date_from,$date_to); 
								if(!empty($wo_data))
								{
									$i=0;
									foreach($wo_data as $wo)
									{
										
										if($i<=2)
										{
										
										/* echo "<tr>										
								
											<td>{$this->ERPfunction->get_projectcode($po['project_id'])}</td>
											<td>{$this->ERPfunction->get_projectname($po['project_id'])}</td>
											<td>{$this->ERPfunction->get_date($po['created_date'])}".','. date('H:i',strtotime($po['created_date']))."</td>
											<td><span class='custom-circle-icon'>{$po['count']}</span></td>
											</tr>"; */
											
										echo "<tr>										
								
											<td><a href='{$this->request->base}/contract/previewapprovedwo/{$wo['wo_id']}'>".$wo['erp_work_order']['wo_no']."</a></td>
											<td>{$this->ERPfunction->get_date($wo['erp_work_order']['created_date'])}".','. date('H:i',strtotime($wo['erp_work_order']['created_date']))."</td>
											<td>{$this->ERPfunction->get_vendor_name($wo['erp_work_order']['party_userid'])}</td>
											<td><span class='custom'>{$this->ERPfunction->get_wo_amount($wo['wo_id'],$date_from,$date_to)}</span></td>
											</tr>";
										}
										$i++;
									}
								}else{
									echo '<tr style="color:#000 !important">
											<td colspan="3">No W.O Record Found.</td>						
										</tr>';
								}
								
							
							
								?>
						  </tbody>
					  </table>                                    
				</div>			
				</div>			
			</div>
		<?php } 
	}
	if($role == 'erphead' || $role == 'ceo' || $role == 'md' || $role == 'erpoperator' || $role == 'erpmanager' || $role == 'projectdirector' || $role == 'constructionmanager' || $role == 'billingengineer' || $role == 'purchasehead' || $role == 'purchasemanager' || $role == 'siteaccountant' || $role == 'senioraccountant' || $role == 'financemanager' || $role == 'accounthead')
		{
		if(isset($project_id)){
			?>
		<div class="block block-drop-shadow dashboard col-xs-12 col-md-12 col-sm-12" id="boxes">
			<div class="head bg-default bg-light-rtl">
				<span>
					<?php 
						echo $this->Html->image('icon/request.png',
						array('class'=>'pull-left','width'=>'40px'));
					?>				
				</span>
				<h2>&nbsp;&nbsp; Accounts  &nbsp;&nbsp;&nbsp;&nbsp; Date From: <?php echo date('d-m-Y',strtotime($date_from)); ?>&nbsp;&nbsp; Date To: <?php echo  date('d-m-Y',strtotime($date_to)); ?></h2>                        
			</div>
			<div class="block block-fill-white no-margin">
			<div class="content list-dash list">				
				  <table class="table dashboard_info_box">
						<thead>
							<tr>
								<th>Description</th>
								<th>Amounts (Rs.)</th>
								<th>View Details</th>
							</tr>
						</thead>
						<tbody>
							<?php  $account_bill = $this->ERPfunction->dashboard_accounts($project_id,$date_from,$date_to);
									$dashboard_advance = $this->ERPfunction->dashboard_advance($project_id,$date_from,$date_to);  
									$dashboard_view_site = $this->ERPfunction->dashboard_view_site($project_id,$date_from,$date_to);  
									$dashboard_debitnots = $this->ERPfunction->dashboard_debitnots($project_id,$date_from,$date_to);
									if($dashboard_debitnots==null){
										$dashboard_debitnots=0.00;
									}
							?>
							
							<?php  if($role == 'purchasehead' || $role == 'purchasemanager'){
										echo "<tr>
												<td >Bills Received	</td>
												<td> $account_bill
													</td>
												<td> <a href='{$this->request->base}/Accounts/accountlist/'><i class='icon-eye-open' style='padding-left:10px;font-size:20px'></i></a></td>
												
											</tr>";
									}	
									
									else{
										
									if($role == 'erphead' || $role == 'ceo' || $role == 'md' || $role == 'erpoperator' || $role == 'erpmanager' || $role == 'projectdirector' || $role == 'financemanager' || $role == 'accounthead'){
										echo "<tr>
												<td >Bills Received	</td>
												<td> $account_bill
													</td>
												<td> <a href='{$this->request->base}/Accounts/accountlist/'><i class='icon-eye-open' style='padding-left:10px;font-size:20px'></i></a></td>
												
											</tr>";
									}
							?>
							<tr>
								<td >View Site Transactions	</td>
								<td><?php 
									echo $dashboard_view_site;
									 ?></td>
								<td><?php echo "<a href='{$this->request->base}/Humanresource/loanlist/{$project_id}/{$date_from}/{$date_to}'><i class='icon-eye-open' style='padding-left:10px;font-size:20px'></i></a>" ?></td>
							</tr>
							<tr>
								<td >View Advance </td>
								<td><?php 
									echo $dashboard_advance;
									 ?></td>
								<td><?php echo "<a href='{$this->request->base}/Accounts/viewadvance/{$project_id}/{$date_from}/{$date_to}'><i class='icon-eye-open' style='padding-left:10px;font-size:20px'></i></a>" ?></td>
							</tr>
							<tr>
								<td >View Debit Notes </td>
								<td><?php 
									echo $dashboard_debitnots;
									 ?></td>
								<td><?php echo "<a href='{$this->request->base}/Accounts/debitnoterecord/{$project_id}/{$date_from}/{$date_to}'><i class='icon-eye-open' style='padding-left:10px;font-size:20px'></i></a>" ?></td>
							</tr>
							
									<?php } ?>	
					  </tbody>
				  </table>                                    
			</div>			
			</div>			
		</div>
		
		
		<?php 
		}
		}
	
	
	if($role == 'erphead' || $role == 'ceo' || $role == 'md' || $role == 'erpoperator' || $role == 'erpmanager' || $role == 'projectdirector' || $role == 'constructionmanager' || $role == 'billingengineer')
		{
		if(isset($project_id)){
			?>
		<div class="block block-drop-shadow dashboard col-xs-12 col-md-12 col-sm-12" id="boxes">
			<div class="head bg-default bg-light-rtl">
				<span>
					<?php 
						echo $this->Html->image('icon/request.png',
						array('class'=>'pull-left','width'=>'40px'));
					?>				
				</span>
				<h2>&nbsp;&nbsp; H.R.  &nbsp;&nbsp;&nbsp;&nbsp; Date From: <?php echo date('d-m-Y',strtotime($date_from)); ?>&nbsp;&nbsp; Date To: <?php echo  date('d-m-Y',strtotime($date_to)); ?></h2>                        
			</div>
			<div class="block block-fill-white no-margin">
			<div class="content list-dash list">				
				  <table class="table dashboard_info_box">
						
						<tbody>
							<?php $contract = $this->ERPfunction->dashboard_project ($project_id); 
							if(!empty($contract)){
								 if($role == 'constructionmanager' || $role == 'billingengineer') { 
									}
																	
									else{ ?>
										<tr>
											<td class='border-right'>Pay Slip Approval Alerts </td>
											<td><?php echo $this->ERPfunction->dashboard_payslip_alerts($project_id); echo "<a href='{$this->request->base}/Humanresource/salarystatement/{$project_id}'><i class='icon-eye-open' style='padding-left:10px;font-size:20px'></i></a>" ?></td>
											
										</tr>
										<tr>
											<td class='border-right'>View Loan Records</td>
											<td><?php echo "<a href='{$this->request->base}/Humanresource/loanlist/{$project_id}/{$date_from}/{$date_to}'><i class='icon-eye-open' style='padding-left:10px;font-size:20px'></i></a>" ?></td>
										</tr>
										<tr>
											<td class='border-right'>View Personal Records</td>
											<td><?php echo "<a href='{$this->request->base}/Humanresource/emplyeelist/{$project_id}'><i class='icon-eye-open' style='padding-left:10px;font-size:20px'></i></a>" ?></td>
										</tr>
							<?php		} 
							?>
							
							
							<tr>
								<td class='border-right'>View Attendance Records</td>
								<td><?php echo "<a href='{$this->request->base}/Attendance/attendancerecord/{$project_id}/{$date_from}/{$date_to}'><i class='icon-eye-open' style='padding-left:10px;font-size:20px'></i></a>" ?></td>
							</tr>
							
								<?php } ?>
					  </tbody>
				  </table>                                    
			</div>			
			</div>			
		</div>
		
		
		<?php 
		}
		}
		/*
	
		
		if($role == 'erphead' || $role == 'erpmanager' || $role == 'ceo' || $role == 'md' || $role == 'projectdirector' || $role == 'contractadmin' || $role == 'projectcoordinator' || $role == 'accounthead' || $role == 'senioraccountant' || $role == 'financehead' || $role == 'financemanager' || $role == 'hrmanager' || $role == 'purchasehead' || $role == 'purchasemanager' || $role == 'constructionmanager' || $role == 'billingengineer')
		{
		
		?>
		<div class="block block-drop-shadow dashboard col-xs-12 col-md-12 col-sm-12" id="boxes">			
			<div class="head bg-default bg-light-rtl">
				<span>
					<?php 
						echo $this->Html->image('icon/asset-log.png',
						array('class'=>'pull-left','width'=>'40px'));
					?>				
				</span>
				<h2> Latest Projects</h2>                        
			</div>
			<script>
		jQuery(document).ready(function() {
		if($(window).width() <= 1100){
		var table = jQuery('.dashboard_info_box').DataTable({responsive: true});
		}
		if($(window).width() <= 1100){
		jQuery('#project_list').DataTable({responsive: true	 
				// "aoColumns":[
					// {"bSortable": true,sWidth:"1%"},
					// {"bSortable": true,sWidth:"20%"},
					// {"bSortable": true,sWidth:"20%"},
					// {"bSortable": true},					
					// {"bSortable": false,sWidth:"5%"}]
			});
		}
		} );
</script>
			<div class="block block-fill-white no-margin">
			<div class="content list-dash list">				
				  <!--<table class="dataTables_wrapper dashboard_info_box table table-striped table-hover dataTable no-footer dtr-inline">-->
				  <table class="table dashboard_info_box">
						<thead>
							<tr>								
								<th>Project Code</th>
								<th>Project Name</th>
								<th>Contract Start Date</th>
								<th>Revised Amount(Rs)</th>
								<th display="no">Revised Completion Date</th>
							</tr>
						</thead>
						<tbody>
							<?php $projects_data = $this->ERPfunction->dashboard_project_list(); 
							if(!empty($projects_data))
							{
								foreach($projects_data as $project)
								{
									if(!empty($project['exten_cmp_date']))
									{
										$actual_cmp_date = $this->ERPfunction->get_date($project['exten_cmp_date']);
									}
									else
									{
										$actual_cmp_date = "";
									}
									echo "<tr>
										<td class='block-fill-white'>{$project['project_code']}</td>
										<td>{$project['project_name']}</td>
										<td>{$this->ERPfunction->get_date($project['contract_start_date'])}</td>
										<td>{$project['revise_amount']}</td>
										<td>{$actual_cmp_date}</td>
										</tr>";									
								}
							}else{
								echo '<tr style="color:#000 !important">
										<td colspan="3">No Project Record Found.</td>						
									</tr>';
							}
							
							
							?>
					  </tbody>
				  </table>                                    
			</div>			
			</div>			
		</div>
		<?php 
		}
		
		if($role == 'erphead' || $role == 'ceo' || $role == 'md' || $role == 'projectdirector' || $role == 'contractadmin' || $role == 'projectcoordinator' || $role == 'accounthead' || $role == 'senioraccountant' || $role == 'financehead' || $role == 'financemanager' || $role == 'constructionmanager' || $role == 'billingengineer')
		{
		
		?>
		<div class="block block-drop-shadow dashboard col-xs-12 col-sm-12 col-md-12" id="boxes">
			<div class="head bg-default bg-light-rtl">
				<span>
					<?php 
						echo $this->Html->image('icon/bill.png',
						array('class'=>'pull-left','width'=>'40px'));
					?>				
				</span>
				<h2>&nbsp;&nbsp; Latest Added RA Bills</h2>                        
			</div>
			<div class="block block-fill-white no-margin">
			<div class="content list-dash list">				
				  <table class="table dashboard_info_box">
						<thead>
							<tr>								
								<th>Project Code</th>
								<th>Project Name</th>
								<th>RA Bill No.</th>
								<th>Net Amount<br>(Rs)</th>
							</tr>	
							</thead>
							<tbody>
							<?php $ra_data = $this->ERPfunction->dashboard_ra_list(); 
							if(!empty($ra_data))
							{
								foreach($ra_data as $ra)
								{
									echo "<tr>										
										
										<td>{$ra['project_code']}</td>
										<td>{$this->ERPfunction->get_projectname_by_code($ra['project_code'])}</td>
										<td class='block-fill-white'>{$ra['ra_bill_no']}</td>
										<td>{$ra['total_paid_amt']}</td>
										</tr>";
								}
							}else{
								echo '<tr style="color:#000 !important">
										<td colspan="3">No R.A Bills Record Found.</td>						
									</tr>';
							}
							
							
							?>
					  </tbody>
				  </table>                                    
			</div>			
			</div>			
		</div>
<?php 
		}  
		
		if($role == 'erphead' || $role == 'ceo' || $role == 'md' || $role == 'projectdirector' || $role == 'contractadmin' || $role == 'projectcoordinator' || $role == 'accounthead' || $role == 'senioraccountant' || $role == 'financehead' || $role == 'financemanager' || $role == 'constructionmanager' || $role == 'billingengineer')
		{
		
		?>	
		<div class="block block-drop-shadow dashboard col-xs-12 col-sm-12 col-md-12" id="boxes">
			<div class="head bg-default bg-light-rtl">
				<span>
					<?php 
						echo $this->Html->image('icon/price.png',
						array('class'=>'pull-left','width'=>'40px'));
					?>				
				</span>
				<h2>&nbsp;&nbsp; Latest Price Variation</h2>                        
			</div>
			<div class="block block-fill-white no-margin">
			<div class="content list-dash list">				
				  <table class="table dashboard_info_box">
						<thead>
							<tr>
								<th>Project Code</th>
								<th>Project Name</th>								
								<th>Price Variation No.</th>
								<th>Net Amount(Rs)</th>
							</tr>	
							</thead>
							<tbody>
							<?php $price_variation_data = $this->ERPfunction->dashboard_price_variation_list(); 
							if(!empty($price_variation_data))
							{
								foreach($price_variation_data as $price_variation)
								{
									echo "<tr>										
										<td>{$price_variation['project_code']}</td>
										<td>{$this->ERPfunction->get_projectname_by_code($price_variation['project_code'])}</td>
										<td class='block-fill-white'>{$price_variation['bill_no']}</td>
										<td>{$price_variation['paid_amt']}</td>
										</tr>";
								}
							}else{
								echo '<tr style="color:#000 !important">
										<td colspan="3">No Price Variation Record Found.</td>						
									</tr>';
							}
							
							
							?>
					  </tbody>
				  </table>                                    
			</div>			
			</div>			
		</div>
	<?php
	 } 
	?>
	<?php 
		<div class="block block-drop-shadow dashboard">
			<div class="head bg-default bg-light-rtl">
				<span>
					<?php 
						echo $this->Html->image('icon/asset.png',
						array('class'=>'pull-left','width'=>'40px'));
					?>				
				</span>
				<h2>&nbsp;&nbsp; Latest SST</h2>        
			</div>
			<div class="block block-fill-white no-margin">
			<div class="content list-dash list">				
				  <table class="table dashboard_info_box">
						<tbody>
							<tr>								
								<th>SST No.</th>
								<th>Project Name</th>
								<th>Created Date</th>
							</tr>	
							<?php $SST_data = $this->ERPfunction->dashboard_Latest_SST_list(); 
							if(!empty($SST_data))
							{
								foreach($SST_data as $SST)
								{
									echo "<tr>										
										<td class='block-fill-white'>{$SST['sst_no']}</td>
										<td>{$this->ERPfunction->get_projectname($SST['project_id'])}</td>
										<td>{$SST['created_date']}</td>
										</tr>";
								}
							}else{
								echo '<tr style="color:#000 !important">
										<td colspan="3">No SST Record Found.</td>						
									</tr>';
							}
							
							
							?>
					  </tbody>
				  </table>                                    
			</div>			
			</div>		
		</div>
		*/?>
	</div>
<?php
	if($role == 'erphead' || $role == 'ceo' || $role == 'md' || $role == 'erpoperator' || $role == 'erpmanager' || $role == 'projectdirector' || $role == 'constructionmanager' || $role == 'billingengineer' || $role == 'purchasehead' || $role == 'purchasemanager' || $role == 'asset-inventoryhead' || $role == 'materialmanager' || $role == 'financemanager' || $role == 'accounthead' || $role == 'siteaccountant' || $role == 'senioraccountant')
		{
			if(isset($project_id)){
		?>	
	<div class="col-md-12 col-sm-12 col-xs-12">
		
		<div class="head bg-default bg-light-rtl">
				<span>
					<?php 
						echo $this->Html->image('icon/price.png',
						array('class'=>'pull-left','width'=>'40px'));
					?>				
				</span>
				<h2>&nbsp;&nbsp; Inventory(Class A Items) &nbsp;&nbsp;&nbsp;&nbsp; Date From: <?php echo date('d-m-Y',strtotime($date_from)); ?>&nbsp;&nbsp; Date To: <?php  echo date('d-m-Y',strtotime($date_to)); ?></h2>
				
			</div>
			<div class="block block-fill-white no-margin">
			<div class="content list-dash list">				
				  <table class="table dashboard_info_box">
						<thead>
							<tr>
								<th>Material Name</th>
								<th>Max Purchase Level</th>								
								<th>Stock In</th>
								<th>Stock Out</th>
								<th>Total Stock In</th>
								<th>Total Stock Out</th>
								<th>Current Balance</th>
								<th>Unit</th>
								<th>View</th>
							</tr>	
							</thead>
							<tbody>
							<?php $invetory_data = $this->ERPfunction->dashboard_inventory_list($project_id, $date_from,$date_to); 
							
							if(!empty($invetory_data))
							{
								foreach($invetory_data as $invetory)
								{
									
									echo "<tr>										
										<td>{$this->ERPfunction->get_material_title($invetory['material_id'])}</td>
										<td>{$invetory['max_quantity']}</td>
										<td>".number_format($this->ERPfunction->dashboard_inventory_total($project_id,$date_from,$date_to,'total_in',$invetory['material_id']),2,'.','')."</td>
										<td>".number_format($this->ERPfunction->dashboard_inventory_total($project_id,$date_from,$date_to,'total_out',$invetory['material_id']),2,'.','')."</td>
										<td>".number_format($this->ERPfunction->dashboard_inventory_stock($project_id,$invetory['material_id'],'stock_in'),2,'.','')."</td>
										<td>".number_format($this->ERPfunction->dashboard_inventory_stock($project_id,$invetory['material_id'],'stock_out'),2,'.','') ."</td>
										<td>".number_format($this->ERPfunction->get_current_stock($project_id,$invetory['material_id']),2,'.','') ."</td>
										<td>{$this->ERPfunction->get_items_units($invetory['material_id'])}</td>
										<td><a href='Inventory/stockledger/{$project_id}/{$invetory['material_id']}' target='_blank' class='btn btn-primary btn-clean'><i class='icon-eye-open'></i> View</a></td>
										</tr>";
								}
							}else{
								echo '<tr style="color:#000 !important">
										<td colspan="9">No Invetory Data Found.</td>						
									</tr>';
							}
							
							
							?>
					  </tbody>
				  </table>                                    
			</div>			
			</div>			
	
	</div>
	<?php 
		}
		}
	?>
</div>
</div>
</div>
<script>
var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
jQuery('.datep').datepicker({
		dateFormat: "dd-mm-yy",
		  changeMonth: true,
	        changeYear: true,
	        yearRange:'-65:+0',
	        onChangeMonthYear: function(year, month, inst) {
	            jQuery(this).val(month + "-" + year);
	        }                    
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
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'ingrnprojectdetaillppo'));?>",
                data:curr_data,
                async:false,
                success: function(response){					
					var json_obj = jQuery.parseJSON(response);					
					jQuery('#project_code').val(json_obj['project_code']);						
					return false;
                },
                error: function (e) {
                     alert('Error');
					 console.log(e.responseText);
                }
            });
			
	});

</script>