<?php
use Cake\Routing\Router;
?>

<script type="text/javascript">
var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;

jQuery(document).ready(function() {
	
	// Load asset 
	jQuery.ajax({
		headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
		url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'loadassetissuestore'));?>",
		async:false,
		success: function(response){			
			jQuery('select#asset_name').empty();
			jQuery('select#asset_name').append(response);
			$("select#asset_name").prepend("<option value='All' selected>All</option>").val('');
			return false;
		},
		error: function (e) {
			 alert('Error');
		}
	});
	
	// Load material
	jQuery.ajax({
		headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
		url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'loadmaterial'));?>",
		async:false,
		success: function(response){
			jQuery('select#material_name').empty();
			jQuery('select#material_name').prepend(response);
			$("select#material_name").prepend("<option value='All' selected>All</option>").val('');
			
			return false;
		},
		error: function (e) {
			 alert('Error');
		}
	});
	
	// Server Side Datatable
	var f_data  = jQuery("#filter_data").val();
	
	var selected = [];
	var table = jQuery('#is_list').DataTable({
		"pageLength": 10,
		"order": [[ 0, "desc" ]],
		columnDefs: [ 
						{
							searchable: false,
							targets:   0,
						},
						{
							searchable: false,
							targets:   7,
						},
						{
							searchable: false,
							targets:   8,
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
				{ "visible": true }
						  ],
		"responsive" : true,
		"processing": true,
		"serverSide": true,
		"ajax": {
				"url": "../Ajaxfunction/viewstoreissue",
				"data": function ( d ) {
											d.myKey = "myValue";
											d.f_data = f_data;
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
else{
?>    				
<div class="block">					
	<div class="head bg-default bg-light-rtl">
		<h2><?php echo "View Store Issue Records";?></h2>
		<div class="pull-right">
			<a href="<?php echo $this->ERPfunction->action_link('Assets','index');?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
		</div>
	</div>		
					
	<div class="content">
		<div class="col-md-12 filter-form">
			<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
					
			<div class="form-row">	
				<div class="col-md-2">Project Name:</div>
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
				<div class="col-md-2 text-right">Asset Name</div>
				<div class="col-md-4">
				<select class="select2" style="width: 100%;" name="asset_name[]" id="asset_name" multiple="multiple">
					<option value="All" selected>All</Option>
				</select>
				</div>
			</div>
			
			<div class="form-row">	
				<div class="col-md-2 text-right">Material Name</div>
				<div class="col-md-4">
				<select class="select2" style="width: 100%;" name="material_name[]" id="material_name" multiple="multiple">
					<option value="All" selected>All</Option>
				</select>
				</div>
			</div>
							
			<div class="form-row">
				<div class="col-md-2"> <div class="col-md-12"><input type="submit" name="go" id="go" class="btn btn-primary" value="Search"/></div></div>
			</div>
			<?php echo $this->Form->end(); ?>
		<input type="hidden" id="filter_data" value='<?php echo (isset($_POST))?json_encode($_POST):""; ?>'>
		</div>
			</div>
				<div class="content list custom-btn-clean" style="overflow-x:scroll;">
				<table id="is_list" class="dataTables_wrapper table table-striped table-hover"style="width: 100% ;">
				<thead>
					<tr>
						<th>Date of Issue</th>
						<th>Asset ID</th>
						<th>Asset Name</th>
						<th>Capacity</th>
						<th>Asset Make</th>												
						<th>Material Name</th>						
						<th>Quantity Issued</th>
						<th>Unit</th>	
						<?php
						if($this->ERPfunction->retrive_accessrights($role,'previewapprovedis')==1)
						{
						?>
						<th>View IS</th>
						<?php
						}
						
						?>
						
					</tr>
				</thead>
				<!-- <tbody>
				<?php 
				$rows = array();
				$rows[] = array("Date of Issue","Asset ID","Asset Name","Capacity","Asset Make","Material Name","Quantity Issued","Unit");
				
				if(!empty($records))
				{
					foreach($records as $data)
					{
						$data = array_merge($data,$data["erp_inventory_is_detail"]);
						$asset_data = explode("_",$data["agency_name"]);
						$asset_id = $asset_data[1];
						$csv = array();
						echo "
						<tr>
							<td>".($csv[] = date("d-m-Y",strtotime($data['is_date'])))."</td>
							<td>".($csv[] = $this->ERPfunction->get_asset_code($asset_id))."</td>
							<td>".($csv[] = $this->ERPfunction->get_asset_name($asset_id))."</td>
							<td>".($csv[] = $this->ERPfunction->get_asset_capacity($asset_id))."</td>							
							<td>".($csv[] = $this->ERPfunction->get_asset_make($asset_id))."</td>
							<td>".($csv[] = $this->ERPfunction->get_material_title($data["material_id"]))."</td>
							<td>".($csv[] = $data['quantity'])."</td>
							<td>".($csv[] = $this->ERPfunction->get_items_units($data['material_id']))."</td>";
							if($this->ERPfunction->retrive_accessrights($role,'previewapprovedis')==1)
						{	
							echo "<td>
							<a href='../inventory/previewapprovedis/{$data['is_id']}' class='btn btn-clean btn-info'><i class='icon-eye-open'></i> View IS </a>
							</td>";
						}
						echo "
						</tr>"; 
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
						<input type="hidden" name="export_filter_data" value='<?php echo (isset($_POST))?json_encode($_POST):""; ?>'>
						<input type="submit" class="btn btn-success" value="Export To Excel" name="export_csv">
					<?php 
						echo $this->Form->end();
					?>
					</div>
					<div class="col-md-2">
					<?php 
						echo $this->Form->Create('export_pdf',['method'=>'post']);
					?>
						<input type="hidden" name="export_filter_data" value='<?php echo (isset($_POST))?json_encode($_POST):""; ?>'>
						<input type="submit" class="btn btn-success" value="Export To PDF" name="export_pdf">
					<?php 
						echo $this->Form->end();
					?>
					</div>
				</div>
				
				</div>				
				
		</div>
<?php } ?>		
</div>
						