<?php
use Cake\Routing\Router;
?>
<div class="modal-header" >
    <button type="button" class="close" data-dismiss="modal">X</button>
	<h4 class="modal-title"> Add Remark</h4>
</div>
<div class="modal-body clearfix">
<div class="controls">
<!-- <form name="medicinecat_form" method="post" class="form-horizontal transferform" action="<?php echo $this->request->base;?>/purchase/updateremarks"> -->
<?php echo $this->Form->Create('',['class'=>'form_horizontal transferform','method'=>'post','url'=>['controller'=>'Purchase','action'=>'updateremarks']]);?>

	<div class="form-row">
		<label class="col-sm-3" for="project_name" >Remark :</label>
		<div class="col-sm-8">
			<input type="hidden" name="pr_detail_row_id" value="<?php echo $pr_detail_id ?>">
			<input type="hidden" name="project_id" value="<?php echo $project_id ?>">
			<textarea name="remark" id="remark"><?php echo $remark_data ?></textarea>
		</div>
	</div>
	
	<div class="form-row">			
		<div class="col-sm-4">
			<input type="submit" value="Submit" name="update_remark" class="btn btn-primary" id="update-remark"/>
		</div>
	</div>
	<?php $this->Form->end(); ?>
</div>
</div>
<div class="modal-footer">	
	<button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>	
</div>