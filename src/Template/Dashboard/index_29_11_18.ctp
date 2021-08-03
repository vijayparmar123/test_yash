<?php
//$this->extend('/Common/menu')
echo $this->Html->script('plugins/fullcalendar/fullcalendar.min.js');
echo $this->Html->css('plugins/fullcalendar/fullcalendar.css');

$user_id = $this->request->session()->read('user_id');
$role = $this->ERPfunction->get_user_role($user_id);
//var_dump($role);
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
<div class="row" style="margin-top: 10px;">
	<div class="col-md-6 col-sm-6 col-xs-12">
		<div id="calendar" class="fc fc-ltr bg-default bg-light-rtl block-drop-shadow col-xs-12 col-md-12 col-sm-12" style="border-radius: 3px;">
		</div>
		<?php 
		if($role == 'erphead' || $role == 'ceo' || $role == 'md' || $role == 'projectdirector' || $role == 'projectcoordinator')
		{
		?>
		<div class="block block-drop-shadow dashboard col-xs-12 col-md-12 col-sm-12" id="boxes">
			<div class="head bg-default bg-light-rtl">
				<span>
					<?php 
						echo $this->Html->image('icon/request.png',
						array('class'=>'pull-left','width'=>'40px'));
					?>				
				</span>
				<h2>&nbsp;&nbsp; Latest Purchase Order</h2>                        
			</div>
			<div class="block block-fill-white no-margin">
			<div class="content list-dash list">				
				  <table class="table dashboard_info_box">
						<thead>
							<tr>								
								<th>Project Code</th>
								<th>Project Name</th>
								<th>Created Date</th>
								<th>No. of Generated PO today</th>
							</tr>
                        </thead>
						<tbody>
							<?php $po_data = $this->ERPfunction->dashboard_po_list(); 
							if(!empty($po_data))
							{
								foreach($po_data as $po)
								{
									echo "<tr>										
							
										<td>{$this->ERPfunction->get_projectcode($po['project_id'])}</td>
										<td>{$this->ERPfunction->get_projectname($po['project_id'])}</td>
										<td>{$this->ERPfunction->get_date($po['created_date'])}".','. date('H:i',strtotime($po['created_date']))."</td>
										<td><span class='custom-circle-icon'>{$po['count']}</span></td>
										</tr>";
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
	 
		if($role == 'erphead' || $role == 'erpmanager' || $role == 'ceo' || $role == 'md' || $role == 'projectdirector' || $role == 'contractadmin' || $role == 'projectcoordinator' || $role == 'accounthead' || $role == 'senioraccountant' || $role == 'financehead' || $role == 'financemanager' || $role == 'constructionmanager')
		{
		
		?>
		<div class="block block-drop-shadow dashboard col-xs-12 col-sm-12 col-md-12" id="boxes">
			<div class="head bg-default bg-light-rtl">
				<span>
					<?php 
						echo $this->Html->image('icon/asset.png',
						array('class'=>'pull-left','width'=>'40px'));
					?>				
				</span>
				<h2>&nbsp;&nbsp; Latest Asset Purchase & Transfer</h2>        
			</div>
			<div class="block block-fill-white no-margin">
			<div class="content list-dash list">				
				  <table class="table dashboard_info_box">
						<thead>
							<tr>								
								<th>Asset Code</th>
								<th>Asset Name</th>
								<th>Created Date</th>
							</tr>	
							</thead>
							<tbody>
							<?php $Asset_Purchase_Transfer_data = $this->ERPfunction->dashboard_Asset_Purchase_Transfer_list(); 
							if(!empty($Asset_Purchase_Transfer_data))
							{
								foreach($Asset_Purchase_Transfer_data as $Asset_Purchase_Transfer)
								{
									echo "<tr>										
										<td class='block-fill-white'>{$Asset_Purchase_Transfer['asset_code']}</td>
										<td>{$Asset_Purchase_Transfer['asset_name']}</td>
										<td>{$this->ERPfunction->get_date($Asset_Purchase_Transfer['created_date'])}".','. date('H:i',strtotime($Asset_Purchase_Transfer['created_date']))."</td>
										</tr>";
								}
							}else{
								echo '<tr style="color:#000 !important">
										<td colspan="3">No Asset Purchase & Transfer Record Found.</td>						
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
		if($role == 'erphead' || $role == 'erpmanager' || $role == 'ceo' || $role == 'md' || $role == 'projectdirector' || $role == 'projectcoordinator' || $role == 'accounthead' || $role == 'senioraccountant' || $role == 'purchasehead' || $role == 'purchasemanager' || $role == 'constructionmanager')
		{
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
							
							<?php $alerts = $this->ERPfunction->dashboard_alert_list(); 
							if(!empty($alerts))
							{
							
								if($role == 'erphead' || $role =='erpmanager' || $role == 'projectdirector' || $role == 'projectcoordinator' || $role == 'purchasehead' || $role == 'purchasemanager' || $role == 'constructionmanager')
								{	
								echo "<tr>
									<td><a href='{$this->request->base}/Inventory/approvedpr'>Purchase Request Alerts</a></td>
									<td><span class='custom-circle-icon'>{$alerts['pr_alert']}</span></td>
									</tr>";
								echo "<tr>
									<td ><a href='{$this->request->base}/Inventory/approvepo'>Purchase Order Alerts</a></td>
									<td><span class='custom-circle-icon'>{$alerts['po_alert']}</span></td>
									</tr>";	
								}
								if($role == 'erphead' || $role =='erpmanager' || $role == 'projectdirector' || $role == 'projectcoordinator' || $role == 'accounthead' || $role == 'senioraccountant' || $role == 'constructionmanager')
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
								if($role == 'erphead' || $role =='erpmanager' || $role == 'projectdirector' || $role == 'projectcoordinator' || $role == 'constructionmanager')
								{	
								echo "<tr>
									<td><a href='{$this->request->base}/Inventory/approverbn'>R.B.N. Alerts</a></td>
									<td><span class='custom-circle-icon'>{$alerts['rbn_alert']}</span></td>
									</tr>";	
								echo "<tr>
									<td><a href='{$this->request->base}/Inventory/approveis'>Issue Slip Alerts</a></td>
									<td><span class='custom-circle-icon'>{$alerts['is_alert']}</span></td>
									</tr>";	
								echo "<tr>
									<td><a href='{$this->request->base}/Inventory/approvesst'>S.S.T. Alerts</a></td>
									<td><span class='custom-circle-icon'>{$alerts['sst_alert']}</td>
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
	<?php /*
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
</div>
</div>
</div>