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
				<h2>CEO List <a href="<?php echo $this->ERPfunction->action_link('Ceo','add');?>" class="btn btn-default">Add New</a></h2>
			</div>
		
		<div class="content">
		<script>
		jQuery(document).ready(function() {
		jQuery('#category_list').DataTable({responsive: true});
		} );
</script>
			<table id="category_list"  class="dataTables_wrapper table table-striped table-hover">
				<thead>
					<tr>
						<th>Material Code</th>
						<th>Category Name</th>						
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$i = 1;
						foreach($category as $key => $retrive_data)
						{
						?>
							<tr>								
								<td><?php echo $retrive_data['material_code'];?></td>
								<td><?php echo $retrive_data['category_name'];?></td>								
								<td>
								<?php 
								echo $this->Html->link(__('View Brand'),array('action' => 'viewbrand', $key),
								array('class'=>'btn btn-primary'));
								echo ' ';
								echo $this->Html->link(__('View Material'),array('action' => 'viewmaterial',$key),
								array('class'=>'btn btn-primary'));
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