<?php
use Cake\Routing\Router;

$created_by = isset($erp_grn_details['created_by'])?$this->ERPfunction->get_user_name($erp_grn_details['created_by']):'NA';
$last_edit = isset($erp_grn_details['last_edit'])?date("m-d-Y H:i:s",strtotime($erp_grn_details['last_edit'])):'NA';
$last_edit_by = isset($erp_grn_details['last_edit_by'])?$this->ERPfunction->get_user_name($erp_grn_details['last_edit_by']):'NA';

?>
<?php 
if(!$is_capable)
	{
		$this->ERPfunction->access_deniedmsg();
	}
else
{ ?>
<div class="col-md-10 ">

	<div class="prevew_pr">		
	<?php 
		if(!empty($debit_detail) ){
			 
	?>	
	    
		<table width="100%" border="1" >
			<tbody>
				<tr align="center">
				<td colspan="8"><?php echo $this->ERPfunction->viewheader($debit_detail['date']);?></td>
				</tr>
				<!--<tr align="center"><td colspan="8"><h1><strong>YashNand Engineers & Contractors</strong></h1></td></tr> -->
				<tr align="center"><td colspan="8"><h2><strong>Debit Note</strong></h2></td></tr>
				<tr>
					<!--<td colspan="2" > <strong> Project Code: <?php // echo $this->ERPfunction->get_projectcode($erp_grn_details['project_id']);?></strong></td> -->
					<td colspan="8" > <strong> Project Name: <?php echo $this->ERPfunction->get_projectname($debit_detail['project_id'])/*.', &nbsp;'.$this->ERPfunction->get_projectaddress($erp_grn_details['project_id']).', &nbsp;'.$this->ERPfunction->get_projectcity($erp_grn_details['project_id']) */;?></strong></td>
				</tr>
				<tr>
					<td><strong>Debit Note No:</strong></td>
					<td colspan="2" > <?php echo $debit_detail['debit_note_no']; ?></td>
					<td colspan="2" ><strong>Date:</strong> </td>
					<td colspan="3" > <?php echo $this->ERPfunction->get_date($debit_detail['date']); ?>  </td>
				</tr>
				<tr>
					<td><strong>Debit To:</strong></td>
					<td colspan="7" ><?php echo $this->ERPfunction->get_vendor_name($debit_detail['debit_to']); ?></td>
				</tr>
				
				<tr>
					<td><strong>Receiver's Name:</strong> </td>
					<td colspan="7"><?php echo $debit_detail['receiver_name']; ?> </td>
				</tr>
				
				<tr>
					<td><strong>Reason / Remarks:</strong> </td>
					<td colspan="7"><?php echo $debit_detail['reason']; ?> </td>
				</tr>
				
				<tr>
					<td align="center" colspan="6"><strong>Material / Item</strong></td>
				</tr>
				<tr>
					<td><strong>Sr.No</strong></td>
					<td colspan="2"><strong>Material / Item</strong></td>
					<td><strong>Approx. Quantity</strong></td>
					<td><strong>Unit</strong></td>
					<td><strong>Approx. Rate</strong></td>
					<td><strong>Approx Amount</strong></td>
				
				</tr>
				<?php 
					$i = 1;
					foreach($previw_list as $retrive_material){
					$mt = $this->ERPfunction->get_material_title($retrive_material['material_id']);
				?>
				<tr>
					<td><?php echo $i; ?></td>
					<td colspan="2"><?php echo $this->ERPfunction->get_material_title($retrive_material['material_id']);?></td>
					<td><?php echo $retrive_material['quantity'];?></td>
					<td><?php echo $this->ERPfunction->get_category_title($this->ERPfunction->get_material_unit_id($retrive_material['material_id']));?></td>
					<td><?php echo $retrive_material['rate'];?></td>
					<td><?php echo $retrive_material['total_amount']; ?></td>
				</tr>
				<?php $i++; } ?>
				 
				<tr>
					<td align="center" colspan="3"><h3><strong> Made By </strong></h3>
						<?php
						if($debit_detail['created_by']){
							echo $this->ERPfunction->get_user_name($debit_detail['created_by']); 
						}
						?>
					</td>
					<td align="center" colspan="4"><h3><strong> Approved By </strong></h3>
						<?php
						$approver = array();
						$ids = array();
						foreach($previw_list as $retrive_material){
							if(!in_array($retrive_material['approved_by'],$ids))
							{
							$approver[] = $this->ERPfunction->get_user_name($retrive_material['second_approved_by']);
							$ids[] = $retrive_material['approved_by'];
							}
						}
						foreach($approver as $app){
								echo $app . "<br>";
						}
						?>
					</td>
				</tr>
				
				
			</tbody>
		</table>
	
		<div class="row" style="font-style:italic;color:gray;padding-top:15px;">
			<div class="col-md-6 pull-left">
				<div class="add_field">
				
							<h2>Attachment</h2>
							<div class="col-md-4">
							<?php if($debit_detail['attachment'] != ""){ ?>
							<a href="<?php echo $this->ERPfunction->get_signed_url($debit_detail['attachment']);?>" download="<?php echo $debit_detail['attachment'];?>" class="btn btn-info btn-clean"><i class="icon-download-alt"></i><?php echo $debit_detail['attachment'];?></a>
							<?php } ?>
						</div>
                        	</div>
			</div>
			<div class="col-md-6 pull-right">
				<a href="../printinventorydebit/<?php echo $debit_detail["debit_id"];?>" class="btn btn-info	" id="print_this" target="_blank"><i class="icon-print"></i> Print</a>
				
				
			</div>
		</div> 
	<?php  
		}
	?>
	</div>
</div>
<?php } ?>        