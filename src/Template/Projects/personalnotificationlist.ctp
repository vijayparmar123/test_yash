<?php
use Cake\Routing\Router;
$login_user = $this->request->session()->read('user_id');
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
										
				<div class="content list custom-btn-clean">
				<table id="notification_list"  class="dataTables_wrapper table table-striped table-hover">
				<thead>
					<tr>
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
					$rows[] = array("Message","Event Date","Type Of Event");
					foreach($search_data as $data)
					{
						echo "
						<tr>
							<td>".($export[] = $data['message'])."</td>
							<td>".($export[] = date("Y-m-d",strtotime($data['event_date'])))."</td>
							<td>".($export[] = ucfirst($data['event_type']))."</td>";
													
							echo "<td>";
							if($this->ERPfunction->retrive_accessrights($role,'personalnotificationlist')==1)
							{
								if($role !='erphead'){
									if($login_user == $data['created_by'])
									{
										echo "<a href='{$this->request->base}/projects/viewpersonalnotification/{$data['id']}' class='btn btn-primary btn-clean' target='_blank'><i class='icon-eye-open'></i> View</a>";
									}
								}else{
									echo "<a href='{$this->request->base}/projects/viewpersonalnotification/{$data['id']}' class='btn btn-primary btn-clean' target='_blank'><i class='icon-eye-open'></i> View</a>";
								}
							}
							if($this->ERPfunction->retrive_accessrights($role,'editpersonalnotification')==1)
							{
								if($role !='erphead'){
									if($login_user == $data['created_by'])
									{	
										echo "<a href='{$this->request->base}/projects/editpersonalnotification/{$data['id']}' class='btn btn-success btn-clean' target='_blank'><i class='icon-pencil'></i> Edit</a>";
									}
								}else{
									echo "<a href='{$this->request->base}/projects/editpersonalnotification/{$data['id']}' class='btn btn-success btn-clean' target='_blank'><i class='icon-pencil'></i> Edit</a>";
								}
							}
							if($this->ERPfunction->retrive_accessrights($role,'deletepersonalnotification')==1)
							{
								echo "<a href='{$this->request->base}/projects/deletepersonalnotification/{$data['id']}' onClick=\"javascript: return confirm('Are you sure,you wish to Delet Record');\" class='btn btn-danger btn-clean'><i class='icon-trash'></i>Remove</a>";
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
						