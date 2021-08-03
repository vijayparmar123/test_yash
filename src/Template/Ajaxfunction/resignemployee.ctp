<script type="text/javascript">
jQuery(document).ready(function() {
	// jQuery('.select2').select2();
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
<form name="medicinecat_form" action="<?php echo $this->request->base;?>/Humanresource/resignemployee" method="post" class="form-horizontal">
  	 	<input type="hidden" name="user_id" value="<?php echo $user_id;?>">
		<div class="form-row">
			<label class="col-sm-4 control-label" for="resign_reason">
				Resign Reson
			</label>
			<div class="col-sm-4">
				<textarea name="resign_reason"></textarea>
			</div>			
		</div>
		<div class="form-row">
			<label class="col-sm-4 control-label" for="transfer_date">
				Date
			</label>
			<div class="col-sm-4">
				<input id="transfer_date" class="form-control text-input" type="text" 
				value="" name="resign_date">
			</div>			
		</div>
		<div class="form-row">			
			<div class="col-sm-4">
				<input type="submit" value="Submit" name="resign" class="btn btn-primary"/>
			</div>
		</div>
  	</form>
</div>
</div>
<div class="modal-footer">	
	<button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>	
</div>