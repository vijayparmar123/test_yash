<?php
use Cake\Routing\Router;
?>
<?php

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
	<h4 class="modal-title"> Manage Stock</h4>
</div>
<div class="modal-body clearfix">
<div class="controls">
<?php echo $this->Form->Create('medicinecat_form',['url'=>['controller'=>'Inventory', 'action'=>'managestock'],'class'=>'form_horizontal transferform','method'=>'post','id'=>'medicinecat_form','enctype'=>'multipart/form-data']);?> 	 	
		
		<div class="form-row">
			<label class="col-sm-3 " for="project_name" >Project Name :</label>
			<div class="col-sm-8">
				<?php if(isset($project_id)){echo $this->ERPfunction->get_projectname($project_id);} ?>  
				<input type="hidden" name="project" readonly="true" value="<?php echo $project_id; ?>">
			</div>
		</div>
		
		<div class="form-row">
			<label class="col-sm-3 " for="material_name" >Material Name :</label>
			<div class="col-sm-8">
				<?php if(isset($material_id)){echo is_numeric($material_id)?$this->ERPfunction->get_material_title($material_id):$material_id;} ?>
				<input type="hidden" readonly="true" name="material" value="<?php echo $material_id; ?>">
			</div>
		</div>
		
		<div class="form-row">
			<label class="col-sm-4 " for="material_name" >Max Purchase Level</label>
			<div class="col-sm-4">
			<?php
				if($this->ERPfunction->retrive_accessrights($role,'max_stock')==1)
				{
			?>
				<input name="max_quentity" value="<?php if(!empty($data)){echo $data[0]["max_quantity"];} ?>"   class="form-control validate[required,custom[number]]"  />
			<?php }else{ ?>
			<input name="max_quentity" value="<?php if(!empty($data)){echo $data[0]["max_quantity"];} ?>"   class="form-control validate[required,custom[number]]" readonly="true" />
			<?php } ?>
			</div>
		</div>
		
		<div class="form-row">
			<label class="col-sm-4 " for="material_name" >Min Stock Level</label>
			<div class="col-sm-4">
			<?php
				if($this->ERPfunction->retrive_accessrights($role,'min_stock')==1)
				{
			?>
				<input name="min_quentity" value="<?php if(!empty($data)){echo $data[0]["min_quantity"];} ?>"   class="form-control validate[required,custom[number]]"  />
			<?php }else{ ?>
				<input name="min_quentity" value="<?php if(!empty($data)){echo $data[0]["min_quantity"];} ?>"   class="form-control validate[required,custom[number]]" readonly="true" />
			<?php } ?>
			</div>
		</div>
		
		<div class="form-row">			
			<div class="col-sm-4">
				<input type="submit" value="Manage" name="manage" class="btn btn-primary" id="btn-add-category"/>
			</div>
		</div>
  		<?php echo $this->Form->end(); ?>
</div>
</div>
<div class="modal-footer">	
	<button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>	
</div>