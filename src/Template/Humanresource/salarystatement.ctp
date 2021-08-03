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
{
?>
<style>
select[multiple], select[size] {
    height: 2px !important;
}
</style>
<script type="text/javascript">
var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
jQuery(document).ready(function() {
	jQuery(".datep").datepicker({changeMonth: true,changeYear:true,dateFormat: 'MM yy'});
	jQuery("#userlist").DataTable();
	
	/* following code for approve single record */
	// $("body").on("change",".approve",function(){
		// if(confirm("Are you sure you want to approve?"))
		// {
			// if(confirm("Are you sure ypu want to approve?"))
			// {
				// var slip_id = $(this).attr('id');
				// var date = $(this).attr('date');
				// var name = $(this).attr('uname');
				// var user_email = $(this).attr('user_email');
				// var url = $(this).attr('data-url');
				// var data = {slip_id:slip_id,date:date,name:name,user_email:user_email};
				// $.ajax({
					// url : url,
					// type : "POST",
					// data : data,
					// success : function(response)
							// {
							  
							// },
					// error : function(e)
							// {
								// console.log(e.responseText);
							// }
				// });
				
			// }else{
				// $(this).attr("checked",false);
			   // }
		// }else{
				// $(this).attr('checked', false);
			// }
	// });
	
	/* Following code for multiple salary record approve at a time */
	jQuery('body').on('click','.multiple_approve',function(){

		if(jQuery(".approve").is(":checked")) {		
			if(confirm("Are you sure you want to approve?")) {
				if(confirm("Are you sure ypu want to approve?")) {
					var mail_check = jQuery('.mail_check:checked').val();
					var val_arr = [];
					jQuery(".approve:checked").each(function(){
					
						var slip_id = $(this).attr('id');
						var date = $(this).attr('date');
						var name = $(this).attr('uname');
						var user_email = $(this).attr('user_email');
						
						val_arr.push({
							slip_id : slip_id,
							date : date,
							name : name,
							user_email : user_email
						});
					});	
					urlstring ="<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'multipleapprovesalaryslip'));?>";
					var curr_data = {
						val_arr:val_arr ,
						mail_check:mail_check
					};		
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
				}
			}
		}else{
			alert('Please Select Record');
		}				
	});
});
</script>
<?php 
$date = (isset($_POST["date"])) ? $_POST["date"] : "";
$project_id = (isset($_POST["project_id"])) ? $_POST["project_id"] : "";
?>	
<div class="row">
	<div class="col-md-12">
		<div class="block">		
			<div class="head bg-default bg-light-rtl">
				<h2>Pay Slip Approval</h2>
				<div class="pull-right">
				<a href="<?php echo $this->ERPfunction->action_link('Humanresource','index');?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
				</div>
			</div>
			<div class="content">
			<div class="col-md-12 filter-form">
			<?php echo $this->Form->Create('form1',['id'=>'eq_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
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
					<div class="col-md-2">
						<input name="date" required="true" class="form-control datep" value="<?php echo $date;?>">
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
				</div>
			<?php echo $this->Form->end();?>
			</div>
			</div>
		<div class="content list custom-btn-clean" style="overflow-y: scroll;">
		<script>
		jQuery(document).ready(function() {
		jQuery('#user_list').DataTable({responsive: true});
		} );
		</script>
			<table id="user_list"  class="dataTables_wrapper table table-striped table-hover">
				<thead>
					<tr>
						<th>Employee No</th>
						<!--<th>PF Slip Ref. No.</th>-->
						<th>Employee Name</th>
						<th>Designation</th>
						<th>Employee at</th>						
						<th>Monthly Salary</th>					
						<th>Total<br>Earning<br>(Rs.)</th>						
						<th>Prof. Tax</th>
						<th>EPF</th>
						<th>ESI</th>
						<th>Loan Repayment/ Advance</th>
						<th>Mobile Bill Recovery</th>
						<th>TDS</th>
						<th>Other Deductions</th>
						<th>Net Pay</th>
						<th class="none">Action</th>
						<?php 
						if($this->ERPfunction->retrive_accessrights($role,'approvesalaryslip')==1)
						{
							?>
						<th>Approve</th>
						<?php } ?>
						<th class="none">Email</th>
					</tr>
				</thead>
				<tbody>
					<?php

						$i = 1;
					if(isset($salary_data))
					{
						$rows = array();
						$rows[] = array("Employee No","Employee Name","Designation","Employee at","Monthly Salarey","Total Earning(Rs.)","Prof. Tax","EPF","ESI","Loan Repayment/ Advance","Mobile Bill Recovery","TDS","Other Deductions","Net Pay");
						
						foreach($salary_data as $retrive_data)
						{
							// debug($retrive_data);die;
						$csv = array();
						?>
							<tr>								
								<td><?php echo ($csv[] = $retrive_data["erp_users"]['user_identy_number']);?></td>
								<!--<td><?php //echo ($csv[] = $this->ERPfunction->get_user_pf_ref_no($retrive_data["erp_users"]['user_id']));?></td>-->
								<td><?php echo ($csv[] = $retrive_data['erp_users']['first_name'] ." ". $retrive_data['erp_users']['last_name']);?></td>						
								<td><?php echo ($csv[] = $this->ERPfunction->get_category_title($retrive_data["erp_users"]['designation']));?></td>								
								<td><?php echo ($csv[] = $this->ERPfunction->get_projectname($retrive_data["employee_at"]));?></td>								
								<td><?php echo($csv[] = $retrive_data['erp_users']['monthly_pay']);?></td>
								<td><?php echo ($csv[] = $retrive_data["total_earning"]);?></td>
								<td><?php echo ($csv[] = $retrive_data['pro_tax']);?></td>
								<td><?php echo ($csv[] = $retrive_data['epf']);?></td>
								<td><?php echo ($csv[] = $retrive_data['esi']);?></td>
								<td><?php echo ($csv[] = $retrive_data["loan_payment"]);?></td>
								<td><?php echo ($csv[] = $retrive_data['mobile_bill_recovery']);?></td>
								<td><?php echo ($csv[] = $retrive_data['tax_deducted_source']);?></td>
								<td><?php echo ($csv[] = $retrive_data['others']);?></td>
								<td><?php echo ($csv[] = $retrive_data['net_pay']);?></td>
								<td>
								<?php 
								if($this->ERPfunction->retrive_accessrights($role,'editsalaryslip')==1)
								{
									if($retrive_data['salaryslip_type'] == "salary_slip")
									{
										echo $this->Html->link("<i class='icon-pencil'></i> Edit",array('action' => 'editsalaryslip',$retrive_data['slip_id']),
										array('class'=>'btn btn-primary btn-clean','escape'=>false,"target"=>"_blank"));
									}elseif($retrive_data['salaryslip_type'] == "voucher"){
										echo $this->Html->link("<i class='icon-pencil'></i> Edit",array('action' => 'editsalaryvoucher',$retrive_data['slip_id']),
										array('class'=>'btn btn-primary btn-clean','escape'=>false,"target"=>"_blank"));
									}elseif($retrive_data['salaryslip_type'] == "labourbill"){
										echo $this->Html->link("<i class='icon-pencil'></i> Edit",array('action' => 'editsalarybill',$retrive_data['slip_id']),
										array('class'=>'btn btn-primary btn-clean','escape'=>false,"target"=>"_blank"));
									}else{
										echo $this->Html->link("<i class='icon-pencil'></i> Edit",array('action' => 'editsalaryslip',$retrive_data['slip_id']),
										array('class'=>'btn btn-primary btn-clean','escape'=>false,"target"=>"_blank"));
									}
								echo ' ';
								}
								if($this->ERPfunction->retrive_accessrights($role,'salarystatement')==1)
								{
								
								if($retrive_data['salaryslip_type'] == "salary_slip")
								{
									echo $this->Html->link("<i class='icon-eye-open'></i> View",array('action' => 'viewsalaryslip', $retrive_data['slip_id']),
									array('class'=>'btn btn-info btn-clean','escape'=>false,"target"=>"_blank"));
								}elseif($retrive_data['salaryslip_type'] == "voucher"){
									echo $this->Html->link("<i class='icon-eye-open'></i> View",array('action' => 'viewsalaryvoucher', $retrive_data['slip_id']),
									array('class'=>'btn btn-info btn-clean','escape'=>false,"target"=>"_blank"));
								}elseif($retrive_data['salaryslip_type'] == "labourbill"){
									echo $this->Html->link("<i class='icon-eye-open'></i> View",array('action' => 'viewsalarybill', $retrive_data['slip_id']),
									array('class'=>'btn btn-info btn-clean','escape'=>false,"target"=>"_blank"));
								}else{
									echo $this->Html->link("<i class='icon-eye-open'></i> View",array('action' => 'viewsalaryslip', $retrive_data['slip_id']),
									array('class'=>'btn btn-info btn-clean','escape'=>false,"target"=>"_blank"));
								}
								echo ' ';
								}

								if($this->ERPfunction->retrive_accessrights($role,'deletesalaryslip')==1)
								{
								echo $this->Html->link("<i class='icon-trash'></i> Delete",array('action' => 'deletesalaryslip', $retrive_data['slip_id'],$month,$year),
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
								
								<?php 
								if($this->ERPfunction->retrive_accessrights($role,'approvesalaryslip')==1)
								{
									?>
									<td>
									<input type="checkbox" data-url="<?php echo $this->request->base;?>/ajaxfunction/approvesalaryslip" class="approve" uname="<?php echo $retrive_data['erp_users']['first_name'] ." ". $retrive_data['erp_users']['last_name'];?>" user_email="<?php echo $retrive_data["erp_users"]["email_id"];?>" date="<?php echo date("F Y",strtotime("1"."-".$retrive_data["month"]."-".$retrive_data["year"]));?>" id="<?php echo $retrive_data["slip_id"];?>" />
									</td>
								<?php } ?>
								
								<td>
								<?php echo $retrive_data["erp_users"]['email_id']; ?>
								</td>
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
			if(!empty($salary_data)){
			?>
			<div class="content">
			<div class="col-md-2"><a href="javascript:void(0);" class="btn btn-success" id="fullscreen" url='<?php echo $_SERVER['REQUEST_URI']; ?>' onClick="DoFullScreen()" >View Full Screen</a></div>
			<div class="col-md-2">
			<?php echo $this->Form->create('export_csv',['method'=>'post']); ?>
				<input type="hidden" name="rows" value='<?php echo base64_encode(serialize($rows));?>'>
				<input type="submit" class="btn btn-success" value="Export To Excel" name="export_csv">
				<?php echo $this->Form->end(); ?>
			</div>
			<div class="col-md-2">
				<?php echo $this->Form->create('export_pdf',['method'=>'post']); ?>
				<input type="hidden" name="rows" value='<?php echo base64_encode(serialize($rows));?>'>
				<input type="submit" class="btn btn-success" value="Export To PDF" name="export_pdf">
				<?php echo $this->Form->end(); ?>
			</div>
			
			<!--<div class="col-md-2" style="margin-left:100px;">
				<div class="radiobox-inline" >
					<label><input type="radio" checked name="mail_check" class="mail_check" value="1"/> Enable</label>
				</div>
				<div class="radiobox-inline" >
					<label><input type="radio" name="mail_check" value="0" class="mail_check" />Disable</label>
				</div>
			</div>-->
			
			<div class="col-md-4 pull-right">
				<div class="radiobox-inline" >
					<label><input type="radio" checked name="mail_check" class="mail_check" value="1"/>Send Mail</label>
				</div>
				<div class="radiobox-inline" >
					<label><input type="radio" name="mail_check" value="0" class="mail_check" />Don't Send Mail</label>
				</div>
				<?php if($this->ERPfunction->retrive_accessrights($role,'approvesalaryslip')==1)
								{ ?>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<button type="button" class="btn btn-success multiple_approve">Approve </button>
								<?php } ?>
			</div>
			</div>
			<?php } }?>
		</div>
		</div>
	</div>
</div>
<?php } ?>
</div>