<?php
use Cake\Routing\Router;

$created_by = isset($pr_material['created_by'])?$this->ERPfunction->get_user_name($pr_material['created_by']):'NA';
$last_edit = isset($pr_material['last_edit'])?date("m-d-Y H:i:s",strtotime($pr_material['last_edit'])):'NA';
$last_edit_by = isset($pr_material['last_edit_by'])?$this->ERPfunction->get_user_name($pr_material['last_edit_by']):'NA';

if(!$is_capable)
{
	$this->ERPfunction->access_deniedmsg();
}
else{

?>
 


<div class="col-md-10 ">
<div class="col-md-12">
	<div class="prevew_pr">		
	<?php 
		if(!empty($pr_material) ){
			 
	?>	
	    <div id="scrolling-div">
		<table width="100%" border="1" >
			<tbody>
				<tr align="center">				
					<td colspan="8"><?php echo $this->ERPfunction->viewheader($pr_material['pr_date']);?></td>
				</tr>
				<!--<tr>
				<td style="border-right: 0; width: 136px;  padding: 0;"><?php echo $this->Html->image('/webroot/img/logo/yashNand_logo.png',['style'=>"margin-right: 50%;"] );?></td>
				<td style="border-left: 0;padding-right: 18%;" colspan="6" class="text-center"><h1><strong>YASH-NAND</strong></h1><i><strong>Engineers & Contractors</strong></i></td></tr>
				-->
				<tr align="center"><td colspan="8"><h2><strong>Purchase Requisition (PR)</strong></h2></td></tr>
				<tr>
				<!-- <td colspan="2" > <strong> Project Code:</strong> <?php //echo $this->ERPfunction->get_projectcode($pr_material['project_id']);?></td> -->
					<td colspan="8" > <strong> Project Name:</strong> <?php echo $this->ERPfunction->get_projectname($pr_material['project_id'])/*.', &nbsp;'.$this->ERPfunction->get_projectaddress($pr_material['project_id']).', &nbsp;'.$this->ERPfunction->get_projectcity($pr_material['project_id'])*/ ;?></td>
				</tr>
				<tr>
					<td colspan="2"><strong>P. R. No:</strong> <?php echo $pr_material['prno']; ?></td>
					<td colspan="" class="text-right"><strong>Date:</strong></td><td colspan="2">&nbsp; <?php echo $this->ERPfunction->get_date($pr_material['pr_date']); ?> </td>
					<td colspan="" class="text-right"><strong>Time:</strong></td><td colspan="2">&nbsp; <?php echo $pr_material['pr_time']; ?></td>
				</tr>
			<!--	<tr>
					<td><strong>Raised From:</strong></td>
					<td colspan="2" ><?php echo $this->ERPfunction->get_user_name($pr_material['raise_from']); ?></td>
					<td colspan="2" ><strong>Contact No: (1)</strong> </td>
					<td colspan="2"><?php echo $pr_material['contact_no1']; ?> </td>
				</tr>
				<tr>
					<td><strong>Forwarded To:</strong></td>
					<td colspan="2" ><?php echo $this->ERPfunction->get_user_name($pr_material['forword_to']);?></td>
					<td colspan="2" ><strong>Contact No: (2) </strong></td>
					<td colspan="2"> <?php echo $pr_material['contact_no2']; ?></td>
				</tr> -->
				<tr>
					<td colspan="" ><strong>Contact No: (1)</strong> </td>
					<td colspan="1"><?php echo $pr_material['contact_no1']; ?> </td>
					<td colspan="3" ><strong>Contact No: (2) </strong></td>
					<td colspan="3"> <?php echo $pr_material['contact_no2']; ?></td>
				</tr>
				<tr>
					<td align="center" rowspan="2" ><strong>Material Code</strong></td>
					<td align="center" colspan="4"><strong>Material / Item</strong></td>
					<td rowspan="2" ><strong>Delivery<br>Date<br>(Planned)</strong></td>
					<td rowspan="2" ><strong>Remarks</strong></td>
					<td rowspan="2" ><strong>Usage</strong></td>
				</tr>
				<tr>
					<td style="width: 470px;"><strong>Description</strong></td>
					<td><strong>Make / Source</strong></td>
					<td><strong>Quantity</strong></td>
					<td><strong>Unit</strong></td>
				</tr>
				<?php 
					foreach($previw_list as $retrive_material){
						if(is_numeric($retrive_material['material_id']) && $retrive_material['material_id'] != 0)
						{
							$m_code = $this->ERPfunction->get_materialitemcode($retrive_material['material_id']);
							$mt = $this->ERPfunction->get_material_title($retrive_material['material_id']);
							$brnd = $this->ERPfunction->get_brandname($retrive_material['brand_id']);
							$unit = $this->ERPfunction->get_items_units($retrive_material['material_id']);
						}
						else
						{
							$m_code = $retrive_material['m_code'];
							$mt = $retrive_material['material_name'];
							$brnd = $retrive_material['brand_name'];
							$unit = $retrive_material['static_unit'];
						}
				?>
				<tr>
					<td><?php echo $m_code; ?></td>
					<td><?php echo $mt;?></td>
					<td><?php echo $brnd;?></td>
					<td><?php echo $retrive_material['quantity'];?></td>
					<td><?php echo $unit;?></td>
					<td><?php echo date_format($retrive_material['delivery_date'],'d-m-Y');?> </td>
					<td><?php echo $retrive_material['name_of_subcontractor'];?> </td>
					<td><?php echo $retrive_material['usages'];?> </td>
				</tr>
				<?php } ?>
				 
				<tr>
					<td align="center" colspan="2"><h3><strong> Prepared By </strong></h3>
						<?php
						if($pr_material['created_by']){
							echo $this->ERPfunction->get_user_name($pr_material['created_by']); 
						}
						?>
					</td>
					<td align="center" colspan="6"><h3><strong> Approved By </strong></h3>
						<?php
						$approver = array();
						$ids = array();
						foreach($previw_list as $retrive_material){
							if(!in_array($retrive_material['approved_by'],$ids))
							{
							$approver[] = $this->ERPfunction->get_user_name($retrive_material['approved_by']);
							$ids[] = $retrive_material['approved_by'];
							}
						}
						foreach($approver as $app){
								echo $app . "<br>";
						}
						?>
					</td>
				</tr>
				<!-- <tr>
					<td align="center" colspan="2"> (Material Manager)</td>
					<td align="center"  colspan="5"> (Construction Manager)</td>
				</tr> -->
				
			</tbody>
		</table></div>
		<div class="row" style="font-style:italic;color:gray;padding-top:15px;">			
			<div class="col-md-6 pull-left">
				<div class="add_field">
				
										<h2>Attachment</h2>
							<?php 
							$attached_files = json_decode($pr_material["attach_file"]);
							$attached_label = json_decode(stripcslashes($pr_material['attach_label']));						
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
				<!-- <div class="col-md-4">
					<?php //echo "Created By: {$created_by}"; ?>
				</div>
				<div class="col-md-4">
					<?php //echo "Last Edited By: {$last_edit_by}"; ?>
				</div>
				
				<div class="col-md-3">
					<form action="../potocsv" method="post"><input type="submit" value="Export to CSV" class="btn"></form>
				</div> -->
				<div class="col-md-1 pull-right">			 
					<a href="<?php echo $this->request->base . "/Inventory/printpr/".$pr_material["pr_id"];?>" class="btn btn-info" id="print_this" target="_blank"><i class="icon-print"></i> Print</a>
				</div>	
		</div>
	<?php  
		}
	?>
	</div>
</div>
</div>  
<?php } ?>             