<tr id="row_id_<?php echo $row_id;?>">
	<td>
		<input type="text" value="" name="drawing[revision_no][]" class="validate[required]" id="revision_no_<?php echo $row_id;?>">
		<input type="hidden" value="<?php echo $row_id;?>" name="row_number" class="row_number">
	</td>
	<td>
		<input type="text" value="" name="drawing[receipt_date][]" class="datepick" id="receipt_date_<?php echo $row_id;?>">
	</td>
	<td>
		<input type="text" value="" name="drawing[remark][]" id="remark_<?php echo $row_id;?>">
	</td>
	<td>
		<input type="text" value="" name="drawing[attach_name][]" class="validate[required]" id="attach_name_<?php echo $row_id;?>">
	</td>
	<td>
		<input type="file" name="drawing[attach_file][]" class="validate[required] input-file" id="attach_file_<?php echo $row_id;?>" />
	</td>
	<td>
		<span class="trash btn btn-danger" detail-id=""><i class="fa fa-trash"></i> Delete</span>
	</td>
</tr>