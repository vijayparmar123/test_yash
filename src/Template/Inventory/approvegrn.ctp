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
<?php
 // echo $this->element('breadcrumbs'); 

// $_REQUEST['project_id'] = array_merge($_REQUEST['project_id'],$this->request->params["pass"]);

 ?>

 
<div class="row">
	<div class="col-md-12">
		<div class="block" style="width:auto;">			
			<div class="head bg-default bg-light-rtl">
				<h2>GRN  Alert</h2>
				<div class="pull-right">
				<a href="<?php echo $this->ERPfunction->action_link('Inventory','index');?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
				</div>
			</div>
			
			<div class="content">
			<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
			<div class="form-row">
				<div class="col-md-2">Project Name:</div>
                <div class="col-md-3">
					<select class="select2" style="width: 100%;" name="project_id[]" id="project_id" multiple="multiple">
						<option value="All">All</Option>
						<?php 
							foreach($projects as $retrive_data)
							{
								$searched_projects = isset($_REQUEST['project_id']) ? $_REQUEST['project_id'] : array() ;								
								$selected = in_array($retrive_data['project_id'],$searched_projects) ? "selected" : "";								
								echo '<option value="'.$retrive_data['project_id'].'" '.$selected.' >'.
									$retrive_data['project_name'].'</option>';
							}
						?>
					</select>
				</div>
				
				<div class="col-md-2">G.R.N. Type:</div>
                <div class="col-md-3">
					<select class="select2" style="width:100%;" name="grn_type">
						<option value="All">All</Option>
						<option value="central" <?php echo (isset($_REQUEST['grn_type']) && $_REQUEST['grn_type'] == "central")?"selected":"";?>>Central Purchase</Option>
						<option value="local" <?php echo (isset($_REQUEST['grn_type']) && $_REQUEST['grn_type'] == "local")?"selected":"";?>>Local Purchase</Option>
						<option value="withoutpo" <?php echo (isset($_REQUEST['grn_type']) && $_REQUEST['grn_type'] == "withoutpo")?"selected":"";?>>Without PO</Option>
					</select>
				</div> 
				<div class="col-md-1">
					<button type="submit" name="search" value="Search" class="btn btn-primary">Go</button>
				</div>
			</div>		
		<script>
		jQuery(document).ready(function() {
			jQuery('#grn_list').DataTable({responsive: true});
			jQuery("body").on("change", ".approve", function(event){
				var grn_id = jQuery(this).val();
				alert(grn_id);
				return false;
				var project_id = jQuery(this).attr('data-project_id');
				if(confirm('Are you Sure approve this GRN?'))
				{
				var curr_data = {	 						 					
	 					grn_id : grn_id,	 					
	 					project_id : project_id,	 					
	 					};	 				
	 /*	 jQuery.ajax({
                type:"POST",
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'approvegrn'));?>",
                data:curr_data,
                async:false,
                success: function(response){					
					 location.reload();
					return false;
                },
                error: function (e) {
                     alert('Error');
                }
		});*/		
		}else
			{
				 jQuery(this).removeAttr('checked');
				 jQuery(this).parent().removeClass('checked');
			}
			});	
		});
</script>
	<?php
	// debug($searched_projects);
	// $session = $this->request->session();
	// $session->write("filter",$searched_projects);
	?>
			<table class="dataTables_wrapper table table-striped"><!-- id="grn_list " table-hover--> 
				<thead>
					<tr class="bg-default">
						<th>Project Name</th>
						<th>G.R.N No</th>					
						<th>Date</th>
						<th>Time</th>		
						<!--<th>Attachment</th>-->					
						<th>Vendor Name</th>
						<th style="width: 170px;text-align: center;">Challan No</th>
						
						<th style="width: 159px;text-align: center;">Material Name</th>
						<th>Make<br>/ Source</th>
						<th>Vendor<br>/Royalty's Qty.<br>/ Weight</th>
						<th>Actual Qty.<br>/ Weight</th>
						<th>Diff.<br>(+/-)</th>
						<th>Unit</th>
						<th>Mode of<br>Purchase</th>
						<th>Mode of<br>Payment</th>
						<th>Edit/View</th>
						<?php if($this->ERPfunction->retrive_accessrights($role,'approvegrnalert_inv')==1)
						{ ?>
						<th>Approved</th>
						<?php } ?>
					</tr>
				</thead>
				<tbody>
					<?php
						$i = 1;
						if(isset($grn_list))
						{
						foreach($grn_list as $retrive_data)
						{
						?>
							<tr  id='dd_<?php echo $i; ?>'>								
								<td><?php echo $p_code=$this->ERPfunction->get_projectname($retrive_data['project_id']);?></td> 
								<td><?php echo $retrive_data['grn_no'];?></td>								
								<td><?php echo $this->ERPfunction->get_date($retrive_data['grn_date']);?></td>								
								<td><?php echo $retrive_data['grn_time'];?></td>
								<!--<td>
									<?php
									$attached_files = json_decode($retrive_data['attach_file']);	
									$attached_label = json_decode(stripcslashes($retrive_data['attach_label']));	
									
									if(!empty($attached_files))
									{							
										$a = 0;
										foreach($attached_files as $file)
										{ 
										   if(!empty($file))
										   { ?>
												<a href="<?php echo $this->request->base;?>/img/users_images/<?php echo $file;?>" download="<?php echo $attached_label[$a];?>" class="btn btn-info btn-clean"><i class="icon-download-alt"></i><?php echo $attached_label[$a];?></a>
											<?php $a++;
											}
										}
									} ?>
								</td>-->
							<!-- <td><?php //echo  $retrive_data['vendor_id'];?></td>								
								<td><?php //echo $this->ERPfunction->get_user_name($retrive_data['vendor_userid']);?></td> -->													
								<td colspan="14">
								 <?php $p_code=$this->ERPfunction->get_projectname($retrive_data['project_id']); ?>
								 <?php echo $this->ERPfunction->get_grn_details($retrive_data['erp_inventory_grn_detail'],$retrive_data["po_id"],$retrive_data['vendor_userid'],$retrive_data['payment_method'],$retrive_data["challan_no"],$p_code,$retrive_data['project_id'],$i);?>
								</td>
							<!-- <td>
								<?php 
								// echo $this->Html->link(__('View'),array('action' => 'previewgrn', $retrive_data['grn_id']),
								// array('class'=>'btn btn-primary','target'=>'_blank'));
								
								?>
								</td>
								<td>
									<div class="checkbox">
										<label><input type="checkbox" class="approve" 
											data-project_id="<?php // echo $retrive_data['project_id'];?>"
										value="<?php //echo $retrive_data['grn_id'];?>" name="Approve"/> Approve</label>
									</div>
								</td>	-->	
							</tr>
						<?php
						$i++;
						}
						}
					?>
					<input type="hidden" id="data-url" value="<?php echo $this->request->base; ?>/Ajaxfunction/approvegrn" >
				</tbody>
			</table>
			<div class="col-md-2 pull-right">
			<?php if($this->ERPfunction->retrive_accessrights($role,'approvegrnalert_inv')==1)
						{ ?>
			<button type="button" class="btn btn-success  multiple_approve">Approve</button>
						<?php } ?>
			</div>
		</div>
		</div>
	</div>
</div>
<?php }?>
</div>

<script>
var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
jQuery(document).ready(function(){
	// jQuery("body").on("click",".approve_grn",function(){	
	jQuery("body").on("click",".multiple_approve",function(){
		if(jQuery('.approve_grn:checked').length > 0)
		{
			if(confirm("Are you sure you want to approve?"))
			{
				if(confirm("Are you sure you want to approve?"))
				{
				$(this).attr("disabled","disabled");		
				var ajaxurl = $("#data-url").val();
				// alert(ajaxurl);return false;
				entry=jQuery('.approve_grn:checked').map(function() {	return this.attributes.entry.textContent;
																				}).get();
				grn_id=jQuery('.approve_grn:checked').map(function() {	return this.attributes.grn_id.textContent;
																				}).get();
				detail_id=jQuery('.approve_grn:checked').map(function() {	return this.attributes.detail_id.textContent;
																				}).get();
				project_id=jQuery('.approve_grn:checked').map(function() {	return this.attributes.project_id.textContent;
																				}).get();
				project_code=jQuery('.approve_grn:checked').map(function() {	return this.attributes.project_code.textContent;
																				}).get();
				material_id=jQuery('.approve_grn:checked').map(function() {	return this.attributes.material_id.textContent;
																				}).get();
				quantity=jQuery('.approve_grn:checked').map(function() {	return this.attributes.quantity.textContent;
																				}).get();
				actual_qty=jQuery('.approve_grn:checked').map(function() {	return this.attributes.actual_qty.textContent;
																				}).get();
				static_unit=jQuery('.approve_grn:checked').map(function() {	return this.attributes.static_unit.textContent;
																				}).get();
				var curr_data = {
								entry:entry,
								grn_id:grn_id,
								detail_id:detail_id,
								project_id:project_id,
								project_code:project_code,
								material_id:material_id,
								quantity:quantity,
								actual_qty:actual_qty,
								static_unit:static_unit
							};
					
					$.ajax({
						headers: {
							'X-CSRF-Token': csrfToken
						},
						method : "POST",								
						url : ajaxurl,
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
				
			}
		}else{
			alert('Please select at least one record.');
			return false;
		}
	});
});
</script>