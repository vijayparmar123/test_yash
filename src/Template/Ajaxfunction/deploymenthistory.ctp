<script type="text/javascript">
	$( document ).ready(function(){
		$("body").on("click", ".del", function(){
			if(confirm('Are you sure, you want Delete this Medicine?')){
				var id = $(this).attr('id');
				$.ajax({
					type: 'POST',
					url: '<?php echo $this->Url->build([
							"controller" => "Doctor",
							"action" => "delete"]);?>',
					data : {department:id},
					success: function (data)
					{
					$('body .del-'+id).hide();
					jQuery("#dep option[value="+id+"]").remove();
					}
				});
			}
		});
		});
</script>

<div class="modal-header" >
    <button type="button" class="close" data-dismiss="modal">X</button>
	<h4 class="modal-title">Transfer History</h4>
</div>
<div class="modal-body clearfix">
<div class="controls">
	<h6> Employee Name: <?php echo $this->ERPfunction->get_user_name($user_id);?></h6>
	<?php 
		echo "<table class='table table-bordered'>";
		echo "<tr>";
		echo "<th>Sr.No.</th>";
		echo "<th>Project</th>";
		// echo "<th>From Date</th>";
		echo "<th>Transfer Date</th>";
		if(!empty($data))
		{
			echo "<th>Delete</th>";
		}
		echo "</tr>";
		$i = 1;
		$rows = array();
		$rows[] = array("Sr.No.","Project","Transfer Date");
		
		foreach($user_data as $urow)
		{
			$csv = array();
			$csv[] = $i;
			$csv[] = $this->ERPfunction->get_projectname($first_project);
			$csv[] = $urow['date_of_joining']->format('d-m-Y');
			$rows[] = $csv;
			
			echo "<tr>
			<td>{$i}</td>
			<td>{$this->ERPfunction->get_projectname($first_project)}</td>
			<td>{$urow['date_of_joining']->format('d-m-Y')}</td>
			</tr>";
			$i++;
		}
		if(!empty($data))
		{
			
			foreach($data as $row)
			{
				$transfer_date = date('d-m-Y',strtotime($row['transfer_date']));
				// echo "<tr>
				// <td>{$this->ERPfunction->get_projectname($row['old_project'])}</td>
				// <td>{$this->ERPfunction->get_projectname($row['new_project'])}</td>
				// <td>{$row['transfer_date']}</td>
				// </tr>";
				$csv = array();
				$csv[] = $i;
				$csv[] = $this->ERPfunction->get_projectname($row['new_project']);
				$csv[] = $transfer_date;
				$rows[] = $csv;
				echo "<tr>
					<td>{$i}</td>
					<td>{$this->ERPfunction->get_projectname($row['new_project'])}</td>
					<td>{$transfer_date}</td>
					<td><a href='{$this->request->base}/Humanresource/deletetransfer/{$row['history_id']}' class='btn btn-danger btn-clean action-btn'><i class='icon-trash'></i>Delete</a></td>
					</tr>";
					$i++;
			}
		}
			echo "</table>";
		
	
	
	?>
	
</div>
</div>
<div class="modal-footer">	
	<div class="col-md-4">
	<?php 
		echo $this->Form->Create('',['id'=>'export','class'=>'form_horizontal formsize','method'=>'post','url'=>['controller'=> 'Humanresource','action'=>'exceldeploymenthistory']]);
	?>
		<input type="hidden" name="rows" value='<?php echo base64_encode(serialize($rows));?>'>
		<input type="submit" class="btn btn-success" value="Export To Excel" name="export_csv">
		<?php $this->Form->end(); ?>
	</div>
	<div class="col-md-4">
		<a href="<?php echo $this->request->base;?>/Humanresource/printdeploymenthistory/<?php echo $user_id; ?>" class="btn btn-primary" id="print_this" target="_blank"><i class="icon-print"></i> Print</a>
	</div>
	<button type="button" class="btn btn-default btn-clean" data-dismiss="modal">Close</button>	
</div>