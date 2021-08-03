<script type="text/javascript">
	var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
	jQuery(document).ready(function() {
		jQuery('#receivepo').validationEngine();
		jQuery('#received_date').datepicker({
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
	<h4 class="modal-title">PO Receive Manual</h4>
</div>
<div class="modal-body clearfix">
<div class="controls">
	<?php 
			echo $this->Form->create("receivepo",["type"=>"file","class"=>"validateForm form-horizontal","role"=>"form","id"=>"receivepo",'url' => ['controller' => 'Purchase','action' => 'recivedpoquantitymanually']]);
		?>
		<div class="form-row">
			<div class="col-md-6">
				<input type="hidden" name="po_detail_id" value="<?php echo $po_detail_id; ?>">
				<input type="hidden" name="po_id" value="<?php echo $po_data["erp_inventory_po"]["po_id"]; ?>">
				<input type="hidden" name="material_id" value="<?php echo $po_data["material_id"]; ?>">
			</div>
		</div>
		<div class="form-row">
			<div class="col-md-3">PO No.</div>
			<div class="col-md-4"><?php echo $po_data["erp_inventory_po"]["po_no"]?></div>
		</div>
		<div class="form-row">
			<div class="col-md-3">Material</div>
			<div class="col-md-4"><?php echo $this->ERPfunction->get_material_title($po_data["material_id"]); ?></div>
		</div>
		<div class="form-row">
			<div class="col-md-3">Received Qty.</div>
			<div class="col-md-6">
				<input type="text" name="received_qty" class="form-control validate[required,custom[number]]">
			</div>
		</div>
		<div class="form-row">
			<div class="col-md-3">Received Date</div>
			<div class="col-md-6">
				<input type="text" name="received_date" id="received_date" onkeydown="return false" class="form-control validate[required] datep text-input" value="<?php echo date("d-m-Y"); ?>">
			</div>
		</div>
		<div class="form-row">
			<div class="col-md-3">Remarks</div>
			<div class="col-md-6">
				<textarea name="remarks" class="form-control"></textarea>
			</div>
		</div>
		<div class="form-row">
			<div class="col-sm-4">
				<input type="submit" value="Submit" name="receive" class="btn btn-primary"/>
			</div>
		</div>
		<?php 
			echo $this->Form->end();
		?>
</div>
</div>
<div class="modal-footer">	
	<button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>	
</div>