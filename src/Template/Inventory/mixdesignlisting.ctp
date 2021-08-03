<?php
use Cake\Routing\Router;
?>
<div class="col-md-10" >
<?php 
if(!$is_capable)
	{
		$this->ERPfunction->access_deniedmsg();
	}
else
{
?>
<div class="row">
	<div class="col-md-12">
		<div class="block" style="width:auto;">			
			<div class="head bg-default bg-light-rtl">
				<h2>Mix Design </h2>
				<div class="pull-right">
				<a href="<?php echo $this->ERPfunction->action_link('Inventory','index');?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
				</div>
			</div>
			<div class="content">
			<?php
			 if($this->ERPfunction->retrive_accessrights($role,'mixdesign')==1)
			{  ?>
			<a href="<?php echo $this->ERPfunction->action_link('Inventory','mixdesign');?>" class="btn btn-primary"><span class="icon-plus-sign"></span> Add Mix Design</a>
			<?php } ?>
			</div>
		<div class="content list custom-btn-clean">
		<script>
		jQuery(document).ready(function() {
			jQuery('#mix_list').DataTable({responsive: true});						
		} );
</script>
			<table id="mix_list" class="dataTables_wrapper table table-striped table-hover">
				<thead>
					<tr>
						<th>Project</th>						
						<th>Asset</th>					
						<th>Concrete Grade</th>
						<th>View</th>												
					</tr>
				</thead>
				<tbody>
					<?php
					if(isset($mix_records))
					{
						$i = 1;
						foreach($mix_records as $retrive_data)
						{
						?>
							<tr>
								<td><?php echo $this->ERPfunction->get_projectname($retrive_data['project_id']);?></td>								
								<td><?php echo $this->ERPfunction->get_asset_name($retrive_data['asset_id']);?></td>									
								<td><?php echo $retrive_data['concrete_grade'];?></td>
								<td>
								<?php 
								echo $this->Html->link("<i class='icon-eye-open'></i> View",array('action' => 'previewmixdesign', $retrive_data['id']),
								array('class'=>'btn btn-primary btn-clean','target'=>'_blank','escape'=>false));
								?>
								</td>
								</tr>
						<?php
						$i++;
						}
					}
					?>
				</tbody>
			</table>
		</div>
		</div>
	</div>
</div>
<?php }?>
</div>