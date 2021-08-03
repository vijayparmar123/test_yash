<?php
use Cake\Routing\Router;

$created_by = isset($record['crated_by'])?$this->ERPfunction->get_user_name($record['crated_by']):'NA';
if($record['working_status'] == "working")
{
	$working_status = "Working";
}elseif($record['working_status'] == "breakdown"){
	$working_status = "Break Down";
}else{
	$working_status = "Idle";
}

?>
<style>
pre{
	background-color:white;
	border:none;
	font-family: 'Helvetica Neue', 'Helvetica', Arial, sans-serif;
	font-size: 16px;
}

</style>

<div class="col-md-10 ">

	<div class="prevew_pr">		
	<?php 
	
		if(!empty($record) ){
			 
	?>	
	    
		<table width="100%" border="1" >
			<tbody>
				<tr align="center">
				<td colspan="8"><?php echo $this->ERPfunction->viewheader($record['date']);?></td>
				</tr>
				<tr align="center"><td colspan="8"><h2><strong>Equipment Log - Owned</strong></h2></td></tr>
				<tr>
					<td colspan="8" > <strong> Project Name: <?php echo $this->ERPfunction->get_projectname($record['project_id']);?></strong></td>
				</tr>
				<tr>
					<td><strong>E.L No.:</strong></td>
					<td colspan="3" > <?php echo $record['el_no']; ?></td>
					<td colspan="1" ><strong>Date:</strong> </td>
					<td colspan="3" > <?php echo $this->ERPfunction->get_date($record['date']); ?>  </td>
				</tr>
				<tr>
					<td><strong>Asset Group:</strong></td>
					<td colspan="3" ><?php echo $this->ERPfunction->get_asset_group_name($record["asset_group_id"]); ?></td>
					<td colspan="1" ><strong>Asset ID:</strong> </td>
					<td colspan="3"><?php echo $record['asset_code']; ?> </td>
				</tr>
				<tr>
					<td><strong>Asset Name:</strong></td>
					<td colspan="7" ><?php echo $this->ERPfunction->get_asset_name($record['asset_id']); ?></td>
				</tr>
				<tr>
					<td><strong>Asset Make:</strong> </td>
					<td colspan="3"><?php echo $record['asset_make']; ?> </td>
					<td colspan="1" ><strong>Asset Capacity:</strong> </td>
					<td colspan="3"><?php echo $record['asset_capacity']; ?> </td>
				</tr>
				<tr>
					<td><strong>Model No:</strong> </td>
					<td colspan="3"><?php echo $record['asset_model']; ?> </td>
					<td colspan="1" ><strong>Identity / Veh. No.:</strong> </td>
					<td colspan="3"><?php echo $record['asset_identity']; ?> </td>
				</tr>	
				<tr>
					<td><strong>Working Status:</strong></td>
					<td colspan="6" ><?php echo $working_status; ?></td>
				</tr>				
				<tr>
					<td><strong>Duty Time(hr.):</strong> </td>
					<td colspan="3"><?php echo $record['duty_time']; ?> </td>
					<td colspan="1" ><strong>Breakdown Time(hr.):</strong> </td>
					<td colspan="3"><?php echo $record['breakdown_time']; ?> </td>
				</tr>
				<tr>
					<td><strong>Start (km):</strong> </td>
					<td colspan="3"><?php echo $record['start_km']; ?> </td>
					<td colspan="1" ><strong>Start (hr):</strong> </td>
					<td colspan="3"><?php echo $record['start_hr']; ?> </td>
				</tr>
				<tr>
					<td><strong>Stop (km):</strong> </td>
					<td colspan="3"><?php echo $record['stop_km']; ?> </td>
					<td colspan="1" ><strong>Stop (hr):</strong> </td>
					<td colspan="3"><?php echo $record['stop_hr']; ?> </td>
				</tr>
				<tr>
					<td><strong>Usage (km):</strong> </td>
					<td colspan="3"><?php echo $record['usage_km']; ?> </td>
					<td colspan="1" ><strong>Usage (hr):</strong> </td>
					<td colspan="3"><?php echo $record['usage_hr']; ?> </td>
				</tr>
				<tr>
					<td><strong>Driver Name:</strong></td>
					<td colspan="7" ><?php echo $record['driver_name']; ?></td>
				</tr>
				<tr>
					<td><strong>Details of Usage:</strong></td>
					<td colspan="7" ><?php echo $record['usage_detail']; ?></td>
				</tr>
				 
				<tr>
					<td align="center" colspan="4"><h3><strong> Made By </strong></h3>
						<?php echo $created_by; ?>
					</td>
					<td align="center" colspan="4"><h3><strong> Approved By </strong></h3>
						<?php echo $created_by; ?>
					</td>
				</tr>
				
				
			</tbody>
		</table>
		<div class="row" style="font-style:italic;color:gray;padding-top:15px;">
			<div class="col-md-6 pull-right">
				<div class="col-md-2 pull-right">						 
					<a href="../printequipmentowned/<?php echo $record["id"];?>" class="btn btn-info" id="print_this" target="_blank"><i class="icon-print"></i> Print</a>
				</div>
			</div>
		</div>
		
	<?php  
		}
	?>
	</div>
</div>
               