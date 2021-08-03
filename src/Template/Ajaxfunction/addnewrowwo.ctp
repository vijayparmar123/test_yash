<tr id="row_id_<?php echo $row_id;?>">
	<td>
		<input type="text" name="material[contract_no][]" id="contract_no_<?php echo $row_id;?>" class="contract_no" data-id="<?php echo $row_id;?>" style="width:130px;">
		<input type="hidden" value="<?php echo $row_id;?>" name="row_number" class="row_number">
	</td>
	
	<td>
		<select class="select2 work_head" style="width:150px;" name="material[work_head][]" id="work_head_<?php echo $row_id;?>" data-id="<?php echo $row_id;?>">
			<option value="">Select Work Head</Option>
			<?php 
			   foreach($work_head_list as $retrive_data)
			   {
					 echo '<option value="'.$retrive_data['work_head_id'].'">'.
					 $retrive_data['work_head_title'].'</option>';
			   }
			?>
		</select>
	</td>
	
	<td>
		<input type="text" name="material[material_name][]" id="material_name_<?php echo $row_id;?>" class="material_name" style="width:120px;">
	</td>
	
	<td> 
		<input type="text" name="material[quantity][]" value="" class="quantity" data-id="<?php echo $row_id;?>" id="quantity_<?php echo $row_id;?>"/>
	</td>
	
	<td>
		<input type="text" value="" name="material[unit][]" id="unit_<?php echo $row_id;?>" class="form-control" style="width:80px;">
	</td>
	
	<td>
		<input type="text" name="material[unit_rate][]" class="unit_rate" value="0" data-id="<?php echo $row_id;?>" id="unit_rate_<?php echo $row_id;?>" style="width:80px" />
	</td>
	
	<td><input type="text" name="material[discount][]" value="0" class="tx_count" id="dc_<?php echo $row_id;?>" data-id="<?php echo $row_id;?>" style="width:55px"></td>
			
	<td><input type="text" name="material[cgst][]" value="0" class="tx_count" id="cgst_<?php echo $row_id;?>" data-id="<?php echo $row_id;?>" style="width:55px"></td>
	
	<td><input type="text" name="material[sgst][]" value="0" class="tx_count" id="sgst_<?php echo $row_id;?>" data-id="<?php echo $row_id;?>" style="width:55px"></td>
	
	<td><input type="text" name="material[igst][]" value="0" class="tx_count" id="igst_<?php echo $row_id;?>" data-id="<?php echo $row_id;?>" style="width:55px"></td>
	
	<td>
		<input type="text" name="material[amount][]" value="0" class="amount" id="amount_<?php echo $row_id;?>" style="width:90px" />
	</td>
	
	<td>
		<a href="#" class="btn btn-danger del_parent">Delete</a>
	</td>
</tr>