<?php
//$this->extend('/Common/menu')
?>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('.datep').datepicker({
		dateFormat: "dd-mm-yy",
		  changeMonth: true,
	        changeYear: true,
	        yearRange:'-65:+0',
	        onChangeMonthYear: function(year, month, inst) {
	            jQuery(this).val(month + "-" + year);
	        }                    
    });
});
</script>
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
						<h2>View Price Variation</h2>
						<div class="pull-right">
						<a href="<?php echo $this->ERPfunction->action_link($back_url,$back_page);?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a></div>
			</div>
			<?php echo $this->Form->Create('form1',['id'=>'eq_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
						<?php 
				$project_id = array();
				$material_id_a = array();
				$vendor_userid_a = array();
				 $project_id = isset($_POST['project_id'])?$_POST['project_id']:'';
				 $as_from_date = isset($_POST['as_from_date'])?$_POST['as_from_date']:'';
				 $as_to_date = isset($_POST['as_to_date'])?$_POST['as_to_date']:'';
				 $pay_from_date = isset($_POST['pay_from_date'])?$_POST['pay_from_date']:'';
				 $pay_to_date = isset($_POST['pay_to_date'])?$_POST['pay_to_date']:'';
				 $material_id_a = isset($_POST['material_id'])?$_POST['material_id']:'';
				 $vendor_userid_a = isset($_POST['vendor_userid'])?$_POST['vendor_userid']:'';
			?>

                    <div class="content controls">
						<div class="form-row">
						<div class="col-md-2">As on Date From -</div>
                        <div class="col-md-4"><input type="text" name="as_from_date" id="as_from_date" value="<?php echo $as_from_date;?>" class="datep form-control"/></div>
						<div class="col-md-2">As on Date To -</div>
                        <div class="col-md-4"><input type="text" name="as_to_date" id="as_to_date" value="<?php echo $as_to_date;?>" class="datep form-control"/></div>
						</div>
						
						<div class="form-row">
						<div class="col-md-2">Date of Payment From -</div>
                        <div class="col-md-4"><input type="text" name="pay_from_date" id="pay_from_date" value="<?php echo $pay_from_date;?>" class="datep form-control"/></div>
						<div class="col-md-2">Date of Payment To -</div>
                        <div class="col-md-4"><input type="text" name="pay_to_date" id="pay_to_date" value="<?php echo $pay_to_date;?>" class="datep form-control"/></div>
						</div>
						
						<div class="form-row">
							<div class="col-md-2">Project Code</div>
							<div class="col-md-4"><input name="project_code" class="form-control"></div>
							<div class="col-md-2">Project Name</div>
							<div class="col-md-4">
							<select class="select2"   style="width: 100%;" name="project_id[]" id="project_id" multiple="multiple">
								<option value="All">All</Option>
								<?php 
									foreach($projects as $retrive_data)
									{
										$selected = (in_array($retrive_data['project_id'],$project_id)) ? "selected" : "";
										echo '<option value="'.$retrive_data['project_id'].'" '. $selected .'>'.$retrive_data['project_name'].'</option>';										
									}
								?>
								</select>
							</div>
						</div>
						<div class="form-row">
							<div class="col-md-2">Bill No</div>
							<div class="col-md-4"><input name="bill_no" class="form-control"></div>				
						</div>
						
						
						<div class="form-row">
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
						$rows = array();
						$rows[] = array("Project Name","Price Variation No.","As On Date","Net Amount.","Date Of Payment");
					
						foreach($price_variation_info as $variation_row)
						{ 
							$export = array();
						?>
							<tr>
								
								<td><?php echo ($export[] = $this->ERPfunction->get_projectname($variation_row['project_id']));?></td>
								<td><?php echo ($export[] = $variation_row['bill_no']);?></td>
								<td><?php echo ($export[] = date('d-m-Y',strtotime($variation_row['upto_date']))) ;?></td>
								<td><?php echo ($export[] = $variation_row['paid_amt']);?></td>
								<td><?php echo ($export[] = $variation_row['payment_date']->format("d-m-Y"));?></td>										
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
												<a href="<?php echo $this->ERPfunction->get_signed_url($file);?>" download="<?php echo $attached_label[$i];?>" class="btn btn-info btn-clean"><i class="icon-download-alt"></i><?php echo $attached_label[$i];?></a>
											<?php $i++;
											}
										}
									} ?>								
								</td>
								<td>
								<?php 
								if($this->ERPfunction->retrive_accessrights($role,'viewpricevariation')==1)
								{
								echo $this->Html->link("<i class='icon-eye-open'></i> View",array('action' => 'viewaddpricevariation', $variation_row['price_variation_id']),
								array('class'=>'btn btn-primary btn-clean','target'=>'_blank','escape'=>false));
								}
								if($this->ERPfunction->retrive_accessrights($role,'addpricevariation')==1)
								{
								echo $this->Html->link("<i class='icon-pencil'></i> Edit",array('action' => 'addpricevariation', $variation_row['price_variation_id']),
								array('class'=>'btn btn-primary btn-clean','target'=>'_blank','escape'=>false));
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
				<input type="hidden" name="rows" value='<?php echo serialize($rows);?>'>
				<input type="submit" class="btn btn-success" value="Export To Excel" name="export_csv">
			<?php echo $this->Form->end(); ?>
			</div>
			<div class="col-md-2">
			<?php echo $this->Form->create('export_pdf',['method'=>'post']); ?>
				<input type="hidden" name="rows" value='<?php echo serialize($rows);?>'>
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