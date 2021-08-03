<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('.datep').datepicker({
		dateFormat: "dd-mm-yy",
		  changeMonth: true,
	        changeYear: true,
	        yearRange:'-65:+0',
	        onChangeMonthYear: function(year, month, inst) {
	            jQuery(this).val(month + "-" + year);
	        }                    
    });
});
</script>
<div class="col-md-10" >
<?php 
if(!$is_capable)
	{
		$this->ERPfunction->access_deniedmsg();
	}
else
{ ?>
<div class="row">
	<div class="col-md-12">
		<div class="block" style="width:auto;">
			<div class="head bg-default bg-light-rtl">
				<h2>View GRN</h2>
				<div class="pull-right">
					<a href="<?php echo $this->ERPfunction->action_link('Inventory','index');?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
				</div>
			</div>
		
		<div class="content">
		<div class="col-md-12 filter-form">
			<?php 
				$project_id = array();
				$material_id_a = array();
				$vendor_userid_a = array();
				 $project_id = isset($_POST['project_id'])?$_POST['project_id']:'';
				 $from_date = isset($_POST['from_date'])?$_POST['from_date']:'';
				 $to_date = isset($_POST['to_date'])?$_POST['to_date']:'';
				 $material_id_a = isset($_POST['material_id'])?$_POST['material_id']:'';
				 $vendor_userid_a = isset($_POST['vendor_userid'])?$_POST['vendor_userid']:'';
			?>
		<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
					<div class="form-row">
						<div class="col-md-2">Date From -</div>
                        <div class="col-md-4"><input type="text" name="from_date" id="from_date" value="<?php echo $from_date;?>" class="datep form-control"/></div>
						<div class="col-md-2">Date To -</div>
                        <div class="col-md-4"><input type="text" name="to_date" id="to_date" value="<?php echo $to_date;?>" class="datep form-control"/></div>
					</div>
					<div class="form-row">	
						<!-- <div class="col-md-2">GRN.No:</div>
                        <div class="col-md-4">
							<input type="text" name="po_no" id="po_no" value="" class="form-control"/>
						</div>					
						-->
						<div class="col-md-2">Project Name:</div>
                        <div class="col-md-4">
							<select class="select2" style="width: 100%;" name="project_id[]" id="project_id" multiple="multiple">
								<option value="All" selected>All</Option>
								<?php 
									foreach($projects as $retrive_data)
									{
										$selected = (in_array($retrive_data['project_id'],$project_id)) ? "selected" : "";
										echo '<option value="'.$retrive_data['project_id'].'" '. $selected .'>'.$retrive_data['project_name'].'</option>';
									}
								?>
							</select>
						</div>
						<div class="col-md-2">Material Name:</div>
                        <div class="col-md-4">
							<select class="select2 material_id" style="width: 100%;" name="material_id[]" id="material_id_0" multiple="multiple">
								<option value="All" selected>All</Option>
								<?php 
									foreach($material_list as $retrive_data)
									{
										$selected = (in_array($retrive_data['material_id'],$material_id_a)) ? "selected" : "";
										echo '<option value="'.$retrive_data['material_id'].'" '.$selected.'>'.
										$retrive_data['material_title'].'</option>';
									}
								?>
							</select>
						</div>
                    </div>
					<!--<div class="form-row">	
						 <div class="col-md-2">Material ID:</div>
                        <div class="col-md-4">
							<input type="text" name="po_no" id="po_no" value="" class="form-control"/>
						</div>
						<div class="col-md-2">Material Name:</div>
                        <div class="col-md-4">
							<select class="select2 material_id" style="width: 100%;" name="material_id[]" id="material_id_0" multiple="multiple">
								<option value="All">All</Option>
								<?php 
									foreach($material_list as $retrive_data)
									{
										$selected = (in_array($retrive_data['material_id'],$material_id_a)) ? "selected" : "";
										echo '<option value="'.$retrive_data['material_id'].'" '.$selected.'>'.
										$retrive_data['material_title'].'</option>';
									}
								?>
							</select>
						</div>
					</div> -->
					<div class="form-row">	
						<!-- <div class="col-md-2">Vendor ID:</div>
                    	<div class="col-md-4">
							<input type="text" name="vendor_id" id="vendor_id" value="" class="form-control" value=""/>
						</div> -->
						<div class="col-md-2">Vendor Name:</div>
                        <div class="col-md-4">
							<select class="select2"  style="width: 100%;" name="vendor_userid[]" id="vendor_userid" multiple="multiple">
								<option value="All" selected>All</Option>
								<?php 
									foreach($vendor_department as $retrive_data)
									{
										$selected = (in_array($retrive_data['user_id'],$vendor_userid_a)) ? "selected" : "";
										echo '<option value="'.$retrive_data['user_id'].'" '.$selected.'>'.
										$this->ERPfunction->get_vendor_name($retrive_data['user_id']).'</option>';									
									
									}
								?>
							</select>
						</div>
						<div class="col-md-2 text-right">Payment Method</div>
							<div class="col-md-4">
							<select class="select2"   style="width: 100%;" name="payment_mod[]" id="payment_type" >
								<option value="All">-- Select Payment --</Option>
								<option value="Cheque">Cheque</Option>
								<option value="Cash">Cash</Option>
								
								</select>
							</div>
						
					</div>
					<div class="form-row">
							<div class="col-md-2 text-right">GRN No</div>
							<div class="col-md-4"><input name="grn_no" class="" /></div>
							<div class="col-md-2 text-right">Mode of Purchase</div>
							<div class="col-md-4">
							<select class="select2"   style="width: 100%;" name="purchase_mod" id="purchase_mod" >
								<option value="All">-- Select Purchase Mod --</Option>
								<option value="central">Central</Option>
								<option value="local">Local</Option>
								
								</select>
							</div>
					</div>
					<div class="form-row">
							<div class="col-md-2 text-right">Challan No</div>
							<div class="col-md-4"><input name="challan_no" class="form-control"></div>
					</div>
					<!--<div class="form-row">	
						<div class="col-md-2">Mode of Purchase:</div>
                    	<div class="col-md-4">
							<input type="text" name="po_mode" id="po_mode" value="" class="form-control" value=""/>
						</div>
						<div class="col-md-2">Payment Method:</div>
                        <div class="col-md-4">
							<select class="select2" style="width: 100%;" name="payment_method[]" id="payment_method" multiple="multiple">
								<option value="All">All</Option>
								<option value="cash">Cash</Option>
								<option value="cheque">Cheque</Option>									</select>
						</div> 			
					</div>	 -->		
					<div class="form-row">
						<div class="col-md-2"> <div class="col-md-12"><input type="submit" name="go" id="go" class="btn btn-primary" value="Search"/></div></div>
					</div>
		
		<?php $this->Form->end(); ?>
			</div>
			</div>
			<div class="content list custom-btn-clean">
		<script>
		jQuery(document).ready(function() {
		var table = jQuery('#grn_list').DataTable({responsive: true,ordering:false,});
		
		table.column(2).visible( false );

		});
</script>
			
			<table id="grn_list"  class="dataTables_wrapper table table-striped table-hover" style="width:100%">
				<thead>
					<tr>
						<th>Project Name</th>
						<th>G.R.N No</th>
						<th>Time</th>
						<th>Date</th>						
															
						<th>Vendor<br>Name</th>
						<th>Challan<br> No</th>
						<th>Material<br> Name</th>
						<th>Make<br>/ Source</th>
						<th>Vendor<br>/Royalty's Qty.<br>/ Weight</th>
						<th>Actual Qty.<br>/ Weight</th>
						<th>Diff.<br>(+/-)</th>
						<th>Unit</th>
						<th>Mode<br> of<br>Purchase</th>
						<th>Mode<br> of<br>Payment</th>
						<th class="none">Attachment</th>
						<!-- <th class="none">Edit</th> -->
						<th class="none">View</th>
						
					</tr>
				</thead>
				<tbody>
					<?php
					if(isset($grn_list))
					{
						$i = 1;
						$rows = array();
						$rows[] = array("Project Name","G.R.N No","Date","Time","Vendor Name","Challan No","Material Name","Make/Source","Vendor/Royalty\"\s Qty./Weight","Actual Qty./Weight","Diff.(+/-)","Unit","Mode of Purchase","Mode of Payment");
						foreach($grn_list as $retrive_data)
						{ 
							
							if(isset($retrive_data["erp_inventory_grn_detail"]))
							{
								$retrive_data = array_merge($retrive_data,$retrive_data["erp_inventory_grn_detail"]);
							}
							$export = array();
							if($retrive_data['material_id'] != 0)
							{
								$mt = $this->ERPfunction->get_material_title($retrive_data['material_id']);
								$brnd = $this->ERPfunction->get_brandname($retrive_data['brand_id']);
								$static_unit = $this->ERPfunction->get_items_units($retrive_data['material_id']);
							}
							else
							{
								$mt = $retrive_data['material_name'];
								$brnd = $retrive_data['brand_name'];
								$static_unit = $retrive_data['static_unit'];
							}
						?>
							<tr>	
								<td><?php echo ($export[] = $this->ERPfunction->get_projectname($retrive_data['project_id']));?></td>
								
								<td style="width:50px;"><?php echo ($export[] = $retrive_data['grn_no']);?></td>
								<td><?php echo ($export[] = $retrive_data['grn_time']);?></td>									
								<td><?php echo ($export[] = $this->ERPfunction->get_date($retrive_data['grn_date']));?></td>								
															
															
								<td><?php echo ($export[] = $this->ERPfunction->get_vendor_name($retrive_data['vendor_userid']));?></td>								
								<td><?php echo ($export[] = $retrive_data['challan_no']);?></td>								
																
								<td><?php echo ($export[] = $mt);?></td>								
								<td><?php echo ($export[] = $brnd);?></td>								
								<td><?php echo ($export[] = $retrive_data['quantity']);?></td>								
								<td><?php echo ($export[] = $retrive_data['actual_qty']);?></td>								
								<td><?php echo ($export[] = $retrive_data['difference_qty']);?></td>								
								<td><?php echo ($export[] = $static_unit);?></td>								
								<td><?php echo ($export[] = ($retrive_data["po_id"] != "")?'Central':'Local');?></td>								
								<td><?php echo ($export[] = $retrive_data['payment_method']);?></td>
								<td>
									<?php
									$attached_files = json_decode($retrive_data['attach_file']);	
									$attached_label = json_decode(stripcslashes($retrive_data['attach_label']));	
									
									if(!empty($attached_files))
									{							
										$i = 0;
										foreach($attached_files as $file)
										{ 
										   if(!empty($file))
										   { ?>
												<a href="<?php echo $this->request->base;?>/img/users_images/<?php echo $file;?>" download="<?php echo $attached_label[$i];?>" class="btn btn-info btn-clean"><i class="icon-download-alt"></i><?php echo $attached_label[$i];?></a>
											<?php $i++;
											}
										}
									} ?>
								</td>								
								<!-- <td>
									<a class='btn btn-info btn-clean' href='updateapprovedgrn/<?php // echo $retrive_data['grn_id'];?>'><i class="icon-edit"></i> Edit</a>									
								</td> -->							
								<td>
								<?php 
									echo $this->Html->link('<i class="icon-eye-open"></i> View',array('action' => 'previewapprovedgrn', $retrive_data['grn_id']),
									array('class'=>'btn btn-primary btn-clean','target'=>'_blank','escape'=> false));								
									
									if($role == "erphead" || $role == "erpmanager" || $role == "erpoperator")
									{
										echo $this->Html->link('<i class="icon-trash"></i> Delete',array('action' => 'deleteapprovedgrn', $retrive_data['grndetail_id']),
										array('class'=>'btn btn-danger btn-clean','escape'=> false));								
									}								
								
								?>
								</td>
								
								</tr>
						<?php
						$i++;
						$rows[] = $export;
						}
						}
					?>
				</tbody>
			</table>
			
			<?php
			if(isset($grn_list))
			{
			 if($grn_list != NULL){
			?>
			<div class="content">
				<!-- <div class="col-md-2"><a href="javascript:void(0);" class="btn btn-success" id="fullscreen" url='<?php //echo $_SERVER['REQUEST_URI']; ?>' onClick="DoFullScreen()" >View Full Screen</a></div>
				-->
				<div class="col-md-2">
				<form method="post">
					<input type="hidden" name="rows" value='<?php echo serialize($rows);?>'>
					<input type="submit" class="btn btn-success" value="Export To Excel" name="export_csv">
				</form>
				</div>
				<div class="col-md-2">
				<form method="post">
					<input type="hidden" name="rows" value='<?php echo serialize($rows);?>'>
					<input type="submit" class="btn btn-success" value="Export To PDF" name="export_pdf">
				</form>
				</div>
			</div>
		</div>
		<?php }} ?>
		</div>
	</div>
</div>
<?php } ?>
</div>