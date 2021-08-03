
<script>
$(document).ready(function(){
	
	// $("body").on("click","#export_csv1",function(){
		// $('#paystructure_form').submit();
	// });
	
	});
</script>

<div class="modal-header" >
    <button type="button" class="close" data-dismiss="modal">X</button>
	<h4 class="modal-title">Expenditure History</h4>
</div>

<div class="modal-body clearfix">
<div class="controls">
	<h6> Employee No: <?php echo $user_data->employee_no ?></h6>
	<h6> Employee Name: <?php echo $this->ERPfunction->get_user_name($user_id);?></h6>
	<h6> Designation : <?php echo $this->ERPfunction->get_category_title($user_data->designation);?></h6>
	<h6> Employee at : <?php  echo $this->ERPfunction->get_user_employee_at($user_id);?></h6>
	<?php 
		echo "<table class='table table-bordered'>";
		echo "<tr>";
				echo "<th rowspan='2'>Expenditure Claim Period</th>";
				echo "<th colspan='5'>Expenditure Claim Amount (Rs.)</th>";
				echo "<th rowspan='2'>Total Amount(RS.)</th>";
				echo "</tr>";
				
				echo "<tr>";
				echo "<th>Travel / Transportation</th>";
				echo "<th>House Rent</th>";
				echo "<th>Mobile Bill</th>";
				echo "<th>Food</th>";
				echo "<th>Other</th>";
				echo "</tr>";
		$i = 1;
		$rows = array();
		$rows[] = array("Expenditure Claim Period","Travel / Transportation","House Rent","Mobile Bill","Food","Other","Total Amount(RS.)");
		

		
		if(!empty($data))
		{
			
			foreach($data as $row)
			{
				
				$csv = array();
				$csv[] = $row['clam_period'];
				$csv[] = $row['travel_charge'];
				$csv[] = $row['house_charge'];
				$csv[] = $row['mobile_charge'];
				$csv[] = $row['food_charge'];
				$csv[] = $row['other_charge'];
				$csv[] = $row['total_amount'];
				$rows[] = $csv;
				echo "<tr>
					<td>{$row['clam_period']}</td>
					<td>{$row['travel_charge']}</td>
					<td>{$row['house_charge']}</td>
					<td>{$row['mobile_charge']}</td>
					<td>{$row['food_charge']}</td>
					<td>{$row['other_charge']}</td>
					<td>{$row['total_amount']}</td>";
					
			}
		}
			echo "</table>";
	
	?>
	
</div>
</div>
<div class="modal-footer">	
	<div class="col-md-4">
	<!-- <form method="post" action="<?php echo $this->request->base;?>/Humanresource/expenditurehistory/"> -->
		<?php echo $this->Form->Create('',['method'=>'post','url'=>['controller'=> 'Humanresource','action'=>'expenditurehistory']]);?>

		<input type="hidden" name="rows" value='<?php echo base64_encode(serialize($rows));?>'>
		<input type="submit" class="btn btn-success" value="Export To Excel" name="export_csv">
	<?php 
		echo $this->Form->end();
	?>
	</div>
	<div class="col-md-4">
		<a href="<?php echo $this->request->base;?>/Humanresource/printexpenditure/<?php echo $user_id; ?>" class="btn btn-primary" id="print_this" target="_blank"><i class="icon-print"></i> Print</a>
	</div>
	<button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>	
</div>