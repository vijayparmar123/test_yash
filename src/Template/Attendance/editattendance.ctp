<?php 
use Cake\Routing\Router;
?>

<script>
var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
jQuery(document).ready(function() {
	jQuery("#attendance_form").validationEngine();
	jQuery('body').on('click','.change_status',function(e){
		// e.preventDefault();
		var data = $(this).attr("data");
		var url = $(this).attr("data-url");
		var status = $(this).attr("status");
		var detail_id = $(this).attr("detail_id");
		var man_pl = $('#man_pl').val();
		
		// var url = "<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'changeattendancestatus'));?>";
	
		var curr_data = {data:data,status:status,detail_id:detail_id,man_pl:man_pl};
		jQuery.ajax({
						headers: {
					'X-CSRF-Token': csrfToken
				},
                type:"POST",
						url:url,
						data:curr_data,
						async:false,
						success: function(response){                    
							jQuery('.modal-content').html(response);
							$('#load_modal').modal("show");
						},
						beforeSend:function(){
									jQuery('#modal-view').html('<center><img src=../images/4.gif width=120px><div><h3>Loading...</h3></div></center>');
								},
						error: function(e) {
								console.log(e.responseText);
							}
					});			
		
	});
	
});
	jQuery("body").on("change", ".count", function(){
		var row_id = $(this).attr("data-row-id");
		calculateAttendance(row_id);
	});
	function calculateAttendance(row_id)
	{
		var present = $("#present_"+row_id).val();
		
		if(present != '' && jQuery.isNumeric(present))
		{
		var month_total_days = $("#total_days").val();
		var holiday = $("#holiday_"+row_id).val();
		var sunday = $("#sunday_"+row_id).val();
		
		/* Count Absent value */
		var absent = parseFloat(month_total_days) - parseFloat(present);
		$(".absent_"+row_id).html(absent.toFixed(1));
		$("#absent_"+row_id).val(absent.toFixed(1));
		/* Count Absent value */
		
		/* Count New PL value */
		var employee_category = $("#employee_category_"+row_id).val();
		
		var new_pl = 0;
		var newpl_count = parseFloat(present) + parseFloat(holiday) + parseFloat(sunday);
		
		if(employee_category == 'a')
		{
			if(parseFloat(newpl_count) >= 28){
				new_pl = 2;
			}else if(parseFloat(newpl_count) >= 14){
				new_pl = 1;
			}
		}else if(employee_category == 'b'){
			if(parseFloat(newpl_count) >= 28){
				new_pl = 4;
			}else if(parseFloat(newpl_count) >= 21){
				new_pl = 3;
			}else if(parseFloat(newpl_count) >= 14){
				new_pl = 2;
			}else if(parseFloat(newpl_count) >= 7){
				new_pl = 1;
			}
		}else if(employee_category == 'c'){
			if(parseFloat(newpl_count) >= 28){
				new_pl = 2;
			}else if(parseFloat(newpl_count) >= 14){
				new_pl = 1;
			}
		}
		$(".new_"+row_id).html(new_pl.toFixed(1));
		$("#new_"+row_id).val(new_pl.toFixed(1));
		/* Count New PL value */
		
		/* Count Used PL value */
		var opening_pl = $("#opening_"+row_id).val();
		var new_pl = $("#new_"+row_id).val();
		var manual_pl = $("#manual_"+row_id).val();
		
		var usedpl_count = parseFloat(opening_pl) + parseFloat(new_pl) + parseFloat(holiday) + parseFloat(sunday) + parseFloat(manual_pl);
		
		var used_pl = 0;
		if(parseFloat(usedpl_count) < parseFloat(absent)){
			used_pl = parseFloat(usedpl_count);
		}else{
			used_pl = parseFloat(absent);
		}
		$(".used_"+row_id).html(used_pl.toFixed(1));
		$("#used_"+row_id).val(used_pl.toFixed(1));
		/* Count Used PL value */
		
		/* Count Remaining PL value */
		var remaining_pl = parseFloat((parseFloat(opening_pl) + parseFloat(new_pl) + parseFloat(holiday) + parseFloat(sunday) + parseFloat(manual_pl)) - parseFloat(used_pl));
		$(".remaining_"+row_id).html(remaining_pl.toFixed(1));
		$("#remaining_"+row_id).val(remaining_pl.toFixed(1));
		/* Count Remaining PL value */
		
		/* Count Remaining PL value */
		var payable_days = parseFloat(present) + parseFloat(used_pl);
		$(".payable_days_"+row_id).html(payable_days.toFixed(1));
		$("#payable_days_"+row_id).val(payable_days.toFixed(1));
		/* Count Remaining PL value */
		}
	}
function confirmBox()
{
	if(confirm("Are you sure you want to approve this time log?"))
	{
		if(confirm("Are you sure you want to approve this time log?"))
		{
			return true;
		}
	}
	return false;
}
</script>

<div class="modal fade " id="load_modal" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content"></div>
    </div>
</div>

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
		<h2>Edit/Approve Attendance Record</h2>
		<div class="pull-right">
			<?php
			if(isset($user_id)){
			?>
			<a href="" onclick = "javascript:window.close();" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
			<?php
			}
			else
			{
			?>
			<a href="<?php echo $this->request->base;?>/Attendance/attendancealert" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
			<?php } ?>
		</div>
	</div>
	<div class="content list custom-btn-clean">
		<br/></br>
		<div class="row">
			<div class="col-sm-3">
				<!--&nbsp;&nbsp;&nbsp;<strong>Employee No. :</strong> <?php echo //$this->ERPfunction->get_employee_no($user_id);
				$user_id; ?>-->
			</div>
			<div class="col-sm-3">
				<!--<strong>Name :</strong> <?php echo $this->ERPfunction->get_employee_name($user_id);?>-->
			</div>
			<div class="col-sm-3">
				<!--<strong>Designation :</strong> 
				<?php 
					// echo $this->ERPfunction->get_user_designation($user_id);
					$curr_date = "{$record['year']}-{$record['month']}-01";
					$curr_date = date("Y-m-d",strtotime($curr_date));
					echo $this->ERPfunction->get_user_designation_by_date($user_id,$curr_date);
				?>-->
			</div>
			<div class="col-sm-3">
			<?php 
			// $month = sprintf("%02s", $month);
			$dateObj   = DateTime::createFromFormat('!m', $month);
			// debug($dateObj);die;
			$monthName = $dateObj->format('F'); 
			?>
				<strong>Attendance Of :</strong> <?php echo $monthName;?> / <?php echo date("Y",strtotime($year));?>
			</div>
		</div>
		<hr/>
		
		<?php

		$start_date = "01-".$month."-".$year;
		$total_days = date("t",strtotime($start_date));
		// $start_time = strtotime($start_date);
		
		// $end_time = strtotime("+1 month", $start_time);

		// for($i=$start_time; $i<$end_time; $i+=86400)
		// {
		   // $dates[] = date('Y-m-d D', $i);
		// }
		?>
		<div class="row">
		<div class="col-md-12">
		
		<div style="overflow-x:auto;min-height: 407px;">
		<?php echo $this->Form->Create('form2',['id'=>'attendance_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
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
		// foreach($dates as $date)
		// {
			// echo "<td>{$date}</td>";
		// }
		?>
		</tr>
		<tr>
		<?php
		// foreach($dates as $date)
		// {
			// $date = date("Y-m-d",strtotime($date));
			// $day = date("j",strtotime($date));
			// echo "<td>";
			// switch($record['day_'.$day.''])
			// {
				// CASE "AA" :
					// echo "<a href='#' class='change_status' status='AA' detail_id='{$record['id']}' data='{$user_id}/{$day}/{$month}/{$year}' data-url='{$this->request->base}/ajaxfunction/changeattendancestatus'><strong><span class='text-warning'>AA</span></strong></a>";
				// break;
				// CASE "manual_AA" :
					// echo "<a href='#' class='change_status' status='AA' id='x_{$day}' detail_id='{$record['id']}' data='{$user_id}/{$day}/{$month}/{$year}' data-url='{$this->request->base}/ajaxfunction/changeattendancestatus'><strong>AA</strong></a>";
					// echo "<script>$('#x_{$day}').parents('td').css({'background':'#F0AD4E'});</script>";
					// break;
				// CASE "A" :
					// echo "<a href='#' class='change_status' status='A' detail_id='{$record['id']}' data='{$user_id}/{$day}/{$month}/{$year}' data-url='{$this->request->base}/ajaxfunction/changeattendancestatus'><strong><span class='text-danger'>&nbsp;A</span></strong></a>";
				// break;
				// CASE "manual_A" :
					// echo "<a href='#' class='change_status' status='A' id='x_{$day}' detail_id='{$record['id']}' data='{$user_id}/{$day}/{$month}/{$year}' data-url='{$this->request->base}/ajaxfunction/changeattendancestatus'><strong>&nbsp;A</strong></a>";
					// echo "<script>$('#x_{$day}').parents('td').css({'background':'#D9534F'});</script>";
				// break;
				// CASE "P" :
					// echo "<a href='#' class='change_status' status='P' detail_id='{$record['id']}' data='{$user_id}/{$day}/{$month}/{$year}' data-url='{$this->request->base}/ajaxfunction/changeattendancestatus'><strong><span class='text-success'>&nbsp;P</span></strong></a>";
				// break;
				// CASE "manual_P" :
					// echo "<a href='#' class='change_status' status='P' id='x_{$day}' detail_id='{$record['id']}' data='{$user_id}/{$day}/{$month}/{$year}' data-url='{$this->request->base}/ajaxfunction/changeattendancestatus'><strong>&nbsp;P</strong></a>";
					// echo "<script>$('#x_{$day}').parents('td').css({'background':'#5CB85C'});</script>";
				// break;
				// CASE "H" :
					// echo "<a href='#' class='change_status' status='H' detail_id='{$record['id']}' data='{$user_id}/{$day}/{$month}/{$year}' data-url='{$this->request->base}/ajaxfunction/changeattendancestatus'><strong><span class='text-primary'>&nbsp;H</span></strong></a>";
				// break;
				// CASE "manual_H" :
					// echo "<a href='#' class='change_status' status='H' id='x_{$day}' detail_id='{$record['id']}' data='{$user_id}/{$day}/{$month}/{$year}' data-url='{$this->request->base}/ajaxfunction/changeattendancestatus'><strong>&nbsp;H</strong></a>";
					// echo "<script>$('#x_{$day}').parents('td').css({'background':'#428BCA'});</script>";
				// break;
				// CASE "HL" :
					// echo "<a href='#' class='change_status' status='HL' detail_id='{$record['id']}' data='{$user_id}/{$day}/{$month}/{$year}' data-url='{$this->request->base}/ajaxfunction/changeattendancestatus'><strong><span class='text-info'>P/2</span></strong></a>";
				// break;
				// CASE "manual_HL" :
					// echo "<a href='#' class='change_status' status='HL' id='x_{$day}' detail_id='{$record['id']}' data='{$user_id}/{$day}/{$month}/{$year}' data-url='{$this->request->base}/ajaxfunction/changeattendancestatus'><strong>P/2</strong></a>";
					// echo "<script>$('#x_{$day}').parents('td').css({'background':'#428BCA'});</script>";
				// break;
			// }
			// echo "</td>";
		// }
		?>	
			<td align="center"><?php echo $this->ERPfunction->get_user_identy_number($user_id); ?></td>
			<td align="center"><?php echo $this->ERPfunction->get_user_pf_ref_no($user_id); ?></td>
			<td align="center"><?php echo $this->ERPfunction->get_employee_name($user_id); ?></td>
			<td align="center"><?php echo $this->ERPfunction->get_user_designation($user_id); ?></td>
			<td align="center"><?php echo $this->ERPfunction->get_user_employee_at($user_id); ?></td>
			<td align="center">
			<input type="text" name="present" data-row-id="0" class="form-control validate[max[<?php echo $total_days; ?>],custom[number]] present count" id="present_0" value="<?php echo $record['total_present']; ?>">
			<input type="hidden" data-row-id="0" name="att_id" class="form-control" value="<?php echo $record['id']; ?>">
			<input type="hidden" data-row-id="0" class="form-control employee_category" id="employee_category_0" value="<?php echo $this->ERPfunction->get_user_category($user_id); ?>">
			<input type="hidden" value="0" class="row_number">
			<input type="hidden" value="<?php echo $total_days; ?>" id="total_days">
			</td>
			<td align="center">
			<span class="absent_0"><?php echo $record['total_absent']; ?></span>
			<input type="hidden" data-row-id="0" name="absent" class="form-control absent" id="absent_0" value="<?php echo $record['total_absent']; ?>">
			</td>
			<td align="center">
			<span class="opening_0"><?php echo $record['opening_pl'] ; ?></span>
			<input type="hidden" name="opening" data-row-id="0" class="form-control opening" id="opening_0" value="<?php echo $this->ERPfunction->get_leave_balance($user_id,$month,$year); ?>">
			</td>
			<td align="center">
			<span class="new_0"><?php echo $record['new'] ; ?></span>
			<input type="hidden" name="new" data-row-id="0" class="form-control new" id="new_0" value="<?php echo $record['new']; ?>">
			</td>			
			<td align="center">
			<span class="sunday_0"><?php echo $this->ERPfunction->total_sundays_category_wise($this->ERPfunction->get_user_category($user_id),$month,$year); ?></span>
			<input type="hidden" name="sunday" data-row-id="0" class="form-control sunday" id="sunday_0" value="<?php echo $this->ERPfunction->total_sundays_category_wise($this->ERPfunction->get_user_category($user_id),$month,$year); ?>">
			</td>
			<td align="center">
			<span class="holiday holiday_0"><?php echo $record['total_holidays'] ; ?></span>
			<input type="hidden" name="holiday" data-row-id="0" class="form-control holiday" id="holiday_0" value="<?php echo $this->ERPfunction->get_holiday_of_month($month,$year); ?>">
			</td>
			<td>
				<input type="text" name="manual" data-row-id="0" class="form-control manual count validate[custom[number]]" id="manual_0" value="<?php echo $record['man_pl']; ?>">
			</td>
			<td align="center">
			<span class="used_0"><?php echo $record['used_pl'] ; ?></span>
			<input type="hidden" name="used" data-row-id="0" class="form-control used" id="used_0" value="<?php echo $record['used_pl']; ?>">
			</td>
			<td align="center">
			<span class="remaining_0"><?php echo $record['remaining_pl'] ; ?></span>
			<input type="hidden" name="remaining" data-row-id="0" class="form-control remaining" id="remaining_0" value="<?php echo $record['remaining_pl']; ?>">
			</td>
			<td align="center">
			<span class="payable_days_0"><?php echo $record['payable_days'] ; ?></span>
			<input type="hidden" name="payable_days" data-row-id="0" class="form-control payable_days" id="payable_days_0" value="<?php echo $record['payable_days']; ?>">
			</td>
		</tr>
		
		</tbody>
		</table>
		<button type="submit" name="update_attendance" class="btn btn-primary">Update</button>
		<?php echo $this->form->end(); ?>
		<br/>
		<br/>
		<br/>
		<?php
		// echo $month;
		// if($month < date("n") && $year <= date("Y"))
		// {
		?>
		<!--<form method="post" onsubmit="return confirmBox()">
		<div class="form-row">
			<div class="col-md-2">
				<input type="hidden" name="detail_id" value="<?php //echo $record["id"];?>">
				Approve : <button type="submit" value="approve" name="approve" class="btn btn-primary">Approve</button>
			</div>
		</div>
		</form>-->
		<?php //} ?>
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