<?php
//$this->extend('/Common/menu')
use Cake\Routing\Router;
?>
<div class="col-md-10" >
<div class="modal fade " id="load_modal" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content"></div>
    </div>
</div>
<?php
if(!$is_capable)
{
	$this->ERPfunction->access_deniedmsg();
}
else{
?>  

	<div class="col-md-12">
		<div class="block">
			<div class="head bg-default bg-light-rtl">
				<h2>P.O. Delivery Records</h2>
				<div class="pull-right">
					<a href="<?php echo $this->ERPfunction->action_link($back_url,$back_page);?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
				</div>
			</div>
		<div class="content ">
		<script>
		
var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
	jQuery(document).ready(function() {
		
		jQuery("body").on("click", ".delivery_status", function(event){ 
			var po_detail_id = jQuery(this).attr('data-id');
		  
			var curr_data = {	 						 					
							po_detail_id : po_detail_id,	 					
							};	 				
			jQuery.ajax({
				headers: {
					'X-CSRF-Token': csrfToken
				},
                type:"POST",
				url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'podeliveryhistory'));?>",
				data:curr_data,
				async:false,
				success: function(response){					
					jQuery('.modal-content').html(response);
				},
				error: function (e) {
					 alert('Error');
				}
			});
	
		});
		
		jQuery("body").on("click", ".po_receivemanual_entry", function(event){ 
			var po_detail_id = jQuery(this).attr('data-id');
		  
			var curr_data = {	 						 					
							po_detail_id : po_detail_id,	 					
							};	 				
			jQuery.ajax({
				headers: {
					'X-CSRF-Token': csrfToken
				},
                type:"POST",
				url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'receivepoquantitymanual'));?>",
				data:curr_data,
				async:false,
				success: function(response){					
					jQuery('.modal-content').html(response);
				},
				error: function (e) {
					 alert('Error');
				}
			});
	
		});
  
		var f_date_from  = jQuery("#f_date_from").val();
		var f_date_to  = jQuery("#f_date_to").val();
		var f_pro_id  = jQuery("#f_pro_id").val();
		var f_material_id  = jQuery("#f_material_id").val();
		var f_brand_id  = jQuery("#f_brand_id").val();
		var f_vendor_userid  = jQuery("#f_vendor_userid").val();
		var f_po_no  = jQuery("#f_po_no").val();
		var f_po_type  = jQuery("#f_po_type").val();

	var selected = [];
	var table = jQuery('#po_list').DataTable({
		"pageLength": 10,
		"order": [[ 1, "desc" ]],
		columnDefs: [ 
					{
						searchable: false,
						targets:   2,
					},
					{
						searchable: false,
						targets:   4,
					},
					{
						searchable: false,
						targets:   5,
					},
					{
						searchable: false,
						targets:   9,
					},
					{
						searchable: false,
						targets:   11,
					}
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
						  ],
		"responsive" : true,
		"processing": true,
		"serverSide": true,
		//"ajax": "../Ajaxfunction/billrecordsdata",
		"ajax": {
				// "url": "../Ajaxfunction/porecords",
				url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'podeliveryrecords'));?>",
				"data": function ( d ) {
											d.myKey = "myValue";
											d.date_from = f_date_from;
											d.date_to = f_date_to;
											d.pro_id = f_pro_id;
											d.material_id = f_material_id;
											d.brand_id = f_brand_id;
											d.vendor_userid = f_vendor_userid;
											d.po_no = f_po_no;
											d.po_type = f_po_type;
										}
				},
		"rowCallback": function( row, data ) {
								
								if ( jQuery.inArray(data.DT_RowId, selected) !== -1 ) {
									jQuery(row).addClass('selected');
								}
						},
		});
		
	} );
	</script>
		<script>
		
var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
		jQuery(document).ready(function() {
			jQuery('#from_date,#to_date').datepicker({
				dateFormat: "dd-mm-yy",
				changeMonth: true,
				changeYear: true,
				yearRange:'-65:+0',
				onChangeMonthYear: function(year, month, inst) {
					jQuery(this).val(month + "-" + year);
				}                    
			});
			// jQuery('#po_list').DataTable({responsive: true,"ordering": false,});
				
			jQuery('body').on('click','.cancelpo',function(event){
				// alert("ASd");
				
				// event.preventDefault();
				var del = false;
				if(confirm('1.Are you sure want to cancel P.O. ? Only approved materials from P.O. Alert will be cancelled.'))
				{
					if(confirm('2.Are you sure want to cancel P.O. ? Only approved materials from P.O. Alert will be cancelled.'))
					{
						if(confirm('3.Are you sure want to cancel P.O. ? Only approved materials from P.O. Alert will be cancelled.'))
						{
							del = true;
						}
					}
				}
				
				if(del)
				{
					return true;
				}else{
					return false;
				}
			});
		

		
		});
</script>
			<div class="col-md-12 filter-form">
			<?php 
$project_id = isset($request_data['project_id'])?$request_data['project_id']:'';
$from_date = isset($request_data['from_date'])?$request_data['from_date']:'';
$to_date = isset($request_data['to_date'])?$request_data['to_date']:'';
?>
				<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
					<div class="form-row">
						<div class="col-md-2">Date From -</div>
                        <div class="col-md-4"><input type="text" name="from_date" id="from_date" value="" class="form-control"/></div>
						<div class="col-md-2">Date To -</div>
                        <div class="col-md-4"><input type="text" name="to_date" id="to_date" value="" class="form-control"/></div>
					</div>
					<div class="form-row">	
					<div class="col-md-2">Material Name</div>
                        <div class="col-md-4">
							<select class="select2 material_id" style="width: 100%;" name="material_id[]" id="material_id_0" multiple="multiple">
								<option value="All">All</Option>
								<?php 
									foreach($material_list as $retrive_data)
									{
										echo '<option value="'.$retrive_data['material_id'].'">'.
										$retrive_data['material_title'].'</option>';
									}
								?>
							</select>
						</div>
                
						<div class="col-md-2">Project Name</div>
                        <div class="col-md-4">
							<select class="select2" style="width: 100%;" name="project_id[]" id="project_id" multiple="multiple">
								<option value="All">All</Option>
								<?php 
									foreach($projects as $retrive_data)
									{
										echo '<option value="'.$retrive_data['project_id'].'">'.$retrive_data['project_name'].'</option>';
									}
								?>
							</select>
						</div>
                    </div>
					
					<div class="form-row">	
						<div class="col-md-2">Make/Source</div>
                        <div class="col-md-4">
							<select class="select2"  style="width: 100%;" name="brand_id[]" id="brand_id" multiple="multiple">
								<option value="All">All</Option>
								<?php 
									foreach($brand_list as $retrive_data)
								{echo '<option value="'.$retrive_data['brand_id'].'">'.
										$retrive_data['brand_name'].'</option>';									
									
								}
								?>
							</select>
						</div>
						
						<div class="col-md-2">Vendor Name</div>
                        <div class="col-md-4">
							<select class="select2"  style="width: 100%;" name="vendor_userid[]" id="vendor_userid" multiple="multiple">
								<option value="All">All</Option>
								<?php 
									foreach($vendor_department as $retrive_data)
								{echo '<option value="'.$retrive_data['user_id'].'">'.
										$this->ERPfunction->get_vendor_name($retrive_data['user_id']).'</option>';									
									
								}
								?>
							</select>
						</div>
					</div>
					<div class="form-row">	
						<div class="col-md-2">P.O.No</div>
                        <div class="col-md-4">
							<input type="text" name="po_no" id="po_no" value="" class="form-control"/>
						</div>
						<!--<div class="col-md-2">P.O. Type</div>
                        <div class="col-md-4">
							<select name="po_type" class="select2" style="width:100%">
								<option value="All">All</option>
								<option value="po">P.O.</option>
								<option value="manual_po">P.O.(Manual)</option>
								<option value="local_po">P.O.(Local)</option>
							</select>
						</div>-->
					</div>			
					<div class="form-row">
						<div class="col-md-2"> <div class="col-md-12"><input type="submit" name="go" id="go" class="btn btn-primary" value="Search"/></div></div>
						
					</div>
				<?php $this->Form->end(); ?>
				
<input type="hidden" id="f_date_from" value="<?php echo isset($_POST["from_date"]) ? $_POST["from_date"] : $from;?>">
<input type="hidden" id="f_date_to" value="<?php echo isset($_POST["to_date"]) ? $_POST["to_date"] : $to;?>">
<input type="hidden" id="f_pro_id" value="<?php echo isset($_POST["project_id"]) ? implode(",",$_POST["project_id"]) : $projects_id;?>">
<input type="hidden" id="f_material_id" value="<?php echo isset($_POST["material_id"]) ? implode(",",$_POST["material_id"]) : "";?>">
<input type="hidden" id="f_brand_id" value="<?php echo isset($_POST["brand_id"]) ? implode(",",$_POST["brand_id"]) : "";?>">
<input type="hidden" id="f_vendor_userid" value="<?php echo isset($_POST["vendor_userid"]) ? implode(",",$_POST["vendor_userid"]) : "";?>">
<input type="hidden" id="f_po_no" value="<?php echo isset($_POST["po_no"]) ? $_POST["po_no"] : "";?>">
<input type="hidden" id="f_po_type" value="<?php echo isset($_POST["po_type"]) ? $_POST["po_type"] : "";?>">
			</div>
			</div>
<div class="content list custom-btn-clean">
			<table id="po_list"  class="dataTables_wrapper table table-striped table-hover" style="width:100%">
				<thead>
					<tr>
						<th>P.O. No</th>	
						<th>P.O. Date</th>	
						<th>Project Name</th>
						<th>Vendor Name</th>
						<th>Material Name</th>
						<th>Make/Source</th>
						<th>PO Quantity</th>
						<th>Received Quantity</th>
						<th>PO's Remaining Quantity</th>
						<th>Unit</th>						
						<th>Remarks</th>												
						<th>Action</th>
						<th class="never">Material Name</th>
						<th class="never">Material Name</th>
					</tr>
				</thead>
				<!--<tbody>
					<?php
						$i = 1;
						$rows = array();
						$rows[] = array("P.O. No","P.O.Date","Project Name","Vendor Name","Material Name","Make/Source","Quantity","Unit","Final Rate","Amount");
						
						foreach($po_list as $retrive_data)
						{
							$export = array();
							$retrive_data = array_merge($retrive_data,$retrive_data["erp_inventory_po_detail"]);
							 
							if($retrive_data['po_id'] != "")
							{ ?>
							<tr>
								<td><?php echo ($export[] = $retrive_data['po_no']); ?></td>
								<td><?php echo ($export[] = $this->ERPfunction->get_date($retrive_data['po_date'])); ?></td>
								
								<td><?php echo ($export[] = $this->ERPfunction->get_projectname($retrive_data['project_id']));?></td>
								<td><?php echo ($export[] = $this->ERPfunction->get_vendor_name($retrive_data['vendor_userid']));?></td>
								<td><?php echo ($export[] = is_numeric($retrive_data['material_id'])?$this->ERPfunction->get_material_title($retrive_data['material_id']):$retrive_data['material_id']);?></td>
								<td><?php echo ($export[] = is_numeric($retrive_data['brand_id'])?$this->ERPfunction->get_brandname($retrive_data['brand_id']):$retrive_data['brand_id']);?></td>
								<td><?php echo ($export[] = $retrive_data['quantity']);?></td>								
								<td><?php echo ($export[] = is_numeric($retrive_data['material_id'])?$this->ERPfunction->get_items_units($retrive_data['material_id']):$retrive_data['static_unit']);?></td>								
								<td><?php echo ($export[] = $retrive_data['single_amount']);?></td>								
								<td><?php echo ($export[] = $retrive_data['amount']);?></td>		
								<td>
								<?php 
								if($this->ERPfunction->retrive_accessrights($role,'viewporecords')==1)
								{
									if(date('Y-m-d',strtotime($retrive_data['po_date'])) > date('Y-m-d',strtotime('01-07-2017')))
									{
										echo $this->Html->link("<i class='icon-eye-open'></i> View",array('action' => 'previewpo2', $retrive_data['po_id']),
										array('escape'=>false,'class'=>'btn btn-primary btn-clean','target'=>'_blank'));
									}
									else
									{
										echo $this->Html->link("<i class='icon-eye-open'></i> View",array('action' => 'previewpo', $retrive_data['po_id']),
										array('escape'=>false,'class'=>'btn btn-primary btn-clean','target'=>'_blank'));
									}
								}
								
								if($this->ERPfunction->retrive_accessrights($role,'cancelpo')==1)
								{
									if($retrive_data['pr_mid'] != 0)
									{
										echo $this->Html->link("<i class='icon-trash'></i> Cancel P.O. ",array('action' => 'cancelpo', $retrive_data['po_id']),
										array('escape'=>false,'class'=>'btn btn-danger btn-clean cancelpo','target'=>'_blank'));
									}
									else if($retrive_data['is_custom'] != 0)
									{
										echo $this->Html->link("<i class='icon-trash'></i> Cancel P.O. ",array('action' => 'cancelpo', $retrive_data['po_id']),
										array('escape'=>false,'class'=>'btn btn-danger btn-clean cancelpo','target'=>'_blank'));
									}
									
								}
								?>
								</td>
							</tr>
						<?php
							} $i++;
							$rows[] = $export;
						}
						
						//For manual PO Print
						
						foreach($manual_po as $retrive)
						{
							$export = array();
							$retrive = array_merge($retrive,$retrive["erp_manual_po_detail"]);
							if($retrive['po_id'] != "")
							{ ?>
							<tr>
								<td><?php echo ($export[] = $retrive['po_no']); ?></td>
								<td><?php echo ($export[] = $this->ERPfunction->get_date($retrive['po_date'])); ?></td>
								
								<td><?php echo ($export[] = $this->ERPfunction->get_projectname($retrive['project_id']));?></td>
								<td><?php echo ($export[] = $this->ERPfunction->get_vendor_name($retrive['vendor_userid']));?></td>
								<td><?php echo ($export[] = is_numeric($retrive['material_id'])?$this->ERPfunction->get_material_title($retrive['material_id']):$retrive['material_id']);?></td>
								<td><?php echo ($export[] = is_numeric($retrive['brand_id'])?$this->ERPfunction->get_brandname($retrive['brand_id']):$retrive['brand_id']);?></td>
								<td><?php echo ($export[] = $retrive['quantity']);?></td>								
								<td><?php echo ($export[] = is_numeric($retrive['material_id'])?$this->ERPfunction->get_items_units($retrive['material_id']):$retrive['static_unit']);?></td>								
								<td><?php echo ($export[] = $retrive['single_amount']);?></td>								
								<td><?php echo ($export[] = $retrive['amount']);?></td>																					
								<td>
								<?php 
								if($user_role == "erphead" || $user_role == "erpmanager" || $user_role == "erpoperator" || $user_role == "ceo" || $user_role == "md" || $user_role == "projectdirector" || $user_role == "contractadmin" || $user_role == "projectcoordinator" || $user_role == "accounthead" || $user_role == "senioraccountant" || $user_role == "purchasehead" || $user_role == "purchasemanager" || $user_role == "constructionmanager" || $user_role == "billingengineer" || $user_role == "asset-inventoryhead" || $user_role == "deputymanagerelectric")
								{
									echo $this->Html->link("<i class='icon-eye-open'></i> View",array('action' => 'manualpreviewpo', $retrive['po_id']),array('escape'=>false,'class'=>'btn btn-primary btn-clean','target'=>'_blank'));
								}
								
								if($user_role == "erphead")
								{
									echo $this->Html->link("<i class='icon-trash'></i> Cancel P.O. ",array('action' => 'cancelpomanual', $retrive['po_id']),
									array('escape'=>false,'class'=>'btn btn-danger btn-clean cancelpo','target'=>'_blank'));
								}
								?>
								</td>
							</tr>
						<?php
							} $i++;
							$rows[] = $export;
						}
					?>
				</tbody>-->
			</table>
			<div class="content">
				<div class="col-md-2">
				<?php 
					echo $this->Form->create("",["type"=>"file","class"=>"validateForm form-horizontal","role"=>"form","id"=>"export_csv"]);
				?>
				<!-- <form method="post"> -->
					<input type="hidden" name="rows" value='<?php //echo base64_encode(serialize($rows));?>'>
					<input type="hidden" name="e_date_from" value="<?php echo isset($_POST["from_date"]) ? $_POST["from_date"] : $from;?>">
					<input type="hidden" name="e_date_to" value="<?php echo isset($_POST["to_date"]) ? $_POST["to_date"] : $to;?>">
					<input type="hidden" name="e_pro_id" value="<?php echo isset($_POST["project_id"]) ? implode(",",$_POST["project_id"]) : $projects_id;?>">
					<input type="hidden" name="e_material_id" value="<?php echo isset($_POST["material_id"]) ? implode(",",$_POST["material_id"]) : "";?>">
					<input type="hidden" name="e_brand_id" value="<?php echo isset($_POST["brand_id"]) ? implode(",",$_POST["brand_id"]) : "";?>">
					<input type="hidden" name="e_vendor_userid" value="<?php echo isset($_POST["vendor_userid"]) ? implode(",",$_POST["vendor_userid"]) : "";?>">
					<input type="hidden" name="e_po_no" value="<?php echo isset($_POST["po_no"]) ? $_POST["po_no"] : "";?>">
					<input type="hidden" name="e_po_type" value="<?php echo isset($_POST["po_type"]) ? $_POST["po_type"] : "";?>">
					<input type="submit" class="btn btn-success" value="Export To Excel" name="export_csv">
				<!-- </form> -->
				<?php 
					echo $this->Form->end();
				?>
				</div>
				<div class="col-md-2">
				<?php 
					echo $this->Form->create("",["type"=>"file","class"=>"validateForm form-horizontal","role"=>"form","id"=>"export_pdf"]);
				?>
				<!-- <form method="post"> -->
					<input type="hidden" name="rows" value='<?php //echo base64_encode(serialize($rows));?>'>
					<input type="hidden" name="e_date_from" value="<?php echo isset($_POST["from_date"]) ? $_POST["from_date"] : $from;?>">
					<input type="hidden" name="e_date_to" value="<?php echo isset($_POST["to_date"]) ? $_POST["to_date"] : $to;?>">
					<input type="hidden" name="e_pro_id" value="<?php echo isset($_POST["project_id"]) ? implode(",",$_POST["project_id"]) : $projects_id;?>">
					<input type="hidden" name="e_material_id" value="<?php echo isset($_POST["material_id"]) ? implode(",",$_POST["material_id"]) : "";?>">
					<input type="hidden" name="e_brand_id" value="<?php echo isset($_POST["brand_id"]) ? implode(",",$_POST["brand_id"]) : "";?>">
					<input type="hidden" name="e_vendor_userid" value="<?php echo isset($_POST["vendor_userid"]) ? implode(",",$_POST["vendor_userid"]) : "";?>">
					<input type="hidden" name="e_po_no" value="<?php echo isset($_POST["po_no"]) ? $_POST["po_no"] : "";?>">
					<input type="hidden" name="e_po_type" value="<?php echo isset($_POST["po_type"]) ? $_POST["po_type"] : "";?>">
					<input type="submit" class="btn btn-success" value="Export To PDF" name="export_pdf">
				<!-- </form> -->
				<?php 
					echo $this->Form->end();
				?>
				</div>
			</div>
		</div>
		</div>
	</div>

<?php } ?>
</div>