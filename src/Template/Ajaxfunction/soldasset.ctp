<script type="text/javascript">
jQuery(document).ready(function() {
	// jQuery('.select2').select2();
	jQuery('#sold_form').validationEngine();
	jQuery("#soldfrm").validationEngine();
	jQuery('#sold_date').datepicker({
		dateFormat: "dd-mm-yy",
		  changeMonth: true,
	        changeYear: true,
	        yearRange:'-65:+0',
	        onChangeMonthYear: function(year, month, inst) {
	            jQuery(this).val(month + "-" + year);
	        }
                    
    }); 
	jQuery('select#vendor_userid').select2();
});
</script>
<style>
#ui-datepicker-div{z-index:9999 !important;}
</style>
<div class="modal-header" >
    <button type="button" class="close" data-dismiss="modal">X</button>
	<h4 class="modal-title"> Asset Sold</h4>
</div>
<div class="modal-body clearfix">
<div class="controls">
<!-- <form name="soldasset_form" id="sold_form" action="soldasset" method="post" class="form-horizontal soldfrm"> -->
  	 <?php echo $this->Form->Create('soldasset_form',['method'=>'post','id' => 'sold_form','class' => 'form-horizontal soldfrm','url' => ['controller' => 'Assets','action' => 'soldasset']]);?>
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
				value="<?php echo $this->ERPfunction->get_asset_name($asset_id); ?>" name="sold_price">
			</div>			
		</div>
		
		<div class="form-row">
			<label class="col-sm-4 control-label" for="project_name">
				Project Name
			</label>
			<input type="hidden" value="<?php echo $deployed_to; ?>" name="deployed_to">
			<div class="col-md-6">
				<input type="text" readonly="true" value="<?php echo $this->ERPfunction->get_projectname($deployed_to); ?>">
			</div>
		</div>
		
		<div class="form-row">
			<label class="col-sm-4 control-label" for="	sold_to">
				Sold To
			</label>
			<div class="col-sm-6">
				<select class="select2" required="true" style="width: 100%;" name="vendor_userid" id="vendor_userid">
				<option value="">--Select Vendor--</Option>
				<?php 
					foreach($vendor_list as $key=>$value){
						echo '<option value="'.$key.'">'.$value.'</option>';
					}
				?>
				</select>
			</div>			
		</div>
		
		<div class="form-row">
			<label class="col-sm-4 control-label" for="sold_date">
				Sold Date
			</label>
			<div class="col-sm-6">
				<input id="sold_date" class="form-control text-input validate[required]" type="text" name="sold_date">
			</div>			
		</div>
						
		<div class="form-row">
			<label class="col-sm-4 control-label" for="sold_price">
				Amount
			</label>
			<div class="col-sm-6">
				<input  class="form-control text-input validate[required]" type="text" 
				value="" name="sold_price">
			</div>			
		</div>
		
		<div class="form-row">
			<label class="col-sm-4 control-label" for="	sold_quantity">
				Voucher No.
			</label>
			<div class="col-sm-6">
				<input  class="form-control text-input validate[required]" type="text"  name="voucher_no">
			</div>			
		</div>
		
		<div class="form-row">			
			<div class="col-sm-4">
				<input type="submit" value="Sold" name="sold" class="btn btn-primary" id="btn-add-category"/>
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