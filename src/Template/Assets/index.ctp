<div class="col-md-10 user_manage">

<div class="row">
	<div class="col-md-4">
		<div class="block block-drop-shadow infobox">
		   <div class="user bg-default bg-light-rtl">
		         <div class="info">                                                                               
		             <img src="<?php echo $this->request->base;?>/img/icon/asset.png" class="img-circle img-thumbnail">				
		         </div>
				 <h4>Assets</h4>
		    </div>
			
		    <div class="content list-group bg-default bg-light-rtl">
				<a href="<?php echo $this->request->base;?>/Assets/add" class="list-group-item">Add Asset </a>
			<!-- <a href="<?php echo $this->request->base;?>/Assets/assetlist" class="list-group-item">Manage Asset</a>	-->
				<a href="<?php echo $this->request->base;?>/Assets/trasnsferaccept" class="list-group-item">Asset Management</a>	
				<a href="<?php echo $this->request->base;?>/Assets/soldtheft" class="list-group-item">Sold/Theft Asset</a>		
		    </div>
		</div>
	</div>
		<div class="col-md-4">
		<div class="block block-drop-shadow infobox">
		   <div class="user bg-default bg-light-rtl">
		         <div class="info">                                                                               
		             <img src="<?php echo $this->request->base;?>/img/icon/assets-maintenance.png" class="img-circle img-thumbnail">				
		         </div>
				 <h4>Assets Maintenance</h4>
		    </div>
		    <div class="content list-group bg-default bg-light-rtl">
				<a href="<?php echo $this->request->base;?>/Assets/addmaintenance" class="list-group-item">Add Asset Maintenance</a>
		        <a href="<?php echo $this->request->base;?>/Assets/aprovemaintenance" class="list-group-item">Asset Maintenance Alert</a>
				<a href="<?php echo $this->request->base;?>/Assets/maintenancerecords" class="list-group-item">Asset Maintenance Records</a>
		    </div>
		</div>
	</div>
			<div class="col-md-4">
		<div class="block block-drop-shadow infobox">
		   <div class="user bg-default bg-light-rtl">
		         <div class="info">                                                                               
		             <img src="<?php echo $this->request->base;?>/img/icon/asset-log.png" class="img-circle img-thumbnail">				
		         </div>
				 <h4>Equipment Log</h4>
		    </div>
		    <div class="content list-group bg-default bg-light-rtl">
				<a href="<?php echo $this->request->base;?>/Assets/equipmentlogown" class="list-group-item">Add Equipment Logs - Owned</a>
				<a href="<?php echo $this->request->base;?>/Assets/equipmentlogownrecord" class="list-group-item">Equipment Log Records - Owned</a>
				<a href="<?php echo $this->request->base;?>/Assets/equipmentlog" class="list-group-item">Add Equipment Log - Rent</a>
				<a href="<?php echo $this->request->base;?>/Assets/equipmentlogrecord" class="list-group-item">Equipment Log Records - Rent</a>
		    </div>
		</div>
	</div>	
		
	<!--<div class="col-md-4">
		<div class="block block-drop-shadow infobox">
		   <div class="user bg-default bg-light-rtl">
		         <div class="info">                                                                               
		             <img src="<?php echo $this->request->base;?>/img/icon/bill1.png" class="img-circle img-thumbnail">				
		         </div>
				 <h4>R.M.C Issue Slip</h4>
		    </div>
		    <div class="content list-group bg-default">
		         <a href="<?php echo $this->request->base;?>/Assets/rmcissueslip" class="list-group-item">R.M.C Issue Slip</a>
		         <a href="<?php echo $this->request->base;?>/Assets/rmcissuealert" class="list-group-item">R.M.C Issue Alert</a>		         
		         <a href="<?php echo $this->request->base;?>/Assets/rmcissuerecord" class="list-group-item">R.M.C Issue Records</a>		         
		    </div>
		</div>
	</div>-->
	
	<div class="col-md-4">
		<div class="block block-drop-shadow infobox">
		   <div class="user bg-default bg-light-rtl">
		         <div class="info">                                                                               
		             <img src="<?php echo $this->request->base;?>/img/icon/alerts.png" class="img-circle img-thumbnail">				
		         </div>
				 <h4>P&M Notification</h4>
		    </div>
		    <div class="content list-group bg-default">
		         <a href="<?php echo $this->request->base;?>/Assets/addmaintenancenotification" class="list-group-item">Add P&M Notification</a>
		         <a href="<?php echo $this->request->base;?>/Assets/maintenancenotificationlist" class="list-group-item">P&M Notification Records</a>	         
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
		        <a href="<?php echo $this->request->base;?>/Assets/storeissue" class="list-group-item">View Store Issue</a>
				<a href="<?php echo $this->request->base;?>/Assets/viewassetporecords" class="list-group-item">PO Records (Asset)</a>
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
				<a href="<?php echo $this->request->base;?>/assets/filemanager#asset" class="list-group-item">File Manager</a>
			 </div>
		</div>
	</div>
	<!--<div class="col-md-4">
		<div class="block block-drop-shadow infobox">
		   <div class="user bg-default bg-light-rtl">
		         <div class="info">                                                                               
		             <img src="<?php echo $this->request->base;?>/img/icon/request.png" class="img-circle img-thumbnail">				
		         </div>
				 <h4>Purchase Order (Asset)</h4>
		    </div>
		    <div class="content list-group bg-default bg-light-rtl">
				<a href="<?php echo $this->request->base;?>/Assets/assetpo" class="list-group-item">PO (Asset)</a>
				<a href="<?php echo $this->request->base;?>/Assets/assetpoalert" class="list-group-item">PO Alert (Asset)</a>
				<a href="<?php echo $this->request->base;?>/Assets/viewassetporecords" class="list-group-item">PO Records (Asset)</a>
		    </div>
		</div>
	</div>-->
</div>
</div>