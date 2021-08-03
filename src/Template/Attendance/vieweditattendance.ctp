<?php 
use Cake\Routing\Router;
?>

<script>
var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
jQuery(document).ready(function() {
	jQuery('body').on('click','.change_status',function(e){
		// e.preventDefault();
		var data = $(this).attr("data");
		var url = $(this).attr("data-url");
		var status = $(this).attr("status");
		var detail_id = $(this).attr("detail_id");
		// var url = "<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'changeattendancestatus'));?>";
	
		var curr_data = {data:data,status:status,detail_id:detail_id};
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
			<a onclick = "javascript:window.close();" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
		</div>
	</div>
	<div class="content list custom-btn-clean">
		<br/></br>
		<div class="row">
			<div class="col-sm-3">
				<!--&nbsp;&nbsp;&nbsp;<strong>Employee No. :</strong> <?php echo //$this->ERPfunction->get_employee_no($user_id);
				$user_id;
				?>-->
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
			$monthName = $dateObj->format('F'); 
			?>
				<strong>Attendance Of :</strong> <?php echo $monthName;?> / <?php echo date("Y",strtotime($year));?>
			</div>
		</div>
		<hr/>
		
		<?php

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
		} ?>
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
					// echo "<strong><span class='text-warning'>AA</span></strong>";
				// break;
				// CASE "manual_AA" :
					// echo "<strong id='x_{$day}'>AA</strong>";
					// echo "<script>$('#x_{$day}').parents('td').css({'background':'#F0AD4E'});</script>";
					// break;
				// CASE "A" :
					// echo "<strong><span class='text-danger'>&nbsp;A</span></strong>";
				// break;
				// CASE "manual_A" :
					// echo "<strong>&nbsp;A</strong>";
					// echo "<script>$('#x_{$day}').parents('td').css({'background':'#D9534F'});</script>";
				// break;
				// CASE "P" :
					// echo "<strong><span class='text-success'>&nbsp;P</span></strong>";
				// break;
				// CASE "manual_P" :
					// echo "<strong id='x_{$day}'>&nbsp;P</strong>";
					// echo "<script>$('#x_{$day}').parents('td').css({'background':'#5CB85C'});</script>";
				// break;
				// CASE "H" :
					// echo "<strong><span class='text-primary'>&nbsp;H</span></strong>";
				// break;
				// CASE "manual_H" :
					// echo "<strong id='x_{$day}'>&nbsp;H</strong>";
					// echo "<script>$('#x_{$day}').parents('td').css({'background':'#428BCA'});</script>";
				// break;
				// CASE "HL" :
					// echo "<strong><span class='text-info'>P/2</span></strong>";
				// break;
				// CASE "manual_HL" :
					// echo "<strong id='x_{$day}'>P/2</strong>";
					// echo "<script>$('#x_{$day}').parents('td').css({'background':'#428BCA'});</script>";
				// break;
			// }
			// echo "</td>";
		// } ?>
			<td align="center"><?php echo $this->ERPfunction->get_user_identy_number($user_id); ?></td>
			<td align="center"><?php echo $this->ERPfunction->get_user_pf_ref_no($user_id); ?></td>
			<td align="center"><?php echo $this->ERPfunction->get_employee_name($user_id); ?></td>
			<td align="center"><?php echo $this->ERPfunction->get_user_designation($user_id); ?></td>
			<td align="center"><?php echo $this->ERPfunction->get_user_employee_at($user_id); ?></td>
			<td align="center"><?php echo $record['total_present'] ; ?></td>
			<td align="center"><?php echo $record['total_absent']; ?></td>
			<td align="center"><?php echo $record['opening_pl'] ; ?></td>
			<td align="center"><?php echo $record['new'] ; ?></td>
			<td align="center"><?php echo $this->ERPfunction->total_sundays_category_wise($this->ERPfunction->get_user_category($user_id),$month,$year); ?></td>
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