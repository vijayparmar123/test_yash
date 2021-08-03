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
							<select class="select2"   style="width: 100%;" name="project_id[]" id="project_id" multiple="multiple">
								<option value=''>All</Option>
								<?php 
									foreach($projects as $retrive_data)
									{?>
										<option value="<?php echo $retrive_data['project_id'];?>" <?php 
											if(isset($data)){
												if($data['project_id'] == $retrive_data['project_id'])
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
							<div class="col-md-2 text-right">Agency Name.</div>
							<div class="col-md-4">
							<?php
								echo $this->Form->select("agency_name",$agency_list,["empty"=>["All"=>"All"],"class"=>"select2","style"=>"width:100%","multiple"=>"multiple"]);
							?>
							</div>
							<div class="col-md-2 text-right">Concrete Grade</div>
							<div class="col-md-4">
								<?php
									$grade = ["All"=>"All","M7.5"=>"M7.5","M10"=>"M10","M15"=>"M15","M20"=>"M20","M25"=>"M25","M30"=>"M30","M35"=>"M35","M40"=>"M40"];
									echo $this->Form->select("concrete_grade",$grade,["class"=>"select2","style"=>"width:100%","multiple"=>"multiple"]);
								?>
							</div>
						</div>
						
						<div class="form-row">
							<div class="col-md-2 text-right">Asset ID</div>
							<div class="col-md-4"><input name="asset_id" class="form-control"></div>
							<div class="col-md-2 text-right">Project Code</div>
							<div class="col-md-4"><input name="project_code" class="form-control"></div>
						</div>
						
						<div class="form-row">
							<div class="col-md-2 text-right">Order By</div>
							<div class="col-md-4"><input name="order_by" class="form-control"></div>
							<div class="col-md-2 text-right">Usage</div>
							<div class="col-md-4"><input name="usage" class="form-control"></div>
						</div>
						
						<div class="form-row">
							<div class="col-md-2 col-md-offset-2">
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
						<th>RMC. L. No.</th>
						<th>Asset Name</th>
						<th>Agency Name</th>
						<th>Order By</th>						
						<th>Concrete Grade</th>						
						<th>Usage</th>						
						<th>Quantity<br>Supplied<br>(Cum)</th>	
						<?php if($this->ERPfunction->retrive_accessrights($role,'viewrmcissueslip')==1 || $this->ERPfunction->retrive_accessrights($role,'unapproveermc')==1 )
						{ ?>
						<th>View</th>
						<?php } ?>
						
					</tr>
				</thead>
				<tbody>
				<?php 
				if(!empty($search_data))
				{
					foreach($search_data as $data)
					{
						echo "
						<tr>
							<td>{$this->ERPfunction->get_date($data['rmc_date'])}</td>
							<td>{$data['isno']}</td>
							<td>{$this->ERPfunction->get_asset_name($data['asset_name'])}</td>
							<td>{$this->ERPfunction->get_agency_name($data['agency_name'])}</td>
							<td>{$data['order_by']}</td>
							<td>{$data['concrete_grade']}</td>
							<td>{$data['rmc_usage']}</td>
							<td>";
							$qty = json_decode($data['quantity']);
							if(!empty($qty))
							{
								$sum = 0;
								foreach( $qty as $quantity)
								{
									$sum = $sum + $quantity;
								}
								echo $sum;
							}
					    echo "</td>";
							// $times = json_decode($data['time_in']);
							// if(!empty($times))
							// {
								// foreach( $times as $time)
								// {
									// echo "{$time}<br>";
								// }
							// }
						// echo "</td>
							// <td>";
							// $out_times = json_decode($data['time_out']);
							// if(!empty($out_times))
							// {
								// foreach( $out_times as $time)
								// {
									// echo "{$time}<br>";
								// }
							// }
						// echo "</td>";
						/* echo "<td><a href='{$this->request->base}/Assets/editrmcrecord/{$data['id']}' class='btn btn-primary btn-clean'><i class='icon-pencil'></i> Edit</a></td>"; */
							if($this->ERPfunction->retrive_accessrights($role,'viewrmcissueslip')==1 || $this->ERPfunction->retrive_accessrights($role,'unapproveermc')==1 )
						{
							echo "<td>";
							if($this->ERPfunction->retrive_accessrights($role,'viewrmcissueslip')==1)
							{
							echo "<a href='{$this->request->base}/Assets/viewrmcissueslip/{$data['id']}' class='btn btn-warning btn-clean' target='_blank'><i class='icon-eye-open'></i> View</a>";
							}
							if($this->ERPfunction->retrive_accessrights($role,'unapproveermc')==1)
							{
							echo "<a href='{$this->request->base}/Assets/unapproveermc/{$data['id']}' onClick=\"javascript: return confirm('Are you sure,you wish to Unapprove');\" class='btn btn-danger btn-clean'><i class='icon-eye-open'></i>Remove</a>";
							}
							echo "</td>";
						}
						// echo "<td>";
						// $challan = json_decode($data["challan"]);						
						// if(!empty($challan))
						// {
							// $i=1;
							// foreach($challan as $file)
							// {
								// echo "<a href='{$this->request->base}/img/users_images/{$file}' download='file' target='_blank' class='btn btn-info btn-clean'><i class='icon-download-alt'></i>File {$i}</a>";
								// $i++;
							// }
						// }
						echo "</tr>";
					}
				}
				?>
				</tbody>
				</table>
								
				<div class="content">
					<div class="col-md-2">
					<button class="btn btn-success">View Full Screen</button>
					</div>
					<div class="col-md-2">
					<button class="btn btn-success">Export TO Excel</button>
					</div>
					<div class="col-md-2">
					<button class="btn btn-success">Export TO Pdf</button>
					</div>
				</div> 
				
				</div>				
				
		</div>
					
					
<?php } ?>				
					
				
				
			
</div>
						