<tr id="row_id_<?php echo $row_id;?>">
	<?php 
		if($row_type == 'dropdown')
		{
		?>
	<td><span id="material_code_<?php echo $row_id;?>"></span>
	<input type="hidden" value="" name="material[m_code][]" id="m_code_<?php echo $row_id;?>">
	</td>
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
	<?php
		}
		else if($row_type == 'textfield')
		{
		?>
			<td>
			<span id="material_code_<?php echo $row_id;?>"><?php echo $this->ERPfunction->generate_auto_id_prepare_pr($last_code); ?></span>
			<input type="hidden" value="<?php echo $this->ERPfunction->generate_auto_id_prepare_pr($last_code); ?>" name="material[m_code][]" class="text_data
			" id="m_code_<?php echo $row_id;?>">
			<input type="hidden" value="1" name="is_custom">
			</td>
			<td>
			<input type="text" name="material[material_id][]" style="width:100%;" class="form-control material_id" id="material_id_<?php echo $row_id;?>" data-id="<?php echo $row_id;?>">
			</td>
			<td>
			<input type="text" name="material[brand_id][]" style="width:130px;" class="form-control" required="true" id="brand_id_<?php echo $row_id;?>">
			</td>
		<?php
		}
		else
		{
		}
		?>
	<td><input type="text" id="current_balance_<?php echo $row_id; ?>" class="form-control" style="padding: 0;width: 52px;" readonly="true"/></td>
	<td><input type="text" id="min_stock_level_<?php echo $row_id; ?>" class="form-control" style="padding: 0;width: 52px;" readonly="true"/></td>
	<td>
	<input type="hidden" value="<?php echo $row_id; ?>" name="row_number" class="row_number">
	<input type="text" name="material[quantity][]" id="quantity_<?php echo $row_id;?>" value="" class="form-control" style="padding: 0;width: 52px;"/></td>
			<?php 
			if($row_type == 'dropdown')
			{
			?>
			<td>
			<span id="unit_name_<?php echo $row_id;?>"></span>
			<input type="hidden" value="" name="material[static_unit][]" id="static_unit_<?php echo $row_id;?>" class="form-control" style="width:80px;">
			</td>
			<?php
			}
			else if($row_type == 'textfield')
			{
			?>
			<td><input type="text" name="material[static_unit][]" id="static_unit_<?php echo $row_id;?>" class="form-control" style="width:80px;"></td>
			<?php
			}
			else
			{
			}
			?>
	<td><input type="text" name="material[delivery_date][]" value="" class="form-control delivery_date" id="delivery_date_<?php echo $row_id;?>" style="padding: 0;width: 67px;"/></td>
	<td style="padding: 2px;"><input type="text" name="material[name_of_subcontractor][]" value="" class="form-control" id="name_of_subcontractor_<?php echo $row_id;?>" style="padding: 0;min-width: 53px;" /></td>
	<td style="padding: 2px;"><input type="text" name="material[usage][]" value="" class="form-control" id="usage_<?php echo $row_id;?>" style="padding: 0;min-width: 53px;" /></td>
	<td>
		<span class="trash btn btn-danger" data-id="<?php echo $row_id;?>"><i class="fa fa-trash"></i> Delete</span>
	</td>
</tr>


