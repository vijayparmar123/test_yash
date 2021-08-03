<?php
use Cake\Routing\Router;
?>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('.select2').select2();
	jQuery('.transferform').validationEngine();
	jQuery('#transfer_date').datepicker({
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
	<h4 class="modal-title"> Advance Transfer</h4>
</div>
<div class="modal-body clearfix">
<div class="controls">
<?php echo $this->Form->create('medicinecat_form',['url'=>['controller'=>'Accounts', 'action'=>'advancetransfer'],'method'=>'post', 'class'=>'form-horizontal transferform']); ?>
		<input type="hidden" name="request_id" value='<?php echo $request_id;?>'>
		<!-- <div class="form-row">
			<label class="col-sm-4 control-label" for="transfer_date">
				Bank
			</label>
			<div class="col-sm-4">
			<input type="text" name="bank_name" id="project_code" value="" class="form-control validate[required]" value=""/>
			</div>
		</div>
		
		<div class="form-row">
			<label class="col-sm-4 control-label" for="transfer_date">
				Cheque No
			</label>
			<div class="col-sm-4">
				<input id="cheque_no" class="form-control text-input validate[required,custom[integer]]" type="text" 
				value="" name="cheque_no">
			</div>			
		</div> -->
		
		<div class="form-row">
			<label class="col-sm-4 control-label" for="transfer_date">
				Transfer Date<span class="require-field">*</span>
			</label>
			<div class="col-sm-4">
				<input id="transfer_date" class="form-control text-input validate[required]" type="text" 
				value="<?php echo date("d-m-Y"); ?>" name="transfer_date">
			</div>			
		</div>
		
	
				<input name="amount" value="<?php echo $cheque_amount; ?>" readonly="true" type="hidden" class="form-control validate[required,custom[number]]" data-errormessage-range-overflow="Incorrect Quantity" />
		
		<div class="form-row">
			<label class="col-sm-4 control-label" for="transfer_to">
				Transfer Type
			</label>
			<div class="col-sm-4">
				<select class="select2" required="true"   style="width: 100%;" name="transfer_type">
					<option value="NEFT">NEFT</Option>
					<option value="Transfer">Transfer</Option>
					<option value="Single-Cheque">Single-Cheque</Option>
				</select>
			</div>			
		</div>
		
		<div class="form-row">			
			<div class="col-sm-4">
				<input type="submit" value="Transfer" name="transfer" class="btn btn-primary" id="btn-add-category"/>
			</div>
		</div>
		<?php echo $this->Form->end(); ?>
</div>
</div>
<div class="modal-footer">	
	<button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>	
</div>