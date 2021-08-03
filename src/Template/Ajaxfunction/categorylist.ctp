<?php ?>
<div class="modal-header" >
    <button type="button" class="close" data-dismiss="modal">X</button>
	<h4 class="modal-title"><?php echo $title;?></h4>
</div>
<div class="categoryList modal-body clearfix">
	<?php if($model != "subcontractbill_option"){ ?>
	<table class="table table-bordered table-striped table-hover categoryList" id="">
		<thead>
			<tr>
				<!--  <th>#</th> -->
				<th><?php echo $table_header_title;?></th>
				<?php 
					if($model == "designation") {
						echo '<th>Category</th>';
					}
					if($model == "subcontractbill_option") {
						echo '<th>Project</th>';
						echo '<th>Unit</th>';
					}
				?>
				<th><?php echo __('Action');?></th>
			</tr>
		</thead>
		<?php
        	$i = 1;
        	if(!empty($cat_result)) {
        		foreach ($cat_result as $retrieved_data) {
					echo '<tr id="cat-'.$retrieved_data['cat_id'].'">';
					//echo '<td>'.$i.'</td>';
					echo '<td>'.$retrieved_data['category_title'].'</td>';
					if($model == "designation") {
						echo '<td>'.strtoupper($retrieved_data['category']).'</td>';
					}
					if($model == "subcontractbill_option") {
						echo '<td>'.$this->ERPfunction->get_projectname($retrieved_data['project_id']).'</td>';
						echo '<td>'.$retrieved_data['unit'].'</td>';
					}
					echo '<td id='.$retrieved_data['cat_id'].'>
						<a class="btn-delete-cat badge badge-delete" model='.$model.' href="#" id='.$retrieved_data['cat_id'].'><i class="icon-trash"></i></a>';
					if($model == "unit" || $model == "designation" || $model == "make_in" || $model == "department" || $model == "subcontractbill_option") {
						echo '&nbsp;&nbsp;&nbsp;&nbsp;<a class="btn-edit-cat badge badge-info" model='.$model.' href="#" id='.$retrieved_data['cat_id'].'><i class="icon-edit"></i></a>';
					}
					echo '</td>';
					echo '</tr>';
					$i++;		
        		}
        	}
        ?>
</table>
<?php } ?>
<div class="controls">
<form name="medicinecat_form" action="" method="post" class="form-horizontal" id="medicinecat_form">
	<div class="form-row">
		<div class="col-sm-4 control-label">Work Group <span class="require-field">*</span> </div>
		<div class="col-sm-4">
			<select name="material_code"  style="width: 100%;" class="select2" required="true"  id="material_code">
			<option value="">--Select Work Group--</option>
			<?php
				foreach($erpWorkGroup as $key => $retrive_data) {
					echo '<option value="'.$retrive_data['work_group_id'].'" >'.$retrive_data['work_group_title'].'</option>';
				}
			?>
			</select>
		</div>
		<div class="col-sm-1">
			<button type="button" id="work_group" data-type="work-group" data-toggle="modal" 
			data-target="#load_modal_workgroup" class="btn btn-default add_workgroup" style="">Add</button>	
		</div>
	</div>
	<div class="form-row">
		<div class="col-sm-4 control-label">Work Sub-group <span class="require-field">*</span> </div>
		<div class="col-sm-4">
			<select name="material_code"  style="width: 100%;" class="select2" required="true"  id="work_subgroup">
				<option value=""></option>
			</select>
		</div>
		<div class="col-sm-1">
			<button type="button" id="work_group" data-type="work-group" data-toggle="modal" 
			data-target="#load_modal_worksubgroup" class="btn btn-default add_subworkgroup" style="">Add</button>	
		</div>
	</div>
	<div class="form-row">
		<label class="col-sm-4 control-label" for="category_name"><?php echo $label_text;?><span class="require-field">*</span></label>
		<div class="col-sm-4">
			<input id="category_name" class="form-control text-input" type="text" 
			value="" name="category_name">
		</div>
	</div>
		
		<?php
		if($model == "subcontractbill_option")
		{
		?>
			<div class="form-row">
				<input type="hidden" name="subc_project_id" id="subc_project_id" value="<?php echo $project_id; ?>">
				<label class="col-sm-4 control-label" for="category_name">Unit<span class="require-field">*</span></label>
				<div class="col-sm-4">
					<input id="subc_description_unit" class="form-control text-input" type="text" 
					value="" name="subc_description_unit">
				</div>
			</div>
		<?php
		}
		?>
		
		<?php
		if($model == "designation")
		{
		?>
			<div class="form-row">
			<label class="col-sm-4 control-label" for="designation_category">Category<span class="require-field">*</span></label>
				<div class="col-sm-4">
					<select name="designation_category" id="designation_category" class="form-control validate[required]">
						<option value="" >Select Category</option>
						<option value="a">A</option>
						<option value="b">B</option>
						<option value="c">C</option>
					</select>
				</div>
			</div>
		<?php
		}
		?>
			
		<div class="form-row">
		<div class="col-sm-4">
				<input type="button" value="<?php echo $button_text;?>" name="save_category" class="btn btn-primary" model="<?php echo $model;?>" id="btn-add-category"/>
			</div>
		</div>
		
  	</form>
</div>
</div>
<div class="modal-footer">	
	<button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>	
</div>
