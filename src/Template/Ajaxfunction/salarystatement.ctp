<?php
use Cake\Routing\Router;
?>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('.transferform').validationEngine();
	$("#from_date").datepicker({ 
		changeMonth: true,
		changeYear: true,
		dateFormat: "MM yy" 
	});
    
	jQuery('#to_date').datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat: "MM yy",
	});	
	
	jQuery("body").on("click", "#filter", function(event){
		var from_date  = jQuery("#from_date").val();
		var to_date  = jQuery("#to_date").val();
		if(from_date == '' || to_date == '')
		{
			alert('Please fill all field');
			return false;
		}
	});
});
</script>
<style>
#ui-datepicker-div{z-index:9999 !important;}
</style>
<div class="modal-header" >
    <button type="button" class="close" data-dismiss="modal">X</button>
	<h4 class="modal-title"> Salary Statement</h4>
</div>
<div class="modal-body clearfix">
<div class="controls">
<!-- <form name="medicinecat_form" action="<?php echo $this->request->base;?>/Humanresource/salarystament" method="post" class="form-horizontal transferform" target="_blank"> -->
<?php 
	echo $this->Form->Create('',['target' => '_blank','id'=>'medicinecat_form','class'=>'form_horizontal transferform','method'=>'post','url'=>['controller'=> 'Humanresource','action'=>'salarystament']]);
?>
		<input type="hidden" name="user_id" value='<?php echo $user_id;?>'>
				
		<div class="form-row">			
				<div class="col-md-2">Date:</div>
				<div class="col-md-1">From<span class="require-field">*</span></div>
				<div class="col-md-4">
					<input type="text" name="from_date" id="from_date" value="" class="form-control validate['required'] from_date"/>
				</div>
				
				<div class="col-md-1">To<span class="require-field">*</span></div>
				<div class="col-md-4">
					<input type="text" name="to_date" id="to_date" value="" class="form-control validate['required'] to_date"/>
				</div>
			</div>
		
		
		<div class="form-row">			
			<div class="col-sm-4">
				<input type="submit" value="Submit" name="filter" class="btn btn-primary" id="filter"/>
			</div>
		</div>
	<?php $this->Form->end(); ?>
</div>
</div>
<div class="modal-footer">	
	<button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>	
</div>