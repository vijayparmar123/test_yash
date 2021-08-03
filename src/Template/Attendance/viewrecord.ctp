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
		<h2>Attendance Record</h2>
		<div class="pull-right">
			<a href="<?php echo $this->request->base;?>/Attendance/attendancerecord" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
		</div>
	</div>
	<div class="content list custom-btn-clean" style="overflow-x:auto;min-height: 407px;">
		<br/></br>
		<?php 
		foreach($record as $retrive_data)
		{
			$month = $retrive_data["month"];
			$year = $retrive_data["year"];
			$user_id = $retrive_data["user_id"];
			
		?>
		<div class="row">
			<div class="col-sm-3">
				&nbsp;&nbsp;&nbsp;<strong>Employee No. :</strong> <?php echo $this->ERPfunction->get_user_identy_number($user_id); ?>
			</div>
			<div class="col-sm-3">
				<strong>Name :</strong> <?php echo $this->ERPfunction->get_employee_name($user_id);?>
			</div>
			<div class="col-sm-3">
				<strong>Designation :</strong> <?php
				//echo $this->ERPfunction->get_user_designation($user_id);
				$curr_date = "{$year}-{$month}-01";
				$curr_date = date("Y-m-d",strtotime($curr_date));
				
				echo $this->ERPfunction->get_user_designation_by_date($user_id,$curr_date);
				?>
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
		<hr/>
		
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
		<div class="row cst_Scroll">
		<div class="col-md-12">
		
		<div>
		<table class='table table-bordered'>
		<thead>
		<tr class="active">
		<?php
		foreach($dates as $date)
		{
			$curr_d = date("d");
			$print_d = date("d",strtotime($date));
			$highlight = ($curr_d === $print_d) ? "label-danger" : "";
			echo "<th rowspan='2' style='border:1px solid !important' class='text-center {$highlight}'>".$print_d."</th>";
		}
		?>
			<th colspan="4" class='text-center' style='border:1px solid !important'>TOTAL</th>
			<th colspan="4" class='text-center' style='border:1px solid !important'>PL</th>
			<th rowspan="2" class='text-center' style='border:1px solid !important'>Payable Days</th>
		</tr>
		<tr>
			<td class="label-success" style='color:#fff;'><strong>&nbsp;P</strong></td>
			<td class="label-danger" style='color:#fff;'><strong>&nbsp;A</strong></td>
			<td class="label-primary" style='color:#fff;'><strong>H</strong></td>
			<td class="label-warning" style='color:#fff;'><strong>AA</strong></td>
			<td>Opening</td>
			<td>New</td>
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
		foreach($dates as $date)
		{
			$date = date("Y-m-d",strtotime($date));
			$day = date("j",strtotime($date));
			echo "<td>";
			switch($retrive_data['day_'.$day.''])
			{
				CASE "AA" :
					echo "<strong><span class='text-warning'>AA</span></strong>";
				break;
				CASE "manual_AA" :
					echo "<strong id='x_{$day}' style='color:#fff'>AA</strong>";
					echo "<script>$('#x_{$day}').parents('td').css({'background':'#F0AD4E'});</script>";
					break;
				CASE "A" :
					echo "<strong><span class='text-danger'>&nbsp;A</span></strong>";
				break;
				CASE "manual_A" :
					echo "<strong id='x_{$day}' style='color:#fff'>&nbsp;A</strong>";
					echo "<script>$('#x_{$day}').parents('td').css({'background':'#D9534F'});</script>";
				break;
				CASE "P" :
					echo "<strong><span class='text-success'>&nbsp;P</span></strong>";
				break;
				CASE "manual_P" :
					echo "<strong id='x_{$day}' style='color:#fff'>&nbsp;P</strong></a>";
					echo "<script>$('#x_{$day}').parents('td').css({'background':'#5CB85C'});</script>";
				break;
				CASE "H" :
					echo "<strong><span class='text-primary'>&nbsp;H</span></strong>";
				break;
				CASE "manual_H" :
					echo "<strong id='x_{$day}' style='color:#fff'>&nbsp;H</strong>";
					echo "<script>$('#x_{$day}').parents('td').css({'background':'#428BCA'});</script>";
				break;
				CASE "HL" :
					echo "<strong><span class='text-info'>P/2</span></strong></a>";
				break;
				CASE "manual_HL" :
					echo "<strong id='x_{$day}' style='color:#fff'>P/2</strong>";
					echo "<script>$('#x_{$day}').parents('td').css({'background':'#428BCA'});</script>";
				break;
			}
			echo "</td>";
		}
		?>	
			<td align="center"><?php echo $retrive_data['total_present'] ; ?></td>
			<td align="center"><?php echo $retrive_data['total_absent']; ?></td>
			<td align="center"><?php echo $retrive_data['total_holidays'] ; ?></td>
			<td align="center"><?php echo $retrive_data['total_aa'] ; ?></td>
			<td align="center"><?php echo $retrive_data['opening_pl'] ; ?></td>
			<td align="center"><?php echo $retrive_data['new'] ; ?></td>
			<td align="center"><?php echo $retrive_data['used_pl'] ; ?></td>
			<td align="center"><?php echo $retrive_data['remaining_pl'] ; ?></td>
			<td align="center"><?php echo $retrive_data['payable_days'] ; ?></td>
		</tr>
		
		</tbody>
		</table>
		<br/>
		<br/>
		<br/>
		
		</div>
		</div>
		</div>
		<hr/>
		<?php
		}
		// debug($dates);
		?>
</div>
</div>
</div>
<?php 
} ?>
</div>