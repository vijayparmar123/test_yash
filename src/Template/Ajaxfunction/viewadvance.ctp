
<style>
#ui-datepicker-div{z-index:9999 !important;}
</style>
<div class="modal-header" >
    <button type="button" class="close" data-dismiss="modal">X</button>
	<h4 class="modal-title"> Advance Transfer</h4>
</div>
<div class="modal-body clearfix">
<div class="controls">
<form name="medicinecat_form" action="advancetransfer" method="post" class="form-horizontal transferform">
  	 	
		
		<?php
		foreach($result as $data)
		{
		?>
		<div class="form-row">
			<label class="col-sm-4 control-label" for="transfer_date">
				Transfer Date 
			</label>
			<div class="col-sm-4">
			<label class="col-sm-12 control-label" for="transfer_date">
				<?php echo $this->ERPfunction->get_date($data['cheque_date']); ?>
			</label>
			</div>
		</div>
		
		<div class="form-row">
			<label class="col-sm-4 control-label" for="transfer_date">
				Transfer Type 
			</label>
			<div class="col-sm-8 col-md-8">
				<label class="col-sm-8 col-md-8 control-label" for="transfer_date">
				<?php echo $data['transfer_type']; ?>
			</label>
			</div>			
		</div>
		
		<!--<div class="form-row">
			<label class="col-sm-4 control-label" for="transfer_date">
				Cheque Amount
			</label>
			<div class="col-sm-4">
				<label class="col-sm-4 control-label" for="transfer_date">
				<?php //$tds = $data['cheque_amount'] * 1/100; 
						//echo $data['cheque_amount'] - $tds;
				?>
			</label>
			</div>			
		</div>
		
		 <div class="form-row">
			<label class="col-sm-4 " for="transfer_to" >
				Bank
			</label>
			<div class="col-sm-4">
				<label class="col-sm-4 control-label" for="transfer_date">
				<?php //echo $data['bank']; ?>
			</label>
			</div>			
		</div>
		
		<div class="form-row">
			<label class="col-sm-4 control-label" for="transfer_to">
				Cheque No
			</label>
			<div class="col-sm-4">
				<label class="col-sm-4 control-label" for="transfer_date">
				<?php //echo $data['cheque_no']; ?>
			</label>
			</div>			
		</div> -->
		<?php
		}
		?>
  	</form>
</div>
</div>
<div class="modal-footer">	
	<button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>	
</div>