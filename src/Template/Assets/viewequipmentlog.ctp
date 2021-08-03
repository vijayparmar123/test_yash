<?php
use Cake\Routing\Router;

$created_by = isset($data['created_by'])?$this->ERPfunction->get_user_name($data['created_by']):'NA';

?>
<?php
if(!$is_capable)
{
	$this->ERPfunction->access_deniedmsg();
}
else{
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
		if(!empty($data) ){
			 
	?>	
	    
		<table width="100%" border="1" >
			<tbody>
				<tr align="center">
				<td colspan="8"><?php echo $this->ERPfunction->viewheader($data['el_date']);?></td>
				</tr>
				<tr align="center"><td colspan="8"><h2><strong>Equipment Log - Rent</strong></h2></td></tr>
				<tr>
					<td colspan="8" > <strong> Project Name: <?php echo $this->ERPfunction->get_projectname($data['project_id']);?></strong></td>
				</tr>
				<tr>
					<td><strong>E.L No.:</strong></td>
					<td colspan="3" > <?php echo $data['elno']; ?></td>
					<td colspan="1" ><strong>Date:</strong> </td>
					<td colspan="3" > <?php echo $this->ERPfunction->get_date($data['el_date']); ?>  </td>
				</tr>
				<tr>
					<td><strong>Asset Name:</strong></td>
					<td colspan="7" ><?php echo $this->ERPfunction->get_asset_name($data['asset_name']); ?></td>
				</tr>
				<tr>
					<td><strong>Driver Name:</strong> </td>
					<td colspan="3"><?php echo $data['driver_name']; ?> </td>
					<td colspan="1" ><strong>Vehicle No:</strong> </td>
					<td colspan="3"><?php echo $data['vehicle_no']; ?> </td>
				</tr>
				<tr>
					<td><strong>Usage:</strong> </td>
					<td colspan="3"><?php echo $data['el_usage']; ?> </td>
					<td colspan="1" ><strong>Unit of Usage:</strong> </td>
					<td colspan="3"><?php echo ucfirst($data['unit_usage']); ?> </td>
				</tr>	
				<tr>
					<td><strong>Details of Usage:</strong></td>
					<td colspan="7" ><?php echo $data['usage_detail']; ?></td>
				</tr>
				 
				<tr>
					<td align="center" colspan="4"><h3><strong> Made By </strong></h3>
						<?php echo $created_by; ?>
					</td>
					<td align="center" colspan="4"><h3><strong> Approved By </strong></h3>
						<?php echo $data['approved_by']; ?>
					</td>
				</tr>
				
				
			</tbody>
		</table>
		<div class="row" style="font-style:italic;color:gray;padding-top:15px;">
			<div class="col-md-6 pull-right">
				<div class="col-md-2 pull-right">						 
					<a href="../printequipmentrent/<?php echo $data["id"];?>" class="btn btn-info" id="print_this" target="_blank"><i class="icon-print"></i> Print</a>
				</div>
			</div>
		</div>
		
	<?php  
		}
	?>
	</div>
</div>
       <?php } ?>        