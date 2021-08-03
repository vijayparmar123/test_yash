<?php
//$this->extend('/Common/menu')
use Cake\Routing\Router;
?>
<script type="text/javascript">
var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
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
	jQuery.ajax({
		headers: {
					'X-CSRF-Token': csrfToken
				},
		type:"POST",
		url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'loaduserprojects'));?>",
		async:false,
		success: function(response){
			jQuery('select#project_id').empty();
			jQuery('select#project_id').append(response);
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
		url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'loadmaterial'));?>",
		async:false,
		success: function(response){
			jQuery('select#material_id_0').empty();
			jQuery('select#material_id_0').prepend(response);
			return false;
		},
		error: function (e) {
			 alert('Error');
		}
	});
			
	// Datatable server side
	var f_date_from  = jQuery("#f_date_from").val();
	var f_date_to  = jQuery("#f_date_to").val();
	var f_pro_id  = jQuery("#f_pro_id").val();
	var f_material_id  = jQuery("#f_material_id").val();
	var f_purchase_mod  = jQuery("#f_purchase_mod").val();
	var f_pr_no  = jQuery("#f_pr_no").val();
	
	var selected = [];
	var table = jQuery('#pr_list').DataTable({
		"pageLength": 10,
		"order": [[ 0, "desc" ]],
		columnDefs: [ 
						{
							searchable: false,
							targets:   0,
						},
						{
							searchable: false,
							targets:   4,
						}
					],
			
		"columns": [
				{ "visible": true },
				{ "visible": true },
				{ "visible": true },
				{ "visible": true },
				{ "visible": true }
						  ],
		"responsive" : true,
		"processing": true,
		"serverSide": true,
		"ajax": {
			headers: {
					'X-CSRF-Token': csrfToken
				},
				url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'viewprrecords'));?>",
				"data": function ( d ) {
											d.myKey = "myValue";
											d.date_from = f_date_from;
											d.date_to = f_date_to;
											d.pro_id = f_pro_id;
											d.material_id = f_material_id;
											d.purchase_mod = f_purchase_mod;
											d.pr_no = f_pr_no;
										}
				},
		"rowCallback": function( row, data ) {
						if ( jQuery.inArray(data.DT_RowId, selected) !== -1 ) {
							jQuery(row).addClass('selected');
						}
					},
	});
});
</script>
<div class="col-md-10" >
<?php 
if(!$is_capable)
	{
		$this->ERPfunction->access_deniedmsg();
	}
else
{ ?>
<div class="row">
	<div class="col-md-12">
		<div class="block">
			<div class="head bg-default bg-light-rtl">
				<h2>View P.R. </h2>
				<div class="pull-right">
				<a href="<?php echo $this->ERPfunction->action_link($back_url,$back_page);?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
				</div>
			</div>
			
			<div class="content">
		<div class="col-md-12 filter-form">
			<?php 
				$project_id = array();
				$material_id_a = array();
				$vendor_userid_a = array();
				 $project_id = isset($_POST['project_id'])?$_POST['project_id']:'';
				 $from_date = isset($_POST['from_date'])?$_POST['from_date']:'';
				 $to_date = isset($_POST['to_date'])?$_POST['to_date']:'';
				 $material_id_a = isset($_POST['material_id'])?$_POST['material_id']:'';
				 $vendor_userid_a = isset($_POST['vendor_userid'])?$_POST['vendor_userid']:'';
				 $pr_no = isset($_POST['pr_no'])?$_POST['pr_no']:'';
				 $purchase_mod = isset($_POST['purchase_mod'])?$_POST['purchase_mod']:'';
			?>
		<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
					<div class="form-row">
						<div class="col-md-2">Date From -</div>
                        <div class="col-md-4"><input type="text" name="from_date" id="from_date" value="<?php echo $from_date;?>" class="datep form-control"/></div>
						<div class="col-md-2">Date To -</div>
                        <div class="col-md-4"><input type="text" name="to_date" id="to_date" value="<?php echo $to_date;?>" class="datep form-control"/></div>
					</div>
					<div class="form-row">
						<div class="col-md-2">Project Name:</div>
                        <div class="col-md-4">
							<select class="select2" style="width: 100%;" name="project_id[]" id="project_id" multiple="multiple">
								<option value="All" selected>All</Option>
							</select>
						</div>
						<div class="col-md-2">Material Name:</div>
                        <div class="col-md-4">
							<select class="select2 material_id" style="width: 100%;" name="material_id[]" id="material_id_0" multiple="multiple">
								<option value="All" selected>All</Option>
							</select>
						</div>
                    </div>
					
					<div class="form-row">
							<div class="col-md-2 text-right">P.R. No.</div>
							<div class="col-md-4"><input name="filter_pr_no" value="<?php echo $pr_no ?>" class="" /></div>
							<div class="col-md-2 text-right">Mode of Purchase</div>
							<div class="col-md-4">
							<select class="select2"   style="width: 100%;" name="filter_purchase_mod" id="purchase_mod" >
								<option value="All">-- Select Purchase Mod --</Option>
								<option value="central" <?php echo ($purchase_mod == "central")?"selected":""; ?>>Central</Option>
								<option value="local" <?php echo ($purchase_mod == "local")?"selected":""; ?>>Local</Option>
								
								</select>
							</div>
						</div>	
					<div class="form-row">
						<div class="col-md-2"> <div class="col-md-12"><input type="submit" name="go" id="go" class="btn btn-primary" value="Search"/></div></div>
					</div>
		
		<?php echo $this->Form->end(); ?>
	<input type="hidden" id="f_date_from" value="<?php echo isset($_POST["from_date"]) ? $_POST["from_date"] : "";?>">
	<input type="hidden" id="f_date_to" value="<?php echo isset($_POST["to_date"]) ? $_POST["to_date"] : "";?>">
	<input type="hidden" id="f_pro_id" value="<?php echo isset($_POST["project_id"]) ? implode(",",$_POST["project_id"]) : "";?>">
	<input type="hidden" id="f_material_id" value="<?php echo isset($_POST["material_id"]) ? implode(",",$_POST["material_id"]) : "";?>">
	<input type="hidden" id="f_pr_no" value="<?php echo isset($_POST["filter_pr_no"]) ? $_POST["filter_pr_no"] : "";?>">
	<input type="hidden" id="f_purchase_mod" value="<?php echo isset($_POST["filter_purchase_mod"]) ? $_POST["filter_purchase_mod"] : "";?>">
			</div>
			</div>
		
		<div class="content list custom-btn-clean">
		<script>
		// jQuery(document).ready(function() {
			// jQuery('#pr_list').DataTable({responsive: {
												// details: {
													// type: 'column',
													// target: -1
												// }
											// },
											// columnDefs: [ {
												// className: 'control',
												// orderable: false,
												// targets:   -1
											// } ],
											// aaSorting: [[ 0, "desc" ]]});
		// });
</script>
			<table id="pr_list" style="width:100%" class="dataTables_wrapper table table-striped table-hover">
				<thead>
					<tr>
						<!--<th style="display:none">id</th>-->
						<th>Project Name</th>
						<th>P.R. No</th>						
						<th>Date</th>
						<!--<th>Time</th>					
						<th>Material Name</th>						
						<th>Make/Source</th>						
						<th>Quanity</th>
						<th>Unit</th>
						<th>Mode of<br>Purchase</th>-->
						<th>Attachment</th>
						<th>View</th>
					</tr>
				</thead>
				<!--<tbody>
					<?php
					if(isset($pr_list))
					{
						$rows = array();
						$rows[] = array("Project Name","P.R. NO.","Date Time","Material Code","Material Name","Make/Source","Quanity","Unit","Mode of Purchase","Delivery/Date");
						// debug($pr_list->fetchAll("assoc"));die;
						$i = 1;
						foreach($pr_list as $retrive_data)
						{
							$export = array();
							//var_dump($retrive_data["pr_id"]);
							//var_dump($retrive_data["approved_date"]);
							$apr_date = $retrive_data["approved_date"];
							$retrive_data = array_merge($retrive_data,$retrive_data["erp_inventory_purhcase_request"]);
							
							// if(is_numeric($retrive_material['material_id']) && $retrive_material['material_id'] != 0)
							// {
								// $m_code = $this->ERPfunction->get_materialitemcode($retrive_material['material_id']);
								// $mt = $this->ERPfunction->get_material_title($retrive_material['material_id']);
								// $brnd = $this->ERPfunction->get_brandname($retrive_material['brand_id']);
								// $unit = $this->ERPfunction->get_items_units($retrive_material['material_id']);
							// }
							// else
							// {
								// $m_code = $retrive_material['m_code'];
								// $mt = $retrive_material['material_name'];
								// $brnd = $retrive_material['brand_name'];
								// $unit = $retrive_material['static_unit'];
							// }
							
						?>
							<tr>
								<!--<td style="display:none"><?php echo $retrive_data["pr_material_id"];?></td>-->
								<td><?php echo ($export[] = $this->ERPfunction->get_projectname($retrive_data['project_id']));?></td>
								<td><?php echo ($export[] = $retrive_data['prno']);?></td>								
								<td><?php echo ($export[] = ($apr_date != "0000-00-00 00:00:00" && $apr_date != NULL)? date("d-m-Y",strtotime($apr_date)) : "NA");?></td>
								<td><?php echo ($export[] = ($apr_date != "0000-00-00 00:00:00" && $apr_date != NULL)? date("H:i",strtotime($apr_date)) : "NA");?></td>
																
								<!--<td><?php echo ($export[] = $mt);?></td>								
								<td><?php echo ($export[] = $brnd);?></td>								
								<td><?php echo ($export[] = $retrive_data['quantity']);?></td>								
								<td><?php echo ($export[] = $unit);?></td>								
								<td><?php echo ($export[] = ($retrive_data['show_in_purchase'] == 1) ? "Central" : "Local");?></td>								
								<td><?php echo ($export[] = $this->ERPfunction->get_date($retrive_data['delivery_date']));?></td>		
								<td>
								<?php 
									
									echo $this->Html->link("<i class='icon-eye-open'></i> View",array('action' => 'previewprapprove',$retrive_data['pr_id']),
									array('class'=>'btn btn-primary btn-clean','target'=>'_blank','escape'=>false));
									
									if($role == "erphead" || $role == "erpmanager")
									{
										echo $this->Html->link("<i class='icon-trash'></i> Delete",array('action' => 'unapprovepr',$retrive_data['pr_material_id']),
										array('class'=>'btn btn-danger btn-clean','target'=>'_blank','escape'=>false));
									}
								?>
								</td>						
								<td></td>						
							</tr>
						<?php
						$i++;
						$rows[] = $export;
						}
						}
					?>
				</tbody>-->
			</table>
			<?php 
			// debug($pr_list);die;
			?>
			<?php
				if(isset($pr_list))
				{
					if($pr_list != NULL)
					{
			?>
			<div class="content">
				<!-- <div class="col-md-2"><a href="javascript:void(0);" class="btn btn-success" id="fullscreen" url='<?php //echo $_SERVER['REQUEST_URI']; ?>' onClick="DoFullScreen()" >View Full Screen</a></div>
				-->
				<div class="col-md-2">
				<form method="post">
					<input type="hidden" name="rows" value='<?php echo base64_encode(serialize($rows));?>'>
					<input type="submit" class="btn btn-success" value="Export To Excel" name="export_csv">
				</form>
				</div>
				<div class="col-md-2">
				<form method="post">
					<input type="hidden" name="rows" value='<?php echo base64_encode(serialize($rows));?>'>
					<input type="submit" class="btn btn-success" value="Export To PDF" name="export_pdf">
				</form>
				</div>
				</div>
			</div>
			<?php } } ?>
		
		
	</div>
</div>
<?php } ?>
</div>