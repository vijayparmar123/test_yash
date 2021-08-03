<?php
use Cake\Routing\Router;

$cgst = 0;
$sgst = 0;
$igst = 0;
$gross = 0;
if(!$is_capable)
{
	$this->ERPfunction->access_deniedmsg();
}
else{
$party_first_number = $result = substr($data['party_gst_no'], 0, 2);
$yashnand_first_number = $result = substr($data['yashnand_gst_no'], 0, 2);

if(is_numeric($party_first_number))
{
	if($party_first_number === $yashnand_first_number)
	{
		$cgst = 1;
		$sgst = 1;
		$gross = 1;
	}else{
		$igst = 1;
		$gross = 1;
	}
}

if($data['party_type'] == "temp_emp" )
	{
		$partyname = $this->ERPfunction->get_user_name($data['party_id']);
	}else{
		$partyname = (is_numeric($data['party_id']))?$this->ERPfunction->get_vendor_name($data['party_id']):$this->ERPfunction->get_vendor_name_by_code($data['party_id']);
	}
?>
 
<style>
div.checker.disabled span, div.radio.disabled span {
   background: #B3B3B3; 
    color: black; 
}
pre{
	color: #333;
    font-size: 15px;
	font-family: 'Helvetica Neue', 'Helvetica', Arial, sans-serif;
}
</style>

<div class="col-md-10 ">
<div class="col-md-12">
	<div class="prevew_pr">		
	<?php 
		if(!empty($data) ){			 
	?>	    
	<div id="scrolling-div">
		<table width="100%" border="1" >
			<tbody>
				<!-- <tr align="center"><td colspan="13"><?php echo $this->ERPfunction->viewheader($data['bill_date']);?></td></tr>-->
				<tr align="center"><td colspan="13"><h2><strong><?php echo $partyname ?></strong></h2></td></tr>
				<tr>
					<td colspan="2"><strong>Client :</strong></td>
					<td colspan="11">YashNand Engineers & Contractors PVT LTD [GST No. – <?php echo $data['yashnand_gst_no'] ?>]</td>
				</tr>
				<tr>
					<td colspan="2"><strong>Project Name:</strong></td>
					<td colspan="11"><?php echo $this->ERPfunction->get_projectname($data['project_id']);?></td>
				</tr>
				<tr>
					<td colspan="2"><strong>Bill No:</strong></td>
					<td colspan="4" > <?php echo $data['bill_no']; ?></td>
					<td colspan="2"><strong>Date:</strong> </td>
					<td colspan="4" ><?php echo date("d/m/Y",strtotime($data['bill_date'])); ?>  </td>
				</tr>	
				<tr>
					<td colspan="2"><strong>Contact No.:</strong> </td>
					<td colspan="4"><?php echo $data["party_no1"];?> </td>
					<td colspan="2" ><strong>Bill Duration: </strong></td>
					<td colspan="5"> <?php echo date("d/m/Y",strtotime($data["bill_from_date"]))." to ".date("d/m/Y",strtotime($data["bill_to_date"])); ?></td>
				</tr>
				<tr>
					<td colspan="2" ><strong>PAN Card No:</strong> </td>
					<td colspan="4"><?php echo $data['party_pan_no']; ?> </td>
					<td colspan="2" ><strong>GST No:</strong></td>
					<td colspan="4"> <?php echo $data['party_gst_no']; ?></td>
				</tr>
				<tr>
					<td colspan="2" ><strong>Our Abstract No.:</strong> </td>
					<td colspan="4"><?php echo $data['our_abstract_no']; ?> </td>
					<td colspan="2" ><strong>WO No:</strong></td>
					<td colspan="4"> <?php echo $data['wo_no']; ?></td>
				</tr>
				<tr>
					<td colspan="2"><strong>Type of Work:</strong></td>
					<td colspan="12" ><?php echo $data['type_of_work']; ?></td>
				</tr>
				<tr>
					
					<td colspan="12" ></td>
				</tr>
				</tbody>
				
				<tbody>
				<tr>
					<th rowspan="2" class="text-center">Item No</th>
					<th rowspan="2" class="text-center">Description</th>
					<th rowspan="2" class="text-center">Unit</th>
					<th colspan="3" class="text-center">Quantity</th>
					<th rowspan="2" class="text-center">Applied Rate</th>
					<th rowspan="2" class="text-center">Full Rate</th>
					<?php if($data['type_of_bill'] == "Labour with Material"){ ?>
						<th colspan="1" class="text-center">Amount</th>
					<?php }else{ ?>
						<th colspan="3" class="text-center">Amount</th>
					<?php } ?>
				</tr>
				<tr>
					<th class="text-center">This Bill</th>
					<th class="text-center">Up To <br>Previous Bill</th>
					<th class="text-center">Till Date</th>
					<?php if($data['type_of_bill'] != "Labour with Material"){ ?>
					<th class="text-center">This Bill</th>
					<th class="text-center">Up To <br> Previous Bill</th>
					<?php } ?>
					<th class="text-center">Till Date</th>
				</tr>
				
				<?php 
					foreach($detail_data as $retrive_material){
				?>
				<tr>
					<td class="text-center"><?php echo $retrive_material['item_no']; ?></td>
					<td class="text-center"><?php echo $this->ERPfunction->get_category_title($retrive_material['description']);?></td>
					<td class="text-center"><?php echo $retrive_material['unit'];?></td>
					<td class="text-right"><?php echo $retrive_material['quantity_this_bill'];?></td>
					<td class="text-right"><?php echo $retrive_material['quantity_previous_bill'];?></td>
					<td class="text-right"><?php echo $retrive_material['quantity_till_date']; ?> </td>
					<td class="text-right"><?php echo $retrive_material['rate']; ?> </td>
					<td class="text-right"><?php echo $retrive_material['full_rate']; ?> </td>
					<?php if($data['type_of_bill'] != "Labour with Material"){ ?>
					<td class="text-right"><?php echo $retrive_material['amount_this_bill']; ?> </td>
					<td class="text-right"><?php echo $retrive_material['amount_previous_bill']; ?> </td>
					<?php } ?>
					<td class="text-right"><?php echo $retrive_material['amount_till_date']; ?> </td>
				</tr>
				<?php 
				
				} ?>
				
				<tr>
					<td colspan="8" class="text-right"><strong>Debit Note</strong></td>
					<?php if($data['type_of_bill'] != "Labour with Material"){ ?>
					<td class="text-right"><?php echo $data['debit_this_bill'];?></td>
					<td class="text-right"><?php echo $data['debit_previous_bill'];?></td>
					<?php } ?>
					<td class="text-right"><?php echo $data['debit_till_date'];?></td>
				</tr>
				<tr>
					<td colspan="8" class="text-right"><strong>Reconciliation / Material Debit Note</strong></td>
					<?php if($data['type_of_bill'] != "Labour with Material"){ ?>
					<td class="text-right"><?php echo $data['reconciliation_this_bill'];?></td>
					<td class="text-right"><?php echo $data['reconciliation_previous_bill'];?></td>
					<?php } ?>
					<td class="text-right"><?php echo $data['reconciliation_till_date'];?></td>
				</tr>
				<tr>
					<td colspan="8" class="text-right"><strong>GRAND TOTAL</strong></td>
					<?php if($data['type_of_bill'] != "Labour with Material"){ ?>
					<td class="text-right"><?php echo $data['sum_a'];?></td>
					<td class="text-right"><?php echo $data['sum_b'];?></td>
					<?php } ?>
					<td class="text-right"><?php echo $data['sum_c'];?></td>
				</tr>

				<?php if($data['type_of_bill'] == "Labour with Material"){ ?>
				<tr>
					<td colspan="8" class="text-right"><strong>MATERIAL ADVANCE OR THIS BILL</strong></td>
					<td class="text-right"><?php echo $data['material_advance'];?></td>
				</tr>
				<tr>
					<td colspan="8" class="text-right"><strong>AMOUNT - TILL DATE</strong></td>
					<td class="text-right"><?php echo $data['amount_till_date_labour'];?></td>
				</tr>
				<tr>
					<td colspan="8" class="text-right"><strong>AMOUNT - UPTO PREVIOUS BILL</strong></td>
					<td class="text-right"><?php echo $data['amount_upto_previous_labour'];?></td>
				</tr>
				
				<?php } ?>

				<tr>
					<td colspan="8" class="text-right"><strong>THIS BILL AMOUNT</strong></td>
					<td <?php echo ($data['type_of_bill'] != "Labour with Material")?"colspan='4'":""; ?> class="text-right"><?php echo $data['this_bill_amount'];?></td>
				</tr>
				<?php if($cgst){ ?>
				<tr>
					<td colspan="<?php echo ($data['type_of_bill'] != "Labour with Material")?"8":"7"; ?>" class="text-right"><strong>CGST (%)</strong></td>
					<td class="text-right"><?php echo $data['cgst_percentage']."%";?></td>
					<td <?php echo ($data['type_of_bill'] != "Labour with Material")?"colspan='3'":""; ?> class="text-right"><?php echo $data['cgst'];?></td>
				</tr>
				<?php } ?>
				<?php if($sgst){ ?>
				<tr>
					<td colspan="<?php echo ($data['type_of_bill'] != "Labour with Material")?"8":"7"; ?>" class="text-right"><strong>SGST (%)</strong></td>
					<td class="text-right"><?php echo $data['sgst_percentage']."%";?></td>
					<td <?php echo ($data['type_of_bill'] != "Labour with Material")?"colspan='3'":""; ?> class="text-right"><?php echo $data['sgst'];?></td>
				</tr>
				<?php } ?>
				<?php if($igst){ ?>
				<tr>
					<td colspan="<?php echo ($data['type_of_bill'] != "Labour with Material")?"8":"7"; ?>" class="text-right"><strong>IGST (%)</strong></td>
					<td class="text-right"><?php echo $data['igst_percentage']."%";?></td>
					<td <?php echo ($data['type_of_bill'] != "Labour with Material")?"colspan='3'":""; ?> class="text-right"><?php echo $data['igst'];?></td>
				</tr>
				<?php } ?>
				<?php if($gross){ ?>
				<tr>
					<td colspan="8" class="text-right"><strong>GROSS AMOUNT</strong></td>
					<td <?php echo ($data['type_of_bill'] != "Labour with Material")?"colspan='3'":""; ?> class="text-right"><?php echo $data['gross_amount'];?></td>
				</tr>
				<?php } ?>
				<tr>
					<td colspan="<?php echo ($data['type_of_bill'] != "Labour with Material")?"8":"7"; ?>" class="text-right"><strong>RETENTION MONEY</strong></td>
					<td class="text-right"><?php echo $data['retention_percentage']."%";?></td>
					<td <?php echo ($data['type_of_bill'] != "Labour with Material")?"colspan='3'":""; ?> class="text-right"><?php echo $data['retention_money'];?></td>
				</tr>
				<tr>
					<td colspan="8" class="text-right"><strong>NET AMOUNT</strong></td>
					<td <?php echo ($data['type_of_bill'] != "Labour with Material")?"colspan='4'":""; ?> class="text-right"><?php echo $data['net_amount'];?></td>
				</tr>
				</tbody>
				</table>
				<table width="100%" border="1" >
			<tbody>
				 <tr>
					<td colspan="13" class="text-center" style="background-color:grey;"><strong>FOR OFFICE USE ONLY</strong></td>
				 </tr>
				 <tr>
					<td colspan="8" class="text-right"><strong>AMOUNT PAID</strong></td>
					<td colspan="4" class="text-center"></td>
				</tr>
				<tr>
					<td valign="top"  align="center"  height="120" colspan="3"> 
						<strong><u>CONSTRUCTION MANAGER</u></strong>
					</td>
					<td valign="top"  align="center"  height="120" colspan="3"> 
						<strong><u>ACCOUNTANT</u></strong>
					</td>
					<td valign="top"  align="center"  height="120" colspan="3"> 
						<strong><u>BILLING ENGINEER</u></strong>
					</td>
					<td valign="top"  align="center"  height="120" colspan="3"> 
						<strong><u>PARTY/CONTRACTOR</u></strong>
					</td>
				</tr>
				
			</tbody>
		</table>
		</div>
	<?php  
		}
	?>
	<div class="form-row add_field">
	<div class='col-md-1'></div>
	 <div class="col-md-2" style="color:#333333;"><strong>Attach Documents</strong></div>
			<?php 
			$attached_files = json_decode($data["attachment"]);			
			if(!empty($attached_files))
			{							
				$i = 0;
				foreach($attached_files as $file)
				{?>
					<div class='del_parent'>
						<div class='form-row'>
							<div class='col-md-1'>
								
							</div>
							<div class='col-md-4'><a href="<?php echo $this->ERpfunction->get_signed_url($file);?>" class="btn btn-primary" target="_blank">View File</a>
							<input type='hidden' name='old_attach_file[]' value='<?php echo $file;?>' class='form-control'></div>
						</div>
					</div>							
				<?php $i++;
				}
			}
			
			?>
			</div>
			
		<div class="row" style="font-style:italic;color:gray;">	
		<?php 
			if($role == 'erphead' || $role == 'erpmanager' || $role == 'erpoperator' || $role == "projectcoordinator" || $role == 'constructionmanager' || $role == 'billingengineer')
			{
		?>
			<div class="col-md-6 pull-left">
				<br><br><br>
				<div class="col-md-2">						 
				  <a href="../printsubcontract/<?php echo $data["id"];?>" class="btn btn-primary" id="print_this" target="_blank"><i class="icon-print"></i>Print Abstract</a>
				</div> 
			</div>
			<div class="col-md-6 pull-right">
				<br><br><br>
				<div class="col-md-2">						 
				  <a href="../subcontractletterhead/<?php echo $data["id"];?>" class="btn btn-primary" id="print_this" target="_blank"><i class="icon-print"></i>Print Letter Head</a>
				</div> 
			</div>
		<?php } ?>
		</div>
				
	</div>
	</div>
</div>
<?php } ?>
               