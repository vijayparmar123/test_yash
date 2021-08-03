<?php
//$this->extend('/Common/menu')
?>
<div class="col-md-10" >
<?php
if(!$is_capable)
{
	$this->ERPfunction->access_deniedmsg();
}
else{
?>              
<div class="modal fade " id="load_modal" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content"></div>
    </div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="block">
		<div class="head bg-default bg-light-rtl">
			<h2>Vendor Master</h2>
			<div class="pull-right">
			<a href="<?php echo $this->request->base;?>/Purchase/addvendor" class="btn btn-primary">Add Vendor</a>
			<a href="<?php echo $this->ERPfunction->action_link($back_url,$back_page);?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
			</div>
		</div>
		
		<?php echo $this->Form->Create('form1',['id'=>'eq_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
			 <div class="content controls">											
						<div class="form-row">
							<div class="col-md-2 text-right">Vendor ID</div>
							<div class="col-md-4">
								<input name="vendor_id" class="form-control">
							</div>
							<!--
							<div class="col-md-2 text-right">Vendor Group</div>
							<div class="col-md-4">
								<?php
									// $groups = $this->ERPfunction->asset_group();									
									// echo "<select class='select2' name='vendor_group[]' style='width:100%' multiple='multiple'>";
										// echo "<option value='All'>All</option>";								
										// foreach($vendor_groups as $key => $retrive_data)
										// {
											// echo '<option value="'.$retrive_data['id'].'" '.$this->ERPfunction->selected($retrive_data['id'],$vendor_group).'>'.$this->ERPfunction->get_vendor_group_name($retrive_data['id']).'</option>';
										// }								
									// echo "</select>";
								?>
							</div> -->
						</div>
						<div class="form-row">
							<div class="col-md-2 text-right">Vendor Name</div>
							<div class="col-md-4">
								<input name="vendor_name" class="form-control">
							</div>
							<div class="col-md-2 text-right">
								<button type="submit" name="search" value="Search" class="btn btn-primary">Search</button>
							</div>							
						</div>		
			</div>		
		<?php echo $this->Form->end();?>
		
		<div class="content list custom-btn-clean">
		<script>
var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
		jQuery(document).ready(function() {
			jQuery('#user_list').DataTable({responsive: true});
			$("body").on("click","#join_vendor_record",function(){
				var vendorChildId = $(this).attr("vendor_row_id");
				var url = $("#join_vendor_url").val();
				var curr_data = {vendorChildId:vendorChildId};

				$.ajax({
					headers: {
					'X-CSRF-Token': csrfToken
				},
					url : url,
					data : curr_data,
					type : "POST",
					async:false,
					success : function(response){
						//$('.modal-dialog').css("width","1076px");
						jQuery('.modal-content').html('');
						jQuery('.modal-content').html(response);
						jQuery('#load_modal').modal('show');
					},
					error : function(e){
						console.log(e.responseText);
					}
				});
			});
		});
</script>
<input type="hidden" value="<?php echo $this->request->base.'/Ajaxfunction/joinvendor'; ?>" id="join_vendor_url">
			<table id="user_list"  class="dataTables_wrapper table table-striped table-hover">
				<thead>
					<tr>
						<!-- <th>Image</th>
						<th>Vendor Group</th> -->
						<th>Vendor ID</th>
						<th>Vendor Name</th>
						<th>Contact No(1)</th>
						<th>Contact No(2)</th>						
						<th>Address</th>						
						<th>Email ID</th>						
						<!--<th>Status</th>						
						<th>Last Removed</th>-->
						<th>Attachment</th>	
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$i = 1;
						$rows = array();
						$rows[] = array("Vendor ID","Vedor Name","Contact No(1)","Contact No(2)","Address","Email ID");
						foreach($user_list as $retrive_data)
						{ $export = array();
						?>
							<tr>
								<!-- <td><?php 
									// echo $this->Html->image($this->ERPfunction->get_vendor_image($retrive_data['user_id']),
				// array('class'=>'userimage','height'=>'50px','width'=>'50px')); ?>
								</td>
								<td><?php //echo $this->ERPfunction->get_vendor_group_name($retrive_data['vendor_group']);?></td>
								-->
								<td><?php echo ($export[] = $retrive_data['vendor_id']);?></td>
								<td><?php echo ($export[] = $retrive_data['vendor_name']);?></td>
								<td><?php echo ($export[] = $retrive_data['contact_no1']);?></td>
								<td><?php echo ($export[] = $retrive_data['contact_no2']);?></td>
								<td><?php echo ($export[] = $retrive_data['vendor_billing_address']);?></td>
								<td><?php echo ($export[] = $retrive_data['email_id']);?></td>								
								<!-- <td>
								<?php /*echo $this->ERPfunction->get_vendor_status($retrive_data['user_id']);?>								
								</td>
								<td>																
								<?php echo $this->ERPfunction->get_vendor_remove_date($retrive_data['user_id']);*/?>
								</td>	-->							
								<td>
								<?php
									$attach_file = json_decode($retrive_data["attach_file"]);						
									$attach_label = json_decode($retrive_data["attach_label"]);						
									if(!empty($attach_file))
									{
										$i=0;
										foreach($attach_file as $file)
										{
											echo "<a href='{$this->ERPfunction->get_signed_url($file)}' download='{$attach_label[$i]}' target='_blank' class='btn btn-info btn-clean'><i class='icon-download-alt'></i>{$attach_label[$i]}</a>";
											$i++;
										}
									} ?>
								</td>
								<td>
								<?php
								 if($this->ERPfunction->retrive_accessrights($role,'viewvendor')==1)
								{
									echo $this->Html->link("<i class='icon-eye-open'></i> View",array('action' => 'viewaddvendor', $retrive_data['user_id']),
									array('class'=>'btn btn-success btn-clean','target'=>'blank',"escape"=>false));
								}
								echo ' ';
								// if($this->ERPfunction->retrive_accessrights($role,'addvendor')==1)
								// {
								echo $this->Html->link("<i class='icon-pencil'></i> Edit",array('action' => 'addvendor', $retrive_data['user_id']),
								array('class'=>'btn btn-primary btn-clean','target'=>'blank','escape'=>false));
						
								if($this->ERPfunction->retrive_accessrights($role,'joinvendor')==1) {
								?>
								<a class='btn btn-primary btn-clean' id="join_vendor_record" href='javascript:void(0);' vendor_row_id="<?php echo $retrive_data['user_id']; ?>"><i class='icon-pencil'></i>Join</a>
								<?php } ?>								
							<?php
								// }
								?>
								</td>
							</tr>
						<?php
						$i++;
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