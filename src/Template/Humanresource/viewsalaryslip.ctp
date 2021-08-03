<?php
use Cake\Routing\Router;
$curr_date = "{$data['year']}-{$data['month']}-01";
$curr_date = date("Y-m-d",strtotime($curr_date));
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
			<h2>View Salary Slip</h2>
			<div class="pull-right">
			<!-- <a href="<?php // echo $this->request->base;?>/humanresource/salarystatement" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a> -->
			<a href="<?php //echo getenv("HTTP_REFERER");?>" onclick = "javascript:window.close();" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
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
					<h3 style="margin-bottom: -15px;"><strong>Pay Slip for <?php echo $monthName ."-". date("y",strtotime($curr_date));?></strong></h3>
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
				//echo $data["user_id"]; 
				echo $this->ERPFunction->get_user_identy_number($data["user_id"]); ?></span>
				
				</div>
				
				<div class="col-md-4"><strong>Full Name : </strong> 				
					<span><?php echo $this->ERPfunction->get_full_user_name($data["user_id"]); ?> </span>
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
				
				<!--<div class="col-md-4"><strong>Date of Joining : </strong>
					<span><?php echo date("d-m-y",strtotime($this->ERPfunction->get_user_joindate($data["user_id"])));?></span>
				</div>-->

				<div class="col-md-4"><strong>Pay Type : </strong>
					<span><?php echo $this->ERPFunction->get_pay_type($data["pay_type"]);?></span>
				</div>
		   </div>
		   <div class="form-row">
				<!--<div class="col-md-4"><strong>E. P. F. No : </strong>
					<span><?php //echo $data["epf_no"];?></span>
				</div>
						
				<div class="col-md-4"><strong>Pay Type : </strong>
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
				</div>
				
				<div class="col-md-4"><strong>PAN Card No : </strong>
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
						<h2 style=";width: 100%;text-align: center;">EARNINGS</h2>
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
								<td align="center"><?php //echo $data["basic_pay_ctc"];?></td>
								<td class="text-right" ><?php echo $data["basic_pay_amount"];?></td>
							</tr>
							<tr>
								<td>Dearness Allowance (D.A.)</td>
								<td align="center"><?php //echo $data["da_ctc"];?></td>
								<td class="text-right" ><?php echo $data["da_amount"];?></td>
							</tr>
							<tr>
								<td>House Rent Allowance (H.R.A.)</td> <!-- It was 'Accommodation Allowance' before -->
								<td align="center">
									<?php // echo $data["acco_ctc"];?>
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
							</tr>
							<tr>
								<td>Conveyance Allowance</td> <!-- It was House Rent Allowance (H.R.A.)' before -->
								<td align="center"><?php //echo $data["hra_ctc"];?></td>
								<td class="text-right" ><?php echo $data["hra_amount"];?></td>
							</tr>
							<tr>
								<td>Transportation Allowance (T.A.)</td>
								<td align="center">
								<?php //echo $data["transport_ctc"];?>
								<?php 
								if($trans_text == 1)
								{
									// echo $data["transport_ctc"];
									// echo " [Bill Paid]";
								}else{									
									// echo $data["transport_ctc"];
									// echo strtoupper(str_replace("Fixed","",str_replace("_"," ",$data["trans_allowance"])));
									// echo '<input type="hidden" name="transport_ctc" id="transport_ctc" value="'.$data["transport_ctc"].'" />';
								}
								?>
								</td>					
								<td class="text-right" id="trans_allowance_amount"><?php echo $data["transport_amount"];?></td>
							</tr>
							<tr>
								<td>Special Allowance</td>
								<td align="center">
								<?php //echo $data["food_ctc"];?>
								<?php 
								if($food_text == 1)
								{
									// echo "<input class='count_earning validate[required,custom[number],min[0]]' set='food_allowance_amount' style='max-width:70px;text-align: center;display:inline' name='food_ctc' value='{$data["food_ctc"]}'/>";
									// echo " <h5 style='display:inline'><span class='label label-danger'>Bill Pd.</span></h5>";
									// echo $data["food_ctc"];
									// echo " [Bill Paid]";
								}else{									
									// echo $data["food_ctc"];
									// echo strtoupper(str_replace("Fixed","",str_replace("_"," ",$data["food_allowance"])));
									// echo '<input type="hidden" name="food_ctc" id="food_ctc" value="'.$data["food_ctc"] .'" />';
								} ?>
								</td>								
								<td class="text-right" id="food_allowance_amount"><?php echo $data["food_amount"];?></td>
							</tr>
							<tr>
								<td>Medical Allowance</td>
								<td align="center"><?php //echo $data["medical_ctc"];?></td>
								<td class="text-right" ><?php echo $data["medical_amount"];?></td>
							</tr>						
							
							<tr>
								<td>Mobile Allowance</td>
								<td align="center">
									<?php //echo $data["mobile_ctc"];?>
									<?php 
									if($mobile_text == 1)
									{
										// echo "<input class='count_earning validate[required,custom[number],min[0]]' set='mobile_allowance_amt' style='max-width:70px;text-align: center;display:inline' name='mobile_ctc' value='{$data["mobile_ctc"]}'/>";
										// echo " <h5 style='display:inline'><span class='label label-danger'>Bill Pd.</span></h5>";
										// echo $data["mobile_ctc"];
										// echo " [Bill Paid]";
									}else{									
										// echo $data["mobile_ctc"];
										// echo strtoupper(str_replace("Fixed","",str_replace("_"," ",$data["mobile_allowance"])));
										// echo '<input type="hidden" name="mobile_ctc" id="mobile_ctc" value="'.$data["mobile_ctc"].'" />';
									}
									?>
								</td>
								<td class="text-right" id="mobile_allowance_amt"><?php echo $data["mobile_amount"];?></td>
							</tr>
							<tr>
								<td>Other Allowance</td>
								<td class="text-center"></td>
								<td class="text-right">
									<?php echo $data["other_allowance_amount"];?>
								</td>
							</tr>
							<tr>
								<td>Perfomance Incentives</td>
								<td class="text-center"></td>
								<td class="text-right">
									<?php echo $data["incentive_amount"];?>
								</td>
							</tr>
							<tr>
								<td>Salary Difference</td>
								<td class="text-center"></td>
								<td class="text-right">
									<?php echo $data["salary_diff_amount"];?>
								</td>
							</tr>
						</tbody>						
						</table>
					</div>                                               
					<div class="footer" style="background: #D99594;">
						<strong>TOTAL EARNINGS <span style="float:right" id="total_earning"><?php echo $data["total_earning"];?></span></strong>
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
									<?php echo $data["pro_tax"];?>
								</td>
							</tr>
							<?php 
							$is_epf = $this->ERPfunction->get_user_is_epf($data["user_id"]);
							if($is_epf != 'no') { 
							?>
							<tr>
								<td>Employee Provident Fund (E.P.F.)</td>
								<td class="text-right">
									<?php echo $data["epf"];?>
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
									<?php echo $data["esi"]; ?>
								</td>
							</tr>
							<?php } ?>
							<tr>
								<td>Loan Repayment / Advance &nbsp; <h5 style="display:inline;"><span class="label label-danger"> O/S : <?php echo $data["loan_outstanding"];?> </span></h5></td>
								<td class="text-right">
									<?php echo $data["loan_payment"];?>
								</td>
							</tr>
							<tr>
								<td>Mobile Bill Recovery
									<?php if($data["mobile_cug"] != 0)
										{ ?>
									&nbsp; <h5 style="display:inline;"><span class="label label-danger">Limit : <?php echo $data["mobile_cug"];?></span></h5>
										<?php } ?>
								<td class="text-right">
									<?php echo $data["mobile_bill_recovery"];?>
								</td>
							</tr>
							<tr>
								<td>Tax Deducted at Source (T.D.S.)</td>
								<td class="text-right">
									<?php echo $data["tax_deducted_source"];?>
								</td>
							</tr>
							<tr>
								<td style="<?php if($is_epf != 'no' && $is_esi != 'no'){ echo "padding-bottom:167px";}else if($is_epf == 'no' && $is_esi == 'no'){ echo "padding-bottom: 240px";}else if($is_epf != 'no' && $is_esi == 'no'){ echo "padding-bottom: 203px";}else{echo "padding-bottom: 203px";} ?>">Others</td>
								<td class="text-right" style="<?php if($is_epf != 'no' && $is_esi != 'no'){ echo "padding-bottom:166px";}else if($is_epf == 'no' && $is_esi == 'no'){ echo "padding-bottom: 239px";}else if($is_epf != 'no' && $is_esi == 'no'){ echo "padding-bottom: 202px";}else{echo "padding-bottom: 202px";} ?>">
									<?php echo $data["others"];?>
								</td>
							</tr>							
						</tbody>
						<!-- <tfoot>
							<tr>
								<th style="padding-bottom: 22px;">TOTAL DEDUCTIONS</th>
								<th class="text-right" id="total_deduction" style="padding-bottom: 19px;"><?php echo $data["total_deduction"];?></th>
							</tr>							
						</tfoot> -->						
						</table>
					</div> 
					<div class="footer" style="background:#FABF8F;">
						<strong>TOTAL DEDUCTIONS<span style="float:right" id="net_pay"><?php echo $data["total_deduction"];?></span></strong>
					</div>
					<div class="footer" style="background: #C0504D; color:#fff;">
						<h5 style="margin:6px 0 0 0;"><strong>NET PAY <span style="float:right" id="net_pay"><?php echo $data["net_pay"];?></span></strong></h5>						
					</div>
                </div>                                               
                
            </div>
				
			</div>			
			
				
		</div>
		<?php $this->Form->end(); ?>
	</div>
<?php } ?>
         </div>
