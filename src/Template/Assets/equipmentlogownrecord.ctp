<?php
use Cake\Routing\Router;
?>
<script type="text/javascript">
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
		var f_data  = jQuery("#filter_data").val();
	
		var selected = [];
		var table = jQuery('#equipment_list').DataTable({
			"pageLength": 10,
			"order": [[ 0, "desc" ]],
			columnDefs: [{
				searchable: false,
				targets:   0,
			}],
			
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
				url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'equipmentlogownrecords'));?>",
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
		
		jQuery('body').on('click','.deleteequipmentlog',function(event){
			var del = false;
			if(confirm('Are you sure you wish delete this record?')) {
				if(confirm('Are you sure you wish delete this record?')) {
					del = true;
				}
			}
			
			if(del) {
				return true;
			}else {
				return false;
			}
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
			<h2><?php echo $form_header;?> </h2>
			<div class="pull-right">
				<a href="<?php echo $this->ERPfunction->action_link('Assets','index');?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
			</div>
		</div>
		
		<?php echo $this->Form->Create('form1',['id'=>'eq_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>

            <div class="content controls">
				<div class="form-row">
					<div class="col-md-2 text-right">Date From</div>
					<div class="col-md-4"><input name="date_from" class="datepick form-control"></div>
					<div class="col-md-2 text-right">To</div>
					<div class="col-md-4"><input name="date_to" class="datepick form-control"></div>
				</div>	
				
				<div class="form-row">
					<div class="col-md-2 text-right">E.L. No.</div>
					<div class="col-md-4"><input name="elno" class="form-control"></div>
					<div class="col-md-2 text-right">Project Name</div>
					<div class="col-md-4">
						<select class="select2"  style="width: 100%;" name="project_id[]" id="project_id" multiple="multiple">
							<option value="All">All</Option>
							<?php 
							foreach($projects as $retrive_data){
							?>
								<option value="<?php echo $retrive_data['project_id'];?>" <?php 
								if(isset($update_inward)){
										if($update_inward['project_id'] == $retrive_data['project_id'])
										{
											echo 'selected="selected"';
										}
	
								}?> >
								<?php echo $retrive_data['project_name']; ?> </option>
							<?php										
								}
							?>
						</select>
					</div>
				</div>
				
				<div class="form-row">
					<div class="col-md-2 text-right">Asset ID</div>
					<div class="col-md-4"><input name="asset_id" class="form-control"></div>
					<div class="col-md-2 text-right">Asset Name</div>
					<div class="col-md-4">
						
						<select style="width: 100%;" class="select2"  name="asset_name[]" id="asset_namelist" multiple="multiple">
						<option value="All">All</option>
						<?php 
						foreach($asset_list as $key=>$value)
						{
							$selected = ($key == $asset_id)?"selected":"";
							echo '<option value="'.$key.'">'.$value.'</option>';
						}
						?>
					</select>
					</div>
				</div>
				
				<div class="form-row">
					<div class="col-md-2 text-right">Vehicle's No.</div>
					<div class="col-md-4"><input name="vehicle_no" class="form-control"></div>
					<div class="col-md-2 text-right">Ownership</div>
					<div class="col-md-4">
						<select class="select2"  style="width: 100%;" name="ownership">
							<option value="All">All</Option>
							<option value="rent" selected>On Rent</Option>
							<option value="owned">owned</Option>
						</select>
					</div>
				</div>
						
				<div class="form-row">
					<div class="col-md-2 col-md-offset-2 text-left">
						<button type="submit" name="search" value="Search" class="btn btn-primary">Search</button>
					</div>
				</div>
						
			</div>
		<?php echo $this->Form->end();?>
		
		<input type="hidden" id="filter_data" value='<?php echo (isset($_POST))?json_encode($_POST):""; ?>'>
					
		<div class="content list custom-btn-clean">
			<table id="equipment_list"  class="dataTables_wrapper table table-striped table-hover">
				<thead>
					<tr>
						<th>Date</th>
						<th>E.L No.</th>
						<th>Asset Name</th>
						<th>Identity / Veh.No.</th>						
						<th>Operational Status</th>						
						<th>Usage(km)</th>
						<th>Usage(hr.)</th>
						<th>Driver Name</th>
						<th>Action</th>
					</tr>
				</thead>
			</table>
			
			<div class="content">
				<div class="col-md-2">
					<button class="btn btn-success">View Full Screen</button>
				</div>
				<div class="col-md-2">
					<?php 
						echo $this->Form->Create('export_csv',['method'=>'post']);
					?>
						<input type="hidden" name="export_filter_data" value='<?php echo (isset($_POST))?json_encode($_POST):""; ?>'>
						<input type="submit" class="btn btn-success" value="Export To Excel" name="export_csv">
					<?php $this->Form->end(); ?>
				</div>
				<div class="col-md-2">
					<?php 
						echo $this->Form->Create('export_pdf',['method'=>'post']);
					?>
						<input type="hidden" name="export_filter_data" value='<?php echo (isset($_POST))?json_encode($_POST):""; ?>'>
						<input type="submit" class="btn btn-success" value="Export To PDF" name="export_pdf">
					<?php $this->Form->end(); ?>
				</div>
			</div>
		
		</div>				
				
	</div>
<?php } ?>
</div>
						