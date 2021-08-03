<?php
use Cake\Routing\Router;
?>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('.bookingform').validationEngine();
	
	jQuery('#requirment_date').datepicker({
		dateFormat: "dd-mm-yy",
		  changeMonth: true,
	        changeYear: true,
	        yearRange:'-65:+0',
	        onChangeMonthYear: function(year, month, inst) {
	            jQuery(this).val(month + "-" + year);
	        }
                    
    });
	// jQuery('.select2').select2();
	
});
</script>
<style>
#ui-datepicker-div{z-index:9999 !important;}
</style>
<div class="modal-header" >
    <button type="button" class="close" data-dismiss="modal">X</button>
	<h4 class="modal-title"> Asset Booking</h4>
</div>
<div class="modal-body clearfix">
<div class="controls">
<form name="medicinecat_form" action="bookingasset" method="post" class="form-horizontal bookingform">
  	 	<input type="hidden" name="asset_id" value="<?php echo $asset_id;?>">
				
		<div class="form-row">
			<label class="col-sm-4 ">
				Asset Id
			</label>
			<div class="col-sm-6">
				<input disabled value="<?php echo $asset_data['asset_code'] ?>" class="form-control"/>
			</div>			
		</div>
		
		<div class="form-row">
			<label class="col-sm-4 ">
				Asset Name
			</label>
			<div class="col-sm-6">
				<input disabled value="<?php echo $asset_data['asset_name'] ?>" class="form-control"/>
			</div>			
		</div>
		
		<div class="form-row">
			<label class="col-sm-4 control-label" for="transfer_to">
				Project
			</label>
			<div class="col-sm-6">
				<input type="hidden" name="project_id" value="<?php echo $asset_data['deployed_to']; ?>">
				<select class="" disabled style="width:100%;">
					<option value="">--Select Project--</Option>
					<?php 
						foreach($projects as $retrive_data)
						{
							$selected = ($retrive_data['project_id'] == $asset_data['deployed_to'])?"selected":""; 
							echo '<option value="'.$retrive_data['project_id'].'"'.$selected.'>'. $retrive_data['project_name'].'</option>';
						}
					?>
				</select>
			</div>			
		</div>
		
		<div class="form-row">
			<label class="col-sm-4 control-label">
				Tentative Date of Release
			</label>
			<div class="col-sm-6">
				<input id="release_date" disabled class="form-control text-input" type="text" 
				value="<?php echo $this->ERPfunction->get_asset_tentativerelease_date($asset_id); ?>" name="release_date">
			</div>			
		</div>
		
		<div class="form-row">
			<label class="col-sm-4 control-label">
				Date of Requirment
			</label>
			<div class="col-sm-6">
				<input id="requirment_date" class="form-control text-input validate[required]" onkeydown="return false" type="text" name="requirment_date">
			</div>			
		</div>
		<div class="form-row">			
			<div class="col-sm-4">
				<input type="submit" value="Book" name="book" class="btn btn-primary" id="btn-add-category"/>
			</div>
		</div>
  	</form>
</div>
</div>
<div class="modal-footer">	
	<button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>	
</div>