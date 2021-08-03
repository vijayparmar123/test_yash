<tr id="row_id_<?php echo $row_id; ?>">
	<td>
		<input type="text" name="bill[item_no][]" id="item_no_<?php echo $row_id; ?>" class="item_no validate[required]" data-id="<?php echo $row_id; ?>" style="width:80px;">
		<input type="hidden" value="<?php echo $row_id; ?>" name="row_number" class="row_number">
	</td>

	<td>
		<select class="select2 description" required="true" style="width: 100%;" name="bill[description][]" class="description" data-id="<?php echo $row_id; ?>" id="description_<?php echo $row_id; ?>">
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
		<input type="text" name="bill[unit][]" readonly="true" id="unit_<?php echo $row_id; ?>" class="unit validate[required]" data-id="<?php echo $row_id; ?>" style="width:80px;">
	</td>

	<td> 
		<input type="text" name="bill[quantity_this_bill][]" id="quantity_this_bill_<?php echo $row_id; ?>" class="quantity_this_bill validate[required,custom[number]]" data-id="<?php echo $row_id; ?>" style="width:80px;" value="">
	</td>

	<td>
		<input type="text" name="bill[quantity_previous_bill][]" id="quantity_previous_bill_<?php echo $row_id; ?>" class="quantity_previous_bill validate[required,custom[number]]" data-id="<?php echo $row_id; ?>" style="width:80px;" value="0">
	</td>

	<td>
		<input type="text" name="bill[quantity_till_date][]" readonly="true" id="quantity_till_date_<?php echo $row_id; ?>" class="quantity_till_date validate[required,custom[number]]" data-id="<?php echo $row_id; ?>" style="width:80px;" value="">
	</td>

	<td>
		<input type="number" min="1" name="bill[rate][]" id="rate_<?php echo $row_id; ?>" class="rate validate[required,custom[number]]" data-id="<?php echo $row_id; ?>" style="width:80px;" value="">
	</td>
	
	<td>
		<input type="number" min="1" name="bill[full_rate][]" id="full_rate_<?php echo $row_id; ?>" class="full_rate validate[required,custom[number]]" data-id="<?php echo $row_id; ?>" style="width:80px;" value="">
	</td>

	<td> 
		<input type="text" name="bill[amount_this_bill][]" id="amount_this_bill_<?php echo $row_id; ?>" readonly="true" class="amount_this_bill validate[required,custom[number]]" data-id="<?php echo $row_id; ?>" style="width:80px;" value="">
	</td>

	<td>
		<input type="text" name="bill[amount_previous_bill][]" id="amount_previous_bill_<?php echo $row_id; ?>" class="amount_previous_bill validate[required,custom[number]]" data-id="<?php echo $row_id; ?>" style="width:80px;" value="0">
	</td>

	<td>
		<input type="text" name="bill[amount_till_date][]" id="amount_till_date_<?php echo $row_id; ?>" readonly="true" class="amount_till_date validate[required,custom[number]]" data-id="<?php echo $row_id; ?>" style="width:80px;" value="">
	</td>

	<td>
		<a href="#" class="btn btn-danger del_parent">Delete</a>
	</td>
</tr>