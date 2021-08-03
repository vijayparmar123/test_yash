<?php
use Cake\Routing\Router;

$salary_date = "{$year}-{$month}-01";

$salary_date = date("Y-m-d",strtotime($salary_date));
$issue_date = $year."-".$month."-06";
$issue_date = date('d-m-Y', strtotime('+1 month', strtotime($issue_date)));
?>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('#user_form').validationEngine();
	
	jQuery('#date_of_birth,#as_on_date,#date_of_joining').datepicker({
		dateFormat: "dd-mm-yy",
		  changeMonth: true,
	        changeYear: true,
	        yearRange:'-65:+0',
	        onChangeMonthYear: function(year, month, inst) {
	            jQuery(this).val(month + "-" + year);
	        }
    });	
});
</script>
<style>
.label{
	font-size : 65%;
}
</style>
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
{
?>				
	<div class="block block-fill-white">
		<div class="head bg-default bg-light-rtl">
			<h2>Salary Slip</h2>
			<div class="pull-right">
			<a href="<?php echo $this->request->base;?>/humanresource/salaryslip" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
			</div>
		</div>
		
		<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
		
		<input type="hidden" name="user_action" class="form-control" value="<?php echo $user_action;?>"/>	
		
	   
	  
		<div class="content controls">			
			<div class="form-row">
				<div class="col-md-12 text-center salar_slip_head"><strong>YASHNAND ENGINEERS AND CONTRACTORS PVT. LTD.</strong></div>
				<div class="col-md-12 text-center">
					<strong>Address:</strong> 214/5, Khyati Complex, Near Mithakhali Underbridge, Ellisbridge, Ahmedabad.
				</div>
			</div>
			<div class="form-row">
				<?php
					$dateObj = DateTime::createFromFormat('!m', $month);
					$monthName = $dateObj->format('F'); 
				?>
				<div class="col-md-12 text-center">
					<h3 style="margin-bottom: -15px;"><strong>Pay Slip for <?php echo $monthName ."-". date('y',strtotime($salary_date));?></strong></h3>
				</div>			
			</div>
			<div class="row" style="clear: both;">
				<div class="col-md-12">
					<hr style="margin-bottom: 2px;" />
				</div>
			</div>
			<div class="row" style="background: aliceblue;margin: 0;">
			<div class="col-md-12 col-sm-offset-1">
			<div class="form-row">				
				<div class="col-md-4"><strong>Employee No : </strong>
					<span><?php echo $this->ERPfunction->get_user_identy_number($user_id); ?></span>				
					<!--<span><?php echo $user_data["pf_ref_no"]; ?></span>-->				
				</div>
				
				<div class="col-md-4"><strong>Full Name : </strong> 				
					<span><?php echo $user_data["first_name"] ." ". $user_data["middle_name"] ." ".$user_data["last_name"];?> </span>
				</div>
			
				
				<div class="col-md-4"><strong>Date of Issue : </strong>
					<span><?php echo $issue_date;?></span>
				</div>
			
			
			</div>						
			<div class="form-row">			
				<div class="col-md-4"><strong>Employee at : </strong>
					<span><?php echo $this->ERPfunction->get_projectname($user_data["employee_at"]);?></span>					
				</div>
				
				<div class="col-md-4"><strong>Designation : </strong>
					<span><?php
						  echo $this->ERPfunction->get_category_title($user_data["designation"]); ?>
						  <input type="hidden" name="designation" value="<?php echo $user_data["designation"]; ?>" >
					</span>
				</div>
				
				<!--<div class="col-md-4"><strong>Date of Joining : </strong>
					<span><?php echo $user_data["date_of_joining"]->format("d-m-y");?></span>
					<input type="hidden" name="date_of_joining" value="<?php echo $user_data["date_of_joining"]->format("Y-m-d"); ?>" >
				</div>-->
				<div class="col-md-4"><strong>Pay Type : </strong>
					<span><?php echo strtoupper($this->ERPFunction->get_pay_type($user_data["pay_type"]));?></span>
					<input type="hidden" name="pay_type" value="<?php echo $user_data["pay_type"]; ?>" >
				</div>
		   </div>
		   <div class="form-row">
				<!--<div class="col-md-4"><strong>E. P. F. No : </strong>
					<span><?php //echo $user_data["epf_no"];?></span>
					<input type="hidden" name="epf_no" value="<?php echo $user_data["epf_no"]; ?>" >
				</div>-->
						
				<!--<div class="col-md-4"><strong>Pay Type : </strong>
					<span><?php echo strtoupper($this->ERPFunction->get_pay_type($user_data["pay_type"]));?></span>
					<input type="hidden" name="pay_type" value="<?php echo $user_data["pay_type"]; ?>" >
				</div>-->
				<div class="col-md-4"><strong>Aadhar Card No : </strong>
					<span><?php echo $user_data["adhaar_card_no"];?></span>
					<input type="hidden" name="adhaar_card_no" value="<?php echo $user_data["adhaar_card_no"]; ?>" >
				</div>
				
				<div class="col-md-4"><strong>PAN Card No : </strong>
					<span><?php echo $user_data["pan_card_no"];?></span>
					<input type="hidden" name="pan_card_no" value="<?php echo $user_data["pan_card_no"]; ?>" >
				</div>
				<!--<div class="col-md-4"><strong>Payable Days : </strong>
					<span><?php echo $att_detail["payable_days"];?></span>
				</div>-->
				
				<div class="col-md-4"><strong>Date of Birth : </strong>
					<span><?php echo date('d-m-y',strtotime($user_data["date_of_birth"]));?></span>
					<input type="hidden" name="ac_no" value="<?php echo $user_data["date_of_birth"]; ?>" >
				</div>
				
			</div>
		  <div class="form-row">				
				<!--<div class="col-md-4"><strong>ESI Number : </strong>
					<span><?php //echo $user_data["esi_no"];?></span>
					<input type="hidden" name="esi_no" value="<?php echo $user_data["esi_no"]; ?>" >
				</div>-->
				
				<div class="col-md-4"><strong>Payable Days : </strong>
					<span><?php echo $att_detail["payable_days"];?></span>
				</div>
				<!--<div class="col-md-4"><strong>PAN Card No : </strong>
					<span><?php echo $user_data["pan_card_no"];?></span>
					<input type="hidden" name="pan_card_no" value="<?php echo $user_data["pan_card_no"]; ?>" >
				</div>-->
				
				<!--<div class="col-md-4"><strong>Aadhar Card No : </strong>
					<span><?php echo $user_data["adhaar_card_no"];?></span>
					<input type="hidden" name="adhaar_card_no" value="<?php echo $user_data["adhaar_card_no"]; ?>" >
				</div>-->
				
				<div class="col-md-4"><strong>Total No. of Days : </strong>
					<span><?php echo $total_days;?></span>
				</div>
				
				<div class="col-md-4"><strong>C.T.C(Year) : </strong>
					<span><?php echo $user_data["ctc"];?></span>
				</div>
				<!--
				<div class="col-md-2">Pay Rate</div>
				<div class="col-md-2"><input type="text" name="pay_rate" id="pay_rate" value="<?php //echo $pay_rate;?>" class="form-control" /></div>
				-->				
		   </div>
		   <div class="form-row">				
				<!--<div class="col-md-4"><strong>A/C No : </strong>
					<span><?php //echo $user_data["ac_no"];?></span>
					<input type="hidden" name="ac_no" value="<?php// echo $user_data["ac_no"]; ?>" >
				</div>
				
				<div class="col-md-4"><strong>Bank : </strong>
					<span><?php //echo $user_data["bank"];?></span>
					<input type="hidden" name="bank" value="<?php //echo $user_data["bank"]; ?>" >
				</div>-->
				<?php if($user_data["is_epf"] != 'no') { ?>
				<div class="col-md-4"><strong>EPF No : </strong>
					<span><?php echo $user_data["epf_no"];?></span>
					<input type="hidden" name="ac_no" value="<?php echo $user_data["epf_no"]; ?>" >
				</div>
				
				<div class="col-md-4"><strong>UAN No : </strong>
					<span><?php echo $user_data["uan_no"];?></span>
					<input type="hidden" name="bank" value="<?php echo $user_data["uan_no"]; ?>" >
				</div>
				<?php } ?>
				
				<?php if($user_data["is_esi"] != 'no') { ?>
				<div class="col-md-4"><strong>ESI No : </strong>
					<span><?php echo $user_data["esi_no"];?></span>
					<input type="hidden" name="ac_no" value="<?php echo $user_data["esi_no"]; ?>" >
				</div>
				<?php } ?>
			</div>
			
			<!--<div class="form-row">
				<div class="col-md-4"><strong>PF Slip Ref. No. : </strong>
					<span><?php echo $user_data["pf_ref_no"];?></span>
				</div>
			</div>-->
		   						
			
			<div class="form-row">
				<div class="col-md-12">
					<input type="hidden" name="user_id" value="<?php echo $user_data["user_id"];?>" />
					<input type="hidden" name="employee_at" value="<?php echo $user_data["employee_at"];?>" />
					<input type="hidden" name="month" value="<?php echo $month;?>" />
					<input type="hidden" name="year" value="<?php echo $year;?>" />
					<input type="hidden" name="total_days" id="total_days" value="<?php echo $total_days;?>" />
					<input type="hidden" name="payable_days" id="payable_days" value="<?php echo $att_detail["payable_days"];?>" />
				</div>
			</div>
			</div>
			</div>
			
			<div class="row">
				<div class="col-md-12">
					<hr style="margin-top: 2px;"/>
				</div>
			</div>
			
			<div class="form-row">
			<div class="col-md-6">                
                <div class="block block-drop-shadow bg-primary">
					<div class="header" style="background: #D99594;">
						<h2 style="width: 100%;text-align: center;">EARNINGS</h2>
					</div>
                    <div class="content" style="background: #F2DBDB;">
						<table class="table">
						<thead>
							<tr>
								<th>Salary Head</th>
								<th class="text-center" ></th>
								<th class="text-right" >Amount (Rs.)</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Basic Salary</td>
								<td align="center"><?php //echo $user_data["basic_salary"];?></td>
								<td class="text-right" id="count_basic_salary"><?php echo $basic_pay_amount;?></td>
								<input type="hidden" name="basic_pay_ctc" id="basic_pay_ctc" value="<?php echo $user_data["basic_salary"];?>" />
								<input type="hidden" name="basic_salary" id="" value="<?php echo $user_data["basic_salary"];?>" />
								<input type="hidden" name="basic_pay_amount" id="basic_pay_amount" value="<?php echo $basic_pay_amount;?>" />
							</tr>
							<tr>
								<td>Dearness Allowance (D.A.)</td>
								<td align="center"><?php //echo $user_data["da"];?></td>
								<td class="text-right" id="count_da"><?php echo $da_amount;?></td>
								<input type="hidden" name="da_ctc" id="da_ctc" value="<?php echo $user_data["da"];?>" />
								<input type="hidden" name="da" id="da" value="<?php echo $da_amount;?>" />
								<input type="hidden" name="da_amount" id="da_amount" value="<?php echo $da_amount;?>" />
							</tr>
							<tr>
								<td>House Rent Allowance (H.R.A.)</td> <!-- It was 'Accommodation Allowance' before -->
								<td align="center">
								<?php 
								if($acco_text == 1)
								{
									// echo "<input type='hidden' class='count_earning validate[required,custom[number],min[0]]' set='acco_allowance_amt' style='max-width:70px;text-align: center;display:inline' name='acco_ctc' placeholder='' value='".$user_data["acc_bill_paid"]."'/>";
									// echo $user_data["acc_bill_paid"];
									// echo " <h5 style='display:inline'><span class='label label-danger'>Bill Pd.</span></h5>";
									// echo '<input type="hidden" name="acco_allowance" id="acco_allowance" value="bill_paid" />';									
								}else{									
									/* echo $acco_allowance; */
									// echo $a_a = strtoupper(str_replace("Fixed","",str_replace("_"," ",$user_data["acco_allowance"])));
									// echo '<input type="hidden" name="acco_ctc" id="acco_ctc" value="'.$acco_allowance.'" />';
									// echo '<input type="hidden" name="acco_allowance" id="acco_allowance" value="'.$a_a.'" />';
								}
								?>
								</td>
								<td class="text-right" id="" <?php echo ($acco_text == 1) ? "style='padding-right:0;padding-top:0;padding-bottom:0;'" : "";?> >
								<?php 
								if($acco_text == 1)
								{ ?>
									<input type="text" class="count_earning" name="acco_amount" id="acco_allowance_amt" value="<?php echo $acco_allowance;?>" style="max-width:70px;text-align: right;display:inline" />
								<?php } else{ ?>
									<input type="hidden" name="acco_amount" id="acco_allowance_amt" value="<?php echo $acco_allowance;?>" style="max-width:70px;text-align: right;display:inline" />
									<?php echo $acco_allowance; 
								} ?>
								</td>
							</tr>
							<tr>
								<td>Conveyance</td> <!-- It was House Rent Allowance (H.R.A.)' before -->
								<td align="center"><?php //echo $user_data["hra"];?></td>
								<td class="text-right" id="count_hra"><?php echo $hra_amount;?></td>
								<input type="hidden" name="hra_ctc" id="hra_ctc" value="<?php echo $user_data["hra"];?>" />
								<input type="hidden" name="hra" id="hr" value="<?php echo $hra_amount;?>" />
								<input type="hidden" name="hra_amount" id="hra_amount" value="<?php echo $hra_amount;?>" />
							</tr>							
							<tr>
								<td>Transportation Allowance (T.A.)</td> <!-- It was 'Transportation Allowance' before -->
								<td align="center">
								<?php 
								if($trans_text == 1)
								{
									// echo "<input type='hidden' class='count_earning validate[required,custom[number],min[0]]' set='trans_allowance_amount' style='max-width:70px;text-align: center;display:inline' name='transport_ctc' placeholder='' value='".$user_data["trans_bill_paid"]."'/>";
									// echo $user_data["trans_bill_paid"];
									// echo " <h5 style='display:inline'><span class='label label-danger'>Bill Pd.</span></h5>";
									// echo '<input type="hidden" name="trans_allowance" id="trans_allowance" value="bill_paid" />';
								}else{
									/* echo $trans_allowance; */
									// echo $tra_a = strtoupper(str_replace("Fixed","",str_replace("_"," ",$user_data["trans_allowance"])));
									// echo '<input type="hidden" name="transport_ctc" id="transport_ctc" value="'.$trans_allowance.'" />';
									// echo '<input type="hidden" name="trans_allowance" id="trans_allowance" value="'.$tra_a.'" />';
								}
								?>
								</td>
								<td class="text-right" <?php echo ($trans_text == 1) ? "style='padding-right:0;padding-top:0;padding-bottom:0;'" : "";?> >
								<?php 
								if($trans_text == 1)
								{ ?>
										<input type="text" class="count_earning" id="trans_allowance_amount"  name="transport_amount" value="<?php echo $trans_allowance_amount;?>" style="max-width:70px;text-align: right;display:inline" />
							<?php } else{ ?>
							
									<input type="hidden" id="trans_allowance_amount"  name="transport_amount" value="<?php echo $trans_allowance_amount;?>"  />
									<span id="trans_allowance_amount" > <?php echo $trans_allowance_amount; ?></span> <?php
									} ?>
								</td>
							</tr>
							<tr>
								<td>Special Allowance</td>
								<td align="center">
								<?php 
								if($food_text == 1)
								{
									// echo "<input type='hidden' class='count_earning validate[required,custom[number],min[0]]' set='food_allowance_amount' style='max-width:70px;text-align: center;display:inline' name='food_ctc' placeholder='' value='".$user_data["food_bill_paid"]."' />";
									// echo $user_data["food_bill_paid"];
									// echo " <h5 style='display:inline'><span class='label label-danger'>Bill Pd.</span></h5>";
									// echo '<input type="hidden" name="food_allowance" id="food_allowance" value="bill_paid" />';
							}else{
									/* echo $food_allowance; */
									// echo $f_a = strtoupper(str_replace("Fixed","",str_replace("_"," ",$user_data["food_allowance"])));
									// echo '<input type="hidden" name="food_ctc" id="food_ctc" value="'.$food_allowance .'" />';
									// echo '<input type="hidden" name="food_allowance" id="food_allowance" value="'.$f_a .'" />';
								} ?>
								</td>
								<td class="text-right" id="" <?php echo ($food_text == 1) ? "style='padding-right:0;padding-top:0;padding-bottom:0;'" : "";?>>
								<?php 
								if($food_text == 1)
								{ ?>
									<input type="text" class="count_earning" name="food_amount" id="food_allowance_amount" value="<?php echo $food_allowance_amount;?>" style="max-width:70px;text-align: right;display:inline" />

							<?php
								}
								else{ ?>
									<input type="hidden" name="food_amount" id="food_allowance_amount" value="<?php echo $food_allowance_amount;?>" style="max-width:70px;text-align: right;display:inline" />
									<?php
									echo $food_allowance_amount;
								   }?>
								</td>
								
								
								
							</tr>
							<tr>
								<td>Medical Allowance</td>
								<td align="center"><?php //echo $user_data["medical_allowance"];?></td>
								<td class="text-right" id="count_ma"><?php echo $medical_amount;?></td>
								<input type="hidden" name="medical_ctc" id="medical_ctc" value="<?php echo $user_data["medical_allowance"];?>" />
								<input type="hidden" name="medical_allowance" id="" value="<?php echo $user_data["medical_allowance"];?>" />
								<input type="hidden" name="medical_amount" id="medical_amount" value="<?php echo $medical_amount;?>" />
							</tr>
														
						<!--	<tr>
								<td>Accommodation Allowance</td>
								<td align="center">
								<?php 
								if($acco_text == 1)
								{
									echo "<input class='count_earning validate[required,custom[number],min[0]]' set='acco_allowance_amt' style='max-width:70px;text-align: center;display:inline' name='acco_ctc' placeholder='' value='".$user_data["acc_bill_paid"]."'/>";
									echo " <h5 style='display:inline'><span class='label label-danger'>Bill Pd.</span></h5>";
									echo '<input type="hidden" name="acco_allowance" id="acco_allowance" value="bill_paid" />';									
								}else{
									/* echo $acco_allowance; */
									echo $a_a = strtoupper(str_replace("Fixed","",str_replace("_"," ",$user_data["acco_allowance"])));
									echo '<input type="hidden" name="acco_ctc" id="acco_ctc" value="'.$acco_allowance.'" />';
									echo '<input type="hidden" name="acco_allowance" id="acco_allowance" value="'.$a_a.'" />';
								}
								?>
								</td>
								<td class="text-right" id="acco_allowance_amt"><?php echo $acco_allowance;?></td>
								<input type="hidden" name="acco_amount" id="acco_allowance_amt_input" value="<?php echo $acco_allowance;?>" />
							</tr> -->
							<tr>
								<td>Mobile Allowance</td>
								<td align="center">
								<?php 
								if($mobile_text == 1)
								{
									// echo "<input type='hidden' class='count_earning validate[required,custom[number],min[0]]' set='mobile_allowance_amt' style='max-width:70px;text-align: center;display:inline' name='mobile_ctc' placeholder='' value='".$user_data["mobile_bill_paid"]."'/>";
									// echo $user_data["mobile_bill_paid"];
									// echo " <h5 style='display:inline'><span class='label label-danger'>Bill Pd.</span></h5>";
									// echo '<input type="hidden" name="mobile_allowance" id="mobile_allowance" value="bill_paid" />';		
								}else{
									/* echo $mobile_allowance; */
									// echo $m_a = strtoupper(str_replace("Fixed","",str_replace("_"," ",$user_data["mobile_allowance"])));
									// echo '<input type="hidden" name="mobile_ctc" id="mobile_ctc" value="'.$mobile_allowance.'" />';
									// echo '<input type="hidden" name="mobile_allowance" id="mobile_allowance" value="'.$m_a.'" />';
								} ?>
								</td>
								<td class="text-right" id=""  <?php echo ($mobile_text == 1) ? "style='padding-right:0;padding-top:0;padding-bottom:0;'" : "";?>>
								<?php 
								if($mobile_text == 1)
								{ ?>
									<input type="text" class="count_earning" name="mobile_amount" id="mobile_allowance_amt" value="<?php echo $mobile_allowance;?>" style="max-width:70px;text-align: right;display:inline" />
								<?php } else { ?>
									<input type="hidden" name="mobile_amount" id="mobile_allowance_amt" value="<?php echo $mobile_allowance;?>" />
								<?php
									echo $mobile_allowance;
								} ?>
								</td>
							</tr>
							<tr>
								<td>Other Allowance</td> 
								<td align="center"><?php //echo $user_data["other_allowance"];?></td>
								<td class="text-right" id="count_other_allowance"><?php echo $other_allowance;?></td>
								<input type="hidden" name="other_allowance_ctc" id="other_allowance_ctc" value="<?php echo $user_data["other_allowance"];?>" />
								<input type="hidden" name="other_allowance" id="" value="<?php echo $other_allowance;?>" />
								<input type="hidden" name="other_allowance_amount" id="other_allowance_amount" value="<?php echo $other_allowance;?>" />
							</tr>	
							
							<?php $all_field_total = $basic_pay_amount + $da_amount + $acco_allowance + $hra_amount + $trans_allowance_amount + $medical_amount + $food_allowance_amount + $mobile_allowance + $other_allowance; 
							
							$incentives = ($basic_pay_amount * $att_detail["payable_days"] / $total_days) - $all_field_total;
							$total_salary = $this->ERPfunction->get_user_ctc_month($user_id);
							$monthly_pay = $this->ERPfunction->get_user_monthly_pay($user_id);
							
							?>
							
							<tr>
								<td>Perfomance Incentives</td>
								<td class="text-center"></td>
								<td class="text-right" style="padding-right: 0px;">
									<input class='count_earning text-right validate[required,custom[number],min[0]]' id="incentive" set="" style='max-width:70px;display:inline' name="incentive_amount" value="<?php echo round(($monthly_pay * $att_detail["payable_days"] / $total_days) - $all_field_total); ?>">
								</td>
							</tr>	
							<tr>
								<td>Salary Difference</td>
								<td class="text-center"></td>
								<td class="text-right" style="padding-right: 0px;">
									<input class='count_earning text-right validate[required,custom[number],min[0]]' id="salary_diff" set="" style='max-width:70px;display:inline' name="salary_diff_amount" value="0">
								</td>
							</tr>
						</tbody>						
						</table>
					</div>
					<div class="footer" style="background: #D99594;">
						<strong>TOTAL EARNINGS <span style="float:right" id="total_earning">0</span></strong>
						<input type="hidden" name="total_earning" id="total_earning_input" value="0" />
					</div>
                </div>
            </div>
			
	
			<div class="col-md-6">                
                <div class="block block-drop-shadow">
					<div class="header" style="background:#FABF8F">
						<h2 style="width: 100%;text-align: center;">DEDUCTIONS</h2>
					</div>
                    <div class="content" style="background:#FBD4B4">
						<table class="table">
						<thead>
							<tr>
								<th>Salary Head</th>
								<th class="text-right">Amount (Rs.)</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Professional Tax</td>
								<td class="text-right">
									<span style="float:right" id="professional_tax">0</span>
									<input type="hidden" name="pro_tax" id="pro_tax" class="count_deduction text-right validate[required,custom[number],min[0]] pro_tax" style="width:120px;display:inline" value="0" />
								</td>
							</tr>
							<?php if($user_data['is_epf'] != 'no') { ?>
							<tr>
								<td>Employee Provident Fund (E.P.F.)</td>
								<?php
								
								$salyear = date('Y',strtotime($salary_date));
								$sdate = $salyear."-".$month."-"."01";
								$limitdate = date("Y-m-d",strtotime("2019-03-30"));
								if($sdate > $limitdate){
								$newepfvalue = round(($basic_pay_amount+$food_allowance_amount)*12/100,0);
								$newepfvalue = ($newepfvalue>1800)?1800:$newepfvalue;
								?>
								<td class="text-right">
									<span><?php echo $newepfvalue;?></span>
									<input type="hidden" name="epf" id="epf" class="count_deduction text-right validate[required,custom[number],min[0]]" style="width:120px;display:inline" value="<?php echo $newepfvalue;?>" />
								</td>
								<?php
								}else{
								?>
								<td class="text-right">
									<span><?php echo round($basic_pay_amount*12/100,0) ;?></span>
									<input type="hidden" name="epf" id="epf" class="count_deduction text-right validate[required,custom[number],min[0]]" style="width:120px;display:inline" value="<?php echo round($basic_pay_amount*12/100,0) ;?>" />
								</td>
								<?php } ?>
							</tr>
							<?php }else { ?>
							<input type="hidden" name="epf" id="epf" class="count_deduction text-right validate[required,custom[number],min[0]]" style="width:120px;display:inline" value="0" />
							<?php } ?>
							
							<?php if($user_data['is_esi'] != 'no') { ?>
							<tr>
								<td>Employee State Insurance (E.S.I.)</td>
								<td class="text-right">
									<span id="show_esi"><?php //echo round($basic_pay_amount*1.75/100,0) ;?></span>
									<input type="hidden" name="esi" id="esi" class="count_deduction text-right unhide_esi validate[required,custom[number],min[0]]" style="width:120px;display:inline" value="<?php //echo round($basic_pay_amount*1.75/100,0) ;?>" />
								</td>
							</tr>
							<?php }else { ?>
							<input type="hidden" name="esi" id="esi" class="count_deduction text-right validate[required,custom[number],min[0]]" style="width:120px;display:inline" value="0" />
							<?php } ?>
								<tr>
								<td>Loan Repayment / Advance &nbsp; <h5 style="display:inline;"><span class="label label-danger"> O/S : <?php echo $os_amt = $this->ERPfunction->get_loan_outstanding($user_data["user_id"]); ?> </span></h5></td>
								<td class="text-right">
									<input type="hidden" name="loan_outstanding" value="<?php echo $os_amt;?>" />
									<input name="loan_payment" id="loan_payment" class="count_deduction text-right validate[required,custom[number],min[0]]" style="width:120px;display:inline" value="<?php echo $installment_amount = $this->ERPfunction->get_installment($user_data["user_id"]);?>" />
								</td>
							</tr>
							<tr>
								<td>Mobile Bill Recovery
									<?php if($user_data["mobile_cug"] != 0)
										{ ?>
										&nbsp; <h5 style="display:inline;"><span class="label label-danger">Limit : <?php echo $user_data["mobile_cug"];?></span></h5>
										<?php } ?>
										<input type="hidden" name="mobile_cug" value="<?php echo $user_data["mobile_cug"];?>" />									
								</td>
								<td class="text-right">
									<input name="mobile_bill_recovery" id="mobile_bill_recovery" class="count_deduction text-right validate[required,custom[number],min[0]]" style="width:120px;display:inline" value="0" />
								</td>
							</tr>
							<tr>
								<td>Tax Deducted at Source (T.D.S.)</td>
								<td class="text-right">
									<input id="tax_deducted_source" name="tax_deducted_source" class="count_deduction text-right validate[required,custom[number],min[0]]" style="width:120px;display:inline" value="0" />
								</td>
							</tr>							
							<tr>
								<td style="<?php if($user_data['is_epf'] != 'no' && $user_data['is_esi'] != 'no'){ echo "padding-bottom:145px";}else if($user_data['is_epf'] == 'no' && $user_data['is_esi'] == 'no'){ echo "padding-bottom: 219px";}else if($user_data['is_epf'] != 'no' && $user_data['is_esi'] == 'no'){ echo "padding-bottom: 182px";}else{echo "padding-bottom: 182px";} ?>">Others</td>
								<td class="text-right" style="<?php if($user_data['is_epf'] != 'no' && $user_data['is_esi'] != 'no'){ echo "padding-bottom:133px";}else if($user_data['is_epf'] == 'no' && $user_data['is_esi'] == 'no'){ echo "padding-bottom: 206px";}else if($user_data['is_epf'] != 'no' && $user_data['is_esi'] == 'no'){ echo "padding-bottom: 169px";}else{echo "padding-bottom: 169px";} ?>">
									<input name="others" id="others" class="count_deduction text-right validate[required,custom[number],min[0]]" style="width:120px;display:inline" value="0" />
								</td>
							</tr>							
						</tbody> <!--
						<tfoot>
							<tr>
								<th style="padding-bottom: 22px;">TOTAL DEDUCTIONS</th>
								<th class="text-right" id="total_deduction" style="padding-bottom: 19px;">0</th>
								<input type="hidden" name="total_deduction" id="total_deduction_input" value="0" />
							</tr>							
						</tfoot>-->					
						</table>
					</div> 
					<div class="footer" style="background: #FABF8F;">
						<strong>TOTAL DEDUCTIONS<span style="float:right" id="total_deduction">0</span></strong>
						<input type="hidden" name="total_deduction" id="total_deduction_input" value="0" />
					</div>
					<div class="footer" style="background: #C0504D; color:#fff;">
						<h5 style="margin:6px 0 0 0;"><strong>NET PAY <span style="float:right" id="net_pay">0</span></strong></h5>
						<input type="hidden" name="net_pay" id="net_pay_input" value="0" />
					</div>
                </div>                                               
                
            </div>
				
			</div>			
			
			<div class="form-row">
				<div class="col-md-2"><button type="submit" id="generate" class="btn btn-primary"><?php echo $button_text;?></button></div>
			</div>
				
		</div>
		<?php $this->Form->end(); ?>
	</div>
<?php } ?>
         </div>
<script>


$(document).ready(function(){
	
	jQuery("body").on("click","#generate",function(){
	
		
			if(confirm("Are you sure,you want to Generate Salary Slip?"))
			{
				if(confirm("Are you sure,you want to Generate Salary Slip?"))
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
	
	count_total_earning();
	count_total_deduction();
	count_net_pay();
	
	$("body").on("change",".count_earning",function(){			
		// parseFloat(pay_wa)
		//.toFixed(2)
		var curr_amount = $(this).val();
		var field = $(this).attr("set");
		var total_days = $("#total_days").val();
		var payable_days = $("#payable_days").val();
		
		var ctc_amt = (parseFloat(curr_amount) / parseFloat(total_days)) * parseFloat(payable_days);
		ctc_amt = ctc_amt.toFixed(2);
		if(field != "")
		{
			$("#"+field).html(ctc_amt);
			$("#"+field+"_input").val(ctc_amt);
		}
		
		count_total_earning();
		count_total_deduction();
		count_net_pay();
		
	});
	
	function count_total_earning()
	{	
		var basic_salary = $("#count_basic_salary").html();
		var da= $("#count_da").html();
		var hra= $("#count_hra").html();
		var ma= $("#count_ma").html();
		
		var food= $("#food_allowance_amount").val();
		var trans = $("#trans_allowance_amount").val();
		var acco = $("#acco_allowance_amt").val();
		var mobile = $("#mobile_allowance_amt").val();
		
		var other_allowance = $("#count_other_allowance").html();
		var incentive = $("#incentive").val();
		var salary_diff = $("#salary_diff").val();

		basic_salary = parseFloat(basic_salary);
		da = parseFloat(da);
		hra = parseFloat(hra);
		ma = parseFloat(ma);
		
		food = parseFloat(food);
		trans = parseFloat(trans);
		acco = parseFloat(acco);
		mobile = parseFloat(mobile);
		other_allowance = parseFloat(other_allowance);
		incentive = parseFloat(incentive);
		salary_diff = parseFloat(salary_diff);
		
		var total_earning =  basic_salary + da + hra + ma + food + acco + trans + mobile + other_allowance + incentive + salary_diff;
		total_earning = Math.round(total_earning);
		
		$("#total_earning").html(total_earning);
		$("#total_earning_input").val(total_earning);
		
		// var count_esi = parseFloat(total_earning)*1.75/100;
		// $("#show_esi").html(Math.ceil(count_esi));
		// $(".unhide_esi").val(Math.ceil(count_esi));
		var incentive_val = $("#incentive").val();
		var count_esi = parseFloat(total_earning - incentive_val)*1.75/100;
		$("#show_esi").html(Math.ceil(count_esi));
		$(".unhide_esi").val(Math.ceil(count_esi));
		
		var pf = 0;
		if(total_earning < 0)
		{
			pf = 0;
		}
		else if(total_earning >= 5999 && total_earning <= 8999)
		{
			pf = 80;
		}
		else if(total_earning >= 9000 && total_earning <= 11999)
		{
			pf = 150;
		}
		else if(total_earning >= 12000)
		{
			pf = 200;
		}
		$("#professional_tax").html(pf);
		$("#pro_tax").val(pf);
	}
	
	
	$("body").on("change",".count_deduction",function(){
		
		count_total_deduction();
		count_total_earning();
		count_net_pay();
		
	});
	
	function count_total_deduction()
	{
		var pro_tax= jQuery("#pro_tax").val();
		var tax_deducted_source = $("#tax_deducted_source").val();
		var epf = $("#epf").val();
		var esi = $("#esi").val();
		var loan_payment = $("#loan_payment").val();
		var mobile_bill_recovery = $("#mobile_bill_recovery").val();
		var others = $("#others").val();
		
		pro_tax = parseFloat(pro_tax);
		tax_deducted_source = parseFloat(tax_deducted_source);
		epf = parseFloat(epf);
		esi = parseFloat(esi);
		loan_payment = parseFloat(loan_payment);
		mobile_bill_recovery = parseFloat(mobile_bill_recovery);
		others = parseFloat(others);
		
		var total_ded = pro_tax + tax_deducted_source + epf + esi + loan_payment + mobile_bill_recovery + others;
		total_ded = Math.round(total_ded);
		
		$("#total_deduction").html(total_ded);
		$("#total_deduction_input").val(total_ded);
	}
	
	function count_net_pay()
	{
		var total_earning= $("#total_earning").html();
		var total_deduction = $("#total_deduction").html();
		
		total_earning = parseFloat(total_earning);
		total_deduction = parseFloat(total_deduction);
		
		var net_pay = total_earning - total_deduction;
		net_pay = Math.round(net_pay);
		
		$("#net_pay").html(net_pay);
		$("#net_pay_input").val(net_pay);
	}
	
});
</script>