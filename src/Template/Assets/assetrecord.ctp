<?php

//$this->extend('/Common/menu')
use Cake\Routing\Router;
?>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('.datep').datepicker({
		dateFormat: "dd-mm-yy",
		  changeMonth: true,
	        changeYear: true,
	        yearRange:'-65:+0',
	        onChangeMonthYear: function(year, month, inst) {
	            jQuery(this).val(month + "-" + year);
	        }                    
    });
});
</script>
<script type="text/javascript">
var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;

jQuery(document).ready(function() {

jQuery('.datepick').datepicker({
		dateFormat: "yy-mm-dd",
		  changeMonth: true,
	        changeYear: true,
	        yearRange:'-65:+0',
	        onChangeMonthYear: function(year, month, inst) {
	            jQuery(this).val(month + "-" + year);
	        }
                    
                });
// jQuery('.viewmodal').click(function(){			
jQuery('body').on('click','.viewmodal',function(){

				payid=jQuery(this).attr('id');
				jQuery('#modal-view').html('hello');
				var model  = jQuery(this).attr('data-type') ;
				var asset_id  = jQuery(this).attr('asset_id') ;
				var asset_code  = jQuery(this).attr('asset_code') ;
				var urlstring = '';
				 
				if(model == 'saledetails')
				{
					urlstring ="<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'viewsale'));?>";
				}
				if(model == 'transferedetails')
				{
					urlstring ="<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'viewtransfer'));?>";
				}
				if(model == 'maintenancedetials')
				{
					urlstring ="<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'viewmaintenancedetials'));?>";
				}
				if(model == 'theftdetails')
				{
					urlstring ="<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'viewtheftdetails'));?>";
				}
			 
				var curr_data = {type : model,asset_id:asset_id,asset_code:asset_code};	 				
					jQuery.ajax({
						
						headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
						url:urlstring,
						data:curr_data,
						async:false,
						success: function(response){ 
							//alert(response);
							jQuery('.modal-content').html(response);					
						},
						beforeSend:function(){
									jQuery('#modal-view').html('<center><img src=../images/4.gif width=120px><div><h3>Loading...</h3></div></center>');
								},
						error: function(e) {
								console.log(e);
								 }
					});			
			})				
	function loadIssueAssetHistory(asset_id)
	{
		var curr_data = {asset_id:asset_id};	 				
		jQuery.ajax({
			
			headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
			url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'viewissuedasset'));?>",
			data:curr_data,
			async:false,
			success: function(response){ 
				//alert(response);
				jQuery('#load_modal_issued_history .modal-content').html(response);		
			},
			beforeSend:function(){
						jQuery('#modal-view').html('<center><img src=../images/4.gif width=120px><div><h3>Loading...</h3></div></center>');
					},
			error: function(e) {
					console.log(e);
					 }
		});
	}
	jQuery('body').on('click','.issuedhistoryviewmodal',function(){	
		var asset_id  = jQuery(this).attr('asset_id') ;
		loadIssueAssetHistory(asset_id);
	});
	
	jQuery('body').on('click','.edit-issued-history',function(){

		$('#load_modal_issued_history').modal('hide');
		$('#load_modal_issued_edit_history').modal('show');
		var asset_id  = jQuery(this).attr('data-asset-id') ;
		var history_record_id  = jQuery(this).attr('data-record-id') ;
		
		var curr_data = {asset_id:asset_id,history_record_id:history_record_id};	 				
			jQuery.ajax({
			
				headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
				url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'updateissuedasset'));?>",
				data:curr_data,
				async:false,
				success: function(response){
					jQuery('#load_modal_issued_edit_history .modal-content').html(response);					
				},
				beforeSend:function(){
							jQuery('#modal-view').html('<center><img src=../images/4.gif width=120px><div><h3>Loading...</h3></div></center>');
						},
				error: function(e) {
						console.log(e);
						 }
			});			
	});
	
	jQuery('body').on('click','#update-issued-history',function(){

		var asset_id = jQuery("#load_modal_issued_edit_history #asset_id").val();
		var issue_to = jQuery("#issue_to").val();
		var issue_date = jQuery("#issue_date").val();
		var history_id = jQuery("#load_modal_issued_edit_history #history_id").val();
	
		var curr_data = {issue_to:issue_to,issue_date:issue_date,history_id:history_id};	 				
		jQuery.ajax({
			
			headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
			url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'updateassetissuedhistory'));?>",
			data:curr_data,
			async:false,
			success: function(response){
					if(response){
						$('#load_modal_issued_edit_history .modal-content').html('');
						$('#load_modal_issued_edit_history').modal('hide');
						loadIssueAssetHistory(asset_id);
						$('#load_modal_issued_history').modal('show');
					}					
			},
			beforeSend:function(){
						jQuery('#modal-view').html('<center><img src=../images/4.gif width=120px><div><h3>Loading...</h3></div></center>');
					},
			error: function(e) {
					console.log(e);
					 }
		});
	});
	
	jQuery('body').on('click','.delete-issued-history',function(){	

		var history_record_id  = jQuery(this).attr('data-record-id');
		var tr_row_id  = jQuery(this).attr('data-row-id');
		var curr_data = {history_record_id:history_record_id};	 				
		jQuery.ajax({
			
			headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
			url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'deleteassetissuedhistory'));?>",
			data:curr_data,
			async:false,
			success: function(response){
					if(response){
						$('#load_modal_issued_history .modal-content #tr_'+tr_row_id).remove();
					}					
			},
			beforeSend:function(){
						jQuery('#modal-view').html('<center><img src=../images/4.gif width=120px><div><h3>Loading...</h3></div></center>');
					},
			error: function(e) {
					console.log(e);
					 }
		});
	});
	
	function loadBookingAssetHistory(asset_id)
	{

		var curr_data = {asset_id:asset_id};

		jQuery.ajax({
			
			headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
			url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'assetbookinghistorylist'));?>",
			data:curr_data,
			async:false,
			success: function(response){ 
				//alert(response);
				jQuery('#load_modal_booking_history .modal-content').html(response);		
			},
			beforeSend:function(){
						jQuery('#modal-view').html('<center><img src=../images/4.gif width=120px><div><h3>Loading...</h3></div></center>');
					},
			error: function(e) {
					console.log(e);
					 }
		});
	}

	function EfficiencyHistory(asset_id)
	{
		var curr_data = {asset_id:asset_id};

		jQuery.ajax({
			headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
			url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'efficiencyhistorylist'));?>",
			data:curr_data,
			async:false,
			success: function(response){ 
				//alert(response);
				jQuery('#Efficiency_history .modal-content').html(response);		
			},
			beforeSend:function(){
						jQuery('#modal-view').html('<center><img src=../images/4.gif width=120px><div><h3>Loading...</h3></div></center>');
					},
			error: function(e) {
					console.log(e);
					 }
		});
	}
	
	jQuery('body').on('click','.bookinghistoryviewmodal',function(){	

		var asset_id  = jQuery(this).attr('asset_id') ;
		
		loadBookingAssetHistory(asset_id);
	});

	jQuery('body').on('click','.efficiencyhistorymodal',function(){	
		
		var asset_id  = jQuery(this).attr('asset_id') ;
		
		EfficiencyHistory(asset_id);
	});
	
	jQuery('body').on('click','.edit-booking-history',function(){

		$('#load_modal_booking_history').modal('hide');
		$('#load_modal_booking_edit_history').modal('show');
		var asset_id  = jQuery(this).attr('data-asset-id') ;
		var history_record_id  = jQuery(this).attr('data-record-id') ;
		
		var curr_data = {asset_id:asset_id,history_record_id:history_record_id};	 				
			jQuery.ajax({
				headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
				url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'editassetbooking'));?>",
				data:curr_data,
				async:false,
				success: function(response){
					jQuery('#load_modal_booking_edit_history .modal-content').html(response);					
				},
				beforeSend:function(){
							jQuery('#modal-view').html('<center><img src=../images/4.gif width=120px><div><h3>Loading...</h3></div></center>');
						},
				error: function(e) {
						console.log(e);
						 }
			});			
	});
	
	jQuery('body').on('click','#update-booking-history',function(){

		var asset_id = jQuery("#load_modal_booking_edit_history #asset_id").val();
		var requirement_date = jQuery("#requirement_date").val();
		var history_id = jQuery("#load_modal_booking_edit_history #history_id").val();
	
		var curr_data = {requirement_date:requirement_date,history_id:history_id};	 				
		jQuery.ajax({
			headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
			url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'updateassetbookinghistory'));?>",
			data:curr_data,
			async:false,
			success: function(response){
					if(response){
						$('#load_modal_booking_edit_history .modal-content').html('');
						$('#load_modal_booking_edit_history').modal('hide');
						loadBookingAssetHistory(asset_id);
						$('#load_modal_booking_history').modal('show');
					}					
			},
			beforeSend:function(){
						jQuery('#modal-view').html('<center><img src=../images/4.gif width=120px><div><h3>Loading...</h3></div></center>');
					},
			error: function(e) {
					console.log(e);
					 }
		});
	});
	
	jQuery('body').on('click','.delete-booking-history',function(){	
		var history_record_id  = jQuery(this).attr('data-record-id');
		var tr_row_id  = jQuery(this).attr('data-row-id');
		var curr_data = {history_record_id:history_record_id};	 				
		jQuery.ajax({
			headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
			url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'deleteassetbookinghistory'));?>",
			data:curr_data,
			async:false,
			success: function(response){
					if(response){
						$('#load_modal_booking_history .modal-content #tr_'+tr_row_id).remove();
					}					
			},
			beforeSend:function(){
						jQuery('#modal-view').html('<center><img src=../images/4.gif width=120px><div><h3>Loading...</h3></div></center>');
					},
			error: function(e) {
					console.log(e);
					 }
		});
	});
	
	/* Server side datatable listing */	
		var f_purchase_from_date  = jQuery("#f_purchase_from_date").val();
		var f_purchase_to_date  = jQuery("#f_purchase_to_date").val();
		var f_project_id  = jQuery("#f_project_id").val();
		var f_make_id  = jQuery("#f_make_id").val();
		var f_asset_group  = jQuery("#f_asset_group").val();
		var f_asset_name  = jQuery("#f_asset_name").val();
		var f_status  = jQuery("#f_status").val();
		var f_asset_id  = jQuery("#f_asset_id").val();
		var f_asset_capacity  = jQuery("#f_asset_capacity").val();
		var f_identity  = jQuery("#f_identity").val();

		var selected = [];
		var table = jQuery('#asset_list').DataTable({
			"pageLength": 10,
			"order": [[ 1, "desc" ]],
			columnDefs: [ 
						// {
							// searchable: false,
							// targets:   8,
						// },
						// {
							// searchable: false,
							// targets:   9,
						// }					
						],
				
			"columns": [
					{ "visible": true },
					{ "visible": true },
					{ "visible": true },
					{ "visible": true },
					{ "visible": true },
					{ "visible": true },
					{ "visible": true },
					{ "visible": true },
					{ "visible": true },
					{ "visible": true },
					{ "visible": true },
					{ "visible": true },
					{ "visible": true },
					{ "visible": true },
					{ "visible": true },
					{ "visible": true },
					{ "visible": true },
					{ "visible": true },
							  ],
			"responsive" : true,
			"processing": true,
			"serverSide": true,
			//"ajax": "../Ajaxfunction/billrecordsdata",
			"ajax": {
					"url": "../Ajaxfunction/assetrecords",
					"data": function ( d ) {
												d.myKey = "myValue";
												d.purchase_from_date = f_purchase_from_date;
												d.purchase_to_date = f_purchase_to_date;
												d.project_id = f_project_id;
												d.make_id = f_make_id;
												d.asset_group = f_asset_group;
												d.asset_name = f_asset_name;
												d.status = f_status;
												d.asset_id = f_asset_id;
												d.asset_capacity = f_asset_capacity;
												d.identity = f_identity;
											}
					},
			"rowCallback": function( row, data ) {
									
									if ( jQuery.inArray(data.DT_RowId, selected) !== -1 ) {
										jQuery(row).addClass('selected');
									}
							},
			});
			/* Server side datatable listing */
}); 
</script>
<div class="modal fade " id="load_modal" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content"></div>
    </div>
</div>
<div class="modal fade " id="load_modal_issued_history" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content"></div>
    </div>
</div>
<div class="modal fade " id="load_modal_issued_edit_history" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content"></div>
    </div>
</div>
<div class="modal fade " id="load_modal_booking_history" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content"></div>
    </div>
</div>
<div class="modal fade " id="load_modal_booking_edit_history" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content"></div>
    </div>
</div>
<div class="modal fade" id="Efficiency_history" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content"></div>
	</div>
</div>
<div class="col-md-10" >
<?php
if(!$is_capable)
{
	$this->ERPfunction->access_deniedmsg();
}
else{
?>    				
                <div class="block">					
					<div class="head bg-default bg-light-rtl">
						<h2><?php echo $form_header;?></h2>
						<div class="pull-right">
						<a href="<?php echo $this->ERPfunction->action_link($back_url,$back_page);?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
						</div>
					</div>		
					<!--
                    <div class="header">
                        <h2><u>Make Filter & Sort as per your Requirement</u></h2>
                    </div> -->
					
			<div class="content">
		<div class="col-md-12 filter-form">
			<?php 
				$project_id = array();
				$make_id_a = array();
				$asset_group = array();
				 $project_id = isset($_POST['project_id'])?$_POST['project_id']:'';
				 $purchase_from_date = isset($_POST['purchase_from_date'])?$_POST['purchase_from_date']:'';
				 $purchase_to_date = isset($_POST['purchase_to_date'])?$_POST['purchase_to_date']:'';
				 $make_id_a = isset($_POST['make_id'])?$_POST['make_id']:'';
				 $asset_group = isset($_POST['asset_group'])?$_POST['asset_group']:'';
			?>
		<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
					<div class="form-row">
						<div class="col-md-2">Date of Purchase From -</div>
                        <div class="col-md-4"><input type="text" name="purchase_from_date" id="purchase_from_date" value="<?php echo $purchase_from_date;?>" class="datep form-control"/></div>
						<div class="col-md-2">Date of Purchase To -</div>
                        <div class="col-md-4"><input type="text" name="purchase_to_date" id="purchase_to_date" value="<?php echo $purchase_to_date;?>" class="datep form-control"/></div>
					</div>
					<div class="form-row">	
						
						<div class="col-md-2">Currently Deployed To:</div>
                        <div class="col-md-4">
							<select class="select2" style="width: 100%;" name="project_id[]" id="project_id" multiple="multiple">
								<option value="All" selected>All</Option>
								<?php 
									foreach($projects as $retrive_data)
									{
										$selected = (in_array($retrive_data['project_id'],$project_id)) ? "selected" : "";
										echo '<option value="'.$retrive_data['project_id'].'" '. $selected .'>'.$retrive_data['project_name'].'</option>';
									}
								?>
							</select>
						</div>
						<div class="col-md-2">Make:</div>
                        <div class="col-md-4">
							<select class="select2 make_id" style="width: 100%;" name="make_id[]" id="make_id_0" multiple="multiple">
								<option value="All" selected>All</Option>
								<?php 
									foreach($makelist as $retrive_data)
									{
										$selected = (in_array($retrive_data['cat_id'],$make_id_a)) ? "selected" : "";
										echo '<option value="'.$retrive_data['cat_id'].'" '.$selected.'>'.
										$retrive_data['category_title'].'</option>';
									}
								?>
							</select>
						</div>
                    </div>
					<div class="form-row">
							<div class="col-md-2 text-right">Asset Group</div>
							<div class="col-md-4">								
								<select style="width: 100%;" class="select2"  name="asset_group[]" id="asset_group" multiple="multiple">
								<option value="All">All</option>
								<?php 
								foreach($asset_groups as $key => $retrive_data)
								{
									//echo '<option value="'.$retrive_data['id'].'" '.$this->ERPfunction->selected($retrive_data['id'],$asset_group).'>'.$this->ERPfunction->get_asset_group_name($retrive_data['id']).'</option>';
									$selected = (in_array($retrive_data['id'],$asset_group)) ? "selected" : "";
										echo '<option value="'.$retrive_data['id'].'" '.$selected.'>'.
										$this->ERPfunction->get_asset_group_name($retrive_data['id']).'</option>';
								}
								?>
								</select>
							</div>
							<div class="col-md-2 text-right">Asset Name</div>
							<div class="col-md-4">
							<?php
								echo $this->Form->select("asset_name",$asset_name,["empty"=>["All"=>"All"],"class"=>"select2","style"=>"width:100%","multiple"=>"multiple"]);
							?>
							</div>
						</div>
					
					<div class="form-row">
							<div class="col-md-2 text-right">Asset ID</div>
							<div class="col-md-4"><input name="asset_id" class="" /></div>
							<div class="col-md-2">Operational Status:</div>
							<div class="col-md-4">
							<?php
								$status = ["All"=>"All","working"=>"Working","breakdown"=>"Break Down","idle"=>"Idle","sold"=>"Sold","theft"=>"Theft"];
								echo $this->Form->select("status",$status,["class"=>"select2","style"=>"width:100%","multiple"=>"multiple"]);
							?>
							</div>
							
						</div>
						<div class="form-row">
							<div class="col-md-2 text-right">Identity / Veh. No.</div>
							<div class="col-md-4"><input name="identity" class="" /></div>
							<div class="col-md-2 text-right">Asset Capacity</div>
							<div class="col-md-4"><input name="asset_capacity" class="" /></div>
						</div>
							
					<div class="form-row">
						<div class="col-md-2"> <div class="col-md-12"><input type="submit" name="go" id="go" class="btn btn-primary" value="Search"/></div></div>
					</div>
		
		<?php echo $this->Form->end(); ?>
<input type="hidden" id="f_purchase_from_date" value="<?php echo isset($_POST["purchase_from_date"]) ? $_POST["purchase_from_date"] : "";?>">
<input type="hidden" id="f_purchase_to_date" value="<?php echo isset($_POST["purchase_to_date"]) ? $_POST["purchase_to_date"] : "";?>">
<input type="hidden" id="f_project_id" value="<?php echo isset($_POST["project_id"]) ? implode(",",$_POST["project_id"]) : "";?>">
<input type="hidden" id="f_make_id" value="<?php echo isset($_POST["make_id"]) ? implode(",",$_POST["make_id"]) : "";?>">
<input type="hidden" id="f_asset_group" value="<?php echo isset($_POST["asset_group"]) ? implode(",",$_POST["asset_group"]) : "";?>">
<input type="hidden" id="f_asset_name" value="<?php echo (isset($_POST["asset_name"]) && ($_POST["asset_name"] != '')) ? implode(",",$_POST["asset_name"]) : "";?>">
<input type="hidden" id="f_status" value="<?php echo (isset($_POST["status"]) && !empty($_POST["status"])) ? implode(",",$_POST["status"]) : "";?>">
<input type="hidden" id="f_asset_id" value="<?php echo isset($_POST["asset_id"]) ? $_POST["asset_id"] : "";?>">
<input type="hidden" id="f_asset_capacity" value="<?php echo isset($_POST["asset_capacity"]) ? $_POST["asset_capacity"] : "";?>">
<input type="hidden" id="f_identity" value="<?php echo isset($_POST["identity"]) ? $_POST["identity"] : "";?>">
			</div>
			</div>
				<div class="content list custom-btn-clean" style="overflow-x:scroll;">
				<table id="asset_list" class="dataTables_wrapper table table-striped table-hover"style="width: 100% ;">
				<thead>
					<tr>
						<th>Asset ID</th>
						<th>Asset Name</th>
						<th>Capacity</th>
						<th>Make</th>												
						<th>Identity<br>/Veh.No.</th>						
						<th>Date of <br>purchase</th>
						<th>Operational<br>Status</th>						
						<th>Currently<br>Deployed to</th>
						<!--<th>Deployed<br>Quantity</th>						
						<th>Unit</th>-->
						<th class="none">View<br>Purchase<br>History</th>
						<th class="none">View<br>Transfer<br>History</th>
						<th class="none">View<br>Issued<br>History</th>
						<th class="none">View<br>Sales<br>Details</th>
						<th class="none">View<br>Theft<br>Details</th>
						<th class="none">View<br>Operational<br>History</th>
						<th class="none">Total<br>Maintenance<br>Expence</th>
						<th class="none">View<br>Maintenance<br>History</th>
						<th class="none">View<br>Booking<br>History</th>
						<th class="none">View<br>Efficiency<br>History</th>
					</tr>
				</thead>
				<!--<tbody>
				<?php 
				$rows = array();
				$rows[] = array("Asset ID","Asset Name","Capacity","Make","Identity/Veh.No.","Date of purchase","Operational Status","Currently Deployed to","Total Maintenance Expence");
				
				if(!empty($search_data))
				{
					foreach($search_data as $data)
					{ $csv = array();
						echo "
						<tr>
							<td>".($csv[] = $data['asset_id']) ."</td>
							<td>".($csv[] = $data['asset_name']) ."</td>
							<td>".($csv[] = $data['capacity']) ."</td>
							<td>".($csv[] = $this->ERPfunction->get_category_title($data['asset_make'])) ."</td>							
							<td>".($csv[] = $data['vehicle_no']) ."</td>
							<td>".($csv[] = $data['purchase_date']->format("Y-m-d")) ."</td>
							<td>".($csv[] = $this->ERPfunction->get_asset_operational_status($data['asset_id'])) ."</td>
							
							<td>".($csv[] = $this->ERPfunction->get_projectname($data['deployed_to'])) ."</td>
							";
							if($this->ERPfunction->retrive_accessrights($role,'viewaddasset')==1)
							{
								echo "<td>";
								echo "<a href='./viewaddasset/{$data['asset_id']}' class='btn btn-clean btn-info'><i class='icon-eye-open'></i> View</a></td>";	
							}
							if($this->ERPfunction->retrive_accessrights($role,'ViewTransferHistory')==1)
							{
								echo "<td>";
							
								echo "<button type='button'  id='transfereasset' data-type='transferedetails' data-toggle='modal' 
								data-target='#load_modal' class='btn btn-info viewmodal btn-clean' asset_id='{$data['asset_id']}' asset_code='{$data['asset_code']}'><i class='icon-eye-open'></i> View</button></td>";
							}
							if($this->ERPfunction->retrive_accessrights($role,'ViewIssuedHistory')==1)
							{
								echo "<td>";
							
								echo "<button type='button'  id='issuedhistory' data-type='issuedhistory' data-toggle='modal' 
								data-target='#load_modal_issued_history' class='btn btn-info issuedhistoryviewmodal btn-clean' asset_id='{$data['asset_id']}'><i class='icon-eye-open'></i> View</button></td>";
							}
							if($this->ERPfunction->retrive_accessrights($role,'ViewSalesDetails')==1)
							{
								echo "<td>";
							
								echo "<button type='button'  id='transfereasset' data-type='saledetails' data-toggle='modal' 
								data-target='#load_modal' class='btn btn-info viewmodal btn-clean' asset_id='{$data['asset_id']}'>						
								<i class='icon-eye-open'></i> View </button></td>";
							}
							if($this->ERPfunction->retrive_accessrights($role,'ViewTheftDetails')==1)
							{
								echo "<td>";
							
								echo "<button type='button'  id='transfereasset' data-type='theftdetails' data-toggle='modal' data-target='#load_modal' class='btn btn-info viewmodal btn-clean' asset_id='{$data['asset_id']}'><i class='icon-eye-open'></i> View</a></button></td>";
							}
							if($this->ERPfunction->retrive_accessrights($role,'equipmentlogownrecord')==1)
							{
								echo "<td>";
							
								echo "<a href='./equipmentlogownrecord/{$data['asset_id']}' class='btn btn-clean btn-info'><i class='icon-eye-open'></i> View</a></td>";
							}
							if($this->ERPfunction->retrive_accessrights($role,'TotalMaintenanceExpence')==1)
							{
								echo "<td>".($csv[] = $this->ERPfunction->get_asset_expense($data['asset_id'])) ."</td>";
							}
							if($this->ERPfunction->retrive_accessrights($role,'maintenancerecords')==1)
							{
								echo "<td>";
							
								// echo "<button type='button'  id='transfereasset' data-type='maintenancedetials' data-toggle='modal' 
								// data-target='#load_modal' class='btn btn-info viewmodal btn-clean' asset_id='{$data['asset_id']}'>						
								// <i class='icon-eye-open'></i> View </button>";
								echo "<a href='./maintenancerecords/{$data['asset_id']}' class='btn btn-clean btn-info'><i class='icon-eye-open'></i> View</a></td>";
							}
							if($this->ERPfunction->retrive_accessrights($role,'ViewBookingHistory')==1)
							{
								echo "
								<td>
									<button type='button'  id='bookinghistory' data-type='bookinghistory' data-toggle='modal' 
									data-target='#load_modal_booking_history' class='btn btn-info bookinghistoryviewmodal btn-clean' asset_id='{$data['asset_id']}'><i class='icon-eye-open'></i> View</a></td></button>
								</td>";
							}
							?>
							<td>
									<?php
									$attached_files = json_decode($data['attach_file']);	
									$attached_label = json_decode(stripcslashes($data['attach_label']));	
									
									if(!empty($attached_files))
									{							
										$i = 0;
										foreach($attached_files as $file)
										{ 
										   if(!empty($file))
										   { ?>
												<a href="<?php echo $this->request->base;?>/img/users_images/<?php echo $file;?>" download="<?php echo $attached_label[$i];?>" class="btn btn-info btn-clean"><i class="icon-download-alt"></i><?php echo $attached_label[$i];?></a>
											<?php $i++;
											}
										}
									} ?>
								</td>	
							<?php
							echo "</tr>"; 
						$rows[] = $csv;	
					}
				}
				?>
				</tbody>-->
				</table>
				<div class="content">
					<div class="col-md-2"><a href="javascript:void(0);" class="btn btn-success" id="fullscreen" url='<?php echo $_SERVER['REQUEST_URI']; ?>' onClick="DoFullScreen()" >View Full Screen</a></div>
					<div class="col-md-2">
					<?php 
						echo $this->Form->Create('export_csv',['method'=>'post']);
					?>
						<!--<input type="hidden" name="rows" value='<?php echo serialize($rows);?>'>-->
						<input type="hidden" name="e_purchase_from_date" value="<?php echo isset($_POST["purchase_from_date"]) ? $_POST["purchase_from_date"] : "";?>">
						<input type="hidden" name="e_purchase_to_date" value="<?php echo isset($_POST["purchase_to_date"]) ? $_POST["purchase_to_date"] : "";?>">
						<input type="hidden" name="e_project_id" value="<?php echo isset($_POST["project_id"]) ? implode(",",$_POST["project_id"]) : "";?>">
						<input type="hidden" name="e_make_id" value="<?php echo isset($_POST["make_id"]) ? implode(",",$_POST["make_id"]) : "";?>">
						<input type="hidden" name="e_asset_group" value="<?php echo isset($_POST["asset_group"]) ? implode(",",$_POST["asset_group"]) : "";?>">
						<input type="hidden" name="e_asset_name" value="<?php echo (isset($_POST["asset_name"]) && ($_POST["asset_name"] != '')) ? implode(",",$_POST["asset_name"]) : "";?>">
						<input type="hidden" name="e_status" value="<?php echo (isset($_POST["status"]) && !empty($_POST["status"])) ? implode(",",$_POST["status"]) : "";?>">
						<input type="hidden" name="e_asset_id" value="<?php echo isset($_POST["asset_id"]) ? $_POST["asset_id"] : "";?>">
						<input type="hidden" name="e_asset_capacity" value="<?php echo isset($_POST["asset_capacity"]) ? $_POST["asset_capacity"] : "";?>">
						<input type="hidden" name="e_identity" value="<?php echo isset($_POST["identity"]) ? $_POST["identity"] : "";?>">
						<input type="submit" class="btn btn-success" value="Export To Excel" name="export_csv">
					<?php $this->Form->end(); ?>
					</div>
					<div class="col-md-2">
					<?php 
						echo $this->Form->Create('export_pdf',['method'=>'post']);
					?>
						<!--<input type="hidden" name="rows" value='<?php echo serialize($rows);?>'>-->
						<input type="hidden" name="e_purchase_from_date" value="<?php echo isset($_POST["purchase_from_date"]) ? $_POST["purchase_from_date"] : "";?>">
						<input type="hidden" name="e_purchase_to_date" value="<?php echo isset($_POST["purchase_to_date"]) ? $_POST["purchase_to_date"] : "";?>">
						<input type="hidden" name="e_project_id" value="<?php echo isset($_POST["project_id"]) ? implode(",",$_POST["project_id"]) : "";?>">
						<input type="hidden" name="e_make_id" value="<?php echo isset($_POST["make_id"]) ? implode(",",$_POST["make_id"]) : "";?>">
						<input type="hidden" name="e_asset_group" value="<?php echo isset($_POST["asset_group"]) ? implode(",",$_POST["asset_group"]) : "";?>">
						<input type="hidden" name="e_asset_name" value="<?php echo (isset($_POST["asset_name"]) && ($_POST["asset_name"] != '')) ? implode(",",$_POST["asset_name"]) : "";?>">
						<input type="hidden" name="e_status" value="<?php echo (isset($_POST["status"]) && !empty($_POST["status"])) ? implode(",",$_POST["status"]) : "";?>">
						<input type="hidden" name="e_asset_id" value="<?php echo isset($_POST["asset_id"]) ? $_POST["asset_id"] : "";?>">
						<input type="hidden" name="e_asset_capacity" value="<?php echo isset($_POST["asset_capacity"]) ? $_POST["asset_capacity"] : "";?>">
						<input type="hidden" name="e_identity" value="<?php echo isset($_POST["identity"]) ? $_POST["identity"] : "";?>">
						<input type="submit" class="btn btn-success" value="Export To PDF" name="export_pdf">
					<?php $this->Form->end(); ?>
					</div>
				</div>
				
				</div>				
				
		</div>
<?php } ?>		
</div>
						