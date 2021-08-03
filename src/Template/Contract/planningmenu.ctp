<div class="col-md-10 user_manage">

<div class="row">
	<div class="col-md-4">
		<div class="block block-drop-shadow infobox">
		   <div class="user bg-default bg-light-rtl">
		         <div class="info">                                                                               
		             <img src="<?php echo $this->request->base;?>/img/icon/inward.png" class="img-circle img-thumbnail">				
		         </div>
				 <h4>Inward Correspondence</h4>
		    </div>
		    <div class="content list-group bg-default bg-light-rtl">
				<a href="<?php echo $this->request->base;?>/Contract/addinward" class="list-group-item">Add Inward Correspondence</a>	
				<a href="<?php echo $this->request->base;?>/Contract/viewinwardlist" class="list-group-item">View Inward Correspondence</a>	
		    </div>
		</div>
	</div>
			<div class="col-md-4">
		<div class="block block-drop-shadow infobox">
		   <div class="user bg-default bg-light-rtl">
		         <div class="info">                                                                               
		             <img src="<?php echo $this->request->base;?>/img/icon/outward.png" class="img-circle img-thumbnail">				
		         </div>
				 <h4>Outward Correspondence</h4>
		    </div>
		    <div class="content list-group bg-default bg-light-rtl">
				<a href="<?php echo $this->request->base;?>/Contract/addoutward" class="list-group-item">Add Outward Correspondence</a>				 	
		        <a href="<?php echo $this->request->base;?>/Contract/viewoutwardlist" class="list-group-item">View Outward Correspondence</a>				 	
		    </div>
		</div>
	</div>
	
	<div class="col-md-4">
		<div class="block block-drop-shadow infobox">
		   <div class="user bg-default bg-light-rtl">
		         <div class="info">                                                                               
		             <img src="<?php echo $this->request->base;?>/img/icon/project.png" class="img-circle img-thumbnail">
		         </div>
				 <h4>Drawing Records</h4>
		    </div>
		    <div class="content list-group bg-default bg-light-rtl">
			 <a href="<?php echo $this->request->base;?>/Contract/adddrawing" class="list-group-item">Create New Drawing Record</a>
			 <a href="<?php echo $this->request->base;?>/Contract/drawingrecords" class="list-group-item">Update / View Drawing Record</a>		        
		    </div>
		</div>
	</div>
	
</div>
<div class="row">
	
	<div class="col-md-4 col-xs-12 col-sm-6">
		<div class="block block-drop-shadow infobox">
			<div class="user bg-default bg-light-rtl">
		         <div class="info">                                                                               
		             <img src="<?php echo $this->request->base;?>/img/icon/project.png" class="img-circle img-thumbnail">				
		         </div>
				 <h4>Project</h4>
		    </div>
			
		    <div class="content list-group bg-default bg-light-rtl">	
				<a href="<?php echo $this->request->base;?>/projects/viewprojectlist" class="list-group-item">Project List</a>	
			</div>
		</div>
	</div>

	<div class="col-md-4">
		<div class="block block-drop-shadow infobox">
		   <div class="user bg-default bg-light-rtl">
		         <div class="info">                                                                               
		             <img src="<?php echo $this->request->base;?>/img/icon/price.png" class="img-circle img-thumbnail">				
		         </div>
				 <h4>Work Master</h4>
		    </div>
		    <div class="content list-group bg-default bg-light-rtl">
			 <a href="<?php echo $this->request->base;?>/Contract/workdescription" class="list-group-item">Work Description</a>       
			 <a href="<?php echo $this->request->base;?>/Contract/planningworkheadlist" class="list-group-item">Work Type</a>       
			 <!-- <a href="<?php echo $this->request->base;?>/Contract/planningpreparewo" class="list-group-item">Prepare W.O.</a>       
			 <a href="<?php echo $this->request->base;?>/Contract/planningapprovewo" class="list-group-item">W.O. Alert</a>
			 <a href="<?php echo $this->request->base;?>/Contract/planningworecords" class="list-group-item">W.O. Records</a> -->
		    </div>
		</div>
	</div>
	
	<div class="col-md-4">
		<div class="block block-drop-shadow infobox">
		   <div class="user bg-default bg-light-rtl">
		         <div class="info">                                                                               
		             <img src="<?php echo $this->request->base;?>/img/icon/price.png" class="img-circle img-thumbnail">				
		         </div>
				 <h4>Work Order</h4>
		    </div>
		    <div class="content list-group bg-default bg-light-rtl">
			 <!-- <a href="<?php echo $this->request->base;?>/Contract/workdescription" class="list-group-item">Work Description</a>       
			 <a href="<?php echo $this->request->base;?>/Contract/planningworkheadlist" class="list-group-item">Work Type</a>        -->
			 <a href="<?php echo $this->request->base;?>/Contract/planningpreparewo" class="list-group-item">Prepare W.O.</a>       
			 <a href="<?php echo $this->request->base;?>/Contract/planningapprovewo" class="list-group-item">W.O. Alert</a>
			 <a href="<?php echo $this->request->base;?>/Contract/planningammendapprovewo" class="list-group-item">Ammended W.O. Record</a>
			 <a href="<?php echo $this->request->base;?>/Contract/planningworecords" class="list-group-item">W.O. Records</a>
		    </div>
		</div>
	</div>
	
	<!-- <div class="col-md-4">
		<div class="block block-drop-shadow infobox">
		   <div class="user bg-default bg-light-rtl">
		         <div class="info">                                                                               
		             <img src="<?php echo $this->request->base;?>/img/icon/purchase.png" class="img-circle img-thumbnail">				
		         </div>
				 <h4>Purchase Request</h4>
		    </div>
			
		    <div class="content list-group bg-default bg-light-rtl">
				<a href="<?php echo $this->request->base;?>/Inventory/preparepr" class="list-group-item">Prepare P.R. </a>
				<a href="<?php echo $this->request->base;?>/Inventory/approvedpr" class="list-group-item">P.R. Alert</a>	
				 <a href="<?php echo $this->request->base;?>/Inventory/viewpr" class="list-group-item">P.R. Records</a>
			</div>
		</div>
		
	</div> -->
</div>

<div class="row">
<div class="col-md-4">
		<div class="block block-drop-shadow infobox">
		   <div class="user bg-default bg-light-rtl">
		         <div class="info">                                                                               
		             <img src="<?php echo $this->request->base;?>/img/icon/purchase.png" class="img-circle img-thumbnail">				
		         </div>
				 <h4>Purchase Request</h4>
		    </div>
			
		    <div class="content list-group bg-default bg-light-rtl">
				<a href="<?php echo $this->request->base;?>/Inventory/preparepr" class="list-group-item">Prepare P.R. </a>
				<a href="<?php echo $this->request->base;?>/Inventory/approvedpr" class="list-group-item">P.R. Alert</a>	
				 <a href="<?php echo $this->request->base;?>/Inventory/viewpr" class="list-group-item">P.R. Records</a>
			</div>
		</div>
		
	</div>
	<div class="col-md-4">
		<div class="block block-drop-shadow infobox">
		   <div class="user bg-default bg-light-rtl">
		         <div class="info">                                                                               
		             <img src="<?php echo $this->request->base;?>/img/icon/inward.png" class="img-circle img-thumbnail">				
		         </div>
				 <h4>Central Purchase Track</h4>
		    </div>
			
		    <div class="content list-group bg-default bg-light-rtl">	
				<!--<a href="<?php echo $this->request->base;?>/Purchase/trackpr" class="list-group-item">P.R. Status - Purchase</a>-->
				<a href="<?php echo $this->request->base;?>/Purchase/approvedpr" class="list-group-item">P.R. Alert</a>
		        <a href="<?php echo $this->request->base;?>/inventory/approvepo" class="list-group-item">P.O. Alert</a>
		        <!--<a href="<?php echo $this->request->base;?>/Contract/approvewo" class="list-group-item">W.O. Alert</a>-->
				<a href="<?php echo $this->request->base;?>/Assets/assetpoalert" class="list-group-item">PO Alert (Asset)</a>
		    </div>
		</div>
	</div>
	
	<div class="col-md-4">
		<div class="block block-drop-shadow infobox">
		   <div class="user bg-default bg-light-rtl">
		         <div class="info">                                                                               
		             <img src="<?php echo $this->request->base;?>/img/icon/asset-log.png" class="img-circle img-thumbnail">				
		         </div>
				 <h4>Purchase Records</h4>
		    </div>
		    <div class="content list-group bg-default bg-light-rtl">
				<a href="<?php echo $this->request->base;?>/Purchase/viewporecords" class="list-group-item">P.O. Records</a>
				<a href="<?php echo $this->request->base;?>/Assets/viewassetporecords" class="list-group-item">P.O. Records(Asset)</a>
				<!--<a href="<?php echo $this->request->base;?>/Contract/worecords" class="list-group-item">W.O. Records</a>-->
			 </div>
		</div>
	</div>
	
	<!-- <div class="col-md-4">
		<div class="block block-drop-shadow infobox">
		   <div class="user bg-default bg-light-rtl">
		         <div class="info">                                                                               
		             <img src="<?php echo $this->request->base;?>/img/icon/purchase.png" class="img-circle img-thumbnail">				
		         </div>
				 <h4>Delivery Track</h4>
		    </div>
			
		    <div class="content list-group bg-default bg-light-rtl">
				<a href="<?php echo $this->request->base;?>/Purchase/postatus" class="list-group-item">PO Status</a>
				<a href="<?php echo $this->request->base;?>/Purchase/podeliveryrecords" class="list-group-item">PO Delivery Records</a>	
			</div>
		</div>
	</div> -->

</div>

<div class="row">
<div class="col-md-4">
		<div class="block block-drop-shadow infobox">
		   <div class="user bg-default bg-light-rtl">
		         <div class="info">                                                                               
		             <img src="<?php echo $this->request->base;?>/img/icon/purchase.png" class="img-circle img-thumbnail">				
		         </div>
				 <h4>Delivery Track</h4>
		    </div>
			
		    <div class="content list-group bg-default bg-light-rtl">
				<a href="<?php echo $this->request->base;?>/Purchase/trackpr" class="list-group-item">Pending PR Status</a>
				<a href="<?php echo $this->request->base;?>/Purchase/postatus" class="list-group-item">Pending Delivery Status</a>
				<a href="<?php echo $this->request->base;?>/Purchase/podeliveryrecords" class="list-group-item">PO Delivery Records</a>	
			</div>
		</div>
	</div>
	<div class="col-md-4 col-xs-12 col-sm-6">
		<div class="block block-drop-shadow infobox">
		   <div class="user bg-default bg-light-rtl">
				 <div class="info">                                                                               
					 <img src="<?php echo $this->request->base;?>/img/icon/request.png" class="img-circle img-thumbnail">				
				 </div>
				 <h4>Inventory Records</h4>
			</div>
			<div class="content list-group bg-default">
				<a href="<?php echo $this->request->base;?>/Inventory/viewrecords" class="list-group-item">View Records</a>
				<a href="javascript:void(0)" class="list-group-item">MIS Reports</a>		         
			</div>
		</div>
	</div>
	<div class="col-md-4 col-xs-12 col-sm-6">
		<div class="block block-drop-shadow infobox">
		   <div class="user bg-default bg-light-rtl">
		         <div class="info">                                                                               
		             <img src="<?php echo $this->request->base;?>/img/icon/material.png" class="img-circle img-thumbnail">				
		         </div>
				 <h4>Material Management</h4>
		    </div>
			
		    <div class="content list-group bg-default bg-light-rtl">
				<!-- <a href="<?php echo $this->request->base;?>/Purchase/addmaterial" class="list-group-item">Add Material </a> -->
				<a href="<?php echo $this->request->base;?>/Purchase/viewmaterial" class="list-group-item">Material List</a>
				<!-- <a href="<?php echo $this->request->base;?>/Purchase/addbrand" class="list-group-item">Add Brand</a> -->
		        <a href="<?php echo $this->request->base;?>/Purchase/brandlist" class="list-group-item">Brand List</a>
			</div>
		</div>
	</div>
	
	<!-- <div class="col-md-4 col-xs-12 col-sm-6">
		<div class="block block-drop-shadow infobox">
		   <div class="user bg-default bg-light-rtl">
				 <div class="info">                                                                               
					 <img src="<?php echo $this->request->base;?>/img/icon/request.png" class="img-circle img-thumbnail">				
				 </div>
				 <h4>Manage Vendor & Agency</h4>
			</div>
			<div class="content list-group bg-default">
				<a href="<?php echo $this->request->base;?>/Purchase/viewvendor" class="list-group-item">Vendor List</a>
		    <!-- <a href="<?php echo $this->request->base;?>/Contract/agencylist" class="list-group-item">Agency List</a> -->     
			<!-- </div> -->
		<!-- </div> -->
	<!-- </div>  -->
	
</div>
<div class="row">
<div class="col-md-4 col-xs-12 col-sm-6">
		<div class="block block-drop-shadow infobox">
		   <div class="user bg-default bg-light-rtl">
				 <div class="info">                                                                               
					 <img src="<?php echo $this->request->base;?>/img/icon/request.png" class="img-circle img-thumbnail">				
				 </div>
				 <!-- <h4>Manage Vendor & Agency</h4> -->
				 <h4>Vendor Master</h4>

			</div>
			<div class="content list-group bg-default">
				<a href="<?php echo $this->request->base;?>/Purchase/viewvendor" class="list-group-item">Vendor Master</a>
		    <!-- <a href="<?php echo $this->request->base;?>/Contract/agencylist" class="list-group-item">Agency List</a> -->     
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="block block-drop-shadow infobox">
		   <div class="user bg-default bg-light-rtl">
		         <div class="info">                                                                               
		             <img src="<?php echo $this->request->base;?>/img/icon/employee-manage.png" class="img-circle img-thumbnail">				
		         </div>
				 <h4>Personnel Information</h4>
		    </div>
			
		    <div class="content list-group bg-default bg-light-rtl">
				<a href="<?php echo $this->request->base;?>/Humanresource/personnel" class="list-group-item">Personnel Information</a>	
			</div>
		</div>
	</div>
	
	<div class="col-md-4">
		<div class="block block-drop-shadow infobox">
		   <div class="user bg-default bg-light-rtl">
		         <div class="info">                                                                               
		             <img src="<?php echo $this->request->base;?>/img/icon/mine1.png" class="img-circle img-thumbnail">				
		         </div>
				 <h4>Records</h4>
		    </div>
		    <div class="content list-group bg-default bg-light-rtl">
				<a href="<?php echo $this->request->base;?>/Assets/assetrecord" class="list-group-item">Asset Record</a>
		    </div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="block block-drop-shadow infobox">
			<div class="user bg-default bg-light-rtl">
		         <div class="info">                                                                               
		             <img src="<?php echo $this->request->base;?>/img/icon/bill.png" class="img-circle img-thumbnail">				
		         </div>
				 <h4>Bill Records</h4>
		    </div>
		    <div class="content list-group bg-default bg-light-rtl">
				<a href="<?php echo $this->request->base;?>/Contract/viewrabill" class="list-group-item">View R.A Bills</a>
				<a href="<?php echo $this->request->base;?>/Contract/viewpricevariation" class="list-group-item">View Price Variation</a>
				<a href="<?php echo $this->request->base;?>/Contract/subcontractrecords" class="list-group-item">Sub-contractor Bills Records</a>
				<a href="<?php echo $this->request->base;?>/Accounts/accountlist" class="list-group-item">Bill Records</a>
		    </div>
		</div>
	</div>
</div>
</div>
