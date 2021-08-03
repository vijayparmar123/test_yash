<tr id="row_id_<?php echo $row_id;?>">
	
	<td><span id="material_code_<?php echo $row_id;?>"></span>
	
	</td>
	<td>
		<select class="select2 material_id" style="width: 100%;" name="material[material_id][]" id="material_id_<?php echo $row_id;?>" data-id="<?php echo $row_id;?>">
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
	<input type="hidden" value="<?php echo $row_id; ?>" name="row_number" class="row_number">
	<span id="unit_name_<?php echo $row_id;?>"></span>
	</td>
			
	<td><input type="text" name="material[consumption][]" class="form-control validate[required,custom[number]]" id="consumption_<?php echo $row_id; ?>"></td>
	
	<td>
		<span class="trash btn btn-danger" data-id="<?php echo $row_id; ?>"><i class="fa fa-trash"></i> <span class="icon-trash"></span></span>
	</td>
</tr>


