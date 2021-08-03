<?php
//$this->extend('/Common/menu')
?>
<div class="col-md-10" >
<?php 
if(!$is_capable)
	{
		$this->ERPfunction->access_deniedmsg();
	}
else
{ ?>	
<div class="row">
	<div class="col-md-12">
		<div class="block">		
			<div class="head bg-default bg-light-rtl">
				<h2>Agency List </h2>
				<div class="pull-right">
				<a href="<?php echo $this->ERPfunction->action_link($back_url,$back_page);?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
				</div>
			</div>
		
		<?php echo $this->Form->Create('form1',['id'=>'eq_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
			        <div class="content controls">											
						<div class="form-row">
							<div class="col-md-2">Agency ID</div>
							<div class="col-md-4"><input name="agency_id" class="form-control"></div>
							<div class="col-md-2">Agency Name</div>
							<div class="col-md-4">
								<?php  echo $this->Form->select("agency",$agency_dropdown,["empty"=>"all","class"=>"select2","id"=>"","multiple"=>"multiple","style"=>"width: 100%"]);?>							
						
							</div>
						</div>
						<div class="form-row">
							<div class="col-md-2">Email ID</div>
							<div class="col-md-4"><input name="email" class="form-control"></div>
						
							<div class="col-md-2 col-md-offset-2">
								<button type="submit" name="search" value="Search" class="btn btn-primary">Search</button>
							</div>
						</div>
					</div>
					<?php echo $this->Form->end();?>	
		
		
		
		
		<div class="content list custom-btn-clean">
		<script>
		jQuery(document).ready(function() {
		jQuery('#brand_list').DataTable({responsive: true});
		});
</script>
			<table id="brand_list"  class="dataTables_wrapper table table-striped table-hover">
				<thead>
					<tr>
						<th>Agency ID</th>						
						<th>Agency Name</th>						
						<th>Contact No.</th>						
						<th>E-mail ID</th>						
						<th>Attachment</th>	
						<th>Edit/View</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$i = 1;
						$rows = array();
						$rows[] = array("Agency ID","Agency Name","Contact No.","Email ID");
					
						foreach($agency_list as $retrive_data)
						{
							$export = array();
						?>
							<tr>
								<td><?php echo ($export[] = $retrive_data['agency_id']);?></td>
								<td><?php echo ($export[] = $retrive_data["agency_name"]);?></td>	
								<td><?php echo ($export[] = $retrive_data["contact_no"]);?></td>	
								<td><?php echo ($export[] = $retrive_data["email_id"]);?></td>
								<td>
									<?php
									$attached_files = json_decode($retrive_data["attach_file"]);	
									$attached_label = json_decode(stripcslashes($retrive_data['attach_label']));	
									
									if(!empty($attached_files))
									{							
										$i = 0;
										foreach($attached_files as $file)
										{ 
										   if(!empty($file))
										   { ?>
												<a href="<?php echo $this->request->base;?>/img/users_images/<?php echo $file;?>" download="<?php echo $attached_label[$i];?>" class="btn btn-info btn-clean"><i class="icon-download-alt"></i><?php echo $attached_label[$i];?></a>
											<?php $i++;
											}
										}
									} ?>								
								</td>
								<td>
								<?php
								if($this->ERPfunction->retrive_accessrights($role,'editagency')==1)
								{
								echo $this->Html->link("<i class='icon-pencil'></i> Edit",array('action' => 'editagency', $retrive_data['id']),
								array('class'=>'btn btn-primary btn-clean','target'=>'blank','escape'=>false));
								}
								?>
								<?php 
								if($this->ERPfunction->retrive_accessrights($role,'agencylist')==1)
								{
								echo $this->Html->link("<i class='icon-eye-open'></i> View",array('action' => 'viewagency', $retrive_data['id']),
								array('class'=>'btn btn-info btn-clean','target'=>'blank','escape'=>false));
								}
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
			<form method="post">
				<input type="hidden" name="rows" value='<?php echo serialize($rows);?>'>
				<input type="submit" class="btn btn-success" value="Export To Excel" name="export_csv">
			</form>
			</div>
			<div class="col-md-2">
			<form method="post">
				<input type="hidden" name="rows" value='<?php echo serialize($rows);?>'>
				<input type="submit" class="btn btn-success" value="Export To PDF" name="export_pdf">
			</form>
			</div>
		</div>
		</div>
		</div>
	</div>
</div>
<?php } ?>
</div>