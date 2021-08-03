<div class="modal-header" >
    <button type="button" class="close" data-dismiss="modal">X</button>
	<h4 class="modal-title">Pay Records</h4>
</div>
<div class="modal-body clearfix">
<div class="controls">
	<h6> Employee Name: <?php echo $this->ERPfunction->get_user_name($user_id);?></h6>
	<?php	
	
	// debug($salary_data);
	?>
	<table class='table table-bordered'>
	<tr>
		<th>Employee No</th>
		<th>Employee Name</th>
		<th>Designation</th>
		<th>Pay Type</th>						
		<th>Employed at</th>						
		<th>Month & Year</th>						
		<th>Payable Days</th>						
		<th>CTC<br>(Month)<br>(Rs.)</th>						
		<th>Net<br>Pay<br>(Rs.)</th>						
		<th>A/C No.</th>
		<th>Bank</th>
		<th>Branch</th>
		<th>IFSC Code</th>		
	</tr>
	<?php
	$i = 1;
	if(!empty($salary_data))
	{	
		$rows = array();
		$rows[] = array("Employee No","Employee Name","Designation","Pay Type","Employed at","Month & Year","Payable Days","CTC (Month)(Rs.)","Net Pay(Rs.)","A/C No.","Bank","Branch","IFSC Code");
		foreach($salary_data as $retrive_data)
		{	
			$csv = array();
			$curr_date = "{$retrive_data['year']}-{$retrive_data['month']}-01";
			$curr_date = date("Y-m-d",strtotime($curr_date));
		?>
			<tr>								
				<td><?php echo ( $csv[] = $retrive_data["erp_users"]['user_identy_number']);?></td>
				<td><?php echo ( $csv[] = $retrive_data['erp_users']['first_name'] ." ". $retrive_data['erp_users']['last_name']);?></td>						
				<td><?php echo ( $csv[] = $this->ERPfunction->get_category_title($retrive_data['designation']));?></td>								
				<td><?php echo ( $csv[] = $this->ERPfunction->get_pay_type($retrive_data["pay_type"]));?></td>
				<td><?php echo ( $csv[] = $this->ERPfunction->get_projectname($retrive_data["employee_at"]));?></td>
				<td><?php echo ( $csv[] = date("M",strtotime($curr_date))."/".date("Y",strtotime($curr_date)));?></td>
				<td><?php echo ( $csv[] = $retrive_data["payable_days"]);?></td>
				<td><?php echo ( $csv[] = $retrive_data["basic_pay_ctc"] + $retrive_data["da_ctc"] + $retrive_data["hra_ctc"] + $retrive_data["medical_ctc"] + $retrive_data["food_ctc"] + $retrive_data["transport_ctc"] + $retrive_data["acco_ctc"] + $retrive_data["mobile_ctc"]);?></td>
				<td><?php echo ( $csv[] = $retrive_data["net_pay"]);?></td>
				<td><?php echo ( $csv[] = $retrive_data['erp_users']["ac_no"]);?></td>
				<td><?php echo ( $csv[] = $retrive_data['erp_users']["bank"]);?></td>
				<td><?php echo ( $csv[] = $retrive_data['erp_users']["branch"]);?></td>
				<td><?php echo ( $csv[] = $retrive_data['erp_users']["ifsc_code"]);?></td>				
			</tr>
		<?php
		$rows[] = $csv;
		} 
	} ?>
	</table>	
</div>
</div>
<div class="modal-footer">
	<?php
	if(!empty($salary_data))
	{
	?>
	<div class="col-md-2">
	<?php 
		echo $this->Form->Create('',['id'=>'export','class'=>'form_horizontal formsize','method'=>'post','url'=>['controller'=> 'Humanresource','action'=>'excelpayrecordshistory']]);
	?>
		<input type="hidden" name="rows" value='<?php echo base64_encode(serialize($rows));?>'>
		<input type="submit" class="btn btn-success" value="Export To Excel" name="export_csv">
	<?php $this->Form->end(); ?>
	</div>
	<div class="col-md-4">
		<a href="<?php echo $this->request->base;?>/Humanresource/printpayrecordshistory/<?php echo $user_id; ?>" class="btn btn-primary" id="print_this" target="_blank"><i class="icon-print"></i> Print</a>
	</div>
	<?php } ?>
	<button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>	
</div>