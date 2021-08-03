<div class="modal-header" >
    <button type="button" class="close" data-dismiss="modal">X</button>
	<h4 class="modal-title"> Change Attendance Status</h4>
</div>
<div class="modal-body clearfix">
<div class="controls">
	<div class="col-sm-6"><h6>Name: <?php echo $this->ERPfunction->get_employee_name($user_id); ?></h6></div>
	<div class="col-sm-4 pull-right"><h6>Date : <?php  echo $day."/".$month."/".$year;   ?></h6></div>
	<form name="medicinecat_form" action="<?php echo $this->request->base;?>/attendance/changeattendancestatus" method="post" class="form-horizontal transferform">
		<div class="form-row">
			<label class="col-sm-3" for="transfer_to" >
				Change To
			</label>
			<div class="col-sm-3">
				<select name='new_status' class="form-control">
					<option value="manual_P" <?php echo ($status == "P" || $status == "manual_P")?"selected":"";?>>P</option>
					<option value="manual_HL" <?php echo ($status == "HL" || $status == "manual_HL")?"selected":"";?>>P/2</option>
					<option value="manual_A" <?php echo ($status == "A" || $status == "manual_A")?"selected":"";?>>A</option>
					<option value="manual_AA" <?php echo ($status == "AA" || $status == "manual_AA")?"selected":"";?>>AA</option>
					<option value="manual_H" <?php echo ($status == "H" || $status == "manual_H")?"selected":"";?>>H</option>
				</select>
			</div>
			<div class="col-sm-4">
				<input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
				<input type="hidden" name="day" value="<?php echo $day;?>">
				<input type="hidden" name="month" value="<?php echo $month;?>">
				<input type="hidden" name="year" value="<?php echo $year;?>">
				<input type="hidden" name="detail_id" value="<?php echo $detail_id;?>">
				<input type="hidden" name="man_pl" value="<?php echo $man_pl;?>">
				<?php if($this->ERPfunction->retrive_accessrights($role,'chage_attendance')==1)
								{ ?>
				<input type="submit" value="Update" name="chage_attendance" class="btn btn-primary" id="btn-add-category"/>
								<?php } ?>
			</div>
		</div>
	</form>
</div>
</div>
<div class="modal-footer">	
	<button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>	
</div>