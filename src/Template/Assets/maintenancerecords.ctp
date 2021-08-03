<?php
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
		url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'loadasset'));?>",
		async:false,
		success: function(response){			
			jQuery('select#asset_name').empty();
			jQuery('select#asset_name').append(response);
			$("select#asset_name").prepend("<option value=''>All</option>").val('');
			return false;
		},
		error: function (e) {
			 alert('Error');
		}
	});
	
	jQuery('body').on('click','.cancelmaintenance',function(event){
		var del = false;
		if(confirm('Are you sure you wish delete this record?'))
		{
			if(confirm('Are you sure you wish delete this record?'))
			{
				del = true;
			}
		}
		
		if(del)
		{
			return true;
		}else{
			return false;
		}
	});
			
	var f_pro_id  = jQuery("#f_pro_id").val();
	var f_asset_name  = jQuery("#f_asset_name").val();
	var f_asset_group  = jQuery("#f_asset_group").val();
	var f_maintenance_type  = jQuery("#f_maintenance_type").val();
	var f_identity  = jQuery("#f_identity").val();
	var f_payment_type  = jQuery("#f_payment_type").val();

	var selected = [];
	var table = jQuery('#asset_list').DataTable({
		"pageLength": 10,
		"order": [[ 2, "desc" ]],
		columnDefs: [ 
						{
							searchable: false,
							targets:   0,
						},
						{
							searchable: false,
							targets:   3,
						},
						{
							searchable: false,
							targets:   7,
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
						  ],
		"responsive" : true,
		"processing": true,
		"serverSide": true,
		"ajax": {
				url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'assetmaintenancerecords'));?>",
				// "url": "../Ajaxfunction/assetmaintenancerecords",
				"data": function ( d ) {
											d.myKey = "myValue";
											d.pro_id = f_pro_id;
											d.asset_name = f_asset_name;
											d.asset_group = f_asset_group;
											d.maintenance_type = f_maintenance_type;
											d.identity = f_identity;
											d.payment_type = f_payment_type;
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
		<h2>Asset Maintenance Records</h2>
		<div class="pull-right">
			<a href="<?php echo $this->ERPfunction->action_link('Assets','index');?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
		</div>
	</div>		
	
	<div class="content">
		<div class="col-md-12 filter-form">
		<?php 
		$project_id = array();
		$asset_group = array();
		$asset_name = array();
		$project_id = isset($_POST['project_id'])?$_POST['project_id']:'';
		$asset_group = isset($_POST['asset_group'])?$_POST['asset_group']:'';
		$asset_name = isset($_POST['asset_name'])?$_POST['asset_name']:'';
		?>
		<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
			
			<div class="form-row">	
				<div class="col-md-2">Project:</div>
				<div class="col-md-4">
					<select class="select2" style="width: 100%;" name="project_id[]" id="project_id" multiple="multiple">
						<option value="" selected>All</Option>
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
					<option value="" selected>All</Option>
					<?php 
						foreach($asset_list as $retrive_data)
						{
							$selected = (in_array($retrive_data['asset_id'],$asset_name)) ? "selected" : "";
							echo '<option value="'.$retrive_data['asset_id'].'" '. $selected .'>'.$retrive_data['asset_name'].'</option>';
						}
					?>
				</select>
				</div>
			</div>
			<div class="form-row">
				<div class="col-md-2 text-right">Asset Group</div>
				<div class="col-md-4">								
					<select style="width: 100%;" class="select2"  name="asset_group[]" id="asset_group" multiple="multiple">
					<option value="">All</option>
					<?php 
					foreach($asset_groups as $key => $retrive_data)
					{
						$selected = (in_array($retrive_data['id'],$asset_group)) ? "selected" : "";
						echo '<option value="'.$retrive_data['id'].'" '.$selected.'>'.
						$this->ERPfunction->get_asset_group_name($retrive_data['id']).'</option>';
					}
					?>
					</select>
				</div>
				<div class="col-md-2 text-right">Maintenance Type</div>
				<div class="col-md-4">								
					<select style="width:100%;" class="select2"  name="maintenance_type[]" id="maintenance_type" multiple="multiple">
						<option value="">All</option>
						<option value="0">Preventive / Routine</option>
						<option value="1">Corrective / Breakdown</option>
					</select>
				</div>
			</div>
			
			<div class="form-row">
				<div class="col-md-2 text-right">Identity / Veh. No.</div>
				<div class="col-md-4"><input name="identity" class="" /></div>
				<div class="col-md-2 text-right">Payment Type</div>
				<div class="col-md-4">								
					<select style="width:100%;" class="select2"  name="payment_type[]" id="payment_type" multiple="multiple">
						<option value="1">Cash</option>
						<option value="2">Cheque</option>
					</select>
				</div>
			</div>
					
			<div class="form-row">
				<div class="col-md-2"> <div class="col-md-12"><input type="submit" name="go" id="go" class="btn btn-primary" value="Search"/></div></div>
			</div>

		<?php echo $this->Form->end(); ?>
		<input type="hidden" id="f_pro_id" value="<?php echo isset($_POST["project_id"]) ? implode(",",$_POST["project_id"]) : "";?>">
		<input type="hidden" id="f_asset_name" value="<?php echo isset($_POST["asset_name"]) ? implode(",",$_POST["asset_name"]) : "";?>">
		<input type="hidden" id="f_asset_group" value="<?php echo isset($_POST["asset_group"]) ? implode(",",$_POST["asset_group"]) : "";?>">
		<input type="hidden" id="f_maintenance_type" value="<?php echo isset($_POST["maintenance_type"]) ? implode(",",$_POST["maintenance_type"]) : "";?>">
		<input type="hidden" id="f_identity" value="<?php echo isset($_POST["identity"]) ? $_POST["identity"] : "";?>">
		<input type="hidden" id="f_payment_type" value="<?php echo isset($_POST["payment_type"]) ? implode(",",$_POST["payment_type"]) : "";?>">

		</div>
	</div>
	<div class="content list custom-btn-clean" style="overflow-x:scroll;">
	<table id="asset_list" class="dataTables_wrapper table table-striped table-hover"style="width: 100% ;">
	<thead>
		<tr>
			<th>Project Name</th>
			<th>Date</th>
			<th>A.M.O No.</th>
			<th>Asset Group</th>
			<th>Asset ID</th>
			<th>Asset Name</th>
			<th>Capacity</th>
			<th>Identity<br>/Vehi.No.</th>						
			<th>Maintenance Type</th>						
			<th>Amount of Expense</th>						
			<th>Payment</th>
			<th>Action</th>
		</tr>
	</thead>
	</table>
	<div class="content">
		<div class="col-md-2">
			<a href="javascript:void(0);" class="btn btn-success" id="fullscreen" url='<?php echo $_SERVER['REQUEST_URI']; ?>' onClick="DoFullScreen()" >View Full Screen</a>
		</div>
		<div class="col-md-2">
			<?php echo $this->Form->Create('form3',['id'=>'export','class'=>'form_horizontal formsize','method'=>'post'],['url'=>['action'=>'']]);?>
				<input type="hidden" name="pro_id" value="<?php echo isset($_POST["project_id"]) ? implode(",",$_POST["project_id"]) : "";?>">
				<input type="hidden" name="asset_name" value="<?php echo isset($_POST["asset_name"]) ? implode(",",$_POST["asset_name"]) : "";?>">
				<input type="hidden" name="asset_group" value="<?php echo isset($_POST["asset_group"]) ? implode(",",$_POST["asset_group"]) : "";?>">
				<input type="hidden" name="maintenance_type" value="<?php echo isset($_POST["maintenance_type"]) ? implode(",",$_POST["maintenance_type"]) : "";?>">
				<input type="hidden" name="identity" value="<?php echo isset($_POST["identity"]) ? $_POST["identity"] : "";?>">
				<input type="hidden" name="payment_type" value="<?php echo isset($_POST["payment_type"]) ? implode(",",$_POST["payment_type"]) : "";?>">
				<input type="submit" class="btn btn-success" value="Export To Excel" name="export_csv">
			<?php $this->Form->end(); ?>
		</div>
		<div class="col-md-2">
			<?php echo $this->Form->Create('form3',['id'=>'export','class'=>'form_horizontal formsize','method'=>'post'],['url'=>['action'=>'']]);?>
				<input type="hidden" name="pro_id" value="<?php echo isset($_POST["project_id"]) ? implode(",",$_POST["project_id"]) : "";?>">
				<input type="hidden" name="asset_name" value="<?php echo isset($_POST["asset_name"]) ? implode(",",$_POST["asset_name"]) : "";?>">
				<input type="hidden" name="asset_group" value="<?php echo isset($_POST["asset_group"]) ? implode(",",$_POST["asset_group"]) : "";?>">
				<input type="hidden" name="maintenance_type" value="<?php echo isset($_POST["maintenance_type"]) ? implode(",",$_POST["maintenance_type"]) : "";?>">
				<input type="hidden" name="identity" value="<?php echo isset($_POST["identity"]) ? $_POST["identity"] : "";?>">
				<input type="hidden" name="payment_type" value="<?php echo isset($_POST["payment_type"]) ? implode(",",$_POST["payment_type"]) : "";?>">
				<input type="submit" class="btn btn-success" value="Export To PDF" name="export_pdf">
			<?php $this->Form->end(); ?>
		</div>
	</div>

	</div>				

</div>
<?php } ?>		
</div>
						