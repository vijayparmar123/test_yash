<tr id="row_id_<?php echo $row_id;?>">
<input type='hidden' value='<?php echo $row_id;?>' name='row_number' class='row_number'>
	<td><span id="material_code_<?php echo $row_id;?>"></span></td>
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
	<td><span id="opening_stock_<?php echo $row_id;?>"></span></td>										
	<td><input type="text" name="material[quantity][]" id="quantity_<?php echo $row_id;?>" value="" class="form-control change_bal validate[required]" row="<?php echo $row_id;?>" /></td>
	<td><input type="text" name="material[balance][]" id="balance_<?php echo $row_id;?>" value="" style="padding:0;" class="form-control" readonly /></td>										
	<td><span id="unit_name_<?php echo $row_id;?>"></span></td>
	<td><input type="text" name="material[name_of_foreman][]" value="" class="form-control" id="name_of_foreman_<?php echo $row_id;?>"/></td>
	<td><input type="text" name="material[time_issue][]" value="" class="form-control" id="time_issue_<?php echo $row_id;?>"/></td>
	<td>
		<span class="trash btn btn-danger" data-id="<?php echo $row_id;?>"><i class="icon-trash"></i></span>
	</td>
</tr>


