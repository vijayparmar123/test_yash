<div class="modal-header" >
    <button type="button" class="close" data-dismiss="modal">X</button>
	<h4 class="modal-title">Payment History</h4>
</div>
<div class="modal-body clearfix">
<div class="controls">
	<h6> Employee Name: <?php echo $this->ERPfunction->get_user_name($user_id);?></h6>
	<?php 
	if(!empty($data))
	{
		echo "<table class='table table-bordered'>";
		echo "<tr>";
		echo "<th>#</th>";
		echo "<th>Date</th>";
		echo "<th>Net Pay</th>";	
		echo "</tr>";
		$i=1;
		foreach($data as $row)
		{
			echo "<tr>
			<td>{$i}</td>
			<td>{$row['created_date']}</td>
			<td>{$row['net_pay']}</td>
			</tr>";
			$i++;
		}
			echo "</table>";
	}else{		
		echo "No Payment Record found!";
	}	
	?>
	
</div>
</div>
<div class="modal-footer">	
	<button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>	
</div>