<div class="col-md-10" >
		<?php 
if(!$is_capable)
	{
		$this->ERPfunction->access_deniedmsg();
	}
else
{
?>
<style>
select[multiple], select[size] {
    height: 2px !important;
}
</style>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery(".datep").datepicker({changeMonth: true,changeYear:true,dateFormat: 'MM yy'});
	jQuery("#userlist").DataTable();

	$("body").on("change",".approve",function(){
		if(confirm("Are you sure ypu want to approve?"))
		{
			if(confirm("Are you sure ypu want to approve?"))
			{
			
				var slip_id = $(this).attr('id');
				var url = $(this).attr('data-url');
				var data = {slip_id:slip_id};
				$.ajax({
					url : url,
					type : "POST",
					data : data,
					success : function(response)
							{
							  location.reload();
							},
					error : function(e)
							{
								console.log(e.responseText);
							}
				});
				
			}else{
				$(this).attr("checked",false);
			   }
		}else{
				$(this).attr('checked', false);
			}
	});
	
});
</script>
<?php 
$from_date = (isset($_POST["from_date"])) ? $_POST["from_date"] : "";
$to_date = (isset($_POST["to_date"])) ? $_POST["to_date"] : "";
$project_id = (isset($_POST["project_id"])) ? $_POST["project_id"] : "";
?>	
<div class="row">
	<div class="col-md-12">
		<div class="block">		
			<div class="head bg-default bg-light-rtl">
				<h2>Pay Records </h2>
				<div class="pull-right">
				<a href="<?php echo $this->ERPfunction->action_link('Humanresource','index');?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
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
						   <option value="<?php echo $retrive_data['user_id'];?>"><?php echo $retrive_data['first_name']." ".$retrive_data['last_name'];?></option>
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
					<div class="col-md-2 text-right">Pay Type</div>
					<div class="col-md-3">
						<select name="pay_type[]" style="width:100%" class="select2" multiple="multiple">
						<option value="All">All</option>
						<option value="employee" selected <?php //echo (in_array('employee',$selected_type)) ? "selected" : "";?>>Employee</option>
						<option value="consultant"  <?php //echo (in_array('consultant',$selected_type)) ? "selected" : "";?>>Labour</option>
						<option value="temporary" <?php //echo (in_array('temporary',$selected_type)) ? "selected" : "";?>>Temporary</option>
						</select>
					</div>
					
					<div class="col-md-2 col-sm-offset-1 text-right">Status</div>
					<div class="col-md-4">
						<select name="status" style="width: 100%;" class="select2">
							<!--<option value="All" selected>All</option>-->
							<option value="working">Working </option>
							<option value="resigned">Resigned / Non-working</option>
						</select>
					</div>
				</div>
				
				<div class="form-row">
					<div class="col-md-1 col-sm-offset-5">
						<input type="submit" name="go" id="go" class="btn btn-primary" value="Go">
					</div>
				</div>
			</form>
		</div>
		</div>
		<div class="content list custom-btn-clean">
		<script>
		jQuery(document).ready(function() {
		jQuery('#user_list').DataTable({
			responsive:  {
						details: {
							type: 'column',
							target: -1
						}
					},
					columnDefs: [ {
						className: 'control',
						orderable: false,
						targets:   -1
					} ] });
		} );
</script>
			<table id="user_list"  class="dataTables_wrapper table table-striped table-hover" style="width:100%">
				<thead>
					<tr>
						<th>Employee No</th>
						<th>Employee Name</th>
						<th>Designation</th>
						<th>Pay Type</th>						
						<th>Employed at</th>						
						<th>Month & Year</th>						
						<th>Payable Days</th>						
						<th>Monthly Salary</th>						
						<th>Net<br>Pay<br>(Rs.)</th>						
						<th>A/C No.</th>
						<th>Bank</th>
						<th>Branch</th>
						<th>IFSC Code</th>
						<th>Action</th>
						<th></th>	
					</tr>
				</thead>
				<tbody>
					<?php
						$i = 1;
					if(isset($salary_data))
					{
						$rows = array();
						$rows[] = array("Employee No","Employee Name","Designation","Pay Type","Employed at","Month & Year","Payable Days","CTC(Month)(Rs.)","NetPay(Rs.)","A/C No.","Bank","Branch","IFSC Code");
						
						foreach($salary_data as $retrive_data)
						{
							$csv = array(); 
							$curr_date = "{$retrive_data['year']}-{$retrive_data['month']}-01";
							$curr_date = date("Y-m-d",strtotime($curr_date));
						?>
							<tr>								
								<!--<td><?php echo ($csv[] = $this->ERPfunction->get_user_pf_ref_no($retrive_data["erp_users"]['user_id']));?></td>-->
								<td><?php echo ($csv[] = $retrive_data["erp_users"]['user_identy_number']);?></td>
								<td><?php echo ($csv[] = $retrive_data['erp_users']['first_name'] ." ". $retrive_data['erp_users']['last_name']);?></td>						
								<td><?php echo ($csv[] = $this->ERPfunction->get_category_title($retrive_data["erp_users"]['designation']));?></td>								
								<td><?php echo ($csv[] = $this->ERPFunction->get_pay_type($retrive_data["erp_users"]["pay_type"]));?></td>
								<td><?php echo ($csv[] = $this->ERPfunction->get_projectname($retrive_data["erp_users"]["employee_at"]));?></td>
								<td><?php echo ($csv[] = $retrive_data['erp_users']['total_salary']);?></td>
								<td><?php echo ($csv[] = $retrive_data["payable_days"]);?></td>
								<td><?php echo ($csv[] = $retrive_data['erp_users']['monthly_pay'] );?></td>
								<td><?php echo ($csv[] = $retrive_data["net_pay"]);?></td>
								<td><?php echo ($csv[] = $retrive_data['erp_users']["ac_no"]);?></td>
								<td><?php echo ($csv[] = $retrive_data['erp_users']["bank"]);?></td>
								<td><?php echo ($csv[] = $retrive_data['erp_users']["branch"]);?></td>
								<td><?php echo ($csv[] = $retrive_data['erp_users']["ifsc_code"]);?></td>
								<td>
								<?php 
								if($this->ERPfunction->retrive_accessrights($role,'salaryrecords')==1)
								{
									if($retrive_data['salaryslip_type'] == "salary_slip")
									{
										echo $this->Html->link("<i class='icon-eye-open'></i> View",array('action' => 'viewsalaryslip', $retrive_data['slip_id']),
										array('class'=>'btn btn-info btn-clean','target'=>'blank','escape'=>false));
									}elseif($retrive_data['salaryslip_type'] == "voucher"){
										echo $this->Html->link("<i class='icon-eye-open'></i> View",array('action' => 'viewsalaryvoucher', $retrive_data['slip_id']),
										array('class'=>'btn btn-info btn-clean','target'=>'blank','escape'=>false));
									}elseif($retrive_data['salaryslip_type'] == "labourbill"){
										echo $this->Html->link("<i class='icon-eye-open'></i> View",array('action' => 'viewsalarybill', $retrive_data['slip_id']),
										array('class'=>'btn btn-info btn-clean','target'=>'blank','escape'=>false));
									}else{
										echo $this->Html->link("<i class='icon-eye-open'></i> View",array('action' => 'viewsalaryslip', $retrive_data['slip_id']),
										array('class'=>'btn btn-info btn-clean','target'=>'blank','escape'=>false));
									}
								
								echo ' ';
								}
								if($this->ERPfunction->retrive_accessrights($role,'unapprovesalaryslip')==1)
								{
								echo $this->Html->link("<i class='icon-remove'></i>Unapprove&nbsp;",array('action' => 'unapprovesalaryslip', $retrive_data['slip_id']),
								array('class'=>'btn btn-danger btn-clean','escape'=>false));
								}
								if($this->ERPfunction->retrive_accessrights($role,'printsalaryslip')==1)
								{
									if($retrive_data['salaryslip_type'] == "salary_slip")
									{
										echo $this->Html->link("<i class='icon-print'></i> Print",array('action' => 'printsalaryslip', $retrive_data['slip_id']),
										array('class'=>'btn btn-success btn-clean','escape'=>false,"target"=>"_blank"));
									}elseif($retrive_data['salaryslip_type'] == "voucher"){
										echo $this->Html->link("<i class='icon-print'></i> Print",array('action' => 'printsalaryvoucher', $retrive_data['slip_id']),
										array('class'=>'btn btn-success btn-clean','escape'=>false,"target"=>"_blank"));
									}elseif($retrive_data['salaryslip_type'] == "labourbill"){
										echo $this->Html->link("<i class='icon-print'></i> Print",array('action' => 'printsalarybill', $retrive_data['slip_id']),
										array('class'=>'btn btn-success btn-clean','escape'=>false,"target"=>"_blank"));
									}else{
										echo $this->Html->link("<i class='icon-print'></i> Print",array('action' => 'printsalaryslip', $retrive_data['slip_id']),
										array('class'=>'btn btn-success btn-clean','escape'=>false,"target"=>"_blank"));
									}
								
								}
								?>
								</td>
								<td></td>

							</tr>
						<?php
						$rows[] = $csv;
						$i++;
						}
					}
					?>
				</tbody>
			</table>
			<?php
			if(isset($salary_data))
			{
			  if(!empty($salary_data))
				{
			?>
			<div class="content">
			<div class="col-md-2"><a href="javascript:void(0);" class="btn btn-success" id="fullscreen" url='<?php echo $_SERVER['REQUEST_URI']; ?>' onClick="DoFullScreen()" >View Full Screen</a></div>
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
			</div>
			<?php } } ?>
		</div>
		</div>
	</div>
</div>
<?php } ?>
</div>