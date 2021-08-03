<?php
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
	
	jQuery("body").on("change", "#asset_id", function(event){ 
	 
	  var asset_name  = jQuery(this).val() ;
		/* alert(product_id);
		return false; */
	   var curr_data = {	 						 					
	 					asset_name : asset_name,	 					
	 					};	 				
	 	 jQuery.ajax({
			headers: {
					'X-CSRF-Token': csrfToken
				},
                type:"POST",
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'getassetid'));?>",
                data:curr_data,
                async:false,
                success: function(response){					
					var json_obj = jQuery.parseJSON(response);					
					jQuery('#asset_code').val(json_obj['asset_code']);					
					return false;
                },
                error: function (e) {
                     alert('Error');
                     console.log(e.responseText);
                }
            });	
	});
		 				
	jQuery("body").on("change", "#project_id", function(event){ 
	  var project_id  = jQuery(this).val() ;
		/* alert(product_id);
		return false; */
	   var curr_data = {	 						 					
	 					project_id : project_id,	 					
	 					};	 				
	 	 jQuery.ajax({
			headers: {
					'X-CSRF-Token': csrfToken
				},
                type:"POST",
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'ingrnprojectdetaillppo'));?>",
                data:curr_data,
                async:false,
                success: function(response){					
					var json_obj = jQuery.parseJSON(response);					
					jQuery('#project_code').val(json_obj['project_code']);						
					return false;
                },
                error: function (e) {
                     alert('Error');
					 console.log(e.responseText);
                }
            });	
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
		<div class="block" style="width:auto;">
			<div class="head bg-default bg-light-rtl">
				<h2>RMC Records</h2>
				<div class="pull-right">
					<a href="<?php echo $this->ERPfunction->action_link('Inventory','index');?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
				</div>
			</div>
		
		<div class="content">
		<div class="col-md-12 filter-form">
			<?php 
				 $from_date = isset($_POST['from_date'])?$_POST['from_date']:'';
				 $to_date = isset($_POST['to_date'])?$_POST['to_date']:'';
			?>
		<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
					<div class="form-row">
						<div class="col-md-2">Date From -</div>
                        <div class="col-md-4"><input type="text" name="from_date" id="from_date" value="<?php echo $from_date;?>" class="datep form-control"/></div>
						<div class="col-md-2">Date To -</div>
                        <div class="col-md-4"><input type="text" name="to_date" id="to_date" value="<?php echo $to_date;?>" class="datep form-control"/></div>
					</div>
					<div class="form-row">
						<div class="col-md-2">Project Code</div>
						<div class="col-md-4"><input type="text" name="project_code" id="project_code" value="" class="form-control" value="" readonly="true"/></div>
						
						<div class="col-md-2">Project Name</div>
                        <div class="col-md-4">
							<select class="select2" style="width: 100%;" name="project_id" id="project_id">
							</select>
						</div>
                    </div>
					<div class="form-row">
						<div class="col-md-2">RMC. L. No.</div>
                        <div class="col-md-4"><input type="text" name="rmc_no" id="rmc_no" value="" class="form-control"/></div>
						<div class="col-md-2">Concrete Grade</div>
						<div class="col-md-4">
							<select class="select2" style="width: 100%;" name="concrete_grade" id="concrete_grade">
							<option value="">--Select Concretegrade--</Option>
							<?php 
								foreach($concrete_grade as $retrive_data)
								{
									echo '<option value="'.$retrive_data['id'].'">'.
									$retrive_data['concrete_grade'].'</option>';
								}
							?>
							</select>
						</div>
					</div>
					<div class="form-row">
						<div class="col-md-2">Asset Code</div>
                        <div class="col-md-4"><input type="text" name="asset_code" id="asset_code" value="" class="form-control" value="" readonly="true"/></div>
						<div class="col-md-2">Asset Name</div>
						<div class="col-md-4">
							<select class="select2" style="width: 100%;" name="asset_id" id="asset_id">
							<option value="">--Select Asset--</Option>
							<?php 
								foreach($asset_names as $retrive_data)
								{
									echo '<option value="'.$retrive_data['asset_id'].'">'.
									$retrive_data['asset_name'].'</option>';
								}
							?>
							</select>
						</div>
					</div>
												
					<div class="form-row">
						<div class="col-md-2"> <div class="col-md-12"><input type="submit" name="go" id="go" class="btn btn-primary" value="Search"/></div></div>
					</div>
		
		<?php echo $this->Form->end(); ?>
		
<input type="hidden" id="f_date_from" value="<?php echo isset($_POST["from_date"]) ? $_POST["from_date"] : "";?>">
<input type="hidden" id="f_date_to" value="<?php echo isset($_POST["to_date"]) ? $_POST["to_date"] : "";?>">
<input type="hidden" id="f_pro_id" value="<?php echo isset($_POST["project_id"]) ?$_POST["project_id"] : "";?>">
<input type="hidden" id="f_rmc_no" value="<?php echo isset($_POST["rmc_no"]) ? $_POST["rmc_no"] : "";?>">
<input type="hidden" id="f_concrete_grade" value="<?php echo isset($_POST["concrete_grade"]) ? $_POST["concrete_grade"] : "";?>">
<input type="hidden" id="f_asset_id" value="<?php echo isset($_POST["asset_id"]) ? $_POST["asset_id"] : "";?>">

			</div>
			</div>
<div class="content list custom-btn-clean">
	<script>
	jQuery(document).ready(function() {
		var f_date_from  = jQuery("#f_date_from").val();
		var f_date_to  = jQuery("#f_date_to").val();
		var f_pro_id  = jQuery("#f_pro_id").val();
		var f_rmc_no  = jQuery("#f_rmc_no").val();
		var f_concrete_grade  = jQuery("#f_concrete_grade").val();
		var f_asset_id  = jQuery("#f_asset_id").val();

	var selected = [];
	var table = jQuery('#rmc_list').DataTable({
		"pageLength": 10,
		"order": [[ 1, "desc" ]],
		columnDefs: [ 
					// {
						// searchable: false,
						// targets:   0,
					// },
					// {
						// searchable: false,
						// targets:   10,
					// },
					// {
						// searchable: false,
						// targets:   11,
					// },
					// {
						// searchable: false,
						// targets:   15,
					// },
					// {
						// searchable: false,
						// targets:   16,
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
						  ],
		"responsive" : true,
		"processing": true,
		"serverSide": true,
		//"ajax": "../Ajaxfunction/billrecordsdata",
		"ajax": {
			headers: {
					'X-CSRF-Token': csrfToken
				},
				"url": "../Ajaxfunction/viewinventoryrmcrecords",
				"data": function ( d ) {
											d.myKey = "myValue";
											d.date_from = f_date_from;
											d.date_to = f_date_to;
											d.pro_id = f_pro_id;
											d.rmc_no = f_rmc_no;
											d.concrete_grade = f_concrete_grade;
											d.asset_id = f_asset_id;
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
			<table id="rmc_list"  class="dataTables_wrapper table table-striped table-hover" style="width:100%">
				<thead>
					<tr>
						<th>Project</th>
						<th>Date</th>
						<th>RMC. L. No.</th>
						<th>Asset Name</th>							
						<th>Order By</th>
						<th>Concrete Grade</th>
						<th>Qty. Supplied(Cum)</th>
						<th>Usage</th>
						<th>Start Time</th>
						<th>End Time</th>
						<th>Action</th>
					</tr>
				</thead>
			</table>
			
			<div class="content">
				<div class="col-md-2">
					<?php 
						echo $this->Form->create("",["type"=>"file","class"=>"validateForm form-horizontal","role"=>"form","id"=>"export_csv"]);
					?>
					<input type="hidden" name="e_date_from" value="<?php echo isset($_POST["from_date"]) ? $_POST["from_date"] : "";?>">
					<input type="hidden" name="e_date_to" value="<?php echo isset($_POST["to_date"]) ? $_POST["to_date"] : "";?>">
					<input type="hidden" name="e_pro_id" value="<?php echo isset($_POST["project_id"]) ?$_POST["project_id"] : "";?>">
					<input type="hidden" name="e_rmc_no" value="<?php echo isset($_POST["rmc_no"]) ? $_POST["rmc_no"] : "";?>">
					<input type="hidden" name="e_concrete_grade" value="<?php echo isset($_POST["concrete_grade"]) ? $_POST["concrete_grade"] : "";?>">
					<input type="hidden" name="e_asset_id" value="<?php echo isset($_POST["asset_id"]) ? $_POST["asset_id"] : "";?>">
					<input type="submit" class="btn btn-success" value="Export To Excel" name="export_csv">
					<?php 
						echo $this->Form->end();
					?>
				</div>
				<div class="col-md-2">
					<?php 
						echo $this->Form->create("",["type"=>"file","class"=>"validateForm form-horizontal","role"=>"form","id"=>"export_pdf"]);
					?>
					<input type="hidden" name="e_date_from" value="<?php echo isset($_POST["from_date"]) ? $_POST["from_date"] : "";?>">
					<input type="hidden" name="e_date_to" value="<?php echo isset($_POST["to_date"]) ? $_POST["to_date"] : "";?>">
					<input type="hidden" name="e_pro_id" value="<?php echo isset($_POST["project_id"]) ?$_POST["project_id"] : "";?>">
					<input type="hidden" name="e_rmc_no" value="<?php echo isset($_POST["rmc_no"]) ? $_POST["rmc_no"] : "";?>">
					<input type="hidden" name="e_concrete_grade" value="<?php echo isset($_POST["concrete_grade"]) ? $_POST["concrete_grade"] : "";?>">
					<input type="hidden" name="e_asset_id" value="<?php echo isset($_POST["asset_id"]) ? $_POST["asset_id"] : "";?>">
					<input type="submit" class="btn btn-success" value="Export To PDF" name="export_pdf">
					<?php 
						echo $this->Form->end();
					?>
				</div>
			</div>
		</div>
		
		</div>
	</div>
</div>
<?php } ?>
</div>