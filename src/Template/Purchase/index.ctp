<div class="col-md-10 user_manage">
	<div class="block col-md-4 col-xs-12 col-sm-6">
		<div class="row">
			<div class="col-md-4 col-xs-12 col-sm-6">
				<div class="block block-drop-shadow infobox">
		   			<div class="user bg-default bg-light-rtl">
		         		<div class="info">                                                                               
		             		<img src="<?php echo $this->request->base;?>/img/icon/material.png" class="img-circle img-thumbnail">				
		         		</div>
				 		<h4>Material Master</h4>
		    		</div>
			
			   		<div class="content list-group bg-default bg-light-rtl">
						<!-- <a href="<?php echo $this->request->base;?>/Purchase/addmaterial" class="list-group-item">Add Material </a> -->
						<a href="<?php echo $this->request->base;?>/Purchase/viewmaterial" class="list-group-item">Material List</a>	
						<a href="<?php echo $this->request->base;?>/Purchase/brandlist" class="list-group-item">Brand List</a>				 	
					</div>
				</div>
			</div>
			<!-- <div class="col-md-4 col-xs-12 col-sm-6">
				<div class="block block-drop-shadow infobox">
				   <div class="user bg-default bg-light-rtl">
			    	    <div class="info">                                                                               
		    	    	   	<img src="<?php echo $this->request->base;?>/img/icon/md.png" class="img-circle img-thumbnail">				
		        	 	</div>
				 		<h4>Manage Brand</h4>
		    		</div>
		    		<div class="content list-group bg-default bg-light-rtl">
						<a href="<?php echo $this->request->base;?>/Purchase/addbrand" class="list-group-item">Add Brand</a>
				        <a href="<?php echo $this->request->base;?>/Purchase/brandlist" class="list-group-item">Brand List</a>				 	
				    </div>
				</div>
			</div> -->
			<div class="col-md-4">
				<div class="block block-drop-shadow infobox">
		   			<div class="user bg-default bg-light-rtl">
		         		<div class="info">                                                                               
		            		<img src="<?php echo $this->request->base;?>/img/icon/manage_vendor.png" class="img-circle img-thumbnail">				
			         	</div>	
					 	<h4>Vendor Master</h4>
			    	</div>
		    		<div class="content list-group bg-default bg-light-rtl">
						<!-- <a href="<?php echo $this->request->base;?>/Purchase/addvendor" class="list-group-item">Add Vendor</a> -->
						<!-- <a href="<?php //echo $this->request->base;?>/Purchase/editvendor" class="list-group-item">Edit Vendor</a> -->
						<a href="<?php echo $this->request->base;?>/Purchase/viewvendor" class="list-group-item">Vendor Master</a>
		    		</div>
				</div>
			</div>
			<!--<div class="col-md-4 col-xs-12 col-sm-6">
				<div class="block block-drop-shadow infobox">
				   <div class="user bg-default bg-light-rtl">
				        <div class="info">                                                                               
		    		        <img src="<?php echo $this->request->base;?>/img/icon/request.png" class="img-circle img-thumbnail">				
		        		</div>
				 		<h4>Finalized Purchase Rate</h4>
		    		</div>
		    		<div class="content list-group bg-default">
						<a href="<?php echo $this->request->base;?>/Purchase/addrate" class="list-group-item">Finalized Rate</a>       
						<a href="<?php echo $this->request->base;?>/Purchase/ratealert" class="list-group-item">Finalized Rate Alert</a>
						<a href="<?php echo $this->request->base;?>/Purchase/raterecords" class="list-group-item">Finalized Rate Records</a>
					</div>
				</div>	
			</div>-->
			<div class="col-md-4">
				<div class="block block-drop-shadow infobox">
		   			<div class="user bg-default bg-light-rtl">
		        		<div class="info">                                                                               
		            		<img src="<?php echo $this->request->base;?>/img/icon/request.png" class="img-circle img-thumbnail">				
		         		</div>
				 		<h4>LOI (Letter of Intent)</h4>
		    		</div>
		    		<div class="content list-group bg-default bg-light-rtl">
						<a href="<?php echo $this->request->base;?>/purchase/prepareloi" class="list-group-item">Prepare LOI</a>
						<a href="<?php echo $this->request->base;?>/purchase/loialert" class="list-group-item">LOI Alert</a>
						<a href="<?php echo $this->request->base;?>/purchase/loirecords" class="list-group-item">LOI Records</a>
		    		</div>
				</div>
			</div>	
		</div>
		<div class="row">
			<div class="col-md-4">
				<div class="block block-drop-shadow infobox">
					<div class="user bg-default bg-light-rtl">
						<div class="info">                                                                               
							<img src="<?php echo $this->request->base;?>/img/icon/purchase.png" class="img-circle img-thumbnail">				
						</div>
						<h4>Central Purchase Track</h4>
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
						<h4>Purchase Request/Purchase Order</h4>
					</div>
					<div class="content list-group bg-default">
						<a href="<?php echo $this->request->base;?>/Purchase/approvedpr" class="list-group-item">P.R. Alert</a>
						<!--<a href="<?php //echo $this->request->base;?>/inventory/preparepo" class="list-group-item">Prepare P.O.</a>-->
						<!-- <a href="<?php echo $this->request->base;?>/Purchase/manualpreparepo" class="list-group-item">Prepare P.O. (Manual)</a> -->
						<a href="<?php echo $this->request->base;?>/inventory/approvepo" class="list-group-item">P.O. Alert</a>
						<a href="<?php echo $this->request->base;?>/Purchase/viewammendporecords" class="list-group-item">Ammended P.O.</a>
						
						<!--<a href="<?php echo $this->request->base;?>/Purchase/manualapprovepo" class="list-group-item">P.O. Alert (Manual)</a>-->
						<!--<a href="<?php echo $this->request->base;?>/Purchase/manualapprovepolocal" class="list-group-item">PO Alert - Local Purchase</a>-->
						<a href="<?php echo $this->request->base;?>/Purchase/viewporecords" class="list-group-item">P.O. Records</a>		         
					</div>
				</div>
			</div>
			<!-- <div class="col-md-4">
				<div class="block block-drop-shadow infobox">
					<div class="user bg-default bg-light-rtl">
						<div class="info">                                                                               
							<img src="<?php echo $this->request->base;?>/img/icon/price.png" class="img-circle img-thumbnail">				
						</div>
						<h4>Work Order</h4>
					</div>
					<div class="content list-group bg-default bg-light-rtl">
						<a href="<?php echo $this->request->base;?>/Contract/workheadlist" class="list-group-item">Work Head</a>       
						<a href="<?php echo $this->request->base;?>/Contract/preparewo" class="list-group-item">Prepare W.O.</a>       
						<a href="<?php echo $this->request->base;?>/Contract/approvewo" class="list-group-item">W.O. Alert</a>
						<a href="<?php echo $this->request->base;?>/Contract/worecords" class="list-group-item">W.O. Records</a>		        
					</div>
				</div>
			</div> -->
			<div class="row">
				<div class="col-md-4">
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
				</div>
			</div>
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
						<a href="<?php echo $this->request->base;?>/Accounts/accountlist" class="list-group-item">Bill Records</a>
					</div>
				</div>
			</div>
			<div class="col-md-4">
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
			</div>
			<div class="row">	
				<!-- <div class="col-md-4">
					<div class="block block-drop-shadow infobox">
		   				<div class="user bg-default bg-light-rtl">
		         			<div class="info">                                                                               
		             			<img src="<?php echo $this->request->base;?>/img/icon/asset-log.png" class="img-circle img-thumbnail">				
		         			</div>
				 			<h4>Records</h4>
		    			</div>
		    			<div class="content list-group bg-default bg-light-rtl">
							<a href="<?php echo $this->request->base;?>/Accounts/accountlist" class="list-group-item">Bill Records</a>
						</div>
					</div>
				</div> -->
			<!-- </div> -->
			<!-- <div class="col-md-4">
				<div class="block block-drop-shadow infobox">
					<div class="user bg-default bg-light-rtl">
						<div class="info">                                                                               
							<img src="<?php echo $this->request->base;?>/img/icon/agency.png" class="img-circle img-thumbnail">				
						</div>
						<h4>AGENCY</h4>
					</div>	
					<div class="content list-group bg-default bg-light-rtl">
						<a href="<?php echo $this->request->base;?>/Contract/addagency" class="list-group-item">Add Agency</a> 
						<a href="<?php echo $this->request->base;?>/Contract/agencylist" class="list-group-item">Agency List</a>		         
					</div>
				</div>
			</div> -->
			<!-- <div class="col-md-4">
				<div class="block block-drop-shadow infobox">
					<div class="user bg-default bg-light-rtl">
						<div class="info">                                                                               
							<img src="<?php echo $this->request->base;?>/img/icon/manage_vendor.png" class="img-circle img-thumbnail">				
						</div>
						<h4>Vendor Master</h4>
					</div>
					<div class="content list-group bg-default bg-light-rtl">
						<a href="<?php echo $this->request->base;?>/Purchase/addvendor" class="list-group-item">Add Vendor</a>
						 <a href="<?php //echo $this->request->base;?>/Purchase/editvendor" class="list-group-item">Edit Vendor</a>
						 <a href="<?php echo $this->request->base;?>/Purchase/viewvendor" class="list-group-item">Vendor List</a>
					</div>
				</div>
			</div>  -->
			<div class="col-md-4">
				<div class="block block-drop-shadow infobox">
					<div class="user bg-default bg-light-rtl">
							<div class="info">                                                                               
								<img src="<?php echo $this->request->base;?>/img/icon/asset-log.png" class="img-circle img-thumbnail">				
							</div>
							<h4>File Manager</h4>
						</div>
						<div class="content list-group bg-default bg-light-rtl">
							<a href="<?php echo $this->request->base;?>/Purchase/filemanager#purchase" class="list-group-item">File Manager</a>
						</div>
					</div>
				</div>
			</div>
			</div>
			<!-- <div class="col-md-4 col-xs-12 col-sm-6">
				<div class="block block-drop-shadow infobox">
					<div class="user bg-default bg-light-rtl">
						<div class="info">                                                                               
							<img src="<?php //echo $this->request->base;?>/img/icon/request.png" class="img-circle img-thumbnail">				
						</div>
						<h4>Purchase Order (Manual)</h4>
					</div>
					<div class="content list-group bg-default">
						<a href="<?php //echo $this->request->base;?>/Purchase/manualpreparepo" class="list-group-item">Prepare P.O. (Manual)</a>
						<a href="<?php //echo $this->request->base;?>/Purchase/manualapprovepo" class="list-group-item">P.O. Alert (Manual)</a>
						<a href="<?php //echo $this->request->base;?>/Purchase/manualviewporecords" class="list-group-item">P.O. Records (Manual)</a>	         
					</div>
				</div>
			</div> -->
			<!-- <div class="col-md-4">
				<div class="block block-drop-shadow infobox">
					<div class="user bg-default bg-light-rtl">
						<div class="info">                                                                               
							<img src="<?php echo $this->request->base;?>/img/icon/Access-Rights-Management.png" class="img-circle img-thumbnail">				
						</div>
						<h4>Category List</h4>
					</div>
					<div class="content list-group bg-default">
						<a href="<?php //echo $this->request->base;?>/Purchase/category" class="list-group-item">Category List</a>
					</div>
				</div>
			</div> -->
		</div>	
	</div>
</div>