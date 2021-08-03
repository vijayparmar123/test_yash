<div class="col-md-10 user_manage">

<div class="row">
	<div class="col-md-4">
		<div class="block block-drop-shadow infobox">
		   <div class="user bg-default bg-light-rtl">
		         <div class="info">                                                                               
		             <img src="<?php echo $this->request->base;?>/img/icon/bill.png" class="img-circle img-thumbnail">				
		         </div>
				 <h4>Bills</h4>
		    </div>
			
		    <div class="content list-group bg-default bg-light-rtl">
				<a href="<?php echo $this->request->base;?>/Accounts/addinwardbill" class="list-group-item">Inward Bills </a>
				<a href="<?php echo $this->request->base;?>/Accounts/acceptbills" class="list-group-item">Accept Bills</a>	
				<a href="<?php echo $this->request->base;?>/Accounts/pendingbills" class="list-group-item">Pending Bills</a>
			</div>
		</div>
	</div>
		<!--<div class="col-md-4">
		<div class="block block-drop-shadow infobox">
		   <div class="user bg-default bg-light-rtl">
		         <div class="info">                                                                               
		             <img src="<?php echo $this->request->base;?>/img/icon/alerts.png" class="img-circle img-thumbnail">				
		         </div>
				 <h4>G.R.N / M.R.N. Alerts</h4>
		    </div>
		    <div class="content list-group bg-default bg-light-rtl">
				<a href="<?php echo $this->request->base;?>/Accounts/grnalert" class="list-group-item">G.R.N Alert</a>
		        <a href="<?php echo $this->request->base;?>/Accounts/mrnalert" class="list-group-item">M.R.N. Alert</a>				 	
		    </div>
		</div>
	</div>-->
	<div class="col-md-4">
		<div class="block block-drop-shadow infobox">
		   <div class="user bg-default bg-light-rtl">
		         <div class="info">                                                                               
		             <img src="<?php echo $this->request->base;?>/img/icon/asset-log.png" class="img-circle img-thumbnail">				
		         </div>
				 <h4>Records</h4>
		    </div>
		    <div class="content list-group bg-default bg-light-rtl">
				<a href="<?php echo $this->request->base;?>/Accounts/accountlist" class="list-group-item">Bill Records</a>
				<a href="<?php echo $this->request->base;?>/Accounts/inwardpayment/" class="list-group-item">Payment Notification</a>
			 </div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="block block-drop-shadow infobox">
		   <div class="user bg-default bg-light-rtl">
		         <div class="info">                                                                               
		             <img src="<?php echo $this->request->base;?>/img/icon/asset-log.png" class="img-circle img-thumbnail">				
		         </div>
				 <h4>Debit Note</h4>
		    </div>
		    <div class="content list-group bg-default bg-light-rtl">
				<a href="<?php echo $this->request->base;?>/Accounts/adddebitnote/" class="list-group-item">Add Debit Note</a>
				<a href="<?php echo $this->request->base;?>/Accounts/debitnotealert/" class="list-group-item">Debit Note Alert</a>
				<a href="<?php echo $this->request->base;?>/Accounts/debitnoterecord/" class="list-group-item">View Debit Note</a>
			 </div>
		</div>
	</div>
</div>

<div class="row">

	<div class="col-md-4">
		<div class="block block-drop-shadow infobox">
		   <div class="user bg-default bg-light-rtl">
		         <div class="info">                                                                               
		             <img src="<?php echo $this->request->base;?>/img/icon/agency.png" class="img-circle img-thumbnail">				
		         </div>
				 <h4>Vendor Master</h4>
		    </div>
		    <div class="content list-group bg-default bg-light-rtl">
			 <!--<a href="<?php echo $this->request->base;?>/Contract/addagency" class="list-group-item">Add Agency</a>
		     <a href="<?php echo $this->request->base;?>/Contract/agencylist" class="list-group-item">Agency List</a>-->
			 <a href="<?php echo $this->request->base;?>/Purchase/viewvendor" class="list-group-item">Vendor Master</a>		         
			</div>
		</div>
	</div>
	
	<!--<div class="col-md-4">
		<div class="block block-drop-shadow infobox">
		   <div class="user bg-default bg-light-rtl">
		         <div class="info">                                                                               
		             <img src="<?php echo $this->request->base;?>/img/icon/manage_vendor.png" class="img-circle img-thumbnail">				
		         </div>
				 <h4>Manage Vendor</h4>
		    </div>
		    <div class="content list-group bg-default bg-light-rtl">
				<!--<a href="<?php echo $this->request->base;?>/Purchase/addvendor" class="list-group-item">Add Vendor</a>-->
				<!-- <a href="<?php //echo $this->request->base;?>/Purchase/editvendor" class="list-group-item">Edit Vendor</a> -->
				<!--<a href="<?php echo $this->request->base;?>/Purchase/viewvendor" class="list-group-item">Vendor List</a>
		    </div>
		</div>
	</div>-->
	<div class="col-md-4">
		<div class="block block-drop-shadow infobox">
		   <div class="user bg-default bg-light-rtl">
		         <div class="info">                                                                               
		             <img src="<?php echo $this->request->base;?>/img/icon/asset-log.png" class="img-circle img-thumbnail">				
		         </div>
				 <h4>Advance</h4>
		    </div>
		    <div class="content list-group bg-default bg-light-rtl">
				<a href="<?php echo $this->request->base;?>/Accounts/advancerequest/" class="list-group-item">Add Advance</a>
				<a href="<?php echo $this->request->base;?>/Accounts/viewrequest/" class="list-group-item">Advance Alert</a>
				<a href="<?php echo $this->request->base;?>/Accounts/viewadvance/" class="list-group-item">View Advance</a>
			 </div>
		</div>
	</div>
		
	<div class="col-md-4">
		<div class="block block-drop-shadow infobox">
		   <div class="user bg-default bg-light-rtl">
		         <div class="info">                                                                               
		             <img src="<?php echo $this->request->base;?>/img/icon/asset-log.png" class="img-circle img-thumbnail">				
		         </div>
				 <h4>Manage Site Accounts</h4>
		    </div>
		    <div class="content list-group bg-default bg-light-rtl">
				<a href="<?php echo $this->request->base;?>/Accounts/createaccount/" class="list-group-item">Create Account</a>
				<a href="<?php echo $this->request->base;?>/Accounts/expensehead/" class="list-group-item">Create Expense Head</a>
				<a href="<?php echo $this->request->base;?>/Accounts/viewexpensehead/" class="list-group-item">View Expense Head</a>
			 </div>
		</div>
	</div>
</div>

<div class="row">	
	
	<div class="col-md-4">
		<div class="block block-drop-shadow infobox">
		   <div class="user bg-default bg-light-rtl">
		         <div class="info">                                                                               
		             <img src="<?php echo $this->request->base;?>/img/icon/asset-log.png" class="img-circle img-thumbnail">				
		         </div>
				 <h4>Site Transactions</h4>
		    </div>
		    <div class="content list-group bg-default bg-light-rtl">
				<a href="<?php echo $this->request->base;?>/Accounts/amountissued/" class="list-group-item">Amount Issued</a>
				<a href="<?php echo $this->request->base;?>/Accounts/addexpence/" class="list-group-item">Add Expence</a>
				<a href="<?php echo $this->request->base;?>/Accounts/expencealert/" class="list-group-item">Expence Alert</a>
				<a href="<?php echo $this->request->base;?>/Accounts/sitetransactions/" class="list-group-item">View Site Transactions</a>
			 </div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="block block-drop-shadow infobox">
		   <div class="user bg-default bg-light-rtl">
		         <div class="info">                                                                               
		             <img src="<?php echo $this->request->base;?>/img/icon/asset-log.png" class="img-circle img-thumbnail">				
		         </div>
				 <h4>File Manager</h4>
		    </div>
		    <div class="content list-group bg-default bg-light-rtl">
				<a href="<?php echo $this->request->base;?>/Accounts/filemanager#account" class="list-group-item">File Manager</a>
			 </div>
		</div>
	</div>	
	
	
</div>

<div class="row">

</div>

</div>