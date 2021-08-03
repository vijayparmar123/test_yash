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
			url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'loadvendor'));?>",
			async:false,
			success: function(response){
				// jQuery('select#vendor_userid').empty();
				jQuery('select#agency_id').append(response);
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
{
?>	
<div class="row">
	<div class="col-md-12">
		<div class="block">
			<div class="head bg-default bg-light-rtl">
				<h2>View ISSUE SLIP</h2>
				<div class="pull-right">
					<a href="<?php echo $this->ERPfunction->action_link('Inventory','index');?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
				</div>
			</div>
			
				<div class="content">
		<div class="col-md-12 filter-form">
			<?php 
				
				 
			?>
		<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
					<div class="form-row">
						<div class="col-md-2">Date From -</div>
                        <div class="col-md-4"><input type="text" value="<?php echo isset($_POST['from_date'])?$_POST['from_date']:''; ?>" name="from_date" id="from_date"  class="datep form-control"/></div>
						<div class="col-md-2">Date To -</div>
                        <div class="col-md-4"><input type="text" value="<?php echo isset($_POST['from_date'])?$_POST['to_date']:''; ?>" name="to_date" id="to_date"  class="datep form-control"/></div>
					</div>
					<div class="form-row">	
						
						<div class="col-md-2">Project Name:</div>
                        <div class="col-md-4">
							<select class="select2" style="width: 100%;" name="project_id[]" id="project_id" multiple="multiple">
								<option value="All">All</Option>
								<?php 
									foreach($projects as $retrive_data)
									{
									?>	
										echo '<option value="<?php echo $retrive_data['project_id']; ?>" <?php if($retrive_data['project_id'] == 2) //echo "selected"; ?>><?php echo $retrive_data['project_name']; ?></option>';
									<?php
									}
								?>
							</select>
						</div>
						<div class="col-md-2">Material Name:</div>
                        <div class="col-md-4">
							<select class="select2 material_id" style="width: 100%;" name="material_id[]" id="material_id_0" multiple="multiple">
								<option value="All">All</Option>
								<?php 
									foreach($material_list as $retrive_data)
									{
										echo '<option value="'.$retrive_data['material_id'].'" >'.
										$retrive_data['material_title'].'</option>';
									}
								?>
							</select>
						</div>
                    </div>
					
					
					<div class="form-row">
							<div class="col-md-2 text-right">Vendor / Asset's Name</div>
							<div class="col-md-4">
							<select class="select2 agency_id" style="width: 100%;" name="agency_id[]" id="agency_id" multiple="multiple">
								<option value="All">All</Option>
								<?php 
									foreach($vendor_list as $retrive_data)
									{
										echo '<option value="'.$retrive_data['user_id'].'">'.
										$retrive_data['vendor_name'].'</option>';
									}
									foreach($assets as $asset)
									{
										echo "<option value='asst_{$asset['asset_id']}' class='added_asset'>{$asset['asset_name']}</option>";
									}
								?>
							</select>
							</div>
							<div class="col-md-2 text-right">IS No</div>
							<div class="col-md-4"><input name="is_no" class="" /></div>
							
						</div>
							
					<div class="form-row">
						<div class="col-md-2"> <div class="col-md-12"><input type="submit" name="go" id="go" class="btn btn-primary" value="Search"/></div></div>
					</div>
		
		<?php echo $this->Form->end(); ?>
			</div>
			</div>
<input type="hidden" id="f_date_from" value="<?php echo isset($_POST["from_date"]) ? $_POST["from_date"] : "";?>">
<input type="hidden" id="f_date_to" value="<?php echo isset($_POST["to_date"]) ? $_POST["to_date"] : "";?>">

<input type="hidden" id="f_pro_id" value="<?php echo isset($_POST["project_id"]) ? implode(",",$_POST["project_id"]) : "";?>">
<input type="hidden" id="f_material_id" value="<?php echo isset($_POST["material_id"]) ? implode(",",$_POST["material_id"]) : "";?>">
<input type="hidden" id="f_agency_id" value="<?php echo isset($_POST["agency_id"]) ? implode(",",$_POST["agency_id"]) : "";?>">
<input type="hidden" id="f_is_no" value="<?php echo isset($_POST["is_no"]) ? $_POST["is_no"] : "";?>">
		<div class="content list custom-btn-clean">
		<script>
		jQuery(document).ready(function() {

			var f_date_from  = jQuery("#f_date_from").val();
			var f_date_to  = jQuery("#f_date_to").val();
			var f_pro_id  = jQuery("#f_pro_id").val();
			var f_material_id  = jQuery("#f_material_id").val();
			var f_agency_id  = jQuery("#f_agency_id").val();
			var f_is_no  = jQuery("#f_is_no").val();
			
			var selected = [];
			jQuery('#is_list').DataTable({
				"order": [[ 3, "desc" ]],
				responsive: true,
				columnDefs: [ {
						orderable: false,
						searchable: false,
						targets:   -1,
						},
						{
						orderable: false,
						searchable: false,
						targets:   0,
						},
						{
						searchable: false,
						targets:   2,
						},
						{
						orderable: false,
						searchable: false,
						targets:   6,
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
					{ "visible": true },
					],
			
			"processing": true,
			"serverSide": true,
			"ajax": {
					"url": "../Ajaxfunction/viewisdata",
					"data": function ( d ) {
												d.myKey = "myValue";
												d.date_from = f_date_from;
												d.date_to = f_date_to;
								 				d.pro_id = f_pro_id;
								 				d.material_id = f_material_id;
												d.agency_id = f_agency_id;
								 				d.is_no = f_is_no;
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
			<table id="is_list"  class="dataTables_wrapper table table-striped table-hover" style="width:100%">
				<thead>
					<tr>
						<!--<th>Project Code</th> -->
						<th>Project Name</th>
						<th>I.S. No</th>						
						<!--<th style="display:none">ID</th>-->						
						<th>Vendor/<br>Asset Name</th>						
						<th style="width:10%">Date</th>					
						<!-- <th>Time</th>	-->				
											
						<th>Material<br>Name</th>					
						<!-- <th>Make/<br>Source</th>-->					
						<th>Quantity</th>					
						<th>Unit</th>					
						<th>Name of Foreman</th>					
						<th >Edit/View</th>
					</tr>
				</thead>
				<!--<tbody>
					<?php
					
						
						$i = 1;
						$rows = array();
						$rows[] = array("Project Name","I.S. No","Agency or Asset Name","Date","Material Name","Make Source","Quantity","Unit","Name of Foreman");
						
						foreach($is_list as $retrive_data)
						{
							$export = array(); 
							$retrive_data = array_merge($retrive_data,$retrive_data["erp_inventory_is_detail"]);
					?>
							<tr>
								<td><?php echo ($export[] =$this->ERPfunction->get_projectname($retrive_data['project_id']));?></td>
								<td><?php echo ($export[] =$retrive_data['is_no']);?></td>								
								<td style="display:none"><?php echo $retrive_data['is_id'];?></td>								
								<td><?php 									
									$is_asset = explode("_",$retrive_data['agency_name']);
									if(isset($is_asset[1]))
									{
										echo ($export[] =$this->ERPfunction->get_asset_name($is_asset[1]));
									}else{
										echo ($export[] =$this->ERPfunction->get_agency_name($retrive_data['agency_name']));
									} ?>
								</td>								
								<td><?php echo ($export[] =$this->ERPfunction->get_date($retrive_data['is_date']));?></td>														
								<!-- <td><?php/*echo $retrive_data['is_time'];*/?></td>	 
								<?php $details = $this->ERPfunction->get_approveis_details($retrive_data['is_id']);?>
								
								<td><?php echo ($export[] =$this->ERPfunction->get_material_title($retrive_data["material_id"]));?></td>																				
								<td>None <?php $export[] = "none"; ?></td>														
								<td><?php echo ($export[] =$retrive_data["quantity"]); ?></td>														
								<td><?php echo ($export[] =$this->ERPfunction->get_items_units($details["material_id"]));?></td>														
								<td><?php echo ($export[] =$retrive_data["name_of_foreman"]); ?></td>														
								<td>
								<?php
								if($role == "erphead" || $role == "erpmanager" || $role == "ceo" || $role == "md" || $role == "projectdirector" ||  $role == "contructionmanager" || $role == "materialmanager" || $role == "billingengineer" || $role == "asset-inventoryhead" || $role == "erpoperator")
								{
									echo $this->Html->link("<i class='icon-eye-open'></i> View",array('action' => 'previewapprovedis', $retrive_data['is_id']),
									array('class'=>'btn btn-primary btn-clean','target'=>'_blank','escape'=>false));
								}
								
								if($role == "erphead" || $role == "erpmanager" || $role == "asset-inventoryhead")
								{
									echo $this->Html->link("<i class='icon-edit'></i> Edit",array('action' => "updateis", $retrive_data['is_id']),
									array('class'=>'btn btn-primary btn-clean','target'=>'_blank','escape'=>false));
								}
								
								if($role == "erphead" || $role == "erpmanager" || $role == "asset-inventoryhead")
								{
									echo $this->Html->link("<i class='icon-trash'></i> Delete",array('action' => 'unapproveis', $retrive_data['is_detail_id']),
									array('class'=>'btn btn-danger btn-clean','target'=>'_blank','escape'=>false));
								}
								?>
								</td>		
							</tr>
						<?php
						$i++;
						$rows[] = $export;
						
						}
						
					?>
				</tbody> -->
			</table>
			
			<div class="content">
				<div class="col-md-2">
				<?php 
					echo $this->Form->create("",["type"=>"file","class"=>"validateForm form-horizontal","role"=>"form","id"=>"export_csv"]);
				?>
					<input type="hidden" name="rows" value='<?php //echo base64_encode(serialize($rows));?>'>
					<input type="hidden" name="f_date_from" id="f_date_from" value="<?php echo isset($_POST["from_date"]) ? $_POST["from_date"] : "";?>">
					<input type="hidden" name="f_date_to" id="f_date_to" value="<?php echo isset($_POST["to_date"]) ? $_POST["to_date"] : "";?>">

					<input type="hidden" name="f_pro_id" id="f_pro_id" value="<?php echo isset($_POST["project_id"]) ? implode(",",$_POST["project_id"]) : "";?>">
					<input type="hidden" name="f_material_id" id="f_material_id" value="<?php echo isset($_POST["material_id"]) ? implode(",",$_POST["material_id"]) : "";?>">
					<input type="hidden" name="f_agency_id" id="f_agency_id" value="<?php echo isset($_POST["agency_id"]) ? implode(",",$_POST["agency_id"]) : "";?>">
					<input type="hidden" name="f_is_no" id="f_is_no" value="<?php echo isset($_POST["is_no"]) ? $_POST["is_no"] : "";?>">
					<input type="submit" class="btn btn-success" value="Export To Excel" name="export_csv">
				<?php 
					echo $this->Form->end();
				?>
				</div>
				<div class="col-md-2">
				<?php 
					echo $this->Form->create("",["type"=>"file","class"=>"validateForm form-horizontal","role"=>"form","id"=>"export_pdf"]);
				?>
					<input type="hidden" name="rows" value='<?php //echo base64_encode(serialize($rows));?>'>
					<input type="hidden" name="rows" value='<?php //echo base64_encode(serialize($rows));?>'>
					<input type="hidden" name="f_date_from" id="f_date_from" value="<?php echo isset($_POST["from_date"]) ? $_POST["from_date"] : "";?>">
					<input type="hidden" name="f_date_to" id="f_date_to" value="<?php echo isset($_POST["to_date"]) ? $_POST["to_date"] : "";?>">

					<input type="hidden" name="f_pro_id" id="f_pro_id" value="<?php echo isset($_POST["project_id"]) ? implode(",",$_POST["project_id"]) : "";?>">
					<input type="hidden" name="f_material_id" id="f_material_id" value="<?php echo isset($_POST["material_id"]) ? implode(",",$_POST["material_id"]) : "";?>">
					<input type="hidden" name="f_agency_id" id="f_agency_id" value="<?php echo isset($_POST["agency_id"]) ? implode(",",$_POST["agency_id"]) : "";?>">
					<input type="hidden" name="f_is_no" id="f_is_no" value="<?php echo isset($_POST["is_no"]) ? $_POST["is_no"] : "";?>">
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
<?php
}
?>
</div>