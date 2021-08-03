<?php
	if($payment_type == "advance")
	{
?>
		<div class="col-md-12" style="margin-bottom:40px;">
							<div class="col-md-6">
								<div class="col-md-4 text-right">Bank Name:*</div>
								<div class="col-md-8">
								<input type="text" class="form-control validate[required]" name="bank_name">
								</div>
							</div>
							<div class="col-md-6">
							<div class="col-md-4 text-right">Cheque No:*</div>
								<div class="col-md-8">
								<input type="text" class="form-control validate[required]" name="cheque_no">
								</div>
							</div>
						</div>
						
						<div class="col-md-12" style="margin-bottom:40px;">
						<div class="col-md-6">
								<div class="col-md-4 text-right">Cheque Amount(Rs.):*</div>
								<div class="col-md-8">
								<input type="text" class="form-control validate[required]" name="cheque_amount">
								</div>
							</div>
						</div>
						<div class="col-md-12" style="margin-bottom:40px;">
								<div class="col-md-2 text-right">Transfer Type:*</div>
								<div class="col-md-10">
						<select class="select2" required="true" id="transfer_type" style="width: 100%;" name="transfer_type">
									<option value="RTGS">RTGS</Option>
									<option value="NEFT">NEFT</Option>
									<option value="Transfer">Transfer</Option>
									<option value="Single-Cheque">Single-Cheque</Option>
									<option value="office">Please Collect Cheque from Corporate Office</Option>
								</select>
								</div>
						</div>
						<div class="col-md-12">
						<div class="col-md-2">Assign Project:*</div>
                            <div class="col-md-10">
								<select class="select2" id="project_id" required="true" multiple="multiple" style="width: 100%;" name="assign_projects[]">
								<option value="">--Select Project--</Option>
								<?php 
									foreach($projects as $retrive_data)
									{
										echo '<option value="'.$retrive_data['project_id'].'" 
										'.$this->ERPfunction->multiselected($retrive_data['project_id'],$assign_projects).'>'.
										$retrive_data['project_code'].' '.$retrive_data['project_name'].'</option>';
									}
								?>
							</select>
							</div>
						</div>
<?php
	}
	else
	{
?>
		<!--<div class="col-md-12">
									<h4 class="text-center"><u><b>PENDING BILLS</b></u></h4>
						<table class="table table-bordered">
							<thead>
								<tr>
									<th>Bill Inward No</th>
									<th>Invoice No</th>
									<th>Bill Date</th>
									<th>Bill Amount(Rs.)</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
			<?php 
				// $i  = 0;
				// foreach($party_data as $data)
				// {
			?>
				<tr id="row_<?php //echo $i; ?>">
					<td>
						<?php //echo $data['inward_bill_no']; ?>
						<input type="hidden" name="inward[bill_no][]" value="<?php //echo $data['inward_bill_no']; ?>">
						<input type="hidden" name="inward[inward_bill_id][]" value="<?php //echo $data['inward_bill_id']; ?>">
					</td>
					
					<td>
						<?php //echo $data['invoice_no']; ?>
						<input type="hidden" name="inward[invoice_no][]" value="<?php //echo $data['invoice_no']; ?>">
					</td>
					
					<td>
						<?php //echo date("d-m-Y",strtotime($data['bill_date'])); ?>
						<input type="hidden" name="inward[bill_date][]" value="<?php //echo $data['bill_date']; ?>">
					</td>
					
					<td>
						<?php //echo $data['total_amt']; ?>
						<input type="hidden" name="inward[bill_amount][]" value="<?php //echo $data['total_amt']; ?>">
					</td>
					
					<td>
						<span class="trash btn btn-danger"><i class="fa fa-trash"></i> Delete</span>
					</td>
				</tr>
			<?php
				//}
			?>
							</tbody>
						</table>
					</div>-->
					<div class="col-md-12" style="margin-bottom:40px;">
							<div class="col-md-6">
								<div class="col-md-4 text-right">Bank Name:*</div>
								<div class="col-md-8">
								<input type="text" class="form-control validate[required]" name="bank_name">
								</div>
							</div>
							<div class="col-md-6">
							<div class="col-md-4 text-right">Cheque No:*</div>
								<div class="col-md-8">
								<input type="text" class="form-control validate[required]" name="cheque_no">
								</div>
							</div>
						</div>
						
						<div class="col-md-12" style="margin-bottom:40px;">
						<div class="col-md-6">
								<div class="col-md-4 text-right">Cheque Amount(Rs.):*</div>
								<div class="col-md-8">
								<input type="text" class="form-control validate[required]" name="cheque_amount">
								</div>
							</div>
						</div>
						<div class="col-md-12" style="margin-bottom:40px;">
								<div class="col-md-2 text-right">Transfer Type:*</div>
								<div class="col-md-10">
						<select class="select2" required="true" id="transfer_type" style="width: 100%;" name="transfer_type">
									<option value="RTGS">RTGS</Option>
									<option value="NEFT">NEFT</Option>
									<option value="Transfer">Transfer</Option>
									<option value="Single-Cheque">Single-Cheque</Option>
									<option value="office">Please Collect Cheque from Corporate Office</Option>
								</select>
								</div>
						</div>
						
						<div class="col-md-12" style="margin-bottom:40px;">
						<div class="col-md-2">Assign Project:*</div>
                            <div class="col-md-10">
								<select class="select2" id="project_id" required="true" multiple="multiple" style="width: 100%;" name="assign_projects[]">
								<option value="">--Select Project--</Option>
								<?php 
									foreach($projects as $retrive_data)
									{
										echo '<option value="'.$retrive_data['project_id'].'" 
										'.$this->ERPfunction->multiselected($retrive_data['project_id'],$assign_projects).'>'.
										$retrive_data['project_code'].' '.$retrive_data['project_name'].'</option>';
									}
								?>
							</select>
							</div>
						</div>
<?php
	}
?>