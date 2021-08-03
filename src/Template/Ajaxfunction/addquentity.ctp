<script>
$( "#quentity_form" ).submit(function( event ) {
	var quantity = $("#quantity").val();
	if(quantity == "")
	{
		alert("Please enter quantity");
		return false;
	}else if(!$.isNumeric(quantity))
	{
		alert("Please enter number");
		return false;
	}
});
</script>
<div class="modal-header" >
    <button type="button" class="close" data-dismiss="modal">X</button>
	<h4 class="modal-title"> <?php echo $add_in; ?></h4>
</div>
<div class="modal-body clearfix">
<div class="controls">
	<form name="add_quentity_form" action="<?php echo $this->request->base;?>/temporary/addquentity" method="post" class="form-horizontal" id="quentity_form">
		<div class="form-row">
			<label class="col-sm-3" for="transfer_to" >
				Quantity
			</label>
			<div class="col-sm-3">
				<input name="quantity" id="quantity" class="validate[required] form-control"/>
			</div>
			<div class="col-sm-4">
				<input type="hidden" name="row" value="<?php echo $row; ?>">
				<input type="hidden" name="add_in" value="<?php echo $add_in; ?>">
				<input type="submit" value="Add" name="save_quentity" class="btn btn-primary" id="btn-add-category"/>
			</div>
		</div>
	</form>
</div>
</div>
<div class="modal-footer">	
	<button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>	
</div>