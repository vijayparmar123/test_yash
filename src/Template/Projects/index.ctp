<div class="col-md-10 user_manage">
<div class="block col-md-4 col-xs-12 col-sm-6">
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
				<a href="<?php echo $this->request->base;?>/projects/add" class="list-group-item">Add Project</a>
				<!-- <a href="<?php /* echo $this->request->base; */?>/projects/editprojectlist" class="list-group-item">EDIT PROJECT</a>-->	
				<a href="<?php echo $this->request->base;?>/projects/viewprojectlist" class="list-group-item">Project List</a>	
			</div>
		</div>
	</div>
	
	<div class="col-md-4">
		<div class="block block-drop-shadow infobox">
		   <div class="user bg-default bg-light-rtl">
		         <div class="info">                                                                               
		             <img src="<?php echo $this->request->base;?>/img/icon/alerts.png" class="img-circle img-thumbnail">				
		         </div>
				 <h4>Tender/Contract Notification</h4>
		    </div>
		    <div class="content list-group bg-default">
		         <a href="<?php echo $this->request->base;?>/projects/addcontractnotification" class="list-group-item">Add Tender/Contract Notification</a>
		         <a href="<?php echo $this->request->base;?>/projects/contractnotificationlist" class="list-group-item">Tender/Contract Notification Records</a>	         
		    </div>
		</div>
	</div>
	
	<div class="col-md-4">
		<div class="block block-drop-shadow infobox">
		   <div class="user bg-default bg-light-rtl">
		         <div class="info">                                                                               
		             <img src="<?php echo $this->request->base;?>/img/icon/alerts.png" class="img-circle img-thumbnail">				
		         </div>
				 <h4>Personal Notification</h4>
		    </div>
		    <div class="content list-group bg-default">
		         <a href="<?php echo $this->request->base;?>/projects/addpersonalnotification" class="list-group-item">Add Personal Notification</a>
		         <a href="<?php echo $this->request->base;?>/projects/personalnotificationlist" class="list-group-item">Personal Notification Records</a>	         
		    </div>
		</div>
	</div>
	
	<?php
		if($role == "erphead") {
	?>
	<div class="col-md-4">
		<div class="block block-drop-shadow infobox">
		   <div class="user bg-default bg-light-rtl">
		         <div class="info">                                                                               
		             <img src="<?php echo $this->request->base;?>/img/icon/price.png" class="img-circle img-thumbnail">				
		         </div>
				 <h4>Work Order</h4>
		    </div>
		    <div class="content list-group bg-default bg-light-rtl">
			 <a href="<?php echo $this->request->base;?>/Contract/worecords" class="list-group-item">W.O. Records</a>		        
		    </div>
		</div>
	</div>
	<?php } ?>
	
	<div class="row">
		<div class="col-md-4">
		<div class="block block-drop-shadow infobox">
		   <div class="user bg-default bg-light-rtl">
		         <div class="info">                                                                               
		             <img src="<?php echo $this->request->base;?>/img/icon/asset-log.png" class="img-circle img-thumbnail">				
		         </div>
				 <h4>File Manager</h4>
		    </div>
		    <div class="content list-group bg-default bg-light-rtl">
				<a href="<?php echo $this->request->base;?>/projects/filemanager#project" class="list-group-item">File Manager</a>
			 </div>
		</div>
	</div>
	</div>
	</div>
</div>
</div>