<div class="modal-header" >
    <button type="button" class="close" data-dismiss="modal">X</button>
	<h4 class="modal-title"> Add Leave Balance</h4>
</div>
<div class="modal-body clearfix">
<div class="controls">
	<div class="col-sm-4"><h6>Name: <?php echo $this->ERPfunction->get_employee_name($user_id); ?></h6></div>	
	<form name="medicinecat_form" action="<?php echo $this->request->base;?>/attendance/addleavebalance" method="post" class="form-horizontal transferform">
		<div class="form-row">
			<label class="col-sm-3" for="transfer_to" >
				Current Balance
			</label>
			<div class="col-sm-3">
				<input name="balance" class="validate[required] form-control" value="<?php echo $balance;?>"  />
			</div>
			<div class="col-sm-4">
				<input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
				<input type="submit" value="Update" name="chage_attendance" class="btn btn-primary" id="btn-add-category"/>
			</div>
		</div>
	</form>
</div>
</div>
<div class="modal-footer">	
	<button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>	
</div>