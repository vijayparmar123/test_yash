<?php ?>
<script>
</script>
<div class="modal-header" >
    <button type="button" class="close" data-dismiss="modal">X</button>
	<h4 class="modal-title">Select Work Description</h4>
</div>
<div class="modal-body clearfix">
	<div class="controls">
		<form name="medicinecat_form" action="" method="post" class="form-horizontal" id="medicinecat_form">
			<div class="form-row">
				<label class="col-sm-4 control-label" for="work_description">Work Description<span class="require-field">*</span></label>
				<div class="col-sm-4" >
					<input type="hidden" value="<?php echo $type; ?>" name = "type_of_modal">
					<select class="select2" name="work_description" id="work_description" required="true" style='width:100%'>
						<option value="">--Select Work Description--</option>
						<?php						
							foreach($erpWorkGroup as $key=>$value) { 
								if($value != NULL) {
									echo '<option value="'.$key.'">'.$value.'</option>';
								}
							}
						?>
					</select>
				</div>
			</div>		
			<div class="form-row">
				<div class="col-sm-4">
					<input type="button" value="Enable" name="save_group" class="btn btn-primary" id="btn-enable-description"/>
				</div>
				<button type="button" id="new_option" data-type="subcontractbill_option" data-toggle="modal" 
					data-target="#load_modal" class="btn btn-default add_option" style="">Add New Option </button>
			</div>
		</form>
	</div>
</div>
<div class="modal-footer">	
	<button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>	
</div>