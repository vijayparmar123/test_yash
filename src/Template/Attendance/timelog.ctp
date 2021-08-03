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
select[multiple], select[size] {
    height: 2px !important;
}
</style>
<?php 
$date = (isset($_POST["date"])) ? $_POST["date"] : "";
$project_id = (isset($_POST["project_id"])) ? $_POST["project_id"] : "";
?>
<div class="row">
<div class="col-md-12">
<div class="block">
		<div class="head bg-default bg-light-rtl">
			<h2>Personnel Time Logs</h2>
			<div class="pull-right">
			<a href="<?php echo $this->request->base;?>/humanresource/index" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
			</div>
		</div>
		<div class="content">
			<div class="col-md-12 filter-form">
			<form name="search" class="form_horizontal formsize" method="post">
				<div class="form-row">
					<div class="col-md-2 text-right">Employee At</div>
					<div class="col-md-3">
						<select class="select2" required="true" style="width: 100%;" name="project_id[]" id="project_id" multiple="multiple">
						<!--<option value="All" selected>All</Option> -->
						<?php 
							foreach($projects as $retrive_data)
							{
								$selected = (in_array($retrive_data['project_id'],$project_id)) ? "selected" : "";
								echo '<option value="'.$retrive_data['project_id'].'" '. $selected .'>'.$retrive_data['project_name'].'</option>';
							}
						?>
					</select>
					</div>
					<div class="col-md-2 text-right">Month & Year</div>
					<div class="col-md-2">
						<input name="date" required="true" class="form-control datep" value="<?php echo $date;?>">
					</div>
					<div class="col-md-1">
						<input type="submit" name="go" id="go" class="btn btn-primary" value="Go">
					</div>
				</div>
			</form>
		</div>
		</div>
		<div class="content list custom-btn-clean">
			<table id="userlist"  class="dataTables_wrapper table table-striped table-hover">
				<thead>
					<tr>
						<th>Employee No</th>					
						<th>Name of Employee</th>					
						<th>Designation</th>			
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
							echo "<td>{$user['erp_user']['user_id']}</td>";
							echo "<td>{$user['erp_user']['first_name']} {$user['erp_user']['last_name']}</td>";
							echo "<td>{$this->ERPfunction->get_category_title($user['erp_user']['designation'])}</td>";
							echo "<td><a href='{$this->request->base}/attendance/viewlog/{$user['user_id']}/{$month}/{$year}' class='btn btn-clean btn-info'><i class='icon-eye-open'></i>View Time Log</a></td>";
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
<?php 
} ?>
</div>