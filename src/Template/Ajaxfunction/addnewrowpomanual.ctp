<tr id="row_id_<?php echo $row_id;?>" class='row_id' data-id="<?php echo $row_id;?>">
		<?php 
		if($row_type == 'dropdown')
		{
		?>
			<td>
				<select class="select2 material_id" style="width:180px;" name="material[material_id][]" id="material_id_<?php echo $row_id;?>" data-id="<?php echo $row_id;?>">
					<option value="">Select Material</Option>
					<?php 
						foreach($material_list as $retrive_data) {
								echo '<option value="'.$retrive_data['material_id'].'">'.
								$retrive_data['material_title'].'</option>';
						}
					?>
				</select>
				<input type="text" placeholder="Description Here" class="desc_textfield" name="material[description][]" value="" id="descriptionTextfield_<?php echo $row_id ?>" style="display:none;">
			</td>
			<!--<td>
			<input type="text" value="" name="material[hsn_code][]" id="hsn_code_<?php echo $row_id;?>" class="hsn_code" style="width:120px;">
			</td>-->
			<td>
			<select class="select2 brand_id"  required="true" data-id="<?php echo $row_id;?>" name="material[brand_id][]" style="width:130px;" id="brand_id_<?php echo $row_id;?>">
			<option value="">Select Item</Option>												
			</select>
			</td>
		<?php
		}
		else if($row_type == 'textfield')
		{
		?>
			<td>
			<input type="text" name="material[material_id][]" style="width:180px;" class="form-control material_id" id="material_id_<?php echo $row_id;?>" data-id="<?php echo $row_id;?>">
			</td>
			<!--<td>
			<input type="text" value="" name="material[hsn_code][]" id="hsn_code_<?php echo $row_id;?>" class="hsn_code" style="width:120px;">
			</td>-->
			<td>
			<input type="text" name="material[brand_id][]" style="width:130px;" class="form-control" required="true" id="brand_id_<?php echo $row_id;?>">
			</td>
		<?php
		}
		else
		{
		}
		?>
			
			<td>
			<input type="hidden" value="<?php echo $row_id; ?>" name="row_number" class="row_number">
			<input type="hidden" value="1" name="material[is_custom][]">
			<input type="text" name="material[quantity][]" value="" data-id="<?php echo $row_id;?>" class="quantity" id="quantity_<?php echo $row_id;?>"/></td>
			<?php 
			if($row_type == 'dropdown')
			{
			?>
			<td>
			<span class="unit_name" id="unit_name_<?php echo $row_id;?>"></span>
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
			<td><input type="text" name="material[unit_rate][]" class="unit_rate" value="" data-id="<?php echo $row_id;?>" id="unit_rate_<?php echo $row_id;?>" style="width:80px" /></td>
			<td><input type="text" name="material[discount][]" value="0" class="tx_count" id="dc_<?php echo $row_id;?>" data-id="<?php echo $row_id;?>" style="width:55px"></td>
			<!-- <td><input type="text" name="material[transportation][]" value="0"  class="tx_count" id="tr_<?php echo $row_id;?>" data-id="<?php echo $row_id;?>" style="width:55px"></td>
			<td><input type="text" name="material[exice][]" class="tx_count" value="0" id="ex_<?php echo $row_id;?>"  data-id="<?php echo $row_id;?>" style="width:55px"></td>
			<td><input type="text" name="material[other_tax][]" class="tx_count" value="0" id="other_tax_<?php echo $row_id;?>"  data-id="<?php echo $row_id;?>" style="width:55px"></td> -->
			<td><input type="text" name="material[gst][]" class="tx_count" value="0" id="gst_<?php echo $row_id; ?>"  data-id="<?php echo $row_id; ?>" style="width:55px"></td>
			<td><input type="text" name="material[amount][]" value="0" class="amount" id="amount_<?php echo $row_id;?>" style="width:90px" /></td>
			<td><input type="text" name="material[single_amount][]" value="0" class="single_amount" id="single_amount_<?php echo $row_id;?>" style="width:90px"/></td>
			<td>
			<a href="javascript:void(0)" class="btn btn-primary add_textfield" onClick="insertRow(<?php echo $row_id; ?>)" id="textfield_<?php echo $row_id; ?>" value="textfield">Textfield</a>
				<a href="#" class="btn btn-danger del_parent">Delete</a>
				<input type="hidden" name="pr_mid[]" value="">
			</td>
		</tr>