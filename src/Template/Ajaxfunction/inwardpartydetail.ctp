<?php 
	$i  = 0;
	foreach($party_data as $data)
	{
?>
	<tr id="row_<?php echo $i; ?>">
		<td>
			<?php echo $data['inward_bill_no']; ?>
			<input type="hidden" name="inward[bill_no][]" value="<?php echo $data['inward_bill_no']; ?>">
			<input type="hidden" name="inward[inward_bill_id][]" value="<?php echo $data['inward_bill_id']; ?>">
		</td>
		
		<td>
			<?php echo $data['invoice_no']; ?>
			<input type="hidden" name="inward[invoice_no][]" value="<?php echo $data['invoice_no']; ?>">
		</td>
		
		<td>
			<?php echo date("d-m-Y",strtotime($data['bill_date'])); ?>
			<input type="hidden" name="inward[bill_date][]" value="<?php echo $data['bill_date']; ?>">
		</td>
		
		<td>
			<?php echo $data['total_amt']; ?>
			<input type="hidden" name="inward[bill_amount][]" value="<?php echo $data['total_amt']; ?>">
		</td>
		
		<td>
			<span class="trash btn btn-danger"><i class="fa fa-trash"></i> Delete</span>
		</td>
	</tr>
<?php
	}
?>