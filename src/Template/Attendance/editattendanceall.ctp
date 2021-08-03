<?php 
use Cake\Routing\Router;
?>
<style>
.table-bordered thead tr th, td
{
	border-color: rgba(0,0,0,0.2);
    line-height: 20px;
    padding-left: 12px;
    padding-right: 12px;
}
</style>
<script>
var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
jQuery(document).ready(function() {
	jQuery('body').on('click','.change_status',function(e){
		// e.preventDefault();
		var data = $(this).attr("data");
		var url = $(this).attr("data-url");
		var status = $(this).attr("status");
		var detail_id = $(this).attr("detail_id");
		var emp_at = $("#emp_at").val();
		var man_pl = $('#man_pl').val();
		
		var curr_data = {data:data,status:status,man_pl:man_pl,detail_id:detail_id,emp_at:emp_at,multi:"yes"};
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
<script>
var nice = false;
$(document).ready(
  function() { 
    // nice = $(".content div").niceScroll({railpadding:{top:0,right:0,left:0,bottom:-5}});
  });
</script>
<div class="row">
<div class="col-md-12">
<div class="block block-fill-white">
	<div class="head bg-default bg-light-rtl">
		<h2>Edit Attendance Records</h2>
		<div class="pull-right">
			<a href="<?php echo $this->request->base;?>/Attendance/attendancealert" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
		</div>
	</div>
	<div class="content list custom-btn-clean" style="overflow-x:auto;min-height: 407px;">
		<br/></br>
		<table class="table table-bordered">
		
		<thead>
			<tr>
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
		
				foreach($dates as $date)
				{
					$curr_d = date("d");
					$print_d = date("d",strtotime($date));
					$highlight = ($curr_d === $print_d) ? "danger" : "";
					echo "<th rowspan='2' style='border:1px solid !important' class='text-center {$highlight}'>".$print_d."</th>";
				}
				?>
			<th colspan="4" class='text-center' style='border:1px solid !important'>TOTAL</th>
			<th colspan="5" class='text-center' style='border:1px solid !important'>PL</th>
			<th rowspan="2" class='text-center' style='border:1px solid !important'>Payable Days</th>
		</tr>
		<tr>
			<td class="label-success" style='color:#fff;'><strong>&nbsp;P</strong></td>
			<td class="label-danger" style='color:#fff;'><strong>&nbsp;A</strong></td>
			<td class="label-primary" style='color:#fff;'><strong>H</strong></td>
			<td class="label-warning" style='color:#fff;'><strong>AA</strong></td>
			<td>Opening</td>
			<td>New</td>
			<td class="text-center">MAN</td>
			<td>Used</td>
			<td>Remaining</td>
		</tr>
		</thead>
		<!--</table>-->
<?php 
if(!empty($records))
{
	foreach($records as $record)
	{
?>
	<tbody>
	<tr>
	<td colspan="45">
	<div class="row">
			<div class="col-sm-3">
				&nbsp;&nbsp;&nbsp;<strong>Employee No. :</strong> <?php echo $record["erp_user"]["pf_ref_no"];?>
			</div>
			<div class="col-sm-3">
				<strong>Name :</strong> <?php echo $record["erp_user"]["first_name"];?> <?php echo $record["erp_user"]["last_name"];?> 
			</div>
			<div class="col-sm-3">
				<strong>Designation :</strong> <?php echo $this->ERPfunction->get_user_designation($record["erp_user"]["user_id"]);?>
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
		<!--<div class="row cst_Scroll">--> <!--style="overflow-x:scroll"-->
		<div class="row "> <!--style="overflow-x:scroll"-->
		
		<div class="col-md-12">
		
		<div>
		<!--<table class='table table-bordered'>
		<thead style="visibility:hidden;height:0px;">
		<tr class="active">
		<?php
		foreach($dates as $date)
		{
			$curr_d = date("d");
			$print_d = date("d",strtotime($date));
			$highlight = ($curr_d === $print_d) ? "danger" : "";
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
		</thead>-->	
		</td>
		</tr>
		<!--<tr class="active">
		<?php
		foreach($dates as $date)
		{
			// echo "<td>{$date}</td>";
		}
		?>
		</tr>-->
		<tr>
		<?php
		
		foreach($dates as $date)
		{
			$date = date("Y-m-d",strtotime($date));
			$day = date("j",strtotime($date));
			$user_id = $record["user_id"];			
			
			echo "<td>";
			switch($record['day_'.$day.''])
			{
				CASE "AA" :
					echo "<a href='#' class='change_status' status='AA' detail_id='{$record['id']}' data='{$user_id}/{$day}/{$month}/{$year}' data-url='{$this->request->base}/ajaxfunction/changeattendancestatusall'><strong><span class='text-warning'>AA</span></strong></a>";
				break;
				CASE "manual_AA" :
					echo "<a href='#' class='change_status' status='AA' id='x_{$day}' detail_id='{$record['id']}' data='{$user_id}/{$day}/{$month}/{$year}' data-url='{$this->request->base}/ajaxfunction/changeattendancestatusall'><strong>AA</strong></a>";
					echo "<script>$('#x_{$day}').parents('td').css({'background':'#F0AD4E'});</script>";
					break;
				CASE "A" :
					echo "<a href='#' class='change_status' status='A' detail_id='{$record['id']}' data='{$user_id}/{$day}/{$month}/{$year}' data-url='{$this->request->base}/ajaxfunction/changeattendancestatusall'><strong><span class='text-danger'>&nbsp;A</span></strong></a>";
				break;
				CASE "manual_A" :
					echo "<a href='#' class='change_status' status='A' id='x_{$day}' detail_id='{$record['id']}' data='{$user_id}/{$day}/{$month}/{$year}' data-url='{$this->request->base}/ajaxfunction/changeattendancestatusall'><strong>&nbsp;A</strong></a>";
					echo "<script>$('#x_{$day}').parents('td').css({'background':'#D9534F'});</script>";
				break;
				CASE "P" :
					echo "<a href='#' class='change_status' status='P' detail_id='{$record['id']}' data='{$user_id}/{$day}/{$month}/{$year}' data-url='{$this->request->base}/ajaxfunction/changeattendancestatusall'><strong><span class='text-success'>&nbsp;P</span></strong></a>";
				break;
				CASE "manual_P" :
					echo "<a href='#' class='change_status' status='P' id='x_{$day}' detail_id='{$record['id']}' data='{$user_id}/{$day}/{$month}/{$year}' data-url='{$this->request->base}/ajaxfunction/changeattendancestatusall'><strong>&nbsp;P</strong></a>";
					echo "<script>$('#x_{$day}').parents('td').css({'background':'#5CB85C'});</script>";
				break;
				CASE "H" :
					echo "<a href='#' class='change_status' status='H' detail_id='{$record['id']}' data='{$user_id}/{$day}/{$month}/{$year}' data-url='{$this->request->base}/ajaxfunction/changeattendancestatusall'><strong><span class='text-primary'>&nbsp;H</span></strong></a>";
				break;
				CASE "manual_H" :
					echo "<a href='#' class='change_status' status='H' id='x_{$day}' detail_id='{$record['id']}' data='{$user_id}/{$day}/{$month}/{$year}' data-url='{$this->request->base}/ajaxfunction/changeattendancestatusall'><strong>&nbsp;H</strong></a>";
					echo "<script>$('#x_{$day}').parents('td').css({'background':'#428BCA'});</script>";
				break;
				CASE "HL" :
					echo "<a href='#' class='change_status' status='HL' detail_id='{$record['id']}' data='{$user_id}/{$day}/{$month}/{$year}' data-url='{$this->request->base}/ajaxfunction/changeattendancestatusall'><strong><span class='text-info'>P/2</span></strong></a>";
				break;
				CASE "manual_HL" :
					echo "<a href='#' class='change_status' status='HL' id='x_{$day}' detail_id='{$record['id']}' data='{$user_id}/{$day}/{$month}/{$year}' data-url='{$this->request->base}/ajaxfunction/changeattendancestatusall'><strong>P/2</strong></a>";
					echo "<script>$('#x_{$day}').parents('td').css({'background':'#428BCA'});</script>";
				break;
			}
			echo "</td>";
			
		}
		?>	
			<td align="center"><?php echo $record['total_present'] ; ?></td>
			<td align="center"><?php echo $record['total_absent']; ?></td>
			<td align="center"><?php echo $record['total_holidays'] ; ?></td>
			<td align="center"><?php echo $record['total_aa'] ; ?></td>
			<td align="center"><?php echo $record['opening_pl'] ; ?></td>
			<td align="center"><?php echo $record['new'] ; ?></td>
			<td>
				<?php 
				if($role == 'erphead' || $role == 'erpmanager' || $role == 'erpoperator'){?>
				<input name="man_pl" style="width:40px;" id="man_pl" value="<?php echo $record['man_pl']; ?>">
				<?php } else {?>
				<span><?php echo $record['man_pl']; ?></span>
				<input type="hidden" name="man_pl" style="width:40px;" id="man_pl" value="<?php echo $record['man_pl']; ?>">
				<?php } ?>
			</td>
			<td align="center"><?php echo $record['used_pl'] ; ?></td>
			<td align="center"><?php echo $record['remaining_pl'] ; ?></td>
			<td align="center"><?php echo $record['payable_days'] ; ?></td>
		</tr>
		
					
		</div>
		</div>
		<!--<br>-->
		</div>
		<!--<hr/>-->
		<?php	
	} /* records foreach ends */
	?>
	</tbody>
		</table>
	<?php
}
?>
<input type="hidden" id="emp_at" value='<?php echo json_encode($emp_at);?>'>
</div>
</div>
</div>
<?php 
} ?>
</div>