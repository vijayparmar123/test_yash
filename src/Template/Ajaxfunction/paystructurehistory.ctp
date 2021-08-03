<script>
$(document).ready(function(){
	
	// $("body").on("click","#export_csv1",function(){
		// $('#paystructure_form').submit();
	// });
	
	});
</script>

<div class="modal-header" >
    <button type="button" class="close" data-dismiss="modal">X</button>
	<h4 class="modal-title">Payment Structure History</h4>
</div>
<div class="modal-body clearfix">
<div class="controls">
	<h6> Employee Name: <?php echo $this->ERPfunction->get_user_name($user_id);?></h6>
	<?php	
	echo "<table class='table table-bordered'>";
	echo "<tr>";
	echo "<th>#</th>";
	echo "<th>Aff. Date</th>";
	//echo "<th>Designation</th>";	
	echo "<th>Pay Type</th>";	
	echo "<th>CTC Month</th>";	
	echo "<th>CTC Year</th>";	
	echo "<th>Account No.</th>";
	echo "<th>Delete</th>";
	echo "</tr>";
	$i = 1;
	
	$rows1 = array();
	// $rows1[] = array("Sr.No.","Aff. Date","Designation","Pay Type","CTC Month","CTC Year","Account No.");
	$rows1[] = array("Sr.No.","Aff. Date","Pay Type","CTC Month","CTC Year","Account No.");
	
	//$csv2 = array();
	$csv2[] = $i;
	if($user_data["is_pay_structure_change"] == 1)
	{
		$csv2[] = date('d-m-Y',strtotime($user_data['change_date']));
	}else{
		$csv2[] = date('d-m-Y',strtotime($user_data['date_of_joining']));
	}
	// $csv2[] = $this->ERPfunction->get_category_title($user_data['designation']);
	$csv2[] = $this->ERPFunction->get_pay_type($user_data['pay_type']);
	$csv2[] = $user_data['total_salary'];
	$csv2[] = $user_data['ctc'];
	$csv2[] = $user_data['ac_no'];
	$rows1[] = $csv2;
	
	echo "<tr>";
	echo "<td>{$i}</td>";
		if($user_data["is_pay_structure_change"] == 1)
		{
			echo "<td>".date('d-m-Y',strtotime($user_data['change_date']))."</td>";
		}else{
			echo "<td>".date('d-m-Y',strtotime($user_data['date_of_joining']))."</td>";
		}
	// echo "<td>{$this->ERPfunction->get_category_title($user_data['designation'])}</td>
	echo "<td>".$this->ERPFunction->get_pay_type($user_data['pay_type'])."</td>
		  <td>{$user_data['total_salary']}</td>
		  <td>{$user_data['ctc']}</td>
		  <td>{$user_data['ac_no']}</td>
		";
	echo "</tr>";
	$i++;
	
	if(!empty($history))
	{
		foreach($history as $data)
		{
			$csv1 = array();
			$csv1[] = $i;
			$csv1[] = ($data['old_date'] != '')?date('d-m-Y',strtotime($data['old_date'])):$data['old_date'];
			// $csv1[] = $this->ERPfunction->get_category_title($data['designation']);
			$csv1[] = $this->ERPFunction->get_pay_type($data['pay_type']);
			$csv1[] = $data['total_salary'];
			$csv1[] = $data['ctc'];
			$csv1[] = $data['ac_no'];
			$rows1[] = $csv1;
			
			echo "<tr>";
			echo "<td>{$i}</td>
				  <td>".date('d-m-Y',strtotime($data['old_date']))."</td>
				  <td>".$this->ERPFunction->get_pay_type($data['pay_type'])."</td>
				  <td>{$data['total_salary']}</td>
				  <td>{$data['ctc']}</td>
				  <td>{$data['ac_no']}</td>	
				   <td><a href='{$this->request->base}/humanresource/deletepaystructure/{$data['user_id']}' class='btn btn-danger btn-clean action-btn'><i class='icon-trash'></i>Delete</a></td>
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
	<div class="col-md-4">
	<!-- <form method="post" id="paystructure_form" action="<?php echo $this->request->base;?>/Humanresource/excelpayhistory"> -->
		<?php echo $this->Form->Create('',['id'=>'paystructure_form','class'=>'form_horizontal formsize','method'=>'post','url'=>['controller'=>'Humanresource','action'=>'excelpayhistory']]);?>
		<input type="hidden" name="payrows" value='<?php echo base64_encode(serialize($rows1));?>'>
		<input type="submit" class="btn btn-success" value="Export To Excel" id="export_csv1" name="export_csv1">
	<?php 
		echo $this->Form->end();
	?>
	</div>
	<div class="col-md-4">
		<a href="<?php echo $this->request->base;?>/Humanresource/printpayhistory/<?php echo $user_id; ?>" class="btn btn-primary" id="print_this" target="_blank"><i class="icon-print"></i> Print</a>
	</div>
	<button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>	
</div>