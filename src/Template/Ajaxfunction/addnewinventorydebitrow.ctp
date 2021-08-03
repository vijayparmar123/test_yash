<tr id="row_id_<?php echo $row_id;?>">
	<td style="width:15%">
		<span id="material_code_<?php echo $row_id;?>" sr_no="<?php echo $sr_no;?>" class="sr_div"><?php echo $sr_no;?></span>
		<input type="hidden" value="<?php echo $sr_no;?>" class="serial_no">
		<input type="hidden" value="<?php echo $row_id;?>" class="row_number">
	</td>
		
	<td>
		<select class="select2 material_id" style="width: 100%;" name="debit[material_id][]" id="material_id_<?php echo $row_id;?>" data-id="<?php echo $row_id;?>">
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
	<td><input type="text" readonly="true" name="debit[till_date_qty][]" id="till_date_qty_<?php echo $row_id;?>" value="" class="no-padding form-control"/></td>
	<td style="width:15%"> 
		<input type="text" name="debit[quantity][]" value="" class="quantity validate[required]" data-id="<?php echo $row_id;?>" style="width:100%" id="quantity_<?php echo $row_id;?>"/>
	</td>
	<td style="width:10%">
		<span id="unit_<?php echo $row_id; ?>"></span>
	</td>
	<td style="width:15%">
		<input type="text" name="debit[rate][]" class="rate" value="" data-id="<?php echo $row_id;?>" id="rate_<?php echo $row_id;?>" style="width:100%" />
	</td>
	
	<td style="width:15%">
		<input type="text" name="debit[single_amount][]" value="0" class="single_amount amount_txt" id="single_amount_<?php echo $row_id;?>" style="width:100%"/></td>
	
	<td>
		<a href="#" class="btn btn-danger del_parent">Delete</a>
	</td>
</tr>


