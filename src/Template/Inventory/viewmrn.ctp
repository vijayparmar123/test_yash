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
		// Vendor data set after the page load
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
				<h2>View M.R.N.</h2>
				<div class="pull-right">
				<a href="<?php echo $this->ERPfunction->action_link('Inventory','index');?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
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
			?>
		<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
					<div class="form-row">
						<div class="col-md-2">Date From -</div>
                        <div class="col-md-4"><input type="text" name="from_date" id="from_date" value="<?php echo $from_date;?>" class="datep form-control"/></div>
						<div class="col-md-2">Date To -</div>
                        <div class="col-md-4"><input type="text" name="to_date" id="to_date" value="<?php echo $to_date;?>" class="datep form-control"/></div>
					</div>
					<div class="form-row">	
						<!-- <div class="col-md-2">GRN.No:</div>
                        <div class="col-md-4">
							<input type="text" name="po_no" id="po_no" value="" class="form-control"/>
						</div>					
						-->
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
						<div class="col-md-2">Material Name:</div>
                        <div class="col-md-4">
							<select class="select2 material_id" style="width: 100%;" name="material_id[]" id="material_id_0" multiple="multiple">
								<option value="All" selected>All</Option>
								<?php 
									foreach($material_list as $retrive_data)
									{
										$selected = (in_array($retrive_data['material_id'],$material_id_a)) ? "selected" : "";
										echo '<option value="'.$retrive_data['material_id'].'" '.$selected.'>'.
										$retrive_data['material_title'].'</option>';
									}
								?>
							</select>
						</div>
                    </div>
					<!--<div class="form-row">	
						 <div class="col-md-2">Material ID:</div>
                        <div class="col-md-4">
							<input type="text" name="po_no" id="po_no" value="" class="form-control"/>
						</div>
						<div class="col-md-2">Material Name:</div>
                        <div class="col-md-4">
							<select class="select2 material_id" style="width: 100%;" name="material_id[]" id="material_id_0" multiple="multiple">
								<option value="All">All</Option>
								<?php 
									foreach($material_list as $retrive_data)
									{
										$selected = (in_array($retrive_data['material_id'],$material_id_a)) ? "selected" : "";
										echo '<option value="'.$retrive_data['material_id'].'" '.$selected.'>'.
										$retrive_data['material_title'].'</option>';
									}
								?>
							</select>
						</div>
					</div> -->
					<div class="form-row">	
						<!-- <div class="col-md-2">Vendor ID:</div>
                    	<div class="col-md-4">
							<input type="text" name="vendor_id" id="vendor_id" value="" class="form-control" value=""/>
						</div> -->
						<div class="col-md-2">Vendor Name:</div>
                        <div class="col-md-4">
							<select class="select2"  style="width: 100%;" name="vendor_userid[]" id="vendor_userid" multiple="multiple">
								<option value="All" selected>All</Option>
								<?php 
									foreach($vendor_department as $retrive_data)
									{
										$selected = (in_array($retrive_data['user_id'],$vendor_userid_a)) ? "selected" : "";
										echo '<option value="'.$retrive_data['user_id'].'" '.$selected.'>'.
										$this->ERPfunction->get_vendor_name($retrive_data['user_id']).'</option>';									
									
									}
								?>
							</select>
						</div>
						<div class="col-md-2 text-right">MRN No</div>
						<div class="col-md-4"><input name="filter_mrn_no" class="" /></div>
						
					</div>
					<!--<div class="form-row">	
						<div class="col-md-2">Mode of Purchase:</div>
                    	<div class="col-md-4">
							<input type="text" name="po_mode" id="po_mode" value="" class="form-control" value=""/>
						</div>
						<div class="col-md-2">Payment Method:</div>
                        <div class="col-md-4">
							<select class="select2" style="width: 100%;" name="payment_method[]" id="payment_method" multiple="multiple">
								<option value="All">All</Option>
								<option value="cash">Cash</Option>
								<option value="cheque">Cheque</Option>									</select>
						</div> 			
					</div>	 -->		
					<div class="form-row">
						<div class="col-md-2"> <div class="col-md-12"><input type="submit" name="go" id="go" class="btn btn-primary" value="Search"/></div></div>
					</div>
		
		<?php $this->Form->end(); ?>
		
		<input type="hidden" id="f_date_from" value="<?php echo isset($_POST["from_date"]) ? $_POST["from_date"] : "";?>">
		<input type="hidden" id="f_date_to" value="<?php echo isset($_POST["to_date"]) ? $_POST["to_date"] : "";?>">
		<input type="hidden" id="f_pro_id" value="<?php echo isset($_POST["project_id"]) ? implode(",",$_POST["project_id"]) : "";?>">
		<input type="hidden" id="f_material_id" value="<?php echo isset($_POST["material_id"]) ? implode(",",$_POST["material_id"]) : "";?>">
		<input type="hidden" id="f_vendor_userid" value="<?php echo isset($_POST["vendor_userid"]) ? implode(",",$_POST["vendor_userid"]) : "";?>">
		<input type="hidden" id="f_mrn_no" value="<?php echo isset($_POST["filter_mrn_no"]) ? $_POST["filter_mrn_no"] : "";?>">
			</div>
			</div>
			
		<div class="content list custom-btn-clean" style="overflow-x:scroll;">
		<script>
		jQuery(document).ready(function() {
			var f_date_from  = jQuery("#f_date_from").val();
			var f_date_to  = jQuery("#f_date_to").val();
			var f_pro_id  = jQuery("#f_pro_id").val();
			var f_material_id  = jQuery("#f_material_id").val();
			var f_vendor_userid  = jQuery("#f_vendor_userid").val();
			var f_mrn_no  = jQuery("#f_mrn_no").val();

		var selected = [];
		var table = jQuery('#mrn_list').DataTable({
			"pageLength": 10,
			"order": [[ 2, "desc" ]],
			columnDefs: [ 
						{
							searchable: false,
							targets:   0,
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
							  ],
			"responsive" : true,
			"processing": true,
			"serverSide": true,
			//"ajax": "../Ajaxfunction/billrecordsdata",
			"ajax": {
				headers: {
					'X-CSRF-Token': csrfToken
				},
					"url": "../Ajaxfunction/viewmrndata",
					"data": function ( d ) {
												d.myKey = "myValue";
												d.date_from = f_date_from;
												d.date_to = f_date_to;
												d.pro_id = f_pro_id;
												d.material_id = f_material_id;
												d.vendor_userid = f_vendor_userid;
												d.mrn_no = f_mrn_no;
											}
					},
			"rowCallback": function( row, data ) {
									
									if ( jQuery.inArray(data.DT_RowId, selected) !== -1 ) {
										jQuery(row).addClass('selected');
									}
							},
			});
			jQuery("body").on("change", ".approve", function(event){
				var mrn_id = jQuery(this).val();
				var data_role = jQuery(this).attr('data-role');
				
				if(confirm('Are you Sure approve this M.R.N.?'))
				{
				var curr_data = {	 						 					
	 					mrn_id : mrn_id,
						data_role :data_role,
	 					};	 				
	 	 jQuery.ajax({
			headers: {
					'X-CSRF-Token': csrfToken
				},
                type:"POST",
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'approvemrn'));?>",
                data:curr_data,
                async:false,
                success: function(response){
					
					 location.reload();
					return false;
                },
                error: function (e) {
                     alert('Error');
                }
		});}
			else
			{
				jQuery(this).prop('checked', true);
			}
			});	
		} );
</script>
			<table id="mrn_list"  class="dataTables_wrapper table table-striped table-hover">
				<thead>
					<tr>
						<th>Project</th>
						<th>M.R.N. No</th>						
						<th>Date</th>					
						<th>Time</th>
						<th>Vendor Name</th>
						<th>Material Name</th>
						<th>Make/Source</th>
						<th>Returned Quantity</th>
						<th>Unit</th>
						<th>Action</th>					
					</tr>
				</thead>
				<!--<tbody>
					<?php
						if(isset($mrn_list))
						{
						$i = 1;
						$rows = array();
						$rows[] = array("Project Name","M.R.N No","Date","Time","Vendor Name","Material Name","Make/Source","Returned Quantity","Unit");
						foreach($mrn_list as $retrive_data)
						{
							$retrive_data = array_merge($retrive_data,$retrive_data["erp_inventory_mrn_detail"]);
						?>
							<tr>								
								<td><?php echo ($export[] = $this->ERPfunction->get_projectname($retrive_data['project_id']));?></td>
								<td><?php echo ($export[] = $retrive_data['mrn_no']);?></td>								
								<td><?php echo ($export[] = $this->ERPfunction->get_date($retrive_data['mrn_date']));?></td>														
								<td><?php echo ($export[] = $retrive_data['mrn_time']);?></td>														
																					
								<td><?php echo ($export[] = $this->ERPfunction->get_vendor_name($retrive_data['vendor_user']));?></td>
																							
								<td><?php echo ($export[] = $this->ERPfunction->get_material_title($retrive_data['material_id']));?></td>													
								<td><?php echo ($export[] = $this->ERPfunction->get_brandname($retrive_data['brand_id']));?></td>													
								<td><?php echo ($export[] = $retrive_data['quantity']);?></td>													
								<td><?php echo ($export[] = $this->ERPfunction->get_items_units($retrive_data['material_id']));?></td>
								
								<?php 
								if($this->ERPfunction->retrive_accessrights($role,'unapprovemrn')==1 || $this->ERPfunction->retrive_accessrights($role,'previewapprovedmrn')==1)
								{	echo "<td>";
									if($this->ERPfunction->retrive_accessrights($role,'previewapprovedmrn')==1)
									{
										echo $this->Html->link("<i class='icon-eye-open'></i> View",array('action' => 'previewapprovedmrn', $retrive_data['mrn_id']),
										array('class'=>'btn btn-primary btn-clean','target'=>'_blank','escape'=>false));
									}
									if($this->ERPfunction->retrive_accessrights($role,'unapprovemrn')==1)
									{
										echo $this->Html->link('<i class="icon-trash"></i> Remove',array('action' => 'unapprovemrn', $retrive_data['mrn_detail_id']),
										array('class'=>'btn btn-danger btn-clean action-btn','style'=>'padding-right:35px','escape'=> false,
										'confirm' => 'Are you sure you wish to remove this Record?'));
									}
									echo "</td>";
								}
								?>
									
								</tr>
						<?php
						$i++;
						$rows[] = $export;
						}
						}
					?>
				</tbody>-->
			</table>
			<div class="content">
				<!-- <div class="col-md-2"><a href="javascript:void(0);" class="btn btn-success" id="fullscreen" url='<?php //echo $_SERVER['REQUEST_URI']; ?>' onClick="DoFullScreen()" >View Full Screen</a></div>
				-->
				<div class="col-md-2">
					<?php 
						echo $this->Form->create("",["type"=>"file","class"=>"validateForm form-horizontal","role"=>"form","id"=>"export_csv"]);
					?>
					<!--<input type="hidden" name="rows" value='<?php echo serialize($rows);?>'>-->
					<input type="hidden" name="e_date_from" value="<?php echo isset($_POST["from_date"]) ? $_POST["from_date"] : "";?>">
					<input type="hidden" name="e_date_to" value="<?php echo isset($_POST["to_date"]) ? $_POST["to_date"] : "";?>">
					<input type="hidden" name="e_pro_id" value="<?php echo isset($_POST["project_id"]) ? implode(",",$_POST["project_id"]) : "";?>">
					<input type="hidden" name="e_material_id" value="<?php echo isset($_POST["material_id"]) ? implode(",",$_POST["material_id"]) : "";?>">
					<input type="hidden" name="e_vendor_userid" value="<?php echo isset($_POST["vendor_userid"]) ? implode(",",$_POST["vendor_userid"]) : "";?>">
					<input type="hidden" name="e_mrn_no" value="<?php echo isset($_POST["filter_mrn_no"]) ? $_POST["filter_mrn_no"] : "";?>">
					<input type="submit" class="btn btn-success" value="Export To Excel" name="export_csv">
					<?php 
						echo $this->Form->end();
					?>
				</div>
				<div class="col-md-2">
					<?php 
						echo $this->Form->create("",["type"=>"file","class"=>"validateForm form-horizontal","role"=>"form","id"=>"export_pdf"]);
					?>
					<!--<input type="hidden" name="rows" value='<?php echo serialize($rows);?>'>-->
					<input type="hidden" name="e_date_from" value="<?php echo isset($_POST["from_date"]) ? $_POST["from_date"] : "";?>">
					<input type="hidden" name="e_date_to" value="<?php echo isset($_POST["to_date"]) ? $_POST["to_date"] : "";?>">
					<input type="hidden" name="e_pro_id" value="<?php echo isset($_POST["project_id"]) ? implode(",",$_POST["project_id"]) : "";?>">
					<input type="hidden" name="e_material_id" value="<?php echo isset($_POST["material_id"]) ? implode(",",$_POST["material_id"]) : "";?>">
					<input type="hidden" name="e_vendor_userid" value="<?php echo isset($_POST["vendor_userid"]) ? implode(",",$_POST["vendor_userid"]) : "";?>">
					<input type="hidden" name="e_mrn_no" value="<?php echo isset($_POST["filter_mrn_no"]) ? $_POST["filter_mrn_no"] : "";?>">
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
</div>
<?php }?>
</div>