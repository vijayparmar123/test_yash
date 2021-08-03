<?php
//$this->extend('/Common/menu')
use Cake\Routing\Router;
?>
<div class="col-md-10" >
 <?php 
if(!$is_capable)
	{
		$this->ERPfunction->access_deniedmsg();
	}
else
{
 ?>
 
<script type="text/javascript">
	var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;

	jQuery(document).ready(function() {
		
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
				jQuery('select#party_id').empty();
				jQuery('select#party_id').append(response);
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
<div class="row">
	<div class="col-md-12">
		<div class="block">
		<div class="head bg-default bg-light-rtl">
			<h2>DEBIT NOTE RECORDS</h2>
			<div class="pull-right">
			<a href="<?php echo $this->ERPfunction->action_link('inventory','index');?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
			</div>
		</div>
			<?php 
				 $from_date = isset($_POST['from_date'])?$_POST['from_date']:'';
				 $to_date = isset($_POST['to_date'])?$_POST['to_date']:'';
				 $debit_no = isset($_POST['filter_debit_no'])?$_POST['filter_debit_no']:'';
			?>
			<div class="content">
		<div class="col-md-12 filter-form">
			
		<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
					<div class="form-row">
						<div class="col-md-2">Date From -</div>
                        <div class="col-md-4"><input type="text" name="from_date" id="from_date" value="<?php echo $from_date;?>" class="datep form-control"/></div>
						<div class="col-md-2">Date To -</div>
                        <div class="col-md-4"><input type="text" name="to_date" id="to_date" value="<?php echo $to_date;?>" class="datep form-control"/></div>
					</div>
						
					<div class="form-row">
							<div class="col-md-2">Project Name</div>
                            <div class="col-md-4">
								<select class="select2" style="width: 100%;" multiple="multiple" name="project_id[]" id="project_id">
								<option value="All">All</Option>
								<?php 
									foreach($projects as $retrive_data)
									{
										echo '<option value="'.$retrive_data['project_id'].'">'.
										$retrive_data['project_name'].'</option>';
									}
								?>
								</select>
							</div>
							
							<div class="col-md-2">Party Name</div>
                            <div class="col-md-4">
								<select class="select2 vendor_id" style="width: 100%;" multiple="multiple" name="party_id[]" id="party_id">
								<option value="All">All</Option>
								</select>
							</div>
                    </div>
                    
					<div class="form-row">	
						<div class="col-md-2">Material Name:</div>
                        <div class="col-md-4">
							<select class="select2 material_id" style="width: 100%;" name="material_id[]" id="material_id_0" multiple="multiple">
								<option value="All" selected>All</Option>
								
							</select>
						</div>
						
						<div class="col-md-2 text-right">Debit No</div>
							<div class="col-md-4"><input name="debit_no" value="<?php echo $debit_no;?>" type="text" class="form-control" value="" /></div>
                    </div>
					
					<div class="form-row">
						<div class="col-md-2"> <div class="col-md-12"><input type="submit" name="go" id="go" class="btn btn-primary" value="Search"/></div></div>
					</div>
		
		<?php echo $this->Form->end(); ?>
		
<input type="hidden" id="f_date_from" value="<?php echo isset($_POST["from_date"]) ? $_POST["from_date"] : "";?>">
<input type="hidden" id="f_date_to" value="<?php echo isset($_POST["to_date"]) ? $_POST["to_date"] : "";?>">
<input type="hidden" id="f_pro_id" value="<?php echo isset($_POST["project_id"]) ? implode(",",$_POST["project_id"]) : "";?>">
<input type="hidden" id="f_material_id" value="<?php echo isset($_POST["material_id"]) ? implode(",",$_POST["material_id"]) : "";?>">
<input type="hidden" id="f_party_id" value="<?php echo isset($_POST["party_id"]) ? implode(",",$_POST["party_id"]) : "";?>">
<input type="hidden" id="f_debit_no" value="<?php echo isset($_POST["debit_no"]) ? $_POST["debit_no"] : "";?>">
			</div>
			</div>
					
		<div class="content list custom-btn-clean">
		<script>
	jQuery(document).ready(function() {
		var f_date_from  = jQuery("#f_date_from").val();
		var f_date_to  = jQuery("#f_date_to").val();
		var f_pro_id  = jQuery("#f_pro_id").val();
		var f_material_id  = jQuery("#f_material_id").val();
		var f_party_id  = jQuery("#f_party_id").val();
		var f_debit_no  = jQuery("#f_debit_no").val();

	var selected = [];
	var table = jQuery('#debit_list').DataTable({
		"pageLength": 10,
		"order": [[ 2, "desc" ]],
		columnDefs: [ 
					{
						searchable: false,
						targets:   6,
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
						  ],
		"responsive" : true,
		"processing": true,
		"serverSide": true,
		//"ajax": "../Ajaxfunction/billrecordsdata",
		"ajax": {
			headers: {
				'X-CSRF-Token': csrfToken
			},
				"url": "../Ajaxfunction/inventorydebitrecords",
				"data": function ( d ) {
											d.myKey = "myValue";
											d.date_from = f_date_from;
											d.date_to = f_date_to;
											d.pro_id = f_pro_id;
											d.material_id = f_material_id;
											d.party_id = f_party_id;
											d.debit_no = f_debit_no;
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

			<table id="debit_list"  class="dataTables_wrapper table table-striped table-hover">
				<thead>
					<tr>
						<th>Project</th>
						<th>Debit Note No</th>
						<th>Date</th>
						<th>Receiver Party Name</th>
						<th>Given To</th>
						<th>Total Amount of Debit(Rs)</th>
						<th>View / Delete</th>
					</tr>
				</thead>
			</table>
		</div>
		
		</div>
	</div>
</div>
<div class="modal fade " id="load_modal" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content"></div>
    </div>
</div>
<?php
  } 
 ?>
</div>
