<div class="col-md-10" >
	<?php 
if(!$is_capable)
	{
		$this->ERPfunction->access_deniedmsg();
	}
else
{
	
	$emp_no = (isset($_REQUEST["employee_no"])) ? $_REQUEST["employee_no"] :"";
?>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery(".datep").datepicker({changeMonth: true,changeYear:true,dateFormat: 'MM yy'});
	jQuery("#userlist").DataTable();	
});
</script>
<style>
/*
table.ui-datepicker-calendar {
    display: none;
}*/
select[multiple], select[size] {
    height: 2px !important;
}
</style>
<?php 
$from_date = (isset($_POST["from_date"])) ? $_POST["from_date"] : "";
$to_date = (isset($_POST["to_date"])) ? $_POST["to_date"] : "";
$project_id = (isset($_POST["project_id"])) ? $_POST["project_id"] : "";
?>
<div class="row">
<div class="col-md-12">
	<div class="block ">
		<div class="head bg-default bg-light-rtl">
			<h2>Attendance Record</h2>
			<div class="pull-right">
			<a href="<?php echo $this->request->base;?>/humanresource/index" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
			</div>
		</div>
		<div class="content">
			<div class="col-md-12 filter-form">
			<form name="search" class="form_horizontal formsize" method="post">
			
			<div class="form-row">
					
					<div class="col-md-2 text-right">Month & Year[From]</div>
					<div class="col-md-4">
						<input name="from_date" required="true" class="form-control datep" value="<?php echo $from_date;?>">
					</div>
					<div class="col-md-2 text-right">Month & Year[To]</div>
					<div class="col-md-4">
						<input name="to_date" required="true" class="form-control datep" value="<?php echo $to_date;?>">
					</div>
			</div>
			
			<div class="form-row">
					
					<div class="col-md-2 text-right">Name</div>
					<div class="col-md-4">
						<select class="select2" style="width: 100%;" id="user_id" name="user_id[]" multiple="multiple">
					  <!-- <option value="All" selected>All</option>  -->
							<?php
							if(isset($name_list)){
								foreach($name_list as $retrive_data){
								?>
						   <option value="<?php echo $retrive_data['user_id'];?>"><?php echo $retrive_data['first_name'];?></option>
									<?php             
								}
							} ?>
						</select>
					</div>
					<div class="col-md-2 text-right">Designation</div>
					<div class="col-md-4">
						<select class="select2" style="width: 100%;" id="designation" name="designation[]" multiple="multiple">
					  <!-- <option value="All" selected>All</option>  -->
							<?php
							if(isset($designationlist)){
								foreach($designationlist as $unit_info){
								?>
						   <option value="<?php echo $unit_info['cat_id'];?>"><?php echo $unit_info['category_title'];?></option>
									<?php             
								}
							} ?>
						</select>
					</div>
				</div>
				<div class="form-row">
					<div class="col-md-2 text-right">Employee No</div>
					<div class="col-md-4">
						<input name="employee_no" class="form-control">
					</div>
					<div class="col-md-2 text-right">Employed at</div>
					<div class="col-md-4">
						<select class="select2" style="width: 100%;" name="project_id[]" id="project_id" multiple="multiple">
						<!--<option value="All" selected>All</Option> -->
						<?php 
							foreach($projects as $retrive_data)
							{
								$selected = ($retrive_data['project_id']==$project_id) ? "selected" : "";
								echo '<option value="'.$retrive_data['project_id'].'" '. $selected .'>'.$retrive_data['project_name'].'</option>';
							}
						?>
						</select>
					</div>
					
				</div>
				
				<div class="form-row">
					<div class="col-md-1">
						<input type="submit" name="go" id="go" class="btn btn-primary" value="Go">
					</div>
				</div>
			</form>
		</div>
		</div>
		<div class="content list custom-btn-clean">

		
			<table id="userlist" class="dataTables_wrapper table table-striped table-hover" style="width:100%">
				<thead>
					<tr>
						<th>Employee No</th>					
						<th>PF Slip No</th>					
						<th>Employee Name</th>					
						<th>Designation</th>
						<th>Employee At</th>		
						<th>Present</th>		
						<th>Absent</th>		
						<th>Opening</th>
						<th>New</th>
						<th>Mannage</th>
						<th>Used</th>
						<th>Remaing</th>
						<th>Holiday</th>
						
						<!-- <th>Opn. PL</th>
						<th>New PL</th>
						<th>Used PL</th>
						<th>Rem. PL</th> -->
						<th>Payable<br>Days</th>			
						<th>View</th>					
					</tr>
				</thead>
				<tbody>
					<?php 
					if(!empty($users))
					{				
						foreach($users as $user)
						{
							echo "<tr>";
							echo "<td>{$user['erp_user']['user_identy_number']}</td>";
							echo "<td>{$user['erp_user']['pf_ref_no']}</td>";
							echo "<td>{$user['erp_user']['first_name']} {$user['erp_user']['last_name']}</td>";
							echo "<td>";
							
							echo "{$this->ERPfunction->get_category_title($user["erp_user"]['designation'])}";
							
						/*	if($user['erp_user']['is_pay_structure_change'] == 1)
							{
								$change_date = $user['erp_user']['change_date']->format('Y-m-d');								
								$change_month = date("n",strtotime($change_date));
								$curr_date = date("Y-m-d",strtotime($date));
								$curr_date_stamp = strtotime($curr_date);
								$change_date_stamp =  strtotime($change_date);
								if($curr_date_stamp < $change_date_stamp) //NO NEED TO CHECK YEAR. LOAD OLD DATA FROM HISTORY TABLE
								{ 
									$data = $this->ERPfunction->get_user_history($user["user_id"],$change_date);
									echo $this->ERPfunction->get_category_title($data["designation"]);
								}else{
									echo $this->ERPfunction->get_category_title($user['erp_user']['designation']);
								}
							}else{
								echo $this->ERPfunction->get_category_title($user['erp_user']['designation']);
							}*/
							
							echo "</td>";
							echo "<td>";
							
							echo "{$this->ERPfunction->get_user_employee_at($user['user_id'])}";

							echo "</td>";					
							echo "<td>{$user['total_present']}</td>";
							echo "<td>{$user['total_absent']}</td>";
							echo "<td>{$user['opening_pl']}</td>";
							echo "<td>{$user['new']}</td>";
						
							echo "<td>{$this->ERPfunction->total_sundays_category_wise($this->ERPfunction->get_user_category($user['user_id']),$from_month,$from_year)}</td>";
							echo "<td>{$user['used_pl']}</td>";
							echo "<td>{$user['remaining_pl']}</td>";	
							echo "<td>{$user['total_holidays']}</td>";
						/* 	echo "<td>{$user['opening_pl']}</td>";
							echo "<td>{$user['new']}</td>";
							echo "<td>{$user['used_pl']}</td>";
							echo "<td>{$user['remaining_pl']}</td>"; */
							echo "<td>{$user['payable_days']}</td>";
							echo "<td><a href='{$this->request->base}/attendance/viewrecord/{$user['user_id']}/{$from_month}/{$from_year}/{$to_month}/{$to_year}' class='btn btn-clean btn-info'><i class='icon-eye-open'></i><span style='padding-right:32px;'>View Time Log</span></a></td>";
							echo "</tr>";
						}
					}
					?>
				</tbody>
			</table>
		</div>		
	</div>
</div>
</div>
<?php } ?>     
</div>