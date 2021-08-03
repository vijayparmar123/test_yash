<tr id="row_id_<?php echo $row_id;?>">
	<td><span id="material_code_<?php echo $row_id;?>" class="sr_div"><?php  echo $sr_no;?></span>
	<input type="hidden" value="<?php  echo $sr_no;?>" class="serial_no">
	<input type="hidden" value="<?php echo $row_id;?>" class="row_number">
	</td>
	<td>
		<input type="text" name="expense[description][]" value="" class="form-control"/>
	</td>
	
	<td>
		<input type="text" name="expense[amount][]" id="amount_value_<?php echo $row_id;?>" value=""  class="form-control amount_txt"/>
	</td>
	<td><a href="#" class="btn btn-danger del_parent">Delete</a>
</tr>


