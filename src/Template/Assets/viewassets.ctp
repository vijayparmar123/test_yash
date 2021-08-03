<?php
//$this->extend('/Common/menu')
use Cake\Routing\Router;
?>
<script type="text/javascript" >
var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;

		jQuery(document).ready(function() {
			jQuery('#maintenace_list').DataTable({
				responsive: true,
				"aoColumns":[
					{"bSortable": true},
					{"bSortable": true},
					{"bSortable": true},
					{"bSortable": true},
					{"bSortable": true},
					{"bSortable": true},
					{"bSortable": false},
					{"bSortable": false},
					{"bSortable": false}]
			});
			
			jQuery('.viewmodal').click(function(){			
				payid=jQuery(this).attr('id');
				jQuery('#modal-view').html('hello');
				var model  = jQuery(this).attr('data-type') ;
				var asset_id  = jQuery(this).attr('asset_id') ;
				var urlstring = '';
				 
				if(model == 'saledetails')
				{
					urlstring ="<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'viewsale'));?>";
				}
				if(model == 'transferedetails')
				{
					urlstring ="<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'viewtransfer'));?>";
				}
				if(model == 'maintenancedetials')
				{
					urlstring ="<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'viewmaintenancedetials'));?>";
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
							//alert(response);
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
<?php echo $this->element('breadcrumbs'); ?>
<div class="row">
	<div class="col-md-12">
		<div class="block">
			<div class="header">
				<h2>Asset Records <a href="<?php echo $this->ERPfunction->action_link('Assets','index');?>" class="btn btn-default">Add New</a></h2>
			</div>
		
		<div class="content">


			<table id="maintenace_list"  class="dataTables_wrapper table table-striped table-hover">
				<thead>
					<tr>
						<th>Asset Group</th>
						<th>Asset Id</th>
						<th>Asset Name</th>
						<th>Make</th>
						<th>Date of Purchase</th>						
						<th>Currently Deployment To</th>						
						<th> View Sale Details </th>						
						<th> View Transfer History </th>						
						<th> View maintenance History </th>						
						 
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
								<td><?php echo '<button type="button"    data-type="saledetails" data-toggle="modal" 
								data-target="#load_modal" class="btn btn-default viewmodal" asset_id="'.$retrive_data['asset_id'].'"> View </button>';
								echo ' ';
								?></td>	
								<td><?php echo '<button type="button"    data-type="transferedetails" data-toggle="modal" 
								data-target="#load_modal" class="btn btn-default viewmodal" asset_id="'.$retrive_data['asset_id'].'"> View </button>';
								echo ' ';
								?></td>	
								<td><?php echo '<button type="button"    data-type="maintenancedetials" data-toggle="modal" 
								data-target="#load_modal" class="btn btn-default viewmodal" asset_id="'.$retrive_data['asset_id'].'"> View </button>';
								echo ' ';
								?></td>
								 
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