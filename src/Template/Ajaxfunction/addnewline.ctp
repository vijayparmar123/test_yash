<tr id="row_id_<?php echo $row_id;?>">
	<td><span id="material_code_<?php echo $row_id;?>"></span>
	<input type="hidden" value="<?php echo $row_id;?>" name="row_number" class="row_number">
	</td>
	<td>
		<select class="select2 material_id" style="width: 100%;" name="agency[agency_id][]" id="material_id_<?php echo $row_id;?>" data-id="<?php echo $row_id;?>">
			<option value="">--Select Vendor--</Option>
			<?php 
				foreach($vendor_list as $retrive_data)
				{
					echo '<option value="'.$retrive_data['user_id'].'">'.
					$retrive_data['vendor_name'].'</option>';
				}
			?>
		</select>
	</td>
	<td>
		<input type="text" name="agency[labors][]" id="quantity_<?php echo $row_id;?>" value="" class="form-control"/>
	</td>
	
	
	
	<td>
		<input type="text" name="agency[advance_rs][]" id="quantity_<?php echo $row_id;?>" value="" class="form-control"/>
	</td>
	<td>
		<span class="trash btn btn-danger" data-id="<?php echo $row_id;?>"><i class="fa fa-trash"></i> Delete</span>
	</td>
</tr>


