<script type="text/javascript">
jQuery(document).ready(function() {
	 
	jQuery('#transfer_list').DataTable({responsive: true});
});
</script>
<style>
#ui-datepicker-div{z-index:9999 !important;}
</style>
<div class="modal-header" >
    <button type="button" class="close" data-dismiss="modal">X</button>
	<h4 class="modal-title"> Sale Assets details </h4>
</div>
<div class="modal-body clearfix">
<div class="controls">


<h6> Asset Name: <?php  echo $assetname;   ?></h6>
<table id="transfer_list"  class="dataTables_wrapper table">
	<thead>
		<th>Transferred Date</th>
		<th>Transfer From</th>
		<th>Transfer To</th>
		<th>Transfer by</th>
		<th>Accept Date</th>
		<th>Accepted By</th>
	</thead>
	<tbody>
	<?php 
	$i = 1;
	$transfer_rows = array();
	$transfer_rows[] = array("Transferred Date","Transfer From","Transfer To","Transfer by","Accept Date","Accepted By");
		foreach($transferdata as $retrive_data)
		{ 
			$transfer_csv = array();
		?>
			<tr>
				<td><?php echo ($transfer_csv[] = $this->ERPfunction->get_date($retrive_data['transfer_date'])); ?></td> 
				<td><?php echo ($transfer_csv[] = $this->ERPfunction->get_projectname($retrive_data['old_project'])); ?></td> 
				<td><?php echo ($transfer_csv[] = $this->ERPfunction->get_projectname($retrive_data['new_project'])); ?></td> 
				<td><?php echo ($transfer_csv[] = $this->ERPfunction->get_user_name($retrive_data['created_by'])); ?></td> 
				<td><?php echo ($transfer_csv[] = ($retrive_data['accept_date'] != "")?date("d-m-Y",strtotime($retrive_data['accept_date'])):"NA"); ?></td> 
				<td><?php echo ($transfer_csv[] = $this->ERPfunction->get_user_name($retrive_data['updated_by'])); ?></td> 
			</tr>
	<?php
	$i++;
		$transfer_rows[] = $transfer_csv;
		}
		?>
	</tbody>
</table>	
<div class="content">
	<div class="col-md-4">
		<?php echo $this->Form->Create('export_csv',['method'=>'post','class' => 'form-horizontal','url' => ['controller' => 'Assets','action' => 'exportassettransferhistory']]);?>
		<input type="hidden" name="transfer_rows" value='<?php echo serialize($transfer_rows);?>'>
		<input type="submit" class="btn btn-success" value="Export To Excel" name="export_csv">
	<?php 
		echo $this->Form->end();
	?>
	</div>
	<div class="col-md-4">
		<?php echo $this->Form->Create('export_pdf',['method'=>'post','class' => 'form-horizontal','url' => ['controller' => 'Assets','action' => 'exportassettransferhistory']]);?>
		<input type="hidden" name="transfer_rows" value='<?php echo serialize($transfer_rows);?>'>
		<input type="submit" class="btn btn-success" value="Export To PDF" name="export_pdf">
	<?php 
		echo $this->Form->end();
	?>
	</div>
</div>					
 
</div>
</div>
<div class="modal-footer">	
	<button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>	
</div>