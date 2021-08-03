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
	<h4 class="modal-title"> Join Material</h4>
</div>
<div class="modal-body clearfix">
<div class="controls">
<!--<form id="join_form"  method="post" class="form-horizontal transferform" action="<?php echo $this->request->base; ?>/purchase/joinmaterial">-->
<?php echo $this->Form->create('join_form',['url'=>['controller'=>'purchase', 'action'=>'joinmaterial'],'method'=>'post', 'id'=>'join_form','class'=>'form-horizontal transferform']); ?>
				
	<div class="form-row">
		<div class="col-md-3">Master Material<span class="require-field">*</span> </div>
		<div class="col-md-6">
			<select class="select2" name="material_id" id="material_id" required="true" style='width:100%'>
				<option value="">--Select Material--</option>
				<?php
					foreach($material_list as $key=>$value)
					{ 
						echo '<option value="'.$key.'">'.$value.'</option>';
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
  	</form>
</div>
</div>
<div class="modal-footer">	
	<button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>	
</div>