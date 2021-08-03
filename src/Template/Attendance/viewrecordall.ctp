<?php 
use Cake\Routing\Router;
?>

<div class="col-md-10" >
	<?php 
if(!$is_capable)
	{
		$this->ERPfunction->access_deniedmsg();
	}
else
{ ?>
<script>
var nice = false;
$(document).ready(
  function() { 
    nice = $(".content div").niceScroll({railpadding:{top:0,right:0,left:0,bottom:-5}});
  });
</script>
<div class="row">
<div class="col-md-12">
<div class="block block-fill-white">
	<div class="head bg-default bg-light-rtl">
		<h2>View Attendance Records</h2>
		<div class="pull-right">
			<a href="<?php echo $this->request->base;?>/Attendance/attendancealert" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
		</div>
	</div>
	<div class="content list custom-btn-clean" style="overflow-x:auto;min-height: 407px;">
		<br/></br>
<?php 
if(!empty($records))
{
	foreach($records as $record)
	{
?>
	<br/>
	<div class="row">
			<div class="col-sm-3">
				<!--&nbsp;&nbsp;&nbsp;<strong>Employee No. :</strong> <?php echo $record["erp_user"]["employee_no"];?>-->
			</div>
			<div class="col-sm-3">
				<!--<strong>Name :</strong> <?php echo $record["erp_user"]["first_name"];?> <?php echo $record["erp_user"]["last_name"];?>--> 
			</div>
			<div class="col-sm-3">
				<!--<strong>Designation :</strong> <?php echo $this->ERPfunction->get_user_designation($record["erp_user"]["user_id"]);?>-->
			</div>
			<div class="col-sm-3">
			<?php 
			// $month = sprintf("%02s", $month);
			$dateObj   = DateTime::createFromFormat('!m', $month);
			$monthName = $dateObj->format('F'); 
			?>
				<strong>Attendance Of :</strong> <?php echo $monthName;?> / <?php echo date("Y",strtotime($year));?>
			</div>
		</div>		
		
		<?php

		// debug($record);
		$start_date = "01-".$month."-".$year;
		$start_time = strtotime($start_date);
		
		$end_time = strtotime("+1 month", $start_time);
		$dates = array();
		for($i=$start_time; $i<$end_time; $i+=86400)
		{
		   $dates[] = date('Y-m-d D', $i);
		}
		?>
		<div class="row cst_Scroll"> <!--style="overflow-x:scroll"-->
		
		<div class="col-md-12">
		
		<div>
		<table class='table table-bordered'>
		<thead>
		<tr class="active">
		<?php
		// foreach($dates as $date)
		// {
			// $curr_d = date("d");
			// $print_d = date("d",strtotime($date));
			// $highlight = ($curr_d === $print_d) ? "danger" : "";
			// echo "<th rowspan='2' style='border:1px solid !important' class='text-center {$highlight}'>".$print_d."</th>";
		// }
		?>
			<th rowspan="2" class='text-center' style='border:1px solid !important'>Employee No</th>
			<th rowspan="2" class='text-center' style='border:1px solid !important'>PF Slip Ref. No.</th>
			<th rowspan="2" class='text-center' style='border:1px solid !important'>Name of Employee</th>
			<th rowspan="2" class='text-center' style='border:1px solid !important'>Designation</th>
			<th rowspan="2" class='text-center' style='border:1px solid !important'>Employed At</th>
			<th colspan="2" class='text-center' style='border:1px solid !important'>TOTAL</th>
			<th colspan="7" class='text-center' style='border:1px solid !important'>PL</th>
			<th rowspan="2" class='text-center' style='border:1px solid !important'>Payable Days</th>
		</tr>
		<tr>
			<td class="label-success" style='color:#fff;'><strong>&nbsp;P</strong></td>
			<td class="label-danger" style='color:#fff;'><strong>&nbsp;A</strong></td>
			<td>Opening</td>
			<td>New</td>
			<td>Sunday</td>
			<td>H</td>
			<td class="text-center">MAN</td>
			<td>Used</td>
			<td>Remaining</td>
		</tr>
		</thead>		
		<tbody>		
		<tr class="active">
		<?php
		foreach($dates as $date)
		{
			// echo "<td>{$date}</td>";
		}
		?>
		</tr>
		<tr>
		<?php
		
		// foreach($dates as $date)
		// {
			// $date = date("Y-m-d",strtotime($date));
			// $day = date("j",strtotime($date));
			// $user_id = $record["user_id"];			
			
			// switch($record['day_'.$day.''])
			// {
				// CASE "AA" :
					// echo "<td>";
					// echo "<strong><span class='text-warning'>AA</span></strong>";
					// echo "</td>";
				// break;
				// CASE "manual_AA" :
					// echo "<td style='background:#F0AD4E'>";
					// echo "<strong style='color:#eee;'>AA</strong>";
					// echo "</td>";
					// break;
				// CASE "A" :
					// echo "<td>";
					// echo "<strong><span class='text-danger'>&nbsp;A</span></strong>";
					// echo "</td>";
				// break;
				// CASE "manual_A" :
					// echo "<td style='background:#D9534F'>";
					// echo "<strong style='color:#eee;'>&nbsp;A</strong>";
					// echo "</td>";
				// break;
				// CASE "P" :
					// echo "<td>";
					// echo "<strong><span class='text-success'>&nbsp;P</span></strong>";
					// echo "</td>";
				// break;
				// CASE "manual_P" :
					// echo "<td style='background:#5CB85C'>";
					// echo "<strong style='color:#eee;'>&nbsp;P</strong>";
					// echo "</td>";
				// break;
				// CASE "H" :
					// echo "<td>";
					// echo "<strong><span class='text-primary'>&nbsp;H</span></strong>";
					// echo "</td>";
				// break;
				// CASE "manual_H" :
					// echo "<td style='background:#428BCA'>";
					// echo "<strong style='color:#eee;'>&nbsp;H</strong>";
					// echo "</td>";
				// break;
				// CASE "HL" :
					// echo "<td>";
					// echo "<strong><span class='text-info'>P/2</span></strong>";
					// echo "</td>";
				// break;
				// CASE "manual_HL" :
					// echo "<td style='background:#428BCA'>";
					// echo "<strong style='color:#eee;'>P/2</strong>";
					// echo "</td>";
				// break;
			// }
		// }
		?>	
			<td align="center"><?php echo $record["erp_user"]["user_id"] ; ?></td>
			<td align="center"><?php echo $this->ERPfunction->get_user_pf_ref_no($record["erp_user"]["user_id"]); ?></td>
			<td align="center"><?php echo $this->ERPfunction->get_employee_name($record["erp_user"]["user_id"]); ?></td>
			<td align="center"><?php echo $this->ERPfunction->get_user_designation($record["erp_user"]["user_id"]); ?></td>
			<td align="center"><?php echo $this->ERPfunction->get_user_employee_at($record["erp_user"]["user_id"]); ?></td>
			<td align="center"><?php echo $record['total_present'] ; ?></td>
			<td align="center"><?php echo $record['total_absent']; ?></td>
			<td align="center"><?php echo $record['opening_pl'] ; ?></td>
			<td align="center"><?php echo $record['new'] ; ?></td>
			<td align="center"><?php echo $this->ERPfunction->total_sundays_category_wise($this->ERPfunction->get_user_category($record["erp_user"]["user_id"]),$month,$year); ?></td>
			<td align="center"><?php echo $record['total_holidays'] ; ?></td>
			<td align="center"><?php echo $record['man_pl'] ; ?></td>
			<td align="center"><?php echo $record['used_pl'] ; ?></td>
			<td align="center"><?php echo $record['remaining_pl'] ; ?></td>
			<td align="center"><?php echo $record['payable_days'] ; ?></td>
		</tr>
		
		</tbody>
		</table>			
		</div>
		</div>
		<br>
		</div>
		<hr/>
		<?php	
	} /* records foreach ends */
}
?>
<input type="hidden" id="emp_at" value='<?php echo json_encode($emp_at);?>'>
</div>
</div>
</div>
<?php 
} ?>
</div>