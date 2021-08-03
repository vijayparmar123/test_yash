<script type="text/javascript">
jQuery(document).ready(function() {
	
	jQuery('#transfer_date').datepicker({
		dateFormat: "dd-mm-yy",
		  changeMonth: true,
	        changeYear: true,
	        yearRange:'-65:+0',
			maxDate: new Date(),
	        onChangeMonthYear: function(year, month, inst) {
	            jQuery(this).val(month + "-" + year);
	        }
                    
    }); 
});
</script>

<style>
#ui-datepicker-div{z-index:9999 !important;}
</style>
<div class="modal-header" >
    <button type="button" class="close" data-dismiss="modal">X</button>
	<h4 class="modal-title"> Employee Transfer</h4>
</div>
<div class="modal-body clearfix">
<div class="controls">
<form name="medicinecat_form" action="<?php echo $this->request->base;?>/humanresource/transferemployee" method="post" class="form-horizontal">
  	 	<input type="hidden" name="user_id" value="<?php echo $user_id;?>">
		<div class="form-row">
			<label class="col-sm-4 control-label" for="transfer_to">
				Transfer To
			</label>
			<div class="col-sm-8">
				<select class="transfer_to" required="true"   style="width: 100%;" name="transfer_to">
								<option value="">--Select Project--</Option>
								<?php 
									foreach($projects as $retrive_data)
									{
										echo '<option value="'.$retrive_data['project_id'].'" >
										'.$retrive_data['project_name'].'</option>';
									}
								?>
							</select>
			</div>			
		</div>
		<div class="form-row">
			<label class="col-sm-4 control-label" for="transfer_date">
				Date
			</label>
			<div class="col-sm-4">
				<input id="transfer_date" class="form-control text-input" type="text" 
				value="" name="transfer_date">
			</div>			
		</div> 
		<div class="form-row">			
			<div class="col-sm-4">
				<input type="submit" value="Transfer" name="transfer" class="btn btn-primary" id="btn-add-category"/>
			</div>
		</div>
  	</form>
</div>
</div>
<div class="modal-footer">	
	<button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>	
</div>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('.transfer_to').select2();
});
</script>