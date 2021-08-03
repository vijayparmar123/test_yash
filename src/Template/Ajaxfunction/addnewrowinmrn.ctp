<tr id="row_id_<?php echo $row_id;?>">
	<input type='hidden' value='<?php echo $row_id;?>' name='row_number' class='row_number'>
	<td><span id="material_code_<?php echo $row_id;?>"></span></td>
	<td>
		<select class="select2 material_id" style="width: 100%;" required="true" name="material[material_id][]" id="material_id_<?php echo $row_id;?>" data-id="<?php echo $row_id;?>">
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
	<td><input type="text" readonly="true" name="material[till_date_qty][]" id="till_date_qty_<?php echo $row_id;?>" value="" class="no-padding form-control"/></td>
	<td><input type="text" name="material[quantity][]" style="padding: 0;" row-id="<?php echo $row_id;?>" id="quantity_<?php echo $row_id;?>" value="" class="form-control validate[required] return_qty"/></td>
	<td><span id="unit_name_<?php echo $row_id;?>"></span></td>
	<td><input type="text" name="material[remarks][]" value="" class="form-control" id="remarks_<?php echo $row_id;?>"/></td>
	<td>
		<span class="trash btn btn-danger" data-id="<?php echo $row_id;?>"><i class="fa fa-trash"></i> Delete</span>
	</td>
</tr>


