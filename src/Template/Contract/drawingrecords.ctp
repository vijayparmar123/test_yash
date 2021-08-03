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
				<h2>Update / View Drawing Record</h2>
				<div class="pull-right">
					<a href="<?php echo $this->ERPfunction->action_link($back_url,$back_page);?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
				</div>
			</div>
			
		
		
		<div class="content ">
			<div class="col-md-12 filter-form">
			<?php 
			$project_id = array();
			$drawing = array();
			$project_id = isset($request_data['project_id'])?$request_data['project_id']:'';
			$drawing = isset($request_data['drawing_type'])?$request_data['drawing_type']:'';
			?>
				<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
					
					
					<div class="form-row">
							<div class="col-md-2">Project Name</div>
                            <div class="col-md-4">
								<select class="select2" style="width: 100%;" name="project_id[]" multiple="multiple">
								<option value="All" selected>All</Option>
								<?php 
									foreach($projects as $retrive_data)
									{
										$selected = (in_array($retrive_data['project_id'],$project_id)) ? "selected" : "";
										echo '<option value="'.$retrive_data['project_id'].'"'.$selected.'>'.
										$retrive_data['project_name'].'</option>';
									}
								?>
								</select>
							</div>
                            <div class="col-md-2">Drawing Type</div>
                            <div class="col-md-4">
								<select class="select2" style="width: 100%;" name="drawing_type[]" multiple="multiple">
								<option value="All" selected>All</Option>
								<?php 
									foreach($drawing_type as $retrive_data)
									{
										$selected = (in_array($retrive_data['id'],$drawing)) ? "selected" : "";
										echo '<option value="'.$retrive_data['id'].'"'.$selected.'>'.
										$retrive_data['title'].'</option>';
									}
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
		jQuery('#drawing_list').DataTable({responsive: true});
		} );
</script>
			<table id="drawing_list"  class="dataTables_wrapper table table-striped table-hover">
				<thead>
					<tr>
						<th>Project Name</th>
						<th>Drawing Type</th>
						<th>Building/Other Reference</th>
						<th>Drawing NO</th>						
						<th>Drawing Title</th>						
						<th>Last Revision No</th>						
						<th>Last Date Of Receipt</th>						
						<th>Last Attachment</th>										
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php	
						$rows = array();
						$rows[] = array("Project Name","Drawing Type","Building/Other Reference","Drawing NO","Drawing Title","Last Revision No","Last Date Of Receipt","Last Attachment");
						
						foreach($drawing_list as $retrive_data)
						{	
							$export = array();
							$retrive_data = array_merge($retrive_data,$retrive_data['erp_drawing_detail']);
						?>
							<tr>
								<td><?php echo ($export[] = $this->ERPfunction->get_projectname($retrive_data['project_id']));?></td>
								<td><?php echo ($export[] = $this->ERPfunction->get_drawing_type($retrive_data['drawing_type']));?></td>	
								<td><?php echo ($export[] = $this->ERPfunction->get_building_reference($retrive_data['building_reference']));?></td>
								<td><?php echo ($export[] = $retrive_data['drawing_no']);?></td>
								<td><?php echo ($export[] = $retrive_data['drawing_title']);?></td>
								<td><?php echo ($export[] = $retrive_data['revision_no']);?></td>
								<td><?php echo ($export[] = date("d-m-Y",strtotime($retrive_data['receipt_date'])));?></td>
								<?php ($export[] = $retrive_data['attach_name']);?>
								<td><a href="<?php echo $this->ERPfunction->get_signed_url($retrive_data['attach_file']);?>" download="<?php echo $retrive_data['attach_file'];?>" class="btn btn-info btn-clean"><i class="icon-download-alt"></i><?php echo $retrive_data['attach_name'];?></a></td>							
								
								<td>
								<?php 
								if($this->ERPfunction->retrive_accessrights($role,'drawingrecords')==1)
								{
									echo $this->Html->link("<i class='icon-eye-open'></i> View",array('action' => 'viewdrawing', $retrive_data['drawing_id']),
									array('escape'=>false,'target'=>'blank','class'=>'btn btn-info btn-clean'));
									echo ' ';
								}
								if($this->ERPfunction->retrive_accessrights($role,'editdrawing')==1)
								{
									echo $this->Html->link("<i class='icon-pencil'></i> Edit",array('action' => 'editdrawing', $retrive_data['drawing_id']),
									array('escape'=>false,'target'=>'blank','class'=>'btn btn-primary btn-clean'));
									echo ' ';
								}
								
								if($this->ERPfunction->retrive_accessrights($role,'deletedrawing')==1)
								{
									echo $this->Html->link("<i class='icon-remove'></i> Delete",array('action' => 'deletedrawing', $retrive_data['drawing_id']),
									array('escape'=>false,'class'=>'btn btn-danger btn-clean'));
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
			</div>
		</div>
		</div>
	</div>
</div>
<?php } ?>
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