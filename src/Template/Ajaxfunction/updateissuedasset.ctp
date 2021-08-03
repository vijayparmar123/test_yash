<?php
use Cake\Routing\Router;
?>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('#issue_date').datepicker({
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
	<h4 class="modal-title"> Edit Asset Issue</h4>
</div>
<div class="modal-body clearfix">
<div class="controls">
<form name="medicinecat_form" action="issueasset" method="post" class="form-horizontal transferform">
  	 	<input type="hidden" id="asset_id" name="asset_id" value="<?php echo $asset_id;?>">
  	 	<input type="hidden" id="history_id" name="history_id" value="<?php echo $history_record_id;?>">
				
		<div class="form-row">
			<label class="col-sm-4 ">
				Asset Id
			</label>
			<div class="col-sm-6">
				<input disabled value="<?php echo $this->ERPfunction->get_asset_code($asset_id); ?>" class="form-control"/>
			</div>			
		</div>
		
		<div class="form-row">
			<label class="col-sm-4 ">
				Asset Name
			</label>
			<div class="col-sm-6">
				<input disabled value="<?php echo $this->ERPfunction->get_asset_name($asset_id); ?>" class="form-control"/>
			</div>			
		</div>
		
		<div class="form-row">
			<label class="col-sm-4 control-label" for="transfer_to">
				Project
			</label>
			<div class="col-sm-6">
				<input type="hidden" name="project_id" value="<?php echo $history_data['project_id']; ?>">
				<select class="" disabled style="width:100%;">
					<option value="">--Select Project--</Option>
					<?php 
						foreach($projects as $retrive_data)
						{
							$selected = ($retrive_data['project_id'] == $history_data['project_id'])?"selected":""; 
							echo '<option value="'.$retrive_data['project_id'].'"'.$selected.'>'. $retrive_data['project_name'].'</option>';
						}
					?>
				</select>
			</div>			
		</div>
		
		<div class="form-row">
			<label class="col-sm-4 control-label" for="issue_to">
				Issued To
			</label>
			<div class="col-sm-6">
				<input class="form-control" id="issue_to" value="<?php echo $history_data["issued_to"] ?>" name="issue_to"/>
			</div>			
		</div>
		
		<div class="form-row">
			<label class="col-sm-4 control-label">
				Issue Date
			</label>
			<div class="col-sm-6">
				<input id="issue_date" class="form-control datep text-input" type="text" 
				value="<?php echo $this->ERPfunction->get_date($history_data["issued_date"]); ?>" name="issue_date">
			</div>			
		</div>
		<div class="form-row">			
			<div class="col-sm-4">
				<input type="button" value="Update Issued" name="issue_aaset" class="btn btn-primary" id="update-issued-history"/>
			</div>
		</div>
  	</form>
</div>
</div>
<div class="modal-footer">	
	<button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>	
</div>