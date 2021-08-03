<script>
$(document).ready(function(){
	
	// $("body").on("click","#export_csv1",function(){
		// $('#paystructure_form').submit();
	// });
	
	});
</script>

<div class="modal-header" >
    <button type="button" class="close" data-dismiss="modal">X</button>
	<h4 class="modal-title">Loan Structure History</h4>
</div>
<div class="modal-body clearfix">
<div class="controls">
	<!--<h6> Employee Name: <?php // echo $this->ERPfunction->get_user_name($user_id);?></h6> -->
	<?php	
	echo "<table class='table table-bordered'>";
	echo "<tr>";
	echo "<th>#</th>";
	echo "<th>Pay Amount Month</th>";	
	
	echo "<th>Paid Amount</th>";	
	echo "<th>Created Date.</th>";	
	echo "<th>Date.</th>";	
	echo "</tr>";
	$i=0;
	
	if(!empty($history))
	{
		foreach($history as $data1)
		{
				$year = $data1['erp_loan_pay_history']['salarey_pay_year'];
			
				echo "<tr>";
				echo "<td>{$i}</td>
					  <td>".$this->ERPFunction->getMonth($data1['erp_loan_pay_history']['salarey_pay_month']).' '.$year."</td>
					
					  <td>{$data1['erp_loan_pay_history']['paid_amount']}</td>
					  <td>".date('d-m-Y',strtotime($data1['erp_loan_pay_history']['created_date']))."</td>
					   <td>".date('d-m-Y',strtotime($data1['erp_loan_pay_history']['date']))."</td>
					  ";
				echo "</tr>";
				$i++;
			
		}
	}
	echo "</table>";
	
	?>
	
</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>	
</div>