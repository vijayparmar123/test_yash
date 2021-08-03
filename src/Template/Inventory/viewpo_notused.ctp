<?php
//$this->extend('/Common/menu')
?>
<div class="col-md-10" >
<div class="row">
	<div class="col-md-12">
		<div class="block">
			<div class="head bg-default bg-light-rtl">
				<h2>P.O. RECORDS</h2>
				<div class="pull-right">
					<a href="<?php echo $this->ERPfunction->action_link('Inventory','preparepo');?>" class="btn btn-success"><span class="icon-plus"></span> Prepare P.O.</a>
				</div>
			</div>
		<div class="content ">
		<script>
		
		jQuery(document).ready(function() {
			jQuery('#from_date,#to_date').datepicker({
		dateFormat: "dd-mm-yy",
		  changeMonth: true,
	        changeYear: true,
	        yearRange:'-65:+0',
	        onChangeMonthYear: function(year, month, inst) {
	            jQuery(this).val(month + "-" + year);
	        }                    
    });
		jQuery('#po_list').DataTable({responsive: true});
		} );
</script>
			<div class="col-md-12 filter-form">
			<?php 
$project_id = isset($request_data['project_id'])?$request_data['project_id']:'';
$from_date = isset($request_data['from_date'])?$request_data['from_date']:'';
$to_date = isset($request_data['to_date'])?$request_data['to_date']:'';
?>
				<?php echo $this->Form->Create('form1',['id'=>'user_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
				
					<div class="form-row">                          
						<div class="col-md-10 text-right">
							<div class="col-md-2">Select Project</div>
						<div class="col-md-2">
							<select class="select2"  required="true"   style="width: 100%;" name="project_id" id="project_id">
								<option value="All">All</Option>
								<?php 
									foreach($projects as $retrive_data)
									{
										echo '<option value="'.$retrive_data['project_id'].'">'.
										$retrive_data['project_name'].'</option>';
									}
								?>
								</select>
						</div>
						<div class="col-md-2">Date From :</div>
                        <div class="col-md-2"><input type="text" name="from_date" id="from_date" value="" class="form-control"/></div>
						<div class="col-md-2">Date To :</div>
                        <div class="col-md-2"><input type="text" name="to_date" id="to_date" value="" class="form-control"/></div>
						</div>
					<div class="col-md-2"> <div class="col-md-12"><input type="submit" name="go" id="go" class="btn btn-primary" value="Go"/></div></div>
						
					</div>
				<?php $this->Form->end(); ?>
			</div>
			</div>
<div class="content list custom-btn-clean">
			<table id="po_list"  class="dataTables_wrapper table table-striped table-hover">
				<thead>
					<tr>
						<th>Project Code</th>
						<th>P.o. No</th>						
						<th>Date</th>						
						<th>Vendor</th>									
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$i = 1;
						foreach($po_list as $retrive_data)
						{
						?>
							<tr>								
								<td><?php echo $this->ERPfunction->get_projectcode($retrive_data['project_id']);?></td>
								<td><?php echo $retrive_data['po_no'];?></td>								
								<td><?php echo $this->ERPfunction->get_date($retrive_data['po_date']);?></td>								
								<td><?php echo $this->ERPfunction->get_user_name($retrive_data['vendor_userid']);?></td>								
																
								<td>
								<?php 
								echo $this->Html->link("<i class='icon-eye-open'></i> View",array('action' => 'previewpo', $retrive_data['po_id']),
								array('escape'=>false,'class'=>'btn btn-primary btn-clean','target'=>'_blank'));
								
								?>
								</td>
							</tr>
						<?php
						$i++;
						}
					?>
				</tbody>
			</table>
		</div>
		</div>
	</div>
</div>
</div>