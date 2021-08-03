<tr id="row_id_<?php echo $row_id;?>">
	<td>
		<input type="text" name="material[contract_no][]" id="contract_no_<?php echo $row_id;?>" class="contract_no" data-id="<?php echo $row_id;?>" style="width:130px;">
		<input type="hidden" value="<?php echo $row_id;?>" name="row_number" class="row_number">
	</td>
	<td>
		<select class="select2 material_name" required="true" style="width: 100%;" name="material[material_name][]" data-id="<?php echo $row_id;?>" id="material_name_<?php echo $row_id;?>">
			<option value="">--Select Option--</option>
			<?php
			foreach($description_options as $key => $retrive_data)
			{ 
				echo '<option value="'.$retrive_data['cat_id'].'">'.$retrive_data['category_title'].'</option>';
			}
		?>
		</select>
	</td>
	<td> 
		<input type="text" name="material[detail_description][]" style="width:200px;" value="" class="detail_description" data-id="<?php echo $row_id;?>" id="detail_description_<?php echo $row_id;?>"/>
	</td>
	<td> 
		<input type="text" name="material[quantity_this_wo][]" style="width:80px;" value="" class="quantity_this_wo" data-id="<?php echo $row_id;?>" id="quantity_this_wo_<?php echo $row_id;?>"/>
	</td>
	<td> 
		<input type="text" name="material[quantity_previous_wo][]" style="width:80px;" value="0" readonly="true" class="quantity_previous_wo" data-id="<?php echo $row_id;?>" id="quantity_previous_wo_<?php echo $row_id;?>"/>
	</td>
	<td> 
		<input type="text" name="material[quantity_till_date][]" style="width:80px;" value="" readonly="true" class="quantity_till_date" data-id="<?php echo $row_id;?>" id="quantity_till_date_<?php echo $row_id;?>"/>
	</td>
	<td>
		<input type="text" value="" name="material[unit][]" readonly="true" id="unit_<?php echo $row_id;?>" class="form-control" style="width:80px;">
	</td>
	<td>
		<input type="text" name="material[unit_rate][]" class="unit_rate" value="0" data-id="<?php echo $row_id;?>" id="unit_rate_<?php echo $row_id;?>" style="width:80px" />
	</td>
	<td>
		<input type="text" name="material[amount][]" value="0" class="amount" id="amount_<?php echo $row_id;?>" style="width:90px" />
	</td>
	<td>
		<input type="text" name="material[amount_till_date][]" value="0" class="amount_till_date" id="amount_till_date_<?php echo $row_id;?>" style="width:90px" />
	</td>
	<td>
        <a href="javascript:void(0)" class="btn btn-primary add_parent" detail-id="<?php echo $row_id; ?>">Add</a>
		<a href="#" class="btn btn-danger del_parent">Delete</a>
	</td>
</tr>