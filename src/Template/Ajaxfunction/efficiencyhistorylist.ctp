<!--select(["SUM(erp_inventory_is_detail.quantity)"])-->

<script type="text/javascript">
jQuery(document).ready(function() {
	 
	jQuery('#efficiency_history_list').DataTable({responsive: true});
});
</script>
<style>
</style>
<div class="modal-header" >
    <button type="button" class="close" data-dismiss="modal">X</button>
	<h4 class="modal-title"> Efficiecny History </h4>
</div>
<div class="modal-body clearfix">
<div class="controls">

<h6> Asset ID: <?php echo $this->ERPfunction->get_asset_code($asset_id); ?></h6>
<h6> Asset Name: <?php echo $this->ERPfunction->get_asset_name($asset_id); ?></h6>
<table id="efficiency_history_list"  class="dataTables_wrapper table">
	<thead>
		<th>Month & Year</th>
		<th>Total Fuel Issued (Ltr.)</th>
		<th>Total Usage (km)</th>
		<th>Total Usage (hr.)</th>
		<th>Average / Efficiency (Ltr/km)</th>
		<th>Average / Efficiency (Ltr/hr.)</th>
		<th>View Stock Record</th>
	</thead>

	<tbody>
	<?php 
	$i = 1;
		$efficiency_rows = array();
		$efficiency_rows[] = array("Monh & Year","Total Fule Issued","Total Usage(km)","Total Usage(hr)","Average/Efficiency(LTR/KM)","Average/Efficiency(LTR/HR)");

		foreach($efficiencydata as $retrive_data)
		{ 

		    $efficiency_csv = array();
		 	$dates = $retrive_data['date'];
		    $date = date('M-Y',(strtotime($retrive_data['date'])));
		    $total_ltr =  $this->ERPfunction->getTotalFual($asset_id,$dates);
		    $total_km = $retrive_data['total_usage_km'];		     
		    $total_hr = $retrive_data['total_usage_hr'];
		    $ltr_km = number_format($total_ltr / $total_km,2);
		    $ltr_hr = number_format($total_ltr / $total_hr,2);
		    


		    
		?>
			<tr id="tr_<?php echo $i; ?>">

				<td><?php echo ($efficiency_csv[] = $date); ?></td> 
				<td><?php echo ($efficiency_csv[] = $total_ltr); ?></td> 
				<td><?php echo ($efficiency_csv[] = $total_km); ?></td>
				<td><?php echo ($efficiency_csv[] = $total_hr); ?></td> 
				<td><?php echo ($efficiency_csv[] = $ltr_km); ?></td>
				<td><?php echo ($efficiency_csv[] = $ltr_hr); ?></td>
				<td>
				<?php 	
				//	if($this->ERPfunction->retrive_accessrights($role,'viewStoreIsuueHistory')==1)
				//	{
						echo "<a class=' badge badge-info' href='{$this->request->base}/Assets/view_storeisuue_history/{$retrive_data['asset_id']}/{$date}'><i class='icon-eye-open'></i></a>";
				//	}
					?>
					
				</td>
			
			</tr>
	<?php
	$i++;
	$efficiency_rows[] = $efficiency_csv;

		}
		?>
	</tbody>
</table>	
		<div class="content">
			<div class="col-md-4">
				<?php 
					echo $this->Form->Create('export_csv',['method'=>'post','class' => 'form-horizontal','url' => ['controller' => 'Assets','action' => 'exportEquipmentownHistory']]);
				?>
				<input type="hidden" name="efficiency_rows" value='<?php echo serialize($efficiency_rows);?>'>
				<input type="submit" class="btn btn-success" value="Export To Excel" name="export_csv">
			<?php 
				echo $this->Form->end();
			?>
			</div>
			<div class="col-md-4">
				<?php 
					echo $this->Form->Create('export_csv',['method'=>'post','class' => 'form-horizontal','url' => ['controller' => 'Assets','action' => 'exportEquipmentownHistory']]);
				?>
				<input type="hidden" name="efficiency_rows" value='<?php echo serialize($efficiency_rows);?>'>
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