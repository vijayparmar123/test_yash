<div class="col-md-10" >
	<?php 
if(!$is_capable)
	{
		$this->ERPfunction->access_deniedmsg();
	}
else
{ ?>
<div class="row">
<div class="col-md-12">
<div class="block block-fill-white">
	<div class="head bg-default bg-light-rtl">
		<h2>Time Logs</h2>
		<div class="pull-right">
			<a href="<?php echo $this->request->base;?>/Attendance/timelog" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
		</div>
	</div>
	<div class="content list custom-btn-clean">
		<br/></br>
		<div class="row">
			<div class="col-sm-3">
				&nbsp;&nbsp;&nbsp;<strong>Employee No. :</strong> <?php echo //$this->ERPfunction->get_employee_no($user_id);
				$user_id;
				?>
			</div>
			<div class="col-sm-3">
				<strong>Name :</strong> <?php echo $this->ERPfunction->get_employee_name($user_id);?>
			</div>
			<div class="col-sm-3">
				<strong>Designation :</strong> <?php echo $this->ERPfunction->get_user_designation($user_id);?>
			</div>
			<div class="col-sm-3">
				<?php 
					$dateObj = DateTime::createFromFormat('!m', $month);
					$monthName = $dateObj->format('F'); 
				?>
				<strong>Attendance Of :</strong> <?php echo $monthName;?>/<?php echo date("Y",strtotime($year));?>
			</div>
		</div>
		<hr/>
		
		<?php
 /*
		$start_date = "01-".$month."-".$year;
			$start_time = strtotime($start_date);

			$end_time = strtotime("+1 month", $start_time);
			for($i=$start_time; $i<$end_time; $i+=86400)
			{
			   // $dates[] = date('Y-m-d D', $i);
			   $day = date('j', $i);
			   $day_name = date('l', $i);
			   if($day_name == "Sunday")
			   {
					$data[$day]["status"] = "P";
					$data[$day]["day"] = $day_name;
			   }else{
					$data[$day]["status"] = "A";
					$data[$day]["day"] = $day_name;
			   }		  
			   
			}
		debug($data);
		
		die; */
		
		$start_date = "01-".$month."-".$year;
		$start_time = strtotime($start_date);

		$end_time = strtotime("+1 month", $start_time);

		for($i=$start_time; $i<$end_time; $i+=86400)
		{
		   $dates[] = date('Y-m-d D', $i);
		}
		?>
		<div class="row">
		<div class="col-md-12">
		
		<div style="overflow-x:auto;min-height: 407px;">
		<table class='table table-bordered'>
		<thead>
		<tr class="success">
		<?php
		foreach($dates as $date)
		{
			echo "<th colspan='3'  style='border:1px solid !important' class='info text-center'>".date("d D",strtotime($date))."</th>";
		}
		?>
		</tr>
		</thead>		
		<tbody>		
		<tr class="active">
		<?php
		foreach($dates as $date)
		{
			echo "<td>IN</td>
				 <td>OUT</td>
				 <td>Attended Hours</td>";
		}
		?>
		</tr>
		<tr>
		<?php		
		foreach($dates as $date)
		{
			$curr_date = date("Y-m-d",strtotime($date));
			$in_time = $this->ERPfunction->get_employee_in_time($user_id,$curr_date);
			$out_time = $this->ERPfunction->get_employee_out_time($user_id,$curr_date);
			$total = $this->ERPfunction->get_employee_total_time($user_id,$curr_date);
			echo "<td>{$in_time}</td>
				 <td>{$out_time}</td>
				 <td>{$total}</td>";
		}
		?>
		</tr>
		</tbody>
		</table>
		
		</div>
		</div>
		</div>
		<?php
		// debug($dates);
		?>
</div>
</div>
</div>
<?php 
} ?>
</div>