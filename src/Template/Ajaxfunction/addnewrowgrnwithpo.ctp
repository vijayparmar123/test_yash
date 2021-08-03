<tr id="row_id_<?php echo $row_id;?>">
	<td><span id="material_code_<?php echo $row_id;?>"></span>
	<input type="hidden" value="<?php echo $row_id;?>" name="row_number" class="row_number">
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
		<select class="select2"  required="true"   name="material[brand_id][]" style="width: 100%;" data-id="<?php echo $row_id;?>" id="brand_id_<?php echo $row_id;?>">
			<option value="">--Select Item--</Option>												
		</select>
	</td>
	<td> <input style="padding-left:0;padding-right:0" type="text" data-id="<?php echo $row_id;?>" name="material[grn_remain_qty][]" readonly="true" value="0" class="validate[required,min[0]]" /></td>
	<td> <input style="padding-left:0;padding-right:0" type="text" name="material[quantity][]" data-id="<?php echo $row_id;?>" id="quantity_<?php echo $row_id;?>" class="vendor_quentity validate[required,min[0]]" /></td>
	<td><input style="padding-left:0;padding-right:0" type="text" name="material[actual_qty][]" value="" data-id="<?php echo $row_id;?>" id="actual_qty_<?php echo $row_id;?>" class="actualy_qty validate[required]"/></td>
	<td><input style="padding-left:0;padding-right:0" type="text" name="material[difference_qty][]" data-id="<?php echo $row_id;?>" readonly="true" value="" id="difference_qty_<?php echo $row_id;?>"/></td>
	<td>
	<span id="unit_name_<?php echo $row_id;?>"></span>
	 <input type="hidden" name="po_mid[]" value="0">
	 <input type="hidden" name="material[total_qty][]" value="0" />
	</td>
	<td> <input style="padding-left:0;padding-right:0" type="text" name="material[unit_rate][]" data-id="<?php echo $row_id;?>" value="0" id="unit_price_<?php echo $row_id;?>" class="unit_rate validate[required]" /></td>
	<td> <input style="padding-left:0;padding-right:0" type="text" name="material[discount][]" data-id="<?php echo $row_id;?>" value="0" id="dis_<?php echo $row_id;?>" class="tx_count validate[required]" /></td>
	<td> <input style="padding-left:0;padding-right:0" type="text" name="material[gst][]" data-id="<?php echo $row_id;?>" value="0" id="gst_<?php echo $row_id;?>" class="gst validate[required]" /></td>
	<td> <input style="padding-left:0;padding-right:0" type="text" name="material[amount][]" data-id="<?php echo $row_id;?>" value="0" id="amount_<?php echo $row_id;?>" class="amount validate[required]" /></td>
	<td> <input style="padding-left:0;padding-right:0" type="text" name="material[single_amount][]" data-id="<?php echo $row_id;?>" id="single_amount_<?php echo $row_id;?>" value="0" class="single_amount validate[required]" /></td>
	
	<td><input type="text" name="material[remark][]" value="" data-id="<?php echo $row_id;?>" id="remark_<?php echo $row_id;?>"/></td>
	<td><a href="javascript::void(0)" class="btn btn-danger del_item" title="Delete">Delete</a></td>
	
</tr>
