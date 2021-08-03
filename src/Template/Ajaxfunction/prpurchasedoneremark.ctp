<?php
use Cake\Routing\Router;
?>
<?php

?>
<div class="modal-header" >
    <button type="button" class="close" data-dismiss="modal">X</button>
	<h4 class="modal-title"> Add Done Remark</h4>
</div>
<div class="modal-body clearfix">
<div class="controls">

 <?php echo $this->Form->Create('medicinecat_form',['url'=>['controller'=>'purchase', 'action'=>'removemanualpr'],'class'=>'form_horizontal formsize transferform','method'=>'post','id'=>'medicinecat_form','enctype'=>'multipart/form-data']);?> 	
	<div class="form-row">
		<label class="col-sm-3" for="project_name" >Remark :</label>
		<div class="col-sm-8">
			<input type="hidden" name="project_id" value="<?php echo $project_id ?>">
			<input type="hidden" name="pr_item_row_id" value="<?php echo $pr_detail_id ?>">
			<textarea name="done_remark" id="remark"></textarea>
		</div>
	</div>
	
	<div class="form-row">			
		<div class="col-sm-4">
			<input type="submit" value="Submit" name="update_remark" class="btn btn-primary" id="update-remark"/>
		</div>
	</div>
  	<?php echo $this->Form->end(); ?>
</div>
</div>
<div class="modal-footer">	
	<button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>	
</div>