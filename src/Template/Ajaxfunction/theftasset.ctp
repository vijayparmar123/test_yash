<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('#theft_form').validationEngine();
	jQuery('#theft_date').datepicker({
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
	<h4 class="modal-title"> Asset Theft</h4>
</div>
<div class="modal-body clearfix">
<div class="controls">
<form name="theftasset_form" id="theft_form" action="theftasset" method="post" class="form-horizontal">
<?php echo $this->Form->Create('theftasset_form',['method'=>'post','id' => 'theft_form','class' => 'form-horizontal','url' => ['controller' => 'Assets','action' => 'theftasset']]);?>
  	 	<input type="hidden" name="asset_id" value="<?php echo $asset_id;?>">
		 
		<div class="form-row">
			<label class="col-sm-4 control-label" for="asset_code">
				Asset ID
			</label>
			<div class="col-sm-6">
				<input  class="form-control text-input" readonly="true" type="text" 
				value="<?php echo $this->ERPfunction->get_asset_code($asset_id); ?>" name="sold_price">
			</div>			
		</div>
		
		<div class="form-row">
			<label class="col-sm-4 control-label" for="asset_name">
				Asset Name
			</label>
			<div class="col-sm-6">
				<input  class="form-control text-input" readonly="true" type="text" 
				value="<?php echo $this->ERPfunction->get_asset_name($asset_id); ?>">
			</div>			
		</div>
		
		<div class="form-row">
			<input type="hidden" value="1" name="theft_quantity">
			<label class="col-sm-4 control-label" for="project_name">
				Project Name
			</label>
			<input type="hidden" value="<?php echo $deployed_to; ?>" name="deployed_to">
			<div class="col-md-6">
				<input type="text" readonly="true" value="<?php echo $this->ERPfunction->get_projectname($deployed_to); ?>">
			</div>
		</div>
		
		<!--<div class="form-row">
			<label class="col-sm-4 control-label" for="theft_quantity">
				Quantity
			</label>
			<div class="col-sm-4">
				<input  class="form-control text-input" type="text" 
				value="" name="theft_quantity">
			</div>			
		</div>-->
		<div class="form-row">
			<label class="col-sm-4 control-label" for="theft_date">
				Theft Date
			</label>
			<div class="col-sm-6">
				<input id="theft_date" class="form-control text-input validate[required]" type="text" 
				value="" name="theft_date">
			</div>			
		</div>
		<div class="form-row">
			<label class="col-sm-4 control-label" for="reason">
				Reason
			</label>
			<div class="col-sm-6">
				<textarea name="reason" class="validate[required]"> </textarea> 
			</div>			
		</div>
		<div class="form-row">			
			<div class="col-sm-4">
				<input type="submit" value="Theft" name="theft" class="btn btn-primary" id="btn-add-category"/>
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