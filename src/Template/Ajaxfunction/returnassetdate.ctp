<?php
use Cake\Routing\Router;
?>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('#asset_return_date').datepicker({
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
<style>
#ui-datepicker-div{z-index:9999 !important;}
</style>
<div class="modal-header" >
    <button type="button" class="close" data-dismiss="modal">X</button>
	<h4 class="modal-title"> Asset Return Date</h4>
</div>
<div class="modal-body clearfix">
<div class="controls">
  	 	<input type="hidden" id="return_asset_id" value="<?php echo $asset_id;?>">
		
		<div class="form-row">
			<label class="col-sm-4 control-label">
				Return Date
			</label>
			<div class="col-sm-6">
				<input id="asset_return_date" class="form-control text-input" type="text" 
				value="">
			</div>			
		</div>
		<div class="form-row">			
			<div class="col-sm-4">
				<input type="button" value="Submit" id="submit_return_date" class="btn btn-primary" id="btn-add-category"/>
			</div>
		</div>
</div>
</div>
<div class="modal-footer">	
	<button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>	
</div>