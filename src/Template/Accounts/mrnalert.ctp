<?php
use Cake\Routing\Router;
use Cake\Network\Session\DatabaseSession;
$user_r=$this->request->session()->read('role');


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
<div class="row">
	<div class="col-md-12">
		<div class="block">			
			<div class="head bg-default bg-light-rtl">
				<h2>M.R.N Alert </h2>
				<div class="pull-right">
				<a href="<?php echo $this->ERPfunction->action_link($back_url,'index');?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
				</div>
			</div>
			
			<?php echo $this->Form->Create('form1',['id'=>'eq_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
					

                    <div class="content controls">		
						<div class="form-row">
							<div class="col-md-2 text-right">MRN Date From</div>
							<div class="col-md-4"><input name="date_from" class="datepick" /></div>
							<div class="col-md-2 text-right">MRN Date To</div>
							<div class="col-md-4"><input name="date_to" class="datepick" /></div>
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
							<div class="col-md-2 text-right">Material Name</div>
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
							
								<div class="col-md-2 text-right">MRN No</div>
							<div class="col-md-4"><input name="mrn_no" class="" /></div>
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
var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
		jQuery(document).ready(function() {
			jQuery('#mrn_list').DataTable({responsive: true});
			jQuery("body").on("change", ".approve", function(event){
				var mrn_id = jQuery(this).val();
				var data_role = jQuery(this).attr('data-role');
								
				if(confirm('Are you Sure approve this M.R.N.?'))
				{
					
				if(confirm('Are you Sure approve this M.R.N.?'))
				{
				var curr_data = {	 						 					
	 					mrn_id : mrn_id,
						data_role :data_role,
	 					};	 				
	 	 jQuery.ajax({
                headers: {
					'X-CSRF-Token': csrfToken
				},
                type:"POST",
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'accountapprovemrn'));?>",
                data:curr_data,
                async:false,
                success: function(response){
					
					 location.reload();
					return false;
                },
                error: function (e) {
                     alert('Error');
					 console.log(e.responseText);
                }
			});
			 }
			 else
			{
				 jQuery(this).removeAttr('checked');
				 jQuery(this).parent().removeClass('checked');
			}
			}
			else
			{
				 jQuery(this).removeAttr('checked');
				 jQuery(this).parent().removeClass('checked');
			}
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
			<?php
			if(isset($mrn_list))
			{
			?>
			<table id="mrn_list"  class="dataTables_wrapper table table-striped table-hover">
				<thead>
					<tr>
						<th>M.R.N. No</th>						
						<th>Date</th>
						<th>Vendor Name</th>
						<th>Material Name</th>
						<th>Make/Source</th>
						<th>Return Qyt.</th>
						<th>Unit</th>
						<th>Action</th>
						<?php 
						if($this->ERPfunction->retrive_accessrights($role,'approvemrnalert')==1){
						?>
						<th>Approve</th>
						<?php
							}						
						?>
						<!-- <th>Approved by CM</th> -->
						<!-- <th>Approved by Accounts</th> -->
						<!-- <th>Approved by Executives</th> -->						
					</tr>
				</thead>
				<tbody>
					<?php
						$rows = array();
						$rows[] = array("M.R.N. No","Date","Time","Vendor ID","Vendor Name","Material Code","Material Name","Make/Source","Return Qyt.","Unit");
					
						$i = 1;
						foreach($mrn_list as $retrive_data)
						{
						$csv = array();
						?>
							<tr>								
								<td><?php echo ($csv[] = $retrive_data['mrn_no']);?></td>								
								<td><?php echo ($csv[] = $this->ERPfunction->get_date($retrive_data['mrn_date']));?></td>														
								<td><?php echo ($csv[] = $this->ERPfunction->get_user_name($retrive_data['vendor_user']));?></td>														
								<td><?php echo ($csv[] = $this->ERPfunction->get_material_title($retrive_data['erp_inventory_mrn_detail']['material_id']));?></td>
								<td><?php echo ($csv[] = $this->ERPfunction->get_brandname($retrive_data['erp_inventory_mrn_detail']['brand_id']));?></td>
								<td><?php echo ($csv[] = $retrive_data['erp_inventory_mrn_detail']['quantity']);?></td>
								<td><?php echo ($csv[] = $this->ERPfunction->get_items_units($retrive_data['erp_inventory_mrn_detail']['material_id']));?></td>
								<td>
								<?php 
								echo $this->Html->link("<i class='icon-eye-open'></i> View",array('controller'=>'Inventory','action' => 'previewmrn', $retrive_data['mrn_id']),
								array('escape'=>false,'class'=>'btn btn-primary btn-clean','target'=>'_blank'));
								
								?>
								</td>
								<!--
								<td>
									<div class="checkbox">
										<label><input type="checkbox" class="approve" data-role="bycm" 
										value="<?php //echo $retrive_data['mrn_id'];?>" 
										<?php //echo $this->ERPfunction->checked($retrive_data['approve_cm'],1);?>
										<?php //if($retrive_data['approve_cm'])echo 'disabled="disabled"';?>
										name="Approve"/> </label>
									</div>
								</td> -->
								<!-- <td>
									<div class="checkbox">
											<?php //if($user_r == 'accountant'){ 
											?>
											<label><input type="checkbox" class="approve" data-role="byac" 
										value="<?php //echo $retrive_data['mrn_id'];?>" 
										<?php //echo $this->ERPfunction->checked($retrive_data['approve_accountant'],1);?>
										name="Approve"/> </label>
										<?php
											//}else{

											?>
										<label><input type="checkbox" class="approve" data-role="byac" 
										value="<?php //echo $retrive_data['mrn_id'];?>" 
										<?php //echo $this->ERPfunction->checked($retrive_data['approve_accountant'],1);?>
										<?php //if($retrive_data['approve_accountant'])echo 'disabled="disabled"';?>
										name="Approve"/> </label>

										<?php //} ?>



									</div>
								</td>	-->
								<!-- <td>
									<div class="checkbox">
										<?php 
											//if($user_r == 'accountant'){
										?>
										<label><input type="checkbox" class="approve" data-role="byexecute" 
										value="<?php //echo $retrive_data['mrn_id'];?>" 
										<?php //echo $this->ERPfunction->checked($retrive_data['approve_executives'],1);?>
										<?php //echo 'disabled="disabled"';?>
										name="Approve"/> </label>
										<?php //}else{ ?>
										<label><input type="checkbox" class="approve" data-role="byexecute" 
										value="<?php //echo $retrive_data['mrn_id'];?>" 
										<?php //echo $this->ERPfunction->checked($retrive_data['approve_executives'],1);?>
										<?php //if($retrive_data['approve_executives'])echo 'disabled="disabled"';?>
										name="Approve"/> </label>
										<?php //} ?>

									</div>
								</td> -->
								<?php 
									if($this->ERPfunction->retrive_accessrights($role,'approvemrnalert')==1)
								{
										?>
								<td>
									<div class="checkbox" style="display: inline-block;">
										<label><input type="checkbox" class="approve" data-role="byexecute" 
										value="<?php echo $retrive_data['mrn_id'];?>" 
										<?php echo $this->ERPfunction->checked($retrive_data['approve_executives'],1);?>
										<?php if($retrive_data['approve_executives'])echo 'disabled="disabled"';?>
										name="Approve"/> </label>
									</div>
								</td>
								<?php } ?>
								</tr>
						<?php
						$rows[] = $csv;
						$i++;
						}
					?>
				</tbody>
			</table>
			<?php
			if($mrn_list != NULL)
			{
			?>
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
			<?php } } ?>
		</div>
		</div>
	</div>
</div>
<?php } ?>
</div>