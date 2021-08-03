<?php
//$this->extend('/Common/menu')
use Cake\Routing\Router;
?>
<script type="text/javascript">
var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;

jQuery(document).ready(function() {
	jQuery.ajax({
		headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
		url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'loaduserprojects'));?>",
		async:false,
		success: function(response){
			var selected_project = $("#selected_project").val();
			
			jQuery('select#project_id').empty();
			jQuery('select#project_id').append(response);
			// $("select#project_id").prepend("<option value='All'>All</option>").val('');
			// $("select#project_id").select2("val", selected_project);
			return false;
		},
		error: function (e) {
			 alert('Error');
		}
	});
	jQuery.ajax({
		headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
		url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'loadassetmake'));?>",
		async:false,
		success: function(response){
			
			jQuery('select#make_id_0').empty();
			jQuery('select#make_id_0').append(response);
			// $("select#project_id").prepend("<option value='All'>All</option>").val('');
			// $("select#project_id").select2("val", selected_project);
			return false;
		},
		error: function (e) {
			 alert('Error');
		}
	});
	
	jQuery.ajax({
		headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
		url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'loadassetlist'));?>",
		async:false,
		success: function(response){
			
			jQuery('select#asset_dropdown').empty();
			jQuery('select#asset_dropdown').append(response);
			// $("select#project_id").prepend("<option value='All'>All</option>").val('');
			// $("select#project_id").select2("val", selected_project);
			return false;
		},
		error: function (e) {
			 alert('Error');
		}
	});
			
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
<script type="text/javascript" >
var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
		jQuery(document).ready(function() {
			
			// jQuery('#load_modal').on('hidden', function () {
			  // $(this).removeData('modal');
			// });
			
			/* jQuery('.viewmodal').click(function(){			 */
			jQuery('body').on('click','.viewmodal',function(){
				
				payid=jQuery(this).attr('id');
				quantity=jQuery(this).attr('quantity');
				
				jQuery('#modal-view').html();
				var model  = jQuery(this).attr('data-type') ;
				var asset_id  = jQuery(this).attr('asset_id') ;
				var urlstring = '';
				
				if(model == 'transfereasset')
				{
					urlstring ="<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'transfereasset'));?>";
				}
			 
				var curr_data = {type : model,asset_id:asset_id,quantity:quantity};	 				
					jQuery.ajax({
						headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
						url:urlstring,
						data:curr_data,
						async:false,
						success: function(response){                    
							jQuery('#load_modal .modal-content').html(response);					
						},
						beforeSend:function(){
									jQuery('#modal-view').html('<center><img src=../images/4.gif width=120px><div><h3>Loading...</h3></div></center>');
								},
						error: function(e) {
								console.log(e.responseText);
								 }
					});			
			});
			
			jQuery('body').on('click','.viewmodalaccept',function(){
				
				payid=jQuery(this).attr('id');
				quantity=jQuery(this).attr('quantity');
				
				jQuery('#modal-view').html();
				var model  = jQuery(this).attr('data-type') ;
				var asset_id  = jQuery(this).attr('asset_id') ;
				var urlstring = '';
				
				if(model == 'acceptasset')
				{
					urlstring ="<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'acceptasset'));?>";
				}
			 
				var curr_data = {type : model,asset_id:asset_id,quantity:quantity};	 				
					jQuery.ajax({
						headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
						url:urlstring,
						data:curr_data,
						async:false,
						success: function(response){                    
							jQuery('#load_modal1 .modal-content').html(response);
						},
						beforeSend:function(){
									jQuery('#modal-view').html('<center><img src=../images/4.gif width=120px><div><h3>Loading...</h3></div></center>');
								},
						error: function(e) {
								console.log(e.responseText);
								 }
					});			
			});
			
			jQuery('body').on('click','.issueviewmodal',function(){
				/* Check asset return from old issued code start */
				var asset_id  = jQuery(this).attr('asset_id') ;
				var ajax_data = {asset_id:asset_id};
				
				jQuery.ajax({
						headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
						url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'checkassetreturn'));?>",
						data:ajax_data,
						async:false,
						success: function(response){                    
							if(response == 1)
							{
								/* If asset not returned from last issue then show return date box code start */
								$('#return_issued_asset_load_modal').modal('show');
								jQuery('#return_issued_asset_load_modal #modal-view').html();
								var return_asset_data = {asset_id:asset_id};
								jQuery.ajax({
									headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
									url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'returnassetdate'));?>",
									data:return_asset_data,
									async:false,
									success: function(response){                    
										jQuery('#return_issued_asset_load_modal .modal-content').html(response);
									},
									beforeSend:function(){
												jQuery('#modal-view').html('<center><img src=../images/4.gif width=120px><div><h3>Loading...</h3></div></center>');
											},
									error: function(e) {
											console.log(e.responseText);
											 }
								});
								/* If asset not returned from last issue then show return date box code end */
							}else{
								/* If asset returned from last issue then show issue box code start */
								$('#issue_load_modal').modal('show');
								jQuery('#modal-view').html();
								var model  = jQuery(this).attr('data-type') ;
								
								var curr_data = {type : model,asset_id:asset_id};	 				
								jQuery.ajax({
									headers: {
										'X-CSRF-Token': csrfToken
									},
										type:"POST",
									url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'issueasset'));?>",
									data:curr_data,
									async:false,
									success: function(response){                    
										jQuery('#issue_load_modal .modal-content').html(response);
									},
									beforeSend:function(){
												jQuery('#modal-view').html('<center><img src=../images/4.gif width=120px><div><h3>Loading...</h3></div></center>');
											},
									error: function(e) {
											console.log(e.responseText);
											 }
								});
								/* If asset returned from last issue then show issue box code end */
							}
						},
						beforeSend:function(){
									jQuery('#modal-view').html('<center><img src=../images/4.gif width=120px><div><h3>Loading...</h3></div></center>');
								},
						error: function(e) {
								console.log(e.responseText);
								 }
					});	
				/* Check asset return from old issued code end */
							
			});
			
			$('body').on('click', '#submit_return_date', function (){
				var return_date = $("#asset_return_date").val();
				var return_asset_id = $("#return_asset_id").val();
				if(return_date == "")
				{
					alert("Please fill the date field.");
					return false;
				}
				
				var retur_asset_data = {return_date : return_date,return_asset_id:return_asset_id};	 				
				jQuery.ajax({
					headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
					url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'updateassetreturndate'));?>",
					data:retur_asset_data,
					async:false,
					success: function(response){                    
						if(response == 1)
						{
							/* If asset returned from last issue then show issue box code start */
							$('#return_issued_asset_load_modal').modal('hide');
							$('#issue_load_modal').modal('show');
							jQuery('#modal-view').html();
							var model  = 'issueasset';
							
							var curr_data = {type : model,asset_id:return_asset_id};	 				
							jQuery.ajax({
								headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
								url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'issueasset'));?>",
								data:curr_data,
								async:false,
								success: function(response){                    
									jQuery('#issue_load_modal .modal-content').html(response);
								},
								beforeSend:function(){
											jQuery('#modal-view').html('<center><img src=../images/4.gif width=120px><div><h3>Loading...</h3></div></center>');
										},
								error: function(e) {
										console.log(e.responseText);
										 }
							});
							/* If asset returned from last issue then show issue box code end */
						}
					},
					beforeSend:function(){
								jQuery('#modal-view').html('<center><img src=../images/4.gif width=120px><div><h3>Loading...</h3></div></center>');
							},
					error: function(e) {
							console.log(e.responseText);
							 }
				});
			});
			
			jQuery('body').on('click','.bookingviewmodal',function(){
								
				jQuery('#asset_booking_load_modal .modal-content').html();
				var model  = jQuery(this).attr('data-type') ;
				var asset_id  = jQuery(this).attr('asset_id') ;
				var urlstring = '';
				// return false;
				if(model == 'bookasset')
				{
					urlstring ="<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'bookingasset'));?>";
				}
			 
				var curr_data = {type : model,asset_id:asset_id};	 				
					jQuery.ajax({
						headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
						url:urlstring,
						data:curr_data,
						async:false,
						success: function(response){                    
							jQuery('#asset_booking_load_modal .modal-content').html(response);
						},
						beforeSend:function(){
									jQuery('#modal-view').html('<center><img src=../images/4.gif width=120px><div><h3>Loading...</h3></div></center>');
								},
						error: function(e) {
								console.log(e.responseText);
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
							  ],
			"responsive" : true,
			"processing": true,
			"serverSide": true,
			//"ajax": "../Ajaxfunction/billrecordsdata",
			"ajax": {
					"url": "../Ajaxfunction/assetmanagement",
					"data": function ( d ) {
												d.myKey = "myValue";
												d.purchase_from_date = f_purchase_from_date;
												d.purchase_to_date = f_purchase_to_date;
												d.project_id = f_project_id;
												d.make_id = f_make_id;
												d.asset_group = f_asset_group;
												d.asset_name = f_asset_name;
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
			
		} );
</script>
<div class="modal fade " id="load_modal" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content"></div>
    </div>
</div>
<div class="modal fade " id="load_modal1" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content"></div>
    </div>
</div>
<div class="modal fade " id="issue_load_modal" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content"></div>
    </div>
</div>
<div class="modal fade " id="return_issued_asset_load_modal" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content"></div>
    </div>
</div>
<div class="modal fade " id="asset_booking_load_modal" role="dialog">
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
<div class="row">
	<div class="col-md-12">
		<div class="block">			
			<div class="head bg-default bg-light-rtl">
				<h2>Asset Management</h2>
				<div class="pull-right">
				<a href="<?php echo $this->ERPfunction->action_link('Assets','index');?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
				</div>
			</div>
			
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
									// foreach($projects as $retrive_data)
									// {
										// $selected = (in_array($retrive_data['project_id'],$project_id)) ? "selected" : "";
										// echo '<option value="'.$retrive_data['project_id'].'" '. $selected .'>'.$retrive_data['project_name'].'</option>';
									// }
								?>
							</select>
						</div>
						<div class="col-md-2">Make:</div>
                        <div class="col-md-4">
							<select class="select2 make_id" style="width: 100%;" name="make_id[]" id="make_id_0" multiple="multiple">
								<option value="All" selected>All</Option>
								<?php 
									// foreach($makelist as $retrive_data)
									// {
										// $selected = (in_array($retrive_data['cat_id'],$make_id_a)) ? "selected" : "";
										// echo '<option value="'.$retrive_data['cat_id'].'" '.$selected.'>'.
										// $retrive_data['category_title'].'</option>';
									// }
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
							<select class="select2 make_id" style="width: 100%;" name="asset_name[]" id="asset_dropdown" multiple="multiple">
								<option value="All">All</Option>
							</select>
							<?php
								//echo $this->Form->select("asset_name",$asset_name,["empty"=>["All"=>"All"],"class"=>"select2","style"=>"width:100%","multiple"=>"multiple"]);
								
							?>
							</div>
						</div>
					
					<div class="form-row">
							<div class="col-md-2 text-right">Asset ID</div>
							<div class="col-md-4"><input name="asset_id" class="" /></div>
							<div class="col-md-2 text-right">Asset Capacity</div>
							<div class="col-md-4"><input name="asset_capacity" class="" /></div>
						</div>
						<div class="form-row">
							<div class="col-md-2 text-right">Identity / Veh. No.</div>
							<div class="col-md-4"><input name="identity" class="" /></div>
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
<input type="hidden" id="f_asset_id" value="<?php echo isset($_POST["asset_id"]) ? $_POST["asset_id"] : "";?>">
<input type="hidden" id="f_asset_capacity" value="<?php echo isset($_POST["asset_capacity"]) ? $_POST["asset_capacity"] : "";?>">
<input type="hidden" id="f_identity" value="<?php echo isset($_POST["identity"]) ? $_POST["identity"] : "";?>">
			</div>
			</div>
			
			
		<div class="content list custom-btn-clean">
		<table id="asset_list"  class="dataTables_wrapper table table-striped table-hover" style="width:100%">
				<thead>
					<tr>
						<th>Asset Group</th>
						<th>Asset ID</th>
						<th>Asset Name</th>
						<th>Capacity</th>
						<th>Make</th>						
						<th>Identity<br>/Veh.No.</th>
						<th>Current Operational Status</th>						
						<th>Currently Deployed To</th>
						<th>Currently Issued To</th>
						<th>Tentative Date of Release</th>
						<th>View</th>
						<th>Issued To</th>
						<th>Book</th>
						<th>Transfer</th>
						<th>Accept</th>
					</tr>
				</thead>
				<!--<tbody>
					<?php
						$i = 1;
						foreach($asset_list as $retrive_data)
						{
						?>
							<tr>
								<td><?php 
									 echo $this->ERPfunction->get_asset_group_name($retrive_data['asset_group']);?>   
								</td>
								<td><?php echo $retrive_data['asset_code']; ?></td>
								<td><?php echo $retrive_data['asset_name'];?></td>
								<td><?php echo $retrive_data['capacity'];?></td>
								<td><?php echo $this->ERPfunction->get_category_title($retrive_data['asset_make']);?></td>
								<td><?php echo $retrive_data['vehicle_no'];?></td>								
								<td><?php echo ucfirst($retrive_data['operational_status']);?></td>
								<td><?php echo  $this->ERPfunction->get_projectname($retrive_data['deployed_to']);?></td>
								<td><?php echo $this->ERPfunction->get_asset_last_issueto($retrive_data['asset_id']);?></td>
								<td><?php echo $this->ERPfunction->get_asset_release_date($retrive_data['asset_id']);?></td>
								<td>
								<?php 
								//View Asset 
								
								echo $this->Html->link("<i class='icon-eye-open'></i> View",array('action' => 'viewasset',$retrive_data['asset_id']),
								array('class'=>'btn btn-primary btn-clean','escape'=>false));
								//View Asset
								
								if($role =='erphead' || $role =='erpmanager' || $role =='md' || $role =='projectdirector' || $role == 'pmm' || $role == 'erpoperator')
								{
								echo $this->Html->link("<i class='icon-pencil'></i> Edit",array('action' => 'add',$retrive_data['asset_id']),
								array('class'=>'btn btn-primary btn-clean','escape'=>false));
								}
								echo ' ';
								/* echo $this->Html->link("<i class='icon-trash'></i> Delete",array('action' => 'delete', $retrive_data['asset_id']),
								array('class'=>'btn  btn-danger btn-clean','escape'=>false, 
								'confirm' => 'Are you sure you wish to Delete this Record?'));	 */
								echo ' ';
								
								?>
								</td>
								
								<?php
								if($this->ERPfunction->retrive_accessrights($role,'isseuasset')==1)
								{
								?>
									<td>
									
									<?php
										
											if($role =='constructionmanager' || $role =='billingengineer' || $role == 'assistantpmm')
											{
												$user_project_ids = $this->ERPfunction->users_project($user_id);
												if(in_array($retrive_data['deployed_to'],$user_project_ids))
												{
													echo '<button type="button"  id="issueasset" data-type="issueasset"  class="btn btn-info issueviewmodal btn-clean" asset_id="'.$retrive_data['asset_id'].'"> <i class="icon-random"></i>Issued To</button>';
												}
											}else{
												echo '<button type="button"  id="issueasset" data-type="issueasset"  class="btn btn-info issueviewmodal btn-clean" asset_id="'.$retrive_data['asset_id'].'"> <i class="icon-random"></i>Issued To</button>';
											}
										
										
									?>
								</td>
								<?php
								}
								
								if($this->ERPfunction->retrive_accessrights($role,'bookingassest')==1)
								{
								?>
								<td>
								<?php
								
									echo '<button type="button" id="bookasset" data-type="bookasset" data-toggle="modal" data-target="#asset_booking_load_modal" class="btn btn-info bookingviewmodal btn-clean" asset_id="'.$retrive_data['asset_id'].'"> <i class="icon-random"></i>Booking </button>';
								
								?>
								</td>
								<?php 
								}
								if($this->ERPfunction->retrive_accessrights($role,'transferasset')==1)
								{
								?>
								<td>
								<?php
									$accept_status = $this->ERPfunction->is_asset_accept_remain($retrive_data['asset_id']);
									if(!$accept_status){
									if($role == 'constructionmanager')
									{
										$allow_projects = $this->ERPfunction->users_project($user_id);
									if(in_array($retrive_data['deployed_to'],$allow_projects))
										{
										echo '<button type="button"  id="transfereasset" data-type="transfereasset" data-toggle="modal" 
										data-target="#load_modal" class="btn btn-info viewmodal btn-clean" asset_id="'.$retrive_data['asset_id'].'" quantity="'.$retrive_data['quantity'].'"> <i class="icon-random"></i>Transfer </button>';
										}
									}else{
										echo '<button type="button"  id="transfereasset" data-type="transfereasset" data-toggle="modal" 
										data-target="#load_modal" class="btn btn-info viewmodal btn-clean" asset_id="'.$retrive_data['asset_id'].'" quantity="'.$retrive_data['quantity'].'"> <i class="icon-random"></i>Transfer </button>';
									}
									}
								
								echo ' ';
								?>
								</td>
								<?php 
								}
								if($this->ERPfunction->retrive_accessrights($role,'acceptasset')==1)
								{
								?>
								<td>
									<?php 
										$accept_status = $this->ERPfunction->is_asset_accept_remain($retrive_data['asset_id']);
										
										if($accept_status){
											
										
										if($role == 'constructionmanager')
										{
											$user_project = $this->ERPfunction->users_project($user_id);
											$transfer_to_project = $this->ERPfunction->get_asset_last_transfer_project($retrive_data['asset_id']);
											if(in_array($transfer_to_project,$user_project)){
											echo '<button type="button"  id="acceptasset" data-type="acceptasset" data-toggle="modal" 
											data-target="#load_modal1" class="btn btn-info viewmodalaccept btn-clean" asset_id="'.$retrive_data['asset_id'].'" quantity="'.$retrive_data['quantity'].'"> <i class="icon-random"></i>Accept </button>';
											}
										}else{
											echo '<button type="button"  id="acceptasset" data-type="acceptasset" data-toggle="modal" 
											data-target="#load_modal1" class="btn btn-info viewmodalaccept btn-clean" asset_id="'.$retrive_data['asset_id'].'" quantity="'.$retrive_data['quantity'].'"> <i class="icon-random"></i>Accept </button>';
										}
										
										}
										
										
									?>
								</td>
							<?php } ?>
							</tr>
						<?php
						$i++;
						}
					?>
				</tbody>-->
			</table>
			
			<div class="content">
				<!-- <div class="col-md-2"><a href="javascript:void(0);" class="btn btn-success" id="fullscreen" url='<?php //echo $_SERVER['REQUEST_URI']; ?>' onClick="DoFullScreen()" >View Full Screen</a></div>
				-->
				<div class="col-md-2">
				<?php echo $this->Form->Create('form3',['id'=>'export','class'=>'form_horizontal formsize','method'=>'post'],['url'=>['action'=>'']]);?>
					<input type="hidden" name="rows" value='<?php //echo serialize($rows);?>'>
					<input type="hidden" name="e_purchase_from_date" value="<?php echo isset($_POST["purchase_from_date"]) ? $_POST["purchase_from_date"] : "";?>">
					<input type="hidden" name="e_purchase_to_date" value="<?php echo isset($_POST["purchase_to_date"]) ? $_POST["purchase_to_date"] : "";?>">
					<input type="hidden" name="e_project_id" value="<?php echo isset($_POST["project_id"]) ? implode(",",$_POST["project_id"]) : "";?>">
					<input type="hidden" name="e_make_id" value="<?php echo isset($_POST["make_id"]) ? implode(",",$_POST["make_id"]) : "";?>">
					<input type="hidden" name="e_asset_group" value="<?php echo isset($_POST["asset_group"]) ? implode(",",$_POST["asset_group"]) : "";?>">
					<input type="hidden" name="e_asset_name" value="<?php echo (isset($_POST["asset_name"]) && ($_POST["asset_name"] != '')) ? implode(",",$_POST["asset_name"]) : "";?>">
					<input type="hidden" name="e_asset_id" value="<?php echo isset($_POST["asset_id"]) ? $_POST["asset_id"] : "";?>">
					<input type="hidden" name="e_asset_capacity" value="<?php echo isset($_POST["asset_capacity"]) ? $_POST["asset_capacity"] : "";?>">
					<input type="hidden" name="e_identity" value="<?php echo isset($_POST["identity"]) ? $_POST["identity"] : "";?>">
					<input type="submit" class="btn btn-success" value="Export To Excel" name="export_csv">
				<?php $this->Form->end(); ?>
				</div>
				<div class="col-md-2">
				<?php echo $this->Form->Create('form3',['id'=>'export','class'=>'form_horizontal formsize','method'=>'post'],['url'=>['action'=>'']]);?>
					<input type="hidden" name="rows" value='<?php //echo serialize($rows);?>'>
					<input type="hidden" name="e_purchase_from_date" value="<?php echo isset($_POST["purchase_from_date"]) ? $_POST["purchase_from_date"] : "";?>">
					<input type="hidden" name="e_purchase_to_date" value="<?php echo isset($_POST["purchase_to_date"]) ? $_POST["purchase_to_date"] : "";?>">
					<input type="hidden" name="e_project_id" value="<?php echo isset($_POST["project_id"]) ? implode(",",$_POST["project_id"]) : "";?>">
					<input type="hidden" name="e_make_id" value="<?php echo isset($_POST["make_id"]) ? implode(",",$_POST["make_id"]) : "";?>">
					<input type="hidden" name="e_asset_group" value="<?php echo isset($_POST["asset_group"]) ? implode(",",$_POST["asset_group"]) : "";?>">
					<input type="hidden" name="e_asset_name" value="<?php echo (isset($_POST["asset_name"]) && ($_POST["asset_name"] != '')) ? implode(",",$_POST["asset_name"]) : "";?>">
					<input type="hidden" name="e_asset_id" value="<?php echo isset($_POST["asset_id"]) ? $_POST["asset_id"] : "";?>">
					<input type="hidden" name="e_asset_capacity" value="<?php echo isset($_POST["asset_capacity"]) ? $_POST["asset_capacity"] : "";?>">
					<input type="hidden" name="e_identity" value="<?php echo isset($_POST["identity"]) ? $_POST["identity"] : "";?>">
					<input type="submit" class="btn btn-success" value="Export To PDF" name="export_pdf">
				<?php $this->Form->end(); ?>
				</div>
			</div>
		</div>
		</div>
	</div>
</div>
<?php } ?>
</div>