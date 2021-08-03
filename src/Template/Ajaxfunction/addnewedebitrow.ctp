<tr id="row_id_<?php echo $row_id;?>">
	<td style="width:15%">
		<span id="material_code_<?php echo $row_id;?>" sr_no="<?php echo $sr_no;?>" class="sr_div"><?php echo $sr_no;?></span>
		<input type="hidden" value="<?php echo $sr_no;?>" class="serial_no">
		<input type="hidden" value="<?php echo $row_id;?>" class="row_number">
	</td>
		
	<td style="width:40%">
		<input type="text" name="debit[reason][]"  value="" class="form-control validate[required]"/>
	</td>
	
	<td style="width:15%"> 
		<input type="text" name="debit[quantity][]" value="" class="quantity" data-id="<?php echo $row_id;?>" style="width:100%" id="quantity_<?php echo $row_id;?>"/>
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


