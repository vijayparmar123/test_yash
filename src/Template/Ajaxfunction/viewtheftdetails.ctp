<script type="text/javascript">
jQuery(document).ready(function() {
	/* jQuery('.select2').select2(); */
	/* jQuery('#transfer_date').datepicker({
		dateFormat: "dd-mm-yy",
		  changeMonth: true,
	        changeYear: true,
	        yearRange:'-65:+0',
	        onChangeMonthYear: function(year, month, inst) {
	            jQuery(this).val(month + "-" + year);
	        }
                   */  
    }); 
	jQuery('#sold_list').DataTable({responsive: true});
	
});
</script>
<style>
#ui-datepicker-div{z-index:9999 !important;}
</style>
<div class="modal-header" >
    <button type="button" class="close" data-dismiss="modal">X</button>
	<h4 class="modal-title"> Asset Theft details </h4>
</div>
<div class="modal-body clearfix">
<div class="controls">


<h6> Asset Name: <?php  echo $assetname;   ?></h6>
<table id="sold_list"  class="dataTables_wrapper table">
	<thead>
		<th>Theft from Project</th>
		<th>Theft Date</th>
		<th>Created By</th>		
		<th>View</th>		
	</thead>
	<tbody>
	<?php 
	$i = 1;
	$theft_rows = array();
	$theft_rows[] = array("Theft from Project","Theft Date","Created By");
	if(!empty($theftdata))
	{
		foreach($theftdata as $retrive_data)
		{ 
		$theft_csv = array();
		?>
			<tr>
				<td><?php echo ($theft_csv[] = $this->ERPfunction->get_projectname($retrive_data['deployed_to'])); ?></td> 
				<td><?php echo ($theft_csv[] = $this->ERPfunction->get_date($retrive_data['theft_date'])); ?></td>
				<td><?php echo ($theft_csv[] = $this->ERPfunction->get_user_name($retrive_data['created_by'])); ?></td>
				<td><a href='./viewaddasset/<?php echo $retrive_data['asset_id'];?>' class="btn btn-info btn-clean"><i class="icon-eye-open"></i> View</a></td>
			
			</tr>
	<?php
	$i++;
	$theft_rows[] = $theft_csv;
		}
	}else{
		echo "<tr><td colspan='4'>No Data Found.</td></tr>";
	}
		?>
	</tbody>
</table>	
<div class="content">
	<div class="col-md-4">
	<?php 
		echo $this->Form->Create('export_csv',['method'=>'post','class' => 'form-horizontal','url' => ['controller' => 'Assets','action' => 'exportassetthefthistory']]);
	?>
		<input type="hidden" name="theft_rows" value='<?php echo serialize($theft_rows);?>'>
		<input type="submit" class="btn btn-success" value="Export To Excel" name="export_csv">
	<?php 
		echo $this->Form->end();
	?>
	</div>
	<div class="col-md-4">
	<?php 
		echo $this->Form->Create('export_csv',['method'=>'post','class' => 'form-horizontal','url' => ['controller' => 'Assets','action' => 'exportassetthefthistory']]);
	?>
		<input type="hidden" name="theft_rows" value='<?php echo serialize($theft_rows);?>'>
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