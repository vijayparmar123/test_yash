<td>
	<input type="hidden" name="material[wo_detail_id][]" value="<?php echo $data['wo_detail_id']; ?>">
	<input type="text" name="material[contract_no][]" value="<?php echo htmlspecialchars($data['contract_no']); ?>" id="contract_no_<?php echo $data['wo_detail_id']; ?>" class="contract_no " data-id="<?php echo $data['wo_detail_id']; ?>" style="width:130px;">
	<input type="hidden" value="<?php echo $data['wo_detail_id']; ?>" name="row_number" class="row_number">
</td>
<td>
	<select class="select2 material_name" required="true" style="width: 100%;" name="material[material_name][]" data-id="<?php echo $data['wo_detail_id']; ?>" id="material_name_<?php echo $data['wo_detail_id']; ?>">
		<option value="">--Select Option--</option>
		<?php
			foreach($description_options as $key => $retrive_data) { 
				$selected = ($retrive_data['cat_id'] == $data['material_name'])?"selected":"";
				echo '<option value="'.$retrive_data['cat_id'].'"'.$selected.'>'.$retrive_data['category_title'].'</option>';
			}
		?>
	</select>
</td>
		
<td> 
	<input type="text" name="material[detail_description][]" style="width:180px;" value="<?php echo $data['detail_description']; ?>" class="detail_description " data-id="0" id="detail_description_<?php echo $data['wo_detail_id']; ?>"/>
</td>

<td> 
	<input type="text" name="material[quantity_this_wo][]" value="<?php echo htmlspecialchars($data['quentity']); ?>" class="quantity_this_wo" data-id="<?php echo $data['wo_detail_id']; ?>" id="quantity_this_wo_<?php echo $data['wo_detail_id']; ?>"/>
</td>

<td> 
	<input type="text" name="material[quantity_previous_wo][]" style="width:80px;" value="<?php echo htmlspecialchars($data['quantity_upto_previous']); ?>" readonly="true" class="quantity_previous_wo" data-id="0" id="quantity_previous_wo_<?php echo $data['wo_detail_id']; ?>"/>
</td>
<td> 
	<input type="text" name="material[quantity_till_date][]" style="width:80px;" value="<?php echo htmlspecialchars($data['till_date_quantity']); ?>" readonly="true" class="quantity_till_date" data-id="0" id="quantity_till_date_<?php echo $data['wo_detail_id']; ?>"/>
</td>
		
<td>
	<input type="text" name="material[unit][]" readonly="true" value="<?php echo htmlspecialchars($data['unit']); ?>" id="unit_<?php echo $data['wo_detail_id']; ?>" class="form-control" style="width:80px;">
</td>
		
<td>
	<input type="text" name="material[unit_rate][]" value="<?php echo $data['unit_rate']; ?>" class="unit_rate" data-id="<?php echo $data['wo_detail_id']; ?>" id="unit_rate_<?php echo $data['wo_detail_id']; ?>" style="width:80px" />
</td>
		
<td>
	<input type="text" name="material[amount][]" value="<?php echo $data['amount']; ?>" class="amount" id="amount_<?php echo $data['wo_detail_id']; ?>" style="width:90px" />
</td>

<td>
	<input type="text" name="material[amount_till_date][]" value="<?php echo $data['amount_till_date']; ?>" class="amount_till_date" id="amount_till_date_<?php echo $data['wo_detail_id']; ?>" style="width:90px" />
</td>
		
<td>
	<a href="javascript:void(0)" class="btn btn-primary update_parent" detail-id="<?php echo $data['wo_detail_id']; ?>" >Update</a>
	<a href="#" class="btn btn-danger del_parent" detail-id="<?php echo $data['wo_detail_id']; ?>" >Delete</a>
</td>
