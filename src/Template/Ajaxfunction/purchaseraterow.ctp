<tr id="row_id_<?php echo $row_id;?>">
		
			<td>
		<select class="select2 material_id" style="width:130px;" name="material[material_id][]" id="material_id_<?php echo $row_id;?>" data-id="<?php echo $row_id;?>">
			<option value="">Select Material</Option>
			<?php 
			   foreach($material_list as $retrive_data)
			   {
					 echo '<option value="'.$retrive_data['material_id'].'">'.
					 $retrive_data['material_title'].'</option>';
			   }
			?>
		</select>
			</td>
			<td><input type="text" name="material[hsn_code][]" value="" class="hsn_code" data-id="<?php echo $row_id;?>" id="hsn_code_<?php echo $row_id;?>" style="width:150px" /></td>
			<td>
			<select class="select2"  required="true"   name="material[brand_id][]" style="width:130px;" id="brand_id_<?php echo $row_id;?>">
			<option value="">Select Item</Option>												
			</select>
			</td>
			
			<td>
			<span id="unit_name_<?php echo $row_id;?>"></span>
			<input type="hidden" value="<?php echo $row_id;?>" name="row_number" class="row_number">
			</td>
			<td><input type="text" name="material[unit_rate][]" value="0" class="unit_rate" value="" data-id="<?php echo $row_id;?>" id="unit_rate_<?php echo $row_id;?>" style="width:80px" /></td>
			<td><input type="text" name="material[discount][]" value="0" class="tx_count" id="dc_<?php echo $row_id;?>" data-id="<?php echo $row_id;?>" style="width:55px"></td>
			<td><input type="text" name="material[transportation][]" value="0"  class="tx_count" id="tr_<?php echo $row_id;?>" data-id="<?php echo $row_id;?>" style="width:55px"></td>
			<td><input type="text" name="material[gst][]" class="tx_count" value="0" id="gst_<?php echo $row_id;?>"  data-id="<?php echo $row_id;?>" style="width:55px"></td>
			<td><input type="text" name="material[other_tax][]" class="tx_count" value="0" id="other_tax_<?php echo $row_id;?>"  data-id="<?php echo $row_id;?>" style="width:55px"></td>
			<td><input type="text" name="material[final_rate][]" value="0" class="final_rate" id="final_rate_<?php echo $row_id;?>" style="width:90px" /></td>
			
			<td><a href="#" class="btn btn-danger del_parent">Delete</a>
			</td>
		</tr>