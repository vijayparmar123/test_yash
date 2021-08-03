<?php
// src/Template/Element/header.ctp
use Cake\Routing\Router;
?>
<!-- Header Navigation -->
<div class="col-md-2">
	<div class="block bg-default bg-light-rtl">
		<div class="user bg-default bg-light-rtl">
			<div class="info">
			<?php 
			$user_id = $this->request->session()->read('user_id');
			echo $this->Html->image($this->ERPfunction->get_user_image($user_id),array('class'=>'img-circle img-thumbnail')); ?>
			<h5 style="float:left;width:100%;margin:10px 0px 0px;text-shadow: 3px 1px 3px #000;"><?php echo $this->ERPfunction->get_user_name($user_id);?></h5>
			</div>
		</div>
	<div class="content list-group list-group-icons">
		<div id="cssmenu">                  
			<ul>
				<li class="">
					<a href="<?php echo $this->ERPfunction->action_link('Dashboard');?>" class="list-group-item">
						<span class="icon-tasks"></span>Dashboard
					</a>
				</li>
				<li class='has-sub'>
					<a href="<?php echo $this->ERPfunction->action_link('Ceo');?>" class="list-group-item">
						<span class="icon-user"></span>User
					</a>						
					<ul>
						<li class=''>
							<a href="<?php echo $this->ERPfunction->action_link('Usermanage','add');?>" class="list-group-item">
								Add User
							</a>
						</li>
						<li class=''>
							<a href="<?php echo $this->ERPfunction->action_link('Usermanage');?>" class="list-group-item">
								Manage User
							</a>
						</li>						
					
						<li class=''>
							<a href="<?php echo $this->ERPfunction->action_link('Usermanage','view');?>" class="list-group-item">
								View User
							</a>
						</li>
					</ul>
				</li>
				<li class='has-sub'>
					<a href="<?php echo $this->ERPfunction->action_link('Vendor');?>" class="list-group-item">
						<span class="icon-tasks"></span>Vendor
					</a>
					<ul>
						<li class=''>
							<a href="<?php echo $this->ERPfunction->action_link('Vendor','add');?>" class="list-group-item">
								Add Vendor
							</a>
						</li>	
						<li class=''>
							<a href="<?php echo $this->ERPfunction->action_link('Vendor');?>" class="list-group-item">
								Manage Vendor
							</a>
						</li>	
						<li class=''>
							<a href="<?php echo $this->ERPfunction->action_link('Vendor','view');?>" class="list-group-item">
								View Vendor
							</a>
						</li>										
					</ul>
				</li>
				<li class='has-sub'>
					<a href="<?php echo $this->ERPfunction->action_link('Projects');?>" class="list-group-item">
						<span class="icon-tasks"></span>Project
					</a>
					<ul>
						<li class=''>
						<a href="<?php echo $this->ERPfunction->action_link('Projects','add');?>" class="list-group-item">
							Add Project
						</a>
						</li>
						<li class=''>
						<a href="<?php echo $this->ERPfunction->action_link('Projects','index');?>" class="list-group-item">
							View Project
						</a>
						</li>
						<li class=''>
						<a href="<?php echo $this->ERPfunction->action_link('Projects','openingstock');?>" class="list-group-item">
							Opening Stock
						</a>
						</li>
												
					</ul>
				</li>
				<li class="has-sub">
					<a href="<?php echo $this->ERPfunction->action_link('Contract');?>" class="list-group-item">
						<span class="icon-tasks"></span>Contract Admin
					</a>
					<ul class="has-sub">
						<li>
							<a href="<?php echo $this->ERPfunction->action_link('Contract','addinward');?>" class="list-group-item">
								Inward Correspondence
							</a>
						</li>


						<li>
							<a href="<?php echo $this->ERPfunction->action_link('Contract','index');?>" class="list-group-item">
								View Inward Correspondence
							</a>
						</li>


						<li class=''>
							<a href="<?php echo $this->ERPfunction->action_link('Contract','addoutward');?>" class="list-group-item">
								Outward Corresponadace
							</a>
						</li>


						<li class=''>
							<a href="<?php echo $this->ERPfunction->action_link('Contract','viewoutward');?>" class="list-group-item">
								View Outward Corresponadace
							</a>
						</li>
						

						<li class=''>
							<a href="<?php echo $this->ERPfunction->action_link('Contract','addrabill');?>" class="list-group-item">
								Add R. A. Bill
							</a>
						</li>

						<li class=''>
							<a href="<?php echo $this->ERPfunction->action_link('Contract','viewrabill');?>" class="list-group-item">
								View R. A. Bill
							</a>
						</li>

						<li class=''>
							<a href="<?php echo $this->ERPfunction->action_link('Contract','addpricevariation');?>" class="list-group-item">
								Add Price Variation
							</a>
						</li>	
						<li>
							<a href="<?php echo $this->ERPfunction->action_link('Contract','addagency');?>" class="list-group-item">
								Add Agency
							</a>
						</li>
						<li>
							<a href="<?php echo $this->ERPfunction->action_link('Contract','agencylist');?>" class="list-group-item">
								Edit Agency
							</a>
						</li>
						<li class=''>
							<a href="<?php echo $this->ERPfunction->action_link('Contract','viewpricevariation');?>" class="list-group-item">
								View Price Variation
							</a>
						</li>

					</ul>
				</li>
				<li class="has-sub">
					<a href="<?php echo $this->ERPfunction->action_link('Assets');?>" class="list-group-item">
						<span class="icon-tasks"></span>Assets
					</a>
					<ul>
						<li>
						<a href="<?php echo $this->ERPfunction->action_link('Assets','add');?>" class="list-group-item">
							Add Assets
						</a>
						</li>
						<li class=''>
						<a href="<?php echo $this->ERPfunction->action_link('Assets','trasnsferaccept');?>" class="list-group-item">
							Asset Transfer & Accept
						</a>
						</li>
						<li class=''>
						<a href="<?php echo $this->ERPfunction->action_link('Assets','soldtheft');?>" class="list-group-item">
							Sold & Theft
						</a>
						</li>
						<li class=''>
						<a href="<?php echo $this->ERPfunction->action_link('Assets','addmaintenance');?>" class="list-group-item">
							Assets Maintenance
						</a>
						</li>
						<li class=''>
						<a href="<?php echo $this->ERPfunction->action_link('Assets','aprovemaintenance');?>" class="list-group-item">
							Maintance list and approve
						</a>
						</li>
						<li class=''>
						<a href="<?php echo $this->ERPfunction->action_link('Assets','viewassets');?>" class="list-group-item">
							Assest Record 
						</a>
						</li>
					</ul>
				</li>
				<li class="has-sub">
					<a href="<?php echo $this->ERPfunction->action_link('Purchase','viewmaterial');?>" class="list-group-item">
						<span class="icon-tasks"></span>Purchase
					</a>
					<ul>
						<li>
						<a href="<?php echo $this->ERPfunction->action_link('Purchase','category');?>" class="list-group-item">
							Category List
						</a>
						</li>
						<li class=''>
						<a href="<?php echo $this->ERPfunction->action_link('Purchase','addbrand');?>" class="list-group-item">
							Add Brand
						</a>
						</li>
						<li class=''>
						<a href="<?php echo $this->ERPfunction->action_link('Purchase','brandlist');?>" class="list-group-item">
							View Brand
						</a>
						</li>
						<li class=''>
						<a href="<?php echo $this->ERPfunction->action_link('Purchase','addmaterial');?>" class="list-group-item">
							Add Material
						</a>
						</li>
						<li class=''>
						<a href="<?php echo $this->ERPfunction->action_link('Purchase','viewmaterial');?>" class="list-group-item">
							View Material
						</a>
						</li>	
						<li class=''>
						<a href="<?php echo $this->ERPfunction->action_link('Purchase','viewpo');?>" class="list-group-item">
							View P.O.
						</a>
						</li>	
					</ul>
				</li>


				<li class="has-sub">
					<a href="<?php echo $this->ERPfunction->action_link('accounts','index');?>" class="list-group-item">
						<span class="icon-tasks"></span>Accounts
					</a>
					<ul>

						<li class=''>
						<a href="<?php echo $this->ERPfunction->action_link('accounts','addinwardbill');?>" class="list-group-item">
							Add Inward Bill
						</a>
						</li>

						<li class=''>
						<a href="<?php echo $this->ERPfunction->action_link('accounts','pendingbills');?>" class="list-group-item">
							Pending Bills
						</a>
						</li>


						<li class=''>
						<a href="<?php echo $this->ERPfunction->action_link('accounts','acceptbills');?>" class="list-group-item">
							Accept Bills
						</a>
						</li>

						<li class=''>
						<a href="<?php echo $this->ERPfunction->action_link('accounts','index');?>" class="list-group-item">
							View Inward Bill
						</a>
						</li>

						<li class=''>
						<a href="<?php echo $this->ERPfunction->action_link('accounts','grnalert');?>" class="list-group-item">
							G.R.N Alert
						</a>
						</li>

						<li class=''>
						<a href="<?php echo $this->ERPfunction->action_link('accounts','mrnalert');?>" class="list-group-item">
							M.R.N Alert

						</a>
						</li>

					</ul>
				</li>


				<li class="has-sub">
					<a href="<?php echo $this->ERPfunction->action_link('Humanresource');?>" class="list-group-item">
						<span class="icon-tasks"></span>Human Resource
					</a>
					<ul>
						<li class=''>
						<a href="<?php echo $this->ERPfunction->action_link('Humanresource','addemployee');?>" class="list-group-item">
							Add Employee
						</a>
						</li>
						<li class=''>
						<a href="<?php echo $this->ERPfunction->action_link('Humanresource');?>" class="list-group-item">
							View Employee
						</a>
						</li>
						<li class=''>
						<a href="<?php echo $this->ERPfunction->action_link('Humanresource','leavesheet');?>" class="list-group-item">
							Leave Sheet
						</a>
						</li>
						<li class=''>
						<a href="<?php echo $this->ERPfunction->action_link('Humanresource','leavesummary');?>" class="list-group-item">
							Leave Summary
						</a>
						</li>
						<li class=''>
						<a href="<?php echo $this->ERPfunction->action_link('Humanresource','salaryslip');?>" class="list-group-item">
							Salary Slip
						</a>
						</li>
						<li class=''>
						<a href="<?php echo $this->ERPfunction->action_link('Humanresource','salarystatement');?>" class="list-group-item">
							Salary Statement
						</a>
						</li>
					</ul>
				</li>
				
				<li class="has-sub">
					<a href="<?php echo $this->ERPfunction->action_link('Inventory');?>" class="list-group-item">
						<span class="icon-tasks"></span>Inventory
					</a>
					<ul>
						
						<li class=''>
						<a href="<?php echo $this->ERPfunction->action_link('Inventory','approvedpr');?>" class="list-group-item">
							Approve P.R.
						</a>
						</li>
						<li class=''>
						<a href="<?php echo $this->ERPfunction->action_link('Inventory','viewpr');?>" class="list-group-item">
							View P.R.
						</a>
						</li>


						<li class=''>
						<a href="<?php echo $this->ERPfunction->action_link('Inventory','approvepo');?>" class="list-group-item">
							Approve P.O.
						</a>
						</li>
						<li class=''>
						<a href="<?php echo $this->ERPfunction->action_link('Inventory','viewpo');?>" class="list-group-item">
							View P.O.
						</a>
						</li>
					
						<li class=''>
						<a href="<?php echo $this->ERPfunction->action_link('Inventory','approvegrn');?>" class="list-group-item">
							Approve G.R.N.
						</a>
						</li>
						<li class=''>
						<a href="<?php echo $this->ERPfunction->action_link('Inventory','viewgrn');?>" class="list-group-item">
							View G.R.N.
						</a>
						</li>
					
						<li class=''>
						<a href="<?php echo $this->ERPfunction->action_link('Inventory','approveis');?>" class="list-group-item">
							Approve I.S.
						</a>
						</li>
						<li class=''>
						<a href="<?php echo $this->ERPfunction->action_link('Inventory','viewis');?>" class="list-group-item">
							View I.S.
						</a>
						</li>
					
						<li class=''>
						<a href="<?php echo $this->ERPfunction->action_link('Inventory','approvemrn');?>" class="list-group-item">
							Approve M.R.N.
						</a>
						</li>
						<li class=''>
						<a href="<?php echo $this->ERPfunction->action_link('Inventory','viewmrn');?>" class="list-group-item">
							View M.R.N.
						</a>
						</li>
						
						<li class=''>
						<a href="<?php echo $this->ERPfunction->action_link('Inventory','approverbn');?>" class="list-group-item">
							Approve R.B.N
						</a>
						</li>
						<li class=''>
						<a href="<?php echo $this->ERPfunction->action_link('Inventory','viewrbn');?>" class="list-group-item">
							View R.B.N
						</a>
						</li>
					
						<li class=''>
						<a href="<?php echo $this->ERPfunction->action_link('Inventory','approvesst');?>" class="list-group-item">
							Approve S.S.T.
						</a>
						</li>
						<li class=''>
						<a href="<?php echo $this->ERPfunction->action_link('Inventory','viewsst');?>" class="list-group-item">
							View S.S.T.
						</a>
						</li>
						<li class=''>
						<a href="<?php echo $this->ERPfunction->action_link('Inventory','stockledger');?>" class="list-group-item">
							View Stock Ledger
						</a>
						</li>
					</ul>
				</li>
				<li class="">
					<a href="<?php echo $this->ERPfunction->action_link('Login','logout');?>" class="list-group-item">
						<span class="icon-tasks"></span>Singout
					</a>
				</li>
			</ul>		   
		</div>
	</div>
    </div> 
</div>