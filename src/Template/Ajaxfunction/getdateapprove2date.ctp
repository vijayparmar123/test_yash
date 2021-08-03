<?php
use Cake\Routing\Router;
?>

<head>
	<script>
		jQuery('.gate_pass_date').datepicker({
			dateFormat: "dd-mm-yy",
		  	changeMonth: true,
	        changeYear: true,
	        yearRange:'-65:+0',
			minDate: 0,
	        onChangeMonthYear: function(year, month, inst) {
	            jQuery(this).val(month + "-" + year);
	        },
			onSelect: function (date) {
                var dt2 = $('.gate_pass_date');
                var minDate = $(this).datepicker('getDate');
                dt2.datepicker('setDate', minDate);
            }
    	});
	</script>
	<style>
		.ui-datepicker {
			z-index : 1041 !important;
		}
	</style>
</head>

<?php
	$date = strtotime($due_date);
	$gate_pass_date = isset($due_date)?date('d-m-Y',strtotime($due_date)):date('d-m-Y');
?>
<div class="modal-header " tabindex="-1">
    <button type="button" class="close" data-dismiss="modal">X</button>
	<h4 class="modal-title"> Add Due Date</h4>
</div>
<div class="modal-body clearfix">
	<div class="controls">
		<form name="medicinecat_form" method="post" class="form-horizontal transferform" action="">
			
			<div class="form-row">
				<label class="col-sm-3" for="project_name" >Due Date :</label>
				<div class="col-sm-8">
					<input type="hidden" name="pr_id" id="pr_id" value="<?php echo $pr_id ?>">
					<input type="text" name="date" id="date" value="<?php echo date("d-m-Y",strtotime($gate_pass_date)) ?>" class="form-control gate_pass_date">
				</div>
			</div>
			
			<div class="form-row">			
				<div class="col-sm-4">
					<input type="submit" value="Submit" name="due_date" class="btn btn-primary" id="add_due_date"/>
				</div>
			</div>
		</form>
		</div>
</div>
<div class="modal-footer">	
	<button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>	
</div>