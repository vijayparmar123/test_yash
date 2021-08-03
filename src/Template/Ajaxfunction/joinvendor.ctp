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
	<h4 class="modal-title"> Join Vendor</h4>
</div>
<div class="modal-body clearfix">
<div class="controls">
<?php echo $this->Form->create('join_form',['url'=>['controller'=>'purchase', 'action'=>'joinvendor'],'method'=>'post', 'id'=>'join_form','class'=>'form-horizontal transferform']); ?>
				
	<div class="form-row">
		<div class="col-md-3">Master vendor <span class="require-field">*</span> </div>
		<div class="col-md-6">
			<select class="select2" name="parent_vendor_id" id="vendor_id" required="true" style='width:100%'>
				<option value="">--Select Vendor--</option>
				<?php
					foreach($vendor_list as $key=>$value)
					{ 
						echo '<option value="'.$key.'">'.$value.'</option>';
					}
				?>
			</select>
			<input type="hidden" value="<?php echo $vendor_id; ?>" name="base_vendor">
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