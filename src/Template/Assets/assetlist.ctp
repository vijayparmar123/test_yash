<?php
//$this->extend('/Common/menu')
use Cake\Routing\Router;
?>
<script type="text/javascript" >
var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;

		jQuery(document).ready(function() {
			jQuery('#asset_list').DataTable({responsive: true});
			
			jQuery('.viewmodal').click(function(){			
				payid=jQuery(this).attr('id');
				jQuery('#modal-view').html('hello');
				var model  = jQuery(this).attr('data-type') ;
				var asset_id  = jQuery(this).attr('asset_id') ;
				var urlstring = '';
				
				if(model == 'transfereasset')
				{
					urlstring ="<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'transfereasset'));?>";
				}
			 
				var curr_data = {type : model,asset_id:asset_id};	 				
					jQuery.ajax({
						headers: {
				'X-CSRF-Token': csrfToken
			},
                type:"POST",
						url:urlstring,
						data:curr_data,
						async:false,
						success: function(response){                    
							jQuery('.modal-content').html(response);					
						},
						beforeSend:function(){
									jQuery('#modal-view').html('<center><img src=../images/4.gif width=120px><div><h3>Loading...</h3></div></center>');
								},
						error: function(e) {
								console.log(e);
								 }
					});			
			});
		} );
</script>
<div class="modal fade " id="load_modal" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content"></div>
    </div>
</div>

<div class="col-md-10" >
<?php
if(!$is_capable)
{
	$this->ERPfunction->access_deniedmsg();
}
else{
?>    
<div class="row">
	<div class="col-md-12">
		<div class="block">		
			<div class="head bg-default bg-light-rtl">
				<h2>Manage Assets</h2>
				<div class="pull-right">
				<a href="<?php echo $this->ERPfunction->action_link('Assets','add');?>" class="btn btn-success"><span class="icon-plus"></span> Add Asset</a>
				</div>
			</div>
		
		<div class="content list custom-btn-clean">
		<table id="asset_list"  class="dataTables_wrapper table table-striped table-hover">
				<thead>
					<tr>
						<th>Asset Group</th>
						<th>Asset Id</th>
						<th>Asset Name</th>
						<th>Make</th>
						<th>Date of Purchase</th>						
						<th>Currently Deployemnt To</th>						
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$i = 1;
						foreach($asset_list as $retrive_data)
						{
						?>
							<tr>
								<td><?php 
									 echo $this->ERPfunction->get_asset_group_name($retrive_data['asset_group']);?>   
								</td>
								<td><?php echo $retrive_data['asset_code']; ?></td>
								<td><?php echo $retrive_data['asset_name'];?></td>
								<td><?php echo $this->ERPfunction->get_category_title($retrive_data['asset_make']);?></td>
								<td><?php echo $this->ERPfunction->get_date($retrive_data['purchase_date']);?></td>
								<td><?php echo  $this->ERPfunction->get_projectname($retrive_data['deployed_to']);?></td>								
								<td>
								<?php 
								echo $this->Html->link("<i class='icon-pencil'></i> Edit",array('action' => 'add',$retrive_data['asset_id']),
								array('class'=>'btn btn-primary btn-clean','escape'=>false));
								echo ' ';
								echo $this->Html->link("<i class='icon-trash'></i> Delete",array('action' => 'delete', $retrive_data['asset_id']),
								array('class'=>'btn  btn-danger btn-clean','escape'=>false,
								'confirm' => 'Are you sure you wish to Delete this Record?'));	
								echo ' ';
								 
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