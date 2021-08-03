
<div class="col-md-10" >
	<?php 
if(!$is_capable)
	{
		$this->ERPfunction->access_deniedmsg();
	}
else
{
?>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery(".datep").datepicker({changeMonth: true,changeYear:true,dateFormat: 'MM yy'});
	jQuery("#userlist").DataTable();

	jQuery("body").on("click","#unapprove",function(){
			if(confirm("Are you sure,you want Unapprove Record?"))
			{
				if(confirm("Are you sure,you want Unapprove Record?"))
				{
					return true;
				}
				else{
					return false;
				}
			}
			else{
				return false;
			}
		
	});
});
</script>
<style>
/*
table.ui-datepicker-calendar {
    display: none;
}
.ui-datepicker select.ui-datepicker-month, .ui-datepicker select.ui-datepicker-year {
    width: 49%;
    display: inline-block;
	margin-left:2px;
} */
</style>
<?php 
$selected_type = array();
$date = (isset($_POST["date"])) ? $_POST["date"] : "";
$project_id = (isset($_POST["project_id"])) ? $_POST["project_id"] : "";
$selected_type = (isset($_POST["pay_type"])) ? $_POST["pay_type"] : "";

?>
<div class="row">
<div class="col-md-12">
<div class="block">
		<div class="head bg-default bg-light-rtl">
			<h2>Pay Slip</h2>
			<div class="pull-right">
			<a href="<?php echo $this->request->base;?>/humanresource/index" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
			</div>
		</div>
		<?php
		$date = (isset($_POST['date']))?$_POST['date']:date("Y-m-d");
		$month = date("m",strtotime($date));
		$year = date("Y",strtotime($date));
		if(isset($_POST['go']))
		{
			
			
		}
		?>
		<div class="content">
			<div class="col-md-12 filter-form">
			<?php echo $this->Form->Create('form1',['id'=>'eq_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
			<!-- <form name="search" class="form_horizontal formsize" method="post" action=""> -->
				<div class="form-row">					
					<div class="col-md-2 text-right">Employee At</div>
					<div class="col-md-3">
						<select class="select2" required="true" style="width: 100%;" name="project_id" id="project_id" >
						<option value="All" selected>All</Option>
						<?php 
							foreach($projects as $retrive_data)
							{ 
								$selected = ($retrive_data['project_id']==$project_id) ? "selected" : "";
								echo '<option value="'.$retrive_data['project_id'].'" '. $selected .'>'.$retrive_data['project_name'].'</option>';
							}
						?>
					</select>
					</div>
					<div class="col-md-2 text-right">Month & Year</div>
					<div class="col-md-3">
						<input name="date" class="form-control datep" required="true" value="<?php echo $date;?>">
					</div>
					<div class="col-md-1">
						<input type="submit" name="go" id="go" class="btn btn-primary" value="Go">
					</div>
				</div>
				<div class="form-row">
					<div class="col-md-2 text-right">Pay Type</div>
					<div class="col-md-3">
						<select name="pay_type[]" style="width:100%" class="select2" multiple="multiple">
						<option value="All">All</option>
						<option value="employee" selected <?php //echo (in_array('employee',$selected_type)) ? "selected" : "";?>>Employee</option>
						<option value="consultant"  <?php //echo (in_array('consultant',$selected_type)) ? "selected" : "";?>>Labour</option>
						<option value="temporary" <?php //echo (in_array('temporary',$selected_type)) ? "selected" : "";?>>Temporary</option>
						</select>
					</div>
					
					<div class="col-md-2 text-right">No of Holidays in Month</div>
					<div class="col-md-3">
						<input name="total_sunday" class="form-control" value="<?php echo $this->ERPfunction->total_sundays($month,$year);?>">
					</div>
				</div>
			<?php $this->Form->end(); ?>
		</div>
		</div>
		<div class="content list custom-btn-clean">
			<table id="userlist"  class="dataTables_wrapper table table-striped table-hover">
				<thead>
					<tr>
						<th>Employee at</th>
						<th>Employee No</th>					
						<!--<th>PF Slip Ref. No.</th>-->					
						<th>Name of Employee</th>					
						<th>Designation</th>						
						<th>Present</th>			
						<!--<th>Absent</th>-->	
						<th>Used PL</th>			
						<th>Payable days</th>			
						<!--<th>Paid Holidays</th>-->			
						<th>Working days</th>			
						<th>Paid days</th>			
						<th>Monthly Salary</th>			
						<th>Action</th>					
					</tr>
				</thead>
				<tbody>
					<?php 
					if(!empty($users))
					{			
						$rows = array();
						$rows[] = array("Employee at","Employee No","Name of Employee","Designation","Present","Used PL","Payable days","Working days","Paid days","Monthly Salarey");
						$month_all_day = $this->ERPfunction->total_day_of_month($month,$year);
						
						
						foreach($users as $user)
						{
							$csv = array();
							$attendance_id = $this->ERPfunction->get_attendance_detail($user['user_id'],$month,$year,'id');
							$payable_days = $this->ERPfunction->get_attendance_detail($user['user_id'],$month,$year,'payable_days');
							$holiday = $this->ERPfunction->get_attendance_detail($user['user_id'],$month,$year,'total_holidays');
							$working_days = $month_all_day - $custom_holiday;
							$paid_days = $payable_days * $working_days / $month_all_day;
							$paid_days = $x = floor($paid_days * 2) / 2;
							$paid_days = number_format((float)$paid_days,2, '.', '');
							echo "<tr>";
							echo "<td>".($csv[] = $this->ERPfunction->get_projectname($user['employee_at']))."</td>";
							echo "<td>".($csv[] = $user['user_identy_number'])."</td>";
							//echo "<td>".($csv[] = $user['pf_ref_no'])."</td>";
							echo "<td>".($csv[] = $user['first_name'].' '.$user['middle_name'].' '.$user['last_name'])."</td>";
							echo "<td>".($csv[] = $this->ERPfunction->get_category_title($user['designation']))."</td>";
							echo "<td>".($csv[] = $this->ERPfunction->get_attendance_detail($user['user_id'],$month,$year,'total_present'))."</td>";
							//echo "<td>".($csv[] = $this->ERPfunction->get_attendance_detail($user['user_id'],$month,$year,'total_absent'))."</td>";
							
							echo "<td>".($csv[] = $this->ERPfunction->get_attendance_detail($user['user_id'],$month,$year,'used_pl'))."</td>";
							echo "<td>".($csv[] = $payable_days)."</td>";
							//echo "<td>".($csv[] = $custom_holiday)."</td>";
							echo "<td>".($csv[] = $working_days)."</td>";
							echo "<td>".($csv[] = $paid_days)."</td>";
							echo "<td>".($csv[] = $user['monthly_pay'])."</td><td>";
						//	echo "<td>".($csv[] = $this->ERPfunction->get_user_ctc_month($user['user_id']))."</td><td>";
							if($this->ERPfunction->retrive_accessrights($role,'generatesalaryslip')==1)
							{
								if($user['pay_type'] == "employee"){
									echo "<a href='{$this->request->base}/humanresource/generatesalaryslip/{$user['user_id']}/{$month}/{$year}/{$custom_holiday}' target='_blank' class='btn btn-clean btn-primary'><i class='icon-money'></i>Generate Salary Slip</a>";
								}elseif($user['pay_type'] == "temporary"){
									echo "<a href='{$this->request->base}/humanresource/generatesalaryvoucher/{$user['user_id']}/{$month}/{$year}/{$custom_holiday}' target='_blank' class='btn btn-clean btn-primary'><i class='icon-money'></i>Generate Voucher</a>";
								}elseif($user['pay_type'] == "consultant"){
									echo "<a href='{$this->request->base}/humanresource/generatesalarybill/{$user['user_id']}/{$month}/{$year}/{$custom_holiday}' target='_blank' class='btn btn-clean btn-primary'><i class='icon-money'></i>Generate Labour Bill</a>";
								}else{
									echo "<a href='{$this->request->base}/humanresource/generatesalaryslip/{$user['user_id']}/{$month}/{$year}/{$custom_holiday}' target='_blank' class='btn btn-clean btn-primary'><i class='icon-money'></i>Generate Salary Slip</a>";
								}
							}
							if($this->ERPfunction->retrive_accessrights($role,'unapproveattendance')==1)
							{
							echo "<a href='{$this->request->base}/humanresource/unapproveattendance/{$attendance_id}' id='unapprove' class='btn btn-clean btn-danger'><i class='icon-remove'></i>Unapprove</a>";
							}
							echo "</td>";
							echo "</tr>";
							
							
							$rows[] = $csv;
							
						}
					}
					?>
				</tbody>
			</table>
			<div class="content">
			<?php
			if(isset($users))
			{
			if($users != NULL)
			{
			?>
			<div class="col-md-2">
			<form method="post">
				<input type="hidden" name="rows" value='<?php echo base64_encode(serialize($rows));?>'>
				<input type="submit" class="btn btn-success" value="Export To Excel" name="export_csv">
			</form>
			</div>
			<div class="col-md-2">
			<form method="post">
				<input type="hidden" name="rows" value='<?php echo base64_encode(serialize($rows));?>'>
				<input type="submit" class="btn btn-success" value="Export To PDF" name="export_pdf">
			</form>
			</div>
			<?php } } ?>
			</div>
		</div>
</div>
</div>
</div>
<?php 
} ?>
</div>