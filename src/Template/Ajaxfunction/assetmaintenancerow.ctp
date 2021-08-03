<tr id="row_id_<?php echo $row_id;?>">
	<td>
		<input type="hidden" value="<?php echo $row_id;?>" name="row_number" class="row_number">
		<input type="text" id="material_<?php echo $row_id;?>" data-id="<?php echo $row_id;?>" name="description[material][]" class="form-control validate[required]"/>
	</td>
	<td>
		<input type="text" id="quantity_<?php echo $row_id;?>" data-id="<?php echo $row_id;?>" name="description[quantity][]" class="form-control validate[required,custom[number]] quantity"/>
	</td>
	<td>
		<input type="text" id="unit_<?php echo $row_id;?>" name="description[unit][]" data-id="<?php echo $row_id;?>" class="form-control validate[required]"/>
	</td>
	<td>
		<input type="text" name="description[rate][]" data-id="<?php echo $row_id;?>" id="rate_<?php echo $row_id;?>" class="form-control validate[required,custom[number]] rate"/>
	</td>
	<td>
		<input type="text" id="gst_<?php echo $row_id;?>" data-id="<?php echo $row_id;?>" name="description[gst][]" class="form-control validate[required,custom[number]] gst"/>
	</td>
	<td>
		<input type="text" id="amount_<?php echo $row_id;?>" data-id="<?php echo $row_id;?>" name="description[amount][]" class="form-control validate[required,custom[number]] amount"/>
	</td>
	<td>
		<span class="trash btn btn-danger" data-id="<?php echo $row_id;?>"><i class="fa fa-trash"></i> Delete</span>
	</td>
</tr>