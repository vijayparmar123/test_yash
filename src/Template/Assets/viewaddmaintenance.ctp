<?php
use Cake\Routing\Router;

$created_by = isset($maintenace_data['created_by'])?$this->ERPfunction->get_user_name($maintenace_data['created_by']):'NA';
$approved_by = (isset($maintenace_data['approved_status']) && ($maintenace_data['approved_status'] == 1))?$this->ERPfunction->get_user_name($maintenace_data['approve_by']):'NA';
$maintenance_type = ($maintenace_data['maintenance_type'])?"Corrective / Breakdown":"Preventive / Routine";
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
		if(!empty($maintenace_data) ){
			 
	?>	
	    
		<table width="100%" border="1" >
			<tbody>
				<tr align="center">
				<td colspan="8"><?php echo $this->ERPfunction->viewheader($maintenace_data['maintenance_date']);?></td>
				</tr>
				<tr align="center"><td colspan="8"><h2><strong>Asset Maintenance</strong></h2></td></tr>
				<tr>
					<td colspan="8" > <strong> Project Name: <?php echo $this->ERPfunction->get_projectname($maintenace_data['project_id']);?></strong></td>
				</tr>
				<tr>
					<td><strong>A.M.O. No.:</strong></td>
					<td colspan="3" > <?php echo $maintenace_data['amo_no']; ?></td>
					<td colspan="1" ><strong>Date:</strong> </td>
					<td colspan="3" > <?php echo $this->ERPfunction->get_date($maintenace_data['maintenance_date']); ?>  </td>
				</tr>
				<tr>
					<td><strong>Asset Group:</strong></td>
					<td colspan="3" ><?php echo $this->ERPfunction->get_asset_group_name($maintenace_data['asset_group']); ?></td>
					<td colspan="1" ><strong>Asset ID:</strong> </td>
					<td colspan="3"><?php echo $this->ERPfunction->get_asset_code($maintenace_data['asset_id']); ?> </td>
				</tr>
				<tr>
					<td><strong>Asset Name:</strong></td>
					<td colspan="7" ><?php echo $this->ERPfunction->get_asset_name($maintenace_data['asset_id']); ?></td>
				</tr>
				<tr>
					<td><strong>Asset Make:</strong> </td>
					<td colspan="3"><?php echo $this->ERPfunction->get_asset_make($maintenace_data['asset_id']); ?> </td>
					<td colspan="1" ><strong>Asset Capacity:</strong> </td>
					<td colspan="3"><?php echo $this->ERPfunction->get_asset_capacity($maintenace_data['asset_id']); ?> </td>
				</tr>
				<tr>
					<td><strong>Model No:</strong> </td>
					<td colspan="3"><?php echo $maintenace_data['model_no']; ?> </td>
					<td colspan="1" ><strong>Identity / Veh. No.:</strong> </td>
					<td colspan="3"><?php echo $maintenace_data['vehicle_no']; ?> </td>
				</tr>	
				<tr>
					<td colspan="2"><strong>Maintenance Type:</strong></td>
					<td colspan="6" ><?php echo $maintenance_type; ?></td>
				</tr>
				<tr>
					<td><strong>Party's Name:</strong></td>
					<td colspan="7" ><?php echo $maintenace_data['party_name']; ?></td>
				</tr>				
				<tr>
					<td colspan="2"><strong>Amount of Expense:</strong> </td>
					<td colspan="2"><?php echo $maintenace_data['expense_amount']; ?> </td>
					<td colspan="1" ><strong>Payment:</strong> </td>
					<td colspan="3"><?php echo ($maintenace_data['payment_by'] == 1)?"Cash":"Cheque";; ?> </td>
				</tr>
				<tr>
					<td colspan="2"><strong>Voch. No. / Inw. No.:</strong> </td>
					<td colspan="2"><?php echo $maintenace_data['voucher_no']; ?> </td>
					<td colspan="1" ><strong>Supervised By:</strong> </td>
					<td colspan="3"><?php echo $maintenace_data['supervised_by']; ?> </td>
				</tr>
				</table>
				<table  width="100%" border="1" >
				<tr>
					<td colspan="8" align="center"><strong>Description</strong></td>
				</tr>
				<?php 
				$created_date=date("Y-m-d",strtotime($maintenace_data['created_date']));
				if($created_date < "2020-02-07")
				{
				?>
				<tr>
					<td colspan="3"><strong>Material / Spares/ Tools / Service / Others</strong></td>
					<td colspan="3"><strong>Reason</strong></td>
					<td colspan="3"><strong>Amount</strong></td>
				</tr>
				<tr>
					<td colspan="3"><pre><?php echo $maintenace_data['desc_maintenance']; ?></pre></td>
					<td colspan="3"><pre><?php echo $maintenace_data['reason']; ?></pre></td>
					<td colspan="3"><pre><?php echo $maintenace_data['desc_amount']; ?></pre></td>
				</tr>
				<?php }else{ ?>
				<tr>
					<td colspan='2'><strong>Material / Spares/ Tools/ Service / Others</strong></td>
					<td><strong>Quantity</strong></td>
					<td><strong>Unit</strong></td>
					<td><strong>Rate</strong></td>
					<td><strong>GST(%)</strong></td>
					<td><strong>Amount</strong></td>
				</tr>
				<?php 
				$total_amount = 0;
				foreach($maintenace_details as $retrive){
				$total_amount += $retrive["amount"];
				?>
				<tr>
					<td colspan='2'><?php echo $retrive["material"]; ?></td>
					<td><?php echo $retrive["quantity"]; ?></td>
					<td><?php echo $retrive["unit"]; ?></td>
					<td><?php echo $retrive["rate"]; ?></td>
					<td><?php echo $retrive["gst"]; ?></td>
					<td><?php echo $retrive["amount"]; ?></td>
				</tr>
				<?php } ?>
				<tr>
					<td colspan='2'></td>
					<td></td>
					<td></td>
					<td></td>
					<td><b>Total</b></td>
					<td><b><?php echo $total_amount; ?></b></td>
				</tr>
				<tr>
					<td ><strong>Reason:</strong></td>
					<td colspan="7" ><?php echo $maintenace_data['reason']; ?></td>
				</tr>
				<?php } ?>
				</table>
				<table width="100%" border="1" >
				<tr>
					<td align="center" colspan="4"><h3><strong> Made By </strong></h3>
						<?php echo $created_by; ?>
					</td>
					<td align="center" colspan="4"><h3><strong> Approved By </strong></h3>
						<?php echo $approved_by; ?>
					</td>
				</tr>
				
				
			</tbody>
		</table>
	
		<div class="row" style="font-style:italic;color:gray;padding-top:15px;">
			<div class="col-md-6 pull-left">
				<div class="add_field">
				
										<h2>Attachment</h2>
							<?php 
							$attached_files = json_decode($maintenace_data["attachment"]);
							$attached_label = json_decode(stripcslashes($maintenace_data['attach_label']));						
							if(!empty($attached_files))
							{							
								$i = 0;
								foreach($attached_files as $file)
								{?>
									<div class='del_parent'>
										<div class='form-row'>
											<div class='col-md-4'>
												<?php echo $attached_label[$i];?>
												<input type='hidden' name='attach_label[]' value='<?php echo $attached_label[$i];?>' class='form-control'>
											</div>
											<div class='col-md-4'><a href="<?php echo $this->ERPfunction->get_signed_url($file);?>" class="btn btn-primary" target="_blank">View File</a>
											<input type='hidden' name='old_attach_file[]' value='<?php echo $file;?>' class='form-control'></div>
											
										</div>
									</div>							
								<?php $i++;
								}
							}
							?>
                        	</div>
			</div>
			<div class="col-md-6 pull-right">
				<div class="col-md-2 pull-right">						 
					<a href="../printassetmaintenance/<?php echo $maintenace_data["maintenace_id"];?>" class="btn btn-info	" id="print_this" target="_blank"><i class="icon-print"></i> Print</a>
				</div>
			</div>
		</div> 
	<?php  
		}
	?>
	</div>
</div>
               