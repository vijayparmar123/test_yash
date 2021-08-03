<?php
use Cake\Routing\Router;
?>
<div class="col-md-10" >
<?php
if(!$is_capable)
{
	$this->ERPfunction->access_deniedmsg();
}
else{
?>              
<div class="col-md-12">
<div class="row">
	
<div class="block">
	<div class="head bg-default bg-light-rtl">
		<h2>Labour Bills Records</h2>
		<div class="pull-right">
			<a href="<?php echo $this->ERPfunction->action_link($back_url,$back_page);?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
		</div>
	</div>
			
<div class="content ">
	<script>
		var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;

		jQuery(document).ready(function() {
			jQuery('#from_date,#to_date').datepicker({
				dateFormat: "dd-mm-yy",
				changeMonth: true,
				changeYear: true,
				yearRange:'-65:+0',
				onChangeMonthYear: function(year, month, inst) {
					jQuery(this).val(month + "-" + year);
				}                    
			});
			
			jQuery("body").on("change", "#project_id", function(event){ 
				jQuery('#project_code').val("");
				var project_id  = jQuery(this).val() ;
				var curr_data = {	 						 					
								project_id : project_id,	 					
								};	 				
				jQuery.ajax({
						headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
						url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'inwoprojectdetail'));?>",
						data:curr_data,
						async:false,
						success: function(response){					
							var json_obj = jQuery.parseJSON(response);					
							jQuery('#project_code').val(json_obj['project_code']);
							return false;
						},
						error: function (e) {
							 alert('Error');
							 console.log(e.responseText);
						}
				});	
			});
			
			jQuery("body").on("change", "#party_id", function(event){
				jQuery('#party_identy').val("");
							
				var party_type = jQuery("#party_id option:selected").attr('dataid');
				var party_id  = jQuery(this).val();
				var curr_data = {	 						 					
								party_id : party_id,party_type : party_type	 					
								};	 				
				jQuery.ajax({
						headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
						url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'vendoragencydetail'));?>",
						data:curr_data,
						async:false,
						success: function(response){					
							var json_obj = jQuery.parseJSON(response);					
							jQuery('#party_identy').val(json_obj['party_id']);
							return false;
						},
						error: function (e) {
							 alert('Error');
						}
				 });	
			});
	
		});
	</script>
			<div class="col-md-12 filter-form">
			<?php 
			$project_id = isset($request_data['project_id'])?$request_data['project_id']:'';
			$from_date = isset($request_data['from_date'])?$request_data['from_date']:'';
			$to_date = isset($request_data['to_date'])?$request_data['to_date']:'';
			?>
				<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
										
					<div class="form-row">			
						<div class="col-md-2">Date:</div>
						<div class="col-md-1" style="max-width:40px;">From</div>
						<div class="col-md-2" style="max-width:125px;">
							<input type="text" name="from_date" id="from_date" value="" class="form-control from_date"/>
						</div>
						
						<div class="col-md-1" style="max-width:40px;">To</div>
						<div class="col-md-2" style="max-width:125px;">
							<input type="text" name="to_date" id="to_date" value="" class="form-control to_date"/>
						</div>
						
						<div class="col-md-2">Bill No.</div>
                        <div class="col-md-4">
							<input type="text" name="bill_no" id="bill_no" value="" class="form-control"/>
						</div>
					</div>
					
					<div class="form-row">
						<div class="col-md-2" class="text-right">Project Code:</div>
						<div class="col-md-4"><input type="text" name="project_code" id="project_code" value="<?php echo (isset($_POST['project_code']))?$_POST['project_code']:''; ?>"
						class="form-control" readonly="true"/></div>					
					
						<div class="col-md-2">Project Name</div>
                        <div class="col-md-4">
							<select class="select2" style="width:100%;" name="project_id" id="project_id">
								<option value="">Select Project</Option>
								<?php 
									foreach($projects as $retrive_data)
									{
										echo '<option value="'.$retrive_data['project_id'].'">'.$retrive_data['project_name'].'</option>';
									}
								?>
							</select>
						</div>
                    </div>
					
					<div class="form-row">
							<div class="col-md-2">Party's Code:</div>
							<div class="col-md-4">
								<input type="text" name="party_identy" readonly="true" id="party_identy" value="<?php echo (isset($_POST['party_identy']))?$_POST['party_identy']:''; ?>" class="form-control" value=""/>
							</div>
                        <div class="col-md-2">Party's Name</div>
                        <div class="col-md-4">
							<select class="select2" style="width: 100%;" name="party_id" id="party_id">
								<option value="">Select Party</Option>
								<?php 
									if($vendor_info){
                            				foreach($vendor_info as $vendor_row){
                            					?>
													<option value="<?php echo $vendor_row['user_id']; ?>" dataid="vendor" <?php 
																if(isset($update_inward)){
																	if($update_inward['party_name'] == $vendor_row['user_id']){
																		echo 'selected="selected"';
																	}
																}

													?> ><?php echo $vendor_row['vendor_name'];?></option>

                            					<?php
                            				}
                            			}
										// if(!empty($agency_list))
										// {
										// 	foreach($agency_list as $agency){ ?>
												<!-- <option value="<?php //echo $agency['agency_id']; ?>" dataid="agency"  -->
												<?php 
													// if(isset($update_inward)){
													// 	if($update_inward['party_name'] == $agency['agency_id']){
													// 		echo 'selected="selected"';
													// 	}
													// }
													?> 
													<!-- > -->
													<!-- <?php// echo $agency['agency_name'];?></option> -->
											<?php	
										// 	}
										// }
										?>
							</select>
						</div>
                        
                    </div>			
					<div class="form-row">
						<div class="col-md-2"> <div class="col-md-12"><input type="submit" name="go" id="go" class="btn btn-primary" value="Search"/></div></div>
					</div>
				<?php $this->Form->end(); ?>
			</div>
			</div>
		<div class="content list custom-btn-clean">
		<script>
		jQuery(document).ready(function() {
		jQuery('#wo_list').DataTable({responsive: true});
		} );
</script>
			<table id="wo_list"  class="dataTables_wrapper table table-striped table-hover">
				<thead>
					<tr>
						<th>Party Name</th>	
						<th>Bill Date</th>
						<th>Bill No</th>
						<th>Party's Name</th>					
						<th>Type of<br>Bill</th>						
						<th>Type of<br>Work</th>						
						<th>Gross Amount</th>						
						<th>Retention Money</th>						
						<th>Net Amount</th>					
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php	
						$rows = array();
						$rows[] = array("Party Name","Bill Date","Bill No","Party's Name","Type of Bill","Type of Work","Gross Amount","Retention Money","Net Amount");
						
						foreach($data as $retrive_data)
						{	
							$export = array();
							if($retrive_data['party_type'] == "temp_emp" )
							{
								$partyname = $this->ERPfunction->get_user_name($retrive_data['party_id']);
							}else{
								$partyname = (is_numeric($retrive_data['party_id']))?$this->ERPfunction->get_vendor_name($retrive_data['party_id']):$this->ERPfunction->get_agency_name_by_code($retrive_data['party_id']);
							}
						?>
							<tr>								
								<td><?php echo ($export[] = $this->ERPfunction->get_projectname($retrive_data['project_id']));?></td>
								<td><?php echo ($export[] = date("Y-m-d",strtotime($retrive_data['bill_date'])));?></td>	
								<td><?php echo ($export[] = $retrive_data['bill_no']);?></td>
								<td><?php echo ($export[] = $partyname);?></td>
								
								<td><?php echo ($export[] = $retrive_data['type_of_bill']);?></td>
								<td><?php echo ($export[] = $retrive_data['type_of_work']);?></td>							
								<td><?php echo ($export[] = $retrive_data['gross_amount']);?></td>
								<td><?php echo ($export[] = $retrive_data['retention_money']);?></td>
								<td><?php echo ($export[] = $retrive_data['net_amount']);?></td>
								<td>
								<?php 
								if($this->ERPfunction->retrive_accessrights($role,'subcontractrecords')==1)
								{
									echo $this->Html->link("<i class='icon-eye-open'></i> View",array('action' => 'previewsubcontract', $retrive_data['id']),
									array('escape'=>false,'target'=>'blank','class'=>'btn btn-info btn-clean'));
									echo ' ';
								}
								if($this->ERPfunction->retrive_accessrights($role,'reversesubcontract')==1)
								{
									echo $this->Html->link("<i class='icon-pencil'></i> Delete",array('action' => 'reversesubcontract', $retrive_data['id']),
									array('escape'=>false,'confirm'=>'Are you sure you want to delete this record?','class'=>'btn btn-danger btn-clean'));
									echo ' ';
								}
								?>
								</td>
							</tr>
						<?php
						$rows[] = $export;
						}
						?>
				</tbody>
			</table>
			<div class="content">
				<div class="col-md-2"><a href="javascript:void(0);" class="btn btn-success" id="fullscreen" url='<?php echo $_SERVER['REQUEST_URI']; ?>' onClick="DoFullScreen()" >View Full Screen</a></div>
				<?php if(!empty($data)){ ?>
				<div class="col-md-2">
				<?php echo $this->Form->create('export_csv',['method'=>'post']); ?>
					<input type="hidden" name="rows" value='<?php echo base64_encode(serialize($rows));?>'>
					<input type="submit" class="btn btn-success" value="Export To Excel" name="export_csv">
				<?php echo $this->Form->end(); ?>
				</div>
				<div class="col-md-2">
				<?php echo $this->Form->create('export_pdf',['method'=>'post']); ?>
					<input type="hidden" name="rows" value='<?php echo base64_encode(serialize($rows));?>'>
					<input type="submit" class="btn btn-success" value="Export To PDF" name="export_pdf">
				<?php echo $this->Form->end(); ?>
				</div>
				<?php } ?>
			</div>
		</div>
		</div>
	</div>
</div>
<?php 
 }
 ?>
</div>
<script>
jQuery(document).ready(function(){
	jQuery("body").on("click",".approve_rate",function(){		
		var checked = jQuery(this).attr('checked');
		if(checked == "checked" && confirm("Are you sure you want to approve?"))
		{
		if(checked == "checked" && confirm("Are you sure you want to approve?"))
		{
			var rate_detail_id = jQuery(this).val();
						
			var curr_data = {
								rate_detail_id:rate_detail_id
							};
			$.ajax({
				method : "POST",								
				url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'approverate'));?>",
				data : curr_data,
				async:false,
				success: function(response){
					// alert("Success");
					location.reload();
				},
				error : function(e){
					console.log(e.responseText);
				}
			});
		}
		else{
			jQuery(this).removeAttr('checked');
			jQuery(this).parent().removeClass('checked');		
		}
		}
		else{
			jQuery(this).removeAttr('checked');
			jQuery(this).parent().removeClass('checked');		
		}
	});
});
</script>