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
			url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'loadvendor'));?>",
			async:false,
			success: function(response){
				jQuery('select#vendor_userid').empty();
				jQuery('select#vendor_userid').append(response);
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
		}else{ 
	?>
	<div class="row">
		<div class="col-md-12">
			<div class="block" style="width:auto;">
				<div class="head bg-default bg-light-rtl">
					<h2>View GRN</h2>
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
							<div class="col-md-2">Vendor Name:</div>
							<div class="col-md-4">
								<select class="select2"  style="width: 100%;" name="vendor_userid[]" id="vendor_userid" multiple="multiple">
									<option value="All" selected>All</Option>
								</select>
							</div>
							<div class="col-md-2 text-right">Payment Method</div>
							<div class="col-md-4">
								<select class="select2"   style="width: 100%;" name="filter_payment_mod" id="payment_type" >
									<option value="">-- Select Payment --</Option>
									<option value="Cheque" <?php echo (isset($_POST["filter_payment_mod"]) && $_POST["filter_payment_mod"]=="Cheque") ? "selected":"";?>>Cheque</Option>
									<option value="Cash" <?php echo (isset($_POST["filter_payment_mod"]) && $_POST["filter_payment_mod"]=="Cash") ? "selected":"";?>>Cash</Option>
								</select>
							</div>
						</div>
						<div class="form-row">
							<div class="col-md-2 text-right">GRN No</div>
							<div class="col-md-4"><input name="filter_grn_no" type="text" class="form-control" value="<?php echo isset($_POST["filter_grn_no"]) ? $_POST["filter_grn_no"] : "";?>" /></div>
							<div class="col-md-2 text-right">Mode of Purchase</div>
							<div class="col-md-4">
							<select class="select2"   style="width: 100%;" name="filter_purchase_mod" id="purchase_mod" >
								<option value="">-- Select Purchase Mod --</Option>
								<option value="central" <?php echo (isset($_POST["filter_purchase_mod"]) && $_POST["filter_purchase_mod"]=="central") ? "selected":"";?>>Central Purchase</Option>
								<option value="local" <?php echo (isset($_POST["filter_purchase_mod"]) && $_POST["filter_purchase_mod"]=="local") ? "selected":"";?>>Local Purchase</Option>
								<option value="withoutpo" <?php echo (isset($_POST["filter_purchase_mod"]) && $_POST["filter_purchase_mod"]=="withoutpo") ? "selected":"";?>>Without PO</Option>
								</select>
							</div>
						</div>
						<div class="form-row">
							<div class="col-md-2 text-right">Challan No</div>
							<div class="col-md-4"><input name="filter_challan_no" value="<?php echo isset($_POST["filter_challan_no"]) ? $_POST["filter_challan_no"] : "";?>" class="form-control"></div>
						</div>
							
					<div class="form-row">
						<div class="col-md-2"> <div class="col-md-12"><input type="submit" name="go" id="go" class="btn btn-primary" value="Search"/></div></div>
					</div>
		
		<?php $this->Form->end(); ?>
		
<input type="hidden" id="f_date_from" value="<?php echo isset($_POST["from_date"]) ? $_POST["from_date"] : "";?>">
<input type="hidden" id="f_date_to" value="<?php echo isset($_POST["to_date"]) ? $_POST["to_date"] : "";?>">
<input type="hidden" id="f_pro_id" value="<?php echo isset($_POST["project_id"]) ? implode(",",$_POST["project_id"]) : "";?>">
<input type="hidden" id="f_material_id" value="<?php echo isset($_POST["material_id"]) ? implode(",",$_POST["material_id"]) : "";?>">
<input type="hidden" id="f_vendor_userid" value="<?php echo isset($_POST["vendor_userid"]) ? implode(",",$_POST["vendor_userid"]) : "";?>">
<input type="hidden" id="f_payment_mod" value="<?php echo isset($_POST["filter_payment_mod"]) ? $_POST["filter_payment_mod"] : "";?>">
<input type="hidden" id="f_grn_no" value="<?php echo isset($_POST["filter_grn_no"]) ? $_POST["filter_grn_no"] : "";?>">
<input type="hidden" id="f_purchase_mod" value="<?php echo isset($_POST["filter_purchase_mod"]) ? $_POST["filter_purchase_mod"] : "";?>">
<input type="hidden" id="f_challan_no" value="<?php echo isset($_POST["filter_challan_no"]) ? $_POST["filter_challan_no"] : "";?>">

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
		var f_vendor_userid  = jQuery("#f_vendor_userid").val();
		var f_payment_mod  = jQuery("#f_payment_mod").val();
		var f_grn_no  = jQuery("#f_grn_no").val();
		var f_purchase_mod  = jQuery("#f_purchase_mod").val();
		var f_challan_no  = jQuery("#f_challan_no").val();

	var selected = [];
	var table = jQuery('#grn_list').DataTable({
		"pageLength": 10,
		"order": [[ 2, "desc" ]],
		columnDefs: [ 
					{
						searchable: false,
						targets:   0,
					},
					{
						searchable: false,
						targets:   11,
					},
					{
						searchable: false,
						targets:   12,
					},
					{
						searchable: false,
						targets:   16,
					},
					{
						searchable: false,
						targets:   17,
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
			"url": "../Ajaxfunction/viewgrndata",
			"data": function ( d ) {
				d.myKey = "myValue";
				d.date_from = f_date_from;
				d.date_to = f_date_to;
				d.pro_id = f_pro_id;
				d.material_id = f_material_id;
				d.vendor_userid = f_vendor_userid;
				d.payment_mod = f_payment_mod;
				d.grn_no = f_grn_no;
				d.purchase_mod = f_purchase_mod;
				d.challan_no = f_challan_no;
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
			<table id="grn_list"  class="dataTables_wrapper table table-striped table-hover" style="width:100%">
				<thead>
					<tr>
						<th>Project Name</th>
						<th>G.R.N No</th>
						<th>Date</th>							
						<th>Vendor<br>Name</th>
						<th>Challan<br> No</th>
						<th>Material<br> Name</th>
						<th>Make<br>/ Source</th>
						<th>Material Group</th>
						<th>Vendor<br>/Royalty's Qty.<br>/ Weight</th>
						<th>Actual Qty.<br>/ Weight</th>
						<th>Diff.<br>(+/-)</th>
						<th class="never">Attachment</th>
						<th class="none">Action</th>
						<th class="never">Material Name</th>
						<th class="never">Brand Name</th>
						<th class="never">Material Title</th>
						<th class="never">Attach label</th>
						<th class="never">Brand id</th>
						<th class="never">Brand id</th>
						<th class="never">Brand id</th>
					</tr>
				</thead>
				
			</table>
			
			<div class="content">
				<div class="col-md-2">
				<?php 
					echo $this->Form->create("",["type"=>"file","class"=>"validateForm form-horizontal","role"=>"form","id"=>"export_csv"]);
				?>
				<!-- <form method="post"> -->
					<input type="hidden" name="rows" value='<?php //echo serialize($rows);?>'>
					<input type="hidden" name="date_from" id="" value="<?php echo isset($_POST["from_date"]) ? $_POST["from_date"] : "";?>">
					<input type="hidden" name="date_to" id="" value="<?php echo isset($_POST["to_date"]) ? $_POST["to_date"] : "";?>">
					<input type="hidden" name="pro_id" id="" value="<?php echo isset($_POST["project_id"]) ? implode(",",$_POST["project_id"]) : "";?>">
					<input type="hidden" name="materials" id="" value="<?php echo isset($_POST["material_id"]) ? implode(",",$_POST["material_id"]) : "";?>">
					<input type="hidden" name="vendors" id="" value="<?php echo isset($_POST["vendor_userid"]) ? implode(",",$_POST["vendor_userid"]) : "";?>">
					<input type="hidden" name="payment_mod" id="" value="<?php echo isset($_POST["filter_payment_mod"]) ? $_POST["filter_payment_mod"] : "";?>">

					<input type="hidden" name="grn_no" id="" value="<?php echo isset($_POST["filter_grn_no"]) ? $_POST["filter_grn_no"] : "";?>">

					<input type="hidden" name="purchase_mod" id="" value="<?php echo isset($_POST["filter_purchase_mod"]) ? $_POST["filter_purchase_mod"] : "";?>">
					<input type="hidden" name="challan_no" id="" value="<?php echo isset($_POST["filter_challan_no"]) ? $_POST["filter_challan_no"] : "";?>">
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
					<input type="hidden" name="rows" value='<?php //echo serialize($rows);?>'>
					<input type="hidden" name="date_from" id="" value="<?php echo isset($_POST["from_date"]) ? $_POST["from_date"] : "";?>">
					<input type="hidden" name="date_to" id="" value="<?php echo isset($_POST["to_date"]) ? $_POST["to_date"] : "";?>">
					<input type="hidden" name="pro_id" id="" value="<?php echo isset($_POST["project_id"]) ? implode(",",$_POST["project_id"]) : "";?>">
					<input type="hidden" name="materials" id="" value="<?php echo isset($_POST["material_id"]) ? implode(",",$_POST["material_id"]) : "";?>">
					<input type="hidden" name="vendors" id="" value="<?php echo isset($_POST["vendor_userid"]) ? implode(",",$_POST["vendor_userid"]) : "";?>">
					<input type="hidden" name="payment_mod" id="" value="<?php echo isset($_POST["filter_payment_mod"]) ? $_POST["filter_payment_mod"] : "";?>">

					<input type="hidden" name="grn_no" id="" value="<?php echo isset($_POST["filter_grn_no"]) ? $_POST["filter_grn_no"] : "";?>">

					<input type="hidden" name="purchase_mod" id="" value="<?php echo isset($_POST["filter_purchase_mod"]) ? $_POST["filter_purchase_mod"] : "";?>">
					<input type="hidden" name="challan_no" id="" value="<?php echo isset($_POST["filter_challan_no"]) ? $_POST["filter_challan_no"] : "";?>">
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
</div>
<?php } ?>
</div>