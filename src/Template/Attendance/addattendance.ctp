<?php
use Cake\Routing\Router;
?>
<div class="col-md-10" >
<script type="text/javascript">
var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
jQuery(document).ready(function() {
	jQuery("#attendance_form").validationEngine();
	jQuery(".datep").datepicker({changeMonth: true,changeYear:true,dateFormat: 'MM yy',maxDate: new Date()});
	jQuery('#employee_id').select2();
	
	jQuery("body").on("click", ".update-holiday", function(event){
		var month  = jQuery('#month').val();
		var year  = jQuery('#year').val();

		var curr_data = {month : month, year : year };

		jQuery.ajax({
			headers: {
					'X-CSRF-Token': csrfToken
				},
                type:"POST",
			url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'updateholiday'));?>",
			data:curr_data,
			async:false,
			success: function(response){
				$(".modal-content").html(response);
			},
			error: function (tab) {
				alert('error');
			}
		});
	});
	
	jQuery("body").on("click", ".remove_attendance", function(event){
		var row = $(this).attr("data-row-id");
		$("#tr_"+row).remove();
	});
	
	jQuery("body").on("click", "#update-holiday-value", function(){
		
		var month = jQuery("#holiday_month").val();
		var year = jQuery("#holiday_year").val();
		var holiday = jQuery("#holiday_number").val();
		
		if(holiday == "")
		{
			alert("Please Enter Holiday Properly.");
			return false;
		}
		var curr_data = { month: month , year : year , holiday : holiday };
					
		jQuery.ajax({
			headers: {
					'X-CSRF-Token': csrfToken
				},
                type:"POST",
			url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'updateholidayvalue'));?>",
			data:curr_data,
			async:false,
			success: function(response){
				if(response)
				{
					if(response)
					{
						$("#holidays").val(holiday);
						$(".holiday").html(holiday);
						$(".holiday").val(holiday);
						$('#load_modal').modal('hide');
						reviseAttendanceCalculation();
					}
					 
				}	
			},
			error: function (tab) {
				alert('error');
			}
		});
	});
  
	jQuery("body").on("change", ".count", function(){
		var row_id = $(this).attr("data-row-id");
		calculateAttendance(row_id);
	});
	function reviseAttendanceCalculation()
	{
		$( ".row_number" ).each(function() {
			var row_id = $(this).val();
			calculateAttendance(row_id);
		});
	}
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
	// jQuery("body").on("change", ".type", function(){
			// var type  = jQuery(this).val() ;
			
			// if(type == 'all')
			// {
				// $(".selected_project_div").css('display','block');
				// $("#project_id").prop('required',true);
				
				// $(".selected_users_div").css('display','none');
				// $("#employee_id").prop('required',false);
			// }
			// else
			// {
				// $(".selected_users_div").css('display','block');
				// $("#employee_id").prop('required',true);
				
				// $(".selected_project_div").css('display','none');
				// $("#project_id").prop('required',false);
			// }				 				
		// });
		
		jQuery("body").on("click",".save_attendance",function(){
	
		
			if(confirm("Are you sure,you want to Generate Attendace?"))
			{
				if(confirm("Are you sure,you want to Generate Attendace?"))
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
<div class="modal fade " id="load_modal" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content"></div>
    </div>
</div>
<?php 
// if(!$is_capable)
	// {
		// $this->ERPfunction->access_deniedmsg();
	// }
// else
// {
?>	
	<div class="block block-fill-white">
		<div class="head bg-default bg-light-rtl">
			<h2>Generate Attendace Records</h2>
			<div class="pull-right">
			
			<a href="<?php echo $this->request->base;?>/humanresource/index" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
			</div>
		</div>
		<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
		<div class="content controls">
		
			<div class="form-row">
				<div class="col-md-2">Employee At * :</div>
				<div class="col-md-3">
					<select class="select2" style="width:100%;" name="project_id" id="project_id">
						<?php 
							foreach($projects as $retrive_data)
							{
								echo '<option value="'.$retrive_data['project_id'] .'">'.
								$retrive_data['project_name'] .'</option>';
							}
						?>
					</select>
				</div>
				<div class="col-md-2">Month & Year * :</div>
				<div class="col-md-3">
					<input name="date" required="true" class="form-control validate[required] datep" value="<?php echo date("F Y"); ?>">
				</div>
			</div>
			<div class="form-row" >
				<div class="col-md-2 text-right">Pay Type</div>
					<div class="col-md-3">
						<select name="pay_type[]" style="width:100%" class="select2" multiple="multiple">
						<option value="All" selected>All</option>
						<option value="employee"  <?php //echo (in_array('employee',$selected_type)) ? "selected" : "";?>>Employee</option>
						<option value="consultant" <?php //echo (in_array('consultant',$selected_type)) ? "selected" : "";?>>P.T. Employee</option>
						<option value="temporary" <?php //echo (in_array('temporary',$selected_type)) ? "selected" : "";?>>Temporary</option>
						</select>
					</div>
				<div class="col-md-2">No of Holidays in Month</div>
				<div class="col-md-3">
					<input type="text" name="holidays" id="holidays" value="<?php echo (isset($month) && isset($year))?$this->ERPfunction->get_holiday_of_month($month,$year):0;?>">
				</div>
				<?php if(isset($month) && isset($year)){ ?>
				<div class="col-md-1">
				<button type="button" data-toggle="modal" data-target="#load_modal" class="btn btn-primary update-holiday">Update Holiday</button>
				</div>
				<?php } ?>
			</div>
			
			<div class="form-row" >				
				<div class="col-md-2"><button type="submit" name="generate" class="btn btn-primary">Generate</button></div>
			</div>
			
		</div>
		<?php echo $this->form->end(); ?>
		</br>
		</br>
		</hr>
		<div class="content list custom-btn-clean">
		<hr>
		<br>
			<?php echo $this->Form->Create('form2',['id'=>'attendance_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
			<table class="table table-bordered">
				<thead>
					<tr>
						<td align="center" rowspan="2"><strong>Employee No</strong></td>
						<td align="center" rowspan="2"><strong>PF Slip Ref. No.</strong></td>
						<td align="center" rowspan="2"><strong>Name of Employee</strong></td>
						<td align="center" rowspan="2"><strong>Designation</strong></td>
						<td align="center" rowspan="2"><strong>Employed At</strong></td>
						<td align="center" colspan="2"><strong>Total</strong></td>
						<td align="center" colspan="7"><strong>PL</strong></td>
						<td rowspan="2"><strong>Payable<br>Days</strong></td>
						<td align="center" rowspan="2"><strong></strong></td>
					</tr>
					<tr>
						<td><strong>P</strong></td>
						<td><strong>A</strong></td>
						<td><strong>Opening</strong></td>
						<td><strong>New</strong></td>
						<td><strong>Sunday</strong></td>
						<td><strong>H</strong></td>
						<td><strong>MAN</strong></td>
						<td><strong>Used</strong></td>
						<td><strong>Remaining</strong></td>	
					</tr>
				</thead>
				<tbody>
				<?php if(isset($attendance_data)){ ?>
					
					<input type="hidden" value="<?php echo $total_days; ?>" id="total_days">
					<input type="hidden" value="<?php echo $month; ?>" id="month">
					<input type="hidden" value="<?php echo $year; ?>" id="year">
					
					<?php
						$i = 0;
						foreach($attendance_data as $retrive){
					?>
					<tr id="tr_<?php echo $i; ?>">
						<td><?php echo $retrive["erp_users"]['user_identy_number']; ?></td>
						<td><?php echo $this->ERPfunction->get_user_pf_ref_no($retrive["erp_users"]['user_id']); ?></td>
						<td><?php echo $this->ERPfunction->get_employee_name($retrive["erp_users"]['user_id']); ?></td>
						<td><?php echo $this->ERPfunction->get_category_title($retrive["erp_users"]['designation']); ?></td>
						<td><?php echo $this->ERPfunction->get_projectname($retrive["erp_users"]['employee_at']); ?></td>
						<td><input type="text" name="attendance[present][]" data-row-id="<?php echo $i; ?>" class="form-control validate[max[<?php echo $total_days; ?>],custom[number]] present count" id="present_<?php echo $i;?>" value="<?php echo $retrive['total_present']; ?>"><input type="hidden" data-row-id="<?php echo $i; ?>" name="attendance[att_id][]" class="form-control" value="<?php echo $retrive['id']; ?>">
						<input type="hidden" name="attendance[employee_category][]" data-row-id="<?php echo $i; ?>" class="form-control employee_category" id="employee_category_<?php echo $i;?>" value="<?php echo $retrive["erp_users"]['category']; ?>">
						<input type="hidden" value="<?php echo $i; ?>" class="row_number">
						</td>
						<td>
						<span class="absent_<?php echo $i;?>"><?php echo $retrive['total_absent']; ?></span><input type="hidden" data-row-id="<?php echo $i; ?>" name="attendance[absent][]" class="form-control absent" id="absent_<?php echo $i;?>" value="<?php echo $retrive['total_absent']; ?>">
						</td>
						<td>
						<span class="opening_<?php echo $i;?>"><?php echo $this->ERPfunction->get_leave_balance($retrive["erp_users"]['user_id'],$month,$year); ?></span><input type="hidden" name="attendance[opening][]" data-row-id="<?php echo $i; ?>" class="form-control opening" id="opening_<?php echo $i;?>" value="<?php echo $this->ERPfunction->get_leave_balance($retrive["erp_users"]['user_id'],$month,$year); ?>">
						</td>
						<td>
						<span class="new_<?php echo $i;?>"><?php echo $retrive['new']; ?></span><input type="hidden" name="attendance[new][]" data-row-id="<?php echo $i; ?>" class="form-control new" id="new_<?php echo $i;?>" value="<?php echo $retrive['new']; ?>">
						</td>
						<td><span class="sunday_<?php echo $i;?>"><?php echo $this->ERPfunction->total_sundays_category_wise($retrive["erp_users"]['category'],$month,$year)?></span><input type="hidden" name="attendance[sunday][]" data-row-id="<?php echo $i; ?>" class="form-control sunday" id="sunday_<?php echo $i;?>" value="<?php echo $this->ERPfunction->total_sundays_category_wise($retrive["erp_users"]['category'],$month,$year)?>">
						</td>
						<td><span class="holiday holiday_<?php echo $i;?>"><?php echo $this->ERPfunction->get_holiday_of_month($month,$year); ?></span><input type="hidden" name="attendance[holiday][]" data-row-id="<?php echo $i; ?>" class="form-control holiday" id="holiday_<?php echo $i;?>" value="<?php echo $this->ERPfunction->get_holiday_of_month($month,$year); ?>">
						</td>
						<td>
						<input type="text" name="attendance[manual][]" data-row-id="<?php echo $i; ?>" class="form-control manual count validate[custom[number]]" id="manual_<?php echo $i;?>" value="<?php echo $retrive['man_pl']; ?>">
						</td>
						<td>
						<span class="used_<?php echo $i;?>"><?php echo $retrive['used_pl']; ?></span><input type="hidden" name="attendance[used][]" data-row-id="<?php echo $i; ?>" class="form-control used" id="used_<?php echo $i;?>" value="<?php echo $retrive['used_pl']; ?>">
						</td>
						<td>
						<span class="remaining_<?php echo $i;?>"><?php echo $retrive['remaining_pl']; ?></span><input type="hidden" name="attendance[remaining][]" data-row-id="<?php echo $i; ?>" class="form-control remaining" id="remaining_<?php echo $i;?>" value="<?php echo $retrive['remaining_pl']; ?>">
						</td>
						<td>
						<span class="payable_days_<?php echo $i;?>"><?php echo $retrive['payable_days']; ?></span><input type="hidden" name="attendance[payable_days][]" data-row-id="<?php echo $i; ?>" class="form-control payable_days" id="payable_days_<?php echo $i;?>" value="<?php echo $retrive['payable_days']; ?>">
						</td>
						<td><a href="javascript:void(0)" data-row-id="<?php echo $i; ?>" class="btn btn-danger btn-xs remove_attendance" title="Remove Attendace from list"><span class="icon-trash"></span> </a></td>
					</tr>
					<?php $i++; } } ?>
					
				</tbody>
				
			</table>
			<?php if(isset($attendance_data) && !empty($attendance_data)){ ?>
			<br>
			<br>
			<center><button type="submit" name="save_attendance" class="btn btn-primary save_attendance">Submit</button></center>
			<br>
			<br>
			<?php } ?>
			<?php echo $this->form->end(); ?>
			
		</div>
	</div>
<?php 
 // }
 ?>     
</div>
						