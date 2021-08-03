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
<div class="row">
	<div class="col-md-12">
		<div class="block" style="width:auto;">			
		<div class="head bg-default bg-light-rtl">
			<h2>I.S Alert </h2>
			<div class="pull-right">
			<a href="<?php echo $this->ERPfunction->action_link('Inventory','index');?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
			</div>
		</div>
		<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
				<div class="content">
					<div class="form-row">
						<div class="col-md-2 text-right">Project Name</div>
						<div class="col-md-4">
							<select name="project_id" id="project_id">
								<option value="All">All</Option>
								<?php 
									$pid = isset($searched_project) ? $searched_project: "";									
									foreach($projects as $retrive_data)
									{ 
										$selected = ($retrive_data['project_id'] == $pid) ? "selected" : "";
										?>
										<option value="<?php echo $retrive_data['project_id'];?>" <?php echo $selected;?>><?php echo $retrive_data['project_name']; ?> </option>
										<?php										
									} ?>
								</select>
							</div>
						
							<div class="col-md-2">
								<button type="submit" name="search" value="Search" class="btn btn-primary">Search</button>
							</div>
						</div>
					</div>
					<?php echo $this->Form->end();?>	
		<div class="content list custom-btn-clean">
		<script>
		jQuery(document).ready(function() {
			jQuery('#is_list').DataTable(
				{
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
					} ],
					aaSorting: [[ 1, "desc" ]],
					// "aoColumns": [					
					// { "bSortable": true },
					// { "bSortable": true },
					// { "bSortable": false,sWidth:"10%"},
					// { "bSortable": true },
					// { "bSortable": true },
					// { "bSortable": false },
					// { "bSortable": false },
					// { "bSortable": true },
					// { "bSortable": false },
					// { "bSortable": false },
					// { "bSortable": false }]
					
		});
			jQuery("body").on("click", ".multiple_approve", function(event){
				//var is_id = jQuery(this).val();
				var is_id = jQuery('.approve:checked').map(function() {	return this.attributes.is_id.textContent;
																			}).get();
				
				//var project_id = jQuery(this).attr('data_project_id');
				var project_id = jQuery('.approve:checked').map(function() { 
				return this.attributes.data_project_id.textContent;}).get();
				//var quantity = jQuery(this).attr('qty');
				var quantity = jQuery('.approve:checked').map(function() {	return this.attributes.qty.textContent;
																			}).get();
				//var material_id = jQuery(this).attr('material_id');
				var material_id = jQuery('.approve:checked').map(function() {	return this.attributes.material_id.textContent;
																			}).get();
				//var is_detail_id = jQuery(this).attr('is_detail_id');
				var is_detail_id = jQuery('.approve:checked').map(function() {	return 												this.attributes.is_detail_id.textContent;
																			}).get();
				
				is_id = JSON.stringify(is_id);
				project_id = JSON.stringify(project_id);
				quantity = JSON.stringify(quantity);
				material_id = JSON.stringify(material_id);
				is_detail_id = JSON.stringify(is_detail_id);
				
				// if(confirm('Are you Sure approve this I.S?'))
				// {
				// if(confirm('Are you Sure approve this I.S?'))
				// {
				
				var curr_data = {	 						 					
	 					is_id : is_id,	 					
	 					project_id : project_id,	
						quantity : quantity,
						material_id : material_id,
						is_detail_id : is_detail_id,
	 					};	 				
	 	 jQuery.ajax({
                type:"POST",
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'approveis'));?>",
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
			<table id="is_list"  class="dataTables_wrapper table table-striped table-hover">
				<thead>
					<tr>
						<!--<th>Project Code</th> -->
						<th>I.S. No</th>						
				
						<th>Agency/<br>Asset Name</th>						
						<th style="width:10%">Date</th>					
						<!-- <th>Time</th>	-->				
											
						<th>Material<br>Name</th>					
						<th>Make/<br>Source</th>					
						<th>Quantity</th>					
						<th>Unit</th>					
						<th>Name of Foreman</th>					
						<th class="none">Edit/View</th>						
						<?php
						if($role == "erphead" || $role == "erpmanager" || $role == "md" || $role == "projectdirector" || $role == "constructionmanager" || $role == "materialmanager")
						{ ?>
							<th>Approve</th>
						<?php } ?>		
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php

						if(isset($is_list))
						{
							$i = 1;
							foreach($is_list as $retrive_data)
							{ 
							?>
								<tr>
									<!-- <td><?php // echo $this->ERPfunction->get_projectcode($retrive_data['project_id']);?></td> -->
																	
									<td class="none"><?php echo $retrive_data['is_id'];?></td>								
									<td><?php 									
										$is_asset = explode("_",$retrive_data['agency_name']);
										if(isset($is_asset[1]))
										{
											echo $this->ERPfunction->get_asset_name($is_asset[1]);
										}else{
											echo $this->ERPfunction->get_agency_name($retrive_data['agency_name']);
										} ?>
									</td>								
									<td><?php echo $this->ERPfunction->get_date($retrive_data['is_date']);?></td>														
									<!-- <td><?php /*echo $retrive_data['is_time']; */?></td>	 -->
									<?php $details = $this->ERPfunction->get_approveis_details($retrive_data['is_id']);?>
																						
									<td><?php echo $this->ERPfunction->get_material_title($details["material_id"]);?></td>																				
									<td>None</td>														
									<td><?php echo $retrive_data["quantity"]; ?></td>														
									<td><?php echo $this->ERPfunction->get_items_units($details["material_id"]);?></td>														
									<td><?php echo $details["name_of_foreman"]; ?></td>														
									<td>
									<?php
									if($role == "erphead" || $role == "erpmanager" || $role == "md" || $role == "projectdirector" || $role == "constructionmanager"|| $role == "materialmanager")
									{
										echo $this->Html->link("<i class='icon-edit'></i> Edit",array('action' => "updateis", $retrive_data['is_id'],$searched_project),
										array('class'=>'btn btn-primary btn-clean','target'=>'_blank','escape'=>false));
									}
									
									if($role == "erphead" || $role == "erpmanager" || $role == "md" || $role == "projectdirector" || $role == "constructionmanager" || $role == "materialmanager")
									{
										echo $this->Html->link("<i class='icon-eye-open'></i> View",array('action' => 'previewis', $retrive_data['is_id']),
										array('class'=>'btn btn-primary btn-clean','target'=>'_blank','escape'=>false));
									}
									
									if($role == "erphead" || $role == "erpmanager")
									{
										echo $this->Html->link("<i class='icon-trash'></i> Delete",array('action' => 'deleteis', $retrive_data['is_id']),
										array('class'=>'btn btn-danger btn-clean','target'=>'_blank','escape'=>false));
									}
									?>
									</td>
									<?php
									if($role == "erphead" || $role == "erpmanager" || $role == "md" || $role == "projectdirector" || $role == "constructionmanager" || $role == "materialmanager")
									{ ?>
									<td class="none">								
										<div class="checkbox" style="display:inline-block;">
											<label>
											<input type="checkbox" class="approve" qty="<?php echo $retrive_data["quantity"];?>" material_id="<?php echo $retrive_data["material_id"];?>" data_project_id="<?php echo $retrive_data['project_id'];?>" value="<?php echo $retrive_data['is_id'];?>" is_id="<?php echo $retrive_data['is_id'];?>" is_detail_id="<?php echo $retrive_data["is_detail_id"];?>" name="Approve" />
											</label>
										</div>								
									</td>
									<?php } ?>
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
						if(isset($is_list))
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