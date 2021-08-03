<td>
	<input type="hidden" name="material[wo_detail_id][]" value="<?php echo $data['wo_detail_id']; ?>">
	<input type="text" readonly name="material[contract_no][]" value="<?php echo htmlspecialchars($data['contract_no']); ?>" id="contract_no_<?php echo $data['wo_detail_id']; ?>" class="contract_no validate[required]" data-id="<?php echo $data['wo_detail_id']; ?>" style="width:130px;">
	<input type="hidden" value="<?php echo $data['wo_detail_id']; ?>" name="row_number" class="row_number">
</td>			
<td>
	<input type="text" readonly style="width:180px;" value="<?php echo $this->ERPfunction->get_category_title($data['material_name']) ; ?>">
	<input type="hidden" style="width:180px;" name="material[material_name][]" value="<?php echo $data['material_name'] ; ?>">
</td>			
<td> 
	<input type="text" readonly name="material[detail_description][]" style="width:200px;" value="<?php echo $data['detail_description']; ?>" class="detail_description validate[required]" data-id="0" id="detail_description_<?php echo $data['wo_detail_id']; ?>"/>
</td>
<td> 
	<input type="text" readonly name="material[quantity_this_wo][]" value="<?php echo htmlspecialchars($data['quentity']); ?>" class="quantity_this_wo validate[required]" data-id="<?php echo $data['wo_detail_id']; ?>" id="quantity_this_wo_<?php echo $data['wo_detail_id']; ?>"/>
</td>

<td> 
	<input type="text" readonly name="material[quantity_previous_wo][]" style="width:80px;" value="<?php echo htmlspecialchars($data['quantity_upto_previous']); ?>" class="quantity_previous_wo validate[required]" data-id="0" id="quantity_previous_wo_<?php echo $data['wo_detail_id']; ?>"/>
</td>
<td> 
	<input type="text" readonly name="material[quantity_till_date][]" style="width:80px;" value="<?php echo htmlspecialchars($data['till_date_quantity']); ?>" class="quantity_till_date validate[required]" data-id="0" id="quantity_till_date_<?php echo $data['wo_detail_id']; ?>"/>
</td>		
<td>
	<input type="text" readonly name="material[unit][]" readonly="true" value="<?php echo htmlspecialchars($data['unit']); ?>" id="unit_<?php echo $data['wo_detail_id']; ?>" class="form-control validate[required]" style="width:80px;">
</td>	
<td>
	<input type="text" readonly name="material[unit_rate][]" value="<?php echo $data['unit_rate']; ?>" class="unit_rate" data-id="<?php echo $data['wo_detail_id']; ?>" id="unit_rate_<?php echo $data['wo_detail_id']; ?>" style="width:80px" />
</td>		
<td>
	<input type="text" readonly name="material[amount][]" value="<?php echo $data['amount']; ?>" class="amount validate[required]" id="amount_<?php echo $data['wo_detail_id']; ?>" style="width:90px" />
</td>
<td>
	<input type="text" readonly name="material[amount_till_date][]" value="<?php echo $data['amount_till_date']; ?>" class="amount_till_date validate[required]" id="amount_till_date_<?php echo $data['wo_detail_id']; ?>" style="width:90px" />
</td>	
<td>
	<a href="javascript:void(0)" class="btn btn-primary edit_parent" detail-id="<?php echo $data['wo_detail_id']; ?>" >Edit</a>
	<a href="#" class="btn btn-danger del_parent" detail-id="<?php echo $data['wo_detail_id']; ?>" >Delete</a>
</td>