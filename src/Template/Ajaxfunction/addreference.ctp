<?php
use Cake\Routing\Router;
?>
<style>
#ui-datepicker-div{z-index:9999 !important;}
</style>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('#reference_form').validationEngine();
	} );
</script>
<div class="modal-header" >
    <button type="button" class="close" data-dismiss="modal">X</button>
	<h4 class="modal-title"> Add Reference</h4>
</div>
<div class="modal-body clearfix">
<div class="controls">

<?php echo $this->Form->Create('reference_form',['url'=>['controller'=>'Contract', 'action'=>'addreference'],'class'=>'form_horizontal formsize','method'=>'post','id'=>'reference_form','enctype'=>'multipart/form-data']);?>
	   <div class="form-row">
			<div class="col-md-4">Reference Name*</div>
			<div class="col-md-8">
				<input type="text" name="reference_name" class="validate[required]" id="reference_name" class="form-control"/>
				<input type="hidden" name="project_id" value="<?php echo $project_id; ?>" id="project_id"/>
			</div>
		</div>
		
		<div class="form-row">			
			<div class="col-sm-4">
				<input type="submit" value="Add" name="insert" class="btn btn-primary" id="insert"/>
			</div>
		</div>
  	<?php $this->Form->end(); ?>
</div>
</div>
<div class="modal-footer">	
	<button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>	
</div>