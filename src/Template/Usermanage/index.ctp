<div class="col-md-10 user_manage">
<div class="block col-md-4 col-xs-12 col-sm-6">
<div class="row">
	<div class="col-md-4 col-xs-12 col-sm-6">
		<div class="block block-drop-shadow infobox">
		   <div class="user bg-default bg-light-rtl">
		         <div class="info">                                                                               
		             <img src="<?php echo $this->request->base;?>/img/icon/Users-Management.png" class="img-circle img-thumbnail">				
		         </div>
				 <h4>Manage User</h4>
		    </div>
			
		    <div class="content list-group bg-default bg-light-rtl">
				<a href="<?php echo $this->request->base;?>/Usermanage/add" class="list-group-item">Add User</a>
				<a href="<?php echo $this->request->base;?>/Usermanage/userlist" class="list-group-item">Manage User</a>	
				<a href="<?php echo $this->request->base;?>/Usermanage/view" class="list-group-item">User Records</a>	
				<a href="<?php echo $this->request->base;?>/Usermanage/viewprojectlist" class="list-group-item">Opening Stock</a>	
			</div>
		</div>
	</div>
	<?php if ($role=="erphead"){ ?>
	<div class="col-md-4 col-xs-12 col-sm-6">
		<div class="block block-drop-shadow infobox">
		   <div class="user bg-default bg-light-rtl">
		         <div class="info">                                                                               
		             <img src="<?php echo $this->request->base;?>/img/icon/Users-Management.png" class="img-circle img-thumbnail">				
		         </div>
				 <h4>Access Rights</h4>
		    </div>
			
		    <div class="content list-group bg-default bg-light-rtl">
				<a href="<?php echo $this->request->base;?>/accessrights/rights" class="list-group-item">View</a>
			</div>
		</div>
	</div>	
	<?php } ?>
	
	<?php if ($role=="erphead"){ ?>
	<div class="col-md-4 col-xs-12 col-sm-6">
		<div class="block block-drop-shadow infobox">
		   <div class="user bg-default bg-light-rtl">
		         <div class="info">                                                   
		             <img src="<?php echo $this->request->base;?>/img/icon/Users-Management.png" class="img-circle img-thumbnail">				
		         </div>
				 <h4>File Manager</h4>
		    </div>
			
		    <div class="content list-group bg-default bg-light-rtl">
				<a href="<?php echo $this->request->base;?>/Usermanage/filemanagerbackup" class="list-group-item">Backup Files</a>
			</div>
		</div>
	</div>	
	<?php } ?>
	</div>
</div>
</div>
</div>