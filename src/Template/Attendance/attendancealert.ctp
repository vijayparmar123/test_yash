<?php
//$this->extend('/Common/menu')
use Cake\Routing\Router;
?>
<style type="text/css">
	tr>th.control:before {
       left:77px !important;
   }
</style>
<div class="col-md-10" >
	<?php 
if(!$is_capable)
	{
		$this->ERPfunction->access_deniedmsg();
	}
else
{
	if(isset($month) && isset($year))
	{
		$salary_date = $year."-".$month."-01";
		$salary_date = date("Y-m",strtotime($salary_date));
		$today_date = date('Y-m');
	}
	$emp_no = (isset($_REQUEST["employee_no"])) ? $_REQUEST["employee_no"] :"";
?>
<script type="text/javascript">
var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
jQuery(document).ready(function() {
	jQuery(".datep").datepicker({changeMonth: true,changeYear:true,dateFormat: 'MM yy'});
//	jQuery("#userlist").DataTable();

	jQuery('body').on('click','.multiple_approve',function(){
		if($(".approve_check").is(":checked")) {
		request_id=jQuery('.approve_check:checked').map(function() {	return this.attributes.data_id.textContent;
																	}).get();
		
		request_id = JSON.stringify(request_id);
		
		urlstring ="<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'multipleapproveattendance'));?>";
		
		var curr_data = {request_id:request_id };	 				
			jQuery.ajax({
				headers: {
					'X-CSRF-Token': csrfToken
				},
                type:"POST",
				url:urlstring,
				data:curr_data,
				async:false,
				success: function(response){                    
										 window.location.reload();
				},
				error: function(e) {
						console.log(e.responseText);
						 }
			});	
		}else{
			alert('Please Select Record');
		}				
			});
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
$date = (isset($_POST["date"])) ? $_POST["date"] : "";
$project_id = (isset($_POST["project_id"])) ? $_POST["project_id"] : "";
?>
<div class="row">
<div class="col-md-12">
	<div class="block ">
		<div class="head bg-default bg-light-rtl">
			<h2>Attendance Alert</h2>
			<div class="pull-right">
			<a href="<?php echo $this->request->base;?>/humanresource/index" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
			</div>
		</div>
		<div class="content controls">
			<div class="col-md-12 filter-form">
			<!-- <form name="search" class="form_horizontal formsize" method="post"> -->
			<?php 
				echo $this->Form->Create('search',['class'=>'form_horizontal formsize','method'=>'post']);
			?>
				<div class="form-row">
					<div class="col-md-2 text-right">Employee At</div>
					<div class="col-md-3">
						<select class="select2" required="true" style="width: 100%;" name="project_id[]" id="project_id" multiple="multiple">
						<option value="All">All</Option> 
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
					<?php if($role == "erphead" || $role == "hrmanager" || $role == "hrhead" || $role == "erpmanager" || $role == "erpoperator")
					{ ?>
					<div class="col-md-1">						
						<input type="submit" name="go" id="go" class="btn btn-primary" value="Go">						
					</div>
					<?php } ?>
					<div class="col-md-2">
						<?php if($role == "erphead" || $role == "hrmanager" || $role == "hrhead" || $role == "erpmanager" || $role == "erpoperator" || $role == "siteaccountant")
						{ ?>
							<input type="submit" name="edit_all" id="go" class="btn btn-primary" value="Edit All">
						<?php 
						}
						if($role == "erphead" || $role == "siteaccountant" ||  $role == "hrmanager" || $role == "constructionmanager" || $role == "hrhead" || $role == "erpmanager" || $role == "erpoperator")
						{ ?>
							<input type="submit" name="view_all" id="go" class="btn btn-success" value="View All">
						<?php } ?>
					</div>
				</div>
			<?php $this->Form->end(); ?>
		</div>
		</div>
		<div class="content list custom-btn-clean">
		<script>
				jQuery(document).ready(function() {
					jQuery('#user_list').DataTable({responsive: {
						details: {
							type: 'column',
							target: -1
						}
					},
					columnDefs: [ {
						className: 'control',
						orderable: false,
						targets:   -1
					} ],});
				});
		</script>
			<table id="user_list"  class="dataTables_wrapper table table-striped table-hover" style="width:100%">
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
						<th>Action</th>
						<th>Approve</th>


						<?php 
						if($role == "erphead" || $role == "hrmanager" || $role == "hrhead" || $role == "erpoperator")
						{
							if(isset($month) && isset($year))
							{
								// if($month < date("n") && $year <= date("Y"))
								if($salary_date <= $today_date)
								{
						?>
									<th>Approve</th>
						<?php
								}
							}
						}
						?>						
					</tr>
				</thead>
				<tbody>
					<?php 
					// debug($users);die;
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
						
							echo "<td>{$this->ERPfunction->total_sundays_category_wise($this->ERPfunction->get_user_category($user['user_id']),$month,$year)}</td>";
							echo "<td>{$user['used_pl']}</td>";
							echo "<td>{$user['remaining_pl']}</td>";	
							echo "<td>{$user['total_holidays']}</td>";
						/*	echo "<td>{$user['opening_pl']}</td>";
							echo "<td>{$user['new']}</td>";
							echo "<td>{$user['used_pl']}</td>";
							echo "<td>{$user['remaining_pl']}</td>"; */
							echo "<td>{$user['payable_days']}</td>";
							echo "<td>";
							if($role == "erphead" || $role == "hrmanager" || $role == "hrhead" || $role == "erpmanager" || $role == "erpoperator" || $role == "siteaccountant")
							{
								echo "<a href='{$this->request->base}/attendance/editattendance/{$user['user_id']}/{$month}/{$year}' target='_blank' class='btn btn-clean btn-info'><i class='icon-edit'></i>Edit</a>";
							}
							
							if($role == "erphead" || $role == "erpmanager" || $role == "siteaccountant" || $role == "hrhead" || $role == "hrmanager" || $role == "constructionmanager" || $role == "erpoperator")
							{
								echo "  <a href='{$this->request->base}/attendance/vieweditattendance/{$user['user_id']}/{$month}/{$year}' target='_blank' class='btn btn-clean btn-primary'><i class='icon-eye-open'></i>View</a>";
							}
							
							echo "</td>";
							if($role == "erphead" || $role == "ceo" || $role == "md" || $role == "projectdirector" || $role == "hrhead" || $role == "hrmanager" || $role == "constructionmanager" || $role == "erpoperator")
							{
							if(isset($month) && isset($year))
							{
							if($salary_date <= $today_date)
							{
								echo "<td>";
								echo "<input type='checkbox' name='approve_box' class='approve_check' data_id='{$user['id']}'>";
								echo "</td>";
							}
							}
							}
							echo "<td></td>";
							echo "</tr>";
						}
					}
					?>
				</tbody>
			</table>
		</div>	
			<div class="content">
			<div class="col-md-2 pull-right">
				<?php
				if($role == "erphead" || $role == "hrmanager" || $role == "hrhead" || $role == "erpoperator")
				{
				if(isset($month) && isset($year))
				{
				if($salary_date <= $today_date)
				{
				?>
					<button type="button" class="btn btn-success multiple_approve">Approve </button>
				<?php } } } ?>
			</div>
		</div>
	</div>
</div>
</div>
<?php } ?>     
</div>