<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('.select2').select2();
 
	jQuery('#maintenance_list').DataTable({responsive: true});
});
</script>
<style>
#ui-datepicker-div{z-index:9999 !important;}
</style>
<div class="modal-header" >
    <button type="button" class="close" data-dismiss="modal">X</button>
	<h4 class="modal-title"> Sale Assets details </h4>
</div>
<div class="modal-body clearfix">
<div class="controls">


<h6> Asset Name: <?php  echo $assetname;   ?></h6>
<table id="maintenance_list"  class="dataTables_wrapper table table-striped ">
	<thead>
		<th>AMO No.</th>
		<th>Maintenance Date</th>
		<th>Expense</th>
		<th>Payment By</th>
		<th>View</th>
	</thead>
	<tbody>
	<?php 
	$i = 1;
		foreach($maintenancedata as $retrive_data)
		{ ?>
			<tr>
				<td><?php  echo $retrive_data['amo_no']; ?> </td> 
				<td><?php echo $this->ERPfunction->get_date($retrive_data['maintenance_date']); ?></td> 
				<td><?php echo  $retrive_data['expense_amount']; ?></td> 
				<td><?php echo $this->ERPfunction->get_payment_method($retrive_data['payment_by']); ?></td> 
				<td><a href='./viewaddmaintenance/<?php echo $retrive_data['maintenace_id'];?>' class="btn btn-info btn-clean"><i class="icon-eye-open"></i> View</a></td>
			</tr>
	<?php
	$i++;
		}
		?>
	</tbody>
</table>	
					
 
</div>
</div>
<div class="modal-footer">	
	<button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>	
</div>