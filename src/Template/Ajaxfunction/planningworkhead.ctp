<?php
use Cake\Routing\Router;
?>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('#material_form').validationEngine();	
	
	
	});
</script>
<style>
#ui-datepicker-div{z-index:9999 !important;}
</style>
<div class="modal-header" >
    <button type="button" class="close" data-dismiss="modal">X</button>
	<h4 class="modal-title"> Add Work Head</h4>
</div>
<div class="modal-body clearfix">
	<div class="controls">
		<form id="workhead_form" method="post" class="form-horizontal">
			
			<input type="hidden" value="<?php echo $project_id; ?>" id="project_id" name="projectId">			
			<div class="form-row">
				<div class="col-md-4">Work Head Code<span class="require-field">*</span></div>
				<div class="col-md-8">
					<input type="text" name="work_head_code" id="work_head_code" class="form-control"
					value="<?php echo $this->ERPfunction->generate_auto_id_planning_work_head(); ?>" readonly="true"/>
				</div>
			</div>

			<div class="form-row">
				<div class="col-md-4">Head Name<span class="require-field">*</span></div>
				<div class="col-md-8">
					<input type="text" name="work_head_title" class="work_head_title form-control validate[required]"/>
				</div>
			</div>
				
			<div class="form-row">			
				<div class="col-sm-4">
					<input type="button" value="Add" id="save" name="go" class="btn btn-primary"/>
				</div>
			</div>
		</form>
	</div>
</div>
<div class="modal-footer">	
	<button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>	
</div>