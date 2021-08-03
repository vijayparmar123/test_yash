<?php
use Cake\Routing\Router;
$salary_date = "{$year}-{$month}-01";
$salary_date = date("Y-m-d",strtotime($salary_date));
$dateObj = DateTime::createFromFormat('!m', $month);
$monthName = $dateObj->format('F');
?>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('#user_form').validationEngine();
	
	jQuery('#date').datepicker({
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
#date{
	border-style: none none none none;
    border-top-style: none;
    background: transparent;
    border-bottom: 1px solid grey;
    border-radius: 0px;
    box-shadow: none;
    border-top: 1px solid aliceblue;
}
hr{
	border-top: 1px solid #000000;
}
</style>

<div class="modal fade " id="load_modal" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content"></div>
    </div>
</div>	


<div class="col-md-10" >
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
			<h2>Payment Voucher</h2>
			<div class="pull-right">
			<a href="<?php echo $this->request->base;?>/humanresource/salaryslip" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
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
	<div class="row" style="background: aliceblue;margin: 0;">
		<div class="col-md-12">
			<div class="form-row">				
				<div class="col-md-1" style="text-align:left;padding-left:0px"><strong>Project : </strong></div>	
				<div class="col-md-11" style="text-align:left;border-bottom:1px solid grey;padding:0;"><span><?php echo $this->ERPFunction->get_user_employee_at($user_id); ?></span></div>				
						
			</div>						
			<div class="form-row">			
				<div class="col-md-1" style="text-align:left;padding-left:0px"><strong>C.V. No. : </strong></div>	
				<div class="col-md-7" style="text-align:left;border-bottom:1px solid grey;padding:0;"><span><?php echo $month."-".$year?></span></div>	
								
				<div class="col-md-1" style="text-align:right;padding-right:0px;"><strong>Date : </strong></div>
				<div class="col-md-2">
					<input type="text" name="date" class="validate[required]" id="date" value="<?php echo date("d-m-Y",strtotime($data['created_date'])); ?>" >
				</div>			
		   </div>
		   <div class="form-row">				
				<div class="col-md-1" style="text-align:left;padding-left:0px"><strong>Debit To : </strong></div>	
				<div class="col-md-11" style="text-align:left;border-bottom:1px solid grey;padding:0;"><span>Conveyance</span></div>				
			</div>
		   <div class="form-row">				
				<div class="col-md-1" style="text-align:left;padding-left:0px"><strong>Paid To : </strong></div>	
				<div class="col-md-11" style="text-align:left;border-bottom:1px solid grey;padding:0;"><span><?php echo $this->ERPFunction->get_user_bank_name($user_id);; ?></span></div>				
			</div>
			
			<div class="form-row">
			<hr/>
				<table width=100% border=1>
					<tbody>
						<tr>
							<td align='center' style="width:100px"><b>Sr.No</b></td>
							<td align='center' colspan='5' style="width:500px"><b>Narration</b></td>
							<td colspan='2' align='center' style="width:100px"><b>Amount(Rs.)</b></td>
						</tr>
						<tr>
							<td align='center'>1</td>
							<td colspan='5'>Conveyance Paid towards service given as <?php
						  echo $this->ERPfunction->get_user_designation($user_id); ?> for <?php echo $monthName ."-". date('Y',strtotime($salary_date));?></td>
							<td colspan='2' align='right'><?php echo round(($data["payable_days"] / $total_days) * $data["basic_salary"]); ?>
							<input type="hidden" value="<?php echo round(($data["payable_days"] / $total_days) * $data["basic_salary"]); ?>" id="earning"></td>
						</tr>
						<tr>
							<td align='center'>2</td>
							<td colspan='5'>Advance</td>
							<td colspan='2' align='center'><input type="text" name="advance" id="advance" value="<?php echo $data["loan_payment"]; ?>" class="count validate[custom[number]]" style="text-align:right;background-color:#ffffff"></td>
						</tr>
						<tr>
							<td align='center'>3</td>
							<td colspan='5'>Other Deductions</td>
							<td colspan='2' align='center'><input type="text" name="other_deductions" id="other_deductions" value="<?php echo $data["others"]; ?>" class="count validate[custom[number]]" style="text-align:right;background-color:#ffffff"></td>
						</tr>
						<tr>
							<td align='center' style="border-right:none;"></td>
							<td align='right' colspan='5' style="border-left:none;"><b>Total</b></td>
							<td colspan='2' align='right'>
							<span class="net_pay"><?php echo $data["net_pay"]; ?></span><input type="hidden" name="net_pay" value="<?php echo $data["net_pay"]; ?>" class="net_pay"></td>
						</tr>
					</tbody>
				</table>
			</div>
			
		</div>
		
	</div><br>
	<div class="form-row">
		<div class="col-md-2"><button type="submit" id="generate" class="btn btn-primary"><?php echo "Update Voucher";?></button></div>
	</div>
</div>
		<?php $this->Form->end(); ?>
	</div>
<?php //} ?>
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
	
	
	
	$("body").on("change",".count",function(){
		count_net_pay();
	});
	
	function count_net_pay()
	{
		var total_earning= $("#earning").val();
		var net_pay = parseFloat(total_earning);
		var advance = $("#advance").val();
		if(advance)
		{
			net_pay = net_pay - parseFloat(advance);
		}
		var deduction = $("#other_deductions").val();
		if(deduction)
		{
			net_pay = net_pay - parseFloat(deduction);
		}
		
		net_pay = parseFloat(net_pay);
		
		net_pay = Math.round(net_pay);
		
		$(".net_pay").html(net_pay);
		$(".net_pay").val(net_pay);
	}
	
});
</script>