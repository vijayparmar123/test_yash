<?php
use Cake\Routing\Router;
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
			<a href="" onclick = "javascript:window.close();" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
			</div>
		</div>
		
		<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
		
		<div class="content controls">			
			<div class="form-row">
				<div class="col-md-12 text-center salar_slip_head"><strong>YASHNAND ENGINEERS AND CONTRACTORS PVT. LTD.</strong></div>
				<div class="col-md-12 text-center">
					<strong>Address:</strong> 214/5, Khyati Complex, Near Mithakhali Underbridge, Ellisbridge, Ahmedabad.
				</div>
			</div>
			<div class="form-row">
				<?php 
					$dateObj = DateTime::createFromFormat('!m', $data["month"]);
					$monthName = $dateObj->format('F'); 
				?>
				<div class="col-md-12 text-center">
					<h3 style="margin-bottom: -15px;"><strong>Pay Slip for <?php echo $monthName ."-". date('y',strtotime($data["year"]));?></strong></h3>
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
				<span><?php //echo $this->ERPfunction->get_employee_no($data["user_id"]);
				echo $this->ERPFunction->get_user_identy_number($data["user_id"]); ?></span>
				
				</div>
				
				<div class="col-md-4"><strong>Full Name : </strong> 				
					<span><?php echo $this->ERPfunction->get_full_user_name($data["user_id"]);?> </span>
				</div>
			
				
				<div class="col-md-4"><strong>Date of Issue : </strong>
					<span><?php echo date("d-m-y",strtotime($data["created_date"]));?></span>
				</div>		
			
			</div>						
			<div class="form-row">			
				<div class="col-md-4"><strong>Employee At : </strong>
					<span><?php echo $this->ERPfunction->get_projectname($data["employee_at"]);?></span>
				</div>
				
				<div class="col-md-4"><strong>Designation : </strong>
					<span><?php echo $this->ERPfunction->get_category_title($data["designation"]);?></span>
				</div>
				
				<div class="col-md-4"><strong>Pay Type : </strong>
					<span><?php echo $this->ERPFunction->get_pay_type($data["pay_type"]);?></span>
				</div>
				<!--<div class="col-md-4"><strong>Date of Joining : </strong>
					<span><?php echo date("d-m-y",strtotime($this->ERPfunction->get_user_joindate($data["user_id"])));?></span>
				</div>-->			
		   </div>
		   <div class="form-row">
				<!--<div class="col-md-4"><strong>E. P. F. No : </strong>
					<span><?php //echo $data["epf_no"];?></span>
				</div>-->
						
				<!--<div class="col-md-4"><strong>Pay Type : </strong>
					<span><?php echo $this->ERPFunction->get_pay_type($data["pay_type"]);?></span>
				</div>
				
				<div class="col-md-4"><strong>Payable Days : </strong>
					<span><?php echo $data["payable_days"];?></span>
				</div>-->
				<div class="col-md-4"><strong>Aadhar Card No : </strong>
					<span><?php echo $data["adhaar_card_no"];?></span>
				</div>
				
				<div class="col-md-4"><strong>PAN Card No : </strong>
					<span><?php echo $data["pan_card_no"];?></span>
				</div>
				
				<div class="col-md-4"><strong>Date of Birth : </strong>
					<span><?php echo date("d-m-y",strtotime($this->ERPfunction->get_user_birthdate($data["user_id"])));?></span>
					<input type="hidden" name="ac_no" value="<?php echo date("d-m-Y",strtotime($this->ERPfunction->get_user_birthdate($data["user_id"]))); ?>" >
				</div>
			</div>
		  <div class="form-row">				
				<!--<div class="col-md-4"><strong>ESI Number : </strong>
					<span><?php //echo $data["esi_no"];?></span>
				</div>-->
				
				<!--<div class="col-md-4"><strong>PAN Card No : </strong>
					<span><?php echo $data["pan_card_no"];?></span>
				</div>
				
				<div class="col-md-4"><strong>Aadhar Card No : </strong>
					<span><?php echo $data["adhaar_card_no"];?></span>
				</div>-->
				
				<div class="col-md-4"><strong>Payable Days : </strong>
					<span><?php echo $data["payable_days"];?></span>
				</div>
				
				<div class="col-md-4"><strong>Total No. of Days : </strong>
					<span><?php echo $data["total_days"];?></span>
				</div>
				
				<div class="col-md-4"><strong>C.T.C(Year) : </strong>
					<span><?php echo $this->ERPFunction->get_user_ctc_year($data["user_id"]);?></span>
				</div>
				<!--
				<div class="col-md-2">Pay Rate</div>
				<div class="col-md-2"><input type="text" name="pay_rate" id="pay_rate" value="<?php //echo $pay_rate;?>" class="form-control" /></div>
				-->				
		   </div>
		   <!--<div class="form-row">				
				<div class="col-md-4"><strong>A/C No : </strong>
					<span><?php //echo $data["ac_no"];?></span>
				</div>
				
				<div class="col-md-4"><strong>Bank : </strong>
					<span><?php //echo $data["bank"];?></span>
				</div>
				
			</div>
		   <div class="form-row">				
				<div class="col-md-4"><strong>IFSC Code : </strong>
					<span><?php //echo $data["ifsc_code"];?></span>
				</div>
				
				<div class="col-md-4"><strong>Branch : </strong>
					<span><?php //echo $data["branch"];?></span>
				</div>
			</div>	-->						
			
			<div class="form-row">				
				<!--<div class="col-md-4"><strong>A/C No : </strong>
					<span><?php //echo $user_data["ac_no"];?></span>
					<input type="hidden" name="ac_no" value="<?php// echo $user_data["ac_no"]; ?>" >
				</div>
				
				<div class="col-md-4"><strong>Bank : </strong>
					<span><?php //echo $user_data["bank"];?></span>
					<input type="hidden" name="bank" value="<?php //echo $user_data["bank"]; ?>" >
				</div>-->
				<?php 
				$is_epf = $this->ERPfunction->get_user_is_epf($data["user_id"]);
				if($is_epf != 'no') { 
				?>
				<div class="col-md-4"><strong>EPF No : </strong>
					<span><?php echo $this->ERPfunction->get_user_epf_no($data["user_id"]);?></span>
					<input type="hidden" name="ac_no" value="<?php echo $this->ERPfunction->get_user_epf_no($data["user_id"]); ?>" >
				</div>
				
				<div class="col-md-4"><strong>UAN No : </strong>
					<span><?php echo $this->ERPfunction->get_user_uan_no($data["user_id"]);?></span>
					<input type="hidden" name="bank" value="<?php echo $this->ERPfunction->get_user_uan_no($data["user_id"]); ?>" >
				</div>
				<?php } ?>
				
				<?php 
				$is_esi = $this->ERPfunction->get_user_is_esi($data["user_id"]);
				if($is_esi != 'no') { 
				?>
				<div class="col-md-4"><strong>ESI No : </strong>
					<span><?php echo $this->ERPfunction->get_user_esi_no($data["user_id"]);?></span>
					<input type="hidden" name="ac_no" value="<?php echo $this->ERPfunction->get_user_esi_no($data["user_id"]); ?>" >
				</div>
				<?php } ?>
			</div>
			
			<!--<div class="form-row">
				<div class="col-md-4"><strong>PF Slip Ref. No. : </strong>
					<span><?php echo $this->ERPfunction->get_user_pf_ref_no($data["user_id"]);?></span>
				</div>
			</div>-->
			
			<div class="form-row">
				<div class="col-md-12">
					<input type="hidden" name="user_id" value="<?php echo $data["user_id"];?>" />
					<input type="hidden" name="employee_at" value="<?php echo $data["employee_at"];?>" />
					<input type="hidden" name="month" value="<?php echo $data["month"];?>" />
					<input type="hidden" name="year" value="<?php echo $data["year"];?>" />
					<input type="hidden" name="total_days" id="total_days" value="<?php echo $data["total_days"];?>" />
					<input type="hidden" name="payable_days" id="payable_days" value="<?php echo $data["payable_days"];?>" />
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
								<td align="center"><?php //echo $data["basic_salary"];?></td>
								<td class="text-right" id="count_basic_salary"><?php echo $data["basic_pay_amount"];?></td>
								<input type="hidden" name="basic_pay_ctc" id="basic_pay_ctc" value="<?php echo $data["basic_salary"];?>" />
								<input type="hidden" name="basic_pay_amount" id="basic_pay_amount" value="<?php echo $data["basic_pay_amount"];?>" />
							</tr>
							<tr>
								<td>Dearness Allowance (D.A.)</td>
								<td align="center"><?php //echo $data["da_ctc"];?></td>
								<td class="text-right" id="count_da"><?php echo $data["da_amount"];?></td>
								<input type="hidden" name="da_ctc" id="da_ctc" value="<?php echo $data["da"];?>" />
								<input type="hidden" name="da_amount" id="da_amount" value="<?php echo $data["da_amount"];?>" />
							</tr>
							<tr>
								<td>House Rent Allowance (H.R.A.)</td> <!-- It was 'Accommodation Allowance' before -->
								<td align="center">
								<?php 
								if($acco_text == 1)
								{
									// echo "<input type='hidden' class='count_earning validate[required,custom[number],min[0]]' set='acco_allowance_amt' style='max-width:70px;text-align: center;display:inline' name='acco_ctc' value='{$data["acco_ctc"]}'/>";
									// echo $data["acco_ctc"];
									// echo " <h5 style='display:inline'><span class='label label-danger'>Bill Pd.</span></h5>";
								}else{									
									// echo $data["acco_ctc"];
									// echo strtoupper(str_replace("Fixed","",str_replace("_"," ",$data["acco_allowance"])));
									// echo '<input type="hidden" name="acco_ctc" id="acco_ctc" value="'.$data["acco_ctc"].'" />';
								}
								?>
								</td>
								<td class="text-right" id=""   <?php echo ($acco_text == 1) ? "style='padding-right:0;padding-top:0;padding-bottom:0;'" : "";?>>
								<?php 
								if($acco_text == 1)
								{ ?>
									<input type="text" class="count_earning" name="acco_amount" id="acco_allowance_amt" value="<?php echo $data["acco_amount"];?>" style="max-width:70px;text-align: right;display:inline"  />
								<?php }else{ ?>
								<input type="hidden" name="acco_amount" id="acco_allowance_amt" value="<?php echo $data["acco_amount"];?>" />
									<?php echo $data["acco_amount"];
								} ?> </td>
							</tr>
							<tr>
								<td>Conveyance</td> <!-- It was House Rent Allowance (H.R.A.)' before -->
								<td align="center"><?php //echo $data["hra_ctc"];?></td>
								<td class="text-right" id="count_hra"><?php echo $data["hra_amount"];?></td>
								<input type="hidden" name="hra_ctc" id="hra_ctc" value="<?php echo $data["hra"];?>" />
								<input type="hidden" name="hra_amount" id="hra_amount" value="<?php echo $data["hra_amount"];?>" />
							</tr>
							<tr>
								<td>Transportation Allowance (T.A.)</td>
								<td align="center">
								<?php 
								if($trans_text == 1)
								{
									// echo "<input type='hidden' class='count_earning validate[required,custom[number],min[0]]' set='trans_allowance_amount' style='max-width:70px;text-align: center;display:inline' name='transport_ctc' value='{$data["transport_ctc"]}'/>";
									// echo $data["transport_ctc"];
									// echo " <h5 style='display:inline'><span class='label label-danger'>Bill Pd.</span></h5>";
								}else{									
									// echo $data["transport_ctc"]; 
									// echo strtoupper(str_replace("Fixed","",str_replace("_"," ",$data["trans_allowance"])));
									// echo '<input type="hidden" name="transport_ctc" id="transport_ctc" value="'.$data["transport_ctc"].'" />';
								}
								?>
								</td>
								<td class="text-right" id=""  <?php echo ($trans_text == 1) ? "style='padding-right:0;padding-top:0;padding-bottom:0;'" : "";?>>
								<?php 
								if($trans_text == 1)
								{ ?>
									<input type="text" class="count_earning" name="transport_amount" id="trans_allowance_amount" value="<?php echo $data["transport_amount"];?>" style="max-width:70px;text-align: right;display:inline"  />
							<?php	}
								else{ ?>
									<input type="hidden" name="transport_amount" id="trans_allowance_amount" value="<?php echo $data["transport_amount"];?>" />
									<?php echo $data["transport_amount"];
								} ?>
								</td>
							</tr>
							<tr>
								<td>Special Allowance</td>
								<td align="center">
								<?php 
								if($food_text == 1)
								{
									// echo "<input type='hidden' class='count_earning validate[required,custom[number],min[0]]' set='food_allowance_amount' style='max-width:70px;text-align: center;display:inline' name='food_ctc' value='{$data["food_ctc"]}'/>";
									// echo $data["food_ctc"];
									// echo " <h5 style='display:inline'><span class='label label-danger'>Bill Pd.</span></h5>";
								}else{									
									// echo $data["food_ctc"];
									// echo strtoupper(str_replace("Fixed","",str_replace("_"," ",$data["food_allowance"])));
									// echo '<input type="hidden" name="food_ctc" id="food_ctc" value="'.$data["food_ctc"] .'" />';
								}
								?>
								</td>
								<td class="text-right" id="" <?php echo ($food_text == 1) ? "style='padding-right:0;padding-top:0;padding-bottom:0;'" : "";?>>
								<?php 
								if($food_text == 1)
								{ ?>
									<input type="text" class="count_earning" name="food_amount" id="food_allowance_amount" value="<?php echo $data["food_amount"];?>" style="max-width:70px;text-align: right;display:inline"  />
								<?php }
								else{ ?>
									<input type="hidden" name="food_amount" id="food_allowance_amount" value="<?php echo $data["food_amount"];?>" />
									<?php echo $data["food_amount"];
								} ?>
								</td>
							</tr>
							<tr>
								<td>Medical Allowance</td>
								<td align="center"><?php //echo $data["medical_allowance"];?></td>
								<td class="text-right" id="count_ma"><?php echo $data["medical_amount"];?></td>
								<input type="hidden" name="medical_ctc" id="medical_ctc" value="<?php echo $data["medical_allowance"];?>" />
								<input type="hidden" name="medical_amount" id="medical_amount" value="<?php echo $data["medical_amount"];?>" />
							</tr>							
							<!-- <tr>
								<td>Accommodation Allowance</td>
								<td align="center">
								<?php 
								if($acco_text == 1)
								{
									// echo "<input class='count_earning validate[required,custom[number],min[0]]' set='acco_allowance_amt' style='max-width:70px;text-align: center;display:inline' name='acco_ctc' value='{$data["acco_ctc"]}'/>";
									// echo " <h5 style='display:inline'><span class='label label-danger'>Bill Pd.</span></h5>";
								}else{									
									// echo $data["acco_ctc"];
									// echo strtoupper(str_replace("Fixed","",str_replace("_"," ",$data["acco_allowance"])));
									// echo '<input type="hidden" name="acco_ctc" id="acco_ctc" value="'.$data["acco_ctc"].'" />';
								}
								?>
								</td>
								<td class="text-right" id="acco_allowance_amt"><?php echo $data["acco_amount"];?></td>
								<input type="hidden" name="acco_amount" id="acco_allowance_amt_input" value="<?php echo $data["acco_amount"];?>" />
							</tr> -->
							<tr>
								<td>Mobile Allowance</td>
								<td align="center">
								<?php 
								if($mobile_text == 1)
								{
									// echo "<input type='hidden' class='count_earning validate[required,custom[number],min[0]]' set='mobile_allowance_amt' style='max-width:70px;text-align: center;display:inline' name='mobile_ctc' value='{$data["mobile_ctc"]}'/>";
									// echo $data["mobile_ctc"];
									// echo " <h5 style='display:inline'><span class='label label-danger'>Bill Pd.</span></h5>";
								}else{									
									// echo strtoupper(str_replace("Fixed","",str_replace("_"," ",$data["mobile_allowance"])));
									// echo '<input type="hidden" name="mobile_ctc" id="mobile_ctc" value="'.$data["mobile_ctc"].'" />';
								}
								?>
								</td>
								<td class="text-right" id="" <?php echo ($mobile_text == 1) ? "style='padding-right:0;padding-top:0;padding-bottom:0;'" : "";?>>
								<?php 
								if($mobile_text == 1)
								{ ?>
									<input type="text" class="count_earning" name="mobile_amount" id="mobile_allowance_amt" value="<?php echo $data["mobile_amount"];?>" style="max-width:70px;text-align: right;display:inline"  />
								<?php }else{ ?>
									<input type="hidden" name="mobile_amount" id="mobile_allowance_amt" value="<?php echo $data["mobile_amount"];?>" />
									<?php echo $data["mobile_amount"];
								} ?>
								</td>
							</tr>
							<tr>
								<td>Other Allowance</td>
								<td align="center"><?php //echo $data["other_allowance_ctc"];?></td>
								<td class="text-right" id="other_allowance"><?php echo $data["other_allowance_amount"];?></td>
								<input type="hidden" name="hra_ctc" id="other_allowance_ctc" value="<?php echo $data["other_allowance"];?>" />
								<input type="hidden" name="hra_amount" id="other_allowance_amount" value="<?php echo $data["other_allowance_amount"];?>" />
							</tr>
							<tr>
								<td>Perfomance Incentives</td>
								<td class="text-center"></td>
								<td class="text-right" style="padding-right: 0px;">
									<input class='count_earning text-right validate[required,custom[number],min[0]]' id="incentive" set="" style='max-width:70px;display:inline' name="incentive_amount" value="<?php echo $data["incentive_amount"];?>">
								</td>
							</tr>
							<tr>
								<td>Salary Difference</td>
								<td class="text-center"></td>
								<td class="text-right" style="padding-right: 0px;">
									<input class='count_earning text-right validate[required,custom[number],min[0]]' id="salary_diff_amount" set="" style='max-width:70px;display:inline' name="salary_diff_amount" value="<?php echo $data["salary_diff_amount"];?>">
								</td>
							</tr>
						</tbody>						
						</table>
					</div>
					<div class="footer" style="background: #D99594;">
						<strong>TOTAL EARNINGS <span style="float:right" id="total_earning"><?php echo $data["total_earning"];?></span></strong>
						<input type="hidden" name="total_earning" id="total_earning_input" value="<?php echo $data["total_earning"];?>" />
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
									<input name="pro_tax" id="pro_tax" class="count_deduction text-right validate[required,custom[number],min[0]]" style="width:120px;display:inline" value="<?php echo $data["pro_tax"];?>" />
								</td>
							</tr>
							<?php 
							$is_epf = $this->ERPfunction->get_user_is_epf($data["user_id"]);
							if($is_epf != 'no') { 
							?>
							<tr>
								<td>Employee Provident Fund (E.P.F.)</td>
								<td class="text-right">
									<input name="epf" id="epf" class="count_deduction text-right validate[required,custom[number],min[0]]" style="width:120px;display:inline" value="<?php echo $data["epf"];?>" />
								</td>
							</tr>
							<?php } ?>
							<?php 
							$is_esi = $this->ERPfunction->get_user_is_esi($data["user_id"]);
							if($is_esi != 'no') { 
							?>
							<tr>
								<td>Employee State Insurance (E.S.I.)</td>
								<td class="text-right">
									<input name="esi" id="esi" class="count_deduction unhide_esi text-right validate[required,custom[number],min[0]]" style="width:120px;display:inline" value="<?php echo $data["esi"];?>" />
								</td>
							</tr>
							<?php }else { ?>
							<input type="hidden" name="esi" id="esi" class="count_deduction text-right validate[required,custom[number],min[0]]" style="width:120px;display:inline" value="0" />
							<?php } ?>
							<tr>
								<td>Loan Repayment / Advance &nbsp; <h5 style="display:inline;"><span class="label label-danger"> O/S : <?php echo $data["loan_outstanding"];?> </span></h5></td>
								<td class="text-right">
									<input type="hidden" name="loan_outstanding" value="<?php echo $data["loan_outstanding"];?>" />
									<input name="loan_payment" id="loan_payment" class="count_deduction text-right validate[required,custom[number],min[0]]" style="width:120px;display:inline" value="<?php echo $data["loan_payment"];?>" readonly="true" disabeled />
								</td>
							</tr>							
							<tr>
								<td>Mobile Bill Recovery
									<?php if($data["mobile_cug"] != 0)
										{ ?>
									&nbsp; <h5 style="display:inline;"><span class="label label-danger">Limit : <?php echo $data["mobile_cug"];?></span></h5>
									<?php } ?>
								</td>
								<td class="text-right">
									<input name="mobile_bill_recovery" id="mobile_bill_recovery" class="count_deduction text-right validate[required,custom[number],min[0]]" style="width:120px;display:inline" value="<?php echo $data["mobile_bill_recovery"];?>" />
								</td>
							</tr>
							<tr>
								<td>Tax Deducted at Source (T.D.S.)</td>
								<td class="text-right">
									<input id="tax_deducted_source" name="tax_deducted_source" class="count_deduction text-right validate[required,custom[number],min[0]]" style="width:120px;display:inline" value="<?php echo $data["tax_deducted_source"];?>" />
								</td>
							</tr>
							<tr>
								<td style="<?php if($is_epf != 'no' && $is_esi != 'no'){ echo "padding-bottom:112px";}else if($is_epf == 'no' && $is_esi == 'no'){ echo "padding-bottom: 207px";}else if($is_epf != 'no' && $is_esi == 'no'){ echo "padding-bottom: 160px";}else{echo "padding-bottom: 160px";} ?>">Others</td> <!-- 47px -->
								<td class="text-right" style="<?php if($is_epf != 'no' && $is_esi != 'no'){ echo "padding-bottom:101px";}else if($is_epf == 'no' && $is_esi == 'no'){ echo "padding-bottom: 196px";}else if($is_epf != 'no' && $is_esi == 'no'){ echo "padding-bottom: 149px";}else{echo "padding-bottom: 149px";} ?>"> <!-- 36px -->
									<input name="others" id="others" class="count_deduction text-right validate[required,custom[number],min[0]]" style="width:120px;display:inline" value="<?php echo $data["others"];?>" />
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
						<strong>TOTAL DEDUCTIONS<span style="float:right" id="total_deduction"><?php echo $data["total_deduction"];?></span></strong>
						<input type="hidden" name="total_deduction" id="total_deduction_input" value="<?php echo $data["total_deduction"];?>" />
					</div>
					<div class="footer" style="background: #C0504D; color:#fff;">
						<h5 style="margin:6px 0 0 0;"><strong>NET PAY <span style="float:right" id="net_pay"><?php echo $data["net_pay"];?></span></strong></h5>
						<input type="hidden" name="net_pay" id="net_pay_input" value="<?php echo $data["net_pay"];?>" />
					</div>
                </div>                                               
                
            </div>
				
			</div>			
			
			<div class="form-row">
				<div class="col-md-2"><button type="submit" class="btn btn-primary">Update Salary Slip</button></div>
			</div>
				
		</div>
		<?php $this->Form->end(); ?>
	</div>
<?php } ?>
         </div>
<script>
$(document).ready(function(){	
	
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
		var acco  = $("#acco_allowance_amt").val();
		var mobile = $("#mobile_allowance_amt").val();
		
		var other_allowance = $("#other_allowance").html();
		var incentive = $("#incentive").val();
		var salary_diff = $("#salary_diff_amount").val();
		
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
		
		var total_earning =  basic_salary + da + hra + ma + food + acco  + trans + mobile + other_allowance + incentive + salary_diff;
		total_earning = Math.round(total_earning);
		
		$("#total_earning").html(total_earning);
		$("#total_earning_input").val(total_earning);
		
		// var count_esi = parseFloat(total_earning)*1.75/100;
		// $("#show_esi").html(Math.ceil(count_esi));
		// $(".unhide_esi").val(Math.ceil(count_esi));
		var incentive_val = $("#incentive").val();
		var count_esi = parseFloat(total_earning - incentive_val)*1.75/100;
		//$("#show_esi").html(Math.ceil(count_esi));
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
		//$("#professional_tax").html(pf);
		$("#pro_tax").val(pf);
	}
	
	
	$("body").on("change",".count_deduction",function(){
		
		count_total_deduction();
		count_total_earning();
		count_net_pay();
		
	});
	
	function count_total_deduction()
	{
		var pro_tax= $("#pro_tax").val();
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