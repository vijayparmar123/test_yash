<?php
//$this->extend('/Common/menu')
use Cake\Routing\Router;
?>
<div class="modal fade " id="load_modal" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content"></div>
    </div>
</div>
<div class="col-md-10" >
<?php
if(!$is_capable)
{
	$this->ERPfunction->access_deniedmsg();
}
else{
?>              
<div class="col-md-12">
<div class="row">
	
		<div class="block">
			<div class="head bg-default bg-light-rtl">
				<h2>Material List</h2>
				<div class="pull-right">
					<a href="<?php echo $this->request->base;?>/Purchase/addmaterial" class="btn btn-primary">Add Material </a>
					<a href="<?php echo $this->ERPfunction->action_link($back_url,$back_page);?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
				</div>
			</div>
			
		<?php echo $this->Form->Create('form1',['id'=>'eq_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
			 <div class="content controls">											
						<div class="form-row">
							<div class="col-md-2 text-right">Material Code</div>
							<div class="col-md-4">
								<input name="material_code" class="form-control">
							</div>
							<div class="col-md-2 text-right">Material Group</div>
							<div class="col-md-4">
								<?php

								/* $groups = $this->ERPfunction->asset_group();									 */
								echo "<select class='select2' name='material_group' style='width:100%' id='material_code'>";
								echo "<option value=''>-- Select Group --</option>";
								foreach($groups as $group)
								{
									echo "<option value='{$group['id']}'>{$group['title']}</option>";									
								}
								echo "</select>";
								?>
							</div>
						</div>
						<div class="form-row">
							<div class="col-md-2 text-right">Material Name</div>
							<div class="col-md-4">
								<input name="material_name" class="form-control">
							</div>
							<div class="col-md-2 text-right">Sub Category </div>
							<div class="col-md-4">
								<select class="select2" style="width: 100%;" id="material-sub-category" name="material_sub_category">
									<option value=""><?php echo __('--Sub Category--'); ?></option>
								</select>
							</div>
						</div>
						
						<div class="form-row">
							<div class="col-md-2 text-right">Consume Type </div>
							<div class="col-md-4">
								<select class="select2" style="width: 100%;" id="consume" name="consume">
									<option value="">-- Consume Type --</option>
									<option value="1">Consumable</option>
									<option value="0">Retunable / Non-consumable</option>
									<option value="3">Asset</option>
								</select>
							</div>
							<div class="col-md-2 text-right">Cost Group</div>
							<div class="col-md-4">
								<select class="select2" style="width: 100%;" id="cost_group" name="cost_group">
									<option value="">-- Cost Group --</option>
									<option value="a">A</option>
									<option value="b">B</option>
									<option value="c">C</option>
									<option value="d">D</option>
									<option value="e">E</option>
								</select>
							</div>
						</div>
						<div class="form-row">
							<div class="col-md-2 text-right">Project </div>
							<div class="col-md-4">
								<?php 
								echo "<select class='select2' multiple='multiple' name='project_id[]' style='width:100%' id='project'>";
								echo "<option value='All' selected>All</option>";
								foreach($projects as $project)
								{
									echo "<option value='{$project['project_id']}'>{$project['project_name']}</option>";									
								}
								echo "</select>";
								?>
							</div>
							
							<div class="col-md-2 text-right">
								<button type="submit" name="search" value="Search" class="btn btn-primary">Search</button>
							</div>
						</div>
		
		<?php echo $this->Form->end();?>	
		
		<input type="hidden" id="f_material_code" value="<?php echo isset($_POST["material_code"]) ? $_POST["material_code"] : "";?>">
		<input type="hidden" id="f_material_name" value="<?php echo isset($_POST["material_name"]) ? $_POST["material_name"] : "";?>">
		<input type="hidden" id="f_material_group" value="<?php echo isset($_POST["material_group"]) ? $_POST["material_group"] : "";?>">
		<input type="hidden" id="f_cost_group" value="<?php echo isset($_POST["cost_group"]) ? $_POST["cost_group"] : "";?>">
		<input type="hidden" id="f_consume" value="<?php echo isset($_POST["consume"]) ? $_POST["consume"] : "";?>">
		<input type="hidden" id="f_material_sub_category" value="<?php echo isset($_POST["material_sub_category"]) ? $_POST["material_sub_category"] : "";?>">
		<input type="hidden" id="f_project_id" value="<?php echo isset($_POST["project_id"]) ? implode(",",$_POST["project_id"]) : "";?>">

		<div class="content list custom-btn-clean">
		<script>
		var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
		jQuery(document).ready(function() {
		// jQuery('#material_list').DataTable({
			// responsive: true,
			// "pagingType": "input",
			// "stateSave": true
			// });
		
		jQuery("body").on("change", "#material_code", function(event){	
			var material_code  = jQuery(this).val();
			var curr_data = {material_code : material_code};	 				
			jQuery.ajax({
				headers: {
					'X-CSRF-Token': csrfToken
				},
				type:"POST",
				url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'getmaterialsubgroup'));?>",
				data:curr_data,
				async:false,
				success: function(response){                    
					jQuery('#material-sub-category').html(response);
					jQuery('.select2').select2();
				},
				error: function(e) {
						console.log(e);
						 }
			});
		});
			
		$("body").on("click","#join_record",function(){
		var material_id = $(this).attr("material_id");
		var url = $("#join_material_url").val();
		var curr_data = {material_id:material_id};

		$.ajax({
			url : url,
			data : curr_data,
			headers: {
				'X-CSRF-Token': csrfToken
			},
			type : "POST",
			async:false,
			success : function(response){
				//$('.modal-dialog').css("width","1076px");
				jQuery('.modal-content').html('');
				jQuery('.modal-content').html(response);
				jQuery('#load_modal').modal('show');
			},
			beforeSend:function(){
				jQuery('#modal-view').html('<center><img src=../images/4.gif width=120px><div><h3>Loading...</h3></div></center>');
			},
			error : function(e){
				console.log(e.responseText);
			}
		});
	});
	
		/* Datatable server side code start */
		var f_material_code  = jQuery("#f_material_code").val();
		var f_material_name  = jQuery("#f_material_name").val();
		var f_material_group  = jQuery("#f_material_group").val();
		var f_cost_group  = jQuery("#f_cost_group").val();
		var f_consume  = jQuery("#f_consume").val();
		var f_material_sub_category  = jQuery("#f_material_sub_category").val();
		var f_project_id  = jQuery("#f_project_id").val();

	var selected = [];
	var table = jQuery('#material_list').DataTable({
		"pageLength": 10,
		"pagingType": "numbers",
		"stateSave": true,
		"order": [[ 1, "desc" ]],
		columnDefs: [ 
					// {
						// searchable: false,
						// targets:   2,
					// },
					// {
						// searchable: false,
						// targets:   4,
					// },
					// {
						// searchable: false,
						// targets:   5,
					// },
					// {
						// searchable: false,
						// targets:   7,
					// },
					// {
						// searchable: false,
						// targets:   11,
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
						  ],
		"responsive" : true,
		"processing": true,
		"serverSide": true,
		//"ajax": "../Ajaxfunction/billrecordsdata",
		"ajax": {
				// "url": "../Ajaxfunction/porecords",
				url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'materialrecords'));?>",
				"data": function ( d ) {
											d.myKey = "myValue";
											d.material_code = f_material_code;
											d.material_name = f_material_name;
											d.material_group = f_material_group;
											d.cost_group = f_cost_group;
											d.consume = f_consume;
											d.material_sub_category = f_material_sub_category;
											d.project_id = f_project_id;
										}
				},
		"rowCallback": function( row, data ) {
								
								if ( jQuery.inArray(data.DT_RowId, selected) !== -1 ) {
									jQuery(row).addClass('selected');
								}
						},
		});
		/* Datatable server side code end */
		} );
</script>
			<input type="hidden" value="<?php echo $this->request->base.'/Ajaxfunction/joinmaterial'; ?>" id="join_material_url">
			<table id="material_list"  class="dataTables_wrapper table table-striped table-hover">
				<thead>
					<tr>
						<th>Material Code</th>
						<th>Material Group</th>
						<th>Material Sub-Group</th>
						<th>Material Name</th>						
						<th>Material Description</th>						
						<th>Unit</th>						
						<th>Project</th>						
						<th>Consume Type</th>						
						<th>Action</th>
					</tr>
				</thead>
				<!--<tbody>
					<?php
						$i = 1;	
						$rows = array();
						$rows[] = array("Material Code","Material Group","Material Sub-Group","Material Name","Material Description","Unit","Project","Consume Type");
						
						foreach($material_list as $retrive_data)
						{	$export = array();
						?>
							<tr>								
								<td><?php echo /*$category[$retrive_data['material_code']]['material_code']*/ ($export[] = $retrive_data["material_item_code"]);?></td>
								<td><?php echo ($export[] = $this->ERPfunction->get_vendor_group_name($retrive_data['material_code']));?></td>
								<td><?php echo ($export[] = $this->ERPfunction->get_material_subgroup_title($retrive_data['material_sub_group']));?></td>
								<td><?php echo ($export[] = $retrive_data['material_title']);?></td>								
								<td><?php echo ($export[] = $retrive_data['desciption']);?></td>	
								<td><?php echo ($export[] = $this->ERPfunction->get_category_title($retrive_data['unit_id']));?></td>
								<td><?php echo ($export[] = ($retrive_data['project_id'])?$this->ERPfunction->get_projectname($retrive_data['project_id']):"All");?></td>
								<td><?php echo ($export[] = $this->ERPfunction->get_consume_type($retrive_data['material_id']));?></td>
								<td>
								<?php 
								if($this->ERPfunction->retrive_accessrights($role,'addmaterial')==1)
								{
									echo $this->Html->link("<i class='icon-pencil'></i> Edit",array('action' => 'addmaterial', $retrive_data['material_id']),
									array('escape'=>false,'target'=>'blank','class'=>'btn btn-primary btn-clean'));
									echo ' ';
								}
								/* echo $this->Html->link(__('Delete'),array('action' => 'deletematerial',$retrive_data['material_id']),
								array('class'=>'btn btn-danger','confirm' => 'Are you sure you wish to delete this Record?')); */
								if($this->ERPfunction->retrive_accessrights($role,'viewmaterial')==1)
								{
									echo $this->Html->link("<i class='icon-eye-open'></i> View",array('action' => 'viewaddmaterial', $retrive_data['material_id']),array('escape'=>false,'class'=>'btn btn-info btn-clean'));
								}
								$rows[] = $export;								
								$i++;
								
								if($this->ERPfunction->retrive_accessrights($role,'Joinmaterial')==1)
								{
									echo "<a class='btn btn-primary btn-clean' id='join_record' href='javascript:void(0);' material_id='{$retrive_data['material_id']}' data-url='{$this->request->base}/Ajaxfunction/joinmaterial'><i class='icon-pencil'></i>Join</a>";
								}
								
								?>
								</td>
							</tr>
						<?php
						$i++;
						}
					?>
				</tbody>-->
			</table>
			<div class="content">
				<div class="col-md-2">
					<?php 
						echo $this->Form->Create('form3',['id'=>'export','class'=>'form_horizontal formsize','method'=>'post'],['url'=>['action'=>'']]);
					?>

					<!--<input type="hidden" name="rows" value='<?php echo base64_encode(serialize($rows));?>'>-->
					<input type="hidden" name="e_material_code" value="<?php echo isset($_POST["material_code"]) ? $_POST["material_code"] : "";?>">
					<input type="hidden" name="e_material_name" value="<?php echo isset($_POST["material_name"]) ? $_POST["material_name"] : "";?>">
					<input type="hidden" name="e_material_group" value="<?php echo isset($_POST["material_group"]) ? $_POST["material_group"] : "";?>">
					<input type="submit" class="btn btn-success" value="Export To Excel" name="export_csv">

				<?php $this->Form->end(); ?>
				</div>
				<div class="col-md-2">
					<?php 
						echo $this->Form->Create('form3',['id'=>'export','class'=>'form_horizontal formsize','method'=>'post'],['url'=>['action'=>'']]);
					?>

					<!--<input type="hidden" name="rows" value='<?php echo base64_encode(serialize($rows));?>'>-->
					<input type="hidden" name="e_material_code" value="<?php echo isset($_POST["material_code"]) ? $_POST["material_code"] : "";?>">
					<input type="hidden" name="e_material_name" value="<?php echo isset($_POST["material_name"]) ? $_POST["material_name"] : "";?>">
					<input type="hidden" name="e_material_group" value="<?php echo isset($_POST["material_group"]) ? $_POST["material_group"] : "";?>">
					<input type="submit" class="btn btn-success" value="Export To PDF" name="export_pdf">

				<?php $this->Form->end(); ?>

				</div>
			</div>
		</div>
		</div>
	</div>
</div>
<?php } ?>
</div>