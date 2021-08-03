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
											<select class="select2"  required="true"   name="material[brand_id][]" style="width: 100%;" id="brand_id_<?php echo $row_id;?>">
												<option value="">--Select Item--</Option>												
											</select>
										</td>
										<td> <input type="text" name="material[quantity][]"  id="quantity_<?php echo $row_id;?>" class="vendor_quentity" data-id="<?php echo $row_id;?>"  /></td>
										<td><input type="text" style="padding-left:0;padding-right:0;min-width:53px;" name="material[actual_qty][]" value="" data-id="<?php echo $row_id;?>" id="actual_qty_<?php echo $row_id;?>" class="actualy_qty validate[required]" /></td>
										<td><input type="text" name="material[difference_qty][]" readonly = "true" value="" id="difference_qty_<?php echo $row_id;?>"/></td>
										
										<td><span id="unit_name_<?php echo $row_id;?>"></span></td>
										<td><input type="text" name="material[remark][]" value="" id="remark_<?php echo $row_id;?>"/></td>
										<td>
											<a href="javascript:void(0)" class="btn btn-danger del_item" title="Delete">Delete</a>
										</td>
									</tr>