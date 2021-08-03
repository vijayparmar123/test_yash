<?php
//$this->extend('/Common/menu')
use Cake\Routing\Router;
?>
<script type="text/javascript">
        // function background() {
            // var table = document.getElementById('user_list'); 		
			// var rows = table.getElementsByTagName("tr");
			// for(i = 0; i < rows.length; i++)
			// {
				// if (rows[i].className == 'show_warning') 
				// {
						// table.appendChild(rows[i]);
				// }
			// }
        // }
        // window.onload = background;
        </script>
<script type="text/javascript">
jQuery(document).ready(function() {
			
			
			
			jQuery('body').on('click','.viewmodal',function(){
				id = jQuery(this).attr('data_id');
				role = jQuery(this).attr('role');
				jQuery('#modal-view').html();
			    urlstring ="<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'billpaymentdetail'));?>";
				var curr_data = {id:id,role:role};
				
					jQuery.ajax({
						type:"POST",
						url:urlstring,
						data:curr_data,
						async:false,
						success: function(response){                    
							jQuery('.modal-content').html(response);					
						},
						beforeSend:function(){
									jQuery('#modal-view').html('<center><img src=../images/4.gif width=120px><div><h3>Loading...</h3></div></center>');
								},
						error: function(e) {
								console.log(e.responseText);
								 }
					});	
									
			});
			
			jQuery('.datepick').datepicker({
		dateFormat: "dd-mm-yy",
		  changeMonth: true,
	        changeYear: true,
	        yearRange:'-65:+0',
	        onChangeMonthYear: function(year, month, inst) {
	            jQuery(this).val(month + "-" + year);
	        }                    
    });
		} );
</script>

<div class="col-md-10" >
<?php 
if(!$is_capable)
	{
		$this->ERPfunction->access_deniedmsg();
	}
else
{
?>

<div class="row">
	<div class="col-md-12">
		<div class="block">
			<div class="head bg-default bg-light-rtl">
				<h2>Bill Records</h2>
				<div class="pull-right">
				<a href="<?php echo $this->ERPfunction->action_link('Accounts','index');?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
				</div>
			</div>
			
				<?php echo $this->Form->Create('form1',['id'=>'eq_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
					

                    <div class="content controls">		
						<div class="form-row">
							<div class="col-md-2 text-right">Inward Date Form</div>
							<div class="col-md-4"><input name="date_from" class="datepick" /></div>
							<div class="col-md-2 text-right">Inward Date To</div>
							<div class="col-md-4"><input name="date_to" class="datepick" /></div>
						</div>
						<div class="form-row">
							<div class="col-md-2 text-right">Bill Date Form</div>
							<div class="col-md-4"><input name="bill_date_from" class="datepick" /></div>
							<div class="col-md-2 text-right">Bill Date To</div>
							<div class="col-md-4"><input name="bill_date_to" class="datepick" /></div>
						</div>
						
						<div class="form-row">
							<div class="col-md-2 text-right">Party's Name</div>
							<div class="col-md-4">
							<select class="select2" style="width: 100%;" name="party_id[]" id="party_id" >
							<option value="All">-- Select Party --</option>
							<?php
							if($vendor_info){
                            				foreach($vendor_info as $vendor_row){
                            					?>
													<option value="<?php echo $vendor_row['user_id']; ?>" dataid="<?php echo $vendor_row['vendor_id'];?>" <?php 
																if(isset($update_inward)){
																	if($update_inward['party_name'] == $vendor_row['user_id']){
																		echo 'selected="selected"';
																	}
																}

													?> ><?php echo $vendor_row['vendor_name'];?></option>

                            					<?php
                            				}
                            			}?>
										</select>
							</div>
							<div class="col-md-2 text-right">Project Name</div>
							<div class="col-md-4">
							<select class="select2" style="width: 100%;" name="project_id[]" id="project_id" multiple="multiple">
								<option value="all">All</Option>
								<?php 
									foreach($projects as $retrive_data)
									{ ?>
										<option value="<?php echo $retrive_data['project_id'];?>" <?php 
											if(isset($update_inward)){
												if($update_inward['project_id'] == $retrive_data['project_id'])
												{
													echo 'selected="selected"';
												}
			
											}?> >
											<?php echo $retrive_data['project_name']; ?> </option>
										<?php										
									} ?>
								</select>
							</div>
						</div>
						<div class="form-row">
							<div class="col-md-2 text-right">Type of Bill</div>
                            <div class="col-md-4">
								<select name="bill_type[]" class="select2" style="width:100%;" multiple="multiple">

									<?php 
										$billtype=array(
															'All'=>'All',
															'Material/Item'=>'Material/Item',
															'Labour'=>'Labour',
															'Labour with Material/Item'=>'Labour with Material/Item',
															'Asset Maintenance'=>'Asset Maintenance',
															'Asset Purchase'=>'Asset Purchase',
															'Transport'=>'Transport',
															'Other'=>'Other',
															
														);

									
									foreach($billtype as $bill_key => $bill_value){
										?>
									<option value="<?php echo $bill_key ;?>" <?php
														if(isset($update_inward)){
															if($update_inward['bill_type']== $bill_key){
																echo 'selected="selected"';
															}
														}

											 ?> ><?php echo $bill_value; ?></option>
									<?php
								}
								?>
								</select>
							</div>
							
								<div class="col-md-2 text-right">Payment Method</div>
							<div class="col-md-4">
							<select class="select2"   style="width: 100%;" name="payment_mod[]" id="payment_type" >
								<option value="All">-- Select Payment --</Option>
								<option value="cheque">Cheque</Option>
								<option value="cash">Cash</Option>
								
								</select>
							</div>
						</div>
						<div class="form-row">
							<div class="col-md-2 text-right">Inward Bill No.</div>
							<div class="col-md-4"><input name="bill_no" class="" /></div>
							<div class="col-md-2 text-right">Invoice No.</div>
							<div class="col-md-4"><input name="invoice_no" class="form-control"></div>
						</div>
						
						<div class="form-row">
							<div class="col-md-2 text-right">P.O./W.O. No.</div>
							<div class="col-md-4"><input name="powono" class="form-control"></div>
							
							<div class="col-md-2 text-right">Pay Status</div>
							<div class="col-md-4">
							<select class="select2"   style="width: 100%;" name="pay_status[]" id="payment_type" >
								<option value="All">-- Select Status --</Option>
								<option value="completed">Completed</Option>
								<option value="accept">Accept</Option>
								<option value="pending">Pending</Option>
								</select>
							</div>
						</div>
						
						
						<div class="form-row">
							<div class="col-md-2 col-md-offset-2">
								<button type="submit" name="search" value="Search" class="btn btn-primary">Search</button>
							</div>
						</div>
					</div>
					<?php echo $this->Form->end();?>	
			
			
			
		<div class="content list custom-btn-clean">
		<script>
		jQuery(document).ready(function() {
		jQuery('#user_list').DataTable({responsive: true,"ordering": false,});
		} );
</script>
 
			<table id="user_list"  class="dataTables_wrapper table table-striped table-hover">
				<thead>
					<tr>						
						<th>Project Name</th>
						<th>Inward Bill No</th>
						<th>Inward Date</th>
						<th>Party's Name</th>
						<th>Invoice No</th>						
						<th>Bill Date</th>
						<th>Bill Amount</th>
						<th>Credit Period</th>
						<th>Diff(+/-)</th>
						<th>Pay Status</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$red = array();
					$green = array();
					$wight = array();
					
					foreach($inward_bill_info as $inward_bill_row)
					{
							$date =  date('Y-m-d');							
							$date1 = new DateTime($inward_bill_row["bill_date"]->format("Y-m-d"));
							$date2 = new DateTime($date);
							$diff = $date2->diff($date1)->format("%r%a");
							$check = $diff + $inward_bill_row["credit_period"];
							$warning = false;
							if($check < 0 )
							{
								$warning = true;								
							}
							
							
							$csv = array();
							$pay_status = "";
							if($inward_bill_row["status_inward"] == "pending")
							{
								$pay_status = "Only Inward";
							}
							else if($inward_bill_row["status_inward"] == "accept")
							{
								$pay_status = "Accepted";
							}else if($inward_bill_row["status_inward"] == "completed" ){
								$pay_status = "Paid";
							}	
							
							if($check < 0 && $pay_status == "Paid")
							{
								$green[] = $inward_bill_row;
							}
							else if($pay_status == "Paid")
							{
								$green[] = $inward_bill_row;
							}
							else if($check < 0)
							{
								$red[] = $inward_bill_row;
							}
							else
							{
								$wight[] = $inward_bill_row;
							}
							//sorting
							$price = array();
							foreach ($green as $key => $row)
							{
								$date =  date('Y-m-d');							
								$date1 = new DateTime($row["bill_date"]->format("Y-m-d"));
								$date2 = new DateTime($date);
								$diff = $date2->diff($date1)->format("%r%a");
								$check = $diff + $row["credit_period"];
								$row['diff'] = $check;
								$price[$key] = $row['diff'];
							}
							array_multisort($price, SORT_ASC, $green);
							
							$price1 = array();
							foreach ($red as $key => $row)
							{
								$date =  date('Y-m-d');							
								$date1 = new DateTime($row["bill_date"]->format("Y-m-d"));
								$date2 = new DateTime($date);
								$diff = $date2->diff($date1)->format("%r%a");
								$check = $diff + $row["credit_period"];
								$row['diff'] = $check;
								$price1[$key] = $row['diff'];
							}
							array_multisort($price1, SORT_ASC, $red);
							
							$price2 = array();
							foreach ($wight as $key => $row)
							{
								$date =  date('Y-m-d');							
								$date1 = new DateTime($row["bill_date"]->format("Y-m-d"));
								$date2 = new DateTime($date);
								$diff = $date2->diff($date1)->format("%r%a");
								$check = $diff + $row["credit_period"];
								$row['diff'] = $check;
								$price2[$key] = $row['diff'];
							}
							array_multisort($price2, SORT_ASC, $wight);							
					}
					
					
					$merge = array_merge($red, $wight);
					
					$inward_bill_info = array_merge($merge, $green);
					
					$rows = array();
					$rows[] = array("Project Code","Inward Bill No","Inward Date","Party's Name","Invoice No.","Bill Date","Bill Amount","Credit Period","Remaining Days","Pay Status");
					
						$i = 1;
						
						foreach($inward_bill_info as $inward_bill_row)
						{
							$date =  date('Y-m-d');							
							$date1 = new DateTime($inward_bill_row["bill_date"]->format("Y-m-d"));
							$date2 = new DateTime($date);
							$diff = $date2->diff($date1)->format("%r%a");
							$check = $diff + $inward_bill_row["credit_period"];
							$warning = false;
							if($check < 0 )
							{
								$warning = true;								
							}
							
							
							$csv = array();
							$pay_status = "";
							if($inward_bill_row["status_inward"] == "pending")
							{
								$pay_status = "Only Inward";
							}
							else if($inward_bill_row["status_inward"] == "accept")
							{
								$pay_status = "Accepted";
							}else if($inward_bill_row["status_inward"] == "completed" ){
								$pay_status = "Paid";
							}						
							
							
						?>
							<tr id="row_<?php echo $i; ?>" <?php echo ($check < 0 && $pay_status == "Paid")?"class='green'":"";?> <?php echo $pay_status == "Paid"?"class='green'":"";?> <?php echo ($warning)?"class='show_warning'":"";?> >
								<td><?php echo ($csv[] = $this->ERPfunction->get_projectname($inward_bill_row['project_id'])); ?></td>
								<td><?php echo ($csv[] = $inward_bill_row['inward_bill_no']) ;?></td>
								<td><?php echo ($csv[] = date('d-m-Y',strtotime($inward_bill_row['date']))) ;?></td>
								<!-- <td><?php //echo ($csv[] = $this->ERPfunction->get_vendor_name($inward_bill_row["party_name"]));?></td> -->
								<td><?php 
									// echo ($csv[] = ($inward_bill_row["party_name"] == "0" )?$this->ERPfunction->get_agency_name($inward_bill_row["party_name"]):$this->ERPfunction->get_vendor_name($inward_bill_row["party_name"]));
									
									 $is_agencry = strpos($inward_bill_row["party_name"],"NEC");
									
									if(($inward_bill_row["party_name"] == "0" || $is_agencry == 1 ) && $inward_bill_row["party_type"] == "old" )
									{
										echo $csv[] =$this->ERPfunction->get_agency_name_by_code($inward_bill_row["party_name"]);
									}
									else if($inward_bill_row["party_type"] == "new")
									{
										echo $csv[] = $inward_bill_row["new_party_name"];
									}
									else
									{
										echo $csv[] = $this->ERPfunction->get_vendor_name($inward_bill_row["party_name"]);										
									}
									?></td>
									<td><?php echo ($csv[] = $inward_bill_row['invoice_no']) ;?></td>
									<td><?php echo ($csv[] = $inward_bill_row["bill_date"]->format("d-m-Y"));?></td>
									<td><?php echo ($csv[] = $inward_bill_row["total_amt"]);?></td>
									<td><?php echo ($csv[] = $inward_bill_row["credit_period"]);?></td>
									<td><?php echo ($csv[] = $diff + $inward_bill_row["credit_period"]);?></td>
									<td><?php echo ($csv[] = $pay_status) ;?></td>
									<td>
								<?php 
								// echo $this->Html->link("<i class='icon-pencil'></i> Edit",array('action' => 'addinwardbill', $inward_bill_row['inward_bill_id']),
								// array('escape'=>false,'class'=>'btn btn-primary btn-clean'));
								echo $this->Html->link("<i class='icon-eye-open'></i> View",array('action' => 'viewbill', $inward_bill_row['inward_bill_id']),
								array('class'=>'btn btn-primary btn-clean','target'=>'blank','escape'=>false));
								if($role=="erphead")
								{
									echo ' ';
									echo $this->Html->link("<i class='icon-trash'></i> Delete",array('action' => 'billdisapprove', $inward_bill_row['inward_bill_id']),
									array('escape'=>false,'class'=>'btn  btn-danger btn-clean',
									'confirm' => 'Are you sure you wish to disapprove this Record?'));
								}
								if($pay_status == "Paid")
								{
									echo '<button type="button"  id="transfereasset" data-type="transfereasset" data-toggle="modal" 
								data-target="#load_modal" class="btn btn-info viewmodal btn-clean"  data_id="'.$inward_bill_row['inward_bill_id'].'" role="'.$role.'"> <i class="icon-eye-open"></i>View Payment Details</button>';
								}
								?>
								</td>
							</tr>
						<?php
						$rows[] = $csv;	
						$i++;
						}
					?>
				</tbody>
			</table>
			<div class="content">
			<div class="col-md-2"><a href="javascript:void(0);" class="btn btn-success" id="fullscreen" url='<?php echo $_SERVER['REQUEST_URI']; ?>' onClick="DoFullScreen()" >View Full Screen</a></div>
			<div class="col-md-2">
			<form method="post">
				<input type="hidden" name="rows" value='<?php echo base64_encode(serialize($rows));?>'>
				<input type="submit" class="btn btn-success" value="Export To Excel" name="export_csv">
			</form>
			</div>
			<div class="col-md-2">
			<form method="post">
				<input type="hidden" name="rows" value='<?php echo base64_encode(serialize($rows));?>'>
				<input type="submit" class="btn btn-success" value="Export To PDF" name="export_pdf">
			</form>
			</div>
			</div>
		</div>
		</div>
	</div>
</div>
<?php } ?>
</div>
<div class="modal fade " id="load_modal" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content"></div>
    </div>
</div>