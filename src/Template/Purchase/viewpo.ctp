<?php
//$this->extend('/Common/menu')
?>
<div class="col-md-10" >
<?php /**/?>
<?php echo $this->element('breadcrumbs'); ?>
<div class="row">
	<div class="col-md-12">
		<div class="block">
			<div class="header">
				<h2>View P.O </h2>
			</div>
		
		<div class="content">
		<script>
		jQuery(document).ready(function() {
		jQuery('#po_list').DataTable({responsive: true});
		} );
</script>
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
								echo $this->Html->link(__('View'),array('action' => 'previewpo', $retrive_data['po_id']),
								array('class'=>'btn btn-primary','target'=>'_blank'));
								
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