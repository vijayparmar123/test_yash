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
	
		// Material data set after the page load
		jQuery.ajax({
			headers: {
				'X-CSRF-Token': csrfToken
			},
			type:"POST",
			url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'loadmaterial'));?>",
			async:false,
			success: function(response){
				jQuery('select.material_id').empty();
				jQuery('select.material_id').append(response);
				return false;
			},
			error: function (e) {
				alert('Error');
			}
		});	

		// Vendor data set after the page load
		jQuery.ajax({
			headers: {
				'X-CSRF-Token': csrfToken
			},
			type:"POST",
			url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'loadvendor'));?>",
			async:false,
			success: function(response){
				jQuery('select#agency_id').empty();
				jQuery('select#agency_id').append(response);
				return false;
			},
			error: function (e) {
				alert('Error');
			}
		});

		// Load Project Data
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
		if(!$is_capable) {
			$this->ERPfunction->access_deniedmsg();
		}
		else{
	?>   
	<div class="row">
		<div class="col-md-12">
			<div class="block">			
				<div class="head bg-default bg-light-rtl">
					<h2>View R.B.N </h2>
					<div class="pull-right">
						<a href="<?php echo $this->ERPfunction->action_link('Inventory','index');?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
					</div>
				</div>
			
				<div class="content">
					<div class="col-md-12 filter-form">
						<?php 
							$project_id = array();
							$material_id_a = array();
							$agency_a = array();
							$project_id = isset($_POST['project_id'])?$_POST['project_id']:'';
							$from_date = isset($_POST['from_date'])?$_POST['from_date']:'';
							$to_date = isset($_POST['to_date'])?$_POST['to_date']:'';
							$material_id_a = isset($_POST['material_id'])?$_POST['material_id']:'';
							$agency_a = isset($_POST['agency_id'])?$_POST['agency_id']:'';
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
							<div class="col-md-2 text-right">Vendor / Asset's Name</div>
							<div class="col-md-4">
							<select class="select2 agency_id" style="width: 100%;" name="agency_id[]" id="agency_id" multiple="multiple">
								<option value="All" selected>All</Option>
							</select>
							</div>
							<div class="col-md-2 text-right">RBN No</div>
							<div class="col-md-4"><input name="filter_rbn_no" class="" /></div>
							
						</div>
							
					<div class="form-row">
						<div class="col-md-2"> <div class="col-md-12"><input type="submit" name="go" id="go" class="btn btn-primary" value="Search"/></div></div>
					</div>
		
		<?php echo $this->Form->end(); ?>
<input type="hidden" id="f_date_from" value="<?php echo isset($_POST["from_date"]) ? $_POST["from_date"] : "";?>">
<input type="hidden" id="f_date_to" value="<?php echo isset($_POST["to_date"]) ? $_POST["to_date"] : "";?>">
<input type="hidden" id="f_pro_id" value="<?php echo isset($_POST["project_id"]) ? implode(",",$_POST["project_id"]) : "";?>">
<input type="hidden" id="f_material_id" value="<?php echo isset($_POST["material_id"]) ? implode(",",$_POST["material_id"]) : "";?>">
<input type="hidden" id="f_agency_id" value="<?php echo isset($_POST["agency_id"]) ? implode(",",$_POST["agency_id"]) : "";?>">
<input type="hidden" id="f_rbn_no" value="<?php echo isset($_POST["filter_rbn_no"]) ? $_POST["filter_rbn_no"] : "";?>">
			</div>
			</div>
				<div class="content list custom-btn-clean">
					<script>
						var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;

						jQuery(document).ready(function() {
							var f_date_from  = jQuery("#f_date_from").val();
							var f_date_to  = jQuery("#f_date_to").val();
							var f_pro_id  = jQuery("#f_pro_id").val();
							var f_material_id  = jQuery("#f_material_id").val();
							var f_agency_id  = jQuery("#f_agency_id").val();
							var f_rbn_no  = jQuery("#f_rbn_no").val();
						
							var selected = [];
							var table = jQuery('#rbn_list').DataTable({
								"pageLength": 10,
								"order": [[ 2, "desc" ]],
								columnDefs: [{
									searchable: false,
									targets:   0,
								},
								{
									searchable: false,
									targets:   5,
								},
								{
									searchable: false,
									targets:   7,
								},
								{
									searchable: false,
									targets:   6,
								},
								{
									searchable: false,
									targets:   8,
								},
								{
									searchable: false,
									targets:   9,
								},
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
								],
								"responsive" : true,
								"processing": true,
								"serverSide": true,
								"ajax": {
									headers: {
										'X-CSRF-Token': csrfToken
									},
									// "url": "../Ajaxfunction/viewrbndata",
									"url" : "<?php echo Router::url(array("controller"=> "Ajaxfunction","action"=> "viewrbndata")) ?>",
									"data": function ( d ) {
										d.myKey = "myValue";
										d.date_from = f_date_from;
										d.date_to = f_date_to;
										d.pro_id = f_pro_id;
										d.material_id = f_material_id;
										d.agency_id = f_agency_id;
										d.rbn_no = f_rbn_no;
									}
								},
								"rowCallback": function( row, data ) {				
									if ( jQuery.inArray(data.DT_RowId, selected) !== -1 ) {
										jQuery(row).addClass('selected');
									}
								},
							});
							jQuery("body").on("change", ".approve", function(event){
								var rbn_id = jQuery(this).val();
								if(confirm('Are you Sure approve this R.B.N.?')) {
									var curr_data = {	 						 					
									rbn_id : rbn_id,						
									};	 				
									jQuery.ajax({
										headers: {
											'X-CSRF-Token': csrfToken
										},
										type:"POST",
										url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'approverbn'));?>",
										data:curr_data,
										async:false,
										success: function(response){								
											location.reload();
											return false;
										},
										error: function (e) {
											alert('Error');
										}
									});
								}else {
									jQuery(this).prop('checked', true);
								}
							});	
						} );
					</script>
					<table id="rbn_list"  class="dataTables_wrapper table table-striped table-hover">
						<thead>
							<tr>
								<th>Project Name</th>
								<th>R. B. N. No.</th>									
								<th>Date</th>
								<th>Vendor/<br>Asset<br>Name</th>
								<th>Material<br>Name</th>
								<th>Make/<br>Source</th>
								<th>Returned<br>Quantity</th>
								<th>Unit</th>
								<th>Name of Foreman</th>
								<th>Edit/<br>View</th>
							</tr>
						</thead>
					</table>
					<?php 
						// if(isset($rbn_list)) {
						// 	if(!empty($rbn_list))  {
					?>
					<div class="content">
						<div class="col-md-2">
						<?php 
							echo $this->Form->create("",["type"=>"file","class"=>"validateForm form-horizontal","role"=>"form","id"=>"export_csv"]);
						?>
							<input type="hidden" name="f_date_from" id="" value="<?php echo isset($_POST["from_date"]) ? $_POST["from_date"] : "";?>">
							<input type="hidden" name="f_date_to" id="" value="<?php echo isset($_POST["to_date"]) ? $_POST["to_date"] : "";?>">
							<input type="hidden" name="pro_id" id="" value="<?php echo isset($_POST["project_id"]) ? implode(",",$_POST["project_id"]) : "";?>">
							<input type="hidden" name="f_material_id" id="" value="<?php echo isset($_POST["material_id"]) ? implode(",",$_POST["material_id"]) : "";?>">
							<input type="hidden" name="f_agency_id" id="" value="<?php echo isset($_POST["agency_id"]) ? implode(",",$_POST["agency_id"]) : "";?>">
							<input type="hidden" name="f_rbn_no" id="" value="<?php echo isset($_POST["filter_rbn_no"]) ? $_POST["filter_rbn_no"] : "";?>">
							<input type="submit" class="btn btn-success" value="Export To Excel" name="export_csv">
						<?php 
							echo $this->Form->end();
						?>
						</div>
						<div class="col-md-2">
						<?php 
							echo $this->Form->create("",["type"=>"file","class"=>"validateForm form-horizontal","role"=>"form","id"=>"export_pdf"]);
						?>
							<!-- <input type="hidden" name="rows" value='<?php //echo base64_encode(serialize($rows));?>'> -->
							<input type="hidden" name="f_date_from" id="f_date_from" value="<?php echo isset($_POST["from_date"]) ? $_POST["from_date"] : "";?>">
							<input type="hidden" name="f_date_to" id="f_date_to" value="<?php echo isset($_POST["to_date"]) ? $_POST["to_date"] : "";?>">
							<input type="hidden" name="pro_id" id="f_pro_id" value="<?php echo isset($_POST["project_id"]) ? implode(",",$_POST["project_id"]) : "";?>">
							<input type="hidden" name="f_material_id" id="f_material_id" value="<?php echo isset($_POST["material_id"]) ? implode(",",$_POST["material_id"]) : "";?>">
							<input type="hidden" name="f_agency_id" id="f_agency_id" value="<?php echo isset($_POST["agency_id"]) ? implode(",",$_POST["agency_id"]) : "";?>">
							<input type="hidden" name="f_rbn_no" id="f_rbn_no" value="<?php echo isset($_POST["filter_rbn_no"]) ? $_POST["filter_rbn_no"] : "";?>">
							<input type="submit" class="btn btn-success" value="Export To PDF" name="export_pdf">
						<?php 
							echo $this->Form->end();
						?>
						</div>
					</div>
					<?php //}
					//} 
					?>
				</div>
			</div>
		</div>
	</div>
	<?php }?>
</div>