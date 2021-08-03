<div class="col-md-10 user_manage">

<div class="row">
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
	
	<div class="col-md-4">
		<div class="block block-drop-shadow infobox">
		   <div class="user bg-default bg-light-rtl">
		         <div class="info">                                                                               
		             <img src="<?php echo $this->request->base;?>/img/icon/bill.png" class="img-circle img-thumbnail">				
		         </div>
				 <!--<h4>R.A Bills</h4>-->
				 <h4>Client Bills</h4>
		    </div>
		    <div class="content list-group bg-default bg-light-rtl">
				<a href="<?php echo $this->request->base;?>/Contract/addrabill" class="list-group-item">Add R.A Bills</a>
				<!-- <a href="<?php //echo $this->request->base;?>/Contract/editrabill" class="list-group-item">EDIT R.A BILLS</a> -->
				<a href="<?php echo $this->request->base;?>/Contract/viewrabill" class="list-group-item">View R.A Bills</a>
				
				<a href="<?php echo $this->request->base;?>/Contract/addpricevariation" class="list-group-item">Add Price Variation</a>
				 <!-- <a href="<?php //echo $this->request->base;?>/Contract/editpricevariation" class="list-group-item">EDIT PRICE VARIATION</a>	-->
				 <a href="<?php echo $this->request->base;?>/Contract/viewpricevariation" class="list-group-item">View Price Variation</a>
		    </div>
		</div>
	</div>
	
	<div class="col-md-4">
		<div class="block block-drop-shadow infobox">
			<div class="user bg-default bg-light-rtl">
		         <div class="info">                                                                               
		             <img src="<?php echo $this->request->base;?>/img/icon/bill.png" class="img-circle img-thumbnail">				
		         </div>
				 <h4>Sub-contractor Bills</h4>
		    </div>
		    <div class="content list-group bg-default bg-light-rtl">
				<a href="<?php echo $this->request->base;?>/Contract/addsubcontractbill" class="list-group-item">Create Sub-contractor Bills</a>
				<a href="<?php echo $this->request->base;?>/Contract/subcontractbillalert" class="list-group-item">Sub-contractor Bills Alert</a>
				<a href="<?php echo $this->request->base;?>/Contract/subcontractrecords" class="list-group-item">Sub-contractor Bills Records</a>
		    </div>
		</div>
	</div>
	
	<!--<div class="col-md-4">
		<div class="block block-drop-shadow infobox">
		   <div class="user bg-default bg-light-rtl">
		         <div class="info">                                                                               
		             <img src="<?php //echo $this->request->base;?>/img/icon/asset-log.png" class="img-circle img-thumbnail">				
		         </div>
				 <h4>File Manager</h4>
		    </div>
		    <div class="content list-group bg-default bg-light-rtl">
				<a href="<?php //echo $this->request->base;?>/contract/filemanager#contract" class="list-group-item">File Manager</a>
			 </div>
		</div>
	</div>-->
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
		             <img src="<?php echo $this->request->base;?>/img/icon/inward.png" class="img-circle img-thumbnail">				
		         </div>
				 <h4>Correspondence Records</h4>
		    </div>
			
		    <div class="content list-group bg-default bg-light-rtl">	
				<a href="<?php echo $this->request->base;?>/Contract/viewinwardlist" class="list-group-item">View Inward Correspondence</a>					 	
		        <a href="<?php echo $this->request->base;?>/Contract/viewoutwardlist" class="list-group-item">View Outward Correspondence</a>
		    </div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="block block-drop-shadow infobox">
		   <div class="user bg-default bg-light-rtl">
		         <div class="info">                                                                               
		             <img src="<?php echo $this->request->base;?>/img/icon/asset-log.png" class="img-circle img-thumbnail">				
		         </div>
				 <h4>Bill Records</h4>
		    </div>
		    <div class="content list-group bg-default bg-light-rtl">
				<a href="<?php echo $this->request->base;?>/Accounts/accountlist" class="list-group-item">Bill Records</a>
			 </div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-4 col-xs-12 col-sm-6">
		<div class="block block-drop-shadow infobox">
		   <div class="user bg-default bg-light-rtl">
				 <div class="info">                                                                               
					 <img src="<?php echo $this->request->base;?>/img/icon/request.png" class="img-circle img-thumbnail">				
				 </div>
				 <h4>Purchase Records</h4>
			</div>
			<div class="content list-group bg-default">
				<a href="<?php echo $this->request->base;?>/Purchase/viewporecords" class="list-group-item">P.O. Records</a>
				<!-- <a href="<?php echo $this->request->base;?>/Contract/worecords" class="list-group-item">W.O. Records</a>		          -->
				<a href="<?php echo $this->request->base;?>/Contract/planningworecords" class="list-group-item">W.O. Records</a>		         
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
					 <img src="<?php echo $this->request->base;?>/img/icon/request.png" class="img-circle img-thumbnail">				
				 </div>
				 <h4>Vendor Master</h4>
			</div>
			<div class="content list-group bg-default">
				<a href="<?php echo $this->request->base;?>/Purchase/viewvendor" class="list-group-item">Vendor Master</a>
		     <!--<a href="<?php echo $this->request->base;?>/Contract/agencylist" class="list-group-item">Agency List</a>-->      
			</div>
		</div>
	</div>
</div>
</div>
