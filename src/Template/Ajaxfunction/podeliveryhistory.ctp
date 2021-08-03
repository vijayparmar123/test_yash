<div class="modal-header" >
    <button type="button" class="close" data-dismiss="modal">X</button>
	<h4 class="modal-title"> PO Delivery History </h4>
</div>
<div class="modal-body clearfix">
<div class="controls">

<table id="issued_list"  class="dataTables_wrapper table">
	<thead>
		<th>GRN No</th>
		<th>Date</th>
		<th>Quantity</th>
		<th>Action</th>
	</thead>
	<tbody>
	<?php 
		foreach($grn_data as $retrive_data)
		{ 
		$grn_approved = $this->ERPfunction->check_grn_material_approved($retrive_data['grndetail_id']);
	?>
			<tr>
				<td><?php echo $retrive_data["erp_inventory_grn"]['grn_no']; ?></td> 
				<td><?php echo $this->ERPfunction->get_date($retrive_data["erp_inventory_grn"]['grn_date']); ?></td> 
				<td><?php echo $retrive_data['actual_qty']; ?></td> 
				<td>
				<?php if($grn_approved){ ?>
				<a href='../inventory/previewapprovedgrn/<?php echo $retrive_data["erp_inventory_grn"]['grn_id']; ?>' target='_blank' class='btn btn-primary btn-clean'><i class='icon-pencil'></i> View</a>
				<?php }else{ ?>
				<a href='../inventory/previewgrn/<?php echo $retrive_data["erp_inventory_grn"]['grn_id']; ?>' target='_blank' class='btn btn-primary btn-clean'><i class='icon-pencil'></i> View</a>
				<?php } ?>
				</td> 
			</tr>
	<?php } ?>
	
	<?php 
		foreach($manual_data as $retrive_data)
		{ 
	?>
			<tr>
				<td><?php echo "Manual Entry"; ?></td> 
				<td><?php echo $this->ERPfunction->get_date($retrive_data['received_date']); ?></td> 
				<td><?php echo $retrive_data['received_qty']; ?></td> 
				<td></td> 
			</tr>
	<?php } ?>
	</tbody>
</table>			
 
</div>
</div>
<div class="modal-footer">	
	<button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>	
</div>