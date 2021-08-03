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
				<h2>RMC Issue Alert </h2>
				<div class="pull-right">
				<a href="<?php echo $this->ERPfunction->action_link('Inventory','index');?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
				</div>
			</div>
			<div class="content">
		<script>
		var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;

		jQuery(document).ready(function() {
		jQuery("#user_form").validationEngine();
		
		jQuery("body").on("change", "#asset_id", function(event){ 
	 
	  var asset_name  = jQuery(this).val() ;
		/* alert(product_id);
		return false; */
	   var curr_data = {	 						 					
	 					asset_name : asset_name,	 					
	 					};	 				
	 	 jQuery.ajax({
			headers: {
					'X-CSRF-Token': csrfToken
				},
                type:"POST",
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'getassetid'));?>",
                data:curr_data,
                async:false,
                success: function(response){					
					var json_obj = jQuery.parseJSON(response);					
					jQuery('#asset_code').val(json_obj['asset_code']);					
					return false;
                },
                error: function (e) {
                     alert('Error');
                     console.log(e.responseText);
                }
            });	
	});
	
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
				
		} );
	
	
</script>

		<div class="col-md-12 filter-form">
			<?php 
@$project_id = isset($request_data['project_id'])?$request_data['project_id']: $this->request->params["pass"]["0"];
?>
				<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
					<div class="form-row">  
						<div class="col-md-2">Project Code:</div>
						<div class="col-md-3"><input type="text" name="project_code" id="project_code" value="<?php echo (isset($selected_pl))?$this->ERPfunction->get_projectcode($project_id):"";?>"
						class="form-control" value="" readonly="true"/></div>                        
						<div class="col-md-2">Select Project</div>
						<div class="col-md-3">
							<select class="select2" style="width: 100%;" name="project_id" id="project_id">
								<!-- <option value="">--Select Project--</Option> -->
								<?php 
									foreach($projects as $retrive_data)
									{
										echo '<option value="'.$retrive_data['project_id'].'" '.$this->ERPfunction->selected($project_id,$retrive_data['project_id']).'>'.
										$retrive_data['project_name'].'</option>';
									}
								?>
								</select>
						</div>
					</div>
					<div class="form-row">  
						<div class="col-md-2">Asset Code<span class="require-field">*</span> </div>
                        <div class="col-md-3"><input type="text" name="asset_code" id="asset_code" value="" class="form-control" value="" readonly="true"/></div>
						<div class="col-md-2">Asset Name *</div>
						<div class="col-md-3">
							<select class="select2" style="width: 100%;" name="asset_id" id="asset_id">
							<option value="">--Select Asset--</Option>
							<?php 
								foreach($asset_names as $retrive_data)
								{
									echo '<option value="'.$retrive_data['asset_id'].'">'.
									$retrive_data['asset_name'].'</option>';
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
	jQuery("body").on("change", "#approve", function(event){
		var rmc_id = $(this).attr("data-id");
		
		if(confirm('Are you Sure approve this RMC?'))
		{
			if(confirm('Are you Sure approve this RMC?'))
			{
				var curr_data = { rmc_id : rmc_id };
				jQuery.ajax({
					headers: {
					'X-CSRF-Token': csrfToken
				},
						type:"POST",
						url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'approveinventoryrmc'));?>",
						data:curr_data,
						async:false,
						success: function(response){
							 location.reload();
							return false;
						},
						error: function (e) {
							 alert('Error');
						}
				})
			}else{
				 jQuery(this).removeAttr('checked');
				 jQuery(this).parent().removeClass('checked');
			}
		}
		else{
			 jQuery(this).removeAttr('checked');
			 jQuery(this).parent().removeClass('checked');
		}
	});
	jQuery('#rmc_list').DataTable({responsive: true});
});
</script>
			<table id="rmc_list"  class="dataTables_wrapper table table-striped table-hover">
				<thead>
					<tr>
						<th>Date</th>						
						<th>RMC No.</th>					
						<th>Order By</th>
						<th>Concrete Grade</th>						
						<th>Usage</th>
						<th>Qty. Supplied(Cum)</th>
						<th>Start Time</th>
						<th>End Time</th>
						<?php
						 if($this->ERPfunction->retrive_accessrights($role,'approveinventoryrmc')==1)
						{  ?>
						<th>Approve</th>
						<?php } ?>
						<th>Action</th>												
					</tr>
				</thead>
				<tbody>
					<?php
					if(isset($rmc_data))
					{
						$i = 1;
						foreach($rmc_data as $retrive_data)
						{
						?>
							<tr>
								<td><?php echo date("d-m-Y",strtotime($retrive_data['rmc_date']));?></td>								
								<td><?php echo $retrive_data['rmc_no'];?></td>				
								<td><?php echo $retrive_data['order_by'];?></td>
								<td><?php echo $this->ERPfunction->get_concrete_grade_name($retrive_data['concrete_grade']);?></td>						
								<td><?php echo $retrive_data['rmc_usage'];?></td>
								<td><?php echo $retrive_data['total_quantity_supplied'];?></td>
								<td><?php echo $retrive_data['start_time'];?></td>				
								<td><?php echo $retrive_data['end_time'];?></td>
								<?php
								 if($this->ERPfunction->retrive_accessrights($role,'approveinventoryrmc')==1)
								{  ?>
								<td><input type="checkbox" id="approve" data-id="<?php echo $retrive_data['id']; ?>"></td>
								<?php } ?>
								<td>
								<?php 
								if($this->ERPfunction->retrive_accessrights($role,'editinventoryrmc')==1)
								{
								echo $this->Html->link("<i class='icon-edit'></i> Edit",array('action' => 'editinventoryrmc', $retrive_data['id']),
								array('class'=>'btn btn-primary btn-clean','target'=>'_blank','escape'=>false));
								echo "<br>";
								}
								echo $this->Html->link("<i class='icon-eye-open'></i> View",array('action' => 'viewinventoryrmc', $retrive_data['id']),
								array('class'=>'btn btn-primary btn-clean','target'=>'_blank','escape'=>false));
								if($this->ERPfunction->retrive_accessrights($role,'deleteinventoryrmc')==1)
								{
								echo "<br>";
								echo $this->Html->link("<i class='icon-remove'></i> Delete",array('action' => 'deleteinventoryrmc', $retrive_data['id']),
								array('class'=>'btn btn-danger btn-clean','escape'=>false));
								}
								?>
								
								</td>
																
								</tr>
						<?php
						$i++;
						}
						}
					?>
				</tbody>
			</table>
		</div>
		</div>
	</div>
</div>
<?php }?>
</div>