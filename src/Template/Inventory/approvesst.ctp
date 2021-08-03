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
				<h2>S.S.T. Alert </h2>
				<div class="pull-right">
				<a href="<?php echo $this->ERPfunction->action_link('Inventory','index');?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
				</div>
			</div>
			<div class="content">
		<script>
		var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;

		jQuery(document).ready(function() {
		jQuery("#user_form").validationEngine();
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
		jQuery('#pr_list').DataTable({"order": [[ 1, "desc" ]]});
		
		jQuery("body").on("change", ".approve", function(event){
				var pr_id = jQuery(this).val();
				
				if(confirm('Are you Sure approve this PR?'))
				{
				var curr_data = {	 						 					
	 					pr_id : pr_id,	 					
	 					};	 				
	 	 jQuery.ajax({
			headers: {
					'X-CSRF-Token': csrfToken
				},
                type:"POST",
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'approvepr'));?>",
                data:curr_data,
                async:false,
                success: function(response){					
					 location.reload();
					return false;
                },
                error: function (e) {
                     alert('Error');
                }
		});}
			else
			{				
				 jQuery(this).removeAttr('checked');
				 jQuery(this).parent().removeClass('checked');
				//jQuery(this).prop('checked', true);
			}
			});		
		} );
	
	function check_select()
	{
		//check item is actually selected or not.
		return true;
	}
</script>

		<div class="col-md-12 filter-form">
			<?php 
@$project_id = isset($request_data['project_id'])?$request_data['project_id']: $this->request->params["pass"]["0"];
$from_date = isset($request_data['from_date'])?$request_data['from_date']:'';
$to_date = isset($request_data['to_date'])?$request_data['to_date']:'';
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
			jQuery('#sst_list').DataTable({responsive: true});
			jQuery("body").on("change", ".approve_site1", function(event){
				var sst_id = jQuery(this).val();
				var data_site = jQuery(this).attr('data-site');
				var sst_detail_id = jQuery(this).attr("sst_detail_id");
				
				var material_id = jQuery(this).attr("material_id");
				var quantity = jQuery(this).attr("qty");
				var project_id = jQuery(this).attr("data-project_id");
				var transfer_to = jQuery(this).attr("transfer_to");
				
				if(confirm('Are you Sure approve this S.S.T.?'))
				{
				if(confirm('Are you Sure approve this S.S.T.?'))
				{
				var curr_data = {	 						 					
	 					sst_id : sst_id,
						data_site :data_site,
						sst_detail_id :sst_detail_id,
						material_id : material_id,
						quantity : quantity,
						project_id : project_id,
						transfer_to : transfer_to,
						
	 					};	 				
	 	 jQuery.ajax({
			headers: {
					'X-CSRF-Token': csrfToken
				},
                type:"POST",
                url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'approvesstsite1'));?>",
                data:curr_data,
                async:false,
                success: function(response){
					
					 location.reload();
					return false;
                },
                error: function (e) {
                     alert('Error');
                }
		});}else
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
			
			jQuery("body").on("change", ".approve_site2", function(event){
				var sst_id = jQuery(this).val();
				var data_site = jQuery(this).attr('data-site');
				var material_id = jQuery(this).attr("material_id");
				var quantity = jQuery(this).attr("qty");
				var project_id = jQuery(this).attr("data-project_id");
				var transfer_to = jQuery(this).attr("transfer_to");
				var sst_detail_id = jQuery(this).attr("sst_detail_id");
				
				if(confirm('Are you Sure approve this S.S.T.?'))
				{
					if(confirm('Are you Sure approve this S.S.T.?'))
					{
						var url = "<?php echo Router::url(array('controller'=>'Inventory','action'=>'preparegrnwithoutpo'));?>";
						// alert(url);return false;
						window.location.href = url+"/?sst_id="+sst_id+"&sst_detail_id="+sst_detail_id;
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
			
		// jQuery("body").on("change", ".approve_site2", function(event){
				// var sst_id = jQuery(this).val();
				// var data_site = jQuery(this).attr('data-site');
				// var material_id = jQuery(this).attr("material_id");
				// var quantity = jQuery(this).attr("qty");
				// var project_id = jQuery(this).attr("data-project_id");
				// var transfer_to = jQuery(this).attr("transfer_to");
				// var sst_detail_id = jQuery(this).attr("sst_detail_id");
				
				// if(confirm('Are you Sure approve this S.S.T.?'))
				// {
				// if(confirm('Are you Sure approve this S.S.T.?'))
				// {
			
				// var curr_data = {	 						 					
	 					// sst_id : sst_id,
						// data_site :data_site,
						// material_id : material_id,
						// quantity : quantity,
						// project_id : project_id,
						// transfer_to : transfer_to,
						// sst_detail_id : sst_detail_id,
	 					// };	 				
	 	 // jQuery.ajax({
                // type:"POST",
                // url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'approvesstsite2'));?>",
                // data:curr_data,
                // async:false,
                // success: function(response){
					
					 // location.reload();
					// return false;
                // },
                // error: function (e) {
                     // alert('Error');
                // }
		// });}else
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
			// });	
			
		} );
</script>
			<table id="sst_list"  class="dataTables_wrapper table table-striped table-hover">
				<thead>
					<tr>
						<th>S.S.T. No</th>						
						<th>Date</th>					
						<th>Time</th>
						<th>Project Name From</th>						
						<th>Project Name To</th>							
						
						<th>Material Name</th>
						<th>Make/<br>Source</th>
						<th>Quantity</th>
						<th>Unit</th>
						<th>View</th>
						<?php
						 if($this->ERPfunction->retrive_accessrights($role,'approvesst1')==1)
						{  ?>
						<th>Approved by Site 1</th>
						<?php
						 }
						if($this->ERPfunction->retrive_accessrights($role,'approvesst2')==1)
						{ ?>
						<th>Approved by site 2</th>
						<?php } ?>
												
					</tr>
				</thead>
				<tbody>
					<?php
					if(isset($sst_list))
					{
						$i = 1;
						foreach($sst_list as $retrive_data)
						{
						?>
							<tr>
								<td><?php echo $retrive_data['sst_no'];?></td>								
								<td><?php echo $this->ERPfunction->get_date($retrive_data['sst_date']);?></td>														
								<td><?php echo $retrive_data['sst_time'];?></td>								
								<td><?php echo $this->ERPfunction->get_projectname($retrive_data['project_id']);?></td>														
								<td><?php echo $this->ERPfunction->get_projectname($retrive_data['transfer_to']);?></td>

																							
								<td><?php echo $this->ERPfunction->get_material_title($retrive_data['material_id']);?></td>													
								<td><?php echo $this->ERPfunction->get_brandname($retrive_data['brand_id']);?></td>													
								<td><?php echo $retrive_data['quantity'];?></td>													
								<td><?php echo $this->ERPfunction->get_items_units($retrive_data['material_id']);?></td>
								<td>
								<?php 
								
								echo $this->Html->link("<i class='icon-eye-open'></i> View",array('action' => 'previewsst', $retrive_data['sst_id']),
								array('class'=>'btn btn-primary btn-clean','target'=>'_blank','escape'=>false));
								
								if($this->ERPfunction->retrive_accessrights($role,'editsst')==1)
								{
								echo $this->Html->link("<i class='icon-pencil'></i> Edit",array('action' => 'editsst', $retrive_data['sst_id']),
								array('class'=>'btn btn-primary btn-clean','target'=>'_blank','escape'=>false));
								}
								if($this->ERPfunction->retrive_accessrights($role,'deletesst')==1)
								{
								echo $this->Html->link("<i class='icon-trash'></i> Delete ",array('action' => 'deletesst', $retrive_data['sst_detail_id']),
								array('class'=>'btn btn-danger btn-clean','escape'=>false));
								}
								?>
								</td>
								<?php
								$site_1 = $this->ERPfunction->users_project($user_id);
								if($this->ERPfunction->retrive_accessrights($role,'approvesst1')==1)
								{
								?>
								<td>
								<?php
								if($this->ERPfunction->project_alloted($role)==1){ 
								
									$site_1 = $this->ERPfunction->users_project($user_id);
									
									if(in_array($retrive_data['project_id'],$site_1))
									{
									if($selected_project == $retrive_data['project_id']){
									?>
										<div class="checkbox">
											<label><input type="checkbox" class="approve_site1" 
											data-site="site1" 
											value="<?php echo $retrive_data['sst_id'];?>"  
											sst_detail_id="<?php echo $retrive_data["sst_detail_id"];?>" 
											transfer_to="<?php echo $retrive_data['transfer_to']; ?>" 
									<?php //echo $this->ERPfunction->checked($retrive_data['approved_site1'],1);?>
									<?php echo ($retrive_data['approved_site1']==1 ? 'checked' : '');?>
									<?php echo ($retrive_data['approved_site1']==1 ? 'DISABLED' : '');?>
											qty="<?php echo $retrive_data["quantity"];?>" 
											material_id="<?php echo $retrive_data["material_id"];?>" 
											data-project_id="<?php echo $retrive_data['project_id'];?>"
											<?php //if($retrive_data['approved_site1'])echo 'disabled="disabled"';?>
											name="Approve"/></label>
										</div>
									
										
									<?php
									} 
									} 
								}
								else
								{
								if($selected_project == $retrive_data['project_id']){
								?>
										 <div class="checkbox">
										<label><input type="checkbox" class="approve_site1" 
										data-site="site1" 
										value="<?php echo $retrive_data['sst_id'];?>"  
										sst_detail_id="<?php echo $retrive_data["sst_detail_id"];?>" 
										transfer_to="<?php echo $retrive_data['transfer_to']; ?>" 
									<?php //echo $this->ERPfunction->checked($retrive_data['approved_site1'],1);?>
									<?php echo ($retrive_data['approved_site1']==1 ? 'checked' : '');?>
									<?php echo ($retrive_data['approved_site1']==1 ? 'DISABLED' : '');?>
											qty="<?php echo $retrive_data["quantity"];?>" 
											material_id="<?php echo $retrive_data["material_id"];?>" 
											data-project_id="<?php echo $retrive_data['project_id'];?>"
											<?php //if($retrive_data['approved_site1'])echo 'disabled="disabled"';?>
											name="Approve"/></label>
										</div> 
								<?php } } ?>
								</td>
								<?php } if($this->ERPfunction->retrive_accessrights($role,'approvesst2')==1) 
									{
								?>
								<td>						
									<?php
									if($this->ERPfunction->project_alloted($role)==1){ 
								if(in_array($retrive_data['transfer_to'],$site_1))
								{
									if($selected_project == $retrive_data['transfer_to']){
									?>
									<div class="checkbox">
										<label><input type="checkbox" class="approve_site2" data-site="site2" 
										value="<?php echo $retrive_data['sst_id'];?>" transfer_to="<?php echo $retrive_data['transfer_to']; ?>" 
										qty="<?php echo $retrive_data["quantity"];?>" material_id="<?php echo $retrive_data["material_id"];?>" data-project_id="<?php echo $retrive_data['project_id'];?>" sst_detail_id="<?php echo $retrive_data["sst_detail_id"];?>"
										<?php //echo $this->ERPfunction->checked($retrive_data['approved_site2'],1);?>
										<?php echo ($retrive_data['approved_site2']==1 ? 'checked' : '');?>
										<?php echo ($retrive_data['approved_site2']==1 ? 'DISABLED' : '');?>
										<?php echo ($retrive_data['approved_site1']==1 ? '' : 'DISABLED');?>
										<?php //if(!$retrive_data['approved_site1'] || $retrive_data['approved_site2'])echo 'disabled="disabled"';?>
										name="Approve"/></label>
									</div>
								<?php } }
									
								
								}
								else
								{ 
									if($selected_project == $retrive_data['transfer_to']){
								?>
									
									 <div class="checkbox">
										<label><input type="checkbox" class="approve_site2" data-site="site2" 
										value="<?php echo $retrive_data['sst_id'];?>" transfer_to="<?php echo $retrive_data['transfer_to']; ?>" 
										qty="<?php echo $retrive_data["quantity"];?>" material_id="<?php echo $retrive_data["material_id"];?>" data-project_id="<?php echo $retrive_data['project_id'];?>" sst_detail_id="<?php echo $retrive_data["sst_detail_id"];?>"
										<?php //echo $this->ERPfunction->checked($retrive_data['approved_site2'],1);?>
										<?php echo ($retrive_data['approved_site2']==1 ? 'checked' : '');?>
										<?php echo ($retrive_data['approved_site2']==1 ? 'DISABLED' : '');?>
										<?php echo ($retrive_data['approved_site1']==1 ? '' : 'DISABLED');?>
										<?php //if(!$retrive_data['approved_site1'] || $retrive_data['approved_site2'])echo 'disabled="disabled"';?>
										name="Approve"/></label>
									</div>
									<?php } } ?>
								</td>	
						<?php } ?>
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