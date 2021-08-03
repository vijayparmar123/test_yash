<?php
use Cake\Routing\Router;
?>
<div class="col-md-10" >
<?php 
if(!$is_capable)
	{
		$this->ERPfunction->access_deniedmsg();
	}
else
{
?>
 
<!--<div class="row">-->
	<div class="col-md-12">
		<div class="block">			
		<div class="head bg-default bg-light-rtl">
			<h2>G. R. N. Alert</h2>
			<div class="pull-right">
			<a href="<?php echo $this->ERPfunction->action_link($back_url,'index');?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
			</div>
		</div>
		
		<?php echo $this->Form->Create('form1',['id'=>'eq_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
					

                    <div class="content controls">		
						<div class="form-row">
							<div class="col-md-2 text-right" id="pr_left">GRN Date From</div>
							<div class="col-md-4"><input name="date_from" class="datepick" /></div>
							<div class="col-md-2 text-right" id="pr_left">GRN Date To</div>
							<div class="col-md-4"><input name="date_to" class="datepick" /></div>
						</div>
						
						
						<div class="form-row">
							<div class="col-md-2 text-right" id="pr_left">Party's Name</div>
							<div class="col-md-4">
							<select class="select2" style="width: 100%;" name="party_id" id="party_id" >
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
							<div class="col-md-2 text-right" id="pr_left">Project Name</div>
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
							<div class="col-md-2 text-right" id="pr_left">Material Name</div>
                            <div class="col-md-4">
								<select class="select2 material_id" style="width: 100%;" name="material[]" id="material">
												<option value="All">--Select Material--</Option>
												<?php 
													foreach($meterial_list as $retrive_data)
													{
														echo '<option value="'.$retrive_data['material_id'].'">'.
														$retrive_data['material_title'].'</option>';
													}
												?>
											</select>
							</div>
							
								<div class="col-md-2 text-right" id="pr_left">Payment Method</div>
							<div class="col-md-4">
							<select class="select2"   style="width: 100%;" name="payment_mod" id="payment_type" >
								<option value="All">-- Select Payment --</Option>
								<option value="cheque">Cheque</Option>
								<option value="cash">Cash</Option>
								
								</select>
							</div>
						</div>
						<div class="form-row">
							<div class="col-md-2 text-right" id="pr_left">GRN No</div>
							<div class="col-md-4"><input name="grn_no" class="" /></div>
							<div class="col-md-2 text-right" id="pr_left">Challan No</div>
							<div class="col-md-4"><input name="challan_no" class="form-control"></div>
						</div>
						
						
						
						
						<div class="form-row">
							<div class="col-md-2 col-md-offset-2">
								<button type="submit" name="search" value="Search" class="btn btn-primary">Search</button>
							</div>
						</div>
					</div>
					<?php echo $this->Form->end();?>
<input type="hidden" id="f_date_from" value="<?php echo isset($_POST["date_from"]) ? $_POST["date_from"] : "";?>">
<input type="hidden" id="f_date_to" value="<?php echo isset($_POST["date_to"]) ? $_POST["date_to"] : "";?>">
<input type="hidden" id="f_party_id" value="<?php echo isset($_POST["party_id"]) ? $_POST["party_id"] : "";?>">
<input type="hidden" id="f_pro_id" value="<?php echo isset($_POST["project_id"]) ? implode(",",$_POST["project_id"]) : "";?>">
<input type="hidden" id="f_material_id" value="<?php echo isset($_POST["material"]) ? implode(",",$_POST["material"]) : "";?>">
<input type="hidden" id="f_payment_mod" value="<?php echo isset($_POST["payment_mod"]) ? $_POST["payment_mod"] : "";?>">
<input type="hidden" id="f_grn_no" value="<?php echo isset($_POST["grn_no"]) ? $_POST["grn_no"] : "";?>">
<input type="hidden" id="f_challan_no" value="<?php echo isset($_POST["challan_no"]) ? $_POST["challan_no"] : "";?>">
		<div class="content list custom-btn-clean">
<script>
var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
	jQuery(document).ready(function() {
		jQuery('.datepick').datepicker({
			dateFormat: "dd-mm-yy",
			changeMonth: true,
			changeYear: true,
			yearRange:'-65:+0',
			onChangeMonthYear: function(year, month, inst) {
				jQuery(this).val(month + "-" + year);
			}                    
		});
		
		// jQuery('#grn_list').DataTable({responsive: true});
		jQuery("body").on("change", ".approve", function(event){				
			var grn_id = jQuery(this).val();
			var project_id = jQuery(this).attr('data-project_id');
			if(confirm('Are you Sure approve this GRN?'))
			{
				if(confirm('Are you Sure approve this GRN?'))
				{
					var curr_data = {	 						 					
										grn_id : grn_id,	 					
										project_id : project_id,	 					
									};	 				
					jQuery.ajax({
						headers: {
					'X-CSRF-Token': csrfToken
				},
                type:"POST",
						url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'accountapprovegrn'));?>",
						data:curr_data,
						async:false,
						success: function(response){					
							 location.reload();
							return false;
						},
						error: function (e) {
							 alert('Error');
						}
					});
				} 
			}
			else
			{
				 jQuery(this).removeAttr('checked');
				 jQuery(this).parent().removeClass('checked');
			}
		});	
		/* Server side datatable listing */	
		var f_date_from  = jQuery("#f_date_from").val();
		var f_date_to  = jQuery("#f_date_to").val();
		var f_party_id  = jQuery("#f_party_id").val();
		var f_pro_id  = jQuery("#f_pro_id").val();
		var f_material_id  = jQuery("#f_material_id").val();
		var f_payment_mod  = jQuery("#f_payment_mod").val();
		var f_grn_no  = jQuery("#f_grn_no").val();
		var f_challan_no  = jQuery("#f_challan_no").val();

		var selected = [];
		var table = jQuery('#grn_list').DataTable({
			"pageLength": 10,
			"order": [[ 1, "desc" ]],
			columnDefs: [ 
						{
							searchable: false,
							targets:   8,
						},
						{
							searchable: false,
							targets:   9,
						}					
						],
				
			"columns": [
					{ "visible": true },
					{ "visible": true },
					{ "visible": true },
					{ "visible": true },
					{ "visible": true },
					{ "visible": true },
					{ "visible": true },
					{ "visible": true },
					{ "visible": true },
					{ "visible": true },
							  ],
			"responsive" : true,
			"processing": true,
			"serverSide": true,
			//"ajax": "../Ajaxfunction/billrecordsdata",
			"ajax": {
					"url": "../Ajaxfunction/accountsgrndata",
					"data": function ( d ) {
												d.myKey = "myValue";
												d.date_from = f_date_from;
												d.date_to = f_date_to;
												d.party_id = f_party_id;
												d.pro_id = f_pro_id;
												d.material_id = f_material_id;
												d.payment_mod = f_payment_mod;
												d.grn_no = f_grn_no;
												d.challan_no = f_challan_no;
											}
					},
			"rowCallback": function( row, data ) {
									
									if ( jQuery.inArray(data.DT_RowId, selected) !== -1 ) {
										jQuery(row).addClass('selected');
									}
							},
			});
			/* Server side datatable listing */
		} );
</script>
		
			<table id="grn_list"  class="dataTables_wrapper table table-striped table-hover">
				<thead>
					<tr>
						<th>G.R.N No</th>
						<th>Date</th>												
						<th>Vendor Name</th>
						<th>Challan No.</th>
						<th>Material Name</th>
						<th>Make/ Source</th>
						<th>Actual Qty.</th>
						<th>Unit</th>
						<th>Action</th>
						<!--<?php
						if($this->ERPfunction->retrive_accessrights($role,'approvegrnalert')==1)
						{ ?>-->
						<th>Approve</th>
						<!--<?php } ?>-->
					</tr>
				</thead>
				<!--<tbody>
					<?php
						$rows = array();
						$rows[] = array("G.R.N No","Date","Vendor Name","Challan No.","Material Name","Make/ Source","Actual Qty.","Unit");
					
						$i = 1;
						foreach($grn_list as $retrive_data)
						{
						$retrive_data = array_merge($retrive_data,$retrive_data["erp_inventory_grn_detail"]);
						$csv = array();
						
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
								<td><?php echo ($csv[] = $retrive_data['grn_no']);?></td>								
								<td class="col-md-1"><?php echo ($csv[] = $this->ERPfunction->get_date($retrive_data['grn_date']));?></td>								
								<td><?php echo ($csv[] = $this->ERPfunction->get_vendor_name($retrive_data['vendor_userid']));?></td>														
								<td class="col-md-1"><?php echo ($csv[] = $retrive_data['challan_no']);?></td>								
								<td><?php echo ($csv[] = $mt);?></td>
								<td><?php echo ($csv[] = $brnd);?></td>
								<td><?php echo ($csv[] = $retrive_data['actual_qty']);?></td>
								<td><?php echo ($csv[] = $static_unit);?></td>
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
								<td>
								<?php 
								echo $this->Html->link("<i class='icon-eye-open'></i>View",array('controller'=>'Accounts','action' => 'accountpreviewgrn', $retrive_data['grn_id']),
								array('class'=>'btn btn-clean btn-primary','target'=>'_blank',"escape"=>false));
								
								?>
								</td>
								<?php
								if($this->ERPfunction->retrive_accessrights($role,'approvegrnalert')==1)
								{ ?>
								<td>
								<div class="checkbox">
										<label><input type="checkbox" class="approve" 
											data-project_id="<?php echo $retrive_data['project_id'];?>"
										value="<?php echo $retrive_data['grn_id'];?>" name="Approve"/> </label>
									</div>
								</td>
								<?php } ?>	
								</tr>
						<?php
						$rows[] = $csv;
						$i++;
						}
					?>
				</tbody>-->
			</table>
			<div class="content">
			<div class="col-md-2"><a href="javascript:void(0);" class="btn btn-success" id="fullscreen" url='<?php echo $_SERVER['REQUEST_URI']; ?>' onClick="DoFullScreen()" >View Full Screen</a></div>
			<div class="col-md-2">
			<?php echo $this->Form->Create('form1',['id'=>'export','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
				<!--<input type="hidden" name="rows" value='<?php echo base64_encode(serialize($rows));?>'>-->
				<input type="hidden" name="e_date_from" value="<?php echo isset($_POST["date_from"]) ? $_POST["date_from"] : "";?>">
				<input type="hidden" name="e_date_to" value="<?php echo isset($_POST["date_to"]) ? $_POST["date_to"] : "";?>">
				<input type="hidden" name="e_party_id" value="<?php echo isset($_POST["party_id"]) ? $_POST["party_id"] : "";?>">
				<input type="hidden" name="e_pro_id" value="<?php echo isset($_POST["project_id"]) ? implode(",",$_POST["project_id"]) : "";?>">
				<input type="hidden" name="e_material_id" value="<?php echo isset($_POST["material"]) ? implode(",",$_POST["material"]) : "";?>">
				<input type="hidden" name="e_payment_mod" value="<?php echo isset($_POST["payment_mod"]) ? $_POST["payment_mod"] : "";?>">
				<input type="hidden" name="e_grn_no" value="<?php echo isset($_POST["grn_no"]) ? $_POST["grn_no"] : "";?>">
				<input type="hidden" name="e_challan_no" value="<?php echo isset($_POST["challan_no"]) ? $_POST["challan_no"] : "";?>">
				<input type="submit" class="btn btn-success" value="Export To Excel" name="export_csv">
			<?php 
					echo $this->Form->end();
				?>
			</div>
			<div class="col-md-2">
			<?php echo $this->Form->Create('form1',['id'=>'export','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
				<!--<input type="hidden" name="rows" value='<?php echo base64_encode(serialize($rows));?>'>-->
				<input type="hidden" name="e_date_from" value="<?php echo isset($_POST["date_from"]) ? $_POST["date_from"] : "";?>">
				<input type="hidden" name="e_date_to" value="<?php echo isset($_POST["date_to"]) ? $_POST["date_to"] : "";?>">
				<input type="hidden" name="e_party_id" value="<?php echo isset($_POST["party_id"]) ? $_POST["party_id"] : "";?>">
				<input type="hidden" name="e_pro_id" value="<?php echo isset($_POST["project_id"]) ? implode(",",$_POST["project_id"]) : "";?>">
				<input type="hidden" name="e_material_id" value="<?php echo isset($_POST["material"]) ? implode(",",$_POST["material"]) : "";?>">
				<input type="hidden" name="e_payment_mod" value="<?php echo isset($_POST["payment_mod"]) ? $_POST["payment_mod"] : "";?>">
				<input type="hidden" name="e_grn_no" value="<?php echo isset($_POST["grn_no"]) ? $_POST["grn_no"] : "";?>">
				<input type="hidden" name="e_challan_no" value="<?php echo isset($_POST["challan_no"]) ? $_POST["challan_no"] : "";?>">
				<input type="submit" class="btn btn-success" value="Export To PDF" name="export_pdf">
			<?php 
					echo $this->Form->end();
				?>
			</div>
			</div>
		</div>
		</div>
	</div>
<!--</div>-->
<?php }?>
</div>