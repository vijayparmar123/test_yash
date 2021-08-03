<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery(".dataTables_wrapper").dataTable();
jQuery('.datepick').datepicker({
		dateFormat: "yy-mm-dd",
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
else{
?>    			
                <div class="block">
					<div class="head bg-default bg-light-rtl">
						<h2><?php echo $form_header;?> </h2>
						<div class="pull-right">
						<a href="<?php echo $this->ERPfunction->action_link('Assets','index');?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
						</div>
					</div>
					<!--
                    <div class="header">
                        <h2><u>Make Filter & Sort as per your Requirement</u></h2>
                    </div> -->
					<?php echo $this->Form->Create('form1',['id'=>'eq_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
					

                    <div class="content controls">
						<div class="form-row">
							<div class="col-md-2 text-right">Date From</div>
							<div class="col-md-4"><input name="date_from" class="datepick form-control"></div>
							<div class="col-md-2 text-right">To</div>
							<div class="col-md-4"><input name="date_to" class="datepick form-control"></div>
						</div>						
						<div class="form-row">
							<div class="col-md-2 text-right">Asset Name</div>
							<div class="col-md-4">
							<?php
								echo $this->Form->select("asset_name",$asset_list,["empty"=>["All"=>"All"],"class"=>"select2","style"=>"width:100%","multiple"=>"multiple"]);
							?>
							</div>
							<div class="col-md-2 text-right">Project Name</div>
							<div class="col-md-4">
							<select class="select2"  style="width: 100%;" name="project_id[]" id="project_id" multiple="multiple">
								<option value="all">All</Option>
								<?php 
									foreach($projects as $retrive_data)
									{?>
										<option value="<?php echo $retrive_data['project_id'];?>" <?php 
											if(isset($update_inward)){
												if($update_inward['project_id'] == $retrive_data['project_id'])
												{
													echo 'selected="selected"';
												}
			
											}?> >
											<?php echo $retrive_data['project_name']; ?> </option>
										<?php										
									}
								?>
								</select>
							</div>
						</div>
						
						<div class="form-row">
							<div class="col-md-2 text-right">Ownership</div>
							<div class="col-md-4">
								<select class="select2"  style="width: 100%;" name="ownership[]" multiple="multiple">
									<option value="rent">On Rent</Option>
									<option value="owned">owned</Option>
									<option value="all">All</Option>
								</select>
							</div>
							<div class="col-md-2 text-right">Project Code</div>
							<div class="col-md-4"><input name="project_code" class="form-control"></div>
						</div>
						
						<div class="form-row">
							<div class="col-md-2 text-right">Asset ID</div>
							<div class="col-md-4"><input name="asset_id" class="form-control"></div>
							<div class="col-md-2 text-right">E.L. No.</div>
							<div class="col-md-4"><input name="elno" class="form-control"></div>
						</div>
						
						<div class="form-row">
							<div class="col-md-2 text-right">Vehicle's No.</div>
							<div class="col-md-4"><input name="vehicle_no" class="form-control"></div>
							<div class="col-md-2 text-right">Driver Name.</div>
							<div class="col-md-4"><input name="driver_name" class="form-control"></div>
						</div>
						
						<div class="form-row">
							<div class="col-md-2 text-right">Approved By</div>
							<div class="col-md-4"><input name="approve_by" class="form-control"></div>
						</div>
						
						<div class="form-row">
							<div class="col-md-2 col-md-offset-2 text-left">
								<button type="submit" name="search" value="Search" class="btn btn-primary">Search</button>
							</div>
						</div>
						
					</div>
					<?php echo $this->Form->end();?>
					
				<div class="content list custom-btn-clean">
				<table id="asset_list"  class="dataTables_wrapper table table-striped table-hover">
				<thead>
					<tr>
						<th>Date</th>
						<th>E.L No.</th>
						<th>Asset Name</th>
						<th>Type of Ownership</th>
						<th>Vehicle's No.</th>						
						<th>Driver's Name</th>						
						<th>Usage</th>
						<th>Unit of Usage</th>
						<th>Approved By</th>
						<?php
						if($this->ERPfunction->retrive_accessrights($role,'editeqrecord')==1)
						{
						?>
						<th>Edit</th>
						<?php
						}
						 if($this->ERPfunction->retrive_accessrights($role,'equipmentlogrecord')==1)
						{
						?>
						<th>View</th>
						<?php
						}
						?>
					</tr>
				</thead>
				<tbody>
				<?php 
				if(!empty($search_data))
				{
					$rows = array();
					$rows[] = array("Date","E.L No.","Asset Name","Type of Ownership","Vehicle's No.","Driver's Name","Usage","Unit of Usage","Approved By");
					foreach($search_data as $data)
					{
						$export = array();
						if(is_numeric($data['asset_name']))
						{
							$asset = $this->ERPfunction->get_asset_name($data['asset_name']);
						}else{
							$asset = $data['asset_name'];
						}
						echo "
						<tr>
							<td>".($export[] = $this->ERPfunction->get_date($data['el_date']))."</td>
							<td>".($export[] = $data['elno'])."</td>
							<td>".($export[] = $asset)."</td>
							<td>".($export[] = $data['ownership'])."</td>
							<td>".($export[] = $data['vehicle_no'])."</td>
							<td>".($export[] = $data['driver_name'])."</td>
							<td>".($export[] = $data['el_usage'])."</td>
							<td>".($export[] = $data['unit_usage'])."</td>
							<td>".($export[] = $data['approved_by'])."</td>";
							if($this->ERPfunction->retrive_accessrights($role,'editeqrecord')==1)
							{
								echo "<td><a href='{$this->request->base}/Assets/editeqrecord/{$data['id']}' class='btn btn-primary btn-clean'><i class='icon-pencil'></i> Edit</a></td>";
							}
							if($this->ERPfunction->retrive_accessrights($role,'equipmentlogrecord')==1)
							{
								echo "<td><a href='{$this->request->base}/Assets/viewequipmentlog/{$data['id']}' class='btn btn-primary btn-clean'><i class='icon-eye-open'></i> View</a></td> ";
							}
							echo "</tr>";
						$rows[] = $export;
					}
				}
				?>
				</tbody>
				</table>
				<div class="content">
					<div class="col-md-2"><a href="javascript:void(0);" class="btn btn-success" id="fullscreen" url='<?php echo $_SERVER['REQUEST_URI']; ?>' onClick="DoFullScreen()" >View Full Screen</a></div>
					<?php if(!empty($search_data)){?>
					<div class="col-md-2">
						<form method="post">
							<input type="hidden" name="rows" value='<?php echo base64_encode(serialize($rows));?>'>
							<input type="submit" class="btn btn-success" value="Export To Excel" name="export_csv">
						</form>
					</div>
					<div class="col-md-2">
						<form method="post">
							<input type="hidden" name="rows" value='<?php echo base64_encode(serialize($rows));?>'>
							<input type="submit" class="btn btn-success" value="Export To Excel" name="export_pdf">
						</form>
					</div>
					<?php } ?>
				</div>
				
				</div>				
				
		</div>
<?php } ?>
</div>
						