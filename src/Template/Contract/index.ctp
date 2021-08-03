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
				<a href="<?php echo $this->request->base;?>/Contract/addinward" class="list-group-item">ADD INWARD CORRESPONDENCE</a>
				<!-- <a href="<?php //echo $this->request->base;?>/Contract/inwardlist" class="list-group-item">EDIT INWARD CORRESPONDENCE</a> -->	
				<a href="<?php echo $this->request->base;?>/Contract/viewinwardlist" class="list-group-item">VIEW INWARD CORRESPONDENCE</a>	
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
				<a href="<?php echo $this->request->base;?>/Contract/addoutward" class="list-group-item">ADD OUTWARD CORRESPONDENCE</a>
		        <!-- <a href="<?php //echo $this->request->base;?>/Contract/outwardlist" class="list-group-item">EDIT OUTWARD CORRESPONDENCE</a> -->				 	
		        <a href="<?php echo $this->request->base;?>/Contract/viewoutwardlist" class="list-group-item">VIEW OUTWARD CORRESPONDENCE</a>				 	
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
				<a href="<?php echo $this->request->base;?>/Contract/addrabill" class="list-group-item">ADD R.A BILLS</a>
				<!-- <a href="<?php //echo $this->request->base;?>/Contract/editrabill" class="list-group-item">EDIT R.A BILLS</a> -->
				<a href="<?php echo $this->request->base;?>/Contract/viewrabill" class="list-group-item">VIEW R.A BILLS</a>
				
				<a href="<?php echo $this->request->base;?>/Contract/addpricevariation" class="list-group-item">ADD PRICE VARIATION</a>
				 <!-- <a href="<?php //echo $this->request->base;?>/Contract/editpricevariation" class="list-group-item">EDIT PRICE VARIATION</a>	-->
				 <a href="<?php echo $this->request->base;?>/Contract/viewpricevariation" class="list-group-item">VIEW PRICE VARIATION</a>
		    </div>
		</div>
	</div>
	<!--<div class="col-md-4">
		<div class="block block-drop-shadow infobox">
		   <div class="user bg-default bg-light-rtl">
		         <div class="info">                                                                               
		             <img src="<?php echo $this->request->base;?>/img/icon/price.png" class="img-circle img-thumbnail">				
		         </div>
				 <h4>Price Variation</h4>
		    </div>
		    <div class="content list-group bg-default bg-light-rtl">
			 <a href="<?php echo $this->request->base;?>/Contract/addpricevariation" class="list-group-item">ADD PRICE VARIATION</a>
			 <!-- <a href="<?php //echo $this->request->base;?>/Contract/editpricevariation" class="list-group-item">EDIT PRICE VARIATION</a>	-->
			 <!--<a href="<?php echo $this->request->base;?>/Contract/viewpricevariation" class="list-group-item">VIEW PRICE VARIATION</a>		        
		    </div>
		</div>
	</div> -->
		
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
				 <h4>Sub-contractor Bills</h4>
		    </div>
		    <div class="content list-group bg-default bg-light-rtl">
				<a href="<?php echo $this->request->base;?>/Contract/addsubcontractbill" class="list-group-item">Create Sub-contractor Bills</a>
				<a href="<?php echo $this->request->base;?>/Contract/subcontractbillalert" class="list-group-item">Sub-contractor Bills Alert</a>
				<a href="<?php echo $this->request->base;?>/Contract/subcontractrecords" class="list-group-item">Sub-contractor Bills Records</a>
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
				<a href="<?php echo $this->request->base;?>/contract/filemanager#contract" class="list-group-item">File Manager</a>
			 </div>
		</div>
	</div>
</div>
</div>
</div>