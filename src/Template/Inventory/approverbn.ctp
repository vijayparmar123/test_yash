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
<script>
var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;

jQuery(document).ready(function() {

jQuery("body").on("change", "#project_id", function(event){ 
	  var project_id  = jQuery(this).val() ;
		/* alert(product_id);
		return false; */
	   var curr_data = {	 						 					
	 					project_id : project_id,	 					
	 					};	 				
	 	 jQuery.ajax({
               headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'ingrnprojectdetaillppo'));?>",
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
	});
</script>

<?php
// $search_project_id = isset($_POST["project_id"]) ? $_POST["project_id"] : "";
@$search_project_id = isset($_POST['project_id'])? $_POST['project_id']: $this->request->params["pass"]["0"];
?>
<div class="row" > <!-- style="overflow-x: scroll;" -->
	<div class="col-md-12">
		<div class="block" style="width:auto;">
			<div class="head bg-default bg-light-rtl">
				<h2>R.B.N Alert </h2>
				<div class="pull-right">
					<a href="<?php echo $this->ERPfunction->action_link('Inventory','index');?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
				</div>
			</div>
		<div class="content">
		<div class="col-md-12 filter-form">
		<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
					<div class="form-row">  
						<div class="col-md-2">Project Code:</div>
                            <div class="col-md-3"><input type="text" name="project_code" id="project_code" value="<?php echo (isset($selected_pl))?$this->ERPfunction->get_projectcode($search_project_id):"";?>"
							class="form-control" value="" readonly="true"/></div>                        
					<div class="col-md-2">Select Project</div>
						<div class="col-md-3">
							<select class="select2" style="width: 100%;" name="project_id" id="project_id">
								<!-- <option value="">--Select Project--</Option> -->
								<?php 
									foreach($projects as $retrive_data)
									{
										$selected = ($search_project_id == $retrive_data["project_id"]) ? "selected" : "";
										echo '<option value="'.$retrive_data['project_id'].'" '.$this->ERPfunction->selected($project_id,$retrive_data['project_id']).' '.$selected.'>'.
										$retrive_data['project_name'].'</option>';
									}
								?>
							</select>
						</div>

					<div class="col-md-2"><input type="submit" name="go" id="go" class="btn btn-primary" value="Go" /></div>
						<br>
						<br>
					</div>
				</form>	
			</div>
		</div>
		<div class="content list custom-btn-clean">
		<script>
		jQuery(document).ready(function() {
			
			jQuery('#rbn_list').DataTable({
			responsive: {
						details: {
							type: 'column',
							target: -1
						}
					},
					columnDefs: [ {
						className: 'control',
						orderable: false,
						targets:   -1
					}],
					aaSorting:[[1,'desc']]});
			
			jQuery("body").on("click", ".multiple_approve", function(event){
				// var rbn_id = jQuery(this).val();
				// var material_id = jQuery(this).attr("material_id");
				// var return_qty = jQuery(this).attr("qty");
				// var project_id = jQuery(this).attr("data-project_id");
				// var rbn_detail_id = jQuery(this).attr("rbn_detail_id");
				
				var rbn_id = jQuery('.approve:checked').map(function() {	return this.attributes.rbn_id.textContent;
																			}).get();
				
				var project_id = jQuery('.approve:checked').map(function() { 
				return this.attributes.data_project_id.textContent;}).get();
		
				var return_qty = jQuery('.approve:checked').map(function() {	return this.attributes.qty.textContent;
																			}).get();
				
				var material_id = jQuery('.approve:checked').map(function() {	return this.attributes.material_id.textContent;
																			}).get();
			
				var rbn_detail_id = jQuery('.approve:checked').map(function() {	return 												this.attributes.rbn_detail_id.textContent;
																			}).get();
				
				rbn_id = JSON.stringify(rbn_id);
				project_id = JSON.stringify(project_id);
				return_qty = JSON.stringify(return_qty);
				material_id = JSON.stringify(material_id);
				rbn_detail_id = JSON.stringify(rbn_detail_id);
				
				// if(confirm('Are you Sure approve this R.B.N.?'))
				// {
				// if(confirm('Are you Sure approve this R.B.N.?'))
				// {
				
				var curr_data = {	 						 					
	 					rbn_id : rbn_id,
						material_id:material_id,
						return_qty:return_qty,
						project_id:project_id,
						rbn_detail_id:rbn_detail_id
	 					};	 				
	 	 jQuery.ajax({
               headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'approverbn'));?>",
                data:curr_data,
                async:false,
                success: function(response){
					
					 location.reload();
					return false;
                },
                error: function (e) {
					console.log(e.responseText);
                     alert('Error');
                }
		});
		// }else
			// {
				 // jQuery(this).removeAttr('checked');
				 // jQuery(this).parent().removeClass('checked');
			// }
		   // }
			// else
			// {
				 // jQuery(this).removeAttr('checked');
				 // jQuery(this).parent().removeClass('checked');
			// }
			});	
		} );
</script>
			<table id="rbn_list" style="width:100%;" class="dataTables_wrapper table table-striped table-hover">
				<thead>
					<tr>						
						<th>R. B. N. No.</th>			
						<th style="display:none">ID</th>						
						<th>Date</th>							
						<th>Time</th>					
						<th>Agency/<br>Asset<br>Name</th>					
											
						<th>Material<br>Name</th>					
						<th>Make/<br>Source</th>					
						<th>Returned<br>Quantity</th>					
						<th>Unit</th>					
						<th>Name of Foreman</th>	
						<th>Edit/<br>View</th>
						<?php
						if($role == "erphead" || $role == "erpmanager" || $role == "md" || $role == "projectdirector" || $role == "constructionmanager" || $role == "materialmanager")
						{
						?>
						<th>Approved</th>
						<?php
						}
						?>
						
						<th></th>						
					</tr>
				</thead>
				<tbody>
					<?php 
						$i = 1;
					if(isset($rbn_list))
					{

						foreach($rbn_list as $retrive_data)
						{
							// if($retrive_data['rbn_no'] != "")
							// { ?>
							<tr>								
								<td><?php echo $retrive_data['rbn_no'];?></td>								
								<td style="display:none"><?php echo $retrive_data['rbn_id'];?></td>								
								<td><?php echo $this->ERPfunction->get_date($retrive_data['rbn_date']);?></td>																	
								<td><?php echo $retrive_data['time_of_return'];?></td>															
								<td><?php echo $this->ERPfunction->get_agency_name($retrive_data['agency_name']);?></td>															
																							
								<td><?php echo $this->ERPfunction->get_material_title($retrive_data['material_id']);?></td>													
								<td><?php echo $this->ERPfunction->get_brandname($retrive_data['brand_id']);?></td>													
								<td><?php echo $retrive_data['quantity_reurn'];?></td>													
								<td><?php echo $this->ERPfunction->get_items_units($retrive_data['material_id']);?></td>
								<td><?php echo $retrive_data['name_of_foreman'];?></td>	
								<td>
								<?php
								if($role == "erphead" || $role == "erpmanager" || $role == "md" || $role == "projectdirector" || $role == "constructionmanager" || $role == "materialmanager")
								{
									echo $this->Html->link("<i class='icon-edit'></i> Edit",array('action' => 'editrbn', $retrive_data['rbn_id'],$search_project_id),
									array('class'=>'btn btn-primary btn-clean','target'=>'_blank','escape'=>false));
								}
								
								if($role == "erphead" || $role == "erpmanager" || $role == "md" || $role == "projectdirector" || $role == "constructionmanager" || $role == "materialmanager")
								{
									echo $this->Html->link("<i class='icon-eye-open'></i> View",array('action' => 'previewrbn', $retrive_data['rbn_id'],$search_project_id),
									array('class'=>'btn btn-primary btn-clean','target'=>'_blank','escape'=>false));
								}
								
								if($role == "erphead" || $role == "erpmanager")
								{
									echo $this->Html->link("<i class='icon-trash'></i> Delete ",array('action' => 'deleterbn', $retrive_data['rbn_detail_id']),
									array('class'=>'btn btn-danger btn-clean','escape'=>false));
								}
								?>
								</td>	
								<?php
								if($role == "erphead" || $role == "erpmanager" || $role == "md" || $role == "projectdirector" || $role == "constructionmanager" || $role == "materialmanager")
								{
								?>
								<td>
									<div class="checkbox">
										<label><input type="checkbox" class="approve"
										qty="<?php echo $retrive_data["quantity_reurn"];?>" material_id="<?php echo $retrive_data["material_id"];?>" data_project_id="<?php echo $retrive_data['project_id'];?>" rbn_detail_id="<?php echo $retrive_data["rbn_detail_id"];?>"
										value="<?php echo $retrive_data['rbn_id'];?>" rbn_id="<?php echo $retrive_data['rbn_id'];?>"
										name="Approve"/></label>
									</div>
								</td>
								<?php
								}
								?>
								<td></td>	
							</tr>
						<?php
							$i++;
							}
						}
					?>
				</tbody>
			</table>
		</div>
		<div class="content">
		<?php
						if(isset($rbn_list))
						{
							?>
			<div class="col-md-2 pull-right">
				<button type="button" class="btn btn-success multiple_approve">Approve </button>
			</div>
			<?php
						}
			?>
		</div>
		</div>
	</div>
</div>
<?php }?>
</div>