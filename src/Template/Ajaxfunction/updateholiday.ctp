<?php 
use Cake\Routing\Router;
?>
<script type="text/javascript">
jQuery(document).ready(function() {
	
});
</script>
<?php 
$dateObj = DateTime::createFromFormat('!m', $month);
$monthName = $dateObj->format('F');
?>
<div class="modal-header" >
    <button type="button" class="close" data-dismiss="modal">X</button>
	<h4 class="modal-title"> <?php echo $monthName."-".$year; ?></h4>
</div>
<div class="modal-body clearfix">
<div class="controls">
	
	<form name="edit_subgroup_form" action="" method="post" class="form-horizontal" id="edit_subgroup_form">
		<div class="form-row">
			<label class="col-md-4" for="transfer_to" >
				Update Holiday
			</label>
			<div class="col-md-6">
				<input type="hidden" id="holiday_month" value="<?php echo $month; ?>">
				<input type="hidden" id="holiday_year" value="<?php echo $year; ?>">
				<input type="text" id="holiday_number" value="<?php echo $holiday; ?>" class="validate[required] form-control"/>
			</div>
		</div>
		<div class="form-row">
			<div class="col-sm-4">
				<input type="button" value="Update" name="update-holiday-value" class="btn btn-primary" id="update-holiday-value"/>
			</div>
		</div>
	</form>
	
</div>
</div>
<div class="modal-footer">	
	<button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>	
</div>