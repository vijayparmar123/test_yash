<?php
use Cake\Routing\Router;
?>
<script type="text/javascript">
	var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
	jQuery(document).ready(function() {
		jQuery(".dataTables_wrapper").dataTable();
		jQuery("body").on("change", "#project_id", function(event){ 
			var project_id  = jQuery(this).val() ;
			var curr_data = {	 						 					
				project_id : project_id,	 					
			};	 				
			jQuery.ajax({
				headers: {
					'X-CSRF-Token': csrfToken
				},
				type:"POST",
				url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'inmanualpoprojectdetail'));?>",
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
						<a href="<?php echo $this->ERPfunction->action_link('Projects','index');?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
						</div>
					</div>
					<!--
                    <div class="header">
                        <h2><u>Make Filter & Sort as per your Requirement</u></h2>
                    </div> -->
					<?php echo $this->Form->Create('form1',['id'=>'eq_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
					

                    <div class="content controls">						
						<div class="form-row">
						<div class="col-md-2" class="text-right">Project Code:<span class="require-field">*</span></div>
							<div class="col-md-4"><input type="text" name="project_code" id="project_code" value="<?php echo isset($data['project_code'])?$data['project_code']:""; ?>"
							class="form-control validate[required]" readonly="true"/></div>
							
							<div class="col-md-2">Project Name:<span class="require-field">*</span></div>
							<div class="col-md-4">
								<select class="select2"  required="true"   style="width: 100%;" name="project_id" id="project_id">
								<option value="">--Select Project--</Option>
								<?php 
									foreach($projects as $retrive_data)
									{
										$selected = ($data['project_id']==$retrive_data['project_id'])?"selected":"";
										echo '<option value="'.$retrive_data['project_id'].'"'.$selected.'>'.$retrive_data['project_name'].'</option>';
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
				<table id="notification_list"  class="dataTables_wrapper table table-striped table-hover">
				<thead>
					<tr>
						<th>Project Code</th>
						<th>Project Name</th>
						<th>Message</th>						
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
					$rows[] = array("Project Code","Project Name","Message","Event Date","Type Of Event");
					foreach($search_data as $data)
					{
						echo "
						<tr>
							<td>".($export[] = $this->ERPfunction->get_projectcode($data['project_id']))."</td>
							<td>".($export[] = $this->ERPfunction->get_projectname($data['project_id']))."</td>
							<td>".($export[] = $data['message'])."</td>
							<td>".($export[] = date("Y-m-d",strtotime($data['event_date'])))."</td>
							<td>".($export[] = ucfirst($data['event_type']))."</td>";
													
							echo "<td>";
							if($this->ERPfunction->retrive_accessrights($role,'contractnotificationlist')==1)
							{
								echo "<a href='{$this->request->base}/projects/viewcontractnotification/{$data['id']}' class='btn btn-primary btn-clean' target='_blank'><i class='icon-eye-open'></i> View</a>";
							}
							if($this->ERPfunction->retrive_accessrights($role,'editcontractnotification')==1)
							{
								echo "<a href='{$this->request->base}/projects/editcontractnotification/{$data['id']}' class='btn btn-success btn-clean' target='_blank'><i class='icon-pencil'></i> Edit</a>";
							}
							if($this->ERPfunction->retrive_accessrights($role,'deletecontractnotification')==1)
							{
								echo "<a href='{$this->request->base}/projects/deletecontractnotification/{$data['id']}' onClick=\"javascript: return confirm('Are you sure,you wish to Delet Record');\" class='btn btn-danger btn-clean'><i class='icon-trash'></i>Remove</a>";
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
						echo $this->Form->create("",["type"=>"file","class"=>"validateForm form-horizontal","role"=>"form","id"=>"export_csv"]);
					?>
					<input type="hidden" name="rows" value='<?php echo serialize($rows);?>'>
					<input type="submit" class="btn btn-success" value="Export To Excel" name="export_csv">
				<?php 
					echo $this->Form->end();
				?>
				</div>
				<div class="col-md-2">
					<?php 
						echo $this->Form->create("",["type"=>"file","class"=>"validateForm form-horizontal","role"=>"form","id"=>"export_pdf"]);
					?>
					<input type="hidden" name="rows" value='<?php echo serialize($rows);?>'>
					<input type="submit" class="btn btn-success" value="Export To PDF" name="export_pdf">
				<?php 
					echo $this->Form->end();
				?>
				</div>
			</div>
		<?php }} ?>
				
				</div>				
				
		</div>
					
					
<?php
 }
 ?>						
</div>
						