<?php
//$this->extend('/Common/menu')
?>
<div class="col-md-10">
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
						<h2>Edit Price Variation</h2>
						<div class="pull-right">
						<a href="<?php echo $this->ERPfunction->action_link('Contract','index');?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a></div>
			</div>
			
			<?php echo $this->Form->Create('form1',['id'=>'eq_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
			        <div class="content controls">											
						<div class="form-row">
							<div class="col-md-2">Project Code</div>
							<div class="col-md-4"><input name="project_code" class="form-control"></div>
							<div class="col-md-2">Project Name</div>
							<div class="col-md-4">
							<select class="select2" required="true"  style="width: 100%;" name="project_id[]" id="project_id" multiple="multiple">
								<option value="all">All</Option>
								<?php 
									foreach($projects as $retrive_data)
									{?>
										<option value="<?php echo $retrive_data['project_id'];?>" <?php 
											if(isset($update_inward)){
												if($update_inward['project_id'] == $retrive_data['project_id'])
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
							<div class="col-md-2">Price Variation Bill No.</div>
							<div class="col-md-4"><input name="priceno" class="form-control"></div>
						
							<div class="col-md-2 col-md-offset-2">
								<button type="submit" name="search" value="Search" class="btn btn-primary">Search</button>
							</div>
						</div>
					</div>
		<?php echo $this->Form->end();?>	
			
			
			
		<div class="content  list custom-btn-clean">
		<script>
		jQuery(document).ready(function() {
		jQuery('#user_list').DataTable({responsive: true});
		} );
</script>
			<table id="user_list"  class="dataTables_wrapper table table-striped table-hover">
				<thead>
					<tr>
						<th><?php echo __('Project Name');?></th>
						<th><?php echo __('Price Variation No.'); ?></th>
						<th><?php echo __('As On Date');?></th>
						<th><?php echo __('Net Amount');?></th>
						<th><?php echo __('Date of Payment');?></th>
						<th><?php echo __('Attachment');?></th>
						<th><?php echo __('Action');?></th>
					</tr>
				</thead>
				<tbody>
					<?php					
						foreach($price_variation_info as $variation_row)
						{ ?>
							<tr>
								
								<td><?php echo $this->ERPfunction->get_projectname($variation_row['project_id']);?></td>
								<td><?php echo $variation_row['bill_no'];?></td>
								<td><?php echo date('d-m-Y',strtotime($variation_row['upto_date'])) ;?></td>
								<td><?php echo $variation_row['paid_amt'];?></td>
								<td><?php echo $variation_row['payment_date']->format("d-m-Y");?></td>										
								<td>
									<?php
									$attached_files = json_decode($variation_row["attach_file"]);	
									$attached_label = json_decode(stripcslashes($variation_row['attach_label']));	
									
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
								echo $this->Html->link("<i class='icon-pencil'></i> Edit",array('action' => 'addpricevariation', $variation_row['price_variation_id']),
								array('class'=>'btn btn-primary btn-clean','escape'=>false));
								echo ' ';
								/* echo $this->Html->link("<i class='icon-trash'></i> Delete",array('action' => 'deletepricevariation', $variation_row['price_variation_id']),
								array('class'=>'btn  btn-danger btn-clean','escape'=>false, 
								'confirm' => 'Are you sure you wish to delete this Record?')) */
								?>
								</td>
							</tr>
						<?php
					
						}
					?>
				</tbody>
			</table>
		</div>
		</div>
	</div>
</div>
<?php } ?>
</div>