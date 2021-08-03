<div class="col-md-10 user_manage">
	<div class="row">
		<div class="col-md-4">
			<div class="block block-drop-shadow infobox">
				<div class="user bg-default bg-light-rtl">
					<div class="info">                                                                               
						<img src="<?php echo $this->request->base;?>/img/icon/purchase.png" class="img-circle img-thumbnail">				
					</div>
					<h4>Purchase Request (P.R.)</h4>
				</div>
					
				<div class="content list-group bg-default bg-light-rtl">
					<a href="<?php echo $this->request->base;?>/Inventory/preparepr" class="list-group-item">Prepare P.R. </a>
					<a href="<?php echo $this->request->base;?>/Inventory/approvedpr" class="list-group-item">P.R. Alert</a>	
					<a href="<?php echo $this->request->base;?>/Inventory/viewpr" class="list-group-item">P.R. Records</a>
					<!--<a href="<?php echo $this->request->base;?>/Purchase/viewmaterial" class="list-group-item">View Material Master</a>
					<a href="<?php echo $this->request->base;?>/Purchase/brandlist" class="list-group-item">View Brand master</a>-->
				</div>
			</div>
		</div>
	
		<div class="col-md-4">
			<div class="block block-drop-shadow infobox">
			<div class="user bg-default bg-light-rtl">
					<div class="info">                                                                               
						<img src="<?php echo $this->request->base;?>/img/icon/purchase.png" class="img-circle img-thumbnail">				
					</div>
					<h4>Cental Purchase Track</h4>
				</div>
				
				<div class="content list-group bg-default bg-light-rtl">
					<a href="<?php echo $this->request->base;?>/Purchase/trackpr" class="list-group-item">Pending PR Status</a>
					<a href="<?php echo $this->request->base;?>/Inventory/ponorate" class="list-group-item">PO Records</a>
					<a href="<?php echo $this->request->base;?>/Inventory/planningwonorate" class="list-group-item">WO Records</a>
					<!--<a href="<?php echo $this->request->base;?>/Inventory/inventorypostatus" class="list-group-item">PO Status</a>
					<a href="<?php echo $this->request->base;?>/Inventory/inventorypodeliveryrecords" class="list-group-item">PO Delivery Records</a>-->
				<a href="<?php echo $this->request->base;?>/Purchase/postatus" class="list-group-item">Pending Delivery Status</a>
				<a href="<?php echo $this->request->base;?>/Purchase/podeliveryrecords" class="list-group-item">PO Delivery Records</a>	
				</div>
			</div>
		</div>
	
		<div class="col-md-4">
			<div class="block block-drop-shadow infobox">
			<div class="user bg-default bg-light-rtl">
					<div class="info">                                                                               
						<img src="<?php echo $this->request->base;?>/img/icon/price.png" class="img-circle img-thumbnail">				
					</div>
					<h4>Goods Receipt Note (G.R.N.)</h4>
				</div>
				<div class="content list-group bg-default bg-light-rtl">
					<!-- <a href="<?php echo $this->request->base;?>/Inventory/preparegrn" class="list-group-item">Prepare G.R.N.</a>-->
					<a href="<?php echo $this->request->base;?>/Inventory/preparegrnwithoutpo" class="list-group-item">Prepare G.R.N.</a>
					<a href="<?php echo $this->request->base;?>/Inventory/approvegrn" class="list-group-item">G.R.N. Alert</a>				 	
					<a href="<?php echo $this->request->base;?>/Inventory/viewgrn" class="list-group-item">G.R.N. Records</a>
				</div>
			</div>
		</div>
	
		<!-- <div class="col-md-4">
			<div class="block block-drop-shadow infobox">
			<div class="user bg-default bg-light-rtl">
					<div class="info">                                                                               
						<img src="<?php //echo $this->request->base;?>/img/icon/bill1.png" class="img-circle img-thumbnail">				
					</div>
					<h4>Issue Slip (I.S.)</h4>
				</div>
				<div class="content list-group bg-default bg-light-rtl">
					
				</div>
			</div>
		</div> -->
	</div>
	
	<div class="row">
		<div class="col-md-4">
			<div class="block block-drop-shadow infobox">
			   <div class="user bg-default bg-light-rtl">
					 <div class="info">                                                                               
						 <img src="<?php echo $this->request->base;?>/img/icon/return.png" class="img-circle img-thumbnail">				
					 </div>
					 <h4>Issue Management</h4>
				</div>
				<div class="content list-group bg-default bg-light-rtl">
					<a href="<?php echo $this->request->base;?>/Inventory/prepareis" class="list-group-item">Prepare I.S.</a>
					<!--<a href="<?php //echo $this->request->base;?>/Inventory/approveis" class="list-group-item">I.S. Alert</a>-->
					<a href="<?php echo $this->request->base;?>/Inventory/viewis" class="list-group-item">I.S. Records</a>
				</div>
			</div>
		</div>
		
		<div class="col-md-4">
			<div class="block block-drop-shadow infobox">
			   <div class="user bg-default bg-light-rtl">
					 <div class="info">                                                                               
						 <img src="<?php echo $this->request->base;?>/img/icon/return.png" class="img-circle img-thumbnail">				
					 </div>
					 <h4>Return Back Note(R.B.N)</h4>
				</div>
				<div class="content list-group bg-default bg-light-rtl">
					<a href="<?php echo $this->request->base;?>/Inventory/preparerbn" class="list-group-item">Prepare R.B.N.</a>
					<!--<a href="<?php //echo $this->request->base;?>/Inventory/approverbn" class="list-group-item">R.B.N. Alert</a>-->
					<a href="<?php echo $this->request->base;?>/Inventory/viewrbn" class="list-group-item">R.B.N. Records</a>
				</div>
			</div>
		</div>
		
		<div class="col-md-4">
			<div class="block block-drop-shadow infobox">
			   <div class="user bg-default bg-light-rtl">
					 <div class="info">                                                                               
						 <img src="<?php echo $this->request->base;?>/img/icon/purchase.png" class="img-circle img-thumbnail">				
					 </div>
					 <h4>Debit Note</h4>
				</div>
				
				<div class="content list-group bg-default bg-light-rtl">
					 <a href="<?php echo $this->request->base;?>/Inventory/inventorypreparedebit" class="list-group-item">Prepare Debit Note</a>
					 <a href="<?php echo $this->request->base;?>/Inventory/inventorydebitnotealert" class="list-group-item">Debit Note Alert</a>
					 <a href="<?php echo $this->request->base;?>/Inventory/inventorydebitrecords" class="list-group-item">Debit Note Records</a>
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
					 <h4>R.M.C. Management</h4>
				</div>
				
				<div class="content list-group bg-default bg-light-rtl">
					 <!--<a href="<?php echo $this->request->base;?>/Inventory/mixdesign" class="list-group-item">Mix Design</a>-->
					 <a href="<?php echo $this->request->base;?>/Inventory/mixdesignlisting" class="list-group-item">Mix Design</a>
					 <a href="<?php echo $this->request->base;?>/Inventory/prepareinventoryrmc" class="list-group-item">Prepare R.M.C. Issue</a>
					 <a href="<?php echo $this->request->base;?>/Inventory/inventoryrmcalert" class="list-group-item">R.M.C. Issue Alert</a>
					 <a href="<?php echo $this->request->base;?>/Inventory/inventoryrmcrecords" class="list-group-item">R.M.C. Issue Records</a>
				</div>
			</div>
		</div>
		
		<div class="col-md-4">
			<div class="block block-drop-shadow infobox">
			<div class="user bg-default bg-light-rtl">
					<div class="info">                                                                               
						<img src="<?php echo $this->request->base;?>/img/icon/material-return.png" class="img-circle img-thumbnail">				
					</div>
					<h4>Material Return Note (M.R.N.)</h4>
				</div>
				<div class="content list-group bg-default bg-light-rtl">
					<a href="<?php echo $this->request->base;?>/Inventory/preparemrn" class="list-group-item">Prepare M.R.N.</a>
					<a href="<?php echo $this->request->base;?>/Inventory/approvemrn" class="list-group-item">M.R.N. Alert</a>
					<a href="<?php echo $this->request->base;?>/Inventory/viewmrn" class="list-group-item">M.R.N. Records</a>
				</div>
			</div>
		</div>
	
		<div class="col-md-4">
			<div class="block block-drop-shadow infobox">
			<div class="user bg-default bg-light-rtl">
					<div class="info">                                                                               
						<img src="<?php echo $this->request->base;?>/img/icon/asset.png" class="img-circle img-thumbnail">				
					</div>
					<h4>Site to Site Transfer (S.S.T.)</h4>
				</div>
				<div class="content list-group bg-default bg-light-rtl">
					<a href="<?php echo $this->request->base;?>/Inventory/preparesst" class="list-group-item">Prepare S.S.T.</a>
					<a href="<?php echo $this->request->base;?>/Inventory/approvesst" class="list-group-item">S.S.T. Alert</a>
					<a href="<?php echo $this->request->base;?>/Inventory/viewsst" class="list-group-item">S.S.T. Records</a>
				</div>
			</div>
		</div>	
	
		<!-- <div class="col-md-4">
			<div class="block block-drop-shadow infobox">
			<div class="user bg-default bg-light-rtl">
					<div class="info">                                                                               
						<img src="<?php //echo $this->request->base;?>/img/icon/mine1.png" class="img-circle img-thumbnail">				
					</div>
					<h4>Records</h4>
				</div>
				<div class="content list-group bg-default bg-light-rtl">
					
					
				</div>
			</div>
		</div>	
		<div class="col-md-4">
			<div class="block block-drop-shadow infobox">
			<div class="user bg-default bg-light-rtl">
					<div class="info">                                                                               
						<img src="<?php //echo $this->request->base;?>/img/icon/purchase.png" class="img-circle img-thumbnail">				
					</div>
					<h4>Material & Brand</h4>
				</div>
				
				<div class="content list-group bg-default bg-light-rtl">
					<a href="<?php //echo $this->request->base;?>/Purchase/viewmaterial" class="list-group-item">Material List</a>
					<a href="<?php //echo $this->request->base;?>/Purchase/brandlist" class="list-group-item">Brand List</a>
				</div>
			</div>
		</div> -->
	</div>
	<div class="row">
		<!-- <div class="col-md-4">
			<div class="block block-drop-shadow infobox">
			<div class="user bg-default bg-light-rtl">
					<div class="info">                                                                               
						<img src="<?php echo $this->request->base;?>/img/icon/asset.png" class="img-circle img-thumbnail">				
					</div>
					<h4>RMC</h4>
				</div>
				<div class="content list-group bg-default bg-light-rtl">
					<a href="<?php echo $this->request->base;?>/Inventory/mixdesign" class="list-group-item">Mix Design</a>
					<a href="<?php echo $this->request->base;?>/Inventory/prepareinventoryrmc" class="list-group-item">Prepare RMC Issue</a>
					<a href="<?php echo $this->request->base;?>/Inventory/inventoryrmcalert" class="list-group-item">RMC Issue Alert</a>
					<a href="<?php echo $this->request->base;?>/Inventory/inventoryrmcrecords" class="list-group-item">RMC Issue Records</a>
				</div>
			</div>
		</div> -->
	
		<div class="col-md-4">
			<div class="block block-drop-shadow infobox">
			<div class="user bg-default bg-light-rtl">
					<div class="info">                                                                               
						<img src="<?php echo $this->request->base;?>/img/icon/bill.png" class="img-circle img-thumbnail">				
					</div>
					<h4>Stock Management</h4>
				</div>
				<div class="content list-group bg-default bg-light-rtl">
					<a href="<?php echo $this->request->base;?>/Inventory/stockledger" class="list-group-item">Stock Ledger</a>
					<!--<a href="<?php echo $this->request->base;?>/Inventory/urgentstockrequirment" class="list-group-item">Urgent Purchase Requirement</a>
					<a href="<?php echo $this->request->base;?>/Inventory/overpurchasedstock" class="list-group-item">Over Purchased Stock</a>-->
					<a href="<?php echo $this->request->base;?>/Inventory/viewrecords" class="list-group-item">Inventory Records</a>
					<!-- <a href="<?php echo $this->request->base;?>/Accounts/accountlist" class="list-group-item">Bill Records</a>	 -->
					<a href="javascript:void(0)" class="list-group-item">MIS Reports</a>
				</div>
			</div>
		</div>
	
		<div class="col-md-4">
			<div class="block block-drop-shadow infobox">
			<div class="user bg-default bg-light-rtl">
					<div class="info">                                                                               
						<img src="<?php echo $this->request->base;?>/img/icon/asset-log.png" class="img-circle img-thumbnail">				
					</div>
					<h4>Audit</h4>
				</div>
				<div class="content list-group bg-default bg-light-rtl">
					<a href="<?php echo $this->request->base;?>/Inventory/grnaudit" class="list-group-item">G.R.N. Audit</a>
					<a href="<?php echo $this->request->base;?>/Inventory/isaudit" class="list-group-item">I.S. Audit</a>
					<a href="<?php echo $this->request->base;?>/Inventory/rbnaudit" class="list-group-item">R.B.N. Audit</a>
				</div>
			</div>
		</div>
	
		<div class="col-md-4">
			<div class="block block-drop-shadow infobox">
			<div class="user bg-default bg-light-rtl">
					<div class="info">                                                                               
						<img src="<?php echo $this->request->base;?>/img/icon/asset-log.png" class="img-circle img-thumbnail">				
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
	
		<!-- <div class="col-md-4">
			<div class="block block-drop-shadow infobox">
			<div class="user bg-default bg-light-rtl">
					<div class="info">                                                                               
						<img src="<?php echo $this->request->base;?>/img/icon/bill.png" class="img-circle img-thumbnail">				
					</div>
					<h4>MIS Records</h4>
				</div>
				<div class="content list-group bg-default bg-light-rtl">
					
				</div>
			</div>
		</div> -->
	</div>
	
	<div class="row">
		<div class="col-md-4">
			<div class="block block-drop-shadow infobox">
			<div class="user bg-default bg-light-rtl">
					<div class="info">                                                                               
						<img src="<?php echo $this->request->base;?>/img/icon/bill.png" class="img-circle img-thumbnail">				
					</div>
					<h4>Bills Management</h4>
				</div>
				
				<div class="content list-group bg-default bg-light-rtl">
					<a href="<?php echo $this->request->base;?>/Accounts/addinwardbill" class="list-group-item">Inward Bills </a>
					<a href="<?php echo $this->request->base;?>/Accounts/accountlist" class="list-group-item">Bill Records</a>	
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
					<a href="<?php echo $this->request->base;?>/inventory/filemanager#inventory" class="list-group-item">File Manager</a>
				</div>
			</div>
		</div>
	</div>
</div>