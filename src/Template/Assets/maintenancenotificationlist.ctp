<?php
use Cake\Routing\Router;
?>
<script type="text/javascript">
	var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;

	jQuery(document).ready(function() {
		jQuery(".dataTables_wrapper").dataTable();
		jQuery("body").on("change", "#asset_namelist", function(event) {	 
			var asset_name  = jQuery(this).val();
			if(asset_name == "") {
				return false;
			}
			var curr_data = {	 						 					
				asset_name : asset_name,	 					
			};	 				
			jQuery.ajax({
				headers: {
					'X-CSRF-Token': csrfToken
				},
				type:"POST",
				url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'namebyassetdata'));?>",
				data:curr_data,
				async:false,
				success: function(response){					
					var json_obj = jQuery.parseJSON(response);					
					jQuery('#asset_code').val(json_obj['asset_code']);		
					jQuery('#deployed_to').val(json_obj['deployed_to_id']).change();	
					jQuery('.deploy_to_project').val(json_obj['deployed_to_id']);	
					jQuery('#vehicle_no').val(json_obj['vehicle_no']);			
					jQuery('.select2').select2();
					return false;
				},
				error: function (e) {
					alert('Error');
				}
			});	
		});
	}); 
</script>
<div class="col-md-10" >
<?php
if(!$is_capable)
{
	$this->ERPfunction->access_deniedmsg();
}
else{
?>    				
                <div class="block">		
					<div class="head bg-default bg-light-rtl">
						<h2><?php echo $form_header;?> </h2>
						<div class="pull-right">
						<a href="<?php echo $this->ERPfunction->action_link('Assets','index');?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
						</div>
					</div>
					<!--
                    <div class="header">
                        <h2><u>Make Filter & Sort as per your Requirement</u></h2>
                    </div> -->
					<?php echo $this->Form->Create('form1',['id'=>'eq_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
					

                    <div class="content controls">						
						<div class="form-row">
							<div class="col-md-2 text-right">Asset ID</div>
							<div class="col-md-4"><input name="asset_id" id="asset_code" value="<?php echo isset($data)?$data["asset_id"]:""; ?>" readonly="true" class="form-control"></div>
							<div class="col-md-2 text-right">Asset Name</div>
							<div class="col-md-4">
							<?php
								echo $this->Form->select("asset_name",$asset_list,["empty"=>[""=>"Select Asset"],"class"=>"select2","style"=>"width:100%","id"=>"asset_namelist"]);
							?>
							</div>
						</div>
						<div class="form-row">
							<div class="col-md-2 text-right">Identity/Veh.No </div>
							<div class="col-md-4"><input name="identity" value="<?php echo isset($data)?$data["identity"]:""; ?>" id="vehicle_no" readonly="true" class="form-control"></div>
							<div class="col-md-2 text-right">Deployed To</div>
							<div class="col-md-4">
							<input type="hidden" class="form-control deploy_to_project" name="deploy_to_project" value="">
							<select class="select2" style="width: 100%;" readonly="true" name="deployed_to" id="deployed_to">
								<option value=''>Select Project</Option>
								<?php 
									foreach($projects as $retrive_data)
									{?>
										<option value="<?php echo $retrive_data['project_id'];?>" <?php 
											if(isset($data)){
												if($data['deploy_to_project'] == $retrive_data['project_id'])
												{
													echo 'selected="selected"';
												}
			
											}?> >
											<?php echo $retrive_data['project_name']; ?> </option>
										<?php										
									}
								?>
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
				<table id="asset_list"  class="dataTables_wrapper table table-striped table-hover">
				<thead>
					<tr>
						<th>Asset Id</th>
						<th>Asset Name</th>
						<th>Make</th>
						<th>Identity/Vehicle No</th>
						<th>Currently Deployed To</th>						
						<th>Event Date</th>						
						<th>Type Of Event</th>									 
						<th>Action</th>
						
					</tr>
				</thead>
				<tbody>
				<?php 
				if(!empty($search_data))
				{
					$rows = array();
					$rows[] = array("Asset Id","Asset Name","Make","Identity/Vehicle No","Currently Deployed To","Event Date","Type Of Event");
					foreach($search_data as $data)
					{
						echo "
						<tr>
							<td>".($export[] = $data['asset_code'])."</td>
							<td>".($export[] = $this->ERPfunction->get_asset_name($data['asset_id']))."</td>
							<td>".($export[] = $data['asset_make'])."</td>
							<td>".($export[] = $data['identity'])."</td>
							<td>".($export[] = $this->ERPfunction->get_projectname($data['deploy_to']))."</td>
							<td>".($export[] = date("Y-m-d",strtotime($data['event_date'])))."</td>
							<td>".($export[] = ucfirst($data['event_type']))."</td>";
													
							echo "<td>";
							
								echo "<a href='{$this->request->base}/Assets/viewmaintainancenotification/{$data['id']}' class='btn btn-primary btn-clean' target='_blank'><i class='icon-eye-open'></i> View</a>";
							
							if($this->ERPfunction->retrive_accessrights($role,'editmaintenancenotification')==1)
							{
								echo "<a href='{$this->request->base}/Assets/editmaintenancenotification/{$data['id']}' class='btn btn-success btn-clean' target='_blank'><i class='icon-pencil'></i> Edit</a>";
							}
							if($this->ERPfunction->retrive_accessrights($role,'deletemaintainancenotification')==1)
							{
								echo "<a href='{$this->request->base}/Assets/deletemaintainancenotification/{$data['id']}' onClick=\"javascript: return confirm('Are you sure,you wish to Delet Record');\" class='btn btn-danger btn-clean'><i class='icon-trash'></i>Remove</a>";
							}
							echo "</td>";
							echo "</tr>";
							$rows[] = $export;
					}
				}
				?>
				</tbody>
				</table>
								
				<?php
			if(isset($search_data))
			{
			 if($search_data != NULL){
			?>
			<div class="content">
				 <div class="col-md-2"><a href="javascript:void(0);" class="btn btn-success" id="fullscreen" url='<?php echo $_SERVER['REQUEST_URI']; ?>' onClick="DoFullScreen()" >View Full Screen</a></div>
				
				<div class="col-md-2">
				<?php 
					echo $this->Form->Create('export_csv',['method'=>'post']);
				?>
					<input type="hidden" name="rows" value='<?php echo serialize($rows);?>'>
					<input type="submit" class="btn btn-success" value="Export To Excel" name="export_csv">
				<?php $this->Form->end(); ?>
				</div>
				<div class="col-md-2">
				<?php 
					echo $this->Form->Create('export_pdf',['method'=>'post']);
				?>
					<input type="hidden" name="rows" value='<?php echo serialize($rows);?>'>
					<input type="submit" class="btn btn-success" value="Export To PDF" name="export_pdf">
				<?php $this->Form->end(); ?>
				</div>
			</div>
		<?php }} ?>
				
				</div>				
				
		</div>
					
					
<?php
 }
 ?>						
</div>
						