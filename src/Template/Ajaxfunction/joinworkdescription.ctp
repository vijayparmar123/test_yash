<?php
use Cake\Routing\Router;
?>
<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('#join_form').validationEngine();
	});
</script>
<div class="modal-header" >
    <button type="button" class="close" data-dismiss="modal">X</button>
	<h4 class="modal-title"> Join Work Description</h4>
</div>
<div class="modal-body clearfix">
	<div class="controls">
	<?php echo $this->Form->Create('join_form',['url'=>['controller'=>'contract', 'action'=>'joinworkdescription'],'class'=>'form_horizontal formsize','method'=>'post','id'=>'join_form','enctype'=>'multipart/form-data']);?>
		
			<div class="form-row">
				<div class="col-md-3">Master Work-description<span class="require-field">*</span> </div>
				<div class="col-md-6">
					<select class="select2" name="material_id" id="material_id" required="true" style='width:100%'>
						<option value="">--Select Work Description--</option>
						<?php						
							foreach($description_list as $key=>$value) { 
								if($value != NULL) {
									echo '<option value="'.$key.'">'.$value.'</option>';
								}
							}
						?>
					</select>
					<input type="hidden" value="<?php echo $material_id; ?>" name="base_material">
				</div>                     
			</div>
			
			<div class="form-row">			
				<div class="col-sm-4">
					<input type="submit" value="Submit" name="insert" class="btn btn-primary" id="insert"/>
				</div>
			</div>
		<?php echo $this->Form->end(); ?>
	</div>
</div>
<div class="modal-footer">	
	<button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>	
</div>