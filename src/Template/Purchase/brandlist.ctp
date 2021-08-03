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
<div class="col-md-12">
<div class="row">
	
		<div class="block">			
		<div class="head bg-default bg-light-rtl">
			<h2>Brand List</h2>
			<div class="pull-right">	
			<a href="<?php echo $this->request->base;?>/Purchase/addbrand" class="btn btn-primary">Add Brand</a>			
			<a href="<?php echo $this->ERPfunction->action_link($back_url,$back_page);?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a></div>
		</div>
		<?php echo $this->Form->Create('form1',['id'=>'eq_form','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
			 <div class="content controls">											
						<div class="form-row">
							<div class="col-md-2 text-right">Brand Name</div>
							<div class="col-md-4">
								<input name="brand_name" class="form-control">
							</div>
							<div class="col-md-2 text-right">Material Group</div>
							<div class="col-md-4">
								<?php
									$groups = $this->ERPfunction->vendor_group();									
									echo "<select class='select2' name='material_group[]' style='width:100%' multiple='multiple'>";
									echo "<option value='All'>All</option>";
									foreach($groups as $group)
									{
										echo "<option value='{$group['id']}'>{$group['title']}</option>";									
									}
									echo "</select>";
								?>
							</div>
						</div>
						<div class="form-row">							
							<div class="col-md-2 text-right">
								<button type="submit" name="search" value="Search" class="btn btn-primary">Search</button>
							</div>							
						</div>		
			</div>		
		<?php echo $this->Form->end();?>
		
		<div class="content list custom-btn-clean">
		<script>
		jQuery(document).ready(function() {
		jQuery('#brand_list').DataTable({responsive: true});
		} );
</script>
			<table id="brand_list"  class="dataTables_wrapper table table-striped table-hover">
				<thead>
					<tr>
						<th class='text-center'>#ID</th>
						<th class='text-center'>Brand Name</th>						
						<th class='text-center'>Material/Item Group</th>						
						<th class='text-center'>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$i = 1;
						foreach($brand_list as $retrive_data)
						{
						?>
							<tr>								
								<td class='text-center'><?php echo $i;?></td>
								<td class='text-center'><?php echo $retrive_data['brand_name'];?></td>
								<td class='text-center'><?php echo $this->ERPfunction->get_vendor_group_name($retrive_data['material_type']);?></td>	
								<td class='text-center'>
								<?php 
								if($this->ERPfunction->retrive_accessrights($role,'addbrand')==1)
								{
									echo $this->Html->link("<i class='icon-pencil'></i> Edit",array('action' => 'addbrand', $retrive_data['brand_id']),array('class'=>'btn btn-primary btn-clean',"escape"=>false));
									echo ' ';
								}
								/* echo $this->Html->link("<i class='icon-pencil'></i> Delete",array('action' => 'deletebrand',$retrive_data['brand_id']),
								array("escape"=>false,'class'=>'btn btnview btn-clean btn-danger','confirm' => 'Are you sure you wish to delete this Record?')); */
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
<?php } ?>
</div>