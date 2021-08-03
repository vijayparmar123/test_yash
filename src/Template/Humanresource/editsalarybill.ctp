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
			<h2>Labour Bill</h2>
			<div class="pull-right">
			<a href="<?php echo $this->request->base;?>/humanresource/salaryslip" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
			</div>
		</div>
		
		<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
		  
<div class="content controls">			
	<div class="form-row">
		<div class="col-md-12 text-center salar_slip_head"><strong><u><?php echo $this->ERPFunction->get_user_bank_name($user_id); ?></u></strong></div>
		<div class="col-md-12 text-center">
			
		</div>
	</div>
	<div class="row" style="background: aliceblue;margin: 0;">
		<div class="col-md-12">
			<div class="form-row">				
				<div class="col-md-1" style="text-align:left;padding-left:0px"><strong>Client : </strong></div>	
				<div class="col-md-11" style="text-align:left;padding:0;"><span>YASHNAND ENGINEERS AND CONTRACTORS PVT. LTD.</span></div>						
			</div>
			
			<div class="form-row">				
				<div class="col-md-1" style="text-align:left;padding-left:0px"><strong>Site : </strong></div>	
				<div class="col-md-11" style="text-align:left;padding:0;"><span><?php echo $this->ERPFunction->get_user_employee_at($user_id); ?></span></div>				
						
			</div>
			
			<div class="form-row">			
				<div class="col-md-1" style="text-align:left;padding-left:0px"><strong>Bill No. : </strong></div>	
				<div class="col-md-7" style="text-align:left;padding:0;"><span><?php echo $month."-".$year?></span></div>	
								
				<div class="col-md-1" style="text-align:right;padding-right:0px;"><strong>Date : </strong></div>
				<div class="col-md-2">
					<input type="text" name="date" class="validate[required]" id="date" value="<?php echo date("d-m-Y",strtotime($data['created_date'])); ?>" >
				</div>			
		   </div>
		   <div class="form-row">				
				<div class="col-md-2" style="text-align:left;padding-left:0px"><strong>Pan Card No. : </strong></div>	
				<div class="col-md-6" style="text-align:left;padding:0;"><span><?php echo $this->ERPFunction->get_user_pan_card($user_id); ?></span></div>
				<div class="col-md-1" style="text-align:right;padding-right:0px;"><strong>GST No. : </strong></div>	
				<div class="col-md-2" style="text-align:left;"><span>Not Given</span></div>
			</div>
		   <div class="form-row">				
				<div class="col-md-2" style="text-align:left;padding-left:0px"><strong>Contact No. : </strong></div>	
				<div class="col-md-10" style="text-align:left;padding:0;"><span><?php echo $this->ERPFunction->get_user_contact_no($user_id); ?></span></div>				
			</div>
			
			<div class="form-row">				
				<div class="col-md-2" style="text-align:left;padding-left:0px"><strong>Type of Work : </strong></div>	
				<div class="col-md-10" style="text-align:left;padding:0;"><span><?php
						  echo $this->ERPfunction->get_user_designation($user_id); ?></span></div>				
			</div>
			
			<div class="form-row">						
				<table class="table table-bordered">
					<thead>
						<tr>
							<th class="text-center">Item No</th>
							<th class="text-center">Description</th>
							<th class="text-center">Unit</th>
							<th class="text-center">This Bill Qty.</th>
							<th class="text-center">Rate</th>
							<th class="text-center">This Bill Amount</th>
						</tr>
					</thead>
					<tbody id="old_record_data">
					</tbody>
					<tbody id="new_record_data">	

						<tr id="row_id_0">
							<td>1</td>

							<td><b>Service Charge - <?php echo $this->ERPfunction->get_user_designation($user_id); ?></b></td>

							<td><?php echo $month; ?></td>

							<td>
							<?php echo $this_bill_qty = number_format((float)$data['payable_days'] / $total_days, 2, '.', '');?></td>

							<td>
							<?php echo $data['basic_salary']; ?>
							<input type="hidden" name="basic_salary" id="basic_salary" value="<?php echo $data['basic_salary']; ?>"></td>

							<td class="text-right">
							<?php echo $this_bill_amount = number_format((float)$this_bill_qty * $data['basic_salary'], 2, '.', '');?>
							
							<input type="hidden" name="this_bill_amount" id="this_bill_amount" value="<?php echo number_format((float)$this_bill_qty * $data['basic_salary'], 2, '.', '');?>"></td>
							</td>

						</tr>
					</tbody>
					<tfoot>
					<tr>
						<td class="text-left">2</td>
						<td colspan="4" class="text-left"><b>Debit Note</b></td>
						<td class="text-right"> 
							<input type="text" name="debit_this_bill" id="debit_this_bill" class="debit_this_bill validate[required,custom[number]] count" style="width:80px;float:right" value="<?php echo $data['others']?>">
						</td>
					</tr>
					<tr>
						<td class="text-left">3</td>
						<td colspan="4" class="text-left"><b>Reconciliation / Material Debit Note</b></td>
						<td class="text-right"><span class="reconciliation">0.00</span><input type="hidden" id="reconciliation" value="0"></td>

					</tr>
					<tr>
						<td colspan="5" class="text-right"><b>GRAND TOTAL</b></td>
						<td class="text-right">
							<span class="grand_total"><?php echo $this_bill_amount - $data['others']; ?></span>
							<input type="hidden" name="grand_total" value="<?php echo $this_bill_amount - $data['others']; ?>" id="grand_total" style="width:80px;">
						</td>

					</tr>
					<tr>
						<td colspan="5" class="text-center"><b>TOTAL AMOUNT THIS BILL</b></td>
						<td class="text-right">
							<span class="total_amount_this_bill"><?php echo $total_amount_this_bill = $this_bill_amount - $data['others']; ?></span>
							<input type="hidden" name="total_amount_this_bill" id="total_amount_this_bill" value="<?php echo $this_bill_amount - $data['others']; ?>" style="width:80px;">
						</td>
					</tr>
					
					<tr>
						<td colspan="5" class="text-center"><b>RETENTION MONEY FOR THIS BILL</b></td>
						<td class="text-right"><span>0.00<span><input type="hidden" id="retention_money" value="0"></td>
					</tr>
					<tr>
						<td colspan="5" class="text-center"><b>NET AMOUNT OF THIS BILL</b></td>
						<td class="text-right">
							<span class="net_amount"><?php echo $net_amount = $total_amount_this_bill; ?></span>
							<input type="hidden" name="net_amount" value="<?php echo $total_amount_this_bill; ?>" id="net_amount" style="width:80px;">
						</td>
					</tr>
					<tr>
						<td colspan="6" class="text-center" style="background-color:grey;"><b>FOR OFFICE USE ONLY</b></td>
					</tr>
					<tr>
						<td colspan="5" class="text-center"><b>ADVANCE/UPAD IN CHEQUE</b></td>
						<td class="text-right">
							<input type="text" name="upad" id="upad" class="upad count validate[custom[number]]" value="<?php echo $data["loan_payment"]; ?>" data-id="0" style="width:80px;float:right">
						</td>
					</tr>
					<tr>
						<td colspan="5" class="text-center"><b>TOTAL AMOUNT TO BE PAID</b></td>
						<td class="text-right">
							<span id="paid_amonut"><?php echo $data["net_pay"]; ?></span>
							<input type="hidden" name="paid_amonut" value="<?php echo $data["net_pay"]; ?>" class="paid_amonut" style="width:80px;">
						</td>
					</tr>
					</tfoot>
				</table>
				
			</div>
		</div>
		
	</div><br>
	<div class="form-row">
		<div class="col-md-2"><button type="submit" id="generate" class="btn btn-primary"><?php echo "Update Labour Bill";?></button></div>
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
		var this_bill_amount = $("#this_bill_amount").val();
		var debit_amount = $("#debit_this_bill").val();
		var reconciliation = $("#reconciliation").val();
		var grand_total = parseFloat(this_bill_amount) - parseFloat(debit_amount) - reconciliation;
		
		$(".grand_total").html(grand_total);
		$("#grand_total").val(grand_total);
		
		$(".total_amount_this_bill").html(grand_total);
		$("#total_amount_this_bill").val(grand_total);
		
		var retention_money = $("#retention_money").val();
		
		var net_amount = grand_total - retention_money;
		
		$(".net_amount").html(net_amount);
		$("#net_amount").val(net_amount);
		
		var upad = $("#upad").val();
		var paid_amount = net_amount - upad;
		
		$(".paid_amonut").val(paid_amount);
		$("#paid_amonut").html(paid_amount);
		
	}
	
});
</script>