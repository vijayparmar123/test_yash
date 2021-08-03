<script type="text/javascript">
jQuery(document).ready(function() {
	 
	jQuery('#issued_list').DataTable({responsive: true});
});
</script>
<style>
</style>
<div class="modal-header" >
    <button type="button" class="close" data-dismiss="modal">X</button>
	<h4 class="modal-title"> Asset Issued details </h4>
</div>
<div class="modal-body clearfix">
<div class="controls">

<h6> Asset ID: <?php echo $this->ERPfunction->get_asset_code($asset_id); ?></h6>
<h6> Asset Name: <?php echo $this->ERPfunction->get_asset_name($asset_id); ?></h6>
<table id="issued_list"  class="dataTables_wrapper table">
	<thead>
		<th>Issued To</th>
		<th>Date of Issue</th>
		<th>Date of Return</th>
		<th>Project</th>
		<th>Created By</th>
		<?php
		if($this->ERPfunction->retrive_accessrights($role,'edit-issued-history')==1 || $this->ERPfunction->retrive_accessrights($role,'delete-issued-history')==1)
		{
		?>
		<th>Edit / Delete</th>
		<?php
		}
		
		?>
	</thead>
	<tbody>
	<?php 
	$i = 1;
		$issue_rows = array();
		$issue_rows[] = array("Issued To","Date of Issue","Date of Return","Project","Created By");
		foreach($issuedata as $retrive_data)
		{ 
		    $issue_csv = array();
		?>
			<tr id="tr_<?php echo $i; ?>">
				<td><?php echo ($issue_csv[] = $retrive_data['issued_to']); ?></td> 
				<td><?php echo ($issue_csv[] = $this->ERPfunction->get_date($retrive_data['issued_date'])); ?></td> 
				<td><?php echo ($issue_csv[] = ($retrive_data['return_date'] != NULL)?$this->ERPfunction->get_date($retrive_data['return_date']):"NA"); ?></td> 
				<td><?php echo ($issue_csv[] = $this->ERPfunction->get_projectname($retrive_data['project_id'])); ?></td> 
				<td><?php echo ($issue_csv[] = $this->ERPfunction->get_full_user_name($retrive_data['created_by'])); ?></td> 
				<?php
				if($this->ERPfunction->retrive_accessrights($role,'edit-issued-history')==1 || $this->ERPfunction->retrive_accessrights($role,'delete-issued-history')==1)
				{
				?>
				<td>
					<?php if($this->ERPfunction->retrive_accessrights($role,'edit-issued-history')==1){ ?>
					<a class="edit-issued-history badge badge-info" href="javascript:void(0)" data-asset-id="<?php echo $asset_id; ?>" data-record-id="<?php echo $retrive_data['id']; ?>"><i class="icon-edit"></i></a>
					<?php }
					if($this->ERPfunction->retrive_accessrights($role,'delete-issued-history')==1){
					?>
					<a class="delete-issued-history badge badge-info" href="javascript:void(0)" data-asset-id="<?php echo $asset_id; ?>" data-record-id="<?php echo $retrive_data['id']; ?>" data-row-id="<?php echo $i; ?>"><i class="icon-trash"></i></a>
					<?php } ?>
				</td>
				<?php } ?>
			</tr>
	<?php
	$i++;
	$issue_rows[] = $issue_csv;
		}
		?>
	</tbody>
</table>	
		<div class="content">
			<div class="col-md-4">
			<?php echo $this->Form->Create('export_csv',['method'=>'post','class' => 'form-horizontal','url' => ['controller' => 'Assets','action' => 'exportassetissuedhistory']]);?>
				<input type="hidden" name="issue_rows" value='<?php echo serialize($issue_rows);?>'>
				<input type="submit" class="btn btn-success" value="Export To Excel" name="export_csv">
			<?php 
				echo $this->Form->end();
			?>
			</div>
			<div class="col-md-4">
			<?php echo $this->Form->Create('export_pdf',['method'=>'post','class' => 'form-horizontal','url' => ['controller' => 'Assets','action' => 'exportassetissuedhistory']]);?>
				<input type="hidden" name="issue_rows" value='<?php echo serialize($issue_rows);?>'>
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