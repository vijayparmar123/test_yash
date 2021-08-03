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

	// Load Project Data
	jQuery.ajax({
		headers: {
			'X-CSRF-Token': csrfToken
		},
		type:"POST",
		url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'loaduserprojects'));?>",
		async:false,
		success: function(response){
			jQuery('select.project_id').empty();
			jQuery('select.project_id').append(response);
			return false;
		},
		error: function (e) {
			alert('Error');
		}
	});
});
</script>
<div class="col-md-10" >
<div class="col-md-12" >
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
				<h2>S.S.T. List </h2>
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
						<div class="col-md-2">Project Name - 1</div>
                        <div class="col-md-4">
							<select class="select2 project_id" style="width: 100%;" name="from_project_id[]" id="fproject_id" multiple="multiple">
								<option value="All" selected>All</Option>
							</select>
						</div>
						<div class="col-md-2">Project Name - 2</div>
                        <div class="col-md-4">
							<select class="select2 project_id" style="width: 100%;" name="to_project_id[]" id="tproject_id" multiple="multiple">
								<option value="All" selected>All</Option>
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
						<div class="col-md-2 text-right">SST No</div>
						<div class="col-md-4"><input name="filter_sst_no" class="" /></div>
						
					</div>
						
					<div class="form-row">
						<div class="col-md-2"> <div class="col-md-12"><input type="submit" name="go" id="go" class="btn btn-primary" value="Search"/></div></div>
					</div>
		
		<?php $this->Form->end(); ?>
		<input type="hidden" id="f_date_from" value="<?php echo isset($_POST["from_date"]) ? $_POST["from_date"] : "";?>">
		<input type="hidden" id="f_date_to" value="<?php echo isset($_POST["to_date"]) ? $_POST["to_date"] : "";?>">
		<input type="hidden" id="f_from_project_id" value="<?php echo isset($_POST["from_project_id"]) ? implode(",",$_POST["from_project_id"]) : "";?>">
		<input type="hidden" id="f_to_project_id" value="<?php echo isset($_POST["to_project_id"]) ? implode(",",$_POST["to_project_id"]) : "";?>">
		<input type="hidden" id="f_material_id" value="<?php echo isset($_POST["material_id"]) ? implode(",",$_POST["material_id"]) : "";?>">
		<input type="hidden" id="f_sst_no" value="<?php echo isset($_POST["filter_sst_no"]) ? $_POST["filter_sst_no"] : "";?>">
			</div>
			</div>
			
		<div class="content list custom-btn-clean">
		<script>
		jQuery(document).ready(function() {
			var f_date_from  = jQuery("#f_date_from").val();
			var f_date_to  = jQuery("#f_date_to").val();
			var f_from_project_id  = jQuery("#f_from_project_id").val();
			var f_to_projet_id  = jQuery("#f_to_project_id").val();
			var f_material_id  = jQuery("#f_material_id").val();
			var f_sst_no  = jQuery("#f_sst_no").val();

		var selected = [];
	var table = jQuery('#sst_list').DataTable()
	var table = jQuery('#sst_list1').DataTable({
		"pageLength": 10,
		"order": [[ 2, "desc" ]],
		columnDefs: [ 
					{
						searchable: false,
						targets:   0,
					},
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
						  ],
		"responsive" : true,
		"processing": true,
		"serverSide": true,
		//"ajax": "../Ajaxfunction/billrecordsdata",
		"ajax": {
				"url": "../Ajaxfunction/viewsstdata",
				"data": function ( d ) {
											d.myKey = "myValue";
											d.date_from = f_date_from;
											d.date_to = f_date_to;
											d.from_project_id = f_from_project_id;
											d.to_project_id = f_to_project_id;
											d.material_id = f_material_id;
											d.sst_no = f_sst_no;
										}
				},
		"rowCallback": function( row, data ) {
								if ( jQuery.inArray(data.DT_RowId, selected) !== -1 ) {
									jQuery(row).addClass('selected');
								}
						},
		});
			
			jQuery("body").on("change", ".approve", function(event){
				var sst_id = jQuery(this).val();
				var data_site = jQuery(this).attr('data-site');
				
				if(confirm('Are you Sure approve this S.S.T.?'))
				{
				var curr_data = {	 						 					
	 					sst_id : sst_id,
						data_site :data_site,
	 					};	 				
	 	 jQuery.ajax({
                type:"POST",
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'approvesst'));?>",
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
			<table id="sst_list"  class="dataTables_wrapper table table-striped table-hover">
				<thead>
					<tr>
						<th>Project Name From</th>
						<th>Project Name To</th>
						<th>S.S.T. No</th>						
						<th>Date</th>					
						<th>Time</th>						
						<th>Material Name</th>
						<th>Make/<br>Source</th>
						<th>Quantity</th>
						<th>Unit</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
					if(isset($sst_list))
					{
						$i = 1;
						$rows = array();
						$rows[] = array("Project Name From","Project Name To","S.S.T. No","Date","Time","Material Name","Make/Source","Quantity","Unit");
						foreach($sst_list as $retrive_data)
						{
							$export = array();
						$retrive_data = array_merge($retrive_data,$retrive_data["erp_inventory_sst_detail"]);
						?>
							<tr>								
								<td><?php echo ($export[] = $this->ERPfunction->get_projectname($retrive_data['project_id']));?></td>
																
								<td><?php echo ($export[] = $this->ERPfunction->get_projectname($retrive_data['transfer_to']));?></td>
								<td><?php echo ($export[] = $retrive_data['sst_no']);?></td>								
								<td><?php echo ($export[] = $this->ERPfunction->get_date($retrive_data['sst_date']));?></td>														
								<td><?php echo ($export[] = $retrive_data['sst_time']);?></td>
																							
								<td><?php echo ($export[] = $this->ERPfunction->get_material_title($retrive_data['material_id']));?></td>													
								<td><?php echo ($export[] = $this->ERPfunction->get_brandname($retrive_data['brand_id']));?></td>													
								<td><?php echo ($export[] = $retrive_data['quantity']);?></td>													
								<td><?php echo ($export[] = $this->ERPfunction->get_items_units($retrive_data['material_id']));?></td>
								
								<?php 
								if($this->ERPfunction->retrive_accessrights($role,'unapprovesst')==1 || $this->ERPfunction->retrive_accessrights($role,'previewapprovedsst')==1)
								{
									echo "<td>";
									if($this->ERPfunction->retrive_accessrights($role,'previewapprovedsst')==1)
									{
									echo $this->Html->link("<i class='icon-eye-open'></i> View",array('action' => 'previewapprovedsst', $retrive_data['sst_id']),
									array('class'=>'btn btn-primary btn-clean','target'=>'_blank','escape'=>false));
									}
									if($this->ERPfunction->retrive_accessrights($role,'unapprovesst')==1)
									{
									echo $this->Html->link('<i class="icon-trash"></i> Remove',array('action' => 'unapprovesst', $retrive_data['sst_detail_id']),
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
				</tbody>
			</table>
			<?php
			if(isset($sst_list))
			{
			 if($sst_list != NULL){
			?>
			<div class="content">
				<!-- <div class="col-md-2"><a href="javascript:void(0);" class="btn btn-success" id="fullscreen" url='<?php //echo $_SERVER['REQUEST_URI']; ?>' onClick="DoFullScreen()" >View Full Screen</a></div>
				-->
				<div class="col-md-2">
					<?php 
						echo $this->Form->create("",["type"=>"file","class"=>"validateForm form-horizontal","role"=>"form","id"=>"export_csv"]);
					?>
					<input type="hidden" name="rows" value='<?php echo serialize($rows);?>'>
					<input type="submit" class="btn btn-success" value="Export To Excel" name="export_csv">
					<?php 
						echo $this->Form->end();
					?>
				</div>
				<div class="col-md-2">
					<?php 
						echo $this->Form->create("",["type"=>"file","class"=>"validateForm form-horizontal","role"=>"form","id"=>"export_pdf"]);
					?>
					<input type="hidden" name="rows" value='<?php echo serialize($rows);?>'>
					<input type="submit" class="btn btn-success" value="Export To PDF" name="export_pdf">
					<?php 
						echo $this->Form->end();
					?>
				</div>
			</div>
		</div>
		<?php }} ?>
		</div>
		</div>
	</div>
</div>
<?php }?>
</div>