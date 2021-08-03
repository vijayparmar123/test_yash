<tr id="row_id_<?php echo $row_id;?>">
	<input type='hidden' value='<?php echo $row_id; ?>' name='row_number' class='row_number'>
	<td><span id="material_code_<?php echo $row_id;?>"></span></td>
	<td>
		<select class="select2 material_id" style="width: 100%;" name="material[material_id][]" id="material_id_<?php echo $row_id;?>" row-id="<?php echo $row_id;?>" data-id="<?php echo $row_id;?>">
			<option value="">--Select Material--</Option>
			<?php 
				foreach($material_list as $retrive_data)
				{
					echo '<option value="'.$retrive_data['material_id'].'">'.
					$retrive_data['material_title'].'</option>';
				}
			?>
		</select>
	</td>
	<td>
		<select class="select2" required="true"  name="material[brand_id][]" style="width: 100%;" id="brand_id_<?php echo $row_id;?>">
			<option value="">--Select Item--</Option>												
		</select>
	</td>
	<td><input type="text" name="material[till_date_qty][]" readonly="true" id="till_date_qty_<?php echo $row_id;?>" value="" class="no-padding form-control"/></td>
	<td><input type="text" name="material[quantity_reurn][]" row-id="<?php echo $row_id;?>" id="quantity_reurn_<?php echo $row_id;?>" value="" class="return_qty no-padding form-control validate[required]"/></td>
	<td><span id="unit_name_<?php echo $row_id;?>"></span></td>
<!-- <td><input type="text" name="material[return_by][]" value="" class="form-control" id="return_by_<?php //echo $row_id;?>"/></td> -->
	<td><input type="text" name="material[name_of_foreman][]" value="" class="form-control" id="name_of_foreman_<?php echo $row_id;?>"/></td>
	<td><input type="text" name="material[time_of_return][]" value="" class="form-control" id="time_of_return_<?php echo $row_id;?>"/></td>
<!-- <td><input type="text" name="material[return_reason][]" value="" class="form-control" id="return_reason_<?php // echo $row_id;?>"/></td> -->
	<td>
		<span class="trash btn btn-danger" data-id="<?php echo $row_id;?>"><i class="fa fa-trash"></i> Delete</span>
	</td>
</tr>


