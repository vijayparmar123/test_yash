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
				<h2>Purchase Rate Alert</h2>
				<div class="pull-right">
					<a href="<?php echo $this->ERPfunction->action_link('Purchase','index');?>" class="btn btn-success"><span class="icon-arrow-left"></span> Back</a>
				</div>
			</div>
			
		<div class="content list custom-btn-clean">
		<script>
		jQuery(document).ready(function() {
		jQuery('#rate_list').DataTable({responsive: true});
		} );
</script>
			<table id="rate_list"  class="dataTables_wrapper table table-striped table-hover">
				<thead>
					<tr>
						<th>Date From</th>
						<th>Date To</th>
						<th>Vendor Name</th>
						<th>Material Name</th>						
						<th>Final Rate</th>						
						<th>Unit</th>						
						<th>All Taxes & <br> Duties</th>						
						<th>Loading & <br> Transportation <br> (F.O.R)</th>						
						<th>Unloading</th>						
						<th>Action</th>						
						<th>Approve</th>
					</tr>
				</thead>
				<tbody>
					<?php	
						foreach($rate_data as $retrive_data)
						{
							if($role == 'deputymanagerelectric')
							{
								$retrive_data = array_merge($retrive_data,$retrive_data['erp_finalized_rate_detail']);
							}
						?>
							<tr>								
								<td><?php echo date("d-m-Y",strtotime($retrive_data['rate_from_date']));?></td>
								<td><?php echo date("d-m-Y",strtotime($retrive_data['rate_to_date']));?></td>	
								<td><?php echo $this->ERPfunction->get_vendor_name($this->ERPfunction->get_vendor_by_rate($retrive_data['rate_id']));?></td>								
								<td><?php echo $this->ERPfunction->get_material_title($retrive_data['material_id']);?></td>
								<td><?php echo $retrive_data['final_rate'];?></td>							
								<td><?php echo $this->ERPfunction->get_items_units($retrive_data['material_id']);?></td>
								<td><?php echo ucfirst($retrive_data['text_duties']);?></td>
								<td><?php echo ucfirst($retrive_data['loading_trans']);?></td>
								<td><?php echo ucfirst($retrive_data['unloading']);?></td>
								<td>
								<?php 
								if($this->ERPfunction->retrive_accessrights($role,'ratealert')==1)
								{
									echo $this->Html->link("<i class='icon-eye-open'></i> View",array('action' => 'viewaddrate', $retrive_data['rate_id']),
									array('escape'=>false,'class'=>'btn btn-info btn-clean'));
									echo ' ';
								}
								if($this->ERPfunction->retrive_accessrights($role,'editrate')==1)
								{
									echo $this->Html->link("<i class='icon-pencil'></i> Edit",array('action' => 'editrate', $retrive_data['rate_id']),
									array('escape'=>false,'class'=>'btn btn-primary btn-clean'));
								}
								?>
								</td>
								<td>
								<?php
								if($this->ERPfunction->retrive_accessrights($role,'approverate')==1)
								{
								?>
								<div class='checkbox'>
									<label><input type='checkbox' value='<?php echo $retrive_data['rate_detail_id']?>' name='approved_list[]' class="approve_rate" data-id="<?php echo $retrive_data['rate_detail_id']?>"/></label>
								</div>
								<?php }
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