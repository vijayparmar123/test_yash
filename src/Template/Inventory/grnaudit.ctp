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
				<h2>GRN Audit</h2>
				<div class="pull-right">
				<a href="<?php echo $this->ERPfunction->action_link('Inventory','index');?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
				</div>
			</div>
			
			<div class="content">
			<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
			<div class="form-row">
				<div class="col-md-2">Project Name:</div>
                <div class="col-md-3">
					<select class="select2" style="width: 100%;" name="project_id" id="project_id">
						<option value="All">All</Option>
						<?php 
							foreach($projects as $retrive_data)
							{
								$searched_projects = isset($_REQUEST['project']) ? $_REQUEST['project'] : array() ;								
								$selected = ($retrive_data['project_id']==$searched_projects) ? "selected" : "";								
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
					<button type="submit" name="search" value="Search" class="btn btn-primary">Search</button>
				</div>
				</form>
			</div>		
		<script>
		var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
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
			
		}else
			{
				 jQuery(this).removeAttr('checked');
				 jQuery(this).parent().removeClass('checked');
			}
			});	
		});
</script>
			<!-- <form action="<?php echo $this->request->base;?>/inventory/approveauditgrn" method="post" id="approveauditform"> -->
			<?php 
				echo $this->Form->Create('',['id'=>'approveauditform','class'=>'form_horizontal formsize','method'=>'post','url'=>['controller'=>'Inventory','action'=>'approveauditgrn']]);
			?>
			<table class="dataTables_wrapper table table-striped">
				<thead>
					<tr class="bg-default">
						<th>Project Name</th>
						<th>G.R.N No</th>					
						<th>Date</th>
						<th>Time</th>							
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
						<th>Approved</th>
					</tr>
				</thead>
				<?php 
				if(isset($grn_list))
				{
				?>
				<tbody>
				<input type="hidden" name="grn_type" id="grn_type" value="<?php echo $grn_type; ?>">
				<input type="hidden" name="project" id="project_id" value="<?php echo $project; ?>">
					<?php
						$i = 1;
						
						foreach($grn_list as $retrive_data)
						{
						?>
							<tr class="audit_row_<?php echo $retrive_data['audit_id']; ?>" id='dd_<?php echo $i; ?>'>								
								<td><?php echo $p_code=$this->ERPfunction->get_projectname($retrive_data['project_id']);?></td> 
								<td><?php echo $retrive_data['grn_no'];?></td>								
								<td><?php echo $this->ERPfunction->get_date($retrive_data['grn_date']);?></td>								
								<td><?php echo $retrive_data['grn_time'];?></td>
																					
								<td colspan="11">
								
								 <?php $p_code=$this->ERPfunction->get_projectname($retrive_data['project_id']); ?>
								<?php echo $this->ERPfunction->get_grn_audit_details($retrive_data['erp_audit_grn_detail'],$retrive_data["po_id"],$retrive_data['vendor_userid'],$retrive_data['payment_method'],$retrive_data["challan_no"],$i); ?>
								</td>
							
								<td>
								<?php if($retrive_data['changes_status'] == 1){ 
										 if($this->ERPfunction->retrive_accessrights($role,'approveauditgrn')==1)
											{
								?>
									<div class="checkbox">
										<input type="checkbox" class="approve_audit" 
										audit_id="<?php echo $retrive_data['audit_id'];?>" value="<?php echo $retrive_data['audit_id'];?>" name="auditid[]"/> 
									</div>
								<?php 
											}
								}else{
											if($this->ERPfunction->retrive_accessrights($role,'doneauditgrn')==1)
											{
								?>
									<button type='button' class='btn btn-success audit_done' data-grn-no= '<?php echo $retrive_data['grn_no'] ?>' data-audit-id='<?php echo $retrive_data['audit_id'];?>'>Done</button>
								<?php 
											}
								} ?>
								</td>	
							</tr>
						<?php
						$i++;
						}
						
					?>
					<!-- <input type="hidden" id="data-url" value="<?php echo $this->request->base; ?>/Ajaxfunction/approvegrnaudit" > -->
				</tbody>
				<?php } ?>
			</table>
			<div class="col-md-2 pull-right">
			<!--<button type="button" class="btn btn-success  multiple_approve">Approve</button>
			<button type="submit" name="approveaudit" class="btn btn-primary">Approve Audit</button>-->
			</div>
			<?php $this->Form->end(); ?>
		</div>
		</div>
	</div>
</div>
<?php }?>
</div>

<script>
var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
jQuery(document).ready(function(){
	/* On click Done button grn audit record will remove from list */
	jQuery("body").on("change",".approve_audit",function(){
		if($(this).prop("checked") == true){
			$(".approve_audit").not(this).prop({ disabled: true, checked: false });
		}
		else if($(this).prop("checked") == false){
			$(".approve_audit").prop({ disabled: false });
		}
	});
	
	jQuery("body").on("change",".approve_audit",function(){
		if(confirm("Are you sure you want to approve this record?"))
		{
			if(confirm("Are you sure you want to approve this record?"))
			{
				$("#approveauditform").submit();
				// var audit_id = $('.approve_audit').attr("audit_id");
				// var project_id = $('#project_id').val();
				// var grn_type = $('#grn_type').val();
				// var curr_data = {
				// 	audit_id,project_id,grn_type
				// }
				// jQuery.ajax({
				// 	headers: {
				// 		'X-CSRF-Token': csrfToken
				// 	},
				// 	type:"POST",
				// 	url:"<?php echo Router::url(array('controller'=>'Inventory','action'=>'approveauditgrn'));?>",
				// 	data:curr_data,
				// 	async:false,
				// 	success: function(response){	
				// 		// alert("Record Approve Successfully");
				// 		window.location.reload();
				// 	},
				// 	error: function (e) {
				// 		alert('Error');
				// 		console.log(e);
                // 	}	
				// });
			}
			
		}
	});
	
	jQuery("body").on("click",".audit_done",function(){
		var audit_id = $(this).attr("data-audit-id");
		var grn_no = $(this).attr("data-grn-no");
		if(audit_id > 0)
		{
			if(confirm("Are you sure you want to Done this record?"))
			{
				if(confirm("Are you sure you want to Done this record?"))
				{
				
					var curr_data = { 
						audit_id:audit_id, 
						grn_no:grn_no
					};
					$.ajax({
						headers: {
							'X-CSRF-Token': csrfToken
						},
						method : "POST",								
						url : "<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'donegrnaudit'));?>",
						data : curr_data,
						async:false,
						success: function(response){
							if(response)
							{
								$('.audit_row_'+audit_id).remove();
							}
						},
						error : function(e){
							console.log(e.responseText);
						}
					});
				}
				
			}
		}else{
			alert("Something went wrong, Try again letter");
			return false;
		}
	});
	/* On click Done button grn audit record will remove from list */
	// jQuery("body").on("click",".multiple_approve",function(){
		// if(jQuery('.approve_audit:checked').length > 0)
		// {
			// if(confirm("Are you sure you want to approve?"))
			// {
				// if(confirm("Are you sure you want to approve?"))
				// {		
				// var ajaxurl = $("#data-url").val();
				
				// audit_id=jQuery('.approve_audit:checked').map(function() {	return this.attributes.audit_id.textContent;}).get();
				
				// var curr_data = {
								// audit_id:audit_id,
							// };
					
					// $.ajax({
						// method : "POST",								
						// url : ajaxurl,
						// data : curr_data,
						// async:false,
						// success: function(response){
						// },
						// error : function(e){
							// console.log(e.responseText);
						// }
					// });
				// }
				
			// }
		// }else{
			// alert('Please select at least one record.');
			// return false;
		// }
	// });
});
</script>